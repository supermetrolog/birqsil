<?php
/** @var array $local */

use common\base\interfaces\notifier\NotifierInterface;
use common\components\Notifier;
use common\components\Param;
use common\services\UserService;
use yii\db\Connection;
use yii\mail\MailerInterface;
use yii\symfonymailer\Mailer;

return [
    'definitions' => [],
    'singletons' => [
        NotifierInterface::class => Notifier::class,
        MailerInterface::class => Mailer::class,
        Mailer::class => [
            'viewPath' => '@common/mail',
            'useFileTransport' => true,
        ],
        Connection::class => [
            'class' => Connection::class,
            'dsn' => 'mysql:host=' . $local['db.host'] .';dbname=' . $local['db.name'],
            'username' => $local['db.username'],
            'password' => $local['db.password'],
            'charset' => 'utf8',
        ]
    ]
];