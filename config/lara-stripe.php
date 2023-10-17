<?php

/**
 * Stripe configuration.
 */
return [
    'driver' => 'config',
    'currency' => env('STRIPE_CURRENCY', 'usd'),
    'secret_key' => env('STRIPE_SECRET_KEY'),
    'public_key' => env('STRIPE_PUBLIC_KEY'),
    'success_url' => env('STRIPE_SUCCESS_URL'),
    'cancel_url' => env('STRIPE_CANCEL_URL'),
    'is_production' => env('STRIPE_IS_PRODUCTION'),
];
