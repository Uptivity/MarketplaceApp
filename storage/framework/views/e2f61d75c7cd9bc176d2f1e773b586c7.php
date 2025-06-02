

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Order #<?php echo e($order->id); ?></h1>
        <a href="<?php echo e(route('seller.orders.index')); ?>" class="text-blue-500 hover:underline flex items-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Orders
        </a>
    </div>

    <?php if(session('success')): ?>
        <div class="bg-green-200 text-green-800 p-4 rounded mb-4">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="bg-red-200 text-red-800 p-4 rounded mb-4">
            <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Order Information -->
        <div class="md:col-span-2">
            <div class="bg-white shadow rounded-lg overflow-hidden mb-6">
                <div class="border-b border-gray-200 px-6 py-4">
                    <h2 class="text-lg font-semibold">Order Details</h2>
                </div>
                <div class="px-6 py-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Order Date</p>
                            <p class="font-medium"><?php echo e($order->created_at->format('M d, Y, h:i A')); ?></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Order Total</p>
                            <p class="font-medium">$<?php echo e(number_format($order->total_price, 2)); ?></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Customer</p>
                            <p class="font-medium"><?php echo e($order->user->name); ?></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Email</p>
                            <p class="font-medium"><?php echo e($order->user->email); ?></p>
                        </div>
                        <div class="col-span-2">
                            <p class="text-sm text-gray-500">Status</p>
                            <p class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium
                            <?php echo e($order->status == 'completed' ? 'bg-green-100 text-green-800' : 
                               ($order->status == 'cancelled' ? 'bg-red-100 text-red-800' : 
                               ($order->status == 'shipped' ? 'bg-blue-100 text-blue-800' : 
                               'bg-yellow-100 text-yellow-800'))); ?>">
                                <?php echo e(ucfirst($order->status)); ?>

                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="bg-white shadow rounded-lg overflow-hidden mb-6">
                <div class="border-b border-gray-200 px-6 py-4">
                    <h2 class="text-lg font-semibold">Order Items</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-medium text-gray-900"><?php echo e($item->product_title); ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-500">$<?php echo e(number_format($item->product_price, 2)); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-500"><?php echo e($item->quantity); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">$<?php echo e(number_format($item->product_price * $item->quantity, 2)); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <tr class="bg-gray-50">
                                <td colspan="3" class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">Order Total:</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-bold">$<?php echo e(number_format($order->total_price, 2)); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Update Status Panel -->
        <div class="md:col-span-1">
            <div class="bg-white shadow rounded-lg overflow-hidden mb-6">
                <div class="border-b border-gray-200 px-6 py-4">
                    <h2 class="text-lg font-semibold">Update Status</h2>
                </div>
                <div class="px-6 py-4">
                    <form action="<?php echo e(route('seller.orders.update-status', $order)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PATCH'); ?>
                        
                        <div class="mb-4">
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">New Status</label>
                            <select id="status" name="status" class="block w-full mt-1 rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <?php if($order->status == 'pending'): ?>
                                    <option value="processing">Processing</option>
                                    <option value="cancelled">Cancelled</option>
                                <?php elseif($order->status == 'processing'): ?>
                                    <option value="shipped">Shipped</option>
                                    <option value="cancelled">Cancelled</option>
                                <?php elseif($order->status == 'shipped'): ?>
                                    <option value="delivered">Delivered</option>
                                    <option value="returned">Returned</option>
                                <?php elseif($order->status == 'delivered'): ?>
                                    <option value="completed">Completed</option>
                                    <option value="returned">Returned</option>
                                <?php elseif($order->status == 'returned'): ?>
                                    <option value="refunded">Refunded</option>
                                    <option value="processing">Processing (Re-shipping)</option>
                                <?php endif; ?>
                            </select>
                        </div>
                        
                        <div class="mb-4">
                            <label for="comment" class="block text-sm font-medium text-gray-700 mb-1">Comment (optional)</label>
                            <textarea id="comment" name="comment" rows="3" class="block w-full mt-1 rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" placeholder="Add details about this status update..."></textarea>
                        </div>

                        <div>
                            <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Update Status
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Status History -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="border-b border-gray-200 px-6 py-4">
                    <h2 class="text-lg font-semibold">Status History</h2>
                </div>
                <div class="px-6 py-4">
                    <div class="flow-root">
                        <ul role="list" class="-mb-8">
                            <?php $__currentLoopData = $order->statusHistory; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $history): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li>
                                    <div class="relative pb-8">
                                        <?php if(!$loop->last): ?>
                                            <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                        <?php endif; ?>
                                        <div class="relative flex space-x-3">
                                            <div>
                                                <span class="h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white
                                                    <?php echo e($history->status == 'completed' ? 'bg-green-500' : 
                                                       ($history->status == 'cancelled' ? 'bg-red-500' : 
                                                       ($history->status == 'shipped' ? 'bg-blue-500' : 
                                                       'bg-yellow-500'))); ?>">
                                                    <svg class="h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                <div>
                                                    <p class="text-sm text-gray-500">Status changed to <span class="font-medium text-gray-900"><?php echo e(ucfirst($history->status)); ?></span></p>
                                                    <?php if($history->comment): ?>
                                                        <p class="mt-1 text-sm text-gray-500"><?php echo e($history->comment); ?></p>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                    <time datetime="<?php echo e($history->created_at->format('Y-m-d')); ?>"><?php echo e($history->created_at->format('M d, Y, h:i A')); ?></time><br>
                                                    <span>by <?php echo e($history->creator ? $history->creator->name : 'System'); ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            
                            <?php if($order->statusHistory->isEmpty()): ?>
                                <li class="text-center py-4 text-sm text-gray-500">No status updates yet</li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp\htdocs\BBvb-main\resources\views/seller/orders/show.blade.php ENDPATH**/ ?>