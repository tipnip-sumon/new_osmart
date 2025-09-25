<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Auth\Notifications\VerifyEmail;

class TestEmailVerification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:email-verification {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test email verification functionality';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        
        // Find user by email
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error("User with email {$email} not found.");
            return 1;
        }
        
        $this->info("Testing email verification for user: {$user->name} ({$user->email})");
        
        // Test mail configuration
        $this->info("Mail Configuration:");
        $this->info("Mailer: " . config('mail.default'));
        $this->info("Host: " . config('mail.mailers.smtp.host'));
        $this->info("Port: " . config('mail.mailers.smtp.port'));
        $this->info("From: " . config('mail.from.address'));
        
        try {
            // Send email verification
            $this->info("Attempting to send email verification...");
            
            if (method_exists($user, 'sendEmailVerificationNotification')) {
                $user->sendEmailVerificationNotification();
                $this->info("✅ Email verification sent successfully!");
                Log::info("Test command: Email verification sent successfully", [
                    'user_id' => $user->id,
                    'email' => $user->email
                ]);
            } else {
                $this->warn("⚠️  User model does not have sendEmailVerificationNotification method");
                Log::warning("Test command: User model missing sendEmailVerificationNotification method");
            }
            
        } catch (\Exception $e) {
            $this->error("❌ Failed to send email verification: " . $e->getMessage());
            Log::error("Test command: Failed to send email verification", [
                'user_id' => $user->id,
                'email' => $user->email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return 1;
        }
        
        return 0;
    }
}
