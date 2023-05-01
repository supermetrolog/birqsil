<?php

use common\base\interfaces\notifier\NotifierInterface;
use common\components\Notifier;
use common\components\Param;
use common\services\UserService;
use yii\mail\MailerInterface;
use yii\symfonymailer\Mailer;

return [
    'definitions' => [],
    'singletons' => [
        UserService::class => [],
        NotifierInterface::class => Notifier::class,
        Param::class => [],
        MailerInterface::class => Mailer::class,
        Mailer::class => [
            'viewPath' => '@common/mail',
            'useFileTransport' => true,
        ]
    ]
];