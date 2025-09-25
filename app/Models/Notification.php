<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'category',
        'title',
        'message',
        'data',
        'icon',
        'color',
        'action_url',
        'action_text',
        'is_read',
        'is_important',
        'read_at',
        'expires_at',
        'reference_type',
        'reference_id'
    ];

    protected $casts = [
        'data' => 'array',
        'is_read' => 'boolean',
        'is_important' => 'boolean',
        'read_at' => 'datetime',
        'expires_at' => 'datetime'
    ];

    // Notification types
    const TYPE_RANK_ACHIEVEMENT = 'rank_achievement';
    const TYPE_SALARY_PAYMENT = 'salary_payment';
    const TYPE_COMMISSION = 'commission';
    const TYPE_MATCHING_BONUS = 'matching_bonus';
    const TYPE_KYC_STATUS = 'kyc_status';
    const TYPE_SYSTEM_ALERT = 'system_alert';
    const TYPE_PROMOTION = 'promotion';
    const TYPE_WITHDRAWAL = 'withdrawal';
    const TYPE_DEPOSIT = 'deposit';
    const TYPE_REFERRAL = 'referral';

    // Categories
    const CATEGORY_SUCCESS = 'success';
    const CATEGORY_WARNING = 'warning';
    const CATEGORY_INFO = 'info';
    const CATEGORY_DANGER = 'danger';

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    public function scopeImportant($query)
    {
        return $query->where('is_important', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', Carbon::now()->subDays($days));
    }

    public function scopeNotExpired($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>', now());
        });
    }

    // Methods
    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => now()
        ]);
    }

    public function markAsUnread()
    {
        $this->update([
            'is_read' => false,
            'read_at' => null
        ]);
    }

    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    public function getFormattedDateAttribute()
    {
        return $this->created_at->format('M d, Y h:i A');
    }

    public function getCategoryIconAttribute()
    {
        $icons = [
            'success' => 'fe-check-circle',
            'warning' => 'fe-alert-triangle',
            'info' => 'fe-info',
            'danger' => 'fe-alert-circle'
        ];

        return $icons[$this->category] ?? 'fe-bell';
    }

    public function getCategoryColorAttribute()
    {
        $colors = [
            'success' => 'success',
            'warning' => 'warning',
            'info' => 'info',
            'danger' => 'danger'
        ];

        return $colors[$this->category] ?? 'primary';
    }

    // Static methods for creating notifications
    public static function createForUser($userId, $type, $title, $message, $options = [])
    {
        return static::create(array_merge([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'category' => static::CATEGORY_INFO,
            'icon' => 'fe-bell',
            'color' => 'primary'
        ], $options));
    }

    public static function createRankAchievement($userId, $rankName, $options = [])
    {
        return static::createForUser($userId, static::TYPE_RANK_ACHIEVEMENT, 
            "🎉 New Rank Achieved!", 
            "Congratulations! You've achieved the {$rankName} rank. Your 30-day qualification period has started.",
            array_merge([
                'category' => static::CATEGORY_SUCCESS,
                'icon' => 'fe-award',
                'color' => 'success',
                'is_important' => true,
                'action_url' => route('member.matching.rank-salary-report'),
                'action_text' => 'View Details'
            ], $options)
        );
    }

    public static function createSalaryPayment($userId, $amount, $rankName, $options = [])
    {
        return static::createForUser($userId, static::TYPE_SALARY_PAYMENT,
            "💰 Salary Distributed!",
            "Your monthly salary of ৳" . number_format($amount) . " for {$rankName} rank has been added to your Interest Wallet.",
            array_merge([
                'category' => static::CATEGORY_SUCCESS,
                'icon' => 'fe-dollar-sign',
                'color' => 'success',
                'is_important' => true,
                'action_url' => route('member.wallet.index'),
                'action_text' => 'View Wallet'
            ], $options)
        );
    }

    public static function createQualificationReminder($userId, $rankName, $daysRemaining, $options = [])
    {
        $category = $daysRemaining <= 7 ? static::CATEGORY_WARNING : static::CATEGORY_INFO;
        
        return static::createForUser($userId, static::TYPE_RANK_ACHIEVEMENT,
            "⏰ Qualification Period Reminder",
            "Your {$rankName} rank qualification period ends in {$daysRemaining} days. Maintain your monthly conditions to become salary eligible.",
            array_merge([
                'category' => $category,
                'icon' => 'fe-clock',
                'color' => $daysRemaining <= 7 ? 'warning' : 'info',
                'action_url' => route('member.matching.rank-salary-report'),
                'action_text' => 'Check Status'
            ], $options)
        );
    }

    public static function createKycNotification($userId, $status, $options = [])
    {
        $messages = [
            'approved' => 'Your KYC verification has been approved! You can now access all features.',
            'rejected' => 'Your KYC verification has been rejected. Please resubmit with correct documents.',
            'pending' => 'Your KYC verification is under review. We\'ll notify you once it\'s processed.'
        ];

        $categories = [
            'approved' => static::CATEGORY_SUCCESS,
            'rejected' => static::CATEGORY_DANGER,
            'pending' => static::CATEGORY_INFO
        ];

        return static::createForUser($userId, static::TYPE_KYC_STATUS,
            "📋 KYC Verification Update",
            $messages[$status] ?? "Your KYC status has been updated.",
            array_merge([
                'category' => $categories[$status] ?? static::CATEGORY_INFO,
                'icon' => 'fe-file-text',
                'color' => $categories[$status] ?? 'info',
                'is_important' => $status === 'rejected',
                'action_url' => route('member.kyc.index'),
                'action_text' => 'View KYC'
            ], $options)
        );
    }

    // Cleanup expired notifications
    public static function cleanupExpired()
    {
        return static::where('expires_at', '<=', now())->delete();
    }

    // Get notification statistics for a user
    public static function getStatsForUser($userId)
    {
        return [
            'total' => static::where('user_id', $userId)->count(),
            'unread' => static::where('user_id', $userId)->unread()->count(),
            'important' => static::where('user_id', $userId)->important()->unread()->count(),
            'recent' => static::where('user_id', $userId)->recent(7)->count()
        ];
    }
}
