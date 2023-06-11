<?php

use common\enums\Status;

return [
    [
        'id' => 1,
        'user_id' => 2,
        'token' => 'valid_token',
        'created_at' => (new DateTime())->format('Y-m-d H:i:s'),
        'status' => Status::Active->value,
        'expire' => 3600 * 24
    ]
];