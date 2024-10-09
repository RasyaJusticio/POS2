<?php

return [

    /*
    |-------------------------------------------------------------------------- 
    | Cross-Origin Resource Sharing (CORS) Configuration 
    |-------------------------------------------------------------------------- 
    |
    | Here you may configure your settings for cross-origin resource sharing 
    | or "CORS". This determines what cross-origin operations may execute 
    | in web browsers. 
    |
    */

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'], // Allow all methods (GET, POST, PUT, DELETE)

    'allowed_origins' => ['*'], // Allow all origins (you may limit to your domain for security)

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'], // Allow all headers, adjust if needed

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true, // Set to true if using cookies (like Laravel Sanctum)
];
