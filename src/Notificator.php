<?php
namespace dimichspb\yii\notificator;

use dimichspb\yii\notificator\channels\MailChannel;
use dimichspb\yii\notificator\forms\Notification\NotificationCreateForm;
use dimichspb\yii\notificator\interfaces\ChannelInterface;
use dimichspb\yii\notificator\interfaces\DispatcherInterface;
use dimichspb\yii\notificator\interfaces\NotificationEventHandlerInterface;
use dimichspb\yii\notificator\interfaces\NotificationInterface;
use dimichspb\yii\notificator\interfaces\NotificationQueueRepositoryInterface;
use dimichspb\yii\notificator\interfaces\NotificationQueueInterface;
use dimichspb\yii\notificator\interfaces\NotificationRepositoryInterface;
use dimichspb\yii\notificator\interfaces\NotificationTypeInterface;
use dimichspb\yii\notificator\interfaces\NotificationTypeRepositoryInterface;
use dimichspb\yii\notificator\interfaces\NotificatorInterface;
use dimichspb\yii\notificator\interfaces\RoleServiceInterface;
use dimichspb\yii\notificator\interfaces\UserServiceInterface;
use dimichspb\yii\notificator\models\Notification\ChannelClass;
use dimichspb\yii\notificator\models\Notification\CreatedBy;
use dimichspb\yii\notificator\models\Notification\Id as NotificationId;
use dimichspb\yii\notificator\models\Notification\Notification;
use dimichspb\yii\notificator\models\Notification\RoleName;
use dimichspb\yii\notificator\models\Notification\UserId;
use dimichspb\yii\notificator\models\NotificationQueue\Id as NotificationQueueId;
use dimichspb\yii\notificator\models\NotificationType\Id as NotificationTypeId;
use yii\base\Component;
use yii\base\Event;
use yii\data\DataProviderInterface;
use yii\db\ActiveRecord;
use yii\di\Container;
use yii\rbac\ManagerInterface;
use yii\web\User;

class Notificator extends Component implements NotificatorInterface
{
    const EVENT_BEFORE_RUN = 'beforeRun';

    const EVENT_AFTER_RUN = 'afterRun';

    /**
     * @var NotificationQueueRepositoryInterface
     */
    public $notificationQueueRepository;

    /**
     * @var NotificationRepositoryInterface
     */
    public $notificationRepository;

    /**
     * @var NotificationTypeRepositoryInterface
     */
    public $notificationTypeRepository;

    public $channels = [
        'mail' => [
            'class' => MailChannel::class,
            'name' => 'E-mail channel',
        ],
    ];

    /**
     * @var UserServiceInterface
     */
    protected $userService;

    /**
     * @var RoleServiceInterface
     */
    protected $roleService;

    /**
     * @var NotificationEventHandlerInterface
     */
    public $handler;

    /**
     * @var DispatcherInterface
     */
    protected $dispatcher;

    public $limit = 10;

    protected $container;

    public function __construct(Container $container,
                                NotificationQueueRepositoryInterface $notificationQueueRepository,
                                NotificationRepositoryInterface $notificationRepository,
                                NotificationTypeRepositoryInterface $notificationTypeRepository,
                                NotificationEventHandlerInterface $handler,
                                DispatcherInterface $dispatcher,
                                UserServiceInterface $userService,
                                RoleServiceInterface $roleService,
                                array $config = [])
    {
        $this->container = $container;
        $this->notificationQueueRepository = $notificationQueueRepository;
        $this->notificationRepository = $notificationRepository;
        $this->notificationTypeRepository = $notificationTypeRepository;
        $this->handler = $handler;
        $this->dispatcher = $dispatcher;
        $this->userService = $userService;
        $this->roleService = $roleService;

        parent::__construct($config);
    }

    public function notifications(array $params = [])
    {
        return $this->notificationRepository->filter($params);
    }

    public function addNotification(NotificationInterface $notification)
    {
        $result = $this->notificationRepository->add($notification);
        $this->dispatcher->dispatch($notification->releaseEvents());

        return $result;
    }

    public function createNotification(NotificationCreateForm $notificationCreateForm)
    {
        $notification = new Notification(
            new NotificationTypeId($notificationCreateForm->notification_type_id),
            new ChannelClass($notificationCreateForm->channel_class),
            array_map(function ($row) {
                return new UserId(
                    $row
                );
            }, $notificationCreateForm->users),
            array_map(function ($row) {
                return new RoleName(
                    $row
                );
            }, $notificationCreateForm->roles),
            array_map(function ($row) {
                return new UserId(
                    $row
                );
            }, $notificationCreateForm->ignored_users),
            array_map(function ($row) {
                return new RoleName(
                    $row
                );
            }, $notificationCreateForm->ignored_roles),
            new CreatedBy($this->userService->getIdentity())
        );

        $this->dispatcher->dispatch($notification->releaseEvents());

        return $notification;
    }

