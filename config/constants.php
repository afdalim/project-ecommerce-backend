<?php

return [
    'user_roles' => [
        'customer' => 'customer',
        'admin' => 'admin',
        'super_admin' => 'super_admin',
    ],

    'order_status' => [
        'pending' => 'pending',
        'confirmed' => 'confirmed',
        'processing' => 'processing',
        'shipped' => 'shipped',
        'delivered' => 'delivered',
        'cancelled' => 'cancelled',
    ],

    'payment_status' => [
        'pending' => 'pending',
        'completed' => 'completed',
        'failed' => 'failed',
        'refunded' => 'refunded',
    ],

    'return_status' => [
        'requested' => 'requested',
        'approved' => 'approved',
        'rejected' => 'rejected',
        'completed' => 'completed',
    ],

    'stock_status' => [
        'in_stock' => 'in_stock',
        'low_stock' => 'low_stock',
        'out_of_stock' => 'out_of_stock',
    ],
];
