@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-6">
    <h1 class="text-2xl font-bold mb-6">Add New Product</h1>

    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf

        <div>
            <label>Title</label>
            <input type="text" name="title" class="w-full border p-2" required>
        </div>

        <div>
            <label>Description</label>
            <textarea name="description" class="w-full border p-2"></textarea>
        </div>

        <div>
            <label>Price ($)</label>
            <input type="number" name="price" class="w-full border p-2" step="0.01" required>
        </div>

        <div>
            <label>Quantity</label>
            <input type="number" name="quantity" class="w-full border p-2" required>
        </div>
        
        <div>
            <label>Low Stock Alert Threshold</label>
            <input type="number" name="low_stock_threshold" value="5" class="w-full border p-2" required>
            <p class="text-sm text-gray-500 mt-1">You will be notified when stock falls below this number</p>
        </div>

        <div>
            <label>Category</label>
            <input type="text" name="category_name" class="w-full border p-2" placeholder="Enter category name">
            <p class="text-sm text-gray-500 mt-1">Enter a category name. New categories will be created automatically.</p>
        </div>
        
        <div>
            <label>Expiry Date (Optional)</label>
            <input type="date" name="expiry_date" class="w-full border p-2" min="{{ date('Y-m-d', strtotime('+1 day')) }}">
            <p class="text-sm text-gray-500 mt-1">Leave blank if the product doesn't expire</p>
        </div>
        
        <div>
            <label>Product Image (Optional)</label>
            <input type="file" name="image" class="w-full border p-2" accept="image/*">
            <p class="text-sm text-gray-500 mt-1">Upload an image of your product (JPEG, PNG, GIF, etc.)</p>
        </div>

        <div>
            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Save</button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    // No scheduling scripts needed anymore
</script>
@endpush
@endsection
