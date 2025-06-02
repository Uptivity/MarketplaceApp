<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;

class ProductController extends Controller
{
    // Show list of products for the logged-in seller
    public function index(Request $request)
    {
        $query = Auth::user()->products();
        $products = $query->latest()->get();
        return view('products.index', compact('products'));
    }

    // Show form to create a new product
    public function create()
    {
        $categories = \App\Models\Category::where('is_active', true)->orderBy('name')->get();
        return view('products.create', compact('categories'));
    }

    // Save the new product
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'low_stock_threshold' => 'required|integer|min:1|max:100',
            'expiry_date' => 'nullable|date|after:today',
            'category_name' => 'nullable|string|max:100',
            'image' => 'nullable|image|max:2048', // max 2MB
        ]);

        $data = $request->except(['image', 'category_name']);
        
        // Handle category by name
        if ($request->filled('category_name')) {
            // Find or create the category
            $category = \App\Models\Category::firstOrCreate(
                ['name' => $request->input('category_name')],
                ['is_active' => true, 'slug' => \Illuminate\Support\Str::slug($request->input('category_name'))]
            );
            $data['category_id'] = $category->id;
        }
        
        // All products are published by default
        $data['is_published'] = true;
        
        // Handle image upload
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $imagePath = $request->file('image')->store('product_images', 'public');
            $data['image_path'] = $imagePath;
        }

        $product = Auth::user()->products()->create($data);
        
        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    // Show form to edit an existing product
    public function edit(Product $product)
    {
        $this->authorizeOwnership($product);
        $categories = \App\Models\Category::where('is_active', true)->orderBy('name')->get();
        return view('products.edit', compact('product', 'categories'));
    }

    // Update the product
    public function update(Request $request, Product $product)
    {
        $this->authorizeOwnership($product);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'low_stock_threshold' => 'required|integer|min:1|max:100',
            'expiry_date' => 'nullable|date|after:today',
            'category_name' => 'nullable|string|max:100',
            'image' => 'nullable|image|max:2048', // max 2MB
        ]);

        $data = $request->except(['image', 'category_name']);
        
        // Handle category by name
        if ($request->filled('category_name')) {
            // Find or create the category
            $category = \App\Models\Category::firstOrCreate(
                ['name' => $request->input('category_name')],
                ['is_active' => true, 'slug' => \Illuminate\Support\Str::slug($request->input('category_name'))]
            );
            $data['category_id'] = $category->id;
        }
        
        // Keep products published
        $data['is_published'] = true;
        
        // Handle image upload
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            // Delete old image if exists
            if ($product->image_path && file_exists(storage_path('app/public/' . $product->image_path))) {
                unlink(storage_path('app/public/' . $product->image_path));
            }
            
            $imagePath = $request->file('image')->store('product_images', 'public');
            $data['image_path'] = $imagePath;
        }

        $product->update($data);
        
        $successMessage = 'Product updated successfully.';

        return redirect()->route('products.index')->with('success', $successMessage);
    }

    // Delete a product
    public function destroy(Product $product)
    {
        $this->authorizeOwnership($product);

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }

    // Extra security: Make sure seller owns the product
    private function authorizeOwnership(Product $product)
    {
        if ($product->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }
    }
    
    /**
     * Show the form for uploading CSV products
     */
    public function showUploadForm()
    {
        return view('products.upload');
    }
    
    /**
     * Process the CSV upload
     */
    public function processUpload(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048'
        ]);
        
        $file = $request->file('csv_file');
        $path = $file->getRealPath();
        
        $records = array_map('str_getcsv', file($path));
        
        // Check if file is empty
        if (count($records) <= 1) {
            return redirect()->back()->with('error', 'CSV file is empty or contains only headers');
        }
        
        // Get headers
        $headers = array_shift($records);
        
        // Check for required columns
        $requiredColumns = ['Title', 'Price', 'Quantity', 'Description'];
        $optionalColumns = ['ExpiryDate', 'Category', 'LowStockThreshold'];
        $missingColumns = [];
        
        foreach ($requiredColumns as $column) {
            if (!in_array($column, $headers)) {
                $missingColumns[] = $column;
            }
        }
        
        if (!empty($missingColumns)) {
            return redirect()->back()->with('error', 'Missing required columns: ' . implode(', ', $missingColumns));
        }
        
        $successCount = 0;
        $errorCount = 0;
        $errors = [];
        
        foreach ($records as $index => $record) {
            if (count($record) !== count($headers)) {
                $errorCount++;
                $errors[] = "Row " . ($index + 2) . " has invalid number of columns";
                continue;
            }
            
            $productData = array_combine($headers, $record);
            
            $validationData = [
                'title' => $productData['Title'],
                'price' => $productData['Price'],
                'quantity' => $productData['Quantity'],
                'description' => $productData['Description'] ?? '',
            ];
            
            if (isset($productData['ExpiryDate']) && !empty($productData['ExpiryDate'])) {
                $validationData['expiry_date'] = $productData['ExpiryDate'];
            }
            
            $validator = Validator::make($validationData, [
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'price' => 'required|numeric|min:0',
                'quantity' => 'required|integer|min:0',
                'expiry_date' => 'nullable|date|after:today',
            ]);
            
            if ($validator->fails()) {
                $errorCount++;
                $errors[] = "Row " . ($index + 2) . ": " . implode(', ', $validator->errors()->all());
                continue;
            }
            
            // Create the product
            $createData = [
                'title' => $productData['Title'],
                'description' => $productData['Description'] ?? '',
                'price' => $productData['Price'],
                'quantity' => $productData['Quantity'],
                'low_stock_threshold' => isset($productData['LowStockThreshold']) ? $productData['LowStockThreshold'] : 5,
            ];
            
            if (isset($productData['ExpiryDate']) && !empty($productData['ExpiryDate'])) {
                $createData['expiry_date'] = $productData['ExpiryDate'];
            }
            
            // Handle category if present
            if (isset($productData['Category']) && !empty($productData['Category'])) {
                // Try to find category by name
                $category = \App\Models\Category::where('name', $productData['Category'])->first();
                if ($category) {
                    $createData['category_id'] = $category->id;
                }
            }
            
            Auth::user()->products()->create($createData);
            
            $successCount++;
        }
        
        $message = $successCount . ' products imported successfully. ';
        if ($errorCount > 0) {
            $message .= $errorCount . ' products failed to import.';
            return redirect()->route('products.index')->with('warning', $message)->with('import_errors', $errors);
        }
        
        return redirect()->route('products.index')->with('success', $message);
    }
    
    /**
     * Export products as CSV
     */
    public function export()
    {
        // Get all products for the authenticated user
        $products = Auth::user()->products()->with('category')->get();
        
        // Define the CSV headers
        $headers = [
            'Title',
            'Description',
            'Price',
            'Quantity',
            'LowStockThreshold',
            'ExpiryDate',
            'Category'
        ];
        
        // Create a file handle for output
        $handle = fopen('php://temp', 'r+');
        
        // Add the header row
        fputcsv($handle, $headers);
        
        // Add products to CSV
        foreach ($products as $product) {
            $row = [
                $product->title,
                $product->description,
                $product->price,
                $product->quantity,
                $product->low_stock_threshold,
                $product->expiry_date ? $product->expiry_date->format('Y-m-d') : '',
                $product->category ? $product->category->name : ''
            ];
            fputcsv($handle, $row);
        }
        
        // Reset the file pointer to the beginning
        rewind($handle);
        
        // Read the content of the CSV
        $content = stream_get_contents($handle);
        fclose($handle);
        
        // Generate filename with current date
        $filename = 'products_' . date('Y-m-d') . '.csv';
        
        // Create the response with CSV content
        $response = Response::make($content, 200);
        $response->header('Content-Type', 'text/csv');
        $response->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
        
        return $response;
    }
    
    /**
     * Handle bulk actions on products
     */
    public function bulkAction(Request $request)
    {
        $action = $request->input('action');
        $productIds = $request->input('product_ids', []);
        
        if (empty($productIds)) {
            return redirect()->route('products.index')->with('warning', 'No products were selected.');
        }
        
        // Make sure the user owns these products
        $products = Auth::user()->products()->whereIn('id', $productIds)->get();
        
        if ($products->count() === 0) {
            return redirect()->route('products.index')->with('warning', 'No valid products were selected.');
        }
        
        // Perform the requested action
        switch ($action) {
            case 'delete':
                // Delete the selected products
                foreach ($products as $product) {
                    // Delete any associated image
                    if ($product->image_path && file_exists(storage_path('app/public/' . $product->image_path))) {
                        unlink(storage_path('app/public/' . $product->image_path));
                    }
                    
                    $product->delete();
                }
                
                return redirect()->route('products.index')
                    ->with('success', count($products) . ' products were deleted successfully.');
                break;
                
            case 'update-stock':
                $stockValue = (int) $request->input('stock_value');
                
                if ($stockValue < 0) {
                    return redirect()->route('products.index')
                        ->with('warning', 'Stock value must be a non-negative number.');
                }
                
                foreach ($products as $product) {
                    $product->quantity = $stockValue;
                    $product->save();
                    
                    // If stock is low, create notification
                    if ($stockValue <= $product->low_stock_threshold && $stockValue > 0) {
                        // Check if we already have a recent notification
                        $recentNotification = \App\Models\Notification::where('user_id', Auth::id())
                            ->where('type', 'low_stock')
                            ->where('content', 'LIKE', '%' . $product->title . '%')
                            ->where('created_at', '>', now()->subDays(1))
                            ->first();
                            
                        if (!$recentNotification) {
                            \App\Models\Notification::create([
                                'user_id' => Auth::id(),
                                'title' => 'Low Stock Alert',
                                'content' => "Your product \"{$product->title}\" has low stock (Currently: {$stockValue}, Threshold: {$product->low_stock_threshold})",
                                'type' => 'low_stock',
                                'url' => route('products.edit', $product),
                            ]);
                        }
                    }
                }
                
                return redirect()->route('products.index')
                    ->with('success', 'Stock level for ' . count($products) . ' products has been updated to ' . $stockValue . '.');
                break;
                
            default:
                return redirect()->route('products.index')
                    ->with('warning', 'Invalid action selected.');
        }
    }
}
