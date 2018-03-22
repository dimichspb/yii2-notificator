<?php
namespace dimichspb\yii\notificator\models\NotificationQueue;

use dimichspb\yii\notificator\interfaces\ChannelInterface;
use dimichspb\yii\notificator\EventTrait;
use dimichspb\yii\notificator\interfaces\MessageInterface;
use dimichspb\yii\notificator\interfaces\NotificationInterface;
use dimichspb\yii\notificator\interfaces\NotificationQueueInterface;
use dimichspb\yii\notificator\interfaces\NotificatorInterface;
use dimichspb\yii\notificator\models\InstantiateTrait;
use dimichspb\yii\notificator\models\NotificationQueue\events\AttemptAddedEvent;
use dimichspb\yii\notificator\models\NotificationQueue\events\ChannelClassUpdatedEvent;
use dimichspb\yii\notificator\models\NotificationQueue\events\MessageUpdatedEvent;
use dimichspb\yii\notificator\models\NotificationQueue\events\CreatedAtUpdatedEvent;
use dimichspb\yii\notificator\models\NotificationQueue\events\SentAtUpdatedEvent;
use dimichspb\yii\notificator\models\NotificationQueue\events\StatusAddedEvent;
use dimichspb\yii\notificator\models\UserId;
use yii\db\ActiveRecord;
use yii\helpers\Json;

/**
 * Class NotificationQueue
 * @package dimichspb\yii\notificator\models\NotificationQueue
 *
 */
class NotificationQueue extends ActiveRecord implements NotificationQueueInterface
{
    use EventTrait, InstantiateTrait;

    /**
     * @var Id
     */
    protected $id;

    /**
     * @var CreatedAt
     */
    protected $created_at;

    /**
     * @var UserId
     */
    protected $user_id;

    /**
     * @var \dimichspb\yii\notificator\models\Notification\Id
     */
    protected $notification_id;

    /**
     * @var ChannelClass
     */
    protected $channel_class;

    /**
     * @var SentAt
     */
    protected $sent_at;

    /**
     * @var ReadAt
     */
    protected $read_at;

    /**
     * @var Attempt[]
     */
    protected $attempts;

    /**
     * @var MessageClass
     */
    protected $message_class;

    /**
     * @var MessageData
     */
    protected $message_data;

    /**
     * @var Status[]
     */
    protected $statuses;

    public function __construct(UserId $userId, NotificationInterface $notification)
    {
        $this->id = new Id();
        $this->created_at = new CreatedAt();
        $this->sent_at = new SentAt();
        $this->user_id = $userId;
        $this->notification_id = $notification->getId();
        $this->channel_class = $notification->getChannelClass();
        //$this->message_class = $notification->getMessage()->className();
        //$this->message_data = $notification->getMessage()->serialize();
        $this->attempts = [];
        $this->statuses[] = new Status(Status::STATUS_NEW);

        parent::__construct();
    }

    protected function setMessage(MessageInterface $message)
    {
        $this->message_class = $message->className();
        $this->message_data = $message->serialize();

        $this->recordEvent(new MessageUpdatedEvent());
        return $this;
    }

    public function getMessage()
    {
        /** @var MessageInterface $message */
        $message = new $this->message_class;
        $message->unserialize($this->message_data);

        return $message;
    }

    protected function setChannelClass($channelClass)
    {
        if (!is_string($channelClass)) {
            throw new \InvalidArgumentException();
        }

        $this->channel_class = $channelClass;

        $this->recordEvent(new ChannelClassUpdatedEvent());
        return $this;
    }

    public function getChannelClass()
    {
        return $this->channel_class;
    }

    /**
     * @param SentAt $sentAt
     */
    protected function setSentAt(SentAt $sentAt)
    {
        $this->sent_at = $sentAt;
        $this->recordEvent(new SentAtUpdatedEvent());
    }

    public function getSentAt()
    {
        return $this->sent_at;
    }

    /**
     * @param ReadAt $readAt
     */
    protected function setReadAt(ReadAt $readAt)
    {
        $this->read_at = $readAt;
        $this->recordEvent(new CreatedAtUpdatedEvent());
    }

    public function getReadAt()
    {
        return $this->read_at;
    }

    public function addStatus(Status $status)
    {
        $this->statuses[] = $status;
        $this->save();
        $this->recordEvent(new StatusAddedEvent());
    }

    public function getLastStatus()
    {
        return end($this->statuses);
    }

