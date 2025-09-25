<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GenerationIncome extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'from_user_id', 
        'generation_level',
        'points',
        'amount',
        'business_volume',
        'status',
        'payment_reason',
        'paid_at',
        'remarks',
        'meta_data'
    ];

    protected $casts = [
        'points' => 'decimal:2',
        'amount' => 'decimal:2',
        'business_volume' => 'decimal:2',
        'paid_at' => 'datetime',
        'meta_data' => 'array'
    ];

    /**
     * User who will receive the generation income
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * User who generated the business (downline member)
     */
    public function fromUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    /**
     * Check if the income is pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if the income is paid
     */
    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    /**
     * Check if the income is invalid (free account)
     */
    public function isInvalid(): bool
    {
        return $this->status === 'invalid';
    }

    /**
     * Mark income as paid and update interest wallet
     */
    public function markAsPaid($reason = null): bool
    {
        $this->update([
            'status' => 'paid',
            'payment_reason' => $reason,
            'paid_at' => now(),
            'remarks' => $reason ? "Paid due to: {$reason}" : 'Generation income paid'
        ]);

        // Add to user's interest wallet
        $user = $this->user;
        $user->increment('interest_wallet', $this->amount);

        return true;
    }

    /**
     * Mark income as invalid (for free accounts)
     */
    public function markAsInvalid($reason = 'Free account - generation income not applicable'): bool
    {
        $this->update([
            'status' => 'invalid',
            'remarks' => $reason
        ]);

        return true;
    }

    /**
     * Get generation income rates for levels 1-20
     */
    public static function getGenerationRates(): array
    {
        return [
            1 => 2.0,    // Level 1: 2 points = 12 TK
            2 => 2.0,    // Level 2: 2 points = 12 TK
            3 => 1.0,    // Level 3: 1 point = 6 TK
            4 => 1.0,    // Level 4: 1 point = 6 TK
            5 => 1.0,    // Level 5: 1 point = 6 TK
            6 => 1.0,    // Level 6: 1 point = 6 TK
            7 => 0.5,    // Level 7: 0.5 points = 3 TK
            8 => 0.5,    // Level 8: 0.5 points = 3 TK
            9 => 0.5,    // Level 9: 0.5 points = 3 TK
            10 => 0.5,   // Level 10: 0.5 points = 3 TK
            11 => 0.5,   // Level 11: 0.5 points = 3 TK
            12 => 0.5,   // Level 12: 0.5 points = 3 TK
            13 => 0.5,   // Level 13: 0.5 points = 3 TK
            14 => 0.5,   // Level 14: 0.5 points = 3 TK
            15 => 0.5,   // Level 15: 0.5 points = 3 TK
            16 => 0.5,   // Level 16: 0.5 points = 3 TK
            17 => 0.5,   // Level 17: 0.5 points = 3 TK
            18 => 0.5,   // Level 18: 0.5 points = 3 TK
            19 => 0.5,   // Level 19: 0.5 points = 3 TK
            20 => 0.5,   // Level 20: 0.5 points = 3 TK
        ];
    }

    /**
     * Get points for a specific level
     */
    public static function getPointsForLevel($level): float
    {
        $rates = self::getGenerationRates();
        return $rates[$level] ?? 0;
    }

    /**
     * Calculate TK amount from points (1 point = 6 TK)
     */
    public static function calculateAmount($points): float
    {
        return $points * 6; // 1 point = 6 TK
    }
}
