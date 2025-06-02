<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto py-6">
    <h1 class="text-2xl font-bold mb-6">Edit Product</h1>

    <form action="<?php echo e(route('products.update', $product)); ?>" method="POST" enctype="multipart/form-data" class="space-y-4">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <div>
            <label>Title</label>
            <input type="text" name="title" value="<?php echo e(old('title', $product->title)); ?>" class="w-full border p-2" required>
        </div>

        <div>
            <label>Description</label>
            <textarea name="description" class="w-full border p-2"><?php echo e(old('description', $product->description)); ?></textarea>
        </div>

        <div>
            <label>Price ($)</label>
            <input type="number" name="price" value="<?php echo e(old('price', $product->price)); ?>" class="w-full border p-2" step="0.01" required>
        </div>

        <div>
            <label>Quantity</label>
            <input type="number" name="quantity" value="<?php echo e(old('quantity', $product->quantity)); ?>" class="w-full border p-2" required>
        </div>
        
        <div>
            <label>Low Stock Alert Threshold</label>
            <input type="number" name="low_stock_threshold" value="<?php echo e(old('low_stock_threshold', $product->low_stock_threshold ?? 5)); ?>" class="w-full border p-2" required>
            <p class="text-sm text-gray-500 mt-1">You will be notified when stock falls below this number</p>
        </div>
        
        <div>
            <label>Category</label>
            <input type="text" name="category_name" class="w-full border p-2" placeholder="Enter category name" 
                   value="<?php echo e(old('category_name', $product->category ? $product->category->name : '')); ?>">
            <p class="text-sm text-gray-500 mt-1">Enter a category name. New categories will be created automatically.</p>
        </div>
        
        <div>
            <label>Expiry Date (Optional)</label>
            <input type="date" name="expiry_date" value="<?php echo e(old('expiry_date', $product->expiry_date ? $product->expiry_date->format('Y-m-d') : '')); ?>" class="w-full border p-2">
            <p class="text-sm text-gray-500 mt-1">Leave blank if the product doesn't expire</p>
        </div>
        
        <div class="border p-4 rounded bg-gray-50">
            <div class="mb-4">
                <div class="font-medium mb-2">Publication Options</div>
                
                <div class="flex items-center mb-2">
                    <input type="radio" id="publish_now" name="publication_type" value="now" class="mr-2" <?php echo e(!$product->publish_at ? 'checked' : ''); ?>>
                    <label for="publish_now">Publish immediately</label>
                </div>
                
                <div class="flex items-center mb-2">
                    <input type="radio" id="publish_scheduled" name="publication_type" value="scheduled" class="mr-2" <?php echo e($product->publish_at ? 'checked' : ''); ?>>
                    <label for="publish_scheduled">Schedule for future publication</label>
                </div>
                
                <div id="publish_options" class="pl-6 mt-3 <?php echo e($product->publish_at ? '' : 'hidden'); ?>">
                    <div class="mb-3">
                        <label>Publication Date & Time</label>
                        <input type="datetime-local" name="schedule_datetime" id="schedule_datetime" 
                               class="w-full border p-2" min="<?php echo e(date('Y-m-d\TH:i')); ?>"
                               value="<?php echo e($product->publish_at ? $product->publish_at->format('Y-m-d\TH:i') : ''); ?>">
                    </div>
                    <!-- Hidden fields for backward compatibility -->
                    <input type="hidden" name="publish_date" id="publish_date" 
                           value="<?php echo e($product->publish_at ? $product->publish_at->format('Y-m-d') : ''); ?>">
                    <input type="hidden" name="publish_time" id="publish_time"
                           value="<?php echo e($product->publish_at ? $product->publish_at->format('H:i') : ''); ?>">
                </div>
            </div>
            
            <div class="flex items-center">
                <input type="checkbox" id="is_published" name="is_published" class="mr-2" <?php echo e($product->is_published ? 'checked' : ''); ?>>
                <label for="is_published" class="font-medium">Enable publication (uncheck to save as draft)</label>
            </div>
        </div>
        
        <div>
            <label>Product Image</label>
            <?php if($product->image_path): ?>
                <div class="mb-2">
                    <img src="<?php echo e(asset('storage/' . $product->image_path)); ?>" alt="<?php echo e($product->title); ?>" class="w-32 h-32 object-cover">
                    <p class="text-sm text-gray-500">Current image</p>
                </div>
            <?php endif; ?>
            <input type="file" name="image" class="w-full border p-2" accept="image/*">
            <p class="text-sm text-gray-500 mt-1">Upload a new image or leave blank to keep current image</p>
        </div>

        <div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Update</button>
        </div>
    </form>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const publishNow = document.getElementById('publish_now');
        const publishScheduled = document.getElementById('publish_scheduled');
        const publishOptions = document.getElementById('publish_options');
        const publishDate = document.getElementById('publish_date');
        const publishTime = document.getElementById('publish_time');
        const scheduleDateTime = document.getElementById('schedule_datetime');
        
        // Get the current date and a date an hour later for default values
        const now = new Date();
        const hourLater = new Date(now);
        hourLater.setHours(now.getHours() + 1);
        
        // Format datetime for the datetime-local input
        const formattedDateTime = hourLater.getFullYear() + '-' + 
                                 (hourLater.getMonth() + 1).toString().padStart(2, '0') + '-' + 
                                 hourLater.getDate().toString().padStart(2, '0') + 'T' +
                                 hourLater.getHours().toString().padStart(2, '0') + ':' + 
                                 hourLater.getMinutes().toString().padStart(2, '0');
        
        // Update hidden date/time fields when datetime is changed
        scheduleDateTime.addEventListener('change', function() {
            if (this.value) {
                const dateTime = new Date(this.value);
                const dateStr = dateTime.getFullYear() + '-' + 
                               (dateTime.getMonth() + 1).toString().padStart(2, '0') + '-' + 
                               dateTime.getDate().toString().padStart(2, '0');
                const timeStr = dateTime.getHours().toString().padStart(2, '0') + ':' + 
                               dateTime.getMinutes().toString().padStart(2, '0');
                
                publishDate.value = dateStr;
                publishTime.value = timeStr;
            }
        });
        
        function togglePublishOptions() {
            if (publishScheduled.checked) {
                publishOptions.classList.remove('hidden');
                
                // Set default value if empty
                if (!scheduleDateTime.value) {
                    scheduleDateTime.value = formattedDateTime;
                    
                    // Also update the hidden fields
                    const dateTime = new Date(formattedDateTime);
                    const dateStr = dateTime.getFullYear() + '-' + 
                                  (dateTime.getMonth() + 1).toString().padStart(2, '0') + '-' + 
                                  dateTime.getDate().toString().padStart(2, '0');
                    const timeStr = dateTime.getHours().toString().padStart(2, '0') + ':' + 
                                   dateTime.getMinutes().toString().padStart(2, '0');
                    
                    publishDate.value = dateStr;
                    publishTime.value = timeStr;
                }
                
                // Give the browser a moment to display the field before focusing
                setTimeout(() => {
                    scheduleDateTime.focus();
                }, 100);
            } else {
                publishOptions.classList.add('hidden');
                publishDate.value = '';
                publishTime.value = '';
                scheduleDateTime.value = '';
            }
        }
        
        publishNow.addEventListener('change', togglePublishOptions);
        publishScheduled.addEventListener('change', togglePublishOptions);
        
        // If publish scheduled is already checked on page load, make sure fields are visible
        if (publishScheduled.checked) {
            publishOptions.classList.remove('hidden');
        }
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp\htdocs\BBvb-main\resources\views/products/edit.blade.php ENDPATH**/ ?>