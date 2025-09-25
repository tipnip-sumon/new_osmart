<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailyPointDistribution extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'distribution_date',
        'points_acquired',
        'sponsor_bonus',
        'generation_bonus',
        'generation_details',
        'acquisition_type',
        'purchase_amount',
        'source',
        'is_processed',
        'processed_at',
        'processing_notes'
    ];

    protected $casts = [
        'distribution_date' => 'date',
        'points_acquired' => 'decimal:2',
        'sponsor_bonus' => 'decimal:2',
        'generation_bonus' => 'decimal:2',
        'purchase_amount' => 'decimal:2',
        'generation_details' => 'array',
        'is_processed' => 'boolean',
        'processed_at' => 'datetime'
    ];

    /**
     * Get the user that owns the distribution
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope for today's distributions
     */
    public function scopeToday($query)
    {
        return $query->whereDate('distribution_date', today());
    }

    /**
     * Scope for pending distributions
     */
    public function scopePending($query)
    {
        return $query->where('is_processed', false);
    }

    /**
     * Scope for processed distributions
     */
    public function scopeProcessed($query)
    {
        return $query->where('is_processed', true);
    }

    /**
     * Check if user already has distribution today
     */
    public static function hasDistributionToday($userId)
    {
        return self::where('user_id', $userId)
            ->whereDate('distribution_date', today())
            ->exists();
    }

    /**
     * Get total generation bonus breakdown
     */
    public function getGenerationBreakdown()
    {
        $details = $this->generation_details ?? [];
        $breakdown = [];
        
        foreach ($details as $level => $data) {
            $breakdown[] = [
                'level' => $level,
                'recipient_id' => $data['user_id'] ?? null,
                'recipient_name' => $data['user_name'] ?? 'Unknown',
                'percentage' => $data['percentage'] ?? 0,
                'bonus_amount' => $data['bonus_amount'] ?? 0
            ];
        }
        
        return $breakdown;
    }

    /**
     * Mark distribution as processed
     */
    public function markAsProcessed($notes = null)
    {
        $this->update([
            'is_processed' => true,
            'processed_at' => now(),
            'processing_notes' => $notes
        ]);
    }
}
