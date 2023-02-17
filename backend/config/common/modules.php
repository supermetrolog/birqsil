<?php

declare(strict_types=1);

return [
    'v1' => [
        'class' => 'app\modules\v1\Module',
        'modules' => [
            'users' => [
                'class' => 'app\modules\v1\modules\user\Module'
            ]
        ]
    ]
];
