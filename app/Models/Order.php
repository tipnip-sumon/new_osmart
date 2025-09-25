<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_number',
        'customer_id',
        'vendor_id',
        'status',
        'payment_status',
        'shipping_status',
        'total_amount',
        'tax_amount',
        'shipping_amount',
        'discount_amount',
        'subtotal',
        'currency',
        'payment_method',
        'shipping_method',
        'shipping_address',
        'billing_address',
        'payment_details',
        'sender_number',
        'receiver_number',
        'transaction_id',
        'payment_proof',
        'payment_proof_data',
        'payment_notes',
        'payment_verified_at',
        'payment_verified_by',
        'notes',
        'shipped_at',
        'delivered_at',
        'cancelled_at',
        'refunded_at',
        'processing_at',
        'tracking_number',
        'cancellation_reason',
        'created_by'
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'shipping_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'shipping_address' => 'array',
        'billing_address' => 'array',
        'payment_details' => 'array',
        'payment_proof_data' => 'array',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'refunded_at' => 'datetime',
        'processing_at' => 'datetime',
        'payment_verified_at' => 'datetime'
    ];

    // Order statuses
    const STATUSES = [
        'pending' => 'Pending',
        'confirmed' => 'Confirmed',
        'processing' => 'Processing',
        'shipped' => 'Shipped',
        'delivered' => 'Delivered',
        'cancelled' => 'Cancelled',
        'refunded' => 'Refunded'
    ];

    // Payment statuses
    const PAYMENT_STATUSES = [
        'pending' => 'Pending',
        'paid' => 'Paid',
        'failed' => 'Failed',
        'refunded' => 'Refunded',
        'partially_refunded' => 'Partially Refunded'
    ];

    // Shipping statuses
    const SHIPPING_STATUSES = [
        'not_shipped' => 'Not Shipped',
        'shipped' => 'Shipped',
        'in_transit' => 'In Transit',
        'delivered' => 'Delivered',
        'failed_delivery' => 'Failed Delivery',
        'returned' => 'Returned'
    ];

    // Relationships
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function inventoryMovements()
    {
        return $this->hasMany(InventoryMovement::class, 'reference_id')
                    ->where('reference_type', 'order');
    }

    public function payments()
    {
        return $this->hasMany(PaymentTransaction::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method', 'code');
    }

    public function paymentVerifier()
    {
        return $this->belongsTo(User::class, 'payment_verified_by');
    }

    // TODO: Create Shipment model and table
    // public function shipments()
    // {
    //     return $this->hasMany(Shipment::class);
    // }

    // Accessors
    public function getStatusNameAttribute()
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function getPaymentStatusNameAttribute()
    {
        return self::PAYMENT_STATUSES[$this->payment_status] ?? $this->payment_status;
    }

    public function getShippingStatusNameAttribute()
    {
        return self::SHIPPING_STATUSES[$this->shipping_status] ?? $this->shipping_status;
    }

    public function getStatusColorAttribute()
    {
        $colors = [
            'pending' => 'warning',
            'confirmed' => 'info',
            'processing' => 'primary',
            'shipped' => 'success',
            'delivered' => 'success',
            'cancelled' => 'danger',
            'refunded' => 'secondary'
        ];

        return $colors[$this->status] ?? 'secondary';
    }

    public function getTotalItemsAttribute()
    {
        return $this->items->sum('quantity');
    }

    public function getFormattedTotalAttribute()
    {
        return number_format($this->total_amount, 2);
    }

    public function getCanCancelAttribute()
    {
        return in_array($this->status, ['pending', 'confirmed']) && 
               in_array($this->payment_status, ['pending', 'failed']);
    }

    public function getCanRefundAttribute()
    {
        return $this->payment_status === 'paid' && 
               in_array($this->status, ['delivered', 'cancelled']);
    }

    // Scopes
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByPaymentStatus($query, $status)
    {
        return $query->where('payment_status', $status);
    }

    public function scopeByVendor($query, $vendorId)
    {
        return $query->where('vendor_id', $vendorId);
    }

    public function scopeByCustomer($query, $customerId)
    {
        return $query->where('customer_id', $customerId);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'delivered');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    public function scopeRecentOrders($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    // Methods
    public function generateOrderNumber()
    {
        $prefix = 'ORD';
        $date = now()->format('Ymd');
        $suffix = str_pad($this->id, 6, '0', STR_PAD_LEFT);
        
        $this->order_number = $prefix . '-' . $date . '-' . $suffix;
        $this->save();

        return $this;
    }

    public function updateStatus($status, $notes = null)
    {
        $oldStatus = $this->status;
        $this->status = $status;
        
        if ($notes) {
            $this->notes = $this->notes ? $this->notes . "\n" . $notes : $notes;
        }

        // Set timestamps based on status
        switch ($status) {
            case 'shipped':
                $this->shipped_at = now();
                $this->shipping_status = 'shipped';
                break;
            case 'delivered':
                $this->delivered_at = now();
                $this->shipping_status = 'delivered';
                break;
            case 'cancelled':
                $this->cancelled_at = now();
                break;
            case 'refunded':
                $this->refunded_at = now();
                $this->payment_status = 'refunded';
                break;
        }

        $this->save();

        // Create status history
        $this->statusHistory()->create([
            'old_status' => $oldStatus,
            'new_status' => $status,
            'notes' => $notes,
            'changed_by' => \Illuminate\Support\Facades\Auth::id()
        ]);

        return $this;
    }

    public function cancel($reason = null)
    {
        if (!$this->can_cancel) {
            throw new \Exception('Order cannot be cancelled');
        }

        // Restore inventory
        foreach ($this->items as $item) {
            $item->product->updateStock($item->quantity, 'add');
        }

        return $this->updateStatus('cancelled', $reason);
    }

    public function refund($amount = null)
    {
        if (!$this->can_refund) {
            throw new \Exception('Order cannot be refunded');
        }

        $refundAmount = $amount ?? $this->total_amount;
        
        // Process refund logic here
        $this->payments()->create([
            'type' => 'refund',
            'amount' => -$refundAmount,
            'status' => 'completed',
            'processed_at' => now()
        ]);

        if ($refundAmount >= $this->total_amount) {
            $this->payment_status = 'refunded';
            $this->updateStatus('refunded', "Full refund of {$refundAmount}");
        } else {
            $this->payment_status = 'partially_refunded';
            $this->notes = $this->notes ? $this->notes . "\n" . "Partial refund of {$refundAmount}" : "Partial refund of {$refundAmount}";
            $this->save();
        }

        return $this;
    }

    public function calculateTotals()
    {
        $this->subtotal = $this->items->sum(function ($item) {
            return $item->quantity * $item->price;
        });

        $this->total_amount = $this->subtotal + $this->tax_amount + $this->shipping_amount - $this->discount_amount;
        $this->save();

        return $this;
    }

    public function createInventoryMovements()
    {
        // Check if warehouses exist, if not skip inventory movements
        if (!\App\Models\Warehouse::exists()) {
            Log::warning("No warehouses found, skipping inventory movements for order {$this->order_number}");
            return $this;
        }

        $defaultWarehouse = \App\Models\Warehouse::first();
        
        foreach ($this->items as $item) {
            try {
                InventoryMovement::create([
                    'product_id' => $item->product_id,
                    'warehouse_id' => $defaultWarehouse->id,
                    'type' => 'stock_out',
                    'quantity' => $item->quantity,
                    'remaining_quantity' => 0, // Will be calculated by the system
                    'reference_type' => 'order',
                    'reference_id' => $this->id,
                    'reference_number' => $this->order_number,
                    'notes' => "Order fulfillment for order {$this->order_number}"
                ]);
            } catch (\Exception $e) {
                Log::error("Failed to create inventory movement for order {$this->order_number}: " . $e->getMessage());
                // Continue with other items even if one fails
            }
        }

        return $this;
    }

    // Boot method
    protected static function boot()
    {
        parent::boot();

        static::created(function ($order) {
            // Generate order number
            $order->generateOrderNumber();
        });

        static::updated(function ($order) {
            // Create inventory movements when order is confirmed
            if ($order->isDirty('status') && $order->status === 'confirmed') {
                $order->createInventoryMovements();
            }

            // Trigger real-time binary volume updates when order status changes
            if ($order->isDirty('status') && $order->customer_id) {
                $oldStatus = $order->getOriginal('status');
                \App\Services\RealTimeBinaryService::handleOrderStatusChange($order, $oldStatus);
            }
        });

        static::created(function ($order) {
            // Trigger binary update when new order is created
            if ($order->customer_id) {
                \App\Services\RealTimeBinaryService::handleOrderStatusChange($order);
            }
        });
    }
}
