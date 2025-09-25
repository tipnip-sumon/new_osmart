<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class AffiliateLinkShare extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_slug',
        'shared_url',
        'shared_platform',
        'share_date',
        'clicks_count',
        'unique_clicks_count',
        'earnings_amount',
        'is_active'
    ];

    protected $casts = [
        'share_date' => 'date',
        'clicks_count' => 'integer',
        'unique_clicks_count' => 'integer',
        'earnings_amount' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Relationship with User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship with Product
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_slug', 'slug');
    }

    /**
     * Relationship with affiliate clicks
     */
    public function affiliateClicks()
    {
        return $this->hasMany(AffiliateClick::class, 'shared_url', 'shared_url');
    }

    /**
     * Get user's shares for today
     */
    public static function getTodayShares($userId)
    {
        return self::where('user_id', $userId)
            ->whereDate('share_date', Carbon::today())
            ->get();
    }

    /**
     * Get user's daily stats
     */
    public static function getDailyStats($userId, $date = null)
    {
        $date = $date ?: Carbon::today();
        
        return self::where('user_id', $userId)
            ->whereDate('share_date', $date)
            ->selectRaw('
                COUNT(*) as total_shares,
                SUM(clicks_count) as total_clicks,
                SUM(unique_clicks_count) as unique_clicks,
                SUM(earnings_amount) as total_earnings
            ')
            ->first();
    }

    /**
     * Check if user can share more links today
     */
    public static function canUserShareToday($userId, $packageSettings)
    {
        if (!$packageSettings) {
            return false;
        }

        $todayShares = self::where('user_id', $userId)
            ->whereDate('share_date', Carbon::today())
            ->count();

        return $todayShares < $packageSettings->daily_share_limit;
    }
}
