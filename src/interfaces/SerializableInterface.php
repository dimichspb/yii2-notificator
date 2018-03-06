<?php
namespace dimichspb\yii\notificator\interfaces;

interface SerializableInterface
{
    public function serialize();

    public function unserialize($data);
}