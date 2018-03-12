<?php
namespace dimichspb\yii\notificator\models\NotificationType;

use Assert\Assertion;

class NotificationTypeClass
{
    private $value;

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

    public function isEqualTo(Id $that)
    {
        return $this->getValue() === $that->getValue();
    }
}