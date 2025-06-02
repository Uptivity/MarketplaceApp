<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PublicProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Seller\OrderManagementController;

// ✅ Correct: Public homepage
Route::get('/', [PublicProductController::class, 'index'])->name('public.products.index');

// Search route
Route::get('/search', [App\Http\Controllers\SearchController::class, 'index'])->name('search');

// Product reviews routes
Route::get('/products/{product}/reviews', [App\Http\Controllers\ReviewController::class, 'index'])->name('reviews.index');
Route::middleware(['auth'])->group(function () {
    Route::get('/reviews/create', [App\Http\Controllers\ReviewController::class, 'create'])->name('reviews.create');
    Route::post('/products/{product}/reviews', [App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store');
    
    // Notification routes
    Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/mark-as-read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.mark-as-read');
    Route::post('/notifications/mark-all-read', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::delete('/notifications/{notification}', [App\Http\Controllers\NotificationController::class, 'destroy'])->name('notifications.destroy');
});

// ❌ DELETE or COMMENT OUT this:
// Route::get('/', function () {
//     return view('welcome');
// });

use App\Http\Controllers\RedirectController;

Route::get('/dashboard', [RedirectController::class, 'home'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');


Route::middleware(['auth'])->group(function () {
    // Seller Dashboard
    Route::get('/seller/dashboard', [App\Http\Controllers\Seller\DashboardController::class, 'index'])->name('seller.dashboard');
    
    // Seller Reports
    Route::get('/seller/reports/inventory', [App\Http\Controllers\Seller\ReportController::class, 'inventory'])->name('seller.reports.inventory');
    
    // Admin Routes
    Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
        // Apply the AdminMiddleware to admin routes
        Route::middleware([App\Http\Middleware\AdminMiddleware::class])->group(function () {
            Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
            Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
            Route::get('/settings', [App\Http\Controllers\Admin\SiteSettingsController::class, 'index'])->name('settings.index');
            Route::put('/settings', [App\Http\Controllers\Admin\SiteSettingsController::class, 'update'])->name('settings.update');
            
            // Categories Routes
            Route::resource('categories', App\Http\Controllers\Admin\CategoryController::class);
            
            // Reviews Management
            Route::get('/reviews', [App\Http\Controllers\Admin\ReviewManagementController::class, 'index'])->name('reviews.index');
            Route::post('/reviews/{review}/approve', [App\Http\Controllers\Admin\ReviewManagementController::class, 'approve'])->name('reviews.approve');
            Route::post('/reviews/{review}/reject', [App\Http\Controllers\Admin\ReviewManagementController::class, 'reject'])->name('reviews.reject');
            
            // Reports Routes
            Route::get('/reports/sales', [App\Http\Controllers\Admin\ReportController::class, 'sales'])->name('reports.sales');
            Route::get('/reports/inventory', [App\Http\Controllers\Admin\ReportController::class, 'inventory'])->name('reports.inventory');
            
            // Review Management Routes
            Route::get('/reviews', [App\Http\Controllers\Admin\ReviewManagementController::class, 'index'])->name('reviews.index');
            Route::put('/reviews/{review}/approve', [App\Http\Controllers\Admin\ReviewManagementController::class, 'approve'])->name('reviews.approve');
            Route::delete('/reviews/{review}/reject', [App\Http\Controllers\Admin\ReviewManagementController::class, 'reject'])->name('reviews.reject');
            
            // Analytics Routes
            Route::get('/analytics/store', [App\Http\Controllers\Admin\AnalyticsController::class, 'store'])->name('analytics.store');
            Route::get('/analytics/users', [App\Http\Controllers\Admin\AnalyticsController::class, 'users'])->name('analytics.users');
            Route::get('/analytics', [App\Http\Controllers\Admin\AnalyticsController::class, 'index'])->name('analytics.index');
        });
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Group routes that require login
Route::middleware(['auth'])->group(function () {
    // Seller Product Management Routes
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::get('/products/upload/csv', [ProductController::class, 'showUploadForm'])->name('products.upload');
    Route::post('/products/process-upload', [ProductController::class, 'processUpload'])->name('products.process-upload');
    Route::get('/products/export', [ProductController::class, 'export'])->name('products.export');
    Route::post('/products/bulk-action', [ProductController::class, 'bulkAction'])->name('products.bulk-action');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
	Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
	Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
	Route::post('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
	Route::post('/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
});

// Public product routes
Route::get('/category/{category}', [PublicProductController::class, 'byCategory'])->name('public.products.category');
Route::get('/featured', [PublicProductController::class, 'featured'])->name('public.products.featured');

// Add product detail route - MOVED AFTER more specific routes
Route::get('/products/{product}', [PublicProductController::class, 'show'])->name('public.products.show');

// Routes that need authentication
Route::middleware(['auth'])->group(function () {
	// Review routes
    Route::get('/products/{product}/reviews', [App\Http\Controllers\ReviewController::class, 'index'])->name('reviews.index');
    Route::get('/reviews/create', [App\Http\Controllers\ReviewController::class, 'create'])->name('reviews.create');
    Route::post('/products/{product}/reviews', [App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store');
    
	// Buyer order routes
    Route::get('/my-orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/my-orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    
    // Seller order routes
    Route::prefix('seller')->name('seller.')->group(function () {
        Route::get('/orders', [OrderManagementController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [OrderManagementController::class, 'show'])->name('orders.show');
        Route::patch('/orders/{order}/status', [OrderManagementController::class, 'updateStatus'])->name('orders.update-status');
    });
});

require __DIR__.'/auth.php';
