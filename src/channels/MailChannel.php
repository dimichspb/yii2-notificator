<?php
namespace dimichspb\yii\notificator\channels;

use dimichspb\yii\notificator\interfaces\MessageInterface;
use dimichspb\yii\notificator\interfaces\UserInterface;
use dimichspb\yii\notificator\models\Message;
use yii\mail\MailerInterface;
use yii\web\IdentityInterface;

class MailChannel extends BaseChannel
{
    /** @var MailerInterface  */
    protected $mailer;

    public $viewPath = __DIR__ . '/views/mail';

    public $view = 'notification';

    public $layouts = [
        'html' => 'html',
        'text' => 'text',
    ];

    public function __construct(array $config = [])
    {
        if (!isset($config['mailer'])) {
            $this->mailer = \Yii::$app->mailer;
        }

        parent::__construct($config);
    }

    public function send(Message $message, UserInterface $to, UserInterface $from)
    {
        //$view = $this->preparePath($this->viewPath, $this->view);

        $result = $this
            ->mailer
            ->compose($this->view, [
                'message' => $message,
            ])
            ->setTo([$to->getEmail() => $to->getUsername()])
            ->setFrom([$from->getEmail() => $from->getUsername()])
            ->send();

        if (!$result) {
            $this->errors[] = 'Message was not sent';
        }

        return $result;
    }

    protected function preparePath($path, $file)
    {
        return rtrim($path, "\t\n\r\0\x0B\\\/") . DIRECTORY_SEPARATOR . ltrim($file, "\t\n\r\0\x0B\\\/");
    }
}