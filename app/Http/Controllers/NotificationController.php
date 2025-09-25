<?php

namespace App\Http\Controllers;

use App\Models\AdminNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Display notifications page
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Get notifications for the user (using AdminNotification model for now)
        $query = AdminNotification::where('user_id', $user->id ?? null)
                                ->orWhereNull('user_id'); // Global notifications
        
        // Apply filters
        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }
        
        if ($request->has('status') && $request->status) {
            if ($request->status === 'read') {
                $query->whereNotNull('read_at');
            } elseif ($request->status === 'unread') {
                $query->whereNull('read_at');
            }
        }
        
        if ($request->has('date') && $request->date) {
            switch ($request->date) {
                case 'today':
                    $query->whereDate('created_at', today());
                    break;
                case 'week':
                    $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'month':
                    $query->whereMonth('created_at', now()->month);
                    break;
            }
        }
        
        $notifications = $query->orderBy('created_at', 'desc')->paginate(10);
        
        // If this is an AJAX request, return only the notifications list
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'html' => view('notifications.partials.list', compact('notifications'))->render(),
                'pagination' => $notifications->appends(request()->query())->links()->render()
            ]);
        }
        
        return view('notifications.index', compact('notifications'));
    }
    
    /**
     * Mark notification as read
     */
    public function markAsRead($id)
    {
        try {
            $user = Auth::user();
            $notification = AdminNotification::where('id', $id)
                                           ->where(function($query) use ($user) {
                                               $query->where('user_id', $user->id ?? null)
                                                     ->orWhereNull('user_id');
                                           })
                                           ->first();
            
            if (!$notification) {
                return response()->json(['success' => false, 'message' => 'Notification not found'], 404);
            }
            
            $notification->update(['read_at' => now()]);
            
            return response()->json([
                'success' => true,
                'message' => 'Notification marked as read'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error marking notification as read'
            ], 500);
        }
    }
    
    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        try {
            $user = Auth::user();
            $updated = AdminNotification::where(function($query) use ($user) {
                                           $query->where('user_id', $user->id ?? null)
                                                 ->orWhereNull('user_id');
                                       })
                                       ->whereNull('read_at')
                                       ->update(['read_at' => now()]);
            
            return response()->json([
                'success' => true,
                'message' => "Marked {$updated} notifications as read",
                'count' => $updated
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error marking notifications as read'
            ], 500);
        }
    }
    
    /**
     * Delete a notification
     */
    public function destroy($id)
    {
        try {
            $user = Auth::user();
            $notification = AdminNotification::where('id', $id)
                                           ->where(function($query) use ($user) {
                                               $query->where('user_id', $user->id ?? null)
                                                     ->orWhereNull('user_id');
                                           })
                                           ->first();
            
            if (!$notification) {
                return response()->json(['success' => false, 'message' => 'Notification not found'], 404);
            }
            
            $notification->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Notification deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting notification'
            ], 500);
        }
    }
    
    /**
     * Clear all notifications
     */
    public function clearAll()
    {
        try {
            $user = Auth::user();
            $deleted = AdminNotification::where(function($query) use ($user) {
                                           $query->where('user_id', $user->id ?? null)
                                                 ->orWhereNull('user_id');
                                       })
                                       ->delete();
            
            return response()->json([
                'success' => true,
                'message' => "Cleared {$deleted} notifications",
                'count' => $deleted
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error clearing notifications'
            ], 500);
        }
    }
    
    /**
     * Get unread notifications count
     */
    public function getUnreadCount()
    {
        try {
            $user = Auth::user();
            $count = AdminNotification::where(function($query) use ($user) {
                                         $query->where('user_id', $user->id ?? null)
                                               ->orWhereNull('user_id');
                                     })
                                     ->whereNull('read_at')
                                     ->count();
            
            return response()->json([
                'success' => true,
                'count' => $count
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error getting notification count'
            ], 500);
        }
    }
}
