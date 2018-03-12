<?php
namespace dimichspb\yii\notificator\interfaces;

interface NotificationTypeClassInterface
{
    public function getClass();

    public function getName();

    public function getDescription();

    public function getView();

    public function getPermission();

    public function getEvents();

    public function getParams();

    public function setParams($data);
}