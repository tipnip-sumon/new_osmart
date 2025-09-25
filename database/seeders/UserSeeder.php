<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        // Clear existing data
        DB::table('users')->delete();

        // Insert single default user
        DB::table('users')->insert([
            'id' => 1,
            'firstname' => 'Md Thamedul',
            'lastname' => 'Islam',
            'username' => 'sumon01',
            'name' => 'Md Thamedul Islam',
            'email' => 'sumonmti498@gmail.com',
            'email_verified_at' => $now,
            'password' => Hash::make('11111111'),
            'phone' => '+8801787909190',
            'mobile' => '+8801787909190',
            'phone_verified_at' => $now,
            'avatar' => null,
            'date_of_birth' => '1982-06-11',
            'gender' => 'prefer_not_to_say',
            'address' => '128/1, East Tejturi bazar,Karwan Bazar, Dhaka',
            'city' => 'Dhaka',
            'state' => 'Dhaka',
            'district' => 'Dhaka',
            'upazila' => 'Tejgaon',
            'union_ward' => 'Ward-15',
            'country' => 'Bangladesh',
            'postal_code' => '1215',
            'role' => 'affiliate',
            'status' => 'active',
            'sponsor' => null,
            'sponsor_id' => null,
            'ref_by' => null,
            'referral_code' => 'SUMON01',
            'referral_hash' => bin2hex(random_bytes(16)),
            'position' => null,
            'placement_type' => 'auto',
            'marketing_consent' => true,
            'ev' => 1, // Email verified
            'sv' => 1, // SMS verified
            'kv' => 1, // KYC verified
            'balance' => 0.00,
            'deposit_wallet' => 0.00,
            'interest_wallet' => 0.00,
            'reserve_points' => 0.00,
            'active_points' => 0.00,
            'total_points_earned' => 0.00,
            'total_points_used' => 0.00,
            'kyc_status' => 'verified',
            'kyc_submitted_at' => $now,
            'kyc_verified_at' => $now,
            'profile_completed_at' => $now,
            'phone_verification_token' => null,
            'phone_verification_token_expires_at' => null,
            'required_fields_completed' => true,
            'profile_completion_percentage' => 100.00,
            'kyc_rejected_at' => null,
            'kyc_rejection_reason' => null,
            'kyc_documents' => json_encode([]),
            'identity_type' => 'passport',
            'identity_number' => 'P123456789',
            'identity_document' => null,
            'address_document' => null,
            'selfie_document' => null,
            'bank_name' => 'Default Bank',
            'bank_account_number' => '1234567890',
            'bank_account_name' => 'Default User',
            'bank_routing_number' => '123456789',
            'bank_swift_code' => 'DEFBANK',
            'commission_rate' => 0.05, // 5%
            'total_earnings' => 0.00,
            'available_balance' => 0.00,
            'pending_balance' => 0.00,
            'withdrawn_amount' => 0.00,
            'last_login_at' => $now,
            'last_login_ip' => '127.0.0.1',
            'last_login_user_agent' => 'Mozilla/5.0 (Default User)',
            'login_count' => 1,
            'login_attempts' => 0,
            'locked_until' => null,
            'current_session_id' => null,
            'session_created_at' => null,
            'session_ip_address' => null,
            'session_user_agent' => null,
            'last_activity_at' => $now,
            'two_factor_enabled' => false,
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'preferences' => json_encode([
                'email_notifications' => true,
                'sms_notifications' => true,
                'marketing_emails' => false,
                'newsletter' => false,
                'two_factor_auth' => false,
                'session_timeout' => 120,
                'login_alerts' => true
            ]),
            'notes' => 'Default System User',
            'is_active' => true,
            'is_featured' => false,
            'is_verified_vendor' => false,
            'shop_name' => null,
            'shop_description' => null,
            'shop_logo' => null,
            'shop_banner' => null,
            'shop_address' => null,
            'business_license' => null,
            'tax_id' => null,
            'subscription_plan_id' => null,
            'current_package_id' => null,
            'current_package_tier' => null,
            'accumulated_points' => 0.00,
            'pending_payout_points' => 0.00,
            'package_activated_at' => null,
            'last_package_upgrade_at' => null,
            'next_payout_eligible_at' => null,
            'payout_locked' => false,
            'total_package_investment' => 0.00,
            'subscription_expires_at' => null,
            'trial_ends_at' => null,
            'remember_token' => null,
            'created_at' => $now,
            'updated_at' => $now,
            'deleted_at' => null,
            'image_data' => null,
            'monthly_sales_volume' => 0.00,
            'processed_monthly_volume' => 0.00,
            'daily_sales_volume' => 0.00,
            'processed_daily_volume' => 0.00,
            'total_sales_volume' => 0.00,
            'processed_total_volume' => 0.00,
            'last_payout_processed_at' => null,
            'last_daily_reset_date' => null,
                        'last_monthly_reset_period' => null,
        ]);

        // Reset auto-increment counters
        DB::statement('ALTER TABLE users AUTO_INCREMENT = 2');

        $this->command->info('✅ Single affiliate user created successfully!');
        $this->command->info('📧 Login credentials:');
        $this->command->info('Affiliate: sumonmti498@gmail.com / 11111111');
        $this->command->info('');
        $this->command->info('🎭 Affiliate user with complete profile!');
    }
}
