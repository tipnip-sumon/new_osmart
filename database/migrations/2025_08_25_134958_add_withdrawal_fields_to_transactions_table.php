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
            $table->decimal('fee', 10, 2)->nullable()->after('amount');
            $table->string('wallet_type')->nullable()->after('payment_method'); // balance, deposit_wallet, etc.
            $table->string('account_number')->nullable()->after('wallet_type');
            $table->string('account_name')->nullable()->after('account_number');
            $table->text('note')->nullable()->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['fee', 'wallet_type', 'account_number', 'account_name', 'note']);
        });
    }
};
