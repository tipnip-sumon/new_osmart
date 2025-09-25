<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\DB;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// ===========================
// VOLUME TRACKING COMMANDS (NEW SYSTEM)
// ===========================

// Daily volume reset at midnight BDT (06:01 AM BDT)
Schedule::command('volume:reset-processed --type=daily')
    ->dailyAt('00:01')
    ->withoutOverlapping()
    ->onOneServer()
    ->runInBackground()
    ->appendOutputTo(storage_path('logs/volume-reset-daily.log'));

// Monthly volume reset on 1st of month BDT (06:05 AM BDT)
Schedule::command('volume:reset-processed --type=monthly')
    ->monthlyOn(1, '00:05')
    ->withoutOverlapping()
    ->onOneServer()
    ->runInBackground()
    ->appendOutputTo(storage_path('logs/volume-reset-monthly.log'));

// Daily system maintenance at 07:00 AM BDT
Schedule::command('system:daily-maintenance')
    ->dailyAt('01:00')
    ->withoutOverlapping()
    ->onOneServer()
    ->runInBackground()
    ->appendOutputTo(storage_path('logs/daily-maintenance.log'));

// ===========================
// DAILY CASHBACK SYSTEM SCHEDULING
// ===========================

// Process daily cashbacks at 02:00 AM BDT
Schedule::command('cashback:process-daily')
    ->dailyAt('02:00')
    ->withoutOverlapping()
    ->onOneServer()
    ->runInBackground()
    ->appendOutputTo(storage_path('logs/cashback-processing.log'));

// Release pending cashbacks at 02:30 AM BDT (after processing)
Schedule::command('cashback:release-pending')
    ->dailyAt('02:30')
    ->withoutOverlapping()
    ->onOneServer()
    ->runInBackground()
    ->appendOutputTo(storage_path('logs/cashback-release.log'));

// ===========================
// Sales Tracking Scheduling (Updated for Payment Status)
// ===========================

// Real-time sales tracking - every minute for active users
Schedule::command('sales:update-tracking --frequency=minute')
    ->everyMinute()
    ->withoutOverlapping()
    ->onOneServer()
    ->runInBackground()
    ->appendOutputTo(storage_path('logs/sales-tracking-minute.log'));

// Hourly comprehensive sales tracking
Schedule::command('sales:update-tracking --frequency=hour')
    ->hourly()
    ->withoutOverlapping()
    ->onOneServer()
    ->runInBackground()
    ->appendOutputTo(storage_path('logs/sales-tracking-hourly.log'));

// ===========================
// Matching Bonus Scheduling (Updated for Payment-Based System)
// ===========================

// Daily matching process - runs every day at 08:00 AM BDT
Schedule::command('matching:daily-process')
    ->dailyAt('02:00')
    ->withoutOverlapping()
    ->onOneServer()
    ->runInBackground()
    ->appendOutputTo(storage_path('logs/daily-matching.log'));

// Reset monthly data on the 1st of each month at 09:00 AM BDT
Schedule::command('matching:reset-monthly --force')
    ->monthlyOn(1, '03:00')
    ->withoutOverlapping()
    ->onOneServer()
    ->runInBackground()
    ->appendOutputTo(storage_path('logs/matching-monthly-reset.log'));

// Weekly comprehensive update - every Monday at 03:00 PM BDT
Schedule::command('sales:update-tracking --frequency=day')
    ->weeklyOn(1, '09:00')
    ->withoutOverlapping()
    ->onOneServer()
    ->runInBackground()
    ->appendOutputTo(storage_path('logs/sales-tracking-weekly.log'));

// ===========================
// Real-Time Binary Volume Tracking
// ===========================

// Binary-only updates every 30 seconds for real-time left/right volumes
Schedule::command('sales:update-tracking --frequency=second --binary-only')
    ->everyThirtySeconds()
    ->withoutOverlapping()
    ->onOneServer()
    ->runInBackground()
    ->appendOutputTo(storage_path('logs/binary-realtime.log'));

// Binary volume updates every 2 minutes for better performance
Schedule::command('sales:update-tracking --frequency=minute --binary-only')
    ->everyTwoMinutes()
    ->withoutOverlapping()
    ->onOneServer()
    ->runInBackground()
    ->appendOutputTo(storage_path('logs/binary-minute.log'));

