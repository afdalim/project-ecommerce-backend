<?php

return [

    'default' => env(
        'PAYMENT_GATEWAY',
        'midtrans'
    ),

    'stripe' => [

        'public_key' => env('STRIPE_PUBLIC_KEY'),

        'secret_key' => env('STRIPE_SECRET_KEY'),

    ],

    'midtrans' => [

        'server_key' => env('MIDTRANS_SERVER_KEY'),

        'client_key' => env('MIDTRANS_CLIENT_KEY'),

        'is_production' => env(
            'MIDTRANS_IS_PRODUCTION',
            false
        ),

    ],

    'currencies' => [

        'IDR'

    ],

    'default_currency' => 'IDR',

    'tax_rate' => 0.10,

    'shipping_cost' => 5.00,

];