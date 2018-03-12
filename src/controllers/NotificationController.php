<?php
namespace dimichspb\yii\notificator\controllers;

use dimichspb\yii\notificator\exceptions\NotificationNotFoundException;
use dimichspb\yii\notificator\forms\Notification\NotificationCreateForm;
use dimichspb\yii\notificator\forms\Notification\NotificationSearchForm;
use dimichspb\yii\notificator\interfaces\NotificatorInterface;
use dimichspb\yii\notificator\models\Notification\Id;
use yii\base\Module;
use yii\filters\VerbFilter;
use yii\web\Controller;

class NotificationController extends Controller
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
        $searchFormModel = new NotificationSearchForm();
        $dataProvider = $this->notificator->filterNotification(\Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchFormModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
        $model = new NotificationCreateForm();

        if ($model->load(\Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);

        }

        return $this->render('create', [
            'model' => $model,
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
     * @return \dimichspb\yii\notificator\interfaces\NotificationInterface|null
     * @throws NotificationNotFoundException
     * @throws \Assert\AssertionFailedException
     */
    protected function findModel($id)
    {
        if (!$model = $this->notificator->get(new Id($id))) {
            throw new NotificationNotFoundException();
        }

        return $model;
    }

}