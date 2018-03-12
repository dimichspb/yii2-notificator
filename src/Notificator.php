<?php
namespace dimichspb\yii\notificator;

use dimichspb\yii\mailqueue\models\MailQueue\search\NotificationQueueSearch;
use dimichspb\yii\notificator\channels\MailChannel;
use dimichspb\yii\notificator\interfaces\NotificationEventHandlerInterface;
use dimichspb\yii\notificator\interfaces\NotificationInterface;
use dimichspb\yii\notificator\interfaces\NotificationQueueAdapterInterface;
use dimichspb\yii\notificator\interfaces\NotificationRepositoryInterface;
use dimichspb\yii\notificator\interfaces\NotificationTypeInterface;
use dimichspb\yii\notificator\interfaces\NotificationTypeRepositoryInterface;
use dimichspb\yii\notificator\interfaces\NotificatorInterface;
use dimichspb\yii\notificator\models\Notification\Id;
use dimichspb\yii\notificator\models\NotificationQueue\NotificationQueue;
use dimichspb\yii\notificator\models\NotificationType\Id as NotificationTypeId;
use yii\base\Component;
use yii\base\Event;
use yii\data\DataProviderInterface;
use yii\db\ActiveRecord;
use yii\di\Container;
use yii\rbac\ManagerInterface;

class Notificator extends Component implements NotificatorInterface
{
    use EventTrait;

    const EVENT_BEFORE_RUN = 'beforeRun';

    const EVENT_AFTER_RUN = 'afterRun';

    /**
     * @var NotificationQueueAdapterInterface
     */
    public $adapter;

    /**
     * @var NotificationRepositoryInterface
     */
    public $notificationRepository;

    /**
     * @var NotificationTypeRepositoryInterface
     */
    public $notificationTypeRepository;

    /**
     * @var NotificationEventHandlerInterface
     */
    public $handler;

    public $limit = 10;

    public $channels = [
        'mail' => [
            'class' => MailChannel::class
        ],
    ];

    protected $container;

    public function __construct(Container $container, NotificationQueueAdapterInterface $adapter,
                                NotificationRepositoryInterface $notificationRepository,
                                NotificationTypeRepositoryInterface $notificationTypeRepository,
                                NotificationEventHandlerInterface $handler,
                                array $config = [])
    {
        $this->container = $container;
        $this->adapter = $adapter;
        $this->notificationRepository = $notificationRepository;
        $this->notificationTypeRepository = $notificationTypeRepository;
        $this->handler = $handler;

        parent::__construct($config);
    }

    public function add(NotificationInterface $notification)
    {
        return $this->adapter->add($notification);
    }

    public function get(Id $id)
    {
        return $this->adapter->get($id);
    }

    public function read(NotificationInterface $notification)
    {
        return $this->adapter->read($notification);
    }

    public function process($limit = null)
    {
        //$this->adapter->process($limit? $limit: $this->limit);
    }

    /**
     * @param array
     * @return DataProviderInterface
     */
    public function queue(array $params = [])
    {
        return $this->adapter->queue($params);
    }

    public function filterNotification(array $params = [])
    {
        return $this->notificationRepository->filter($params);
    }

    public function addType(NotificationTypeInterface $notificationType)
    {
        return $this->notificationTypeRepository->add($notificationType);
    }

    public function deleteType(NotificationTypeInterface $notificationType)
    {
        return $this->notificationTypeRepository->remove($notificationType);
    }

    public function filterNotificationType(array $params = [])
    {
        return $this->notificationTypeRepository->filter($params);
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

    public function update(NotificationInterface $notification)
    {
        return $this->notificationRepository->update($notification);
    }

    public function delete(NotificationInterface $notification)
    {
        return $this->notificationRepository->remove($notification);
    }

    public function activate(NotificationInterface $notification)
    {
        // TODO: Implement activate() method.
    }

    public function deactivate(NotificationInterface $notification)
    {
        // TODO: Implement deactivate() method.
    }

    public function getType(NotificationTypeId $id)
    {
        return $this->notificationTypeRepository->get($id);
    }

    public function updateType(NotificationTypeInterface $notificationType)
    {
        return $this->notificationTypeRepository->update($notificationType);
    }


}