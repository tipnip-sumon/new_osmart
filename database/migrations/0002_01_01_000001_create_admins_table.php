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
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            
            // Basic information
            $table->string('name');
            $table->string('email')->unique();
            $table->string('username', 50)->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('image')->nullable();
            $table->string('phone', 20)->nullable();
            $table->timestamp('phone_verified_at')->nullable();
            $table->text('address')->nullable();
            
            // Role and permissions
            $table->enum('role', [
                'super_admin', 'admin', 'manager', 'supervisor', 
                'moderator', 'support', 'finance', 'marketing', 
                'hr', 'developer'
            ])->default('admin');
            $table->json('permissions')->nullable();
            $table->boolean('is_active')->default(false);
            $table->boolean('is_super_admin')->default(false);
            
            // Financial fields
            $table->decimal('balance', 15, 2)->default(0);
            $table->decimal('total_deposited', 15, 2)->default(0);
            $table->decimal('total_withdrawn', 15, 2)->default(0);
            $table->decimal('total_transferred', 15, 2)->default(0);
            
            // Two-factor authentication
            $table->text('two_factor_secret')->nullable();
            $table->text('two_factor_recovery_codes')->nullable();
            $table->timestamp('two_factor_confirmed_at')->nullable();
            
            // Login tracking and security
            $table->timestamp('last_login_at')->nullable();
            $table->ipAddress('last_login_ip')->nullable();
            $table->text('last_login_user_agent')->nullable();
            $table->unsignedInteger('login_attempts')->default(0);
            $table->timestamp('locked_until')->nullable();
            
            // Additional admin fields
            $table->text('notes')->nullable();
            $table->enum('status', [
                'active', 'inactive', 'suspended', 'on_leave', 
                'terminated', 'pending'
            ])->default('pending');
            $table->enum('department', [
                'administration', 'finance', 'marketing', 'hr', 
                'it', 'support', 'operations', 'sales', 
                'legal', 'compliance'
            ])->nullable();
            $table->string('designation', 100)->nullable();
            $table->string('employee_id', 20)->unique()->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female', 'other', 'prefer_not_to_say'])->nullable();
            $table->string('emergency_contact', 100)->nullable();
            $table->string('emergency_phone', 20)->nullable();
            $table->date('hire_date')->nullable();
            $table->decimal('salary', 12, 2)->nullable();
            $table->decimal('commission_rate', 5, 4)->default(0);
            $table->json('preferences')->nullable();
            
            // Session tracking
            $table->string('session_id')->nullable();
            $table->timestamp('session_created_at')->nullable();
            $table->timestamp('last_activity_at')->nullable();
            
            // Password management
            $table->timestamp('password_changed_at')->nullable();
            $table->boolean('must_change_password')->default(true);
            
            // API access
            $table->boolean('api_access_enabled')->default(false);
            $table->unsignedInteger('api_rate_limit')->default(1000);
            
            // Location details
            $table->string('country', 100)->nullable();
            $table->string('state', 100)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('postal_code', 20)->nullable();
            
            // User preferences
            $table->string('timezone', 50)->default('UTC');
            $table->string('language', 10)->default('en');
            $table->enum('theme_preference', ['light', 'dark', 'auto'])->default('light');
            
            // Supervisor relationship
            $table->foreignId('supervisor_id')->nullable()->constrained('admins')->onDelete('set null');
            
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes for better performance
            $table->index(['email']);
            $table->index(['username']);
            $table->index(['phone']);
            $table->index(['role', 'is_active']);
            $table->index(['status']);
            $table->index(['department']);
            $table->index(['employee_id']);
            $table->index(['is_super_admin']);
            $table->index(['email_verified_at']);
            $table->index(['phone_verified_at']);
            $table->index(['last_login_at']);
            $table->index(['login_attempts']);
            $table->index(['locked_until']);
            $table->index(['last_activity_at']);
            $table->index(['supervisor_id']);
            $table->index(['hire_date']);
            $table->index(['created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
