<?php
namespace dimichspb\yii\notificator\channels;

use dimichspb\yii\notificator\interfaces\ChannelInterface;
use yii\base\BaseObject;

abstract class BaseChannel extends BaseObject implements ChannelInterface
{
    protected $name;

    public function getName()
    {
        return $this->name;
    }
}