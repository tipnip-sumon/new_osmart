<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Unit::query();
        
        // Apply filters
        if ($request->filled('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
        }
        
        if ($request->filled('status')) {
            $active = $request->status === 'active';
            $query->where('is_active', $active);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('symbol', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        $units = $query->with('baseUnit')
                    ->orderBy('type')
                    ->orderBy('sort_order')
                    ->orderBy('name')
                    ->paginate(15);
        
        // Get statistics for all units (not filtered)
        $stats = [
            'total' => Unit::count(),
            'weight' => Unit::where('type', 'weight')->count(),
            'length' => Unit::where('type', 'length')->count(),
            'volume' => Unit::where('type', 'volume')->count(),
            'area' => Unit::where('type', 'area')->count(),
            'active' => Unit::where('is_active', true)->count(),
        ];
        
        return view('admin.units.index', compact('units', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $types = Unit::TYPES;
        $baseUnits = Unit::where('is_active', true)->get()->groupBy('type');
        
        return view('admin.units.create', compact('types', 'baseUnits'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'symbol' => 'required|string|max:10',
            'type' => ['required', Rule::in(array_keys(Unit::TYPES))],
            'description' => 'nullable|string|max:1000',
            'base_factor' => 'nullable|numeric|min:0.000001',
            'base_unit_id' => 'nullable|exists:units,id',
            'is_active' => 'boolean',
            'is_default' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ], [
            'name.required' => 'Unit name is required',
            'symbol.required' => 'Unit symbol is required',
            'type.required' => 'Unit type is required',
            'type.in' => 'Invalid unit type selected',
        ]);

        // Check for unique name and symbol within the same type
        $existingName = Unit::where('name', $request->name)
                           ->where('type', $request->type)
                           ->first();
        
        if ($existingName) {
            return back()->withErrors(['name' => 'A unit with this name already exists for this type.'])->withInput();
        }

        $existingSymbol = Unit::where('symbol', $request->symbol)
                             ->where('type', $request->type)
                             ->first();
        
        if ($existingSymbol) {
            return back()->withErrors(['symbol' => 'A unit with this symbol already exists for this type.'])->withInput();
        }

        $data = $request->all();
        
        // Handle boolean fields
        $data['is_active'] = $request->has('is_active');
        $data['is_default'] = $request->has('is_default');
        
        // Set default values
        $data['base_factor'] = $data['base_factor'] ?? 1.0;
        $data['sort_order'] = $data['sort_order'] ?? 0;

        // If this is set as default, unset other defaults for this type
        if ($data['is_default']) {
            Unit::where('type', $data['type'])->update(['is_default' => false]);
        }

        Unit::create($data);

        return redirect()->route('admin.units.index')->with('success', 'Unit created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Unit $unit)
    {
        $unit->load(['baseUnit', 'derivedUnits', 'products']);
        
        return view('admin.units.show', compact('unit'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Unit $unit)
    {
        $types = Unit::TYPES;
        $baseUnits = Unit::where('is_active', true)
                        ->where('id', '!=', $unit->id)
                        ->get()
                        ->groupBy('type');
        
        return view('admin.units.edit', compact('unit', 'types', 'baseUnits'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Unit $unit)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'symbol' => 'required|string|max:10',
            'type' => ['required', Rule::in(array_keys(Unit::TYPES))],
            'description' => 'nullable|string|max:1000',
            'base_factor' => 'nullable|numeric|min:0.000001',
            'base_unit_id' => 'nullable|exists:units,id',
            'is_active' => 'boolean',
            'is_default' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        // Check for unique name and symbol within the same type (excluding current unit)
        $existingName = Unit::where('name', $request->name)
                           ->where('type', $request->type)
                           ->where('id', '!=', $unit->id)
                           ->first();
        
        if ($existingName) {
            return back()->withErrors(['name' => 'A unit with this name already exists for this type.'])->withInput();
        }

        $existingSymbol = Unit::where('symbol', $request->symbol)
                             ->where('type', $request->type)
                             ->where('id', '!=', $unit->id)
                             ->first();
        
        if ($existingSymbol) {
            return back()->withErrors(['symbol' => 'A unit with this symbol already exists for this type.'])->withInput();
        }

        $data = $request->all();
        
        // Handle boolean fields
        $data['is_active'] = $request->has('is_active');
        $data['is_default'] = $request->has('is_default');
        
        // Set default values
        $data['base_factor'] = $data['base_factor'] ?? 1.0;
        $data['sort_order'] = $data['sort_order'] ?? 0;

        // If this is set as default, unset other defaults for this type
        if ($data['is_default']) {
            Unit::where('type', $data['type'])
               ->where('id', '!=', $unit->id)
               ->update(['is_default' => false]);
        }

        $unit->update($data);

        return redirect()->route('admin.units.index')->with('success', 'Unit updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Unit $unit)
    {
        // Check if unit is being used by products
        if ($unit->products()->count() > 0) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete unit as it is being used by products.'
                ], 422);
            }
            return redirect()->route('admin.units.index')
                           ->with('error', 'Cannot delete unit as it is being used by products.');
        }

        // Check if unit is being used as base unit by other units
        if ($unit->derivedUnits()->count() > 0) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete unit as it is being used as base unit by other units.'
                ], 422);
            }
            return redirect()->route('admin.units.index')
                           ->with('error', 'Cannot delete unit as it is being used as base unit by other units.');
        }

        $unit->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Unit deleted successfully'
            ]);
        }

        return redirect()->route('admin.units.index')->with('success', 'Unit deleted successfully');
    }

    /**
     * Toggle the status of the specified resource.
     */
    public function toggleStatus(Request $request, Unit $unit)
    {
        $request->validate([
            'is_active' => 'sometimes|boolean'
        ]);

        // Use the provided is_active value or toggle current status
        $isActive = $request->has('is_active') ? $request->boolean('is_active') : !$unit->is_active;
        
        $unit->update(['is_active' => $isActive]);
        
        $status = $unit->is_active ? 'activated' : 'deactivated';
        
        return response()->json([
            'success' => true, 
            'message' => "Unit {$status} successfully",
            'is_active' => $unit->is_active
        ]);
    }

    /**
     * Handle bulk actions.
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete',
            'ids' => 'required|array',
            'ids.*' => 'exists:units,id'
        ]);

        $units = Unit::whereIn('id', $request->ids);
        $count = $units->count();

        switch ($request->action) {
            case 'activate':
                $units->update(['is_active' => true]);
                $message = "{$count} units activated successfully";
                break;
            
            case 'deactivate':
                $units->update(['is_active' => false]);
                $message = "{$count} units deactivated successfully";
                break;
            
            case 'delete':
                // Check if any unit is being used
                $unitsInUse = Unit::whereIn('id', $request->ids)
                                 ->where(function($query) {
                                     $query->has('products')
                                           ->orHas('derivedUnits');
                                 })
                                 ->pluck('name');
                
                if ($unitsInUse->count() > 0) {
                    return redirect()->route('admin.units.index')
                                   ->with('error', 'Cannot delete units that are in use: ' . $unitsInUse->implode(', '));
                }
                
                $units->delete();
                $message = "{$count} units deleted successfully";
                break;
        }

        return redirect()->route('admin.units.index')->with('success', $message);
    }

    /**
     * Export units to CSV.
     */
    public function export()
    {
        $units = Unit::with('baseUnit')->orderBy('type')->orderBy('name')->get();
        
        $filename = 'units_export_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($units) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'ID', 'Name', 'Symbol', 'Type', 'Description', 
                'Base Factor', 'Base Unit', 'Is Active', 'Is Default', 
                'Sort Order', 'Created At'
            ]);
            
            // CSV data
            foreach ($units as $unit) {
                fputcsv($file, [
                    $unit->id,
                    $unit->name,
                    $unit->symbol,
                    $unit->type_name,
                    $unit->description,
                    $unit->base_factor,
                    $unit->baseUnit ? $unit->baseUnit->name : '',
                    $unit->is_active ? 'Yes' : 'No',
                    $unit->is_default ? 'Yes' : 'No',
                    $unit->sort_order,
                    $unit->created_at->format('Y-m-d H:i:s'),
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Show analytics for units.
     */
    public function analytics()
    {
        $analytics = [
            'total_units' => Unit::count(),
            'active_units' => Unit::active()->count(),
            'inactive_units' => Unit::where('is_active', false)->count(),
            'units_by_type' => Unit::selectRaw('type, COUNT(*) as count')
                                  ->groupBy('type')
                                  ->pluck('count', 'type'),
            'units_with_products' => Unit::has('products')->count(),
            'units_without_products' => Unit::doesntHave('products')->count(),
            'recent_units' => Unit::where('created_at', '>=', now()->subDays(30))->count(),
            'top_used_units' => Unit::withCount('products')
                                   ->orderBy('products_count', 'desc')
                                   ->limit(5)
                                   ->get(),
            'monthly_stats' => Unit::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
                                  ->where('created_at', '>=', now()->subYear())
                                  ->groupBy('month')
                                  ->pluck('count', 'month'),
        ];
        
        return view('admin.units.analytics', compact('analytics'));
    }

    /**
     * Show weight units.
     */
    public function weight()
    {
        $weightUnits = Unit::byType('weight')
                          ->with('baseUnit')
                          ->orderBy('sort_order')
                          ->orderBy('name')
                          ->get();
        
        return view('admin.units.weight', compact('weightUnits'));
    }

    /**
     * Show dimension units.
     */
    public function dimensions()
    {
        $lengthUnits = Unit::byType('length')->with('baseUnit')->orderBy('sort_order')->get();
        $areaUnits = Unit::byType('area')->with('baseUnit')->orderBy('sort_order')->get();
        $volumeUnits = Unit::byType('volume')->with('baseUnit')->orderBy('sort_order')->get();
        
        return view('admin.units.dimensions', compact('lengthUnits', 'areaUnits', 'volumeUnits'));
    }
}
