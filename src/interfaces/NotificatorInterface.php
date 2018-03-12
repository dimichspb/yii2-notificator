<?php
namespace dimichspb\yii\notificator\interfaces;

use dimichspb\yii\mailqueue\models\MailQueue\search\NotificationQueueSearch;
use yii\base\Event;
use yii\data\DataProviderInterface;

interface NotificatorInterface
{
    public function add(NotificationInterface $notification);

    public function get($userId, $limit = null);

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