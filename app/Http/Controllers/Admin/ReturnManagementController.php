<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Return_;
use Illuminate\Http\Request;

class ReturnManagementController extends Controller
{
    public function getAllReturns(Request $request)
    {
        $query = Return_::with([
            'order',
            'user'
        ]);

        if ($request->status) {
            $query->where('status', $request->status);
        }

        return response()->json(
            $query->latest()->paginate(20)
        );
    }

    public function getReturnDetail($returnId)
    {
        $return = Return_::with([
            'order',
            'user'
        ])->findOrFail($returnId);

        return response()->json($return);
    }

    public function approveReturn(Request $request, $returnId)
    {
        $return = Return_::findOrFail($returnId);

        if ($return->status !== 'requested') {
            return response()->json([
                'message' => 'Return cannot be approved',
                'status' => $return->status
            ],400);
        }

        $return->update([
    'status' => 'approved',
    'approved_at' => now(),
    'refund_amount' => $return->order->final_amount,
    'refund_status' => 'pending'
]);

        return response()->json([
            'message' => 'Return approved successfully',
            'return' => $return
        ]);
    }

    public function rejectReturn(Request $request,$returnId)
    {
        $request->validate([
            'reason' => 'required|string'
        ]);

        $return = Return_::findOrFail($returnId);

        if ($return->status !== 'requested') {
            return response()->json([
                'message' => 'Return cannot be rejected'
            ],400);
        }

        $return->update([
            'status' => 'rejected',
            'refund_status' => 'failed'
        ]);

        return response()->json([
            'message' => 'Return rejected successfully',
            'return' => $return
        ]);
    }

    public function completeReturn($returnId)
    {
        $return = Return_::findOrFail($returnId);

        if ($return->status !== 'approved') {
            return response()->json([
                'message' => 'Only approved returns can be completed'
            ],400);
        }

        $return->update([
    'status' => 'completed',
    'refund_status' => 'processed',
    'completed_at' => now(),
]);
        return response()->json([
            'message' => 'Return completed successfully',
            'return' => $return
        ]);
    }
}