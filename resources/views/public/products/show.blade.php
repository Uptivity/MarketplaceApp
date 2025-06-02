@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <div class="mb-4 text-sm">
            <a href="{{ route('public.products.index') }}" class="text-blue-500 hover:text-blue-700">Products</a>
            <span class="mx-2 text-gray-400">/</span>
            <span class="text-gray-600">{{ $product->title }}</span>
        </div>
        
        <!-- Product Detail -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Product Image -->
                    <div class="flex justify-center">
                        @if(isset($product->image_path) && $product->image_path)
                            <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->title }}" class="max-h-96 object-contain">
                        @else
                            <div class="h-96 w-full bg-gray-200 flex items-center justify-center">
                                <svg class="w-24 h-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Product Info -->
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $product->title }}</h1>
                        
                        <!-- Rating -->
                        <div class="flex items-center mb-4">
                            <div class="flex items-center">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $avgRating)
                                        <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                    @else
                                        <svg class="w-5 h-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                    @endif
                                @endfor
                            </div>
                            <span class="text-gray-600 ml-2">{{ $reviews->count() }} {{ Str::plural('review', $reviews->count()) }}</span>
                        </div>
                        
                        <!-- Price -->
                        <div class="mb-6">
                            <span class="text-3xl font-bold text-blue-700">${{ number_format($product->price, 2) }}</span>
                            @if(isset($product->original_price) && $product->original_price > $product->price)
                                <span class="text-lg text-gray-500 line-through ml-2">${{ number_format($product->original_price, 2) }}</span>
                                <span class="ml-2 bg-red-100 text-red-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                                    {{ round((($product->original_price - $product->price) / $product->original_price) * 100) }}% OFF
                                </span>
                            @endif
                        </div>
                        
                        <!-- Description -->
                        <div class="mb-8">
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Description</h3>
                            <div class="text-gray-700 prose max-w-none">
                                {{ $product->description }}
                            </div>
                        </div>
                          <!-- Stock -->
                        <div class="mb-8">
                            @if($product->quantity > 0)
                                <span class="text-green-600 flex items-center">
                                    <svg class="w-5 h-5 mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                    In Stock ({{ $product->quantity }} available)
                                </span>
                            @else
                                <span class="text-red-600 flex items-center">
                                    <svg class="w-5 h-5 mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                                    Out of Stock
                                </span>
                            @endif
                        </div>
                        
                        <!-- Add to Cart -->
                        @if($product->quantity > 0)
                            <form action="{{ route('cart.add', $product) }}" method="POST" class="mb-4">
                                @csrf                                <div class="flex items-center mb-4">
                                    <label for="quantity" class="mr-4 text-gray-700">Quantity:</label>
                                    <select name="quantity" id="quantity" class="rounded-md shadow-sm border-gray-300 focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                        @for($i = 1; $i <= min($product->quantity, 10); $i++)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                                
                                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    Add to Cart
                                </button>
                            </form>
                        @else
                            <button disabled class="w-full bg-gray-300 text-gray-500 font-bold py-3 px-6 rounded-lg flex items-center justify-center cursor-not-allowed">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                Out of Stock
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Reviews Section -->
        <div class="mt-8 bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Customer Reviews</h2>
                
                @if($reviews->count() > 0)
                    <div class="space-y-6">
                        @foreach($reviews as $review)
                            <div class="border-b pb-6">
                                <div class="flex items-center mb-2">
                                    <div class="flex items-center mr-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $review->rating)
                                                <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                            @else
                                                <svg class="w-4 h-4 text-gray-300" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                            @endif
                                        @endfor                                    </div>
                                </div>
                                
                                <p class="text-gray-600 mb-2">{{ $review->comment }}</p>
                                
                                <div class="text-sm text-gray-500 flex items-center">
                                    <span>By {{ $review->user->name }}</span>
                                    <span class="mx-2">•</span>
                                    <span>{{ $review->created_at->format('M d, Y') }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-gray-600">No reviews yet for this product.</div>
                @endif
                
                @auth
                    <div class="mt-8">
                        <a href="{{ route('reviews.create', ['product' => $product->id]) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                            Write a Review
                        </a>
                    </div>
                @else
                    <div class="mt-8 text-gray-600">
                        Please <a href="{{ route('login') }}" class="text-blue-600 hover:underline">log in</a> to write a review.
                    </div>
                @endauth
            </div>
        </div>
        
        <!-- Similar Products -->
        @if(isset($similarProducts) && $similarProducts->count() > 0)
            <div class="mt-8 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Similar Products</h2>
                    
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                        @foreach($similarProducts as $similarProduct)
                            <div class="border rounded-lg overflow-hidden hover:shadow-lg transition-shadow">
                                <div class="h-40 bg-gray-200">
                                    @if(isset($similarProduct->image_path) && $similarProduct->image_path)
                                        <img src="{{ asset('storage/' . $similarProduct->image_path) }}" alt="{{ $similarProduct->title }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="flex items-center justify-center h-full">
                                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="p-4">
                                    <h3 class="text-gray-900 font-medium text-lg truncate">{{ $similarProduct->title }}</h3>
                                    <p class="text-blue-600 font-bold mt-2">${{ number_format($similarProduct->price, 2) }}</p>
                                    <a href="{{ route('public.products.show', $similarProduct) }}" class="mt-3 block text-center bg-gray-100 hover:bg-gray-200 py-2 px-4 rounded text-sm text-gray-800 font-medium">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
