<?php
namespace dimichspb\yii\notificator\types;

use dimichspb\yii\notificator\interfaces\NotificationTypeClassInterface;

abstract class BaseNotificationTypeClass implements NotificationTypeClassInterface
{
    public function getClass()
    {
        return self::class;
    }
}