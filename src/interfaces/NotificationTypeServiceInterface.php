<?php
namespace dimichspb\yii\notificator\interfaces;

use dimichspb\yii\notificator\models\NotificationType\Id;
use dimichspb\yii\notificator\models\NotificationType\NotificationType;
use yii\base\Event;
use yii\data\DataProviderInterface;

interface NotificationTypeServiceInterface
{
    /**
     * @param Id $id
     * @return NotificationTypeInterface
     */
    public function get(Id $id);
    public function add(NotificationTypeInterface $notificationType);
    public function remove(NotificationTypeInterface $notificationType);
    public function update(NotificationTypeInterface $notificationType);
    /**
     * @param array $params
     * @return DataProviderInterface
     */
    public function filter(array $params = []);

    /**
     * @param Event $event
     * @return NotificationType[]
     */
    public function findByEvent(Event $event);
}