<?php

namespace App\Observers;

use App\Models\User;
use App\Jobs\UpdateUserBinarySummary;
use Illuminate\Support\Facades\Log;

class UserObserver
{
    /**
     * Handle the User "updated" event.
     * Trigger binary summary update when user's points change
     */
    public function updated(User $user)
    {
        // Check if points-related fields were changed
        if ($user->wasChanged(['active_points', 'reserve_points']) && $user->upline_id) {
            Log::info("User points changed, queuing binary summary update", [
                'user_id' => $user->id,
                'username' => $user->username,
                'active_points' => $user->active_points,
                'reserve_points' => $user->reserve_points
            ]);
            
            // Queue job to update this user's binary summary
            UpdateUserBinarySummary::dispatch($user)->delay(now()->addMinutes(1));
            
            // Also queue updates for users who have this user in their downline
            $this->queueUplineUpdates($user);
        }
    }

    /**
     * Handle the User "created" event.
     * Trigger binary summary update when new user joins binary tree
     */
    public function created(User $user)
    {
        if ($user->upline_id) {
            Log::info("New user joined binary tree, queuing upline updates", [
                'user_id' => $user->id,
                'username' => $user->username,
                'upline_id' => $user->upline_id,
                'position' => $user->position
            ]);
            
            // Queue updates for users who now have this user in their downline
            $this->queueUplineUpdates($user);
        }
    }

    /**
     * Queue binary summary updates for all users upline from the changed user
     */
    private function queueUplineUpdates(User $user)
    {
        $currentUser = $user;
        $level = 0;
        $maxLevels = 10; // Limit to prevent infinite loops
        
        while ($currentUser->upline_id && $level < $maxLevels) {
            $uplineUser = User::find($currentUser->upline_id);
            
            if ($uplineUser) {
                // Queue update for upline user with delay based on level
                $delay = now()->addMinutes(2 + $level);
                UpdateUserBinarySummary::dispatch($uplineUser)->delay($delay);
                
                Log::debug("Queued upline binary summary update", [
                    'upline_user_id' => $uplineUser->id,
                    'level' => $level,
                    'delay_minutes' => 2 + $level
                ]);
                
                $currentUser = $uplineUser;
                $level++;
            } else {
                break;
            }
        }
    }
}