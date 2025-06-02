@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="flex flex-col md:flex-row md:gap-6">
        <!-- Filters Sidebar -->
        <div class="w-full md:w-1/4 mb-6 md:mb-0">
            <div class="bg-white shadow rounded-lg p-4 sticky top-6">
                <h2 class="text-lg font-semibold mb-4">Filters</h2>
                <form action="{{ route('search') }}" method="GET">
                    <!-- Search Input -->
                    <div class="mb-4">
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                        <input 
                            type="text" 
                            name="search" 
                            value="{{ request('search') }}" 
                            placeholder="Search products..."
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                        >
                    </div>
                    
                    <!-- Category Filter -->
                    <div class="mb-4">
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                        <select 
                            name="category" 
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                        >
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Price Range -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Price Range</label>
                        <div class="flex space-x-2">
                            <input 
                                type="number" 
                                name="min_price" 
                                value="{{ request('min_price') }}" 
                                placeholder="Min"
                                min="0"
                                step="0.01"
                                class="w-1/2 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                            >
                            <input 
                                type="number" 
                                name="max_price" 
                                value="{{ request('max_price') }}" 
                                placeholder="Max"
                                min="0"
                                step="0.01"
                                class="w-1/2 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                            >
                        </div>
                    </div>
                    
                    <!-- Availability Filter -->
                    <div class="mb-4">
                        <label class="flex items-center">
                            <input 
                                type="checkbox"
                                name="in_stock" 
                                value="1"
                                {{ request('in_stock') == '1' ? 'checked' : '' }}
                                class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                            >
                            <span class="ml-2 text-sm text-gray-700">In Stock Only</span>
                        </label>
                    </div>
                    
                    <!-- Sort By -->
                    <div class="mb-4">
                        <label for="sort" class="block text-sm font-medium text-gray-700 mb-1">Sort By</label>
                        <select 
                            name="sort" 
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                        >
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest First</option>
                            <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                        </select>
                    </div>
                    
                    <!-- Filter Actions -->
                    <div class="flex space-x-2">
                        <button 
                            type="submit"
                            class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded transition w-full"
                        >
                            Apply Filters
                        </button>
                        <a 
                            href="{{ route('search') }}"
                            class="bg-gray-300 hover:bg-gray-400 text-gray-800 py-2 px-4 rounded transition"
                        >
                            Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Product Results -->
        <div class="w-full md:w-3/4">
            <div class="bg-white shadow rounded-lg p-4 mb-6">
                <div class="flex justify-between items-center">
                    <h1 class="text-2xl font-bold">Search Results</h1>
                    <p class="text-gray-500">{{ $products->total() }} products found</p>
                </div>
            </div>
            
            <!-- Product Grid -->
            @if($products->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($products as $product)
                        <div class="bg-white p-4 rounded shadow flex flex-col">
                            <h2 class="text-xl font-semibold mb-2">{{ $product->title }}</h2>
                            <p class="text-gray-600 mb-2">
                                {{ Str::limit($product->description, 100) }}
                            </p>
                            <p class="text-gray-800 font-bold mb-2">${{ number_format($product->price, 2) }}</p>
                            
                            @php
                                $isOutOfStock = $product->quantity <= 0;
                                $isLowStock = $product->quantity > 0 && $product->quantity <= 5;
                                $isExpired = $product->expiry_date && $product->expiry_date->isPast();
                            @endphp
                            
                            <div class="mb-3">
                                @if($isOutOfStock)
                                    <span class="bg-red-100 text-red-800 text-xs font-semibold px-2 py-1 rounded">Out of Stock</span>
                                @elseif($isLowStock)
                                    <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-2 py-1 rounded">Only {{ $product->quantity }} left</span>
                                @else
                                    <span class="bg-green-100 text-green-800 text-xs font-semibold px-2 py-1 rounded">In Stock</span>
                                @endif
                                
                                @if($isExpired)
                                    <span class="bg-red-100 text-red-800 text-xs font-semibold px-2 py-1 rounded ml-1">Expired</span>
                                @endif
                                
                                @if($product->category)
                                    <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2 py-1 rounded ml-1">{{ $product->category->name }}</span>
                                @endif
                            </div>

                            <div class="mt-auto">
                                <form action="{{ route('cart.add', $product->id) }}" method="POST" class="mt-auto">
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
                    {{ $products->links() }}
                </div>
            @else
                <div class="bg-white p-6 rounded-lg shadow text-center">
                    <p class="text-gray-500">No products found matching your criteria.</p>
                    <a href="{{ route('search') }}" class="text-blue-500 hover:underline mt-2 inline-block">Clear all filters</a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
