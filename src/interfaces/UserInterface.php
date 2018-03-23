<?php
namespace dimichspb\yii\notificator\interfaces;

use dimichspb\yii\notificator\models\User\Email;
use dimichspb\yii\notificator\models\User\Username;

interface UserInterface
{
    /**
     * @return Username
     */
    public function getUsername();

    /**
     * @return Email
     */
    public function getEmail();
}