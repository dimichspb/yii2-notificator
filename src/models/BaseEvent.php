<?php
namespace dimichspb\yii\notificator\models;

abstract class BaseEvent
{
    protected $sender;

    public function __construct($sender)
    {
        $this->sender = $sender;
    }

    public function getSender()
    {
        return $this->sender;
    }
}