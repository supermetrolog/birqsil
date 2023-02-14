<?php

declare(strict_types=1);

return [
    'bootstrap' => ['gii', 'debug'],
    'modules' => [
        'debug' => [
            'class' => 'yii\debug\Module',
            'allowedIPs' => ["*"],
        ],
        'gii' => [
            'class' => 'yii\gii\Module',
            'generators' => [
                'jobs' => yii\queue\gii\Generator::class,
            ],
        ]
    ]
];
