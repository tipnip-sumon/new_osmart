<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

/**
 * MRAM SMS Gateway Service - One To Many Implementation
 * 
 * This service implements the MRAM SMS API for Bangladesh
 * API Documentation: https://sms.mram.com.bd/
 * 
 * API Key: C300238768cd82a4899006.97231254
 * 
 * Features:
 * - One To Many SMS (Bulk SMS)
 * - Many To Many SMS (Different messages to different contacts)
 * - Balance Check
 * - Delivery Reports
 * - Price Check
 * - Inbox Reply Check
 */
class MramSmsService
{
    protected $apiKey;
    protected $baseUrl;
    protected $senderId;

    public function __construct()
    {
        $this->apiKey = 'C300238768cd82a4899006.97231254';
        $this->baseUrl = 'https://sms.mram.com.bd';
        $this->senderId = 'O-Smart'; // Default sender ID, can be overridden
    }

    /**
     * Send SMS using One To Many format (Bulk SMS)
     * 
     * @param array|string $contacts Phone numbers (format: 88017xxxxxxxx+88018xxxxxxxx)
     * @param string $message SMS message content
     * @param string $type Message type: 'text' or 'unicode'
     * @param string $label Purpose: 'transactional' or 'promotional'
     * @param string $senderId Optional sender ID override
     * @return array Response with success status and data
     */
    public function sendOneToMany($contacts, $message, $type = 'text', $label = 'transactional', $senderId = null)
    {
        try {
            // Format contacts for MRAM API
            $formattedContacts = $this->formatContactsOneToMany($contacts);
            
            if (empty($formattedContacts)) {
                return [
                    'success' => false,
                    'error' => 'No valid phone numbers provided',
                    'code' => 'INVALID_CONTACTS'
                ];
            }

            // Prepare parameters according to MRAM API specification
            $params = [
                'api_key' => $this->apiKey,
                'type' => $type,
                'contacts' => $formattedContacts,
                'senderid' => $senderId ?? $this->senderId,
                'msg' => $message,
                'label' => $label
            ];

            Log::info('MRAM SMS: Sending One To Many SMS', [
                'contacts_count' => substr_count($formattedContacts, '+') + 1,
                'message_length' => strlen($message),
                'type' => $type,
                'label' => $label
            ]);

            // Send via GET request as per MRAM API documentation
            $response = Http::timeout(30)->get($this->baseUrl . '/smsapi', $params);

            return $this->handleResponse($response, 'One To Many SMS');

        } catch (Exception $e) {
            Log::error('MRAM SMS: One To Many SMS failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'error' => 'SMS service error: ' . $e->getMessage(),
                'code' => 'EXCEPTION'
            ];
        }
    }

    /**
     * Send SMS using Many To Many format (Personalized SMS)
     * 
     * @param array $recipients Array of ['phone' => 'message'] pairs
     * @param string $type Message type: 'text' or 'unicode'
     * @param string $label Purpose: 'transactional' or 'promotional'
     * @param string $senderId Optional sender ID override
     * @return array Response with success status and data
     */
    public function sendManyToMany($recipients, $type = 'text', $label = 'transactional', $senderId = null)
    {
        try {
            if (empty($recipients) || !is_array($recipients)) {
                return [
                    'success' => false,
                    'error' => 'No recipients provided',
                    'code' => 'NO_RECIPIENTS'
                ];
            }

            $results = [];
            $successCount = 0;
            $failedCount = 0;

            // Send individual SMS for each recipient (Many To Many approach)
            foreach ($recipients as $recipient) {
                $phone = $recipient['phone'] ?? '';
                $message = $recipient['message'] ?? '';

                if (empty($phone) || empty($message)) {
                    $results[] = [
                        'phone' => $phone,
                        'success' => false,
                        'error' => 'Phone number or message is empty'
                    ];
                    $failedCount++;
                    continue;
                }

                // Send individual SMS
                $result = $this->sendSingleSms($phone, $message, $type, $label, $senderId);
                
                $results[] = [
                    'phone' => $phone,
                    'success' => $result['success'],
                    'message' => $result['success'] ? 'SMS sent successfully' : $result['error'],
                    'sms_id' => $result['sms_id'] ?? null
                ];

                if ($result['success']) {
                    $successCount++;
                } else {
                    $failedCount++;
                }

                // Small delay to avoid hitting rate limits
                usleep(100000); // 0.1 second delay
            }

            return [
                'success' => $successCount > 0,
                'message' => "Many To Many SMS completed. Success: {$successCount}, Failed: {$failedCount}",
                'data' => [
                    'total_count' => count($recipients),
                    'success_count' => $successCount,
                    'failed_count' => $failedCount,
                    'results' => $results
                ]
            ];

        } catch (Exception $e) {
            Log::error('MRAM SMS: Many To Many SMS failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'error' => 'Many To Many SMS service error: ' . $e->getMessage(),
                'code' => 'EXCEPTION'
            ];
        }
    }

