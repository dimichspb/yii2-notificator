<?php
namespace dimichspb\yii\notificator\interfaces;

use dimichspb\yii\notificator\models\NotificationType\Id;
use yii\data\DataProviderInterface;

interface NotificationTypeRepositoryInterface
{
    public function get(Id $id);
    public function add(NotificationTypeInterface $notificationType);
    public function remove(NotificationTypeInterface $notificationType);
    public function update(NotificationTypeInterface $notificationType);
    /**
     * @param array $params
     * @return DataProviderInterface
     */
    public function filter(array $params = []);
}