<?php
namespace dimichspb\yii\notificator\interfaces;

use yii\data\DataProviderInterface;

interface NotificationRepositoryInterface
{
    /**
     * @param array $params
     * @return DataProviderInterface
     */
    public function filter(array $params = []);
}