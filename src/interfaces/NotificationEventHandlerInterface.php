<?php
namespace dimichspb\yii\notificator\interfaces;

use yii\base\Event;

interface NotificationEventHandlerInterface
{
    public function handle(Event $event);
}