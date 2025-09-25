<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Models\Product;
use App\Models\Order;
use App\Models\UserNotification;
use App\Mail\PasswordChangedNotification;

class MemberHeaderController extends Controller
{
    /**
     * Get header counts for real-time updates
     */
    public function getHeaderCounts()
    {
        $user = Auth::user();
        
        // Using UserNotification model instead of Laravel's built-in notifications
        $unreadNotifications = UserNotification::where('user_id', $user->id)
            ->where('is_read', false)
            ->where('type', '!=', 'message')
            ->count();
            
        $unreadMessages = UserNotification::where('user_id', $user->id)
            ->where('is_read', false)
            ->where('type', 'message')
            ->count();

        return response()->json([
            'success' => true,
            'unread_notifications' => $unreadNotifications,
            'unread_messages' => $unreadMessages,
            'wallet_balance' => $user->wallet_balance ?? 0
        ]);
    }

    /**
     * Live search functionality
     */
    public function liveSearch(Request $request)
    {
        $query = $request->input('query');
        $user = Auth::user();
        
        if (empty($query) || strlen($query) < 2) {
            return response()->json([
                'success' => false,
                'message' => 'Query too short'
            ]);
        }

        $results = [];

        // Search products
        $products = Product::where('status', 'active')
            ->where(function($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('description', 'LIKE', "%{$query}%")
                  ->orWhere('sku', 'LIKE', "%{$query}%");
            })
            ->take(5)
            ->get(['id', 'name', 'slug', 'price', 'image']);

        $results['products'] = $products->map(function($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'price' => number_format($product->price, 2),
                'image' => $product->image ? asset('storage/' . $product->image) : asset('admin-assets/images/products/default.jpg')
            ];
        });

        // Search user's orders
        $orders = Order::where('user_id', $user->id)
            ->where(function($q) use ($query) {
                $q->where('order_number', 'LIKE', "%{$query}%")
                  ->orWhere('status', 'LIKE', "%{$query}%");
            })
            ->take(3)
            ->get(['id', 'order_number', 'status', 'total_amount']);

        $results['orders'] = $orders->map(function($order) {
            return [
                'id' => $order->id,
                'order_number' => $order->order_number,
                'status' => ucfirst($order->status),
                'total' => number_format($order->total_amount, 2)
            ];
        });

        return response()->json([
            'success' => true,
            'products' => $results['products'],
            'orders' => $results['orders']
        ]);
    }

    /**
     * Main search page
     */
    public function search(Request $request)
    {
        $query = $request->input('q');
        $user = Auth::user();
        
        if (empty($query)) {
            return redirect()->back()->with('error', 'Please enter a search term');
        }

        // Search products
        $products = Product::where('status', 'active')
            ->where(function($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('description', 'LIKE', "%{$query}%")
                  ->orWhere('sku', 'LIKE', "%{$query}%");
            })
            ->paginate(12);

        // Search user's orders
        $orders = Order::where('user_id', $user->id)
            ->where(function($q) use ($query) {
                $q->where('order_number', 'LIKE', "%{$query}%")
                  ->orWhere('status', 'LIKE', "%{$query}%");
            })
            ->paginate(10);

        return view('member.search.index', compact('query', 'products', 'orders'));
    }

    /**
     * Mark notification as read
     */
    public function markNotificationAsRead(Request $request)
    {
        $notificationId = $request->input('notification_id');
        $user = Auth::user();

        $notification = UserNotification::where('user_id', $user->id)
            ->where('id', $notificationId)
            ->first();
        
        if ($notification) {
            $notification->markAsRead();
            
            return response()->json([
                'success' => true,
                'message' => 'Notification marked as read'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Notification not found'
        ]);
    }

    /**
     * Mark message as read
     */
    public function markMessageAsRead(Request $request)
    {
        $messageId = $request->input('message_id');
        $user = Auth::user();

        $message = UserNotification::where('user_id', $user->id)
            ->where('type', 'message')
            ->where('id', $messageId)
            ->first();
        
        if ($message) {
            $message->markAsRead();
            
            return response()->json([
                'success' => true,
                'message' => 'Message marked as read'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Message not found'
        ]);
    }

    /**
     * Update password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        // Check if current password is correct
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Current password is incorrect'
            ]);
        }

        try {
            // Update password
            DB::table('users')
                ->where('id', $user->id)
                ->update(['password' => Hash::make($request->new_password)]);

            // Send email notification with new password
            try {
                $includePassword = config('password.include_password_in_email', true);
                $passwordToSend = $includePassword ? $request->new_password : null;
                
                Mail::to($user->email)->send(new PasswordChangedNotification(
                    $user,
                    $request->ip(),
                    $request->header('User-Agent'),
                    $passwordToSend
                ));
            } catch (\Exception $e) {
                // Log email error but don't fail the password update
                Log::error('Failed to send password change notification email: ' . $e->getMessage());
            }

            return response()->json([
                'success' => true,
                'message' => 'Password updated successfully. A confirmation email has been sent to your email address.'
            ]);

        } catch (\Exception $e) {
            Log::error('Password update failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update password. Please try again.'
            ]);
        }
    }
}
