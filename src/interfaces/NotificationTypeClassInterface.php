<?php
namespace dimichspb\yii\notificator\interfaces;

use yii\base\Event;

interface NotificationTypeClassInterface
{
    public function getClass();

    public function getName();

    public function getDescription();

    /**
     * @return Event
     */
    public function getEvent();

    public function getMessage(Event $event);
}