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
        Schema::table('payment_transactions', function (Blueprint $table) {
            // Modify the type enum to include 'withdrawal'
            $table->enum('type', ['payment', 'refund', 'partial_refund', 'fund_addition', 'withdrawal'])->default('payment')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_transactions', function (Blueprint $table) {
            // Revert back to original enum without 'withdrawal'
            $table->enum('type', ['payment', 'refund', 'partial_refund', 'fund_addition'])->default('payment')->change();
        });
    }
};
