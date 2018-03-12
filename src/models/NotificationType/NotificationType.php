<?php
namespace dimichspb\yii\notificator\models\NotificationType;

use Assert\Assertion;
use dimichspb\yii\notificator\EventTrait;
use dimichspb\yii\notificator\interfaces\NotificationTypeClassInterface;
use dimichspb\yii\notificator\interfaces\NotificationTypeInterface;
use dimichspb\yii\notificator\models\InstantiateTrait;
use dimichspb\yii\notificator\models\NotificationType\events\CreatedAtUpdatedEvent;
use dimichspb\yii\notificator\models\NotificationType\events\CreatedByUpdatedEvent;
use dimichspb\yii\notificator\models\NotificationType\events\EventAddedEvent;
use dimichspb\yii\notificator\models\NotificationType\events\EventRemovedEvent;
use dimichspb\yii\notificator\models\NotificationType\events\EventsUpdatedEvent;
use dimichspb\yii\notificator\models\NotificationType\events\NotificationTypeClassUpdatedEvent;
use dimichspb\yii\notificator\models\NotificationType\events\ParamsUpdatedEvent;
use dimichspb\yii\notificator\models\NotificationType\events\StatusAddedEvent;
use yii\db\ActiveRecord;
use yii\helpers\Json;

class NotificationType extends ActiveRecord implements NotificationTypeInterface
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


    protected $events = [];
    protected $params = [];

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
        foreach ($notificationTypeClass->getEvents() as $event) {
            $this->events[] = new Event($event);
        };
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

    public function getEvents()
    {
        return $this->events;
    }

    public function addEvent(Event $event)
    {
        $this->events[] = $event;
        $this->recordEvent(new EventAddedEvent());

        return $this;
    }

    public function removeEvent(Event $event)
    {
        $this->events = array_filter($this->events, function (Event $item) use ($event) {
            return !$item->isEqualTo($event);
        });
        $this->recordEvent(new EventRemovedEvent());

        return $this;
    }

    public function setEvents(array $events)
    {
        $this->events = [];

        foreach ($events as $event) {
            $this->events[] = new Event($events);
        }

        $this->recordEvent(new EventsUpdatedEvent());

        return $this;
    }

    public function getParams()
    {
        return $this->params;
    }

    public function setParams(array $params)
    {
        $this->params = $params;
        $this->recordEvent(new ParamsUpdatedEvent());

        return $this;
    }

    public function addStatus(Status $status)
    {
        $this->statuses[] = $status;
        $this->save();
        $this->recordEvent(new StatusAddedEvent());

        return $this;
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
        $this->events = array_map(function ($row) {
            return new Event(
                $row['value']
            );
        }, Json::decode($this->getAttribute('events')));
        $this->params = array_map(function ($row) {
            return new Param(
                $row['name'],
                $row['value']
            );
        }, Json::decode($this->getAttribute('params')));
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
        $this->setAttribute('events', Json::encode(array_map(function (Event $event) {
            return [
                'value' => $event->getValue(),
            ];
        }, $this->events)));
        $this->setAttribute('params', Json::encode(array_map(function (Param $param) {
            return [
                'name' => $param->getName(),
                'value' => $param->getValue(),
            ];
        }, $this->params)));
        $this->setAttribute('statuses', Json::encode(array_map(function (Status $status) {
            return [
                'value' => $status->getValue(),
                'created_at' => $status->getCreatedAt()->getValue(),
            ];
        }, $this->statuses)));

        return parent::beforeSave($insert);
    }
}