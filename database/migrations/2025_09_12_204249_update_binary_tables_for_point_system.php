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
        // Update binary_matchings table
        if (Schema::hasTable('binary_matchings')) {
            Schema::table('binary_matchings', function (Blueprint $table) {
                $table->enum('bonus_type', ['volume_matching', 'point_matching'])->default('volume_matching')->after('matching_bonus')->comment('Type of matching bonus');
                $table->decimal('matched_points', 15, 2)->nullable()->after('bonus_type')->comment('Points matched in point-based system');
                $table->decimal('point_value', 8, 2)->default(6.00)->after('matched_points')->comment('Value per point in Tk');
            });
        }
        
        // Update binary_summaries table
        if (Schema::hasTable('binary_summaries')) {
            Schema::table('binary_summaries', function (Blueprint $table) {
                $table->enum('summary_type', ['volume', 'points'])->default('volume')->after('updated_at')->comment('Type of binary summary');
                $table->decimal('left_total_points', 15, 2)->nullable()->after('summary_type')->comment('Total left leg points');
                $table->decimal('right_total_points', 15, 2)->nullable()->after('left_total_points')->comment('Total right leg points');
                $table->decimal('matched_points', 15, 2)->nullable()->after('right_total_points')->comment('Total points matched');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('binary_matchings')) {
            Schema::table('binary_matchings', function (Blueprint $table) {
                $table->dropColumn(['bonus_type', 'matched_points', 'point_value']);
            });
        }
        
        if (Schema::hasTable('binary_summaries')) {
            Schema::table('binary_summaries', function (Blueprint $table) {
                $table->dropColumn(['summary_type', 'left_total_points', 'right_total_points', 'matched_points']);
            });
        }
    }
};
