<?php

function test_endpoint($method, $path, $data = null) {
    $url = 'http://localhost:8000' . $path;
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Accept: application/json',
        'Content-Type: application/json'
    ]);
    
    if ($data && $method !== 'GET') {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    }
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);
    
    return [
        'status_code' => $httpCode,
        'response' => $response,
        'error' => $curlError
    ];
}

echo "====================================\n";
echo "  LARAVEL E-COMMERCE SYSTEM STATUS\n";
echo "====================================\n\n";

// Test 1: Health Check
echo "1. Health Check (/api/health)\n";
echo str_repeat("-", 40) . "\n";
$result = test_endpoint('GET', '/api/health');
echo "Status Code: " . $result['status_code'] . "\n";
if ($result['status_code'] == 200) {
    $data = json_decode($result['response'], true);
    echo "✓ Status: " . ($data['status'] ?? 'unknown') . "\n";
    echo "✓ Message: " . ($data['message'] ?? '') . "\n";
} else {
    echo "✗ Failed: " . $result['response'] . "\n";
}
echo "\n";

// Test 2: Database Connection
echo "2. Database Test (/api/test-db)\n";
echo str_repeat("-", 40) . "\n";
$result = test_endpoint('GET', '/api/test-db');
echo "Status Code: " . $result['status_code'] . "\n";
if ($result['status_code'] == 200) {
    $data = json_decode($result['response'], true);
    echo "✓ Database: " . ($data['database'] ?? 'unknown') . "\n";
    if (isset($data['tables'])) {
        echo "✓ Database Tables:\n";
        foreach ($data['tables'] as $table => $count) {
            echo "  - $table: $count records\n";
        }
    }
} else {
    echo "✗ Database Error\n";
    echo $result['response'] . "\n";
}
echo "\n";

echo "====================================\n";
echo "  SYSTEM CHECK COMPLETE\n";
echo "====================================\n";
echo "\nYour Laravel E-Commerce Platform is running!\n";
echo "Server: http://localhost:8000\n";
echo "\nNext Steps:\n";
echo "1. Configure your frontend/API client to point to http://localhost:8000\n";
echo "2. Create a user account via /register endpoint\n";
echo "3. Browse products, add to cart, and place orders\n";
echo "\nAPI Documentation:\n";
echo "- Public: GET  /api/health - Check if API is running\n";
echo "- Public: GET  /api/test-db - Check database connection\n";
echo "- Auth:   POST /api/auth/register - Create account\n";
echo "- Auth:   POST /api/auth/login - Login\n";
