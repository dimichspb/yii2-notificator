<?php
namespace dimichspb\yii\notificator\services;

use dimichspb\yii\notificator\interfaces\NotificationTypeInterface;
use dimichspb\yii\notificator\models\NotificationType\Id;
use dimichspb\yii\notificator\models\NotificationType\NotificationType;
use yii\base\Event;
use yii\data\DataProviderInterface;

class NotificationTypeService extends BaseNotificationTypeService
{
    public function get(Id $id)
    {
        $notificationType = $this->repository->get($id);
        $this->dispatcher->dispatch($notificationType->releaseEvents());

        return $notificationType;
    }

    public function add(NotificationTypeInterface $notificationType)
    {
        $result =  $this->repository->add($notificationType);
        $this->dispatcher->dispatch($notificationType->releaseEvents());

        return $result;
    }

    public function remove(NotificationTypeInterface $notificationType)
    {
        $result = $this->repository->remove($notificationType);
        $this->dispatcher->dispatch($notificationType->releaseEvents());

        return $result;
    }

    public function update(NotificationTypeInterface $notificationType)
    {
        $result = $this->repository->update($notificationType);
        $this->dispatcher->dispatch($notificationType->releaseEvents());

        return $result;
    }

    public function filter(array $params = [])
    {
        return $this->repository->filter($params);
    }

    public function findByEvent(Event $event)
    {
        return $this->repository->findByEvent($event);
    }

}