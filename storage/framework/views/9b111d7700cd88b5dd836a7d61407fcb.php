<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marketplace Test</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <style>
        .toast {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 1rem;
            border-radius: 0.375rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            z-index: 50;
            transform: translateY(-100%);
            opacity: 0;
            transition: transform 0.3s ease-out, opacity 0.3s ease-out;
        }
        .toast.show {
            transform: translateY(0);
            opacity: 1;
        }
        .toast-success {
            background-color: #def7ec;
            color: #046c4e;
            border-left: 4px solid #0e9f6e;
        }
        .toast-error {
            background-color: #fde8e8;
            color: #9b1c1c;
            border-left: 4px solid #f05252;
        }
        .toast-warning {
            background-color: #feecdc;
            color: #9a3412;
            border-left: 4px solid #ff5a1f;
        }
    </style>
</head>
<body class="bg-gray-100 text-gray-900">

    <?php echo $__env->make('layouts.navigation', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?> <!-- Keep your existing nav -->

    <!-- Toast Notifications -->
    <?php if(session('success')): ?>
        <div id="successToast" class="toast toast-success">
            <div class="flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span><?php echo e(session('success')); ?></span>
            </div>
        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div id="errorToast" class="toast toast-error">
            <div class="flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                <span><?php echo e(session('error')); ?></span>
            </div>
        </div>
    <?php endif; ?>

    <?php if(session('warning')): ?>
        <div id="warningToast" class="toast toast-warning">
            <div class="flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <span><?php echo e(session('warning')); ?></span>
            </div>
        </div>
    <?php endif; ?>

    <main class="py-8">
        <div class="container mx-auto px-4">
            <?php echo $__env->yieldContent('content'); ?> <!-- Correct slot for normal Blade views -->
        </div>
    </main>

    <script>
        // Show toast notifications
        document.addEventListener('DOMContentLoaded', function() {
            const toasts = document.querySelectorAll('.toast');
            
            toasts.forEach(function(toast) {
                // Show the toast
                setTimeout(function() {
                    toast.classList.add('show');
                }, 100);
                
                // Hide the toast after 5 seconds
                setTimeout(function() {
                    toast.classList.remove('show');
                    // Remove from DOM after animation completes
                    setTimeout(function() {
                        toast.remove();
                    }, 300);
                }, 5000);
            });
        });
    </script>
    
    <!-- Stack for page-specific scripts -->
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH D:\xampp\htdocs\BBvb-main\resources\views/layouts/app.blade.php ENDPATH**/ ?>