<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttributeValue;
use App\Models\Attribute;
use App\Traits\HandlesImageUploads;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class AttrvaluesController extends Controller
{
    use HandlesImageUploads;
    /**
     * Display a listing of attribute values.
     */
    public function index(Request $request)
    {
        try {
            $values = $this->getAttrValuesQuery();
            
            // Apply filters
            if ($request->filled('attribute_id')) {
                $values = $values->where('attribute_id', $request->attribute_id);
            }
            
            if ($request->filled('status')) {
                $status = $request->status === 'active' ? 1 : 0;
                $values = $values->where('is_active', $status);
            }
            
            if ($request->filled('type')) {
                $values = $values->where('type', $request->type);
            }
            
            if ($request->filled('search')) {
                $search = $request->search;
                $values = $values->filter(function($value) use ($search) {
                    return stripos($value['value'], $search) !== false ||
                           stripos($value['display_name'], $search) !== false ||
                           stripos($value['description'], $search) !== false;
                });
            }
            
            // Get attribute value statistics
            $stats = $this->getAttrValueStatistics();
            
            // Get attributes and filter options
            $attributes = $this->getAttributes();
            $types = $this->getValueTypes();
            
            return view('admin.attrvalues.index', compact('values', 'stats', 'attributes', 'types'));
            
        } catch (\Exception $e) {
            Log::error('Error fetching attribute values: ' . $e->getMessage());
            return back()->with('error', 'Failed to load attribute values.');
        }
    }

    /**
     * Show the form for creating a new attribute value.
     */
    public function create(Request $request)
    {
        try {
            $attributes = $this->getAttributes();
            $types = $this->getValueTypes();
            $selectedAttribute = $request->get('attribute_id');
            
            return view('admin.attrvalues.create', compact('attributes', 'types', 'selectedAttribute'));
            
        } catch (\Exception $e) {
            Log::error('Error loading attribute value create form: ' . $e->getMessage());
            return back()->with('error', 'Failed to load attribute value form.');
        }
    }

    /**
     * Store a newly created attribute value in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'attribute_id' => 'required|integer|exists:attributes,id',
            'value' => 'required|string|max:255',
            'display_name' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'type' => 'required|in:text,color,image,number,url',
            'color_code' => 'required_if:type,color|nullable|string|max:7',
            'image' => 'required_if:type,image|nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:1024',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'is_default' => 'boolean',
            'extra_price' => 'nullable|numeric|min:0',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();
            
            // Check if value already exists for this attribute
            if ($this->valueExistsForAttribute($request->attribute_id, $request->value)) {
                return back()->with('error', 'This value already exists for the selected attribute.')->withInput();
            }
            
            // Handle image upload
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $this->uploadImage($request->file('image'), 'attributes/values');
            }
            
            // Handle icon upload
            $iconPath = null;
            if ($request->hasFile('icon')) {
                $iconPath = $this->uploadImage($request->file('icon'), 'attributes/icons');
            }
            
            // If this is set as default, remove default from other values
            if ($request->boolean('is_default')) {
                $this->removeDefaultFromAttribute($request->attribute_id);
            }
            
            $valueData = [
                'attribute_id' => $request->attribute_id,
                'value' => $request->value,
                'slug' => Str::slug($request->value),
                'display_name' => $request->display_name ?: $request->value,
                'description' => $request->description,
                'type' => $request->type,
                'color_code' => $request->color_code,
                'image' => $imagePath,
                'icon' => $iconPath,
                'sort_order' => $request->sort_order ?: 0,
                'is_active' => $request->boolean('is_active', true),
                'is_default' => $request->boolean('is_default', false),
                'extra_price' => $request->extra_price ?: 0,
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,
                'usage_count' => 0
            ];
            
            $attrValue = $this->createAttrValue($valueData);
            
            DB::commit();
            
            Log::info('Attribute value created successfully', ['value_id' => $attrValue['id']]);
            
            return redirect()->route('admin.attrvalues.index', ['attribute_id' => $request->attribute_id])
                           ->with('success', 'Attribute value created successfully.');
                           
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating attribute value: ' . $e->getMessage());
            return back()->with('error', 'Failed to create attribute value.')->withInput();
        }
    }

    /**
     * Display the specified attribute value.
     */
    public function show($id)
    {
        try {
            $attrValue = $this->findAttrValue($id);
            
            if (!$attrValue) {
                return back()->with('error', 'Attribute value not found.');
            }
            
            // Get attribute details
            $attribute = $this->getAttributeById($attrValue['attribute_id']);
            
            // Get products using this value
            $products = $this->getProductsUsingValue($id);
            
            // Get usage analytics
            $analytics = $this->getValueAnalytics($id);
            
            return view('admin.attrvalues.show', compact('attrValue', 'attribute', 'products', 'analytics'));
            
        } catch (\Exception $e) {
            Log::error('Error fetching attribute value details: ' . $e->getMessage());
            return back()->with('error', 'Failed to load attribute value details.');
        }
    }

    /**
     * Show the form for editing the specified attribute value.
     */
    public function edit($id)
    {
        try {
            $attrValue = $this->findAttrValue($id);
            
            if (!$attrValue) {
                return back()->with('error', 'Attribute value not found.');
            }
            
            $attributes = $this->getAttributes();
            $types = $this->getValueTypes();
            
            return view('admin.attrvalues.edit', compact('attrValue', 'attributes', 'types'));
            
        } catch (\Exception $e) {
            Log::error('Error loading attribute value edit form: ' . $e->getMessage());
            return back()->with('error', 'Failed to load attribute value form.');
        }
    }

    /**
     * Update the specified attribute value in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'attribute_id' => 'required|integer|exists:attributes,id',
            'value' => ['required', 'string', 'max:255', Rule::unique('attr_values')->ignore($id)->where('attribute_id', $request->attribute_id)],
            'display_name' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'type' => 'required|in:text,color,image,number,url',
            'color_code' => 'required_if:type,color|nullable|string|max:7',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:1024',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'is_default' => 'boolean',
            'extra_price' => 'nullable|numeric|min:0',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $attrValue = $this->findAttrValue($id);
            
            if (!$attrValue) {
                return back()->with('error', 'Attribute value not found.');
            }
            
            DB::beginTransaction();
            
            // Handle image upload
            $imagePath = $attrValue['image'];
            if ($request->hasFile('image')) {
                // Delete old image
                if ($imagePath) {
                    $this->deleteImage($imagePath);
                }
                $imagePath = $this->uploadImage($request->file('image'), 'attributes/values');
            }
            
            // Handle icon upload
            $iconPath = $attrValue['icon'];
            if ($request->hasFile('icon')) {
                // Delete old icon
                if ($iconPath) {
                    $this->deleteImage($iconPath);
                }
                $iconPath = $this->uploadImage($request->file('icon'), 'attributes/icons');
            }
            
            // If this is set as default, remove default from other values
            if ($request->boolean('is_default') && !$attrValue['is_default']) {
                $this->removeDefaultFromAttribute($request->attribute_id);
            }
            
            $valueData = [
                'attribute_id' => $request->attribute_id,
                'value' => $request->value,
                'slug' => Str::slug($request->value),
                'display_name' => $request->display_name ?: $request->value,
                'description' => $request->description,
                'type' => $request->type,
                'color_code' => $request->color_code,
                'image' => $imagePath,
                'icon' => $iconPath,
                'sort_order' => $request->sort_order ?: 0,
                'is_active' => $request->boolean('is_active', true),
                'is_default' => $request->boolean('is_default', false),
                'extra_price' => $request->extra_price ?: 0,
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description
            ];
            
            $this->updateAttrValue($id, $valueData);
            
            DB::commit();
            
            Log::info('Attribute value updated successfully', ['value_id' => $id]);
            
            return redirect()->route('admin.attrvalues.show', $id)
                           ->with('success', 'Attribute value updated successfully.');
                           
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating attribute value: ' . $e->getMessage());
            return back()->with('error', 'Failed to update attribute value.')->withInput();
        }
    }

    /**
     * Remove the specified attribute value from storage.
     */
    public function destroy($id)
    {
        try {
            $attrValue = $this->findAttrValue($id);
            
            if (!$attrValue) {
                return back()->with('error', 'Attribute value not found.');
            }
            
            // Check if value is being used by products
            if ($this->isValueInUse($id)) {
                return back()->with('error', 'Cannot delete attribute value that is being used by products.');
            }
            
            DB::beginTransaction();
            
            // Delete images
            if ($attrValue['image']) {
                $this->deleteImage($attrValue['image']);
            }
            if ($attrValue['icon']) {
                $this->deleteImage($attrValue['icon']);
            }
            
            // Delete the value
            $this->deleteAttrValue($id);
            
            DB::commit();
            
            Log::info('Attribute value deleted successfully', ['value_id' => $id]);
            
            return redirect()->route('admin.attrvalues.index', ['attribute_id' => $attrValue['attribute_id']])
                           ->with('success', 'Attribute value deleted successfully.');
                           
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting attribute value: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete attribute value.');
        }
    }

    /**
     * Toggle attribute value status.
     */
    public function toggleStatus($id)
    {
        try {
            $attrValue = $this->findAttrValue($id);
            
            if (!$attrValue) {
                return response()->json(['error' => 'Attribute value not found.'], 404);
            }
            
            $newStatus = !$attrValue['is_active'];
            $this->updateAttrValue($id, ['is_active' => $newStatus]);
            
            Log::info('Attribute value status toggled', ['value_id' => $id, 'status' => $newStatus]);
            
            return response()->json([
                'success' => true,
                'status' => $newStatus,
                'message' => 'Attribute value status updated successfully.'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error toggling attribute value status: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to update status.'], 500);
        }
    }

    /**
     * Set attribute value as default.
     */
    public function setDefault($id)
    {
        try {
            $attrValue = $this->findAttrValue($id);
            
            if (!$attrValue) {
                return response()->json(['error' => 'Attribute value not found.'], 404);
            }
            
            DB::beginTransaction();
            
            // Remove default from other values in the same attribute
            $this->removeDefaultFromAttribute($attrValue['attribute_id']);
            
            // Set this value as default
            $this->updateAttrValue($id, ['is_default' => true]);
            
            DB::commit();
            
            Log::info('Attribute value set as default', ['value_id' => $id]);
            
            return response()->json([
                'success' => true,
                'message' => 'Attribute value set as default successfully.'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error setting attribute value as default: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to set default.'], 500);
        }
    }

    /**
     * Duplicate an attribute value.
     */
    public function duplicate($id)
    {
        try {
            $attrValue = $this->findAttrValue($id);
            
            if (!$attrValue) {
                return back()->with('error', 'Attribute value not found.');
            }
            
            DB::beginTransaction();
            
            // Prepare data for duplication
            $newValueData = $attrValue;
            unset($newValueData['id'], $newValueData['created_at'], $newValueData['updated_at']);
            $newValueData['value'] = $attrValue['value'] . ' (Copy)';
            $newValueData['slug'] = Str::slug($newValueData['value']);
            $newValueData['display_name'] = $attrValue['display_name'] . ' (Copy)';
            $newValueData['is_default'] = false;
            $newValueData['is_active'] = false;
            $newValueData['usage_count'] = 0;
            
            // Copy images if they exist
            if ($attrValue['image']) {
                $newValueData['image'] = $this->copyImage($attrValue['image']);
            }
            if ($attrValue['icon']) {
                $newValueData['icon'] = $this->copyImage($attrValue['icon']);
            }
            
            $newValue = $this->createAttrValue($newValueData);
            
            DB::commit();
            
            Log::info('Attribute value duplicated successfully', ['original_id' => $id, 'new_id' => $newValue['id']]);
            
            return redirect()->route('admin.attrvalues.edit', $newValue['id'])
                           ->with('success', 'Attribute value duplicated successfully.');
                           
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error duplicating attribute value: ' . $e->getMessage());
            return back()->with('error', 'Failed to duplicate attribute value.');
        }
    }

    /**
     * Bulk actions for attribute values.
     */
    public function bulkAction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'action' => 'required|in:activate,deactivate,delete,change_attribute',
            'value_ids' => 'required|array|min:1',
            'value_ids.*' => 'integer',
            'target_attribute_id' => 'required_if:action,change_attribute|integer'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        try {
            $valueIds = $request->value_ids;
            $action = $request->action;
            $processedCount = 0;
            
            DB::beginTransaction();
            
            foreach ($valueIds as $valueId) {
                $attrValue = $this->findAttrValue($valueId);
                if (!$attrValue) continue;
                
                switch ($action) {
                    case 'activate':
                        $this->updateAttrValue($valueId, ['is_active' => true]);
                        $processedCount++;
                        break;
                        
                    case 'deactivate':
                        $this->updateAttrValue($valueId, ['is_active' => false]);
                        $processedCount++;
                        break;
                        
                    case 'change_attribute':
                        if (!$this->valueExistsForAttribute($request->target_attribute_id, $attrValue['value'])) {
                            $this->updateAttrValue($valueId, ['attribute_id' => $request->target_attribute_id]);
                            $processedCount++;
                        }
                        break;
                        
                    case 'delete':
                        if (!$this->isValueInUse($valueId)) {
                            // Delete images
                            if ($attrValue['image']) {
                                $this->deleteImage($attrValue['image']);
                            }
                            if ($attrValue['icon']) {
                                $this->deleteImage($attrValue['icon']);
                            }
                            $this->deleteAttrValue($valueId);
                            $processedCount++;
                        }
                        break;
                }
            }
            
            DB::commit();
            
            Log::info('Bulk action performed on attribute values', [
                'action' => $action,
                'processed_count' => $processedCount,
                'value_ids' => $valueIds
            ]);
            
            return back()->with('success', "Successfully processed {$processedCount} attribute value(s).");
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error performing bulk action: ' . $e->getMessage());
            return back()->with('error', 'Failed to perform bulk action.');
        }
    }

    /**
     * Update sort order (AJAX).
     */
    public function updateSortOrder(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'value_id' => 'required|integer',
                'sort_order' => 'required|integer|min:0'
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => 'Invalid data.'], 400);
            }

            $this->updateAttrValue($request->value_id, ['sort_order' => $request->sort_order]);
            
            return response()->json([
                'success' => true,
                'message' => 'Sort order updated successfully.'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error updating sort order: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to update sort order.'], 500);
        }
    }

    /**
     * Get attribute values by attribute ID (AJAX).
     */
    public function getByAttribute($attributeId)
    {
        try {
            // Get attribute values from database
            $values = AttributeValue::with('attribute')
                ->where('attribute_id', $attributeId)
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

    /**
     * Search attribute values (AJAX).
     */
    public function search(Request $request)
    {
        try {
            $query = $request->get('q', '');
            $attributeId = $request->get('attribute_id');
            
            $values = $this->searchValues($query, $attributeId);
            
            return response()->json([
                'success' => true,
                'values' => $values
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error searching attribute values: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to search attribute values.'], 500);
        }
    }

    /**
     * Export attribute values to CSV.
     */
    public function export(Request $request)
    {
        try {
            $values = $this->getAttrValuesQuery();
            
            // Apply same filters as index
            if ($request->filled('attribute_id')) {
                $values = $values->where('attribute_id', $request->attribute_id);
            }
            if ($request->filled('status')) {
                $status = $request->status === 'active' ? 1 : 0;
                $values = $values->where('is_active', $status);
            }
            
            $filename = 'attribute_values_export_' . date('Y-m-d_H-i-s') . '.csv';
            
            return $this->generateCsvExport($values, $filename);
            
        } catch (\Exception $e) {
            Log::error('Error exporting attribute values: ' . $e->getMessage());
            return back()->with('error', 'Failed to export attribute values.');
        }
    }

    // Private helper methods

    private function getAttrValuesQuery()
    {
        // Mock query for demonstration - replace with actual database query
        return collect([
            [
                'id' => 1,
                'attribute_id' => 1,
                'attribute_name' => 'Color',
                'value' => 'Red',
                'slug' => 'red',
                'display_name' => 'Bright Red',
                'description' => 'A vibrant red color option',
                'type' => 'color',
                'color_code' => '#FF0000',
                'image' => null,
                'icon' => 'attributes/icons/red.svg',
                'sort_order' => 1,
                'is_active' => true,
                'is_default' => false,
                'extra_price' => 0.00,
                'meta_title' => 'Red Color Option',
                'meta_description' => 'Choose red color for your product',
                'usage_count' => 45,
                'created_at' => now()->subDays(10),
                'updated_at' => now()->subDays(2)
            ],
            [
                'id' => 2,
                'attribute_id' => 1,
                'attribute_name' => 'Color',
                'value' => 'Blue',
                'slug' => 'blue',
                'display_name' => 'Ocean Blue',
                'description' => 'A deep ocean blue color',
                'type' => 'color',
                'color_code' => '#0000FF',
                'image' => null,
                'icon' => 'attributes/icons/blue.svg',
                'sort_order' => 2,
                'is_active' => true,
                'is_default' => true,
                'extra_price' => 0.00,
                'meta_title' => 'Blue Color Option',
                'meta_description' => 'Choose blue color for your product',
                'usage_count' => 67,
                'created_at' => now()->subDays(10),
                'updated_at' => now()->subDays(1)
            ],
            [
                'id' => 3,
                'attribute_id' => 2,
                'attribute_name' => 'Size',
                'value' => 'Small',
                'slug' => 'small',
                'display_name' => 'Small (S)',
                'description' => 'Small size option',
                'type' => 'text',
                'color_code' => null,
                'image' => null,
                'icon' => null,
                'sort_order' => 1,
                'is_active' => true,
                'is_default' => false,
                'extra_price' => 0.00,
                'meta_title' => null,
                'meta_description' => null,
                'usage_count' => 34,
                'created_at' => now()->subDays(8),
                'updated_at' => now()->subDays(3)
            ],
            [
                'id' => 4,
                'attribute_id' => 2,
                'attribute_name' => 'Size',
                'value' => 'Medium',
                'slug' => 'medium',
                'display_name' => 'Medium (M)',
                'description' => 'Medium size option',
                'type' => 'text',
                'color_code' => null,
                'image' => null,
                'icon' => null,
                'sort_order' => 2,
                'is_active' => true,
                'is_default' => true,
                'extra_price' => 5.00,
                'meta_title' => null,
                'meta_description' => null,
                'usage_count' => 78,
                'created_at' => now()->subDays(8),
                'updated_at' => now()->subDays(1)
            ],
            [
                'id' => 5,
                'attribute_id' => 3,
                'attribute_name' => 'Material',
                'value' => 'Cotton',
                'slug' => 'cotton',
                'display_name' => '100% Cotton',
                'description' => 'Pure cotton material',
                'type' => 'image',
                'color_code' => null,
                'image' => 'attributes/values/cotton.jpg',
                'icon' => 'attributes/icons/cotton.svg',
                'sort_order' => 1,
                'is_active' => false,
                'is_default' => false,
                'extra_price' => 10.00,
                'meta_title' => 'Cotton Material',
                'meta_description' => 'High quality cotton material option',
                'usage_count' => 23,
                'created_at' => now()->subDays(6),
                'updated_at' => now()->subDays(6)
            ]
        ]);
    }

    private function getAttrValueStatistics()
    {
        return [
            'total_values' => 125,
            'active_values' => 98,
            'inactive_values' => 27,
            'default_values' => 15,
            'values_with_extra_price' => 34,
            'type_distribution' => [
                'text' => 65,
                'color' => 28,
                'image' => 18,
                'number' => 10,
                'url' => 4
            ],
            'most_used_values' => [
                ['name' => 'Blue', 'usage_count' => 67],
                ['name' => 'Medium', 'usage_count' => 78],
                ['name' => 'Red', 'usage_count' => 45]
            ]
        ];
    }

    private function getAttributes()
    {
        // Mock attributes - replace with actual database query
        return collect([
            ['id' => 1, 'name' => 'Color', 'type' => 'color'],
            ['id' => 2, 'name' => 'Size', 'type' => 'text'],
            ['id' => 3, 'name' => 'Material', 'type' => 'image'],
            ['id' => 4, 'name' => 'Weight', 'type' => 'number']
        ]);
    }

    private function getValueTypes()
    {
        return [
            'text' => 'Text',
            'color' => 'Color',
            'image' => 'Image',
            'number' => 'Number',
            'url' => 'URL'
        ];
    }

    private function createAttrValue($data)
    {
        // Mock creation - replace with actual database insert
        return array_merge(['id' => rand(1000, 9999)], $data, ['created_at' => now(), 'updated_at' => now()]);
    }

    private function findAttrValue($id)
    {
        // Mock data - replace with actual database query
        $values = $this->getAttrValuesQuery();
        return $values->firstWhere('id', $id);
    }

    private function updateAttrValue($id, $data)
    {
        // Mock update - replace with actual database update
        Log::info('Attribute value updated', ['id' => $id, 'data' => $data]);
    }

    private function deleteAttrValue($id)
    {
        // Mock deletion - replace with actual database delete
        Log::info('Attribute value deleted', ['id' => $id]);
    }

    private function uploadImage($file, $directory)
    {
        try {
            $imageData = $this->uploadSingleImage($file, $directory);
            return $imageData['filename'];
        } catch (\Exception $e) {
            Log::error('Attribute value image upload failed: ' . $e->getMessage());
            throw new \Exception('Failed to upload attribute value image: ' . $e->getMessage());
        }
    }

    private function deleteImage($path)
    {
        try {
            if ($path) {
                Log::info('Attribute value image deletion attempted', ['path' => $path]);
                return true;
            }
            return true;
        } catch (\Exception $e) {
            Log::error('Attribute value image deletion failed: ' . $e->getMessage());
            return false;
        }
    }

    private function copyImage($originalPath)
    {
        try {
            if ($originalPath) {
                // For duplication, we can reuse the same image path
                Log::info('Attribute value image copied/reused', ['original' => $originalPath]);
                return $originalPath;
            }
            return null;
        } catch (\Exception $e) {
            Log::error('Attribute value image copy failed: ' . $e->getMessage());
            return null;
        }
    }

    private function valueExistsForAttribute($attributeId, $value)
    {
        // Mock check - replace with actual database query
        $values = $this->getAttrValuesQuery()
                      ->where('attribute_id', $attributeId)
                      ->where('value', $value);
        return $values->isNotEmpty();
    }

    private function removeDefaultFromAttribute($attributeId)
    {
        // Mock update - replace with actual database update
        Log::info('Default removed from attribute values', ['attribute_id' => $attributeId]);
    }

    private function isValueInUse($valueId)
    {
        // Mock check - replace with actual database query to check if value is used by products
        return false; // Assume not in use for demo
    }

    private function getAttributeById($id)
    {
        // Mock data - replace with actual database query
        $attributes = $this->getAttributes();
        return $attributes->firstWhere('id', $id);
    }

    private function getProductsUsingValue($valueId)
    {
        // Mock products - replace with actual database query
        return collect([
            ['id' => 1, 'name' => 'Red T-Shirt', 'sku' => 'TSH-001'],
            ['id' => 2, 'name' => 'Red Hoodie', 'sku' => 'HOD-001']
        ]);
    }

    private function getValueAnalytics($valueId)
    {
        // Mock analytics - replace with actual analytics query
        return [
            'total_products' => 12,
            'total_sales' => 245,
            'revenue_generated' => 3456.78,
            'avg_order_value' => 14.11,
            'monthly_usage' => [
                '2025-01' => 15,
                '2025-02' => 18,
                '2025-03' => 22,
                '2025-04' => 19,
                '2025-05' => 24,
                '2025-06' => 28,
                '2025-07' => 31
            ]
        ];
    }

    private function getValuesByAttribute($attributeId)
    {
        // Mock query - replace with actual database query
        return $this->getAttrValuesQuery()
                   ->where('attribute_id', $attributeId)
                   ->where('is_active', true)
                   ->sortBy('sort_order')
                   ->values();
    }

    private function searchValues($query, $attributeId = null)
    {
        // Mock search - replace with actual database search
        $values = $this->getAttrValuesQuery();
        
        if ($attributeId) {
            $values = $values->where('attribute_id', $attributeId);
        }
        
        if ($query) {
            $values = $values->filter(function($value) use ($query) {
                return stripos($value['value'], $query) !== false ||
                       stripos($value['display_name'], $query) !== false;
            });
        }
        
        return $values->take(10)->values();
    }

    private function generateCsvExport($values, $filename)
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($values) {
            $file = fopen('php://output', 'w');
            
            // CSV Headers
            fputcsv($file, [
                'ID',
                'Attribute',
                'Value',
                'Display Name',
                'Type',
                'Color Code',
                'Sort Order',
                'Status',
                'Default',
                'Extra Price',
                'Usage Count',
                'Created At'
            ]);
            
            // CSV Data
            foreach ($values as $value) {
                fputcsv($file, [
                    $value['id'],
                    $value['attribute_name'],
                    $value['value'],
                    $value['display_name'],
                    ucfirst($value['type']),
                    $value['color_code'] ?? '',
                    $value['sort_order'],
                    $value['is_active'] ? 'Active' : 'Inactive',
                    $value['is_default'] ? 'Yes' : 'No',
                    '$' . number_format($value['extra_price'], 2),
                    $value['usage_count'],
                    $value['created_at']
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
