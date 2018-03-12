<?php
namespace dimichspb\yii\notificator\interfaces;

use dimichspb\yii\notificator\models\NotificationType\Event;
use dimichspb\yii\notificator\models\NotificationType\Id;
use dimichspb\yii\notificator\models\NotificationType\NotificationTypeClass;
use dimichspb\yii\notificator\models\NotificationType\Param;

interface NotificationTypeInterface
{
    public function __construct(NotificationTypeClassInterface $notificationTypeClass, $createdBy = null, array $config = []);

    /**
     * @return Id
     */
    public function getId();

    /**
     * @return Event[]
     */
    public function getEvents();

    /**
     * @return Param[]
     */
    public function getParams();

    /**
     * @return NotificationTypeClass
     */
    public function getNotificationTypeClass();

    public function getName();
    public function getDescription();
    public function getStatus();
}