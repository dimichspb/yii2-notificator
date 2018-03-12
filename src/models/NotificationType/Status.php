<?php
namespace dimichspb\yii\notificator\models\NotificationType;

use Assert\Assertion;

class Status
{
    const STATUS_ACTIVE = 'active';
    const STATUS_ARCHIVE = 'archive';
    const STATUS_DELETED = 'deleted';

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
            self::STATUS_ACTIVE,
            self::STATUS_ARCHIVE,
            self::STATUS_DELETED,
        ];
    }

}