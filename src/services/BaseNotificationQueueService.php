<?php
namespace dimichspb\yii\notificator\services;

use dimichspb\yii\notificator\interfaces\DispatcherInterface;
use dimichspb\yii\notificator\interfaces\NotificationQueueRepositoryInterface;
use dimichspb\yii\notificator\interfaces\NotificationQueueServiceInterface;
use yii\base\BaseObject;

abstract class BaseNotificationQueueService extends BaseObject implements NotificationQueueServiceInterface
{
    /**
     * @var NotificationQueueRepositoryInterface
     */
    protected $repository;

    /**
     * @var DispatcherInterface
     */
    protected $dispatcher;

    public function __construct(NotificationQueueRepositoryInterface $repository, DispatcherInterface $dispatcher, array $config = [])
    {
        $this->repository = $repository;
        $this->dispatcher = $dispatcher;

        parent::__construct($config);
    }
}