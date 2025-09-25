<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function dashboard()
    {
        // Sample dashboard statistics
        $stats = [
            'total_sales' => 125600.50,
            'total_orders' => 1245,
            'total_products' => 89,
            'total_vendors' => 15,
            'total_customers' => 3420,
            'pending_orders' => 23,
            'low_stock_products' => 12,
            'new_vendors' => 3
        ];

        // Recent orders sample data
        $recentOrders = collect([
            (object) [
                'id' => 'ORD-001',
                'customer' => 'John Doe',
                'vendor' => 'TechStore',
                'amount' => 299.99,
                'status' => 'pending',
                'date' => '2024-01-15'
            ],
            (object) [
                'id' => 'ORD-002',
                'customer' => 'Jane Smith',
                'vendor' => 'FashionHub',
                'amount' => 149.50,
                'status' => 'processing',
                'date' => '2024-01-15'
            ],
            (object) [
                'id' => 'ORD-003',
                'customer' => 'Mike Johnson',
                'vendor' => 'HomeDecor',
                'amount' => 89.99,
                'status' => 'shipped',
                'date' => '2024-01-14'
            ],
            (object) [
                'id' => 'ORD-004',
                'customer' => 'Sarah Wilson',
                'vendor' => 'TechStore',
                'amount' => 199.99,
                'status' => 'delivered',
                'date' => '2024-01-14'
            ],
            (object) [
                'id' => 'ORD-005',
                'customer' => 'David Brown',
                'vendor' => 'FashionHub',
                'amount' => 75.00,
                'status' => 'cancelled',
                'date' => '2024-01-13'
            ]
        ]);

        // Top selling products
        $topProducts = collect([
            (object) [
                'name' => 'Wireless Bluetooth Headphones',
                'vendor' => 'TechStore',
                'sales' => 156,
                'revenue' => 23400.00,
                'image' => 'headphones.jpg'
            ],
            (object) [
                'name' => 'Summer Dress Collection',
                'vendor' => 'FashionHub',
                'sales' => 89,
                'revenue' => 13335.00,
                'image' => 'dress.jpg'
            ],
            (object) [
                'name' => 'Home Office Chair',
                'vendor' => 'HomeDecor',
                'sales' => 67,
                'revenue' => 20100.00,
                'image' => 'chair.jpg'
            ],
            (object) [
                'name' => 'Smart Phone Case',
                'vendor' => 'TechStore',
                'sales' => 234,
                'revenue' => 7020.00,
                'image' => 'phonecase.jpg'
            ],
            (object) [
                'name' => 'Kitchen Appliance Set',
                'vendor' => 'HomeDecor',
                'sales' => 34,
                'revenue' => 10200.00,
                'image' => 'kitchen.jpg'
            ]
        ]);

        // Monthly sales data for chart
        $monthlySales = [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            'data' => [12500, 15600, 18900, 21200, 24300, 27800, 30200, 28900, 32100, 35600, 38900, 42100]
        ];

        return view('admin.dashboard', compact(
            'stats', 
            'recentOrders', 
            'topProducts', 
            'monthlySales'
        ));
    }
}
