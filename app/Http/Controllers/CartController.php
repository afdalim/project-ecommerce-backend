<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{

    public function getCart()
    {
        $user = auth()->user();

        $cart = $user
            ->cart()
            ->with('items.product')
            ->first();

        if(!$cart){

            $cart = Cart::create([
                'user_id' => $user->id
            ]);

        }

        return response()->json($cart);
    }





    public function addItem(Request $request)
    {

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);


        $user = auth()->user();


        $product =
        Product::with('stock')
        ->findOrFail($request->product_id);



        if(!$product->stock || $product->stock->available <= 0){

            return response()->json([
                'message' => 'Stock produk habis'
            ],400);

        }



        if($request->quantity > $product->stock->available){

            return response()->json([
                'message' => 'Jumlah melebihi stock tersedia'
            ],400);

        }




        $cart = $user->cart;


        if(!$cart){

            $cart = Cart::create([
                'user_id'=>$user->id
            ]);

        }



        $cart->addItem(
            $product,
            $request->quantity
        );



        return response()->json([
            'message'=>'Item added to cart'
        ],201);

    }







    public function removeItem($itemId)
    {

        $cartItem =
        CartItem::findOrFail($itemId);


        if($cartItem->cart->user_id !== auth()->id()){

            return response()->json([
                'message'=>'Unauthorized'
            ],403);

        }


        $cartItem
        ->cart
        ->removeItem($itemId);



        return response()->json([
            'message'=>'Item removed from cart'
        ]);

    }







    public function updateItem(Request $request,$itemId)
    {

        $request->validate([
            'quantity'=>'required|integer|min:1'
        ]);


        $cartItem =
        CartItem::findOrFail($itemId);



        if($cartItem->cart->user_id !== auth()->id()){

            return response()->json([
                'message'=>'Unauthorized'
            ],403);
        }



        $cartItem
        ->cart
        ->updateItemQuantity(
            $itemId,
            $request->quantity
        );



        return response()->json([
            'message'=>'Item updated'
        ]);

    }







    public function clear()
    {

        $cart =
        auth()->user()->cart;


        if(!$cart){

            return response()->json([
                'message'=>'Cart is empty'
            ]);

        }


        $cart->clear();


        return response()->json([
            'message'=>'Cart cleared'
        ]);

    }


}