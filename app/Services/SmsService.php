<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Models\GeneralSetting;

class SmsService
{
    protected $config;
    protected $apiKey;
    protected $baseUrl;
    protected $senderId;
    protected $enabled;

    public function __construct()
    {
        $this->loadConfig();
    }

    /**
     * Load SMS configuration from database
     */
    protected function loadConfig()
    {
        try {
            $settings = GeneralSetting::getSettings();
            $smsConfigJson = $settings->sms_config ?? null;
            
            if ($smsConfigJson && is_string($smsConfigJson)) {
                $this->config = json_decode($smsConfigJson, true) ?? [];
            } elseif (is_array($smsConfigJson)) {
                $this->config = $smsConfigJson;
            } else {
                $this->config = [];
            }

            // Set default configuration
            $this->config = array_merge([
                'gateway' => 'mram',
                'api_key' => '',
                'sender_id' => 'O-Smart',
                'enabled' => false,
                'base_url' => 'https://sms.mram.com.bd',
                'type' => 'text', // text or unicode
                'label' => 'transactional' // transactional or promotional
            ], $this->config);

            $this->apiKey = $this->config['api_key'];
            $this->baseUrl = $this->config['base_url'] ?? 'https://sms.mram.com.bd';
            $this->senderId = $this->config['sender_id'] ?? 'O-Smart';
            $this->enabled = $this->config['enabled'] ?? false;

        } catch (Exception $e) {
            Log::error('SMS Service: Failed to load configuration', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Set default values
            $this->config = [];
            $this->enabled = false;
        }
    }

    /**
     * Send SMS using MRAM API
     *
     * @param string|array $phoneNumbers Phone number(s) in international format
     * @param string $message SMS message content
     * @param array $options Additional options
     * @return array Response with success status and data
     */
    public function sendSms($phoneNumbers, $message, $options = [])
    {
        try {
            if (!$this->enabled) {
                return [
                    'success' => false,
                    'error' => 'SMS service is disabled',
                    'code' => 'DISABLED'
                ];
            }

            if (empty($this->apiKey)) {
                return [
                    'success' => false,
                    'error' => 'SMS API key not configured',
                    'code' => 'NO_API_KEY'
                ];
            }

            // Format phone numbers
            $contacts = $this->formatPhoneNumbers($phoneNumbers);
            
            if (empty($contacts)) {
                return [
                    'success' => false,
                    'error' => 'Invalid phone number format',
                    'code' => 'INVALID_PHONE'
                ];
            }

            // URL encode the message
            $encodedMessage = urlencode($message);
            
            // Get message type
            $messageType = $options['type'] ?? $this->config['type'] ?? 'text';
            
            // Get label
            $label = $options['label'] ?? $this->config['label'] ?? 'transactional';
            
            // Get sender ID
            $senderId = $options['sender_id'] ?? $this->senderId;

            // Build API URL
            $apiUrl = "{$this->baseUrl}/smsapi";
            
            // Build parameters
            $params = [
                'api_key' => $this->apiKey,
                'type' => $messageType,
                'contacts' => $contacts,
                'senderid' => $senderId,
                'msg' => $encodedMessage,
                'label' => $label
            ];

            Log::info('SMS Service: Sending SMS', [
                'url' => $apiUrl,
                'contacts' => $contacts,
                'sender_id' => $senderId,
                'type' => $messageType,
                'message_length' => strlen($message)
            ]);

            // Send SMS via GET request
            $response = Http::timeout(30)->get($apiUrl, $params);

            if ($response->successful()) {
                $responseBody = $response->body();
                
                // MRAM API can return error codes as plain text (like "1016")
                if (is_numeric(trim($responseBody))) {
                    $errorCode = trim($responseBody);
                    Log::error('SMS Service: MRAM API returned error code', [
                        'error_code' => $errorCode,
                        'error_message' => $this->getErrorMessage($errorCode),
                        'contacts' => $contacts,
                        'params' => $params
                    ]);
                    
                    return [
                        'success' => false,
                        'error' => $this->getErrorMessage($errorCode),
                        'code' => $errorCode,
                        'raw_response' => $responseBody,
                        'contacts_sent_to' => $contacts
                    ];
                }
                
                $responseData = $response->json();
                
                // MRAM API returns different response formats
                // Check if it's a successful response
                if (isset($responseData['error_code'])) {
                    return [
                        'success' => false,
                        'error' => $this->getErrorMessage($responseData['error_code']),
                        'code' => $responseData['error_code'],
                        'raw_response' => $responseData
                    ];
                } else {
                    return [
                        'success' => true,
                        'message' => 'SMS sent successfully',
                        'data' => $responseData,
                        'sms_id' => $responseData['sms_id'] ?? null
                    ];
                }
            } else {
                return [
                    'success' => false,
                    'error' => 'HTTP request failed: ' . $response->status(),
                    'code' => 'HTTP_ERROR'
                ];
            }

        } catch (Exception $e) {
            Log::error('SMS Service: Send SMS failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'phone' => $phoneNumbers,
                'message_preview' => substr($message, 0, 50)
            ]);

            return [
                'success' => false,
                'error' => 'SMS service error: ' . $e->getMessage(),
                'code' => 'EXCEPTION'
            ];
        }
    }

