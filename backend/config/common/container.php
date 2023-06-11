<?php
/** @var array $local */


use common\models\AR\User;
use yii\di\Container;
use yii\web\IdentityInterface;

return [
    'definitions' => [],
    'singletons' => [
        \yii\web\User::class => require __DIR__ . '/../user.php',
        User::class => fn (Container $container) => $container->get(\yii\web\User::class)->identity,
        IdentityInterface::class => User::class
    ]
];