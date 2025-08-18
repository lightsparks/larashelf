<?php

return [
    'paths' => [
        'api/*',
        'sanctum/csrf-cookie',
        'login',   // <— add if /login is a web route
        'logout',  // <— add if /logout is a web route
    ],
    'allowed_methods' => ['*'],
    'allowed_origins' => [
        'http://127.0.0.1:8002', // CMS (Laravel serve)
        'http://127.0.0.1:8003', // Portal (Laravel serve, later)
        'http://127.0.0.1:5174', // CMS Vite
        'http://127.0.0.1:5175', // Portal Vite (later)
        'http://localhost:8002',
        'http://localhost:8003',
        'http://localhost:5174',
        'http://localhost:5175',
    ],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true,
];
