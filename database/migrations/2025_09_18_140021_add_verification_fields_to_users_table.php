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
        Schema::table('users', function (Blueprint $table) {
            // Check if columns don't exist before adding them
            if (!Schema::hasColumn('users', 'profile_completed_at')) {
                $table->timestamp('profile_completed_at')->nullable()->after('kyc_verified_at');
            }
            
            if (!Schema::hasColumn('users', 'phone_verification_token')) {
                $table->string('phone_verification_token', 6)->nullable()->after('profile_completed_at');
                $table->timestamp('phone_verification_token_expires_at')->nullable()->after('phone_verification_token');
            }
            
            // Profile completion tracking
            if (!Schema::hasColumn('users', 'required_fields_completed')) {
                $table->json('required_fields_completed')->nullable()->after('phone_verification_token_expires_at');
                $table->integer('profile_completion_percentage')->default(0)->after('required_fields_completed');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'profile_completed_at',
                'phone_verification_token',
                'phone_verification_token_expires_at',
                'required_fields_completed',
                'profile_completion_percentage'
            ]);
        });
    }
};
