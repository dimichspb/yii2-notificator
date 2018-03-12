<?php
namespace dimichspb\yii\notificator\models\NotificationType;

use Assert\Assertion;

class Event
{
    private $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function isEqualTo(Event $that)
    {
        return $this->getValue() === $that->getValue();
    }
}