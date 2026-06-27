<?php

// Test API endpoints
$testCases = [
    'GET /api/products' => 'GET /api/products',
    'GET /api/categories' => 'GET /api/categories',
];

echo "Testing Laravel E-Commerce API\n";
echo str_repeat("=", 50) . "\n\n";

foreach ($testCases as $label => $endpoint) {
    $parts = explode(' ', $endpoint);
    $method = $parts[0];
    $path = $parts[1];
    
    $url = 'http://localhost:8000' . $path;
    
    echo "Testing: $label\n";
    echo "URL: $url\n";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);
    
    if ($curlError) {
        echo "Error: $curlError\n";
    } else {
        echo "HTTP Status: $httpCode\n";
        if ($httpCode == 200) {
            $decoded = json_decode($response, true);
            if ($decoded) {
                echo "Response (JSON): " . json_encode($decoded, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
            } else {
                echo "Response: " . substr($response, 0, 200) . "\n";
            }
        } else {
            echo "Response: " . substr($response, 0, 200) . "\n";
        }
    }
    echo str_repeat("-", 50) . "\n\n";
}

echo "Test completed!\n";
