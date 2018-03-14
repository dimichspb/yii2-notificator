<?php
namespace dimichspb\yii\notificator;

use dimichspb\yii\notificator\channels\MailChannel;
use dimichspb\yii\notificator\forms\Notification\NotificationCreateForm;
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
    use EventTrait;

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

    public $limit = 10;

    protected $container;

    public function __construct(Container $container,
                                NotificationQueueRepositoryInterface $notificationQueueRepository,
                                NotificationRepositoryInterface $notificationRepository,
                                NotificationTypeRepositoryInterface $notificationTypeRepository,
                                NotificationEventHandlerInterface $handler,
                                UserServiceInterface $userService,
                                RoleServiceInterface $roleService,
                                array $config = [])
    {
        $this->container = $container;
        $this->notificationQueueRepository = $notificationQueueRepository;
        $this->notificationRepository = $notificationRepository;
        $this->notificationTypeRepository = $notificationTypeRepository;
        $this->handler = $handler;
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
        return $this->notificationRepository->add($notification);
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

        return $notification;
    }

    public function getNotification(NotificationId $id)
    {
        return $this->notificationRepository->get($id);
    }

    public function updateNotification(NotificationInterface $notification)
    {
        return $this->notificationRepository->update($notification);
    }

    public function deleteNotification(NotificationInterface $notification)
    {
        return $this->notificationRepository->remove($notification);
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
        return $this->notificationQueueRepository->get($id);
    }

    public function addQueue(NotificationQueueInterface $notificationQueue)
    {
        return $this->notificationQueueRepository->add($notificationQueue);
    }

    public function updateQueue(NotificationQueueInterface $notificationQueue)
    {
        // TODO: Implement updateQueue() method.
    }

    public function deleteQueue(NotificationQueueInterface $notificationQueue)
    {
        // TODO: Implement deleteQueue() method.
    }


    public function types(array $params = [])
    {
        return $this->notificationTypeRepository->filter($params);
    }

    public function addType(NotificationTypeInterface $notificationType)
    {
        return $this->notificationTypeRepository->add($notificationType);
    }

    public function deleteType(NotificationTypeInterface $notificationType)
    {
        return $this->notificationTypeRepository->remove($notificationType);
    }

    public function getType(NotificationTypeId $id)
    {
        return $this->notificationTypeRepository->get($id);
    }

    public function updateType(NotificationTypeInterface $notificationType)
    {
        return $this->notificationTypeRepository->update($notificationType);
    }

    public function getChannels(array $params = [])
    {
        return $this->channels;
    }

    public function getChannel($channelClass)
    {
        $key = array_search($channelClass, array_column($this->channels, 'class'));
        if (is_null($key) || !isset($this->channels[$key])) {
            return null;
        }
        $config = $this->channels[$key];

        return $this->container->get($channelClass, $config);
    }

    public function handle(Event $event)
    {
        return $this->handler->handle($event);
    }

    public function read(NotificationQueueInterface $notification)
    {
        return $this->notificationQueueRepository->read($notification);
    }

    public function process($limit = null)
    {
        //$this->adapter->process($limit? $limit: $this->limit);
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