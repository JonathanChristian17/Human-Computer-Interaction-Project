<?php

$url = 'https://3f73-125-165-51-77.ngrok-free.app/api/midtrans/webhook';
$orderId = 'ORDER-1748289487-9'; // Existing order ID
$serverKey = 'SB-Mid-server-LkfxEsACKuEtIjNwrGDWOnn5';
$timestamp = time();

$data = [
    'transaction_status' => 'settlement',
    'status_code' => '200',
    'signature_key' => hash('sha512', $serverKey . $timestamp),
    'status_message' => 'midtrans payment notification',
    'order_id' => $orderId,
    'payment_type' => 'bank_transfer',
    'transaction_id' => 'test-' . $timestamp,
    'transaction_time' => date('Y-m-d H:i:s'),
    'va_numbers' => [
        [
            'bank' => 'bca',
            'va_number' => '12345678'
        ]
    ],
    'gross_amount' => '250000.00',
    'currency' => 'IDR',
    'fraud_status' => 'accept'
];

$headers = [
    'Content-Type: application/json',
    'Accept: application/json'
];

try {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    if (curl_errno($ch)) {
        echo "Error: " . curl_error($ch) . "\n";
    } else {
        echo "Response code: " . $httpCode . "\n";
        echo "Response body: " . $response . "\n";
    }
    
    curl_close($ch);
} catch (Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n";
} 