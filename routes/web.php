<?php

use Illuminate\Support\Facades\Route;

// Health check endpoints
Route::get('/api/health', function () {
    return response()->json([
        'status' => 'ok',
        'message' => 'Laravel E-Commerce API is running',
        'timestamp' => now()->toIso8601String()
    ]);
});

Route::get('/api/test-db', function () {
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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/products', function () {
    return view('products');
});

Route::get('/cart', function () {
    return view('cart');
});

Route::get('/checkout', function () {
    return view('checkout');
});

Route::get('/login', function () {
    return view('auth.login');
});

Route::get('/register', function () {
    return view('auth.register');
});

Route::get('/admin', function () {
    return view('admin.dashboard');
});

Route::get('/admin', function () {
    return view('admin.dashboard');
});

use Illuminate\Support\Facades\Response;

Route::get('/product-image/{filename}', function ($filename) {

    $path = storage_path(
        'app/public/products/' . $filename
    );

    if (!file_exists($path)) {
        abort(404);
    }

    return Response::file($path);

});

// Catch-all for SPA
Route::fallback(function () {
    return view('welcome');
});

Route::fallback(function () {

    if (request()->is('storage/*')) {
        abort(404);
    }

    return view('welcome');
});