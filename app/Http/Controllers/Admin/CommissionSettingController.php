<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CommissionSetting;
use Illuminate\Http\Request;

class CommissionSettingController extends Controller
{
    public function index()
    {
        $commissionSettings = CommissionSetting::orderBy('priority')->orderBy('type')->get();
        
        return view('admin.commission-settings.index', compact('commissionSettings'));
    }

    public function create()
    {
        $types = CommissionSetting::getTypes();
        $calculationTypes = CommissionSetting::getCalculationTypes();
        
        return view('admin.commission-settings.create', compact('types', 'calculationTypes'));
    }

    public function store(Request $request)
    {
        // Determine validation rules based on multi-level configuration
        $maxLevelsRule = $request->has('enable_multi_level') && $request->enable_multi_level 
            ? 'required|integer|min:1|max:20' 
            : 'nullable|integer|min:1|max:20';

        // First validate basic fields
        $request->validate([
            'name' => 'required|string|max:255|unique:commission_settings',
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:' . implode(',', array_keys(CommissionSetting::getTypes())),
            'calculation_type' => 'required|in:fixed,percentage',
            'value' => 'required|numeric|min:0',
            'min_qualification' => 'nullable|numeric|min:0',
            'max_payout' => 'nullable|numeric|min:0',
            'max_levels' => $maxLevelsRule,
            'is_active' => 'boolean',
            'priority' => 'nullable|integer|min:0',
            'enable_multi_level' => 'boolean',
            // Calculation basis fields
            'qualification_basis' => 'nullable|in:volume,points',
            'pv_calculation_basis' => 'nullable|in:volume,points',
            'purchase_basis' => 'nullable|in:volume,points'
        ]);

        // Add validation for multi-level configuration
        if ($request->has('enable_multi_level') && $request->enable_multi_level) {
            $request->validate([
                'levels' => 'nullable|array',
                'levels.*.level' => 'required_with:levels|integer|min:1',
                'levels.*.value' => 'required_with:levels|numeric|min:0',
                'levels.*.min_qualification' => 'nullable|numeric|min:0',
                'levels.*.max_payout' => 'nullable|numeric|min:0',
                'levels.*.condition' => 'nullable|string|max:255'
            ]);
        }

        // Add enhanced matching validation rules if type is matching
        if ($request->type === 'matching') {
            $request->validate([
                'carry_forward_enabled' => 'boolean',
                'carry_side' => 'nullable|in:strong,weak,both',
                'carry_percentage' => 'nullable|numeric|min:0|max:100',
                'carry_max_days' => 'nullable|integer|min:1|max:365',
                'slot_matching_enabled' => 'boolean',
                'slot_size' => 'nullable|integer|min:1',
                'slot_type' => 'nullable|in:volume,count,mixed',
                'min_slot_volume' => 'nullable|numeric|min:0',
                'min_slot_count' => 'nullable|integer|min:1',
                'auto_balance_enabled' => 'boolean',
                'balance_ratio' => 'nullable|numeric|min:0.1|max:10',
                'spillover_enabled' => 'boolean',
                'spillover_direction' => 'nullable|in:weaker,stronger,alternate',
                'flush_enabled' => 'boolean',
                'flush_percentage' => 'nullable|numeric|min:0|max:100',
                'daily_cap_enabled' => 'boolean',
                'daily_cap_amount' => 'nullable|numeric|min:0',
                'weekly_cap_enabled' => 'boolean',
                'weekly_cap_amount' => 'nullable|numeric|min:0',
                'matching_frequency' => 'nullable|in:real_time,hourly,daily,weekly',
                'matching_time' => 'nullable|date_format:H:i',
                'personal_volume_required' => 'boolean',
                'min_personal_volume' => 'nullable|numeric|min:0',
                'both_legs_required' => 'boolean',
                'min_left_volume' => 'nullable|numeric|min:0',
                'min_right_volume' => 'nullable|numeric|min:0'
            ]);
        } else {
            // For non-matching types, remove matching-specific fields from request data
            // to prevent validation issues
            $fieldsToRemove = [
                'matching_time', 'matching_frequency', 'carry_forward_enabled', 'carry_side',
                'carry_percentage', 'carry_max_days', 'slot_matching_enabled', 'slot_size',
                'slot_type', 'min_slot_volume', 'min_slot_count', 'auto_balance_enabled',
                'balance_ratio', 'spillover_enabled', 'spillover_direction', 'flush_enabled',
                'flush_percentage', 'daily_cap_enabled', 'daily_cap_amount', 'weekly_cap_enabled',
                'weekly_cap_amount', 'personal_volume_required', 'min_personal_volume',
                'both_legs_required', 'min_left_volume', 'min_right_volume'
            ];
            
            foreach ($fieldsToRemove as $field) {
                $request->request->remove($field);
            }
        }

        $data = $request->all();
        
        // Handle priority field - ensure it has a default value
        if (!isset($data['priority']) || $data['priority'] === null || $data['priority'] === '') {
            $data['priority'] = 0;
        }
        
        // Handle multi-level configuration
        if (!$request->has('enable_multi_level') || !$request->enable_multi_level) {
            // If multi-level is not enabled, set max_levels to 1
            $data['max_levels'] = 1;
            $data['enable_multi_level'] = false;
        } else {
            $data['enable_multi_level'] = true;
        }
        
        // Convert boolean fields to proper boolean values
        $booleanFields = [
            'carry_forward_enabled', 'slot_matching_enabled', 'auto_balance_enabled',
            'spillover_enabled', 'flush_enabled', 'daily_cap_enabled', 'weekly_cap_enabled',
            'personal_volume_required', 'both_legs_required', 'enable_multi_level'
        ];
        
        foreach ($booleanFields as $field) {
            $data[$field] = $request->has($field) ? true : false;
        }
        
        // Process conditions based on commission type
        $conditions = $this->processConditions($request);
        $data['conditions'] = $conditions;
        
        // Process levels if max_levels > 1
        $levels = $this->processLevels($request);
        $data['levels'] = $levels;

        CommissionSetting::create($data);

        return redirect()->route('admin.commission-settings.index')
            ->with('success', 'Commission setting created successfully.');
    }

