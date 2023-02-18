<?php

declare(strict_types=1);

namespace common\models\user;

enum StatusEnum: int
{
    case Active = 10;
    case Inactive = 9;
    case Deleted = 0;
}
