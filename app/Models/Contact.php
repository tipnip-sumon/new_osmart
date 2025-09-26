<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email', 
        'phone',
        'subject',
        'message',
        'subscribe_newsletter',
        'status',
        'reference_id',
        'ip_address',
        'user_agent',
        'replied_at',
        'admin_notes'
    ];

    protected $casts = [
        'subscribe_newsletter' => 'boolean',
        'replied_at' => 'datetime',
    ];

    protected $dates = [
        'replied_at',
        'created_at',
        'updated_at'
    ];

    /**
     * Generate unique reference ID
     */
    public static function generateReferenceId()
    {
        do {
            $referenceId = 'CNT-' . now()->format('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        } while (self::where('reference_id', $referenceId)->exists());
        
        return $referenceId;
    }

    /**
     * Scope for new contacts
     */
    public function scopeNew($query)
    {
        return $query->where('status', 'new');
    }

    /**
     * Scope for unread contacts
     */
    public function scopeUnread($query)
    {
        return $query->whereIn('status', ['new']);
    }

    /**
     * Mark as read
     */
    public function markAsRead()
    {
        $this->update(['status' => 'read']);
    }

    /**
     * Mark as replied
     */
    public function markAsReplied($adminNotes = null)
    {
        $this->update([
            'status' => 'replied',
            'replied_at' => now(),
            'admin_notes' => $adminNotes
        ]);
    }

    /**
     * Get subject display name
     */
    public function getSubjectDisplayAttribute()
    {
        return ucfirst(str_replace('_', ' ', $this->subject));
    }

    /**
     * Get formatted created date
     */
    public function getFormattedCreatedAtAttribute()
    {
        return $this->created_at->format('M d, Y h:i A');
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeClassAttribute()
    {
        return match($this->status) {
            'new' => 'badge-danger',
            'read' => 'badge-warning', 
            'replied' => 'badge-success',
            'closed' => 'badge-secondary',
            default => 'badge-secondary'
        };
    }
}
