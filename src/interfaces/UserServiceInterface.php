<?php
namespace dimichspb\yii\notificator\interfaces;

interface UserServiceInterface
{
    public function getIdentity($id = null);
    public function findAll(array $criteria = []);
}