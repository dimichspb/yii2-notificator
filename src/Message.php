<?php
namespace dimichspb\yii\notificator;

use dimichspb\yii\notificator\interfaces\MessageInterface;

class Message implements MessageInterface
{
    protected $subject;
    protected $body;

    public function className()
    {
        return get_class($this);
    }

    public function serialize()
    {
        return json_encode($this);
    }

    public function unserialize($data)
    {
         $array = json_decode($data, true);

         foreach ($array as $name => $value) {
             $this->$name = $value;
         }

         return $this;
    }

    public function getSubject()
    {
        return $this->subject;
    }

    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }
}