<?php
namespace dimichspb\yii\notificator\interfaces;

use dimichspb\yii\notificator\models\BaseEvent;

interface EventableInterface
{
    /**
     * @return BaseEvent[]
     */
    public function getEvents();

    /**
     * @return BaseEvent[]
     */
    public function releaseEvents();
}