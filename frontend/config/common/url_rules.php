<?php

declare(strict_types=1);


use yii\rest\UrlRule;

return [
    [
        'class' => UrlRule::class,
        'controller' => ['' => 'site'],
        'extraPatterns' => [
            'GET' => 'index',
        ]
    ],
    [
        'class' => UrlRule::class,
        'controller' => ['restaurant' => 'restaurant'],
        'prefix' => 'v1',
        'patterns' => [
            'GET unique/<unique_name>' => 'unique-view',
        ]
    ],
];
