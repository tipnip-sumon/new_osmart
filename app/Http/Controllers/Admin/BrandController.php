<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Traits\HandlesImageUploads;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    use HandlesImageUploads;
    public function index()
    {
        $brands = Brand::ordered()
            ->paginate(10);
        
        return view('admin.brands.index', compact('brands'));
    }

    public function create()
    {
        return view('admin.brands.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:brands',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'website_url' => 'nullable|url',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'status' => 'required|in:Active,Inactive',
            'is_featured' => 'boolean',
        ]);

        $data = $request->all();
        
        // Generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }
        
        // Handle logo upload and processing
        if ($request->hasFile('logo')) {
            try {
                $logoData = $this->uploadBrandImage($request->file('logo'), 'brands');
                $data['logo'] = $logoData['filename'];
                $data['logo_data'] = json_encode($logoData);
            } catch (\Exception $e) {
                return back()->with('error', 'Failed to upload logo: ' . $e->getMessage())->withInput();
            }
        }

        // Convert checkbox value
        $data['is_featured'] = $request->has('is_featured') ? 1 : 0;

        // Create brand
        Brand::create($data);

        return redirect()->route('admin.brands.index')->with('success', 'Brand created successfully!');
    }

    public function show($id)
    {
        $brand = Brand::findOrFail($id);
        return view('admin.brands.show', compact('brand'));
    }

    public function edit($id)
    {
        $brand = Brand::findOrFail($id);
        return view('admin.brands.edit', compact('brand'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:brands,slug,' . $id,
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'website_url' => 'nullable|url',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'status' => 'required|in:Active,Inactive',
            'is_featured' => 'boolean',
        ]);

        $data = $request->except(['logo']);
        
        // Handle logo upload if new file is provided
        if ($request->hasFile('logo')) {
            $brand = Brand::findOrFail($id);
            
            // Delete old logo if exists
            if ($brand->logo_data) {
                $oldLogoData = json_decode($brand->logo_data, true);
                if ($oldLogoData) {
                    $this->deleteImageFiles($oldLogoData);
                }
            } elseif ($brand->logo) {
                // Handle legacy logo deletion
                $this->deleteLegacyImageFile($brand->logo);
            }
            
            try {
                $logoData = $this->uploadBrandImage($request->file('logo'), 'brands');
                $data['logo'] = $logoData['filename'];
                $data['logo_data'] = json_encode($logoData);
            } catch (\Exception $e) {
                return back()->with('error', 'Failed to upload logo: ' . $e->getMessage())->withInput();
            }
        }

        // Convert checkbox value
        $data['is_featured'] = $request->has('is_featured') ? 1 : 0;

        // Update brand
        $brand = Brand::findOrFail($id);
        $brand->update($data);

        return redirect()->route('admin.brands.index')->with('success', 'Brand updated successfully!');
    }

    public function destroy($id)
    {
        try {
            $brand = Brand::findOrFail($id);
            
            // Delete associated image files if they exist
            if ($brand->logo_data) {
                $logoData = json_decode($brand->logo_data, true);
                if ($logoData) {
                    $this->deleteImageFiles($logoData);
                }
            } elseif ($brand->logo) {
                // Handle legacy logo deletion
                $this->deleteLegacyImageFile($brand->logo);
            }
            
            $brand->delete();

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Brand deleted successfully!'
                ]);
            }

            return redirect()->route('admin.brands.index')->with('success', 'Brand deleted successfully!');
        } catch (\Exception $e) {
            Log::error('Error deleting brand: ' . $e->getMessage());
            
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete brand.'
                ], 500);
            }
            
            return back()->with('error', 'Failed to delete brand.');
        }
    }

    /**
     * Toggle brand status
     */
    public function toggleStatus($id)
    {
        try {
            Log::info('Toggle status request received', ['brand_id' => $id]);
            
            $brand = Brand::findOrFail($id);
            $currentStatus = $brand->status;
            $newStatus = $currentStatus === 'Active' ? 'Inactive' : 'Active';
            
            Log::info('Toggling brand status', [
                'brand_id' => $id,
                'current_status' => $currentStatus,
                'new_status' => $newStatus
            ]);
            
            $brand->update(['status' => $newStatus]);
            
            Log::info('Brand status toggled successfully', [
                'brand_id' => $id,
                'status' => $newStatus
            ]);
            
            return response()->json([
                'success' => true,
                'status' => $newStatus,
                'message' => 'Brand status updated successfully.'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error toggling brand status: ' . $e->getMessage(), [
                'brand_id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update brand status.'
            ], 500);
        }
    }

    /**
     * Toggle brand featured status
     */
    public function toggleFeatured($id)
    {
        try {
            Log::info('Toggle featured request received', ['brand_id' => $id]);
            
            $brand = Brand::findOrFail($id);
            $currentFeatured = $brand->is_featured;
            $newFeatured = !$currentFeatured;
            
            Log::info('Toggling brand featured status', [
                'brand_id' => $id,
                'current_featured' => $currentFeatured,
                'new_featured' => $newFeatured
            ]);
            
            $brand->update(['is_featured' => $newFeatured]);
            
            Log::info('Brand featured status toggled successfully', [
                'brand_id' => $id,
                'featured' => $newFeatured
            ]);
            
            return response()->json([
                'success' => true,
                'featured' => $newFeatured,
                'message' => 'Brand featured status updated successfully.'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error toggling brand featured status: ' . $e->getMessage(), [
                'brand_id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update brand featured status.'
            ], 500);
        }
    }

    /**
     * Handle bulk actions
     */
    public function bulkAction(Request $request)
    {
        try {
            $action = $request->input('action');
            $ids = $request->input('ids', []);
            
            if (empty($ids)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No brands selected.'
                ], 400);
            }
            
            $updatedCount = 0;
            
            switch ($action) {
                case 'activate':
                    $updatedCount = Brand::whereIn('id', $ids)->update(['status' => 'Active']);
                    break;
                case 'deactivate':
                    $updatedCount = Brand::whereIn('id', $ids)->update(['status' => 'Inactive']);
                    break;
                case 'feature':
                    $updatedCount = Brand::whereIn('id', $ids)->update(['is_featured' => true]);
                    break;
                case 'unfeature':
                    $updatedCount = Brand::whereIn('id', $ids)->update(['is_featured' => false]);
                    break;
                case 'delete':
                    $brands = Brand::whereIn('id', $ids)->get();
                    foreach ($brands as $brand) {
                        // Delete associated image files
                        if ($brand->logo_data) {
                            $logoData = json_decode($brand->logo_data, true);
                            if ($logoData) {
                                $this->deleteImageFiles($logoData);
                            }
                        } elseif ($brand->logo) {
                            $this->deleteLegacyImageFile($brand->logo);
                        }
                    }
                    $updatedCount = Brand::whereIn('id', $ids)->delete();
                    break;
                default:
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid action.'
                    ], 400);
            }
            
            return response()->json([
                'success' => true,
                'message' => "Bulk {$action} completed successfully. {$updatedCount} brands affected."
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error in bulk action: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to perform bulk action.'
            ], 500);
        }
    }

    /**
     * Validate brand slug
     */
    public function validateSlug(Request $request)
    {
        $slug = $request->input('slug');
        $id = $request->input('id'); // For edit form
        
        if (!$slug) {
            return response()->json([
                'available' => false,
                'message' => 'Slug is required.'
            ]);
        }
        
        $query = Brand::where('slug', $slug);
        
        // Exclude current brand if editing
        if ($id) {
            $query->where('id', '!=', $id);
        }
        
        $exists = $query->exists();
        
        return response()->json([
            'available' => !$exists,
            'message' => $exists ? 'Slug is already taken.' : 'Slug is available.'
        ]);
    }
}
