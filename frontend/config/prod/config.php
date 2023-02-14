<?php

declare(strict_types=1);

return  [
    'components' => [
        'db' => [
            'enableSchemaCache' => true,
            'schemaCacheDuration' => 120,
            'schemaCache' => 'cache',
        ],
        'db_old' => [
            'enableSchemaCache' => true,
            'schemaCacheDuration' => 120,
            'schemaCache' => 'cache',
        ]
    ]
];
