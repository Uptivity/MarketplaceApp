@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6">
    <h1 class="text-3xl font-bold mb-8">Featured Products</h1>

    <!-- Top-rated products section -->
    <div class="mb-12">
        <h2 class="text-2xl font-semibold mb-6">Top Rated Products</h2>
        @if($topRatedProducts->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($topRatedProducts as $product)
                    <div class="bg-white p-4 rounded shadow flex flex-col">
                        @if($product->image_path)
                            <div class="mb-4">
                                <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->title }}" 
                                     class="w-full h-48 object-cover rounded">
                            </div>
                        @endif
                        <div class="flex items-center mb-2">
                            <div class="flex text-yellow-400">
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= round($product->reviews_avg_rating))
                                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                                            <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                                        </svg>
                                    @else
                                        <svg class="w-5 h-5 fill-current text-gray-300" viewBox="0 0 24 24">
                                            <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                                        </svg>
                                    @endif
                                @endfor
                            </div>
                            <span class="ml-1 text-sm text-gray-600">({{ number_format($product->reviews_avg_rating, 1) }})</span>
                        </div>
                        <h2 class="text-xl font-semibold mb-2">{{ $product->title }}</h2>
                        <p class="text-gray-600 mb-2 text-sm line-clamp-2">{{ $product->description }}</p>
                        <p class="text-gray-800 font-bold mb-4">${{ number_format($product->price, 2) }}</p>

                        <div class="mt-auto flex space-x-2">
                            <a href="{{ route('public.products.show', $product->id) }}" class="flex-1 px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white text-center rounded">
                                View Details
                            </a>
                            <form action="{{ route('cart.add', $product->id) }}" method="POST" class="flex-1">
                                @csrf
                                <button type="submit" class="w-full px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded">
                                    Add to Cart
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-600">No top-rated products found.</p>
        @endif
    </div>

    <!-- Newest products section -->
    <div class="mb-12">
        <h2 class="text-2xl font-semibold mb-6">New Arrivals</h2>
        @if($newestProducts->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($newestProducts as $product)
                    <div class="bg-white p-4 rounded shadow flex flex-col">
                        @if($product->image_path)
                            <div class="mb-4">
                                <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->title }}" 
                                     class="w-full h-48 object-cover rounded">
                            </div>
                        @endif
                        <div class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full w-fit mb-2">New</div>
                        <h2 class="text-xl font-semibold mb-2">{{ $product->title }}</h2>
                        <p class="text-gray-600 mb-2 text-sm line-clamp-2">{{ $product->description }}</p>
                        <p class="text-gray-800 font-bold mb-4">${{ number_format($product->price, 2) }}</p>

                        <div class="mt-auto flex space-x-2">
                            <a href="{{ route('public.products.show', $product->id) }}" class="flex-1 px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white text-center rounded">
                                View Details
                            </a>
                            <form action="{{ route('cart.add', $product->id) }}" method="POST" class="flex-1">
                                @csrf
                                <button type="submit" class="w-full px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded">
                                    Add to Cart
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-600">No new products found.</p>
        @endif
    </div>

    <!-- Popular categories section -->
    <div>
        <h2 class="text-2xl font-semibold mb-6">Shop by Category</h2>
        @if($popularCategories->count() > 0)
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                @foreach($popularCategories as $category)
                    <a href="{{ route('public.products.category', $category->id) }}" class="bg-white p-4 rounded shadow hover:shadow-md transition-shadow">
                        <div class="text-center">
                            <h3 class="font-medium mb-2">{{ $category->name }}</h3>
                            <p class="text-sm text-gray-500">{{ $category->products_count }} products</p>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <p class="text-gray-600">No categories found.</p>
        @endif
    </div>
</div>
@endsection
