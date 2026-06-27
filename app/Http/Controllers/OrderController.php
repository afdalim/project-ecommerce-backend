<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Services\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function create(Request $request)
    {
        $request->validate([
            'shipping_address' => 'required|array',
            'billing_address' => 'required|array',
            'shipping_method' => 'required|string',
        ]);

        try {
            $order = $this->orderService->createOrder(auth()->user(), $request->all());

            return response()->json([
                'message' => 'Order created successfully',
                'order' => $order,
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function getOrders()
    {
        $orders = auth()->user()->orders()
            ->with([

    'items.product',

    'returnRequest'

])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json($orders);
    }

    public function getOrderDetail($orderId)
{


    $order =
    Order::with([

        'items.product'

    ])
    ->findOrFail($orderId);




    if($order->user_id !== auth()->id()){


        return response()->json([

            'message' =>
            'Unauthorized'

        ],403);


    }




    return response()->json($order);


}

    public function cancelOrder($orderId)
    {
        $order = Order::findOrFail($orderId);

        if ($order->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if (!in_array($order->status, ['pending', 'confirmed'])) {
            return response()->json(['message' => 'Cannot cancel this order'], 400);
        }

        $this->orderService->cancelOrder($order);

        return response()->json(['message' => 'Order cancelled successfully']);
    }
}
