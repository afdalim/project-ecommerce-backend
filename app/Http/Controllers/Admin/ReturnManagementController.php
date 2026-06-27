<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Return_ extends Model
{
    use SoftDeletes;

    protected $table = 'returns';

    protected $fillable = [
        'return_number',
        'order_id',
        'user_id',
        'status',
        'reason',
        'description',
        'image_url',
        'refund_amount',
        'refund_status',
        'requested_at',
        'approved_at',
        'completed_at',
    ];

    protected $casts = [
        'image_url' => 'string',
        'refund_amount' => 'decimal:2',
        'requested_at' => 'datetime',
        'approved_at' => 'datetime',
        'completed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeRequested($query)
    {
        return $query->where('status', 'requested');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function approveReturn(Request $request,$returnId)
{
    $return = Return_::findOrFail($returnId);

    if($return->status != 'requested'){
        return response()->json([
            'message' => 'Return cannot be approved'
        ],400);
    }

    $return->approve();

    $return->update([
        'refund_amount' => $return->order->final_amount
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

    if($return->status != 'requested'){
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
        ], 400);
    }

    $return->update([
        'status' => 'completed',
        'refund_status' => 'refunded',
        'completed_at' => now(),
    ]);

    return response()->json([
        'message' => 'Return completed and refunded successfully',
        'return' => $return
    ]);
}
}