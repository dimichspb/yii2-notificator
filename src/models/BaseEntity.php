<?php
namespace dimichspb\yii\notificator\models;

use dimichspb\yii\notificator\interfaces\EntityInterface;
use dimichspb\yii\notificator\models\EventTrait;
use yii\db\ActiveRecord;

abstract class BaseEntity extends ActiveRecord implements EntityInterface
{
    use EventTrait, InstantiateTrait;
}