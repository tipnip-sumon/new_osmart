<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $paymentMethods = [
            [
                'name' => 'Cash on Delivery',
                'code' => 'cash',
                'type' => 'cash',
                'description' => 'Pay cash when you receive the order',
                'is_active' => true,
                'requires_verification' => false,
                'min_amount' => 0,
                'max_amount' => null,
                'fee_percentage' => 0,
                'fee_fixed' => 0,
                'sort_order' => 1,
                'instructions' => 'You can pay cash to our delivery person when you receive your order.'
            ],
            [
                'name' => config('app.name', 'App') . ' Balance',
                'code' => 'app_balance',
                'type' => 'wallet',
                'description' => 'Pay using your ' . config('app.name', 'App') . ' wallet balance',
                'is_active' => true,
                'requires_verification' => false,
                'min_amount' => 1,
                'max_amount' => null,
                'fee_percentage' => 0,
                'fee_fixed' => 0,
                'sort_order' => 2,
                'instructions' => 'Payment will be instantly deducted from your ' . config('app.name', 'App') . ' wallet balance.'
            ],
            [
                'name' => 'bKash',
                'code' => 'bkash',
                'type' => 'mobile',
                'description' => 'Pay using bKash mobile banking',
                'account_number' => '+8801XXXXXXXXX',
                'account_name' => 'Business Account',
                'is_active' => true,
                'requires_verification' => true,
                'min_amount' => 10,
                'max_amount' => 50000,
                'fee_percentage' => 1.5,
                'fee_fixed' => 0,
                'sort_order' => 3,
                'extra_fields' => [
                    'sender_number' => 'required',
                    'transaction_id' => 'required',
                    'payment_proof' => 'required'
                ],
                'instructions' => 'Send money to our bKash number and provide the transaction ID and payment screenshot.'
            ],
            [
                'name' => 'Nagad',
                'code' => 'nagad',
                'type' => 'mobile',
                'description' => 'Pay using Nagad mobile banking',
                'account_number' => '+8801YYYYYYYYY',
                'account_name' => 'Business Account',
                'is_active' => true,
                'requires_verification' => true,
                'min_amount' => 10,
                'max_amount' => 50000,
                'fee_percentage' => 1.2,
                'fee_fixed' => 0,
                'sort_order' => 4,
                'extra_fields' => [
                    'sender_number' => 'required',
                    'transaction_id' => 'required',
                    'payment_proof' => 'required'
                ],
                'instructions' => 'Send money to our Nagad number and provide the transaction ID and payment screenshot.'
            ],
            [
                'name' => 'Rocket',
                'code' => 'rocket',
                'type' => 'mobile',
                'description' => 'Pay using Rocket mobile banking',
                'account_number' => '+8801ZZZZZZZZZ',
                'account_name' => 'Business Account',
                'is_active' => true,
                'requires_verification' => true,
                'min_amount' => 10,
                'max_amount' => 25000,
                'fee_percentage' => 1.8,
                'fee_fixed' => 0,
                'sort_order' => 5,
                'extra_fields' => [
                    'sender_number' => 'required',
                    'transaction_id' => 'required',
                    'payment_proof' => 'required'
                ],
                'instructions' => 'Send money to our Rocket number and provide the transaction ID and payment screenshot.'
            ],
            [
                'name' => 'Bank Transfer',
                'code' => 'bank_transfer',
                'type' => 'bank',
                'description' => 'Direct bank transfer',
                'account_number' => '1234567890',
                'account_name' => 'Company Limited',
                'bank_name' => 'ABC Bank Limited',
                'branch_name' => 'Main Branch',
                'routing_number' => '123456789',
                'is_active' => true,
                'requires_verification' => true,
                'min_amount' => 100,
                'max_amount' => null,
                'fee_percentage' => 0,
                'fee_fixed' => 0,
                'sort_order' => 6,
                'extra_fields' => [
                    'sender_bank' => 'required',
                    'sender_account' => 'required',
                    'transaction_id' => 'required',
                    'payment_proof' => 'required'
                ],
                'instructions' => 'Transfer money to our bank account and provide the bank slip or screenshot as proof.'
            ]
        ];

        foreach ($paymentMethods as $method) {
            PaymentMethod::updateOrCreate(
                ['code' => $method['code']],
                $method
            );
        }
    }
}
