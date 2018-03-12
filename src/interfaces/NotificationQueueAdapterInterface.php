<?php
namespace dimichspb\yii\notificator\interfaces;

use dimichspb\yii\notificator\models\NotificationQueue\Id;
use yii\data\DataProviderInterface;

interface NotificationQueueAdapterInterface
{
    public function add(NotificationInterface $notification);

    public function get(Id $id);

    public function update(NotificationInterface $notification);

    public function delete(NotificationInterface $notification);

    public function read(NotificationInterface $notification);

    /**
     * @param array $params
     * @return DataProviderInterface
     */
    public function queue(array $params = []);
}