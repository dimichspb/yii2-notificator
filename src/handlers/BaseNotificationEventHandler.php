<?php
namespace dimichspb\yii\notificator\handlers;

use dimichspb\yii\notificator\interfaces\NotificationEventHandlerInterface;
use dimichspb\yii\notificator\interfaces\NotificationInterface;
use dimichspb\yii\notificator\interfaces\NotificationQueueRepositoryInterface;
use dimichspb\yii\notificator\interfaces\NotificationRepositoryInterface;
use dimichspb\yii\notificator\interfaces\NotificationTypeInterface;
use dimichspb\yii\notificator\interfaces\NotificationTypeRepositoryInterface;
use dimichspb\yii\notificator\interfaces\NotificatorInterface;
use dimichspb\yii\notificator\interfaces\RoleServiceInterface;
use dimichspb\yii\notificator\interfaces\UserServiceInterface;
use yii\base\BaseObject;
use yii\base\Event;

abstract class BaseNotificationEventHandler extends BaseObject implements NotificationEventHandlerInterface
{
    /**
     * @var NotificationRepositoryInterface
     */
    protected $notificationRepository;

    /**
     * @var NotificationQueueRepositoryInterface
     */
    protected $notificationQueueRepository;

    /**
     * @var NotificationTypeRepositoryInterface
     */
    protected $notificationTypeRepository;

    /**
     * @var UserServiceInterface
     */
    protected $userService;

    /**
     * @var RoleServiceInterface
     */
    protected $roleService;

    public function __construct(
        NotificationRepositoryInterface $notificationRepository,
        NotificationQueueRepositoryInterface $notificationQueueRepository,
        NotificationTypeRepositoryInterface $notificationTypeRepository,
        UserServiceInterface $userService,
        RoleServiceInterface $roleService,
        array $config = []
    ) {
        $this->notificationRepository = $notificationRepository;
        $this->notificationQueueRepository = $notificationQueueRepository;
        $this->notificationTypeRepository = $notificationTypeRepository;
        $this->userService = $userService;
        $this->roleService = $roleService;

        parent::__construct($config);
    }

    protected function getNotificationTypesByEvent(Event $event)
    {
        return $this->notificationTypeRepository->findByEvent($event);
    }

    protected function getNotificationsByNotificationType(NotificationTypeInterface $notificationType)
    {
        return $this->notificationRepository->findByNotificationType($notificationType);
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