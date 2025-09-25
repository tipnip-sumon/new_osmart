<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'rating',
        'title',
        'comment',
        'is_verified_purchase',
        'is_approved',
        'helpful_count',
        'images'
    ];

    protected $casts = [
        'images' => 'array',
        'is_verified_purchase' => 'boolean',
        'is_approved' => 'boolean',
        'helpful_count' => 'integer',
        'rating' => 'integer'
    ];

    // Relationships
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    public function scopeByRating($query, $rating)
    {
        return $query->where('rating', $rating);
    }

    // Accessors
    public function getStarsArrayAttribute()
    {
        $stars = [];
        for ($i = 1; $i <= 5; $i++) {
            $stars[] = $i <= $this->rating ? 'filled' : 'empty';
        }
        return $stars;
    }

    public function getFormattedDateAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    // Static methods
    public static function averageRating($productId)
    {
        return static::where('product_id', $productId)
            ->approved()
            ->avg('rating') ?? 0;
    }

    public static function totalCount($productId)
    {
        return static::where('product_id', $productId)
            ->approved()
            ->count();
    }

    public static function ratingBreakdown($productId)
    {
        $breakdown = [];
        $total = static::totalCount($productId);
        
        for ($i = 5; $i >= 1; $i--) {
            $count = static::where('product_id', $productId)
                ->approved()
                ->where('rating', $i)
                ->count();
            
            $breakdown[$i] = [
                'count' => $count,
                'percentage' => $total > 0 ? round(($count / $total) * 100) : 0
            ];
        }
        
        return $breakdown;
    }
};
