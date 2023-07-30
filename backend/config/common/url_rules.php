<?php

declare(strict_types=1);

use yii\rest\UrlRule;

return [
    [
        'class' => UrlRule::class,
        'controller' => ['' => 'auth'],
        'prefix' => 'v1',
        'patterns' => [
            'POST signup' => 'signup',
            'POST signin' => 'signin',
            'GET verify-email' => 'verify-email',
            'POST reset-password' => 'reset-password',
            'POST reset-password-request' => 'reset-password-request',
        ]
    ],
    [
        'class' => UrlRule::class,
        'controller' => ['' => 'site'],
        'extraPatterns' => [
            'GET' => 'index',
        ]
    ],
    [
        'class' => UrlRule::class,
        'controller' => ['user' => 'user'],
        'prefix' => 'v1',
        'patterns' => [
            'GET check-email-exists' => 'check-email-exists',
            'GET find-by-token/<token>' => 'find-by-token',
        ]
    ],
    [
        'class' => UrlRule::class,
        'controller' => ['restaurant' => 'restaurant'],
        'prefix' => 'v1',
        'patterns' => [
            'GET' => 'index',
            'GET <id>' => 'view',
            'POST' => 'create',
            'PUT <id>' => 'update',
            'DELETE <id>' => 'delete',
            'POST <id>/publish' => 'publish',
            'POST <id>/hide' => 'hide',
            'GET <id>/qrcode' => 'qrcode',
        ]
    ],
    [
        'class' => UrlRule::class,
        'controller' => ['menu' => 'menu'],
        'prefix' => 'v1',
        'patterns' => [
            'GET <restaurant_id>' => 'index',
            'GET item/<id>' => 'view',
            'POST' => 'create',
            'PUT <id>' => 'update',
            'DELETE <id>' => 'delete',
            'POST <id>/upload-file' => 'upload-file',
        ]
    ],
    [
        'class' => UrlRule::class,
        'controller' => ['category' => 'category'],
        'prefix' => 'v1',
        'patterns' => [
            'GET <restaurant_id>' => 'index',
            'POST' => 'create',
            'PUT <id>' => 'update',
            'DELETE <id>' => 'delete',
        ]
    ],
];
