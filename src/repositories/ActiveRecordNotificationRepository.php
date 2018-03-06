<?php
namespace dimichspb\yii\notificator\repositories;

use dimichspb\yii\mailqueue\models\MailQueue\search\NotificationSearch;
use dimichspb\yii\notificator\models\notification\Notification;

class ActiveRecordNotificationRepository extends BaseNotificationRepository
{
    public $notificationClass = Notification::class;
    public $notificationSearchClass = NotificationSearch::class;

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