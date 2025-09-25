<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\GeneralSetting;

class GeneralSettingsSeeder extends Seeder 
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if settings already exist
        if (GeneralSetting::count() > 0) {
            $this->command->info('General settings already exist. Skipping seeder.');
            return;
        } 

        GeneralSetting::create([
            // Basic Site Settings
            'site_name' => 'TechVenture Solutions Ltd.',
            'cur_text' => 'BDT',
            'cur_sym' => '৳',
            'email_from' => 'admin@techventure.com',
            'base_color' => '#007bff',
            'secondary_color' => '#6c757d',
            
            // Company Information
            'company_name' => 'TechVenture Solutions Ltd.',
            'company_address' => 'House #123, Road #456, Dhanmondi, Dhaka-1205, Bangladesh',
            'company_phone' => '+880 1700-123456',
            'company_email' => 'info@techventure.com',
            'company_website' => 'https://www.techventure.com',
            'company_tin' => '123456789012',
            'company_trade_license' => 'TL-2024-001234',
            'company_vat_number' => 'VAT-123456789',
            'contact_person' => 'Mohammad Rahman',
            'contact_designation' => 'Chief Executive Officer',
            
            // Bank Information
            'bank_name' => 'Islami Bank Bangladesh Limited',
            'bank_account_name' => 'TechVenture Solutions Ltd.',
            'bank_account_number' => '20502580100012345',
            'bank_routing_number' => '125261729',
            'bank_swift_code' => 'IBBLBDDH',
            
            // Email Template
            'email_template' => '<div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; background-color: #f8f9fa;">
                <div style="background-color: #007bff; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0;">
                    <h1 style="margin: 0;">{{site_name}}</h1>
                </div>
                <div style="background-color: white; padding: 30px; border-radius: 0 0 8px 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                    {{message_body}}
                </div>
                <div style="text-align: center; margin-top: 20px; color: #6c757d; font-size: 12px;">
                    <p>&copy; 2025 {{site_name}}. All rights reserved.</p>
                </div>
            </div>',
            
            // SMS Settings
            'sms_body' => 'Hello {{name}}, {{message}}',
            'sms_from' => 'IBL eCommerce',
            
            // Header Settings
            'header_content' => '<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
                <div class="container">
                    <a class="navbar-brand" href="/">{{site_name}}</a>
                </div>
            </nav>',
            'header_background_color' => '#ffffff',
            'header_text_color' => '#000000',
            'header_scripts' => json_encode([]),
            
            // Footer Settings
            'footer_content' => '<footer class="bg-dark text-white py-4">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>{{site_name}}</h5>
                            <p>Bangladesh\'s leading multi-vendor e-commerce marketplace.</p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <p>&copy; 2025 {{site_name}}. All rights reserved.</p>
                        </div>
                    </div>
                </div>
            </footer>',
            'footer_background_color' => '#343a40',
            'footer_text_color' => '#ffffff',
            'copyright_text' => '© 2025 IBL BD. All rights reserved.',
            'footer_scripts' => json_encode([]),
            
            // SEO Settings
            'meta_title' => 'IBL BD - Multi-Vendor E-commerce Platform',
            'meta_description' => 'Join IBL BD, the leading multi-vendor e-commerce platform in Bangladesh. Shop from thousands of vendors or start selling today.',
            'meta_keywords' => 'ecommerce, online shopping, multi-vendor, bangladesh, IBL BD, vendor marketplace',
            'meta_image' => '/assets/images/og-image.jpg',
            
            // Social Media Links
            'social_media_links' => json_encode([
                'facebook' => 'https://facebook.com/iblbd',
                'twitter' => 'https://twitter.com/iblbd',
                'instagram' => 'https://instagram.com/iblbd',
                'youtube' => 'https://youtube.com/iblbd'
            ]),
            
            // Contact Information
            'contact_email' => 'contact@techventure.com',
            'contact_phone' => '+880 1700-123456',
            'contact_address' => 'House #123, Road #456, Dhanmondi, Dhaka-1205, Bangladesh',
            
            // Content Areas
            'home_page_content' => '<div class="hero-section">
                <h1>Welcome to IBL BD</h1>
                <p>Bangladesh\'s premier multi-vendor e-commerce marketplace!</p>
                <a href="/register" class="btn btn-primary btn-lg">Get Started</a>
            </div>',
            
            'about_us_content' => '<h2>About IBL BD</h2>
            <p>IBL BD is Bangladesh\'s leading multi-vendor e-commerce platform that connects buyers with thousands of trusted vendors. We provide a secure and reliable marketplace for online shopping.</p>
            <h3>Our Mission</h3>
            <p>To create the most comprehensive and user-friendly e-commerce platform in Bangladesh, empowering vendors and providing customers with the best shopping experience.</p>',
            
            'terms_conditions' => '<h2>Terms and Conditions</h2>
            <h3>1. Acceptance of Terms</h3>
            <p>By using IBL BD, you agree to these terms and conditions.</p>
            <h3>2. User Responsibilities</h3>
            <p>Users must provide accurate information and comply with platform rules.</p>
            <h3>3. Payment Terms</h3>
            <p>Payments are processed according to our payment schedule and policies.</p>',
            
            'privacy_policy' => '<h2>Privacy Policy</h2>
            <h3>Information We Collect</h3>
            <p>We collect information necessary to provide our services, including email, name, and usage data.</p>
            <h3>How We Use Information</h3>
            <p>Information is used to provide services, process payments, and improve user experience.</p>
            <h3>Data Protection</h3>
            <p>We implement security measures to protect your personal information.</p>',
            
            // Custom Styling
            'custom_css' => '/* Custom CSS for IBL BD */
            .hero-section {
                background: linear-gradient(135deg, #007bff, #0056b3);
                color: white;
                padding: 80px 0;
                text-align: center;
            }
            
            .earning-card {
                border-radius: 15px;
                box-shadow: 0 4px 15px rgba(0,123,255,0.1);
                transition: transform 0.3s ease;
            }
            
            .earning-card:hover {
                transform: translateY(-5px);
            }',
            
            'custom_js' => '// Custom JavaScript for IBL BD
            document.addEventListener("DOMContentLoaded", function() {
                // Add smooth scrolling
                document.querySelectorAll("a[href^=\'#\']").forEach(anchor => {
                    anchor.addEventListener("click", function (e) {
                        e.preventDefault();
                        document.querySelector(this.getAttribute("href")).scrollIntoView({
                            behavior: "smooth"
                        });
                    });
                });
            });',

            // {"driver":"smtp","host":"localhost","port":"25","username":"noreply@iblbd.com","password":"vG586xq$7","encryption":"tls","from_address":"noreply@iblbd.com","from_name":"IBL BD"}
            
            // Configuration Settings
            'mail_config' => json_encode([
                'driver' => 'smtp',
                'host' => 'localhost',
                'port' => '25',
                'username' => 'info@iblbd.com',
                'password' => 'Mm123678@#',
                'encryption' => 'tls',
                'from_address' => 'noreply@iblbd.com',
                'from_name' => 'IBL BD'
            ]),
            
            'sms_config' => json_encode([
                'provider' => 'twilio',
                'api_key' => '',
                'api_secret' => '',
                'from_number' => ''
            ]),
            
            'global_shortcodes' => json_encode([
                'site_name' => 'IBL BD',
                'site_url' => url('/'),
                'support_email' => 'support@iblbd.com'
            ]),
            
            // System Settings
            'kv' => json_encode(['kyc_verification' => false]),
            'ev' => json_encode(['email_verification' => true]),
            'en' => json_encode(['email_notification' => true]),
            'sv' => json_encode(['sms_verification' => false]),
            'sn' => json_encode(['sms_notification' => false]),
            
            // Security and Features
            'force_ssl' => false,
            'maintenance_mode' => false,
            'secure_password' => true,
            'agree' => true,
            'registration' => true,
            'active_template' => 'basic',
            
            // Business Settings
            'deposit_commission' => 0.00,
            'investment_commission' => 0.00,
            'invest_return_commission' => 0.00,
            'signup_bonus_amount' => 0.00,
            'signup_bonus_control' => 'one_time',
            
            // Payment Settings
            'b_transfer' => true,
            'f_charge' => 0.50,
            'p_charge' => 12.00,
            'holiday_withdraw' => true,
            'language_switch' => true,
            
            // Notification Settings
            'notification_settings' => json_encode([
                'email_notifications' => true,
                'sms_notifications' => false,
                'push_notifications' => false,
                'notification_sound' => true
            ]),
            
            // Theme Settings
            'theme_settings' => json_encode([
                'theme_mode' => 'light',
                'primary_color' => '#007bff',
                'secondary_color' => '#6c757d',
                'success_color' => '#28a745',
                'warning_color' => '#ffc107',
                'danger_color' => '#dc3545',
                'dark_mode_available' => true
            ]),
            
            // System Information
            'system_info' => json_encode([
                'version' => '1.0.0',
                'last_update' => now()->toDateString(),
                'system_status' => 'active'
            ]),
            
            // Firebase Configuration
            'firebase_config' => json_encode([
                'enabled' => false,
                'api_key' => '',
                'auth_domain' => '',
                'project_id' => '',
                'storage_bucket' => '',
                'messaging_sender_id' => '',
                'app_id' => ''
            ]),
            
            'firebase_template' => json_encode([
                'title' => '{{site_name}}',
                'body' => '{{message}}',
                'icon' => '/assets/images/notification-icon.png'
            ]),
            
            // Additional Settings
            'push_notify' => false,
            'off_day' => 'Friday',
            'last_cron' => now(),
            'promotional_tool' => 'Shop from thousands of verified vendors across Bangladesh!',
            
            // Localization
            'timezone' => 'Asia/Dhaka',
            'date_format' => 'd-m-Y',
            'time_format' => 'H:i:s',
            
            // File Upload Settings
            'file_upload_settings' => json_encode([
                'max_file_size' => '2048', // 2MB in KB
                'allowed_extensions' => ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx'],
                'upload_path' => 'uploads/',
                'image_quality' => 85
            ]),
            
            // Security Settings
            'security_settings' => json_encode([
                'session_timeout' => 120, // minutes
                'max_login_attempts' => 5,
                'lockout_duration' => 15, // minutes
                'password_min_length' => 8,
                'require_special_chars' => true,
                'two_factor_auth' => false
            ]),
            
            // API Settings
            'api_settings' => json_encode([
                'api_enabled' => true,
                'api_rate_limit' => 100, // requests per minute
                'api_version' => 'v1',
                'cors_enabled' => true
            ]),
            
            // Maintenance Settings
            'maintenance_message' => 'We are currently performing scheduled maintenance. Please check back soon!',
            'maintenance_image' => '/assets/images/maintenance.png',
            
            // Widget Settings
            'widget_settings' => json_encode([
                'show_social_links' => true,
                'show_contact_info' => true,
                'show_newsletter_signup' => true,
                'show_recent_activities' => true
            ])
        ]);

        $this->command->info('General settings seeded successfully.');
    }
}
