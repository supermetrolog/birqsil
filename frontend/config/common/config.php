<?php

declare(strict_types=1);

use common\components\response\ErrorResponse;
use yii\base\Event;
use yii\web\JsonResponseFormatter;
use yii\web\Response;

return [
    'id' => 'frontend',
    'basePath' => dirname(__DIR__, 2),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'container' => require __DIR__ . '/container.php',
    'components' => [
        'user' => fn () => Yii::$container->get(\yii\web\User::class),
        'request' => [
            'enableCsrfValidation' => false,
            'enableCookieValidation' => false,
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
            'baseUrl' => ''
        ],
        'response' => [
            'formatters' => [
                'json' => [
                    'class' => JsonResponseFormatter::class,
                ]
            ],
            'on beforeSend' => function (Event $event) {
                /** @var Response $response */
                $response = $event->sender;
                (new ErrorResponse($response))->processed();
            },
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => require __DIR__ . '/url_rules.php',
        ],
    ],
    'params' => [],
];
