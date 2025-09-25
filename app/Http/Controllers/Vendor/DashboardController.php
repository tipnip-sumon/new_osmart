<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\VendorTransfer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $vendor = Auth::user();
        
        // Get vendor statistics
        $stats = [
            'total_products' => Product::where('vendor_id', $vendor->id)->count(),
            'active_products' => Product::where('vendor_id', $vendor->id)->where('is_active', true)->count(),
            'total_orders' => Order::where('vendor_id', $vendor->id)->count(),
            'pending_orders' => Order::where('vendor_id', $vendor->id)->where('status', 'pending')->count(),
            'total_revenue' => Order::where('vendor_id', $vendor->id)->where('status', 'completed')->sum('total_amount'),
            'monthly_revenue' => Order::where('vendor_id', $vendor->id)
                ->where('status', 'completed')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('total_amount'),
        ];

        // Get transfer statistics
        $transferStats = [
            'total_sent' => VendorTransfer::where('vendor_id', $vendor->id)
                ->where('status', 'completed')
                ->sum('net_amount'),
            'pending_count' => VendorTransfer::where('vendor_id', $vendor->id)
                ->whereIn('status', ['pending', 'processing'])
                ->count(),
        ];

        // Get recent orders
        $recent_orders = Order::where('vendor_id', $vendor->id)
            ->with(['customer', 'items.product'])
            ->latest()
            ->take(5)
            ->get();

        // Get low stock products
        $low_stock_products = Product::where('vendor_id', $vendor->id)
            ->where('stock_quantity', '<=', 10)
            ->where('track_quantity', true)
            ->take(5)
            ->get();

        return view('vendor.dashboard', compact('vendor', 'stats', 'transferStats', 'recent_orders', 'low_stock_products'));
    }

    public function refreshBalance()
    {
        $vendor = Auth::user();
        
        $transferStats = [
            'total_sent' => VendorTransfer::where('vendor_id', $vendor->id)
                ->where('status', 'completed')
                ->sum('net_amount'),
            'pending_count' => VendorTransfer::where('vendor_id', $vendor->id)
                ->whereIn('status', ['pending', 'processing'])
                ->count(),
        ];

        return response()->json([
            'success' => true,
            'balance' => number_format($vendor->deposit_wallet ?? 0, 2),
            'total_sent' => number_format($transferStats['total_sent'], 2),
            'pending_count' => $transferStats['pending_count']
        ]);
    }

    public function profile()
    {
        // Check if user is vendor
        if (!Auth::check() || Auth::user()->role !== 'vendor') {
            abort(403, 'Access denied. Vendors only.');
        }
        
        $vendor = Auth::user();
        return view('vendor.profile', compact('vendor'));
    }

    public function updateProfile(Request $request)
    {
        // Check if user is vendor
        if (!Auth::check() || Auth::user()->role !== 'vendor') {
            abort(403, 'Access denied. Vendors only.');
        }
        
        $vendor = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $vendor->id,
            'phone' => 'nullable|string|max:20',
            'shop_name' => 'required|string|max:255',
            'shop_description' => 'nullable|string',
            'shop_address' => 'nullable|string',
            'business_license' => 'nullable|string|max:100',
            'tax_id' => 'nullable|string|max:100',
        ]);

        $vendor->update($request->only([
            'name', 'email', 'phone', 'shop_name', 'shop_description', 
            'shop_address', 'business_license', 'tax_id'
        ]));

        return redirect()->route('vendor.profile')
            ->with('success', 'Profile updated successfully!');
    }
}
