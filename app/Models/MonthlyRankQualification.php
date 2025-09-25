<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class MonthlyRankQualification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'rank_id',
        'qualification_month',
        'left_points',
        'right_points',
        'matched_points',
        'matching_bonus',
        'qualified',
        'salary_paid',
        'salary_amount',
        'salary_paid_at',
        'is_processed',
        'processed_at'
    ];

    protected $casts = [
        'qualification_month' => 'date',
        'left_points' => 'decimal:2',
        'right_points' => 'decimal:2',
        'matched_points' => 'decimal:2',
        'matching_bonus' => 'decimal:2',
        'salary_amount' => 'decimal:2',
        'qualified' => 'boolean',
        'salary_paid' => 'boolean',
        'is_processed' => 'boolean',
        'salary_paid_at' => 'datetime',
        'processed_at' => 'datetime'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function rank()
    {
        return $this->belongsTo(BinaryRankStructure::class, 'rank_id');
    }

    public function rankAchievement()
    {
        return $this->belongsTo(BinaryRankAchievement::class, 'rank_id', 'id');
    }

    // Scopes
    public function scopeQualified($query)
    {
        return $query->where('qualified', true);
    }

    public function scopeSalaryPaid($query)
    {
        return $query->where('salary_paid', true);
    }

    public function scopeSalaryPending($query)
    {
        return $query->where('qualified', true)->where('salary_paid', false);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeForMonth($query, $month)
    {
        return $query->whereMonth('qualification_month', Carbon::parse($month)->month)
                    ->whereYear('qualification_month', Carbon::parse($month)->year);
    }

    public function scopeCurrentMonth($query)
    {
        return $query->whereMonth('qualification_month', Carbon::now()->month)
                    ->whereYear('qualification_month', Carbon::now()->year);
    }

    public function scopePending($query)
    {
        return $query->where('is_processed', false);
    }

    public function scopeProcessed($query)
    {
        return $query->where('is_processed', true);
    }

    // Methods
    public function processQualification($leftPoints, $rightPoints)
    {
        if (!$this->rank) {
            return false;
        }

        $this->left_points = $leftPoints;
        $this->right_points = $rightPoints;
        $this->matched_points = min($leftPoints, $rightPoints);
        
        // Check qualification
        $this->qualified = $this->rank->isQualifiedForMonthlySalary($leftPoints, $rightPoints);
        
        if ($this->qualified) {
            // Calculate matching bonus
            $this->matching_bonus = $this->rank->calculateMatchingBonus($leftPoints, $rightPoints);
            
            // Set salary amount if qualified
            $this->salary_amount = $this->rank->salary;
        }
        
        $this->is_processed = true;
        $this->processed_at = now();
        $this->save();
        
        return $this->qualified;
    }

    public function paySalary()
    {
        if (!$this->qualified || $this->salary_paid) {
            return false;
        }

        // Here you would integrate with your wallet/payment system
        // For now, just mark as paid
        $this->salary_paid = true;
        $this->salary_paid_at = now();
        $this->save();

        // Update user's wallet or create transaction record
        $this->createSalaryTransaction();

        return true;
    }

    private function createSalaryTransaction()
    {
        // Create a transaction record for the salary payment
        // This should integrate with your existing transaction system
        
        // Example:
        // Transaction::create([
        //     'user_id' => $this->user_id,
        //     'type' => 'rank_salary',
        //     'amount' => $this->salary_amount,
        //     'description' => "Monthly rank salary for {$this->rank->rank_name}",
        //     'reference_id' => $this->id,
        //     'reference_type' => static::class,
        //     'status' => 'completed'
        // ]);

        \Illuminate\Support\Facades\Log::info("Salary paid: ৳{$this->salary_amount} to User ID {$this->user_id} for rank {$this->rank->rank_name}");
    }

    public function getStatusBadgeAttribute()
    {
        if ($this->qualified && $this->salary_paid) {
            return '<span class="badge bg-success">Paid</span>';
        } elseif ($this->qualified && !$this->salary_paid) {
            return '<span class="badge bg-warning">Qualified - Pending Payment</span>';
        } else {
            return '<span class="badge bg-danger">Not Qualified</span>';
        }
    }

    public function getFormattedMonthAttribute()
    {
        return $this->qualification_month->format('F Y');
    }

    // Static methods for bulk processing
    public static function processMonthlyQualifications($month = null)
    {
        $month = $month ?? Carbon::now()->format('Y-m-01');
        
        // Get all users with binary summary data
        $users = User::whereHas('binarySummary')->get();
        
        foreach ($users as $user) {
            static::processUserMonthlyQualification($user->id, $month);
        }
    }

    public static function processUserMonthlyQualification($userId, $month = null)
    {
        $month = $month ?? Carbon::now()->format('Y-m-01');
        
        // Get user's current binary points
        $binarySummary = \App\Models\BinarySummary::where('user_id', $userId)->latest()->first();
        
        if (!$binarySummary) {
            return false;
        }
        
        $leftPoints = $binarySummary->lifetime_left_volume ?? 0;
        $rightPoints = $binarySummary->lifetime_right_volume ?? 0;
        
        // Get user's achieved ranks
        $achievedRanks = BinaryRankAchievement::forUser($userId)->achieved()->get();
        
        foreach ($achievedRanks as $achievement) {
            $qualification = static::firstOrCreate([
                'user_id' => $userId,
                'rank_id' => $achievement->rankStructure->id,
                'qualification_month' => $month
            ]);
            
            $qualification->processQualification($leftPoints, $rightPoints);
        }
        
        return true;
    }

    public static function payPendingSalaries()
    {
        $pendingQualifications = static::salaryPending()->get();
        
        foreach ($pendingQualifications as $qualification) {
            $qualification->paySalary();
        }
        
        return $pendingQualifications->count();
    }
}