<?php
namespace dimichspb\yii\notificator\interfaces;

use dimichspb\yii\notificator\models\Message;
use dimichspb\yii\notificator\models\NotificationQueue\ChannelClass;
use dimichspb\yii\notificator\models\UserId;

interface NotificationQueueInterface extends EntityInterface
{
    public function __construct(NotificationInterface $notification, Message $message, UserId $userId);
    public function getId();

    /**
     * @return \dimichspb\yii\notificator\models\NotificationQueue\UserId
     */
    public function getUserId();
    public function isAlreadyInQueue();
    public function read();

    /**
     * @return ChannelClass
     */
    public function getChannelClass();
    public function getMessage();
    public function attempt();
    public function process();
    public function success($result);
    public function error($message);
}