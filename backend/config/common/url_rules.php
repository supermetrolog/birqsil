<?php

declare(strict_types=1);

use yii\rest\UrlRule;

return [
    [
        'class' => UrlRule::class,
        'controller' => ['' => 'site'],
        'prefix' => 'v1',
        'extraPatterns' => [
            'POST signup' => 'signup',
            'POST signin' => 'signin',
            'GET verify-email' => 'verify-email',
            'POST reset-password' => 'reset-password',
            'POST reset-password-request' => 'reset-password-request',
        ]
    ],
];
