<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CouponController extends Controller
{
    /**
     * Display a listing of coupons
     */
    public function index(Request $request)
    {
        $query = Coupon::with(['creator']);
        
        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('code', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }
        
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true)
                      ->where('start_date', '<=', now())
                      ->where('end_date', '>=', now());
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            } elseif ($request->status === 'expired') {
                $query->where('end_date', '<', now());
            }
        }
        
        // Get paginated results
        $coupons = $query->orderBy('created_at', 'desc')->paginate(15);
        
        // Calculate statistics
        $stats = [
            'total' => Coupon::count(),
            'active' => Coupon::where('is_active', true)
                             ->where('start_date', '<=', now())
                             ->where('end_date', '>=', now())
                             ->count(),
            'expired' => Coupon::where('end_date', '<', now())->count(),
            'scheduled' => Coupon::where('start_date', '>', now())->count(),
            'total_usage' => CouponUsage::count(),
            'total_discount' => CouponUsage::sum('discount_amount') ?? 0
        ];
        
        return view('admin.coupons.index', compact('coupons', 'stats'));
    }

    /**
     * Show the form for creating a new coupon
     */
    public function create()
    {
        $categories = Category::all();
        
        // Handle missing products table gracefully
        try {
            $products = Product::all();
        } catch (\Exception $e) {
            $products = collect([]);
        }
        
        $users = User::all();
        
        return view('admin.coupons.create', compact('categories', 'products', 'users'));
    }

    /**
     * Store a newly created coupon
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:coupons,code',
            'description' => 'nullable|string|max:1000',
            'type' => 'required|in:percentage,fixed,buy_x_get_y,free_shipping',
            'value' => 'required|numeric|min:0',
            'minimum_amount' => 'nullable|numeric|min:0',
            'maximum_discount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'usage_limit_per_user' => 'nullable|integer|min:1',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'is_active' => 'required|boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();
            
            $coupon = new Coupon();
            $coupon->name = $request->name;
            $coupon->code = strtoupper($request->code);
            $coupon->description = $request->description;
            $coupon->type = $request->type;
            $coupon->value = $request->value;
            $coupon->minimum_amount = $request->minimum_amount;
            $coupon->maximum_discount = $request->maximum_discount;
            $coupon->usage_limit = $request->usage_limit;
            $coupon->usage_limit_per_user = $request->usage_limit_per_user;
            $coupon->start_date = Carbon::parse($request->start_date);
            $coupon->end_date = Carbon::parse($request->end_date);
            $coupon->is_active = $request->is_active;
            $coupon->created_by = Auth::id();
            $coupon->save();
            
            DB::commit();
            
            return redirect()->route('admin.coupons.index')
                ->with('success', 'Coupon created successfully!');
                
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Failed to create coupon: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified coupon
     */
    public function show(Coupon $coupon)
    {
        $coupon->load(['creator', 'usages.user', 'usages.order']);
        
        // Usage statistics
        $usageStats = [
            'total_usage' => $coupon->usages->count(),
            'total_discount' => $coupon->usages->sum('discount_amount'),
            'unique_users' => $coupon->usages->unique('user_id')->count(),
            'recent_usage' => $coupon->usages->take(10),
            'usage_by_month' => $coupon->usages()
                ->selectRaw('MONTH(created_at) as month, COUNT(*) as usage_count, SUM(discount_amount) as total_discount')
                ->whereYear('created_at', now()->year)
                ->groupBy('month')
                ->get()
        ];
        
        return view('admin.coupons.show', compact('coupon', 'usageStats'));
    }

    /**
     * Show the form for editing the specified coupon
     */
    public function edit(Coupon $coupon)
    {
        $categories = Category::where('is_active', 1)->get();
        
        // Handle missing products table gracefully
        try {
            $products = Product::where('is_active', 1)->get();
        } catch (\Exception $e) {
            $products = collect([]);
        }
        
        $users = User::where('is_active', 1)->get();
        
        return view('admin.coupons.edit', compact('coupon', 'categories', 'products', 'users'));
    }

    /**
     * Update the specified coupon
     */
    public function update(Request $request, Coupon $coupon)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:coupons,code,' . $coupon->id,
            'description' => 'nullable|string|max:1000',
            'type' => 'required|in:percentage,fixed,buy_x_get_y,free_shipping',
            'value' => 'required|numeric|min:0',
            'minimum_amount' => 'nullable|numeric|min:0',
            'maximum_discount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'usage_limit_per_user' => 'nullable|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_active' => 'required|boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();
            
            $coupon->name = $request->name;
            $coupon->code = strtoupper($request->code);
            $coupon->description = $request->description;
            $coupon->type = $request->type;
            $coupon->value = $request->value;
            $coupon->minimum_amount = $request->minimum_amount;
            $coupon->maximum_discount = $request->maximum_discount;
            $coupon->usage_limit = $request->usage_limit;
            $coupon->usage_limit_per_user = $request->usage_limit_per_user;
            $coupon->start_date = Carbon::parse($request->start_date);
            $coupon->end_date = Carbon::parse($request->end_date);
            $coupon->is_active = $request->is_active;
            $coupon->save();
            
            DB::commit();
            
            return redirect()->route('admin.coupons.index')
                ->with('success', 'Coupon updated successfully!');
                
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Failed to update coupon: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified coupon
     */
    public function destroy(Coupon $coupon)
    {
        try {
            // Check if coupon has been used
            if ($coupon->usages()->count() > 0) {
                return redirect()->back()
                    ->with('error', 'Cannot delete coupon that has been used. Consider deactivating it instead.');
            }
            
            $coupon->delete();
            
            return redirect()->route('admin.coupons.index')
                ->with('success', 'Coupon deleted successfully!');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete coupon: ' . $e->getMessage());
        }
    }

    /**
     * Bulk actions for coupons
     */
    public function bulkAction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'action' => 'required|in:activate,deactivate,delete',
            'coupon_ids' => 'required|array|min:1',
            'coupon_ids.*' => 'exists:coupons,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid data provided'
            ], 422);
        }

        try {
            DB::beginTransaction();
            
            $action = $request->action;
            $couponIds = $request->coupon_ids;
            $affectedCount = 0;
            
            switch ($action) {
                case 'activate':
                    $affectedCount = Coupon::whereIn('id', $couponIds)
                        ->update(['is_active' => 1]);
                    $message = "Successfully activated {$affectedCount} coupons.";
                    break;
                    
                case 'deactivate':
                    $affectedCount = Coupon::whereIn('id', $couponIds)
                        ->update(['is_active' => 0]);
                    $message = "Successfully deactivated {$affectedCount} coupons.";
                    break;
                    
                case 'delete':
                    // Check if any coupons have been used
                    $usedCoupons = Coupon::whereIn('id', $couponIds)
                        ->whereHas('usages')
                        ->count();
                        
                    if ($usedCoupons > 0) {
                        return response()->json([
                            'success' => false,
                            'message' => "Cannot delete {$usedCoupons} coupons that have been used."
                        ], 422);
                    }
                    
                    $affectedCount = Coupon::whereIn('id', $couponIds)->delete();
                    $message = "Successfully deleted {$affectedCount} coupons.";
                    break;
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => $message
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
     * Generate unique coupon code
     */
    public function generateCode(Request $request)
    {
        $length = $request->get('length', 8);
        $prefix = $request->get('prefix', '');
        
        do {
            $code = $prefix . strtoupper(Str::random($length));
        } while (Coupon::where('code', $code)->exists());
        
        return response()->json([
            'success' => true,
            'code' => $code
        ]);
    }

    /**
     * Validate coupon code
     */
    public function validateCode(Request $request)
    {
        $code = $request->get('code');
        $userId = $request->get('user_id');
        $cartTotal = $request->get('cart_total', 0);
        
        $coupon = Coupon::where('code', strtoupper($code))->first();
        
        if (!$coupon) {
            return response()->json([
                'valid' => false,
                'message' => 'Invalid coupon code.'
            ]);
        }
        
        // Check if coupon is active
        if (!$coupon->is_active) {
            return response()->json([
                'valid' => false,
                'message' => 'This coupon is not active.'
            ]);
        }
        
        // Check date validity
        $now = now();
        if ($now < $coupon->start_date || $now > $coupon->end_date) {
            return response()->json([
                'valid' => false,
                'message' => 'This coupon has expired or is not yet active.'
            ]);
        }
        
        // Check minimum amount
        if ($coupon->minimum_amount && $cartTotal < $coupon->minimum_amount) {
            return response()->json([
                'valid' => false,
                'message' => "Minimum order amount of {$coupon->minimum_amount} required."
            ]);
        }
        
        // Check usage limit
        if ($coupon->usage_limit && $coupon->usages()->count() >= $coupon->usage_limit) {
            return response()->json([
                'valid' => false,
                'message' => 'This coupon has reached its usage limit.'
            ]);
        }
        
        // Check per-user usage limit
        if ($userId && $coupon->usage_limit_per_user) {
            $userUsage = $coupon->usages()->where('user_id', $userId)->count();
            if ($userUsage >= $coupon->usage_limit_per_user) {
                return response()->json([
                    'valid' => false,
                    'message' => 'You have reached the usage limit for this coupon.'
                ]);
            }
        }
        
        // Calculate discount
        $discount = $this->calculateDiscount($coupon, $cartTotal);
        
        return response()->json([
            'valid' => true,
            'coupon' => $coupon,
            'discount_amount' => $discount,
            'message' => 'Coupon applied successfully!'
        ]);
    }

    /**
     * Calculate discount amount
     */
    private function calculateDiscount(Coupon $coupon, $cartTotal)
    {
        $discount = 0;
        
        switch ($coupon->type) {
            case 'percentage':
                $discount = ($cartTotal * $coupon->value) / 100;
                if ($coupon->maximum_discount && $discount > $coupon->maximum_discount) {
                    $discount = $coupon->maximum_discount;
                }
                break;
                
            case 'fixed':
                $discount = min($coupon->value, $cartTotal);
                break;
                
            case 'free_shipping':
                // This would typically be handled in shipping calculation
                $discount = 0; // Shipping cost would be set to 0
                break;
                
            case 'buy_x_get_y':
                // This would require more complex cart analysis
                // For now, return fixed discount value
                $discount = $coupon->value;
                break;
        }
        
        return round($discount, 2);
    }

    /**
     * Export coupons
     */
    public function export(Request $request)
    {
        $format = $request->get('format', 'csv');
        
        $coupons = Coupon::with(['creator', 'usages'])
            ->when($request->filled('status'), function($q) use ($request) {
                $q->where('is_active', $request->status === 'active' ? 1 : 0);
            })
            ->get();
        
        if ($format === 'csv') {
            return $this->exportToCsv($coupons);
        }
        
        return redirect()->back()->with('error', 'Invalid export format.');
    }

    /**
     * Export to CSV
     */
    private function exportToCsv($coupons)
    {
        $filename = 'coupons_' . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];
        
        $callback = function() use ($coupons) {
            $file = fopen('php://output', 'w');
            
            fputcsv($file, [
                'ID', 'Name', 'Code', 'Type', 'Value', 'Minimum Amount',
                'Usage Limit', 'Used Count', 'Status', 'Starts At', 'Expires At'
            ]);
            
            foreach ($coupons as $coupon) {
                fputcsv($file, [
                    $coupon->id,
                    $coupon->name,
                    $coupon->code,
                    $coupon->type,
                    $coupon->value,
                    $coupon->minimum_amount,
                    $coupon->usage_limit,
                    $coupon->usages->count(),
                    $coupon->is_active ? 'Active' : 'Inactive',
                    $coupon->start_date->format('Y-m-d'),
                    $coupon->end_date->format('Y-m-d')
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get coupon analytics
     */
    public function analytics(Request $request)
    {
        $period = $request->get('period', '30'); // days
        $startDate = now()->subDays($period);
        
        $analytics = [
            'total_coupons' => Coupon::count(),
            'active_coupons' => Coupon::where('is_active', 1)->count(),
            'total_usage' => CouponUsage::where('created_at', '>=', $startDate)->count(),
            'total_discount' => CouponUsage::where('created_at', '>=', $startDate)->sum('discount_amount'),
            'top_coupons' => Coupon::withCount('usages')
                ->orderBy('usages_count', 'desc')
                ->take(10)
                ->get(),
            'usage_by_day' => CouponUsage::selectRaw('DATE(created_at) as date, COUNT(*) as count, SUM(discount_amount) as total_discount')
                ->where('created_at', '>=', $startDate)
                ->groupBy('date')
                ->orderBy('date')
                ->get(),
            'discount_by_type' => Coupon::selectRaw('type, COUNT(*) as count, SUM(COALESCE((SELECT SUM(discount_amount) FROM coupon_usages WHERE coupon_id = coupons.id), 0)) as total_discount')
                ->groupBy('type')
                ->get()
        ];
        
        return view('admin.coupons.analytics', compact('analytics', 'period'));
    }
}
