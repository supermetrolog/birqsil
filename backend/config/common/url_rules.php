<?php

declare(strict_types=1);

use yii\rest\UrlRule;

return [
    array(
        'class' => UrlRule::class,
        'controller' => ['' => 'site'],
        'extraPatterns' => [
            'POST signup' => 'signup',
            'POST signin' => 'signin',
            'GET verify-email' => 'verify-email',
            'POST reset-password' => 'reset-password',
        ]
    ),
];
