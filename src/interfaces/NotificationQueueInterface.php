<?php
namespace dimichspb\yii\notificator\interfaces;

use dimichspb\yii\notificator\models\Message;
use dimichspb\yii\notificator\models\UserId;

interface NotificationQueueInterface extends EntityInterface
{
    public function __construct(NotificationInterface $notification, Message $message, UserId $userId);
    public function getId();
    public function isAlreadyInQueue();
    public function read();
    public function getChannelClass();
    public function getMessage();
    public function attempt();
    public function process();
    public function success($result);
    public function error($message);
}