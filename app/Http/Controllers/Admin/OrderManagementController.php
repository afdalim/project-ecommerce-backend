<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;


class OrderManagementController extends Controller
{


    public function index()
    {


        $orders =
        Order::with([
            'user',
            'items.product'
        ])
        ->latest()
        ->get();



        return response()
        ->json($orders);


    }






    public function updateStatus(
        Request $request,
        $id
    ){

        $request->validate([

            'status' =>
            'required|in:processing,shipped,delivered'

        ]);



        $order =
        Order::findOrFail($id);



        $order->update([

            'status' =>
            $request->status

        ]);



        return response()->json([

            'message' =>
            'Status berhasil diupdate',


            'order' =>
            $order

        ]);



    }


}