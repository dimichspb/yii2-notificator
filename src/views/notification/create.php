<?php

/** @var $this \yii\web\View */
/** @var $model \dimichspb\yii\notificator\forms\Notification\NotificationCreateForm */

$this->title = \Yii::t('notificator', 'Notification Create');
$this->params['breadcrumbs'][] = ['label' => \Yii::t('notificator', 'Notifications'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="notification-create">
    <div class="box">
        <div class="box-body">
            <?= $this->render('_create-form', [
                'model' => $model,
            ]) ?>
        </div>
    </div>
</div>