// ===========================
// MANUAL CONSOLE COMMANDS REGISTRATION
// ===========================

// Quick volume check command
Artisan::command('volume:check {username?}', function ($username = null) {
    $this->info('=== Volume Status Check ===');
    
    if ($username) {
        $user = App\Models\User::where('username', $username)->first();
        if (!$user) {
            $this->error("User not found: {$username}");
            return;
        }
        $users = collect([$user]);
    } else {
        $users = App\Models\User::where('is_active', true)->limit(5)->get();
    }
    
    $volumeService = app(App\Services\VolumeTrackingService::class);
    
    foreach ($users as $user) {
        $summary = $volumeService->getVolumeSummary($user);
        $this->info("User: {$user->username}");
        $this->info("  Monthly: Total ৳{$summary['monthly']['total']} | Available ৳{$summary['monthly']['available_for_payout']}");
        $this->info("  Daily: Total ৳{$summary['daily']['total']} | Available ৳{$summary['daily']['available_for_payout']}");
        $this->line('---');
    }
})->purpose('Quick volume status check for users');

// Emergency volume recalculation
Artisan::command('volume:emergency-fix {username?}', function ($username = null) {
    $this->info('=== Emergency Volume Recalculation ===');
    
    $volumeService = app(App\Services\VolumeTrackingService::class);
    
    if ($username) {
        $user = App\Models\User::where('username', $username)->first();
        if (!$user) {
            $this->error("User not found: {$username}");
            return;
        }
        $this->info("Recalculating volumes for: {$username}");
        $volumes = $volumeService->recalculateUserVolumes($user);
        $this->info("Updated: Daily ৳{$volumes['daily']}, Monthly ৳{$volumes['monthly']}, Total ৳{$volumes['total']}");
    } else {
        $this->info('Recalculating volumes for all active users...');
        $users = App\Models\User::where('is_active', true)->get();
        $bar = $this->output->createProgressBar($users->count());
        
        foreach ($users as $user) {
            $volumeService->recalculateUserVolumes($user);
            $bar->advance();
        }
        $bar->finish();
        $this->newLine();
        $this->info("✅ Emergency recalculation complete for {$users->count()} users");
    }
})->purpose('Emergency volume recalculation from paid orders');

// System health check
Artisan::command('system:health-check', function () {
    $this->info('=== System Health Check ===');
    
    // Check database
    try {
        DB::connection()->getPdo();
        $this->info('✅ Database connection: OK');
    } catch (\Exception $e) {
        $this->error('❌ Database connection: FAILED');
    }
    
    // Check volume tracking
    $volumeService = app(App\Services\VolumeTrackingService::class);
    $testUser = App\Models\User::where('is_active', true)->first();
    if ($testUser) {
        $summary = $volumeService->getVolumeSummary($testUser);
        $this->info('✅ Volume tracking service: OK');
    } else {
        $this->error('❌ No active users found');
    }
    
    // Check orders with payment status
    $paidOrders = App\Models\Order::where('payment_status', 'paid')->count();
    $this->info("✅ Paid orders in system: {$paidOrders}");
    
    // Check recent volume updates
    $recentUpdates = App\Models\User::whereNotNull('last_payout_processed_at')
                                   ->where('last_payout_processed_at', '>=', now()->subDays(7))
                                   ->count();
    $this->info("✅ Users with recent volume processing: {$recentUpdates}");
    
    $this->info('=== Health Check Complete ===');
})->purpose('Comprehensive system health check');

// ===========================
// BINARY RANK PROCESSING AND SALARY DISTRIBUTION
// ===========================

// Process binary rank achievements and qualifications - every hour
Schedule::command('binary-rank:process')
    ->hourly()
    ->withoutOverlapping()
    ->onOneServer()
    ->runInBackground()
    ->appendOutputTo(storage_path('logs/binary-rank-processing.log'));

// Update qualification progress for all active qualification periods - every 6 hours
Schedule::command('rank:process-qualifications')
    ->everySixHours()
    ->withoutOverlapping()
    ->onOneServer()
    ->runInBackground()
    ->appendOutputTo(storage_path('logs/rank-qualifications.log'));

// Distribute eligible rank salaries - daily at 12:00 PM BDT
Schedule::command('rank:process-qualifications --distribute-salary')
    ->dailyAt('06:00')
    ->withoutOverlapping()
    ->onOneServer()
    ->runInBackground()
    ->appendOutputTo(storage_path('logs/rank-salary-distribution.log'));

