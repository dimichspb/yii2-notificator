<?php
namespace dimichspb\yii\notificator;

use dimichspb\yii\notificator\dispatchers\NotificationQueueDispatcher;
use dimichspb\yii\notificator\interfaces\DispatcherInterface;
use dimichspb\yii\notificator\interfaces\NotificationQueueServiceInterface;
use dimichspb\yii\notificator\interfaces\NotificationServiceInterface;
use dimichspb\yii\notificator\interfaces\NotificationTypeServiceInterface;
use dimichspb\yii\notificator\interfaces\RoleServiceInterface;
use dimichspb\yii\notificator\interfaces\UserServiceInterface;
use dimichspb\yii\notificator\repositories\ActiveRecordNotificationQueueRepository;
use dimichspb\yii\notificator\handlers\BasicNotificationEventHandler;
use dimichspb\yii\notificator\interfaces\NotificationEventHandlerInterface;
use dimichspb\yii\notificator\interfaces\NotificationQueueRepositoryInterface;
use dimichspb\yii\notificator\interfaces\NotificationRepositoryInterface;
use dimichspb\yii\notificator\interfaces\NotificationTypeRepositoryInterface;
use dimichspb\yii\notificator\interfaces\NotificatorInterface;
use dimichspb\yii\notificator\repositories\ActiveRecordNotificationRepository;
use dimichspb\yii\notificator\repositories\ActiveRecordNotificationTypeRepository;
use dimichspb\yii\notificator\services\ActiveRecordUserService;
use dimichspb\yii\notificator\services\AuthManagerRoleService;
use dimichspb\yii\notificator\services\EmptyUserService;
use dimichspb\yii\notificator\services\NotificationQueueService;
use dimichspb\yii\notificator\services\NotificationService;
use dimichspb\yii\notificator\services\NotificationTypeService;
use yii\base\Event;
use yii\base\InvalidConfigException;
use yii\di\Container;
use yii\queue\ErrorEvent;
use yii\queue\Queue;
use yii\web\Application as WebApplication;
use yii\console\Application as ConsoleApplication;

class Bootstrap implements \yii\base\BootstrapInterface
{
    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        /** @var Container $container */
        $container = \Yii::$container;

        $container->setDefinitions([
            NotificatorInterface::class => Notificator::class,
            NotificationServiceInterface::class => NotificationService::class,
            NotificationQueueServiceInterface::class => NotificationQueueService::class,
            NotificationTypeServiceInterface::class => NotificationTypeService::class,
            NotificationQueueRepositoryInterface::class => ActiveRecordNotificationQueueRepository::class,
            NotificationRepositoryInterface::class => ActiveRecordNotificationRepository::class,
            NotificationTypeRepositoryInterface::class => ActiveRecordNotificationTypeRepository::class,
            NotificationEventHandlerInterface::class => BasicNotificationEventHandler::class,
            DispatcherInterface::class => NotificationQueueDispatcher::class,
            RoleServiceInterface::class => AuthManagerRoleService::class,
        ]);

        $notificator = $container->get(NotificatorInterface::class);

        Event::on('*', '*', [$notificator, 'handle']);

        if ($app instanceof WebApplication) {
            $this->initUrlRoutes($app);
        }
        if ($app instanceof ConsoleApplication) {
           $app->controllerMap['notificator'] = 'dimichspb\yii\notificator\commands\NotificationQueueController';
           $app->controllerMap['migrate']['migrationPath'][] = '@vendor/dimichspb/yii2-notificator/src/migrations';
        }

        \Yii::$app->queue->on(Queue::EVENT_AFTER_ERROR, function (ErrorEvent $event) {
            var_dump($event);
        });
    }

    /**
     * Initializes web url routes (rules in Yii2).
     *
     * @param WebApplication $app
     *
     * @throws InvalidConfigException
     */
    protected function initUrlRoutes(WebApplication $app)
    {
        /** @var $module Module */
        $module = $app->getModule('notificator');
        $config = [
            'class' => 'yii\web\GroupUrlRule',
            'prefix' => 'notifications',
            'routePrefix' => 'notificator',
            'rules' => $module->routes,
        ];

        $rule = \Yii::createObject($config);

        $app->getUrlManager()->addRules([$rule], false);
    }
}