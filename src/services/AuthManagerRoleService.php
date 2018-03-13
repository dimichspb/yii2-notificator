<?php
namespace dimichspb\yii\notificator\services;

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
}