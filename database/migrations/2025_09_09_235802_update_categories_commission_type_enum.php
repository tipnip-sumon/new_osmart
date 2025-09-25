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
        Schema::table('categories', function (Blueprint $table) {
            // Drop the existing enum column and recreate with updated values
            $table->dropColumn('commission_type');
        });
        
        Schema::table('categories', function (Blueprint $table) {
            // Recreate commission_type enum with disabled option
            $table->enum('commission_type', ['percentage', 'fixed', 'disabled'])->default('percentage')->after('commission_rate');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            // Drop the updated enum column
            $table->dropColumn('commission_type');
        });
        
        Schema::table('categories', function (Blueprint $table) {
            // Recreate original enum without disabled option
            $table->enum('commission_type', ['percentage', 'fixed'])->default('percentage')->after('commission_rate');
        });
    }
};
