<?php

namespace App\Services;

use App\Models\Order;
use Midtrans\Config;
use Midtrans\Snap;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey = config(
            'payment.midtrans.server_key'
        );

        Config::$isProduction = config(
            'payment.midtrans.is_production'
        );

        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function createTransaction(Order $order)
    {
        \Log::info(
            'MASUK MIDTRANS SERVICE'
        );

        $order->load(
            'user',
            'items.product'
        );

        $itemDetails = [];

        foreach ($order->items as $item) {

            $itemDetails[] = [

                'id' => $item->product_id,

                'price' => (int) round(
                    $item->price
                ),

                'quantity' => (int) $item->quantity,

                'name' => $item->product->name
                    ?? 'Product',

            ];
        }

        // Shipping
        if ($order->shipping_cost > 0) {

            $itemDetails[] = [

                'id' => 'SHIPPING',

                'price' => (int) round(
                    $order->shipping_cost
                ),

                'quantity' => 1,

                'name' => 'Shipping Cost',

            ];
        }

        // Tax
        if ($order->tax_amount > 0) {

            $itemDetails[] = [

                'id' => 'TAX',

                'price' => (int) round(
                    $order->tax_amount
                ),

                'quantity' => 1,

                'name' => 'Tax',

            ];
        }

        $params = [

            'transaction_details' => [

                'order_id' =>
                    $order->order_number,

                'gross_amount' =>
                    (int) round(
                        $order->final_amount
                    ),

            ],

            'customer_details' => [

                'first_name' =>
                    $order->user->name,

                'email' =>
                    $order->user->email,

            ],

            'item_details' =>
                $itemDetails,

        ];

        try {

            \Log::info(
                'MIDTRANS PARAMS',
                $params
            );

            $snapToken =
                Snap::getSnapToken(
                    $params
                );

            return $snapToken;

        } catch (\Exception $e) {

            \Log::error(
                'MIDTRANS ERROR : '
                .
                $e->getMessage()
            );

            throw $e;
        }
    }
}