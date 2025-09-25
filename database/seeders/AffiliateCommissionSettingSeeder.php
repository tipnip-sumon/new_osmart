<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CommissionSetting;

class AffiliateCommissionSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if affiliate commission setting already exists
        $existingAffiliateSetting = CommissionSetting::where('type', 'affiliate')->first();
        
        if (!$existingAffiliateSetting) {
            // Create default affiliate commission setting
            CommissionSetting::create([
                'name' => 'default_affiliate_commission',
                'display_name' => 'Default Affiliate Commission',
                'description' => 'Default commission rate for affiliate referrals when users purchase through shared product links',
                'type' => 'affiliate',
                'calculation_type' => 'percentage',
                'value' => 5.00, // 5% commission
                'conditions' => [
                    'min_order_amount' => 0,
                    'max_commission_per_order' => null,
                    'applicable_product_categories' => [], // Empty means all categories
                    'excluded_product_categories' => [],
                    'min_affiliate_level' => null,
                    'max_affiliate_level' => null
                ],
                'levels' => [], // Single level commission for now
                'min_qualification' => 0, // No minimum qualification
                'max_payout' => null, // No maximum payout limit
                'max_levels' => 1, // Single level
                'is_active' => true,
                'priority' => 100, // High priority
                'enable_multi_level' => false,
                // Enhanced features - all disabled for simple affiliate commission
                'carry_forward_enabled' => false,
                'carry_side' => null,
                'carry_percentage' => null,
                'carry_max_days' => null,
                'slot_matching_enabled' => false,
                'slot_size' => null,
                'slot_type' => null,
                'min_slot_volume' => null,
                'min_slot_count' => null,
                'auto_balance_enabled' => false,
                'balance_ratio' => 1.00, // Default ratio
                'spillover_enabled' => false,
                'spillover_direction' => null,
                'flush_enabled' => false,
                'flush_percentage' => null,
                'daily_cap_enabled' => false,
                'daily_cap_amount' => null,
                'weekly_cap_enabled' => false,
                'weekly_cap_amount' => null,
                'matching_frequency' => 'daily', // Default frequency
                'matching_time' => null,
                'personal_volume_required' => false,
                'min_personal_volume' => null,
                'both_legs_required' => false,
                'min_left_volume' => null,
                'min_right_volume' => null
            ]);
            
            $this->command->info('✅ Default affiliate commission setting created successfully');
        } else {
            $this->command->info('ℹ️  Affiliate commission setting already exists - skipping creation');
        }
        
        // Create additional affiliate commission tiers for different user levels
        $tiers = [
            [
                'name' => 'bronze_affiliate_commission',
                'display_name' => 'Bronze Affiliate Commission',
                'description' => 'Enhanced commission rate for Bronze level affiliates',
                'value' => 7.00, // 7% commission
                'priority' => 90,
                'conditions' => [
                    'min_affiliate_level' => 'bronze',
                    'min_order_amount' => 1000, // Minimum ৳1000 order
                ]
            ],
            [
                'name' => 'silver_affiliate_commission',
                'display_name' => 'Silver Affiliate Commission',
                'description' => 'Premium commission rate for Silver level affiliates',
                'value' => 10.00, // 10% commission
                'priority' => 80,
                'conditions' => [
                    'min_affiliate_level' => 'silver',
                    'min_order_amount' => 2000, // Minimum ৳2000 order
                ]
            ],
            [
                'name' => 'gold_affiliate_commission',
                'display_name' => 'Gold Affiliate Commission',
                'description' => 'Elite commission rate for Gold level affiliates',
                'value' => 15.00, // 15% commission
                'priority' => 70,
                'conditions' => [
                    'min_affiliate_level' => 'gold',
                    'min_order_amount' => 5000, // Minimum ৳5000 order
                ]
            ]
        ];
        
        foreach ($tiers as $tier) {
            $existingTier = CommissionSetting::where('name', $tier['name'])->first();
            
            if (!$existingTier) {
                CommissionSetting::create([
                    'name' => $tier['name'],
                    'display_name' => $tier['display_name'],
                    'description' => $tier['description'],
                    'type' => 'affiliate',
                    'calculation_type' => 'percentage',
                    'value' => $tier['value'],
                    'conditions' => $tier['conditions'],
                    'levels' => [],
                    'min_qualification' => 0,
                    'max_payout' => null,
                    'max_levels' => 1,
                    'is_active' => false, // Disabled by default - admin can enable as needed
                    'priority' => $tier['priority'],
                    'enable_multi_level' => false,
                    // All enhanced features disabled
                    'carry_forward_enabled' => false,
                    'carry_side' => null,
                    'carry_percentage' => null,
                    'carry_max_days' => null,
                    'slot_matching_enabled' => false,
                    'slot_size' => null,
                    'slot_type' => null,
                    'min_slot_volume' => null,
                    'min_slot_count' => null,
                    'auto_balance_enabled' => false,
                    'balance_ratio' => 1.00, // Default ratio
                    'spillover_enabled' => false,
                    'spillover_direction' => null,
                    'flush_enabled' => false,
                    'flush_percentage' => null,
                    'daily_cap_enabled' => false,
                    'daily_cap_amount' => null,
                    'weekly_cap_enabled' => false,
                    'weekly_cap_amount' => null,
                    'matching_frequency' => 'daily', // Default frequency
                    'matching_time' => null,
                    'personal_volume_required' => false,
                    'min_personal_volume' => null,
                    'both_legs_required' => false,
                    'min_left_volume' => null,
                    'min_right_volume' => null
                ]);
                
                $this->command->info("✅ {$tier['display_name']} setting created successfully");
            }
        }
        
        $this->command->info('🎉 Affiliate commission settings seeding completed!');
    }
}
