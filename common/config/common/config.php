<?php

/** @var array $local */

declare(strict_types=1);

return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(dirname(__DIR__))) . '/vendor',
    'container' => require 'container.php',
    'components' => [
        'cache' => [
            'class' => \yii\caching\FileCache::class,
        ],
        'db' => fn() => Yii::$container->get(\yii\db\Connection::class),
        'log' => [
            'targets' => [
                [
                    'class' => \yii\log\FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'param' => [
            'class' => \common\components\Param::class
        ]
    ],
    'params' => [
        'senderEmail' => 'birqsil@birqsil.ru',
        'user.passwordResetTokenExpire' => 3600,
        'user.passwordMin' => 8,
        'user.passwordMax' => 32,
        'user.emailMin' => 5,
        'user.emailMax' => 64,
        'user.access_token_expire' => 3600 * 24,
    ]
];
