

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="mb-6">
        <h1 class="text-2xl font-bold mb-2">Store Performance Analytics</h1>
        <p class="text-gray-600">Comprehensive data about your store's sales and performance</p>
    </div>

    <div class="flex flex-wrap -mx-3 mb-6">
        <!-- Revenue Summary Card -->
        <div class="w-full md:w-1/3 px-3 mb-6">
            <div class="bg-white rounded-lg shadow p-6 h-full">
                <h3 class="text-lg font-medium mb-4 text-gray-900">Revenue Summary</h3>
                <div class="flex justify-between items-center mb-4">
                    <div>
                        <p class="text-gray-500 text-sm">Average Order Value</p>
                        <p class="text-2xl font-bold">$<?php echo e(number_format($averageOrderValue, 2)); ?></p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Monthly Growth</p>
                        <p class="text-2xl font-bold <?php echo e($salesGrowth >= 0 ? 'text-green-600' : 'text-red-600'); ?>">
                            <?php echo e($salesGrowth >= 0 ? '+' : ''); ?><?php echo e(number_format($salesGrowth, 1)); ?>%
                        </p>
                    </div>
                </div>
                
                <div class="w-full bg-gray-100 h-1 mb-4"></div>
                
                <div class="text-sm">
                    <p class="font-medium">Monthly Revenue Trends</p>
                    <div class="h-40 mt-3">
                        <!-- Chart would be rendered here with JavaScript -->
                        <div id="monthly-sales-chart" class="w-full h-full"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Top Products Card -->
        <div class="w-full md:w-1/3 px-3 mb-6">
            <div class="bg-white rounded-lg shadow p-6 h-full">
                <h3 class="text-lg font-medium mb-4 text-gray-900">Top Selling Products</h3>
                
                <?php if($topSellingProducts->isEmpty()): ?>
                    <p class="text-gray-500">No sales data available yet.</p>
                <?php else: ?>
                    <div class="overflow-y-auto max-h-64">
                        <table class="min-w-full">
                            <thead>
                                <tr>
                                    <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider py-2">Product</th>
                                    <th class="text-right text-xs font-medium text-gray-500 uppercase tracking-wider py-2">Sold</th>
                                    <th class="text-right text-xs font-medium text-gray-500 uppercase tracking-wider py-2">Revenue</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $topSellingProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td class="py-2 text-sm font-medium text-gray-900"><?php echo e($product->product_title); ?></td>
                                        <td class="py-2 text-sm text-gray-500 text-right"><?php echo e($product->total_quantity); ?></td>
                                        <td class="py-2 text-sm text-gray-500 text-right">$<?php echo e(number_format($product->total_revenue, 2)); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Top Categories Card -->
        <div class="w-full md:w-1/3 px-3 mb-6">
            <div class="bg-white rounded-lg shadow p-6 h-full">
                <h3 class="text-lg font-medium mb-4 text-gray-900">Top Categories</h3>
                
                <?php if($topSellingCategories->isEmpty()): ?>
                    <p class="text-gray-500">No category data available yet.</p>
                <?php else: ?>
                    <div class="h-40 mb-4">
                        <!-- Chart would be rendered here with JavaScript -->
                        <div id="categories-chart" class="w-full h-full"></div>
                    </div>
                    
                    <div class="overflow-y-auto max-h-36">
                        <table class="min-w-full">
                            <thead>
                                <tr>
                                    <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider py-2">Category</th>
                                    <th class="text-right text-xs font-medium text-gray-500 uppercase tracking-wider py-2">Items Sold</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $topSellingCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td class="py-2 text-sm font-medium text-gray-900"><?php echo e($category->name); ?></td>
                                        <td class="py-2 text-sm text-gray-500 text-right"><?php echo e($category->total_sold); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="flex flex-wrap -mx-3">
        <!-- Daily Sales Chart -->
        <div class="w-full md:w-2/3 px-3 mb-6">
            <div class="bg-white rounded-lg shadow p-6 h-full">
                <h3 class="text-lg font-medium mb-4 text-gray-900">Daily Sales Trend (Last 30 Days)</h3>
                <div class="h-64">
                    <!-- Chart would be rendered here with JavaScript -->
                    <div id="daily-sales-chart" class="w-full h-full"></div>
                </div>
            </div>
        </div>
        
        <!-- Top Sellers -->
        <div class="w-full md:w-1/3 px-3 mb-6">
            <div class="bg-white rounded-lg shadow p-6 h-full">
                <h3 class="text-lg font-medium mb-4 text-gray-900">Top Sellers</h3>
                
                <?php if($salesBySellerChart->isEmpty()): ?>
                    <p class="text-gray-500">No seller data available yet.</p>
                <?php else: ?>
                    <div class="overflow-y-auto max-h-64">
                        <table class="min-w-full">
                            <thead>
                                <tr>
                                    <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider py-2">Seller</th>
                                    <th class="text-right text-xs font-medium text-gray-500 uppercase tracking-wider py-2">Revenue</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $salesBySellerChart; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $seller): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td class="py-2 text-sm font-medium text-gray-900"><?php echo e($seller->name); ?></td>
                                        <td class="py-2 text-sm text-gray-500 text-right">$<?php echo e(number_format($seller->total_revenue, 2)); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Only initialize charts if data is available
        <?php if(isset($dailySales) && count($dailySales) > 0): ?>
        // Daily Sales Chart
        const dailySalesCtx = document.getElementById('daily-sales-chart').getContext('2d');
        new Chart(dailySalesCtx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($dailySales->pluck('formatted_date')->toArray()); ?>,
                datasets: [{
                    label: 'Daily Sales',
                    data: <?php echo json_encode($dailySales->pluck('total')->toArray()); ?>,
                    backgroundColor: 'rgba(59, 130, 246, 0.2)',
                    borderColor: 'rgb(59, 130, 246)',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '$' + value;
                            }
                        }
                    }
                }
            }
        });
        <?php endif; ?>
        
        <?php if(isset($monthlySales) && count($monthlySales) > 0): ?>
        // Monthly Sales Chart
        const monthlySalesCtx = document.getElementById('monthly-sales-chart').getContext('2d');
        new Chart(monthlySalesCtx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($monthlySales->pluck('formatted_month')->toArray()); ?>,
                datasets: [{
                    label: 'Monthly Sales',
                    data: <?php echo json_encode($monthlySales->pluck('total')->toArray()); ?>,
                    backgroundColor: 'rgba(59, 130, 246, 0.7)',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '$' + value;
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false,
                    }
                }
            }
        });
        <?php endif; ?>
        
        <?php if(isset($topSellingCategories) && count($topSellingCategories) > 0): ?>
        // Categories Chart
        const categoriesCtx = document.getElementById('categories-chart').getContext('2d');
        new Chart(categoriesCtx, {
            type: 'doughnut',
            data: {
                labels: <?php echo json_encode($topSellingCategories->pluck('name')->toArray()); ?>,
                datasets: [{
                    data: <?php echo json_encode($topSellingCategories->pluck('total_sold')->toArray()); ?>,
                    backgroundColor: [
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(16, 185, 129, 0.8)',
                        'rgba(245, 158, 11, 0.8)',
                        'rgba(239, 68, 68, 0.8)',
                        'rgba(139, 92, 246, 0.8)',
                    ],
                    borderWidth: 1,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 12,
                            font: {
                                size: 10
                            }
                        }
                    }
                }
            }
        });
        <?php endif; ?>
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp\htdocs\BBvb-main\resources\views/admin/analytics/store.blade.php ENDPATH**/ ?>