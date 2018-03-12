<?php
namespace dimichspb\yii\notificator;

use yii\console\Application as ConsoleApplication;

class Module extends \yii\base\Module
{
    public $prefix = 'notificator';

    public $routes = [
        '/' => 'notification/index',
        'create' => 'notification/create',
        'view' => 'notification/view',
        'update' => 'notification/update',
        'delete' => 'notification/delete',
        'activate' => 'notification/activate',
        'deactivate' => 'notification/deactivate',

        'types' => 'notification-type/index',
        'types/view' => 'notification-type/view',

        'queue' => 'notification-queue/index',
        'queue/view' => 'notification-queue/view',
        'queue/delete' => 'notification-queue/delete',
    ];

    public function init()
    {
        parent::init();

        $this->registerTranslations();
    }

    public function registerTranslations()
    {
        \Yii::$app->i18n->translations['notificator*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath' => __DIR__ . '/messages',
            'fileMap' => [
                'notificator' => 'messages.php',
            ],
        ];
    }
}