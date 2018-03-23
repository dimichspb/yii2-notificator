<?php
namespace dimichspb\yii\notificator\interfaces;

use dimichspb\yii\notificator\models\Message;
use yii\web\IdentityInterface;

interface ChannelInterface
{
    /**
     * @param Message $message
     * @param UserInterface $to
     * @param UserInterface $from
     * @return bool|string|array|object
     */
    public function send(Message $message, UserInterface $to, UserInterface $from);

    /**
     * @return array
     */
    public function getErrors();
}