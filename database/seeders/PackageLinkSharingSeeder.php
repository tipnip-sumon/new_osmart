<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PackageLinkSharingSetting;

class PackageLinkSharingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $packageSettings = [
            [
                'package_name' => 'starter',
                'daily_share_limit' => 5,
                'click_reward_amount' => 2.00,
                'daily_earning_limit' => 10.00,
                'total_share_limit' => 150,
                'is_active' => true,
                'conditions' => [
                    'min_package_value' => 100,
                    'unique_device_only' => true,
                    'attribution_hours' => 24
                ]
            ],
            [
                'package_name' => 'silver',
                'daily_share_limit' => 10,
                'click_reward_amount' => 2.00,
                'daily_earning_limit' => 20.00,
                'total_share_limit' => 300,
                'is_active' => true,
                'conditions' => [
                    'min_package_value' => 500,
                    'unique_device_only' => true,
                    'attribution_hours' => 24
                ]
            ],
            [
                'package_name' => 'gold',
                'daily_share_limit' => 20,
                'click_reward_amount' => 2.00,
                'daily_earning_limit' => 40.00,
                'total_share_limit' => 600,
                'is_active' => true,
                'conditions' => [
                    'min_package_value' => 1000,
                    'unique_device_only' => true,
                    'attribution_hours' => 24
                ]
            ],
            [
                'package_name' => 'diamond',
                'daily_share_limit' => 50,
                'click_reward_amount' => 2.00,
                'daily_earning_limit' => 100.00,
                'total_share_limit' => 1500,
                'is_active' => true,
                'conditions' => [
                    'min_package_value' => 5000,
                    'unique_device_only' => true,
                    'attribution_hours' => 24
                ]
            ]
        ];

        foreach ($packageSettings as $setting) {
            PackageLinkSharingSetting::create($setting);
            
            $this->command->info("Created package link sharing setting: {$setting['package_name']}");
        }
    }
}
