<?php

return [

    'facility' => env('VATUSA_FACILITY'),
    'jwk' => env('VATUSA_ULS_JWK'),
    'api_key' => env('VATUSA_API_KEY'),
    'dev_callback' => env('VATUSA_DEV_CALLBACK_URL', '3'),
    'base' => env('VATUSA_API_BASE', 'https://api.vatusa.net'),
    'academy_crs_local' => 11,
    'academy_crs_approach' => 12,
    'academy_crs_enroute' => 13
];
