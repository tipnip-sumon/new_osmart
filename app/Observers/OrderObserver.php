<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\User;
use App\Services\VolumeTrackingService;
use App\Services\PointService;
use Illuminate\Support\Facades\Log;

class OrderObserver
{
    protected VolumeTrackingService $volumeService;
    protected PointService $pointService;

    public function __construct()
    {
        $this->initializeServices();
        Log::info('OrderObserver constructed successfully');
    }

    /**
     * Initialize services with fallback mechanism
     */
    private function initializeServices(): void
    {
        try {
            // Try dependency injection first
            $this->volumeService = app(VolumeTrackingService::class);
            $this->pointService = app(PointService::class);
        } catch (\Exception $e) {
            // Fallback to direct instantiation
            $this->volumeService = new VolumeTrackingService();
            $this->pointService = new PointService();
            Log::warning('OrderObserver using fallback service instantiation', [
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Handle the Order "created" event.
     * Update volumes and allocate points when a new order is created with paid status
     */
    public function created(Order $order): void
    {
        // Add debug logging
        Log::info('OrderObserver::created called', [
            'order_id' => $order->id,
            'payment_status' => $order->payment_status,
            'customer_id' => $order->customer_id
        ]);

        if ($order->payment_status === 'paid' && $order->customer_id) {
            $user = User::find($order->customer_id);
            if ($user) {
                try {
                    $this->volumeService->updateUserVolumesOnPurchase($user, $order->total_amount);
                    Log::info("Volume updated for user {$user->id} on order creation: +{$order->total_amount}");

                    // Allocate points for paid orders
                    $this->allocatePointsForOrder($order, $user);
                } catch (\Exception $e) {
                    Log::error('Error in OrderObserver::created', [
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                }
            }
        }
    }

    /**
     * Handle the Order "updated" event.
     * Track when payment status changes to 'paid'
     */
    public function updated(Order $order): void
    {
        // Add debug logging
        Log::info('OrderObserver::updated called', [
            'order_id' => $order->id,
            'payment_status' => $order->payment_status,
            'is_dirty_payment_status' => $order->isDirty('payment_status'),
            'customer_id' => $order->customer_id
        ]);

        // Check if payment status changed to 'paid'
        if ($order->isDirty('payment_status') && $order->payment_status === 'paid' && $order->customer_id) {
            $user = User::find($order->customer_id);
            if ($user) {
                try {
                    $this->volumeService->updateUserVolumesOnPurchase($user, $order->total_amount);
                    Log::info("Volume updated for user {$user->id} on payment status change to paid: +{$order->total_amount}");

                    // Allocate points when payment status changes to paid
                    $this->allocatePointsForOrder($order, $user);
                } catch (\Exception $e) {
                    Log::error('Error in OrderObserver::updated', [
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                }
            }
        }
        
        // Handle refunds - subtract from volumes when payment status changes to 'refunded'
        if ($order->isDirty('payment_status') && $order->payment_status === 'refunded' && $order->customer_id) {
            $user = User::find($order->customer_id);
            if ($user) {
                // Subtract the refunded amount from volumes
                $this->volumeService->updateUserVolumesOnPurchase($user, -$order->total_amount);
                Log::info("Volume reduced for user {$user->id} on refund: -{$order->total_amount}");
            }
        }
    }

    /**
     * Handle the Order "deleted" event.
     */
    public function deleted(Order $order): void
    {
        // If a paid order is deleted, subtract from volumes
        if ($order->payment_status === 'paid' && $order->customer_id) {
            $user = User::find($order->customer_id);
            if ($user) {
                $this->volumeService->updateUserVolumesOnPurchase($user, -$order->total_amount);
                Log::info("Volume reduced for user {$user->id} on order deletion: -{$order->total_amount}");
            }
        }
    }

    /**
     * Handle the Order "restored" event.
     */
    public function restored(Order $order): void
    {
        // If a paid order is restored, add back to volumes
        if ($order->payment_status === 'paid' && $order->customer_id) {
            $user = User::find($order->customer_id);
            if ($user) {
                $this->volumeService->updateUserVolumesOnPurchase($user, $order->total_amount);
                Log::info("Volume restored for user {$user->id} on order restoration: +{$order->total_amount}");
            }
        }
    }

    /**
     * Handle the Order "force deleted" event.
     */
    public function forceDeleted(Order $order): void
    {
        // Same as deleted
        $this->deleted($order);
    }

    /**
     * Allocate points for order items when order is paid
     */
    protected function allocatePointsForOrder(Order $order, User $user): void
    {
        Log::info('allocatePointsForOrder called', [
            'order_id' => $order->id,
            'user_id' => $user->id,
            'order_total' => $order->total_amount
        ]);

        try {
            // Get order items
            $orderItems = $order->items()->with('product')->get();
            
            Log::info('Order items loaded', [
                'order_id' => $order->id,
                'items_count' => $orderItems->count()
            ]);
            
            if ($orderItems->isEmpty()) {
                Log::warning("No order items found for point allocation", [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number
                ]);
                return;
            }

            $totalPointsAllocated = 0;

            foreach ($orderItems as $orderItem) {
                if (!$orderItem->product) {
                    Log::warning("Product not found for order item", [
                        'order_item_id' => $orderItem->id,
                        'product_id' => $orderItem->product_id
                    ]);
                    continue;
                }

                // Use PointService to allocate points
                $result = $this->pointService->allocatePointsForPurchase(
                    $user,
                    $orderItem->product,
                    $orderItem->quantity
                );

                if ($result['success']) {
                    $totalPointsAllocated += $result['points_allocated'];
                    
                    Log::info('Points allocated for order item', [
                        'order_id' => $order->id,
                        'product_id' => $orderItem->product->id,
                        'product_name' => $orderItem->product->name,
                        'quantity' => $orderItem->quantity,
                        'points_allocated' => $result['points_allocated'],
                        'user_id' => $user->id
                    ]);
                } else {
                    Log::warning('Failed to allocate points for order item', [
                        'order_item_id' => $orderItem->id,
                        'error' => $result['error'] ?? 'Unknown error'
                    ]);
                }
            }

            if ($totalPointsAllocated > 0) {
                Log::info('Point allocation completed for order', [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'user_id' => $user->id,
                    'username' => $user->username,
                    'total_points_allocated' => $totalPointsAllocated
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Failed to allocate points for order', [
                'order_id' => $order->id,
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}
