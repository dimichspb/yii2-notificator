<?php
namespace dimichspb\yii\notificator\services;

use dimichspb\yii\notificator\interfaces\UserServiceInterface;
use yii\base\Application;
use yii\base\BaseObject;
use yii\web\IdentityInterface;
use yii\web\User;
use yii\web\Application as WebApplication;

abstract class BaseUserService extends BaseObject implements UserServiceInterface
{
    /**
     * @var User
     */
    protected $userComponent;

    public function __construct(Application $app, array $config = [])
    {
        if ($app instanceof WebApplication) {
            $this->userComponent = $app->user;
        }

        parent::__construct($config);
    }

    public function getIdentity($id = null)
    {
        /** @var IdentityInterface $identityClass */
        $identityClass = $this->getIdentityClass();
        if (is_null($id)) {
            $id = $identityClass->getId();
        }
        return $id? $identityClass::findIdentity($id): null;

    }

    protected function getIdentityClass()
    {
        return $this->userComponent->identityClass;
    }
}