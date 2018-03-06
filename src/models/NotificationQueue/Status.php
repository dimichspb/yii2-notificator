<?php
namespace dimichspb\yii\notificator\models\NotificationQueue;

use Assert\Assertion;

class Status
{
    const STATUS_NEW = 'new';
    const STATUS_PROCESS = 'process';
    const STATUS_ERROR = 'error';
    const STATUS_DONE = 'done';

    private $value;
    private $created_at;

    public function __construct($value, CreatedAt $createdAt = null)
    {
        Assertion::inArray($value, self::getAvailableStatuses());

        $this->value = $value;
        $this->created_at = $createdAt?: new CreatedAt();
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function isEqualTo(Status $that)
    {
        return $this->getValue() === $that->getValue();
    }

    public static function getAvailableStatuses()
    {
        return [
            self::STATUS_NEW,
            self::STATUS_PROCESS,
            self::STATUS_ERROR,
            self::STATUS_DONE
        ];
    }

}