<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateProductSpecifications extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'products:update-specifications 
                           {--product=* : Specific product IDs to update}
                           {--missing-only : Only update products with missing specifications}
                           {--dry-run : Show what would be updated without making changes}';

    /**
     * The console command description.
     */
    protected $description = 'Update product specifications with sample data or identify missing specifications';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Product Specifications Update Tool');
        $this->info('=====================================');

        $productIds = $this->option('product');
        $missingOnly = $this->option('missing-only');
        $dryRun = $this->option('dry-run');

        // Build query
        $query = Product::query();

        if (!empty($productIds)) {
            $query->whereIn('id', $productIds);
            $this->info('Filtering products by IDs: ' . implode(', ', $productIds));
        }

        if ($missingOnly) {
            $query->where(function ($q) {
                $q->whereNull('specifications')
                  ->orWhereNull('features')
                  ->orWhereNull('weight')
                  ->orWhereNull('material')
                  ->orWhereNull('warranty_period');
            });
            $this->info('Only processing products with missing specifications');
        }

        $products = $query->get();

        if ($products->isEmpty()) {
            $this->warn('No products found matching the criteria.');
            return;
        }

        $this->info("Found {$products->count()} products to process");

        if ($dryRun) {
            $this->warn('DRY RUN MODE - No changes will be made');
        }

        $updated = 0;
        $skipped = 0;

        foreach ($products as $product) {
            $this->line("\nProcessing: {$product->name} (ID: {$product->id})");
            
            $updates = [];
            $hasUpdates = false;

            // Check and update specifications
            if (!$product->specifications || empty($product->specifications)) {
                $specifications = $this->generateSpecifications($product);
                $updates['specifications'] = $specifications;
                $hasUpdates = true;
                $this->info('  - Adding technical specifications');
            }

            // Check and update features
            if (!$product->features || empty($product->features)) {
                $features = $this->generateFeatures($product);
                $updates['features'] = $features;
                $hasUpdates = true;
                $this->info('  - Adding product features');
            }

            // Check and update included items
            if (!$product->included_items || empty($product->included_items)) {
                $includedItems = $this->generateIncludedItems($product);
                $updates['included_items'] = $includedItems;
                $hasUpdates = true;
                $this->info('  - Adding included items');
            }

            // Check missing basic fields
            if (!$product->weight) {
                $updates['weight'] = $this->estimateWeight($product);
                $hasUpdates = true;
                $this->info('  - Estimating weight');
            }

            if (!$product->material && $product->category) {
                $material = $this->guessMaterial($product);
                if ($material) {
                    $updates['material'] = $material;
                    $hasUpdates = true;
                    $this->info('  - Guessing material: ' . $material);
                }
            }

            if (!$product->warranty_period) {
                $updates['warranty_period'] = $this->getDefaultWarranty($product);
                $hasUpdates = true;
                $this->info('  - Setting default warranty');
            }

            if (!$product->model_number) {
                $updates['model_number'] = $this->generateModelNumber($product);
                $hasUpdates = true;
                $this->info('  - Generating model number');
            }

            // Apply updates
            if ($hasUpdates && !$dryRun) {
                $product->update($updates);
                $updated++;
                $this->info('  ✅ Updated successfully');
            } elseif ($hasUpdates && $dryRun) {
                $this->comment('  📋 Would update: ' . implode(', ', array_keys($updates)));
                $updated++;
            } else {
                $skipped++;
                $this->comment('  ⏭️  No updates needed');
            }
        }

        $this->newLine();
        $this->info('Summary:');
        $this->info("- Products processed: {$products->count()}");
        $this->info("- Products " . ($dryRun ? 'would be ' : '') . "updated: {$updated}");
        $this->info("- Products skipped: {$skipped}");

        if ($dryRun) {
            $this->warn('This was a dry run. Use without --dry-run to apply changes.');
        }
    }

    private function generateSpecifications($product)
    {
        $specs = [];

        // Category-based specifications
        $categoryName = $product->category->name ?? '';

        if (stripos($categoryName, 'electronic') !== false || stripos($categoryName, 'gadget') !== false) {
            $specs = [
                'power_consumption' => '10-50W',
                'operating_temperature' => '0°C to 40°C',
                'connectivity' => 'USB, WiFi, Bluetooth',
                'display_type' => 'LED',
                'resolution' => 'HD/FHD',
                'processor' => 'ARM Cortex',
                'memory' => '4GB-8GB',
                'storage' => '32GB-128GB'
            ];
        } elseif (stripos($categoryName, 'clothing') !== false || stripos($categoryName, 'fashion') !== false) {
            $specs = [
                'fabric_composition' => '100% Cotton / Cotton Blend',
                'care_instructions' => 'Machine wash cold, tumble dry low',
                'fit_type' => 'Regular Fit',
                'season' => 'All Season',
                'origin' => 'Bangladesh',
                'gsm' => '160-200 GSM'
            ];
        } elseif (stripos($categoryName, 'home') !== false || stripos($categoryName, 'kitchen') !== false) {
            $specs = [
                'capacity' => '1-5 Liters',
                'power_rating' => '100-1500W',
                'voltage' => '220-240V',
                'frequency' => '50Hz',
                'safety_features' => 'Overheating protection, Auto shut-off',
                'certification' => 'CE, RoHS'
            ];
        } elseif (stripos($categoryName, 'beauty') !== false || stripos($categoryName, 'cosmetic') !== false) {
            $specs = [
                'skin_type' => 'All skin types',
                'ingredients' => 'Natural extracts, Vitamins',
                'volume' => '50ml-200ml',
                'shelf_life' => '24 months',
                'pH_level' => '5.5-7.0',
                'paraben_free' => 'Yes'
            ];
        } else {
            // Generic specifications
            $specs = [
                'quality_grade' => 'Premium',
                'certification' => 'ISO Certified',
                'origin' => 'Bangladesh',
                'packaging' => 'Eco-friendly packaging',
                'shelf_life' => '12-24 months'
            ];
        }

        return $specs;
    }

    private function generateFeatures($product)
    {
        $features = [];
        $categoryName = $product->category->name ?? '';

        if (stripos($categoryName, 'electronic') !== false) {
            $features = [
                'Energy efficient design',
                'Modern sleek appearance',
                'Easy to use interface',
                'Durable construction',
                'Advanced technology',
                'User-friendly controls',
                'Compact and portable'
            ];
        } elseif (stripos($categoryName, 'clothing') !== false) {
            $features = [
                'Comfortable fit',
                'Breathable fabric',
                'Durable stitching',
                'Color-fast material',
                'Easy care and maintenance',
                'Stylish design',
                'Premium quality fabric'
            ];
        } elseif (stripos($categoryName, 'home') !== false) {
            $features = [
                'Space-saving design',
                'Easy to clean',
                'Durable materials',
                'Modern aesthetic',
                'Functional design',
                'Safe for daily use',
                'Long-lasting performance'
            ];
        } else {
            $features = [
                'High-quality materials',
                'Excellent craftsmanship',
                'Durable and long-lasting',
                'Great value for money',
                'User-friendly design',
                'Reliable performance'
            ];
        }

        // Randomize and return 4-6 features
        shuffle($features);
        return array_slice($features, 0, rand(4, 6));
    }

    private function generateIncludedItems($product)
    {
        $items = ['1x ' . $product->name];
        
        $categoryName = $product->category->name ?? '';

        if (stripos($categoryName, 'electronic') !== false) {
            $items = array_merge($items, [
                '1x User Manual',
                '1x Power Adapter',
                '1x USB Cable',
                '1x Warranty Card'
            ]);
        } elseif (stripos($categoryName, 'clothing') !== false) {
            $items = array_merge($items, [
                '1x Care Instructions',
                '1x Size Chart',
                '1x Brand Tag'
            ]);
        } else {
            $items = array_merge($items, [
                '1x User Guide',
                '1x Warranty Information'
            ]);
        }

        return $items;
    }

    private function estimateWeight($product)
    {
        $categoryName = $product->category->name ?? '';
        
        if (stripos($categoryName, 'electronic') !== false) {
            return rand(200, 2000) / 1000; // 0.2kg to 2kg
        } elseif (stripos($categoryName, 'clothing') !== false) {
            return rand(100, 800) / 1000; // 0.1kg to 0.8kg
        } elseif (stripos($categoryName, 'home') !== false) {
            return rand(500, 5000) / 1000; // 0.5kg to 5kg
        } else {
            return rand(100, 1000) / 1000; // 0.1kg to 1kg
        }
    }

    private function guessMaterial($product)
    {
        $categoryName = $product->category->name ?? '';
        
        $materials = [
            'electronic' => ['Plastic', 'Metal', 'Glass', 'Silicon'],
            'clothing' => ['Cotton', 'Polyester', 'Cotton Blend', 'Linen'],
            'home' => ['Plastic', 'Metal', 'Ceramic', 'Glass'],
            'beauty' => ['Plastic', 'Glass', 'Aluminum'],
            'default' => ['Premium Quality Material', 'Durable Material']
        ];

        foreach ($materials as $category => $options) {
            if (stripos($categoryName, $category) !== false) {
                return $options[array_rand($options)];
            }
        }

        return $materials['default'][array_rand($materials['default'])];
    }

    private function getDefaultWarranty($product)
    {
        $categoryName = $product->category->name ?? '';
        
        if (stripos($categoryName, 'electronic') !== false) {
            return '1 Year';
        } elseif (stripos($categoryName, 'clothing') !== false) {
            return '30 Days';
        } elseif (stripos($categoryName, 'home') !== false) {
            return '6 Months';
        } else {
            return '90 Days';
        }
    }

    private function generateModelNumber($product)
    {
        $prefix = strtoupper(substr($product->category->name ?? 'GEN', 0, 3));
        $suffix = str_pad($product->id, 4, '0', STR_PAD_LEFT);
        return $prefix . '-' . $suffix;
    }
}
