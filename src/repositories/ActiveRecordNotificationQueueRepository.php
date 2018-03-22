<?php
namespace dimichspb\yii\notificator\repositories;

use dimichspb\yii\notificator\interfaces\NotificationQueueInterface;
use dimichspb\yii\notificator\models\NotificationQueue\Id;
use dimichspb\yii\notificator\models\NotificationQueue\search\NotificationQueueSearch;
use dimichspb\yii\notificator\interfaces\NotificationInterface;
use dimichspb\yii\notificator\models\NotificationQueue\NotificationQueue;
use yii\db\ActiveRecord;

class ActiveRecordNotificationQueueRepository extends BaseNotificationQueueRepository
{
    public $notificationQueueClass = NotificationQueue::class;
    public $notificationQueueSearchClass = NotificationQueueSearch::class;

    public function getNotificationQueueClass()
    {
        return $this->notificationQueueClass;
    }

    public function add(NotificationQueueInterface $notificationQueue)
    {
        if (!$notificationQueue instanceof ActiveRecord) {
            throw new \InvalidArgumentException();
        }

        if (!$notificationQueue->isAlreadyInQueue()) {
            return $notificationQueue->save();
        }
        return false;
    }

    public function get(Id $id)
    {
        /** @var ActiveRecord $notificationQueueClass */
        $notificationQueueClass = $this->notificationQueueClass;

        return $notificationQueueClass::findOne($id->getValue());
    }

    public function update(NotificationQueueInterface $notificationQueue)
    {
        if (!$notificationQueue instanceof ActiveRecord) {
            throw new \InvalidArgumentException();
        }

        /** @var ActiveRecord $notificationQueue */
        return $notificationQueue->update();
    }

    public function delete(NotificationQueueInterface $notificationQueue)
    {
        if (!$notificationQueue instanceof ActiveRecord) {
            throw new \InvalidArgumentException();
        }

        /** @var ActiveRecord $notificationQueue */
        return $notificationQueue->delete();
    }

    public function read(NotificationQueueInterface $notificationQueue)
    {
        return $notificationQueue->read();
    }

    /**
     * @param array $params
     * @return \yii\data\ActiveDataProvider
     */
    public function queue(array $params = [])
    {
        /** @var NotificationQueueSearch $searchModel */
        $searchModel = new $this->notificationQueueSearchClass;

        return $searchModel->search($params);
    }
}