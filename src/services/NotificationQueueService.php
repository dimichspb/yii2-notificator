<?php
namespace dimichspb\yii\notificator\services;

use dimichspb\yii\notificator\interfaces\NotificationInterface;
use dimichspb\yii\notificator\interfaces\NotificationQueueInterface;
use dimichspb\yii\notificator\models\Message;
use dimichspb\yii\notificator\models\NotificationQueue\Id;
use yii\data\DataProviderInterface;

class NotificationQueueService extends BaseNotificationQueueService
{
    public function add(NotificationQueueInterface $notificationQueue)
    {
        $result = $this->repository->add($notificationQueue);
        $this->dispatcher->dispatch($notificationQueue->releaseEvents());

        return $result;
    }

    public function create(NotificationInterface $notification, Message $message, array $userIds)
    {
        $result = $this->repository->create($notification, $message, $userIds);
        $this->dispatcher->dispatch($notification->releaseEvents());

        return $result;
    }

    public function get(Id $id)
    {
        $notificationQueue = $this->repository->get($id);
        $this->dispatcher->dispatch($notificationQueue->releaseEvents());

        return $notificationQueue;
    }

    public function update(NotificationQueueInterface $notificationQueue)
    {
        $result = $this->repository->update($notificationQueue);
        $this->dispatcher->dispatch($notificationQueue->releaseEvents());

        return $result;
    }

    public function delete(NotificationQueueInterface $notificationQueue)
    {
        $result = $this->repository->delete($notificationQueue);
        $this->dispatcher->dispatch($notificationQueue->releaseEvents());

        return $result;
    }

    public function read(NotificationQueueInterface $notificationQueue)
    {
        $result = $this->repository->read($notificationQueue);
        $this->dispatcher->dispatch($notificationQueue->releaseEvents());

        return $result;
    }

    public function queue(array $params = [])
    {
        return $this->repository->queue($params);
    }

}