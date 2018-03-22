<?php
namespace dimichspb\yii\notificator\forms\Notification;

use dimichspb\yii\notificator\interfaces\ChannelInterface;
use dimichspb\yii\notificator\interfaces\NotificationTypeInterface;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\rbac\Role;
use yii\web\IdentityInterface;

class NotificationCreateForm extends Model
{
    public $notification_type_id;
    public $channel_class;
    public $users;
    public $roles;
    public $ignored_users = [];
    public $ignored_roles = [];

    protected $available_users = [];
    protected $available_roles = [];
    protected $available_types = [];
    protected $available_channels = [];

    public function __construct(
        array $availableUsers = [],
        array $availableRoles = [],
        array $availableTypes = [],
        array $availableChannels = [],
        array $config = []
    ) {
        $this->available_users = $availableUsers;
        $this->available_roles = $availableRoles;
        $this->available_types = $availableTypes;
        $this->available_channels = $availableChannels;

        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['notification_type_id', 'channel_class',], 'required'],
            [
                'roles',
                'required',
                'when' => function ($model) {
                    return is_null($model->users);
                },
                'whenClient' => "function (attribute, value) {
                    return $('#users').val() == null;
                }"
            ],
            ['notification_type_id', 'in', 'range' => ArrayHelper::getColumn($this->available_types, 'id')],
            ['channel_class', 'in', 'range' => ArrayHelper::getColumn($this->available_channels, 'class')],
            [['users', 'ignored_users'], 'each', 'rule' => ['in', 'range' => ArrayHelper::getColumn($this->available_users, 'id')]],
            [['roles', 'ignored_roles'], 'each', 'rule' => ['in', 'range' => ArrayHelper::getColumn($this->available_roles, 'name')]],
            [['roles', 'ignored_roles'], 'each', 'rule' => ['in', 'range' => ArrayHelper::getColumn($this->available_roles, 'name')]],
            [['users', 'ignored_users', 'roles', 'ignored_roles'], 'default', 'value' => []],
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

    /**
     * @return ChannelInterface[]
     */
    public function getAvailableChannels()
    {
        return $this->available_channels;
    }
}