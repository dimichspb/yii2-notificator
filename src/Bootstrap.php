<?php
namespace dimichspb\yii\notificator;

use dimichspb\yii\notificator\interfaces\NotificatorInterface;
use yii\base\InvalidConfigException;
use yii\di\Container;
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

        $container->set(NotificatorInterface::class, Notificator::class);

        if ($app instanceof WebApplication) {
            $this->initUrlRoutes($app);
        }
        if ($app instanceof ConsoleApplication) {
           $app->controllerMap['notificator'] = 'dimichspb\yii\notificator\commands\NotificationQueueController';
        }
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
            'prefix' => $module->prefix,
            'rules' => $module->routes,
        ];

        if ($module->prefix !== 'notificator') {
            $config['routePrefix'] = 'notificator';
        }

        $rule = \Yii::createObject($config);
        $app->getUrlManager()->addRules([$rule], false);
    }
}