<?php
namespace dimichspb\yii\notificator\types;

use dimichspb\yii\notificator\interfaces\NotificationTypeClassInterface;
use yii\base\View;

abstract class BaseNotificationTypeClass implements NotificationTypeClassInterface
{
    protected $_view;

    public function getClass()
    {
        return static::class;
    }

    public function render($view, $params = [])
    {
        return $this->getView()->render($view, $params, $this);
    }

    /**
     * Returns the view object that can be used to render views or view files.
     * The [[render()]], [[renderPartial()]] and [[renderFile()]] methods will use
     * this view object to implement the actual view rendering.
     * If not set, it will default to the "view" application component.
     * @return View|\yii\web\View the view object that can be used to render views or view files.
     */
    public function getView()
    {
        if ($this->_view === null) {
            $this->_view = \Yii::$app->getView();
        }

        return $this->_view;
    }


}