<?php

use common\enums\Status;

return [
    [
        'id' => 1,
        'restaurant_id' => 2,
        'title' => 'Test',
        'status' => Status::Active->value,
        'ordering' => 1,
        'price' => 125,
        'category_id' => 2
    ],
    [
        'id' => 2,
        'restaurant_id' => 2,
        'title' => 'Test2',
        'status' => Status::Active->value,
        'ordering' => 2,
        'price' => 125,
        'category_id' => 2
    ],
    [
        'id' => 3,
        'restaurant_id' => 2,
        'title' => 'Test3',
        'status' => Status::Active->value,
        'ordering' => 3,
        'price' => 125,
        'category_id' => 2
    ],
];