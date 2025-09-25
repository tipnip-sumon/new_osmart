<?php
/**
 * MRAM SMS API Implementation Example
 * One To Many PHP Format (Bulk SMS)
 * 
 * API Documentation: https://sms.mram.com.bd/
 * Your API Key: C300238768cd82a4899006.97231254
 * 
 * This file demonstrates how to send bulk SMS using MRAM's One To Many format
 * as specified in your API documentation.
 */

function send_sms_one_to_many() {
    $url = "https://sms.mram.com.bd/smsapi";
    
    // Your API configuration
    $data = [
        "api_key" => "C300238768cd82a4899006.97231254",
        "type" => "text", // or "unicode" for Bengali SMS
        "contacts" => "88017xxxxxxxx+88018xxxxxxxx+88019xxxxxxxx", // One To Many format with + separator
        "senderid" => "O-Smart", // Your approved sender ID
        "msg" => "Hello from O-Smart! This is a test bulk SMS message using MRAM One To Many format.",
        "label" => "transactional" // or "promotional"
    ];

    // Initialize cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data)); // Use http_build_query for proper encoding
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);

    // Execute and get response
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    if ($error) {
        return [
            'success' => false,
            'error' => 'cURL Error: ' . $error
        ];
    }

    if ($httpCode == 200) {
        return [
            'success' => true,
            'message' => 'Bulk SMS sent successfully',
            'response' => $response,
            'contacts_count' => substr_count($data['contacts'], '+') + 1
        ];
    } else {
        return [
            'success' => false,
            'error' => 'HTTP Error: ' . $httpCode,
            'response' => $response
        ];
    }
}

/**
 * Check SMS Balance
 */
function check_sms_balance() {
    $api_key = "C300238768cd82a4899006.97231254";
    $url = "https://sms.mram.com.bd/miscapi/{$api_key}/getBalance";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    if ($error) {
        return ['success' => false, 'error' => $error];
    }

    return [
        'success' => $httpCode == 200,
        'balance' => $response,
        'http_code' => $httpCode
    ];
}

/**
 * Get Delivery Report for All SMS
 */
function get_delivery_reports() {
    $api_key = "C300238768cd82a4899006.97231254";
    $url = "https://sms.mram.com.bd/miscapi/{$api_key}/getDLR/getAll";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return [
        'success' => $httpCode == 200,
        'data' => json_decode($response, true),
        'http_code' => $httpCode
    ];
}

/**
 * Get SMS Price Information
 */
function get_sms_price() {
    $api_key = "C300238768cd82a4899006.97231254";
    $url = "https://sms.mram.com.bd/miscapi/{$api_key}/getPrice";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return [
        'success' => $httpCode == 200,
        'data' => json_decode($response, true),
        'http_code' => $httpCode
    ];
}

/**
 * Get Inbox Replies
 */
function get_inbox_replies() {
    $api_key = "C300238768cd82a4899006.97231254";
    $url = "https://sms.mram.com.bd/miscapi/{$api_key}/getUnreadReplies";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return [
        'success' => $httpCode == 200,
        'data' => json_decode($response, true),
        'http_code' => $httpCode
    ];
}

/**
 * Format phone numbers for MRAM One To Many format
 */
function format_contacts_for_mram($contacts) {
    if (is_string($contacts)) {
        // Split by comma if comma-separated
        $contacts = explode(',', $contacts);
    }

    $formatted = [];
    foreach ($contacts as $contact) {
        $cleaned = preg_replace('/[^0-9]/', '', trim($contact));
        
        // Add Bangladesh country code if not present
        if (strlen($cleaned) == 11 && substr($cleaned, 0, 2) == '01') {
            $formatted[] = '88' . $cleaned;
        } elseif (strlen($cleaned) == 10 && substr($cleaned, 0, 1) == '1') {
            $formatted[] = '880' . $cleaned;
        } elseif (strlen($cleaned) >= 13 && substr($cleaned, 0, 2) == '88') {
            $formatted[] = $cleaned;
        }
    }

    // Join with + for MRAM One To Many format
    return implode('+', $formatted);
}

/**
 * Error code meanings as per MRAM API documentation
 */
function get_error_meaning($error_code) {
    $errors = [
        '1002' => 'Sender Id/Masking Not Found',
        '1003' => 'API Not Found',
        '1004' => 'SPAM Detected',
        '1005' => 'Internal Error',
        '1006' => 'Internal Error',
        '1007' => 'Balance Insufficient',
        '1008' => 'Message is empty',
        '1009' => 'Message Type Not Set (text/unicode)',
        '1010' => 'Invalid User & Password',
        '1011' => 'Invalid User Id',
        '1012' => 'Invalid Number',
        '1013' => 'API limit error',
        '1014' => 'No matching template',
        '1015' => 'SMS Content Validation Fails',
        '1016' => 'IP address not allowed!!',
        '1019' => 'Sms Purpose Missing',
    ];
    
    return $errors[$error_code] ?? "Unknown error (Code: {$error_code})";
}

// Example usage:
if (basename(__FILE__) == basename($_SERVER['SCRIPT_NAME'])) {
    echo "<h1>MRAM SMS API Example</h1>";
    echo "<h2>Your API Key: C300238768cd82a4899006.97231254</h2>";
    echo "<hr>";
    
    // Example 1: Send bulk SMS
    echo "<h3>Example 1: Send Bulk SMS (One To Many)</h3>";
    $bulk_result = send_sms_one_to_many();
    echo "<pre>";
    print_r($bulk_result);
    echo "</pre><hr>";
    
    // Example 2: Check balance
    echo "<h3>Example 2: Check SMS Balance</h3>";
    $balance_result = check_sms_balance();
    echo "<pre>";
    print_r($balance_result);
    echo "</pre><hr>";
    
    // Example 3: Get SMS price
    echo "<h3>Example 3: Get SMS Price</h3>";
    $price_result = get_sms_price();
    echo "<pre>";
    print_r($price_result);
    echo "</pre><hr>";
    
    // Example 4: Format contacts
    echo "<h3>Example 4: Format Contacts for MRAM</h3>";
    $sample_contacts = "01712345678,01812345678,01912345678";
    $formatted = format_contacts_for_mram($sample_contacts);
    echo "Original: {$sample_contacts}<br>";
    echo "Formatted for MRAM: {$formatted}<br>";
    echo "<hr>";
    
    // Example 5: Error codes
    echo "<h3>Example 5: Common Error Codes</h3>";
    $common_errors = ['1002', '1007', '1008', '1012', '1016'];
    foreach ($common_errors as $code) {
        echo "Error {$code}: " . get_error_meaning($code) . "<br>";
    }
}
?>