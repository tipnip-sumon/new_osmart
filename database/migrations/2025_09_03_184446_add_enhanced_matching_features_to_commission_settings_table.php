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
        Schema::table('commission_settings', function (Blueprint $table) {
            // Multi-Level Configuration Control
            $table->boolean('enable_multi_level')->default(false)->after('priority');
            
            // Enhanced Matching Commission Features
            $table->boolean('carry_forward_enabled')->default(false)->after('enable_multi_level');
            $table->enum('carry_side', ['strong', 'weak', 'both'])->nullable()->after('carry_forward_enabled');
            $table->decimal('carry_percentage', 5, 2)->nullable()->after('carry_side');
            $table->integer('carry_max_days')->nullable()->after('carry_percentage');
            
            // Slot Matching Features
            $table->boolean('slot_matching_enabled')->default(false)->after('carry_max_days');
            $table->integer('slot_size')->nullable()->after('slot_matching_enabled');
            $table->enum('slot_type', ['volume', 'count', 'mixed'])->nullable()->after('slot_size');
            $table->decimal('min_slot_volume', 15, 2)->nullable()->after('slot_type');
            $table->integer('min_slot_count')->nullable()->after('min_slot_volume');
            
            // Advanced Matching Rules
            $table->boolean('auto_balance_enabled')->default(false)->after('min_slot_count');
            $table->decimal('balance_ratio', 5, 2)->default(1.00)->after('auto_balance_enabled');
            $table->boolean('spillover_enabled')->default(false)->after('balance_ratio');
            $table->enum('spillover_direction', ['weaker', 'stronger', 'alternate'])->nullable()->after('spillover_enabled');
            
            // Flush and Capping
            $table->boolean('flush_enabled')->default(false)->after('spillover_direction');
            $table->decimal('flush_percentage', 5, 2)->nullable()->after('flush_enabled');
            $table->boolean('daily_cap_enabled')->default(false)->after('flush_percentage');
            $table->decimal('daily_cap_amount', 15, 2)->nullable()->after('daily_cap_enabled');
            $table->boolean('weekly_cap_enabled')->default(false)->after('daily_cap_amount');
            $table->decimal('weekly_cap_amount', 15, 2)->nullable()->after('weekly_cap_enabled');
            
            // Matching Frequency
            $table->enum('matching_frequency', ['real_time', 'hourly', 'daily', 'weekly'])->default('daily')->after('weekly_cap_amount');
            $table->time('matching_time')->nullable()->after('matching_frequency');
            
            // Qualification Requirements
            $table->boolean('personal_volume_required')->default(false)->after('matching_time');
            $table->decimal('min_personal_volume', 15, 2)->nullable()->after('personal_volume_required');
            $table->boolean('both_legs_required')->default(true)->after('min_personal_volume');
            $table->decimal('min_left_volume', 15, 2)->nullable()->after('both_legs_required');
            $table->decimal('min_right_volume', 15, 2)->nullable()->after('min_left_volume');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('commission_settings', function (Blueprint $table) {
            $table->dropColumn([
                'carry_forward_enabled',
                'carry_side',
                'carry_percentage',
                'carry_max_days',
                'slot_matching_enabled',
                'slot_size',
                'slot_type',
                'min_slot_volume',
                'min_slot_count',
                'auto_balance_enabled',
                'balance_ratio',
                'spillover_enabled',
                'spillover_direction',
                'flush_enabled',
                'flush_percentage',
                'daily_cap_enabled',
                'daily_cap_amount',
                'weekly_cap_enabled',
                'weekly_cap_amount',
                'matching_frequency',
                'matching_time',
                'personal_volume_required',
                'min_personal_volume',
                'both_legs_required',
                'min_left_volume',
                'min_right_volume',
                'enable_multi_level'
            ]);
        });
    }
};