    public function show(CommissionSetting $commissionSetting)
    {
        return view('admin.commission-settings.show', compact('commissionSetting'));
    }

    public function edit(CommissionSetting $commissionSetting)
    {
        $types = CommissionSetting::getTypes();
        $calculationTypes = CommissionSetting::getCalculationTypes();
        
        return view('admin.commission-settings.edit', compact('commissionSetting', 'types', 'calculationTypes'));
    }

    public function update(Request $request, CommissionSetting $commissionSetting)
    {
        // Determine validation rules based on multi-level configuration
        $maxLevelsRule = $request->has('enable_multi_level') && $request->enable_multi_level 
            ? 'required|integer|min:1|max:20' 
            : 'nullable|integer|min:1|max:20';

        // First validate basic fields
        $request->validate([
            'name' => 'required|string|max:255|unique:commission_settings,name,' . $commissionSetting->id,
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:' . implode(',', array_keys(CommissionSetting::getTypes())),
            'calculation_type' => 'required|in:fixed,percentage',
            'value' => 'required|numeric|min:0',
            'min_qualification' => 'nullable|numeric|min:0',
            'max_payout' => 'nullable|numeric|min:0',
            'max_levels' => $maxLevelsRule,
            'is_active' => 'boolean',
            'priority' => 'nullable|integer|min:0',
            'enable_multi_level' => 'boolean'
        ]);

        // Add validation for multi-level configuration
        if ($request->has('enable_multi_level') && $request->enable_multi_level) {
            $request->validate([
                'levels' => 'nullable|array',
                'levels.*.level' => 'required_with:levels|integer|min:1',
                'levels.*.value' => 'required_with:levels|numeric|min:0',
                'levels.*.min_qualification' => 'nullable|numeric|min:0',
                'levels.*.max_payout' => 'nullable|numeric|min:0',
                'levels.*.condition' => 'nullable|string|max:255'
            ]);
        }

        // Add enhanced matching validation rules if type is matching
        if ($request->type === 'matching') {
            $request->validate([
                'carry_forward_enabled' => 'boolean',
                'carry_side' => 'nullable|in:strong,weak,both',
                'carry_percentage' => 'nullable|numeric|min:0|max:100',
                'carry_max_days' => 'nullable|integer|min:1|max:365',
                'slot_matching_enabled' => 'boolean',
                'slot_size' => 'nullable|integer|min:1',
                'slot_type' => 'nullable|in:volume,count,mixed',
                'min_slot_volume' => 'nullable|numeric|min:0',
                'min_slot_count' => 'nullable|integer|min:1',
                'auto_balance_enabled' => 'boolean',
                'balance_ratio' => 'nullable|numeric|min:0.1|max:10',
                'spillover_enabled' => 'boolean',
                'spillover_direction' => 'nullable|in:weaker,stronger,alternate',
                'flush_enabled' => 'boolean',
                'flush_percentage' => 'nullable|numeric|min:0|max:100',
                'daily_cap_enabled' => 'boolean',
                'daily_cap_amount' => 'nullable|numeric|min:0',
                'weekly_cap_enabled' => 'boolean',
                'weekly_cap_amount' => 'nullable|numeric|min:0',
                'matching_frequency' => 'nullable|in:real_time,hourly,daily,weekly',
                'matching_time' => 'nullable|date_format:H:i',
                'personal_volume_required' => 'boolean',
                'min_personal_volume' => 'nullable|numeric|min:0',
                'both_legs_required' => 'boolean',
                'min_left_volume' => 'nullable|numeric|min:0',
                'min_right_volume' => 'nullable|numeric|min:0'
            ]);
        } else {
            // For non-matching types, remove matching-specific fields from request data
            // to prevent validation issues
            $fieldsToRemove = [
                'matching_time', 'matching_frequency', 'carry_forward_enabled', 'carry_side',
                'carry_percentage', 'carry_max_days', 'slot_matching_enabled', 'slot_size',
                'slot_type', 'min_slot_volume', 'min_slot_count', 'auto_balance_enabled',
                'balance_ratio', 'spillover_enabled', 'spillover_direction', 'flush_enabled',
                'flush_percentage', 'daily_cap_enabled', 'daily_cap_amount', 'weekly_cap_enabled',
                'weekly_cap_amount', 'personal_volume_required', 'min_personal_volume',
                'both_legs_required', 'min_left_volume', 'min_right_volume'
            ];
            
            foreach ($fieldsToRemove as $field) {
                $request->request->remove($field);
            }
        }

        $data = $request->all();
        
        // Handle priority field - ensure it has a default value
        if (!isset($data['priority']) || $data['priority'] === null || $data['priority'] === '') {
            $data['priority'] = 0;
        }
        
        // Handle multi-level configuration
        if (!$request->has('enable_multi_level') || !$request->enable_multi_level) {
            // If multi-level is not enabled, set max_levels to 1
            $data['max_levels'] = 1;
            $data['enable_multi_level'] = false;
        } else {
            $data['enable_multi_level'] = true;
        }
        
        // Convert boolean fields to proper boolean values
        $booleanFields = [
            'carry_forward_enabled', 'slot_matching_enabled', 'auto_balance_enabled',
            'spillover_enabled', 'flush_enabled', 'daily_cap_enabled', 'weekly_cap_enabled',
            'personal_volume_required', 'both_legs_required', 'enable_multi_level'
        ];
        
        foreach ($booleanFields as $field) {
            $data[$field] = $request->has($field) ? true : false;
        }
        
        // Process conditions based on commission type
        $conditions = $this->processConditions($request);
        $data['conditions'] = $conditions;
        
        // Process levels if max_levels > 1
        $levels = $this->processLevels($request);
        $data['levels'] = $levels;

        $commissionSetting->update($data);

        return redirect()->route('admin.commission-settings.index')
            ->with('success', 'Commission setting updated successfully.');
    }

