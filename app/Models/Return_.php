<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Return_ extends Model
{
    use SoftDeletes;

    protected $table = 'returns';

    protected $fillable = [
        'return_number', 'order_id', 'user_id', 'status', 'reason',
        'description', 'image_url', 'refund_amount', 'refund_status',
        'requested_at', 'approved_at', 'completed_at',
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

    public function scopePending($query)
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

    public function approve()
    {
        $this->update(['status' => 'approved', 'approved_at' => now()]);
    }

    public function reject()
    {
        $this->update(['status' => 'rejected']);
    }

    public function complete()
    {
        $this->update(['status' => 'completed', 'refund_status' => 'processed', 'completed_at' => now()]);
    }
}
