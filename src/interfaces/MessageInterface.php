<?php
namespace dimichspb\yii\notificator\interfaces;

interface MessageInterface extends SerializableInterface
{
    public function className();
    public function __toString();
}