<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminNotificationController extends Controller
{
    /**
     * Get recent notifications for admin
     */
    public function recent()
    {
        // Mock notification data for now
        $notifications = [
            [
                'id' => 1,
                'title' => 'New Order Received',
                'message' => 'Order #12345 has been placed',
                'type' => 'order',
                'is_read' => false,
                'created_at' => now()->subMinutes(5)->toISOString(),
                'icon' => 'bx bx-shopping-bag',
                'color' => 'success'
            ],
            [
                'id' => 2,
                'title' => 'Low Stock Alert',
                'message' => 'Product "Omega-3 Capsules" is running low',
                'type' => 'inventory',
                'is_read' => false,
                'created_at' => now()->subHours(2)->toISOString(),
                'icon' => 'bx bx-error',
                'color' => 'warning'
            ],
            [
                'id' => 3,
                'title' => 'New Customer Registration',
                'message' => 'John Doe has registered as a new customer',
                'type' => 'customer',
                'is_read' => true,
                'created_at' => now()->subHours(4)->toISOString(),
                'icon' => 'bx bx-user-plus',
                'color' => 'info'
            ],
            [
                'id' => 4,
                'title' => 'Payment Received',
                'message' => 'Payment of $250.00 received for Order #12344',
                'type' => 'payment',
                'is_read' => true,
                'created_at' => now()->subHours(6)->toISOString(),
                'icon' => 'bx bx-dollar',
                'color' => 'primary'
            ],
            [
                'id' => 5,
                'title' => 'Product Review',
                'message' => 'New 5-star review received for "Vitamin D3"',
                'type' => 'review',
                'is_read' => true,
                'created_at' => now()->subDays(1)->toISOString(),
                'icon' => 'bx bx-star',
                'color' => 'success'
            ]
        ];

        $unreadCount = collect($notifications)->where('is_read', false)->count();

        return response()->json([
            'success' => true,
            'data' => [
                'notifications' => $notifications,
                'unread_count' => $unreadCount,
                'total_count' => count($notifications)
            ]
        ]);
    }

    /**
     * Get all notifications with pagination
     */
    public function index(Request $request)
    {
        // This would typically fetch from database with pagination
        return $this->recent();
    }

    /**
     * Mark notification as read
     */
    public function markAsRead($id)
    {
        // In real implementation, update database
        // AdminNotification::find($id)->update(['is_read' => true]);
        
        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read'
        ]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        // In real implementation, update all notifications
        // AdminNotification::where('admin_id', auth('admin')->id())
        //     ->update(['is_read' => true]);
        
        return response()->json([
            'success' => true,
            'message' => 'All notifications marked as read'
        ]);
    }

    /**
     * Delete notification
     */
    public function destroy($id)
    {
        // In real implementation, delete from database
        // AdminNotification::find($id)->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Notification deleted successfully'
        ]);
    }

    /**
     * Get notification count for badge
     */
    public function getUnreadCount()
    {
        // Mock unread count
        $unreadCount = 3;
        
        return response()->json([
            'success' => true,
            'data' => [
                'unread_count' => $unreadCount
            ]
        ]);
    }
}
