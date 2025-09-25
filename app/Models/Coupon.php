<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Coupon extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'code',
        'name',
        'description',
        'type',
        'value',
        'minimum_amount',
        'maximum_discount',
        'usage_limit',
        'usage_limit_per_user',
        'used_count',
        'start_date',
        'end_date',
        'is_active',
        'vendor_id',
        'created_by',
        'applicable_products',
        'applicable_categories',
        'exclude_products',
        'exclude_categories',
        'user_restrictions',
        'country_restrictions',
        'first_order_only',
        'free_shipping',
        'stackable',
        'auto_apply',
        'priority',
        'terms_conditions'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean',
        'first_order_only' => 'boolean',
        'free_shipping' => 'boolean',
        'stackable' => 'boolean',
        'auto_apply' => 'boolean',
        'value' => 'decimal:2',
        'minimum_amount' => 'decimal:2',
        'maximum_discount' => 'decimal:2',
        'usage_limit' => 'integer',
        'usage_limit_per_user' => 'integer',
        'used_count' => 'integer',
        'priority' => 'integer',
        'applicable_products' => 'array',
        'applicable_categories' => 'array',
        'exclude_products' => 'array',
        'exclude_categories' => 'array',
        'user_restrictions' => 'array',
        'country_restrictions' => 'array'
    ];

    // Coupon types
    const TYPES = [
        'percentage' => 'Percentage',
        'fixed' => 'Fixed Amount',
        'free_shipping' => 'Free Shipping',
        'buy_x_get_y' => 'Buy X Get Y',
        'bulk_discount' => 'Bulk Discount'
    ];

    // Coupon status
    const STATUS = [
        'active' => 'Active',
        'inactive' => 'Inactive',
        'expired' => 'Expired',
        'used_up' => 'Used Up',
        'scheduled' => 'Scheduled'
    ];

    /**
     * Relationships
     */
    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_coupons')
                    ->withPivot('discount_amount', 'applied_at')
                    ->withTimestamps();
    }

    public function usages()
    {
        return $this->hasMany(CouponUsage::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'coupon_products');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'coupon_categories');
    }

    /**
     * Accessors
     */
    public function getTypeNameAttribute()
    {
        return self::TYPES[$this->type] ?? $this->type;
    }

    public function getStatusAttribute()
    {
        if (!$this->is_active) {
            return 'inactive';
        }

        if ($this->start_date && $this->start_date->isFuture()) {
            return 'scheduled';
        }

        if ($this->end_date && $this->end_date->isPast()) {
            return 'expired';
        }

        if ($this->usage_limit && $this->used_count >= $this->usage_limit) {
            return 'used_up';
        }

        return 'active';
    }

    public function getStatusNameAttribute()
    {
        return self::STATUS[$this->status] ?? $this->status;
    }

    public function getStatusSlugAttribute()
    {
        return $this->status;
    }

    public function getDiscountTextAttribute()
    {
        switch ($this->type) {
            case 'percentage':
                return $this->value . '% OFF';
            case 'fixed':
                return '$' . number_format($this->value, 2) . ' OFF';
            case 'free_shipping':
                return 'FREE SHIPPING';
            case 'buy_x_get_y':
                return 'BUY X GET Y';
            case 'bulk_discount':
                return 'BULK DISCOUNT';
            default:
                return 'DISCOUNT';
        }
    }

    public function getRemainingUsesAttribute()
    {
        if (!$this->usage_limit) {
            return null; // Unlimited
        }

        return max(0, $this->usage_limit - $this->used_count);
    }

    public function getUsagePercentageAttribute()
    {
        if (!$this->usage_limit) {
            return 0;
        }

        return round(($this->used_count / $this->usage_limit) * 100, 2);
    }

    public function getIsExpiredAttribute()
    {
        return $this->end_date && $this->end_date->isPast();
    }

    public function getIsScheduledAttribute()
    {
        return $this->start_date && $this->start_date->isFuture();
    }

    public function getIsValidAttribute()
    {
        return $this->status === 'active';
    }

    public function getDaysRemainingAttribute()
    {
        if (!$this->end_date) {
            return null;
        }

        return max(0, now()->diffInDays($this->end_date, false));
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                    ->where(function($q) {
                        $q->whereNull('start_date')->orWhere('start_date', '<=', now());
                    })
                    ->where(function($q) {
                        $q->whereNull('end_date')->orWhere('end_date', '>=', now());
                    });
    }

    public function scopeExpired($query)
    {
        return $query->where('end_date', '<', now());
    }

    public function scopeScheduled($query)
    {
        return $query->where('start_date', '>', now());
    }

    public function scopeByVendor($query, $vendorId)
    {
        return $query->where('vendor_id', $vendorId);
    }

    public function scopeGlobal($query)
    {
        return $query->whereNull('vendor_id');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByCode($query, $code)
    {
        return $query->where('code', strtoupper($code));
    }

    public function scopeAutoApply($query)
    {
        return $query->where('auto_apply', true);
    }

    public function scopeStackable($query)
    {
        return $query->where('stackable', true);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where(function($q) use ($userId) {
            $q->whereNull('user_restrictions')
              ->orWhereJsonContains('user_restrictions', $userId);
        });
    }

    public function scopeForCountry($query, $country)
    {
        return $query->where(function($q) use ($country) {
            $q->whereNull('country_restrictions')
              ->orWhereJsonContains('country_restrictions', $country);
        });
    }

    public function scopeNotUsedUp($query)
    {
        return $query->where(function($q) {
            $q->whereNull('usage_limit')
              ->orWhereRaw('used_count < usage_limit');
        });
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('code', 'LIKE', "%{$search}%")
              ->orWhere('name', 'LIKE', "%{$search}%")
              ->orWhere('description', 'LIKE', "%{$search}%");
        });
    }

    /**
     * Methods
     */
    public function isValidForUser($userId)
    {
        // Check if coupon is valid
        if (!$this->is_valid) {
            return false;
        }

        // Check user restrictions
        if ($this->user_restrictions && !in_array($userId, $this->user_restrictions)) {
            return false;
        }

        // Check usage limit per user
        if ($this->usage_limit_per_user) {
            $userUsageCount = $this->usages()->where('user_id', $userId)->count();
            if ($userUsageCount >= $this->usage_limit_per_user) {
                return false;
            }
        }

        // Check if first order only
        if ($this->first_order_only) {
            $userOrdersCount = Order::where('user_id', $userId)->count();
            if ($userOrdersCount > 0) {
                return false;
            }
        }

        return true;
    }

    public function isValidForOrder($order)
    {
        // Check minimum amount
        if ($this->minimum_amount && $order->subtotal < $this->minimum_amount) {
            return false;
        }

        // Check vendor restrictions
        if ($this->vendor_id && $order->vendor_id !== $this->vendor_id) {
            return false;
        }

        // Check country restrictions
        if ($this->country_restrictions && !in_array($order->country, $this->country_restrictions)) {
            return false;
        }

        // Check product restrictions
        if ($this->applicable_products) {
            $orderProductIds = $order->items->pluck('product_id')->toArray();
            $hasApplicableProduct = array_intersect($orderProductIds, $this->applicable_products);
            if (empty($hasApplicableProduct)) {
                return false;
            }
        }

        // Check excluded products
        if ($this->exclude_products) {
            $orderProductIds = $order->items->pluck('product_id')->toArray();
            $hasExcludedProduct = array_intersect($orderProductIds, $this->exclude_products);
            if (!empty($hasExcludedProduct)) {
                return false;
            }
        }

        return true;
    }

    public function calculateDiscount($order)
    {
        $discount = 0;

        switch ($this->type) {
            case 'percentage':
                $discount = ($order->subtotal * $this->value) / 100;
                break;

            case 'fixed':
                $discount = $this->value;
                break;

            case 'free_shipping':
                $discount = $order->shipping_cost ?? 0;
                break;

            case 'buy_x_get_y':
                $discount = $this->calculateBuyXGetYDiscount($order);
                break;

            case 'bulk_discount':
                $discount = $this->calculateBulkDiscount($order);
                break;
        }

        // Apply maximum discount limit
        if ($this->maximum_discount && $discount > $this->maximum_discount) {
            $discount = $this->maximum_discount;
        }

        // Ensure discount doesn't exceed order total
        return min($discount, $order->subtotal);
    }

    private function calculateBuyXGetYDiscount($order)
    {
        // Implementation for Buy X Get Y logic
        // This would need specific business rules
        return 0;
    }

    private function calculateBulkDiscount($order)
    {
        // Implementation for bulk discount logic
        // This would need specific business rules
        return 0;
    }

    public function use($userId, $orderId = null, $discountAmount = 0)
    {
        // Increment usage count
        $this->increment('used_count');

        // Create usage record
        $this->usages()->create([
            'user_id' => $userId,
            'order_id' => $orderId,
            'discount_amount' => $discountAmount,
            'used_at' => now()
        ]);

        return $this;
    }

    public function canBeUsed()
    {
        return $this->is_valid && $this->remaining_uses !== 0;
    }

    public function activate()
    {
        $this->is_active = true;
        $this->save();
        return $this;
    }

    public function deactivate()
    {
        $this->is_active = false;
        $this->save();
        return $this;
    }

    public function extend($days)
    {
        if ($this->end_date) {
            $this->end_date = $this->end_date->addDays($days);
        } else {
            $this->end_date = now()->addDays($days);
        }
        $this->save();
        return $this;
    }

    public function increaseUsageLimit($amount)
    {
        if ($this->usage_limit) {
            $this->usage_limit += $amount;
        } else {
            $this->usage_limit = $amount;
        }
        $this->save();
        return $this;
    }

    public function duplicate($newCode = null)
    {
        $newCoupon = $this->replicate();
        $newCoupon->code = $newCode ?: $this->generateUniqueCode();
        $newCoupon->used_count = 0;
        $newCoupon->created_at = now();
        $newCoupon->updated_at = now();
        $newCoupon->save();

        return $newCoupon;
    }

    public static function generateUniqueCode($length = 8)
    {
        do {
            $code = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, $length));
        } while (self::where('code', $code)->exists());

        return $code;
    }

    public static function findByCode($code)
    {
        return self::where('code', strtoupper($code))->first();
    }

    public static function getAutoApplyCoupons($order, $userId = null)
    {
        $query = self::active()->autoApply();

        if ($userId) {
            $query->forUser($userId);
        }

        if (isset($order->country)) {
            $query->forCountry($order->country);
        }

        return $query->orderBy('priority', 'desc')->get()->filter(function($coupon) use ($order) {
            return $coupon->isValidForOrder($order);
        });
    }

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($coupon) {
            // Generate unique code if not provided
            if (!$coupon->code) {
                $coupon->code = self::generateUniqueCode();
            } else {
                $coupon->code = strtoupper($coupon->code);
            }

            // Set default values
            $coupon->used_count = $coupon->used_count ?? 0;
            $coupon->is_active = $coupon->is_active ?? true;
            $coupon->priority = $coupon->priority ?? 1;
            $coupon->stackable = $coupon->stackable ?? false;
            $coupon->auto_apply = $coupon->auto_apply ?? false;
            $coupon->first_order_only = $coupon->first_order_only ?? false;
            $coupon->free_shipping = $coupon->free_shipping ?? false;
        });

        static::updating(function ($coupon) {
            if ($coupon->isDirty('code')) {
                $coupon->code = strtoupper($coupon->code);
            }
        });
    }
}
