<?php
namespace dimichspb\yii\notificator\dispatchers;

use dimichspb\yii\notificator\interfaces\DispatcherInterface;
use dimichspb\yii\notificator\models\BaseEvent;

abstract class BaseDispatcher implements DispatcherInterface
{
    /**
     * @param BaseEvent[] $events
     */
    public function dispatch(array $events)
    {
        foreach ($events as $event) {
            \Yii::info(\Yii::t('notificator', 'Event was dispatched. ') . get_class($event). ', ' . get_class($event->getSender()));
        }
    }
}