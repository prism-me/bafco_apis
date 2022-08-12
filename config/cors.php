<?php
//
//return [
//
//    /*
//    |--------------------------------------------------------------------------
//    | Cross-Origin Resource Sharing (CORS) Configuration
//    |--------------------------------------------------------------------------
//    |
//    | Here you may configure your settings for cross-origin resource sharing
//    | or "CORS". This determines what cross-origin operations may execute
//    | in web browsers. You are free to adjust these settings as needed.
//    |
//    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
//    |
//    */
//
//    'paths' => ['v1/*' ,'api/*','sanctum/csrf-cookie'],
//
//    'allowed_methods' => ['PUT','POST','DELETE','GET','OPTION'],
//
//    'allowed_origins' => ['http://localhost:3001','http://localhost:3000'],
//
//    'allowed_origins_patterns' => [],
//
//    'allowed_headers' => ['*'],
//
//    'exposed_headers' => [],
//
//    'max_age' => 0,
//
//    'supports_credentials' => false,
//
//];

return [
    'paths' => ['v1/*' ,'api/*','sanctum/csrf-cookie'],
    'allowed_methods' => ['POST', 'GET', 'DELETE', 'PUT', '*'],
    'allowed_origins' => ['http://localhost:3000', 'http://bafco-next.herokuapp.com/'],
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['X-Custom-Header', 'Upgrade-Insecure-Requests', '*'],
     'exposed_headers' => false,
    'max_age' => false,
    'supports_credentials' => false,
];
