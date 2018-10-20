<?php
namespace kuaukutsu\console\task\events;

use yii\base\Action;
use yii\base\Event;
use yii\console\Controller;
use yii\helpers\Inflector;

/**
 * Class TaskEvent
 * @package kuaukutsu\console\task
 */
class TaskEvent extends Event
{
    /**
     * @var Action
     */
    public $action;

    /**
     * @var Controller
     */
    public $controller;

    /**
     * @return string
     */
    public function __toString(): string
    {
        return sprintf('%s[%s]',
            Inflector::id2camel($this->controller->getUniqueId()),
            $this->action->id
        );
    }
}