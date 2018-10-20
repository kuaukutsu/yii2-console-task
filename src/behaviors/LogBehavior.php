<?php
namespace kuaukutsu\console\task\behaviors;

use yii\base\ActionEvent;
use yii\base\Behavior;
use kuaukutsu\console\task\Task;
use kuaukutsu\console\task\events\TaskEvent;
use kuaukutsu\console\task\events\ErrorEvent;

/**
 * Class LogBehavior
 * @package kuaukutsu\console\task
 */
class LogBehavior extends Behavior
{
    /**
     * @var bool
     */
    public $debug = YII_ENV_DEV;

    /**
     * @var bool
     */
    public $autoFlush = true;

    /**
     * @inheritdoc
     */
    public function events()
    {
        return [
            Task::EVENT_BEFORE_ACTION   => 'beforeAction',
            Task::EVENT_AFTER_ACTION    => 'afterAction',
            Task::EVENT_BEFORE_RUN      => 'beforeRun',
            Task::EVENT_AFTER_RUN       => 'afterRun',
            Task::EVENT_BEFORE_ERROR    => 'beforeError',
            Task::EVENT_AFTER_ERROR     => 'afterError'
        ];
    }

    /**
     * @param ActionEvent $event
     */
    public function beforeAction(ActionEvent $event)
    {
        \Yii::info(sprintf('Begin %s', $event->action->getUniqueId()), Task::class);
        if ($this->autoFlush) {
            \Yii::getLogger()->flush(true);
        }
    }

    /**
     * @param ActionEvent $event
     */
    public function afterAction(ActionEvent $event)
    {
        \Yii::info(sprintf('End %s', $event->action->getUniqueId()), Task::class);
        if ($this->autoFlush) {
            \Yii::getLogger()->flush(true);
        }
    }

    /**
     * @param TaskEvent $event
     */
    public function beforeRun(TaskEvent $event)
    {
        \Yii::info("$event is started", Task::class);
        \Yii::beginProfile($event, Task::class);
    }

    /**
     * @param TaskEvent $event
     */
    public function afterRun(TaskEvent $event)
    {
        \Yii::endProfile($event, Task::class);
        \Yii::info("$event is finished", Task::class);
        if ($this->autoFlush) {
            \Yii::getLogger()->flush(true);
        }
    }

    /**
     * @param TaskEvent $event
     */
    public function beforeError(TaskEvent $event)
    {
        \Yii::endProfile($event, Task::class);
        if ($this->autoFlush) {
            \Yii::getLogger()->flush(true);
        }
    }

    /**
     * @param ErrorEvent $event
     */
    public function afterError(ErrorEvent $event)
    {
        \Yii::error($event->exception->getMessage(), Task::class);
        if ($this->autoFlush) {
            \Yii::getLogger()->flush(true);
        }
    }
}