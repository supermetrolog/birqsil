<?php

declare(strict_types=1);

return [
    'id' => 'frontend-tests',
    'components' => [
        'urlManager' => [
            'showScriptName' => true,
        ],
    ],
    'bootstrap' => ['gii', 'debug'],
    'modules' => [
        'debug' => [
            'class' => 'yii\debug\Module',
            'allowedIPs' => ["*"],
        ],
        'gii' => [
            'class' => 'yii\gii\Module',
            'allowedIPs' => ["*"],
        ]
    ]
];
