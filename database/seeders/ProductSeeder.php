<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\User;
use App\Models\MlmProductSetting;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if products already exist
        if (Product::count() > 0) {
            $this->command->info('Products already exist. Skipping seeding.');
            return;
        }

        // Get necessary data
        $categories = Category::all();
        $brands = Brand::all();
        $vendors = User::where('role', 'vendor')->get();
        
        if ($categories->isEmpty() || $brands->isEmpty()) {
            $this->command->error('Categories and Brands must be seeded first!');
            return;
        }

        // Use existing vendor from UserSeeder (Demo Vendor)
        if ($vendors->isEmpty()) {
            $this->command->error('No vendors found! Please run UserSeeder first.');
            return;
        }

        $products = [
            // Electronics Category
            [
                'name' => 'iPhone 15 Pro Max',
                'description' => 'The most advanced iPhone with titanium design, A17 Pro chip, and Pro camera system.',
                'short_description' => 'Latest iPhone with titanium design and advanced camera system.',
                'price' => 1199.99,
                'sale_price' => 1099.99,
                'cost_price' => 800.00,
                'wholesale_price' => 950.00,
                'compare_price' => 1299.99,
                'stock_quantity' => 50,
                'min_stock_level' => 10,
                'max_stock_level' => 200,
                'weight' => 0.221,
                'length' => 159.9,
                'width' => 76.7,
                'height' => 8.25,
                'brand_name' => 'Apple',
                'category_name' => 'Smartphones',
                'size' => '6.7 inch',
                'color' => 'Titanium Blue',
                'material' => 'Titanium',
                'barcode' => '1234567890123',
                'model_number' => 'iPhone15ProMax',
                'warranty_period' => '12 months',
                'specifications' => [
                    'Display' => '6.7-inch Super Retina XDR OLED',
                    'Processor' => 'A17 Pro chip',
                    'Storage' => '256GB',
                    'Camera' => '48MP + 12MP + 12MP',
                    'Battery' => '4441 mAh',
                    'OS' => 'iOS 17'
                ],
                'features' => [
                    'Face ID',
                    'Wireless Charging',
                    'Water Resistant',
                    'Night Mode',
                    '5G Connectivity'
                ],
                'is_featured' => true,
                'is_mlm' => true,
                'mlm_settings' => [
                    'pv_points' => 100.00,
                    'bv_points' => 150.00,
                    'generates_commission' => true,
                    'is_autoship_eligible' => true,
                    'counts_towards_qualification' => true,
                ]
            ],
            [
                'name' => 'Samsung Galaxy S24 Ultra',
                'description' => 'Flagship Samsung smartphone with S Pen, advanced camera system, and powerful performance.',
                'short_description' => 'Premium Samsung smartphone with S Pen and quad camera.',
                'price' => 1299.99,
                'sale_price' => 1199.99,
                'cost_price' => 850.00,
                'wholesale_price' => 1000.00,
                'compare_price' => 1399.99,
                'stock_quantity' => 40,
                'min_stock_level' => 8,
                'max_stock_level' => 150,
                'weight' => 0.233,
                'length' => 162.3,
                'width' => 79.0,
                'height' => 8.6,
                'brand_name' => 'Samsung',
                'category_name' => 'Smartphones',
                'size' => '6.8 inch',
                'color' => 'Phantom Black',
                'material' => 'Aluminum',
                'barcode' => '1234567890124',
                'model_number' => 'SM-S928U',
                'warranty_period' => '12 months',
                'specifications' => [
                    'Display' => '6.8-inch Dynamic AMOLED 2X',
                    'Processor' => 'Snapdragon 8 Gen 3',
                    'Storage' => '512GB',
                    'Camera' => '200MP + 50MP + 12MP + 10MP',
                    'Battery' => '5000 mAh',
                    'OS' => 'Android 14'
                ],
                'is_featured' => true,
                'is_mlm' => true,
                'mlm_settings' => [
                    'pv_points' => 120.00,
                    'bv_points' => 180.00,
                    'generates_commission' => true,
                    'is_autoship_eligible' => true,
                    'counts_towards_qualification' => true,
                ]
            ],
            [
                'name' => 'MacBook Pro 16-inch M3 Max',
                'description' => 'Professional laptop with M3 Max chip, stunning Liquid Retina XDR display, and all-day battery life.',
                'short_description' => 'High-performance MacBook Pro with M3 Max chip.',
                'price' => 3999.99,
                'sale_price' => 3799.99,
                'cost_price' => 2800.00,
                'wholesale_price' => 3200.00,
                'compare_price' => 4299.99,
                'stock_quantity' => 25,
                'min_stock_level' => 5,
                'max_stock_level' => 100,
                'weight' => 2.15,
                'length' => 355.7,
                'width' => 248.1,
                'height' => 16.8,
                'brand_name' => 'Apple',
                'category_name' => 'Laptops',
                'size' => '16 inch',
                'color' => 'Space Gray',
                'material' => 'Aluminum',
                'barcode' => '1234567890125',
                'model_number' => 'MRW13LL/A',
                'warranty_period' => '12 months',
                'specifications' => [
                    'Display' => '16.2-inch Liquid Retina XDR',
                    'Processor' => 'Apple M3 Max',
                    'Storage' => '1TB SSD',
                    'Memory' => '36GB Unified Memory',
                    'Graphics' => '40-core GPU',
                    'Battery' => 'Up to 22 hours'
                ],
                'is_featured' => true,
                'is_mlm' => true,
                'mlm_settings' => [
                    'pv_points' => 300.00,
                    'bv_points' => 450.00,
                    'generates_commission' => true,
                    'is_autoship_eligible' => false,
                    'minimum_rank_required' => 'Bronze',
                    'counts_towards_qualification' => true,
                ]
            ],
            // Clothing Category
            [
                'name' => 'Nike Air Max 270',
                'description' => 'Lifestyle shoe with large Max Air unit for all-day comfort and style.',
                'short_description' => 'Comfortable lifestyle shoe with Max Air technology.',
                'price' => 150.00,
                'sale_price' => 129.99,
                'cost_price' => 75.00,
                'wholesale_price' => 95.00,
                'compare_price' => 170.00,
                'stock_quantity' => 100,
                'min_stock_level' => 20,
                'max_stock_level' => 300,
                'weight' => 0.5,
                'brand_name' => 'Nike',
                'category_name' => 'Shoes',
                'size' => 'US 10',
                'color' => 'Black/White',
                'material' => 'Synthetic/Mesh',
                'barcode' => '1234567890126',
                'model_number' => 'AH8050-002',
                'warranty_period' => '6 months',
                'is_featured' => false,
                'is_mlm' => true,
                'mlm_settings' => [
                    'pv_points' => 50.00,
                    'bv_points' => 75.00,
                    'generates_commission' => true,
                    'is_autoship_eligible' => true,
                    'counts_towards_qualification' => true,
                ]
            ],
            [
                'name' => 'Adidas Ultraboost 22',
                'description' => 'High-performance running shoe with responsive BOOST midsole.',
                'short_description' => 'Premium running shoe with BOOST technology.',
                'price' => 180.00,
                'sale_price' => 159.99,
                'cost_price' => 90.00,
                'wholesale_price' => 115.00,
                'compare_price' => 200.00,
                'stock_quantity' => 80,
                'min_stock_level' => 15,
                'max_stock_level' => 250,
                'weight' => 0.45,
                'brand_name' => 'Adidas',
                'category_name' => 'Shoes',
                'size' => 'US 9.5',
                'color' => 'Core Black',
                'material' => 'Primeknit/Boost',
                'barcode' => '1234567890127',
                'model_number' => 'GZ0127',
                'warranty_period' => '6 months',
                'is_featured' => false,
                'is_mlm' => true,
                'mlm_settings' => [
                    'pv_points' => 60.00,
                    'bv_points' => 90.00,
                    'generates_commission' => true,
                    'is_autoship_eligible' => true,
                    'counts_towards_qualification' => true,
                ]
            ],
            // Home & Garden Category
            [
                'name' => 'Sony WH-1000XM5 Headphones',
                'description' => 'Industry-leading noise canceling wireless headphones with premium sound quality.',
                'short_description' => 'Premium noise-canceling wireless headphones.',
                'price' => 399.99,
                'sale_price' => 349.99,
                'cost_price' => 200.00,
                'wholesale_price' => 250.00,
                'compare_price' => 450.00,
                'stock_quantity' => 60,
                'min_stock_level' => 12,
                'max_stock_level' => 200,
                'weight' => 0.25,
                'brand_name' => 'Sony',
                'category_name' => 'Audio',
                'color' => 'Black',
                'material' => 'Plastic/Leather',
                'barcode' => '1234567890128',
                'model_number' => 'WH1000XM5/B',
                'warranty_period' => '12 months',
                'specifications' => [
                    'Driver' => '30mm',
                    'Frequency Response' => '4Hz-40kHz',
                    'Battery Life' => '30 hours',
                    'Connectivity' => 'Bluetooth 5.2',
                    'Noise Canceling' => 'Yes'
                ],
                'is_featured' => true,
                'is_mlm' => true,
                'mlm_settings' => [
                    'pv_points' => 80.00,
                    'bv_points' => 120.00,
                    'generates_commission' => true,
                    'is_autoship_eligible' => true,
                    'counts_towards_qualification' => true,
                ]
            ],
            // Standard product without MLM
            [
                'name' => 'Basic Cotton T-Shirt',
                'description' => 'Comfortable cotton t-shirt for everyday wear.',
                'short_description' => 'Basic cotton t-shirt in various colors.',
                'price' => 19.99,
                'sale_price' => 16.99,
                'cost_price' => 8.00,
                'wholesale_price' => 12.00,
                'compare_price' => 25.00,
                'stock_quantity' => 200,
                'min_stock_level' => 50,
                'max_stock_level' => 500,
                'weight' => 0.2,
                'category_name' => 'T-Shirts',
                'size' => 'Medium',
                'color' => 'Navy Blue',
                'material' => '100% Cotton',
                'barcode' => '1234567890129',
                'is_featured' => false,
                'is_mlm' => false,
            ],
        ];

        foreach ($products as $productData) {
            // Find or create category
            $category = $categories->where('name', $productData['category_name'])->first();
            if (!$category) {
                $category = $categories->first(); // Fallback to first category
            }

            // Find brand
            $brand = null;
            if (isset($productData['brand_name'])) {
                $brand = $brands->where('name', $productData['brand_name'])->first();
            }

            // Select random vendor
            $vendor = $vendors->random();

            // Prepare product data
            $product = Product::create([
                'vendor_id' => $vendor->id,
                'category_id' => $category->id,
                'brand_id' => $brand ? $brand->id : null,
                'name' => $productData['name'],
                'slug' => Str::slug($productData['name']),
                'sku' => strtoupper(Str::random(3)) . '-' . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT),
                'description' => $productData['description'],
                'short_description' => $productData['short_description'],
                'price' => $productData['price'],
                'sale_price' => $productData['sale_price'] ?? null,
                'cost_price' => $productData['cost_price'] ?? null,
                'wholesale_price' => $productData['wholesale_price'] ?? null,
                'compare_price' => $productData['compare_price'] ?? null,
                'stock_quantity' => $productData['stock_quantity'],
                'min_stock_level' => $productData['min_stock_level'],
                'max_stock_level' => $productData['max_stock_level'],
                'weight' => $productData['weight'] ?? null,
                'length' => $productData['length'] ?? null,
                'width' => $productData['width'] ?? null,
                'height' => $productData['height'] ?? null,
                'size' => $productData['size'] ?? null,
                'color' => $productData['color'] ?? null,
                'material' => $productData['material'] ?? null,
                'barcode' => $productData['barcode'] ?? null,
                'model_number' => $productData['model_number'] ?? null,
                'warranty_period' => $productData['warranty_period'] ?? null,
                'specifications' => $productData['specifications'] ?? null,
                'features' => $productData['features'] ?? null,
                'is_featured' => $productData['is_featured'] ?? false,
                'is_active' => true,
                'view_count' => rand(10, 1000),
                'purchase_count' => rand(5, 100),
                'average_rating' => rand(35, 50) / 10, // 3.5 to 5.0
                'review_count' => rand(5, 200),
                'images' => [
                    'products/sample-image-1.jpg',
                    'products/sample-image-2.jpg',
                    'products/sample-image-3.jpg'
                ],
                'meta_title' => $productData['name'] . ' - Best Price Online',
                'meta_description' => substr($productData['description'], 0, 155),
                'created_at' => now()->subDays(rand(1, 30)),
            ]);

            // Create MLM settings if it's an MLM product
            if (isset($productData['is_mlm']) && $productData['is_mlm'] && isset($productData['mlm_settings'])) {
                $mlmSettings = $productData['mlm_settings'];
                $mlmSettings['product_id'] = $product->id;
                
                MlmProductSetting::create($mlmSettings);
            }
        }

        $this->command->info('Products seeded successfully!');
    }
}
