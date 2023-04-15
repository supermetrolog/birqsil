<?php

declare(strict_types=1);

return [
    'id' => 'app-common-tests',
    'basePath' => dirname(__DIR__ . '/../'),
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
