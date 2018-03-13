<?php

use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

/** @var $this \yii\web\View */
/** @var $model \dimichspb\yii\notificator\forms\Notification\NotificationCreateForm */

?>

<div class="notification-create-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'notification_type_id')->widget(Select2::class, [
        'data' => ArrayHelper::map($model->getAvailableTypes(), 'id', 'name'),
        'options' => ['placeholder' => 'Select notification type ...'],
        'pluginOptions' => [
            'allowClear' => false
        ],
    ]); ?>

    <?= $form->field($model, 'users')->widget(Select2::class, [
        'data' => ArrayHelper::map($model->getAvailableUsers(), 'id', 'username'),
        'options' => [
            'placeholder' => 'Select subscribed users ...',
            'multiple' => true
        ],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    <?= $form->field($model, 'roles')->widget(Select2::class, [
        'data' => ArrayHelper::map($model->getAvailableRoles(), 'name', 'description'),
        'options' => [
            'placeholder' => 'Select subscribed roles ...',
            'multiple' => true
        ],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    <?= $form->field($model, 'ignored_users')->widget(Select2::class, [
        'data' => ArrayHelper::map($model->getAvailableUsers(), 'id', 'username'),
        'options' => [
            'placeholder' => 'Select ignored users ...',
            'multiple' => true
        ],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    <?= $form->field($model, 'ignored_roles')->widget(Select2::class, [
        'data' => ArrayHelper::map($model->getAvailableRoles(), 'name', 'description'),
        'options' => [
            'placeholder' => 'Select ignored roles ...',
            'multiple' => true
        ],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    <div class="form-group">
        <?= \yii\helpers\Html::submitButton(\Yii::t('notificator', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

