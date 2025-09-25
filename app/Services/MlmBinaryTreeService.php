<?php

namespace App\Services;

use App\Models\User;
use App\Models\MlmBinaryTree;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MlmBinaryTreeService
{
    /**
     * Insert a new user into the MLM binary tree
     *
     * @param User $user
     * @return bool
     */
    public function insertUserIntoTree(User $user)
    {
        try {
            DB::beginTransaction();

            // Check if user already exists in the tree
            if ($this->checkUserExistsInTree($user->id)) {
                Log::info("User {$user->id} already exists in MLM binary tree");
                DB::rollback();
                return false;
            }

            // Get sponsor and placement information
            $sponsor = null;
            if ($user->sponsor_id) {
                $sponsor = User::find($user->sponsor_id);
            }
            
            if (!$sponsor) {
                // If no sponsor, create as root node
                $treeNode = $this->createRootNode($user);
                if ($treeNode) {
                    Log::info("User {$user->id} successfully inserted as root node in MLM binary tree");
                    DB::commit();
                    return true;
                }
                Log::error("Failed to create root node for user {$user->id}");
                DB::rollback();
                return false;
            }

            // Determine placement
            $placement = $this->determinePlacement($user, $sponsor);
            
            // Insert user into binary tree
            $treeNode = $this->createTreeNode($user, $sponsor, $placement);
            
            if ($treeNode) {
                // Update parent's children references
                $this->updateParentChildren($treeNode);
                
                // Update upline counts and volumes
                $this->updateUplineCounts($treeNode);
                
                // Update user's upline_id and position in users table
                $uplineUser = User::find($treeNode->parent_id);
                $updateData = [
                    'upline_id' => $treeNode->parent_id,
                    'position' => $treeNode->position
                ];
                
                // Also update upline_username to maintain consistency
                if ($uplineUser) {
                    $updateData['upline_username'] = $uplineUser->username;
                    Log::info("MLM Tree Service: Updating user {$user->id} upline_id={$treeNode->parent_id}, upline_username='{$uplineUser->username}', position={$treeNode->position}");
                } else {
                    Log::warning("MLM Tree Service: Upline user with ID {$treeNode->parent_id} not found when updating user {$user->id}");
                }
                
                $user->update($updateData);

                Log::info("User {$user->id} successfully inserted into MLM binary tree at position {$treeNode->position} under parent {$treeNode->parent_id}");
                
                DB::commit();
                return true;
            }

            DB::rollback();
            return false;

        } catch (\Exception $e) {
            DB::rollback();
            Log::error("Error inserting user {$user->id} into MLM binary tree: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Check if user already exists in the MLM binary tree
     *
     * @param int $userId
     * @return bool
     */
    public function checkUserExistsInTree($userId)
    {
        return MlmBinaryTree::where('user_id', $userId)->exists();
    }

    /**
     * Determine the placement for a new user
     *
     * @param User $user
     * @param User $sponsor
     * @return array
     */
    private function determinePlacement(User $user, User $sponsor)
    {
        // Get sponsor's tree node
        $sponsorTree = MlmBinaryTree::where('user_id', $sponsor->id)->first();
        
        if (!$sponsorTree) {
            // If sponsor doesn't exist in tree, create root position for sponsor first
            $sponsorTree = $this->createRootNode($sponsor);
        }

        // Determine placement based on user's preference and availability
        switch ($user->placement_type) {
            case 'sponsor_choice':
                return $this->findSponsorChoicePlacement($sponsorTree, $user->position);
                
            case 'auto_left':
                return $this->findAutoPlacement($sponsorTree, 'left');
                
            case 'auto_right':
                return $this->findAutoPlacement($sponsorTree, 'right');
                
            case 'balanced':
            default:
                return $this->findBalancedPlacement($sponsorTree);
        }
    }

    /**
     * Find placement based on sponsor's choice and user's preferred position
     *
     * @param MlmBinaryTree $sponsorTree
     * @param string $preferredPosition
     * @return array
     */
    private function findSponsorChoicePlacement($sponsorTree, $preferredPosition = null)
    {
        // If user specified a position preference, try that first
        if ($preferredPosition && in_array($preferredPosition, ['left', 'right'])) {
            $placement = $this->findAvailablePosition($sponsorTree, $preferredPosition);
            if ($placement) {
                return $placement;
            }
        }

        // Fallback to balanced placement
        return $this->findBalancedPlacement($sponsorTree);
    }

    /**
     * Find auto placement in specified direction
     *
     * @param MlmBinaryTree $sponsorTree
     * @param string $direction
     * @return array
     */
    private function findAutoPlacement($sponsorTree, $direction)
    {
        return $this->findAvailablePosition($sponsorTree, $direction) ?: 
               $this->findBalancedPlacement($sponsorTree);
    }

    /**
     * Find balanced placement (smaller leg gets priority)
     *
     * @param MlmBinaryTree $sponsorTree
     * @return array
     */
    private function findBalancedPlacement($sponsorTree)
    {
        // Find the leg with fewer people
        $leftCount = $sponsorTree->left_leg_count ?? 0;
        $rightCount = $sponsorTree->right_leg_count ?? 0;
        
        $preferredSide = $leftCount <= $rightCount ? 'left' : 'right';
        
        return $this->findAvailablePosition($sponsorTree, $preferredSide) ?: 
               $this->findAvailablePosition($sponsorTree, $preferredSide === 'left' ? 'right' : 'left');
    }

    /**
     * Find available position starting from sponsor tree
     *
     * @param MlmBinaryTree $startTree
     * @param string $preferredSide
     * @return array|null
     */
    private function findAvailablePosition($startTree, $preferredSide)
    {
        $queue = [['tree' => $startTree, 'side' => $preferredSide]];
        $visited = [];

        while (!empty($queue)) {
            $current = array_shift($queue);
            $currentTree = $current['tree'];
            $side = $current['side'];

            // Skip if already visited
            if (in_array($currentTree->user_id, $visited)) {
                continue;
            }
            $visited[] = $currentTree->user_id;

            // Check if the preferred side is available
            $childField = $side . '_child_id';
            if (!$currentTree->$childField) {
                return [
                    'parent_id' => $currentTree->user_id,
                    'position' => $side,
                    'level' => $currentTree->level + 1,
                    'path' => $this->buildPath($currentTree->path, $side)
                ];
            }

            // If not available, add children to queue for deeper search
            if ($currentTree->left_child_id) {
                $leftChild = MlmBinaryTree::where('user_id', $currentTree->left_child_id)->first();
                if ($leftChild) {
                    $queue[] = ['tree' => $leftChild, 'side' => 'left'];
                    $queue[] = ['tree' => $leftChild, 'side' => 'right'];
                }
            }

            if ($currentTree->right_child_id) {
                $rightChild = MlmBinaryTree::where('user_id', $currentTree->right_child_id)->first();
                if ($rightChild) {
                    $queue[] = ['tree' => $rightChild, 'side' => 'left'];
                    $queue[] = ['tree' => $rightChild, 'side' => 'right'];
                }
            }
        }

        return null;
    }

    /**
     * Create root node for user if they don't have a sponsor
     *
     * @param User $user
     * @return MlmBinaryTree
     */
    private function createRootNode(User $user)
    {
        return MlmBinaryTree::create([
            'user_id' => $user->id,
            'sponsor_id' => null,
            'parent_id' => null,
            'position' => null,
            'level' => 1,
            'path' => 'R', // Root
            'is_active' => true,
            'personal_volume' => 0,
            'left_leg_volume' => 0,
            'right_leg_volume' => 0,
            'total_team_volume' => 0,
            'left_leg_count' => 0,
            'right_leg_count' => 0,
            'total_downline_count' => 0,
            'rank' => 'member'
        ]);
    }

    /**
     * Create tree node for new user
     *
     * @param User $user
     * @param User $sponsor
     * @param array $placement
     * @return MlmBinaryTree|null
     */
    private function createTreeNode(User $user, User $sponsor, $placement)
    {
        if (!$placement) {
            Log::error("No valid placement found for user {$user->id}");
            return null;
        }

        return MlmBinaryTree::create([
            'user_id' => $user->id,
            'sponsor_id' => $sponsor->id,
            'parent_id' => $placement['parent_id'],
            'position' => $placement['position'],
            'level' => $placement['level'],
            'path' => $placement['path'],
            'placement_type' => $user->placement_type ?? 'balanced',
            'is_active' => true,
            'personal_volume' => 0,
            'left_leg_volume' => 0,
            'right_leg_volume' => 0,
            'total_team_volume' => 0,
            'left_leg_count' => 0,
            'right_leg_count' => 0,
            'total_downline_count' => 0,
            'active_left_count' => 0,
            'active_right_count' => 0,
            'rank' => 'member',
            'last_activity_date' => now()->toDateString(),
            'qualification_date' => now()->toDateString()
        ]);
    }

    /**
     * Update parent's children references
     *
     * @param MlmBinaryTree $treeNode
     * @return void
     */
    private function updateParentChildren(MlmBinaryTree $treeNode)
    {
        if ($treeNode->parent_id) {
            $parent = MlmBinaryTree::where('user_id', $treeNode->parent_id)->first();
            if ($parent) {
                $childField = $treeNode->position . '_child_id';
                $parent->update([$childField => $treeNode->user_id]);
            }
        }
    }

    /**
     * Update upline counts and statistics
     *
     * @param MlmBinaryTree $treeNode
     * @return void
     */
    private function updateUplineCounts(MlmBinaryTree $treeNode)
    {
        $currentNode = $treeNode;
        
        while ($currentNode && $currentNode->parent_id) {
            $parent = MlmBinaryTree::where('user_id', $currentNode->parent_id)->first();
            if (!$parent) break;

            // Update leg counts
            if ($currentNode->position === 'left') {
                $parent->increment('left_leg_count');
                $parent->increment('active_left_count');
            } else {
                $parent->increment('right_leg_count');
                $parent->increment('active_right_count');
            }

            // Update total downline count
            $parent->increment('total_downline_count');

            // Move up the tree
            $currentNode = $parent;
        }
    }

    /**
     * Build path string for tree navigation
     *
     * @param string|null $parentPath
     * @param string $position
     * @return string
     */
    private function buildPath($parentPath, $position)
    {
        $positionCode = $position === 'left' ? 'L' : 'R';
        return $parentPath ? $parentPath . '-' . $positionCode : $positionCode;
    }

    /**
     * Get tree statistics for a user
     *
     * @param int $userId
     * @return array
     */
    public function getTreeStatistics($userId)
    {
        $treeNode = MlmBinaryTree::where('user_id', $userId)->first();
        
        if (!$treeNode) {
            return [
                'exists_in_tree' => false,
                'message' => 'User not found in MLM binary tree'
            ];
        }

        return [
            'exists_in_tree' => true,
            'user_id' => $treeNode->user_id,
            'parent_id' => $treeNode->parent_id,
            'position' => $treeNode->position,
            'level' => $treeNode->level,
            'path' => $treeNode->path,
            'left_leg_count' => $treeNode->left_leg_count,
            'right_leg_count' => $treeNode->right_leg_count,
            'total_downline_count' => $treeNode->total_downline_count,
            'left_leg_volume' => $treeNode->left_leg_volume,
            'right_leg_volume' => $treeNode->right_leg_volume,
            'total_team_volume' => $treeNode->total_team_volume,
            'rank' => $treeNode->rank,
            'is_active' => $treeNode->is_active,
            'created_at' => $treeNode->created_at
        ];
    }

    /**
     * Verify tree integrity for a user
     *
     * @param int $userId
     * @return array
     */
    public function verifyTreeIntegrity($userId)
    {
        $issues = [];
        $treeNode = MlmBinaryTree::where('user_id', $userId)->first();

        if (!$treeNode) {
            return [
                'has_issues' => true,
                'issues' => ['User not found in MLM binary tree']
            ];
        }

        // Check parent-child relationships
        if ($treeNode->parent_id) {
            $parent = MlmBinaryTree::where('user_id', $treeNode->parent_id)->first();
            if (!$parent) {
                $issues[] = "Parent user {$treeNode->parent_id} not found in tree";
            } else {
                $childField = $treeNode->position . '_child_id';
                if ($parent->$childField !== $treeNode->user_id) {
                    $issues[] = "Parent's {$treeNode->position} child reference is incorrect";
                }
            }
        }

        // Check children references
        if ($treeNode->left_child_id) {
            $leftChild = MlmBinaryTree::where('user_id', $treeNode->left_child_id)->first();
            if (!$leftChild || $leftChild->parent_id !== $treeNode->user_id) {
                $issues[] = "Left child reference is incorrect";
            }
        }

        if ($treeNode->right_child_id) {
            $rightChild = MlmBinaryTree::where('user_id', $treeNode->right_child_id)->first();
            if (!$rightChild || $rightChild->parent_id !== $treeNode->user_id) {
                $issues[] = "Right child reference is incorrect";
            }
        }

        return [
            'has_issues' => !empty($issues),
            'issues' => $issues
        ];
    }

    /**
     * Get all users in MLM binary tree
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllTreeUsers()
    {
        return MlmBinaryTree::select('*')->get();
    }
}
