<?php
namespace dimichspb\yii\notificator\interfaces;

interface ChannelInterface
{
    public function send(MessageInterface $message);
}