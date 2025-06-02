@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6">
    <h1 class="text-2xl font-bold mb-6">My Products</h1>

    @if (session('success'))
        <div class="bg-green-200 text-green-800 p-4 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif
    
    @if (session('warning'))
        <div class="bg-yellow-200 text-yellow-800 p-4 rounded mb-4">
            {{ session('warning') }}
            
            @if (session('import_errors'))
                <div class="mt-2">
                    <button class="text-sm underline" onclick="document.getElementById('import-errors').classList.toggle('hidden')">
                        Show/Hide Errors
                    </button>
                    <ul id="import-errors" class="hidden mt-2 list-disc list-inside">
                        @foreach(session('import_errors') as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    @endif

    <div class="mb-4 flex space-x-4">
        <a href="{{ route('products.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Add New Product</a>
        <a href="{{ route('products.upload') }}" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Upload CSV</a>
        <a href="{{ route('products.export') }}" class="bg-purple-500 text-white px-4 py-2 rounded hover:bg-purple-600 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path>
            </svg>
            Export to CSV
        </a>
    </div>
    
    <div class="mb-4">
        <h2 class="text-lg font-medium text-gray-700">All Products</h2>
    </div>

    <!-- Bulk Actions Form -->
    @if($products->count())
        <div class="mb-4">
            <form action="{{ route('products.bulk-action') }}" method="POST" id="bulk-action-form">
                @csrf
                <div class="flex items-center space-x-2">
                    <select name="action" class="rounded border-gray-300 shadow-sm">
                        <option value="">Bulk Actions</option>
                        <option value="delete">Delete Selected</option>
                        <option value="update-stock">Update Stock</option>
                    </select>
                    <input type="number" name="stock_value" placeholder="New stock value" class="rounded border-gray-300 shadow-sm w-40" min="0">
                    <button type="submit" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Apply</button>
                </div>
            </form>
        </div>
        
        <table class="min-w-full bg-white shadow rounded">
            <thead>
                <tr>
                    <th class="py-2 px-4">
                        <input type="checkbox" id="select-all" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    </th>
                    <th class="py-2 px-4">Image</th>
                    <th class="py-2 px-4">Title</th>
                    <th class="py-2 px-4">Price</th>
                    <th class="py-2 px-4">Quantity</th>
                    <th class="py-2 px-4">Expiry Date</th>
                    <th class="py-2 px-4">Status</th>
                    <th class="py-2 px-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                @php
                    $isExpiringSoon = $product->expiry_date && $product->expiry_date->diffInDays(now()) <= 7;
                    $rowClass = $isExpiringSoon ? 'border-t bg-yellow-50' : 'border-t';
                @endphp
                <tr class="{{ $rowClass }}">
                    <td class="py-2 px-4">
                        <input type="checkbox" name="product_ids[]" value="{{ $product->id }}" form="bulk-action-form" class="product-checkbox rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    </td>
                    <td class="py-2 px-4">
                        @if($product->image_path)
                            <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->title }}" class="w-16 h-16 object-cover rounded">
                        @else
                            <div class="w-16 h-16 bg-gray-200 flex items-center justify-center rounded">
                                <span class="text-gray-500 text-xs">No image</span>
                            </div>
                        @endif
                    </td>
                    <td class="py-2 px-4">{{ $product->title }}</td>
                    <td class="py-2 px-4">${{ $product->price }}</td>
                    <td class="py-2 px-4">
                        <span class="{{ $product->quantity <= $product->low_stock_threshold && $product->quantity > 0 ? 'text-yellow-600' : ($product->quantity == 0 ? 'text-red-600 font-bold' : '') }}">
                            {{ $product->quantity }}
                        </span>
                        @if($product->quantity <= $product->low_stock_threshold && $product->quantity > 0)
                            <span class="ml-1 text-xs bg-yellow-200 text-yellow-800 px-1 rounded">Low Stock</span>
                        @elseif($product->quantity == 0)
                            <span class="ml-1 text-xs bg-red-200 text-red-800 px-1 rounded">Out of Stock</span>
                        @endif
                    </td>
                    <td class="py-2 px-4">
                        @if($product->expiry_date)
                            <span class="{{ $isExpiringSoon ? 'text-red-600 font-semibold' : '' }}">
                                {{ $product->expiry_date->format('M d, Y') }}
                                @if($isExpiringSoon)
                                    <span class="block text-xs text-red-600">Expires soon!</span>
                                @endif
                            </span>
                        @else
                            <span class="text-gray-400">N/A</span>
                        @endif
                    </td>
                    <td class="py-2 px-4">
                        <span class="bg-green-200 text-green-800 text-xs px-2 py-1 rounded">Published</span>
                    </td>
                    <td class="py-2 px-4">
                        <a href="{{ route('products.edit', $product) }}" class="text-blue-600">Edit</a>

                        <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button class="text-red-600 ml-2" onclick="return confirm('Delete this product?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No products yet.</p>
    @endif
</div>

@push('scripts')
<script>
    // Select all checkbox functionality
    document.getElementById('select-all').addEventListener('change', function() {
        const isChecked = this.checked;
        document.querySelectorAll('.product-checkbox').forEach(checkbox => {
            checkbox.checked = isChecked;
        });
    });
    
    // Form validation to ensure products are selected
    document.getElementById('bulk-action-form').addEventListener('submit', function(e) {
        const action = this.elements.action.value;
        const checkedBoxes = document.querySelectorAll('.product-checkbox:checked');
        
        if (!action) {
            e.preventDefault();
            alert('Please select an action to perform.');
            return;
        }
        
        if (checkedBoxes.length === 0) {
            e.preventDefault();
            alert('Please select at least one product.');
            return;
        }
        
        if (action === 'update-stock') {
            const stockValue = this.elements.stock_value.value;
            if (!stockValue) {
                e.preventDefault();
                alert('Please enter a stock value.');
                return;
            }
        }
        
        if (action === 'delete') {
            if (!confirm('Are you sure you want to delete the selected products? This action cannot be undone.')) {
                e.preventDefault();
            }
        }
    });
</script>
@endpush
@endsection
