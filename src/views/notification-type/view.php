<?php
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model \dimichspb\yii\notificator\models\NotificationType\NotificationType */

$this->title = \Yii::t('notificator', 'Notification Type') . ' - ' . $model->getId()->getValue();
$this->params['breadcrumbs'][] = ['label' => \Yii::t('notificator', 'Notifications'), 'url' => ['notification/index']];
$this->params['breadcrumbs'][] = ['label' => \Yii::t('notificator', 'Notification Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="notification-type-view">
    <div class="box">
        <div class="box-body">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'created_at:datetime',
                    'notification_type_class',
                    'name',
                    'description',
                    [
                        'attribute' => 'events',
                        'value' => \yii\helpers\Json::encode($model->getEvents(), true),
                    ],
                    [
                        'attribute' => 'params',
                        'value' => \yii\helpers\Json::encode($model->getParams(), true),
                    ],
                    [
                        'attribute' => 'status',
                        'value' => $model->getStatus()->getValue(),
                    ],
                ],
            ]) ?>
        </div>
    </div>
</div>
