<?php

namespace common\enums;

enum Status: int
{
    case Active = 10;
    case Inactive = 9;
    case Deleted = 0;

    /**
     * @return array
     */
    public static function asArray(): array
    {
        return [
            self::Active->value,
            self::Inactive->value,
            self::Deleted->value
        ];
    }
}
