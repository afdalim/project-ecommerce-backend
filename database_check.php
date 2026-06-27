<?php
/**
 * Database Setup Script
 * Run this after: php artisan migrate
 * This script verifies database is set up correctly
 */

try {
    echo "===============================================\n";
    echo "CORAL DAISY - Database Setup Verification\n";
    echo "===============================================\n\n";

    // Check .env file
    if (!file_exists('.env')) {
        echo "❌ Error: .env file not found\n";
        echo "Please copy .env.example to .env and configure it\n";
        exit(1);
    }
    echo "✅ .env file exists\n";

    // Check database connection
    try {
        $pdo = new PDO(
            'mysql:host=' . env('DB_HOST') . ';port=' . env('DB_PORT'),
            env('DB_USERNAME'),
            env('DB_PASSWORD')
        );
        echo "✅ Database connection successful\n";
    } catch (Exception $e) {
        echo "❌ Database connection failed: " . $e->getMessage() . "\n";
        exit(1);
    }

    // Check if database exists
    try {
        $pdo = new PDO(
            'mysql:host=' . env('DB_HOST') . ';port=' . env('DB_PORT') . ';dbname=' . env('DB_DATABASE'),
            env('DB_USERNAME'),
            env('DB_PASSWORD')
        );
        echo "✅ Database '" . env('DB_DATABASE') . "' exists\n";
    } catch (Exception $e) {
        echo "❌ Database '" . env('DB_DATABASE') . "' not found\n";
        echo "Run: php artisan migrate\n";
        exit(1);
    }

    // Check tables
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "✅ Found " . count($tables) . " tables\n";

    if (count($tables) > 0) {
        echo "\nDatabase tables:\n";
        foreach ($tables as $table) {
            echo "  - $table\n";
        }
    }

    echo "\n✅ Database setup verification complete!\n";
    echo "\nYou can now run:\n";
    echo "  php artisan serve\n";
    echo "\nThen visit: http://localhost:8000\n";
    echo "\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
