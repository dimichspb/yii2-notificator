<?php
namespace dimichspb\yii\notificator\interfaces;

use dimichspb\yii\notificator\models\UserId;

interface UserServiceInterface
{
    public function getFrom();
    public function getUser(UserId $id);
    public function getIdentity($id = null);
    public function findAll(array $criteria = []);
}