<?php

declare(strict_types=1);

namespace common\components\interfaces;

interface SenderInterface
{
    function sms(string $phoneNumber, string $content): bool;
    function email(string $address, string $content): bool;
}
