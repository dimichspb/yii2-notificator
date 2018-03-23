<?php
namespace dimichspb\yii\notificator\services;

use dimichspb\yii\notificator\interfaces\DispatcherInterface;
use dimichspb\yii\notificator\interfaces\NotificationTypeRepositoryInterface;
use dimichspb\yii\notificator\interfaces\NotificationTypeServiceInterface;
use yii\base\BaseObject;

abstract class BaseNotificationTypeService extends BaseObject implements NotificationTypeServiceInterface
{
    /**
     * @var NotificationTypeRepositoryInterface
     */
    protected $repository;

    /**
     * @var DispatcherInterface
     */
    protected $dispatcher;

    public function __construct(NotificationTypeRepositoryInterface $repository, DispatcherInterface $dispatcher, array $config = [])
    {
        $this->repository = $repository;
        $this->dispatcher = $dispatcher;

        parent::__construct($config);
    }
}