<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\Return_;
use Illuminate\Http\Request;



class ReturnManagementController extends Controller
{



    public function getAllReturns(Request $request)
    {


        $query =
        Return_::query()
        ->with([

            'order.items.product',

            'user'

        ]);




        if($request->status){


            $query->where(

                'status',

                $request->status

            );


        }




        $returns =
        $query
        ->orderBy(
            'created_at',
            'desc'
        )
        ->paginate(20);




        return response()
        ->json(
            $returns
        );


    }









    public function getReturnDetail($returnId)
    {


        $return =
        Return_::with([

            'order.items.product',

            'user'

        ])
        ->findOrFail(
            $returnId
        );



        return response()
        ->json(
            $return
        );


    }









    public function approveReturn(Request $request,$returnId)
    {


        $return =
        Return_::findOrFail(
            $returnId
        );




        if(
            $return->status !== 'pending'
        ){


            return response()->json([

                'message'=>
                'Return cannot be approved'

            ],400);


        }





        $return->approve();




        $return->update([

            'refund_amount'=>
            $return->order->final_amount

        ]);





        return response()->json([


            'message'=>
            'Return approved successfully',


            'return'=>
            $return


        ]);


    }









    public function rejectReturn(Request $request,$returnId)
    {


        $request->validate([

            'reason'=>
            'required|string'

        ]);




        $return =
        Return_::findOrFail(
            $returnId
        );




        if(
            $return->status !== 'pending'
        ){


            return response()->json([

                'message'=>
                'Return cannot be rejected'

            ],400);


        }




        $return->update([


            'status'=>
            'rejected',


            'refund_status'=>
            'failed'


        ]);





        return response()->json([


            'message'=>
            'Return rejected successfully',


            'return'=>
            $return


        ]);


    }










    public function completeReturn($returnId)
{


    $return =
    Return_::findOrFail(
        $returnId
    );



    if(
        $return->status !== 'approved'
    ){


        return response()->json([

            'message'=>
            'Only approved returns can be completed'

        ],400);


    }





    $return->complete();





    $return->update([


        'refund_status'=>
        'refunded'


    ]);





    return response()->json([


        'message'=>
        'Return completed and refunded successfully',


        'return'=>
        $return


    ]);



}



}