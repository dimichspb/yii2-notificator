<?php
namespace dimichspb\yii\notificator\forms\Notification;

use dimichspb\yii\notificator\interfaces\NotificationTypeInterface;
use yii\base\Model;
use yii\rbac\Role;
use yii\web\IdentityInterface;

class NotificationCreateForm extends Model
{
    public $notification_type_id;
    public $users;
    public $roles;
    public $ignored_users = [];
    public $ignored_roles = [];

    protected $available_users = [];
    protected $available_roles = [];
    protected $available_types = [];

    public function __construct(
        array $availableUsers = [],
        array $availableRoles = [],
        array $availableTypes = [],
        array $config = []
    ) {
        $this->available_users = $availableUsers;
        $this->available_roles = $availableRoles;
        $this->available_types = $availableTypes;

        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['notification_type_id'], 'required'],
            [
                'roles',
                'required',
                'when' => function ($model) {
                    return is_null($model->user_id);
                },
                'whenClient' => "function (attribute, value) {
                    return $('#user_id').val() == null;
                }"
            ],
            [['users', 'ignored_users'], 'each', 'rule' => ['in', 'range' => $this->available_users]],
            [['roles', 'ignored_roles'], 'each', 'rule' => ['in', 'range' => $this->available_roles]],
        ];
    }

    /**
     * @return IdentityInterface[]
     */
    public function getAvailableUsers()
    {
        return $this->available_users;
    }

    /**
     * @return Role[]
     */
    public function getAvailableRoles()
    {
        return $this->available_roles;
    }

    /**
     * @return NotificationTypeInterface[]
     */
    public function getAvailableTypes()
    {
        return $this->available_types;
    }
}