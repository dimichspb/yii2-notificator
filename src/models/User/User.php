<?php
namespace dimichspb\yii\notificator\models\User;

use dimichspb\yii\notificator\interfaces\UserInterface;
use dimichspb\yii\notificator\models\EventTrait;
use dimichspb\yii\notificator\models\InstantiateTrait;
use dimichspb\yii\notificator\models\User\events\CreatedEvent;

class User implements UserInterface
{
    use EventTrait, InstantiateTrait;

    protected $username;
    protected $email;

    public function __construct(Username $username, Email $email)
    {
        $this->username = $username;
        $this->email = $email;

        $this->recordEvent(new CreatedEvent($this));
    }

    public function getUsername()
    {
        $this->username;
    }

    public function getEmail()
    {
        $this->email;
    }

}