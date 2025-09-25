<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\BinarySummary;
use App\Services\RealTimeBinaryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RealTimeBinaryController extends Controller
{
    /**
     * Get real-time binary volumes for the authenticated user
     */
    public function getBinaryVolumes(Request $request)
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Get quick binary volumes from cache
        $quickVolumes = RealTimeBinaryService::getQuickBinaryVolumes($user->id);
        
        // Get detailed binary summary
        $binarySummary = BinarySummary::where('user_id', $user->id)->first();
        
        // Get downline structure
        $leftDownline = User::where('upline_id', $user->id)
            ->where('position', 'left')
            ->select('id', 'username', 'name', 'monthly_sales_volume', 'is_active')
            ->first();
            
        $rightDownline = User::where('upline_id', $user->id)
            ->where('position', 'right')
            ->select('id', 'username', 'name', 'monthly_sales_volume', 'is_active')
            ->first();

        return response()->json([
            'user_id' => $user->id,
            'username' => $user->username,
            'position' => $user->position,
            'upline_id' => $user->upline_id,
            'real_time_volumes' => [
                'left' => $quickVolumes['left'],
                'right' => $quickVolumes['right'],
                'updated_at' => $quickVolumes['updated_at']
            ],
            'binary_summary' => $binarySummary ? [
                'monthly_left_volume' => $binarySummary->monthly_left_volume,
                'monthly_right_volume' => $binarySummary->monthly_right_volume,
                'daily_left_volume' => $binarySummary->daily_left_volume,
                'daily_right_volume' => $binarySummary->daily_right_volume,
                'lifetime_left_volume' => $binarySummary->lifetime_left_volume,
                'lifetime_right_volume' => $binarySummary->lifetime_right_volume,
                'current_period_left' => $binarySummary->current_period_left,
                'current_period_right' => $binarySummary->current_period_right,
                'last_calculated_at' => $binarySummary->last_calculated_at
            ] : null,
            'downlines' => [
                'left' => $leftDownline ? [
                    'id' => $leftDownline->id,
                    'username' => $leftDownline->username,
                    'name' => $leftDownline->name,
                    'monthly_sales' => $leftDownline->monthly_sales_volume,
                    'is_active' => $leftDownline->is_active
                ] : null,
                'right' => $rightDownline ? [
                    'id' => $rightDownline->id,
                    'username' => $rightDownline->username,
                    'name' => $rightDownline->name,
                    'monthly_sales' => $rightDownline->monthly_sales_volume,
                    'is_active' => $rightDownline->is_active
                ] : null
            ]
        ]);
    }

    /**
     * Get binary volumes for a specific user (admin only)
     */
    public function getUserBinaryVolumes(Request $request, $userId)
    {
        // Check if user is admin
        if (!Auth::user() || Auth::user()->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $user = User::find($userId);
        
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $quickVolumes = RealTimeBinaryService::getQuickBinaryVolumes($userId);
        $binarySummary = BinarySummary::where('user_id', $userId)->first();

        return response()->json([
            'user_id' => $user->id,
            'username' => $user->username,
            'name' => $user->name,
            'real_time_volumes' => $quickVolumes,
            'binary_summary' => $binarySummary
        ]);
    }

    /**
     * Trigger real-time update for a user (admin only)
     */
    public function triggerUpdate(Request $request, $userId)
    {
        // Check if user is admin
        if (!Auth::user() || Auth::user()->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $user = User::find($userId);
        
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        try {
            // Trigger real-time update
            RealTimeBinaryService::updateUserAndUplines($userId);
            
            return response()->json([
                'success' => true,
                'message' => 'Binary volumes updated successfully',
                'user' => $user->username
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to update binary volumes: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get binary tree structure for visualization
     */
    public function getBinaryTree(Request $request)
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $treeData = $this->buildBinaryTreeData($user, 3); // 3 levels deep

        return response()->json([
            'tree' => $treeData,
            'user_position' => $user->position,
            'upline_id' => $user->upline_id
        ]);
    }

    /**
     * Build binary tree data recursively
     */
    private function buildBinaryTreeData($user, $depth = 2)
    {
        if ($depth <= 0) {
            return null;
        }

        $leftChild = User::where('upline_id', $user->id)
            ->where('position', 'left')
            ->first();
            
        $rightChild = User::where('upline_id', $user->id)
            ->where('position', 'right')
            ->first();

        $binarySummary = BinarySummary::where('user_id', $user->id)->first();

        return [
            'id' => $user->id,
            'username' => $user->username,
            'name' => $user->name,
            'position' => $user->position,
            'monthly_sales' => $user->monthly_sales_volume ?? 0,
            'binary_volumes' => $binarySummary ? [
                'left' => $binarySummary->monthly_left_volume ?? 0,
                'right' => $binarySummary->monthly_right_volume ?? 0
            ] : ['left' => 0, 'right' => 0],
            'left_child' => $leftChild ? $this->buildBinaryTreeData($leftChild, $depth - 1) : null,
            'right_child' => $rightChild ? $this->buildBinaryTreeData($rightChild, $depth - 1) : null
        ];
    }
}
