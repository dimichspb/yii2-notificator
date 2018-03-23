<?php
namespace dimichspb\yii\notificator\models;

trait EventTrait
{
    /**
     * @var BaseEvent[]
     */
    private $_events = [];

    protected function recordEvent(BaseEvent $event)
    {
        $this->_events[] = $event;
    }

    /**
     * @return BaseEvent[]
     */
    public function getEvents()
    {
        return $this->_events;
    }

    /**
     * @return BaseEvent[]
     */
    public function releaseEvents()
    {
        $events = $this->_events;
        $this->_events = [];
        return $events;
    }
}