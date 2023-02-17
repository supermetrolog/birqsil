<?php

declare(strict_types=1);

return [
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'site',
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['v1/users' => 'v1/users/default'],
    ],
];
