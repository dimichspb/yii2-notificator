<?php
namespace dimichspb\yii\mailqueue\commands;

use dimichspb\yii\mailqueue\models\MailQueue\search\NotificationQueueSearch;
use dimichspb\yii\notificator\interfaces\NotificatorInterface;
use dimichspb\yii\notificator\Module;
use yii\console\Controller;

class NotificationQueueController extends Controller
{
    /**
     * @var NotificatorInterface
     */
    protected $notificator;

    public function __construct($id, Module $module, NotificatorInterface $notificator, array $config = [])
    {
        $this->notificator = $notificator;

        parent::__construct($id, $module, $config);
    }

    public function actionIndex()
    {
        $dataProvider = $this->notificator->queue();

        $this->stdout($dataProvider->getTotalCount());
    }
}