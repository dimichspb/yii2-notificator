<?php
namespace dimichspb\yii\notificator\models;

use Assert\Assertion;

class UserId
{
    protected $value;

    /**
     * Id constructor.
     * @param $value
     * @throws \Assert\AssertionFailedException
     */
    public function __construct($value)
    {
        Assertion::notEmpty($value);

        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function isEqualTo(UserId $that)
    {
        return $this->getValue() === $that->getValue();
    }
}