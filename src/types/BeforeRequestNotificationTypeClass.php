<?php
namespace dimichspb\yii\notificator\types;

use yii\base\Application;
use yii\base\Event;
use yii\web\View;

class BeforeRequestNotificationTypeClass extends BaseNotificationTypeClass
{
    public function getName()
    {
        return \Yii::t('notificator', 'Notification before request');
    }

    public function getDescription()
    {
        return \Yii::t('notificator', 'This is common basic notification example which is going to be fired at the moment BeforeRequest event is triggered');
    }

    public function getMessage(Event $event)
    {
        return $this->render('views/before-request', [
            'senderClass' => get_class($event->sender),
        ]);
    }

    public function getEventName()
    {
         return Application::EVENT_BEFORE_REQUEST;
    }
}