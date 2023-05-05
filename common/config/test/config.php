<?php

declare(strict_types=1);

use yii\db\Connection;

return [
    'id' => 'app-common-tests',
    'basePath' => dirname(__DIR__ . '/../'),
    'container' => [
        'singletons' => [
            Connection::class => [
                'dsn' => 'mysql:host=mysql_test;port=3306;dbname=birqsil_test',
            ]
        ]
    ],
    'components' => [
        'db' => [
            'dsn' => 'mysql:host=mysql_test;port=3306;dbname=birqsil_test',
        ],
        'user' => [
            'class' => \yii\web\User::class,
            'identityClass' => 'common\models\AR\User',
        ],
    ],
];
