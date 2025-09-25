<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ProductSpecificationController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'brand']);

        // Filter by missing specifications
        if ($request->get('missing_only')) {
            $query->where(function ($q) {
                $q->whereNull('specifications')
                  ->orWhereNull('features')
                  ->orWhereNull('weight')
                  ->orWhereNull('material')
                  ->orWhereNull('warranty_period');
            });
        }

        // Filter by category
        if ($request->get('category_id')) {
            $query->where('category_id', $request->get('category_id'));
        }

        // Search
        if ($request->get('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('sku', 'LIKE', "%{$search}%");
            });
        }

        $products = $query->paginate(20);
        $categories = Category::where('status', 'active')->get();

        // Count products with missing specifications
        $missingCount = Product::where(function ($q) {
            $q->whereNull('specifications')
              ->orWhereNull('features')
              ->orWhereNull('weight')
              ->orWhereNull('material')
              ->orWhereNull('warranty_period');
        })->count();

        return view('admin.products.specifications.index', compact('products', 'categories', 'missingCount'));
    }

    public function edit($id)
    {
        $product = Product::with(['category', 'brand'])->findOrFail($id);
        
        return view('admin.products.specifications.edit', compact('product'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'weight' => 'nullable|numeric|min:0',
            'length' => 'nullable|numeric|min:0',
            'width' => 'nullable|numeric|min:0',
            'height' => 'nullable|numeric|min:0',
            'shipping_weight' => 'nullable|numeric|min:0',
            'material' => 'nullable|string|max:255',
            'warranty_period' => 'nullable|string|max:255',
            'warranty_terms' => 'nullable|string',
            'support_email' => 'nullable|email',
            'support_phone' => 'nullable|string|max:20',
            'model_number' => 'nullable|string|max:255',
            'barcode' => 'nullable|string|max:255',
            'specifications' => 'nullable|array',
            'features' => 'nullable|array',
            'included_items' => 'nullable|array',
            'compatibility' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $updateData = $request->only([
                'weight', 'length', 'width', 'height', 'shipping_weight',
                'material', 'warranty_period', 'warranty_terms',
                'support_email', 'support_phone', 'model_number', 'barcode'
            ]);

            // Process array fields
            if ($request->has('specifications')) {
                $specifications = [];
                $specKeys = $request->input('spec_keys', []);
                $specValues = $request->input('spec_values', []);
                
                foreach ($specKeys as $index => $key) {
                    if (!empty($key) && !empty($specValues[$index])) {
                        $specifications[$key] = $specValues[$index];
                    }
                }
                $updateData['specifications'] = $specifications;
            }

            if ($request->has('features')) {
                $features = array_filter($request->input('features', []), 'strlen');
                $updateData['features'] = $features;
            }

            if ($request->has('included_items')) {
                $includedItems = array_filter($request->input('included_items', []), 'strlen');
                $updateData['included_items'] = $includedItems;
            }

            if ($request->has('compatibility')) {
                $compatibility = array_filter($request->input('compatibility', []), 'strlen');
                $updateData['compatibility'] = $compatibility;
            }

            $product->update($updateData);

            return redirect()
                ->route('admin.products.specifications.index')
                ->with('success', 'Product specifications updated successfully!');

        } catch (\Exception $e) {
            Log::error('Error updating product specifications: ' . $e->getMessage());
            return back()->with('error', 'Failed to update product specifications.')->withInput();
        }
    }

    public function bulkUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_ids' => 'required|array',
            'product_ids.*' => 'exists:products,id',
            'action' => 'required|in:auto_generate,bulk_edit',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        try {
            $productIds = $request->input('product_ids');
            $action = $request->input('action');

            if ($action === 'auto_generate') {
                $this->autoGenerateSpecifications($productIds);
                $message = 'Specifications auto-generated for selected products.';
            } else {
                // Handle bulk edit
                $message = 'Bulk edit feature coming soon.';
            }

            return back()->with('success', $message);

        } catch (\Exception $e) {
            Log::error('Error in bulk update: ' . $e->getMessage());
            return back()->with('error', 'Failed to update product specifications.');
        }
    }

    private function autoGenerateSpecifications($productIds)
    {
        $products = Product::with('category')->whereIn('id', $productIds)->get();

        foreach ($products as $product) {
            $updates = [];

            // Generate specifications if missing
            if (!$product->specifications || empty($product->specifications)) {
                $updates['specifications'] = $this->generateSpecifications($product);
            }

            // Generate features if missing
            if (!$product->features || empty($product->features)) {
                $updates['features'] = $this->generateFeatures($product);
            }

            // Generate included items if missing
            if (!$product->included_items || empty($product->included_items)) {
                $updates['included_items'] = $this->generateIncludedItems($product);
            }

            // Set basic fields if missing
            if (!$product->weight) {
                $updates['weight'] = $this->estimateWeight($product);
            }

            if (!$product->material) {
                $updates['material'] = $this->guessMaterial($product);
            }

            if (!$product->warranty_period) {
                $updates['warranty_period'] = $this->getDefaultWarranty($product);
            }

            if (!$product->model_number) {
                $updates['model_number'] = $this->generateModelNumber($product);
            }

            if (!empty($updates)) {
                $product->update($updates);
            }
        }
    }

    // Helper methods (same as in the command)
    private function generateSpecifications($product)
    {
        $specs = [];
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
        } else {
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

    /**
     * Show bulk update form
     */
    public function bulk(Request $request)
    {
        $categories = Category::where('status', 'active')->get();
        $brands = Brand::where('status', 'active')->get();
        
        // Get products for bulk update if filters are applied
        $products = collect();
        if ($request->hasAny(['category_id', 'brand_id', 'missing_only'])) {
            $query = Product::with(['category', 'brand']);
            
            if ($request->category_id) {
                $query->where('category_id', $request->category_id);
            }
            
            if ($request->brand_id) {
                $query->where('brand_id', $request->brand_id);
            }
            
            if ($request->missing_only) {
                $query->where(function ($q) {
                    $q->whereNull('specifications')
                      ->orWhereNull('features')
                      ->orWhereNull('weight')
                      ->orWhereNull('material');
                });
            }
            
            $products = $query->limit(100)->get();
        }

        return view('admin.products.specifications.bulk', compact('categories', 'brands', 'products'));
    }

    /**
     * Show products with missing specifications
     */
    public function missing()
    {
        $products = Product::with(['category', 'brand'])
            ->where(function ($q) {
                $q->whereNull('specifications')
                  ->orWhereNull('features')
                  ->orWhereNull('weight')
                  ->orWhereNull('material')
                  ->orWhereNull('warranty_period');
            })
            ->paginate(20);

        $categories = Category::where('status', 'active')->get();

        return view('admin.products.specifications.missing', compact('products', 'categories'));
    }

    /**
     * Show generate specifications form
     */
    public function generate()
    {
        $categories = Category::where('status', 'active')->get();
        $brands = Brand::where('status', 'active')->get();

        return view('admin.products.specifications.generate', compact('categories', 'brands'));
    }

    /**
     * Generate specifications for selected products
     */
    public function generateSpecs(Request $request)
    {
        $request->validate([
            'products' => 'required|array',
            'products.*' => 'exists:products,id',
            'generate_features' => 'boolean',
            'generate_specifications' => 'boolean',
            'generate_warranty' => 'boolean',
        ]);

        $updatedCount = 0;
        $errors = [];

        foreach ($request->products as $productId) {
            try {
                $product = Product::findOrFail($productId);
                $updates = [];

                if ($request->generate_features && !$product->features) {
                    $updates['features'] = $this->generateFeatures($product);
                }

                if ($request->generate_specifications && !$product->specifications) {
                    $updates['specifications'] = $this->generateSpecifications($product);
                }

                if ($request->generate_warranty && !$product->warranty_period) {
                    $updates['warranty_period'] = $this->getDefaultWarranty($product);
                }

                if (!empty($updates)) {
                    $product->update($updates);
                    $updatedCount++;
                }
            } catch (\Exception $e) {
                $errors[] = "Product ID {$productId}: " . $e->getMessage();
            }
        }

        if ($updatedCount > 0) {
            return redirect()->back()->with('success', "Successfully generated specifications for {$updatedCount} products.");
        } else {
            return redirect()->back()->with('warning', 'No products were updated. They may already have specifications.');
        }
    }
}
