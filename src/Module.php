<?php
namespace kuaukutsu\console\task;

use Yii;
use yii\base\BootstrapInterface;
use yii\base\InvalidConfigException;
use yii\console\Application;
use yii\helpers\Inflector;

/**
 * Class Module
 * @package kuaukutsu\console\task
 */
class Module implements BootstrapInterface
{
    /**
     * @var array ['name', Behavior::class] OR [['name', Behavior::class], ['name', Behavior::class]]
     */
    public $as = [];

    /**
     * @param \yii\base\Application $app
     * @throws InvalidConfigException
     */
    public function bootstrap($app): void
    {
        if ($app instanceof Application) {
            $app->controllerMap[$this->getCommandId()] = [
                    'class' => Task::class
                ];

            $this->prepareAs($this->as, $app->controllerMap[$this->getCommandId()]);
        }
    }

    /**
     * @return string command id
     * @throws
     */
    private function getCommandId(): string
    {
        foreach (Yii::$app->getComponents(false) as $id => $component) {
            if ($component === $this) {
                return Inflector::camel2id($id);
            }
        }

        throw new InvalidConfigException('Task must be an application component.');
    }

    /**
     * NOTE: recursive
     * @param array $as
     * @param $controllerMap
     */
    private function prepareAs(array $as, &$controllerMap): void
    {
        // prepare as
        if (count($as)) {
            if (is_string($as[0])) {
                $controllerMap['as ' . $as[0]] = $as[1];
            } else {
                foreach ($as as $item) {
                    $this->prepareAs($item, $controllerMap);
                }
            }
        }
    }
}