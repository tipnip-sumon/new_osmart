<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class CouponController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of coupons
     */
    public function index(Request $request)
    {
        $query = Coupon::with(['vendor', 'creator']);

        // Filter by search
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Filter by status
        if ($request->filled('status')) {
            switch ($request->status) {
                case 'active':
                    $query->active();
                    break;
                case 'expired':
                    $query->expired();
                    break;
                case 'scheduled':
                    $query->scheduled();
                    break;
                case 'inactive':
                    $query->where('is_active', false);
                    break;
            }
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->byType($request->type);
        }

        // Filter by vendor (for admin)
        if ($request->filled('vendor_id')) {
            $query->byVendor($request->vendor_id);
        }

        // If user is vendor, show only their coupons
        if (Auth::user()->role === 'vendor') {
            $query->byVendor(Auth::id());
        }

        $coupons = $query->orderBy('created_at', 'desc')->paginate(15);

        $stats = [
            'total' => Coupon::count(),
            'active' => Coupon::active()->count(),
            'expired' => Coupon::expired()->count(),
            'scheduled' => Coupon::scheduled()->count(),
            'total_usage' => CouponUsage::count(),
            'total_discount' => CouponUsage::sum('discount_amount')
        ];

        return view('admin.coupons.index', compact('coupons', 'stats'));
    }

    /**
     * Show the form for creating a new coupon
     */
    public function create()
    {
        $vendors = User::vendors()->active()->get();
        $products = Product::active()->get();
        $categories = Category::active()->get();
        
        return view('admin.coupons.create', compact('vendors', 'products', 'categories'));
    }

    /**
     * Store a newly created coupon
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'nullable|string|max:50|unique:coupons,code',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'type' => 'required|in:percentage,fixed,free_shipping,buy_x_get_y,bulk_discount',
            'value' => 'required|numeric|min:0',
            'minimum_amount' => 'nullable|numeric|min:0',
            'maximum_discount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'usage_limit_per_user' => 'nullable|integer|min:1',
            'start_date' => 'nullable|date|after_or_equal:today',
            'end_date' => 'nullable|date|after:start_date',
            'vendor_id' => 'nullable|exists:users,id',
            'applicable_products' => 'nullable|array',
            'applicable_products.*' => 'exists:products,id',
            'applicable_categories' => 'nullable|array',
            'applicable_categories.*' => 'exists:categories,id',
            'exclude_products' => 'nullable|array',
            'exclude_products.*' => 'exists:products,id',
            'exclude_categories' => 'nullable|array',
            'exclude_categories.*' => 'exists:categories,id',
            'user_restrictions' => 'nullable|array',
            'user_restrictions.*' => 'exists:users,id',
            'country_restrictions' => 'nullable|array',
            'priority' => 'nullable|integer|min:1|max:10'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $couponData = $request->all();
            $couponData['created_by'] = Auth::id();

            // If vendor is creating coupon, set vendor_id
            if (Auth::user()->role === 'vendor') {
                $couponData['vendor_id'] = Auth::id();
            }

            // Handle percentage validation
            if ($request->type === 'percentage' && $request->value > 100) {
                return response()->json([
                    'success' => false,
                    'message' => 'Percentage value cannot exceed 100%'
                ], 422);
            }

            $coupon = Coupon::create($couponData);

            // Attach products if specified
            if ($request->filled('applicable_products')) {
                $coupon->products()->attach($request->applicable_products);
            }

            // Attach categories if specified
            if ($request->filled('applicable_categories')) {
                $coupon->categories()->attach($request->applicable_categories);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Coupon created successfully',
                'coupon' => $coupon->load(['vendor', 'creator'])
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create coupon: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified coupon
     */
    public function show(Coupon $coupon)
    {
        $coupon->load(['vendor', 'creator', 'usages.user', 'usages.order']);
        
        $usageStats = [
            'total_uses' => $coupon->usages->count(),
            'unique_users' => $coupon->usages->unique('user_id')->count(),
            'total_discount' => $coupon->usages->sum('discount_amount'),
            'average_discount' => $coupon->usages->avg('discount_amount'),
            'recent_uses' => $coupon->usages()->with(['user', 'order'])
                                             ->latest()
                                             ->limit(10)
                                             ->get()
        ];

        $usageChart = $coupon->usages()
                            ->select(DB::raw('DATE(used_at) as date'), DB::raw('COUNT(*) as count'))
                            ->where('used_at', '>=', now()->subDays(30))
                            ->groupBy('date')
                            ->orderBy('date')
                            ->get();

        return view('admin.coupons.show', compact('coupon', 'usageStats', 'usageChart'));
    }

    /**
     * Show the form for editing the specified coupon
     */
    public function edit(Coupon $coupon)
    {
        // Check if user can edit this coupon
        if (Auth::user()->role === 'vendor' && $coupon->vendor_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this coupon');
        }

        $vendors = User::vendors()->active()->get();
        $products = Product::active()->get();
        $categories = Category::active()->get();
        
        $coupon->load(['products', 'categories']);

        return view('admin.coupons.edit', compact('coupon', 'vendors', 'products', 'categories'));
    }

    /**
     * Update the specified coupon
     */
    public function update(Request $request, Coupon $coupon)
    {
        // Check if user can edit this coupon
        if (Auth::user()->role === 'vendor' && $coupon->vendor_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to this coupon'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:50|unique:coupons,code,' . $coupon->id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'type' => 'required|in:percentage,fixed,free_shipping,buy_x_get_y,bulk_discount',
            'value' => 'required|numeric|min:0',
            'minimum_amount' => 'nullable|numeric|min:0',
            'maximum_discount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'usage_limit_per_user' => 'nullable|integer|min:1',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'vendor_id' => 'nullable|exists:users,id',
            'applicable_products' => 'nullable|array',
            'applicable_products.*' => 'exists:products,id',
            'applicable_categories' => 'nullable|array',
            'applicable_categories.*' => 'exists:categories,id',
            'exclude_products' => 'nullable|array',
            'exclude_products.*' => 'exists:products,id',
            'exclude_categories' => 'nullable|array',
            'exclude_categories.*' => 'exists:categories,id',
            'user_restrictions' => 'nullable|array',
            'user_restrictions.*' => 'exists:users,id',
            'country_restrictions' => 'nullable|array',
            'priority' => 'nullable|integer|min:1|max:10'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Handle percentage validation
            if ($request->type === 'percentage' && $request->value > 100) {
                return response()->json([
                    'success' => false,
                    'message' => 'Percentage value cannot exceed 100%'
                ], 422);
            }

            $coupon->update($request->all());

            // Sync products
            if ($request->has('applicable_products')) {
                $coupon->products()->sync($request->applicable_products ?? []);
            }

            // Sync categories
            if ($request->has('applicable_categories')) {
                $coupon->categories()->sync($request->applicable_categories ?? []);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Coupon updated successfully',
                'coupon' => $coupon->load(['vendor', 'creator'])
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update coupon: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified coupon
     */
    public function destroy(Coupon $coupon)
    {
        // Check if user can delete this coupon
        if (Auth::user()->role === 'vendor' && $coupon->vendor_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to this coupon'
            ], 403);
        }

        try {
            DB::beginTransaction();

            // Detach relationships
            $coupon->products()->detach();
            $coupon->categories()->detach();

            // Soft delete the coupon
            $coupon->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Coupon deleted successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete coupon: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Activate/Deactivate coupon
     */
    public function toggleStatus(Coupon $coupon)
    {
        // Check if user can modify this coupon
        if (Auth::user()->role === 'vendor' && $coupon->vendor_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to this coupon'
            ], 403);
        }

        try {
            $coupon->is_active = !$coupon->is_active;
            $coupon->save();

            return response()->json([
                'success' => true,
                'message' => 'Coupon status updated successfully',
                'is_active' => $coupon->is_active
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update coupon status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Duplicate a coupon
     */
    public function duplicate(Coupon $coupon)
    {
        // Check if user can duplicate this coupon
        if (Auth::user()->role === 'vendor' && $coupon->vendor_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to this coupon'
            ], 403);
        }

        try {
            DB::beginTransaction();

            $newCoupon = $coupon->duplicate();
            $newCoupon->created_by = Auth::id();
            $newCoupon->save();

            // Copy product relationships
            $newCoupon->products()->attach($coupon->products->pluck('id'));
            
            // Copy category relationships
            $newCoupon->categories()->attach($coupon->categories->pluck('id'));

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Coupon duplicated successfully',
                'coupon' => $newCoupon->load(['vendor', 'creator'])
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to duplicate coupon: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Validate coupon code for frontend
     */
    public function validateCoupon(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string',
            'total_amount' => 'required|numeric|min:0',
            'user_id' => 'nullable|exists:users,id',
            'vendor_id' => 'nullable|exists:users,id',
            'product_ids' => 'nullable|array',
            'product_ids.*' => 'exists:products,id',
            'country' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid request data',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $coupon = Coupon::findByCode($request->code);

            if (!$coupon) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid coupon code'
                ], 404);
            }

            // Check if coupon is valid
            if (!$coupon->is_valid) {
                return response()->json([
                    'success' => false,
                    'message' => 'This coupon is not valid or has expired'
                ]);
            }

            // Check user validity
            if ($request->user_id && !$coupon->isValidForUser($request->user_id)) {
                return response()->json([
                    'success' => false,
                    'message' => 'This coupon is not valid for your account'
                ]);
            }

            // Create mock order object for validation
            $mockOrder = (object) [
                'subtotal' => $request->total_amount,
                'vendor_id' => $request->vendor_id,
                'country' => $request->country,
                'items' => collect($request->product_ids ?? [])->map(function($id) {
                    return (object) ['product_id' => $id];
                })
            ];

            if (!$coupon->isValidForOrder($mockOrder)) {
                return response()->json([
                    'success' => false,
                    'message' => 'This coupon is not applicable to your order'
                ]);
            }

            $discountAmount = $coupon->calculateDiscount($mockOrder);

            return response()->json([
                'success' => true,
                'message' => 'Coupon is valid',
                'coupon' => [
                    'id' => $coupon->id,
                    'code' => $coupon->code,
                    'name' => $coupon->name,
                    'type' => $coupon->type,
                    'discount_text' => $coupon->discount_text,
                    'discount_amount' => $discountAmount,
                    'free_shipping' => $coupon->free_shipping
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to validate coupon: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get auto-apply coupons for an order
     */
    public function getAutoApplyCoupons(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'total_amount' => 'required|numeric|min:0',
            'user_id' => 'nullable|exists:users,id',
            'vendor_id' => 'nullable|exists:users,id',
            'product_ids' => 'nullable|array',
            'product_ids.*' => 'exists:products,id',
            'country' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid request data',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Create mock order object
            $mockOrder = (object) [
                'subtotal' => $request->total_amount,
                'vendor_id' => $request->vendor_id,
                'country' => $request->country,
                'items' => collect($request->product_ids ?? [])->map(function($id) {
                    return (object) ['product_id' => $id];
                })
            ];

            $coupons = Coupon::getAutoApplyCoupons($mockOrder, $request->user_id);

            $applicableCoupons = $coupons->map(function($coupon) use ($mockOrder) {
                return [
                    'id' => $coupon->id,
                    'code' => $coupon->code,
                    'name' => $coupon->name,
                    'type' => $coupon->type,
                    'discount_text' => $coupon->discount_text,
                    'discount_amount' => $coupon->calculateDiscount($mockOrder),
                    'free_shipping' => $coupon->free_shipping,
                    'priority' => $coupon->priority
                ];
            });

            return response()->json([
                'success' => true,
                'coupons' => $applicableCoupons
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get auto-apply coupons: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get coupon analytics
     */
    public function analytics(Request $request)
    {
        try {
            $dateRange = $request->get('range', 'last_30_days');
            
            switch ($dateRange) {
                case 'today':
                    $startDate = now()->startOfDay();
                    $endDate = now()->endOfDay();
                    break;
                case 'yesterday':
                    $startDate = now()->subDay()->startOfDay();
                    $endDate = now()->subDay()->endOfDay();
                    break;
                case 'last_7_days':
                    $startDate = now()->subDays(7)->startOfDay();
                    $endDate = now()->endOfDay();
                    break;
                case 'last_30_days':
                default:
                    $startDate = now()->subDays(30)->startOfDay();
                    $endDate = now()->endOfDay();
                    break;
                case 'this_month':
                    $startDate = now()->startOfMonth();
                    $endDate = now()->endOfMonth();
                    break;
                case 'last_month':
                    $startDate = now()->subMonth()->startOfMonth();
                    $endDate = now()->subMonth()->endOfMonth();
                    break;
            }

            $analytics = [
                'overview' => [
                    'total_coupons' => Coupon::count(),
                    'active_coupons' => Coupon::active()->count(),
                    'expired_coupons' => Coupon::expired()->count(),
                    'total_usage' => CouponUsage::whereBetween('used_at', [$startDate, $endDate])->count(),
                    'total_discount' => CouponUsage::whereBetween('used_at', [$startDate, $endDate])->sum('discount_amount'),
                    'unique_users' => CouponUsage::whereBetween('used_at', [$startDate, $endDate])->distinct('user_id')->count()
                ],
                
                'top_coupons' => Coupon::withCount(['usages' => function($query) use ($startDate, $endDate) {
                        $query->whereBetween('used_at', [$startDate, $endDate]);
                    }])
                    ->with(['usages' => function($query) use ($startDate, $endDate) {
                        $query->whereBetween('used_at', [$startDate, $endDate]);
                    }])
                    ->orderBy('usages_count', 'desc')
                    ->limit(10)
                    ->get()
                    ->map(function($coupon) {
                        return [
                            'code' => $coupon->code,
                            'name' => $coupon->name,
                            'usage_count' => $coupon->usages_count,
                            'total_discount' => $coupon->usages->sum('discount_amount')
                        ];
                    }),

                'usage_by_type' => Coupon::select('type')
                    ->withCount(['usages' => function($query) use ($startDate, $endDate) {
                        $query->whereBetween('used_at', [$startDate, $endDate]);
                    }])
                    ->groupBy('type')
                    ->get()
                    ->map(function($item) {
                        return [
                            'type' => $item->type,
                            'type_name' => Coupon::TYPES[$item->type] ?? $item->type,
                            'usage_count' => $item->usages_count
                        ];
                    }),

                'daily_usage' => CouponUsage::selectRaw('DATE(used_at) as date, COUNT(*) as count, SUM(discount_amount) as total_discount')
                    ->whereBetween('used_at', [$startDate, $endDate])
                    ->groupBy('date')
                    ->orderBy('date')
                    ->get(),

                'vendor_performance' => CouponUsage::join('coupons', 'coupon_usages.coupon_id', '=', 'coupons.id')
                    ->join('users', 'coupons.vendor_id', '=', 'users.id')
                    ->selectRaw('users.name as vendor_name, COUNT(*) as usage_count, SUM(discount_amount) as total_discount')
                    ->whereBetween('coupon_usages.used_at', [$startDate, $endDate])
                    ->whereNotNull('coupons.vendor_id')
                    ->groupBy('users.id', 'users.name')
                    ->orderBy('usage_count', 'desc')
                    ->limit(10)
                    ->get()
            ];

            return response()->json([
                'success' => true,
                'analytics' => $analytics
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get analytics: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk operations
     */
    public function bulkAction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'action' => 'required|in:activate,deactivate,delete,extend,duplicate',
            'coupon_ids' => 'required|array|min:1',
            'coupon_ids.*' => 'exists:coupons,id',
            'extend_days' => 'required_if:action,extend|integer|min:1|max:365'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $query = Coupon::whereIn('id', $request->coupon_ids);

            // If vendor, only allow their coupons
            if (Auth::user()->role === 'vendor') {
                $query->where('vendor_id', Auth::id());
            }

            $coupons = $query->get();

            if ($coupons->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No valid coupons found'
                ], 404);
            }

            $affectedCount = 0;

            foreach ($coupons as $coupon) {
                switch ($request->action) {
                    case 'activate':
                        $coupon->activate();
                        $affectedCount++;
                        break;
                    
                    case 'deactivate':
                        $coupon->deactivate();
                        $affectedCount++;
                        break;
                    
                    case 'delete':
                        $coupon->delete();
                        $affectedCount++;
                        break;
                    
                    case 'extend':
                        $coupon->extend($request->extend_days);
                        $affectedCount++;
                        break;
                    
                    case 'duplicate':
                        $newCoupon = $coupon->duplicate();
                        $newCoupon->created_by = Auth::id();
                        $newCoupon->save();
                        $affectedCount++;
                        break;
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Successfully {$request->action}d {$affectedCount} coupon(s)"
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to perform bulk action: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate coupon code
     */
    public function generateCode()
    {
        try {
            $code = Coupon::generateUniqueCode();
            
            return response()->json([
                'success' => true,
                'code' => $code
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate code: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export coupons
     */
    public function export(Request $request)
    {
        try {
            $query = Coupon::with(['vendor', 'creator']);

            // Apply filters
            if ($request->filled('status')) {
                switch ($request->status) {
                    case 'active':
                        $query->active();
                        break;
                    case 'expired':
                        $query->expired();
                        break;
                    case 'scheduled':
                        $query->scheduled();
                        break;
                    case 'inactive':
                        $query->where('is_active', false);
                        break;
                }
            }

            if ($request->filled('type')) {
                $query->byType($request->type);
            }

            if (Auth::user()->role === 'vendor') {
                $query->byVendor(Auth::id());
            } elseif ($request->filled('vendor_id')) {
                $query->byVendor($request->vendor_id);
            }

            $coupons = $query->get();

            $csvData = [];
            $csvData[] = [
                'Code', 'Name', 'Type', 'Value', 'Minimum Amount', 'Usage Limit', 
                'Used Count', 'Start Date', 'End Date', 'Status', 'Vendor', 
                'Created At'
            ];

            foreach ($coupons as $coupon) {
                $csvData[] = [
                    $coupon->code,
                    $coupon->name,
                    $coupon->type_name,
                    $coupon->value,
                    $coupon->minimum_amount ?? 'N/A',
                    $coupon->usage_limit ?? 'Unlimited',
                    $coupon->used_count,
                    $coupon->start_date ? $coupon->start_date->format('Y-m-d H:i') : 'N/A',
                    $coupon->end_date ? $coupon->end_date->format('Y-m-d H:i') : 'N/A',
                    $coupon->status_name,
                    $coupon->vendor ? $coupon->vendor->name : 'Global',
                    $coupon->created_at->format('Y-m-d H:i')
                ];
            }

            $filename = 'coupons_' . now()->format('Y_m_d_H_i_s') . '.csv';
            $handle = fopen('php://temp', 'w');

            foreach ($csvData as $row) {
                fputcsv($handle, $row);
            }

            rewind($handle);
            $csv = stream_get_contents($handle);
            fclose($handle);

            return response($csv)
                ->header('Content-Type', 'text/csv')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to export coupons: ' . $e->getMessage()
            ], 500);
        }
    }
}
