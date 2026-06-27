<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Order;
use Stripe\Stripe;
use Stripe\Charge;

class PaymentService
{
    public function __construct()
    {
        if (config('payment.default') === 'stripe') {
            Stripe::setApiKey(config('payment.stripe.secret_key'));
        }
    }

public function processPayment(Order $order, array $paymentData)
{

    try {


        if (
            $paymentData['payment_method'] === 'stripe'
            ||
            $paymentData['payment_method'] === 'credit_card'
        ) {


            return $this->processStripePayment(
                $order,
                $paymentData
            );


        }





        $payment =
        $this->processGenericPayment(
            $order,
            $paymentData
        );





        $order->update([

            'payment_status' =>
            'completed',


            'status' =>
            'processing'

        ]);





        return $payment;



    } catch (\Exception $e) {




        Payment::create([


            'user_id' =>
            $order->user_id,


            'order_id' =>
            $order->id,


            'amount' =>
            $order->final_amount,


            'payment_method' =>
            $paymentData['payment_method'],


            'status' =>
            'failed',


            'failure_reason' =>
            $e->getMessage(),


        ]);





        $order->update([


            'payment_status' =>
            'failed'


        ]);




        throw $e;



    }


}

    private function processStripePayment(Order $order, array $paymentData)
    {
        $charge = Charge::create([
            'amount' => (int) ($order->final_amount * 100),
            'currency' => strtolower(config('payment.default_currency', 'usd')),
            'source' => $paymentData['token'],
            'description' => "Order {$order->order_number}",
            'metadata' => ['order_id' => $order->id],
        ]);

        $payment = Payment::create([
            'user_id' => $order->user_id,
            'order_id' => $order->id,
            'amount' => $order->final_amount,
            'payment_method' => 'stripe',
            'status' => 'completed',
            'transaction_id' => $charge->id,
            'gateway_response' => json_encode($charge),
            'completed_at' => now(),
        ]);

        $order->update([
            'payment_status' => 'completed',
            'status' => 'confirmed',
        ]);

        return $payment;
    }

    private function processGenericPayment(
    Order $order,
    array $paymentData
){

    $payment =
    Payment::create([

        'user_id' =>
        $order->user_id,


        'order_id' =>
        $order->id,


        'amount' =>
        $order->final_amount,


        'payment_method' =>
        $paymentData['payment_method'],


        'status' =>
        'completed',

    ]);





    $order->update([


        'payment_status' =>
        'completed',


        'status' =>
        'processing'


    ]);




    return $payment;


}

    public function refundPayment(Payment $payment)
    {
        if ($payment->status !== 'completed') {
            throw new \Exception('Only completed payments can be refunded');
        }

        try {
            if ($payment->payment_method === 'stripe') {
                \Stripe\Refund::create([
                    'charge' => $payment->transaction_id,
                    'amount' => (int) ($payment->amount * 100),
                ]);
            }

            $payment->update([
                'status' => 'refunded',
            ]);

            return $payment;
        } catch (\Exception $e) {
            throw new \Exception('Refund failed: ' . $e->getMessage());
        }
    }
}
