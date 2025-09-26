<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GeneralSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use LaravelDaily\Invoices\Invoice;
use LaravelDaily\Invoices\Classes\Party;
use LaravelDaily\Invoices\Classes\InvoiceItem;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        // Initialize the query
        $query = \App\Models\Order::with(['customer', 'items', 'vendor'])
            ->withCount('items');

        // Apply filters
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_status') && $request->payment_status !== 'all') {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('customer', function($customerQuery) use ($search) {
                      $customerQuery->where('name', 'like', "%{$search}%")
                                  ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('vendor')) {
            $query->where('vendor_id', $request->vendor);
        }

        // Sort orders
        $sortBy = $request->get('sort_by', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');
        $query->orderBy($sortBy, $sortDirection);

        // Handle export request
        if ($request->has('export')) {
            return $this->exportOrders($query, $request->export);
        }

        // Paginate results
        $perPage = $request->get('per_page', 15);
        $orders = $query->paginate($perPage)->withQueryString();

        // Calculate statistics
        $stats = $this->getOrderStats($request);

        // Get filter options
        $vendors = \App\Models\User::where('role', 'vendor')
            ->select('id', 'name')
            ->get();

        $orderStatuses = \App\Models\Order::STATUSES;
        $paymentStatuses = \App\Models\Order::PAYMENT_STATUSES;

        return view('admin.orders.index', compact(
            'orders', 
            'stats', 
            'vendors', 
            'orderStatuses', 
            'paymentStatuses'
        ));
    }

    /**
     * Show the form for creating a new order.
     */
    public function create()
    {
        // Get all users (customers, vendors, affiliates, etc.)
        $customers = \App\Models\User::select('id', 'name', 'email', 'role')
            ->orderBy('name')
            ->get();

        // Get available vendors
        $vendors = \App\Models\User::where('role', 'vendor')
            ->select('id', 'name', 'shop_name')
            ->orderBy('name')
            ->get();

        // Get available products
        $products = \App\Models\Product::select('id', 'name', 'price', 'stock_quantity')
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        // Get shipping options from config
        $shippingOptions = config('shipping.options', []);
        
        // Get tax configuration
        $taxConfig = [
            'default_rate' => config('tax.default_rate', 0),
            'rates_by_location' => config('tax.rates_by_location', []),
            'tax_inclusive' => config('tax.tax_inclusive', false),
            'label' => config('tax.label', 'Tax'),
            'currency' => config('tax.display.currency_symbol', '৳'),
        ];

        return view('admin.orders.create', compact('customers', 'vendors', 'products', 'shippingOptions', 'taxConfig'));
    }

    /**
     * Search users for order creation (all roles: customer, vendor, affiliate, admin, etc.)
     */
    public function searchCustomers(Request $request)
    {
        $query = $request->get('q');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }
        
        $users = \App\Models\User::where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('email', 'like', "%{$query}%")
                  ->orWhere('phone', 'like', "%{$query}%")
                  ->orWhere('username', 'like', "%{$query}%");
            })
            ->withCount('orders')
            ->limit(20)
            ->get(['id', 'name', 'email', 'phone', 'username', 'role']);
        
        $result = $users->map(function($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'username' => $user->username,
                'role' => ucfirst($user->role ?? 'user'),
                'orders_count' => $user->orders_count
            ];
        });
        
        return response()->json($result);
    }

    /**
     * Search products for order creation
     */
    public function searchProducts(Request $request)
    {
        $query = $request->get('q');
        $categoryId = $request->get('category_id');
        $vendorId = $request->get('vendor_id');
        $stockStatus = $request->get('stock_status');
        
        $products = \App\Models\Product::query()
            ->with(['vendor:id,name'])
            ->select(['id', 'name', 'sku', 'price', 'stock_quantity', 'vendor_id'])
            ->when($query, function($q) use ($query) {
                $q->where(function($subQuery) use ($query) {
                    $subQuery->where('name', 'like', "%{$query}%")
                            ->orWhere('sku', 'like', "%{$query}%")
                            ->orWhere('barcode', 'like', "%{$query}%");
                });
            })
            ->when($categoryId, function($q) use ($categoryId) {
                $q->where('category_id', $categoryId);
            })
            ->when($vendorId, function($q) use ($vendorId) {
                $q->where('vendor_id', $vendorId);
            })
            ->when($stockStatus, function($q) use ($stockStatus) {
                switch ($stockStatus) {
                    case 'in_stock':
                        $q->where('stock_quantity', '>', 10);
                        break;
                    case 'low_stock':
                        $q->whereBetween('stock_quantity', [1, 10]);
                        break;
                    case 'out_of_stock':
                        $q->where('stock_quantity', '<=', 0);
                        break;
                }
            })
            ->limit(20)
            ->get()
            ->map(function($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'sku' => $product->sku,
                    'price' => $product->price,
                    'stock_quantity' => $product->stock_quantity,
                    'vendor_name' => $product->vendor ? $product->vendor->name : null
                ];
            });
        
        return response()->json($products);
    }

    /**
     * Get categories for product filtering
     */
    public function getCategories()
    {
        $categories = \App\Models\Category::select(['id', 'name'])
            ->where('status', 'active')
            ->orderBy('name')
            ->get();
        
        return response()->json($categories);
    }

    /**
     * Store a newly created order in storage.
     */
    public function store(Request $request)
    {
        try {
            // Log the incoming request for debugging
            Log::info('Store order request received', [
                'data' => $request->all(),
                'user' => Auth::id()
            ]);

            $request->validate([
                'customer_id' => 'required|exists:users,id',
                'vendor_id' => 'nullable|exists:users,id',
                'items' => 'required|array|min:1',
                'items.*.product_id' => 'required|exists:products,id',
                'items.*.quantity' => 'required|integer|min:1',
                'items.*.price' => 'required|numeric|min:0',
                'shipping_amount' => 'nullable|numeric|min:0',
                'tax_amount' => 'nullable|numeric|min:0',
                'discount_amount' => 'nullable|numeric|min:0',
                'payment_method' => 'nullable|string|max:50',
                'shipping_method' => 'nullable|string|max:50',
                
                // Shipping address validation
                'shipping_address' => 'required|array',
                'shipping_address.first_name' => 'required|string|max:100',
                'shipping_address.last_name' => 'required|string|max:100',
                'shipping_address.company' => 'nullable|string|max:100',
                'shipping_address.address_line_1' => 'required|string|max:255',
                'shipping_address.address_line_2' => 'nullable|string|max:255',
                'shipping_address.city' => 'required|string|max:100',
                'shipping_address.state' => 'nullable|string|max:100',
                'shipping_address.postal_code' => 'nullable|string|max:20',
                'shipping_address.country' => 'required|string|max:2',
                'shipping_address.phone' => 'nullable|string|max:20',
                
                // Billing address validation (optional since it can be same as shipping)
                'billing_address' => 'nullable|array',
                'billing_address.first_name' => 'nullable|string|max:100',
                'billing_address.last_name' => 'nullable|string|max:100',
                'billing_address.company' => 'nullable|string|max:100',
                'billing_address.address_line_1' => 'nullable|string|max:255',
                'billing_address.address_line_2' => 'nullable|string|max:255',
                'billing_address.city' => 'nullable|string|max:100',
                'billing_address.state' => 'nullable|string|max:100',
                'billing_address.postal_code' => 'nullable|string|max:20',
                'billing_address.country' => 'nullable|string|max:2',
                'billing_address.phone' => 'nullable|string|max:20',
                
                'notes' => 'nullable|string|max:1000'
            ]);

            // Validate stock availability first before creating order
            foreach ($request->items as $item) {
                $product = \App\Models\Product::find($item['product_id']);
                if (!$product) {
                    throw new \Exception("Product with ID {$item['product_id']} not found");
                }

                // Check if sufficient stock is available
                if ($product->track_quantity && $product->stock_quantity < $item['quantity']) {
                    throw new \Exception("Insufficient stock for product '{$product->name}'. Available: {$product->stock_quantity}, Requested: {$item['quantity']}");
                }
            }

            // Start database transaction for data consistency
            DB::beginTransaction();

            // Calculate totals
            $subtotal = 0;
            foreach ($request->items as $item) {
                $subtotal += $item['quantity'] * $item['price'];
            }

            $shippingAmount = $request->shipping_amount ?? 0;
            $taxAmount = $request->tax_amount ?? 0;
            $discountAmount = $request->discount_amount ?? 0;
            $totalAmount = $subtotal + $shippingAmount + $taxAmount - $discountAmount;

            // Generate order number
            $orderNumber = 'ORD-' . strtoupper(uniqid());

            // Determine payment status based on payment method
            $paymentStatus = 'pending';
            if ($request->payment_method === 'cash') {
                $paymentStatus = 'paid'; // Cash payments are immediately paid
            }

            // Create the order
            $order = \App\Models\Order::create([
                'order_number' => $orderNumber,
                'customer_id' => $request->customer_id,
                'vendor_id' => $request->vendor_id,
                'status' => 'pending',
                'payment_status' => $paymentStatus,
                'payment_method' => $request->payment_method,
                'shipping_method' => $request->shipping_method,
                'subtotal' => $subtotal,
                'shipping_amount' => $shippingAmount,
                'tax_amount' => $taxAmount,
                'discount_amount' => $discountAmount,
                'total_amount' => $totalAmount,
                'currency' => 'BDT', // Default currency for Bangladesh
                'shipping_address' => $request->shipping_address,
                'billing_address' => $request->billing_address ?? $request->shipping_address,
                'notes' => $request->notes,
                'created_by' => Auth::id()
            ]);

            // Create order items and update inventory
            foreach ($request->items as $item) {
                // Validate stock availability before processing
                $product = \App\Models\Product::find($item['product_id']);
                if (!$product) {
                    throw new \Exception("Product with ID {$item['product_id']} not found");
                }

                // Check if sufficient stock is available
                if ($product->track_quantity && $product->stock_quantity < $item['quantity']) {
                    throw new \Exception("Insufficient stock for product '{$product->name}'. Available: {$product->stock_quantity}, Requested: {$item['quantity']}");
                }

                // Create order item
                \App\Models\OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total' => $item['quantity'] * $item['price']
                ]);

                // Update product stock if tracking is enabled
                if ($product->track_quantity) {
                    $previousQuantity = $product->stock_quantity;
                    
                    // Decrement stock quantity
                    $product->decrement('stock_quantity', $item['quantity']);
                    $newQuantity = $product->fresh()->stock_quantity;
                    
                    // Update stock status
                    $product->in_stock = $newQuantity > 0;
                    $product->save();
                    
                    // Log inventory movement if InventoryMovement model exists
                    if (class_exists('\App\Models\InventoryMovement')) {
                        // Get default warehouse or use first available
                        $warehouseId = null;
                        if (class_exists('\App\Models\Warehouse')) {
                            $warehouse = \App\Models\Warehouse::first();
                            $warehouseId = $warehouse ? $warehouse->id : null;
                        }
                        
                        // Create inventory movement record
                        if ($warehouseId) {
                            \App\Models\InventoryMovement::create([
                                'product_id' => $product->id,
                                'warehouse_id' => $warehouseId,
                                'type' => 'sold',
                                'quantity' => $item['quantity'],
                                'remaining_quantity' => $newQuantity,
                                'unit_cost' => $product->cost_price ?? $item['price'],
                                'previous_quantity' => $previousQuantity,
                                'new_quantity' => $newQuantity,
                                'reason' => 'Order creation - Order #' . $order->order_number,
                                'reference_id' => $order->id,
                                'reference_type' => 'order_creation',
                                'user_id' => Auth::id(),
                                'order_id' => $order->id,
                                'created_by' => Auth::id(),
                                'notes' => 'Stock sold via order creation',
                                'is_approved' => true,
                                'approved_by' => Auth::id(),
                                'approved_at' => now(),
                                'movement_date' => now(),
                                'reference_number' => 'MOV-' . now()->format('Ymd') . '-' . rand(1000, 9999)
                            ]);
                        }
                    }
                    
                    // Update inventory record if it exists
                    $inventory = $product->inventory;
                    if ($inventory) {
                        $oldInventoryQuantity = $inventory->quantity;
                        $inventory->quantity = max(0, $inventory->quantity - $item['quantity']);
                        // Note: available_quantity is auto-calculated as (quantity - reserved_quantity)
                        $inventory->last_updated_by = Auth::id();
                        $inventory->save();
                        
                        // Check for low stock alerts
                        if (method_exists($inventory, 'checkAndCreateAlerts')) {
                            $inventory->checkAndCreateAlerts();
                        }
                    }
                    
                    // Check if product needs low stock alert
                    if ($newQuantity <= ($product->min_stock_level ?? 5)) {
                        Log::warning("Low stock alert for product {$product->name} (ID: {$product->id}). Current stock: {$newQuantity}, Minimum level: " . ($product->min_stock_level ?? 5));
                        
                        // Create low stock alert if InventoryAlert model exists
                        if (class_exists('\App\Models\InventoryAlert') && $inventory) {
                            try {
                                \App\Models\InventoryAlert::firstOrCreate([
                                    'inventory_id' => $inventory->id,
                                    'type' => 'low_stock',
                                    'is_resolved' => false
                                ], [
                                    'message' => "Low stock: {$product->name} has {$newQuantity} units remaining",
                                    'severity' => $newQuantity == 0 ? 'critical' : 'warning',
                                    'triggered_at' => now(),
                                    'threshold_value' => $product->min_stock_level ?? 5,
                                    'current_value' => $newQuantity
                                ]);
                            } catch (\Exception $e) {
                                Log::error("Failed to create low stock alert: " . $e->getMessage());
                            }
                        }
                    }
                    
                    Log::info("Stock updated for product {$product->name} (ID: {$product->id}): {$previousQuantity} → {$newQuantity} (sold: {$item['quantity']})");
                }
            }

            // Commit the transaction if everything was successful
            DB::commit();

            Log::info("Order {$orderNumber} created successfully by admin " . Auth::id());

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Order created successfully',
                    'order' => $order->fresh()->load(['customer', 'vendor', 'items.product'])
                ]);
            }

            // Create success message based on payment method
            $successMessage = 'Order created successfully! Order #' . $order->order_number;
            if ($order->payment_method === 'cash') {
                $successMessage .= ' - Payment status automatically set to PAID (Cash payment)';
            } else {
                $successMessage .= ' - Payment status is PENDING (awaiting ' . ucfirst($order->payment_method) . ' confirmation)';
            }

            return redirect()->route('admin.orders.show', $order->id)->with('success', $successMessage);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Rollback transaction on validation error
            DB::rollback();
            
            Log::error('Order creation validation failed', [
                'errors' => $e->errors(),
                'request_data' => $request->all()
            ]);
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->errors()
                ], 422);
            }

            return redirect()->back()->withErrors($e->errors())->withInput()->with('error', 'Validation failed');
        } catch (\Exception $e) {
            // Rollback transaction on any other error
            DB::rollback();
            
            Log::error('Error creating order', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create order: ' . $e->getMessage(),
                    'error_details' => $e->getMessage()
                ], 500);
            }

            return redirect()->back()->withInput()->with('error', 'Failed to create order: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified order from storage.
     */
    public function destroy(Request $request, $id)
    {
        try {
            $order = \App\Models\Order::findOrFail($id);
            
            // Check if order can be deleted
            if (in_array($order->status, ['delivered', 'shipped']) && !$request->has('force')) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Cannot delete a ' . $order->status . ' order. Use force parameter to override.'
                    ], 422);
                }
                return redirect()->back()->with('error', 'Cannot delete a ' . $order->status . ' order');
            }

            $orderNumber = $order->order_number;

            // Restore inventory if order was not delivered
            if ($order->status !== 'delivered') {
                foreach ($order->items as $item) {
                    if ($item->product) {
                        $item->product->increment('stock_quantity', $item->quantity);
                    }
                }
            }

            // Delete order items first
            $order->items()->delete();
            
            // Delete the order
            $order->delete();

            Log::info("Order {$orderNumber} (ID: {$id}) deleted by admin " . Auth::id());

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Order deleted successfully'
                ]);
            }

            return redirect()->route('admin.orders.index')->with('success', 'Order deleted successfully');
        } catch (\Exception $e) {
            Log::error('Error deleting order: ' . $e->getMessage());
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete order: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Failed to delete order');
        }
    }

    private function getOrderStats($request = null)
    {
        $baseQuery = \App\Models\Order::query();
        
        // Apply same date filters to stats if provided
        if ($request && $request->filled('date_from')) {
            $baseQuery->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request && $request->filled('date_to')) {
            $baseQuery->whereDate('created_at', '<=', $request->date_to);
        }

        return [
            'total_orders' => $baseQuery->count(),
            'total_revenue' => $baseQuery->sum('total_amount'),
            'pending_orders' => $baseQuery->where('status', 'pending')->count(),
            'completed_orders' => $baseQuery->where('status', 'delivered')->count(),
            'cancelled_orders' => $baseQuery->where('status', 'cancelled')->count(),
            'revenue_growth' => $this->calculateRevenueGrowth(),
            'orders_growth' => $this->calculateOrdersGrowth(),
        ];
    }

    private function calculateRevenueGrowth()
    {
        $currentMonth = \App\Models\Order::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_amount');
            
        $lastMonth = \App\Models\Order::whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->sum('total_amount');

        if ($lastMonth == 0) return 0;
        
        return round((($currentMonth - $lastMonth) / $lastMonth) * 100, 1);
    }

    private function calculateOrdersGrowth()
    {
        $currentMonth = \App\Models\Order::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
            
        $lastMonth = \App\Models\Order::whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->count();

        if ($lastMonth == 0) return 0;
        
        return round((($currentMonth - $lastMonth) / $lastMonth) * 100, 1);
    }

    public function exportOrders($query, $format = 'csv')
    {
        $orders = $query->with(['customer', 'items', 'vendor'])->get();
        
        $data = $orders->map(function($order) {
            return [
                'Order Number' => $order->order_number ?? '#' . $order->id,
                'Customer' => $order->customer->name ?? 'N/A',
                'Customer Email' => $order->customer->email ?? 'N/A',
                'Vendor' => $order->vendor->name ?? 'N/A',
                'Total Amount' => $order->total_amount,
                'Status' => $order->status_name,
                'Payment Status' => $order->payment_status_name,
                'Items Count' => $order->items_count ?? 0,
                'Created At' => $order->created_at->format('Y-m-d H:i:s'),
                'Shipping Address' => is_array($order->shipping_address) 
                    ? implode(', ', $order->shipping_address) 
                    : $order->shipping_address,
            ];
        });

        if ($format === 'csv') {
            return $this->exportToCsv($data, 'orders_' . now()->format('Y_m_d_H_i_s') . '.csv');
        }

        // Add other export formats here if needed
        return response()->json(['error' => 'Unsupported export format'], 400);
    }

    private function exportToCsv($data, $filename)
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            
            // Add headers
            if ($data->isNotEmpty()) {
                fputcsv($file, array_keys($data->first()));
                
                // Add data rows
                foreach ($data as $row) {
                    fputcsv($file, $row);
                }
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $request->validate([
                'status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled,refunded',
                'note' => 'nullable|string|max:500',
                'discount_amount' => 'nullable|numeric|min:0',
                'updated_items' => 'nullable|string'
            ]);

            // Find order by numeric ID or order number
            if (is_numeric($id)) {
                $order = \App\Models\Order::findOrFail($id);
            } else {
                $order = \App\Models\Order::where('order_number', $id)->firstOrFail();
            }
            
            $oldStatus = $order->status;
            
            // Update discount if provided
            if ($request->filled('discount_amount')) {
                $order->update(['discount_amount' => $request->discount_amount]);
            }
            
            // Update order items if provided
            if ($request->filled('updated_items')) {
                $updatedItems = json_decode($request->updated_items, true);
                if (is_array($updatedItems)) {
                    foreach ($updatedItems as $itemData) {
                        if (isset($itemData['index'])) {
                            $orderItem = $order->items->skip($itemData['index'])->first();
                            if ($orderItem) {
                                $orderItem->update([
                                    'price' => $itemData['price'] ?? $orderItem->price,
                                    'quantity' => $itemData['quantity'] ?? $orderItem->quantity,
                                    'total' => $itemData['total'] ?? ($orderItem->price * $orderItem->quantity)
                                ]);
                            }
                        }
                    }
                    
                    // Recalculate order total
                    $newSubtotal = $order->items->sum('total');
                    $newTotal = $newSubtotal - ($order->discount_amount ?? 0) + ($order->tax_amount ?? 0) + ($order->shipping_amount ?? 0);
                    $order->update([
                        'subtotal' => $newSubtotal,
                        'total_amount' => $newTotal
                    ]);
                }
            }
            
            // Prepare comprehensive notes with admin tracking
            $notesUpdate = $order->notes ?? '';
            $adminName = Auth::check() ? (Auth::user()->name ?? 'Admin') : 'System';
            $adminId = Auth::check() ? Auth::id() : 'system';
            
            // Add status change note
            $statusChangeNote = now()->format('Y-m-d H:i:s') . " - Status changed from '{$oldStatus}' to '{$request->status}' by {$adminName} (ID: {$adminId})";
            if ($request->note) {
                $statusChangeNote .= " | Note: " . $request->note;
            }
            $notesUpdate = $notesUpdate ? $notesUpdate . "\n" . $statusChangeNote : $statusChangeNote;
            
            // Update status
            $order->update([
                'status' => $request->status,
                'notes' => $notesUpdate
            ]);

            // Update payment status and timestamps based on order status
            $paymentUpdates = [];
            switch ($request->status) {
                case 'shipped':
                    $paymentUpdates['shipped_at'] = now();
                    break;
                case 'delivered':
                    $paymentUpdates['delivered_at'] = now();
                    // Auto-complete payment for delivered orders if not already paid
                    if ($order->payment_status !== 'paid') {
                        $paymentUpdates['payment_status'] = 'paid';
                        $paymentUpdates['payment_details'] = json_encode([
                            'status' => 'paid',
                            'paid_at' => now()->toDateTimeString(),
                            'method' => $order->payment_method ?? 'cash',
                            'auto_paid' => true,
                            'auto_paid_reason' => 'Order delivered - automatic payment completion',
                            'updated_by' => $adminName,
                            'updated_at' => now()->toDateTimeString()
                        ]);
                    }
                    break;
                case 'cancelled':
                    $paymentUpdates['cancelled_at'] = now();
                    // Update payment status to failed if not already paid
                    if ($order->payment_status === 'pending') {
                        $paymentUpdates['payment_status'] = 'failed';
                        $paymentUpdates['payment_details'] = json_encode([
                            'status' => 'failed',
                            'failed_at' => now()->toDateTimeString(),
                            'method' => $order->payment_method ?? 'cash',
                            'failed_reason' => $request->note ?? 'Order cancelled',
                            'updated_by' => $adminName,
                            'updated_at' => now()->toDateTimeString()
                        ]);
                    } elseif ($order->payment_status === 'paid') {
                        // If payment was made, mark for refund
                        $paymentUpdates['payment_status'] = 'refunded';
                        $paymentUpdates['refunded_at'] = now();
                        $paymentUpdates['payment_details'] = json_encode([
                            'status' => 'refunded',
                            'refunded_at' => now()->toDateTimeString(),
                            'method' => $order->payment_method ?? 'cash',
                            'refund_reason' => $request->note ?? 'Order cancelled - payment refunded',
                            'refund_amount' => $order->total_amount,
                            'updated_by' => $adminName,
                            'updated_at' => now()->toDateTimeString()
                        ]);
                    }
                    break;
                case 'refunded':
                    $paymentUpdates['refunded_at'] = now();
                    $paymentUpdates['payment_status'] = 'refunded';
                    $paymentUpdates['payment_details'] = json_encode([
                        'status' => 'refunded',
                        'refunded_at' => now()->toDateTimeString(),
                        'method' => $order->payment_method ?? 'cash',
                        'refund_amount' => $order->total_amount,
                        'refund_reason' => $request->note ?? 'Manual refund processed',
                        'updated_by' => $adminName,
                        'updated_at' => now()->toDateTimeString()
                    ]);
                    break;
                case 'confirmed':
                case 'processing':
                    // For cash payments, auto-mark as paid when confirmed/processing
                    if ($order->payment_method === 'cash' && $order->payment_status !== 'paid') {
                        $paymentUpdates['payment_status'] = 'paid';
                        $paymentUpdates['payment_details'] = json_encode([
                            'status' => 'paid',
                            'paid_at' => now()->toDateTimeString(),
                            'method' => 'cash',
                            'currency' => 'BDT',
                            'auto_paid' => true,
                            'auto_paid_reason' => 'Cash payment auto-marked as paid on order confirmation',
                            'updated_by' => $adminName,
                            'updated_at' => now()->toDateTimeString()
                        ]);
                    }
                    
                    // Handle manual activation for direct point purchase orders
                    if ($oldStatus === 'pending' && in_array($request->status, ['confirmed', 'processing'])) {
                        $this->handleDirectPointPurchaseActivation($order, $adminName);
                    }
                    break;
            }
            
            // Apply payment and timestamp updates
            if (!empty($paymentUpdates)) {
                $order->update($paymentUpdates);
            }

            // Log comprehensive status change with payment details
            $logData = [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'old_status' => $oldStatus,
                'new_status' => $request->status,
                'payment_status' => $order->fresh()->payment_status,
                'admin_id' => $adminId,
                'admin_name' => $adminName,
                'note' => $request->note,
                'timestamp' => now()->toDateTimeString(),
                'payment_updates' => $paymentUpdates ?? []
            ];
            
            Log::info("Order status updated comprehensively", $logData);

            // Handle JSON response for AJAX requests
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Order status updated successfully',
                    'order' => $order->fresh(),
                    'old_status' => $oldStatus,
                    'new_status' => $request->status
                ]);
            }

            return redirect()->back()->with('success', 'Order status and details updated successfully');
        } catch (\Exception $e) {
            Log::error('Error updating order status: ' . $e->getMessage());
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update order status: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Failed to update order status');
        }
    }

    public function updateCustomerInfo(Request $request, $id)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'payment_method' => 'nullable|string|max:50',
            'tracking_number' => 'nullable|string|max:100',
        ]);

        // Find order by numeric ID or order number
        if (is_numeric($id)) {
            $order = \App\Models\Order::findOrFail($id);
        } else {
            $order = \App\Models\Order::where('order_number', $id)->firstOrFail();
        }

        // Update customer information
        if ($order->customer) {
            $order->customer->update([
                'name' => $request->customer_name,
                'email' => $request->customer_email,
                'phone' => $request->customer_phone,
            ]);
        }

        // Update order fields
        $order->update([
            'payment_method' => $request->payment_method,
            'tracking_number' => $request->tracking_number,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Customer information updated successfully'
        ]);
    }

    public function updateShippingAddress(Request $request, $id)
    {
        $request->validate([
            'shipping_name' => 'required|string|max:255',
            'shipping_address' => 'required|string|max:255',
            'shipping_city' => 'required|string|max:100',
            'shipping_state' => 'nullable|string|max:100',
            'shipping_postal_code' => 'nullable|string|max:20',
            'shipping_country' => 'required|string|max:100',
        ]);

        // Find order by numeric ID or order number
        if (is_numeric($id)) {
            $order = \App\Models\Order::findOrFail($id);
        } else {
            $order = \App\Models\Order::where('order_number', $id)->firstOrFail();
        }

        $shippingAddress = [
            'name' => $request->shipping_name,
            'address' => $request->shipping_address,
            'city' => $request->shipping_city,
            'state' => $request->shipping_state,
            'postal_code' => $request->shipping_postal_code,
            'country' => $request->shipping_country,
        ];

        $order->update([
            'shipping_address' => $shippingAddress
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Shipping address updated successfully'
        ]);
    }

    public function updateBillingAddress(Request $request, $id)
    {
        $request->validate([
            'billing_name' => 'required|string|max:255',
            'billing_address' => 'required|string|max:255',
            'billing_city' => 'required|string|max:100',
            'billing_state' => 'nullable|string|max:100',
            'billing_postal_code' => 'nullable|string|max:20',
            'billing_country' => 'required|string|max:100',
        ]);

        // Find order by numeric ID or order number
        if (is_numeric($id)) {
            $order = \App\Models\Order::findOrFail($id);
        } else {
            $order = \App\Models\Order::where('order_number', $id)->firstOrFail();
        }

        $billingAddress = [
            'name' => $request->billing_name,
            'address' => $request->billing_address,
            'city' => $request->billing_city,
            'state' => $request->billing_state,
            'postal_code' => $request->billing_postal_code,
            'country' => $request->billing_country,
        ];

        $order->update([
            'billing_address' => $billingAddress
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Billing address updated successfully'
        ]);
    }

    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'order_ids' => 'required|array',
            'order_ids.*' => 'exists:orders,id',
            'action' => 'required|in:update_status,cancel,delete',
            'status' => 'required_if:action,update_status|in:' . implode(',', array_keys(\App\Models\Order::STATUSES))
        ]);

        $orders = \App\Models\Order::whereIn('id', $request->order_ids);

        switch ($request->action) {
            case 'update_status':
                $orders->update(['status' => $request->status]);
                $message = 'Orders status updated successfully';
                break;
            case 'cancel':
                $orders->update(['status' => 'cancelled', 'cancelled_at' => now()]);
                $message = 'Orders cancelled successfully';
                break;
            case 'delete':
                $orders->delete();
                $message = 'Orders deleted successfully';
                break;
        }

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }

    public function show($id)
    {
        // Find order by numeric ID or order number
        if (is_numeric($id)) {
            $order = \App\Models\Order::with([
                'customer', 
                'vendor', 
                'items.product', 
                'payments'
            ])->findOrFail($id);
        } else {
            $order = \App\Models\Order::with([
                'customer', 
                'vendor', 
                'items.product', 
                'payments'
            ])->where('order_number', $id)->firstOrFail();
        }

        // Calculate PV points from actual order items
        $totalPvPoints = $order->items->sum(function($item) {
            $productPv = $item->product->pv_points ?? 0;
            return $productPv * $item->quantity;
        });

        // Calculate commission info based on actual order data
        $commissionInfo = [
            'direct_commission' => $order->total_amount * 0.05, // 5% direct commission
            'level_2_commission' => $order->total_amount * 0.03, // 3% level 2 commission
            'total_commission' => $order->total_amount * 0.08, // 8% total commission
            'pv_points' => $totalPvPoints
        ];

        // Calculate actual totals to ensure accuracy
        $itemsSubtotal = $order->items->sum(function($item) {
            return $item->price * $item->quantity;
        });

        // Format order data for the view
        $orderData = [
            'id' => $order->order_number ?? '#' . $order->id,
            'customer' => $order->customer->name ?? 'N/A',
            'customer_email' => $order->customer->email ?? 'N/A',
            'customer_phone' => $order->customer->phone ?? $order->customer->mobile ?? 'N/A',
            'vendor' => $order->vendor->name ?? 'N/A',
            'vendor_shop' => $order->vendor->shop_name ?? 'N/A',
            'total' => $order->total_amount,
            'subtotal' => $order->subtotal ?? $itemsSubtotal,
            'tax' => $order->tax_amount ?? 0,
            'shipping' => $order->shipping_amount ?? 0,
            'discount' => $order->discount_amount ?? 0,
            'pv_points' => $totalPvPoints,
            'status' => $order->status_name,
            'payment_status' => $order->payment_status_name,
            'shipping_status' => $order->shipping_status_name ?? 'Not Set',
            'payment_method' => $order->payment_method ?? 'N/A',
            'shipping_method' => $order->shipping_method ?? 'N/A',
            'order_date' => $order->created_at->format('Y-m-d H:i:s'),
            'shipped_date' => $order->shipped_at?->format('Y-m-d H:i:s'),
            'delivered_date' => $order->delivered_at?->format('Y-m-d H:i:s'),
            'cancelled_date' => $order->cancelled_at?->format('Y-m-d H:i:s'),
            'tracking_number' => $order->tracking_number ?? 'N/A',
            'notes' => $order->notes ?? '',
            'shipping_address' => is_array($order->shipping_address) ? $order->shipping_address : [
                'name' => $order->customer->name ?? 'N/A',
                'street' => 'N/A',
                'city' => 'N/A',
                'state' => 'N/A',
                'zip' => 'N/A',
                'country' => 'N/A'
            ],
            'billing_address' => is_array($order->billing_address) ? $order->billing_address : (
                is_array($order->shipping_address) ? $order->shipping_address : [
                    'name' => $order->customer->name ?? 'N/A',
                    'street' => 'N/A',
                    'city' => 'N/A',
                    'state' => 'N/A',
                    'zip' => 'N/A',
                    'country' => 'N/A'
                ]
            ),
            'items' => $order->items->map(function($item) {
                return [
                    'id' => $item->id,
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name ?? 'Product #' . $item->product_id,
                    'product_image' => $item->product ? $item->product->image : null,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'pv_points' => $item->product->pv_points ?? 0,
                    'total' => $item->total ?? ($item->price * $item->quantity)
                ];
            }),
            'commission_info' => $commissionInfo,
            'payment_details' => $order->payment_details,
            'payment_history' => $order->payments->map(function($payment) {
                return [
                    'id' => $payment->id,
                    'amount' => $payment->amount,
                    'status' => $payment->status,
                    'method' => $payment->payment_method,
                    'reference' => $payment->transaction_reference,
                    'date' => $payment->created_at->format('Y-m-d H:i:s')
                ];
            })
        ];

        return view('admin.orders.show', ['order' => $orderData]);
    }

    /**
     * Get real payment details for an order from database
     */
    public function getPaymentDetails($id)
    {
        try {
            // Find order by ID with all related data
            $order = \App\Models\Order::with([
                'customer', 
                'vendor', 
                'items.product', 
                'payments'
            ])->findOrFail($id);

            // Parse payment_details JSON if it exists
            $paymentDetails = null;
            if ($order->payment_details) {
                $paymentDetails = is_string($order->payment_details) 
                    ? json_decode($order->payment_details, true) 
                    : $order->payment_details;
            }

            // Create structured response with real database data
            $response = [
                'success' => true,
                'order' => [
                    'id' => $order->id,
                    'order_number' => $order->order_number,
                    'total_amount' => (float) $order->total_amount,
                    'paid_amount' => (float) ($order->paid_amount ?? 0),
                    'payment_status' => $order->payment_status,
                    'payment_method' => $order->payment_method,
                    'customer_name' => $order->customer->name ?? 'Unknown Customer',
                    'customer_email' => $order->customer->email ?? null,
                    'order_date' => $order->created_at->toISOString(),
                    'status' => $order->status
                ],
                'payment_info' => null,
                'payment_history' => []
            ];

            // Add real payment information if available
            if ($paymentDetails) {
                $response['payment_info'] = [
                    'order_id' => $order->id,
                    'user_submitted' => true,
                    'payment_method' => $paymentDetails['online_payment_type'] ?? $paymentDetails['method'] ?? $order->payment_method,
                    'submitted_amount' => (float) $order->total_amount,
                    'submitted_transaction_id' => $paymentDetails['transaction_id'] ?? 'Not provided',
                    'submitted_date' => $paymentDetails['created_at'] ?? $order->created_at->toISOString(),
                    'sender_number' => $paymentDetails['sender_number'] ?? null,
                    'receiver_number' => $paymentDetails['receiver_number'] ?? null,
                    'from_number' => $paymentDetails['from_number'] ?? $paymentDetails['sender_number'] ?? null,
                    'to_number' => $paymentDetails['to_number'] ?? $paymentDetails['receiver_number'] ?? null,
                    'customer_notes' => $paymentDetails['notes'] ?? 'Payment submitted via ' . ($paymentDetails['online_payment_type'] ?? $order->payment_method),
                    'submitted_at' => $paymentDetails['created_at'] ?? $order->created_at->toISOString(),
                    'requires_verification' => $paymentDetails['status'] === 'pending' ?? true,
                    'payment_proof_url' => $paymentDetails['proof_url'] ?? null,
                    'verification_status' => $paymentDetails['status'] ?? 'pending',
                    'admin_notes' => '',
                    'customer_ip' => $paymentDetails['customer_ip'] ?? null,
                    'user_agent' => $paymentDetails['user_agent'] ?? null
                ];
            }

            // Add real payment history from payments table
            if ($order->payments && $order->payments->count() > 0) {
                foreach ($order->payments as $payment) {
                    $response['payment_history'][] = [
                        'action_title' => 'Payment Recorded',
                        'status' => $payment->status ?? 'completed',
                        'description' => "Payment of ৳{$payment->amount} via {$payment->method}",
                        'amount' => (float) $payment->amount,
                        'transaction_id' => $payment->transaction_id ?? $payment->transaction_reference,
                        'created_at' => $payment->created_at->toISOString(),
                        'admin_name' => 'System'
                    ];
                }
            } else {
                // Create history from order data
                $response['payment_history'][] = [
                    'action_title' => 'Order Created',
                    'status' => 'pending',
                    'description' => "Order #{$order->order_number} created with {$order->payment_method} payment method",
                    'amount' => (float) $order->total_amount,
                    'transaction_id' => $paymentDetails['transaction_id'] ?? null,
                    'created_at' => $order->created_at->toISOString(),
                    'admin_name' => 'System'
                ];
            }

            return response()->json($response);

        } catch (\Exception $e) {
            Log::error('Error fetching payment details for order ' . $id . ': ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Order not found or error retrieving payment details',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    public function changeStatus(Request $request, $id)
    {
        try {
            $request->validate([
                'status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled,refunded',
                'note' => 'nullable|string|max:500'
            ]);

            // Find order by numeric ID or order number
            if (is_numeric($id)) {
                $order = \App\Models\Order::findOrFail($id);
            } else {
                $order = \App\Models\Order::where('order_number', $id)->firstOrFail();
            }

            // Use the comprehensive updateStatus logic
            return $this->updateStatus($request, $id);
            
        } catch (\Exception $e) {
            Log::error('Error changing order status: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to change order status: ' . $e->getMessage());
        }
    }

    public function sendInvoiceEmail(Request $request, $id)
    {
        try {
            // Validate email
            $request->validate([
                'email' => 'required|email'
            ]);

            // Find order by numeric ID or order number
            if (is_numeric($id)) {
                $order = \App\Models\Order::with(['customer', 'vendor', 'items.product'])->findOrFail($id);
            } else {
                $order = \App\Models\Order::with(['customer', 'vendor', 'items.product'])->where('order_number', $id)->firstOrFail();
            }

            // Check General Settings for mail configuration
            $generalSettings = GeneralSetting::getSettings();
            
            if (!$generalSettings) {
                return response()->json([
                    'success' => false,
                    'message' => 'General settings not found. Please configure mail settings first.'
                ], 400);
            }

            // Check if mail configuration exists
            $mailConfig = $generalSettings->mail_config;
            if (is_string($mailConfig)) {
                $mailConfig = json_decode($mailConfig, true) ?? [];
            }

            if (empty($mailConfig) || 
                empty($mailConfig['host']) || 
                empty($mailConfig['username']) || 
                empty($mailConfig['password'])) {
                
                return response()->json([
                    'success' => false,
                    'message' => 'Mail configuration not found in General Settings. Please configure SMTP settings first.'
                ], 400);
            }

            // Prepare real order data
            $orderData = [
                'id' => $order->order_number ?? $order->id,
                'customer' => $order->customer ? $order->customer->name : 'N/A',
                'customer_email' => $order->customer ? $order->customer->email : 'N/A',
                'customer_phone' => $order->customer ? $order->customer->phone : 'N/A',
                'total' => $order->total ?? 0,
                'subtotal' => $order->subtotal ?? 0,
                'tax' => $order->tax ?? 0,
                'shipping' => $order->shipping_cost ?? 0,
                'discount' => $order->discount ?? 0,
                'status' => $order->status ?? 'Pending',
                'payment_status' => $order->payment_status ?? 'Pending',
                'payment_method' => $order->payment_method ?? 'N/A',
                'order_date' => $order->created_at ?? now(),
                'tracking_number' => $order->tracking_number ?? null,
                'billing_address' => $this->formatAddressString($order, 'billing'),
                'notes' => $order->notes ?? null,
                'items' => $order->items ? $order->items->map(function($item) {
                    return [
                        'product_id' => $item->product_id ?? 'N/A',
                        'product_name' => $item->product ? $item->product->name : $item->product_name ?? 'N/A',
                        'quantity' => $item->quantity ?? 0,
                        'price' => $item->price ?? 0,
                        'total' => ($item->quantity ?? 0) * ($item->price ?? 0)
                    ];
                })->toArray() : []
            ];

            $emailTo = $request->input('email', $orderData['customer_email']);

            // Generate printable invoice URL
            $invoiceUrl = url("/admin/orders/{$orderData['id']}/printable-invoice");

            // Implement actual email sending
            try {
                // Temporarily configure mail settings for this request
                $this->configureTempMailSettings($mailConfig, $generalSettings);

                // Send email using Laravel Mail
                Mail::send([], [], function($message) use ($emailTo, $orderData, $generalSettings, $invoiceUrl) {
                    $message->to($emailTo)
                            ->subject('Invoice #' . $orderData['id'] . ' - Your Order Details')
                            ->from(
                                $generalSettings->email_from ?? 'noreply@example.com',
                                $generalSettings->site_name ?? 'Laravel Application'
                            )
                            ->html($this->generateModernInvoiceEmailHtml($orderData, $generalSettings, $invoiceUrl));
                });

                Log::info("Invoice email sent successfully", [
                    'order_id' => $orderData['id'],
                    'recipient' => $emailTo,
                    'invoice_url' => $invoiceUrl,
                    'mail_host' => $mailConfig['host'] ?? 'not_configured'
                ]);

                return response()->json([
                    'success' => true,
                    'message' => "Invoice email sent successfully to {$emailTo}! Invoice URL: {$invoiceUrl}"
                ]);

            } catch (\Exception $mailException) {
                Log::error("Failed to send invoice email", [
                    'order_id' => $orderData['id'],
                    'recipient' => $emailTo,
                    'error' => $mailException->getMessage(),
                    'mail_config' => isset($mailConfig['host']) ? $mailConfig['host'] : 'not_configured'
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Failed to send email: ' . $mailException->getMessage()
                ], 500);
            }

        } catch (\Exception $e) {
            Log::error("Invoice email sending failed", [
                'order_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to send invoice email: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Temporarily configure mail settings for this request
     */
    private function configureTempMailSettings($mailConfig, $generalSettings)
    {
        Config::set([
            'mail.default' => $mailConfig['driver'] ?? 'smtp',
            'mail.mailers.smtp.transport' => 'smtp',
            'mail.mailers.smtp.host' => $mailConfig['host'],
            'mail.mailers.smtp.port' => $mailConfig['port'] ?? 587,
            'mail.mailers.smtp.encryption' => $mailConfig['encryption'] ?? 'tls',
            'mail.mailers.smtp.username' => $mailConfig['username'],
            'mail.mailers.smtp.password' => $mailConfig['password'],
            'mail.mailers.smtp.timeout' => 60,
            'mail.from.address' => $mailConfig['from_address'] ?? $generalSettings->email_from ?? 'noreply@example.com',
            'mail.from.name' => $mailConfig['from_name'] ?? $generalSettings->site_name ?? 'Laravel Application',
        ]);
    }

    /**
     * Generate HTML content for invoice email
     */
    /**
     * Generate modern invoice email HTML with printable invoice link
     */
    private function generateModernInvoiceEmailHtml($order, $generalSettings = null, $invoiceUrl = '')
    {
        // Use the new dynamic company fields
        $settings = is_array($generalSettings) ? $generalSettings : ($generalSettings ? $generalSettings->toArray() : []);
        
        $companyName = $settings['company_name'] ?? $settings['site_name'] ?? 'Your Company Name';
        $companyEmail = $settings['company_email'] ?? $settings['email_from'] ?? 'info@company.com';
        $companyPhone = $settings['company_phone'] ?? $settings['contact_phone'] ?? '+880 1700-000000';
        $companyAddress = $settings['company_address'] ?? $settings['contact_address'] ?? '123 Business Street, Dhaka, Bangladesh 1207';
        
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <style>
                body { 
                    font-family: Arial, sans-serif; 
                    margin: 0; 
                    padding: 20px; 
                    background-color: #f5f5f5;
                    line-height: 1.6;
                }
                .email-container {
                    max-width: 600px;
                    margin: 0 auto;
                    background-color: white;
                    border-radius: 8px;
                    overflow: hidden;
                    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                }
                .header { 
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    color: white; 
                    padding: 30px 20px; 
                    text-align: center; 
                }
                .header h1 { 
                    margin: 0; 
                    font-size: 28px; 
                    font-weight: bold;
                }
                .header p { 
                    margin: 10px 0 0 0; 
                    opacity: 0.9;
                    font-size: 16px;
                }
                .content { 
                    padding: 30px 20px; 
                }
                .invoice-summary {
                    background: #f8f9fa;
                    border-radius: 6px;
                    padding: 20px;
                    margin-bottom: 25px;
                    border-left: 4px solid #667eea;
                }
                .invoice-summary h3 {
                    margin: 0 0 15px 0;
                    color: #333;
                    font-size: 18px;
                }
                .summary-row {
                    display: flex;
                    justify-content: space-between;
                    margin-bottom: 8px;
                    font-size: 14px;
                }
                .summary-row.total {
                    font-weight: bold;
                    font-size: 16px;
                    color: #667eea;
                    border-top: 1px solid #ddd;
                    padding-top: 8px;
                    margin-top: 8px;
                }
                .btn-container {
                    text-align: center;
                    margin: 30px 0;
                }
                .btn-primary {
                    display: inline-block;
                    padding: 15px 30px;
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    color: white;
                    text-decoration: none;
                    border-radius: 6px;
                    font-weight: bold;
                    font-size: 16px;
                    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
                    transition: all 0.3s ease;
                }
                .btn-primary:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
                }
                .order-details {
                    background: #fff;
                    border: 1px solid #e9ecef;
                    border-radius: 6px;
                    overflow: hidden;
                    margin-bottom: 25px;
                }
                .order-details h4 {
                    background: #f8f9fa;
                    margin: 0;
                    padding: 15px 20px;
                    border-bottom: 1px solid #e9ecef;
                    color: #333;
                    font-size: 16px;
                }
                .order-items {
                    padding: 0;
                }
                .item {
                    padding: 15px 20px;
                    border-bottom: 1px solid #f1f3f4;
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                }
                .item:last-child {
                    border-bottom: none;
                }
                .item-info {
                    flex: 1;
                }
                .item-name {
                    font-weight: 600;
                    color: #333;
                    margin-bottom: 4px;
                }
                .item-details {
                    font-size: 12px;
                    color: #666;
                }
                .item-total {
                    font-weight: bold;
                    color: #667eea;
                    font-size: 14px;
                }
                .footer {
                    background: #f8f9fa;
                    padding: 25px 20px;
                    text-align: center;
                    color: #666;
                    font-size: 14px;
                }
                .company-info {
                    margin-bottom: 20px;
                    font-size: 13px;
                    line-height: 1.4;
                }
                .social-links {
                    margin-top: 15px;
                }
                .social-links a {
                    color: #667eea;
                    text-decoration: none;
                    margin: 0 10px;
                }
                @media only screen and (max-width: 600px) {
                    .email-container { margin: 0; border-radius: 0; }
                    .content { padding: 20px 15px; }
                    .btn-primary { padding: 12px 25px; font-size: 14px; }
                    .summary-row { font-size: 13px; }
                }
            </style>
        </head>
        <body>
            <div class="email-container">
                <div class="header">
                    <h1>📧 Invoice Ready</h1>
                    <p>Your order invoice #' . $order['id'] . ' is ready for review</p>
                </div>
                
                <div class="content">
                    <div class="invoice-summary">
                        <h3>📋 Order Summary</h3>
                        <div class="summary-row">
                            <span>Order Number:</span>
                            <span><strong>' . $order['id'] . '</strong></span>
                        </div>
                        <div class="summary-row">
                            <span>Order Date:</span>
                            <span>' . date('F j, Y', strtotime($order['order_date'])) . '</span>
                        </div>
                        <div class="summary-row">
                            <span>Status:</span>
                            <span><strong>' . ucfirst($order['status']) . '</strong></span>
                        </div>
                        <div class="summary-row">
                            <span>Payment Method:</span>
                            <span>' . $order['payment_method'] . '</span>
                        </div>
                        <div class="summary-row total">
                            <span>Total Amount:</span>
                            <span>৳' . number_format($order['total'], 2) . '</span>
                        </div>
                    </div>

                    <div class="btn-container">
                        <a href="' . $invoiceUrl . '" class="btn-primary" target="_blank">
                            🖨️ View & Print Invoice
                        </a>
                    </div>

                    <div class="order-details">
                        <h4>📦 Order Items</h4>
                        <div class="order-items">';
        
        // Add order items
        if (isset($order['items']) && is_array($order['items'])) {
            foreach ($order['items'] as $item) {
                $html .= '
                            <div class="item">
                                <div class="item-info">
                                    <div class="item-name">' . $item['product_name'] . '</div>
                                    <div class="item-details">Qty: ' . $item['quantity'] . ' × ৳' . number_format($item['price'], 2) . '</div>
                                </div>
                                <div class="item-total">৳' . number_format($item['total'], 2) . '</div>
                            </div>';
            }
        } else {
            $html .= '
                            <div class="item">
                                <div class="item-info">
                                    <div class="item-name">No items found</div>
                                </div>
                            </div>';
        }
        
        $html .= '
                        </div>
                    </div>

                    <div class="invoice-summary">
                        <h3>💰 Payment Breakdown</h3>
                        <div class="summary-row">
                            <span>Subtotal:</span>
                            <span>৳' . number_format($order['subtotal'], 2) . '</span>
                        </div>';
        
        if (isset($order['discount']) && $order['discount'] > 0) {
            $html .= '
                        <div class="summary-row">
                            <span>Discount:</span>
                            <span>-৳' . number_format($order['discount'], 2) . '</span>
                        </div>';
        }
        
        $html .= '
                        <div class="summary-row">
                            <span>Tax:</span>
                            <span>৳' . number_format($order['tax'], 2) . '</span>
                        </div>
                        <div class="summary-row">
                            <span>Shipping:</span>
                            <span>৳' . number_format($order['shipping'], 2) . '</span>
                        </div>
                        <div class="summary-row total">
                            <span>Final Total:</span>
                            <span>৳' . number_format($order['total'], 2) . '</span>
                        </div>
                    </div>

                    <p style="text-align: center; margin: 30px 0; color: #666; font-size: 14px;">
                        🔗 <strong>Direct Invoice Link:</strong><br>
                        <a href="' . $invoiceUrl . '" style="color: #667eea; word-break: break-all;">' . $invoiceUrl . '</a>
                    </p>
                </div>

                <div class="footer">
                    <div class="company-info">
                        <strong>' . $companyName . '</strong><br>
                        ' . $companyAddress . '<br>
                        📞 ' . $companyPhone . ' | 📧 ' . $companyEmail . '
                    </div>
                    
                    <hr style="border: none; border-top: 1px solid #ddd; margin: 20px 0;">
                    
                    <p style="margin: 15px 0 5px 0;">
                        <strong>Thank you for your business! 🙏</strong>
                    </p>
                    <p style="margin: 0; font-size: 12px; color: #888;">
                        This is an automated email. Please do not reply to this message.
                    </p>
                    
                    <div class="social-links">
                        <a href="#">📧 Email</a>
                        <a href="#">🌐 Website</a>
                        <a href="#">📱 Support</a>
                    </div>
                </div>
            </div>
        </body>
        </html>';

        return $html;
    }

    /**
     * Show the form for editing the specified order.
     */
    public function edit($id)
    {
        // Mock order data - replace with actual database query
        $order = [
            'id' => $id,
            'order_number' => 'ORD-' . str_pad($id, 6, '0', STR_PAD_LEFT),
            'customer_name' => 'John Smith',
            'customer_email' => 'john@example.com',
            'customer_phone' => '+1-555-0123',
            'status' => 'Processing',
            'payment_status' => 'Paid',
            'payment_method' => 'Credit Card',
            'total' => 679.97,
            'subtotal' => 579.97,
            'tax' => 58.00,
            'shipping' => 42.00,
            'discount' => 0,
            'created_at' => now()->subDays(5),
            'shipping_address' => [
                'name' => 'John Smith',
                'street' => '123 Main St',
                'city' => 'New York',
                'state' => 'NY',
                'zip' => '10001',
                'country' => 'USA'
            ],
            'notes' => 'Customer requested express shipping'
        ];

        return view('admin.orders.edit', compact('order'));
    }

    /**
     * Update the specified order.
     */
    public function update(Request $request, $id)
    {
        // Validate the request
        $request->validate([
            'status' => 'required|string',
            'payment_status' => 'required|string',
            'shipping_address.name' => 'required|string|max:255',
            'shipping_address.street' => 'required|string|max:255',
            'shipping_address.city' => 'required|string|max:255',
            'shipping_address.state' => 'required|string|max:255',
            'shipping_address.zip' => 'required|string|max:20',
            'shipping_address.country' => 'required|string|max:255',
        ]);

        // Here you would update the order in the database
        // Order::find($id)->update($request->all());

        return redirect()->route('admin.orders.show', $id)->with('success', 'Order updated successfully!');
    }

    /**
     * Generate professional PDF invoice using Laravel Daily Invoices
     */
    public function generateProfessionalInvoice($id)
    {
        try {
            // Check if this is a print request (HTML) or download request (PDF)
            $isPrintRequest = request()->has('print') && request()->get('print') === 'true';
            
            // Check if NumberFormatter class is available (requires intl extension)
            if (!class_exists('NumberFormatter')) {
                Log::warning('NumberFormatter not available, falling back to simple invoice');
                return $this->generateSimpleInvoice($id);
            }

            // Find order by numeric ID or order number
            if (is_numeric($id)) {
                $order = \App\Models\Order::with([
                    'customer', 
                    'vendor', 
                    'items.product', 
                    'payments'
                ])->findOrFail($id);
            } else {
                $order = \App\Models\Order::with([
                    'customer', 
                    'vendor', 
                    'items.product', 
                    'payments'
                ])->where('order_number', $id)->firstOrFail();
            }

            // Create seller party
            $seller = new Party([
                'name' => 'Multi-Vendor E-commerce Platform',
                'address' => '123 Business Street',
                'city' => 'Dhaka',
                'state' => 'Dhaka', 
                'postal_code' => '1207',
                'country' => 'Bangladesh',
                'phone' => '+880 1700-000000',
                'custom_fields' => [
                    'email' => 'info@company.com',
                    'website' => 'www.company.com',
                ],
            ]);

            // Create buyer party
            $buyer = new Party([
                'name' => $order->customer->name ?? 'N/A',
                'address' => 'Address Not Available',
                'city' => 'N/A',
                'state' => 'N/A',
                'postal_code' => 'N/A',
                'country' => 'Bangladesh',
                'phone' => $order->customer->phone ?? $order->customer->mobile ?? 'N/A',
                'custom_fields' => [
                    'email' => $order->customer->email ?? 'N/A',
                    'payment_method' => $order->payment_method ?? 'N/A',
                ],
            ]);

            // Create invoice items
            $items = [];
            foreach ($order->items as $item) {
                $productPv = $item->product->pv_points ?? 0;
                $invoiceItem = InvoiceItem::make($item->product->name ?? 'Product #' . $item->product_id)
                    ->description('Product ID: ' . $item->product_id . ($productPv ? ' | PV Points: ' . $productPv : ''))
                    ->pricePerUnit($item->price)
                    ->quantity($item->quantity);
                
                $items[] = $invoiceItem;
            }

            // Add discount item if exists
            if ($order->discount_amount > 0) {
                $discountItem = InvoiceItem::make('Discount')
                    ->pricePerUnit(-$order->discount_amount)
                    ->quantity(1);
                $items[] = $discountItem;
            }

            // Add tax item if exists
            if ($order->tax_amount > 0) {
                $taxItem = InvoiceItem::make('Tax')
                    ->pricePerUnit($order->tax_amount)
                    ->quantity(1);
                $items[] = $taxItem;
            }

            // Add shipping item if exists
            if ($order->shipping_amount > 0) {
                $shippingItem = InvoiceItem::make('Shipping')
                    ->pricePerUnit($order->shipping_amount)
                    ->quantity(1);
                $items[] = $shippingItem;
            }

            // Create the invoice
            $invoice = Invoice::make()
                ->series('INV')
                ->sequence($order->id)
                ->delimiter('-')
                ->serialNumberFormat('{SERIES}{DELIMITER}{SEQUENCE}')
                ->seller($seller)
                ->buyer($buyer)
                ->date($order->created_at)
                ->dateFormat('d/m/Y')
                ->payUntilDays(7)
                ->currencySymbol('&#2547;') // HTML entity for Bengali Taka symbol ৳
                ->currencyCode('BDT');

            // Add all items
            foreach ($items as $item) {
                $invoice->addItem($item);
            }

            // Add notes if exists
            $notesText = '';
            if ($order->notes) {
                $notesText .= $order->notes . "\n\n";
            }
            $notesText .= 'Thank you for your business!' . "\n";
            $notesText .= 'We appreciate your trust in our services.';
            
            $invoice->notes($notesText);

            // If print request, return the invoice as HTML for printing
            if ($isPrintRequest) {
                // Get the HTML content from the invoice and add print styling
                $invoiceHtml = $invoice->toHtml();
                
                // Add print-specific styling and auto-print JavaScript
                $printableHtml = '
                <!DOCTYPE html>
                <html>
                <head>
                    <meta charset="UTF-8">
                    <title>Invoice #' . $order->id . '</title>
                    <style>
                        @media print {
                            @page { margin: 15mm; }
                            body { font-size: 12px; }
                        }
                        @media screen {
                            body { margin: 20px; }
                        }
                    </style>
                    <script>
                        window.onload = function() {
                            setTimeout(function() {
                                window.print();
                            }, 500);
                        };
                        window.onafterprint = function() {
                            window.close();
                        };
                    </script>
                </head>
                <body>
                    ' . $invoiceHtml . '
                </body>
                </html>';
                
                return response($printableHtml)->header('Content-Type', 'text/html');
            }

            // Generate and return PDF for download
            return $invoice->stream();

        } catch (\Exception $e) {
            Log::error('Error generating professional invoice: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to generate invoice: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Generate printable invoice (public endpoint)
     */
    public function printableInvoice($id)
    {
        return $this->generatePrintableInvoice($id, false);
    }

    /**
     * Download printable invoice as PDF (public endpoint)
     */
    public function downloadPrintableInvoice($id)
    {
        return $this->generatePrintableInvoice($id, true);
    }

    /**
     * Generate printable HTML invoice for direct printing or PDF download
     */
    private function generatePrintableInvoice($id, $asPdf = false)
    {
        try {
            // Get dynamic company settings
            $settings = \App\Models\GeneralSetting::getSettings();
            
            // Find order by numeric ID or order number
            if (is_numeric($id)) {
                $order = \App\Models\Order::with([
                    'customer', 
                    'vendor', 
                    'items.product', 
                    'payments'
                ])->findOrFail($id);
            } else {
                $order = \App\Models\Order::with([
                    'customer', 
                    'vendor', 
                    'items.product', 
                    'payments'
                ])->where('order_number', $id)->firstOrFail();
            }
            
            // Calculate actual totals to ensure accuracy
            $itemsSubtotal = $order->items->sum(function($item) {
                return $item->price * $item->quantity;
            });
            
            // Format order data for the printable view using correct field names
            $orderData = [
                'id' => $order->order_number ?? '#' . $order->id,
                'order_date' => $order->created_at ? $order->created_at->format('Y-m-d') : now()->format('Y-m-d'),
                'customer' => $order->customer->name ?? 'Guest Customer',
                'customer_email' => $order->customer->email ?? 'N/A',
                'customer_phone' => $order->customer->phone ?? $order->customer->mobile ?? 'N/A',
                'billing_address' => $this->formatAddressString($order->billing_address ?? []),
                'shipping_address' => $this->formatAddressString($order->shipping_address ?? []),
                'payment_method' => $order->payment_method ?? 'Not specified',
                'status' => $order->status_name ?? $order->status ?? 'Pending',
                'tracking_number' => $order->tracking_number ?? '',
                'notes' => $order->notes ?? '',
                'subtotal' => $order->subtotal ?? $itemsSubtotal,
                'discount' => $order->discount_amount ?? 0,
                'tax' => $order->tax_amount ?? 0,
                'shipping' => $order->shipping_amount ?? 0,
                'total' => $order->total_amount ?? $itemsSubtotal,
                'items' => []
            ];
            
            // Format order items
            foreach ($order->items as $item) {
                $orderData['items'][] = [
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name ?? $item->product_name ?? 'Unknown Product',
                    'price' => $item->price ?? 0,
                    'quantity' => $item->quantity ?? 1,
                    'total' => ($item->price * $item->quantity) ?? 0
                ];
            }
            
            // Debug: Log the number of items to check for duplicates
            Log::info("Order {$order->id} has " . count($order->items) . " items, formatted to " . count($orderData['items']) . " items");
            
            if ($asPdf) {
                // Generate PDF using DomPDF with improved settings for proper sizing
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.invoices.simple-print-invoice', compact('orderData', 'settings'));
                
                // Configure PDF settings for proper A4 sizing and better formatting
                $pdf->setPaper('A4', 'portrait');
                $pdf->setOptions([
                    'defaultFont' => 'DejaVu Sans',
                    'isRemoteEnabled' => true,
                    'isPhpEnabled' => false,
                    'defaultPaperSize' => 'A4',
                    'dpi' => 96,
                    'fontHeightRatio' => 1.0,
                    'chroot' => public_path(),
                    'fontDir' => storage_path('fonts/'),
                    'fontCache' => storage_path('fonts/'),
                    'tempDir' => storage_path('framework/cache/'),
                    'debugKeepTemp' => false,
                    'debugCss' => false,
                    'debugLayout' => false
                ]);
                
                return $pdf->download('printable-invoice-' . $orderData['id'] . '.pdf');
            } else {
                // Return HTML version for printing
                return view('admin.invoices.simple-print-invoice', compact('orderData', 'settings'));
            }
            
        } catch (\Exception $e) {
            Log::error('Error generating printable invoice: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to generate printable invoice: ' . $e->getMessage());
        }
    }

    /**
     * Convert number to words (for amount in words)
     */
    private function convertNumberToWords($number)
    {
        $hyphen      = '-';
        $conjunction = ' and ';
        $separator   = ', ';
        $negative    = 'negative ';
        $decimal     = ' point ';
        $dictionary  = array(
            0                   => 'zero',
            1                   => 'one',
            2                   => 'two',
            3                   => 'three',
            4                   => 'four',
            5                   => 'five',
            6                   => 'six',
            7                   => 'seven',
            8                   => 'eight',
            9                   => 'nine',
            10                  => 'ten',
            11                  => 'eleven',
            12                  => 'twelve',
            13                  => 'thirteen',
            14                  => 'fourteen',
            15                  => 'fifteen',
            16                  => 'sixteen',
            17                  => 'seventeen',
            18                  => 'eighteen',
            19                  => 'nineteen',
            20                  => 'twenty',
            30                  => 'thirty',
            40                  => 'fourty',
            50                  => 'fifty',
            60                  => 'sixty',
            70                  => 'seventy',
            80                  => 'eighty',
            90                  => 'ninety',
            100                 => 'hundred',
            1000                => 'thousand',
            1000000             => 'million',
            1000000000          => 'billion',
            1000000000000       => 'trillion',
            1000000000000000    => 'quadrillion',
            1000000000000000000 => 'quintillion'
        );

        if (!is_numeric($number)) {
            return false;
        }

        if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
            // overflow
            trigger_error(
                'convertNumberToWords only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
                E_USER_WARNING
            );
            return false;
        }

        if ($number < 0) {
            return $negative . $this->convertNumberToWords(abs($number));
        }

        $string = $fraction = null;

        if (strpos($number, '.') !== false) {
            list($number, $fraction) = explode('.', $number, 2);
            $fraction = str_pad($fraction, 2, '0', STR_PAD_RIGHT);
            $fraction = (int) $fraction;
        }

        switch (true) {
            case $number < 21:
                $string = $dictionary[$number];
                break;
            case $number < 100:
                $tens   = ((int) ($number / 10)) * 10;
                $units  = $number % 10;
                $string = $dictionary[$tens];
                if ($units) {
                    $string .= $hyphen . $dictionary[$units];
                }
                break;
            case $number < 1000:
                $hundreds  = $number / 100;
                $remainder = $number % 100;
                $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
                if ($remainder) {
                    $string .= $conjunction . $this->convertNumberToWords($remainder);
                }
                break;
            default:
                $baseUnit = pow(1000, floor(log($number, 1000)));
                $numBaseUnits = (int) ($number / $baseUnit);
                $remainder = $number % $baseUnit;
                $string = $this->convertNumberToWords($numBaseUnits) . ' ' . $dictionary[$baseUnit];
                if ($remainder) {
                    $string .= $remainder < 100 ? $conjunction : $separator;
                    $string .= $this->convertNumberToWords($remainder);
                }
                break;
        }

        if (null !== $fraction && is_numeric($fraction)) {
            $string .= $decimal;
            $words = array();
            foreach (str_split((string) $fraction) as $digit) {
                $words[] = $dictionary[$digit];
            }
            $string .= implode(' ', $words);
        }

        return $string;
    }

    /**
     * Helper method to format address array
     */
    private function formatAddressArray($address)
    {
        if (is_string($address)) {
            $address = json_decode($address, true) ?? [];
        }
        
        if (!is_array($address)) {
            return [
                'street' => 'N/A',
                'address' => 'N/A',
                'city' => 'N/A',
                'state' => 'N/A',
                'zip' => 'N/A',
                'postal_code' => 'N/A',
                'country' => 'N/A'
            ];
        }
        
        return array_merge([
            'street' => 'N/A',
            'address' => 'N/A',
            'city' => 'N/A',
            'state' => 'N/A',
            'zip' => 'N/A',
            'postal_code' => 'N/A',
            'country' => 'N/A'
        ], $address);
    }

    /**
     * Helper method to format address as string
     */
    private function formatAddressString($address)
    {
        if (is_string($address)) {
            $address = json_decode($address, true) ?? [];
        }
        
        if (!is_array($address)) {
            return 'N/A';
        }
        
        $addressParts = [];
        
        // Add street/address if available
        if (!empty($address['street'])) {
            $addressParts[] = $address['street'];
        } elseif (!empty($address['address'])) {
            $addressParts[] = $address['address'];
        }
        
        // Add city
        if (!empty($address['city'])) {
            $addressParts[] = $address['city'];
        }
        
        // Add state and zip/postal code
        $stateZip = [];
        if (!empty($address['state'])) {
            $stateZip[] = $address['state'];
        }
        if (!empty($address['zip'])) {
            $stateZip[] = $address['zip'];
        } elseif (!empty($address['postal_code'])) {
            $stateZip[] = $address['postal_code'];
        }
        if (!empty($stateZip)) {
            $addressParts[] = implode(' ', $stateZip);
        }
        
        // Add country
        if (!empty($address['country'])) {
            $addressParts[] = $address['country'];
        }
        
        return !empty($addressParts) ? implode(', ', $addressParts) : 'N/A';
    }

    /**
     * Download professional PDF invoice using Laravel Daily Invoices
     */
    public function downloadProfessionalInvoice($id)
    {
        try {
            // Check if NumberFormatter class is available (requires intl extension)
            if (!class_exists('NumberFormatter')) {
                Log::warning('NumberFormatter not available, falling back to simple invoice download');
                return $this->downloadSimpleInvoice($id);
            }

            // Find order by numeric ID or order number
            if (is_numeric($id)) {
                $order = \App\Models\Order::with([
                    'customer', 
                    'vendor', 
                    'items.product', 
                    'payments'
                ])->findOrFail($id);
            } else {
                $order = \App\Models\Order::with([
                    'customer', 
                    'vendor', 
                    'items.product', 
                    'payments'
                ])->where('order_number', $id)->firstOrFail();
            }

            // Create seller party
            $seller = new Party([
                'name' => 'Multi-Vendor E-commerce Platform',
                'address' => '123 Business Street',
                'city' => 'Dhaka',
                'state' => 'Dhaka',
                'postal_code' => '1207',
                'country' => 'Bangladesh',
                'phone' => '+880 1700-000000',
                'custom_fields' => [
                    'email' => 'info@company.com',
                    'website' => 'www.company.com',
                ],
            ]);

            // Create buyer party
            $buyer = new Party([
                'name' => $order->customer->name ?? 'N/A',
                'address' => 'Address Not Available',
                'city' => 'N/A',
                'state' => 'N/A',
                'postal_code' => 'N/A',
                'country' => 'Bangladesh',
                'phone' => $order->customer->phone ?? $order->customer->mobile ?? 'N/A',
                'custom_fields' => [
                    'email' => $order->customer->email ?? 'N/A',
                    'payment_method' => $order->payment_method ?? 'N/A',
                ],
            ]);

            // Create invoice items
            $items = [];
            foreach ($order->items as $item) {
                $productPv = $item->product->pv_points ?? 0;
                $invoiceItem = InvoiceItem::make($item->product->name ?? 'Product #' . $item->product_id)
                    ->description('Product ID: ' . $item->product_id . ($productPv ? ' | PV Points: ' . $productPv : ''))
                    ->pricePerUnit($item->price)
                    ->quantity($item->quantity);
                
                $items[] = $invoiceItem;
            }

            // Add discount item if exists
            if ($order->discount_amount > 0) {
                $discountItem = InvoiceItem::make('Discount')
                    ->pricePerUnit(-$order->discount_amount)
                    ->quantity(1);
                $items[] = $discountItem;
            }

            // Add tax item if exists
            if ($order->tax_amount > 0) {
                $taxItem = InvoiceItem::make('Tax')
                    ->pricePerUnit($order->tax_amount)
                    ->quantity(1);
                $items[] = $taxItem;
            }

            // Add shipping item if exists
            if ($order->shipping_amount > 0) {
                $shippingItem = InvoiceItem::make('Shipping')
                    ->pricePerUnit($order->shipping_amount)
                    ->quantity(1);
                $items[] = $shippingItem;
            }

            // Create the invoice
            $invoice = Invoice::make()
                ->series('INV')
                ->sequence($order->id)
                ->delimiter('-')
                ->serialNumberFormat('{SERIES}{DELIMITER}{SEQUENCE}')
                ->seller($seller)
                ->buyer($buyer)
                ->date($order->created_at)
                ->dateFormat('d/m/Y')
                ->payUntilDays(7)
                ->currencySymbol('Tk') // Use 'Tk' instead of ৳ for better PDF compatibility
                ->currencyCode('BDT');

            // Add all items
            foreach ($items as $item) {
                $invoice->addItem($item);
            }

            // Add notes if exists
            $notesText = '';
            if ($order->notes) {
                $notesText .= $order->notes . "\n\n";
            }
            $notesText .= 'Thank you for your business!' . "\n";
            $notesText .= 'We appreciate your trust in our services.';
            
            $invoice->notes($notesText);

            // Generate and return PDF for download
            return $invoice->download('invoice-' . $order->id);

        } catch (\Exception $e) {
            Log::error('Error downloading professional invoice: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to download invoice: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Generate simple PDF invoice using DomPDF (fallback)
     */
    public function generateSimpleInvoice($id)
    {
        try {
            // Find order by numeric ID or order number
            if (is_numeric($id)) {
                $order = \App\Models\Order::with([
                    'customer', 
                    'vendor', 
                    'items.product', 
                    'payments'
                ])->findOrFail($id);
            } else {
                $order = \App\Models\Order::with([
                    'customer', 
                    'vendor', 
                    'items.product', 
                    'payments'
                ])->where('order_number', $id)->firstOrFail();
            }

            // Format order data for simple PDF
            $orderData = [
                'id' => $order->id,
                'order_number' => $order->order_number ?? '#' . $order->id,
                'customer' => $order->customer->name ?? 'N/A',
                'customer_email' => $order->customer->email ?? 'N/A',
                'customer_phone' => $order->customer->phone ?? $order->customer->mobile ?? 'N/A',
                'total' => $order->total_amount,
                'subtotal' => $order->subtotal ?? 0,
                'tax' => $order->tax_amount ?? 0,
                'shipping' => $order->shipping_amount ?? 0,
                'discount' => $order->discount_amount ?? 0,
                'payment_method' => $order->payment_method ?? 'N/A',
                'order_date' => $order->created_at->format('Y-m-d H:i:s'),
                'notes' => $order->notes ?? '',
                'billing_address' => $order->billing_address ?? [
                    'street' => 'N/A',
                    'city' => 'N/A', 
                    'state' => 'N/A',
                    'zip' => 'N/A',
                    'country' => 'Bangladesh'
                ],
                'shipping_address' => $order->shipping_address ?? [
                    'street' => 'N/A',
                    'city' => 'N/A',
                    'state' => 'N/A', 
                    'zip' => 'N/A',
                    'country' => 'Bangladesh'
                ],
                'pv_points' => $order->items->sum(function($item) {
                    return ($item->product->pv_points ?? 0) * $item->quantity;
                }),
                'items' => $order->items->map(function($item) {
                    return [
                        'product_name' => $item->product->name ?? 'Product #' . $item->product_id,
                        'product_id' => $item->product_id,
                        'quantity' => $item->quantity,
                        'price' => $item->price,
                        'pv_points' => ($item->product->pv_points ?? 0) * $item->quantity,
                        'total' => $item->price * $item->quantity
                    ];
                }),
                'currency_symbol' => '৳'
            ];

            // Generate simple PDF using DomPDF
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.invoices.simple-invoice', compact('orderData'));
            $pdf->setPaper('A4', 'portrait');
            $pdf->setOptions([
                'defaultFont' => 'DejaVu Sans',
                'isRemoteEnabled' => false,
                'isPhpEnabled' => false,
                'defaultPaperSize' => 'A4',
                'dpi' => 96,
                'fontHeightRatio' => 1.1
            ]);

            return $pdf->stream('simple-invoice-' . $order->id . '.pdf');

        } catch (\Exception $e) {
            Log::error('Error generating simple invoice: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to generate simple invoice: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Download simple PDF invoice using DomPDF (fallback)
     */
    public function downloadSimpleInvoice($id)
    {
        try {
            // Find order by numeric ID or order number
            if (is_numeric($id)) {
                $order = \App\Models\Order::with([
                    'customer', 
                    'vendor', 
                    'items.product', 
                    'payments'
                ])->findOrFail($id);
            } else {
                $order = \App\Models\Order::with([
                    'customer', 
                    'vendor', 
                    'items.product', 
                    'payments'
                ])->where('order_number', $id)->firstOrFail();
            }

            // Format order data for simple PDF
            $orderData = [
                'id' => $order->id,
                'order_number' => $order->order_number ?? '#' . $order->id,
                'customer' => $order->customer->name ?? 'N/A',
                'customer_email' => $order->customer->email ?? 'N/A',
                'customer_phone' => $order->customer->phone ?? $order->customer->mobile ?? 'N/A',
                'total' => $order->total_amount,
                'subtotal' => $order->subtotal ?? 0,
                'tax' => $order->tax_amount ?? 0,
                'shipping' => $order->shipping_amount ?? 0,
                'discount' => $order->discount_amount ?? 0,
                'payment_method' => $order->payment_method ?? 'N/A',
                'order_date' => $order->created_at->format('Y-m-d H:i:s'),
                'notes' => $order->notes ?? '',
                'billing_address' => $order->billing_address ?? [
                    'street' => 'N/A',
                    'city' => 'N/A', 
                    'state' => 'N/A',
                    'zip' => 'N/A',
                    'country' => 'Bangladesh'
                ],
                'shipping_address' => $order->shipping_address ?? [
                    'street' => 'N/A',
                    'city' => 'N/A',
                    'state' => 'N/A', 
                    'zip' => 'N/A',
                    'country' => 'Bangladesh'
                ],
                'pv_points' => $order->items->sum(function($item) {
                    return ($item->product->pv_points ?? 0) * $item->quantity;
                }),
                'items' => $order->items->map(function($item) {
                    return [
                        'product_name' => $item->product->name ?? 'Product #' . $item->product_id,
                        'product_id' => $item->product_id,
                        'quantity' => $item->quantity,
                        'price' => $item->price,
                        'pv_points' => ($item->product->pv_points ?? 0) * $item->quantity,
                        'total' => $item->price * $item->quantity
                    ];
                }),
                'currency_symbol' => '৳'
            ];

            // Generate simple PDF using DomPDF for download
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.invoices.simple-invoice', compact('orderData'));
            $pdf->setPaper('A4', 'portrait');
            $pdf->setOptions([
                'defaultFont' => 'DejaVu Sans',
                'isRemoteEnabled' => false,
                'isPhpEnabled' => false,
                'defaultPaperSize' => 'A4',
                'dpi' => 96,
                'fontHeightRatio' => 1.1
            ]);

            return $pdf->download('simple-invoice-' . $order->id . '.pdf');

        } catch (\Exception $e) {
            Log::error('Error downloading simple invoice: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to download simple invoice: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Update payment status
     */
    public function updatePaymentStatus(Request $request, $id)
    {
        $request->validate([
            'payment_status' => 'required|in:pending,paid,failed,refunded,partially_refunded',
            'payment_method' => 'nullable|string|max:50',
            'transaction_id' => 'nullable|string|max:100',
            'payment_note' => 'nullable|string|max:500',
            'refund_amount' => 'nullable|numeric|min:0',
            'amount' => 'nullable|numeric|min:0',
            'payment_date' => 'nullable|date',
            'payment_gateway' => 'nullable|string|max:50',
            'sender_number' => 'nullable|string|max:20',
            'receiver_number' => 'nullable|string|max:20',
            'payment_reference' => 'nullable|string|max:100',
            'payment_fee' => 'nullable|numeric|min:0',
            'payment_verified' => 'nullable|boolean',
            'requires_review' => 'nullable|boolean',
            'notify_customer' => 'nullable|boolean',
            'notify_vendor' => 'nullable|boolean',
            'send_sms' => 'nullable|boolean'
        ]);

        try {
            $order = \App\Models\Order::findOrFail($id);
            $oldPaymentStatus = $order->payment_status;

            // Prepare update data
            $updateData = [
                'payment_status' => $request->payment_status,
            ];

            if ($request->filled('payment_method')) {
                $updateData['payment_method'] = $request->payment_method;
            }

            // Handle payment details
            $paymentDetails = $order->payment_details ?? [];
            
            if ($request->filled('transaction_id')) {
                $paymentDetails['transaction_id'] = $request->transaction_id;
            }

            if ($request->filled('refund_amount')) {
                $paymentDetails['refund_amount'] = $request->refund_amount;
                $paymentDetails['refund_date'] = now()->toDateTimeString();
            }

            if ($request->filled('payment_note')) {
                $paymentDetails['notes'] = ($paymentDetails['notes'] ?? '') . "\n" . now()->format('Y-m-d H:i:s') . ": " . $request->payment_note;
            }

            $updateData['payment_details'] = $paymentDetails;

            // Update timestamp based on payment status
            if ($request->payment_status === 'refunded') {
                $updateData['refunded_at'] = now();
            }

            $order->update($updateData);

            // Log payment status change
            Log::info("Order {$order->order_number} payment status changed from {$oldPaymentStatus} to {$request->payment_status} by admin " . (Auth::check() ? Auth::id() : 'system'));

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Payment status updated successfully',
                    'order' => $order->fresh()
                ]);
            }

            return redirect()->back()->with('success', 'Payment status updated successfully');
        } catch (\Exception $e) {
            Log::error('Error updating payment status: ' . $e->getMessage());
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update payment status: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Failed to update payment status');
        }
    }

    /**
     * Add order note
     */
    public function addNote(Request $request, $id)
    {
        $request->validate([
            'note' => 'required|string|max:1000',
            'note_type' => 'nullable|in:internal,customer,vendor',
            'is_visible_to_customer' => 'nullable|boolean'
        ]);

        try {
            $order = \App\Models\Order::findOrFail($id);
            
            $noteType = $request->note_type ?? 'internal';
            $isVisible = $request->is_visible_to_customer ?? false;
            $adminName = Auth::check() ? Auth::user()->name : 'System';
            
            $newNote = now()->format('Y-m-d H:i:s') . " [{$noteType}] by {$adminName}: " . $request->note;
            
            if ($isVisible) {
                $newNote .= " [Visible to Customer]";
            }

            $order->update([
                'notes' => $order->notes ? $order->notes . "\n" . $newNote : $newNote
            ]);

            // Log note addition
            Log::info("Note added to order {$order->order_number} by admin " . (Auth::check() ? Auth::id() : 'system'));

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Note added successfully',
                    'note' => $newNote
                ]);
            }

            return redirect()->back()->with('success', 'Note added successfully');
        } catch (\Exception $e) {
            Log::error('Error adding note: ' . $e->getMessage());
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to add note: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Failed to add note');
        }
    }

    /**
     * Send order email
     */
    public function sendEmail(Request $request, $id)
    {
        $request->validate([
            'email_type' => 'required|in:order_confirmation,payment_confirmation,shipping_notification,delivery_confirmation,custom',
            'recipient' => 'required|email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'include_invoice' => 'nullable|boolean'
        ]);

        try {
            $order = \App\Models\Order::with(['customer', 'vendor', 'items.product'])->findOrFail($id);
            
            $emailData = [
                'order' => $order,
                'subject' => $request->subject,
                'message' => $request->message,
                'email_type' => $request->email_type,
                'include_invoice' => $request->include_invoice ?? false
            ];

            // Send email using Mail facade
            Mail::send('emails.order-notification', $emailData, function($mail) use ($request, $order) {
                $mail->to($request->recipient)
                     ->subject($request->subject);
                
                // Attach invoice if requested
                if ($request->include_invoice) {
                    try {
                        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.invoices.simple-invoice', [
                            'orderData' => [
                                'id' => $order->id,
                                'order_number' => $order->order_number ?? '#' . $order->id,
                                'customer_name' => $order->customer->name ?? 'N/A',
                                'total_amount' => $order->total_amount,
                                'items' => $order->items
                            ]
                        ]);
                        
                        $mail->attachData($pdf->output(), 'invoice-' . $order->id . '.pdf', [
                            'mime' => 'application/pdf',
                        ]);
                    } catch (\Exception $e) {
                        Log::warning('Failed to attach invoice to email: ' . $e->getMessage());
                    }
                }
            });

            // Log email sent
            Log::info("Email sent for order {$order->order_number} to {$request->recipient} by admin " . (Auth::check() ? Auth::id() : 'system'));

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Email sent successfully'
                ]);
            }

            return redirect()->back()->with('success', 'Email sent successfully');
        } catch (\Exception $e) {
            Log::error('Error sending email: ' . $e->getMessage());
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to send email: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Failed to send email');
        }
    }

    /**
     * Cancel order
     */
    public function cancelOrder(Request $request, $id)
    {
        $request->validate([
            'cancellation_reason' => 'nullable|string|max:500',
            'refund_payment' => 'nullable|string|in:on',
            'restore_inventory' => 'nullable|string|in:on'
        ]);

        // Convert checkbox values to boolean
        $refundPayment = $request->has('refund_payment') && $request->refund_payment === 'on';
        $restoreInventory = $request->has('restore_inventory') && $request->restore_inventory === 'on';

        try {
            $order = \App\Models\Order::findOrFail($id);
            
            // Check if order can be cancelled
            if (in_array($order->status, ['delivered', 'cancelled', 'refunded'])) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Order cannot be cancelled as it is already ' . $order->status
                    ], 422);
                }
                return redirect()->back()->with('error', 'Order cannot be cancelled as it is already ' . $order->status);
            }

            $oldStatus = $order->status;
            $oldPaymentStatus = $order->payment_status;
            $now = now();
            
            // Prepare update data
            $updateData = [
                'status' => 'cancelled',
                'cancelled_at' => $now
            ];

            // Create comprehensive cancellation note
            $cancellationNote = $now->format('Y-m-d H:i:s') . " [ORDER CANCELLED] by " . (Auth::check() ? Auth::user()->name . " (ID: " . Auth::id() . ")" : 'System');
            
            if ($request->filled('cancellation_reason')) {
                $cancellationNote .= "\nReason: " . $request->cancellation_reason;
            } else {
                $cancellationNote .= "\nReason: Order cancelled by admin";
            }
            
            $cancellationNote .= "\nPrevious Status: " . ucfirst($oldStatus) . " → Cancelled";
            $cancellationNote .= "\nPrevious Payment Status: " . ucfirst($oldPaymentStatus);

            // Handle payment status and refunds
            if ($refundPayment && $order->payment_status === 'paid') {
                $updateData['payment_status'] = 'refunded';
                $updateData['refunded_at'] = $now;
                $cancellationNote .= " → Refunded";
                
                // Update payment details with refund information
                $paymentDetails = $order->payment_details ?? [];
                $paymentDetails['refund_amount'] = $order->total_amount;
                $paymentDetails['refund_date'] = $now->toDateTimeString();
                $paymentDetails['refund_reason'] = 'Order cancellation';
                $paymentDetails['refund_method'] = $order->payment_method;
                $paymentDetails['refund_by'] = Auth::check() ? Auth::user()->name : 'System';
                $paymentDetails['original_amount'] = $order->total_amount;
                $updateData['payment_details'] = $paymentDetails;
                
                $cancellationNote .= "\nRefund Amount: ৳" . number_format($order->total_amount, 2);
                $cancellationNote .= "\nRefund Method: " . ucfirst($order->payment_method);
            } elseif (in_array($order->payment_status, ['pending', 'failed'])) {
                // For pending/failed payments, mark as failed (no refund needed)
                $updateData['payment_status'] = 'failed';
                $cancellationNote .= " → Failed (No refund needed)";
            } else {
                $cancellationNote .= " → " . ucfirst($oldPaymentStatus) . " (No refund requested)";
            }

            // Inventory restoration note
            if ($restoreInventory) {
                $cancellationNote .= "\nInventory: Restored to stock";
            } else {
                $cancellationNote .= "\nInventory: Not restored";
            }

            // Append to existing notes
            $existingNotes = $order->notes ? $order->notes . "\n\n" : "";
            $updateData['notes'] = $existingNotes . $cancellationNote;

            // Update the order
            $order->update($updateData);

            // Restore inventory if requested
            if ($restoreInventory) {
                foreach ($order->items as $item) {
                    if ($item->product) {
                        $previousQuantity = $item->product->stock_quantity;
                        $item->product->increment('stock_quantity', $item->quantity);
                        $newQuantity = $item->product->fresh()->stock_quantity;
                        
                        // Log inventory movement
                        if (class_exists('\App\Models\InventoryMovement')) {
                            // Get default warehouse or use first available
                            $warehouseId = null;
                            if (class_exists('\App\Models\Warehouse')) {
                                $warehouse = \App\Models\Warehouse::first();
                                $warehouseId = $warehouse ? $warehouse->id : null;
                            }
                            
                            // Only create movement if warehouse exists
                            if ($warehouseId) {
                                \App\Models\InventoryMovement::create([
                                    'product_id' => $item->product_id,
                                    'warehouse_id' => $warehouseId,
                                    'type' => 'returned',
                                    'quantity' => $item->quantity,
                                    'remaining_quantity' => $newQuantity,
                                    'unit_cost' => $item->price ?? 0,
                                    'previous_quantity' => $previousQuantity,
                                    'new_quantity' => $newQuantity,
                                    'reason' => 'Order cancellation - Order #' . ($order->order_number ?? $order->id),
                                    'reference_id' => $order->id,
                                    'reference_type' => 'order_cancellation',
                                    'user_id' => Auth::id(),
                                    'order_id' => $order->id,
                                    'created_by' => Auth::id(),
                                    'notes' => 'Inventory restored due to order cancellation',
                                    'is_approved' => true,
                                    'approved_by' => Auth::id(),
                                    'approved_at' => now(),
                                    'movement_date' => now(),
                                    'reference_number' => 'MOV-' . now()->format('Ymd') . '-' . rand(1000, 9999)
                                ]);
                            } else {
                                // Log warning if no warehouse found
                                Log::warning("No warehouse found for inventory movement when cancelling order {$order->id}");
                            }
                        }
                    }
                }
            }

            // Log comprehensive order cancellation details
            Log::info("Order cancellation completed", [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'status_change' => $oldStatus . ' → cancelled',
                'payment_status_change' => $oldPaymentStatus . ' → ' . $order->fresh()->payment_status,
                'cancelled_at' => $order->cancelled_at,
                'refunded_at' => $order->refunded_at,
                'refund_requested' => $refundPayment,
                'inventory_restored' => $restoreInventory,
                'cancelled_by' => Auth::check() ? Auth::user()->name . " (ID: " . Auth::id() . ")" : 'System',
                'cancellation_reason' => $request->cancellation_reason ?? 'No reason provided',
                'total_amount' => $order->total_amount,
                'refund_amount' => $refundPayment && $order->payment_status === 'paid' ? $order->total_amount : 0
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Order cancelled successfully',
                    'order' => $order->fresh()->load(['customer', 'vendor', 'items.product']),
                    'changes' => [
                        'status' => $oldStatus . ' → cancelled',
                        'payment_status' => $oldPaymentStatus . ' → ' . $order->fresh()->payment_status,
                        'cancelled_at' => $order->cancelled_at,
                        'refunded_at' => $order->refunded_at,
                        'inventory_restored' => $restoreInventory
                    ]
                ]);
            }

            return redirect()->back()->with('success', 'Order cancelled successfully');
        } catch (\Exception $e) {
            Log::error('Error cancelling order: ' . $e->getMessage());
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to cancel order: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Failed to cancel order');
        }
    }
    
    /**
     * Handle direct point purchase activation when admin confirms order
     */
    private function handleDirectPointPurchaseActivation($order, $adminName)
    {
        try {
            // Check if this is a direct point purchase order
            $isDpp = str_contains($order->notes ?? '', 'Direct point purchase') || 
                     $order->payment_method === 'wallet';
            
            if (!$isDpp) {
                return; // Not a direct point purchase order
            }
            
            // Get the customer and products
            $customer = \App\Models\User::find($order->customer_id);
            if (!$customer) {
                Log::error('Customer not found for direct point purchase activation', ['order_id' => $order->id]);
                return;
            }
            
            // Get the point service
            $pointService = app(\App\Services\PointService::class);
            
            // Process each order item for point allocation
            foreach ($order->items as $orderItem) {
                $product = $orderItem->product;
                if (!$product || !$product->pv_points) {
                    continue;
                }
                
                // Allocate points using the point service
                $pointResult = $pointService->allocatePointsForPurchase(
                    $customer, 
                    $product, 
                    $orderItem->quantity
                );
                
                if ($pointResult['success']) {
                    Log::info('Direct point purchase activated - points allocated', [
                        'order_id' => $order->id,
                        'customer_id' => $customer->id,
                        'product_id' => $product->id,
                        'points_allocated' => $product->pv_points * $orderItem->quantity,
                        'activated_by' => $adminName
                    ]);
                } else {
                    Log::error('Failed to allocate points for direct purchase activation', [
                        'order_id' => $order->id,
                        'customer_id' => $customer->id,
                        'product_id' => $product->id,
                        'error' => $pointResult['error'] ?? 'Unknown error'
                    ]);
                }
            }
            
            // Update order notes to reflect activation
            $currentNotes = $order->notes ?? '';
            $activationNote = "\n" . now()->format('Y-m-d H:i:s') . " - Package activated and points allocated by {$adminName}";
            $order->update(['notes' => $currentNotes . $activationNote]);
            
        } catch (\Exception $e) {
            Log::error('Error in handleDirectPointPurchaseActivation', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}
