<?php
namespace dimichspb\yii\notificator\models\notification;

use dimichspb\yii\notificator\EventTrait;
use dimichspb\yii\notificator\interfaces\MessageInterface;
use dimichspb\yii\notificator\interfaces\NotificationInterface;
use dimichspb\yii\notificator\models\InstantiateTrait;
use dimichspb\yii\notificator\models\Notification\events\CreatedAtUpdatedEvent;
use dimichspb\yii\notificator\models\Notification\events\CreatedByUpdatedEvent;
use dimichspb\yii\notificator\models\Notification\events\StatusAddedEvent;
use dimichspb\yii\notificator\models\NotificationQueue\events\ChannelClassUpdatedEvent;
use dimichspb\yii\notificator\models\NotificationQueue\events\NotificationTypeClassUpdatedEvent;
use yii\db\ActiveRecord;
use yii\helpers\Json;

class Notification extends ActiveRecord implements NotificationInterface
{
    use EventTrait, InstantiateTrait;

    /**
     * @var Id
     */
    protected $id;

    /**
     * @var UserId
     */
    protected $user_id;

    /**
     * @var CreatedAt
     */
    protected $created_at;

    /**
     * @var CreatedBy
     */
    protected $created_by;

    /**
     * @var ChannelClass
     */
    protected $channel_class;

    /**
     * @var NotificationTypeClass
     */
    protected $notification_type_class;

    /**
     * @var Status[]
     */
    protected $statuses;

    protected function setUserId($userId)
    {
        $this->user_id = $userId;

        return $this;
    }

    public function getUserId()
    {
        return $this->user_id;
    }

    protected function setCreatedAt(CreatedAt $createdAt)
    {
        $this->created_at = $createdAt;
        $this->recordEvent(new CreatedAtUpdatedEvent());

        return $this;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    protected function setCreatedBy(CreatedBy $createdBy)
    {
        $this->created_by = $createdBy;
        $this->recordEvent(new CreatedByUpdatedEvent());

        return $this;
    }

    public function getCreatedBy()
    {
        return $this->created_by;
    }

    protected function setChannelClass(ChannelClass $channelClass)
    {
        $this->channel_class = $channelClass;
        $this->recordEvent(new ChannelClassUpdatedEvent());

        return $this->channel_class;
    }

    public function getChannelClass()
    {
        return $this->channel_class;
    }

    protected function setNotificationTypeClass(NotificationTypeClass $notificationTypeClass)
    {
        $this->notification_type_class = $notificationTypeClass;
        $this->recordEvent(new NotificationTypeClassUpdatedEvent());

        return $this;
    }

    public function getNotificationTypeClass()
    {
        return $this->notification_type_class;
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

    public function getMessage()
    {

    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%notification%}}';
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
        $this->created_by = new CreatedBy(
            $this->getAttribute('created_by')
        );
        $this->channel_class = new ChannelClass(
            $this->getAttribute('channel_class')
        );
        $this->notification_type_class = new NotificationTypeClass(
            $this->getAttribute('notification_type_class')
        );
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
        $this->setAttribute('created_by', $this->created_by->getValue());
        $this->setAttribute('channel_class', $this->channel_class->getValue());
        $this->setAttribute('notification_type_class', $this->notification_type_class->getValue());
        $this->setAttribute('statuses', Json::encode(array_map(function (Status $status) {
            return [
                'value' => $status->getValue(),
                'created_at' => $status->getCreatedAt()->getValue(),
            ];
        }, $this->statuses)));

        return parent::beforeSave($insert);
    }

}