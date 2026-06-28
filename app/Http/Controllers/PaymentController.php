<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Services\MidtransService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    protected MidtransService $midtransService;

    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }

    /**
     * Generate Midtrans Snap Token
     */
    public function createTransaction(Request $request)
    {

        \Log::info('MASUK PAYMENT CONTROLLER');

        $request->validate([
            'order_id' => 'required|exists:orders,id',
        ]);

        $order = Order::with(
            'user',
            'items.product'
            )->findOrFail(
            $request->order_id
            );

        // Pastikan order milik user yang sedang login
        if ($order->user_id !== auth()->id()) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        // Jangan buat token baru jika sudah dibayar
        if ($order->payment_status === 'completed') {
            return response()->json([
                'message' => 'Order already paid'
            ], 400);
        }

        try {

            $snapToken = $this->midtransService->createTransaction($order);

            return response()->json([

    'success' => true,

    'snap_token' => $snapToken,

    'order_id' => $order->id,

]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Failed to create payment.',
                'error' => $e->getMessage()
            ], 500);

        }
    }

    /**
     * Get Payment Status
     */
    public function getPaymentStatus($paymentId)
    {
        $payment = Payment::findOrFail($paymentId);

        if (
            $payment->user_id !== auth()->id() &&
            !auth()->user()->isAdmin()
        ) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'payment' => $payment
        ]);
    }
}