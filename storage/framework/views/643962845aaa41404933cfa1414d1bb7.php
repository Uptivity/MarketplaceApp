

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Upload Products from CSV</h1>
        <a href="<?php echo e(route('products.index')); ?>" class="bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded">
            Back to Products
        </a>
    </div>

    <?php if(session('error')): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline"><?php echo e(session('error')); ?></span>
        </div>
    <?php endif; ?>

    <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <form action="<?php echo e(route('products.process-upload')); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="csv_file">
                    CSV File (with columns: Title, Price, Quantity, Description)
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline <?php $__errorArgs = ['csv_file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                    id="csv_file" 
                    type="file" 
                    name="csv_file" 
                    accept=".csv,.txt" 
                    required>
                
                <?php $__errorArgs = ['csv_file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-red-500 text-xs italic mt-2"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="bg-gray-100 p-4 mb-6 rounded">
                <h3 class="font-bold mb-2">CSV Format Instructions</h3>
                <p class="mb-2">Your CSV file should contain these columns:</p>                <ul class="list-disc list-inside mb-2">
                    <li><strong>Title</strong> - Product name (required)</li>
                    <li><strong>Price</strong> - Product price, numeric value (required)</li>
                    <li><strong>Quantity</strong> - Amount in stock, whole number (required)</li>
                    <li><strong>Description</strong> - Product description (optional)</li>
                    <li><strong>ExpiryDate</strong> - Product expiry date in YYYY-MM-DD format (optional)</li>
                </ul>
                <p>The first row should be the header with these exact column names.</p>
            </div>

            <div class="flex items-center justify-between">
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                    Upload and Process
                </button>
                <a class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800" href="<?php echo e(route('products.create')); ?>">
                    Add Single Product Instead
                </a>
            </div>
        </form>
    </div>
    
    <div class="mb-6 bg-yellow-50 border border-yellow-200 p-4 rounded">
        <h3 class="text-xl mb-2">Sample CSV Format</h3>
        <pre class="bg-white p-4 overflow-auto rounded"><code>Title,Price,Quantity,Description
Product 1,19.99,100,"This is product 1 description"
Product 2,24.99,50,"Another product description"</code></pre>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp\htdocs\BBvb-main\resources\views/products/upload.blade.php ENDPATH**/ ?>