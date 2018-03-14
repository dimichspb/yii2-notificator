<?php
namespace dimichspb\yii\notificator\channels;

use dimichspb\yii\notificator\interfaces\ChannelInterface;

abstract class BaseChannel implements ChannelInterface
{
    protected $name;

    public function className()
    {
        return get_class($this);
    }

    public function getName()
    {
        return $this->name;
    }
}