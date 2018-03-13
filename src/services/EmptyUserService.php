<?php
namespace dimichspb\yii\notificator\services;

class EmptyUserService extends BaseUserService
{
    public function findAll(array $criteria = [])
    {
        return [];
    }

}