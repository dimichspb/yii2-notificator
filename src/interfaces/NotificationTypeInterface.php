<?php
namespace dimichspb\yii\notificator\interfaces;

use dimichspb\yii\notificator\models\NotificationType\Event;
use dimichspb\yii\notificator\models\NotificationType\Id;
use dimichspb\yii\notificator\models\NotificationType\NotificationTypeClass;
use dimichspb\yii\notificator\models\NotificationType\Param;
use dimichspb\yii\notificator\models\NotificationType\Status;

interface NotificationTypeInterface extends EntityInterface
{
    public function __construct(NotificationTypeClassInterface $notificationTypeClass, $createdBy = null, array $config = []);

    /**
     * @return Id
     */
    public function getId();

    /**
     * @return Event
     */
    public function getEvent();

    /**
     * @return NotificationTypeClass
     */
    public function getNotificationTypeClass();

    public function getName();
    public function getDescription();
    public function getMessage(\yii\base\Event $event);

    /**
     * @return Status
     */
    public function getStatus();
}