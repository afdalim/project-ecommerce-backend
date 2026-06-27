<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $table = 'stock';

    protected $fillable = [
    'product_id',
    'quantity',
    'reserved_quantity',
    'available',
    'reorder_level'
];

    public $timestamps = false;

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function updateAvailable()
{
    $this->available = $this->quantity - $this->reserved_quantity;
    return $this->save();
}

    public function isLowStock()
    {
        return $this->available <= $this->reorder_level;
    }

    public function isOutOfStock()
    {
        return $this->available <= 0;
    }
}
