<?php
namespace kuaukutsu\console\task;

use yii\base\{
    Action,
    Controller,
    Exception,
    InvalidConfigException
};

use yii\helpers\ {
    Inflector,
    StringHelper
};

use kuaukutsu\console\task\events\ErrorEvent;
use kuaukutsu\console\task\events\TaskEvent;
use kuaukutsu\console\task\behaviors\VerboseBehavior;

/**
 * Class Task
 * @package kuaukutsu\console\task
 */
final class Task extends BaseController
{
    /*********************
     * EVENTs
     ********************/

    const EVENT_BEFORE_ERROR = 'event-before-error';
    const EVENT_AFTER_ERROR = 'event-after-error';
    const EVENT_BEFORE_RUN = 'event-before-run';
    const EVENT_AFTER_RUN = 'event-after-run';

    /**
     * @var array additional options to the verbose behavior.
     */
    public $verboseConfig = [
        'class' => VerboseBehavior::class,
    ];

    /*********************
     * BASE
     ********************/

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        if ($this->verbose) {
            $this->attachBehavior('verbose', $this->verboseConfig);
        }

        if ($isValid = parent::beforeAction($action)) {
            $this->runDependence($action);
        }

        return $isValid;
    }

    /**
     * @param \Exception $exception
     */
    protected function handlerError(\Exception $exception)
    {
        $this->trigger(self::EVENT_AFTER_ERROR, new ErrorEvent($exception));
    }

    /***********************
     * BUILDER
     **********************/

    /**
     * @param Action $action
     */
    private function runDependence(Action $action)
    {
        $params = [];
        foreach ($this->options($action->id) as $option) {
            $params[$option] = $this->{$option};
        }

        foreach (glob(\Yii::$app->getControllerPath() . DIRECTORY_SEPARATOR . '*') as $filename) {
            $className = \Yii::$app->controllerNamespace . '\\' . StringHelper::basename($filename, '.php');
            if (class_exists($className) && is_a($className, BaseController::class, true)) {
                if ($controller = $this->prepareController($className)) {
                    if ($this->existAction($controller, $action)) {
                        $this->runDependenceAction($controller, $action, $params);
                    }
                }
            }
        }
    }

    /**
     * @param Controller $controller
     * @param Action $action
     * @param array $params
     */
    private function runDependenceAction(Controller $controller, Action $action, array $params)
    {
        try {

            $this->trigger(self::EVENT_BEFORE_RUN, new TaskEvent([
                'action' => $action,
                'controller' => $controller
            ]));

            $controller->runAction($action->id, $params);

            $this->trigger(self::EVENT_AFTER_RUN, new TaskEvent([
                'action' => $action,
                'controller' => $controller
            ]));

        } catch (Exception $exception) {

            $this->trigger(self::EVENT_BEFORE_ERROR, new TaskEvent([
                'action' => $action,
                'controller' => $controller
            ]));

            $this->handlerError($exception);
        }
    }

    /**
     * Controller class name -> controllerId -> create BaseController
     *
     * @param string $className
     * @return null|Controller
     */
    private function prepareController(string $className): ?Controller
    {
        try {
            return \Yii::$app->createControllerByID(
                Inflector::camel2id(StringHelper::basename($className, 'Controller'))
            );
        } catch (InvalidConfigException $exception) {
            $this->handlerError($exception);
        }

        return null;
    }

    /**
     * @param Controller $controller
     * @param Action $action
     * @return bool
     */
    private function existAction(Controller $controller, Action $action): bool
    {
        try {
            $reflectionController = new \ReflectionClass(get_class($controller));
            if ($reflectionMethod = $reflectionController->getMethod(Inflector::id2camel('action-' . $action->id))) {
                return $reflectionMethod->class !== BaseController::class;
            }
        } catch (\ReflectionException $exception) {
            $this->handlerError($exception);
        }

        return false;
    }
}