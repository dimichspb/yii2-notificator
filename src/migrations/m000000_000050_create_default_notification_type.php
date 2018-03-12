<?php

use dimichspb\yii\notificator\models\NotificationType\Event;
use dimichspb\yii\notificator\models\NotificationType\NotificationType;
use yii\db\Migration;

/**
 * Handles the creation of table `notification_queue`.
 */
class m000000_000050_create_default_notification_type extends Migration
{
    /**
     * @var \dimichspb\yii\notificator\interfaces\NotificatorInterface
     */
    protected $notificator;

    public function __construct(\dimichspb\yii\notificator\interfaces\NotificatorInterface $notificator, array $config = [])
    {
        $this->notificator = $notificator;

        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function up()
    {
        $notificationType = new NotificationType(new \dimichspb\yii\notificator\types\BeforeRequestNotificationTypeClass());

        $this->notificator->addType($notificationType);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $notificationType = new NotificationType(new \dimichspb\yii\notificator\types\BeforeRequestNotificationTypeClass());

        $this->notificator->deleteType($notificationType);
    }
}
