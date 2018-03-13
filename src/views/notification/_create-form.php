<?php
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

/** @var $this \yii\web\View */
/** @var $model \dimichspb\yii\notificator\forms\Notification\NotificationCreateForm */

?>

<div class="notification-create-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'notification_type_id')->dropDownList(ArrayHelper::map($model->getAvailableTypes(), 'id', 'name'), ['prompt' => \Yii::t('notificator', 'Choose notification type...')]) ?>

    <?= $form->field($model, 'users')->dropDownList(ArrayHelper::map($model->getAvailableUsers(), 'id', 'name'), ['prompt' => \Yii::t('notificator', 'Choose subscribed users...')]) ?>

    <?= $form->field($model, 'roles')->dropDownList(ArrayHelper::map($model->getAvailableRoles(), 'id', 'name'), ['prompt' => \Yii::t('notificator', 'Choose subscribed roles...')]) ?>

    <?= $form->field($model, 'ignored_users')->dropDownList(ArrayHelper::map($model->getAvailableUsers(), 'id', 'name'), ['prompt' => \Yii::t('notificator', 'Choose ignored users...')]) ?>

    <?= $form->field($model, 'ignored_roles')->dropDownList(ArrayHelper::map($model->getAvailableRoles(), 'id', 'name'), ['prompt' => \Yii::t('notificator', 'Choose ignored roles...')]) ?>

    <div class="form-group">
        <?= \yii\helpers\Html::submitButton(\Yii::t('notificator', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

