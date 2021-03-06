<?php
namespace dimichspb\yii\notificator\controllers;

use dimichspb\yii\notificator\exceptions\NotificationNotFoundException;
use dimichspb\yii\notificator\forms\Notification\NotificationCreateForm;
use dimichspb\yii\notificator\forms\Notification\NotificationSearchForm;
use dimichspb\yii\notificator\interfaces\NotificatorInterface;
use dimichspb\yii\notificator\interfaces\RoleServiceInterface;
use dimichspb\yii\notificator\interfaces\TypeServiceInterface;
use dimichspb\yii\notificator\interfaces\UserServiceInterface;
use dimichspb\yii\notificator\models\Notification\Id;
use yii\base\Module;
use yii\filters\VerbFilter;
use yii\rbac\ManagerInterface;
use yii\web\Controller;

class NotificationController extends Controller
{
    /**
     * @var NotificatorInterface
     */
    protected $notificator;

    /**
     * @var UserServiceInterface
     */
    protected $userService;

    /**
     * @var RoleServiceInterface
     */
    protected $roleService;

    public function __construct(
        $id,
        Module $module,
        NotificatorInterface $notificator,
        array $config = []
    ) {
        $this->notificator = $notificator;
        $this->userService = $this->notificator->getUserService();
        $this->roleService = $this->notificator->getRoleService();

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
        $dataProvider = $this->notificator->notifications(\Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchFormModel,
            'dataProvider' => $dataProvider,
            'notificator' => $this->notificator,
        ]);
    }

    public function actionCreate()
    {
        $model = new NotificationCreateForm(
            $this->getAvailableUsers(),
            $this->getAvailableRoles(),
            $this->getAvailableTypes(),
            $this->getAvailableChannels()
        );

        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            $notification = $this->saveNotification($model);

            return $this->redirect(['view', 'id' => $notification->getId()]);

        }

        return $this->render('create', [
            'model' => $model,
            'notificator' => $this->notificator,
        ]);
    }

    public function actionView($id)
    {
        $model = $this->findModel($id);

        return $this->render('view', [
            'model' => $model,
            'notificator' => $this->notificator,
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
        if (!$model = $this->notificator->getNotification(new Id($id))) {
            throw new NotificationNotFoundException();
        }

        return $model;
    }

    protected function getAvailableUsers()
    {
        return $this->userService->findAll();
    }

    protected function getAvailableRoles()
    {
        return $this->roleService->findAll();
    }

    protected function getAvailableTypes()
    {
        return $this->notificator->types()->getModels();
    }

    protected function getAvailableChannels()
    {
        return $this->notificator->getChannels();
    }

    protected function saveNotification(NotificationCreateForm $createForm)
    {
        $notification = $this->notificator->createNotification($createForm);
        $this->notificator->addNotification($notification);

        return $notification;
    }
}