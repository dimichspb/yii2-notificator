<?php
namespace dimichspb\yii\notificator\services;

use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use yii\web\User;

class ActiveRecordUserService extends BaseUserService
{
    public $userClass = 'app\models\User';

    /**
     * @var mixed|\yii\web\User
     */
    protected $userComponent;

    /**
     * @var ActiveRecord
     */
    protected $identityClass;

    public function __construct(array $config = [])
    {
        $this->userComponent = \Yii::$app->user;
        $this->identityClass = $this->userComponent->identityClass;

        $identity = new $this->identityClass;

        if (!$identity instanceof ActiveRecord) {
            throw new InvalidConfigException();
        }

        parent::__construct($config);
    }

    public function findAll(array $criteria = [])
    {
        /** @var ActiveRecord $identityClass */
        $identityClass = $this->identityClass;

        return $identityClass::findAll($criteria);
    }

}