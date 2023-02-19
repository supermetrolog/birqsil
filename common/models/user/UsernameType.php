<?php

declare(strict_types=1);

namespace common\models\user;

enum UsernameType
{
    case Email;
    case Phone;
    case UNKNOWN;
}
