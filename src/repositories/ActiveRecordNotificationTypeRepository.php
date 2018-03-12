<?php
namespace dimichspb\yii\notificator\repositories;

use dimichspb\yii\notificator\interfaces\NotificationTypeInterface;
use dimichspb\yii\notificator\models\NotificationType\search\NotificationTypeSearch;
use dimichspb\yii\notificator\models\NotificationType\Id;
use dimichspb\yii\notificator\models\NotificationType\NotificationType;
use yii\data\DataProviderInterface;
use yii\db\ActiveRecord;

class ActiveRecordNotificationTypeRepository extends BaseNotificationTypeRepository
{
    public $notificationTypeClass = NotificationType::class;
    public $notificationTypeSearchClass = NotificationTypeSearch::class;

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

}