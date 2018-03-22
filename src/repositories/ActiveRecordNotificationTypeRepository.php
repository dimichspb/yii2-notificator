<?php
namespace dimichspb\yii\notificator\repositories;

use dimichspb\yii\notificator\interfaces\NotificationTypeInterface;
use dimichspb\yii\notificator\models\NotificationType\search\NotificationTypeSearch;
use dimichspb\yii\notificator\models\NotificationType\Id;
use dimichspb\yii\notificator\models\NotificationType\NotificationType;
use yii\base\Event;
use yii\data\DataProviderInterface;
use yii\db\ActiveRecord;
use yii\db\Expression;

class ActiveRecordNotificationTypeRepository extends BaseNotificationTypeRepository
{
    public $notificationTypeClass = NotificationType::class;
    public $notificationTypeSearchClass = NotificationTypeSearch::class;

    protected static $processedEvents = [];

    public function get(Id $id)
    {
        /** @var ActiveRecord $notificationType */
        $notificationType = $this->notificationTypeClass;
        return $notificationType::findOne($id);
    }

    public function add(NotificationTypeInterface $notificationType)
    {
        /** @var ActiveRecord $notificationType */
        return $notificationType->save();
    }

    public function remove(NotificationTypeInterface $notificationType)
    {
        /** @var ActiveRecord $notificationType*/
        return $notificationType->delete();
    }

    public function update(NotificationTypeInterface $notificationType)
    {
        /** @var ActiveRecord $notificationType */
        return $notificationType->update();
    }

    public function filter(array $params = [])
    {
        /** @var NotificationTypeSearch $searchModel */
        $searchModel = new $this->notificationTypeSearchClass;

        return $searchModel->search($params);
    }

    public function findByEvent(Event $event)
    {
        /** @var ActiveRecord $notificationType */
        $notificationType = $this->notificationTypeClass;
        if ($this->isAlreadyProccessed($event)) {
            return [];
        }

        $notificationTypes = $notificationType::find()->all();

        $notificationTypes = array_filter($notificationTypes, function (NotificationTypeInterface $notificationType) use ($event) {
            return $notificationType->getEvent()->getValue() == $event->name;
        });

        return $notificationTypes;
    }

    protected function isAlreadyProccessed(Event $event)
    {
        foreach (self::$processedEvents as $processedEvent) {
            if ($processedEvent instanceof Event &&
                $processedEvent->name == $event->name &&
                get_class($processedEvent->sender) == get_class($event->sender)
            ) {
                return true;
            }
        }

        self::$processedEvents[] = $event;
        return false;
    }

}