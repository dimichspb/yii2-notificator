<?php
namespace dimichspb\yii\notificator\services;

use dimichspb\yii\notificator\models\UserId;
use yii\web\IdentityInterface;

class EmptyUserService extends BaseUserService
{
    public function findAll(array $criteria = [])
    {
        return [];
    }

    public function getIdentity($id = null)
    {
        return null;
    }

    public function getUser(UserId $id)
    {
        return null;
    }

    public function getFrom()
    {
        return null;
    }
}