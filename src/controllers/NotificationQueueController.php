<?php
namespace dimichspb\yii\notificator\controllers;

use dimichspb\yii\mailqueue\models\MailQueue\search\NotificationQueueSearch;
use dimichspb\yii\notificator\exceptions\NotificationQueueNotFoundException;
use dimichspb\yii\notificator\forms\NotificationQueue\NotificationQueueSearchForm;
use dimichspb\yii\notificator\interfaces\NotificatorInterface;
use dimichspb\yii\notificator\models\NotificationQueue\Id;
use yii\base\Module;
use yii\filters\VerbFilter;
use yii\web\Controller;

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

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchFormModel = new NotificationQueueSearchForm();
        $dataProvider = $this->notificator->queue(\Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchFormModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        $model = $this->findModel($id);

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * @param $id
     * @return \dimichspb\yii\notificator\interfaces\NotificationQueueInterface
     * @throws NotificationQueueNotFoundException
     * @throws \Assert\AssertionFailedException
     */
    protected function findModel($id)
    {
        if (!$model = $this->notificator->getQueue(new Id($id))) {
            throw new NotificationQueueNotFoundException();
        }

        return $model;
    }
}