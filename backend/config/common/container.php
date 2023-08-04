<?php
/** @var array $local */


use common\models\AR\User;
use yii\di\Container;
use yii\web\IdentityInterface;

return [
    'definitions' => [],
    'singletons' => [
        User::class => fn (Container $container) => $container->get(\yii\web\User::class)->identity,
        IdentityInterface::class => User::class
    ]
];