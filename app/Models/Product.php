<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'price',
        'quantity',
        'low_stock_threshold',
        'low_stock_notified_at',
        'expiry_date',
        'image_path',
        'user_id', // seller who created the product
        'category_id', // category of the product
        'is_published', // whether product is published
        'last_low_stock_notification', // when last low stock notification was sent
    ];
    
    /**
     * Get the user (seller) that owns the product
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Get the category that owns the product.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    
    /**
     * Check if this product is in any order items
     */
    public function hasOrderItems()
    {
        // Since there's no direct relationship in the database via product_id,
        // we need to check by comparing titles
        return \App\Models\OrderItem::where('product_title', $this->title)->exists();
    }
    
    /**
     * Get the reviews for the product.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    
    /**
     * Get the average rating for the product.
     */
    public function getAverageRatingAttribute()
    {
        return $this->reviews()->where('is_approved', true)->avg('rating') ?? 0;
    }
    
    /**
     * Get the total number of reviews for the product.
     */
    public function getReviewsCountAttribute()
    {
        return $this->reviews()->where('is_approved', true)->count();
    }
    
    /**
     * Get the stock quantity as an alias for quantity
     */
    public function getStockAttribute()
    {
        return $this->quantity;
    }
    
    /**
     * Scope a query to only include published products.
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }
    
    protected $casts = [
        'expiry_date' => 'date',
        'low_stock_notified_at' => 'datetime',
        'last_low_stock_notification' => 'datetime',
        'is_published' => 'boolean',
    ];
}
