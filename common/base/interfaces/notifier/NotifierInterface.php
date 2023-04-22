<?php

namespace common\base\interfaces\notifier;

use common\models\AR\User;

interface NotifierInterface
{
    public function notify(
        User $user,
        EmailNotificationInterface | TelegramNotificationInterface $notification)
    : void;
}