<?php

return  [
    'id' => 'basic-tests',
    'components' => [
        'db' => [
            'dsn' => 'mysql:host=localhost;dbname=test_db',
        ],
        'mailer' => [
            'useFileTransport' => true,
        ],
        'request' => [
            'cookieValidationKey' => 'test',
            'enableCsrfValidation' => false,
        ],
    ],
];
