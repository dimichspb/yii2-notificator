<?php
namespace dimichspb\yii\notificator\interfaces;

use dimichspb\yii\mailqueue\models\MailQueue\search\NotificationQueueSearch;
use yii\data\DataProviderInterface;

interface NotificatorInterface
{
    public function add(NotificationInterface $notification);

    public function getChannel($channelClass);

    public function get($userId, $limit = null);

    public function read(NotificationInterface $notification);

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
    public function filter(array $params = []);
}