<?php

namespace common\enums;

enum RestaurantStatus: int
{
    case HIDDEN = 9;
    case PUBLISHED = 10;
    case DELETED = 11;
}
