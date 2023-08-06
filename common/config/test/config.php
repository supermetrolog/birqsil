<?php

declare(strict_types=1);

use common\enums\AppParams;
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
    'params' => [
        AppParams::UPLOAD_FILE_BASE_PATH->value => dirname(__DIR__, 2) . '/tests/_output/',
    ]
];
