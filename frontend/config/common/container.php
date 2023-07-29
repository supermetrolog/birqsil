<?php
/** @var array $local */


use common\models\AR\User;
use yii\di\Container;
use yii\web\IdentityInterface;

return [
    'definitions' => [],
    'singletons' => [
        \yii\web\User::class => [
            'identityClass' => User::class,
            'enableAutoLogin' => false,
            'enableSession' => false,
        ],
        User::class => fn (Container $container) => $container->get(\yii\web\User::class)->identity,
        IdentityInterface::class => User::class
    ]
];