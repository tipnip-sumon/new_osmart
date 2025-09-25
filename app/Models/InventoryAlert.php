<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryAlert extends Model
{
    use HasFactory;

    protected $fillable = [
        'inventory_id',
        'type',
        'priority',
        'message',
        'data',
        'is_resolved',
        'resolved_by',
        'resolved_at',
        'notified_at'
    ];

    protected $casts = [
        'data' => 'array',
        'is_resolved' => 'boolean',
        'resolved_at' => 'datetime',
        'notified_at' => 'datetime'
    ];

    // Alert types
    const TYPES = [
        'low_stock' => 'Low Stock',
        'out_of_stock' => 'Out of Stock',
        'overstock' => 'Overstock',
        'expiring_soon' => 'Expiring Soon',
        'expired' => 'Expired',
        'price_change' => 'Price Change',
        'movement_anomaly' => 'Movement Anomaly'
    ];

    // Priority levels
    const PRIORITIES = [
        'low' => 'Low',
        'medium' => 'Medium',
        'high' => 'High',
        'critical' => 'Critical'
    ];

    // Relationships
    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }

    public function resolvedBy()
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    // Accessors
    public function getTypeNameAttribute()
    {
        return self::TYPES[$this->type] ?? $this->type;
    }

    public function getPriorityNameAttribute()
    {
        return self::PRIORITIES[$this->priority] ?? $this->priority;
    }

    public function getPriorityColorAttribute()
    {
        $colors = [
            'low' => 'success',
            'medium' => 'warning',
            'high' => 'danger',
            'critical' => 'dark'
        ];

        return $colors[$this->priority] ?? 'secondary';
    }

    public function getTypeIconAttribute()
    {
        $icons = [
            'low_stock' => 'ti-alert-triangle',
            'out_of_stock' => 'ti-x-circle',
            'overstock' => 'ti-trending-up',
            'expiring_soon' => 'ti-clock',
            'expired' => 'ti-ban',
            'price_change' => 'ti-currency-dollar',
            'movement_anomaly' => 'ti-activity'
        ];

        return $icons[$this->type] ?? 'ti-bell';
    }

    // Scopes
    public function scopeUnresolved($query)
    {
        return $query->where('is_resolved', false);
    }

    public function scopeResolved($query)
    {
        return $query->where('is_resolved', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeCritical($query)
    {
        return $query->where('priority', 'critical');
    }

    public function scopeHigh($query)
    {
        return $query->where('priority', 'high');
    }

    public function scopeRecentlyCreated($query, $hours = 24)
    {
        return $query->where('created_at', '>=', now()->subHours($hours));
    }

    // Methods
    public function resolve($userId = null, $notes = null)
    {
        $this->is_resolved = true;
        $this->resolved_by = $userId;
        $this->resolved_at = now();
        
        if ($notes) {
            $data = $this->data ?? [];
            $data['resolution_notes'] = $notes;
            $this->data = $data;
        }
        
        $this->save();

        return $this;
    }

    public function markAsNotified()
    {
        $this->notified_at = now();
        $this->save();

        return $this;
    }

    public function shouldNotify()
    {
        // Don't notify if already notified within last 24 hours
        if ($this->notified_at && $this->notified_at->diffInHours(now()) < 24) {
            return false;
        }

        // Don't notify if already resolved
        if ($this->is_resolved) {
            return false;
        }

        return true;
    }

    // Boot method
    protected static function boot()
    {
        parent::boot();

        static::created(function ($alert) {
            // Auto-notify for critical alerts
            if ($alert->priority === 'critical') {
                // Trigger notification logic here
                event(new \App\Events\CriticalInventoryAlert($alert));
            }
        });
    }
}
