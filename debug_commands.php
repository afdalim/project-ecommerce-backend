<?php

$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$kernel = $app['Illuminate\Foundation\Console\Application'];
$reflector = new ReflectionClass($kernel);
$property = $reflector->getProperty('commands');
$property->setAccessible(true);
$commands = $property->getValue($kernel);

echo "Total commands: " . count($commands) . "\n";
foreach ($commands as $name => $command) {
    if (str_contains($name, 'migrate')) {
        echo "Found: $name\n";
    }
}

// List all command namespaces
echo "\nAll namespaces:\n";
$namespaces = [];
foreach ($commands as $name => $command) {
    if (strpos($name, ':') !== false) {
        $parts = explode(':', $name);
        $ns = $parts[0];
        if (!in_array($ns, $namespaces)) {
            $namespaces[] = $ns;
        }
    }
}
var_dump($namespaces);
