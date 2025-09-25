<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class NotificationService
{
    /**
     * Send notification to a specific user
     */
    public function sendToUser($userId, string $type, string $title, string $message, array $options = []): Notification
    {
        return Notification::createForUser($userId, $type, $title, $message, $options);
    }

    /**
     * Send notification to multiple users
     */
    public function sendToUsers(array $userIds, string $type, string $title, string $message, array $options = []): Collection
    {
        $notifications = collect();
        
        foreach ($userIds as $userId) {
            $notifications->push($this->sendToUser($userId, $type, $title, $message, $options));
        }
        
        return $notifications;
    }

    /**
     * Send rank achievement notification
     */
    public function sendRankAchievement($userId, string $rankName, array $options = []): Notification
    {
        return Notification::createRankAchievement($userId, $rankName, $options);
    }

    /**
     * Send salary payment notification
     */
    public function sendSalaryPayment($userId, float $amount, string $rankName, array $options = []): Notification
    {
        return Notification::createSalaryPayment($userId, $amount, $rankName, $options);
    }

    /**
     * Send qualification reminder notification
     */
    public function sendQualificationReminder($userId, string $rankName, int $daysRemaining, array $options = []): Notification
    {
        return Notification::createQualificationReminder($userId, $rankName, $daysRemaining, $options);
    }

    /**
     * Send KYC status notification
     */
    public function sendKycNotification($userId, string $status, array $options = []): Notification
    {
        return Notification::createKycNotification($userId, $status, $options);
    }

    /**
     * Send commission notification
     */
    public function sendCommission($userId, float $amount, string $type, array $options = []): Notification
    {
        return $this->sendToUser($userId, Notification::TYPE_COMMISSION, 
            "💰 Commission Earned!", 
            "You've earned ৳" . number_format($amount, 2) . " from {$type} commission.",
            array_merge([
                'category' => Notification::CATEGORY_SUCCESS,
                'icon' => 'fe-trending-up',
                'color' => 'success',
                'is_important' => true,
                'action_url' => route('member.wallet.index'),
                'action_text' => 'View Wallet',
                'data' => [
                    'amount' => $amount,
                    'commission_type' => $type
                ]
            ], $options)
        );
    }

    /**
     * Send matching bonus notification
     */
    public function sendMatchingBonus($userId, float $amount, int $level, array $options = []): Notification
    {
        return $this->sendToUser($userId, Notification::TYPE_MATCHING_BONUS,
            "🎯 Matching Bonus!", 
            "You've received ৳" . number_format($amount, 2) . " matching bonus from Level {$level}.",
            array_merge([
                'category' => Notification::CATEGORY_SUCCESS,
                'icon' => 'fe-target',
                'color' => 'success',
                'action_url' => route('member.matching.binary'),
                'action_text' => 'View Details',
                'data' => [
                    'amount' => $amount,
                    'level' => $level
                ]
            ], $options)
        );
    }

    /**
     * Send withdrawal notification
     */
    public function sendWithdrawal($userId, float $amount, string $status, array $options = []): Notification
    {
        $statusMessages = [
            'pending' => "Your withdrawal request of ৳" . number_format($amount, 2) . " is being processed.",
            'processing' => "Your withdrawal of ৳" . number_format($amount, 2) . " is currently being processed.",
            'approved' => "Your withdrawal of ৳" . number_format($amount, 2) . " has been approved!",
            'rejected' => "Your withdrawal request of ৳" . number_format($amount, 2) . " has been rejected.",
            'completed' => "Your withdrawal of ৳" . number_format($amount, 2) . " has been completed!",
            'cancelled' => "Your withdrawal request of ৳" . number_format($amount, 2) . " has been cancelled."
        ];

        $statusCategories = [
            'pending' => Notification::CATEGORY_INFO,
            'processing' => Notification::CATEGORY_INFO,
            'approved' => Notification::CATEGORY_SUCCESS,
            'rejected' => Notification::CATEGORY_DANGER,
            'completed' => Notification::CATEGORY_SUCCESS,
            'cancelled' => Notification::CATEGORY_WARNING
        ];

        return $this->sendToUser($userId, Notification::TYPE_WITHDRAWAL,
            "💳 Withdrawal Update",
            $statusMessages[$status] ?? "Your withdrawal status has been updated.",
            array_merge([
                'category' => $statusCategories[$status] ?? Notification::CATEGORY_INFO,
                'icon' => 'fe-credit-card',
                'color' => $statusCategories[$status] ?? 'info',
                'is_important' => in_array($status, ['approved', 'rejected', 'completed']),
                'action_url' => route('member.wallet.withdraw'),
                'action_text' => 'View Withdrawals',
                'data' => [
                    'amount' => $amount,
                    'status' => $status
                ]
            ], $options)
        );
    }

    /**
     * Send deposit notification
     */
    public function sendDeposit($userId, float $amount, string $method, array $options = []): Notification
    {
        return $this->sendToUser($userId, Notification::TYPE_DEPOSIT,
            "💰 Deposit Received!",
            "Great! Your deposit of ৳" . number_format($amount, 2) . " via {$method} has been credited to your account.",
            array_merge([
                'category' => Notification::CATEGORY_SUCCESS,
                'icon' => 'fe-arrow-down-circle',
                'color' => 'success',
                'is_important' => true,
                'action_url' => route('member.wallet.index'),
                'action_text' => 'View Wallet',
                'data' => [
                    'amount' => $amount,
                    'method' => $method
                ]
            ], $options)
        );
    }

    /**
     * Send transfer received notification
     */
    public function sendTransferReceived($userId, float $amount, string $fromUser, string $walletType, array $options = []): Notification
    {
        $walletNames = [
            'deposit_wallet' => 'Deposit Wallet',
            'interest_wallet' => 'Interest Wallet',
            'balance' => 'Main Balance'
        ];

        return $this->sendToUser($userId, Notification::TYPE_DEPOSIT,
            "🔄 Transfer Received!",
            "You've received ৳" . number_format($amount, 2) . " from {$fromUser} to your " . ($walletNames[$walletType] ?? $walletType) . ".",
            array_merge([
                'category' => Notification::CATEGORY_SUCCESS,
                'icon' => 'fe-arrow-down-right',
                'color' => 'success',
                'action_url' => route('member.wallet.index'),
                'action_text' => 'View Wallet',
                'data' => [
                    'amount' => $amount,
                    'from_user' => $fromUser,
                    'wallet_type' => $walletType
                ]
            ], $options)
        );
    }

    /**
     * Send transfer sent notification
     */
    public function sendTransferSent($userId, float $amount, string $toUser, string $walletType, array $options = []): Notification
    {
        $walletNames = [
            'deposit_wallet' => 'Deposit Wallet',
            'interest_wallet' => 'Interest Wallet',
            'balance' => 'Main Balance'
        ];

        return $this->sendToUser($userId, Notification::TYPE_WITHDRAWAL,
            "🔄 Transfer Sent",
            "You've successfully transferred ৳" . number_format($amount, 2) . " to {$toUser} from your " . ($walletNames[$walletType] ?? $walletType) . ".",
            array_merge([
                'category' => Notification::CATEGORY_INFO,
                'icon' => 'fe-arrow-up-right',
                'color' => 'info',
                'action_url' => route('member.wallet.index'),
                'action_text' => 'View Wallet',
                'data' => [
                    'amount' => $amount,
                    'to_user' => $toUser,
                    'wallet_type' => $walletType
                ]
            ], $options)
        );
    }

    /**
     * Send point transaction notification
     */
    public function sendPointTransaction($userId, float $amount, string $type, string $description, array $options = []): Notification
    {
        $typeMessages = [
            'credit' => "You've earned {$amount} points!",
            'debit' => "You've used {$amount} points."
        ];

        $typeCategories = [
            'credit' => Notification::CATEGORY_SUCCESS,
            'debit' => Notification::CATEGORY_INFO
        ];

        return $this->sendToUser($userId, Notification::TYPE_COMMISSION,
            "⭐ Point Transaction",
            $typeMessages[$type] ?? $description,
            array_merge([
                'category' => $typeCategories[$type] ?? Notification::CATEGORY_INFO,
                'icon' => 'fe-award',
                'color' => $typeCategories[$type] ?? 'info',
                'action_url' => route('member.points.index'),
                'action_text' => 'View Points',
                'data' => [
                    'amount' => $amount,
                    'type' => $type,
                    'description' => $description
                ]
            ], $options)
        );
    }

    /**
     * Send binary matching notification
     */
    public function sendBinaryMatching($userId, float $amount, array $matchingData, array $options = []): Notification
    {
        return $this->sendToUser($userId, Notification::TYPE_MATCHING_BONUS,
            "🎯 Binary Matching Bonus!",
            "You've earned ৳" . number_format($amount, 2) . " from binary matching. Left: " . number_format($matchingData['left_volume'] ?? 0) . ", Right: " . number_format($matchingData['right_volume'] ?? 0),
            array_merge([
                'category' => Notification::CATEGORY_SUCCESS,
                'icon' => 'fe-target',
                'color' => 'success',
                'action_url' => route('member.matching.binary'),
                'action_text' => 'View Binary',
                'data' => array_merge([
                    'amount' => $amount,
                    'type' => 'binary_matching'
                ], $matchingData)
            ], $options)
        );
    }

    /**
     * Send MLM commission notification
     */
    public function sendMlmCommission($userId, float $amount, string $commissionType, string $fromUser, array $options = []): Notification
    {
        $typeLabels = [
            'direct_sales' => 'Direct Sales Commission',
            'binary_bonus' => 'Binary Bonus',
            'matching_bonus' => 'Matching Bonus',
            'leadership_bonus' => 'Leadership Bonus',
            'rank_bonus' => 'Rank Bonus',
            'retail_profit' => 'Retail Profit',
            'team_volume' => 'Team Volume Bonus',
            'fast_start' => 'Fast Start Bonus'
        ];

        $typeLabel = $typeLabels[$commissionType] ?? 'Commission';

        return $this->sendToUser($userId, Notification::TYPE_COMMISSION,
            "💼 {$typeLabel}!",
            "You've earned ৳" . number_format($amount, 2) . " from {$typeLabel}" . ($fromUser ? " (from {$fromUser})" : '') . ".",
            array_merge([
                'category' => Notification::CATEGORY_SUCCESS,
                'icon' => 'fe-trending-up',
                'color' => 'success',
                'action_url' => route('member.earnings.index'),
                'action_text' => 'View Earnings',
                'data' => [
                    'amount' => $amount,
                    'commission_type' => $commissionType,
                    'from_user' => $fromUser
                ]
            ], $options)
        );
    }

    /**
     * Send vendor transfer notification
     */
    public function sendVendorTransfer($userId, float $amount, string $status, string $fromVendor, array $options = []): Notification
    {
        $statusMessages = [
            'pending' => "Transfer request of ৳" . number_format($amount, 2) . " from {$fromVendor} is pending approval.",
            'approved' => "Transfer of ৳" . number_format($amount, 2) . " from {$fromVendor} has been approved!",
            'completed' => "You've received ৳" . number_format($amount, 2) . " from vendor {$fromVendor}!",
            'rejected' => "Transfer request of ৳" . number_format($amount, 2) . " from {$fromVendor} was rejected."
        ];

        $statusCategories = [
            'pending' => Notification::CATEGORY_INFO,
            'approved' => Notification::CATEGORY_SUCCESS,
            'completed' => Notification::CATEGORY_SUCCESS,
            'rejected' => Notification::CATEGORY_DANGER
        ];

        return $this->sendToUser($userId, Notification::TYPE_DEPOSIT,
            "🏪 Vendor Transfer",
            $statusMessages[$status] ?? "Vendor transfer status updated.",
            array_merge([
                'category' => $statusCategories[$status] ?? Notification::CATEGORY_INFO,
                'icon' => 'fe-shopping-bag',
                'color' => $statusCategories[$status] ?? 'info',
                'is_important' => in_array($status, ['approved', 'completed', 'rejected']),
                'action_url' => route('member.wallet.index'),
                'action_text' => 'View Wallet',
                'data' => [
                    'amount' => $amount,
                    'status' => $status,
                    'from_vendor' => $fromVendor
                ]
            ], $options)
        );
    }

    /**
     * Send bonus notification (generic bonus)
     */
    public function sendBonus($userId, float $amount, string $bonusType, string $description, array $options = []): Notification
    {
        return $this->sendToUser($userId, Notification::TYPE_COMMISSION,
            "🎁 Bonus Earned!",
            "Congratulations! You've earned ৳" . number_format($amount, 2) . " " . $description . ".",
            array_merge([
                'category' => Notification::CATEGORY_SUCCESS,
                'icon' => 'fe-gift',
                'color' => 'success',
                'is_important' => true,
                'action_url' => route('member.earnings.index'),
                'action_text' => 'View Earnings',
                'data' => [
                    'amount' => $amount,
                    'bonus_type' => $bonusType,
                    'description' => $description
                ]
            ], $options)
        );
    }

    /**
     * Send refund notification
     */
    public function sendRefund($userId, float $amount, string $reason, array $options = []): Notification
    {
        return $this->sendToUser($userId, Notification::TYPE_DEPOSIT,
            "🔄 Refund Processed",
            "You've received a refund of ৳" . number_format($amount, 2) . ". Reason: {$reason}",
            array_merge([
                'category' => Notification::CATEGORY_SUCCESS,
                'icon' => 'fe-rotate-ccw',
                'color' => 'success',
                'action_url' => route('member.wallet.index'),
                'action_text' => 'View Wallet',
                'data' => [
                    'amount' => $amount,
                    'reason' => $reason
                ]
            ], $options)
        );
    }

    /**
     * Send penalty notification
     */
    public function sendPenalty($userId, float $amount, string $reason, array $options = []): Notification
    {
        return $this->sendToUser($userId, Notification::TYPE_SYSTEM_ALERT,
            "⚠️ Penalty Applied",
            "A penalty of ৳" . number_format($amount, 2) . " has been applied to your account. Reason: {$reason}",
            array_merge([
                'category' => Notification::CATEGORY_DANGER,
                'icon' => 'fe-alert-triangle',
                'color' => 'danger',
                'is_important' => true,
                'action_url' => route('member.wallet.index'),
                'action_text' => 'View Transactions',
                'data' => [
                    'amount' => $amount,
                    'reason' => $reason
                ]
            ], $options)
        );
    }

    /**
     * Send general transaction notification
     */
    public function sendTransaction($userId, string $type, float $amount, string $description, array $options = []): Notification
    {
        $typeData = [
            'commission' => ['icon' => 'fe-trending-up', 'category' => 'success', 'title' => '💰 Commission Earned'],
            'bonus' => ['icon' => 'fe-gift', 'category' => 'success', 'title' => '🎁 Bonus Received'],
            'withdrawal' => ['icon' => 'fe-arrow-up-circle', 'category' => 'info', 'title' => '💳 Withdrawal'],
            'deposit' => ['icon' => 'fe-arrow-down-circle', 'category' => 'success', 'title' => '💰 Deposit'],
            'transfer_in' => ['icon' => 'fe-arrow-down-right', 'category' => 'success', 'title' => '🔄 Transfer Received'],
            'transfer_out' => ['icon' => 'fe-arrow-up-right', 'category' => 'info', 'title' => '🔄 Transfer Sent'],
            'refund' => ['icon' => 'fe-rotate-ccw', 'category' => 'success', 'title' => '🔄 Refund'],
            'penalty' => ['icon' => 'fe-alert-triangle', 'category' => 'danger', 'title' => '⚠️ Penalty'],
            'rank_salary' => ['icon' => 'fe-award', 'category' => 'success', 'title' => '🏆 Rank Salary']
        ];

        $config = $typeData[$type] ?? ['icon' => 'fe-dollar-sign', 'category' => 'info', 'title' => '💵 Transaction'];

        return $this->sendToUser($userId, $type,
            $config['title'],
            $description,
            array_merge([
                'category' => $config['category'],
                'icon' => $config['icon'],
                'color' => $config['category'],
                'action_url' => route('member.wallet.index'),
                'action_text' => 'View Wallet',
                'data' => [
                    'amount' => $amount,
                    'transaction_type' => $type
                ]
            ], $options)
        );
    }

    /**
     * Send referral notification
     */
    public function sendReferral($userId, string $referredUserName, array $options = []): Notification
    {
        return $this->sendToUser($userId, Notification::TYPE_REFERRAL,
            "👥 New Referral!",
            "Great news! {$referredUserName} has joined using your referral link.",
            array_merge([
                'category' => Notification::CATEGORY_SUCCESS,
                'icon' => 'fe-users',
                'color' => 'success',
                'action_url' => route('member.referral.tree'),
                'action_text' => 'View Referrals',
                'data' => [
                    'referred_user' => $referredUserName
                ]
            ], $options)
        );
    }

    /**
     * Send system alert
     */
    public function sendSystemAlert($userId, string $title, string $message, array $options = []): Notification
    {
        return $this->sendToUser($userId, Notification::TYPE_SYSTEM_ALERT, $title, $message,
            array_merge([
                'category' => Notification::CATEGORY_WARNING,
                'icon' => 'fe-alert-triangle',
                'color' => 'warning',
                'is_important' => true
            ], $options)
        );
    }

    /**
     * Send promotional notification
     */
    public function sendPromotion($userId, string $title, string $message, array $options = []): Notification
    {
        return $this->sendToUser($userId, Notification::TYPE_PROMOTION, $title, $message,
            array_merge([
                'category' => Notification::CATEGORY_INFO,
                'icon' => 'fe-gift',
                'color' => 'primary'
            ], $options)
        );
    }

    /**
     * Get user's notifications with pagination
     */
    public function getUserNotifications($userId, array $options = []): Collection
    {
        $query = Notification::where('user_id', $userId)
            ->notExpired()
            ->orderBy('created_at', 'desc');

        if (isset($options['type'])) {
            $query->byType($options['type']);
        }

        if (isset($options['category'])) {
            $query->byCategory($options['category']);
        }

        if (isset($options['unread_only']) && $options['unread_only']) {
            $query->unread();
        }

        if (isset($options['important_only']) && $options['important_only']) {
            $query->important();
        }

        $limit = $options['limit'] ?? 50;
        return $query->limit($limit)->get();
    }

    /**
     * Get unread notifications count for user
     */
    public function getUnreadCount($userId): int
    {
        return Notification::where('user_id', $userId)
            ->unread()
            ->notExpired()
            ->count();
    }

    /**
     * Get notification statistics for user
     */
    public function getStatsForUser($userId): array
    {
        return Notification::getStatsForUser($userId);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead($notificationId): bool
    {
        $notification = Notification::find($notificationId);
        if ($notification) {
            $notification->markAsRead();
            return true;
        }
        return false;
    }

    /**
     * Mark all notifications as read for user
     */
    public function markAllAsReadForUser($userId): int
    {
        return Notification::where('user_id', $userId)
            ->unread()
            ->update([
                'is_read' => true,
                'read_at' => now()
            ]);
    }

    /**
     * Delete notification
     */
    public function delete($notificationId): bool
    {
        return Notification::destroy($notificationId) > 0;
    }

    /**
     * Cleanup expired notifications
     */
    public function cleanupExpired(): int
    {
        return Notification::cleanupExpired();
    }

    /**
     * Send bulk notifications to all users
     */
    public function broadcastToAllUsers(string $type, string $title, string $message, array $options = []): int
    {
        $userIds = User::pluck('id')->toArray();
        $this->sendToUsers($userIds, $type, $title, $message, $options);
        return count($userIds);
    }

    /**
     * Send notifications to users based on conditions
     */
    public function sendToUsersWhere(callable $condition, string $type, string $title, string $message, array $options = []): int
    {
        $users = User::get()->filter($condition);
        $userIds = $users->pluck('id')->toArray();
        $this->sendToUsers($userIds, $type, $title, $message, $options);
        return count($userIds);
    }

    /**
     * Get recent notifications for dashboard
     */
    public function getRecentForDashboard($userId, int $limit = 5): Collection
    {
        return Notification::where('user_id', $userId)
            ->notExpired()
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Format notifications for API response
     */
    public function formatForApi(Collection $notifications): array
    {
        return $notifications->map(function ($notification) {
            return [
                'id' => $notification->id,
                'type' => $notification->type,
                'category' => $notification->category,
                'title' => $notification->title,
                'message' => $notification->message,
                'icon' => $notification->icon,
                'color' => $notification->color,
                'action_url' => $notification->action_url,
                'action_text' => $notification->action_text,
                'is_read' => $notification->is_read,
                'is_important' => $notification->is_important,
                'time_ago' => $notification->time_ago,
                'formatted_date' => $notification->formatted_date,
                'data' => $notification->data
            ];
        })->toArray();
    }
}