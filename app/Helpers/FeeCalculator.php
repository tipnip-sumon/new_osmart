<?php

namespace App\Helpers;

use App\Models\GeneralSetting;

class FeeCalculator
{
    /**
     * Calculate transfer fee based on wallet type and amount
     *
     * @param string $walletType
     * @param float $amount
     * @return array
     */
    public static function calculateTransferFee($walletType, $amount)
    {
        $settings = GeneralSetting::first();
        
        // Interest wallet transfers are free
        if ($walletType === 'interest_wallet') {
            return [
                'fee' => 0,
                'net_amount' => $amount,
                'total_deduction' => $amount,
                'min_amount' => 10, // Default minimum
                'max_amount' => 50000, // Default maximum
                'fee_type' => 'fixed',
                'fee_amount' => 0
            ];
        }
        
        // Get fee settings based on wallet type
        $feeType = $walletType === 'balance' 
            ? $settings->transfer_balance_fee_type 
            : $settings->transfer_deposit_fee_type;
            
        $feeAmount = $walletType === 'balance' 
            ? $settings->transfer_balance_fee_amount 
            : $settings->transfer_deposit_fee_amount;
            
        $minAmount = $walletType === 'balance' 
            ? $settings->transfer_balance_minimum_amount 
            : $settings->transfer_deposit_minimum_amount;
            
        $maxAmount = $walletType === 'balance' 
            ? $settings->transfer_balance_maximum_amount 
            : $settings->transfer_deposit_maximum_amount;
        
        // Calculate fee
        $fee = $feeType === 'percentage' 
            ? ($amount * $feeAmount / 100) 
            : $feeAmount;
            
        return [
            'fee' => $fee,
            'net_amount' => $amount,
            'total_deduction' => $amount + $fee,
            'min_amount' => $minAmount,
            'max_amount' => $maxAmount,
            'fee_type' => $feeType,
            'fee_amount' => $feeAmount
        ];
    }
    
    /**
     * Calculate withdrawal fee based on wallet type and amount
     *
     * @param string $walletType
     * @param float $amount
     * @return array
     */
    public static function calculateWithdrawalFee($walletType, $amount)
    {
        $settings = GeneralSetting::first();
        
        // Get fee settings based on wallet type
        switch ($walletType) {
            case 'balance':
                $feeType = $settings->withdrawal_balance_fee_type;
                $feeAmount = $settings->withdrawal_balance_fee_amount;
                $minAmount = $settings->withdrawal_balance_minimum_amount;
                $maxAmount = $settings->withdrawal_balance_maximum_amount;
                break;
            case 'deposit_wallet':
                $feeType = $settings->withdrawal_deposit_fee_type;
                $feeAmount = $settings->withdrawal_deposit_fee_amount;
                $minAmount = $settings->withdrawal_deposit_minimum_amount;
                $maxAmount = $settings->withdrawal_deposit_maximum_amount;
                break;
            case 'interest_wallet':
                $feeType = $settings->withdrawal_interest_fee_type;
                $feeAmount = $settings->withdrawal_interest_fee_amount;
                $minAmount = $settings->withdrawal_interest_minimum_amount;
                $maxAmount = $settings->withdrawal_interest_maximum_amount;
                break;
            default:
                $feeType = 'fixed';
                $feeAmount = 20;
                $minAmount = 100;
                $maxAmount = 50000;
        }
        
        // Calculate fee
        $fee = $feeType === 'percentage' 
            ? ($amount * $feeAmount / 100) 
            : $feeAmount;
            
        return [
            'fee' => $fee,
            'net_amount' => $amount - $fee,
            'total_deduction' => $amount,
            'min_amount' => $minAmount,
            'max_amount' => $maxAmount,
            'fee_type' => $feeType,
            'fee_amount' => $feeAmount
        ];
    }
    
