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
            // Add calculation basis fields for different commission aspects
            $table->enum('qualification_basis', ['volume', 'points'])->default('volume')->after('min_qualification');
            $table->enum('pv_calculation_basis', ['volume', 'points'])->default('volume')->after('min_qualification');
            $table->enum('purchase_basis', ['volume', 'points'])->default('volume')->after('min_qualification');
            $table->enum('personal_volume_basis', ['volume', 'points'])->default('volume')->after('min_personal_volume');
            $table->enum('leg_calculation_basis', ['volume', 'points'])->default('volume')->after('min_right_volume');
            $table->enum('slot_volume_basis', ['volume', 'points'])->default('volume')->after('min_slot_volume');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('commission_settings', function (Blueprint $table) {
            $table->dropColumn([
                'qualification_basis',
                'pv_calculation_basis', 
                'purchase_basis',
                'personal_volume_basis',
                'leg_calculation_basis',
                'slot_volume_basis'
            ]);
        });
    }
};
