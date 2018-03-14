<?php
namespace dimichspb\yii\notificator\models;

abstract class BaseString
{
    abstract public function getValue();

    public function __toString()
    {
        return $this->getValue();
    }
}