    /**
     * Format phone numbers for MRAM API
     *
     * @param string|array $phoneNumbers
     * @return string Formatted phone numbers joined with +
     */
    protected function formatPhoneNumbers($phoneNumbers)
    {
        if (is_string($phoneNumbers)) {
            $phoneNumbers = [$phoneNumbers];
        }

        $formattedNumbers = [];
        
        foreach ($phoneNumbers as $phone) {
            $cleaned = preg_replace('/[^0-9]/', '', $phone);
            
            // Add country code for Bangladesh if not present
            if (strlen($cleaned) == 11 && substr($cleaned, 0, 2) == '01') {
                $cleaned = '88' . $cleaned;
            } elseif (strlen($cleaned) == 10 && substr($cleaned, 0, 1) == '1') {
                $cleaned = '880' . $cleaned;
            } elseif (strlen($cleaned) == 13 && substr($cleaned, 0, 2) == '88') {
                // Already has country code
            } else {
                // Try to add 88 prefix if it looks like a BD number
                if (strlen($cleaned) >= 10) {
                    $cleaned = '88' . $cleaned;
                }
            }
            
            if (strlen($cleaned) >= 10) {
                $formattedNumbers[] = $cleaned;
            }
        }

        return implode('+', $formattedNumbers);
    }

    /**
     * Get account balance
     *
     * @return array Response with balance information
     */
    public function getBalance()
    {
        try {
            if (empty($this->apiKey)) {
                return [
                    'success' => false,
                    'error' => 'API key not configured',
                    'gateway' => 'MRAM SMS',
                    'balance' => 0
                ];
            }

            $apiUrl = "{$this->baseUrl}/miscapi/{$this->apiKey}/getBalance";
            
            Log::info('SMS Service: Checking balance', [
                'url' => $apiUrl,
                'api_key' => substr($this->apiKey, 0, 8) . '...'
            ]);
            
            $response = Http::timeout(15)->get($apiUrl);

            if ($response->successful()) {
                $responseBody = $response->body();
                
                Log::info('SMS Service: Balance response received', [
                    'response_body' => $responseBody,
                    'response_length' => strlen($responseBody)
                ]);
                
                // Handle different response formats
                if (strpos($responseBody, 'Error:') !== false) {
                    // MRAM API returned an error
                    $errorCode = trim(str_replace('Error:', '', $responseBody));
                    
                    return [
                        'success' => false,
                        'error' => $this->getErrorMessage($errorCode) . " (Note: Balance API may not be available for your account type)",
                        'gateway' => 'MRAM SMS',
                        'balance' => 0,
                        'api_key' => substr($this->apiKey, 0, 8) . '...',
                        'sender_id' => $this->senderId,
                        'enabled' => $this->enabled,
                        'note' => 'SMS sending functionality is available even if balance check is not supported'
                    ];
                }
                
                // Try to parse as JSON
                $data = $response->json();
                
                return [
                    'success' => true,
                    'balance' => $data['balance'] ?? ($data['Balance'] ?? 0),
                    'gateway' => 'MRAM SMS',
                    'api_key' => substr($this->apiKey, 0, 8) . '...',
                    'sender_id' => $this->senderId,
                    'enabled' => $this->enabled,
                    'data' => $data
                ];
            }

            Log::warning('SMS Service: Balance check failed', [
                'status' => $response->status(),
                'response' => $response->body()
            ]);

            return [
                'success' => false,
                'error' => 'Failed to get balance - HTTP ' . $response->status(),
                'gateway' => 'MRAM SMS',
                'balance' => 0,
                'api_key' => substr($this->apiKey, 0, 8) . '...',
                'sender_id' => $this->senderId,
                'enabled' => $this->enabled
            ];

        } catch (Exception $e) {
            Log::error('SMS Service: Get balance failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
                'gateway' => 'MRAM SMS',
                'balance' => 0,
                'api_key' => substr($this->apiKey, 0, 8) . '...',
                'sender_id' => $this->senderId,
                'enabled' => $this->enabled
            ];
        }
    }

