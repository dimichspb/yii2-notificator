<?php
namespace dimichspb\yii\notificator\interfaces;

interface UserServiceInterface
{
    public function getFrom();
    public function getUser($id);
    public function getIdentity($id = null);
    public function findAll(array $criteria = []);
}