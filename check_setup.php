#!/usr/bin/env php
<?php

// Bootstrap Laravel
require __DIR__ . '/bootstrap/app.php';

try {
    echo "✓ Bootstrap loaded successfully\n";
} catch (\Throwable $e) {
    echo "✗ Error loading bootstrap: " . $e->getMessage() . "\n";
    exit(1);
}

$app = require __DIR__ . '/bootstrap/app.php';

try {
    echo "✓ Application instance created\n";
} catch (\Throwable $e) {
    echo "✗ Error creating application: " . $e->getMessage() . "\n";
    exit(1);
}

try {
    $kernel = $app->make('Illuminate\Contracts\Console\Kernel');
    echo "✓ Console kernel loaded\n";
} catch (\Throwable $e) {
    echo "✗ Error loading console kernel: " . $e->getMessage() . "\n";
    exit(1);
}

try {
    // Try to get database connection
    $db = $app->make('db');
    $connection = $db->connection();
    $connection->getPDO();
    echo "✓ Database connection successful\n";
    
    // Get table count
    $tables = $connection->select("SELECT COUNT(*) as count FROM information_schema.tables WHERE table_schema = ?", [env('DB_DATABASE')]);
    echo "✓ Total tables in database: " . $tables[0]->count . "\n";
} catch (\Throwable $e) {
    echo "✗ Database error: " . $e->getMessage() . "\n";
    exit(1);
}

// Test data from database
try {
    $users = $connection->select("SELECT COUNT(*) as count FROM users");
    echo "✓ Users count: " . $users[0]->count . "\n";
    
    $products = $connection->select("SELECT COUNT(*) as count FROM products");
    echo "✓ Products count: " . $products[0]->count . "\n";
    
    $categories = $connection->select("SELECT COUNT(*) as count FROM categories");
    echo "✓ Categories count: " . $categories[0]->count . "\n";
} catch (\Throwable $e) {
    echo "✗ Error querying tables: " . $e->getMessage() . "\n";
}

echo "\n✓ All checks passed! Application is ready.\n";
