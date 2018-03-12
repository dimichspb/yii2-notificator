<?php
namespace dimichspb\yii\notificator\handlers;

use yii\base\Event;

class BasicNotificationEventHandler extends BaseNotificationEventHandler
{
    public function handle(Event $event)
    {
        var_dump($event->sender);
    }
}