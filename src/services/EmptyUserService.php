<?php
namespace dimichspb\yii\notificator\services;

use yii\web\IdentityInterface;

class EmptyUserService extends BaseUserService
{
    public function findAll(array $criteria = [])
    {
        return [];
    }
}