    /**
     * Calculate fund addition fee based on payment method and amount
     *
     * @param string $paymentMethod
     * @param float $amount
     * @return array
     */
    public static function calculateFundFee($paymentMethod, $amount)
    {
        $settings = GeneralSetting::first();
        
        // Get fee settings based on payment method
        switch ($paymentMethod) {
            case 'bkash':
                $feeType = $settings->fund_bkash_fee_type;
                $feeAmount = $settings->fund_bkash_fee_amount;
                $minAmount = $settings->fund_bkash_minimum_amount;
                $maxAmount = $settings->fund_bkash_maximum_amount;
                break;
            case 'nagad':
                $feeType = $settings->fund_nagad_fee_type;
                $feeAmount = $settings->fund_nagad_fee_amount;
                $minAmount = $settings->fund_nagad_minimum_amount;
                $maxAmount = $settings->fund_nagad_maximum_amount;
                break;
            case 'rocket':
                $feeType = $settings->fund_rocket_fee_type;
                $feeAmount = $settings->fund_rocket_fee_amount;
                $minAmount = $settings->fund_rocket_minimum_amount;
                $maxAmount = $settings->fund_rocket_maximum_amount;
                break;
            case 'bank_transfer':
                $feeType = $settings->fund_bank_fee_type;
                $feeAmount = $settings->fund_bank_fee_amount;
                $minAmount = $settings->fund_bank_minimum_amount;
                $maxAmount = $settings->fund_bank_maximum_amount;
                break;
            case 'upay':
                $feeType = $settings->fund_upay_fee_type;
                $feeAmount = $settings->fund_upay_fee_amount;
                $minAmount = $settings->fund_upay_minimum_amount;
                $maxAmount = $settings->fund_upay_maximum_amount;
                break;
            default:
                $feeType = 'percentage';
                $feeAmount = 2.00;
                $minAmount = 10;
                $maxAmount = 100000;
        }
        
        // Calculate fee
        $fee = $feeType === 'percentage' 
            ? ($amount * $feeAmount / 100) 
            : $feeAmount;
            
        $netAmount = $amount - $fee;
        
        return [
            'fee' => $fee,
            'net_amount' => $netAmount,
            'total_amount' => $amount,
            'min_amount' => $minAmount,
            'max_amount' => $maxAmount,
            'fee_type' => $feeType,
            'fee_amount' => $feeAmount
        ];
    }
    
    /**
     * Get all payment methods with their fee settings
     *
     * @return array
     */
    public static function getPaymentMethods()
    {
        $settings = GeneralSetting::first();
        
        return [
            'bkash' => [
                'name' => 'bKash',
                'icon' => 'bkash-icon.png',
                'fee_type' => $settings->fund_bkash_fee_type,
                'fee_amount' => $settings->fund_bkash_fee_amount,
                'min_amount' => $settings->fund_bkash_minimum_amount,
                'max_amount' => $settings->fund_bkash_maximum_amount,
                'description' => 'Instant payment via bKash mobile wallet'
            ],
            'nagad' => [
                'name' => 'Nagad',
                'icon' => 'nagad-icon.png',
                'fee_type' => $settings->fund_nagad_fee_type,
                'fee_amount' => $settings->fund_nagad_fee_amount,
                'min_amount' => $settings->fund_nagad_minimum_amount,
                'max_amount' => $settings->fund_nagad_maximum_amount,
                'description' => 'Secure payment via Nagad digital wallet'
            ],
            'rocket' => [
                'name' => 'Rocket',
                'icon' => 'rocket-icon.png',
                'fee_type' => $settings->fund_rocket_fee_type,
                'fee_amount' => $settings->fund_rocket_fee_amount,
                'min_amount' => $settings->fund_rocket_minimum_amount,
                'max_amount' => $settings->fund_rocket_maximum_amount,
                'description' => 'Fast payment via Rocket mobile banking'
            ],
            'bank_transfer' => [
                'name' => 'Bank Transfer',
                'icon' => 'bank-icon.png',
                'fee_type' => $settings->fund_bank_fee_type,
                'fee_amount' => $settings->fund_bank_fee_amount,
                'min_amount' => $settings->fund_bank_minimum_amount,
                'max_amount' => $settings->fund_bank_maximum_amount,
                'description' => 'Direct bank account transfer'
            ],
            'upay' => [
                'name' => 'Upay',
                'icon' => 'upay-icon.png',
                'fee_type' => $settings->fund_upay_fee_type,
                'fee_amount' => $settings->fund_upay_fee_amount,
                'min_amount' => $settings->fund_upay_minimum_amount,
                'max_amount' => $settings->fund_upay_maximum_amount,
                'description' => 'Quick payment via Upay wallet'
            ]
        ];
    }
}
