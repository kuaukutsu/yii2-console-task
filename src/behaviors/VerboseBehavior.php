<?php
namespace kuaukutsu\console\task\behaviors;

use yii\base\ActionEvent;
use yii\base\Behavior;
use yii\helpers\Console;
use kuaukutsu\console\task\Task;
use kuaukutsu\console\task\events\TaskEvent;
use kuaukutsu\console\task\events\ErrorEvent;

/**
 * Class VerboseBehavior
 * @package kuaukutsu\console\task
 */
class VerboseBehavior extends Behavior
{
    /**
     * @inheritdoc
     */
    public function events(): array
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
    public function beforeAction(ActionEvent $event): void
    {
        Console::stdout(
            Console::ansiFormat(
                sprintf('Begin %s', $event->action->getUniqueId())
            ) . PHP_EOL
        );
    }

    /**
     * @param ActionEvent $event
     */
    public function afterAction(ActionEvent $event): void
    {
        Console::stdout(
            Console::ansiFormat(
                sprintf('End %s', $event->action->getUniqueId())
            ) . PHP_EOL
        );
    }

    /**
     * @param TaskEvent $event
     */
    public function beforeRun(TaskEvent $event): void
    {
        Console::stdout(
            Console::ansiFormat(
                "$event is started"
            ) . PHP_EOL
        );
    }

    /**
     * @param TaskEvent $event
     */
    public function afterRun(TaskEvent $event): void
    {
        Console::stdout(
            Console::ansiFormat(
                "$event is finished"
            ) . PHP_EOL
        );
    }

    /**
     * @param TaskEvent $event
     */
    public function beforeError(TaskEvent $event): void
    {
        Console::stdout(
            Console::ansiFormat(
                "$event is error"
            ) . PHP_EOL
        );
    }

    /**
     * @param ErrorEvent $event
     */
    public function afterError(ErrorEvent $event): void
    {
        Console::stderr(
            Console::ansiFormat(
                'error: ' . $event
            ) . PHP_EOL
        );
    }
}