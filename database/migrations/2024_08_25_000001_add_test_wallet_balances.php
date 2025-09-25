<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update existing users with some wallet balances for testing
        DB::table('users')->update([
            'balance' => DB::raw('CASE 
                WHEN balance IS NULL OR balance = 0 THEN 10000.00 
                ELSE balance 
            END'),
            'deposit_wallet' => DB::raw('CASE 
                WHEN deposit_wallet IS NULL OR deposit_wallet = 0 THEN 5000.00 
                ELSE deposit_wallet 
            END'),
            'interest_wallet' => DB::raw('CASE 
                WHEN interest_wallet IS NULL OR interest_wallet = 0 THEN 1000.00 
                ELSE interest_wallet 
            END'),
            'available_balance' => DB::raw('CASE 
                WHEN available_balance IS NULL OR available_balance = 0 THEN 3000.00 
                ELSE available_balance 
            END'),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reset wallet balances
        DB::table('users')->update([
            'balance' => 0,
            'deposit_wallet' => 0,
            'interest_wallet' => 0,
            'available_balance' => 0,
        ]);
    }
};
