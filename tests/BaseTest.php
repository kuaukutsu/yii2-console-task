<?php
namespace kuaukutsu\console\task\tests;

use kuaukutsu\console\task\Task;
use kuaukutsu\console\task\behaviors\VerboseBehavior;

/**
 * Class BaseTest
 * @package kuaukutsu\struct\related\tests
 */
class BaseTest extends \PHPUnit\Framework\TestCase
{
    public function testBase()
    {
        $controller = new Task('task', \Yii::$app);
        $controller->attachBehavior('verbose', VerboseBehavior::class);

        $controller->run('half-hourly');
        $controller->run('hourly');
        $controller->run('daily');
        $controller->run('weekly');
        $controller->run('monthly');

        $this->assertTrue(true);
    }
}