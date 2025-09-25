<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\BinaryRankService;
use App\Models\User;
use App\Models\BinaryRankStructure;
use Carbon\Carbon;

class ProcessBinaryRanks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'binary-rank:process 
                            {--user-id= : Process specific user ID}
                            {--monthly : Process monthly qualifications and salaries}
                            {--seed : Seed rank structures}
                            {--all : Process all users}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process binary rank achievements, qualifications, and monthly salaries';

    protected $binaryRankService;

    public function __construct(BinaryRankService $binaryRankService)
    {
        parent::__construct();
        $this->binaryRankService = $binaryRankService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting Binary Rank Processing...');

        try {
            // Seed rank structures if requested
            if ($this->option('seed')) {
                $this->seedRankStructures();
            }

            // Process specific user
            if ($userId = $this->option('user-id')) {
                $this->processUser($userId);
                return;
            }

            // Process monthly qualifications and salaries
            if ($this->option('monthly')) {
                $this->processMonthlyQualifications();
                return;
            }

            // Process all users
            if ($this->option('all')) {
                $this->processAllUsers();
                return;
            }

            // Default: Show help
            $this->showHelp();

        } catch (\Exception $e) {
            $this->error("Error processing binary ranks: " . $e->getMessage());
            return 1;
        }

        return 0;
    }

    /**
     * Seed rank structures
     */
    private function seedRankStructures()
    {
        $this->info('Seeding binary rank structures...');
        
        BinaryRankStructure::seedDefaultRanks();
        
        $count = BinaryRankStructure::count();
        $this->info("Seeded {$count} rank structures successfully!");
        
        // Display rank structure
        $this->table(
            ['SL', 'Rank Name', 'Left Points', 'Right Points', 'Salary', 'Duration'],
            BinaryRankStructure::orderBy('sl_no')->get()->map(function($rank) {
                return [
                    $rank->sl_no,
                    $rank->rank_name,
                    number_format($rank->left_points),
                    number_format($rank->right_points),
                    '৳' . number_format($rank->salary),
                    $rank->duration_months . ' months'
                ];
            })->toArray()
        );
    }

    /**
     * Process specific user
     */
    private function processUser($userId)
    {
        $user = User::find($userId);
        if (!$user) {
            $this->error("User not found: {$userId}");
            return;
        }

        $this->info("Processing binary ranks for user: {$user->name} (ID: {$userId})");

        // Get current status
        $beforeStatus = $this->binaryRankService->getUserRankStatus($userId);
        
        // Process achievements
        $achievements = $this->binaryRankService->processUserRankAchievements($userId);
        
        // Get updated status
        $afterStatus = $this->binaryRankService->getUserRankStatus($userId);

        // Display results
        $this->displayUserStatus($beforeStatus, $afterStatus, $achievements);
    }

    /**
     * Process all users
     */
    private function processAllUsers()
    {
        $this->info('Processing binary ranks for all users...');
        
        $progressBar = $this->output->createProgressBar();
        $results = $this->binaryRankService->bulkProcessAllUsers();
        $progressBar->finish();
        
        $this->info("\nBulk processing completed:");
        $this->info("✓ Processed: {$results['processed']} users");
        if ($results['errors'] > 0) {
            $this->warn("⚠ Errors: {$results['errors']} users");
        }
        $this->info("📊 Total: {$results['total']} users");
    }

    /**
     * Process monthly qualifications
     */
    private function processMonthlyQualifications()
    {
        $month = Carbon::now()->format('Y-m-01');
        $this->info("Processing monthly qualifications for: " . Carbon::parse($month)->format('F Y'));

        // Process qualifications
        \App\Models\MonthlyRankQualification::processMonthlyQualifications($month);
        
        // Process salary payments
        $salaryResults = $this->binaryRankService->processMonthlyRankSalaries($month);

        $this->info("Monthly processing completed:");
        $this->info("✓ Total Salary Paid: ৳" . number_format($salaryResults['total_paid']));
        $this->info("✓ Users Paid: {$salaryResults['users_paid']}");

        // Show qualification summary
        $this->showMonthlyQualificationSummary($month);
    }

    /**
     * Display user status
     */
    private function displayUserStatus($beforeStatus, $afterStatus, $achievements)
    {
        $this->info("\n📊 User Binary Rank Status:");
        
        $this->table(
            ['Metric', 'Value'],
            [
                ['Left Points', number_format($afterStatus['left_points'])],
                ['Right Points', number_format($afterStatus['right_points'])],
                ['Current Rank', $afterStatus['current_rank']->rank_name ?? 'None'],
                ['Progress to Next', number_format($afterStatus['progress_to_next'], 1) . '%'],
                ['Monthly Qualified', $afterStatus['monthly_qualified'] ? 'Yes' : 'No'],
                ['Consecutive Months', $afterStatus['consecutive_months']]
            ]
        );

        if (!empty($achievements)) {
            $this->info("\n🎉 New Achievements:");
            foreach ($achievements as $achievement) {
                $this->info("✓ Achieved {$achievement['rank']->rank_name} rank!");
                $this->info("  💰 Bonus: ৳" . number_format($achievement['bonus']['total_value']));
                $this->info("  🎁 Rewards: " . json_encode($achievement['rewards']));
            }
        }
    }

    /**
     * Show monthly qualification summary
     */
    private function showMonthlyQualificationSummary($month)
    {
        $this->info("\n📈 Monthly Qualification Summary:");
        
        $stats = $this->binaryRankService->getRankStatistics();
        
        $this->table(
            ['Rank', 'Total Achieved', 'Monthly Qualified'],
            collect($stats)->map(function($stat) {
                return [
                    $stat['rank']->rank_name,
                    $stat['achieved_count'],
                    $stat['monthly_qualified']
                ];
            })->toArray()
        );
    }

    /**
     * Show command help
     */
    private function showHelp()
    {
        $this->info('Binary Rank Processing Commands:');
        $this->info('');
        $this->info('🔧 Setup:');
        $this->info('  php artisan binary-rank:process --seed');
        $this->info('     Seed the rank structures into database');
        $this->info('');
        $this->info('👤 User Processing:');
        $this->info('  php artisan binary-rank:process --user-id=123');
        $this->info('     Process ranks for specific user');
        $this->info('');
        $this->info('  php artisan binary-rank:process --all');
        $this->info('     Process ranks for all users');
        $this->info('');
        $this->info('📅 Monthly Processing:');
        $this->info('  php artisan binary-rank:process --monthly');
        $this->info('     Process monthly qualifications and pay salaries');
        $this->info('');
        $this->info('💡 Usage Examples:');
        $this->info('  # Initialize system with rank data');
        $this->info('  php artisan binary-rank:process --seed');
        $this->info('');
        $this->info('  # Process specific user');
        $this->info('  php artisan binary-rank:process --user-id=1');
        $this->info('');
        $this->info('  # Monthly salary run (schedule this monthly)');
        $this->info('  php artisan binary-rank:process --monthly');
    }
}