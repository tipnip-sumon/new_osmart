<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            
            // Basic user information
            $table->string('firstname', 100)->nullable();
            $table->string('lastname', 100)->nullable();
            $table->string('username', 50)->unique()->nullable();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('phone', 20)->nullable();
            $table->string('mobile', 20)->nullable(); // Alternative mobile field
            $table->timestamp('phone_verified_at')->nullable();
            $table->string('avatar')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female', 'other', 'prefer_not_to_say'])->nullable();

            // Address information
            $table->text('address')->nullable();
            $table->string('city', 100)->nullable();
            $table->string('state', 100)->nullable();
            $table->string('country', 100)->nullable();
            $table->string('postal_code', 20)->nullable();

            // Role and status
            $table->enum('role', ['customer', 'vendor', 'affiliate', 'admin', 'super_admin'])->default('customer');
            $table->enum('status', ['active', 'inactive', 'suspended', 'banned', 'pending'])->default('active');

            // Sponsor/Referral system
            $table->string('sponsor', 100)->nullable(); // Sponsor username/ID
            $table->foreignId('sponsor_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('ref_by', 100)->nullable(); // Referrer username
            $table->string('referral_code', 20)->unique()->nullable();
            $table->string('referral_hash', 64)->unique()->nullable(); // Referral hash

            // Verification status fields (0/1 flags for compatibility)
            $table->tinyInteger('ev')->default(0)->comment('Email verification status');
            $table->tinyInteger('sv')->default(0)->comment('SMS verification status');
            $table->tinyInteger('kv')->default(0)->comment('KYC verification status');

            // Financial balances and wallets
            $table->decimal('balance', 15, 2)->default(0)->comment('Main user balance');
            $table->decimal('deposit_wallet', 15, 2)->default(0)->comment('Deposit wallet balance');
            $table->decimal('interest_wallet', 15, 2)->default(0)->comment('Interest wallet balance');

            // KYC (Know Your Customer) fields
            $table->enum('kyc_status', ['not_submitted', 'pending', 'under_review', 'verified', 'rejected', 'resubmission_required'])
                  ->default('not_submitted');
            $table->timestamp('kyc_submitted_at')->nullable();
            $table->timestamp('kyc_verified_at')->nullable();
            $table->timestamp('kyc_rejected_at')->nullable();
            $table->text('kyc_rejection_reason')->nullable();
            $table->json('kyc_documents')->nullable();

            // Identity verification
            $table->enum('identity_type', ['national_id', 'passport', 'driving_license', 'voter_id'])->nullable();
            $table->string('identity_number', 100)->nullable();
            $table->string('identity_document')->nullable();
            $table->string('address_document')->nullable();
            $table->string('selfie_document')->nullable();

            // Banking information
            $table->string('bank_name', 100)->nullable();
            $table->string('bank_account_number', 50)->nullable();
            $table->string('bank_account_name', 100)->nullable();
            $table->string('bank_routing_number', 50)->nullable();
            $table->string('bank_swift_code', 20)->nullable();

            // Commission and earnings
            $table->decimal('commission_rate', 5, 4)->default(0.0500); // 5% default
            $table->decimal('total_earnings', 15, 2)->default(0);
            $table->decimal('available_balance', 15, 2)->default(0);
            $table->decimal('pending_balance', 15, 2)->default(0);
            $table->decimal('withdrawn_amount', 15, 2)->default(0);

            // Login tracking and security
            $table->timestamp('last_login_at')->nullable();
            $table->ipAddress('last_login_ip')->nullable();
            $table->text('last_login_user_agent')->nullable();
            $table->unsignedInteger('login_count')->default(0);
            $table->unsignedInteger('login_attempts')->default(0);
            $table->timestamp('locked_until')->nullable();

            // Session tracking fields
            $table->string('current_session_id')->nullable();
            $table->timestamp('session_created_at')->nullable();
            $table->ipAddress('session_ip_address')->nullable();
            $table->text('session_user_agent')->nullable();
            $table->timestamp('last_activity_at')->nullable();

            // Two-factor authentication
            $table->boolean('two_factor_enabled')->default(false);
            $table->text('two_factor_secret')->nullable();
            $table->text('two_factor_recovery_codes')->nullable();

            // Preferences and notes
            $table->json('preferences')->nullable();
            $table->text('notes')->nullable();

            // Status flags
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_verified_vendor')->default(false);

            // Vendor-specific fields
            $table->string('shop_name', 255)->nullable();
            $table->text('shop_description')->nullable();
            $table->string('shop_logo')->nullable();
            $table->string('shop_banner')->nullable();
            $table->text('shop_address')->nullable();
            $table->string('business_license')->nullable();
            $table->string('tax_id', 50)->nullable();

            // Subscription fields (plan reference removed since subscription_plans table doesn't exist)
            $table->unsignedBigInteger('subscription_plan_id')->nullable();
            $table->timestamp('subscription_expires_at')->nullable();
            $table->timestamp('trial_ends_at')->nullable();

            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

            // Indexes for better performance
            $table->index(['username']);
            $table->index(['firstname', 'lastname']);
            $table->index(['phone']);
            $table->index(['mobile']);
            $table->index(['role', 'is_active']);
            $table->index(['status']);
            $table->index(['sponsor']);
            $table->index(['sponsor_id']);
            $table->index(['ref_by']);
            $table->index(['referral_code']);
            $table->index(['referral_hash']);
            $table->index(['ev', 'sv', 'kv']); // Composite index for verification status
            $table->index(['balance']);
            $table->index(['deposit_wallet']);
            $table->index(['interest_wallet']);
            $table->index(['kyc_status']);
            $table->index(['country', 'city']);
            $table->index(['email_verified_at']);
            $table->index(['phone_verified_at']);
            $table->index(['last_login_at']);
            $table->index(['login_attempts']);
            $table->index(['locked_until']);
            $table->index(['current_session_id']);
            $table->index(['last_activity_at']);
            $table->index(['created_at']);
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
