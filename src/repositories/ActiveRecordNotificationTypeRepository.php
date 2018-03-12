<?php
namespace dimichspb\yii\notificator\repositories;

use dimichspb\yii\notificator\interfaces\NotificationTypeInterface;
use dimichspb\yii\notificator\models\Notification\search\NotificationTypeSearch;
use dimichspb\yii\notificator\models\NotificationType\Id;
use dimichspb\yii\notificator\models\NotificationType\NotificationType;
use yii\data\DataProviderInterface;

class ActiveRecordNotificationTypeRepository extends BaseNotificationTypeRepository
{
    public $notificationTypeClass = NotificationType::class;
    public $notificationTypeSearchClass = NotificationTypeSearch::class;

    public function get(Id $id)
    {
        // TODO: Implement get() method.
    }

    public function add(NotificationTypeInterface $notificationType)
    {
        // TODO: Implement add() method.
    }

    public function remove(Id $id)
    {
        // TODO: Implement remove() method.
    }

    public function update(NotificationTypeInterface $notificationType)
    {
        // TODO: Implement update() method.
    }

    public function filter(array $params = [])
    {
        /** @var NotificationTypeSearch $searchModel */
        $searchModel = new $this->notificationTypeSearchClass;

        return $searchModel->search($params);
    }

}