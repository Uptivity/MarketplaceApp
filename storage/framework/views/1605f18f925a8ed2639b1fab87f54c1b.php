<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto py-6">
    <h1 class="text-2xl font-bold mb-6">Add New Product</h1>

    <form action="<?php echo e(route('products.store')); ?>" method="POST" enctype="multipart/form-data" class="space-y-4">
        <?php echo csrf_field(); ?>

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
            <input type="date" name="expiry_date" class="w-full border p-2" min="<?php echo e(date('Y-m-d', strtotime('+1 day'))); ?>">
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

<?php $__env->startPush('scripts'); ?>
<script>
    // No scheduling scripts needed anymore
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp\htdocs\BBvb-main\resources\views/products/create.blade.php ENDPATH**/ ?>