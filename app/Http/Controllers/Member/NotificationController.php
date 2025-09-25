<?php

namespace App\Http\Controllers\Member;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class NotificationController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Display notifications page
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Get paginated notifications for the user
        $notifications = Notification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        // Add computed properties for display
        $notifications->getCollection()->transform(function ($notification) {
            // Add category color mapping
            $notification->category_color = match($notification->category) {
                'financial' => 'success',
                'system' => 'info',
                'achievement' => 'warning',
                'alert' => 'danger',
                default => 'primary'
            };
            
            // Add icon mapping
            $notification->icon = match($notification->type) {
                'commission_earned' => 'fe fe-dollar-sign',
                'salary_paid' => 'fe fe-credit-card',
                'withdrawal_completed' => 'fe fe-arrow-down-circle',
                'deposit_received' => 'fe fe-arrow-up-circle',
                'transfer_sent' => 'fe fe-send',
                'transfer_received' => 'fe fe-inbox',
                'point_earned' => 'fe fe-award',
                'point_deducted' => 'fe fe-minus-circle',
                'rank_achieved' => 'fe fe-star',
                'kyc_updated' => 'fe fe-user-check',
                default => 'fe fe-bell'
            };
            
            // Add human readable time
            $notification->time_ago = $notification->created_at->diffForHumans();
            
            return $notification;
        });
        
        // Get notification statistics
        $stats = [
            'total' => Notification::where('user_id', $user->id)->count(),
            'unread' => Notification::where('user_id', $user->id)->where('is_read', false)->count(),
            'important' => Notification::where('user_id', $user->id)->where('is_important', true)->count(),
            'recent' => Notification::where('user_id', $user->id)->where('created_at', '>=', Carbon::now()->subWeek())->count(),
        ];
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'notifications' => $notifications,
                'stats' => $stats
            ]);
        }
        
        return view('member.notifications.index', compact('notifications', 'stats'));
    }

    /**
     * Get notifications for member header dropdown
     */
    public function getHeaderNotifications(Request $request)
    {
        $userId = Auth::id();
        
        // Get recent notifications (limit 10 for header)
        $notifications = $this->notificationService->getUserNotifications($userId, [
            'limit' => 10
        ]);

        // Get unread count
        $unreadCount = $this->notificationService->getUnreadCount($userId);

        return response()->json([
            'success' => true,
            'notifications' => $this->notificationService->formatForApi($notifications),
            'unread_count' => $unreadCount,
            'has_more' => $notifications->count() >= 10
        ]);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(Request $request, $notificationId)
    {
        $result = $this->notificationService->markAsRead($notificationId);
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => $result,
                'message' => $result ? 'Notification marked as read' : 'Notification not found'
            ]);
        }

        return back();
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead(Request $request)
    {
        $userId = Auth::id();
        $count = $this->notificationService->markAllAsReadForUser($userId);
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => "{$count} notifications marked as read",
                'count' => $count
            ]);
        }

        return back();
    }

    /**
     * Delete a notification
     */
    public function delete(Request $request, $notificationId)
    {
        $userId = Auth::id();
        
        // Ensure user can only delete their own notifications
        $notification = Notification::where('id', $notificationId)
            ->where('user_id', $userId)
            ->first();

        if (!$notification) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Notification not found'
                ], 404);
            }
            return back()->with('error', 'Notification not found');
        }

        $notification->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Notification deleted successfully'
            ]);
        }

        return back()->with('success', 'Notification deleted successfully');
    }

    /**
     * Test notification (for development)
     */
    public function test(Request $request)
    {
        if (!app()->environment('local')) {
            abort(404);
        }

        $userId = Auth::id();
        
        // Create test notifications
        $this->notificationService->sendRankAchievement($userId, 'Executive');
        $this->notificationService->sendSalaryPayment($userId, 5000, 'Executive');
        $this->notificationService->sendCommission($userId, 500, 'Direct Referral');
        $this->notificationService->sendMatchingBonus($userId, 1000, 3);
        $this->notificationService->sendKycNotification($userId, 'approved');
        
        return back()->with('success', 'Test notifications created successfully!');
    }
}
