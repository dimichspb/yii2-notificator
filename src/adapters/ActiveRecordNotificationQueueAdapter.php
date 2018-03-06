<?php
namespace dimichspb\yii\notificator\adapters;

use dimichspb\yii\notificator\models\NotificationQueue\search\NotificationQueueSearch;
use dimichspb\yii\notificator\interfaces\NotificationInterface;
use dimichspb\yii\notificator\models\NotificationQueue\NotificationQueue;
use yii\db\ActiveRecord;

class ActiveRecordNotificationQueueAdapter extends BaseNotificationQueueAdapter
{
    public $notificationQueueClass = NotificationQueue::class;
    public $notificationQueueSearchClass = NotificationQueueSearch::class;

    public function add(NotificationInterface $notification)
    {
        if (!$notification instanceof ActiveRecord) {
            throw new \InvalidArgumentException();
        }

        /** @var NotificationQueue $notificationQueue */
        $notificationQueue = new $this->notificationQueueClass(
            $notification->getUserId(),
            $notification->getMessage(),
            $notification->getChannelClass()
        );
        if (!$notificationQueue->isAlreadyInQueue()) {
            return $notificationQueue->save();
        }
        return false;
    }

    /**
     * @param $user_id
     * @param $limit
     * @return NotificationQueue[]
     */
    public function get($user_id, $limit)
    {
        /** @var ActiveRecord $notificationQueueClass */
        $notificationQueueClass = $this->notificationQueueClass;
        return $notificationQueueClass::find()
            ->where([
                'user_id' => $user_id,
            ])
            ->andWhere(
                ['NOT NULL', 'sent_at']
            )
            ->limit($limit)
            ->all();
    }

    public function read(NotificationInterface $notification)
    {
        /** @var NotificationQueue $notificationQueue */
        $notificationQueue = new $this->notificationQueueClass(
            $notification->getUserId(),
            $notification->getMessage(),
            $notification->getChannelClass()
        );

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