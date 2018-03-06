<?php
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model \dimichspb\yii\notificator\models\Notification\Notification */

$this->title = \Yii::t('notificator', 'Notification') . ' - ' . $model->getId()->getValue();
$this->params['breadcrumbs'][] = ['label' => \Yii::t('notificator', 'Notifications'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="event-view">
    <div class="box">
        <div class="box-body">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'id',
                    'user_id',
                    'created_at:datetime',
                    [
                        'attribute' => 'message',
                        'value' => $model->getMessage(),
                    ],
                    'attempts',
                    'statuses',
                    'sent_at:datetime',
                ],
            ]) ?>
        </div>
    </div>
</div>
