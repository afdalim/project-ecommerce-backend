<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = ['user_id', 'total_items', 'total_price'];

    protected $casts = [
        'total_items' => 'integer',
        'total_price' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    public function addItem($product, $quantity = 1, $price = null)
    {
        $existingItem = $this->items()->where('product_id', $product->id)->first();

        if ($existingItem) {
            $existingItem->increment('quantity', $quantity);
        } else {
            $this->items()->create([
                'product_id' => $product->id,
                'quantity' => $quantity,
                'price' => $price ?? $product->price,
            ]);
        }

        $this->recalculateTotal();
        return $this;
    }

    public function removeItem($cartItemId)
    {
        CartItem::find($cartItemId)->delete();
        $this->recalculateTotal();
        return $this;
    }

    public function updateItemQuantity($cartItemId, $quantity)
    {
        if ($quantity <= 0) {
            return $this->removeItem($cartItemId);
        }

        CartItem::find($cartItemId)->update(['quantity' => $quantity]);
        $this->recalculateTotal();
        return $this;
    }

    public function recalculateTotal()
{
    $items = $this->items()->get();

    $total = $items->sum(function($item) {
        return $item->quantity * $item->price;
    });

    $this->update([
        'total_items' => $items->sum('quantity'),
        'total_price' => $total,
    ]);

    return $this;
}

    public function clear()
    {
        $this->items()->delete();
        $this->update(['total_items' => 0, 'total_price' => 0]);
        return $this;
    }

    public function isEmpty()
    {
        return $this->items()->count() === 0;
    }
}
