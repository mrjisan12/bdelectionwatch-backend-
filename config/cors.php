<?php

return [

    'paths' => ['api/*'],

    'allowed_methods' => ['POST', 'GET', 'OPTIONS'],

    'allowed_origins' => [
        'http://localhost:5173',
        'https://www.bdelectionwatch.com'
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => [
        'Content-Type',
        'Accept',
        'Authorization',
        'X-Requested-With',
        'X-API-TOKEN'
    ],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false,
];