    public function destroy(CommissionSetting $commissionSetting)
    {
        $commissionSetting->delete();

        return redirect()->route('admin.commission-settings.index')
            ->with('success', 'Commission setting deleted successfully.');
    }

    public function toggleStatus(CommissionSetting $commissionSetting)
    {
        $commissionSetting->update([
            'is_active' => !$commissionSetting->is_active
        ]);

        $status = $commissionSetting->is_active ? 'activated' : 'deactivated';
        
        return response()->json([
            'success' => true,
            'message' => "Commission setting {$status} successfully.",
            'is_active' => $commissionSetting->is_active
        ]);
    }

    private function processConditions(Request $request)
    {
        $conditions = [];

        switch ($request->type) {
            case 'rank':
                $conditions = [
                    'required_rank' => $request->required_rank,
                    'rank_duration' => $request->rank_duration
                ];
                break;
            case 'club':
                $conditions = [
                    'required_club' => $request->required_club,
                    'club_volume' => $request->club_volume
                ];
                break;
            case 'generation':
                $conditions = [
                    'generation_level' => $request->generation_level,
                    'min_generation_volume' => $request->min_generation_volume
                ];
                break;
        }

        return $conditions;
    }

    private function processLevels(Request $request)
    {
        $levels = [];
        
        // Check if multi-level is enabled
        if ($request->has('enable_multi_level') && $request->enable_multi_level) {
            // Handle array-based levels input format: levels[0][value], levels[1][value], etc.
            if ($request->has('levels') && is_array($request->levels)) {
                foreach ($request->levels as $levelData) {
                    if (isset($levelData['value']) && $levelData['value'] !== null) {
                        $levels[] = [
                            'level' => $levelData['level'] ?? count($levels) + 1,
                            'value' => floatval($levelData['value']),
                            'min_qualification' => isset($levelData['min_qualification']) ? floatval($levelData['min_qualification']) : null,
                            'max_payout' => isset($levelData['max_payout']) ? floatval($levelData['max_payout']) : null,
                            'condition' => $levelData['condition'] ?? null
                        ];
                    }
                }
            }
            
            // Fallback: Handle individual level inputs format: level_1_value, level_2_value, etc.
            if (empty($levels) && $request->max_levels > 1) {
                for ($i = 1; $i <= $request->max_levels; $i++) {
                    $levelValue = $request->input("level_{$i}_value");
                    $levelMinQualification = $request->input("level_{$i}_min_qualification");
                    $levelMaxPayout = $request->input("level_{$i}_max_payout");
                    
                    if ($levelValue !== null) {
                        $levels[] = [
                            'level' => $i,
                            'value' => floatval($levelValue),
                            'min_qualification' => $levelMinQualification ? floatval($levelMinQualification) : null,
                            'max_payout' => $levelMaxPayout ? floatval($levelMaxPayout) : null
                        ];
                    }
                }
            }
        }
        
        return $levels;
    }
}
