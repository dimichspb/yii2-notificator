<?php

use dimichspb\yii\notificator\models\NotificationQueue\NotificationQueue;
use yii\grid\GridView;
use yii\helpers\Html;

/** @var $this \yii\web\View */
/** @var $dataProvider \yii\data\ActiveDataProvider */
/** @var $searchModel \dimichspb\yii\mailqueue\models\MailQueue\search\NotificationQueueSearch */

$this->title = \Yii::t('notificator', 'Notification Queue');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="blog-index">
    <div class="box">
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'options' => ['class' => 'table-responsive'],
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'id',
                    'created_at:datetime',
                    'send_at:datetime',
                    [
                        'attribute' => 'message',
                        'value' => function (NotificationQueue $model) {
                            return $model->getMessage();
                        },
                    ],
                    'attempts',
                    'statuses',
                    'sent_at:datetime',
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{view} {delete}'
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>

