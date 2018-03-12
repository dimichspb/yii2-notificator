<?php
namespace dimichspb\yii\notificator\interfaces;

interface NotificationQueueInterface
{
    public function getId();
    public function isAlreadyInQueue();
    public function read();
}