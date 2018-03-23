<?php
namespace dimichspb\yii\notificator\models\NotificationQueue;

use Assert\Assertion;
use dimichspb\yii\notificator\models\DateTime;

class CreatedAt extends DateTime
{
    public function __construct($datetime = null)
    {
        $datetime = $datetime?: date($this->format);

        parent::__construct($datetime);
    }
}