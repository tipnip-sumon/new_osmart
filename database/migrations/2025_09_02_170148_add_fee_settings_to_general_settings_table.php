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
        Schema::table('general_settings', function (Blueprint $table) {
            // Transfer fee settings - Balance Wallet
            $table->enum('transfer_balance_fee_type', ['fixed', 'percentage'])->default('fixed')->after('time_format');
            $table->decimal('transfer_balance_fee_amount', 10, 2)->default(5.00)->after('transfer_balance_fee_type');
            $table->decimal('transfer_balance_minimum_amount', 10, 2)->default(10.00)->after('transfer_balance_fee_amount');
            $table->decimal('transfer_balance_maximum_amount', 10, 2)->default(50000.00)->after('transfer_balance_minimum_amount');
            
            // Transfer fee settings - Deposit Wallet
            $table->enum('transfer_deposit_fee_type', ['fixed', 'percentage'])->default('fixed')->after('transfer_balance_maximum_amount');
            $table->decimal('transfer_deposit_fee_amount', 10, 2)->default(3.00)->after('transfer_deposit_fee_type');
            $table->decimal('transfer_deposit_minimum_amount', 10, 2)->default(10.00)->after('transfer_deposit_fee_amount');
            $table->decimal('transfer_deposit_maximum_amount', 10, 2)->default(50000.00)->after('transfer_deposit_minimum_amount');
            
            // Withdrawal fee settings - Balance Wallet
            $table->enum('withdrawal_balance_fee_type', ['fixed', 'percentage'])->default('fixed')->after('transfer_deposit_maximum_amount');
            $table->decimal('withdrawal_balance_fee_amount', 10, 2)->default(20.00)->after('withdrawal_balance_fee_type');
            $table->decimal('withdrawal_balance_minimum_amount', 10, 2)->default(100.00)->after('withdrawal_balance_fee_amount');
            $table->decimal('withdrawal_balance_maximum_amount', 10, 2)->default(50000.00)->after('withdrawal_balance_minimum_amount');
            
            // Withdrawal fee settings - Deposit Wallet
            $table->enum('withdrawal_deposit_fee_type', ['fixed', 'percentage'])->default('fixed')->after('withdrawal_balance_maximum_amount');
            $table->decimal('withdrawal_deposit_fee_amount', 10, 2)->default(15.00)->after('withdrawal_deposit_fee_type');
            $table->decimal('withdrawal_deposit_minimum_amount', 10, 2)->default(100.00)->after('withdrawal_deposit_fee_amount');
            $table->decimal('withdrawal_deposit_maximum_amount', 10, 2)->default(50000.00)->after('withdrawal_deposit_minimum_amount');
            
            // Withdrawal fee settings - Interest Wallet
            $table->enum('withdrawal_interest_fee_type', ['fixed', 'percentage'])->default('fixed')->after('withdrawal_deposit_maximum_amount');
            $table->decimal('withdrawal_interest_fee_amount', 10, 2)->default(25.00)->after('withdrawal_interest_fee_type');
            $table->decimal('withdrawal_interest_minimum_amount', 10, 2)->default(100.00)->after('withdrawal_interest_fee_amount');
            $table->decimal('withdrawal_interest_maximum_amount', 10, 2)->default(50000.00)->after('withdrawal_interest_minimum_amount');
            
            // Add fund fee settings - bKash
            $table->enum('fund_bkash_fee_type', ['fixed', 'percentage'])->default('percentage')->after('withdrawal_interest_maximum_amount');
            $table->decimal('fund_bkash_fee_amount', 10, 2)->default(1.85)->after('fund_bkash_fee_type');
            $table->decimal('fund_bkash_minimum_amount', 10, 2)->default(10.00)->after('fund_bkash_fee_amount');
            $table->decimal('fund_bkash_maximum_amount', 10, 2)->default(25000.00)->after('fund_bkash_minimum_amount');
            
            // Add fund fee settings - Nagad
            $table->enum('fund_nagad_fee_type', ['fixed', 'percentage'])->default('percentage')->after('fund_bkash_maximum_amount');
            $table->decimal('fund_nagad_fee_amount', 10, 2)->default(1.80)->after('fund_nagad_fee_type');
            $table->decimal('fund_nagad_minimum_amount', 10, 2)->default(10.00)->after('fund_nagad_fee_amount');
            $table->decimal('fund_nagad_maximum_amount', 10, 2)->default(25000.00)->after('fund_nagad_minimum_amount');
            
            // Add fund fee settings - Rocket
            $table->enum('fund_rocket_fee_type', ['fixed', 'percentage'])->default('percentage')->after('fund_nagad_maximum_amount');
            $table->decimal('fund_rocket_fee_amount', 10, 2)->default(1.90)->after('fund_rocket_fee_type');
            $table->decimal('fund_rocket_minimum_amount', 10, 2)->default(10.00)->after('fund_rocket_fee_amount');
            $table->decimal('fund_rocket_maximum_amount', 10, 2)->default(20000.00)->after('fund_rocket_minimum_amount');
            
            // Add fund fee settings - Bank Transfer
            $table->enum('fund_bank_fee_type', ['fixed', 'percentage'])->default('fixed')->after('fund_rocket_maximum_amount');
            $table->decimal('fund_bank_fee_amount', 10, 2)->default(0.00)->after('fund_bank_fee_type');
            $table->decimal('fund_bank_minimum_amount', 10, 2)->default(100.00)->after('fund_bank_fee_amount');
            $table->decimal('fund_bank_maximum_amount', 10, 2)->default(100000.00)->after('fund_bank_minimum_amount');
            
            // Add fund fee settings - Upay
            $table->enum('fund_upay_fee_type', ['fixed', 'percentage'])->default('percentage')->after('fund_bank_maximum_amount');
            $table->decimal('fund_upay_fee_amount', 10, 2)->default(1.75)->after('fund_upay_fee_type');
            $table->decimal('fund_upay_minimum_amount', 10, 2)->default(10.00)->after('fund_upay_fee_amount');
            $table->decimal('fund_upay_maximum_amount', 10, 2)->default(25000.00)->after('fund_upay_minimum_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('general_settings', function (Blueprint $table) {
            $table->dropColumn([
                // Transfer settings - Balance Wallet
                'transfer_balance_fee_type',
                'transfer_balance_fee_amount',
                'transfer_balance_minimum_amount',
                'transfer_balance_maximum_amount',
                
                // Transfer settings - Deposit Wallet
                'transfer_deposit_fee_type',
                'transfer_deposit_fee_amount',
                'transfer_deposit_minimum_amount',
                'transfer_deposit_maximum_amount',
                
                // Withdrawal settings - Balance Wallet
                'withdrawal_balance_fee_type',
                'withdrawal_balance_fee_amount',
                'withdrawal_balance_minimum_amount',
                'withdrawal_balance_maximum_amount',
                
                // Withdrawal settings - Deposit Wallet
                'withdrawal_deposit_fee_type',
                'withdrawal_deposit_fee_amount',
                'withdrawal_deposit_minimum_amount',
                'withdrawal_deposit_maximum_amount',
                
                // Withdrawal settings - Interest Wallet
                'withdrawal_interest_fee_type',
                'withdrawal_interest_fee_amount',
                'withdrawal_interest_minimum_amount',
                'withdrawal_interest_maximum_amount',
                
                // Fund settings - bKash
                'fund_bkash_fee_type',
                'fund_bkash_fee_amount',
                'fund_bkash_minimum_amount',
                'fund_bkash_maximum_amount',
                
                // Fund settings - Nagad
                'fund_nagad_fee_type',
                'fund_nagad_fee_amount',
                'fund_nagad_minimum_amount',
                'fund_nagad_maximum_amount',
                
                // Fund settings - Rocket
                'fund_rocket_fee_type',
                'fund_rocket_fee_amount',
                'fund_rocket_minimum_amount',
                'fund_rocket_maximum_amount',
                
                // Fund settings - Bank Transfer
                'fund_bank_fee_type',
                'fund_bank_fee_amount',
                'fund_bank_minimum_amount',
                'fund_bank_maximum_amount',
                
                // Fund settings - Upay
                'fund_upay_fee_type',
                'fund_upay_fee_amount',
                'fund_upay_minimum_amount',
                'fund_upay_maximum_amount'
            ]);
        });
    }
};
