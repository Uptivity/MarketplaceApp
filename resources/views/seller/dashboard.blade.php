@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6">
    <h1 class="text-3xl font-bold mb-6">Seller Dashboard</h1>
      <!-- Action Buttons -->
    <div class="flex justify-end mb-6">
        <a href="{{ route('seller.reports.inventory') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center mr-2">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            Inventory Report
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded shadow">
            <h2 class="text-lg font-semibold text-gray-600">Total Products</h2>
            <p class="text-3xl font-bold mt-2">{{ $inventoryStats['total_products'] }}</p>
            <a href="{{ route('products.index') }}" class="text-blue-500 text-sm hover:underline">View all</a>
        </div>
        
        <div class="bg-white p-6 rounded shadow">
            <h2 class="text-lg font-semibold {{ $inventoryStats['low_stock_products'] > 0 ? 'text-orange-600' : 'text-gray-600' }}">
                Low Stock Products
            </h2>
            <p class="text-3xl font-bold mt-2 {{ $inventoryStats['low_stock_products'] > 0 ? 'text-orange-600' : '' }}">
                {{ $inventoryStats['low_stock_products'] }}
            </p>
            @if($inventoryStats['low_stock_products'] > 0)
                <a href="#low-stock-section" class="text-orange-500 text-sm hover:underline">View details</a>
            @endif
        </div>
        
        <div class="bg-white p-6 rounded shadow">
            <h2 class="text-lg font-semibold {{ $inventoryStats['out_of_stock_products'] > 0 ? 'text-red-600' : 'text-gray-600' }}">
                Out of Stock
            </h2>
            <p class="text-3xl font-bold mt-2 {{ $inventoryStats['out_of_stock_products'] > 0 ? 'text-red-600' : '' }}">
                {{ $inventoryStats['out_of_stock_products'] }}
            </p>
            @if($inventoryStats['out_of_stock_products'] > 0)
                <a href="{{ route('products.index', ['filter' => 'out_of_stock']) }}" class="text-red-500 text-sm hover:underline">View details</a>
            @endif
        </div>
        
        <div class="bg-white p-6 rounded shadow">
            <h2 class="text-lg font-semibold text-gray-600">Monthly Revenue</h2>
            <p class="text-3xl font-bold mt-2">${{ number_format($salesStats['monthly_revenue'], 2) }}</p>
            <p class="text-sm text-gray-500">From {{ $salesStats['monthly_orders'] }} orders</p>
        </div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Notifications Column -->
        <div class="col-span-1">
            <div class="bg-white rounded shadow mb-6">
                <div class="p-4 border-b">
                    <h2 class="text-xl font-semibold">Recent Notifications</h2>
                </div>
                <div class="p-4">
                    @if($notifications->count() > 0)
                        <ul class="space-y-4">
                            @foreach($notifications as $notification)
                                <li class="p-3 {{ $notification->read_at ? 'bg-white' : 'bg-blue-50' }} rounded border">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h3 class="font-medium">{{ $notification->title }}</h3>
                                            <p class="text-gray-600 text-sm">{{ $notification->content }}</p>
                                        </div>
                                        <span class="text-xs text-gray-500">
                                            {{ $notification->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                    @if($notification->url)
                                        <a href="{{ $notification->url }}" class="text-sm text-blue-500 hover:underline">View details</a>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                        <div class="mt-4">
                            <a href="{{ route('notifications.index') }}" class="text-blue-500 hover:underline text-sm">View all notifications</a>
                        </div>
                    @else
                        <p class="text-gray-600">No new notifications</p>
                    @endif
                </div>
            </div>
            
            <!-- Low Stock Products Section -->
            <div id="low-stock-section" class="bg-white rounded shadow">
                <div class="p-4 border-b">
                    <h2 class="text-xl font-semibold">Low Stock Products</h2>
                </div>
                <div class="p-4">
                    @if($lowStockProducts->count() > 0)
                        <ul class="space-y-4">
                            @foreach($lowStockProducts as $product)
                                <li class="p-3 bg-orange-50 rounded border border-orange-200">
                                    <h3 class="font-medium">{{ $product->title }}</h3>
                                    <div class="flex justify-between items-center mt-2">
                                        <span class="text-orange-600 font-medium">
                                            {{ $product->quantity }} in stock
                                        </span>
                                        <a href="{{ route('products.edit', $product) }}" 
                                           class="text-xs px-2 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">
                                            Update Stock
                                        </a>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                        <div class="mt-4">
                            <a href="{{ route('products.index', ['filter' => 'low_stock']) }}" class="text-blue-500 hover:underline text-sm">View all low stock products</a>
                        </div>
                    @else
                        <p class="text-gray-600">No low stock products</p>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Recent Orders Column -->
        <div class="col-span-1 lg:col-span-2">
            <div class="bg-white rounded shadow">
                <div class="p-4 border-b">
                    <h2 class="text-xl font-semibold">Recent Orders</h2>
                </div>
                <div class="p-4">
                    @if($recentOrders->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="bg-gray-100">
                                        <th class="text-left p-2">Order #</th>
                                        <th class="text-left p-2">Date</th>
                                        <th class="text-left p-2">Customer</th>
                                        <th class="text-right p-2">Amount</th>
                                        <th class="text-center p-2">Status</th>
                                        <th class="text-right p-2">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentOrders as $order)
                                        @php
                                            $orderTotal = 0;
                                            foreach ($order->items as $item) {
                                                $orderTotal += $item->quantity * $item->price;
                                            }
                                        @endphp
                                        <tr class="border-b">
                                            <td class="p-2">{{ $order->id }}</td>
                                            <td class="p-2">{{ $order->created_at->format('M d, Y') }}</td>
                                            <td class="p-2">{{ $order->user->name }}</td>
                                            <td class="p-2 text-right">${{ number_format($orderTotal, 2) }}</td>
                                            <td class="p-2 text-center">
                                                <span class="inline-block px-2 py-1 rounded text-xs 
                                                    @if($order->status == 'pending')
                                                        bg-yellow-100 text-yellow-800
                                                    @elseif($order->status == 'processing')
                                                        bg-blue-100 text-blue-800
                                                    @elseif($order->status == 'completed')
                                                        bg-green-100 text-green-800
                                                    @elseif($order->status == 'cancelled')
                                                        bg-red-100 text-red-800
                                                    @endif
                                                ">
                                                    {{ ucfirst($order->status) }}
                                                </span>
                                            </td>
                                            <td class="p-2 text-right">
                                                <a href="{{ route('seller.orders.show', $order) }}" class="text-blue-500 hover:underline">View</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('seller.orders.index') }}" class="text-blue-500 hover:underline text-sm">View all orders</a>
                        </div>
                    @else
                        <p class="text-gray-600">No recent orders</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
