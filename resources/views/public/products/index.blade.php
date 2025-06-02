@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6">
    <h1 class="text-3xl font-bold mb-6">Marketplace Products</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($products as $product)
            <div class="bg-white p-4 rounded shadow flex flex-col">
                <h2 class="text-xl font-semibold mb-2">{{ $product->title }}</h2>
                <p class="text-gray-600 mb-2">{{ $product->description }}</p>
                <p class="text-gray-800 font-bold mb-2">${{ $product->price }}</p>
                
                @php
                    $isOutOfStock = $product->quantity <= 0;
                    $isLowStock = $product->quantity > 0 && $product->quantity <= 5;
                    $isExpired = $product->expiry_date && $product->expiry_date->isPast();
                    $isExpiringSoon = $product->expiry_date && !$product->expiry_date->isPast() && $product->expiry_date->diffInDays(now()) <= 7;
                @endphp
                
                <div class="mb-4">
                    @if($isOutOfStock)
                        <p class="text-red-500 font-medium">Out of Stock</p>
                    @elseif($isLowStock)
                        <p class="text-orange-500 font-medium">Low Stock: {{ $product->quantity }} left</p>
                    @else
                        <p class="text-sm text-gray-500">Available: {{ $product->quantity }}</p>
                    @endif
                    
                    @if($isExpired)
                        <p class="text-red-500 text-sm mt-1">This product has expired</p>
                    @elseif($isExpiringSoon)
                        <p class="text-orange-500 text-sm mt-1">Expires soon: {{ $product->expiry_date->format('M d, Y') }}</p>
                    @elseif($product->expiry_date)
                        <p class="text-gray-500 text-sm mt-1">Expires: {{ $product->expiry_date->format('M d, Y') }}</p>
                    @endif
                </div>

                <form action="{{ route('cart.add', $product->id) }}" method="POST" class="mt-auto">
                    @csrf
                    <button type="submit" 
                            class="w-full px-4 py-2 rounded {{ $isOutOfStock || $isExpired ? 'bg-gray-400 cursor-not-allowed' : 'bg-green-500 hover:bg-green-600' }} text-white"
                            {{ $isOutOfStock || $isExpired ? 'disabled' : '' }}>
                        {{ $isOutOfStock ? 'Out of Stock' : ($isExpired ? 'Expired' : 'Add to Cart') }}
                    </button>
                </form>
            </div>
        @endforeach
    </div>

    <div class="mt-6">
        {{ $products->links() }}
    </div>
</div>
@endsection
