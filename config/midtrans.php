<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Midtrans Configuration
    |--------------------------------------------------------------------------
    |
    | Here you can configure your Midtrans integration settings.
    |
    */

    // Midtrans merchant ID
    'merchant_id' => env('MIDTRANS_MERCHANT_ID', ''),

    // Midtrans client key
    'client_key' => env('MIDTRANS_CLIENT_KEY', ''),

    // Midtrans server key
    'server_key' => env('MIDTRANS_SERVER_KEY', ''),

    // Set to true for production environment
    'is_production' => env('MIDTRANS_IS_PRODUCTION', false),

    // Enable 3D Secure by default
    'is_3ds' => true,

    // Enable request sanitization
    'is_sanitized' => true,

    // Enable request-response logging
    'enable_logging' => true,

    // Default currency
    'currency' => 'IDR',

    // Default language
    'language' => 'id',
];