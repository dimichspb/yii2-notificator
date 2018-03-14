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

        $this->value = $datetime? \DateTimeImmutable::createFromFormat($this->format, $datetime): new \DateTimeImmutable();

    }

    public function getValue()
    {
        return $this->value->format($this->format);
    }

    /**
     * @param $interval
     * @return static
     * @throws \Exception
     */
    public function add($interval)
    {
        $this->value->add(new \DateInterval($interval));

        return $this;
    }

    public function isEqualTo(DateTime $that)
    {
        return $this->getValue() === $that->getValue();
    }
}