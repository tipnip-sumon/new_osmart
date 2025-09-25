<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MlmRank;
use App\Models\MlmCommissionStructure;
use App\Models\MlmBonusSetting;
use App\Models\MlmProductSetting;
use Illuminate\Support\Str;

class MlmSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if MLM data already exists
        if (MlmRank::count() > 0) {
            $this->command->info('MLM data already exists. Skipping seeding.');
            return;
        }

        // Create MLM Ranks
        $ranks = [
            [
                'name' => 'Member',
                'slug' => 'member',
                'description' => 'Starting level for all new members',
                'level' => 1,
                'color_code' => '#6B7280',
                'min_personal_volume' => 0,
                'min_team_volume' => 0,
                'min_direct_referrals' => 0,
                'min_active_legs' => 0,
                'qualification_period_months' => 1,
                'rank_bonus_amount' => 0,
                'commission_multiplier' => 1.00,
                'is_active' => true,
                'additional_benefits' => json_encode(['Basic commission eligibility']),
            ],
            [
                'name' => 'Bronze',
                'slug' => 'bronze',
                'description' => 'First achievement rank with enhanced benefits',
                'level' => 2,
                'color_code' => '#CD7F32',
                'min_personal_volume' => 100,
                'min_team_volume' => 500,
                'min_direct_referrals' => 2,
                'min_active_legs' => 2,
                'qualification_period_months' => 1,
                'rank_bonus_amount' => 50,
                'monthly_bonus_amount' => 25,
                'commission_multiplier' => 1.10,
                'override_bonus_eligible' => true,
                'is_active' => true,
                'additional_benefits' => json_encode(['10% commission boost', 'Monthly bonus', 'Override bonuses']),
            ],
            [
                'name' => 'Silver',
                'slug' => 'silver',
                'description' => 'Silver rank with leadership benefits',
                'level' => 3,
                'color_code' => '#C0C0C0',
                'min_personal_volume' => 200,
                'min_team_volume' => 1500,
                'min_left_leg_volume' => 500,
                'min_right_leg_volume' => 500,
                'min_direct_referrals' => 3,
                'min_active_legs' => 2,
                'qualification_period_months' => 2,
                'rank_bonus_amount' => 150,
                'monthly_bonus_amount' => 75,
                'commission_multiplier' => 1.25,
                'override_bonus_eligible' => true,
                'leadership_rank' => true,
                'max_compression_levels' => 3,
                'is_active' => true,
                'additional_benefits' => json_encode(['25% commission boost', 'Leadership bonuses', 'Compression bonuses']),
            ],
            [
                'name' => 'Gold',
                'slug' => 'gold',
                'description' => 'Gold rank with premium benefits and car bonus',
                'level' => 4,
                'color_code' => '#FFD700',
                'min_personal_volume' => 300,
                'min_team_volume' => 5000,
                'min_left_leg_volume' => 1500,
                'min_right_leg_volume' => 1500,
                'min_direct_referrals' => 4,
                'min_active_legs' => 2,
                'min_qualified_legs' => 2,
                'qualification_period_months' => 3,
                'rank_bonus_amount' => 500,
                'monthly_bonus_amount' => 200,
                'car_bonus_amount' => 300,
                'commission_multiplier' => 1.50,
                'infinity_bonus_eligible' => true,
                'infinity_bonus_rate' => 2.00,
                'override_bonus_eligible' => true,
                'leadership_rank' => true,
                'public_recognition' => true,
                'max_compression_levels' => 5,
                'is_active' => true,
                'additional_benefits' => json_encode(['50% commission boost', 'Car bonus', 'Infinity bonus', 'Public recognition']),
            ],
            [
                'name' => 'Platinum',
                'slug' => 'platinum',
                'description' => 'Platinum rank with travel benefits',
                'level' => 5,
                'color_code' => '#E5E4E2',
                'min_personal_volume' => 500,
                'min_team_volume' => 15000,
                'min_left_leg_volume' => 5000,
                'min_right_leg_volume' => 5000,
                'min_direct_referrals' => 5,
                'min_active_legs' => 2,
                'min_qualified_legs' => 3,
                'qualification_period_months' => 3,
                'rank_bonus_amount' => 1500,
                'monthly_bonus_amount' => 500,
                'car_bonus_amount' => 700,
                'travel_bonus_amount' => 2000,
                'commission_multiplier' => 2.00,
                'infinity_bonus_eligible' => true,
                'infinity_bonus_rate' => 3.00,
                'override_bonus_eligible' => true,
                'leadership_rank' => true,
                'public_recognition' => true,
                'recognition_title' => 'Platinum Leader',
                'max_compression_levels' => 7,
                'grace_period_months' => 2,
                'is_active' => true,
                'additional_benefits' => json_encode(['100% commission boost', 'Travel bonus', 'Premium car bonus', 'Leadership title']),
            ],
            [
                'name' => 'Diamond',
                'slug' => 'diamond',
                'description' => 'Highest achievable rank with lifetime benefits',
                'level' => 6,
                'color_code' => '#B9F2FF',
                'min_personal_volume' => 1000,
                'min_team_volume' => 50000,
                'min_left_leg_volume' => 15000,
                'min_right_leg_volume' => 15000,
                'min_direct_referrals' => 6,
                'min_active_legs' => 2,
                'min_qualified_legs' => 4,
                'qualification_period_months' => 6,
                'rank_bonus_amount' => 5000,
                'monthly_bonus_amount' => 1500,
                'car_bonus_amount' => 1500,
                'travel_bonus_amount' => 10000,
                'commission_multiplier' => 3.00,
                'infinity_bonus_eligible' => true,
                'infinity_bonus_rate' => 5.00,
                'override_bonus_eligible' => true,
                'leadership_rank' => true,
                'public_recognition' => true,
                'recognition_title' => 'Diamond Executive',
                'max_compression_levels' => 10,
                'grace_period_months' => 6,
                'lifetime_rank' => true,
                'is_active' => true,
                'additional_benefits' => json_encode(['300% commission boost', 'Lifetime status', 'Premium benefits', 'Executive title']),
            ],
        ];

        foreach ($ranks as $rank) {
            MlmRank::create($rank);
        }

        $this->command->info('MLM ranks seeded successfully!');
    }
}
