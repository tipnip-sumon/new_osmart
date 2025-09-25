<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminNotice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminNoticeController extends Controller
{
    /**
     * Display a listing of the notices.
     */
    public function index()
    {
        return view('admin.notices.index');
    }

    /**
     * Get notices data for DataTables (AJAX)
     */
    public function getData(Request $request)
    {
        // Start with base query
        $baseQuery = AdminNotice::query();

        // Apply search filters to base query
        if ($request->has('search') && isset($request->search['value']) && $request->search['value'] !== null && trim($request->search['value']) !== '') {
            $searchValue = trim($request->search['value']);
            $baseQuery->where(function ($q) use ($searchValue) {
                $q->where('message', 'like', '%' . $searchValue . '%')
                  ->orWhere('type', 'like', '%' . $searchValue . '%');
            });
        }

        // Apply status filter
        if ($request->has('status') && $request->status !== null && $request->status !== '') {
            $baseQuery->where('is_active', $request->status);
        }

        // Apply type filter
        if ($request->has('type') && $request->type !== null && $request->type !== '') {
            $baseQuery->where('type', $request->type);
        }

        // Get total records (without any filters)
        $totalRecords = AdminNotice::count();
        
        // Get filtered records count (with search/filter conditions applied)
        $filteredRecords = $baseQuery->count();

        // Clone the base query for data retrieval
        $dataQuery = clone $baseQuery;

        // Apply ordering
        if ($request->has('order') && $request->order && count($request->order) > 0) {
            $orderColumnIndex = $request->order[0]['column'] ?? 0;
            $orderDirection = $request->order[0]['dir'] ?? 'asc';
            
            // Define column mapping to match frontend DataTable columns
            $columns = [
                0 => 'id',           // Checkbox column
                1 => 'message',      // Message
                2 => 'type',         // Type
                3 => 'priority',     // Priority  
                4 => 'is_active',    // Status
                5 => 'start_date',   // Start Date
                6 => 'end_date',     // End Date
                7 => 'created_at',   // Created
                8 => 'id'            // Actions (use id as fallback, not sortable anyway)
            ];
            
            $orderColumn = $columns[$orderColumnIndex] ?? 'created_at';
            $dataQuery->orderBy($orderColumn, $orderDirection);
        } else {
            $dataQuery->orderBy('priority', 'desc')->orderBy('created_at', 'desc');
        }

        // Apply pagination
        if ($request->has('start') && $request->has('length')) {
            $dataQuery->skip($request->start)->take($request->length);
        }

        $notices = $dataQuery->get();

        $data = [];
        foreach ($notices as $notice) {
            $data[] = [
                'id' => $notice->id,
                'message' => $notice->message,
                'type' => $notice->type,
                'priority' => $notice->priority,
                'is_active' => $notice->is_active,
                'start_date' => $notice->start_date ? $notice->start_date->format('Y-m-d H:i') : '',
                'end_date' => $notice->end_date ? $notice->end_date->format('Y-m-d H:i') : '',
                'created_at' => $notice->created_at->format('Y-m-d H:i:s'),
                'actions' => $this->generateActionButtons($notice)
            ];
        }

        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data
        ]);
    }

    /**
     * Show the form for creating a new notice.
     */
    public function create()
    {
        return view('admin.notices.create');
    }

    /**
     * Store a newly created notice in storage (AJAX).
     */
    public function store(Request $request)
    {
        // Handle checkbox value properly
        $data = $request->all();
        $data['is_active'] = $request->has('is_active') ? 1 : 0;
        
        $validator = Validator::make($data, [
            'message' => 'required|string|max:500',
            'type' => 'required|in:info,success,warning,danger',
            'priority' => 'required|integer|min:1|max:10',
            'is_active' => 'required|boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $notice = AdminNotice::create([
                'message' => $request->message,
                'type' => $request->type,
                'priority' => $request->priority,
                'is_active' => $data['is_active'],
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Notice created successfully!',
                'data' => $notice
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating notice: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified notice.
     */
    public function show($id)
    {
        $notice = AdminNotice::findOrFail($id);
        
        return response()->json([
            'success' => true,
            'data' => $notice
        ]);
    }

    /**
     * Show the form for editing the specified notice.
     */
    public function edit($id)
    {
        $notice = AdminNotice::findOrFail($id);
        return view('admin.notices.edit', compact('notice'));
    }

    /**
     * Update the specified notice in storage (AJAX).
     */
    public function update(Request $request, $id)
    {
        $notice = AdminNotice::findOrFail($id);

        // Handle checkbox value properly
        $data = $request->all();
        $data['is_active'] = $request->has('is_active') ? 1 : 0;

        $validator = Validator::make($data, [
            'message' => 'required|string|max:500',
            'type' => 'required|in:info,success,warning,danger',
            'priority' => 'required|integer|min:1|max:10',
            'is_active' => 'required|boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $notice->update([
                'message' => $request->message,
                'type' => $request->type,
                'priority' => $request->priority,
                'is_active' => $data['is_active'],
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Notice updated successfully!',
                'data' => $notice
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating notice: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified notice from storage (AJAX).
     */
    public function destroy($id)
    {
        try {
            $notice = AdminNotice::findOrFail($id);
            $notice->delete();

            return response()->json([
                'success' => true,
                'message' => 'Notice deleted successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting notice: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle notice status (AJAX).
     */
    public function toggleStatus($id)
    {
        try {
            $notice = AdminNotice::findOrFail($id);
            $notice->update(['is_active' => !$notice->is_active]);

            return response()->json([
                'success' => true,
                'message' => 'Notice status updated successfully!',
                'is_active' => $notice->is_active
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk delete notices (AJAX).
     */
    public function bulkDelete(Request $request)
    {
        try {
            $ids = $request->ids;
            if (empty($ids)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No notices selected'
                ], 400);
            }

            AdminNotice::whereIn('id', $ids)->delete();

            return response()->json([
                'success' => true,
                'message' => count($ids) . ' notices deleted successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting notices: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate action buttons for DataTable
     */
    private function generateActionButtons($notice)
    {
        $statusClass = $notice->is_active ? 'btn-success' : 'btn-secondary';
        $statusIcon = $notice->is_active ? 'bx-check' : 'bx-x';
        $statusText = $notice->is_active ? 'Active' : 'Inactive';

        return '
            <div class="btn-group btn-group-sm" role="group">
                <button type="button" class="btn btn-info btn-sm view-notice" data-id="' . $notice->id . '" title="View">
                    <i class="bx bx-show"></i>
                </button>
                <button type="button" class="btn btn-primary btn-sm edit-notice" data-id="' . $notice->id . '" title="Edit">
                    <i class="bx bx-edit"></i>
                </button>
                <button type="button" class="btn ' . $statusClass . ' btn-sm toggle-status" data-id="' . $notice->id . '" title="Toggle Status">
                    <i class="bx ' . $statusIcon . '"></i>
                </button>
                <button type="button" class="btn btn-danger btn-sm delete-notice" data-id="' . $notice->id . '" title="Delete">
                    <i class="bx bx-trash"></i>
                </button>
            </div>
        ';
    }
}
