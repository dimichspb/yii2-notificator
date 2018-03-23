<?php
namespace dimichspb\yii\notificator\jobs;

use dimichspb\yii\notificator\exceptions\ChannelSendMethodReturnsFalseResultException;
use dimichspb\yii\notificator\exceptions\UserEmailNotSetException;
use dimichspb\yii\notificator\interfaces\NotificatorInterface;
use dimichspb\yii\notificator\models\NotificationQueue\Id;
use yii\base\BaseObject;
use yii\queue\Queue;
use yii\queue\RetryableJobInterface;

class ProcessNotificationQueueJob extends BaseObject implements RetryableJobInterface
{
    public $notificationQueueId;

    public $timeToTry = 60;
    
    public $numberOfRetries = 5;
    /**
     * @var NotificatorInterface
     */
    protected $notificator;


    public function execute($queue)
    {
        $this->notificator = \Yii::$container->get(NotificatorInterface::class);

        $notificationQueueId = new Id($this->notificationQueueId);
        $notificationQueue = $this->notificator->getQueue($notificationQueueId);
        $channel = $this->notificator->getChannel($notificationQueue->getChannelClass()->getValue());
        $to = $this->notificator->getUser($notificationQueue->getUserId());
        $from = $this->notificator->getFrom();

        try {
            $notificationQueue->attempt();
            $result = $channel->send($notificationQueue->getMessage(), $to, $from);
            if (is_null($result) || $result === false) {
                throw new ChannelSendMethodReturnsFalseResultException();
            }
            $notificationQueue->success($result);
            $this->notificator->updateQueue($notificationQueue);
        } catch (\Exception $exception) {
            $notificationQueue->error($exception->getMessage());
            $this->notificator->updateQueue($notificationQueue);
            throw $exception;
        }
    }

    public function getTtr()
    {
        return $this->timeToTry;
    }

    public function canRetry($attempt, $error)
    {
        return $attempt <= $this->numberOfRetries;
    }
}