// Monthly rank salary distribution - 1st of each month at 01:00 PM BDT
Schedule::command('rank:process-qualifications --distribute-salary')
    ->monthlyOn(1, '07:00')
    ->withoutOverlapping()
    ->onOneServer()
    ->runInBackground()
    ->appendOutputTo(storage_path('logs/monthly-rank-salaries.log'));

// Rank system health check - daily at 02:00 PM BDT
Schedule::command('rank:health-check')
    ->dailyAt('08:00')
    ->withoutOverlapping()
    ->onOneServer()
    ->runInBackground()
    ->appendOutputTo(storage_path('logs/rank-health-check.log'));

// Clean up old qualification tracking data - weekly on Sunday at 08:00 AM BDT
Schedule::call(function () {
    // Clean up qualification tracking data older than 90 days
    $cleaned = App\Models\BinaryRankAchievement::where('qualification_period_active', false)
                ->where('salary_qualification_start_date', '<', now()->subDays(90))
                ->update(['qualification_monthly_tracking' => null]);
    
    \Illuminate\Support\Facades\Log::info("Cleaned qualification tracking data for {$cleaned} records");
})->weeklyOn(0, '02:00')
  ->name('cleanup-qualification-data')
  ->withoutOverlapping()
  ->onOneServer();

// ===========================
// COMMISSION DISTRIBUTION COMMANDS
// ===========================

// Scheduled automatic commission processing for eligible users
Schedule::command('commission:distribute')
    ->dailyAt('01:00') // Run at 07:00 AM BDT daily
    ->withoutOverlapping()
    ->onOneServer()
    ->runInBackground()
    ->appendOutputTo(storage_path('logs/commission-distribution.log'));

// Quick commission distribution command for specific user
Artisan::command('commission:quick {user_id} {points=100}', function () {
    $userId = $this->argument('user_id');
    $points = $this->argument('points');
    
    $this->info("Processing commission for User {$userId} with {$points} points...");
    
    try {
        $this->call('commission:distribute', [
            '--user-id' => $userId,
            '--points' => $points,
            '--force' => true
        ]);
        
        $this->info('✅ Commission distribution completed!');
    } catch (\Exception $e) {
        $this->error('❌ Error: ' . $e->getMessage());
    }
})->purpose('Quick commission distribution for specific user');

// Bulk commission processing for all eligible users  
Artisan::command('commission:bulk {points=100}', function () {
    $points = $this->argument('points');
    
    $this->info("Processing bulk commission distribution with {$points} points...");
    
    try {
        $this->call('commission:distribute', [
            '--points' => $points
        ]);
        
        $this->info('✅ Bulk commission distribution completed!');
    } catch (\Exception $e) {
        $this->error('❌ Error: ' . $e->getMessage());
    }
})->purpose('Bulk commission distribution for all eligible users');

// Commission system status check
Artisan::command('commission:status', function () {
    $this->call('commission:distribute', ['--check' => true]);
})->purpose('Check commission system status and data');

// ===========================
// RANK MANAGEMENT COMMANDS
// ===========================

// Quick rank status check for a user
Artisan::command('rank:status {username?}', function ($username = null) {
    $this->info('=== Binary Rank Status Check ===');
    
    if ($username) {
        $user = App\Models\User::where('username', $username)->first();
        if (!$user) {
            $this->error("User not found: {$username}");
            return;
        }
        $users = collect([$user]);
    } else {
        $users = App\Models\User::where('is_active', true)->limit(5)->get();
    }
    
    $binaryRankService = app(App\Services\BinaryRankService::class);
    
    foreach ($users as $user) {
        $status = $binaryRankService->getUserRankStatus($user->id);
        $this->info("User: {$user->username} ({$user->name})");
        $this->info("  Current Rank: " . ($status['current_rank']->rank_name ?? 'No Rank'));
        $this->info("  Next Rank: " . ($status['next_rank']->rank_name ?? 'Max Rank Achieved'));
        $this->info("  Progress: {$status['progress_to_next']}%");
        $this->info("  Points: Left {$status['left_points']} | Right {$status['right_points']}");
        $this->info("  Monthly Qualified: " . ($status['monthly_qualified'] ? 'Yes' : 'No'));
        
        // Check qualification periods
        $qualifications = App\Models\BinaryRankAchievement::where('user_id', $user->id)
                           ->where('qualification_period_active', true)
                           ->get();
        
        if ($qualifications->count() > 0) {
            $this->info("  Active Qualifications:");
            foreach ($qualifications as $qual) {
                $this->info("    - {$qual->rank_name}: {$qual->qualification_days_remaining} days remaining");
            }
        }
        
        $this->line('---');
    }
})->purpose('Check binary rank status for users');

