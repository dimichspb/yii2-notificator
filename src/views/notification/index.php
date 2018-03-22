<?php

use dimichspb\yii\notificator\interfaces\NotificationInterface;
use dimichspb\yii\notificator\models\Notification\Notification;
use yii\grid\GridView;
use yii\helpers\Html;

/** @var $this \yii\web\View */
/** @var $dataProvider \yii\data\ActiveDataProvider */
/** @var $searchModel \dimichspb\yii\notificator\models\Notification\search\NotificationSearch */
/** @var $notificator \dimichspb\yii\notificator\interfaces\NotificatorInterface */


$this->title = \Yii::t('notificator', 'Notifications');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="notification-index">
    <div class="box">
        <div class="box-header">
            <p>
                <?= Html::a(\Yii::t('notificator', 'Create Notification'), ['create'], ['class' => 'btn btn-success']) ?>
                <?= Html::a(\Yii::t('notificator', 'Notification Queue'), ['notification-type/index'], ['class' => 'btn btn-default']) ?>
                <?= Html::a(\Yii::t('notificator', 'Notification Types'), ['notification-queue/index'], ['class' => 'btn btn-default']) ?>
            </p>
        </div>
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'options' => ['class' => 'table-responsive'],
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    //'id',
                    'created_at:datetime',
                    [
                        'attribute' => 'notification_type_id',
                        'value' => function (NotificationInterface $model) use ($notificator) {
                            $notificationType = $notificator->getType($model->getNotificationTypeId());
                            return Html::a($notificationType->getName(), ['notification-type/view', 'id' => $notificationType->getId()->getValue()]);
                        },
                        'format' => 'raw',
                    ],
                    [
                        'attribute' => 'channel_class',
                        'value' => function (NotificationInterface $model) use ($notificator) {
                            return $notificator->getChannelName($model->getChannelClass()->getValue());
                        },
                    ],
                    'user_ids',
                    'role_names',
                    'ignored_user_ids',
                    'ignored_role_names',
                    [
                        'attribute' => 'status',
                        'value' => function (Notification $model) {
                            return $model->getLastStatus()->getValue();
                        },
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{view} {delete}'
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>

