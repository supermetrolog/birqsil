<?php

/** @var array $local */

declare(strict_types=1);

return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(dirname(__DIR__))) . '/vendor',
    'components' => [
        'cache' => [
            'class' => \yii\caching\FileCache::class,
        ],
        'db' => [
            'class' => \yii\db\Connection::class,
            'dsn' => 'mysql:host=mysql;dbname=' . $local['db.name'],
            'username' => $local['db.username'],
            'password' => $local['db.password'],
            'charset' => 'utf8',
        ],

        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@common/mail',
            // send all mails to a file by default.
            'useFileTransport' => true,
        ],
        'log' => [
            'targets' => [
                [
                    'class' => \yii\log\FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
    ],
    'params' => [
        'adminEmail' => 'admin@example.com',
        'supportEmail' => 'support@example.com',
        'senderEmail' => 'noreply@example.com',
        'senderName' => 'Example.com mailer',
        'user.passwordResetTokenExpire' => 3600,
        'user.passwordMinLength' => 8,
    ]
];