// Force rank processing for a specific user
Artisan::command('rank:process-user {username}', function ($username) {
    $user = App\Models\User::where('username', $username)->first();
    if (!$user) {
        $this->error("User not found: {$username}");
        return;
    }
    
    $this->info("Processing ranks for: {$user->name} ({$username})");
    
    try {
        $this->call('binary-rank:process', ['--user-id' => $user->id]);
        $this->call('rank:process-qualifications', ['--user-id' => $user->id]);
        $this->info('✅ Rank processing completed!');
    } catch (\Exception $e) {
        $this->error('❌ Error: ' . $e->getMessage());
    }
})->purpose('Process ranks for a specific user');

// Distribute salary for eligible users (manual trigger)
Artisan::command('salary:distribute {username?}', function ($username = null) {
    $this->info('=== Manual Salary Distribution ===');
    
    if ($username) {
        $user = App\Models\User::where('username', $username)->first();
        if (!$user) {
            $this->error("User not found: {$username}");
            return;
        }
        
        $this->info("Distributing salary for: {$user->name}");
        $this->call('rank:process-qualifications', [
            '--user-id' => $user->id,
            '--distribute-salary' => true
        ]);
    } else {
        $this->info('Distributing salaries for all eligible users...');
        $this->call('rank:process-qualifications', ['--distribute-salary' => true]);
    }
    
    $this->info('✅ Salary distribution completed!');
})->purpose('Manually distribute rank salaries');

// Check salary eligibility
Artisan::command('salary:check {username?}', function ($username = null) {
    $this->info('=== Salary Eligibility Check ===');
    
    if ($username) {
        $user = App\Models\User::where('username', $username)->first();
        if (!$user) {
            $this->error("User not found: {$username}");
            return;
        }
        $users = collect([$user]);
    } else {
        $users = App\Models\User::whereHas('binaryRankAchievements', function($q) {
            $q->where('salary_eligible', true);
        })->limit(10)->get();
    }
    
    foreach ($users as $user) {
        $eligibleRanks = App\Models\BinaryRankAchievement::where('user_id', $user->id)
                          ->where('salary_eligible', true)
                          ->get();
        
        if ($eligibleRanks->count() > 0) {
            $this->info("User: {$user->username} ({$user->name})");
            $this->info("  Interest Wallet: ৳{$user->interest_wallet}");
            foreach ($eligibleRanks as $rank) {
                $status = $rank->isEligibleForSalary() ? 'Eligible' : 'Not Eligible';
                $this->info("  - {$rank->rank_name}: {$status} (৳{$rank->salary_amount})");
            }
            $this->line('---');
        }
    }
})->purpose('Check salary eligibility for users');

// Rank system health check
Artisan::command('rank:health-check', function () {
    $this->info('=== Rank System Health Check ===');
    
    // Check total achievements
    $totalAchievements = App\Models\BinaryRankAchievement::where('is_achieved', true)->count();
    $this->info("✅ Total Rank Achievements: {$totalAchievements}");
    
    // Check active qualification periods
    $activeQualifications = App\Models\BinaryRankAchievement::where('qualification_period_active', true)->count();
    $this->info("✅ Active Qualification Periods: {$activeQualifications}");
    
    // Check salary eligible users
    $salaryEligible = App\Models\BinaryRankAchievement::where('salary_eligible', true)->count();
    $this->info("✅ Salary Eligible Achievements: {$salaryEligible}");
    
    // Check recent salary distributions
    $recentSalaries = App\Models\Transaction::where('type', 'rank_salary')
                       ->where('created_at', '>=', now()->subDays(30))
                       ->count();
    $totalSalaryAmount = App\Models\Transaction::where('type', 'rank_salary')
                          ->where('created_at', '>=', now()->subDays(30))
                          ->sum('amount');
    
    $this->info("✅ Salary Distributions (30 days): {$recentSalaries} (৳{$totalSalaryAmount})");
    
    // Check qualification periods that should have ended
    $expiredQualifications = App\Models\BinaryRankAchievement::where('qualification_period_active', true)
                              ->where('salary_qualification_start_date', '<=', now()->subDays(30))
                              ->count();
    
    if ($expiredQualifications > 0) {
        $this->warn("⚠️  {$expiredQualifications} qualification periods may have expired - run qualification processing");
    }
    
    $this->info('=== Health Check Complete ===');
})->purpose('Comprehensive rank system health check');

