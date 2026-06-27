<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReturnController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductManagementController;
use App\Http\Controllers\Admin\StockController;
use App\Http\Controllers\Admin\ReturnManagementController;
use App\Http\Controllers\Admin\OrderManagementController;
use App\Http\Controllers\Admin\CategoryManagementController;
use App\Http\Controllers\NotificationController;

// Health Check (No Auth Required)
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'message' => 'Laravel E-Commerce API is running',
        'timestamp' => now()->toIso8601String()
    ]);
});

// Database Test (No Auth Required)
Route::get('/test-db', function () {
    try {
        $users = \DB::select("SELECT COUNT(*) as count FROM users");
        $products = \DB::select("SELECT COUNT(*) as count FROM products");
        $categories = \DB::select("SELECT COUNT(*) as count FROM categories");
        $orders = \DB::select("SELECT COUNT(*) as count FROM orders");
        
        return response()->json([
            'status' => 'ok',
            'database' => 'connected',
            'tables' => [
                'users' => $users[0]->count ?? 0,
                'products' => $products[0]->count ?? 0,
                'categories' => $categories[0]->count ?? 0,
                'orders' => $orders[0]->count ?? 0,
            ],
            'timestamp' => now()->toIso8601String()
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ], 500);
    }
});

// Public Routes (No Auth Required)
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/featured', [ProductController::class, 'featured']);
Route::get('/products/{id}', [ProductController::class, 'show']);
Route::get('/products/{id}/reviews', [ProductController::class, 'reviews']);
Route::get('/categories', function () {
    $categories = \App\Models\Category::all();
    return response()->json(['data' => $categories]);
});

// Public Authentication Routes
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);


Route::post(
    '/midtrans/notification',
    [NotificationController::class,'handle']
);


// Protected Customer Routes
Route::middleware(['jwt.auth'])->group(function () {
    
    // Auth
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me', [AuthController::class, 'me']);
    Route::put(
    '/auth/profile',
    [AuthController::class,'updateProfile']
);
    Route::post('/auth/refresh', [AuthController::class, 'refresh']);

    // Products - Add Review (Protected)
    Route::post('/products/{id}/reviews', [ProductController::class, 'addReview']);

    // Cart Management
    Route::get('/cart', [CartController::class, 'getCart']);
    Route::post('/cart/add', [CartController::class, 'addItem']);
    Route::delete('/cart/items/{itemId}', [CartController::class, 'removeItem']);
    Route::put('/cart/items/{itemId}', [CartController::class, 'updateItem']);
    Route::post('/cart/clear', [CartController::class, 'clear']);

    // Orders
    Route::post('/orders', [OrderController::class, 'create']);
    Route::get('/orders', [OrderController::class, 'getOrders']);
    Route::get('/orders/{orderId}', [OrderController::class, 'getOrderDetail']);
    Route::post('/orders/{orderId}/cancel', [OrderController::class, 'cancelOrder']);

// Payments
Route::post('/payments/create', [PaymentController::class, 'createTransaction']);
Route::get('/payments/{paymentId}', [PaymentController::class, 'getPaymentStatus']);

    // Returns
    Route::post('/returns', [ReturnController::class, 'initiateReturn']);
    Route::get('/returns', [ReturnController::class, 'getReturns']);
    Route::get('/returns/{returnId}', [ReturnController::class, 'getReturnDetail']);
});

// Admin Routes
Route::middleware(['jwt.auth', 'admin'])->prefix('admin')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/analytics', [DashboardController::class, 'analytics']);
    // Order Management
Route::get(
    '/orders',
    [
        OrderManagementController::class,
        'index'
    ]
);


Route::put(
    '/orders/{id}/status',
    [
        OrderManagementController::class,
        'updateStatus'
    ]
);

// Category Management
Route::get(
    '/categories',
    [
        CategoryManagementController::class,
        'index'
    ]
);


Route::post(
    '/categories',
    [
        CategoryManagementController::class,
        'store'
    ]
);


Route::put(
    '/categories/{id}',
    [
        CategoryManagementController::class,
        'update'
    ]
);


Route::delete(
    '/categories/{id}',
    [
        CategoryManagementController::class,
        'delete'
    ]
);

    // Product Management
    Route::get('/products', [ProductManagementController::class, 'index']);
    Route::post('/products', [ProductManagementController::class, 'store']);
    Route::put('/products/{id}', [ProductManagementController::class, 'update']);
    Route::post(
    '/products-update/{id}',
    [
        ProductManagementController::class,
        'update'
    ]
);
    Route::delete('/products/{id}', [ProductManagementController::class, 'delete']);
    Route::post('/products/{id}/restore', [ProductManagementController::class, 'restore']);

    // Stock Management
    Route::get('/stock', [StockController::class, 'index']);
    Route::put('/stock/{id}', [StockController::class, 'update']);
    Route::post('/stock/reorder', [StockController::class, 'reorderStock']);
    Route::get('/stock/low-stock', [StockController::class, 'getLowStockProducts']);

    // Returns Management
    Route::get('/returns', [ReturnManagementController::class, 'getAllReturns']);
    Route::get('/returns/{id}', [ReturnManagementController::class, 'getReturnDetail']);
    Route::put('/returns/{id}/approve', [ReturnManagementController::class, 'approveReturn']);
    Route::put('/returns/{id}/reject', [ReturnManagementController::class, 'rejectReturn']);
    Route::put('/returns/{id}/complete', [ReturnManagementController::class, 'completeReturn']);
});

// Fallback
Route::fallback(function () {
    return response()->json(['message' => 'Not Found'], 404);
});

