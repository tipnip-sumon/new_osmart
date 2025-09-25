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
            // Add MLM commission fields if they don't exist
            if (!Schema::hasColumn('plans', 'binary_left')) {
                $table->decimal('binary_left', 8, 2)->default(0);
            }
            if (!Schema::hasColumn('plans', 'binary_right')) {
                $table->decimal('binary_right', 8, 2)->default(0);
            }
            if (!Schema::hasColumn('plans', 'direct_commission')) {
                $table->decimal('direct_commission', 8, 2)->default(0);
            }
            if (!Schema::hasColumn('plans', 'level_commission')) {
                $table->decimal('level_commission', 8, 2)->default(0);
            }
            if (!Schema::hasColumn('plans', 'is_active')) {
                $table->boolean('is_active')->default(true);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn([
                'binary_left',
                'binary_right',
                'direct_commission',
                'level_commission',
                'is_active'
            ]);
        });
    }
};
