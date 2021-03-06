<?php
namespace dimichspb\yii\notificator\forms\NotificationType;

use dimichspb\yii\notificator\models\Notification\Status;
use yii\base\Model;

class NotificationTypeSearchForm extends Model
{
    public $id;
    public $created_at_from;
    public $created_at_till;
    public $created_by;
    public $user_id;
    public $channel_class;
    public $notification_type_id;
    public $statuses;

    public function rules()
    {
        return [
            [['created_at_from', 'created_at_till',], 'safe'],
            [['id'], 'string'],
            [['channel_class', ], 'string'],
            [['statuses'], 'in', 'allowArray' => true, 'range' => Status::getAvailableStatuses()],
        ];
    }
}