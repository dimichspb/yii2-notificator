<?php
namespace dimichspb\yii\notificator\forms\Notification;

use dimichspb\yii\notificator\interfaces\ChannelInterface;
use dimichspb\yii\notificator\interfaces\NotificationTypeInterface;
use yii\base\Model;
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
                    return is_null($model->user_id);
                },
                'whenClient' => "function (attribute, value) {
                    return $('#user_id').val() == null;
                }"
            ],
            ['notification_type_id', 'in', 'range' => $this->available_types],
            ['channel_class', 'in', 'range' => $this->available_channels],
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

    /**
     * @return ChannelInterface[]
     */
    public function getAvailableChannels()
    {
        return $this->available_channels;
    }
}