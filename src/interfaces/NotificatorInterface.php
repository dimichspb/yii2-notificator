<?php
namespace dimichspb\yii\notificator\interfaces;

use dimichspb\yii\notificator\forms\Notification\NotificationCreateForm;
use dimichspb\yii\notificator\models\NotificationType\Id;
use dimichspb\yii\notificator\models\UserId;
use yii\base\Event;
use yii\data\DataProviderInterface;
use dimichspb\yii\notificator\models\Notification\Id as NotificationId;
use dimichspb\yii\notificator\models\NotificationType\Id as NotificationTypeId;
use dimichspb\yii\notificator\models\NotificationQueue\Id as NotificationQueueId;

interface NotificatorInterface
{
    /**
     * @param array $params
     * @return DataProviderInterface
     */
    public function notifications(array $params = []);
    /**
     * @param NotificationInterface $notification
     * @return mixed
     */
    public function addNotification(NotificationInterface $notification);

    /**
     * @param NotificationCreateForm $notificationCreateForm
     * @return NotificationInterface
     */
    public function createNotification(NotificationCreateForm $notificationCreateForm);
    /**
     * @param NotificationId $id
     * @return NotificationInterface
     */
    public function getNotification(NotificationId $id);

    /**
     * @param NotificationInterface $notification
     * @return mixed
     */
    public function updateNotification(NotificationInterface $notification);

    /**
     * @param NotificationInterface $notification
     * @return mixed
     */
    public function deleteNotification(NotificationInterface $notification);

    /**
     * @param NotificationInterface $notification
     * @return mixed
     */
    public function activateNotification(NotificationInterface $notification);

    /**
     * @param NotificationInterface $notification
     * @return mixed
     */
    public function deactivateNotification(NotificationInterface $notification);

    /**
     * @param array
     * @return DataProviderInterface
     */
    public function queue(array $params = []);

    /**
     * @param NotificationQueueId $id
     * @return NotificationQueueInterface
     */
    public function getQueue(NotificationQueueId $id);

    /**
     * @param NotificationQueueInterface $notificationQueue
     * @return mixed
     */
    public function addQueue(NotificationQueueInterface $notificationQueue);

    /**
     * @param NotificationQueueInterface $notificationQueue
     * @return mixed
     */
    public function updateQueue(NotificationQueueInterface $notificationQueue);

    /**
     * @param NotificationQueueInterface $notificationQueue
     * @return mixed
     */
    public function deleteQueue(NotificationQueueInterface $notificationQueue);

    /**
     * @param NotificationQueueInterface $notification
     * @return mixed
     */
    public function read(NotificationQueueInterface $notification);

    /**
     * @param array $params
     * @return DataProviderInterface
     */
    public function types(array $params = []);
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


    public function getChannels(array $params = []);

    /**
     * @param $channelClassName
     * @return ChannelInterface
     */
    public function getChannel($channelClassName);

    public function getChannelName($channelClassName);

    public function handle(Event $event);

    /**
     * @return UserServiceInterface
     */
    public function getUserService();

    /**
     * @param UserId $userId
     * @return UserInterface
     */
    public function getUser(UserId $userId);

    public function getFrom();

    /**
     * @return RoleServiceInterface
     */
    public function getRoleService();
}