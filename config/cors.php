<?php

return [
<<<<<<< HEAD

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    'paths' => ['api/*', 'sanctum/csrf-cookie', 'oauth/token'],
=======
    'paths' => ['api/*', 'oauth/*', 'sanctum/csrf-cookie'],
>>>>>>> d4e8cc2a6b7a0b8b2c85aab69891a1a27d5632cd

    'allowed_methods' => ['*'],

    'allowed_origins' => ['*'],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false,
<<<<<<< HEAD

];
=======
];
>>>>>>> d4e8cc2a6b7a0b8b2c85aab69891a1a27d5632cd
