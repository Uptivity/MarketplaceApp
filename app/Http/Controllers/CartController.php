<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;

class CartController extends Controller
{
    public function add(Request $request, Product $product)
    {
        // Check if product is available
        if ($product->quantity <= 0) {
            return redirect()->back()->with('error', 'Sorry, this product is out of stock.');
        }
        
        // Check if product has expired
        if ($product->expiry_date && $product->expiry_date->isPast()) {
            return redirect()->back()->with('error', 'Sorry, this product has expired.');
        }
        
        $cart = session()->get('cart', []);

        // If the product is already in cart, just increase quantity (with inventory check)
        if (isset($cart[$product->id])) {
            // Check if adding more would exceed available inventory
            if (($cart[$product->id]['quantity'] + 1) > $product->quantity) {
                return redirect()->back()->with('error', 'Cannot add more of this product. Maximum available quantity reached.');
            }
            $cart[$product->id]['quantity']++;
        } else {
            $cart[$product->id] = [
                'title' => $product->title,
                'price' => $product->price,
                'quantity' => 1,
            ];
        }

        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Product added to cart!');
    }
	public function index()
	{
		$cart = session()->get('cart', []);
        $total = 0;
        
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return view('cart.index', compact('cart', 'total'));
	}
	public function remove($id)
	{
    $cart = session()->get('cart', []);

    if (isset($cart[$id])) {
        unset($cart[$id]);
        session()->put('cart', $cart);
    }

    return redirect()->route('cart.index')->with('success', 'Product removed from cart!');
	}
	public function checkout()
	{
    $cart = session()->get('cart', []);

    if (empty($cart)) {
        return redirect()->route('cart.index')->with('error', 'Your cart is empty!');
    }

    // Check product availability before placing order
    $outOfStockItems = [];
    $insufficientQuantityItems = [];
    $expiredItems = [];
    
    foreach ($cart as $productId => $item) {
        $product = Product::find($productId);
        
        if (!$product) {
            $outOfStockItems[] = $item['title'];
            continue;
        }
        
        if ($product->quantity < $item['quantity']) {
            $insufficientQuantityItems[] = [
                'title' => $product->title,
                'requested' => $item['quantity'],
                'available' => $product->quantity
            ];
        }
        
        if ($product->expiry_date && $product->expiry_date->isPast()) {
            $expiredItems[] = $product->title;
        }
    }
    
    if (!empty($outOfStockItems)) {
        return redirect()->route('cart.index')->with('error', 'Some products are no longer available: ' . implode(', ', $outOfStockItems));
    }
    
    if (!empty($expiredItems)) {
        return redirect()->route('cart.index')->with('error', 'Some products have expired: ' . implode(', ', $expiredItems));
    }
    
    if (!empty($insufficientQuantityItems)) {
        $message = 'Some products have insufficient quantity: ';
        foreach ($insufficientQuantityItems as $item) {
            $message .= "{$item['title']} (requested: {$item['requested']}, available: {$item['available']}), ";
        }
        return redirect()->route('cart.index')->with('error', rtrim($message, ', '));
    }
    
    $total = 0;
    foreach ($cart as $productId => $item) {
        $total += $item['price'] * $item['quantity'];
    }

    $order = Order::create([
        'user_id' => auth()->id(),
        'total_price' => $total,
        'status' => 'pending',
    ]);

    foreach ($cart as $productId => $item) {
        // Create order item
        OrderItem::create([
            'order_id' => $order->id,
            'product_title' => $item['title'],
            'product_price' => $item['price'],
            'quantity' => $item['quantity'],
        ]);
        
        // Reduce product inventory
        $product = Product::find($productId);
        if ($product) {
            $product->quantity -= $item['quantity'];
            $product->save();
        }
    }

    session()->forget('cart');

	return redirect('/')->with('success', 'Order placed successfully! Your order number is #' . $order->id);
	}


	
}
