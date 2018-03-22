<?php
namespace dimichspb\yii\notificator\interfaces;

use dimichspb\yii\notificator\models\Message;
use dimichspb\yii\notificator\models\NotificationQueue\Id;
use yii\data\DataProviderInterface;

interface NotificationQueueRepositoryInterface
{
    public function add(NotificationQueueInterface $notificationQueue);

    public function create(NotificationInterface $notification, Message $message, array $userIds);

    public function get(Id $id);

    public function update(NotificationQueueInterface $notificationQueue);

    public function delete(NotificationQueueInterface $notificationQueue);

    public function read(NotificationQueueInterface $notificationQueue);

    /**
     * @param array $params
     * @return DataProviderInterface
     */
    public function queue(array $params = []);
}