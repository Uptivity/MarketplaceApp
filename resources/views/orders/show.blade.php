@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Order #{{ $order->id }}</h1>
        <a href="{{ route('orders.index') }}" class="text-blue-500 hover:underline flex items-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to My Orders
        </a>
    </div>

    <!-- Order Summary Card -->
    <div class="bg-white shadow rounded-lg overflow-hidden mb-6">
        <div class="border-b border-gray-200 px-6 py-4 flex justify-between items-center">
            <h2 class="text-lg font-semibold">Order Summary</h2>
            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                {{ $order->status == 'completed' ? 'bg-green-100 text-green-800' : 
                   ($order->status == 'cancelled' ? 'bg-red-100 text-red-800' : 
                   ($order->status == 'shipped' ? 'bg-blue-100 text-blue-800' : 
                   'bg-yellow-100 text-yellow-800')) }}">
                {{ ucfirst($order->status) }}
            </span>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Order Date</h3>
                    <p class="mt-1 text-sm text-gray-900">{{ $order->created_at->format('M d, Y, h:i A') }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Total Amount</h3>
                    <p class="mt-1 text-sm text-gray-900">${{ number_format($order->total_price, 2) }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Order Status</h3>
                    <p class="mt-1 text-sm text-gray-900">{{ ucfirst($order->status) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Items -->
    <div class="bg-white shadow rounded-lg overflow-hidden mb-6">
        <div class="border-b border-gray-200 px-6 py-4">
            <h2 class="text-lg font-semibold">Order Items</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($order->items as $item)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $item->product_title }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">${{ number_format($item->product_price, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">{{ $item->quantity }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 text-right">${{ number_format($item->product_price * $item->quantity, 2) }}</td>
                        </tr>
                    @endforeach
                    <!-- Order Total -->
                    <tr class="bg-gray-50">
                        <td colspan="3" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 text-right">Total:</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 text-right">${{ number_format($order->total_price, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Order Timeline -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="border-b border-gray-200 px-6 py-4">
            <h2 class="text-lg font-semibold">Order Status Timeline</h2>
        </div>
        <div class="p-6">
            @if($order->statusHistory->count() > 0)
                <div class="flow-root">
                    <ul role="list" class="-mb-8">
                        @foreach($order->statusHistory as $history)
                            <li>
                                <div class="relative pb-8">
                                    @if(!$loop->last)
                                        <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                    @endif
                                    <div class="relative flex space-x-3">
                                        <div>
                                            <span class="h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white bg-blue-500">
                                                <svg class="h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                                </svg>
                                            </span>
                                        </div>
                                        <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                            <div>
                                                <p class="text-sm text-gray-500">Order status changed to <span class="font-medium text-gray-900">{{ ucfirst($history->status) }}</span></p>
                                                @if($history->comment)
                                                    <p class="mt-1 text-sm text-gray-500">{{ $history->comment }}</p>
                                                @endif
                                            </div>
                                            <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                <time datetime="{{ $history->created_at }}">{{ $history->created_at->format('M d, Y, h:i A') }}</time>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @else
                <div class="text-center py-4 text-gray-500">
                    <p>No status updates available for this order yet.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Need Help Section -->
    <div class="mt-6 bg-blue-50 rounded-lg p-6">
        <h3 class="text-lg font-medium text-blue-800 mb-2">Need help with your order?</h3>
        <p class="text-sm text-blue-700 mb-4">If you have any questions or concerns about your order, please contact our customer service.</p>
        <a href="#" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
            Contact Support
        </a>
    </div>
</div>
@endsection
