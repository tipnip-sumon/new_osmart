<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Plan;

class CreateSpecialCashbackPackage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:special-cashback-package 
                            {--duration=365 : Duration in days for cashback (0 = unlimited)}
                            {--min=10 : Minimum daily cashback amount}
                            {--max=15 : Maximum daily cashback amount}
                            {--force : Force create even if exists}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create special 500-point cashback package with daily 10-15 TK cashback and referral conditions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $duration = $this->option('duration');
        $minCashback = $this->option('min');
        $maxCashback = $this->option('max');
        $force = $this->option('force');

        // Check if special package already exists
        $existingPackage = Plan::where('is_special_package', true)
                              ->where('minimum_points', 500)
                              ->first();

        if ($existingPackage && !$force) {
            $this->warn('🚫 Special 500-point cashback package already exists!');
            $this->table(['Property', 'Value'], [
                ['Package ID', $existingPackage->id],
                ['Package Name', $existingPackage->name],
                ['Minimum Points', $existingPackage->minimum_points],
                ['Daily Cashback Enabled', $existingPackage->daily_cashback_enabled ? 'Yes' : 'No'],
                ['Min Cashback', $existingPackage->daily_cashback_min],
                ['Max Cashback', $existingPackage->daily_cashback_max],
                ['Require Referral', $existingPackage->require_referral_for_cashback ? 'Yes' : 'No'],
            ]);
            
            $this->line('');
            $this->info('💡 Use --force flag to update the existing package');
            return 0;
        }

        if ($existingPackage && $force) {
            $this->warn('🔄 Updating existing package with --force flag');
            $package = $existingPackage;
        } else {
            $this->info('🆕 Creating new special cashback package');
            $package = new Plan();
        }

        // Define referral conditions for the special package
        $referralConditions = [
            'direct_referrals' => 5,
            'team_size' => 20,
            'min_investment' => 1000, // Points, not Taka
            'time_limit' => 90
        ];

        // Create/Update the special cashback package
        $package->fill([
            'name' => 'Special Daily Cashback Package',
            'description' => 'Premium package with daily cashback rewards. Get ৳' . $minCashback . '-' . $maxCashback . ' daily cashback after meeting referral conditions. Requires ৳500 + 500 points (৳3,000 value) investment. Team must invest 1,000 points (৳6,000 total value).',
            'fixed_amount' => 500.00,
            'minimum' => 500.00,
            'maximum' => 500.00,
            'minimum_points' => 500,
            'maximum_points' => 500,
            'point_price' => 1.00,
            'points_reward' => 500.00,
            'time' => 365,
            'time_name' => 'days',
            'status' => true,
            'is_active' => true,
            'point_based' => true,
            'wallet_purchase' => 1,
            'point_purchase' => 0,
            'is_special_package' => true,
            'purchase_type' => 'one_time',
            'daily_cashback_enabled' => true,
            'daily_cashback_min' => $minCashback,
            'daily_cashback_max' => $maxCashback,
            'cashback_duration_days' => $duration == 0 ? null : $duration,
            'cashback_type' => 'random',
            'require_referral_for_cashback' => true,
            'referral_conditions' => $referralConditions,
            'features' => [
                'daily_cashback' => true,
                'referral_bonus' => true,
                'premium_support' => true,
                'priority_processing' => true
            ],
            'sponsor_commission' => 1,
            'generation_commission' => 1,
            'binary_matching' => 0,
            'direct_commission' => 25.00,
            'level_commission' => 5.00,
            'binary_left' => 50.00,
            'binary_right' => 50.00,
        ]);

        $package->save();

        $this->line('');
        $this->info('🎉 Special Cashback Package ' . ($existingPackage && $force ? 'Updated' : 'Created') . ' Successfully!');
        
        // Display package details
        $this->table(['Property', 'Value'], [
            ['Package ID', $package->id],
            ['Package Name', $package->name],
            ['Fixed Amount', '৳' . number_format($package->fixed_amount, 2)],
            ['Required Points', $package->minimum_points],
            ['Daily Cashback Range', '৳' . $package->daily_cashback_min . ' - ৳' . $package->daily_cashback_max],
            ['Cashback Duration', $package->cashback_duration_days ? $package->cashback_duration_days . ' days' : 'Unlimited'],
            ['Requires Referral Conditions', $package->require_referral_for_cashback ? 'Yes' : 'No'],
            ['Package Validity', $package->time . ' ' . $package->time_name],
            ['Status', $package->is_active ? 'Active' : 'Inactive'],
        ]);

        if ($package->require_referral_for_cashback && $package->referral_conditions) {
            $this->line('');
            $this->info('📋 Referral Conditions:');
            $conditions = $package->referral_conditions;
            $this->table(['Requirement', 'Value'], [
                ['Direct Referrals Required', $conditions['direct_referrals'] ?? 'N/A'],
                ['Total Team Size Required', $conditions['team_size'] ?? 'N/A'],
                ['Minimum Team Investment', number_format($conditions['min_investment'] ?? 0) . ' Points'],
                ['Time Limit', ($conditions['time_limit'] ?? 'N/A') . ' days'],
            ]);
        }

        $this->line('');
        $this->info('💡 Next Steps:');
        $this->line('1. Run: php artisan cashback:process-daily to start processing daily cashbacks');
        $this->line('2. Run: php artisan cashback:release-pending to release pending cashbacks for qualified users');
        $this->line('3. Monitor the system with: php artisan cashback:process-daily --dry-run');

        return 0;
    }
}
