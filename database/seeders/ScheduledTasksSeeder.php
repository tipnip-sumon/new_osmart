<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ScheduledTasksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();
        
        $tasks = [
            [
                'name' => 'Database Backup',
                'description' => 'Automatic daily database backup',
                'command' => 'backup:database',
                'schedule' => 'Daily at 02:00 AM',
                'cron_expression' => '0 2 * * *',
                'last_run' => $now->copy()->subDay()->setTime(2, 0),
                'next_run' => $now->copy()->addDay()->setTime(2, 0),
                'status' => 'active',
                'run_count' => rand(50, 200),
                'failure_count' => rand(0, 5),
                'timeout' => 1800, // 30 minutes
                'prevent_overlapping' => true,
                'metadata' => json_encode([
                    'backup_type' => 'full',
                    'retention_days' => 30,
                    'compression' => true
                ])
            ],
            [
                'name' => 'Log Cleanup',
                'description' => 'Weekly cleanup of old log files',
                'command' => 'logs:cleanup',
                'schedule' => 'Weekly on Sunday at 03:00 AM',
                'cron_expression' => '0 3 * * 0',
                'last_run' => $now->copy()->previous(Carbon::SUNDAY)->setTime(3, 0),
                'next_run' => $now->copy()->next(Carbon::SUNDAY)->setTime(3, 0),
                'status' => 'active',
                'run_count' => rand(20, 50),
                'failure_count' => rand(0, 2),
                'timeout' => 600, // 10 minutes
                'prevent_overlapping' => true,
                'metadata' => json_encode([
                    'retention_days' => 30,
                    'file_types' => ['log', 'tmp']
                ])
            ],
            [
                'name' => 'Cache Optimization',
                'description' => 'Clear and rebuild application cache',
                'command' => 'cache:optimize',
                'schedule' => 'Every 6 hours',
                'cron_expression' => '0 */6 * * *',
                'last_run' => $now->copy()->subHours(rand(1, 6)),
                'next_run' => $now->copy()->addHours(rand(1, 6)),
                'status' => rand(0, 1) ? 'active' : 'paused',
                'run_count' => rand(100, 500),
                'failure_count' => rand(0, 10),
                'timeout' => 300, // 5 minutes
                'prevent_overlapping' => true,
                'metadata' => json_encode([
                    'cache_types' => ['application', 'route', 'config', 'view'],
                    'rebuild_after_clear' => true
                ])
            ],
            [
                'name' => 'System Health Check',
                'description' => 'Monitor system components and performance',
                'command' => 'system:health-check',
                'schedule' => 'Every 15 minutes',
                'cron_expression' => '*/15 * * * *',
                'last_run' => $now->copy()->subMinutes(rand(1, 15)),
                'next_run' => $now->copy()->addMinutes(rand(5, 15)),
                'status' => 'active',
                'run_count' => rand(1000, 5000),
                'failure_count' => rand(0, 20),
                'timeout' => 60, // 1 minute
                'prevent_overlapping' => false,
                'metadata' => json_encode([
                    'checks' => ['database', 'cache', 'storage', 'queue'],
                    'alert_on_failure' => true,
                    'notification_email' => 'admin@example.com'
                ])
            ],
            [
                'name' => 'Queue Worker Monitor',
                'description' => 'Monitor and restart queue workers if needed',
                'command' => 'queue:monitor',
                'schedule' => 'Every 5 minutes',
                'cron_expression' => '*/5 * * * *',
                'last_run' => $now->copy()->subMinutes(rand(1, 5)),
                'next_run' => $now->copy()->addMinutes(rand(1, 5)),
                'status' => 'active',
                'run_count' => rand(2000, 10000),
                'failure_count' => rand(0, 30),
                'timeout' => 30,
                'prevent_overlapping' => true,
                'metadata' => json_encode([
                    'max_jobs_per_worker' => 1000,
                    'memory_limit' => '512M',
                    'restart_on_failure' => true
                ])
            ],
            [
                'name' => 'Database Optimization',
                'description' => 'Optimize database tables for better performance',
                'command' => 'database:optimize',
                'schedule' => 'Weekly on Saturday at 01:00 AM',
                'cron_expression' => '0 1 * * 6',
                'last_run' => $now->copy()->previous(Carbon::SATURDAY)->setTime(1, 0),
                'next_run' => $now->copy()->next(Carbon::SATURDAY)->setTime(1, 0),
                'status' => 'active',
                'run_count' => rand(10, 30),
                'failure_count' => rand(0, 3),
                'timeout' => 3600, // 1 hour
                'prevent_overlapping' => true,
                'metadata' => json_encode([
                    'tables_to_optimize' => 'all',
                    'analyze_tables' => true,
                    'repair_if_needed' => true
                ])
            ]
        ];
        
        foreach ($tasks as $task) {
            $task['created_at'] = $now;
            $task['updated_at'] = $now;
            DB::table('scheduled_tasks')->insert($task);
        }
    }
}
