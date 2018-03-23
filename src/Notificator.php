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
use dimichspb\yii\notificator\interfaces\NotificationQueueServiceInterface;
use dimichspb\yii\notificator\interfaces\NotificationRepositoryInterface;
use dimichspb\yii\notificator\interfaces\NotificationServiceInterface;
use dimichspb\yii\notificator\interfaces\NotificationTypeInterface;
use dimichspb\yii\notificator\interfaces\NotificationTypeRepositoryInterface;
use dimichspb\yii\notificator\interfaces\NotificationTypeServiceInterface;
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
use dimichspb\yii\notificator\services\NotificationTypeService;
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
     * @var NotificationQueueServiceInterface
     */
    public $notificationQueueService;

    /**
     * @var NotificationServiceInterface
     */
    public $notificationService;

    /**
     * @var NotificationTypeServiceInterface
     */
    public $notificationTypeService;

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
                                NotificationQueueServiceInterface $notificationQueueService,
                                NotificationServiceInterface $notificationService,
                                NotificationTypeServiceInterface $notificationTypeService,
                                NotificationEventHandlerInterface $handler,
                                DispatcherInterface $dispatcher,
                                UserServiceInterface $userService,
                                RoleServiceInterface $roleService,
                                array $config = [])
    {
        $this->container = $container;
        $this->notificationQueueService = $notificationQueueService;
        $this->notificationService = $notificationService;
        $this->notificationTypeService = $notificationTypeService;
        $this->handler = $handler;
        $this->dispatcher = $dispatcher;
        $this->userService = $userService;
        $this->roleService = $roleService;

        parent::__construct($config);
    }

    public function notifications(array $params = [])
    {
        return $this->notificationService->filter($params);
    }

    public function addNotification(NotificationInterface $notification)
    {
        return $this->notificationService->add($notification);
    }

    public function createNotification(NotificationCreateForm $notificationCreateForm)
    {
        return $this->notificationService->create($notificationCreateForm);
    }

    public function getNotification(NotificationId $id)
    {
        return $this->notificationService->get($id);
    }

    public function updateNotification(NotificationInterface $notification)
    {
        return $this->notificationService->update($notification);
    }

    public function deleteNotification(NotificationInterface $notification)
    {
        return $this->notificationService->remove($notification);
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
        return $this->notificationQueueService->queue($params);
    }

    public function getQueue(NotificationQueueId $id)
    {
        return $this->notificationQueueService->get($id);
    }

    public function addQueue(NotificationQueueInterface $notificationQueue)
    {
        return $this->notificationQueueService->add($notificationQueue);
    }

    public function updateQueue(NotificationQueueInterface $notificationQueue)
    {
        return $this->notificationQueueService->update($notificationQueue);
    }

    public function deleteQueue(NotificationQueueInterface $notificationQueue)
    {
        return $this->notificationQueueService->delete($notificationQueue);
    }

    public function read(NotificationQueueInterface $notificationQueue)
    {
        return $this->notificationQueueService->read($notificationQueue);
    }

    public function types(array $params = [])
    {
        return $this->notificationTypeService->filter($params);
    }

    public function addType(NotificationTypeInterface $notificationType)
    {
        return $this->notificationTypeService->add($notificationType);
    }

    public function deleteType(NotificationTypeInterface $notificationType)
    {
        return $this->notificationTypeService->remove($notificationType);
    }

    public function getType(NotificationTypeId $id)
    {
        return $this->notificationTypeService->get($id);
    }

    public function updateType(NotificationTypeInterface $notificationType)
    {
        return $this->notificationTypeService->update($notificationType);
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