<?php
namespace dimichspb\yii\notificator\repositories;

use dimichspb\yii\notificator\interfaces\NotificationInterface;
use dimichspb\yii\notificator\models\Notification\Id;
use dimichspb\yii\notificator\models\Notification\search\NotificationSearch;
use dimichspb\yii\notificator\models\Notification\Notification;
use yii\db\ActiveRecord;

class ActiveRecordNotificationRepository extends BaseNotificationRepository
{
    public $notificationClass = Notification::class;
    public $notificationSearchClass = NotificationSearch::class;

    public function get(NotificationInterface $notification)
    {
        /** @var ActiveRecord $notification */
        return $notification::findOne($notification->getId());
    }

    public function add(NotificationInterface $notification)
    {
        /** @var ActiveRecord $notification */
        return $notification->save();
    }

    public function remove(NotificationInterface $notification)
    {
        /** @var ActiveRecord $notification */
        return $notification->delete();
    }

    public function update(NotificationInterface $notification)
    {
        /** @var ActiveRecord $notification */
        return $notification->update();
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