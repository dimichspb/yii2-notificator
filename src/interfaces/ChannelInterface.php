<?php
namespace dimichspb\yii\notificator\interfaces;

interface ChannelInterface
{
    /**
     * @param MessageInterface $message
     * @return bool|string|array|object
     */
    public function send(MessageInterface $message);

    /**
     * @return array
     */
    public function getErrors();
}