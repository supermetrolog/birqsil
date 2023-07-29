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
];
