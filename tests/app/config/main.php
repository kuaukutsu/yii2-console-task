<?php
return [
    'id' => 'yii2-console-task',
    'basePath' => dirname(__DIR__),
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'runtimePath' => dirname(__DIR__) . '/runtime',

    'bootstrap' => [
        'log',
        'task'
    ],

    'components' => [

        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => \yii\log\FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],

        'task' => [
            'class' => \kuaukutsu\console\task\Module::class,
            'as' => ['log', \kuaukutsu\console\task\behaviors\LogBehavior::class]
        ]
    ]
];