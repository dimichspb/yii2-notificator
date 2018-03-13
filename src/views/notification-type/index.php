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
<div class="blog-index">
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
                    [
                        'attribute' => 'events',
                        'value' => function (\dimichspb\yii\notificator\interfaces\NotificationTypeInterface $notificationType) {
                            $events = [];
                            foreach ($notificationType->getEvents() as $event) {
                                $events[] = $event->getValue();
                            }
                            return \yii\helpers\Json::encode($events, true);
                        },
                    ],
                    [
                        'attribute' => 'params',
                        'value' => function (\dimichspb\yii\notificator\interfaces\NotificationTypeInterface $notificationType) {
                            return \yii\helpers\Json::encode($notificationType->getParams(), true);
                        },
                    ],
                    [
                        'attribute' => 'status',
                        'value' => function (\dimichspb\yii\notificator\interfaces\NotificationTypeInterface $notificationType) {
                            return $notificationType->getStatus()->getValue();
                        },
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{view}',
                        /*'urlCreator' => function ($action, \dimichspb\yii\notificator\interfaces\NotificationTypeInterface $model) {
                            $url = '';
                            switch ($action) {
                                case 'view':
                                    //$url = ['view', 'id' => $model->getId()->getValue()];
                                    $url = '123';
                                    break;
                                default:
                                    break;
                            };
                            return $url;
                        }*/
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>

