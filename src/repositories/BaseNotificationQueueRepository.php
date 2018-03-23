<?php
namespace dimichspb\yii\notificator\repositories;

use dimichspb\yii\notificator\interfaces\NotificationInterface;
use dimichspb\yii\notificator\interfaces\NotificationQueueInterface;
use dimichspb\yii\notificator\interfaces\NotificationQueueRepositoryInterface;
use dimichspb\yii\notificator\models\Message;
use yii\base\BaseObject;

abstract class BaseNotificationQueueRepository extends BaseObject implements NotificationQueueRepositoryInterface
{
    abstract public function getNotificationQueueClass();
}