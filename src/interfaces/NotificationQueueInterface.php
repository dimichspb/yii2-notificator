<?php
namespace dimichspb\yii\notificator\interfaces;

use dimichspb\yii\notificator\models\UserId;

interface NotificationQueueInterface
{
    public function __construct(UserId $userId, NotificationInterface $notification);
    public function getId();
    public function isAlreadyInQueue();
    public function read();
}