<?php

namespace common\enums;

enum Status: int
{
    case Active = 10;
    case Inactive = 9;
    case Deleted = 0;
}
