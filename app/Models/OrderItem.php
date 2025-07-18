<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_title',
        'product_price',
        'quantity',
    ];
    
    /**
     * Get the order that owns the order item.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    
    /**
     * Get the product associated with this order item.
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_title', 'title');
    }
}
