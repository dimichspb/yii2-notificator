<?php
namespace dimichspb\yii\notificator;

trait EventTrait
{
    private $_events = [];

    protected function recordEvent($event)
    {
        $this->_events[] = $event;
    }

    public function releaseEvents()
    {
        $events = $this->_events;
        $this->_events = [];
        return $events;
    }
}