    public function getNotification(NotificationId $id)
    {
        $notification = $this->notificationRepository->get($id);
        $this->dispatcher->dispatch($notification->releaseEvents());

        return $notification;
    }

    public function updateNotification(NotificationInterface $notification)
    {
        $result = $this->notificationRepository->update($notification);
        $this->dispatcher->dispatch($notification->releaseEvents());

        return $result;
    }

    public function deleteNotification(NotificationInterface $notification)
    {
        $result = $this->notificationRepository->remove($notification);
        $this->dispatcher->dispatch($notification->releaseEvents());

        return $result;
    }

    public function activateNotification(NotificationInterface $notification)
    {
        // TODO: Implement activate() method.
    }

    public function deactivateNotification(NotificationInterface $notification)
    {
        // TODO: Implement deactivate() method.
    }

    /**
     * @param array
     * @return DataProviderInterface
     */
    public function queue(array $params = [])
    {
        return $this->notificationQueueRepository->queue($params);
    }

    public function getQueue(NotificationQueueId $id)
    {
        $notificationQueue = $this->notificationQueueRepository->get($id);
        $this->dispatcher->dispatch($notificationQueue->releaseEvents());

        return $notificationQueue;
    }

    public function addQueue(NotificationQueueInterface $notificationQueue)
    {
        $result = $this->notificationQueueRepository->add($notificationQueue);
        $this->dispatcher->dispatch($notificationQueue->releaseEvents());

        return $result;
    }

    public function updateQueue(NotificationQueueInterface $notificationQueue)
    {
        $result = $this->notificationQueueRepository->update($notificationQueue);
        $this->dispatcher->dispatch($notificationQueue->releaseEvents());

        return $result;
    }

    public function deleteQueue(NotificationQueueInterface $notificationQueue)
    {
        $result = $this->notificationQueueRepository->delete($notificationQueue);
        $this->dispatcher->dispatch($notificationQueue->releaseEvents());

        return $result;
    }

    public function read(NotificationQueueInterface $notificationQueue)
    {
        $result = $this->notificationQueueRepository->read($notificationQueue);
        $this->dispatcher->dispatch($notificationQueue->releaseEvents());

        return $result;
    }

    public function types(array $params = [])
    {
        return $this->notificationTypeRepository->filter($params);
    }

    public function addType(NotificationTypeInterface $notificationType)
    {
        $result =  $this->notificationTypeRepository->add($notificationType);
        $this->dispatcher->dispatch($notificationType->releaseEvents());

        return $result;
    }

    public function deleteType(NotificationTypeInterface $notificationType)
    {
        $result = $this->notificationTypeRepository->remove($notificationType);
        $this->dispatcher->dispatch($notificationType->releaseEvents());

        return $result;
    }

    public function getType(NotificationTypeId $id)
    {
        $notificationType = $this->notificationTypeRepository->get($id);
        $this->dispatcher->dispatch($notificationType->releaseEvents());

        return $notificationType;
    }

    public function updateType(NotificationTypeInterface $notificationType)
    {
        $result = $this->notificationTypeRepository->update($notificationType);
        $this->dispatcher->dispatch($notificationType->releaseEvents());

        return $result;
    }

    public function getChannels(array $params = [])
    {
        return $this->channels;
    }

    public function getChannel($channelClassName)
    {
        $key = $this->getChannelKey($channelClassName);

        if (is_null($key) || !isset($this->channels[$key])) {
            return null;
        }
        $config = $this->channels[$key];

        return $this->container->get($channelClassName, $config);
    }

    protected function getChannelKey($channelClassName)
    {
        $index = array_search($channelClassName, array_column($this->channels, 'class'));

        return array_keys($this->channels)[$index];
    }

    public function getChannelName($channelClassName)
    {
        $key = $this->getChannelKey($channelClassName);
        $name = isset($this->channels[$key]['name'])? $this->channels[$key]['name']: $key;

        return \Yii::t('app', $name);
    }

    public function handle(Event $event)
    {
        return $this->handler->handle($event);
    }

    public function getUserService()
    {
        return $this->userService;
    }

    public function getRoleService()
    {
        return $this->roleService;
    }


}