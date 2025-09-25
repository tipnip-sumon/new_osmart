<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'country',
        'postal_code',
        'tax_id',
        'payment_terms',
        'credit_limit',
        'current_balance',
        'contact_person',
        'contact_email',
        'contact_phone',
        'website',
        'notes',
        'is_active',
        'rating'
    ];

    protected $casts = [
        'credit_limit' => 'decimal:2',
        'current_balance' => 'decimal:2',
        'is_active' => 'boolean',
        'rating' => 'decimal:1'
    ];

    // Relationships
    public function inventoryMovements()
    {
        return $this->hasMany(InventoryMovement::class);
    }

    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'supplier_products')
                    ->withPivot('supplier_sku', 'cost_price', 'lead_time')
                    ->withTimestamps();
    }

    public function payments()
    {
        return $this->hasMany(SupplierPayment::class);
    }

    // Accessors
    public function getFullAddressAttribute()
    {
        $parts = array_filter([
            $this->address,
            $this->city,
            $this->state,
            $this->postal_code,
            $this->country
        ]);

        return implode(', ', $parts);
    }

    public function getAvailableCreditAttribute()
    {
        return $this->credit_limit - $this->current_balance;
    }

    public function getCreditUtilizationAttribute()
    {
        if ($this->credit_limit <= 0) {
            return 0;
        }

        return ($this->current_balance / $this->credit_limit) * 100;
    }

    public function getRatingStarsAttribute()
    {
        $rating = $this->rating ?? 0;
        $stars = '';
        
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $rating) {
                $stars .= '★';
            } elseif ($i - 0.5 <= $rating) {
                $stars .= '☆';
            } else {
                $stars .= '☆';
            }
        }

        return $stars;
    }

    public function getStatusColorAttribute()
    {
        return $this->is_active ? 'success' : 'danger';
    }

    public function getTotalOrdersAttribute()
    {
        return $this->purchaseOrders()->count();
    }

    public function getTotalOrderValueAttribute()
    {
        return $this->purchaseOrders()->sum('total_amount');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    public function scopeByCountry($query, $country)
    {
        return $query->where('country', $country);
    }

    public function scopeByState($query, $state)
    {
        return $query->where('state', $state);
    }

    public function scopeWithCredit($query)
    {
        return $query->where('credit_limit', '>', 0);
    }

    public function scopeOverCreditLimit($query)
    {
        return $query->whereColumn('current_balance', '>', 'credit_limit');
    }

    public function scopeHighRated($query, $rating = 4.0)
    {
        return $query->where('rating', '>=', $rating);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'LIKE', "%{$search}%")
              ->orWhere('email', 'LIKE', "%{$search}%")
              ->orWhere('contact_person', 'LIKE', "%{$search}%")
              ->orWhere('tax_id', 'LIKE', "%{$search}%");
        });
    }

    // Methods
    public function addBalance($amount, $description = null)
    {
        $this->current_balance += $amount;
        $this->save();

        // Record payment
        $this->payments()->create([
            'amount' => $amount,
            'type' => 'invoice',
            'description' => $description ?? 'Balance adjustment',
            'date' => now()
        ]);

        return $this;
    }

    public function reduceBalance($amount, $description = null)
    {
        $this->current_balance -= $amount;
        $this->current_balance = max(0, $this->current_balance);
        $this->save();

        // Record payment
        $this->payments()->create([
            'amount' => -$amount,
            'type' => 'payment',
            'description' => $description ?? 'Payment received',
            'date' => now()
        ]);

        return $this;
    }

    public function canOrder($amount = 0)
    {
        if (!$this->is_active) {
            return false;
        }

        if ($this->credit_limit > 0) {
            return ($this->current_balance + $amount) <= $this->credit_limit;
        }

        return true;
    }

    public function updateRating()
    {
        // Calculate rating based on delivery performance, quality, etc.
        $orders = $this->purchaseOrders()->completed()->count();
        $onTimeDeliveries = $this->purchaseOrders()->onTime()->count();
        
        if ($orders > 0) {
            $onTimePercentage = ($onTimeDeliveries / $orders) * 100;
            
            if ($onTimePercentage >= 95) {
                $this->rating = 5.0;
            } elseif ($onTimePercentage >= 85) {
                $this->rating = 4.0;
            } elseif ($onTimePercentage >= 75) {
                $this->rating = 3.0;
            } elseif ($onTimePercentage >= 60) {
                $this->rating = 2.0;
            } else {
                $this->rating = 1.0;
            }
            
            $this->save();
        }

        return $this;
    }

    public function addProduct($productId, $supplierSku = null, $costPrice = null, $leadTime = null)
    {
        $this->products()->attach($productId, [
            'supplier_sku' => $supplierSku,
            'cost_price' => $costPrice,
            'lead_time' => $leadTime,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return $this;
    }

    public function removeProduct($productId)
    {
        $this->products()->detach($productId);

        return $this;
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

    public function getProductCost($productId)
    {
        $pivot = $this->products()->wherePivot('product_id', $productId)->first();
        
        return $pivot ? $pivot->pivot->cost_price : null;
    }

    public function getProductLeadTime($productId)
    {
        $pivot = $this->products()->wherePivot('product_id', $productId)->first();
        
        return $pivot ? $pivot->pivot->lead_time : null;
    }

    // Boot method
    protected static function boot()
    {
        parent::boot();

        static::created(function ($supplier) {
            // Initialize balance and rating
            $supplier->current_balance = 0;
            $supplier->rating = 3.0; // Default rating
            $supplier->save();
        });
    }
}
