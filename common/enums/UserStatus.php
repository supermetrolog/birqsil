<?php

declare(strict_types=1);

namespace common\enums;

enum UserStatus: int
{
    case Active = 10;
    case Inactive = 9;
    case Deleted = 0;
}