    /**
     * Get SMS price information
     *
     * @return array Response with price information
     */
    public function getPrice()
    {
        try {
            if (empty($this->apiKey)) {
                return [
                    'success' => false,
                    'error' => 'API key not configured'
                ];
            }

            $apiUrl = "{$this->baseUrl}/miscapi/{$this->apiKey}/getPrice";
            
            $response = Http::timeout(15)->get($apiUrl);

            if ($response->successful()) {
                $data = $response->json();
                
                return [
                    'success' => true,
                    'data' => $data
                ];
            }

            return [
                'success' => false,
                'error' => 'Failed to get price'
            ];

        } catch (Exception $e) {
            Log::error('SMS Service: Get price failed', [
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Get delivery report for all SMS
     *
     * @return array Response with delivery reports
     */
    public function getDeliveryReports()
    {
        try {
            if (empty($this->apiKey)) {
                return [
                    'success' => false,
                    'error' => 'API key not configured'
                ];
            }

            $apiUrl = "{$this->baseUrl}/miscapi/{$this->apiKey}/getDLR/getAll";
            
            $response = Http::timeout(15)->get($apiUrl);

            if ($response->successful()) {
                $data = $response->json();
                
                return [
                    'success' => true,
                    'data' => $data
                ];
            }

            return [
                'success' => false,
                'error' => 'Failed to get delivery reports'
            ];

        } catch (Exception $e) {
            Log::error('SMS Service: Get delivery reports failed', [
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Get delivery report for specific SMS
     *
     * @param string $smsId SMS shoot ID
     * @return array Response with delivery report
     */
    public function getDeliveryReport($smsId)
    {
        try {
            if (empty($this->apiKey)) {
                return [
                    'success' => false,
                    'error' => 'API key not configured'
                ];
            }

            $apiUrl = "{$this->baseUrl}/miscapi/{$this->apiKey}/getDLR/{$smsId}";
            
            $response = Http::timeout(15)->get($apiUrl);

            if ($response->successful()) {
                $data = $response->json();
                
                return [
                    'success' => true,
                    'data' => $data
                ];
            }

            return [
                'success' => false,
                'error' => 'Failed to get delivery report'
            ];

        } catch (Exception $e) {
            Log::error('SMS Service: Get delivery report failed', [
                'error' => $e->getMessage(),
                'sms_id' => $smsId
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Send verification SMS
     *
     * @param string $phoneNumber Phone number
     * @param string $code Verification code
     * @param array $options Additional options
     * @return array Response
     */
    public function sendVerificationSms($phoneNumber, $code, $options = [])
    {
        $siteName = config('app.name', 'O-Smart');
        $message = $options['message'] ?? "Your {$siteName} verification code is: {$code}. Do not share this code with anyone.";
        
        return $this->sendSms($phoneNumber, $message, [
            'type' => 'text',
            'label' => 'transactional'
        ]);
    }

    /**
     * Send OTP SMS
     *
     * @param string $phoneNumber Phone number
     * @param string $otp OTP code
     * @param int $expiryMinutes Expiry time in minutes
     * @return array Response
     */
    public function sendOtp($phoneNumber, $otp, $expiryMinutes = 10)
    {
        $siteName = config('app.name', 'O-Smart');
        $message = "Your {$siteName} OTP is: {$otp}. Valid for {$expiryMinutes} minutes. Do not share this OTP.";
        
        return $this->sendSms($phoneNumber, $message, [
            'type' => 'text',
            'label' => 'transactional'
        ]);
    }

    /**
     * Get error message for MRAM error codes
     *
     * @param string $errorCode
     * @return string Error message
     */
    protected function getErrorMessage($errorCode)
    {
        $errorMessages = [
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

        return $errorMessages[$errorCode] ?? "Unknown error (Code: {$errorCode})";
    }

    /**
     * Test SMS configuration
     *
     * @param string $phoneNumber Test phone number
     * @return array Response
     */
    public function testConfiguration($phoneNumber)
    {
        $testMessage = "This is a test message from " . config('app.name', 'O-Smart') . " SMS service.";
        
        return $this->sendSms($phoneNumber, $testMessage, [
            'type' => 'text',
            'label' => 'transactional'
        ]);
    }

    /**
     * Check if SMS service is enabled
     *
     * @return bool
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * Send SMS to multiple contacts using MRAM One To Many format
     * 
     * @param array|string $contacts Phone numbers (can be array or comma/+ separated string)
     * @param string $message SMS message content
     * @param array $options Additional options
     * @return array Response
     */
    public function sendBulkSms($contacts, $message, $options = [])
    {
        try {
            if (!$this->enabled) {
                return [
                    'success' => false,
                    'error' => 'SMS service is disabled',
                    'code' => 'DISABLED'
                ];
            }

            if (empty($this->apiKey)) {
                return [
                    'success' => false,
                    'error' => 'SMS API key not configured',
                    'code' => 'NO_API_KEY'
                ];
            }

            // Format contacts for MRAM API (join with +)
            $formattedContacts = $this->formatContactsForBulk($contacts);
            
            if (empty($formattedContacts)) {
                return [
                    'success' => false,
                    'error' => 'No valid phone numbers provided',
                    'code' => 'NO_VALID_NUMBERS'
                ];
            }

            // Prepare data according to MRAM One To Many format (POST form-data, not JSON)
            $data = [
                'api_key' => $this->apiKey,
                'type' => $options['type'] ?? 'text',
                'contacts' => $formattedContacts,
                'senderid' => $this->senderId,
                'msg' => $message
            ];

            // Add optional scheduledDateTime if provided
            if (isset($options['scheduledDateTime']) && !empty($options['scheduledDateTime'])) {
                $data['scheduledDateTime'] = $options['scheduledDateTime'];
            }

            // Make cURL request using form-data format (not JSON)
            $url = "https://sms.mram.com.bd/smsapi";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlError = curl_error($ch);
            curl_close($ch);

            if ($curlError) {
                Log::error('SMS Service: cURL error in bulk SMS', [
                    'error' => $curlError,
                    'contacts' => $formattedContacts
                ]);
                
                return [
                    'success' => false,
                    'error' => 'Network error: ' . $curlError,
                    'code' => 'CURL_ERROR'
                ];
            }

            // Log the request for debugging
            Log::info('SMS Service: Bulk SMS request sent', [
                'url' => $url,
                'contacts' => $formattedContacts,
                'sender_id' => $this->senderId,
                'message_length' => strlen($message),
                'http_code' => $httpCode,
                'response_preview' => substr($response, 0, 200)
            ]);

            // Parse response - MRAM API can return error codes as plain text or JSON
            $responseData = json_decode($response, true);
            
            // Check if response is just an error code (like "1016")
            // MRAM can return: plain "1016" (decoded as int) or JSON with error
            if (is_numeric($response) && trim($response) >= 1000) {
                $errorCode = trim($response);
                Log::error('SMS Service: MRAM API returned error code', [
                    'error_code' => $errorCode,
                    'error_message' => $this->getErrorMessage($errorCode),
                    'contacts' => $formattedContacts,
                    'raw_response' => $response
                ]);
                
                return [
                    'success' => false,
                    'error' => $this->getErrorMessage($errorCode),
                    'code' => $errorCode,
                    'raw_response' => $response,
                    'contacts_sent_to' => $formattedContacts,
                    'note' => 'MRAM API rejected the request with error code: ' . $errorCode
                ];
            }
            
            // Also check if responseData itself is a numeric error code
            if (is_numeric($responseData) && $responseData >= 1000) {
                $errorCode = (string)$responseData;
                Log::error('SMS Service: MRAM API returned numeric error code', [
                    'error_code' => $errorCode,
                    'error_message' => $this->getErrorMessage($errorCode),
                    'contacts' => $formattedContacts,
                    'raw_response' => $response
                ]);
                
                return [
                    'success' => false,
                    'error' => $this->getErrorMessage($errorCode),
                    'code' => $errorCode,
                    'raw_response' => $responseData,
                    'contacts_sent_to' => $formattedContacts,
                    'note' => 'MRAM API rejected the request with error code: ' . $errorCode
                ];
            }
            
            if ($httpCode === 200) {
                if (isset($responseData['error']) || isset($responseData['error_code'])) {
                    return [
                        'success' => false,
                        'error' => $responseData['error'] ?? $this->getErrorMessage($responseData['error_code']),
                        'code' => $responseData['error_code'] ?? 'UNKNOWN_ERROR',
                        'raw_response' => $responseData
                    ];
                } elseif ($responseData !== null) {
                    // Valid JSON response
                    return [
                        'success' => true,
                        'message' => 'Bulk SMS sent successfully',
                        'data' => $responseData,
                        'contacts_count' => count(explode('+', $formattedContacts)),
                        'sms_id' => $responseData['sms_id'] ?? null,
                        'message_id' => $responseData['message_id'] ?? null
                    ];
                } else {
                    // Response is not JSON and not a numeric error code
                    Log::warning('SMS Service: Unexpected response format', [
                        'response' => $response,
                        'contacts' => $formattedContacts
                    ]);
                    
                    return [
                        'success' => false,
                        'error' => 'Unexpected response format from MRAM API',
                        'code' => 'INVALID_RESPONSE',
                        'raw_response' => $response,
                        'contacts_sent_to' => $formattedContacts
                    ];
                }
            } else {
                return [
                    'success' => false,
                    'error' => 'HTTP request failed: ' . $httpCode,
                    'code' => 'HTTP_ERROR',
                    'response' => $response
                ];
            }

        } catch (Exception $e) {
            Log::error('SMS Service: Bulk SMS failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'contacts' => $contacts,
                'message_preview' => substr($message, 0, 50)
            ]);

            return [
                'success' => false,
                'error' => 'Bulk SMS service error: ' . $e->getMessage(),
                'code' => 'EXCEPTION'
            ];
        }
    }

    /**
     * Format contacts for MRAM bulk API (join with + separator)
     * 
     * @param array|string $contacts
     * @return string
     */
    protected function formatContactsForBulk($contacts)
    {
        if (is_string($contacts)) {
            // If already in MRAM format (e.g., "88017xxxxxxxx+88018xxxxxxxx")
            if (strpos($contacts, '+') !== false) {
                return $contacts;
            }
            // Split by comma if comma-separated
            $contacts = array_map('trim', explode(',', $contacts));
        }

        $formattedNumbers = [];
        
        foreach ($contacts as $phone) {
            $cleaned = preg_replace('/[^0-9]/', '', trim($phone));
            
            // Add country code for Bangladesh if not present
            if (strlen($cleaned) == 11 && substr($cleaned, 0, 2) == '01') {
                $cleaned = '88' . $cleaned;
            } elseif (strlen($cleaned) == 10 && substr($cleaned, 0, 1) == '1') {
                $cleaned = '880' . $cleaned;
            } elseif (strlen($cleaned) == 13 && substr($cleaned, 0, 2) == '88') {
                // Already has country code
            } else {
                // Try to add 88 prefix if it looks like a BD number
                if (strlen($cleaned) >= 10) {
                    $cleaned = '88' . $cleaned;
                }
            }
            
            if (strlen($cleaned) >= 10) {
                $formattedNumbers[] = $cleaned;
            }
        }

        return implode('+', $formattedNumbers);
    }

    /**
     * Send different messages to different contacts using MRAM Many To Many format
     * 
     * @param array $messages Array of ['to' => 'phone', 'message' => 'text'] pairs
     * @param array $options Additional options
     * @return array Response
     */
    public function sendManyToMany($messages, $options = [])
    {
        try {
            if (!$this->enabled) {
                return [
                    'success' => false,
                    'error' => 'SMS service is disabled',
                    'code' => 'DISABLED'
                ];
            }

            if (empty($this->apiKey)) {
                return [
                    'success' => false,
                    'error' => 'SMS API key not configured',
                    'code' => 'NO_API_KEY'
                ];
            }

            if (empty($messages) || !is_array($messages)) {
                return [
                    'success' => false,
                    'error' => 'No messages provided',
                    'code' => 'NO_MESSAGES'
                ];
            }

            // Format messages for MRAM API
            $formattedMessages = [];
            foreach ($messages as $msg) {
                if (!isset($msg['to']) || !isset($msg['message'])) {
                    continue; // Skip invalid message format
                }

                $formattedPhone = $this->formatSinglePhone($msg['to']);
                if ($formattedPhone) {
                    $formattedMessages[] = [
                        'to' => $formattedPhone,
                        'message' => $msg['message']
                    ];
                }
            }

            if (empty($formattedMessages)) {
                return [
                    'success' => false,
                    'error' => 'No valid messages to send',
                    'code' => 'NO_VALID_MESSAGES'
                ];
            }

            // Prepare data according to MRAM Many To Many format
            $data = [
                'api_key' => $this->apiKey,
                'senderid' => $this->senderId,
                'messages' => json_encode($formattedMessages)
            ];

            // Make cURL request using the Many To Many endpoint
            $url = "https://sms.mram.com.bd/smsapimany";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlError = curl_error($ch);
            curl_close($ch);

            if ($curlError) {
                Log::error('SMS Service: cURL error in Many To Many SMS', [
                    'error' => $curlError,
                    'messages_count' => count($formattedMessages)
                ]);
                
                return [
                    'success' => false,
                    'error' => 'Network error: ' . $curlError,
                    'code' => 'CURL_ERROR'
                ];
            }

            // Log the request for debugging
            Log::info('SMS Service: Many To Many SMS request sent', [
                'url' => $url,
                'messages_count' => count($formattedMessages),
                'sender_id' => $this->senderId,
                'http_code' => $httpCode,
                'response_preview' => substr($response, 0, 200)
            ]);

            // Parse response
            $responseData = json_decode($response, true);
            
            if ($httpCode === 200) {
                if (isset($responseData['error']) || isset($responseData['error_code'])) {
                    return [
                        'success' => false,
                        'error' => $responseData['error'] ?? $this->getErrorMessage($responseData['error_code']),
                        'code' => $responseData['error_code'] ?? 'UNKNOWN_ERROR',
                        'raw_response' => $responseData
                    ];
                }
                
                // Check if MRAM returned a numeric error code directly (for cases like "1016")
                if (is_numeric($responseData) && $responseData >= 1000) {
                    $errorCode = (string)$responseData;
                    return [
                        'success' => false,
                        'error' => $this->getErrorMessage($errorCode),
                        'code' => $errorCode,
                        'raw_response' => $responseData,
                        'note' => $errorCode === '1016' ? 'Your IP address needs to be whitelisted. Contact MRAM support.' : null
                    ];
                }
                
                return [
                    'success' => true,
                    'message' => 'Many To Many SMS sent successfully',
                    'data' => $responseData,
                    'messages_count' => count($formattedMessages),
                    'sms_id' => $responseData['sms_id'] ?? null,
                    'message_id' => $responseData['message_id'] ?? null
                ];
            } else {
                return [
                    'success' => false,
                    'error' => 'HTTP request failed: ' . $httpCode,
                    'code' => 'HTTP_ERROR',
                    'response' => $response
                ];
            }

        } catch (Exception $e) {
            Log::error('SMS Service: Many To Many SMS failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'messages_count' => is_array($messages) ? count($messages) : 0
            ]);

            return [
                'success' => false,
                'error' => 'Many To Many SMS service error: ' . $e->getMessage(),
                'code' => 'EXCEPTION'
            ];
        }
    }

    /**
     * Format a single phone number for MRAM API
     * 
     * @param string $phone
     * @return string|null
     */
    protected function formatSinglePhone($phone)
    {
        $cleaned = preg_replace('/[^0-9]/', '', trim($phone));
        
        // Add country code for Bangladesh if not present
        if (strlen($cleaned) == 11 && substr($cleaned, 0, 2) == '01') {
            $cleaned = '88' . $cleaned;
        } elseif (strlen($cleaned) == 10 && substr($cleaned, 0, 1) == '1') {
            $cleaned = '880' . $cleaned;
        } elseif (strlen($cleaned) == 13 && substr($cleaned, 0, 2) == '88') {
            // Already has country code
        } else {
            // Try to add 88 prefix if it looks like a BD number
            if (strlen($cleaned) >= 10) {
                $cleaned = '88' . $cleaned;
            }
        }
        
        return (strlen($cleaned) >= 10) ? $cleaned : null;
    }

    /**
     * Diagnose SMS sending issues
     * 
     * @return array Diagnostic information
     */
    public function diagnose()
    {
        try {
            // Get server IP
            $serverIp = $_SERVER['SERVER_ADDR'] ?? 'Unknown';
            
            // Get public IP
            $publicIp = 'Unknown';
            try {
                $publicIpResponse = Http::timeout(10)->get('https://api.ipify.org');
                if ($publicIpResponse->successful()) {
                    $publicIp = $publicIpResponse->body();
                }
            } catch (Exception $e) {
                $publicIp = 'Could not determine: ' . $e->getMessage();
            }

            $diagnostic = [
                'service_enabled' => $this->enabled,
                'api_key_configured' => !empty($this->apiKey),
                'api_key_preview' => !empty($this->apiKey) ? substr($this->apiKey, 0, 8) . '...' : 'Not set',
                'sender_id' => $this->senderId,
                'sender_id_valid' => $this->validateSenderId($this->senderId),
                'base_url' => $this->baseUrl,
                'server_ip' => $serverIp,
                'public_ip' => $publicIp,
                'config' => $this->config,
                'common_issues' => [
                    'error_1016' => 'IP address not allowed - Contact MRAM to whitelist your IP: ' . $publicIp,
                    'error_1007' => 'Balance Insufficient - Top up your MRAM account',
                    'error_1012' => 'Invalid Number - Check phone number format (must include country code)',
                    'error_1002' => 'Sender Id/Masking Not Found - Verify sender ID with MRAM'
                ],
                'recommendations' => $this->getRecommendations($publicIp)
            ];

            return $diagnostic;

        } catch (Exception $e) {
            return [
                'error' => 'Diagnostic failed: ' . $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ];
        }
    }

    /**
     * Validate sender ID format for MRAM API
     * 
     * @param string $senderId
     * @return bool
     */
    protected function validateSenderId($senderId)
    {
        if (empty($senderId)) {
            return false;
        }

        // For MRAM API, both brand names and phone numbers can be used
        // Brand names need approval, phone numbers may work as default
        
        // If it's a phone number format, it might be acceptable for MRAM
        if (is_numeric($senderId)) {
            // Bangladesh phone number validation
            $cleaned = preg_replace('/[^0-9]/', '', $senderId);
            if (strlen($cleaned) >= 10 && 
                (substr($cleaned, 0, 2) === '01' || 
                 substr($cleaned, 0, 4) === '8801' || 
                 substr($cleaned, 0, 3) === '880')) {
                return true; // Valid BD phone number format
            }
            return false;
        }

        // Check length (MRAM allows up to 11 characters for brand names)
        if (strlen($senderId) > 11) {
            return false;
        }

        // Check for valid characters (alphanumeric for brand names)
        if (!preg_match('/^[a-zA-Z0-9\-_]+$/', $senderId)) {
            return false;
        }

        return true;
    }

    /**
     * Get recommendations based on current configuration
     * 
     * @param string $publicIp
     * @return array
     */
    protected function getRecommendations($publicIp)
    {
        $recommendations = [];

        // IP Whitelisting recommendation
        if ($publicIp && $publicIp !== 'Unknown') {
            $recommendations[] = "🔒 Contact MRAM support to whitelist IP: {$publicIp}";
        }

        // Sender ID recommendation
        if (!$this->validateSenderId($this->senderId)) {
            $recommendations[] = "📧 Fix Sender ID format - use valid Bangladesh phone number or approved brand name";
        } else if (is_numeric($this->senderId)) {
            // Phone number is valid but inform about brand name option
            $recommendations[] = "📧 Using phone number as Sender ID. For custom brand name, get MRAM approval first";
        }

        // API Key recommendation
        if (empty($this->apiKey)) {
            $recommendations[] = "🔑 Configure your MRAM API key in SMS settings";
        }

        // Service status recommendation
        if (!$this->enabled) {
            $recommendations[] = "⚡ Enable SMS service in configuration settings";
        }

        return $recommendations;
    }

    /**
     * Test One To Many SMS format (exactly like MRAM example)
     * 
     * @param array|string $contacts Phone numbers for testing
     * @param string $message Test message
     * @return array Response
     */
    public function testOneToMany($contacts = ['01712345678', '01812345679'], $message = 'Test message from O-Smart')
    {
        try {
            if (!$this->enabled) {
                return [
                    'success' => false,
                    'error' => 'SMS service is disabled',
                    'code' => 'DISABLED'
                ];
            }

            if (empty($this->apiKey)) {
                return [
                    'success' => false,
                    'error' => 'SMS API key not configured',
                    'code' => 'NO_API_KEY'
                ];
            }

            // Format contacts exactly like MRAM example: "88017xxxxxxxx+88018xxxxxxxx"
            $formattedContacts = $this->formatContactsForBulk($contacts);
            
            Log::info('SMS Service: Testing One To Many format', [
                'original_contacts' => $contacts,
                'formatted_contacts' => $formattedContacts,
                'api_key' => substr($this->apiKey, 0, 8) . '...',
                'sender_id' => $this->senderId,
                'message' => $message
            ]);

            // Exact MRAM One To Many format
            $data = [
                "api_key" => $this->apiKey,
                "type" => "text",
                "contacts" => $formattedContacts,
                "senderid" => $this->senderId,
                "msg" => $message
            ];

            $url = "https://sms.mram.com.bd/smsapi";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlError = curl_error($ch);
            curl_close($ch);

            if ($curlError) {
                return [
                    'success' => false,
                    'error' => 'Network error: ' . $curlError,
                    'code' => 'CURL_ERROR'
                ];
            }

            Log::info('SMS Service: One To Many test response', [
                'http_code' => $httpCode,
                'response' => $response,
                'contacts' => $formattedContacts
            ]);

            // Parse response
            $responseData = json_decode($response, true);
            
            if ($httpCode === 200) {
                if (isset($responseData['error']) || isset($responseData['error_code'])) {
                    return [
                        'success' => false,
                        'error' => $responseData['error'] ?? $this->getErrorMessage($responseData['error_code']),
                        'code' => $responseData['error_code'] ?? 'UNKNOWN_ERROR',
                        'raw_response' => $responseData,
                        'formatted_contacts' => $formattedContacts
                    ];
                }
                
                // Check if MRAM returned a numeric error code directly (for cases like "1016")
                if (is_numeric($responseData) && $responseData >= 1000) {
                    $errorCode = (string)$responseData;
                    return [
                        'success' => false,
                        'error' => $this->getErrorMessage($errorCode),
                        'code' => $errorCode,
                        'raw_response' => $responseData,
                        'formatted_contacts' => $formattedContacts,
                        'note' => $errorCode === '1016' ? 'Your IP address needs to be whitelisted. Contact MRAM support.' : null
                    ];
                }
                
                return [
                    'success' => true,
                    'message' => 'One To Many SMS test successful',
                    'data' => $responseData,
                    'contacts_count' => count(explode('+', $formattedContacts)),
                    'formatted_contacts' => $formattedContacts,
                    'sms_id' => $responseData['sms_id'] ?? null
                ];
            } else {
                return [
                    'success' => false,
                    'error' => 'HTTP request failed: ' . $httpCode,
                    'code' => 'HTTP_ERROR',
                    'response' => $response,
                    'formatted_contacts' => $formattedContacts
                ];
            }

        } catch (Exception $e) {
            Log::error('SMS Service: One To Many test failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'error' => 'One To Many test error: ' . $e->getMessage(),
                'code' => 'EXCEPTION'
            ];
        }
    }

    /**
     * Get current configuration
     *
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }
}