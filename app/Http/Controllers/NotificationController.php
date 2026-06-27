<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Notification;

class NotificationController extends Controller
{
    public function handle(Request $request)
    {
        Config::$serverKey = config(
            'payment.midtrans.server_key'
        );

        Config::$isProduction = config(
            'payment.midtrans.is_production'
        );

        $notification =
            new Notification();

        $transactionStatus =
            $notification->transaction_status;

        $paymentType =
            $notification->payment_type;

        $orderId =
            $notification->order_id;

        $transactionId =
            $notification->transaction_id;

        $grossAmount =
            $notification->gross_amount;

        $order =
            Order::where(
                'order_number',
                $orderId
            )->first();

        if (!$order) {

            return response()->json([
                'message' =>
                'Order not found'
            ],404);

        }

        if (
            $transactionStatus === 'capture'
            ||
            $transactionStatus === 'settlement'
        ) {

            $order->update([
                'payment_status' => 'completed',
                'status' => 'processing'
            ]);

            Payment::updateOrCreate(
                [
                    'order_id' => $order->id
                ],
                [
                    'user_id' => $order->user_id,
                    'amount' => $grossAmount,
                    'payment_method' => $paymentType,
                    'status' => 'completed',
                    'transaction_id' => $transactionId,
                    'completed_at' => now()
                ]
            );
        }

        if (
            $transactionStatus === 'pending'
        ) {

            $order->update([
                'payment_status' => 'pending'
            ]);

        }

        if (
            $transactionStatus === 'expire'
            ||
            $transactionStatus === 'cancel'
            ||
            $transactionStatus === 'deny'
        ) {

            $order->update([
                'payment_status' => 'failed'
            ]);

        }

        return response()->json([
            'success' => true
        ]);
    }
}