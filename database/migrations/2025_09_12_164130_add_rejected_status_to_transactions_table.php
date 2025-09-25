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
            // Modify the status enum to include 'rejected'
            $table->enum('status', ['pending', 'completed', 'failed', 'cancelled', 'rejected', 'approved'])->default('pending')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Revert back to original enum without 'rejected' and 'approved'
            $table->enum('status', ['pending', 'completed', 'failed', 'cancelled'])->default('pending')->change();
        });
    }
};
