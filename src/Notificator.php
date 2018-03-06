<?php
namespace dimichspb\yii\notificator;

use dimichspb\yii\mailqueue\models\MailQueue\search\NotificationQueueSearch;
use dimichspb\yii\notificator\channels\MailChannel;
use dimichspb\yii\notificator\interfaces\NotificationInterface;
use dimichspb\yii\notificator\interfaces\NotificationQueueAdapterInterface;
use dimichspb\yii\notificator\interfaces\NotificationRepositoryInterface;
use dimichspb\yii\notificator\interfaces\NotificatorInterface;
use dimichspb\yii\notificator\models\NotificationQueue\NotificationQueue;
use yii\base\Component;
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
    public $repository;

    public $limit = 10;

    public $channels = [
        'mail' => [
            'class' => MailChannel::class
        ],
    ];

    protected $container;

    public function __construct(Container $container, NotificationQueueAdapterInterface $adapter,
                                NotificationRepositoryInterface $repository, array $config = [])
    {
        $this->container = $container;
        $this->adapter = $adapter;
        $this->repository = $repository;

        parent::__construct($config);
    }

    public function add(NotificationInterface $notification)
    {
        $this->adapter->add($notification);
    }

    public function get($userId, $limit = null)
    {
        return $this->adapter->get($userId, $limit? $limit: $this->limit);
    }

    public function read(NotificationInterface $notification)
    {
        $this->adapter->read($notification);
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

    public function filter(array $params = [])
    {
        return $this->repository->filter($params);
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
}