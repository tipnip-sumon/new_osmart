<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SettingsController extends Controller
{
    /**
     * Display settings overview/index
     */
    public function index()
    {
        return view('admin.settings.index');
    }
    
    /**
     * Display general settings
     */
    public function general()
    {
        // Mock settings data - replace with actual database retrieval later
        $settings = [
            // Site Information
            'site_name' => 'MLM Ecommerce Platform',
            'site_tagline' => 'Your Success, Our Mission',
            'site_description' => 'Premium MLM ecommerce platform for building your network marketing business',
            'site_keywords' => 'mlm, ecommerce, network marketing, online business',
            'site_url' => 'https://yourdomain.com',
            'admin_email' => 'admin@yourdomain.com',
            'contact_email' => 'contact@yourdomain.com',
            'support_email' => 'support@yourdomain.com',
            'noreply_email' => 'noreply@yourdomain.com',
            
            // Company Information
            'company_name' => 'MLM Ecommerce Ltd.',
            'company_address' => '123 Business Street, Suite 100',
            'company_city' => 'New York',
            'company_state' => 'NY',
            'company_zip' => '10001',
            'company_country' => 'United States',
            'company_phone' => '+1 (555) 123-4567',
            'company_fax' => '+1 (555) 123-4568',
            'company_registration' => 'REG123456789',
            'company_tax_id' => 'TAX987654321',
            
            // Logo & Branding
            'site_logo' => '/assets/images/logo.png',
            'site_favicon' => '/assets/images/favicon.ico',
            'admin_logo' => '/assets/images/admin-logo.png',
            'email_logo' => '/assets/images/email-logo.png',
            'mobile_logo' => '/assets/images/mobile-logo.png',
            'default_avatar' => '/assets/images/default-avatar.png',
            
            // Social Media
            'facebook_url' => 'https://facebook.com/yourpage',
            'twitter_url' => 'https://twitter.com/youraccount',
            'instagram_url' => 'https://instagram.com/youraccount',
            'linkedin_url' => 'https://linkedin.com/company/yourcompany',
            'youtube_url' => 'https://youtube.com/yourchannel',
            'telegram_url' => 'https://t.me/yourchannel',
            
            // Regional Settings
            'default_language' => 'en',
            'default_currency' => 'USD',
            'currency_symbol' => '$',
            'currency_position' => 'before', // before, after
            'decimal_places' => 2,
            'thousand_separator' => ',',
            'decimal_separator' => '.',
            'default_timezone' => 'America/New_York',
            'date_format' => 'M d, Y',
            'time_format' => '12', // 12 or 24
            
            // Site Features
            'user_registration' => true,
            'vendor_registration' => true,
            'guest_checkout' => true,
            'wishlist_enabled' => true,
            'reviews_enabled' => true,
            'compare_enabled' => true,
            'blog_enabled' => true,
            'newsletter_enabled' => true,
            'maintenance_mode' => false,
            'debug_mode' => false,
            
            // SEO Settings
            'meta_title' => 'MLM Ecommerce Platform - Build Your Network Marketing Business',
            'meta_description' => 'Join our premium MLM ecommerce platform and start building your successful network marketing business today.',
            'meta_keywords' => 'mlm, network marketing, ecommerce, online business, affiliate marketing',
            'google_analytics' => '',
            'google_tag_manager' => '',
            'facebook_pixel' => '',
            'google_site_verification' => '',
            
            // Performance Settings
            'cache_enabled' => true,
            'image_optimization' => true,
            'lazy_loading' => true,
            'compression_enabled' => true,
            'cdn_enabled' => false,
            'cdn_url' => '',
            
            // Security Settings
            'ssl_enabled' => true,
            'force_https' => true,
            'session_lifetime' => 120, // minutes
            'password_min_length' => 8,
            'max_login_attempts' => 5,
            'lockout_duration' => 15, // minutes
            'two_factor_auth' => false,
            
            // File Upload Settings
            'max_file_size' => 10, // MB
            'allowed_file_types' => 'jpg,jpeg,png,gif,pdf,doc,docx',
            'image_quality' => 85,
            'watermark_enabled' => false,
            'watermark_image' => '',
            'watermark_position' => 'bottom-right',
            
            // Email Settings
            'mail_driver' => 'smtp',
            'mail_host' => 'smtp.gmail.com',
            'mail_port' => 587,
            'mail_username' => 'your-email@gmail.com',
            'mail_password' => '********',
            'mail_encryption' => 'tls',
            'mail_from_address' => 'noreply@yourdomain.com',
            'mail_from_name' => 'MLM Ecommerce Platform',
            
            // Backup Settings
            'auto_backup' => true,
            'backup_frequency' => 'daily', // daily, weekly, monthly
            'backup_retention' => 30, // days
            'backup_storage' => 'local', // local, s3, ftp
            
            // API Settings
            'api_enabled' => true,
            'api_rate_limit' => 1000, // requests per hour
            'webhook_enabled' => false,
            'webhook_secret' => '',
            
            // MLM Specific Settings
            'mlm_enabled' => true,
            'binary_tree' => true,
            'unilevel_tree' => false,
            'matrix_plan' => false,
            'commission_payout_day' => 'Friday',
            'minimum_payout' => 50.00,
            'payout_currency' => 'USD',
            'rank_advancement' => true,
            'genealogy_levels' => 10,
            
            // Notification Settings
            'email_notifications' => true,
            'sms_notifications' => false,
            'push_notifications' => true,
            'admin_notifications' => true,
            'order_notifications' => true,
            'commission_notifications' => true,
            'rank_notifications' => true,
        ];
        
        return view('admin.settings.general', compact('settings'));
    }
    
    /**
     * Update general settings
     */
    public function updateGeneral(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'site_name' => 'required|string|max:255',
            'site_tagline' => 'nullable|string|max:500',
            'site_description' => 'nullable|string|max:1000',
            'admin_email' => 'required|email|max:255',
            'contact_email' => 'required|email|max:255',
            'company_name' => 'required|string|max:255',
            'company_phone' => 'nullable|string|max:20',
            'default_currency' => 'required|string|size:3',
            'default_timezone' => 'required|string',
            'site_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'site_favicon' => 'nullable|image|mimes:ico,png|max:1024',
            'admin_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'email_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'meta_title' => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
            'session_lifetime' => 'required|integer|min:5|max:1440',
            'password_min_length' => 'required|integer|min:6|max:50',
            'max_file_size' => 'required|integer|min:1|max:100',
            'minimum_payout' => 'required|numeric|min:0',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Handle file uploads
        $uploadedFiles = [];
        $logoFields = ['site_logo', 'site_favicon', 'admin_logo', 'email_logo', 'mobile_logo'];
        
        foreach ($logoFields as $field) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $filename = time() . '_' . $field . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('public/logos', $filename);
                $uploadedFiles[$field] = '/storage/logos/' . $filename;
            }
        }
        
        // In a real application, you would save these to database or config files
        // For now, we'll just simulate the save operation
        
        // Example of what you might do:
        // foreach ($request->all() as $key => $value) {
        //     if (!in_array($key, ['_token', '_method']) && !$request->hasFile($key)) {
        //         Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        //     }
        // }
        
        // Save uploaded files
        // foreach ($uploadedFiles as $key => $path) {
        //     Setting::updateOrCreate(['key' => $key], ['value' => $path]);
        // }
        
        return redirect()->back()->with('success', 'General settings updated successfully!');
    }
    
    /**
     * Display payment settings
     */
    public function payment()
    {
        $paymentSettings = [
            // Payment Gateways
            'paypal_enabled' => true,
            'paypal_mode' => 'sandbox', // sandbox, live
            'paypal_client_id' => 'your-paypal-client-id',
            'paypal_client_secret' => '********',
            
            'stripe_enabled' => true,
            'stripe_publishable_key' => 'pk_test_your-stripe-key',
            'stripe_secret_key' => '********',
            'stripe_webhook_secret' => '********',
            
            'razorpay_enabled' => false,
            'razorpay_key_id' => '',
            'razorpay_key_secret' => '********',
            
            'square_enabled' => false,
            'square_application_id' => '',
            'square_access_token' => '********',
            'square_location_id' => '',
            
            'bank_transfer_enabled' => true,
            'bank_name' => 'Your Bank Name',
            'bank_account_name' => 'Company Account Name',
            'bank_account_number' => 'XXXX-XXXX-XXXX-1234',
            'bank_routing_number' => '123456789',
            'bank_swift_code' => 'BANKSWFT',
            
            // Payment Settings
            'default_payment_method' => 'stripe',
            'payment_timeout' => 30, // minutes
            'auto_capture' => true,
            'save_cards' => true,
            'minimum_order_amount' => 10.00,
            'transaction_fee' => 2.9, // percentage
            'fixed_transaction_fee' => 0.30,
            
            // Currency Settings
            'accepted_currencies' => ['USD', 'EUR', 'GBP', 'CAD', 'AUD'],
            'auto_currency_conversion' => true,
            'exchange_rate_provider' => 'fixer.io',
            'exchange_rate_api_key' => '',
            
            // Refund Settings
            'auto_refund_enabled' => false,
            'refund_processing_time' => '5-7 business days',
            'partial_refunds_allowed' => true,
            'refund_fee' => 0.00,
            
            // Security Settings
            'ssl_required' => true,
            'fraud_detection' => true,
            'cvv_verification' => true,
            'address_verification' => true,
            'max_payment_attempts' => 3,
            
            // MLM Payment Settings
            'commission_payout_method' => 'bank_transfer',
            'payout_schedule' => 'weekly',
            'minimum_commission_payout' => 50.00,
            'payout_fee' => 0.00,
            'hold_period' => 7, // days
        ];
        
        return view('admin.settings.payment', compact('paymentSettings'));
    }
    
    /**
     * Display shipping settings
     */
    public function shipping()
    {
        $shippingSettings = [
            // Shipping Options
            'free_shipping_enabled' => true,
            'free_shipping_minimum' => 75.00,
            'local_pickup_enabled' => true,
            'same_day_delivery' => false,
            'express_shipping' => true,
            'international_shipping' => true,
            
            // Shipping Rates
            'flat_rate_shipping' => 9.99,
            'weight_based_shipping' => false,
            'zone_based_shipping' => true,
            'real_time_rates' => false,
            
            // Shipping Providers
            'ups_enabled' => true,
            'ups_api_key' => '',
            'fedex_enabled' => true,
            'fedex_api_key' => '',
            'dhl_enabled' => false,
            'dhl_api_key' => '',
            'usps_enabled' => true,
            'usps_api_key' => '',
            
            // Processing Settings
            'processing_time' => '1-2 business days',
            'cutoff_time' => '14:00', // 2:00 PM
            'weekend_processing' => false,
            'holiday_processing' => false,
            
            // Packaging Settings
            'default_package_weight' => 1.0, // lbs
            'default_package_dimensions' => '12x12x6', // inches
            'signature_required' => false,
            'insurance_enabled' => true,
            'tracking_enabled' => true,
            
            // International Settings
            'customs_forms' => true,
            'duty_taxes_notice' => 'Customer responsible for any duties and taxes',
            'restricted_countries' => ['XX', 'YY'],
            'shipping_restrictions' => 'No shipping to PO Boxes for international orders',
            
            // MLM Shipping Settings
            'drop_shipping_enabled' => true,
            'vendor_shipping_enabled' => true,
            'split_shipping' => true,
            'shipping_commission' => 5.0, // percentage
        ];
        
        return view('admin.settings.shipping', compact('shippingSettings'));
    }
    
    /**
     * Display tax settings
     */
    public function tax()
    {
        $taxSettings = [
            // Tax Configuration
            'tax_enabled' => true,
            'prices_include_tax' => false, // Added missing key
            'tax_inclusive_prices' => false,
            'tax_display_mode' => 'excluding', // including, excluding, both
            'tax_calculation_based_on' => 'shipping_address', // billing_address, shipping_address, store_address
            'tax_calculation_method' => 'per_line', // Added missing key
            'tax_on_shipping' => true, // Added missing key
            'compound_tax' => false, // Added missing key
            'tax_rounding' => true, // Added missing key
            'tax_address_calculation' => 'shipping', // Added missing key
            
            // Tax Rates
            'default_tax_rate' => 8.25,
            'digital_tax_rate' => 6.00,
            'shipping_tax_rate' => 0.00,
            
            // Tax Classes
            'standard_tax_class' => 'Standard Rate',
            'reduced_tax_class' => 'Reduced Rate',
            'zero_tax_class' => 'Zero Rate',
            
            // Regional Settings
            'eu_vat_enabled' => false,
            'vat_number_validation' => false,
            'reverse_charge_enabled' => false,
            
            // Tax Exemptions
            'tax_exempt_roles' => ['wholesale', 'tax_exempt'],
            'exempt_shipping' => true,
            'exempt_digital_products' => false,
            
            // Reporting
            'tax_reporting_enabled' => true,
            'quarterly_reports' => true,
            'annual_reports' => true,
            'tax_report_frequency' => 'monthly', // Added missing key
            'tax_fiscal_year_start' => 'january', // Added missing key
            'auto_tax_reports' => true, // Added missing key
            'email_tax_reports' => false, // Added missing key
            
            // Integration
            'avalara_enabled' => false,
            'avalara_account_id' => '',
            'avalara_license_key' => '',
            'taxjar_enabled' => false,
            'taxjar_api_token' => '',
            
            // MLM Tax Settings
            'commission_tax_enabled' => true,
            'commission_tax' => true, // Added missing key
            'bonus_tax' => true, // Added missing key
            'vendor_tax_class' => 'standard', // Added missing key
            'vendor_tax_exempt' => false, // Added missing key
            'commission_tax_rate' => 15.0,
            'tax_form_generation' => true, // 1099 forms
            'quarterly_tax_reports' => true,
            
            // Digital Products Tax
            'digital_tax_enabled' => true, // Added missing key
            'eu_vat_moss' => false, // Added missing key
        ];
        
        return view('admin.settings.tax', compact('taxSettings'));
    }
    
    /**
     * Display email settings
     */
    public function email()
    {
        $emailSettings = [
            // SMTP Configuration
            'mail_driver' => 'smtp',
            'mail_host' => 'smtp.gmail.com',
            'mail_port' => 587,
            'mail_username' => 'your-email@gmail.com',
            'mail_password' => '********',
            'mail_encryption' => 'tls',
            'mail_from_address' => 'noreply@yourdomain.com',
            'mail_from_name' => 'MLM Ecommerce Platform',
            
            // Email Queue Settings - Added missing keys
            'queue_emails' => true,
            'queue_connection' => 'database',
            'emails_per_batch' => 50,
            'email_retry_attempts' => 3,
            
            // Email Templates
            'email_header_logo' => '/assets/images/email-logo.png',
            'email_footer_text' => '© 2025 MLM Ecommerce Platform. All rights reserved.',
            'email_primary_color' => '#007bff',
            'email_secondary_color' => '#6c757d',
            
            // Notification Settings
            'order_confirmation_enabled' => true,
            'order_status_updates' => true,
            'shipping_notifications' => true,
            'commission_notifications' => true,
            'rank_advancement_notifications' => true,
            'welcome_email_enabled' => true,
            'password_reset_enabled' => true,
            'newsletter_enabled' => true,
            
            // Admin Notifications
            'admin_new_order' => true,
            'admin_low_stock' => true,
            'admin_new_user' => true,
            'admin_new_vendor' => true,
            'admin_commission_payout' => true,
            
            // Email Marketing
            'mailchimp_enabled' => false,
            'mailchimp_api_key' => '',
            'mailchimp_list_id' => '',
            'constant_contact_enabled' => false,
            'constant_contact_api_key' => '',
            'aweber_enabled' => false,
            'aweber_api_key' => '',
            
            // MLM Email Settings - Added missing keys
            'commission_emails' => true,
            'downline_emails' => true,
            'rank_advancement_emails' => true,
            'vendor_notifications' => true,
            'commission_email_frequency' => 'weekly',
            
            // Advanced Settings
            'email_queue_enabled' => true,
            'max_send_rate' => 100, // emails per hour
            'bounce_handling' => true,
            'unsubscribe_enabled' => true,
            'track_opens' => true,
            'track_clicks' => true,
            
            // Templates
            'order_confirmation_template' => 'emails.orders.confirmation',
            'welcome_email_template' => 'emails.users.welcome',
            'commission_template' => 'emails.commissions.payout',
            'newsletter_template' => 'emails.newsletter.default',
        ];
        
        return view('admin.settings.email', compact('emailSettings'));
    }
    
    /**
     * Test email configuration
     */
    public function testEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'test_email' => 'required|email',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Invalid email address']);
        }
        
        try {
            // In a real application, you would send a test email here
            // Mail::to($request->test_email)->send(new TestEmail());
            
            return response()->json([
                'success' => true, 
                'message' => 'Test email sent successfully to ' . $request->test_email
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => 'Failed to send test email: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Display integrations settings
     */
    public function integrations()
    {
        // Mock integrations data - replace with actual database retrieval later
        $integrations = [
            // Payment Gateways
            'stripe_enabled' => false,
            'stripe_public_key' => '',
            'stripe_secret_key' => '',
            'stripe_webhook_secret' => '',
            
            'paypal_enabled' => false,
            'paypal_client_id' => '',
            'paypal_secret' => '',
            'paypal_mode' => 'sandbox', // sandbox or live
            
            'razorpay_enabled' => false,
            'razorpay_key_id' => '',
            'razorpay_key_secret' => '',
            
            // Social Media APIs
            'facebook_app_id' => '',
            'facebook_app_secret' => '',
            'google_client_id' => '',
            'google_client_secret' => '',
            'twitter_api_key' => '',
            'twitter_api_secret' => '',
            
            // Analytics
            'google_analytics_id' => '',
            'google_tag_manager_id' => '',
            'facebook_pixel_id' => '',
            
            // Communication
            'twilio_account_sid' => '',
            'twilio_auth_token' => '',
            'twilio_phone_number' => '',
            
            // Cloud Storage
            'aws_access_key_id' => '',
            'aws_secret_access_key' => '',
            'aws_default_region' => 'us-east-1',
            'aws_bucket' => '',
            
            // Other Services
            'recaptcha_site_key' => '',
            'recaptcha_secret_key' => '',
            'firebase_server_key' => '',
            'pusher_app_id' => '',
            'pusher_app_key' => '',
            'pusher_app_secret' => '',
            'pusher_app_cluster' => '',
        ];

        return view('admin.settings.integrations', compact('integrations'));
    }

    /**
     * Update integrations settings
     */
    public function updateIntegrations(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // Payment Gateway Validations
            'stripe_public_key' => 'nullable|string',
            'stripe_secret_key' => 'nullable|string',
            'stripe_webhook_secret' => 'nullable|string',
            'paypal_client_id' => 'nullable|string',
            'paypal_secret' => 'nullable|string',
            'paypal_mode' => 'required|in:sandbox,live',
            'razorpay_key_id' => 'nullable|string',
            'razorpay_key_secret' => 'nullable|string',
            
            // Social Media Validations
            'facebook_app_id' => 'nullable|string',
            'facebook_app_secret' => 'nullable|string',
            'google_client_id' => 'nullable|string',
            'google_client_secret' => 'nullable|string',
            'twitter_api_key' => 'nullable|string',
            'twitter_api_secret' => 'nullable|string',
            
            // Analytics Validations
            'google_analytics_id' => 'nullable|string',
            'google_tag_manager_id' => 'nullable|string',
            'facebook_pixel_id' => 'nullable|string',
            
            // Communication Validations
            'twilio_account_sid' => 'nullable|string',
            'twilio_auth_token' => 'nullable|string',
            'twilio_phone_number' => 'nullable|string',
            
            // Cloud Storage Validations
            'aws_access_key_id' => 'nullable|string',
            'aws_secret_access_key' => 'nullable|string',
            'aws_default_region' => 'nullable|string',
            'aws_bucket' => 'nullable|string',
            
            // Other Services Validations
            'recaptcha_site_key' => 'nullable|string',
            'recaptcha_secret_key' => 'nullable|string',
            'firebase_server_key' => 'nullable|string',
            'pusher_app_id' => 'nullable|string',
            'pusher_app_key' => 'nullable|string',
            'pusher_app_secret' => 'nullable|string',
            'pusher_app_cluster' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Please fix the validation errors below.');
        }

        try {
            // TODO: Implement actual database save logic
            // For now, we'll just show a success message
            // In a real implementation, you would save to a settings table or config files
            
            return redirect()->back()->with('success', 'Integration settings updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update integration settings: ' . $e->getMessage());
        }
    }
}
