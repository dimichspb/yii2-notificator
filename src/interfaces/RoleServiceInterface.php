<?php
namespace dimichspb\yii\notificator\interfaces;

use dimichspb\yii\notificator\models\UserId;

interface RoleServiceInterface
{
    public function findAll(array $criteria = []);

    /**
     * @param $roleName
     * @return UserId[]
     */
    public function getUserIdsByRoleName($roleName);
}