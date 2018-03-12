<?php
namespace dimichspb\yii\notificator\controllers;


use dimichspb\yii\notificator\exceptions\NotificationTypeNotFoundException;
use dimichspb\yii\notificator\forms\NotificationType\NotificationTypeCreateForm;
use dimichspb\yii\notificator\forms\NotificationType\NotificationTypeSearchForm;
use dimichspb\yii\notificator\interfaces\NotificatorInterface;
use dimichspb\yii\notificator\models\NotificationType\Id;
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

    public function actionIndex()
    {
        $searchFormModel = new NotificationTypeSearchForm();
        $dataProvider = $this->notificator->types(\Yii::$app->request->queryParams);

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
     * @return \dimichspb\yii\notificator\interfaces\NotificationTypeInterface|null
     * @throws NotificationTypeNotFoundException
     * @throws \Assert\AssertionFailedException
     */
    protected function findModel($id)
    {
        if (!$model = $this->notificator->getType(new Id($id))) {
            throw new NotificationTypeNotFoundException();
        }

        return $model;
    }
}