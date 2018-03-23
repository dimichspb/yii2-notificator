<?php
namespace dimichspb\yii\notificator\interfaces;

use dimichspb\yii\notificator\models\Message;

interface ChannelInterface
{
    /**
     * @param Message $message
     * @return bool|string|array|object
     */
    public function send(Message $message);

    /**
     * @return array
     */
    public function getErrors();
}