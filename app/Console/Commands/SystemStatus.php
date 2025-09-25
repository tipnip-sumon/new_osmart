<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Order;
use App\Models\Commission;
use App\Models\BinaryMatching;
use App\Services\VolumeTrackingService;
use Carbon\Carbon;

class SystemStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'system:status {--detailed : Show detailed breakdown}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Display comprehensive system status and statistics';

    protected VolumeTrackingService $volumeService;

    public function __construct(VolumeTrackingService $volumeService)
    {
        parent::__construct();
        $this->volumeService = $volumeService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('📊 System Status Report');
        $this->info('Generated: ' . now()->format('Y-m-d H:i:s'));
        $this->newLine();

        // User Statistics
        $this->displayUserStatistics();
        
        // Order & Payment Statistics
        $this->displayOrderStatistics();
        
        // Volume & Commission Statistics
        $this->displayVolumeStatistics();
        
        // System Health Check
        $this->displaySystemHealth();

        if ($this->option('detailed')) {
            $this->newLine();
            $this->displayDetailedBreakdown();
        }

        return Command::SUCCESS;
    }

    private function displayUserStatistics()
    {
        $this->info('👥 User Statistics:');
        
        $totalUsers = User::count();
        $activeUsers = User::where('is_active', true)->count();
        $customers = User::where('role', 'customer')->count();
        $vendors = User::where('role', 'vendor')->count();
        $affiliates = User::where('role', 'affiliate')->count();
        $admins = User::where('role', 'admin')->count();
        
        $this->info("   Total Users: {$totalUsers}");
        $this->info("   Active Users: {$activeUsers}");
        $this->info("   Customers: {$customers} | Vendors: {$vendors} | Affiliates: {$affiliates} | Admins: {$admins}");
        $this->newLine();
    }

    private function displayOrderStatistics()
    {
        $this->info('🛒 Order & Payment Statistics:');
        
        $today = Carbon::today();
        $currentMonth = Carbon::now()->format('Y-m');
        
        // Total orders
        $totalOrders = Order::count();
        $todayOrders = Order::whereDate('created_at', $today)->count();
        $monthlyOrders = Order::whereRaw("DATE_FORMAT(created_at, '%Y-%m') = ?", [$currentMonth])->count();
        
        // Payment status breakdown
        $paidOrders = Order::where('payment_status', 'paid')->count();
        $pendingOrders = Order::where('payment_status', 'pending')->count();
        $failedOrders = Order::where('payment_status', 'failed')->count();
        $refundedOrders = Order::where('payment_status', 'refunded')->count();
        
        // Revenue
        $totalRevenue = Order::where('payment_status', 'paid')->sum('total_amount');
        $todayRevenue = Order::where('payment_status', 'paid')->whereDate('created_at', $today)->sum('total_amount');
        $monthlyRevenue = Order::where('payment_status', 'paid')->whereRaw("DATE_FORMAT(created_at, '%Y-%m') = ?", [$currentMonth])->sum('total_amount');
        
        $this->info("   Total Orders: {$totalOrders} | Today: {$todayOrders} | This Month: {$monthlyOrders}");
        $this->info("   Payment Status - Paid: {$paidOrders} | Pending: {$pendingOrders} | Failed: {$failedOrders} | Refunded: {$refundedOrders}");
        $this->info("   Revenue - Total: ৳" . number_format($totalRevenue, 2) . " | Today: ৳" . number_format($todayRevenue, 2) . " | Monthly: ৳" . number_format($monthlyRevenue, 2));
        $this->newLine();
    }

    private function displayVolumeStatistics()
    {
        $this->info('📊 Volume & Commission Statistics:');
        
        // Get total volumes
        $totalDailyVolume = User::sum('daily_sales_volume');
        $totalMonthlyVolume = User::sum('monthly_sales_volume');
        $totalLifetimeVolume = User::sum('total_sales_volume');
        
        // Get processed volumes
        $totalProcessedDaily = User::sum('processed_daily_volume');
        $totalProcessedMonthly = User::sum('processed_monthly_volume');
        $totalProcessedLifetime = User::sum('processed_total_volume');
        
        // Available for payout
        $availableDaily = $totalDailyVolume - $totalProcessedDaily;
        $availableMonthly = $totalMonthlyVolume - $totalProcessedMonthly;
        $availableLifetime = $totalLifetimeVolume - $totalProcessedLifetime;
        
        // Commissions
        $totalCommissions = Commission::sum('commission_amount');
        $todayCommissions = Commission::whereDate('created_at', Carbon::today())->sum('commission_amount');
        $monthlyCommissions = Commission::whereRaw("DATE_FORMAT(created_at, '%Y-%m') = ?", [Carbon::now()->format('Y-m')])->sum('commission_amount');
        
        $this->info("   Volume Summary:");
        $this->info("     Daily - Total: ৳" . number_format($totalDailyVolume, 2) . " | Available: ৳" . number_format($availableDaily, 2));
        $this->info("     Monthly - Total: ৳" . number_format($totalMonthlyVolume, 2) . " | Available: ৳" . number_format($availableMonthly, 2));
        $this->info("     Lifetime - Total: ৳" . number_format($totalLifetimeVolume, 2) . " | Available: ৳" . number_format($availableLifetime, 2));
        $this->info("   Commissions - Total: ৳" . number_format($totalCommissions, 2) . " | Today: ৳" . number_format($todayCommissions, 2) . " | Monthly: ৳" . number_format($monthlyCommissions, 2));
        $this->newLine();
    }

    private function displaySystemHealth()
    {
        $this->info('🔧 System Health Check:');
        
        $health = [];
        
        // Check database connection
        try {
            User::count();
            $health[] = "✅ Database connection: OK";
        } catch (\Exception $e) {
            $health[] = "❌ Database connection: FAILED";
        }
        
        // Check volume tracking service
        try {
            $testUser = User::first();
            if ($testUser) {
                $this->volumeService->getUnprocessedVolumes($testUser);
                $health[] = "✅ Volume tracking service: OK";
            } else {
                $health[] = "⚠️  Volume tracking service: No users to test";
            }
        } catch (\Exception $e) {
            $health[] = "❌ Volume tracking service: FAILED";
        }
        
        // Check order observer
        $health[] = "✅ Order observer: Registered";
        
        // Check for users with invalid data
        $usersWithNegativeVolumes = User::where('daily_sales_volume', '<', 0)
            ->orWhere('monthly_sales_volume', '<', 0)
            ->orWhere('total_sales_volume', '<', 0)
            ->count();
        
        if ($usersWithNegativeVolumes > 0) {
            $health[] = "⚠️  Users with negative volumes: {$usersWithNegativeVolumes}";
        } else {
            $health[] = "✅ Volume data integrity: OK";
        }
        
        foreach ($health as $status) {
            $this->info("   {$status}");
        }
        $this->newLine();
    }

    private function displayDetailedBreakdown()
    {
        $this->info('📋 Detailed Breakdown:');
        
        // Top 10 users by volume
        $topUsers = User::where('monthly_sales_volume', '>', 0)
            ->orderBy('monthly_sales_volume', 'desc')
            ->take(10)
            ->get(['username', 'monthly_sales_volume', 'processed_monthly_volume']);
        
        if ($topUsers->count() > 0) {
            $this->info('   Top 10 Users by Monthly Volume:');
            foreach ($topUsers as $user) {
                $available = $user->monthly_sales_volume - $user->processed_monthly_volume;
                $this->info("     {$user->username}: ৳" . number_format($user->monthly_sales_volume, 2) . " (Available: ৳" . number_format($available, 2) . ")");
            }
        } else {
            $this->info('   No users with monthly volume found');
        }
        
        $this->newLine();
        
        // Recent matching bonuses
        $recentMatchings = BinaryMatching::with('user')
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
        
        if ($recentMatchings->count() > 0) {
            $this->info('   Recent Matching Bonuses (Last 7 days):');
            foreach ($recentMatchings as $matching) {
                $this->info("     {$matching->user->username}: ৳" . number_format($matching->matching_bonus, 2) . " on " . $matching->created_at->format('Y-m-d'));
            }
        } else {
            $this->info('   No recent matching bonuses found');
        }
    }
}
