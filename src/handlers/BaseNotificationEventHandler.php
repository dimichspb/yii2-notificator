<?php
namespace dimichspb\yii\notificator\handlers;

use dimichspb\yii\notificator\interfaces\NotificationEventHandlerInterface;
use dimichspb\yii\notificator\interfaces\NotificationInterface;
use dimichspb\yii\notificator\interfaces\NotificationQueueRepositoryInterface;
use dimichspb\yii\notificator\interfaces\NotificationQueueServiceInterface;
use dimichspb\yii\notificator\interfaces\NotificationRepositoryInterface;
use dimichspb\yii\notificator\interfaces\NotificationServiceInterface;
use dimichspb\yii\notificator\interfaces\NotificationTypeInterface;
use dimichspb\yii\notificator\interfaces\NotificationTypeRepositoryInterface;
use dimichspb\yii\notificator\interfaces\NotificationTypeServiceInterface;
use dimichspb\yii\notificator\interfaces\NotificatorInterface;
use dimichspb\yii\notificator\interfaces\RoleServiceInterface;
use dimichspb\yii\notificator\interfaces\UserServiceInterface;
use yii\base\BaseObject;
use yii\base\Event;

abstract class BaseNotificationEventHandler extends BaseObject implements NotificationEventHandlerInterface
{
    /**
     * @var NotificationServiceInterface
     */
    protected $notificationService;

    /**
     * @var NotificationQueueServiceInterface
     */
    protected $notificationQueueService;

    /**
     * @var NotificationTypeServiceInterface
     */
    protected $notificationTypeService;

    /**
     * @var UserServiceInterface
     */
    protected $userService;

    /**
     * @var RoleServiceInterface
     */
    protected $roleService;

    public function __construct(
        NotificationServiceInterface $notificationService,
        NotificationQueueServiceInterface $notificationQueueService,
        NotificationTypeServiceInterface $notificationTypeService,
        UserServiceInterface $userService,
        RoleServiceInterface $roleService,
        array $config = []
    ) {
        $this->notificationService = $notificationService;
        $this->notificationQueueService = $notificationQueueService;
        $this->notificationTypeService = $notificationTypeService;
        $this->userService = $userService;
        $this->roleService = $roleService;

        parent::__construct($config);
    }

    protected function getNotificationTypesByEvent(Event $event)
    {
        return $this->notificationTypeService->findByEvent($event);
    }

    protected function getNotificationsByNotificationType(NotificationTypeInterface $notificationType)
    {
        return $this->notificationService->findByNotificationType($notificationType);
    }

    /**
     * @param Event $event
     * @return NotificationInterface[]
     */
    protected function getNotificationsByEvent(Event $event)
    {
        $notificationTypes = $this->getNotificationTypesByEvent($event);

        $notifications = [];
        foreach ($notificationTypes as $notificationType) {
            $notifications = array_merge($notifications, $this->getNotificationsByNotificationType($notificationType));
        }

        return $notifications;
    }
}