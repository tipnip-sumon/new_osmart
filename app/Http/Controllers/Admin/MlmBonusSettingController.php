<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MlmBonusSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MlmBonusSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = MlmBonusSetting::query();

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('setting_type', $request->type);
        }

        // Filter by status
        if ($request->filled('status')) {
            $status = $request->status === 'active';
            $query->where('is_active', $status);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('setting_name', 'LIKE', "%{$search}%")
                  ->orWhere('setting_key', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        $settings = $query->orderBy('category')
                          ->orderBy('level')
                          ->orderBy('setting_name')
                          ->paginate(20);

        $categories = MlmBonusSetting::CATEGORIES;
        $types = MlmBonusSetting::SETTING_TYPES;

        return view('admin.mlm-bonus-settings.index', compact('settings', 'categories', 'types'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = MlmBonusSetting::CATEGORIES;
        $types = MlmBonusSetting::SETTING_TYPES;
        $calculationMethods = MlmBonusSetting::CALCULATION_METHODS;

        return view('admin.mlm-bonus-settings.create', compact('categories', 'types', 'calculationMethods'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'setting_name' => 'required|string|max:255',
            'setting_key' => 'required|string|max:255|unique:mlm_bonus_settings,setting_key',
            'description' => 'nullable|string',
            'setting_type' => 'required|in:percentage,fixed,boolean,array',
            'value' => 'required|numeric',
            'min_value' => 'nullable|numeric',
            'max_value' => 'nullable|numeric',
            'category' => 'required|string',
            'subcategory' => 'nullable|string',
            'level' => 'nullable|integer|min:1',
            'threshold_amount' => 'nullable|numeric|min:0',
            'threshold_count' => 'nullable|integer|min:0',
            'calculation_method' => 'required|in:percentage,fixed,sliding_scale,tier_based',
            'requires_kyc' => 'boolean',
            'requires_rank' => 'boolean',
            'rank_required' => 'nullable|string',
            'formula' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        // Auto-generate setting key if not provided
        if (!$request->setting_key) {
            $request->merge(['setting_key' => Str::slug($request->setting_name, '_')]);
        }

        // Process additional settings based on calculation method
        $additionalSettings = [];
        if ($request->calculation_method === 'sliding_scale') {
            $additionalSettings['scales'] = $request->input('scales', []);
        } elseif ($request->calculation_method === 'tier_based') {
            $additionalSettings['tiers'] = $request->input('tiers', []);
        }

        // Process conditions
        $conditions = [];
        if ($request->filled('condition_keys')) {
            $keys = $request->input('condition_keys', []);
            $values = $request->input('condition_values', []);
            
            foreach ($keys as $index => $key) {
                if (!empty($key) && isset($values[$index])) {
                    $conditions[$key] = $values[$index];
                }
            }
        }

        $data = $request->all();
        $data['additional_settings'] = $additionalSettings;
        $data['conditions'] = $conditions;

        MlmBonusSetting::create($data);

        return redirect()->route('admin.mlm-bonus-settings.index')
                        ->with('success', 'MLM bonus setting created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(MlmBonusSetting $mlmBonusSetting)
    {
        return view('admin.mlm-bonus-settings.show', compact('mlmBonusSetting'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MlmBonusSetting $mlmBonusSetting)
    {
        $categories = MlmBonusSetting::CATEGORIES;
        $types = MlmBonusSetting::SETTING_TYPES;
        $calculationMethods = MlmBonusSetting::CALCULATION_METHODS;

        return view('admin.mlm-bonus-settings.edit', compact('mlmBonusSetting', 'categories', 'types', 'calculationMethods'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MlmBonusSetting $mlmBonusSetting)
    {
        $request->validate([
            'setting_name' => 'required|string|max:255',
            'setting_key' => 'required|string|max:255|unique:mlm_bonus_settings,setting_key,' . $mlmBonusSetting->id,
            'description' => 'nullable|string',
            'setting_type' => 'required|in:percentage,fixed,boolean,array',
            'value' => 'required|numeric',
            'min_value' => 'nullable|numeric',
            'max_value' => 'nullable|numeric',
            'category' => 'required|string',
            'subcategory' => 'nullable|string',
            'level' => 'nullable|integer|min:1',
            'threshold_amount' => 'nullable|numeric|min:0',
            'threshold_count' => 'nullable|integer|min:0',
            'calculation_method' => 'required|in:percentage,fixed,sliding_scale,tier_based',
            'requires_kyc' => 'boolean',
            'requires_rank' => 'boolean',
            'rank_required' => 'nullable|string',
            'formula' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        // Check if setting is editable
        if (!$mlmBonusSetting->is_editable) {
            return redirect()->back()->withErrors(['error' => 'This setting is not editable.']);
        }

        // Process additional settings based on calculation method
        $additionalSettings = [];
        if ($request->calculation_method === 'sliding_scale') {
            $additionalSettings['scales'] = $request->input('scales', []);
        } elseif ($request->calculation_method === 'tier_based') {
            $additionalSettings['tiers'] = $request->input('tiers', []);
        }

        // Process conditions
        $conditions = [];
        if ($request->filled('condition_keys')) {
            $keys = $request->input('condition_keys', []);
            $values = $request->input('condition_values', []);
            
            foreach ($keys as $index => $key) {
                if (!empty($key) && isset($values[$index])) {
                    $conditions[$key] = $values[$index];
                }
            }
        }

        $data = $request->all();
        $data['additional_settings'] = $additionalSettings;
        $data['conditions'] = $conditions;

        $mlmBonusSetting->update($data);

        return redirect()->route('admin.mlm-bonus-settings.index')
                        ->with('success', 'MLM bonus setting updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MlmBonusSetting $mlmBonusSetting)
    {
        if (!$mlmBonusSetting->is_editable) {
            return response()->json(['error' => 'This setting cannot be deleted.'], 403);
        }

        $mlmBonusSetting->delete();

        return response()->json(['success' => 'MLM bonus setting deleted successfully.']);
    }

    /**
     * Toggle status of the setting
     */
    public function toggleStatus(MlmBonusSetting $mlmBonusSetting)
    {
        $mlmBonusSetting->update(['is_active' => !$mlmBonusSetting->is_active]);

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully.',
            'is_active' => $mlmBonusSetting->is_active
        ]);
    }

    /**
     * Bulk actions
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete',
            'ids' => 'required|array',
            'ids.*' => 'exists:mlm_bonus_settings,id'
        ]);

        $settings = MlmBonusSetting::whereIn('id', $request->ids);

        switch ($request->action) {
            case 'activate':
                $settings->update(['is_active' => true]);
                $message = 'Selected settings activated successfully.';
                break;
            case 'deactivate':
                $settings->update(['is_active' => false]);
                $message = 'Selected settings deactivated successfully.';
                break;
            case 'delete':
                $settings->where('is_editable', true)->delete();
                $message = 'Selected editable settings deleted successfully.';
                break;
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Initialize default settings
     */
    public function initializeDefaults()
    {
        $defaultSettings = $this->getDefaultSettings();

        foreach ($defaultSettings as $setting) {
            MlmBonusSetting::updateOrCreate(
                ['setting_key' => $setting['setting_key']],
                $setting
            );
        }

        return redirect()->route('admin.mlm-bonus-settings.index')
                        ->with('success', 'Default MLM bonus settings initialized successfully.');
    }

    /**
     * Get default settings
     */
    private function getDefaultSettings()
    {
        return [
            // Sponsor Commission
            [
                'setting_key' => 'sponsor_commission_direct',
                'setting_name' => 'Direct Sponsor Commission',
                'description' => 'Commission for direct sponsorship',
                'setting_type' => 'percentage',
                'value' => 10.00,
                'category' => 'sponsor_commission',
                'calculation_method' => 'percentage',
                'is_active' => true,
                'is_editable' => true,
            ],
            
            // Binary Matching
            [
                'setting_key' => 'binary_matching_percentage',
                'setting_name' => 'Binary Matching Percentage',
                'description' => 'Percentage for binary matching bonus',
                'setting_type' => 'percentage',
                'value' => 5.00,
                'category' => 'binary_matching',
                'calculation_method' => 'percentage',
                'threshold_amount' => 100.00,
                'is_active' => true,
                'is_editable' => true,
            ],
            
            // Unilevel Commission (Multiple Levels)
            [
                'setting_key' => 'unilevel_level_1',
                'setting_name' => 'Unilevel Level 1 Commission',
                'description' => 'Commission for level 1 in unilevel plan',
                'setting_type' => 'percentage',
                'value' => 8.00,
                'category' => 'unilevel',
                'level' => 1,
                'calculation_method' => 'percentage',
                'is_active' => true,
                'is_editable' => true,
            ],
            [
                'setting_key' => 'unilevel_level_2',
                'setting_name' => 'Unilevel Level 2 Commission',
                'description' => 'Commission for level 2 in unilevel plan',
                'setting_type' => 'percentage',
                'value' => 5.00,
                'category' => 'unilevel',
                'level' => 2,
                'calculation_method' => 'percentage',
                'is_active' => true,
                'is_editable' => true,
            ],
            [
                'setting_key' => 'unilevel_level_3',
                'setting_name' => 'Unilevel Level 3 Commission',
                'description' => 'Commission for level 3 in unilevel plan',
                'setting_type' => 'percentage',
                'value' => 3.00,
                'category' => 'unilevel',
                'level' => 3,
                'calculation_method' => 'percentage',
                'is_active' => true,
                'is_editable' => true,
            ],
            
            // Generation Commission
            [
                'setting_key' => 'generation_level_1',
                'setting_name' => 'Generation Level 1 Commission',
                'description' => 'Commission for generation level 1',
                'setting_type' => 'percentage',
                'value' => 12.00,
                'category' => 'generation',
                'level' => 1,
                'calculation_method' => 'percentage',
                'requires_rank' => true,
                'rank_required' => 'Team Leader',
                'is_active' => true,
                'is_editable' => true,
            ],
            
            // Rank Bonus
            [
                'setting_key' => 'rank_promotion_bonus',
                'setting_name' => 'Rank Promotion Bonus',
                'description' => 'One-time bonus for rank promotion',
                'setting_type' => 'fixed',
                'value' => 500.00,
                'category' => 'rank',
                'calculation_method' => 'fixed',
                'is_active' => true,
                'is_editable' => true,
            ],
            
            // Club Bonus
            [
                'setting_key' => 'club_monthly_bonus',
                'setting_name' => 'Club Monthly Bonus',
                'description' => 'Monthly bonus for club members',
                'setting_type' => 'fixed',
                'value' => 100.00,
                'category' => 'club',
                'calculation_method' => 'fixed',
                'requires_kyc' => true,
                'is_active' => true,
                'is_editable' => true,
            ],
            
            // Daily Cashback
            [
                'setting_key' => 'daily_cashback_percentage',
                'setting_name' => 'Daily Cashback Percentage',
                'description' => 'Daily cashback on purchases',
                'setting_type' => 'percentage',
                'value' => 1.00,
                'category' => 'daily_cashback',
                'calculation_method' => 'percentage',
                'threshold_amount' => 50.00,
                'is_active' => true,
                'is_editable' => true,
            ],
        ];
    }
}
