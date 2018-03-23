<?php
namespace dimichspb\yii\notificator\services;

use dimichspb\yii\notificator\exceptions\UserEmailNotSetException;
use dimichspb\yii\notificator\exceptions\UserNotFoundException;
use dimichspb\yii\notificator\exceptions\UserUsernameNotSetException;
use dimichspb\yii\notificator\models\User\Email;
use dimichspb\yii\notificator\models\User\Username;
use yii\base\Application;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use dimichspb\yii\notificator\models\User\User;

class ActiveRecordUserService extends BaseUserService
{
    public $fromName = 'Administrator';
    public $fromEmail = 'admin@example.com';

    public function __construct(array $config = [])
    {
        $this->fromName = isset(\Yii::$app->params['adminEmail'])? \Yii::$app->params['adminEmail']: $this->fromEmail;

        $identity = new ($this->getIdentityClass());

        if (!$identity instanceof ActiveRecord) {
            throw new InvalidConfigException();
        }

        parent::__construct($config);
    }

    public function getIdentity($id = null)
    {
        /** @var IdentityInterface $identityClass */
        $identityClass = $this->getIdentityClass();
        if (is_null($id)) {
            $id = $this->userComponent->getIdentity()? $this->userComponent->getIdentity()->getId(): null;
        }

        return $id? $identityClass::findIdentity($id): null;
    }

    protected function getIdentityUsername(IdentityInterface $identity)
    {
        return isset($identity->username)? $identity->username: null;
    }

    protected function getIdentityEmail(IdentityInterface $identity)
    {
        return isset($identity->email)? $identity->email: null;
    }

    public function findAll(array $criteria = [])
    {
        /** @var ActiveRecord $identityClass */
        $identityClass = $this->getIdentityClass();

        return $identityClass::findAll($criteria);
    }

    public function getUser($id)
    {
        $identity = $this->getIdentity($id);
        if (is_null($identity)) {
            throw new UserNotFoundException();
        }
        $username = $this->getIdentityUsername($identity);
        if (is_null($username)) {
            throw new UserUsernameNotSetException();
        }
        $email = $this->getIdentityEmail($identity);
        if (is_null($email)) {
            throw new UserEmailNotSetException();
        }

        return new User(
            new Username($username),
            new Email($email)
        );
    }

    public function getFrom()
    {
        return new User(
            new Username($this->fromName),
            new Email($this->fromEmail)
        );
    }

}