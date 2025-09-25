<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Product;
use App\Models\Warehouse;
use App\Models\InventoryMovement;
use App\Models\InventoryAdjustment;
use App\Models\InventoryAlert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class InventoryController extends Controller
{
    /**
     * Display a listing of inventory items.
     */
    public function index(Request $request)
    {
        try {
            $query = Inventory::with(['product', 'warehouse'])
                ->select('inventories.*');

            // Search functionality
            if ($request->filled('search')) {
                $search = $request->search;
                $query->whereHas('product', function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('sku', 'LIKE', "%{$search}%")
                      ->orWhere('barcode', 'LIKE', "%{$search}%");
                });
            }

            // Filter by warehouse
            if ($request->filled('warehouse_id')) {
                $query->where('warehouse_id', $request->warehouse_id);
            }

            // Filter by stock status
            if ($request->filled('stock_status')) {
                switch ($request->stock_status) {
                    case 'low_stock':
                        $query->lowStock();
                        break;
                    case 'out_of_stock':
                        $query->outOfStock();
                        break;
                    case 'overstock':
                        $query->overstock();
                        break;
                    case 'in_stock':
                        $query->inStock();
                        break;
                }
            }

            // Filter by condition
            if ($request->filled('condition')) {
                $query->where('condition', $request->condition);
            }

            // Sort functionality
            $sortBy = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');
            
            $allowedSorts = ['product_name', 'quantity', 'available_quantity', 'cost_per_unit', 'total_value', 'created_at'];
            if (in_array($sortBy, $allowedSorts)) {
                if ($sortBy === 'product_name') {
                    $query->join('products', 'inventories.product_id', '=', 'products.id')
                          ->orderBy('products.name', $sortOrder);
                } else {
                    $query->orderBy($sortBy, $sortOrder);
                }
            }

            $inventories = $query->paginate(20);
            $warehouses = Warehouse::active()->get();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'data' => $inventories,
                    'html' => view('inventory.partials.inventory-table', compact('inventories'))->render()
                ]);
            }

            return view('inventory.index', compact('inventories', 'warehouses'));

        } catch (\Exception $e) {
            Log::error('Inventory index error: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error loading inventory data'
                ], 500);
            }

            return back()->with('error', 'Error loading inventory data');
        }
    }

    /**
     * Show the form for creating a new inventory item.
     */
    public function create()
    {
        try {
            $products = Product::active()->whereDoesntHave('inventory')->get();
            $warehouses = Warehouse::active()->get();

            return view('inventory.create', compact('products', 'warehouses'));

        } catch (\Exception $e) {
            Log::error('Inventory create form error: ' . $e->getMessage());
            return back()->with('error', 'Error loading create form');
        }
    }

    /**
     * Store a newly created inventory item.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'product_id' => 'required|exists:products,id|unique:inventories,product_id',
                'warehouse_id' => 'required|exists:warehouses,id',
                'quantity' => 'required|integer|min:0',
                'min_stock_level' => 'required|integer|min:0',
                'max_stock_level' => 'required|integer|min:1',
                'reorder_point' => 'required|integer|min:0',
                'reorder_quantity' => 'required|integer|min:1',
                'cost_per_unit' => 'required|numeric|min:0',
                'location' => 'nullable|string|max:100',
                'batch_number' => 'nullable|string|max:100',
                'serial_number' => 'nullable|string|max:100',
                'expiry_date' => 'nullable|date|after:today',
                'manufacturing_date' => 'nullable|date|before_or_equal:today',
                'condition' => 'required|in:new,used,damaged,expired',
                'notes' => 'nullable|string|max:1000'
            ], [
                'product_id.unique' => 'Inventory record already exists for this product.',
                'max_stock_level.min' => 'Maximum stock level must be at least 1.',
                'expiry_date.after' => 'Expiry date must be in the future.',
                'manufacturing_date.before_or_equal' => 'Manufacturing date cannot be in the future.'
            ]);

            DB::beginTransaction();

            $inventory = Inventory::create($request->all());

            // Create initial inventory movement
            InventoryMovement::create([
                'product_id' => $inventory->product_id,
                'warehouse_id' => $inventory->warehouse_id,
                'inventory_id' => $inventory->id,
                'type' => 'stock_in',
                'quantity' => $inventory->quantity,
                'remaining_quantity' => $inventory->quantity,
                'unit_cost' => $inventory->cost_per_unit,
                'reference_type' => 'initial_stock',
                'notes' => 'Initial stock entry',
                'created_by' => auth()->id(),
                'is_approved' => true,
                'approved_by' => auth()->id(),
                'approved_at' => now()
            ]);

            // Check stock levels and create alerts if needed
            $inventory->checkStockLevels();

            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Inventory item created successfully',
                    'data' => $inventory->load(['product', 'warehouse'])
                ]);
            }

            return redirect()->route('inventory.index')
                           ->with('success', 'Inventory item created successfully');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->errors()
                ], 422);
            }

            return back()->withErrors($e->errors())->withInput();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Inventory store error: ' . $e->getMessage());

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error creating inventory item'
                ], 500);
            }

            return back()->with('error', 'Error creating inventory item')->withInput();
        }
    }

    /**
     * Display the specified inventory item.
     */
    public function show(Inventory $inventory)
    {
        try {
            $inventory->load([
                'product',
                'warehouse',
                'movements' => function ($query) {
                    $query->with(['createdBy', 'approvedBy'])->latest()->take(10);
                },
                'adjustments' => function ($query) {
                    $query->with(['requestedBy', 'approvedBy'])->latest()->take(5);
                },
                'alerts' => function ($query) {
                    $query->unresolved()->latest();
                }
            ]);

            $recentMovements = $inventory->movements;
            $recentAdjustments = $inventory->adjustments;
            $activeAlerts = $inventory->alerts;

            return view('inventory.show', compact(
                'inventory',
                'recentMovements',
                'recentAdjustments',
                'activeAlerts'
            ));

        } catch (\Exception $e) {
            Log::error('Inventory show error: ' . $e->getMessage());
            return back()->with('error', 'Error loading inventory details');
        }
    }

    /**
     * Show the form for editing the specified inventory item.
     */
    public function edit(Inventory $inventory)
    {
        try {
            $warehouses = Warehouse::active()->get();
            
            return view('inventory.edit', compact('inventory', 'warehouses'));

        } catch (\Exception $e) {
            Log::error('Inventory edit form error: ' . $e->getMessage());
            return back()->with('error', 'Error loading edit form');
        }
    }

    /**
     * Update the specified inventory item.
     */
    public function update(Request $request, Inventory $inventory)
    {
        try {
            $request->validate([
                'warehouse_id' => 'required|exists:warehouses,id',
                'min_stock_level' => 'required|integer|min:0',
                'max_stock_level' => 'required|integer|min:1',
                'reorder_point' => 'required|integer|min:0',
                'reorder_quantity' => 'required|integer|min:1',
                'cost_per_unit' => 'required|numeric|min:0',
                'location' => 'nullable|string|max:100',
                'batch_number' => 'nullable|string|max:100',
                'serial_number' => 'nullable|string|max:100',
                'expiry_date' => 'nullable|date|after:today',
                'manufacturing_date' => 'nullable|date|before_or_equal:today',
                'condition' => 'required|in:new,used,damaged,expired',
                'notes' => 'nullable|string|max:1000'
            ]);

            DB::beginTransaction();

            $oldData = $inventory->toArray();
            $inventory->update($request->all());

            // Log significant changes
            $changes = [];
            if ($oldData['warehouse_id'] != $inventory->warehouse_id) {
                $changes[] = "Warehouse changed";
            }
            if ($oldData['cost_per_unit'] != $inventory->cost_per_unit) {
                $changes[] = "Cost per unit changed from {$oldData['cost_per_unit']} to {$inventory->cost_per_unit}";
            }

            if (!empty($changes)) {
                InventoryMovement::create([
                    'product_id' => $inventory->product_id,
                    'warehouse_id' => $inventory->warehouse_id,
                    'inventory_id' => $inventory->id,
                    'type' => 'adjustment',
                    'quantity' => 0,
                    'remaining_quantity' => $inventory->quantity,
                    'unit_cost' => $inventory->cost_per_unit,
                    'reference_type' => 'inventory_update',
                    'notes' => 'Inventory updated: ' . implode(', ', $changes),
                    'created_by' => auth()->id(),
                    'is_approved' => true,
                    'approved_by' => auth()->id(),
                    'approved_at' => now()
                ]);
            }

            // Check stock levels
            $inventory->checkStockLevels();

            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Inventory item updated successfully',
                    'data' => $inventory->load(['product', 'warehouse'])
                ]);
            }

            return redirect()->route('inventory.show', $inventory)
                           ->with('success', 'Inventory item updated successfully');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->errors()
                ], 422);
            }

            return back()->withErrors($e->errors())->withInput();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Inventory update error: ' . $e->getMessage());

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error updating inventory item'
                ], 500);
            }

            return back()->with('error', 'Error updating inventory item')->withInput();
        }
    }

    /**
     * Remove the specified inventory item.
     */
    public function destroy(Request $request, Inventory $inventory)
    {
        try {
            DB::beginTransaction();

            // Check if inventory has movements or adjustments
            if ($inventory->movements()->count() > 1 || $inventory->adjustments()->count() > 0) {
                throw new \Exception('Cannot delete inventory with existing movements or adjustments');
            }

            $inventory->delete();

            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Inventory item deleted successfully'
                ]);
            }

            return redirect()->route('inventory.index')
                           ->with('success', 'Inventory item deleted successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Inventory delete error: ' . $e->getMessage());

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 500);
            }

            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Adjust inventory quantity.
     */
    public function adjust(Request $request, Inventory $inventory)
    {
        try {
            $request->validate([
                'type' => 'required|in:increase,decrease,count_correction,damage,expiry',
                'quantity' => 'required|integer|min:1',
                'reason' => 'required|string|max:255',
                'notes' => 'nullable|string|max:1000',
                'batch_number' => 'nullable|string|max:100',
                'serial_number' => 'nullable|string|max:100',
                'attachments' => 'nullable|array',
                'attachments.*' => 'file|mimes:jpg,jpeg,png,pdf|max:5120'
            ]);

            DB::beginTransaction();

            $quantityBefore = $inventory->quantity;
            $quantityAfter = $request->type === 'increase' 
                ? $quantityBefore + $request->quantity
                : $quantityBefore - $request->quantity;

            // Ensure quantity doesn't go below 0
            $quantityAfter = max(0, $quantityAfter);

            // Handle file uploads
            $attachments = [];
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('inventory/adjustments', 'public');
                    $attachments[] = [
                        'path' => $path,
                        'name' => $file->getClientOriginalName(),
                        'size' => $file->getSize(),
                        'type' => $file->getMimeType()
                    ];
                }
            }

            // Create adjustment request
            $adjustment = InventoryAdjustment::create([
                'inventory_id' => $inventory->id,
                'product_id' => $inventory->product_id,
                'warehouse_id' => $inventory->warehouse_id,
                'type' => $request->type,
                'quantity_before' => $quantityBefore,
                'quantity_after' => $quantityAfter,
                'unit_cost' => $inventory->cost_per_unit,
                'reason' => $request->reason,
                'notes' => $request->notes,
                'batch_number' => $request->batch_number,
                'serial_number' => $request->serial_number,
                'requested_by' => auth()->id(),
                'requested_at' => now(),
                'attachments' => $attachments,
                'status' => 'pending'
            ]);

            $adjustment->generateAdjustmentNumber();

            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Adjustment request created successfully',
                    'data' => $adjustment
                ]);
            }

            return redirect()->route('inventory.show', $inventory)
                           ->with('success', 'Adjustment request created successfully');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->errors()
                ], 422);
            }

            return back()->withErrors($e->errors())->withInput();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Inventory adjustment error: ' . $e->getMessage());

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error creating adjustment request'
                ], 500);
            }

            return back()->with('error', 'Error creating adjustment request')->withInput();
        }
    }

    /**
     * Get inventory analytics data.
     */
    public function analytics(Request $request)
    {
        try {
            $warehouseId = $request->get('warehouse_id');
            
            $query = Inventory::query();
            if ($warehouseId) {
                $query->where('warehouse_id', $warehouseId);
            }

            $analytics = [
                'total_products' => $query->count(),
                'total_quantity' => $query->sum('quantity'),
                'total_value' => $query->sum(DB::raw('quantity * cost_per_unit')),
                'low_stock_items' => $query->lowStock()->count(),
                'out_of_stock_items' => $query->outOfStock()->count(),
                'overstock_items' => $query->overstock()->count(),
                'expiring_soon' => $query->expiringSoon()->count(),
                'expired_items' => $query->expired()->count()
            ];

            // Top products by value
            $topProductsByValue = $query->with('product')
                ->select('inventories.*', DB::raw('quantity * cost_per_unit as total_value'))
                ->orderBy('total_value', 'desc')
                ->take(10)
                ->get();

            // Recent movements
            $recentMovements = InventoryMovement::with(['product', 'warehouse'])
                ->when($warehouseId, function ($q) use ($warehouseId) {
                    $q->where('warehouse_id', $warehouseId);
                })
                ->latest()
                ->take(10)
                ->get();

            // Active alerts
            $activeAlerts = InventoryAlert::with('inventory.product')
                ->whereHas('inventory', function ($q) use ($warehouseId) {
                    if ($warehouseId) {
                        $q->where('warehouse_id', $warehouseId);
                    }
                })
                ->unresolved()
                ->latest()
                ->take(10)
                ->get();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'analytics' => $analytics,
                        'top_products' => $topProductsByValue,
                        'recent_movements' => $recentMovements,
                        'active_alerts' => $activeAlerts
                    ]
                ]);
            }

            $warehouses = Warehouse::active()->get();

            return view('inventory.analytics', compact(
                'analytics',
                'topProductsByValue',
                'recentMovements',
                'activeAlerts',
                'warehouses'
            ));

        } catch (\Exception $e) {
            Log::error('Inventory analytics error: ' . $e->getMessage());

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error loading analytics data'
                ], 500);
            }

            return back()->with('error', 'Error loading analytics data');
        }
    }

    /**
     * Toggle inventory status.
     */
    public function toggleStatus(Request $request, Inventory $inventory)
    {
        try {
            $inventory->is_active = !$inventory->is_active;
            $inventory->save();

            $status = $inventory->is_active ? 'activated' : 'deactivated';

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => "Inventory item {$status} successfully",
                    'is_active' => $inventory->is_active
                ]);
            }

            return back()->with('success', "Inventory item {$status} successfully");

        } catch (\Exception $e) {
            Log::error('Inventory toggle status error: ' . $e->getMessage());

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error updating inventory status'
                ], 500);
            }

            return back()->with('error', 'Error updating inventory status');
        }
    }
}
