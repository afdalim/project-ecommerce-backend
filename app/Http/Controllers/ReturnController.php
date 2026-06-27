<?php

namespace App\Http\Controllers;


use App\Models\Return_;
use App\Models\Order;
use Illuminate\Http\Request;


class ReturnController extends Controller
{



    public function initiateReturn(Request $request)
    {


        $request->validate([


            'order_id' =>
            'required|exists:orders,id',


            'reason' =>
            'required|in:defective,not_as_described,wrong_item,changed_mind,other',


            'description' =>
            'required|string|min:10',


            'image' =>
            'nullable|image|mimes:jpg,jpeg,png|max:2048',


        ]);






        $order =
        Order::where(
            'id',
            $request->order_id
        )
        ->where(
            'user_id',
            auth()->id()
        )
        ->first();





        if(!$order){


            return response()->json([


                'message' =>
                'Order tidak ditemukan'


            ],404);


        }









        $imagePath =
        null;





        if(
            $request->hasFile('image')
        ){



            $imagePath =
            '/storage/' .
            $request
            ->file('image')
            ->store(
                'returns',
                'public'
            );



        }









        $return =
        Return_::create([


            'return_number' =>
            'RET-' . time(),



            'order_id' =>
            $request->order_id,



            'user_id' =>
            auth()->id(),



            'reason' =>
            $request->reason,



            'description' =>
            $request->description,



            'image_url' =>
            $imagePath,



            'refund_amount' =>
0,

'refund_status' =>
'pending',

'requested_at' =>
now(),

'status' =>
'requested'

        ]);








        return response()->json([


            'message' =>
            'Return initiated successfully',


            'return' =>
            $return


        ],201);



    }











    public function getReturns()
    {



        $returns =
        Return_::where(
            'user_id',
            auth()->id()
        )
        ->with([

            'order',
            'user'

        ])
        ->orderBy(
            'created_at',
            'desc'
        )
        ->paginate(10);






        return response()
        ->json(
            $returns
        );


    }












    public function getReturnDetail($returnId)
    {


        $return =
        Return_::findOrFail(
            $returnId
        );





        if(
            $return->user_id !== auth()->id()
            &&
            !auth()->user()->isAdmin()
        ){



            return response()->json([


                'message' =>
                'Unauthorized'


            ],403);



        }






        $return->load([


            'order',


            'user'


        ]);






        return response()
        ->json(
            $return
        );



    }




}