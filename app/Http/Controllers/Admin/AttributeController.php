<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AttributeController extends Controller
{
    /**
     * Display a listing of attributes.
     */
    public function index(Request $request)
    {
        try {
            // Start with base query
            $query = Attribute::query();
            
            // Apply filters
            if ($request->filled('type')) {
                $query->where('type', $request->type);
            }
            
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                      ->orWhere('slug', 'like', '%' . $search . '%');
                });
            }
            
            // Get paginated results
            $attributes = $query->orderBy('sort_order')->orderBy('name')->get();
            
            // Transform data for view compatibility
            $attributes = $attributes->map(function($attribute) {
                return [
                    'id' => $attribute->id,
                    'name' => $attribute->name,
                    'slug' => $attribute->slug,
                    'type' => $attribute->type,
                    'description' => $attribute->description,
                    'is_required' => $attribute->is_required,
                    'is_filterable' => $attribute->is_filterable,
                    'is_variation' => $attribute->is_variation,
                    'is_active' => $attribute->status === 'active',
                    'input_type' => ucfirst($attribute->type),
                    'created_at' => $attribute->created_at->toDateTimeString()
                ];
            });
            
            // Get statistics
            $totalAttributes = Attribute::count();
            $totalValues = 0; // Will implement when AttributeValue model is ready
            $activeAttributes = Attribute::where('status', 'active')->count();
            $filterableAttributes = Attribute::where('is_filterable', true)->count();
            
            return view('admin.attributes.index', [
                'attributes' => $attributes,
                'totalAttributes' => $totalAttributes,
                'totalValues' => $totalValues,
                'activeAttributes' => $activeAttributes,
                'filterableAttributes' => $filterableAttributes
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error fetching attributes: ' . $e->getMessage());
            return back()->with('error', 'Failed to load attributes.');
        }
    }

    /**
     * Show the form for creating a new attribute.
     */
    public function create()
    {
        try {
            $attributeTypes = $this->getAttributeTypes();
            
            return view('admin.attributes.create', compact('attributeTypes'));
            
        } catch (\Exception $e) {
            Log::error('Error loading attribute create form: ' . $e->getMessage());
            return back()->with('error', 'Failed to load attribute form.');
        }
    }

    /**
     * Store a newly created attribute in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:attributes,name',
            'type' => 'required|in:text,textarea,number,decimal,boolean,date,datetime,select,multiselect,radio,checkbox,color,image,file,url,email',
            'description' => 'nullable|string|max:1000',
            'is_required' => 'boolean',
            'is_filterable' => 'boolean',
            'is_variation' => 'boolean',
            'is_active' => 'boolean' // Accept is_active from form
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();
            
            // Generate slug
            $slug = Str::slug($request->name);
            
            // Convert is_active to status for database
            $status = $request->boolean('is_active') ? 'active' : 'inactive';
            
            $attribute = Attribute::create([
                'name' => $request->name,
                'slug' => $slug,
                'type' => $request->type,
                'display_name' => $request->name,
                'description' => $request->description,
                'is_required' => $request->boolean('is_required'),
                'is_filterable' => $request->boolean('is_filterable'),
                'is_variation' => $request->boolean('is_variation'),
                'is_global' => false,
                'admin_only' => false,
                'status' => $status,
                'sort_order' => 0
            ]);
            
            DB::commit();
            
            Log::info('Attribute created successfully', ['attribute_id' => $attribute->id]);
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Attribute created successfully.',
                    'attribute' => $attribute
                ]);
            }
            
            return redirect()->route('admin.attributes.index')
                           ->with('success', 'Attribute created successfully.');
                           
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating attribute: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create attribute.',
                    'error' => $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Failed to create attribute.')->withInput();
        }
    }

    /**
     * Display the specified attribute.
     */
    public function show($id)
    {
        try {
            $attribute = $this->findAttribute($id);
            
            if (!$attribute) {
                return back()->with('error', 'Attribute not found.');
            }
            
            // Get attribute options
            $options = $this->getAttributeOptions($id);
            
            // Get products using this attribute
            $productsCount = $this->getProductsUsingAttribute($id);
            
            return view('admin.attributes.show', compact('attribute', 'options', 'productsCount'));
            
        } catch (\Exception $e) {
            Log::error('Error fetching attribute details: ' . $e->getMessage());
            return back()->with('error', 'Failed to load attribute details.');
        }
    }

    /**
     * Show the form for editing the specified attribute.
     */
    public function edit($id)
    {
        try {
            $attribute = $this->findAttribute($id);
            
            if (!$attribute) {
                return back()->with('error', 'Attribute not found.');
            }
            
            $attributeTypes = $this->getAttributeTypes();
            $inputTypes = $this->getInputTypes();
            $options = $this->getAttributeOptions($id);
            
            return view('admin.attributes.edit', compact('attribute', 'attributeTypes', 'inputTypes', 'options'));
            
        } catch (\Exception $e) {
            Log::error('Error loading attribute edit form: ' . $e->getMessage());
            return back()->with('error', 'Failed to load attribute form.');
        }
    }

    /**
     * Update the specified attribute in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('attributes', 'name')->ignore($id)
            ],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('attributes', 'slug')->ignore($id)
            ],
            'type' => 'required|in:text,number,select,multiselect,color,boolean,date',
            'input_type' => 'required|in:text,textarea,select,radio,checkbox,color,number,date,file',
            'description' => 'nullable|string|max:1000',
            'is_required' => 'boolean',
            'is_filterable' => 'boolean',
            'is_searchable' => 'boolean',
            'is_comparable' => 'boolean',
            'is_visible_on_front' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
            'validation_rules' => 'nullable|string|max:500',
            'default_value' => 'nullable|string|max:255',
            'options' => 'nullable|array',
            'options.*.value' => 'required_with:options|string|max:255',
            'options.*.label' => 'required_with:options|string|max:255',
            'options.*.sort_order' => 'nullable|integer|min:0'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $attribute = $this->findAttribute($id);
            
            if (!$attribute) {
                return back()->with('error', 'Attribute not found.');
            }
            
            DB::beginTransaction();
            
            // Generate slug if not provided
            $slug = $request->slug ?: Str::slug($request->name);
            
            $attributeData = [
                'name' => $request->name,
                'slug' => $slug,
                'type' => $request->type,
                'input_type' => $request->input_type,
                'description' => $request->description,
                'is_required' => $request->boolean('is_required'),
                'is_filterable' => $request->boolean('is_filterable'),
                'is_searchable' => $request->boolean('is_searchable'),
                'is_comparable' => $request->boolean('is_comparable'),
                'is_visible_on_front' => $request->boolean('is_visible_on_front'),
                'is_active' => $request->boolean('is_active', true),
                'sort_order' => $request->sort_order ?: 0,
                'validation_rules' => $request->validation_rules,
                'default_value' => $request->default_value
            ];
            
            $this->updateAttribute($id, $attributeData);
            
            // Update attribute options
            if (in_array($request->type, ['select', 'multiselect', 'radio', 'checkbox'])) {
                $this->deleteAttributeOptions($id);
                if ($request->has('options')) {
                    $this->createAttributeOptions($id, $request->options);
                }
            }
            
            DB::commit();
            
            Log::info('Attribute updated successfully', ['attribute_id' => $id]);
            
            return redirect()->route('admin.attributes.show', $id)
                           ->with('success', 'Attribute updated successfully.');
                           
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating attribute: ' . $e->getMessage());
            return back()->with('error', 'Failed to update attribute.')->withInput();
        }
    }

    /**
     * Remove the specified attribute from storage.
     */
    public function destroy($id)
    {
        try {
            $attribute = $this->findAttribute($id);
            
            if (!$attribute) {
                return back()->with('error', 'Attribute not found.');
            }
            
            // Check if attribute is being used by products
            $productsCount = $this->getProductsUsingAttribute($id);
            if ($productsCount > 0) {
                return back()->with('error', 'Cannot delete attribute. It is being used by ' . $productsCount . ' product(s).');
            }
            
            DB::beginTransaction();
            
            // Delete attribute options first
            $this->deleteAttributeOptions($id);
            
            // Delete the attribute
            $this->deleteAttribute($id);
            
            DB::commit();
            
            Log::info('Attribute deleted successfully', ['attribute_id' => $id]);
            
            return redirect()->route('admin.attributes.index')
                           ->with('success', 'Attribute deleted successfully.');
                           
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting attribute: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete attribute.');
        }
    }

    /**
     * Toggle attribute status.
     */
    public function toggleStatus(Request $request, $id)
    {
        try {
            $attribute = Attribute::findOrFail($id);
            $newStatus = $request->boolean('is_active'); // Use is_active from form
            
            $attribute->update(['status' => $newStatus ? 'active' : 'inactive']);
            
            Log::info('Attribute status toggled', ['attribute_id' => $id, 'status' => $newStatus]);
            
            return response()->json([
                'success' => true,
                'status' => $newStatus,
                'message' => 'Attribute status updated successfully.'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error toggling attribute status: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update status.'
            ], 500);
        }
    }

    /**
     * Bulk actions for attributes.
     */
    public function bulkAction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'action' => 'required|in:activate,deactivate,delete',
            'attribute_ids' => 'required|array|min:1',
            'attribute_ids.*' => 'integer'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        try {
            $attributeIds = $request->attribute_ids;
            $action = $request->action;
            $processedCount = 0;
            
            DB::beginTransaction();
            
            foreach ($attributeIds as $attributeId) {
                $attribute = $this->findAttribute($attributeId);
                if (!$attribute) continue;
                
                switch ($action) {
                    case 'activate':
                        $this->updateAttribute($attributeId, ['is_active' => true]);
                        $processedCount++;
                        break;
                        
                    case 'deactivate':
                        $this->updateAttribute($attributeId, ['is_active' => false]);
                        $processedCount++;
                        break;
                        
                    case 'delete':
                        // Check if attribute is being used
                        $productsCount = $this->getProductsUsingAttribute($attributeId);
                        if ($productsCount === 0) {
                            $this->deleteAttributeOptions($attributeId);
                            $this->deleteAttribute($attributeId);
                            $processedCount++;
                        }
                        break;
                }
            }
            
            DB::commit();
            
            Log::info('Bulk action performed on attributes', [
                'action' => $action,
                'processed_count' => $processedCount,
                'attribute_ids' => $attributeIds
            ]);
            
            return back()->with('success', "Successfully {$action}d {$processedCount} attribute(s).");
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error performing bulk action: ' . $e->getMessage());
            return back()->with('error', 'Failed to perform bulk action.');
        }
    }

    /**
     * Export attributes to CSV.
     */
    public function export()
    {
        try {
            $attributes = $this->getAttributesQuery();
            $filename = 'attributes_export_' . date('Y-m-d_H-i-s') . '.csv';
            
            return $this->generateCsvExport($attributes, $filename);
            
        } catch (\Exception $e) {
            Log::error('Error exporting attributes: ' . $e->getMessage());
            return back()->with('error', 'Failed to export attributes.');
        }
    }

    /**
     * Get attribute options via AJAX.
     */
    public function getOptions($id)
    {
        try {
            $options = $this->getAttributeOptions($id);
            
            return response()->json([
                'success' => true,
                'options' => $options
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error fetching attribute options: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch options.'], 500);
        }
    }

    // Private helper methods

    private function getAttributesQuery()
    {
        // Mock query for demonstration - replace with actual database query
        return collect([
            [
                'id' => 1,
                'name' => 'Size',
                'slug' => 'size',
                'type' => 'select',
                'input_type' => 'select',
                'description' => 'Product size attribute',
                'is_required' => true,
                'is_filterable' => true,
                'is_searchable' => false,
                'is_comparable' => true,
                'is_visible_on_front' => true,
                'is_active' => true,
                'sort_order' => 1,
                'validation_rules' => 'required',
                'default_value' => null,
                'created_at' => now()->subDays(10),
                'updated_at' => now()->subDays(5)
            ],
            [
                'id' => 2,
                'name' => 'Color',
                'slug' => 'color',
                'type' => 'color',
                'input_type' => 'color',
                'description' => 'Product color attribute',
                'is_required' => true,
                'is_filterable' => true,
                'is_searchable' => false,
                'is_comparable' => true,
                'is_visible_on_front' => true,
                'is_active' => true,
                'sort_order' => 2,
                'validation_rules' => 'required',
                'default_value' => null,
                'created_at' => now()->subDays(8),
                'updated_at' => now()->subDays(3)
            ],
            [
                'id' => 3,
                'name' => 'Material',
                'slug' => 'material',
                'type' => 'select',
                'input_type' => 'select',
                'description' => 'Product material attribute',
                'is_required' => false,
                'is_filterable' => true,
                'is_searchable' => true,
                'is_comparable' => true,
                'is_visible_on_front' => true,
                'is_active' => true,
                'sort_order' => 3,
                'validation_rules' => null,
                'default_value' => null,
                'created_at' => now()->subDays(6),
                'updated_at' => now()->subDays(2)
            ],
            [
                'id' => 4,
                'name' => 'Weight',
                'slug' => 'weight',
                'type' => 'number',
                'input_type' => 'number',
                'description' => 'Product weight in grams',
                'is_required' => false,
                'is_filterable' => false,
                'is_searchable' => false,
                'is_comparable' => true,
                'is_visible_on_front' => true,
                'is_active' => false,
                'sort_order' => 4,
                'validation_rules' => 'numeric|min:0',
                'default_value' => '0',
                'created_at' => now()->subDays(4),
                'updated_at' => now()->subDay()
            ]
        ]);
    }

    private function getAttributeStatistics()
    {
        return [
            'total_attributes' => 25,
            'active_attributes' => 20,
            'inactive_attributes' => 5,
            'filterable_attributes' => 15,
            'required_attributes' => 8,
            'type_distribution' => [
                'select' => 12,
                'text' => 6,
                'number' => 4,
                'color' => 2,
                'boolean' => 1
            ]
        ];
    }

    private function getAttributeTypes()
    {
        return [
            'text' => 'Text',
            'textarea' => 'Textarea',
            'number' => 'Number',
            'decimal' => 'Decimal',
            'boolean' => 'Yes/No',
            'date' => 'Date',
            'datetime' => 'Date & Time',
            'select' => 'Select Dropdown',
            'multiselect' => 'Multi Select',
            'radio' => 'Radio Buttons',
            'checkbox' => 'Checkboxes',
            'color' => 'Color Picker',
            'image' => 'Image Upload',
            'file' => 'File Upload',
            'url' => 'URL',
            'email' => 'Email'
        ];
    }

    private function getInputTypes()
    {
        return [
            'text' => 'Text Input',
            'textarea' => 'Textarea',
            'select' => 'Select Dropdown',
            'radio' => 'Radio Buttons',
            'checkbox' => 'Checkboxes',
            'color' => 'Color Picker',
            'number' => 'Number Input',
            'date' => 'Date Picker',
            'file' => 'File Upload'
        ];
    }

    private function createAttribute($data)
    {
        // Create actual database record
        $attribute = Attribute::create($data);
        return $attribute->toArray();
    }

    private function findAttribute($id)
    {
        // Mock data - replace with actual database query
        $attributes = $this->getAttributesQuery();
        return $attributes->firstWhere('id', $id);
    }

    private function updateAttribute($id, $data)
    {
        // Update actual database record
        $attribute = Attribute::findOrFail($id);
        $attribute->update($data);
        return $attribute->toArray();
    }

    private function deleteAttribute($id)
    {
        // Delete actual database record
        $attribute = Attribute::findOrFail($id);
        $attribute->delete();
    }

    private function createAttributeOptions($attributeId, $options)
    {
        // Mock creation - replace with actual database insert
        foreach ($options as $index => $option) {
            Log::info('Attribute option created', [
                'attribute_id' => $attributeId,
                'value' => $option['value'],
                'label' => $option['label'],
                'sort_order' => $option['sort_order'] ?? $index
            ]);
        }
    }

    private function getAttributeOptions($attributeId)
    {
        // Mock data - replace with actual database query
        if ($attributeId == 1) { // Size attribute
            return collect([
                ['id' => 1, 'value' => 'xs', 'label' => 'Extra Small', 'sort_order' => 1],
                ['id' => 2, 'value' => 's', 'label' => 'Small', 'sort_order' => 2],
                ['id' => 3, 'value' => 'm', 'label' => 'Medium', 'sort_order' => 3],
                ['id' => 4, 'value' => 'l', 'label' => 'Large', 'sort_order' => 4],
                ['id' => 5, 'value' => 'xl', 'label' => 'Extra Large', 'sort_order' => 5]
            ]);
        } elseif ($attributeId == 3) { // Material attribute
            return collect([
                ['id' => 6, 'value' => 'cotton', 'label' => 'Cotton', 'sort_order' => 1],
                ['id' => 7, 'value' => 'polyester', 'label' => 'Polyester', 'sort_order' => 2],
                ['id' => 8, 'value' => 'wool', 'label' => 'Wool', 'sort_order' => 3],
                ['id' => 9, 'value' => 'silk', 'label' => 'Silk', 'sort_order' => 4]
            ]);
        }
        
        return collect([]);
    }

    private function deleteAttributeOptions($attributeId)
    {
        // Mock deletion - replace with actual database delete
        Log::info('Attribute options deleted', ['attribute_id' => $attributeId]);
    }

    private function getProductsUsingAttribute($attributeId)
    {
        // Mock count - replace with actual database query
        return rand(0, 50);
    }

    private function generateCsvExport($attributes, $filename)
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($attributes) {
            $file = fopen('php://output', 'w');
            
            // CSV Headers
            fputcsv($file, [
                'ID',
                'Name',
                'Slug',
                'Type',
                'Input Type',
                'Description',
                'Required',
                'Filterable',
                'Searchable',
                'Comparable',
                'Visible on Front',
                'Status',
                'Sort Order',
                'Created At'
            ]);
            
            // CSV Data
            foreach ($attributes as $attribute) {
                fputcsv($file, [
                    $attribute['id'],
                    $attribute['name'],
                    $attribute['slug'],
                    ucfirst($attribute['type']),
                    ucfirst($attribute['input_type']),
                    $attribute['description'] ?? '',
                    $attribute['is_required'] ? 'Yes' : 'No',
                    $attribute['is_filterable'] ? 'Yes' : 'No',
                    $attribute['is_searchable'] ? 'Yes' : 'No',
                    $attribute['is_comparable'] ? 'Yes' : 'No',
                    $attribute['is_visible_on_front'] ? 'Yes' : 'No',
                    $attribute['is_active'] ? 'Active' : 'Inactive',
                    $attribute['sort_order'],
                    $attribute['created_at']
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Display sizes attributes
     */
    public function sizes(Request $request)
    {
        try {
            $sizes = $this->getAttributesByType('size');
            
            // Apply search filter
            if ($request->filled('search')) {
                $search = $request->search;
                $sizes = $sizes->filter(function($size) use ($search) {
                    return stripos($size['name'], $search) !== false ||
                           stripos($size['value'], $search) !== false;
                });
            }
            
            $stats = [
                'total_sizes' => count($sizes),
                'active_sizes' => $sizes->where('is_active', true)->count(),
                'inactive_sizes' => $sizes->where('is_active', false)->count()
            ];
            
            return view('admin.attributes.sizes', compact('sizes', 'stats'));
            
        } catch (\Exception $e) {
            Log::error('Error fetching sizes: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading sizes: ' . $e->getMessage());
        }
    }

    /**
     * Display colors attributes
     */
    public function colors(Request $request)
    {
        try {
            $colors = $this->getAttributesByType('color');
            
            // Apply search filter
            if ($request->filled('search')) {
                $search = $request->search;
                $colors = $colors->filter(function($color) use ($search) {
                    return stripos($color['name'], $search) !== false ||
                           stripos($color['value'], $search) !== false;
                });
            }
            
            $stats = [
                'total_colors' => count($colors),
                'active_colors' => $colors->where('is_active', true)->count(),
                'inactive_colors' => $colors->where('is_active', false)->count()
            ];
            
            return view('admin.attributes.colors', compact('colors', 'stats'));
            
        } catch (\Exception $e) {
            Log::error('Error fetching colors: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading colors: ' . $e->getMessage());
        }
    }

    /**
     * Display materials attributes
     */
    public function materials(Request $request)
    {
        try {
            $materials = $this->getAttributesByType('material');
            
            // Apply search filter
            if ($request->filled('search')) {
                $search = $request->search;
                $materials = $materials->filter(function($material) use ($search) {
                    return stripos($material['name'], $search) !== false ||
                           stripos($material['value'], $search) !== false;
                });
            }
            
            $stats = [
                'total_materials' => count($materials),
                'active_materials' => $materials->where('is_active', true)->count(),
                'inactive_materials' => $materials->where('is_active', false)->count()
            ];
            
            return view('admin.attributes.materials', compact('materials', 'stats'));
            
        } catch (\Exception $e) {
            Log::error('Error fetching materials: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading materials: ' . $e->getMessage());
        }
    }

    /**
     * Display attribute sets
     */
    public function sets(Request $request)
    {
        try {
            $attributeSets = $this->getAttributeSets();
            
            // Apply search filter
            if ($request->filled('search')) {
                $search = $request->search;
                $attributeSets = $attributeSets->filter(function($set) use ($search) {
                    return stripos($set['name'], $search) !== false ||
                           stripos($set['description'], $search) !== false;
                });
            }
            
            $stats = [
                'total_sets' => count($attributeSets),
                'active_sets' => $attributeSets->where('is_active', true)->count(),
                'inactive_sets' => $attributeSets->where('is_active', false)->count()
            ];
            
            return view('admin.attributes.sets', compact('attributeSets', 'stats'));
            
        } catch (\Exception $e) {
            Log::error('Error fetching attribute sets: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading attribute sets: ' . $e->getMessage());
        }
    }

    /**
     * Get attributes by type
     */
    private function getAttributesByType($type)
    {
        // Sample data for demonstration - replace with actual database queries
        $sampleData = [
            'size' => [
                ['id' => 1, 'name' => 'Small', 'value' => 'S', 'code' => 'SM', 'is_active' => true, 'sort_order' => 1],
                ['id' => 2, 'name' => 'Medium', 'value' => 'M', 'code' => 'MD', 'is_active' => true, 'sort_order' => 2],
                ['id' => 3, 'name' => 'Large', 'value' => 'L', 'code' => 'LG', 'is_active' => true, 'sort_order' => 3],
                ['id' => 4, 'name' => 'Extra Large', 'value' => 'XL', 'code' => 'XL', 'is_active' => true, 'sort_order' => 4],
                ['id' => 5, 'name' => 'XXL', 'value' => 'XXL', 'code' => 'XXL', 'is_active' => false, 'sort_order' => 5],
            ],
            'color' => [
                ['id' => 1, 'name' => 'Red', 'value' => '#FF0000', 'code' => 'RED', 'is_active' => true, 'sort_order' => 1],
                ['id' => 2, 'name' => 'Blue', 'value' => '#0000FF', 'code' => 'BLUE', 'is_active' => true, 'sort_order' => 2],
                ['id' => 3, 'name' => 'Green', 'value' => '#00FF00', 'code' => 'GREEN', 'is_active' => true, 'sort_order' => 3],
                ['id' => 4, 'name' => 'Black', 'value' => '#000000', 'code' => 'BLACK', 'is_active' => true, 'sort_order' => 4],
                ['id' => 5, 'name' => 'White', 'value' => '#FFFFFF', 'code' => 'WHITE', 'is_active' => true, 'sort_order' => 5],
            ],
            'material' => [
                ['id' => 1, 'name' => 'Cotton', 'value' => 'cotton', 'code' => 'COT', 'is_active' => true, 'sort_order' => 1],
                ['id' => 2, 'name' => 'Polyester', 'value' => 'polyester', 'code' => 'POL', 'is_active' => true, 'sort_order' => 2],
                ['id' => 3, 'name' => 'Silk', 'value' => 'silk', 'code' => 'SLK', 'is_active' => true, 'sort_order' => 3],
                ['id' => 4, 'name' => 'Wool', 'value' => 'wool', 'code' => 'WOL', 'is_active' => true, 'sort_order' => 4],
                ['id' => 5, 'name' => 'Leather', 'value' => 'leather', 'code' => 'LTH', 'is_active' => false, 'sort_order' => 5],
            ]
        ];

        return collect($sampleData[$type] ?? []);
    }

    /**
     * Get attribute sets
     */
    private function getAttributeSets()
    {
        // Sample data for demonstration - replace with actual database queries
        $sampleSets = [
            ['id' => 1, 'name' => 'Clothing Attributes', 'description' => 'Standard attributes for clothing items', 'attributes_count' => 5, 'is_active' => true],
            ['id' => 2, 'name' => 'Electronics Attributes', 'description' => 'Attributes for electronic products', 'attributes_count' => 8, 'is_active' => true],
            ['id' => 3, 'name' => 'Home & Garden', 'description' => 'Attributes for home and garden products', 'attributes_count' => 6, 'is_active' => true],
            ['id' => 4, 'name' => 'Sports Equipment', 'description' => 'Attributes for sports and fitness products', 'attributes_count' => 4, 'is_active' => false],
        ];

        return collect($sampleSets);
    }
    
    /**
     * Update attribute via AJAX
     */
    public function updateAjax(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'value' => 'required|string|max:255',
            'code' => 'required|string|max:50',
            'sort_order' => 'required|integer|min:0',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $attribute = Attribute::findOrFail($id);
            
            $attribute->update([
                'name' => $request->name,
                'display_name' => $request->value,
                'slug' => Str::slug($request->code),
                'sort_order' => $request->sort_order,
                'status' => $request->boolean('is_active') ? 'active' : 'inactive'
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Attribute updated successfully',
                'attribute' => [
                    'id' => $attribute->id,
                    'name' => $attribute->name,
                    'value' => $attribute->display_name,
                    'code' => $attribute->slug,
                    'sort_order' => $attribute->sort_order,
                    'is_active' => $attribute->status === 'active'
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update attribute'
            ], 500);
        }
    }
    
    /**
     * Delete attribute via AJAX
     */
    public function deleteAjax($id)
    {
        try {
            $attribute = Attribute::findOrFail($id);
            $attribute->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Attribute deleted successfully'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete attribute'
            ], 500);
        }
    }

    /**
     * Get attribute values for a specific attribute
     */
    public function values($id)
    {
        try {
            // Import the AttributeValue model
            $values = \App\Models\AttributeValue::where('attribute_id', $id)
                ->where('status', 'active')
                ->orderBy('sort_order')
                ->orderBy('value')
                ->get()
                ->map(function ($value) {
                    return [
                        'id' => $value->id,
                        'value' => $value->value,
                        'display_name' => $value->display_name ?: $value->value,
                        'color_code' => $value->color_code,
                        'image' => $value->image,
                        'icon' => $value->icon,
                        'extra_price' => $value->extra_price ?: 0,
                        'is_default' => $value->is_default,
                        'sort_order' => $value->sort_order ?: 0
                    ];
                });
            
            return response()->json([
                'success' => true,
                'values' => $values
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error fetching attribute values: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Failed to fetch attribute values.',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
