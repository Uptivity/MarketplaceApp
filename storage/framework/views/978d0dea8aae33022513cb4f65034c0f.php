

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="flex flex-col md:flex-row md:gap-6">
        <!-- Filters Sidebar -->
        <div class="w-full md:w-1/4 mb-6 md:mb-0">
            <div class="bg-white shadow rounded-lg p-4 sticky top-6">
                <h2 class="text-lg font-semibold mb-4">Filters</h2>
                <form action="<?php echo e(route('search')); ?>" method="GET">
                    <!-- Search Input -->
                    <div class="mb-4">
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                        <input 
                            type="text" 
                            name="search" 
                            value="<?php echo e(request('search')); ?>" 
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
                            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($category->id); ?>" <?php echo e(request('category') == $category->id ? 'selected' : ''); ?>>
                                    <?php echo e($category->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    
                    <!-- Price Range -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Price Range</label>
                        <div class="flex space-x-2">
                            <input 
                                type="number" 
                                name="min_price" 
                                value="<?php echo e(request('min_price')); ?>" 
                                placeholder="Min"
                                min="0"
                                step="0.01"
                                class="w-1/2 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                            >
                            <input 
                                type="number" 
                                name="max_price" 
                                value="<?php echo e(request('max_price')); ?>" 
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
                                <?php echo e(request('in_stock') == '1' ? 'checked' : ''); ?>

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
                            <option value="newest" <?php echo e(request('sort') == 'newest' ? 'selected' : ''); ?>>Newest First</option>
                            <option value="price_low" <?php echo e(request('sort') == 'price_low' ? 'selected' : ''); ?>>Price: Low to High</option>
                            <option value="price_high" <?php echo e(request('sort') == 'price_high' ? 'selected' : ''); ?>>Price: High to Low</option>
                            <option value="oldest" <?php echo e(request('sort') == 'oldest' ? 'selected' : ''); ?>>Oldest First</option>
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
                            href="<?php echo e(route('search')); ?>"
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
                    <p class="text-gray-500"><?php echo e($products->total()); ?> products found</p>
                </div>
            </div>
            
            <!-- Product Grid -->
            <?php if($products->count() > 0): ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="bg-white p-4 rounded shadow flex flex-col">
                            <h2 class="text-xl font-semibold mb-2"><?php echo e($product->title); ?></h2>
                            <p class="text-gray-600 mb-2">
                                <?php echo e(Str::limit($product->description, 100)); ?>

                            </p>
                            <p class="text-gray-800 font-bold mb-2">$<?php echo e(number_format($product->price, 2)); ?></p>
                            
                            <?php
                                $isOutOfStock = $product->quantity <= 0;
                                $isLowStock = $product->quantity > 0 && $product->quantity <= 5;
                                $isExpired = $product->expiry_date && $product->expiry_date->isPast();
                            ?>
                            
                            <div class="mb-3">
                                <?php if($isOutOfStock): ?>
                                    <span class="bg-red-100 text-red-800 text-xs font-semibold px-2 py-1 rounded">Out of Stock</span>
                                <?php elseif($isLowStock): ?>
                                    <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-2 py-1 rounded">Only <?php echo e($product->quantity); ?> left</span>
                                <?php else: ?>
                                    <span class="bg-green-100 text-green-800 text-xs font-semibold px-2 py-1 rounded">In Stock</span>
                                <?php endif; ?>
                                
                                <?php if($isExpired): ?>
                                    <span class="bg-red-100 text-red-800 text-xs font-semibold px-2 py-1 rounded ml-1">Expired</span>
                                <?php endif; ?>
                                
                                <?php if($product->category): ?>
                                    <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2 py-1 rounded ml-1"><?php echo e($product->category->name); ?></span>
                                <?php endif; ?>
                            </div>

                            <div class="mt-auto">
                                <form action="<?php echo e(route('cart.add', $product->id)); ?>" method="POST" class="mt-auto">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" 
                                            class="w-full px-4 py-2 rounded <?php echo e($isOutOfStock || $isExpired ? 'bg-gray-400 cursor-not-allowed' : 'bg-green-500 hover:bg-green-600'); ?> text-white"
                                            <?php echo e($isOutOfStock || $isExpired ? 'disabled' : ''); ?>>
                                        <?php echo e($isOutOfStock ? 'Out of Stock' : ($isExpired ? 'Expired' : 'Add to Cart')); ?>

                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                
                <div class="mt-6">
                    <?php echo e($products->links()); ?>

                </div>
            <?php else: ?>
                <div class="bg-white p-6 rounded-lg shadow text-center">
                    <p class="text-gray-500">No products found matching your criteria.</p>
                    <a href="<?php echo e(route('search')); ?>" class="text-blue-500 hover:underline mt-2 inline-block">Clear all filters</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp\htdocs\BBvb-main\resources\views/search/index.blade.php ENDPATH**/ ?>