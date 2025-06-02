

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-2xl font-bold">Your Notifications</h1>
        
        <?php if($notifications->where('read_at', null)->count() > 0): ?>
            <form action="<?php echo e(route('notifications.mark-all-read')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <button type="submit" class="text-blue-600 hover:text-blue-800">
                    Mark all as read
                </button>
            </form>
        <?php endif; ?>
    </div>

    <?php if(session('success')): ?>
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <?php if($notifications->count() > 0): ?>
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <ul class="divide-y divide-gray-200">
                <?php $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li class="relative <?php echo e($notification->read_at ? 'bg-white' : 'bg-blue-50'); ?>">
                        <div class="flex px-4 py-4 sm:px-6">
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center mb-1">
                                    <?php switch($notification->type):
                                        case ('info'): ?>
                                            <div class="bg-blue-100 p-2 rounded-full">
                                                <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </div>
                                            <?php break; ?>
                                        <?php case ('warning'): ?>
                                            <div class="bg-yellow-100 p-2 rounded-full">
                                                <svg class="h-5 w-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                                </svg>
                                            </div>
                                            <?php break; ?>
                                        <?php case ('success'): ?>
                                            <div class="bg-green-100 p-2 rounded-full">
                                                <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </div>
                                            <?php break; ?>
                                        <?php case ('danger'): ?>
                                            <div class="bg-red-100 p-2 rounded-full">
                                                <svg class="h-5 w-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </div>
                                            <?php break; ?>
                                        <?php default: ?>
                                            <div class="bg-gray-100 p-2 rounded-full">
                                                <svg class="h-5 w-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </div>
                                    <?php endswitch; ?>
                                    <p class="ml-3 text-sm font-medium text-gray-900"><?php echo e($notification->title); ?></p>
                                    <span class="ml-auto text-sm text-gray-500"><?php echo e($notification->created_at->diffForHumans()); ?></span>
                                </div>
                                <div class="mt-1 text-sm text-gray-700">
                                    <?php echo e($notification->content); ?>

                                </div>
                            </div>
                            <div class="ml-4 flex-shrink-0 flex items-center space-x-4">
                                <?php if($notification->isUnread()): ?>
                                    <form action="<?php echo e(route('notifications.mark-as-read', $notification)); ?>" method="POST">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="text-blue-600 hover:text-blue-800">
                                            Mark as read
                                        </button>
                                    </form>
                                <?php endif; ?>
                                <form action="<?php echo e(route('notifications.destroy', $notification)); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="text-red-600 hover:text-red-800" 
                                            onclick="return confirm('Are you sure you want to delete this notification?')">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                        <?php if($notification->url): ?>
                            <a href="<?php echo e(route('notifications.mark-as-read', $notification)); ?>" 
                               class="absolute inset-0 z-10" aria-hidden="true">
                            </a>
                        <?php endif; ?>
                    </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
            <div class="p-4">
                <?php echo e($notifications->links()); ?>

            </div>
        </div>
    <?php else: ?>
        <div class="bg-white p-6 rounded-lg shadow text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
            </svg>
            <p class="mt-4 text-gray-500">You have no notifications.</p>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp\htdocs\BBvb-main\resources\views/notifications/index.blade.php ENDPATH**/ ?>