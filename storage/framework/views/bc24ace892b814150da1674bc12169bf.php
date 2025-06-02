<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto py-6">
    <h1 class="text-3xl font-bold mb-6">Marketplace Products</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="bg-white p-4 rounded shadow flex flex-col">
                <h2 class="text-xl font-semibold mb-2"><?php echo e($product->title); ?></h2>
                <p class="text-gray-600 mb-2"><?php echo e($product->description); ?></p>
                <p class="text-gray-800 font-bold mb-2">$<?php echo e($product->price); ?></p>
                
                <?php
                    $isOutOfStock = $product->quantity <= 0;
                    $isLowStock = $product->quantity > 0 && $product->quantity <= 5;
                    $isExpired = $product->expiry_date && $product->expiry_date->isPast();
                    $isExpiringSoon = $product->expiry_date && !$product->expiry_date->isPast() && $product->expiry_date->diffInDays(now()) <= 7;
                ?>
                
                <div class="mb-4">
                    <?php if($isOutOfStock): ?>
                        <p class="text-red-500 font-medium">Out of Stock</p>
                    <?php elseif($isLowStock): ?>
                        <p class="text-orange-500 font-medium">Low Stock: <?php echo e($product->quantity); ?> left</p>
                    <?php else: ?>
                        <p class="text-sm text-gray-500">Available: <?php echo e($product->quantity); ?></p>
                    <?php endif; ?>
                    
                    <?php if($isExpired): ?>
                        <p class="text-red-500 text-sm mt-1">This product has expired</p>
                    <?php elseif($isExpiringSoon): ?>
                        <p class="text-orange-500 text-sm mt-1">Expires soon: <?php echo e($product->expiry_date->format('M d, Y')); ?></p>
                    <?php elseif($product->expiry_date): ?>
                        <p class="text-gray-500 text-sm mt-1">Expires: <?php echo e($product->expiry_date->format('M d, Y')); ?></p>
                    <?php endif; ?>
                </div>

                <form action="<?php echo e(route('cart.add', $product->id)); ?>" method="POST" class="mt-auto">
                    <?php echo csrf_field(); ?>
                    <button type="submit" 
                            class="w-full px-4 py-2 rounded <?php echo e($isOutOfStock || $isExpired ? 'bg-gray-400 cursor-not-allowed' : 'bg-green-500 hover:bg-green-600'); ?> text-white"
                            <?php echo e($isOutOfStock || $isExpired ? 'disabled' : ''); ?>>
                        <?php echo e($isOutOfStock ? 'Out of Stock' : ($isExpired ? 'Expired' : 'Add to Cart')); ?>

                    </button>
                </form>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <div class="mt-6">
        <?php echo e($products->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp\htdocs\BBvb-main\resources\views/public/products/index.blade.php ENDPATH**/ ?>