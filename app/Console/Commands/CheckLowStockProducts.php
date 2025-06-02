<?php

namespace App\Console\Commands;

use App\Models\Notification;
use App\Models\Product;
use Illuminate\Console\Command;

class CheckLowStockProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:check-low-stock';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for low stock products and notify sellers';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for low stock products...');
        
        // Get products with stock below threshold but not zero (which is out of stock)
        $lowStockProducts = Product::whereRaw('quantity > 0 AND quantity <= low_stock_threshold')
            ->whereNull('low_stock_notified_at')
            ->orWhere('low_stock_notified_at', '<', now()->subDays(7)) // Re-notify after 7 days
            ->get();
        
        $notifiedCount = 0;
        
        foreach ($lowStockProducts as $product) {
            // Create notification for the seller
            Notification::create([
                'user_id' => $product->user_id,
                'title' => 'Low Stock Alert',
                'content' => "Your product \"{$product->title}\" is running low on stock. Current quantity: {$product->quantity}",
                'type' => 'low_stock',
                'url' => route('products.edit', $product->id),
            ]);
            
            // Update product to mark as notified
            $product->low_stock_notified_at = now();
            $product->save();
            
            $notifiedCount++;
        }
        
        $this->info("Notified sellers about $notifiedCount low stock products");
    }
}
