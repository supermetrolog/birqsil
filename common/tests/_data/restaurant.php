<?php

use common\enums\RestaurantStatus;

return [
    [
        'id' => 1,
        'name' => 'test',
        'user_id' => 1,
        'legal_name' => 'test legal',
        'address' => 'test',
        'created_at' => '2023-04-01 20:00:00',
        'updated_at' => '2023-04-01 20:00:00',
        'status' => RestaurantStatus::HIDDEN->value,
        'unique_name' => '11111'
    ]
];