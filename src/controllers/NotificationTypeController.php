<?php
namespace dimichspb\yii\notificator\controllers;


use dimichspb\yii\notificator\forms\NotificationType\NotificationTypeCreateForm;
use dimichspb\yii\notificator\forms\NotificationType\NotificationTypeSearchForm;
use dimichspb\yii\notificator\interfaces\NotificatorInterface;
use yii\base\Module;
use yii\filters\VerbFilter;
use yii\web\Controller;

class NotificationTypeController extends Controller
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
        $searchFormModel = new NotificationTypeSearchForm();
        $dataProvider = $this->notificator->filterNotificationType(\Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchFormModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
        $model = new NotificationTypeCreateForm();

        if ($model->load(\Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);

        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

}