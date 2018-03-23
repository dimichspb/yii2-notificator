<?php
namespace dimichspb\yii\notificator\interfaces;

use dimichspb\yii\notificator\models\Notification\Id;
use dimichspb\yii\notificator\models\Notification\Notification;
use yii\data\DataProviderInterface;

interface NotificationRepositoryInterface
{
    /**
     * @param Id $id
     * @return NotificationInterface
     */
    public function get(Id $id);
    public function add(NotificationInterface $notification);
    public function remove(NotificationInterface $notification);
    public function update(NotificationInterface $notification);
    /**
     * @param array $params
     * @return DataProviderInterface
     */
    public function filter(array $params = []);

    /**
     * @param NotificationTypeInterface $notificationType
     * @return Notification[]
     */
    public function findByNotificationType(NotificationTypeInterface $notificationType);
}