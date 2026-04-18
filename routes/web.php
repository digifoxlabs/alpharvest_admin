<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FeedController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\MessageController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PermissionManagementController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\RoleManagementController;
use App\Http\Controllers\Admin\StoreController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Website\HomeController;
use App\Http\Controllers\Website\OrderTrackingController;
use App\Http\Controllers\Website\SeoController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/products/{slug}', [HomeController::class, 'show'])->name('products.show');
Route::get('/sitemap.xml', [SeoController::class, 'sitemap'])->name('seo.sitemap');
Route::get('/robots.xml', [SeoController::class, 'robotsXml'])->name('seo.robots-xml');

Route::get('/payments/{payment:reference}', [PaymentController::class, 'show'])->name('payments.show');
Route::get('/orders/track/{token}', [OrderTrackingController::class, 'show'])->name('orders.track');

Route::get('/feeds/meta-products', [FeedController::class, 'metaProducts'])->name('feeds.meta-products');
Route::get('/feeds/meta-placeholder.svg', [FeedController::class, 'metaPlaceholder'])->name('feeds.meta-placeholder');

Route::middleware('guest')->group(function () {
    Route::get('/admin-login', [AuthController::class, 'create'])->name('login');
    Route::post('/admin-login', [AuthController::class, 'store'])->name('login.attempt');
});

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:admin|manager'])
    ->group(function () {
        Route::get('/', fn () => redirect()->route('admin.dashboard'))->name('home');
        Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');
        Route::get('/profile', [AuthController::class, 'profile'])->name('profile.edit');
        Route::put('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');
        Route::get('/users/{user}/profile-photo', [AuthController::class, 'profilePhoto'])->name('users.profile-photo');

        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->middleware('permission:view dashboard')
            ->name('dashboard');
        Route::get('/dashboard/export/orders', [DashboardController::class, 'export'])
            ->middleware('permission:view dashboard')
            ->name('dashboard.export.orders');

        Route::prefix('stores')->name('stores.')->group(function () {
            Route::get('/', [StoreController::class, 'index'])->middleware('permission:view stores')->name('index');
            Route::get('/create', [StoreController::class, 'create'])->middleware('permission:create stores')->name('create');
            Route::post('/', [StoreController::class, 'store'])->middleware('permission:create stores')->name('store');
            Route::get('/{store}/edit', [StoreController::class, 'edit'])->middleware('permission:edit stores')->name('edit');
            Route::put('/{store}', [StoreController::class, 'update'])->middleware('permission:edit stores')->name('update');
            Route::delete('/{store}', [StoreController::class, 'destroy'])->middleware('permission:delete stores')->name('destroy');
        });

        Route::prefix('categories')->name('categories.')->group(function () {
            Route::get('/', [CategoryController::class, 'index'])->middleware('permission:view categories')->name('index');
            Route::get('/create', [CategoryController::class, 'create'])->middleware('permission:create categories')->name('create');
            Route::post('/', [CategoryController::class, 'store'])->middleware('permission:create categories')->name('store');
            Route::patch('/{category}/restore', [CategoryController::class, 'restore'])->middleware('permission:delete categories')->name('restore');
            Route::delete('/{category}/force', [CategoryController::class, 'forceDelete'])->middleware('permission:delete categories')->name('force-delete');
            Route::get('/{category}/edit', [CategoryController::class, 'edit'])->middleware('permission:edit categories')->name('edit');
            Route::put('/{category}', [CategoryController::class, 'update'])->middleware('permission:edit categories')->name('update');
            Route::delete('/{category}', [CategoryController::class, 'destroy'])->middleware('permission:delete categories')->name('destroy');
        });

        Route::prefix('products')->name('products.')->group(function () {
            Route::get('/', [ProductController::class, 'index'])->middleware('permission:view products')->name('index');
            Route::get('/export/excel', [ProductController::class, 'export'])->middleware('permission:view products')->name('export');
            Route::get('/create', [ProductController::class, 'create'])->middleware('permission:create products')->name('create');
            Route::post('/', [ProductController::class, 'store'])->middleware('permission:create products')->name('store');
            Route::patch('/{product}/restore', [ProductController::class, 'restore'])->middleware('permission:delete products')->name('restore');
            Route::delete('/{product}/force', [ProductController::class, 'forceDelete'])->middleware('permission:delete products')->name('force-delete');
            Route::get('/{product}/edit', [ProductController::class, 'edit'])->middleware('permission:edit products')->name('edit');
            Route::put('/{product}', [ProductController::class, 'update'])->middleware('permission:edit products')->name('update');
            Route::delete('/{product}', [ProductController::class, 'destroy'])->middleware('permission:delete products')->name('destroy');
        });

        Route::prefix('orders')->name('orders.')->group(function () {
            Route::get('/', [OrderController::class, 'index'])->middleware('permission:view orders')->name('index');
            Route::get('/export/excel', [OrderController::class, 'export'])->middleware('permission:view orders')->name('export');
            Route::get('/create', [OrderController::class, 'create'])->middleware('permission:create orders')->name('create');
            Route::post('/', [OrderController::class, 'store'])->middleware('permission:create orders')->name('store');
            Route::patch('/{order}/restore', [OrderController::class, 'restore'])->middleware('permission:delete orders')->name('restore');
            Route::delete('/{order}/force', [OrderController::class, 'forceDelete'])->middleware('permission:delete orders')->name('force-delete');
            Route::get('/{order}', [OrderController::class, 'show'])->middleware('permission:view orders')->name('show');
            Route::get('/{order}/edit', [OrderController::class, 'edit'])->middleware('permission:edit orders')->name('edit');
            Route::put('/{order}', [OrderController::class, 'update'])->middleware('permission:edit orders')->name('update');
            Route::delete('/{order}', [OrderController::class, 'destroy'])->middleware('permission:delete orders')->name('destroy');
        });

        Route::prefix('inventory')->name('inventory.')->group(function () {
            Route::get('/', [InventoryController::class, 'index'])->middleware('permission:view inventory')->name('index');
            Route::get('/create', [InventoryController::class, 'create'])->middleware('permission:create inventory')->name('create');
            Route::post('/', [InventoryController::class, 'store'])->middleware('permission:create inventory')->name('store');
            Route::patch('/{transaction}/restore', [InventoryController::class, 'restore'])->middleware('permission:delete inventory')->name('restore');
            Route::delete('/{transaction}/force', [InventoryController::class, 'forceDelete'])->middleware('permission:delete inventory')->name('force-delete');
            Route::delete('/{transaction}', [InventoryController::class, 'destroy'])->middleware('permission:delete inventory')->name('destroy');
        });

        Route::prefix('customers')->name('customers.')->group(function () {
            Route::get('/', [CustomerController::class, 'index'])->middleware('permission:view customers')->name('index');
            Route::patch('/{customer}/restore', [CustomerController::class, 'restore'])->middleware('permission:delete customers')->name('restore');
            Route::delete('/{customer}/force', [CustomerController::class, 'forceDelete'])->middleware('permission:delete customers')->name('force-delete');
            Route::get('/{customer}', [CustomerController::class, 'show'])->middleware('permission:view customers')->name('show');
            Route::get('/{customer}/edit', [CustomerController::class, 'edit'])->middleware('permission:edit customers')->name('edit');
            Route::put('/{customer}', [CustomerController::class, 'update'])->middleware('permission:edit customers')->name('update');
            Route::delete('/{customer}', [CustomerController::class, 'destroy'])->middleware('permission:delete customers')->name('destroy');
        });

        Route::prefix('messages')->name('messages.')->group(function () {
            Route::get('/', [MessageController::class, 'index'])->middleware('permission:view chats')->name('index');
            Route::patch('/{message}/restore', [MessageController::class, 'restore'])->middleware('permission:delete chats')->name('restore');
            Route::delete('/{message}/force', [MessageController::class, 'forceDelete'])->middleware('permission:delete chats')->name('force-delete');
            Route::delete('/{message}', [MessageController::class, 'destroy'])->middleware('permission:delete chats')->name('destroy');
        });

        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [UserManagementController::class, 'index'])->middleware('permission:view users')->name('index');
            Route::get('/create', [UserManagementController::class, 'create'])->middleware('permission:create users')->name('create');
            Route::post('/', [UserManagementController::class, 'store'])->middleware('permission:create users')->name('store');
            Route::get('/{user}/edit', [UserManagementController::class, 'edit'])->middleware('permission:edit users')->name('edit');
            Route::put('/{user}', [UserManagementController::class, 'update'])->middleware('permission:edit users')->name('update');
            Route::delete('/{user}', [UserManagementController::class, 'destroy'])->middleware('permission:delete users')->name('destroy');
        });

        Route::prefix('roles')->name('roles.')->group(function () {
            Route::get('/', [RoleManagementController::class, 'index'])->middleware('permission:view roles')->name('index');
            Route::get('/create', [RoleManagementController::class, 'create'])->middleware('permission:create roles')->name('create');
            Route::post('/', [RoleManagementController::class, 'store'])->middleware('permission:create roles')->name('store');
            Route::get('/{role}/edit', [RoleManagementController::class, 'edit'])->middleware('permission:edit roles')->name('edit');
            Route::put('/{role}', [RoleManagementController::class, 'update'])->middleware('permission:edit roles')->name('update');
            Route::delete('/{role}', [RoleManagementController::class, 'destroy'])->middleware('permission:delete roles')->name('destroy');
        });

        Route::prefix('permissions')->name('permissions.')->group(function () {
            Route::get('/', [PermissionManagementController::class, 'index'])->middleware('permission:view permissions')->name('index');
            Route::get('/create', [PermissionManagementController::class, 'create'])->middleware('permission:create permissions')->name('create');
            Route::post('/', [PermissionManagementController::class, 'store'])->middleware('permission:create permissions')->name('store');
            Route::get('/{permission}/edit', [PermissionManagementController::class, 'edit'])->middleware('permission:edit permissions')->name('edit');
            Route::put('/{permission}', [PermissionManagementController::class, 'update'])->middleware('permission:edit permissions')->name('update');
            Route::delete('/{permission}', [PermissionManagementController::class, 'destroy'])->middleware('permission:delete permissions')->name('destroy');
        });

        Route::middleware(['role:admin'])->group(function () {
            Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

            Route::get('/system-report', function () {
                return view('admin.system-report');
            })->name('system-report');
        });
    });
