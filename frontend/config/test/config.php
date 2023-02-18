<?php

return  [
    'id' => 'basic-tests',
    'components' => [
        'mailer' => [
            'useFileTransport' => true,
        ],
        'request' => [
            'cookieValidationKey' => 'test',
            'enableCsrfValidation' => false,
        ],
    ],
];
