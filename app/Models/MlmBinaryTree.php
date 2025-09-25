<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MlmBinaryTree extends Model
{
    use HasFactory;

    protected $table = 'mlm_binary_tree';

    protected $fillable = [
        'user_id',
        'sponsor_id',
        'parent_id',
        'position',
        'left_child_id',
        'right_child_id',
        'level',
        'left_count',
        'right_count',
        'left_volume',
        'right_volume',
        'personal_volume',
        'total_volume',
        'last_left_spillover',
        'last_right_spillover',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'left_volume' => 'decimal:2',
        'right_volume' => 'decimal:2',
        'personal_volume' => 'decimal:2',
        'total_volume' => 'decimal:2',
        'last_left_spillover' => 'datetime',
        'last_right_spillover' => 'datetime'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sponsor()
    {
        return $this->belongsTo(User::class, 'sponsor_id');
    }

    public function parent()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    public function leftChild()
    {
        return $this->belongsTo(User::class, 'left_child_id');
    }

    public function rightChild()
    {
        return $this->belongsTo(User::class, 'right_child_id');
    }

    public function binaryVolumes()
    {
        return $this->hasMany(MlmBinaryVolume::class, 'user_id', 'user_id');
    }

    // Helper methods
    public function getAvailablePosition()
    {
        if (!$this->left_child_id) {
            return 'left';
        } elseif (!$this->right_child_id) {
            return 'right';
        }
        return null; // Both positions filled
    }

    public function getSmallerLeg()
    {
        return $this->left_volume <= $this->right_volume ? 'left' : 'right';
    }

    public function getBinaryVolume()
    {
        return min($this->left_volume, $this->right_volume);
    }

    public function getCarryVolume()
    {
        return max($this->left_volume, $this->right_volume) - min($this->left_volume, $this->right_volume);
    }
}
