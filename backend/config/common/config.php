<?php

declare(strict_types=1);

use backend\components\response\ErrorResponse;
use yii\base\Event;
use yii\web\JsonResponseFormatter;
use yii\web\Response;

return [
    'id' => 'backend',
    'basePath' => dirname(__DIR__, 2),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'container' => require __DIR__ . '/container.php',
    'components' => [
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
        'user' => require __DIR__ . '/../user.php',
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