    public function addAttempt(Attempt $attempt)
    {
        $this->attempts[] = $attempt;
        $this->save();
        $this->recordEvent(new AttemptAddedEvent());
    }

    public function getLastAttempt()
    {
        return end($this->attempts);
    }

    public function updateLastAttempt($value, $result = null)
    {
        $attempt = $this->getLastAttempt();
        if (!$attempt) {
            $attempt = new Attempt(Attempt::ATTEMPT_NEW);
            $this->addAttempt($attempt);
        }
        switch ($value) {
            case Attempt::ATTEMPT_PROCESS:
                $attempt->process();
                $this->addStatus(new Status(Status::STATUS_PROCESS));
                break;
            case Attempt::ATTEMPT_ERROR:
                $attempt->error($result);
                $this->addStatus(new Status(Status::STATUS_ERROR));
                break;
            case Attempt::ATTEMPT_DONE:
                $attempt->done($result);
                $this->addStatus(new Status(Status::STATUS_DONE));
                $this->setSentAt(new SentAt());
                break;
            default:
                throw new \InvalidArgumentException();
        }

        $this->save();
    }

    protected function setId(Id $id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * @return NotificationQueue|null
     */
    protected function findSame()
    {
        $found = self::findOne([
            'user_id' => $this->user_id,
            'notification_id' => $this->notification_id,
            'message_class' => $this->message_class,
            'message_data' => $this->message_data,
            'channel_class' => $this->channel_class,
            'sent_at' => null,
            'read_at' => null,
        ]);

        return $found;
    }

    public function isAlreadyInQueue()
    {
        $found = $this->findSame();

        return !is_null($found);
    }

    public function read()
    {
        $found = $this->findSame();
        if (!$found) {
            return null;
        }

        $found->setReadAt(new ReadAt());

        return $found->save();
    }


    /**
     * @throws \Assert\AssertionFailedException
     */
    public function afterFind()
    {
        $this->id = new Id(
            $this->getAttribute('id')
        );
        $this->user_id = new UserId(
            $this->getAttribute('user_id')
        );
        $this->created_at = new CreatedAt(
            $this->getAttribute('created_at')
        );
        $this->channel_class = new ChannelClass(
            $this->getAttribute('channel_class')
        );
        $this->sent_at = new SentAt(
            $this->getAttribute('sent_at')
        );
        $this->read_at = new ReadAt(
            $this->getAttribute('read_at')
        );
        $this->notification_id = new \dimichspb\yii\notificator\models\Notification\Id(
            $this->getAttribute('notification_id')
        );
        $this->message_class = new MessageClass(
            $this->getAttribute('message_class')
        );
        $this->message_data = new MessageData(
            $this->getAttribute('message_data')
        );

        $this->attempts = array_map(function ($row) {
            return new Attempt(
                $row['value'],
                new CreatedAt($row['created_at'])
            );
        }, Json::decode($this->getAttribute('attempts')));

        $this->statuses = array_map(function ($row) {
            return new Status(
                $row['value'],
                new CreatedAt($row['created_at'])
            );
        }, Json::decode($this->getAttribute('statuses')));

        parent::afterFind();
    }

    public function beforeSave($insert)
    {
        $this->setAttribute('id', $this->id->getValue());
        $this->setAttribute('created_at', $this->created_at->getValue());
        $this->setAttribute('user_id', $this->user_id->getValue());
        $this->setAttribute('sent_at', $this->sent_at->getValue());
        $this->setAttribute('read_at', $this->read_at? $this->read_at->getValue(): null);
        $this->setAttribute('channel_class', $this->channel_class->getValue());
        $this->setAttribute('notification_id', $this->notification_id->getValue());
        $this->setAttribute('message_class', $this->message_class? $this->message_class->getValue(): null);
        $this->setAttribute('message_data', $this->message_data? $this->message_data->getValue(): null);


        $this->setAttribute('attempts', Json::encode(array_map(function (Attempt $attempt) {
            return [
                'value' => $attempt->getValue(),
                'created_at' => $attempt->getCreatedAt()->getValue(),
            ];
        }, $this->attempts)));

        $this->setAttribute('statuses', Json::encode(array_map(function (Status $status) {
            return [
                'value' => $status->getValue(),
                'created_at' => $status->getCreatedAt()->getValue(),
            ];
        }, $this->statuses)));

        return parent::beforeSave($insert);
    }
}