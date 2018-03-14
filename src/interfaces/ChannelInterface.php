<?php
namespace dimichspb\yii\notificator\interfaces;

interface ChannelInterface
{
    public function className();

    public function getName();

    public function send(MessageInterface $message);
}