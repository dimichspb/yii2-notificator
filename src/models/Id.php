<?php
namespace dimichspb\yii\notificator\models;

use Assert\Assertion;
use Ramsey\Uuid\Uuid;

class Id extends BaseString
{
    protected $value;

    /**
     * Id constructor.
     * @param null $id
     * @throws \Assert\AssertionFailedException
     */
    public function __construct($id = null)
    {
        Assertion::nullOrNotEmpty($id);

        $this->value = $id?: Uuid::uuid4()->toString();
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