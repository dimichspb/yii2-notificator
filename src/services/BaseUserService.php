<?php
namespace dimichspb\yii\notificator\services;

use dimichspb\yii\notificator\interfaces\UserServiceInterface;
use dimichspb\yii\notificator\models\UserId;
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

    public function __construct(array $config = [])
    {
        if (\Yii::$app instanceof WebApplication) {
            $this->userComponent = \Yii::$app->user;
        }

        parent::__construct($config);
    }

    abstract public function getIdentity($id = null);

    abstract public function getUser(UserId $id);

    protected function getIdentityClass()
    {
        return $this->userComponent? $this->userComponent->identityClass: '';
    }
}