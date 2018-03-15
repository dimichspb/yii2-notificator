<?php
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model \dimichspb\yii\notificator\models\Notification\Notification */
/* @var \dimichspb\yii\notificator\interfaces\NotificatorInterface $notificator */

$notificationType = $notificator->getType($model->getNotificationTypeId());

$this->title = \Yii::t('notificator', 'Notification') . ' - ' . $model->getId()->getValue();
$this->params['breadcrumbs'][] = ['label' => \Yii::t('notificator', 'Notifications'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="notification-view">
    <div class="box">
        <div class="box-body">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'created_at:datetime',
                    [
                        'attribute' => 'notification_type_id',
                        'value' => Html::a($notificationType->getName(), ['notification-type/view', 'id' => $notificationType->getId()->getValue()]),
                        'format' => 'raw',
                    ],
                    [
                        'attribute' => 'channel_class',
                        'value' => $notificator->getChannelName($model->getChannelClass()->getValue()),
                    ],
                    'user_ids',
                    'role_names',
                    'ignored_user_ids',
                    'ignored_role_names',
                    [
                        'attribute' => 'status',
                        'value' => $model->getLastStatus()->getValue(),
                    ],
                ],
            ]) ?>
        </div>
    </div>
</div>
