<?php
namespace dimichspb\yii\notificator\interfaces;

use yii\base\Event;

interface NotificationTypeClassInterface
{
    public function getClass();

    public function getName();

    public function getDescription();

    public function getEventName();

    public function getMessage(Event $event);
}