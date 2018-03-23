<?php
namespace dimichspb\yii\notificator\dispatchers;

use dimichspb\yii\notificator\jobs\ProcessNotificationQueueJob;
use dimichspb\yii\notificator\models\NotificationQueue\events\SavedEvent;
use yii\queue\Queue;

class NotificationQueueDispatcher extends BaseDispatcher
{
    /**
     * @var Queue
     */
    protected $queue;

    public function __construct()
    {
        $this->queue = \Yii::$app->queue;
    }

    public function dispatch(array $events)
    {
        foreach ($events as $event) {
            if ($event instanceof SavedEvent) {
                var_dump('PUSH');
                $this->queue->push(new ProcessNotificationQueueJob([
                    'notificationQueueId' => $event->getSender()->getId()->getValue()
                ]));
            }
        }
        parent::dispatch($events);
    }

}