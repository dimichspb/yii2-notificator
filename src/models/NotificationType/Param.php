<?php
namespace dimichspb\yii\notificator\models\NotificationType;

use Assert\Assertion;

class Param
{
    private $name;
    private $value;

    public function __construct($name, $value = null)
    {
        Assertion::string($name);
        Assertion::nullOrString($value);

        $this->name = $name;
        $this->value = $value;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function isEqualTo(Param $that)
    {
        return $this->getValue() === $that->getValue();
    }
}