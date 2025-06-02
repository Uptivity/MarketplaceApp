<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total_price',
        'status',
    ];
    
    /**
     * Get the user that owns the order.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Get the order items for the order.
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
    
    /**
     * Get the status history for the order.
     */
    public function statusHistory()
    {
        return $this->hasMany(OrderStatusHistory::class)->orderBy('created_at', 'desc');
    }
    
    /**
     * Get the reviews associated with the order.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    
    /**
     * Add a status update to the order
     */
    public function addStatusUpdate($status, $comment = null)
    {
        // Update the order's status
        $this->status = $status;
        $this->save();
        
        // Add a status history entry
        return $this->statusHistory()->create([
            'status' => $status,
            'comment' => $comment,
            'created_by' => auth()->id(),
        ]);
    }
    
    /**
     * Check if the order can be updated to the given status
     */
    public function canUpdateStatus($newStatus)
    {
        // Define order status flow
        $validTransitions = [
            'pending' => ['processing', 'cancelled'],
            'processing' => ['shipped', 'cancelled'],
            'shipped' => ['delivered', 'returned'],
            'delivered' => ['returned', 'completed'],
            'returned' => ['refunded', 'processing'],
            'cancelled' => [],
            'refunded' => [],
            'completed' => [],
        ];
        
        return in_array($newStatus, $validTransitions[$this->status] ?? []);
    }
}
