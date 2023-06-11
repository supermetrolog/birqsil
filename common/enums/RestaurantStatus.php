<?php

namespace common\enums;

enum RestaurantStatus: int
{
    case HIDDEN = 9;
    case PUBLISHED = 10;
    case DELETED = 11;

    /**
     * @return int[]
     */
    public static function toArray(): array
    {
        return [
            self::DELETED->value,
            self::PUBLISHED->value,
            self::HIDDEN->value,
        ];
    }
}
