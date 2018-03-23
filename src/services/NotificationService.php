<?php
namespace dimichspb\yii\notificator\services;

use dimichspb\yii\notificator\forms\Notification\NotificationCreateForm;
use dimichspb\yii\notificator\interfaces\NotificationInterface;
use dimichspb\yii\notificator\interfaces\NotificationTypeInterface;
use dimichspb\yii\notificator\models\Notification\ChannelClass;
use dimichspb\yii\notificator\models\Notification\CreatedBy;
use dimichspb\yii\notificator\models\Notification\Id;
use dimichspb\yii\notificator\models\Notification\RoleName;
use dimichspb\yii\notificator\models\NotificationType\Id as NotificationTypeId;
use dimichspb\yii\notificator\models\Notification\Notification;
use dimichspb\yii\notificator\models\UserId;
use yii\data\DataProviderInterface;

class NotificationService extends BaseNotificationService
{
    public function get(Id $id)
    {
        $notification = $this->repository->get($id);
        $this->dispatcher->dispatch($notification->releaseEvents());

        return $notification;
    }

    public function add(NotificationInterface $notification)
    {
        $result = $this->repository->add($notification);
        $this->dispatcher->dispatch($notification->releaseEvents());

        return $result;
    }

    public function create(NotificationCreateForm $notificationCreateForm)
    {
        $notification = new Notification(
            new NotificationTypeId($notificationCreateForm->notification_type_id),
            new ChannelClass($notificationCreateForm->channel_class),
            array_map(function ($row) {
                return new UserId(
                    $row
                );
            }, $notificationCreateForm->users),
            array_map(function ($row) {
                return new RoleName(
                    $row
                );
            }, $notificationCreateForm->roles),
            array_map(function ($row) {
                return new UserId(
                    $row
                );
            }, $notificationCreateForm->ignored_users),
            array_map(function ($row) {
                return new RoleName(
                    $row
                );
            }, $notificationCreateForm->ignored_roles),
            new CreatedBy($this->userService->getIdentity())
        );

        $this->dispatcher->dispatch($notification->releaseEvents());

        return $notification;
    }

    public function remove(NotificationInterface $notification)
    {
        $result = $this->repository->remove($notification);
        $this->dispatcher->dispatch($notification->releaseEvents());

        return $result;
    }

    public function update(NotificationInterface $notification)
    {
        $result = $this->repository->update($notification);
        $this->dispatcher->dispatch($notification->releaseEvents());

        return $result;
    }

    public function filter(array $params = [])
    {
        return $this->repository->filter($params);
    }

    public function findByNotificationType(NotificationTypeInterface $notificationType)
    {
        return $this->repository->findByNotificationType($notificationType);
    }

}