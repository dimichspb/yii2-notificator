<?php
namespace dimichspb\yii\notificator\interfaces;

use dimichspb\yii\notificator\models\Notification\Id;
use dimichspb\yii\notificator\models\Notification\UserId;

interface NotificationInterface
{
    /**
     * @return Id
     */
    public function getId();
    /**
     * @return UserId
     */
    public function getUserIds();

    public function getRoleNames();

    public function getIgnoredUserIds();

    public function getIgnoredRoleNames();
    /**
     * @return MessageInterface
     */
    public function getMessage();
    /**
     * @return string
     */
    public function getChannelClass();

    public function getNotificationTypeId();
}