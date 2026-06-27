<?php

require 'vendor/autoload.php';

echo "=== Autoloader Debug ===\n";

// Check if Illuminate is loadable
$classes_to_test = [
    'Illuminate\Foundation\Application',
    'Illuminate\Support\Facades\DB',
    'Illuminate\Database\DatabaseServiceProvider',
    'Illuminate\Filesystem\FilesystemServiceProvider',
];

foreach ($classes_to_test as $class) {
    if (class_exists($class)) {
        echo "✓ $class loaded\n";
    } else {
        echo "✗ $class NOT loaded\n";
    }
}

echo "\n=== Autoloader Info ===\n";

// Check what the autoloader loaded
$composer_autoload_static = \Composer\Autoload\ComposerStaticInitsomething::class;
// Get the function_exists for class mapper
$psr4 = require 'vendor/composer/autoload_psr4.php';

echo "PSR4 Prefixes loaded: " . count($psr4) . "\n";

if (isset($psr4['Illuminate\\'])) {
    echo "\nIlluminate paths:\n";
    $illuminate_paths = $psr4['Illuminate\\'];
    foreach ((array)$illuminate_paths as $path) {
        echo "  - $path\n";
    }
}

// Try manual loading
echo "\n=== Manual Load Attempt ===\n";
$path = 'vendor/laravel/framework/src/Illuminate/Foundation/Application.php';
if (file_exists($path)) {
    echo "✓ File exists: $path\n";
    require_once $path;
    if (class_exists('Illuminate\Foundation\Application')) {
        echo "✓ After manual require, class exists!\n";
    } else {
        echo "✗ After manual require, class STILL doesn't exist\n";
    }
} else {
    echo "✗ File doesn't exist: $path\n";
}
