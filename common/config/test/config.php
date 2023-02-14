<?php

declare(strict_types=1);

return [
    'id' => 'app-common-tests',
    'basePath' => dirname(__DIR__ . '/../'),
    'components' => [
        'db' => [
            'dsn' => 'mysql:host=localhost;dbname=yii2advanced_test',
        ],
        'user' => [
            'class' => \yii\web\User::class,
            'identityClass' => 'common\models\User',
        ],
    ],
];
