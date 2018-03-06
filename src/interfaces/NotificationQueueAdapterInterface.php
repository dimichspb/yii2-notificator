<?php
namespace dimichspb\yii\notificator\interfaces;

use dimichspb\yii\mailqueue\models\MailQueue\search\NotificationQueueSearch;
use yii\data\DataProviderInterface;

interface NotificationQueueAdapterInterface
{
    public function add(NotificationInterface $notification);

    public function get($user_id, $limit);

    public function read(NotificationInterface $notification);

    /**
     * @param array $params
     * @return DataProviderInterface
     */
    public function queue(array $params = []);
}