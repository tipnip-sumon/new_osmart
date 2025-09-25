<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminMenu;
use App\Helpers\AdminMenuHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

class AdminMenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = AdminMenu::with(['parent', 'children'])
                          ->when($request->search, function ($q) use ($request) {
                              $q->where('title', 'like', '%' . $request->search . '%')
                                ->orWhere('route', 'like', '%' . $request->search . '%');
                          })
                          ->when($request->status !== null, function ($q) use ($request) {
                              $q->where('is_active', $request->status);
                          })
                          ->when($request->type, function ($q) use ($request) {
                              $q->where('menu_type', $request->type);
                          })
                          ->orderBy('sort_order');

        if ($request->ajax()) {
            $menus = $query->paginate(20);
            return response()->json([
                'html' => view('admin.menu.partials.menu-table', compact('menus'))->render(),
                'pagination' => $menus->links()->render(),
            ]);
        }

        $menus = $query->paginate(20);
        
        return view('admin.menu.index', compact('menus'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $parentMenus = AdminMenu::whereNull('parent_id')
                                ->where('is_active', true)
                                ->orderBy('sort_order')
                                ->get();
        
        $routes = $this->getAvailableRoutes();
        
        return view('admin.menu.create', compact('parentMenus', 'routes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'route' => 'nullable|string|max:255',
            'url' => 'nullable|string|max:255',
            'parent_id' => 'nullable|exists:admin_menus,id',
            'sort_order' => 'nullable|integer|min:0',
            'permission' => 'nullable|string|max:255',
            'badge_text' => 'nullable|string|max:50',
            'badge_color' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:500',
            'target' => 'nullable|in:_self,_blank',
            'is_external' => 'nullable|boolean',
            'menu_type' => 'required|in:main,sidebar,both',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();
        $data['is_active'] = $request->has('is_active');
        $data['is_external'] = $request->has('is_external');
        $data['target'] = $data['target'] ?? '_self';

        AdminMenu::create($data);

        return redirect()->route('admin.menu.index')
                        ->with('success', 'Menu item created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(AdminMenu $menu)
    {
        $menu->load(['parent', 'children.children']);
        return view('admin.menu.show', compact('menu'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AdminMenu $menu)
    {
        $parentMenus = AdminMenu::whereNull('parent_id')
                                ->where('id', '!=', $menu->id)
                                ->where('is_active', true)
                                ->orderBy('sort_order')
                                ->get();
        
        $routes = $this->getAvailableRoutes();
        
        return view('admin.menu.edit', compact('menu', 'parentMenus', 'routes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AdminMenu $menu)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'route' => 'nullable|string|max:255',
            'url' => 'nullable|string|max:255',
            'parent_id' => 'nullable|exists:admin_menus,id',
            'sort_order' => 'nullable|integer|min:0',
            'permission' => 'nullable|string|max:255',
            'badge_text' => 'nullable|string|max:50',
            'badge_color' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:500',
            'target' => 'nullable|in:_self,_blank',
            'is_external' => 'nullable|boolean',
            'menu_type' => 'required|in:main,sidebar,both',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();
        $data['is_active'] = $request->has('is_active');
        $data['is_external'] = $request->has('is_external');
        $data['target'] = $data['target'] ?? '_self';

        $menu->update($data);

        return redirect()->route('admin.menu.index')
                        ->with('success', 'Menu item updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AdminMenu $menu)
    {
        try {
            // Check if menu has children
            if ($menu->children()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete menu item with children. Please delete children first.'
                ], 422);
            }

            $menu->delete();

            return response()->json([
                'success' => true,
                'message' => 'Menu item deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting menu item: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menu builder interface
     */
    public function builder()
    {
        $menus = AdminMenu::with(['children' => function ($query) {
            $query->orderBy('sort_order');
        }])
        ->whereNull('parent_id')
        ->orderBy('sort_order')
        ->get();

        return view('admin.menu.builder', compact('menus'));
    }

    /**
     * Update menu order
     */
    public function updateOrder(Request $request)
    {
        try {
            DB::beginTransaction();

            $menuOrder = $request->input('menu_order', []);
            
            foreach ($menuOrder as $order => $menuData) {
                $menu = AdminMenu::find($menuData['id']);
                if ($menu) {
                    $menu->update([
                        'sort_order' => $order + 1,
                        'parent_id' => $menuData['parent_id'] ?? null,
                    ]);

                    // Update children order
                    if (isset($menuData['children'])) {
                        foreach ($menuData['children'] as $childOrder => $childData) {
                            $childMenu = AdminMenu::find($childData['id']);
                            if ($childMenu) {
                                $childMenu->update([
                                    'sort_order' => $childOrder + 1,
                                    'parent_id' => $menu->id,
                                ]);
                            }
                        }
                    }
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Menu order updated successfully.'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            
            return response()->json([
                'success' => false,
                'message' => 'Error updating menu order: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle menu status
     */
    public function toggleStatus(AdminMenu $menu)
    {
        try {
            $menu->update(['is_active' => !$menu->is_active]);

            return response()->json([
                'success' => true,
                'message' => 'Menu status updated successfully.',
                'status' => $menu->is_active
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating menu status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Duplicate a menu item
     */
    public function duplicate(AdminMenu $menu)
    {
        try {
            DB::beginTransaction();

            $newMenu = $menu->replicate();
            $newMenu->title = $menu->title . ' (Copy)';
            $newMenu->sort_order = AdminMenu::where('parent_id', $menu->parent_id)->max('sort_order') + 1;
            $newMenu->save();

            // Duplicate children if any
            foreach ($menu->children as $child) {
                $newChild = $child->replicate();
                $newChild->parent_id = $newMenu->id;
                $newChild->sort_order = $child->sort_order;
                $newChild->save();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Menu item duplicated successfully.'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            
            return response()->json([
                'success' => false,
                'message' => 'Error duplicating menu item: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get available routes
     */
    private function getAvailableRoutes()
    {
        $routes = collect(Route::getRoutes())->map(function ($route) {
            return [
                'name' => $route->getName(),
                'uri' => $route->uri(),
                'methods' => $route->methods(),
            ];
        })->filter(function ($route) {
            return $route['name'] && 
                   str_starts_with($route['name'], 'admin.') &&
                   in_array('GET', $route['methods']);
        })->sortBy('name');

        return $routes;
    }

    /**
     * Export menu configuration
     */
    public function export()
    {
        $menus = AdminMenu::with(['children' => function ($query) {
            $query->orderBy('sort_order');
        }])
        ->whereNull('parent_id')
        ->orderBy('sort_order')
        ->get();

        $filename = 'admin_menu_export_' . date('Y_m_d_H_i_s') . '.json';
        
        return response()->json($menus->toArray())
                        ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * Import menu configuration
     */
    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'menu_file' => 'required|file|mimes:json',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        try {
            $content = file_get_contents($request->file('menu_file')->getRealPath());
            $menuData = json_decode($content, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Invalid JSON file');
            }

            DB::beginTransaction();

            // Optionally clear existing menus
            if ($request->has('clear_existing')) {
                AdminMenu::truncate();
            }

            $this->importMenuItems($menuData);

            DB::commit();

            return redirect()->route('admin.menu.index')
                           ->with('success', 'Menu configuration imported successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            
            return back()->withErrors(['menu_file' => 'Error importing menu: ' . $e->getMessage()]);
        }
    }

    /**
     * Import menu items recursively
     */
    private function importMenuItems($menus, $parentId = null)
    {
        foreach ($menus as $menuData) {
            $children = $menuData['children'] ?? [];
            unset($menuData['children'], $menuData['id'], $menuData['created_at'], $menuData['updated_at'], $menuData['deleted_at']);

            $menuData['parent_id'] = $parentId;
            $menu = AdminMenu::create($menuData);

            if (!empty($children)) {
                $this->importMenuItems($children, $menu->id);
            }
        }
    }

    /**
     * Clear menu cache
     */
    public function clearCache()
    {
        try {
            AdminMenuHelper::clearCache();
            
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Menu cache cleared successfully!'
                ]);
            }
            
            return redirect()->back()->with('success', 'Menu cache cleared successfully!');
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error clearing cache: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()->with('error', 'Error clearing cache: ' . $e->getMessage());
        }
    }
}