    /**
     * Send single SMS (helper method)
     */
    private function sendSingleSms($phone, $message, $type = 'text', $label = 'transactional', $senderId = null)
    {
        try {
            $formattedPhone = $this->formatPhoneNumber($phone);
            
            if (empty($formattedPhone)) {
                return [
                    'success' => false,
                    'error' => 'Invalid phone number format'
                ];
            }

            $params = [
                'api_key' => $this->apiKey,
                'type' => $type,
                'contacts' => $formattedPhone,
                'senderid' => $senderId ?? $this->senderId,
                'msg' => $message,
                'label' => $label
            ];

            $response = Http::timeout(30)->get($this->baseUrl . '/smsapi', $params);
            
            return $this->handleResponse($response, 'Single SMS');

        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Check account balance
     * 
     * @return array Response with balance information
     */
    public function getBalance()
    {
        try {
            $apiUrl = "{$this->baseUrl}/miscapi/{$this->apiKey}/getBalance";
            
            Log::info('MRAM SMS: Checking balance', [
                'url' => $apiUrl,
                'api_key' => substr($this->apiKey, 0, 8) . '...'
            ]);
            
            $response = Http::timeout(15)->get($apiUrl);

            if ($response->successful()) {
                $responseBody = $response->body();
                
                // Handle different response formats
                if (strpos($responseBody, 'Error:') !== false) {
                    $errorCode = trim(str_replace('Error:', '', $responseBody));
                    
                    return [
                        'success' => false,
                        'error' => $this->getErrorMessage($errorCode),
                        'balance' => 0,
                        'raw_response' => $responseBody
                    ];
                }
                
                // Try to parse as JSON or plain text
                $data = $response->json();
                if (json_last_error() === JSON_ERROR_NONE) {
                    return [
                        'success' => true,
                        'balance' => $data['balance'] ?? ($data['Balance'] ?? 0),
                        'data' => $data
                    ];
                } else {
                    // Handle plain text response
                    return [
                        'success' => true,
                        'balance' => trim($responseBody),
                        'raw_response' => $responseBody
                    ];
                }
            }

            return [
                'success' => false,
                'error' => 'Failed to get balance - HTTP ' . $response->status(),
                'balance' => 0
            ];

        } catch (Exception $e) {
            Log::error('MRAM SMS: Get balance failed', [
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
                'balance' => 0
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
                'error' => 'Failed to get price information'
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Get delivery reports for all SMS
     * 
     * @return array Response with delivery reports
     */
    public function getDeliveryReports()
    {
        try {
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
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Get inbox replies
     * 
     * @return array Response with inbox replies
     */
    public function getInboxReplies()
    {
        try {
            $apiUrl = "{$this->baseUrl}/miscapi/{$this->apiKey}/getUnreadReplies";
            
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
                'error' => 'Failed to get inbox replies'
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Format contacts for One To Many API (join with +)
     * 
     * @param array|string $contacts
     * @return string Formatted contacts string
     */
    private function formatContactsOneToMany($contacts)
    {
        if (is_string($contacts)) {
            // Handle comma or plus separated string
            $contacts = preg_split('/[,+]/', $contacts);
        }

        if (!is_array($contacts)) {
            return '';
        }

        $formattedNumbers = [];
        
        foreach ($contacts as $contact) {
            $formatted = $this->formatPhoneNumber($contact);
            if (!empty($formatted)) {
                $formattedNumbers[] = $formatted;
            }
        }

        return implode('+', $formattedNumbers);
    }

    /**
     * Format single phone number for Bangladesh
     * 
     * @param string $phone
     * @return string Formatted phone number
     */
    private function formatPhoneNumber($phone)
    {
        $cleaned = preg_replace('/[^0-9]/', '', trim($phone));
        
        // Add country code for Bangladesh if not present
        if (strlen($cleaned) == 11 && substr($cleaned, 0, 2) == '01') {
            return '88' . $cleaned;
        } elseif (strlen($cleaned) == 10 && substr($cleaned, 0, 1) == '1') {
            return '880' . $cleaned;
        } elseif (strlen($cleaned) == 13 && substr($cleaned, 0, 2) == '88') {
            return $cleaned; // Already has country code
        }
        
        return '';
    }

    /**
     * Handle API response
     * 
     * @param \Illuminate\Http\Client\Response $response
     * @param string $operation
     * @return array
     */
    private function handleResponse($response, $operation)
    {
        if ($response->successful()) {
            $responseData = $response->json();
            
            // Check for API error codes
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
                    'message' => "{$operation} completed successfully",
                    'data' => $responseData,
                    'sms_id' => $responseData['sms_id'] ?? null
                ];
            }
        } else {
            return [
                'success' => false,
                'error' => "HTTP request failed: " . $response->status(),
                'code' => 'HTTP_ERROR'
            ];
        }
    }

    /**
     * Get error message for MRAM error codes
     * 
     * @param string $errorCode
     * @return string Error message
     */
    private function getErrorMessage($errorCode)
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
     * Test SMS configuration with a simple test message
     * 
     * @param string $phoneNumber Test phone number
     * @return array Response
     */
    public function testConfiguration($phoneNumber)
    {
        $testMessage = "Test message from O-Smart SMS service. Time: " . date('Y-m-d H:i:s');
        
        return $this->sendOneToMany([$phoneNumber], $testMessage, 'text', 'transactional');
    }

    /**
     * Get API configuration info
     * 
     * @return array Configuration information
     */
    public function getApiInfo()
    {
        return [
            'api_key' => substr($this->apiKey, 0, 8) . '...',
            'base_url' => $this->baseUrl,
            'sender_id' => $this->senderId,
            'supported_types' => ['text', 'unicode'],
            'supported_labels' => ['transactional', 'promotional'],
            'format_one_to_many' => '88017xxxxxxxx+88018xxxxxxxx',
            'endpoints' => [
                'sms_api' => '/smsapi',
                'balance' => '/miscapi/{API_KEY}/getBalance',
                'price' => '/miscapi/{API_KEY}/getPrice',
                'delivery_reports' => '/miscapi/{API_KEY}/getDLR/getAll',
                'delivery_report' => '/miscapi/{API_KEY}/getDLR/{SMS_ID}',
                'inbox_replies' => '/miscapi/{API_KEY}/getUnreadReplies'
            ]
        ];
    }

    /**
     * Set custom API key
     * 
     * @param string $apiKey
     * @return self
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
        return $this;
    }

    /**
     * Set custom sender ID
     * 
     * @param string $senderId
     * @return self
     */
    public function setSenderId($senderId)
    {
        $this->senderId = $senderId;
        return $this;
    }

    /**
     * Set custom base URL
     * 
     * @param string $baseUrl
     * @return self
     */
    public function setBaseUrl($baseUrl)
    {
        $this->baseUrl = rtrim($baseUrl, '/');
        return $this;
    }
}