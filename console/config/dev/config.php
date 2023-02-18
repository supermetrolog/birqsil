<?php

declare(strict_types=1);

return [
    'bootstrap' => ['gii'],
    'modules' => [
        'gii' => 'yii\gii\Module',
    ],
    'components' => [
        'db' => [
            'dsn' => 'mysql:host=localhost;dbname=' . $local['db.name'],
        ]
    ],
];
