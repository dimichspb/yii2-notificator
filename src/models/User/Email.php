<?php
namespace dimichspb\yii\notificator\models\User;

use Assert\Assertion;
use dimichspb\yii\notificator\models\BaseString;

class Email extends BaseString
{
    protected $value;

    /**
     * Id constructor.
     * @param null $email
     * @throws \Assert\AssertionFailedException
     */
    public function __construct($email = null)
    {
        Assertion::email($email);

        $this->value = $email;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function isEqualTo(Email $that)
    {
        return $this->getValue() === $that->getValue();
    }
}
