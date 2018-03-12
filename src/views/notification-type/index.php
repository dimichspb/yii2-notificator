<?php

use dimichspb\yii\notificator\models\Notification\Notification;
use dimichspb\yii\notificator\models\NotificationType\NotificationType;
use yii\grid\GridView;
use yii\helpers\Html;

/** @var $this \yii\web\View */
/** @var $dataProvider \yii\data\ActiveDataProvider */
/** @var $searchModel \dimichspb\yii\notificator\models\NotificationType\search\NotificationTypeSearch */

$this->title = \Yii::t('notificator', 'Notification Types');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="blog-index">
    <div class="box">
        <div class="box-header">
            <p><?= Html::a(\Yii::t('notificator', 'Create Notification Type'), ['create'], ['class' => 'btn btn-success']) ?></p>
        </div>
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'options' => ['class' => 'table-responsive'],
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'id',
                    'created_at:datetime',
                    'notification_type_class',
                    'events',
                    'params',
                    'statuses',
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{view} {delete}'
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>

