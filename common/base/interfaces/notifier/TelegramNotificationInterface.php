<?php

namespace common\base\interfaces\notifier;

interface TelegramNotificationInterface
{
    public function send(): void;
}