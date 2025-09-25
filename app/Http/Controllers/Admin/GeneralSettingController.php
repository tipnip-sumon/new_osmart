<?php

namespace App\Http\Controllers\admin;

use Exception;
use Illuminate\Http\Request;
use App\Models\GeneralSetting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; 
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Interfaces\ImageInterface;
use Illuminate\Http\UploadedFile;

class GeneralSettingController extends Controller
{
    /**
     * Display the general settings page.
     */
    public function index()
    {
        try {
            $settings = GeneralSetting::getSettings();
            
            // If no settings exist, create default ones
            if (!$settings || !$settings->exists) {
                $settings = GeneralSetting::create([
                    'site_name' => 'osmartbd',
                    'cur_text' => 'USD',
                    'cur_sym' => '$',
                    'email_from' => 'admin@osmartbd.com',
                    'base_color' => '#007bff',
                    'secondary_color' => '#6c757d',
                    'registration' => true,
                    'ev' => false,
                    'sv' => false,
                    'kv' => false,
                    'en' => true,
                    'sn' => false,
                    'force_ssl' => false,
                    'maintenance_mode' => false,
                    'secure_password' => false,
                    'agree' => false,
                    'deposit_commission' => true,
                    'invest_commission' => true,
                    'invest_return_commission' => true,
                    'signup_bonus_control' => false,
                    'promotional_tool' => false,
                    'push_notify' => false,
                    'b_transfer' => false,
                    'holiday_withdraw' => false,
                    'language_switch' => false,
                    'signup_bonus_amount' => 0,
                    'f_charge' => 0,
                    'p_charge' => 0,
                    'active_template' => 'default',
                ]);
            }
            $pageTitle = 'General Settings';
            
            return view('admin.general-settings.general', compact('settings', 'pageTitle'))
                ->with('pageTitle', 'General Settings'); 
        } catch (Exception $e) {
            return back()->with('error', 'Failed to load settings: ' . $e->getMessage());
        }
    }

