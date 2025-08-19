<?php

return [
    'paths' => [
        'api/*',
        'sanctum/csrf-cookie',
        'login',
        'logout',
    ],
    'allowed_methods' => ['*'],
    'allowed_origins' => [
        'http://127.0.0.1:8002', // CMS (Laravel serve)
        'http://127.0.0.1:8003', // Portal (Laravel serve, later)
        'http://127.0.0.1:5174', // CMS Vite
        'http://127.0.0.1:5175', // Portal Vite (later)
    ],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true,
];
