<?php
namespace dimichspb\yii\notificator\interfaces;

use dimichspb\yii\notificator\models\Notification\Id;
use yii\data\DataProviderInterface;

interface NotificationRepositoryInterface
{
    public function get(NotificationInterface $notification);
    public function add(NotificationInterface $notification);
    public function remove(NotificationInterface $notification);
    public function update(NotificationInterface $notification);
    /**
     * @param array $params
     * @return DataProviderInterface
     */
    public function filter(array $params = []);
}