// ===========================
// DAILY CASHBACK SYSTEM HEALTH CHECK
// ===========================

Artisan::command('cashback:health-check', function () {
    $this->info('=== Daily Cashback System Health Check ===');
    
    // Check cashback-enabled plans
    $cashbackPlans = App\Models\Plan::where('daily_cashback_enabled', true)->get();
    $this->info("📦 Active Cashback Plans: {$cashbackPlans->count()}");
    
    foreach ($cashbackPlans as $plan) {
        $this->line("  - {$plan->name} (ID: {$plan->id}) - ৳{$plan->daily_cashback_min}-{$plan->daily_cashback_max} for {$plan->cashback_duration_days} days");
        
        // Check users with this plan
        $activeUsers = App\Models\User::whereHas('activePackages', function ($query) use ($plan) {
            $query->where('plan_id', $plan->id)->where('is_active', true);
        })->count();
        $this->line("    👥 Active Users: {$activeUsers}");
        
        // Check today's cashbacks
        $todayCashbacks = App\Models\UserDailyCashback::where('plan_id', $plan->id)
                           ->whereDate('cashback_date', today())
                           ->count();
        $todayPaid = App\Models\UserDailyCashback::where('plan_id', $plan->id)
                      ->whereDate('cashback_date', today())
                      ->where('status', 'paid')
                      ->count();
        $todayPending = App\Models\UserDailyCashback::where('plan_id', $plan->id)
                         ->whereDate('cashback_date', today())
                         ->where('status', 'pending')
                         ->count();
        
        $this->line("    💰 Today's Cashbacks: {$todayCashbacks} (Paid: {$todayPaid}, Pending: {$todayPending})");
    }
    
    // Check pending cashbacks across all plans
    $totalPending = App\Models\UserDailyCashback::where('status', 'pending')->count();
    $totalPendingAmount = App\Models\UserDailyCashback::where('status', 'pending')->sum('cashback_amount');
    $this->line('');
    $this->info("⏳ Total Pending Cashbacks: {$totalPending} (৳{$totalPendingAmount})");
    
    // Check users with pending cashbacks who might now qualify
    $usersWithPending = App\Models\User::whereHas('dailyCashbacks', function ($query) {
        $query->where('status', 'pending');
    })->limit(5)->get();
    
    if ($usersWithPending->count() > 0) {
        $this->line('');
        $this->info("👥 Sample users with pending cashbacks:");
        
        foreach ($usersWithPending as $user) {
            $pendingCount = $user->dailyCashbacks()->where('status', 'pending')->count();
            $pendingAmount = $user->dailyCashbacks()->where('status', 'pending')->sum('cashback_amount');
            $this->line("  - {$user->name}: {$pendingCount} pending (৳{$pendingAmount})");
            
            // Check if they now meet conditions for any plan
            $cashbackPlans = App\Models\Plan::where('daily_cashback_enabled', true)->get();
            foreach ($cashbackPlans as $plan) {
                if ($plan->userMeetsReferralConditions($user->id)) {
                    $this->line("    ✅ Now qualifies for {$plan->name} - run cashback:release-pending");
                    break;
                }
            }
        }
    }
    
    // Check recent transactions
    $recentCashbackTransactions = App\Models\Transaction::where('type', 'daily_cashback')
                                   ->where('created_at', '>=', today())
                                   ->count();
    $recentCashbackAmount = App\Models\Transaction::where('type', 'daily_cashback')
                             ->where('created_at', '>=', today())
                             ->sum('amount');
    
    $this->line('');
    $this->info("💳 Today's Cashback Transactions: {$recentCashbackTransactions} (৳{$recentCashbackAmount})");
    
    $this->info('=== Cashback Health Check Complete ===');
})->purpose('Check daily cashback system health and statistics');
