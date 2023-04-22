<?php

namespace common\base\interfaces\notifier;

use common\models\AR\User;

interface NotifierInterface
{
    // TODO: сделать интерфейс нотификаций
    /**
     * @param User $user
     * @param $notification
     * @return void
     */
    public function notify(User $user, $notification): void;
}