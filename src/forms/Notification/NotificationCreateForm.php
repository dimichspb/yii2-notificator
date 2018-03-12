<?php
namespace dimichspb\yii\notificator\forms\Notification;

use yii\base\Model;

class NotificationCreateForm extends Model
{
    public $notification_type_id;
    public $users;
    public $roles;

    protected $available_users = [];
    protected $available_roles = [];

    public function __construct(array $availableUsers = [], array $availableRoles = [], array $config = [])
    {
        $this->available_users = $availableUsers;
        $this->available_roles = $availableRoles;

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
            ['users', 'each', 'rule' => ['in', 'range' => $this->available_users]],
            ['roles', 'each', 'rule' => ['in', 'range' => $this->available_roles]],
        ];
    }
}