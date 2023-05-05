<?php

declare(strict_types=1);

use yii\db\Connection;

return [
    'id' => 'app-common-tests',
    'basePath' => dirname(__DIR__ . '/../'),
    'container' => [
        'singletons' => [
            Connection::class => [
                'dsn' => 'mysql:host=birqsil_mysql_test_1;port=3306;dbname=birqsil_test',
            ]
        ]
    ],
    'components' => [
        'db' => [
            'dsn' => 'mysql:host=birqsil_mysql_test_1;port=3306;dbname=birqsil_test',
        ],
        'user' => [
            'class' => \yii\web\User::class,
            'identityClass' => 'common\models\AR\User',
        ],
    ],
];
