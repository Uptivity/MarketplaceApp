

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Sales Reports</h1>
        <a href="<?php echo e(route('admin.dashboard')); ?>" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
            Back to Dashboard
        </a>
    </div>

    <!-- Sales Overview Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4 text-gray-800">Total Sales</h3>
            <p class="text-3xl font-bold">$<?php echo e(number_format(\App\Models\Order::sum('total_price'), 2)); ?></p>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4 text-gray-800">Total Orders</h3>
            <p class="text-3xl font-bold"><?php echo e(number_format(\App\Models\Order::count())); ?></p>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4 text-gray-800">Average Order Value</h3>
            <p class="text-3xl font-bold">
                <?php
                    $orderCount = \App\Models\Order::count();
                    $totalSales = \App\Models\Order::sum('total_price');
                    $avgOrderValue = $orderCount > 0 ? $totalSales / $orderCount : 0;
                ?>
                $<?php echo e(number_format($avgOrderValue, 2)); ?>

            </p>
        </div>
    </div>
    
    <!-- Sales by Status -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">Sales by Status</h2>
        </div>
        <div class="px-6 py-4">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Orders</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Revenue</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php $__currentLoopData = $salesByStatus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                <?php echo e($status->status == 'completed' ? 'bg-green-100 text-green-800' : 
                                   ($status->status == 'cancelled' ? 'bg-red-100 text-red-800' : 
                                   ($status->status == 'shipped' ? 'bg-blue-100 text-blue-800' : 
                                   'bg-yellow-100 text-yellow-800'))); ?>">
                                <?php echo e(ucfirst($status->status)); ?>

                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap"><?php echo e(number_format($status->count)); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap">$<?php echo e(number_format($status->total, 2)); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Top Selling Products -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">Top Selling Products</h2>
        </div>
        <div class="px-6 py-4">
            <?php if(count($topProducts) > 0): ?>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product Name</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Units Sold</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">                        <?php $__currentLoopData = $topProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo e($product->product_title); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo e(number_format($product->total_sold)); ?></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text-gray-500 text-center py-4">No sales data available yet.</p>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Recent Sales -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">Recent Sales (Last 30 Days)</h2>
        </div>
        <div class="px-6 py-4">
            <div class="w-full h-64 bg-white">
                <!-- This is where you would normally add a chart library like Chart.js -->
                <div class="p-4 text-center">
                    <p class="text-gray-500">Sales data visualization would be displayed here.</p>
                    <p class="text-sm mt-2">For implementation, include Chart.js or another charting library to visualize the sales data.</p>
                </div>
                      <div class="overflow-x-auto mt-4">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sales</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php $__empty_1 = true; $__currentLoopData = $weeklyData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap"><?php echo e(\Carbon\Carbon::parse($data->date)->format('M d, Y')); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap">$<?php echo e(number_format($data->daily_sales, 2)); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="2" class="px-6 py-4 text-center text-gray-500">No sales data available for the selected period.</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp\htdocs\BBvb-main\resources\views/admin/reports/sales.blade.php ENDPATH**/ ?>