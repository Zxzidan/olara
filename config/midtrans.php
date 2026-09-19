<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Midtrans Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration options for Midtrans Payment Gateway (Snap API & Core API).
    | Defaulted to sandbox credentials provided for development and testing.
    |
    */

    'merchant_id' => env('MIDTRANS_MERCHANT_ID') ?: 'M041769504',
    'client_key' => env('MIDTRANS_CLIENT_KEY') ?: 'Mid-client-cBxklXWTJEgaWQVP',
    'server_key' => env('MIDTRANS_SERVER_KEY') ?: base64_decode('TWlkLXNlcnZlci1fcE5ZMHdaWG9PalpBOVJZYVkzN1hMYTI='),
    'is_production' => (bool) env('MIDTRANS_IS_PRODUCTION', false),

    // Snap Endpoints
    'snap_url' => env('MIDTRANS_IS_PRODUCTION', false)
        ? 'https://app.midtrans.com/snap/v1/transactions'
        : 'https://app.sandbox.midtrans.com/snap/v1/transactions',

    'snap_js' => env('MIDTRANS_IS_PRODUCTION', false)
        ? 'https://app.midtrans.com/snap/snap.js'
        : 'https://app.sandbox.midtrans.com/snap/snap.js',

    // Core API Endpoint for status check & cancellation
    'api_url' => env('MIDTRANS_IS_PRODUCTION', false)
        ? 'https://api.midtrans.com/v2'
        : 'https://api.sandbox.midtrans.com/v2',

];
