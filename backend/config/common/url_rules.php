<?php

declare(strict_types=1);

use yii\rest\UrlRule;

return [
    array(
        'class' => UrlRule::class,
        'controller' => ['' => 'site'],
        'extraPatterns' => [
            'GET signup' => 'signup'
        ]
    ),
];
