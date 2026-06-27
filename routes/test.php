<?php

Route::get('/api/health', function () {
    return response()->json([
        'status' => 'ok',
        'message' => 'Laravel E-Commerce API is running',
        'database' => 'connected',
        'timestamp' => now()->toIso8601String()
    ]);
});

Route::get('/api/test-db', function () {
    try {
        $users = \DB::select("SELECT COUNT(*) as count FROM users");
        $products = \DB::select("SELECT COUNT(*) as count FROM products");
        $categories = \DB::select("SELECT COUNT(*) as count FROM categories");
        
        return response()->json([
            'status' => 'ok',
            'database' => 'connected',
            'tables' => [
                'users' => $users[0]->count ?? 0,
                'products' => $products[0]->count ?? 0,
                'categories' => $categories[0]->count ?? 0,
            ]
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ], 500);
    }
});
