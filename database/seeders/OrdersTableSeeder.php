<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Product;
use Carbon\Carbon;

class OrdersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get existing product IDs
        $productIds = Product::pluck('id')->toArray();
        
        if (empty($productIds)) {
            $this->command->info('No products found. Please create products first.');
            return;
        }

        // Get existing user IDs for customers and vendors
        $customerIds = User::where('role', 'customer')->pluck('id')->toArray();
        $vendorIds = User::where('role', 'vendor')->pluck('id')->toArray();
        
        if (empty($customerIds)) {
            $customerIds = User::limit(5)->pluck('id')->toArray();
        }
        
        if (empty($vendorIds)) {
            $vendorIds = User::skip(5)->limit(3)->pluck('id')->toArray();
        }

        for ($i = 1; $i <= 10; $i++) {
            $customerId = $customerIds[array_rand($customerIds)] ?? 1;
            $vendorId = $vendorIds[array_rand($vendorIds)] ?? 2;
            
            $order = Order::create([
                'order_number' => 'ORD-' . date('Y') . '-' . str_pad($i, 6, '0', STR_PAD_LEFT),
                'customer_id' => $customerId,
                'vendor_id' => $vendorId,
                'total_amount' => 0, // Will calculate later
                'status' => ['pending', 'processing', 'shipped', 'delivered', 'cancelled'][array_rand(['pending', 'processing', 'shipped', 'delivered', 'cancelled'])],
                'payment_status' => ['pending', 'paid', 'failed', 'refunded'][array_rand(['pending', 'paid', 'failed', 'refunded'])],
                'shipping_address' => json_encode([
                    'name' => 'John Doe',
                    'phone' => '+880123456789',
                    'address' => '123 Main Street',
                    'city' => 'Dhaka',
                    'postal_code' => '1000',
                    'country' => 'Bangladesh'
                ]),
                'notes' => 'Sample order #' . $i,
                'created_at' => now()->subDays(rand(1, 30)),
                'updated_at' => now(),
            ]);

            // Add 1-3 items per order
            $numItems = rand(1, 3);
            $totalAmount = 0;
            
            for ($j = 1; $j <= $numItems; $j++) {
                $productId = $productIds[array_rand($productIds)];
                $quantity = rand(1, 3);
                $price = rand(20, 100);
                $total = $price * $quantity;
                $totalAmount += $total;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'price' => $price,
                    'total' => $total,
                ]);
            }

            // Update order total
            $order->update(['total_amount' => $totalAmount]);
        }

        $this->command->info('Created 10 sample orders with items.');
    }
}
