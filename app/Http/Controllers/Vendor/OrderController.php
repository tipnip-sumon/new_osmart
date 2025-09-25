<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    /**
     * Display a listing of vendor's orders.
     */
    public function index(Request $request)
    {
        if (Auth::user()->role !== 'vendor') {
            abort(403, 'Access denied. Vendor role required.');
        }

        $query = Order::whereHas('items.product', function($q) {
            $q->where('vendor_id', Auth::id());
        });

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Search by order number or customer
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'LIKE', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'LIKE', "%{$search}%")
                               ->orWhere('email', 'LIKE', "%{$search}%");
                  });
            });
        }

        $orders = $query->with(['customer', 'items.product'])
                       ->orderBy('created_at', 'desc')
                       ->paginate(15);

        // Calculate vendor-specific totals for each order
        foreach ($orders as $order) {
            $vendorTotal = 0;
            $vendorItems = $order->items->filter(function($item) {
                return $item->product && $item->product->vendor_id == Auth::id();
            });
            
            foreach ($vendorItems as $item) {
                $vendorTotal += $item->price * $item->quantity;
            }
            
            $order->vendor_total = $vendorTotal;
            $order->vendor_items_count = $vendorItems->count();
        }

        return view('vendor.orders.index', compact('orders'));
    }

    /**
     * Display pending orders for vendor.
     */
    public function pending(Request $request)
    {
        if (Auth::user()->role !== 'vendor') {
            abort(403, 'Access denied. Vendor role required.');
        }

        $query = Order::whereHas('items.product', function($q) {
            $q->where('vendor_id', Auth::id());
        })->where('status', 'pending');

        $orders = $query->with(['customer', 'items.product'])
                       ->orderBy('created_at', 'desc')
                       ->paginate(15);

        return view('vendor.orders.pending', compact('orders'));
    }

    /**
     * Display completed orders for vendor.
     */
    public function completed(Request $request)
    {
        if (Auth::user()->role !== 'vendor') {
            abort(403, 'Access denied. Vendor role required.');
        }

        $query = Order::whereHas('items.product', function($q) {
            $q->where('vendor_id', Auth::id());
        })->where('status', 'completed');

        $orders = $query->with(['customer', 'items.product'])
                       ->orderBy('created_at', 'desc')
                       ->paginate(15);

        return view('vendor.orders.completed', compact('orders'));
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order)
    {
        if (Auth::user()->role !== 'vendor') {
            abort(403, 'Access denied. Vendor role required.');
        }

        // Check if vendor has items in this order
        $hasVendorItems = $order->items()->whereHas('product', function($q) {
            $q->where('vendor_id', Auth::id());
        })->exists();

        if (!$hasVendorItems) {
            abort(403, 'Access denied. You do not have items in this order.');
        }

        // Get only vendor's items from this order
        $vendorItems = $order->items()->with('product')
                            ->whereHas('product', function($q) {
                                $q->where('vendor_id', Auth::id());
                            })->get();

        // Calculate vendor total
        $vendorTotal = $vendorItems->sum(function($item) {
            return $item->price * $item->quantity;
        });

        return view('vendor.orders.show', compact('order', 'vendorItems', 'vendorTotal'));
    }

    /**
     * Update order status for vendor items.
     */
    public function updateStatus(Request $request, Order $order)
    {
        if (Auth::user()->role !== 'vendor') {
            abort(403, 'Access denied. Vendor role required.');
        }

        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
            'tracking_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:500'
        ]);

        // Check if vendor has items in this order
        $hasVendorItems = $order->items()->whereHas('product', function($q) {
            $q->where('vendor_id', Auth::id());
        })->exists();

        if (!$hasVendorItems) {
            abort(403, 'Access denied. You do not have items in this order.');
        }

        // Validate status transitions
        $currentStatus = $order->status;
        $newStatus = $request->status;
        
        $validTransitions = [
            'pending' => ['pending', 'processing', 'cancelled'],
            'processing' => ['processing', 'shipped', 'cancelled'],
            'shipped' => ['shipped', 'delivered'],
            'delivered' => [], // No transitions from delivered
            'cancelled' => [] // No transitions from cancelled
        ];

        if (!in_array($newStatus, $validTransitions[$currentStatus] ?? [])) {
            return redirect()->back()
                           ->with('error', 'Invalid status transition. Cannot change from ' . $currentStatus . ' to ' . $newStatus);
        }

        try {
            // Begin transaction
            DB::beginTransaction();

            // Update order status
            $updateData = ['status' => $newStatus];
            
            // Add timestamp fields based on status
            switch ($newStatus) {
                case 'processing':
                    if (!$order->processing_at) {
                        $updateData['processing_at'] = now();
                    }
                    break;
                case 'shipped':
                    if (!$order->shipped_at) {
                        $updateData['shipped_at'] = now();
                    }
                    if ($request->tracking_number) {
                        $updateData['tracking_number'] = $request->tracking_number;
                    }
                    break;
                case 'delivered':
                    if (!$order->delivered_at) {
                        $updateData['delivered_at'] = now();
                    }
                    break;
                case 'cancelled':
                    if (!$order->cancelled_at) {
                        $updateData['cancelled_at'] = now();
                    }
                    if ($request->notes) {
                        $updateData['cancellation_reason'] = $request->notes;
                    }
                    break;
            }

            // Update tracking number if provided
            if ($request->tracking_number && in_array($newStatus, ['processing', 'shipped'])) {
                $updateData['tracking_number'] = $request->tracking_number;
            }

            $order->update($updateData);

            // Log the status change
            Log::info('Order status updated by vendor', [
                'order_id' => $order->id,
                'vendor_id' => Auth::id(),
                'old_status' => $currentStatus,
                'new_status' => $newStatus,
                'tracking_number' => $request->tracking_number,
                'notes' => $request->notes
            ]);

            DB::commit();

            $message = 'Order status updated to ' . ucfirst($newStatus) . ' successfully!';
            
            if ($request->tracking_number) {
                $message .= ' Tracking number: ' . $request->tracking_number;
            }

            return redirect()->route('vendor.orders.show', $order)
                           ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Failed to update order status', [
                'order_id' => $order->id,
                'vendor_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                           ->with('error', 'Failed to update order status. Please try again.');
        }
    }

    /**
     * Generate invoice for vendor items in an order.
     */
    public function invoice(Order $order)
    {
        if (Auth::user()->role !== 'vendor') {
            abort(403, 'Access denied. Vendor role required.');
        }

        // Check if vendor has items in this order
        $hasVendorItems = $order->items()->whereHas('product', function($q) {
            $q->where('vendor_id', Auth::id());
        })->exists();

        if (!$hasVendorItems) {
            abort(403, 'Access denied. You do not have items in this order.');
        }

        // Get only vendor's items from this order
        $vendorItems = $order->items()->with('product')
                            ->whereHas('product', function($q) {
                                $q->where('vendor_id', Auth::id());
                            })->get();

        // Calculate vendor total
        $vendorTotal = $vendorItems->sum(function($item) {
            return $item->price * $item->quantity;
        });

        $vendor = Auth::user();

        return view('vendor.orders.invoice', compact('order', 'vendorItems', 'vendorTotal', 'vendor'));
    }
}
