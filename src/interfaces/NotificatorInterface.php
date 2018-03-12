<?php
namespace dimichspb\yii\notificator\interfaces;

use dimichspb\yii\notificator\models\NotificationType\Id;
use yii\base\Event;
use yii\data\DataProviderInterface;
use dimichspb\yii\notificator\models\Notification\Id as NotificationId;
use dimichspb\yii\notificator\models\NotificationType\Id as NotificationTypeId;
use dimichspb\yii\notificator\models\NotificationQueue\Id as NotificationQueueId;

interface NotificatorInterface
{
    /**
     * @param NotificationInterface $notification
     * @return mixed
     */
    public function add(NotificationInterface $notification);

    public function get(NotificationId $id);

    public function update(NotificationInterface $notification);

    public function delete(NotificationInterface $notification);

    public function read(NotificationInterface $notification);

    public function activate(NotificationInterface $notification);

    public function deactivate(NotificationInterface $notification);

    public function getChannel($channelClass);

    public function process($limit = null);

    /**
     * @param array
     * @return DataProviderInterface
     */
    public function queue(array $params = []);

    public function getQueue(NotificationQueueId $id);

    /**
     * @param array $params
     * @return DataProviderInterface
     */
    public function filterNotification(array $params = []);

    /**
     * @param NotificationTypeInterface $notificationType
     * @return mixed
     */
    public function addType(NotificationTypeInterface $notificationType);
    /**
     * @param Id $id
     * @return NotificationTypeInterface|null
     */
    public function getType(NotificationTypeId $id);

    /**
     * @param NotificationTypeInterface $notificationType
     * @return mixed
     */
    public function updateType(NotificationTypeInterface $notificationType);

    /**
     * @param NotificationTypeInterface $notificationType
     * @return mixed
     */
    public function deleteType(NotificationTypeInterface $notificationType);
    /**
     * @param array $params
     * @return DataProviderInterface
     */
    public function filterNotificationType(array $params = []);

    public function handle(Event $event);
}