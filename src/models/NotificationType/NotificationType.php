<?php
namespace dimichspb\yii\notificator\models\NotificationType;

use dimichspb\yii\notificator\interfaces\NotificationTypeClassInterface;
use dimichspb\yii\notificator\interfaces\NotificationTypeInterface;
use dimichspb\yii\notificator\models\BaseEntity;
use dimichspb\yii\notificator\models\EventTrait;
use dimichspb\yii\notificator\models\InstantiateTrait;
use dimichspb\yii\notificator\models\Message;
use dimichspb\yii\notificator\models\NotificationType\events\CreatedAtUpdatedEvent;
use dimichspb\yii\notificator\models\NotificationType\events\CreatedByUpdatedEvent;
use dimichspb\yii\notificator\models\NotificationType\events\EventUpdatedEvent;
use dimichspb\yii\notificator\models\NotificationType\events\NotificationTypeClassUpdatedEvent;
use dimichspb\yii\notificator\models\NotificationType\events\StatusAddedEvent;
use yii\helpers\Json;

class NotificationType extends BaseEntity implements NotificationTypeInterface
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
     * @var CreatedBy
     */
    protected $created_by;

    /**
     * @var NotificationTypeClass
     */
    protected $notification_type_class;

    /**
     * @var Event
     */
    protected $event;

    /**
     * @var Status[]
     */
    protected $statuses = [];

    public function __construct(NotificationTypeClassInterface $notificationTypeClass, $createdBy = null, array $config = [])
    {
        $this->id = new Id();
        $this->created_at = new CreatedAt();
        $this->created_by = new CreatedBy($createdBy);
        $this->notification_type_class = new NotificationTypeClass($notificationTypeClass->getClass());
        $this->event = new Event($notificationTypeClass->getEventName());

        $this->statuses[] = new Status(Status::STATUS_ACTIVE);

        parent::__construct($config);
    }

    public function getId()
    {
        return $this->id;
    }

    protected function setCreatedAt(CreatedAt $createdAt)
    {
        $this->created_at = $createdAt;
        $this->recordEvent(new CreatedAtUpdatedEvent($this));

        return $this;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    protected function setCreatedBy(CreatedBy $createdBy)
    {
        $this->created_by = $createdBy;
        $this->recordEvent(new CreatedByUpdatedEvent($this));

        return $this;
    }

    public function getCreatedBy()
    {
        return $this->created_by;
    }

    protected function setNotificationTypeClass(NotificationTypeClass $notificationTypeClass)
    {
        $this->notification_type_class = $notificationTypeClass;
        $this->recordEvent(new NotificationTypeClassUpdatedEvent($this));

        return $this;
    }

    public function getNotificationTypeClass()
    {
        return $this->notification_type_class;
    }

    public function getName()
    {
        $notificationTypeClassName = $this->notification_type_class->getValue();
        /** @var NotificationTypeClassInterface $notificationTypeClass */
        $notificationTypeClass = new $notificationTypeClassName;

        return $notificationTypeClass->getName();
    }

    public function getDescription()
    {
        $notificationTypeClassName = $this->notification_type_class->getValue();
        /** @var NotificationTypeClassInterface $notificationTypeClass */
        $notificationTypeClass = new $notificationTypeClassName;

        return $notificationTypeClass->getDescription();
    }

    public function getMessage(\yii\base\Event $event)
    {
        $notificationTypeClassName = $this->notification_type_class->getValue();
        /** @var NotificationTypeClassInterface $notificationTypeClass */
        $notificationTypeClass = new $notificationTypeClassName;

        return new Message($notificationTypeClass->getMessage($event));
    }

    public function getEvent()
    {
        return $this->event;
    }

    public function setEvent(Event $event)
    {
        $this->event = $event;
        $this->recordEvent(new EventUpdatedEvent($this));

        return $this;
    }

    public function addStatus(Status $status)
    {
        $this->statuses[] = $status;
        $this->save();
        $this->recordEvent(new StatusAddedEvent($this));

        return $this;
    }

    public function getStatus()
    {
        return $this->getLastStatus();
    }

    public function getLastStatus()
    {
        return end($this->statuses);
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%notification_type%}}';
    }

    /**
     * @throws \Assert\AssertionFailedException
     */
    public function afterFind()
    {
        $this->id = new Id(
            $this->getAttribute('id')
        );
        $this->created_at = new CreatedAt(
            $this->getAttribute('created_at')
        );
        $this->created_by = new CreatedBy(
            $this->getAttribute('created_by')
        );
        $this->notification_type_class = new NotificationTypeClass(
            $this->getAttribute('notification_type_class')
        );
        $this->event = new Event(
            $this->getAttribute('event')
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
        $this->setAttribute('notification_type_class', $this->notification_type_class->getValue());
        $this->setAttribute('event', $this->event->getValue());

        $this->setAttribute('statuses', Json::encode(array_map(function (Status $status) {
            return [
                'value' => $status->getValue(),
                'created_at' => $status->getCreatedAt()->getValue(),
            ];
        }, $this->statuses)));

        return parent::beforeSave($insert);
    }
}