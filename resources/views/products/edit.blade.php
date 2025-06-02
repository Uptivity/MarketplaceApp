@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-6">
    <h1 class="text-2xl font-bold mb-6">Edit Product</h1>

    <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label>Title</label>
            <input type="text" name="title" value="{{ old('title', $product->title) }}" class="w-full border p-2" required>
        </div>

        <div>
            <label>Description</label>
            <textarea name="description" class="w-full border p-2">{{ old('description', $product->description) }}</textarea>
        </div>

        <div>
            <label>Price ($)</label>
            <input type="number" name="price" value="{{ old('price', $product->price) }}" class="w-full border p-2" step="0.01" required>
        </div>

        <div>
            <label>Quantity</label>
            <input type="number" name="quantity" value="{{ old('quantity', $product->quantity) }}" class="w-full border p-2" required>
        </div>
        
        <div>
            <label>Low Stock Alert Threshold</label>
            <input type="number" name="low_stock_threshold" value="{{ old('low_stock_threshold', $product->low_stock_threshold ?? 5) }}" class="w-full border p-2" required>
            <p class="text-sm text-gray-500 mt-1">You will be notified when stock falls below this number</p>
        </div>
        
        <div>
            <label>Category</label>
            <input type="text" name="category_name" class="w-full border p-2" placeholder="Enter category name" 
                   value="{{ old('category_name', $product->category ? $product->category->name : '') }}">
            <p class="text-sm text-gray-500 mt-1">Enter a category name. New categories will be created automatically.</p>
        </div>
        
        <div>
            <label>Expiry Date (Optional)</label>
            <input type="date" name="expiry_date" value="{{ old('expiry_date', $product->expiry_date ? $product->expiry_date->format('Y-m-d') : '') }}" class="w-full border p-2">
            <p class="text-sm text-gray-500 mt-1">Leave blank if the product doesn't expire</p>
        </div>
        
        <div>
            <label>Product Image</label>
            @if($product->image_path)
                <div class="mb-2">
                    <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->title }}" class="w-32 h-32 object-cover">
                    <p class="text-sm text-gray-500">Current image</p>
                </div>
            @endif
            <input type="file" name="image" class="w-full border p-2" accept="image/*">
            <p class="text-sm text-gray-500 mt-1">Upload a new image or leave blank to keep current image</p>
        </div>

        <div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Update</button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    // No scheduling scripts needed anymore
</script>
@endpush
@endsection
