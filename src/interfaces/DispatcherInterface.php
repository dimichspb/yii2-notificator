<?php
namespace dimichspb\yii\notificator\interfaces;

interface DispatcherInterface
{
    public function dispatch(array $events);
}