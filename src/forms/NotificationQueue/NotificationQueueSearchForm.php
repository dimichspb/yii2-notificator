<?php
namespace dimichspb\yii\notificator\forms\NotificationQueue;

use dimichspb\yii\notificator\models\NotificationQueue\Status;
use yii\base\Model;

class NotificationQueueSearchForm extends Model
{
    public $id;
    public $created_at_from;
    public $created_at_till;
    public $sent_at_from;
    public $sent_at_till;
    public $attempts_from;
    public $attempts_till;
    public $statuses;
    public $message;

    public function rules()
    {
        return [
            [['created_at_from', 'created_at_till', 'sent_at_from', 'sent_at_till'], 'safe'],
            [['attempts_from', 'attempts_till'], 'integer'],
            [['id'], 'integer'],
            [['message', ], 'string'],
            [['statuses'], 'in', 'allowArray' => true, 'range' => Status::getAvailableStatuses()],
        ];
    }
}