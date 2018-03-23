<?php
namespace dimichspb\yii\notificator\services;

use dimichspb\yii\notificator\interfaces\DispatcherInterface;
use dimichspb\yii\notificator\interfaces\NotificationRepositoryInterface;
use dimichspb\yii\notificator\interfaces\NotificationServiceInterface;
use dimichspb\yii\notificator\interfaces\UserServiceInterface;
use yii\base\BaseObject;

abstract class BaseNotificationService extends BaseObject implements NotificationServiceInterface
{
    /**
     * @var NotificationRepositoryInterface
     */
    protected $repository;

    /**
     * @var DispatcherInterface
     */
    protected $dispatcher;

    /**
     * @var UserServiceInterface
     */
    protected $userService;

    public function __construct(
        NotificationRepositoryInterface $repository,
        DispatcherInterface $dispatcher,
        UserServiceInterface $userService,
        array $config = [])
    {
        $this->repository = $repository;
        $this->dispatcher = $dispatcher;
        $this->userService = $userService;

        parent::__construct($config);
    }
}