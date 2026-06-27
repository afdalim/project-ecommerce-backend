<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'slug', 'description', 'short_description', 'price', 'cost',
        'sku', 'category_id', 'brand', 'rating', 'total_reviews', 'image_url',
        'is_active', 'is_featured', 'meta_title', 'meta_description', 'meta_keywords',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'price' => 'decimal:2',
        'cost' => 'decimal:2',
        'rating' => 'float',
        'total_reviews' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function stock()
    {
        return $this->hasOne(Stock::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeSearch($query, $term)
    {
        return $query->where('name', 'LIKE', "%{$term}%")
            ->orWhere('description', 'LIKE', "%{$term}%")
            ->orWhere('sku', 'LIKE', "%{$term}%");
    }

    public function scopePriceRange($query, $minPrice, $maxPrice)
    {
        return $query->whereBetween('price', [$minPrice, $maxPrice]);
    }

    public function isInStock()
    {
        return $this->stock && $this->stock->quantity > 0;
    }

    public function getStockQuantity()
    {
        return $this->stock ? $this->stock->quantity : 0;
    }

    public function decreaseStock($quantity)
    {
        if ($this->stock) {
            $this->stock->decrement('quantity', $quantity);
        }
    }

    public function increaseStock($quantity)
    {
        if ($this->stock) {
            $this->stock->increment('quantity', $quantity);
        }
    }

    public function updateRating($newRating)
    {
        $this->increment('total_reviews');
        $totalRating = (($this->rating * ($this->total_reviews - 1)) + $newRating) / $this->total_reviews;
        $this->update(['rating' => $totalRating]);
    }
}
