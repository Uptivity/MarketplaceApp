<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderStatusHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'status',
        'comment',
        'created_by',
    ];
    
    /**
     * Get the order that owns the status update.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    
    /**
     * Get the user that created the status update.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
