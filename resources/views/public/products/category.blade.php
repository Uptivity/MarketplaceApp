@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">{{ $category->name }}</h1>
        
        <div>
            <label for="sort-options" class="mr-2">Sort by:</label>
            <select id="sort-options" class="border rounded p-1" onchange="window.location.href = this.value">
                <option value="{{ route('public.products.category', ['category' => $category->id]) }}" {{ request('sort') == null ? 'selected' : '' }}>Latest</option>
                <option value="{{ route('public.products.category', ['category' => $category->id, 'sort' => 'price_low_high']) }}" {{ request('sort') == 'price_low_high' ? 'selected' : '' }}>Price: Low to High</option>
                <option value="{{ route('public.products.category', ['category' => $category->id, 'sort' => 'price_high_low']) }}" {{ request('sort') == 'price_high_low' ? 'selected' : '' }}>Price: High to Low</option>
                <option value="{{ route('public.products.category', ['category' => $category->id, 'sort' => 'oldest']) }}" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest</option>
            </select>
        </div>
    </div>
    
    <div class="flex flex-wrap">
        <!-- Sidebar with category list -->
        <div class="w-full md:w-1/4 pr-0 md:pr-4 mb-6">
            <div class="bg-white p-4 rounded shadow">
                <h2 class="text-lg font-semibold mb-4">Categories</h2>
                <ul class="space-y-2">
                    @foreach($categories as $cat)
                        <li>
                            <a href="{{ route('public.products.category', $cat->id) }}" 
                               class="block px-3 py-2 rounded {{ $category->id == $cat->id ? 'bg-blue-100 text-blue-700' : 'hover:bg-gray-100' }}">
                                {{ $cat->name }}
                                <span class="text-gray-500 text-sm">({{ $cat->products_count ?? $cat->products()->count() }})</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        
        <!-- Products grid -->
        <div class="w-full md:w-3/4">
            @if($products->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($products as $product)
                        <div class="bg-white p-4 rounded shadow flex flex-col">
                            @if($product->image_path)
                                <div class="mb-4">
                                    <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->title }}" 
                                         class="w-full h-48 object-cover rounded">
                                </div>
                            @endif
                            <h2 class="text-xl font-semibold mb-2">{{ $product->title }}</h2>
                            <p class="text-gray-600 mb-2 text-sm line-clamp-2">{{ $product->description }}</p>
                            <p class="text-gray-800 font-bold mb-2">${{ number_format($product->price, 2) }}</p>
                            
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

                            <div class="mt-auto flex space-x-2">
                                <a href="{{ route('public.products.show', $product->id) }}" class="flex-1 px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white text-center rounded">
                                    View Details
                                </a>
                                <form action="{{ route('cart.add', $product->id) }}" method="POST" class="flex-1">
                                    @csrf
                                    <button type="submit" 
                                            class="w-full px-4 py-2 rounded {{ $isOutOfStock || $isExpired ? 'bg-gray-400 cursor-not-allowed' : 'bg-green-500 hover:bg-green-600' }} text-white"
                                            {{ $isOutOfStock || $isExpired ? 'disabled' : '' }}>
                                        {{ $isOutOfStock ? 'Out of Stock' : ($isExpired ? 'Expired' : 'Add to Cart') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="mt-6">
                    {{ $products->withQueryString()->links() }}
                </div>
            @else
                <div class="bg-white p-8 rounded shadow text-center">
                    <h2 class="text-2xl font-semibold mb-2">No products found</h2>
                    <p class="text-gray-600">There are currently no products available in this category.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
