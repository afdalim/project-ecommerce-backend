<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;


class OrderService
{


    public function createOrder($user, $data)
    {


        return DB::transaction(function () use ($user, $data) {


            $cart =
            $user->cart()
            ->with('items.product')
            ->first();




            if(
                !$cart
                ||
                $cart->items->isEmpty()
            ){


                throw new \Exception(
                    'Cart is empty'
                );


            }





            // ========================
            // HITUNG TOTAL
            // ========================


            $subtotal =
            0;



            foreach($cart->items as $item){


                $subtotal +=
                $item->price
                *
                $item->quantity;


            }





            // Pajak 10%
            $taxRate =
            config(
                'payment.tax_rate',
                0.10
            );



            $tax =
            $subtotal * $taxRate;





            // Ongkir tetap
            $shippingCost =
            10000;





            // Total akhir
            $finalAmount =
            $subtotal
            +
            $tax
            +
            $shippingCost;







            // ========================
            // CREATE ORDER
            // ========================


            $order =
            Order::create([



                'order_number' =>
                'ORD-' . time() . rand(1000,9999),



                'user_id' =>
                $user->id,



                'total_amount' =>
                $subtotal,



                'tax_amount' =>
                $tax,



                'shipping_cost' =>
                $shippingCost,



                'final_amount' =>
                $finalAmount,



                'status' =>
                'pending',



                'payment_status' =>
                'pending',



                'shipping_address' =>
                $data['shipping_address'],



                'billing_address' =>
                $data['billing_address'],



            ]);







            // ========================
            // PINDAH CART KE ORDER ITEM
            // ========================


            foreach($cart->items as $item){



                OrderItem::create([


                    'order_id' =>
                    $order->id,


                    'product_id' =>
                    $item->product_id,


                    'quantity' =>
                    $item->quantity,


                    'price' =>
                    $item->price,


                ]);






                // Kurangi stok produk

                $item->product
                ->decreaseStock(
                    $item->quantity
                );



            }





            // Kosongkan cart setelah checkout

            $cart->clear();




            return $order;



        });


    }









    public function cancelOrder(Order $order)
    {


        return DB::transaction(function () use ($order){



            if(
                !in_array(
                    $order->status,
                    [
                        'pending',
                        'confirmed'
                    ]
                )
            ){


                throw new \Exception(
                    'Cannot cancel this order'
                );


            }




            $order->cancel();





            foreach($order->items as $item){


                $item->product
                ->increaseStock(
                    $item->quantity
                );


            }





            return $order;



        });


    }










    public function shipOrder(
        Order $order,
        $trackingNumber = null
    ){


        $order->update([


            'status' =>
            'shipped',


            'tracking_number' =>
            $trackingNumber,


        ]);




        return $order;


    }










    public function deliverOrder(Order $order)
    {


        $order->markAsDelivered();



        return $order;


    }



}