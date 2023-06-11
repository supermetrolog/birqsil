<?php

use common\models\AR\User;

return [
    'identityClass' => User::class,
    'enableAutoLogin' => false,
    'enableSession' => false,
];