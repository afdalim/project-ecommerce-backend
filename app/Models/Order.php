<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Return_;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'order_number', 'user_id', 'total_amount', 'discount_amount', 'tax_amount',
        'shipping_cost', 'final_amount', 'status', 'payment_status', 'shipping_address',
        'billing_address', 'tracking_number', 'notes', 'ordered_at', 'shipped_at', 'delivered_at',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'final_amount' => 'decimal:2',
        'shipping_address' => 'array',
        'billing_address' => 'array',
        'ordered_at' => 'datetime',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function returnRequest()
{


    return $this->hasOne(
        Return_::class,
        'order_id'
    );


}
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function returns()
    {
        return $this->hasMany(Return_::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeDelivered($query)
    {
        return $query->where('status', 'delivered');
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function markAsConfirmed()
    {
        $this->update(['status' => 'confirmed', 'payment_status' => 'completed']);
    }

    public function markAsShipped()
    {
        $this->update(['status' => 'shipped', 'shipped_at' => now()]);
    }

    public function markAsDelivered()
    {
        $this->update(['status' => 'delivered', 'delivered_at' => now()]);
    }

    public function cancel()
    {
        $this->update(['status' => 'cancelled']);
    }
}
