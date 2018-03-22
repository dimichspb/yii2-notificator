<?php
namespace dimichspb\yii\notificator\models;

use Assert\Assertion;

class Message
{
    private $value;

    /**
     * Id constructor.
     * @param $value
     * @throws \Assert\AssertionFailedException
     */
    public function __construct($value)
    {
        Assertion::string($value);

        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function isEqualTo(Id $that)
    {
        return $this->getValue() === $that->getValue();
    }
}