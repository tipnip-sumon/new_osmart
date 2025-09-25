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
        Schema::table('vendor_transfers', function (Blueprint $table) {
            // Add transfer_reference field for the generated transfer numbers like VT202509173120
            $table->string('transfer_reference', 50)->nullable()->after('transaction_id');
            $table->index('transfer_reference');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vendor_transfers', function (Blueprint $table) {
            $table->dropIndex(['transfer_reference']);
            $table->dropColumn('transfer_reference');
        });
    }
};
