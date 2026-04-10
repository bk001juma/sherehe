<?php

/**
 * Quick test script for Mobishastra SMS API
 * Run: php test_sms.php
 * 
 * ⚠️  REPLACE the phone number below with YOUR real phone number
 * ⚠️  This will send a REAL SMS and use credits
 */

// ---- CONFIG ----
$user      = 'HUMTECH';
$password  = 'BtQnCiuVR@cE5qD';
$senderId  = 'SHEREHE';
$phone     = '0680522062';  // <-- PUT YOUR PHONE NUMBER HERE (e.g. 255712345678)
$message   = 'Test SMS from Mobishastra API - Sherehe Digital';

// ---- Step 1: Check Balance ----
echo "=== Checking Mobishastra Balance ===\n";
$balanceUrl = 'https://mshastra.com/balance.aspx?' . http_build_query([
    'user' => $user,
    'pwd'  => $password,
]);

$ch = curl_init($balanceUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
$balanceResponse = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Code: {$httpCode}\n";
echo "Balance Response: {$balanceResponse}\n\n";

// ---- Step 2: Send Test SMS ----
if ($phone === '255XXXXXXXXX') {
    echo "❌ ERROR: Please edit test_sms.php and replace the phone number with your real number!\n";
    exit(1);
}

// Format Tanzania phone: 0680522062 -> 255680522062
$formattedPhone = $phone;
if (strpos($formattedPhone, '0') === 0) {
    $formattedPhone = '255' . substr($formattedPhone, 1);
}
echo "Formatted phone: {$formattedPhone}\n\n";

echo "=== Sending Test SMS to {$formattedPhone} ===\n";
$smsUrl = 'https://mshastra.com/sendurl.aspx?' . http_build_query([
    'user'        => $user,
    'pwd'         => $password,
    'senderid'    => $senderId,
    'mobileno'    => $formattedPhone,
    'msgtext'     => $message,
    'priority'    => 'High',
    'CountryCode' => 'ALL',
]);

echo "URL: {$smsUrl}\n\n";

$ch = curl_init($smsUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_TIMEOUT, 60);
$smsResponse = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
curl_close($ch);

echo "HTTP Code: {$httpCode}\n";
echo "Response: {$smsResponse}\n";

if ($curlError) {
    echo "cURL Error: {$curlError}\n";
}

// ---- Interpret result ----
echo "\n=== Result ===\n";
if (stripos($smsResponse, 'Send Successful') !== false || stripos(trim($smsResponse), '000') === 0) {
    echo "✅ SMS sent successfully! Check your phone.\n";
} else {
    echo "❌ SMS failed. Response: {$smsResponse}\n";
    echo "\nCommon errors:\n";
    echo "  - Invalid Password\n";
    echo "  - Invalid Profile Id\n";
    echo "  - No More Credits\n";
    echo "  - Country not activated\n";
}
