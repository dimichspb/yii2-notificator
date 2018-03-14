<?php
namespace dimichspb\yii\notificator\models;

use Assert\Assertion;

class UserId extends BaseString
{
    protected $value;

    /**
     * Id constructor.
     * @param $value
     * @throws \Assert\AssertionFailedException
     */
    public function __construct($value)
    {
        Assertion::nullOrNotEmpty($value);

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