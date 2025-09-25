<?php

if (!function_exists('sendSms')) {
    /**
     * Send SMS using configured gateway
     *
     * @param string|array $phoneNumbers
     * @param string $message
     * @param array $options
     * @return array
     */
    function sendSms($phoneNumbers, $message, $options = [])
    {
        try {
            $smsService = new \App\Services\SmsService();
            return $smsService->sendSms($phoneNumbers, $message, $options);
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => 'SMS helper error: ' . $e->getMessage()
            ];
        }
    }
}

if (!function_exists('sendVerificationSms')) {
    /**
     * Send verification SMS
     *
     * @param string $phoneNumber
     * @param string $code
     * @param array $options
     * @return array
     */
    function sendVerificationSms($phoneNumber, $code, $options = [])
    {
        try {
            $smsService = new \App\Services\SmsService();
            return $smsService->sendVerificationSms($phoneNumber, $code, $options);
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => 'Verification SMS error: ' . $e->getMessage()
            ];
        }
    }
}

if (!function_exists('sendOtpSms')) {
    /**
     * Send OTP SMS
     *
     * @param string $phoneNumber
     * @param string $otp
     * @param int $expiryMinutes
     * @return array
     */
    function sendOtpSms($phoneNumber, $otp, $expiryMinutes = 10)
    {
        try {
            $smsService = new \App\Services\SmsService();
            return $smsService->sendOtp($phoneNumber, $otp, $expiryMinutes);
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => 'OTP SMS error: ' . $e->getMessage()
            ];
        }
    }
}

if (!function_exists('getSmsBalance')) {
    /**
     * Get SMS account balance
     *
     * @return array
     */
    function getSmsBalance()
    {
        try {
            $smsService = new \App\Services\SmsService();
            return $smsService->getBalance();
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => 'Balance check error: ' . $e->getMessage()
            ];
        }
    }
}

if (!function_exists('isSmsEnabled')) {
    /**
     * Check if SMS service is enabled
     *
     * @return bool
     */
    function isSmsEnabled()
    {
        try {
            $smsService = new \App\Services\SmsService();
            return $smsService->isEnabled();
        } catch (Exception $e) {
            return false;
        }
    }
}

if (!function_exists('formatPhoneNumberForSms')) {
    /**
     * Format phone number for SMS (Bangladesh format)
     *
     * @param string $phone
     * @return string|null
     */
    function formatPhoneNumberForSms($phone)
    {
        // Remove all non-numeric characters
        $cleaned = preg_replace('/[^0-9]/', '', $phone);
        
        // Handle Bangladesh phone numbers
        if (strlen($cleaned) == 11 && substr($cleaned, 0, 2) == '01') {
            // 01XXXXXXXXX format - add 88 prefix
            return '88' . $cleaned;
        } elseif (strlen($cleaned) == 10 && substr($cleaned, 0, 1) == '1') {
            // 1XXXXXXXXX format - add 880 prefix
            return '880' . $cleaned;
        } elseif (strlen($cleaned) == 13 && substr($cleaned, 0, 2) == '88') {
            // 88 prefixed - already in correct format
            return $cleaned;
        } elseif (strlen($cleaned) >= 10) {
            // Try to add 88 prefix for other cases
            return '88' . $cleaned;
        }
        
        return null; // Invalid format
    }
}

if (!function_exists('generateSmsOtp')) {
    /**
     * Generate OTP for SMS verification
     *
     * @param int $length
     * @return string
     */
    function generateSmsOtp($length = 6)
    {
        $digits = '';
        for ($i = 0; $i < $length; $i++) {
            $digits .= random_int(0, 9);
        }
        return $digits;
    }
}

if (!function_exists('validateBangladeshPhone')) {
    /**
     * Validate Bangladesh phone number
     *
     * @param string $phone
     * @return bool
     */
    function validateBangladeshPhone($phone)
    {
        $cleaned = preg_replace('/[^0-9]/', '', $phone);
        
        // Valid patterns for Bangladesh
        $patterns = [
            '/^01[3-9]\d{8}$/',           // 01XXXXXXXXX (11 digits)
            '/^8801[3-9]\d{8}$/',        // 8801XXXXXXXXX (13 digits)
            '/^880[0-9]\d{8,9}$/',       // 880XXXXXXXXXX (12-13 digits)
        ];
        
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $cleaned)) {
                return true;
            }
        }
        
        return false;
    }
}

if (!function_exists('maskPhoneNumber')) {
    /**
     * Mask phone number for display
     *
     * @param string $phone
     * @return string
     */
    function maskPhoneNumber($phone)
    {
        $cleaned = preg_replace('/[^0-9]/', '', $phone);
        
        if (strlen($cleaned) >= 11) {
            // Show first 3 and last 2 digits
            $start = substr($cleaned, 0, 3);
            $end = substr($cleaned, -2);
            $middle = str_repeat('*', strlen($cleaned) - 5);
            return $start . $middle . $end;
        }
        
        return '***-***-**' . substr($cleaned, -2);
    }
}

if (!function_exists('sendBulkSms')) {
    /**
     * Send bulk SMS to multiple contacts using MRAM One To Many
     *
     * @param array|string $contacts Phone numbers (can be array or comma/+ separated string)
     * @param string $message SMS message content
     * @param array $options Additional options (type, scheduledDateTime)
     * @return array
     */
    function sendBulkSms($contacts, $message, $options = [])
    {
        try {
            $smsService = new \App\Services\SmsService();
            return $smsService->sendBulkSms($contacts, $message, $options);
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => 'Bulk SMS helper error: ' . $e->getMessage()
            ];
        }
    }
}

if (!function_exists('sendManyToMany')) {
    /**
     * Send Many To Many SMS (different messages to different contacts)
     *
     * @param array $messages Array of ['to' => 'phone', 'message' => 'text'] pairs
     * @param array $options Additional options
     * @return array
     */
    function sendManyToMany($messages, $options = [])
    {
        try {
            $smsService = new \App\Services\SmsService();
            return $smsService->sendManyToMany($messages, $options);
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => 'Many To Many SMS helper error: ' . $e->getMessage()
            ];
        }
    }
}