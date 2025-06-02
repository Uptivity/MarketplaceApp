<?php $__env->startSection('content'); ?>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Welcome Section -->
            <div class="mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h2 class="text-xl font-bold mb-4">Welcome back, <?php echo e($user->name); ?>!</h2>
                        <p class="text-gray-600"><?php echo e(\Carbon\Carbon::now()->format('l, F j, Y')); ?></p>
                    </div>
                </div>
            </div>

            <!-- User Profile and Activity -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <!-- User Profile Section -->
                <div class="lg:col-span-1">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg h-full">
                        <div class="p-6">
                            <div class="text-center mb-4">
                                <div class="w-24 h-24 bg-blue-100 rounded-full mx-auto mb-3 flex items-center justify-center">
                                    <?php if(isset($user->profile_photo_path) && $user->profile_photo_path): ?>
                                        <img src="<?php echo e(asset('storage/' . $user->profile_photo_path)); ?>" alt="<?php echo e($user->name); ?>" class="w-20 h-20 rounded-full">
                                    <?php else: ?>
                                        <span class="text-3xl font-bold text-blue-500"><?php echo e(substr($user->name, 0, 1)); ?></span>
                                    <?php endif; ?>
                                </div>
                                <h3 class="text-xl font-semibold"><?php echo e($user->name); ?></h3>
                                <p class="text-gray-500 text-sm">Member since <?php echo e(\Carbon\Carbon::parse($user->created_at)->format('M Y')); ?></p>
                            </div>
                            
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500 mb-2">Profile Completion</h4>
                                <div class="w-full bg-gray-200 rounded-full h-2.5">
                                    <div class="bg-blue-600 h-2.5 rounded-full" style="width: <?php echo e($profileCompletion); ?>%"></div>
                                </div>
                                <p class="text-xs text-gray-500 mt-1"><?php echo e(floor($profileCompletion)); ?>% complete</p>
                            </div>
                            
                            <div class="border-t pt-4 mt-4">
                                <h4 class="text-sm font-medium mb-2">Quick Actions</h4>
                                <ul>
                                    <li class="py-1.5"><a href="<?php echo e(route('profile.edit')); ?>" class="text-blue-500 hover:text-blue-700 text-sm flex items-center"><svg class="w-3.5 h-3.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg> Complete Your Profile</a></li>
                                    <li class="py-1.5"><a href="<?php echo e(route('public.products.index')); ?>" class="text-blue-500 hover:text-blue-700 text-sm flex items-center"><svg class="w-3.5 h-3.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg> Shop Products</a></li>
                                    <li class="py-1.5"><a href="<?php echo e(route('cart.index')); ?>" class="text-blue-500 hover:text-blue-700 text-sm flex items-center"><svg class="w-3.5 h-3.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg> View Your Cart</a></li>
                                    <li class="py-1.5"><a href="<?php echo e(route('notifications.index')); ?>" class="text-blue-500 hover:text-blue-700 text-sm flex items-center"><svg class="w-3.5 h-3.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg> Notifications 
                                        <?php if(isset($user->unread_notifications_count) && $user->unread_notifications_count > 0): ?>
                                            <span class="bg-red-500 text-white text-xs rounded-full px-1 ml-1"><?php echo e($user->unread_notifications_count); ?></span>
                                        <?php endif; ?>
                                    </a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Recent Orders and Activity -->
                <div class="lg:col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                Recent Orders
                            </h3>
                            
                            <?php if(isset($recentOrders) && $recentOrders->count() > 0): ?>
                                <div class="overflow-x-auto">
                                    <table class="w-full">
                                        <thead class="text-left">
                                            <tr class="border-b">
                                                <th class="pb-3 text-gray-500 text-sm">Order ID</th>
                                                <th class="pb-3 text-gray-500 text-sm">Date</th>
                                                <th class="pb-3 text-gray-500 text-sm">Total</th>
                                                <th class="pb-3 text-gray-500 text-sm">Status</th>
                                                <th class="pb-3 text-gray-500 text-sm">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $__currentLoopData = $recentOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr class="border-b">
                                                    <td class="py-3">#<?php echo e($order->id); ?></td>
                                                    <td class="py-3"><?php echo e($order->created_at->format('M d, Y')); ?></td>
                                                    <td class="py-3">$<?php echo e(number_format($order->total_price, 2)); ?></td>
                                                    <td class="py-3">
                                                        <span class="px-2 py-1 text-xs rounded 
                                                            <?php if($order->status == 'completed'): ?> bg-green-100 text-green-800
                                                            <?php elseif($order->status == 'processing'): ?> bg-blue-100 text-blue-800
                                                            <?php elseif($order->status == 'cancelled'): ?> bg-red-100 text-red-800
                                                            <?php else: ?> bg-yellow-100 text-yellow-800 <?php endif; ?>">
                                                            <?php echo e(ucfirst($order->status ?? 'pending')); ?>

                                                        </span>
                                                    </td>
                                                    <td class="py-3">
                                                        <a href="<?php echo e(route('orders.show', $order)); ?>" class="text-sm text-blue-500 hover:text-blue-700">View</a>
                                                        <?php if(in_array($order->status, ['completed', 'delivered'])): ?>
                                                            | <a href="<?php echo e(route('reviews.create', ['order' => $order->id])); ?>" class="text-sm text-green-500 hover:text-green-700">Review</a>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="mt-4">
                                    <a href="<?php echo e(route('orders.index')); ?>" class="text-sm text-blue-500 hover:text-blue-700 flex items-center">
                                        View all orders 
                                        <svg class="w-3.5 h-3.5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                    </a>
                                </div>
                            <?php else: ?>
                                <div class="bg-gray-50 rounded-lg p-6 text-center">
                                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                    <p class="text-gray-500 mb-3">You haven't placed any orders yet.</p>
                                    <a href="<?php echo e(route('public.products.index')); ?>" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none transition ease-in-out duration-150">
                                        Start Shopping
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Recent Notifications -->
                    <div class="mt-6">
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-semibold mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                                    Recent Notifications
                                </h3>
                                
                                <?php if(isset($recentNotifications) && $recentNotifications->count() > 0): ?>
                                    <div class="space-y-3">
                                        <?php $__currentLoopData = $recentNotifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="p-3 rounded-lg <?php echo e($notification->read_at ? 'bg-white' : 'bg-blue-50'); ?> border">
                                                <div class="flex justify-between items-start">
                                                    <div class="flex-1">
                                                        <h4 class="font-medium <?php echo e($notification->read_at ? 'text-gray-800' : 'text-blue-800'); ?>"><?php echo e($notification->title); ?></h4>
                                                        <p class="text-sm text-gray-600 mt-1"><?php echo e(Str::limit($notification->message, 100)); ?></p>
                                                        <div class="text-xs text-gray-500 mt-1"><?php echo e($notification->created_at->diffForHumans()); ?></div>
                                                    </div>
                                                    <?php if(!$notification->read_at): ?>
                                                        <span class="inline-flex rounded-full h-2 w-2 bg-blue-600"></span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                    <div class="mt-4">
                                        <a href="<?php echo e(route('notifications.index')); ?>" class="text-sm text-blue-500 hover:text-blue-700 flex items-center">
                                            View all notifications
                                            <svg class="w-3.5 h-3.5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                        </a>
                                    </div>
                                <?php else: ?>
                                    <p class="text-gray-500">No new notifications.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Recommended Products -->
            <div class="mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a4 4 0 112.76 3.77c.08-.65.14-1.3.14-1.77V6c0-4.42-3.58-8-8-8-4.42 0-8 3.58-8 8v3.28c0 .47.06 1.1.14 1.77A4.001 4.001 0 018 6v7H6a4 4 0 01-4-4V6c0-3.31 2.69-6 6-6s6 2.69 6 6v2c0 .34-.03.67-.08 1A4.002 4.002 0 0116 6v2a4 4 0 01-4 4h-2v1a1 1 0 002 0"></path></svg>
                            Recommended For You
                        </h3>
                        
                        <?php if(isset($recommendedProducts) && $recommendedProducts->count() > 0): ?>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                <?php $__currentLoopData = $recommendedProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="bg-gray-50 rounded-lg overflow-hidden border">
                                        <div class="h-36 bg-gray-200 flex items-center justify-center">
                                            <?php if(isset($product->image_path) && $product->image_path): ?>
                                                <img src="<?php echo e(asset('storage/' . $product->image_path)); ?>" alt="<?php echo e($product->title); ?>" class="h-full w-full object-cover">
                                            <?php else: ?>
                                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            <?php endif; ?>
                                        </div>
                                        <div class="p-3">
                                            <h4 class="font-medium text-gray-900 truncate"><?php echo e($product->title); ?></h4>
                                            <p class="text-blue-600 font-bold mt-1">$<?php echo e(number_format($product->price, 2)); ?></p>
                                            <a href="<?php echo e(route('public.products.show', $product)); ?>" class="mt-2 w-full inline-flex justify-center items-center px-3 py-1.5 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none transition ease-in-out duration-150">
                                                View Product
                                            </a>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php else: ?>
                            <p class="text-gray-500">No product recommendations available yet.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- Categories Information -->
            <div class="mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                            Product Categories
                        </h3>
                        
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                            <h4 class="font-medium text-blue-800 mb-2">Custom Categories</h4>
                            <p class="text-blue-700">This marketplace uses custom categories. When adding products, simply enter any category name and it will be created automatically.</p>
                            <p class="text-blue-700 mt-2">No preset categories exist - you have the freedom to organize your products however you prefer!</p>
                            
                            <?php if(Auth::user()->role === 'seller'): ?>
                                <div class="mt-4">
                                    <a href="<?php echo e(route('products.create')); ?>" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:border-blue-700 focus:shadow-outline-blue transition ease-in-out duration-150">
                                        Add New Product
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Products Needing Reviews -->
            <?php if(isset($pendingReviews) && $pendingReviews->count() > 0): ?>
                <div class="mb-6">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                                Products to Review
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <?php $__currentLoopData = $pendingReviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="bg-yellow-50 rounded-lg overflow-hidden border border-yellow-100 p-4 flex items-center">
                                            <div class="flex-shrink-0 mr-4">
                                                <?php if(isset($item->product) && $item->product && isset($item->product->image_path) && $item->product->image_path): ?>
                                                    <img src="<?php echo e(asset('storage/' . $item->product->image_path)); ?>" alt="<?php echo e($item->product_title); ?>" class="h-16 w-16 object-cover rounded">
                                                <?php else: ?>
                                                    <div class="h-16 w-16 bg-gray-200 rounded flex items-center justify-center">
                                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="flex-1">
                                                <h4 class="font-medium text-gray-900"><?php echo e($item->product_title); ?></h4>
                                                <p class="text-sm text-gray-600 mt-1">Ordered on <?php echo e($order->created_at->format('M d, Y')); ?></p>
                                                <a href="<?php echo e(route('reviews.create', ['product' => isset($item->product) ? $item->product->id : null, 'order' => $order->id])); ?>" class="mt-2 inline-flex items-center text-sm text-yellow-600 hover:text-yellow-800">
                                                    Leave a review
                                                    <svg class="w-3.5 h-3.5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                                </a>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp\htdocs\BBvb-main\resources\views/dashboard.blade.php ENDPATH**/ ?>