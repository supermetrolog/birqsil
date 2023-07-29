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
        'controller' => ['menu' => 'menu'],
        'prefix' => 'v1',
        'extraPatterns' => [
            'GET <restaurant_unique_name>' => 'index',
        ]
    ],
];
