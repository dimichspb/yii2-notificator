<?php
namespace dimichspb\yii\notificator\models;

use Assert\Assertion;

class DateTime extends BaseString
{
    protected $value;
    protected $format = 'Y-m-d H:i:s';

    public function __construct($datetime = null)
    {
        Assertion::nullOrNotEmpty($datetime);

        $this->value = $datetime? \DateTimeImmutable::createFromFormat($this->format, $datetime): null;

    }

    public function getValue()
    {
        return $this->value? $this->value->format($this->format): null;
    }

    /**
     * @param $interval
     * @return static
     * @throws \Exception
     */
    public function add($interval)
    {
        if ($this->value) {
            $this->value->add(new \DateInterval($interval));
        }

        return $this;
    }

    public function isEqualTo(DateTime $that)
    {
        return $this->getValue() === $that->getValue();
    }
}