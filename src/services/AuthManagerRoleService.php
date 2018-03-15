<?php
namespace dimichspb\yii\notificator\services;

use dimichspb\yii\notificator\models\UserId;

class AuthManagerRoleService extends BaseRoleService
{
    /**
     * @var \yii\rbac\ManagerInterface
     */
    protected $authManager;

    public function __construct(array $config = [])
    {
        $this->authManager = \Yii::$app->authManager;

        parent::__construct($config);
    }

    public function findAll(array $criteria = [])
    {
        return $this->authManager->getRoles();
    }

    /**
     * @param $roleName
     * @return array|UserId[]
     * @throws \Assert\AssertionFailedException
     */
    public function getUserIdsByRoleName($roleName)
    {
        $userIds = [];
        $users = $this->authManager->getUserIdsByRole($roleName);
        foreach ($users as $user) {
            $userIds[] = new UserId($user);
        }
        return $userIds;
    }


}