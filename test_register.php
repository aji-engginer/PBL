<?php
// Test 1: Valid registration with new email
echo "=== Test 1: Valid Registration ===\n";
$ch = curl_init('http://localhost:8000/api/register');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    'kecamatan_id' => 1,
    'nama' => 'Admin Boyolali',
    'email' => 'admin_baru@example.com',
    'password' => 'password123'
]));
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
echo "HTTP Code: $httpCode\n";
echo "Response: $response\n\n";
curl_close($ch);

// Test 2: Duplicate email (should return 422)
echo "=== Test 2: Duplicate Email (Should Return 422) ===\n";
$ch = curl_init('http://localhost:8000/api/register');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    'kecamatan_id' => 1,
    'nama' => 'Admin Lain',
    'email' => 'admin_baru@example.com',
    'password' => 'password123'
]));
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
echo "HTTP Code: $httpCode\n";
echo "Response: $response\n\n";
curl_close($ch);

// Test 3: Invalid email format (should return 422)
echo "=== Test 3: Invalid Email Format (Should Return 422) ===\n";
$ch = curl_init('http://localhost:8000/api/register');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    'kecamatan_id' => 1,
    'nama' => 'Admin Test',
    'email' => 'not-an-email',
    'password' => 'password123'
]));
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
echo "HTTP Code: $httpCode\n";
echo "Response: $response\n\n";
curl_close($ch);
