<?php
namespace dimichspb\yii\notificator\forms\NotificationType;

use yii\base\Model;

class NotificationTypeCreateForm extends Model
{
    public $notification_type_class;
    public $events;
    public $params;

    public function rules()
    {
        return [
            [['notification_type_class','events'], 'required'],
        ];
    }
}