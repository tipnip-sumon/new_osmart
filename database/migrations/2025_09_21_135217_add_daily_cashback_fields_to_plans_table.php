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
        Schema::table('plans', function (Blueprint $table) {
            // Daily cashback system fields
            $table->boolean('daily_cashback_enabled')->default(false)->after('is_active');
            $table->decimal('daily_cashback_min', 8, 2)->default(0.00)->after('daily_cashback_enabled');
            $table->decimal('daily_cashback_max', 8, 2)->default(0.00)->after('daily_cashback_min');
            $table->integer('cashback_duration_days')->default(0)->after('daily_cashback_max')->comment('0 = unlimited, number = days');
            $table->enum('cashback_type', ['fixed', 'random', 'percentage'])->default('fixed')->after('cashback_duration_days');
            $table->boolean('is_special_package')->default(false)->after('cashback_type');
            
            // Referral conditions for cashback eligibility
            $table->json('referral_conditions')->nullable()->after('is_special_package')->comment('Dynamic referral requirements for cashback eligibility');
            $table->boolean('require_referral_for_cashback')->default(false)->after('referral_conditions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn([
                'daily_cashback_enabled',
                'daily_cashback_min',
                'daily_cashback_max',
                'cashback_duration_days',
                'cashback_type',
                'is_special_package',
                'referral_conditions',
                'require_referral_for_cashback'
            ]);
        });
    }
};
