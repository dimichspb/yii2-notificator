<?php
namespace dimichspb\yii\notificator\services;

use yii\base\Application;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use yii\web\User;

class ActiveRecordUserService extends BaseUserService
{
    public function __construct(Application $application, array $config = [])
    {
        $identity = new ($this->getIdentityClass());

        if (!$identity instanceof ActiveRecord) {
            throw new InvalidConfigException();
        }

        parent::__construct($application, $config);
    }

    public function findAll(array $criteria = [])
    {
        /** @var ActiveRecord $identityClass */
        $identityClass = $this->getIdentityClass();

        return $identityClass::findAll($criteria);
    }

}