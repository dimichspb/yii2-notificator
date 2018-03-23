<?php
namespace dimichspb\yii\notificator\models\User;

use Assert\Assertion;
use dimichspb\yii\notificator\models\BaseString;

class Username extends BaseString
{
    protected $value;

    /**
     * Id constructor.
     * @param null $username
     * @throws \Assert\AssertionFailedException
     */
    public function __construct($username = null)
    {
        Assertion::string($username);

        $this->value = $username;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function isEqualTo(Username $that)
    {
        return $this->getValue() === $that->getValue();
    }
}
