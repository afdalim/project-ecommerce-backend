<?php

// Simple HTTP client test
function test_endpoint($method, $path) {
    $url = 'http://localhost:8000' . $path;
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Accept: application/json',
        'Content-Type: application/json'
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);
    
    return [
        'method' => $method,
        'path' => $path,
        'url' => $url,
        'status_code' => $httpCode,
        'response' => $response,
        'error' => $curlError
    ];
}

echo "===== LARAVEL E-COMMERCE API TEST =====\n\n";

// Test 1: Home page
echo "Test 1: Home Page\n";
$result = test_endpoint('GET', '/');
echo "Status: " . $result['status_code'] . "\n";
echo "Response length: " . strlen($result['response']) . "\n";
if ($result['status_code'] == 200) {
    echo "✓ Home page working\n";
} else {
    echo "✗ Home page error\n";
}
echo str_repeat("-", 50) . "\n\n";

// Test 2: Register endpoint
echo "Test 2: API - Register (POST /api/auth/register)\n";
$url = 'http://localhost:8000/api/auth/register';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    'name' => 'Test User',
    'email' => 'test@example.com',
    'password' => 'password123',
    'password_confirmation' => 'password123'
]));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Accept: application/json',
    'Content-Type: application/json'
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

echo "Status: " . $httpCode . "\n";
echo "Response:\n";
if ($response) {
    $decoded = @json_decode($response, true);
    if (is_array($decoded)) {
        echo json_encode($decoded, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
    } else {
        echo substr($response, 0, 500) . "\n";
    }
} else {
    echo "No response\n";
}
if ($error) {
    echo "Error: $error\n";
}
echo str_repeat("-", 50) . "\n\n";

// Test 3: Products page
echo "Test 3: Web - Products Page\n";
$result = test_endpoint('GET', '/products');
echo "Status: " . $result['status_code'] . "\n";
if ($result['status_code'] == 200) {
    echo "✓ Products page working\n";
} else {
    echo "✗ Products page error\n";
}
echo str_repeat("-", 50) . "\n\n";

echo "===== TEST COMPLETED =====\n";
