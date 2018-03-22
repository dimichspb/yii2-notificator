<?php
namespace dimichspb\yii\notificator\interfaces;

use dimichspb\yii\notificator\models\Notification\ChannelClass;
use dimichspb\yii\notificator\models\Notification\Id;
use dimichspb\yii\notificator\models\Notification\RoleName;
use dimichspb\yii\notificator\models\Notification\UserId;

interface NotificationInterface
{
    /**
     * @return Id
     */
    public function getId();
    /**
     * @return UserId[]
     */
    public function getUserIds();

    /**
     * @return RoleName[]
     */
    public function getRoleNames();

    public function getIgnoredUserIds();

    public function getIgnoredRoleNames();

    /**
     * @return MessageInterface
     */
    public function getMessage();

    /**
     * @return ChannelClass
     */
    public function getChannelClass();

    /**
     * @return \dimichspb\yii\notificator\models\NotificationType\Id
     */
    public function getNotificationTypeId();
}