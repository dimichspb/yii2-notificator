<?php

use dimichspb\yii\notificator\models\Notification\Notification;
use dimichspb\yii\notificator\models\NotificationType\NotificationType;
use yii\grid\GridView;
use yii\helpers\Html;

/** @var $this \yii\web\View */
/** @var $dataProvider \yii\data\ActiveDataProvider */
/** @var $searchModel \dimichspb\yii\notificator\models\NotificationType\search\NotificationTypeSearch */

$this->title = \Yii::t('notificator', 'Notification Types');
$this->params['breadcrumbs'][] = ['label' => \Yii::t('notificator', 'Notifications'), 'url' => ['notification/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="notification-type-index">
    <div class="box">
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'options' => ['class' => 'table-responsive'],
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'created_at:datetime',
                    'notification_type_class',
                    'name',
                    'description',
                    'event',
                    [
                        'attribute' => 'status',
                        'value' => function (\dimichspb\yii\notificator\interfaces\NotificationTypeInterface $notificationType) {
                            return $notificationType->getStatus()->getValue();
                        },
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{view}',
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>

