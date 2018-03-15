<?php
namespace dimichspb\yii\notificator\interfaces;

interface NotificationQueueInterface
{
    public function __construct($userId, NotificationInterface $notification);
    public function getId();
    public function isAlreadyInQueue();
    public function read();
}