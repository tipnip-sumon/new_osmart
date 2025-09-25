<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Display sales reports for vendor.
     */
    public function index(Request $request)
    {
        if (Auth::user()->role !== 'vendor') {
            abort(403, 'Access denied. Vendor role required.');
        }

        // Get date range
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth());

        // Sales overview
        $salesData = $this->getSalesData($startDate, $endDate);
        
        // Top selling products
        $topProducts = $this->getTopSellingProducts($startDate, $endDate);
        
        // Monthly sales chart data
        $monthlySales = $this->getMonthlySalesData();
        
        // Product performance
        $productPerformance = $this->getProductPerformance($startDate, $endDate);

        return view('vendor.reports.index', compact(
            'salesData', 
            'topProducts', 
            'monthlySales', 
            'productPerformance',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Generate sales data for the given period.
     */
    private function getSalesData($startDate, $endDate)
    {
        $vendorId = Auth::id();

        // Total sales amount
        $totalSales = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('products.vendor_id', $vendorId)
            ->where('orders.status', '!=', 'cancelled')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->sum(DB::raw('order_items.price * order_items.quantity'));

        // Total orders count
        $totalOrders = DB::table('orders')
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('products.vendor_id', $vendorId)
            ->where('orders.status', '!=', 'cancelled')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->distinct('orders.id')
            ->count('orders.id');

        // Total products sold
        $totalProductsSold = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('products.vendor_id', $vendorId)
            ->where('orders.status', '!=', 'cancelled')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->sum('order_items.quantity');

        // Average order value
        $averageOrderValue = $totalOrders > 0 ? $totalSales / $totalOrders : 0;

        return [
            'total_sales' => $totalSales,
            'total_orders' => $totalOrders,
            'total_products_sold' => $totalProductsSold,
            'average_order_value' => $averageOrderValue
        ];
    }

    /**
     * Get top selling products.
     */
    private function getTopSellingProducts($startDate, $endDate, $limit = 10)
    {
        return DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->select(
                'products.id',
                'products.name',
                'products.sku',
                'products.price as product_price',
                DB::raw('SUM(order_items.quantity) as total_sold'),
                DB::raw('SUM(order_items.price * order_items.quantity) as total_revenue')
            )
            ->where('products.vendor_id', Auth::id())
            ->where('orders.status', '!=', 'cancelled')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->groupBy('products.id', 'products.name', 'products.sku', 'products.price')
            ->orderBy('total_sold', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get monthly sales data for chart.
     */
    private function getMonthlySalesData($months = 12)
    {
        $vendorId = Auth::id();
        $data = [];

        for ($i = $months - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $startOfMonth = $date->copy()->startOfMonth();
            $endOfMonth = $date->copy()->endOfMonth();

            $sales = DB::table('order_items')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->where('products.vendor_id', $vendorId)
                ->where('orders.status', '!=', 'cancelled')
                ->whereBetween('orders.created_at', [$startOfMonth, $endOfMonth])
                ->sum(DB::raw('order_items.price * order_items.quantity'));

            $data[] = [
                'month' => $date->format('M Y'),
                'sales' => $sales ?: 0
            ];
        }

        return collect($data);
    }

    /**
     * Get product performance data.
     */
    private function getProductPerformance($startDate, $endDate)
    {
        return Product::where('vendor_id', Auth::id())
            ->select('id', 'name', 'sku', 'price', 'stock_quantity', 'status')
            ->withCount(['orderItems as total_sold' => function($query) use ($startDate, $endDate) {
                $query->join('orders', 'order_items.order_id', '=', 'orders.id')
                      ->where('orders.status', '!=', 'cancelled')
                      ->whereBetween('orders.created_at', [$startDate, $endDate]);
            }])
            ->withSum(['orderItems as total_revenue' => function($query) use ($startDate, $endDate) {
                $query->join('orders', 'order_items.order_id', '=', 'orders.id')
                      ->where('orders.status', '!=', 'cancelled')
                      ->whereBetween('orders.created_at', [$startDate, $endDate]);
            }], DB::raw('order_items.price * order_items.quantity'))
            ->orderBy('total_sold', 'desc')
            ->get();
    }

    /**
     * Export sales report to CSV.
     */
    public function exportSales(Request $request)
    {
        if (Auth::user()->role !== 'vendor') {
            abort(403, 'Access denied. Vendor role required.');
        }

        $startDate = $request->get('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth());

        $orders = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->select(
                'orders.order_number',
                'orders.created_at',
                'users.name as customer_name',
                'users.email as customer_email',
                'products.name as product_name',
                'products.sku',
                'order_items.quantity',
                'order_items.price',
                DB::raw('order_items.price * order_items.quantity as total')
            )
            ->where('products.vendor_id', Auth::id())
            ->where('orders.status', '!=', 'cancelled')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->orderBy('orders.created_at', 'desc')
            ->get();

        $filename = 'vendor_sales_report_' . now()->format('Y_m_d_H_i_s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($orders) {
            $file = fopen('php://output', 'w');
            
            // Add CSV headers
            fputcsv($file, [
                'Order Number',
                'Date',
                'Customer Name',
                'Customer Email',
                'Product Name',
                'SKU',
                'Quantity',
                'Price',
                'Total'
            ]);

            // Add data rows
            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->order_number,
                    $order->created_at,
                    $order->customer_name,
                    $order->customer_email,
                    $order->product_name,
                    $order->sku,
                    $order->quantity,
                    $order->price,
                    $order->total
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
