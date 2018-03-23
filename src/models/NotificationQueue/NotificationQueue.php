<?php
namespace dimichspb\yii\notificator\models\NotificationQueue;

use dimichspb\yii\notificator\interfaces\NotificationInterface;
use dimichspb\yii\notificator\interfaces\NotificationQueueInterface;
use dimichspb\yii\notificator\models\BaseEntity;
use dimichspb\yii\notificator\models\EventTrait;
use dimichspb\yii\notificator\models\InstantiateTrait;
use dimichspb\yii\notificator\models\NotificationQueue\events\AttemptAddedEvent;
use dimichspb\yii\notificator\models\NotificationQueue\events\ChannelClassUpdatedEvent;
use dimichspb\yii\notificator\models\NotificationQueue\events\MessageUpdatedEvent;
use dimichspb\yii\notificator\models\NotificationQueue\events\CreatedAtUpdatedEvent;
use dimichspb\yii\notificator\models\NotificationQueue\events\SavedEvent;
use dimichspb\yii\notificator\models\NotificationQueue\events\SentAtUpdatedEvent;
use dimichspb\yii\notificator\models\NotificationQueue\events\StatusAddedEvent;
use dimichspb\yii\notificator\models\UserId;
use dimichspb\yii\notificator\models\Message;
use yii\helpers\Json;

/**
 * Class NotificationQueue
 * @package dimichspb\yii\notificator\models\NotificationQueue
 *
 */
class NotificationQueue extends BaseEntity implements NotificationQueueInterface
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
     * @var Message
     */
    protected $message;

    /**
     * @var Status[]
     */
    protected $statuses;

    public function __construct(NotificationInterface $notification, Message $message, UserId $userId)
    {
        $this->id = new Id();
        $this->created_at = new CreatedAt();
        $this->sent_at = new SentAt();
        $this->read_at = new ReadAt();
        $this->user_id = $userId;
        $this->notification_id = $notification->getId();
        $this->channel_class = $notification->getChannelClass();
        $this->message = $message;
        $this->attempts = [];
        $this->statuses[] = new Status(Status::STATUS_NEW);

        parent::__construct();
    }

    protected function setMessage(Message $message)
    {
        $this->message = $message;

        $this->recordEvent(new MessageUpdatedEvent($this));
        return $this;
    }

    public function getMessage()
    {
        return $this->message;
    }

    protected function setChannelClass($channelClass)
    {
        if (!is_string($channelClass)) {
            throw new \InvalidArgumentException();
        }

        $this->channel_class = $channelClass;

        $this->recordEvent(new ChannelClassUpdatedEvent($this));
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
        $this->recordEvent(new SentAtUpdatedEvent($this));
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
        $this->recordEvent(new CreatedAtUpdatedEvent($this));
    }

    public function getReadAt()
    {
        return $this->read_at;
    }

    public function addStatus(Status $status)
    {
        $this->statuses[] = $status;
        $this->save();
        $this->recordEvent(new StatusAddedEvent($this));
    }

    public function getLastStatus()
    {
        return end($this->statuses);
    }

    public function addAttempt(Attempt $attempt)
    {
        $this->attempts[] = $attempt;
        $this->save();
        $this->recordEvent(new AttemptAddedEvent($this));
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
            'user_id' => $this->user_id->getValue(),
            'notification_id' => $this->notification_id->getValue(),
            'message' => $this->message->getValue(),
            'channel_class' => $this->channel_class->getValue(),
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

    public function attempt()
    {
        $this->addAttempt(new Attempt(Attempt::ATTEMPT_NEW));
    }

    public function process()
    {
        $this->updateLastAttempt(Attempt::ATTEMPT_PROCESS);
    }

    public function success($result)
    {
        $this->setSentAt(new SentAt());
        $this->updateLastAttempt(Attempt::ATTEMPT_DONE, $result);
    }

    public function error($message)
    {
        $this->updateLastAttempt(Attempt::ATTEMPT_ERROR, $message);
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
        $this->message = new Message(
            $this->getAttribute('message')
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
        $this->setAttribute('message', $this->message->getValue());

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

    public function afterSave($insert, $changedAttributes)
    {
        $this->recordEvent(new SavedEvent($this));

        parent::afterSave($insert, $changedAttributes);
    }

    public static function find()
    {
        return parent::find()->orderBy(['created_at' => SORT_DESC]);
    }
}