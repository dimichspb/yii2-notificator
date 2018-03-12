<?php
namespace dimichspb\yii\notificator\types;

use yii\base\Application;

class BeforeRequestNotificationTypeClass extends BaseNotificationTypeClass
{
    public function getName()
    {
        return \Yii::t('notificator', 'Notification before request');
    }

    public function getDescription()
    {
        return \Yii::t('notificator', 'This is common basic notification example which is going to be fired at the moment BeforeRequest event is triggered');
    }

    public function getView()
    {
        return 'views/before-request';
    }

    public function getPermission()
    {
        // TODO: Implement getPermission() method.
    }

    public function getEvents()
    {
        return [
            Application::EVENT_BEFORE_REQUEST,
        ];
    }

    public function getParams()
    {
        // TODO: Implement getParams() method.
    }

    public function setParams($data)
    {
        // TODO: Implement setParams() method.
    }

}