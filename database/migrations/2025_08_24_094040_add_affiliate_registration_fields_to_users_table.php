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
            // Add position field for binary tree structure if it doesn't exist
            if (!Schema::hasColumn('users', 'position')) {
                $table->enum('position', ['left', 'right'])->nullable()->after('referral_hash');
            }
            
            // Add placement type field if it doesn't exist
            if (!Schema::hasColumn('users', 'placement_type')) {
                $table->enum('placement_type', ['auto', 'manual'])->default('auto')->after('position');
            }
            
            // Add marketing consent field if it doesn't exist
            if (!Schema::hasColumn('users', 'marketing_consent')) {
                $table->boolean('marketing_consent')->default(false)->after('placement_type');
            }
            
            // Ensure referral_code exists
            if (!Schema::hasColumn('users', 'referral_code')) {
                $table->string('referral_code')->nullable()->unique()->after('ref_by');
            }
            
            // Ensure referral_hash exists
            if (!Schema::hasColumn('users', 'referral_hash')) {
                $table->string('referral_hash', 8)->nullable()->unique()->after('referral_code');
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
                'position',
                'placement_type', 
                'marketing_consent'
            ]);
        });
    }
};
