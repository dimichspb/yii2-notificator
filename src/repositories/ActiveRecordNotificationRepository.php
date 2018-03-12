<?php
namespace dimichspb\yii\notificator\repositories;

use dimichspb\yii\notificator\interfaces\NotificationInterface;
use dimichspb\yii\notificator\models\Notification\Id;
use dimichspb\yii\notificator\models\Notification\search\NotificationSearch;
use dimichspb\yii\notificator\models\Notification\Notification;

class ActiveRecordNotificationRepository extends BaseNotificationRepository
{
    public $notificationClass = Notification::class;
    public $notificationSearchClass = NotificationSearch::class;

    public function get(Id $id)
    {
        // TODO: Implement get() method.
    }

    public function add(NotificationInterface $notification)
    {
        // TODO: Implement add() method.
    }

    public function remove(Id $id)
    {
        // TODO: Implement remove() method.
    }

    public function update(NotificationInterface $notification)
    {
        // TODO: Implement update() method.
    }

    /**
     * @param array $params
     * @return \yii\data\ActiveDataProvider|\yii\data\DataProviderInterface
     */
    public function filter(array $params = [])
    {
        /** @var NotificationSearch $searchModel */
        $searchModel = new $this->notificationSearchClass;

        return $searchModel->search($params);
    }

}