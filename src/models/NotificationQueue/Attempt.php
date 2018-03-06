<?php
namespace dimichspb\yii\notificator\models\NotificationQueue;

use Assert\Assertion;
use dimichspb\yii\notificator\models\NotificationQueue\ChangedAt;

class Attempt
{
    const ATTEMPT_NEW = 'new';
    const ATTEMPT_PROCESS = 'process';
    const ATTEMPT_ERROR = 'error';
    const ATTEMPT_DONE = 'done';

    private $value;
    private $result;
    private $created_at;
    private $changed_at;

    public function __construct($value, CreatedAt $createdAt = null)
    {
        Assertion::inArray($value, $this->getAvailableValues());

        $this->value = $value;
        $this->created_at = $createdAt?: new CreatedAt();
    }

    public function getValue()
    {
        return $this->value;
    }

    protected function setValue($value)
    {
        Assertion::inArray($value, $this->getAvailableValues());

        $this->value = $value;
        $this->change();

        return $this;
    }

    public function getResult()
    {
        return $this->result;
    }

    protected function setResult($result)
    {
        $this->result = $result;

        return $this;
    }

    public function isNew()
    {
        return $this->getValue() === Attempt::ATTEMPT_NEW;
    }

    public function process()
    {
        Assertion::true($this->isNew());

        $this->setValue(Attempt::ATTEMPT_PROCESS);

        return $this;
    }

    public function isInProcess()
    {
        return $this->getValue() === Attempt::ATTEMPT_PROCESS;
    }

    public function error($result = null)
    {
        Assertion::true($this->isInProcess());

        $this->setResult($result);
        $this->setValue(Attempt::ATTEMPT_ERROR);

        return $this;
    }

    public function done($result = null)
    {
        Assertion::true($this->isInProcess());

        $this->setResult($result);
        $this->setValue(Attempt::ATTEMPT_DONE);

        return $this;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function getChangedAt()
    {
        return $this->changed_at;
    }

    protected function change()
    {
        $this->changed_at = new ChangedAt();

        return $this;
    }

    public function isEqualTo(Status $that)
    {
        return $this->getValue() === $that->getValue();
    }

    protected function getAvailableValues()
    {
        return [
            self::ATTEMPT_NEW,
            self::ATTEMPT_PROCESS,
            self::ATTEMPT_ERROR,
            self::ATTEMPT_DONE
        ];
    }
}