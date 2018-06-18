<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'app\commands',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'notifier' => function () {
            return app\infr\Notifier\NotifierFactory::create(app\infr\Notifier\NotifierFactory::EMAIL);
        },
        'resourceMonitor' => [
            'class' => 'app\components\ResourceMonitor',
            'resources' => ['http://yandex.ru', 'http://yandex.ru/test'],
            'on ResourceUnavailable' => function ($event) {
                \Yii::$app->notifier->notify('from@from.ru', 'to@to.ru', ['subject' => 'Ресурс недоступен', 'text' => $event->uri]);
            },
            'on ResourceAvailable' => function ($event) {
                \Yii::$app->notifier->notify('from@from.ru', 'to@to.ru', ['subject' => 'Ресурс доступен', 'text' => $event->uri]);
            },
        ],
    ],
    'params' => $params,
    /*
    'controllerMap' => [
        'fixture' => [ // Fixture generation command line.
            'class' => 'yii\faker\FixtureController',
        ],
    ],
    */
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
