<?php
namespace kuaukutsu\console\task;

use yii\console\Controller;

/**
 * Class BaseController
 * @package kuaukutsu\console\task
 *
 * Add instructions in crontab
 */
abstract class BaseController extends Controller
{
    /**
     * @var bool verbose mode of a task execute.
     * will be printed.
     */
    public $verbose = YII_ENV_DEV;

    /**
     * @inheritdoc
     */
    public function options($actionID)
    {
        return array_merge(parent::options($actionID), ['verbose']);
    }

    /**
     * @inheritdoc
     */
    public function optionAliases(): array
    {
        return array_merge(parent::optionAliases(), [
            'v' => 'verbose'
        ]);
    }

    /**
     * Выполняется каждые полчаса в указанное сервером время
     */
    public function actionHalfHourly(): void
    {
    }

    /**
     * Выполняется ежечасно в указанное сервером время
     */
    public function actionHourly(): void
    {
    }

    /**
     * Выполняется ежедневно в указанное сервером время
     */
    public function actionDaily(): void
    {
    }

    /**
     * Выполняется еженедельно в указанное сервером время (last day of the week)
     */
    public function actionWeekly(): void
    {
    }

    /**
     * Выполняется ежемесячно в указанное сервером время (first day of the mounth)
     */
    public function actionMonthly(): void
    {
    }
}