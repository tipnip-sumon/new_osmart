<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'billing_period',
        'product_limit',
        'storage_limit',
        'transaction_limit',
        'commission_rate',
        'features',
        'is_popular',
        'is_active',
        'trial_days',
        'sort_order'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'commission_rate' => 'decimal:4',
        'features' => 'array',
        'is_popular' => 'boolean',
        'is_active' => 'boolean'
    ];

    // Billing periods
    const BILLING_PERIODS = [
        'monthly' => 'Monthly',
        'yearly' => 'Yearly',
        'lifetime' => 'Lifetime'
    ];

    // Relationships
    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    // Accessors
    public function getBillingPeriodNameAttribute()
    {
        return self::BILLING_PERIODS[$this->billing_period] ?? $this->billing_period;
    }

    public function getFormattedPriceAttribute()
    {
        return number_format($this->price, 2);
    }

    public function getPricePerMonthAttribute()
    {
        return match($this->billing_period) {
            'monthly' => $this->price,
            'yearly' => $this->price / 12,
            'lifetime' => 0,
            default => $this->price
        };
    }

    public function getCommissionRatePercentAttribute()
    {
        return ($this->commission_rate * 100) . '%';
    }

    public function getProductLimitTextAttribute()
    {
        return $this->product_limit === 0 ? 'Unlimited' : number_format($this->product_limit);
    }

    public function getStorageLimitTextAttribute()
    {
        if ($this->storage_limit === 0) {
            return 'Unlimited';
        }
        
        if ($this->storage_limit >= 1024) {
            return number_format($this->storage_limit / 1024, 1) . ' GB';
        }
        
        return $this->storage_limit . ' MB';
    }

    public function getTransactionLimitTextAttribute()
    {
        return $this->transaction_limit === 0 ? 'Unlimited' : number_format($this->transaction_limit);
    }

    public function getSavingsAttribute()
    {
        if ($this->billing_period !== 'yearly') {
            return 0;
        }

        $monthlyEquivalent = $this->price / 12;
        $assumedMonthlyPrice = $this->price * 12 / 10; // Assume 20% discount for yearly
        
        return max(0, $assumedMonthlyPrice - $this->price);
    }

    public function getSavingsPercentAttribute()
    {
        if ($this->billing_period !== 'yearly') {
            return 0;
        }

        return 20; // Fixed 20% savings for yearly plans
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopePopular($query)
    {
        return $query->where('is_popular', true);
    }

    public function scopeByBillingPeriod($query, $period)
    {
        return $query->where('billing_period', $period);
    }

    public function scopeMonthly($query)
    {
        return $query->where('billing_period', 'monthly');
    }

    public function scopeYearly($query)
    {
        return $query->where('billing_period', 'yearly');
    }

    public function scopeLifetime($query)
    {
        return $query->where('billing_period', 'lifetime');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('price');
    }

    // Methods
    public function hasFeature($feature)
    {
        return in_array($feature, $this->features ?? []);
    }

    public function getFeatureList()
    {
        $allFeatures = [
            'unlimited_products' => 'Unlimited Products',
            'unlimited_storage' => 'Unlimited Storage',
            'unlimited_transactions' => 'Unlimited Transactions',
            'advanced_analytics' => 'Advanced Analytics',
            'priority_support' => 'Priority Support',
            'custom_branding' => 'Custom Branding',
            'api_access' => 'API Access',
            'multi_warehouse' => 'Multi-Warehouse Support',
            'bulk_operations' => 'Bulk Operations',
            'advanced_reporting' => 'Advanced Reporting',
            'email_marketing' => 'Email Marketing Tools',
            'seo_tools' => 'SEO Tools',
            'social_media_integration' => 'Social Media Integration',
            'abandoned_cart_recovery' => 'Abandoned Cart Recovery',
            'loyalty_program' => 'Loyalty Program',
            'affiliate_management' => 'Affiliate Management',
            'multi_currency' => 'Multi-Currency Support',
            'multi_language' => 'Multi-Language Support',
            'ssl_certificate' => 'Free SSL Certificate',
            'cdn_hosting' => 'CDN Hosting'
        ];

        $planFeatures = [];
        foreach ($this->features ?? [] as $feature) {
            if (isset($allFeatures[$feature])) {
                $planFeatures[$feature] = $allFeatures[$feature];
            }
        }

        return $planFeatures;
    }

    public function canUserSubscribe(User $user)
    {
        // Check if user already has an active subscription
        if ($user->subscription_plan_id && $user->subscription_expires_at > now()) {
            return false;
        }

        return true;
    }

    public function calculateNextBillingDate($startDate = null)
    {
        $startDate = $startDate ? carbon($startDate) : now();

        return match($this->billing_period) {
            'monthly' => $startDate->addMonth(),
            'yearly' => $startDate->addYear(),
            'lifetime' => null,
            default => $startDate->addMonth()
        };
    }

    public function createSubscription(User $user, $paymentMethod = null)
    {
        $expiresAt = $this->calculateNextBillingDate();
        
        // Update user subscription
        $user->subscription_plan_id = $this->id;
        $user->subscription_expires_at = $expiresAt;
        $user->commission_rate = $this->commission_rate;
        $user->save();

        // Create subscription record
        $subscription = Subscription::create([
            'user_id' => $user->id,
            'subscription_plan_id' => $this->id,
            'amount' => $this->price,
            'billing_period' => $this->billing_period,
            'starts_at' => now(),
            'expires_at' => $expiresAt,
            'payment_method' => $paymentMethod,
            'status' => 'active'
        ]);

        // Create transaction
        Transaction::create([
            'user_id' => $user->id,
            'type' => 'subscription',
            'amount' => -$this->price,
            'status' => 'completed',
            'payment_method' => $paymentMethod,
            'description' => "Subscription to {$this->name} plan",
            'reference_type' => 'subscription',
            'reference_id' => $subscription->id,
            'processed_at' => now()
        ]);

        return $subscription;
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

    public function makePopular()
    {
        // Remove popular flag from other plans
        self::where('is_popular', true)->update(['is_popular' => false]);
        
        $this->is_popular = true;
        $this->save();

        return $this;
    }

    // Boot method
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($plan) {
            // Generate slug if not provided
            if (!$plan->slug) {
                $plan->slug = str_slug($plan->name);
            }

            // Set default sort order
            if (!$plan->sort_order) {
                $plan->sort_order = self::max('sort_order') + 1;
            }
        });
    }
}
