<?php
namespace dimichspb\yii\notificator\interfaces;

use dimichspb\yii\notificator\models\Message;
use dimichspb\yii\notificator\models\UserId;

interface NotificationQueueInterface
{
    public function __construct(NotificationInterface $notification, Message $message, UserId $userId);
    public function getId();
    public function isAlreadyInQueue();
    public function read();
}