    /**
     * Update general settings.
     */
    public function update(Request $request)
    {
        // Add debugging
        Log::info('GeneralSetting update method called', [
            'request_data' => $request->all(),
            'method' => $request->method(),
            'url' => $request->url(),
            'is_ajax' => $request->ajax()
        ]);

        $validator = Validator::make($request->all(), [
            'site_name' => 'required|string|max:40',
            'cur_text' => 'required|string|max:40',
            'cur_sym' => 'required|string|max:10',
            'email_from' => 'required|email|max:40',
            'base_color' => 'nullable|string|max:7',
            'secondary_color' => 'nullable|string|max:7',
            'signup_bonus_amount' => 'nullable|numeric|min:0',
            'f_charge' => 'nullable|numeric|min:0',
            'p_charge' => 'nullable|numeric|min:0|max:100',
        ]);

        if ($validator->fails()) {
            Log::warning('GeneralSetting update validation failed', ['errors' => $validator->errors()]);
            
            // Return JSON response for AJAX requests
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            return back()->withErrors($validator)->withInput();
        }

        try {
            $data = $request->only([
                'site_name',
                'cur_text',
                'cur_sym',
                'email_from',
                'email_template',
                'sms_body',
                'sms_from',
                'base_color',
                'secondary_color',
                'signup_bonus_amount',
                'f_charge',
                'p_charge',
                'active_template',
            ]);

            // Handle boolean fields that actually exist in the database
            $booleanFields = [
                'kv', 'ev', 'en', 'sv', 'sn', 'force_ssl', 'maintenance_mode',
                'secure_password', 'agree', 'registration', 'deposit_commission',
                'investment_commission', 'invest_return_commission', 'signup_bonus_control',
                'promotional_tool', 'push_notify', 'b_transfer', 'holiday_withdraw',
                'language_switch'
            ];

            foreach ($booleanFields as $field) {
                $data[$field] = $request->has($field) ? 1 : 0;
            }

            // Handle JSON fields
            if ($request->has('mail_config')) {
                $data['mail_config'] = json_encode($request->mail_config);
            }

            if ($request->has('sms_config')) {
                $data['sms_config'] = json_encode($request->sms_config);
            }

            if ($request->has('firebase_config')) {
                $data['firebase_config'] = json_encode($request->firebase_config);
            }

            if ($request->has('off_day')) {
                $data['off_day'] = json_encode($request->off_day);
            }

            Log::info('GeneralSetting data to update', ['data' => $data]);

            $result = GeneralSetting::updateOrCreateSetting($data);
            
            Log::info('GeneralSetting update successful', ['result' => $result]);

            // Return JSON response for AJAX requests
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'General settings updated successfully!',
                    'data' => $result
                ]);
            }

            return back()->with('success', 'General settings updated successfully!');
        } catch (Exception $e) {
            Log::error('GeneralSetting update failed', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            
            // Return JSON response for AJAX requests
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred while updating settings: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'An error occurred while updating settings: ' . $e->getMessage());
        }
    }

    /**
     * Update mail configuration.
     */
    public function updateMailConfig(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mail_driver' => 'required|string|in:smtp,sendmail,mailgun,ses,postmark,log',
            'mail_host' => 'required_if:mail_driver,smtp|string',
            'mail_port' => 'required_if:mail_driver,smtp|integer|min:1|max:65535',
            'mail_username' => 'required_if:mail_driver,smtp|string',
            'mail_password' => 'required_if:mail_driver,smtp|string',
            'mail_encryption' => 'nullable|string|in:tls,ssl,starttls',
            'from_address' => 'required|email',
            'from_name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $mailConfig = [
                'driver' => $request->mail_driver,
                'host' => $request->mail_host,
                'port' => (int) $request->mail_port,
                'username' => $request->mail_username,
                'password' => $request->mail_password,
                'encryption' => $request->mail_encryption,
                'from_address' => $request->from_address,
                'from_name' => $request->from_name,
            ];

            // Validate SMTP connection if driver is SMTP
            if ($request->mail_driver === 'smtp') {
                $validationResult = $this->validateSmtpConnection($mailConfig);
                if (!$validationResult['success']) {
                    return back()->with('warning', 'Mail configuration saved but SMTP connection test failed: ' . $validationResult['message'] . '. Please verify your settings.');
                }
            }

            // Update the mail configuration
            GeneralSetting::updateMailConfig($mailConfig); 
            
            // Also update email_from in general settings if provided
            if ($request->from_address) {
                GeneralSetting::updateOrCreateSetting([
                    'email_from' => $request->from_address,
                ]);
            }

            // Clear cache to ensure new configuration is loaded
            Cache::forget('general_settings');
            Artisan::call('config:clear');

            Log::info('Mail configuration updated successfully', [
                'driver' => $request->mail_driver,
                'host' => $request->mail_host,
                'from_address' => $request->from_address,
                'updated_by' => Auth::user()->email ?? 'Unknown'
            ]);

            return back()->with('success', 'Mail configuration updated successfully! You can now test the configuration.');
        } catch (Exception $e) {
            Log::error('Failed to update mail configuration', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->with('error', 'An error occurred while updating mail configuration: ' . $e->getMessage());
        }
    }

    /**
     * Validate SMTP connection.
     */
    private function validateSmtpConnection($mailConfig)
    {
        try {
            // Basic validation
            if (empty($mailConfig['host']) || empty($mailConfig['username']) || empty($mailConfig['password'])) {
                return [
                    'success' => false,
                    'message' => 'Host, username, and password are required for SMTP'
                ];
            }

            // Try to create a socket connection to test SMTP server
            $timeout = 10;
            $socket = @fsockopen($mailConfig['host'], $mailConfig['port'], $errno, $errstr, $timeout);
            
            if (!$socket) {
                return [
                    'success' => false,
                    'message' => "Cannot connect to SMTP server {$mailConfig['host']}:{$mailConfig['port']} - {$errstr} ({$errno})"
                ];
            }
            
            fclose($socket);
            
            return [
                'success' => true,
                'message' => 'SMTP connection test successful'
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'SMTP validation failed: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Get current mail configuration status for debugging.
     */
    public function getMailConfigStatus()
    {
        try {
            $settings = GeneralSetting::getSettings();
            $mailConfig = $settings->mail_config ?? [];
            
            // Get current Laravel config
            $currentConfig = [
                'default' => config('mail.default'),
                'host' => config('mail.mailers.smtp.host'),
                'port' => config('mail.mailers.smtp.port'),
                'username' => config('mail.mailers.smtp.username'),
                'encryption' => config('mail.mailers.smtp.encryption'),
                'from_address' => config('mail.from.address'),
                'from_name' => config('mail.from.name'),
            ];
            
            // Check if MailConfigServiceProvider is working
            $providerStatus = [
                'config_loaded' => !empty($currentConfig['host']),
                'database_config_exists' => !empty($mailConfig),
                'config_matches_db' => false
            ];
            
            if (!empty($mailConfig) && is_array($mailConfig)) {
                $providerStatus['config_matches_db'] = 
                    $currentConfig['host'] === $mailConfig['host'] &&
                    $currentConfig['port'] == $mailConfig['port'] &&
                    $currentConfig['username'] === $mailConfig['username'];
            }
            
            return response()->json([
                'success' => true,
                'database_config' => $mailConfig,
                'current_config' => $currentConfig,
                'provider_status' => $providerStatus,
                'mail_config_service_provider' => 'Active'
            ]);
            
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get mail configuration status: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update SMS configuration.
     */
    public function updateSmsConfig(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'gateway' => 'required|string',
            'api_key' => 'required|string',
            'sender_id' => 'required|string|max:11',
            'enabled' => 'boolean',
            'base_url' => 'nullable|url',
            'sms_type' => 'nullable|in:text,unicode',
            'sms_label' => 'nullable|in:transactional,promotional',
            'api_secret' => 'nullable|string',
            'from_number' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $smsConfig = [
                'gateway' => $request->gateway,
                'api_key' => $request->api_key,
                'sender_id' => $request->sender_id,
                'enabled' => $request->has('enabled'),
                'api_secret' => $request->api_secret,
                'from_number' => $request->from_number,
            ];

            // Add MRAM specific configuration
            if ($request->gateway === 'mram') {
                $smsConfig['base_url'] = $request->base_url ?? 'https://sms.mram.com.bd';
                $smsConfig['type'] = $request->sms_type ?? 'text';
                $smsConfig['label'] = $request->sms_label ?? 'transactional';
            }

            GeneralSetting::updateOrCreateSetting([
                'sms_config' => json_encode($smsConfig)
            ]);

            return back()->with('success', 'SMS configuration updated successfully!');
        } catch (Exception $e) {
            return back()->with('error', 'An error occurred while updating SMS configuration: ' . $e->getMessage());
        }
    }

    /**
     * Test SMS configuration
     */
    public function testSms(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => $validator->errors()->first()
            ], 400);
        }

        try {
            $smsService = new \App\Services\SmsService();
            $result = $smsService->testConfiguration($request->phone);

            return response()->json($result);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Test SMS failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check SMS balance
     */
    public function checkSmsBalance(Request $request)
    {
        try {
            $smsService = new \App\Services\SmsService();
            $result = $smsService->getBalance();

            return response()->json($result);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Balance check failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Diagnose SMS configuration and common issues
     */
    public function diagnoseSms(Request $request)
    {
        try {
            $smsService = new \App\Services\SmsService();
            $diagnostic = $smsService->diagnose();

            return response()->json($diagnostic);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Diagnostic failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send bulk SMS to multiple contacts
     */
    public function sendBulkSms(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'contacts' => 'required|string',
                'message' => 'required|string|max:1000',
                'type' => 'sometimes|string|in:text,unicode',
                'scheduledDateTime' => 'sometimes|date|after:now'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $smsService = new \App\Services\SmsService();
            $result = $smsService->sendBulkSms(
                $request->contacts,
                $request->message,
                [
                    'type' => $request->type ?? 'text',
                    'scheduledDateTime' => $request->scheduledDateTime
                ]
            );

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'message' => 'Bulk SMS sent successfully!',
                    'data' => $result['data'],
                    'contacts_count' => $result['contacts_count'] ?? 0,
                    'message_id' => $result['message_id'] ?? null,
                    'sms_id' => $result['sms_id'] ?? null
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'error' => $result['error'] ?? 'Failed to send bulk SMS',
                    'code' => $result['code'] ?? null,
                    'contacts_sent_to' => $result['contacts_sent_to'] ?? null,
                    'raw_response' => $result['raw_response'] ?? null,
                    'note' => $result['note'] ?? null
                ], 400);
            }

        } catch (Exception $e) {
            Log::error('Bulk SMS failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Bulk SMS failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send Many To Many SMS (different messages to different contacts)
     */
    public function sendManyToMany(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'messages' => 'required|array|min:1',
                'messages.*.to' => 'required|string',
                'messages.*.message' => 'required|string|max:1000'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Validate that we have valid messages
            $validMessages = [];
            foreach ($request->messages as $msg) {
                if (!empty($msg['to']) && !empty($msg['message'])) {
                    $validMessages[] = [
                        'to' => $msg['to'],
                        'message' => $msg['message']
                    ];
                }
            }

            if (empty($validMessages)) {
                return response()->json([
                    'success' => false,
                    'error' => 'No valid messages provided'
                ], 422);
            }

            $smsService = new \App\Services\SmsService();
            $result = $smsService->sendManyToMany($validMessages);

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'message' => 'Many To Many SMS sent successfully!',
                    'data' => $result['data'],
                    'messages_count' => $result['messages_count'] ?? 0,
                    'message_id' => $result['message_id'] ?? null,
                    'sms_id' => $result['sms_id'] ?? null
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'error' => $result['error'] ?? 'Failed to send Many To Many SMS',
                    'code' => $result['code'] ?? null,
                    'messages_count' => $result['messages_count'] ?? null,
                    'raw_response' => $result['raw_response'] ?? null,
                    'note' => $result['note'] ?? null
                ], 400);
            }

        } catch (Exception $e) {
            Log::error('Many To Many SMS failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Many To Many SMS failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Clear cache.
     */
    public function clearCache()
    {
        try {
            Cache::flush();
            Artisan::call('config:clear');
            Artisan::call('route:clear');
            Artisan::call('view:clear');
            
            return back()->with('success', 'Cache cleared successfully!');
        } catch (Exception $e) {
            return back()->with('error', 'Failed to clear cache: ' . $e->getMessage());
        }
    }

    /**
     * Toggle maintenance mode.
     */
    public function toggleMaintenanceMode()
    {
        try {
            $settings = GeneralSetting::getSettings();
            $newMode = !$settings->maintenance_mode;
            
            GeneralSetting::updateOrCreateSetting(['maintenance_mode' => $newMode]);
            
            $message = $newMode ? 'Maintenance mode enabled!' : 'Maintenance mode disabled!';
            return back()->with('success', $message);
        } catch (Exception $e) {
            return back()->with('error', 'Failed to toggle maintenance mode: ' . $e->getMessage());
        }
    }

    /**
     * Display mail configuration page.
     */
    public function mailConfig()
    {
        try {
            $settings = GeneralSetting::getSettings();
            // Get mail configuration, decode JSON if it exists
            $mailConfigJson = $settings->mail_config ?? null;
            $mailConfig = [];
            $mailConfig = $settings->mail_config ?? [];
            if ($mailConfigJson && is_string($mailConfigJson)) {
                $mailConfig = json_decode($mailConfigJson, true) ?? [];
            }
            
            // Set defaults if config is empty or invalid
            $mailConfig = array_merge([
                'driver' => $mailConfig['driver'] ?? 'smtp',
                'host' => $mailConfig['host'] ?? 'localhost',
                'port' => $mailConfig['port'] ?? 25,
                'username' => $mailConfig['username'] ?? '',
                'password' => $mailConfig['password'] ?? '',
                'encryption' => $mailConfig['encryption'] ?? 'tls',
                'from_address' => $mailConfig['from_address'] ?? 'info@iblbd.com',
                'from_name' => $mailConfig['from_name'] ?? $settings->site_name ?? 'osmartbd',
            ], $mailConfig);
            
            $pageTitle = 'Mail Configuration';
            $configStatus = GeneralSetting::getMailConfigStatus();

            return view('admin.general-settings.mail-config', compact('settings', 'mailConfig', 'pageTitle', 'configStatus'))
                ->with('pageTitle', 'Mail Configuration');
        } catch (Exception $e) {
            return back()->with('error', 'Failed to load mail configuration: ' . $e->getMessage());
        }
    }

    /**
     * Test email configuration.
     */
    public function testEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'test_email' => 'required|email',
            'test_subject' => 'nullable|string|max:255',
            'test_message' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            // Check if request expects JSON (AJAX request)
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed: ' . $validator->errors()->first(),
                    'errors' => $validator->errors()
                ], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        try {
            $settings = GeneralSetting::getSettings();
            
            // Clear any cached configuration
            Cache::forget('general_settings');
            Artisan::call('config:clear');
            
            // Force refresh mail configuration using MailConfigServiceProvider logic
            $this->forceRefreshMailConfig($settings);
            
            // Log current mail configuration for debugging
            Log::info('Testing email with configuration:', [
                'host' => config('mail.mailers.smtp.host'),
                'port' => config('mail.mailers.smtp.port'),
                'username' => config('mail.mailers.smtp.username'),
                'encryption' => config('mail.mailers.smtp.encryption'),
                'from_address' => config('mail.from.address'),
                'from_name' => config('mail.from.name'),
            ]);
            
            // Get custom subject and message from request
            $subject = $request->test_subject ?: ('Test Email from ' . $settings->site_name);
            $message = $request->test_message ?: ('This is a test email from ' . $settings->site_name . '. Mail configuration is working correctly. Sent at: ' . now()->format('Y-m-d H:i:s'));
            
            // Send the email
            Mail::raw($message, function ($mail) use ($request, $subject) {
                $mail->to($request->test_email)
                     ->subject($subject);
            });
            
            // Log successful email sending
            Log::info('Test email sent successfully', [
                'to' => $request->test_email,
                'subject' => $subject,
                'timestamp' => now()->toISOString()
            ]);
            
            $successMessage = 'Test email sent successfully to ' . $request->test_email . '. Please check your inbox (and spam folder).';
            
            // Check if request expects JSON (AJAX request)
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => $successMessage,
                    'email' => $request->test_email,
                    'config_info' => [
                        'host' => config('mail.mailers.smtp.host'),
                        'port' => config('mail.mailers.smtp.port'),
                        'from_address' => config('mail.from.address'),
                        'encryption' => config('mail.mailers.smtp.encryption'),
                    ]
                ]);
            }
            
            return back()->with('success', $successMessage);
        } catch (Exception $e) {
            // Log the error for debugging
            Log::error('Email test failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'mail_config' => [
                    'host' => config('mail.mailers.smtp.host'),
                    'port' => config('mail.mailers.smtp.port'),
                    'username' => config('mail.mailers.smtp.username'),
                    'from_address' => config('mail.from.address'),
                ]
            ]);
            
            $errorMessage = 'Failed to send test email: ' . $e->getMessage() . ' (Check logs for details)';
            
            // Check if request expects JSON (AJAX request)
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage,
                    'error' => $e->getMessage(),
                    'config_debug' => [
                        'host' => config('mail.mailers.smtp.host'),
                        'port' => config('mail.mailers.smtp.port'),
                        'username' => config('mail.mailers.smtp.username') ? 'Set' : 'Not Set',
                        'password' => config('mail.mailers.smtp.password') ? 'Set' : 'Not Set',
                        'from_address' => config('mail.from.address'),
                    ]
                ], 500);
            }
            
            return back()->with('error', $errorMessage);
        }
    }

    /**
     * Force refresh mail configuration using MailConfigServiceProvider logic.
     */
    private function forceRefreshMailConfig($settings)
    {
        try {
            if ($settings && isset($settings->mail_config)) {
                $mailConfig = $settings->mail_config;
                
                // Ensure mailConfig is an array
                if (is_string($mailConfig)) {
                    $mailConfig = json_decode($mailConfig, true) ?? [];
                }
                
                // Configure mail settings dynamically if valid configuration exists
                if (is_array($mailConfig) && 
                    !empty($mailConfig['host']) && 
                    !empty($mailConfig['username']) && 
                    !empty($mailConfig['password'])) {
                    
                    // Set mail configuration directly
                    config([
                        'mail.default' => $mailConfig['driver'] ?? 'smtp',
                        'mail.mailers.smtp.transport' => 'smtp',
                        'mail.mailers.smtp.host' => $mailConfig['host'],
                        'mail.mailers.smtp.port' => $mailConfig['port'] ?? 587,
                        'mail.mailers.smtp.encryption' => $mailConfig['encryption'] ?? 'tls',
                        'mail.mailers.smtp.username' => $mailConfig['username'],
                        'mail.mailers.smtp.password' => $mailConfig['password'],
                        'mail.mailers.smtp.timeout' => 60,
                        'mail.mailers.smtp.auth_mode' => null,
                        'mail.from.address' => $mailConfig['from_address'] ?? $settings->email_from ?? 'noreply@example.com',
                        'mail.from.name' => $mailConfig['from_name'] ?? $settings->site_name ?? 'Laravel Application',
                    ]);
                    
                    Log::info('Mail configuration refreshed successfully', [
                        'host' => $mailConfig['host'],
                        'from_address' => $mailConfig['from_address'] ?? $settings->email_from
                    ]);
                } else {
                    Log::warning('Incomplete mail configuration found', [
                        'has_host' => !empty($mailConfig['host']),
                        'has_username' => !empty($mailConfig['username']),
                        'has_password' => !empty($mailConfig['password']),
                        'config_type' => gettype($mailConfig)
                    ]);
                }
            }
        } catch (Exception $e) {
            Log::error('Failed to force refresh mail configuration', [
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get system information.
     */
    public function getSystemInfo()
    {
        try {
            $systemInfo = [
                'php_version' => PHP_VERSION,
                'laravel_version' => app()->version(),
                'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
                'database_version' => DB::select('select version() as version')[0]->version ?? 'Unknown',
                'max_execution_time' => ini_get('max_execution_time'),
                'memory_limit' => ini_get('memory_limit'),
                'upload_max_filesize' => ini_get('upload_max_filesize'),
                'post_max_size' => ini_get('post_max_size'),
                'disk_free_space' => $this->formatBytes(disk_free_space('.')),
                'disk_total_space' => $this->formatBytes(disk_total_space('.')),
            ];
            
            return response()->json([
                'success' => true,
                'data' => $systemInfo
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get system information: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Export settings.
     */
    public function exportSettings()
    {
        try {
            $settings = GeneralSetting::getSettings();
            $filename = 'settings_backup_' . date('Y-m-d_H-i-s') . '.json';
            
            return response()->json($settings->toArray())
                   ->header('Content-Type', 'application/json')
                   ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
        } catch (Exception $e) {
            return back()->with('error', 'Failed to export settings: ' . $e->getMessage());
        }
    }

    /**
     * Import settings.
     */
    public function importSettings(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'settings_file' => 'required|file|mimes:json|max:2048',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $file = $request->file('settings_file');
            $content = file_get_contents($file->getPathname());
            $data = json_decode($content, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                return back()->with('error', 'Invalid JSON file format.');
            }
            
            // Remove fields that shouldn't be imported
            unset($data['id'], $data['created_at'], $data['updated_at']);
            
            GeneralSetting::updateOrCreateSetting($data);
            
            return back()->with('success', 'Settings imported successfully!');
        } catch (Exception $e) {
            return back()->with('error', 'Failed to import settings: ' . $e->getMessage());
        }
    }

    /**
     * Format bytes to human readable format.
     */
    private function formatBytes($size, $precision = 2)
    {
        $base = log($size, 1024);
        $suffixes = array('', 'KB', 'MB', 'GB', 'TB');
        
        return round(pow(1024, $base - floor($base)), $precision) . ' ' . $suffixes[floor($base)];
    }

    /**
     * Display the media settings page.
     */
    public function mediaSettings()
    {
        try {
            $settings = GeneralSetting::getSettings();
            $pageTitle = 'Media Settings';

            return view('admin.general-settings.media', compact('settings', 'pageTitle'));
        } catch (Exception $e) {
            return back()->with('error', 'Failed to load media settings: ' . $e->getMessage());
        }
    }

    /**
     * Update media settings.
     */
    public function updateMediaSettings(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'admin_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'favicon' => 'nullable|image|mimes:jpeg,png,jpg,gif,ico,webp|max:2048',
            'meta_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'maintenance_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $data = [];

            // Handle logo uploads with Intervention Image
            if ($request->hasFile('logo')) {
                $logoData = $this->processMediaUpload($request->file('logo'), 'logos', [
                    'original' => ['width' => 800, 'height' => 400],
                    'medium' => ['width' => 400, 'height' => 200],
                    'small' => ['width' => 200, 'height' => 100]
                ]);
                if ($logoData) {
                    $data['logo'] = $logoData['filename'];
                    $data['logo_data'] = json_encode($logoData);
                } else {
                    return back()->with('error', 'Failed to upload logo. Please try again.');
                }
            }

            if ($request->hasFile('admin_logo')) {
                $adminLogoData = $this->processMediaUpload($request->file('admin_logo'), 'admin-logos', [
                    'original' => ['width' => 600, 'height' => 300],
                    'medium' => ['width' => 300, 'height' => 150],
                    'small' => ['width' => 150, 'height' => 75]
                ]);
                if ($adminLogoData) {
                    $data['admin_logo'] = $adminLogoData['filename'];
                    $data['admin_logo_data'] = json_encode($adminLogoData);
                } else {
                    return back()->with('error', 'Failed to upload admin logo. Please try again.');
                }
            }

            if ($request->hasFile('favicon')) {
                $faviconData = $this->processMediaUpload($request->file('favicon'), 'favicons', [
                    'original' => ['width' => 512, 'height' => 512],
                    'large' => ['width' => 256, 'height' => 256],
                    'medium' => ['width' => 128, 'height' => 128],
                    'small' => ['width' => 64, 'height' => 64],
                    'tiny' => ['width' => 32, 'height' => 32],
                    'icon' => ['width' => 16, 'height' => 16]
                ]);
                if ($faviconData) {
                    $data['favicon'] = $faviconData['filename'];
                    $data['favicon_data'] = json_encode($faviconData);
                } else {
                    return back()->with('error', 'Failed to upload favicon. Please try again.');
                }
            }

            if ($request->hasFile('meta_image')) {
                $metaImageData = $this->processMediaUpload($request->file('meta_image'), 'meta-images', [
                    'original' => ['width' => 1200, 'height' => 630],
                    'facebook' => ['width' => 1200, 'height' => 630],
                    'twitter' => ['width' => 1024, 'height' => 512],
                    'linkedin' => ['width' => 1200, 'height' => 627]
                ]);
                if ($metaImageData) {
                    $data['meta_image'] = $metaImageData['filename'];
                    $data['meta_image_data'] = json_encode($metaImageData);
                } else {
                    return back()->with('error', 'Failed to upload meta image. Please try again.');
                }
            }

            if ($request->hasFile('maintenance_image')) {
                $maintenanceImageData = $this->processMediaUpload($request->file('maintenance_image'), 'maintenance', [
                    'original' => ['width' => 1920, 'height' => 1080],
                    'large' => ['width' => 1200, 'height' => 675],
                    'medium' => ['width' => 800, 'height' => 450],
                    'small' => ['width' => 400, 'height' => 225]
                ]);
                if ($maintenanceImageData) {
                    $data['maintenance_image'] = $maintenanceImageData['filename'];
                    $data['maintenance_image_data'] = json_encode($maintenanceImageData);
                } else {
                    return back()->with('error', 'Failed to upload maintenance image. Please try again.');
                }
            }

            if (!empty($data)) {
                GeneralSetting::updateOrCreateSetting($data);
                
                // Clear cache to ensure new images are loaded
                Cache::forget('general_settings');
                
                return back()->with('success', 'Media settings updated successfully with optimized images!');
            }

            return back()->with('info', 'No files were uploaded.');
        } catch (Exception $e) {
            Log::error('Media settings update failed: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->with('error', 'An error occurred while updating media settings: ' . $e->getMessage());
        }
    }

    /**
     * Display the SEO settings page.
     */
    public function seoSettings()
    {
        try {
            $settings = GeneralSetting::getSettings();
            $pageTitle = 'SEO Settings';

            return view('admin.general-settings.seo', compact('settings', 'pageTitle'));
        } catch (Exception $e) {
            return back()->with('error', 'Failed to load SEO settings: ' . $e->getMessage());
        }
    }

    /**
     * Update SEO settings.
     */
    public function updateSeoSettings(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $data = $request->only([
                'meta_title',
                'meta_description',
                'meta_keywords'
            ]);

            GeneralSetting::updateOrCreateSetting($data);

            return back()->with('success', 'SEO settings updated successfully!');
        } catch (Exception $e) {
            return back()->with('error', 'An error occurred while updating SEO settings: ' . $e->getMessage());
        }
    }

    /**
     * Display the content settings page.
     */
    public function contentSettings()
    {
        try {
            $settings = GeneralSetting::getSettings();
            $pageTitle = 'Content Settings';

            return view('admin.general-settings.content', compact('settings', 'pageTitle'));
        } catch (Exception $e) {
            return back()->with('error', 'Failed to load content settings: ' . $e->getMessage());
        }
    }

    /**
     * Update content settings.
     */
    public function updateContentSettings(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'header_content' => 'nullable|string',
            'footer_content' => 'nullable|string',
            'copyright_text' => 'nullable|string|max:255',
            'home_page_content' => 'nullable|string',
            'about_us_content' => 'nullable|string',
            'terms_conditions' => 'nullable|string',
            'privacy_policy' => 'nullable|string',
            'maintenance_message' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $data = $request->only([
                'header_content',
                'footer_content',
                'copyright_text',
                'home_page_content',
                'about_us_content',
                'terms_conditions',
                'privacy_policy',
                'maintenance_message'
            ]);

            GeneralSetting::updateOrCreateSetting($data);

            return back()->with('success', 'Content settings updated successfully!');
        } catch (Exception $e) {
            return back()->with('error', 'An error occurred while updating content settings: ' . $e->getMessage());
        }
    }

    /**
     * Display the theme settings page.
     */
    public function themeSettings()
    {
        try {
            $settings = GeneralSetting::getSettings();
            $pageTitle = 'Theme Settings';

            return view('admin.general-settings.theme', compact('settings', 'pageTitle'));
        } catch (Exception $e) {
            return back()->with('error', 'Failed to load theme settings: ' . $e->getMessage());
        }
    }

    /**
     * Update theme settings.
     */
    public function updateThemeSettings(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'header_background_color' => 'nullable|string|max:7',
            'header_text_color' => 'nullable|string|max:7',
            'footer_background_color' => 'nullable|string|max:7',
            'footer_text_color' => 'nullable|string|max:7',
            'custom_css' => 'nullable|string',
            'custom_js' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $data = $request->only([
                'header_background_color',
                'header_text_color',
                'footer_background_color',
                'footer_text_color',
                'custom_css',
                'custom_js'
            ]);

            // Handle theme settings JSON
            if ($request->has('theme_settings')) {
                $data['theme_settings'] = $request->theme_settings;
            }

            GeneralSetting::updateOrCreateSetting($data);

            return back()->with('success', 'Theme settings updated successfully!');
        } catch (Exception $e) {
            return back()->with('error', 'An error occurred while updating theme settings: ' . $e->getMessage());
        }
    }

    /**
     * Display the social media settings page.
     */
    public function socialMediaSettings()
    {
        try {
            $settings = GeneralSetting::getSettings();
            $pageTitle = 'Social Media Settings';

            return view('admin.general-settings.social-media', compact('settings', 'pageTitle'));
        } catch (Exception $e) {
            return back()->with('error', 'Failed to load social media settings: ' . $e->getMessage());
        }
    }

    /**
     * Update social media settings.
     */
    public function updateSocialMediaSettings(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'social_media_links' => 'nullable|array',
            'social_media_links.*' => 'nullable|url',
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string|max:20',
            'contact_address' => 'nullable|string|max:500',
            'business_hours' => 'nullable|array',
            'business_hours.*.open' => 'nullable|date_format:H:i',
            'business_hours.*.close' => 'nullable|date_format:H:i',
            'business_hours.*.closed' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $data = $request->only([
                'contact_email',
                'contact_phone',
                'contact_address'
            ]);

            if ($request->has('social_media_links')) {
                $data['social_media_links'] = array_filter($request->social_media_links);
            }

            // Handle business hours
            if ($request->has('business_hours')) {
                $businessHours = [];
                foreach ($request->business_hours as $day => $hours) {
                    $businessHours[$day] = [
                        'open' => $hours['open'] ?? '09:00',
                        'close' => $hours['close'] ?? '17:00',
                        'closed' => !empty($hours['closed'])
                    ];
                }
                $data['business_hours'] = $businessHours;
            }

            GeneralSetting::updateOrCreateSetting($data);

            return back()->with('success', 'Social media settings updated successfully!');
        } catch (Exception $e) {
            return back()->with('error', 'An error occurred while updating social media settings: ' . $e->getMessage());
        }
    }

    /**
     * Display SMS configuration form.
     */
    public function smsConfig()
    {
        try {
            $settings = GeneralSetting::getSettings();
            
            // Get SMS configuration, decode JSON if it exists
            $smsConfigJson = $settings->sms_config ?? null;
            $smsConfig = [];
            
            if ($smsConfigJson && is_string($smsConfigJson)) {
                $smsConfig = json_decode($smsConfigJson, true) ?? [];
            } elseif (is_array($smsConfigJson)) {
                $smsConfig = $smsConfigJson;
            }
            
            // Set defaults if config is empty or invalid
            $smsConfig = array_merge([
                'gateway' => $smsConfig['gateway'] ?? 'twilio',
                'api_key' => $smsConfig['api_key'] ?? '',
                'api_secret' => $smsConfig['api_secret'] ?? '',
                'sender_id' => $smsConfig['sender_id'] ?? '',
                'from_number' => $smsConfig['from_number'] ?? '',
                'enabled' => $smsConfig['enabled'] ?? false,
            ], $smsConfig);
            
            $pageTitle = 'SMS Configuration';

            return view('admin.general-settings.sms-config', compact('settings', 'smsConfig', 'pageTitle'))
                ->with('pageTitle', 'SMS Configuration');
        } catch (Exception $e) {
            return back()->with('error', 'Failed to load SMS configuration: ' . $e->getMessage());
        }
    }

    /**
     * Display security settings.
     */
    public function securitySettings()
    {
        try {
            $settings = GeneralSetting::getSettings();
            $pageTitle = 'Security Settings';
            
            // Get security settings from the database
            $securitySettings = $settings->security_settings ?? [];
            
            // Set defaults if config is empty or invalid
            $securitySettings = array_merge([
                'max_login_attempts' => 5,
                'lockout_duration' => 15, // minutes
                'password_min_length' => 8,
                'password_require_uppercase' => false,
                'password_require_lowercase' => false,
                'password_require_numbers' => false,
                'password_require_symbols' => false,
                'session_timeout' => 120, // minutes
                'force_https' => false,
                'two_factor_enabled' => false,
                'ip_whitelist_enabled' => false,
                'ip_whitelist' => [],
                'failed_login_notifications' => true,
                'login_history_days' => 30,
                'auto_logout_inactive' => true,
                'password_expiry_days' => 0, // 0 = never expire
                'prevent_concurrent_sessions' => false,
                'audit_log_enabled' => true,
                'security_headers_enabled' => true,
            ], $securitySettings);

            return view('admin.general-settings.security', compact('settings', 'securitySettings', 'pageTitle'))
                ->with('pageTitle', 'Security Settings');
        } catch (Exception $e) {
            return back()->with('error', 'Failed to load security settings: ' . $e->getMessage());
        }
    }

    /**
     * Update security settings.
     */
    public function updateSecuritySettings(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'max_login_attempts' => 'required|integer|min:1|max:10',
            'lockout_duration' => 'required|integer|min:1|max:1440',
            'password_min_length' => 'required|integer|min:6|max:50',
            'session_timeout' => 'required|integer|min:5|max:1440',
            'login_history_days' => 'required|integer|min:1|max:365',
            'password_expiry_days' => 'required|integer|min:0|max:365',
            'ip_whitelist' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            // Process IP whitelist
            $ipWhitelist = [];
            if ($request->ip_whitelist) {
                $ips = explode("\n", $request->ip_whitelist);
                foreach ($ips as $ip) {
                    $ip = trim($ip);
                    if ($ip && filter_var($ip, FILTER_VALIDATE_IP)) {
                        $ipWhitelist[] = $ip;
                    }
                }
            }

            $securitySettings = [
                'max_login_attempts' => $request->max_login_attempts,
                'lockout_duration' => $request->lockout_duration,
                'password_min_length' => $request->password_min_length,
                'password_require_uppercase' => $request->has('password_require_uppercase'),
                'password_require_lowercase' => $request->has('password_require_lowercase'),
                'password_require_numbers' => $request->has('password_require_numbers'),
                'password_require_symbols' => $request->has('password_require_symbols'),
                'session_timeout' => $request->session_timeout,
                'force_https' => $request->has('force_https'),
                'two_factor_enabled' => $request->has('two_factor_enabled'),
                'ip_whitelist_enabled' => $request->has('ip_whitelist_enabled'),
                'ip_whitelist' => $ipWhitelist,
                'failed_login_notifications' => $request->has('failed_login_notifications'),
                'login_history_days' => $request->login_history_days,
                'auto_logout_inactive' => $request->has('auto_logout_inactive'),
                'password_expiry_days' => $request->password_expiry_days,
                'prevent_concurrent_sessions' => $request->has('prevent_concurrent_sessions'),
                'audit_log_enabled' => $request->has('audit_log_enabled'),
                'security_headers_enabled' => $request->has('security_headers_enabled'),
            ];

            GeneralSetting::updateOrCreateSetting([
                'security_settings' => $securitySettings
            ]);

            return back()->with('success', 'Security settings updated successfully!');
        } catch (Exception $e) {
            return back()->with('error', 'An error occurred while updating security settings: ' . $e->getMessage());
        }
    }

    /**
     * Process media upload with Intervention Image library
     */
    private function processMediaUpload(UploadedFile $file, string $folder, array $sizes): ?array
    {
        try {
            // Initialize ImageManager with GD driver
            $manager = new ImageManager(new Driver());
            
            // Generate unique filename
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = strtolower($file->getClientOriginalExtension());
            $filename = time() . '_' . Str::slug($originalName) . '.' . $extension;
            
            // Create base path with date structure
            $basePath = 'media/' . $folder . '/' . date('Y/m');
            
            // Read the uploaded image
            $image = $manager->read($file->getPathname());
            
            // Optimize the image (reduce quality for web use)
            $optimizedImage = $this->optimizeImage($image, $extension);
            
            // Store original optimized image with proper encoding
            $originalPath = $basePath . '/original/' . $filename;
            $encodedImage = $this->encodeImageByFormat($optimizedImage, $extension, 90);
            Storage::disk('public')->put($originalPath, $encodedImage);
            
            $imageData = [
                'filename' => $filename,
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
                'folder' => $folder,
                'base_path' => $basePath,
                'urls' => [
                    'original' => asset('storage/' . $originalPath)
                ],
                'dimensions' => [
                    'original' => [
                        'width' => $image->width(),
                        'height' => $image->height()
                    ]
                ]
            ];

            // Generate different sizes
            foreach ($sizes as $sizeName => $dimensions) {
                $resizedPath = $basePath . '/' . $sizeName . '/' . $filename;
                
                // Create a new image instance for resizing
                $resizedImage = clone $image;
                
                // Resize with aspect ratio maintained
                $resizedImage->resize($dimensions['width'], $dimensions['height'], function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize(); // Prevent upsizing
                });
                
                // Optimize resized image
                $optimizedResized = $this->optimizeImage($resizedImage, $extension);
                
                // Save resized image with proper encoding
                $encodedResized = $this->encodeImageByFormat($optimizedResized, $extension, 85);
                Storage::disk('public')->put($resizedPath, $encodedResized);
                
                // Add to URLs and dimensions
                $imageData['urls'][$sizeName] = asset('storage/' . $resizedPath);
                $imageData['dimensions'][$sizeName] = [
                    'width' => $resizedImage->width(),
                    'height' => $resizedImage->height()
                ];
            }
            
            Log::info('Media upload processed successfully', [
                'filename' => $filename,
                'folder' => $folder,
                'sizes_generated' => array_keys($sizes)
            ]);
            
            return $imageData;
            
        } catch (Exception $e) {
            Log::error('Media upload processing failed: ' . $e->getMessage(), [
                'file' => $file->getClientOriginalName(),
                'folder' => $folder,
                'error' => $e->getTraceAsString()
            ]);
            
            return null;
        }
    }

    /**
     * Optimize image for web usage
     */
    private function optimizeImage(ImageInterface $image, string $extension, int $quality = 85): ImageInterface
    {
        try {
            // Get current dimensions for sharpening decision
            $currentWidth = $image->width();
            $currentHeight = $image->height();
            
            // Apply sharpening for larger images before encoding
            if ($currentWidth > 800 || $currentHeight > 800) {
                // For Intervention Image v3, sharpening is applied differently
                // We'll skip sharpening for now to avoid method issues
            }
            
            // Return the image as-is for encoding
            return $image;
            
        } catch (Exception $e) {
            Log::warning('Image optimization failed, returning original: ' . $e->getMessage());
            return $image;
        }
    }

    /**
     * Encode image by format with quality settings
     */
    private function encodeImageByFormat(ImageInterface $image, string $extension, int $quality = 85): string
    {
        try {
            // Apply image encoding based on format
            switch (strtolower($extension)) {
                case 'jpg':
                case 'jpeg':
                    return $image->toJpeg($quality);
                    
                case 'png':
                    return $image->toPng();
                    
                case 'webp':
                    return $image->toWebp($quality);
                    
                case 'gif':
                    return $image->toGif();
                    
                default:
                    return $image->encode();
            }
            
        } catch (Exception $e) {
            Log::warning('Image encoding failed, using default: ' . $e->getMessage());
            return $image->encode();
        }
    }

    /**
     * Display company information settings
     */
    public function companyInfo()
    {
        try {
            $settings = GeneralSetting::getSettings();
            
            // If no settings exist, create default ones
            if (!$settings || !$settings->exists) {
                $settings = GeneralSetting::create([
                    'site_name' => 'Your Company Ltd.',
                    'company_name' => 'Your Company Ltd.',
                    'company_address' => 'House #123, Road #456, Dhanmondi, Dhaka-1205, Bangladesh',
                    'company_phone' => '+880 1700-000000',
                    'company_email' => 'info@company.com',
                    'company_website' => 'www.company.com',
                    'company_tin' => '123456789012',
                    'company_trade_license' => 'TL-123456',
                    'company_vat_number' => 'VAT-123456',
                    'contact_person' => 'John Doe',
                    'contact_designation' => 'Managing Director',
                    'bank_name' => 'Islami Bank Bangladesh Limited',
                    'bank_account_name' => 'Your Company Ltd.',
                    'bank_account_number' => '1234567890',
                    'bank_routing_number' => '125261729',
                    'bank_swift_code' => 'IBBLBDDH',
                ]);
            }

            return view('admin.general-settings.company-info', compact('settings'));
            
        } catch (Exception $e) {
            Log::error('Error fetching company info settings: ' . $e->getMessage());
            return back()->with('error', 'Error loading company information settings.');
        }
    }

    /**
     * Update company information settings
     */
    public function updateCompanyInfo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_name' => 'required|string|max:255',
            'company_address' => 'required|string|max:500',
            'company_phone' => 'required|string|max:20',
            'company_email' => 'required|email|max:100',
            'company_website' => 'nullable|url|max:100',
            'company_tin' => 'nullable|string|max:50',
            'company_trade_license' => 'nullable|string|max:50',
            'company_vat_number' => 'nullable|string|max:50',
            'contact_person' => 'nullable|string|max:100',
            'contact_designation' => 'nullable|string|max:100',
            'bank_name' => 'nullable|string|max:100',
            'bank_account_name' => 'nullable|string|max:100',
            'bank_account_number' => 'nullable|string|max:50',
            'bank_routing_number' => 'nullable|string|max:20',
            'bank_swift_code' => 'nullable|string|max:20',
            'company_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()
                        ->with('error', 'Please check the form for errors.');
        }

        try {
            $settings = GeneralSetting::getSettings();
            
            if (!$settings) {
                $settings = new GeneralSetting();
            }

            // Handle logo upload
            if ($request->hasFile('company_logo')) {
                try {
                    $logoFile = $request->file('company_logo');
                    $logoPath = 'uploads/company/' . time() . '_logo.' . $logoFile->getClientOriginalExtension();
                    
                    // Create directory if it doesn't exist
                    $directory = dirname(public_path($logoPath));
                    if (!file_exists($directory)) {
                        mkdir($directory, 0755, true);
                    }
                    
                    // Move the uploaded file
                    $logoFile->move(public_path('uploads/company'), basename($logoPath));
                    
                    // Delete old logo if exists
                    if ($settings->company_logo && file_exists(public_path($settings->company_logo))) {
                        unlink(public_path($settings->company_logo));
                    }
                    
                    $settings->company_logo = $logoPath;
                    
                } catch (Exception $e) {
                    Log::error('Logo upload failed: ' . $e->getMessage());
                    return back()->with('error', 'Failed to upload company logo. Please try again.');
                }
            }

            // Update company information
            $settings->company_name = $request->company_name;
            $settings->company_address = $request->company_address;
            $settings->company_phone = $request->company_phone;
            $settings->company_email = $request->company_email;
            $settings->company_website = $request->company_website;
            $settings->company_tin = $request->company_tin;
            $settings->company_trade_license = $request->company_trade_license;
            $settings->company_vat_number = $request->company_vat_number;
            $settings->contact_person = $request->contact_person;
            $settings->contact_designation = $request->contact_designation;
            $settings->bank_name = $request->bank_name;
            $settings->bank_account_name = $request->bank_account_name;
            $settings->bank_account_number = $request->bank_account_number;
            $settings->bank_routing_number = $request->bank_routing_number;
            $settings->bank_swift_code = $request->bank_swift_code;

            // Also update site name if company name is provided
            if ($request->company_name) {
                $settings->site_name = $request->company_name;
            }

            $settings->save();

            // Clear cache
            Cache::forget('general_settings');
            
            Log::info('Company information updated by admin: ' . Auth::user()->name);

            return back()->with('success', 'Company information updated successfully!');
            
        } catch (Exception $e) {
            Log::error('Error updating company info: ' . $e->getMessage());
            return back()->with('error', 'Failed to update company information. Please try again.');
        }
    }

    /**
     * Display the fee settings page.
     */
    public function feeSettings()
    {
        try {
            $settings = GeneralSetting::first();
            
            return view('admin.general-settings.fee-settings', compact('settings'));
            
        } catch (Exception $e) {
            Log::error('Error loading fee settings: ' . $e->getMessage());
            return back()->with('error', 'Failed to load fee settings. Please try again.');
        }
    }

    /**
     * Update fee settings.
     */
    public function updateFeeSettings(Request $request)
    {
        try {
            $settings = GeneralSetting::first();
            
            // Validate the request
            $validator = Validator::make($request->all(), [
                // Transfer settings - Balance Wallet
                'transfer_balance_fee_type' => 'required|in:fixed,percentage',
                'transfer_balance_fee_amount' => 'required|numeric|min:0',
                'transfer_balance_minimum_amount' => 'required|numeric|min:0',
                'transfer_balance_maximum_amount' => 'required|numeric|min:0',
                
                // Transfer settings - Deposit Wallet
                'transfer_deposit_fee_type' => 'required|in:fixed,percentage',
                'transfer_deposit_fee_amount' => 'required|numeric|min:0',
                'transfer_deposit_minimum_amount' => 'required|numeric|min:0',
                'transfer_deposit_maximum_amount' => 'required|numeric|min:0',
                
                // Withdrawal settings - Balance Wallet
                'withdrawal_balance_fee_type' => 'required|in:fixed,percentage',
                'withdrawal_balance_fee_amount' => 'required|numeric|min:0',
                'withdrawal_balance_minimum_amount' => 'required|numeric|min:0',
                'withdrawal_balance_maximum_amount' => 'required|numeric|min:0',
                
                // Withdrawal settings - Deposit Wallet
                'withdrawal_deposit_fee_type' => 'required|in:fixed,percentage',
                'withdrawal_deposit_fee_amount' => 'required|numeric|min:0',
                'withdrawal_deposit_minimum_amount' => 'required|numeric|min:0',
                'withdrawal_deposit_maximum_amount' => 'required|numeric|min:0',
                
                // Withdrawal settings - Interest Wallet
                'withdrawal_interest_fee_type' => 'required|in:fixed,percentage',
                'withdrawal_interest_fee_amount' => 'required|numeric|min:0',
                'withdrawal_interest_minimum_amount' => 'required|numeric|min:0',
                'withdrawal_interest_maximum_amount' => 'required|numeric|min:0',
                
                // Fund settings - bKash
                'fund_bkash_fee_type' => 'required|in:fixed,percentage',
                'fund_bkash_fee_amount' => 'required|numeric|min:0',
                'fund_bkash_minimum_amount' => 'required|numeric|min:0',
                'fund_bkash_maximum_amount' => 'required|numeric|min:0',
                
                // Fund settings - Nagad
                'fund_nagad_fee_type' => 'required|in:fixed,percentage',
                'fund_nagad_fee_amount' => 'required|numeric|min:0',
                'fund_nagad_minimum_amount' => 'required|numeric|min:0',
                'fund_nagad_maximum_amount' => 'required|numeric|min:0',
                
                // Fund settings - Rocket
                'fund_rocket_fee_type' => 'required|in:fixed,percentage',
                'fund_rocket_fee_amount' => 'required|numeric|min:0',
                'fund_rocket_minimum_amount' => 'required|numeric|min:0',
                'fund_rocket_maximum_amount' => 'required|numeric|min:0',
                
                // Fund settings - Bank Transfer
                'fund_bank_fee_type' => 'required|in:fixed,percentage',
                'fund_bank_fee_amount' => 'required|numeric|min:0',
                'fund_bank_minimum_amount' => 'required|numeric|min:0',
                'fund_bank_maximum_amount' => 'required|numeric|min:0',
                
                // Fund settings - Upay
                'fund_upay_fee_type' => 'required|in:fixed,percentage',
                'fund_upay_fee_amount' => 'required|numeric|min:0',
                'fund_upay_minimum_amount' => 'required|numeric|min:0',
                'fund_upay_maximum_amount' => 'required|numeric|min:0',
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            // Update fee settings
            $settings->update($request->only([
                // Transfer settings - Balance Wallet
                'transfer_balance_fee_type',
                'transfer_balance_fee_amount',
                'transfer_balance_minimum_amount',
                'transfer_balance_maximum_amount',
                
                // Transfer settings - Deposit Wallet
                'transfer_deposit_fee_type',
                'transfer_deposit_fee_amount',
                'transfer_deposit_minimum_amount',
                'transfer_deposit_maximum_amount',
                
                // Withdrawal settings - Balance Wallet
                'withdrawal_balance_fee_type',
                'withdrawal_balance_fee_amount',
                'withdrawal_balance_minimum_amount',
                'withdrawal_balance_maximum_amount',
                
                // Withdrawal settings - Deposit Wallet
                'withdrawal_deposit_fee_type',
                'withdrawal_deposit_fee_amount',
                'withdrawal_deposit_minimum_amount',
                'withdrawal_deposit_maximum_amount',
                
                // Withdrawal settings - Interest Wallet
                'withdrawal_interest_fee_type',
                'withdrawal_interest_fee_amount',
                'withdrawal_interest_minimum_amount',
                'withdrawal_interest_maximum_amount',
                
                // Fund settings - bKash
                'fund_bkash_fee_type',
                'fund_bkash_fee_amount',
                'fund_bkash_minimum_amount',
                'fund_bkash_maximum_amount',
                
                // Fund settings - Nagad
                'fund_nagad_fee_type',
                'fund_nagad_fee_amount',
                'fund_nagad_minimum_amount',
                'fund_nagad_maximum_amount',
                
                // Fund settings - Rocket
                'fund_rocket_fee_type',
                'fund_rocket_fee_amount',
                'fund_rocket_minimum_amount',
                'fund_rocket_maximum_amount',
                
                // Fund settings - Bank Transfer
                'fund_bank_fee_type',
                'fund_bank_fee_amount',
                'fund_bank_minimum_amount',
                'fund_bank_maximum_amount',
                
                // Fund settings - Upay
                'fund_upay_fee_type',
                'fund_upay_fee_amount',
                'fund_upay_minimum_amount',
                'fund_upay_maximum_amount',
            ]));

            // Clear cache
            Cache::forget('general_settings');
            
            Log::info('Fee settings updated by admin: ' . Auth::user()->name);

            return back()->with('success', 'Fee settings updated successfully!');
            
        } catch (Exception $e) {
            Log::error('Error updating fee settings: ' . $e->getMessage());
            return back()->with('error', 'Failed to update fee settings. Please try again.');
        }
    }
}
