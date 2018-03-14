<?php
namespace dimichspb\yii\notificator\channels;

use dimichspb\yii\notificator\interfaces\MessageInterface;
use yii\mail\MailerInterface;

class MailChannel extends BaseChannel
{
    protected $name = 'E-mail channel';

    /** @var MailerInterface  */
    protected $mailer;

    public $viewPath = __DIR__ . '/views/mail';

    public $view = 'notification';

    public $layouts = [
        'html' => 'html',
        'text' => 'text',
    ];

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function send(MessageInterface $message)
    {
        $view = $this->preparePath($this->viewPath, $this->view);

        $this->mailer->compose($view, [
            'message' => $message,
        ])->send();
    }

    protected function preparePath($path, $file)
    {
        return rtrim($path, "\t\n\r\0\x0B\\\/") . DIRECTORY_SEPARATOR . ltrim($file, "\t\n\r\0\x0B\\\/");
    }
}