<?php
// ensure we get report on all possible php errors
error_reporting(-1);

defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'test');

$_SERVER['SCRIPT_NAME'] = '/' . __DIR__;
$_SERVER['SCRIPT_FILENAME'] = __FILE__;

require_once(__DIR__ . '/../vendor/autoload.php');
require_once(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

$config = require(__DIR__ . '/app/config/main.php');

/** @noinspection PhpUnhandledExceptionInspection */
$app = new \yii\console\Application($config);
