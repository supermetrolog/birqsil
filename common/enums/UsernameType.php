<?php

declare(strict_types=1);

namespace common\enums;

enum UsernameType
{
    case Email;
    case Phone;
    case UNKNOWN;
}
