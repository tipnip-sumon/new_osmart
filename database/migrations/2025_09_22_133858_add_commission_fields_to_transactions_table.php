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
        Schema::table('transactions', function (Blueprint $table) {
            $table->decimal('commission_rate', 5, 2)->nullable()->after('amount')->comment('Commission rate percentage (e.g., 10.50 for 10.5%)');
            $table->decimal('commission_amount', 15, 2)->nullable()->after('commission_rate')->comment('Commission amount calculated');
            $table->decimal('base_amount', 15, 2)->nullable()->after('commission_amount')->comment('Original base amount before commission');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['commission_rate', 'commission_amount', 'base_amount']);
        });
    }
};
