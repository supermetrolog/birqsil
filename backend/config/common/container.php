<?php
/** @var array $local */

use common\base\interfaces\notifier\NotifierInterface;
use common\components\Notifier;
use common\components\Param;
use common\models\AR\User;
use common\services\UserService;
use yii\db\Connection;
use yii\di\Container;
use yii\mail\MailerInterface;
use yii\symfonymailer\Mailer;
use yii\web\IdentityInterface;

return [
    'definitions' => [],
    'singletons' => [
        UserService::class => [],
        \yii\web\User::class => require '../user.php',
        User::class => fn (Container $container) => $container->get(\yii\web\User::class)->identity,
        IdentityInterface::class => User::class
    ]
];