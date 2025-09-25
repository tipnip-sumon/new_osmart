<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SupportTicket extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'ticket_number',
        'user_id',
        'assigned_to',
        'category_id',
        'subject',
        'description',
        'priority',
        'status',
        'type',
        'source',
        'tags',
        'attachments',
        'metadata',
        'first_response_at',
        'resolved_at',
        'closed_at',
        'last_activity_at',
        'satisfaction_rating',
        'satisfaction_comment'
    ];

    protected $casts = [
        'tags' => 'array',
        'attachments' => 'array',
        'metadata' => 'array',
        'first_response_at' => 'datetime',
        'resolved_at' => 'datetime',
        'closed_at' => 'datetime',
        'last_activity_at' => 'datetime',
        'satisfaction_rating' => 'integer'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assignedAgent()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function replies()
    {
        return $this->hasMany(SupportReply::class, 'ticket_id')->orderBy('created_at');
    }

    public function category()
    {
        return $this->belongsTo(SupportCategory::class, 'category_id');
    }

    // Scopes
    public function scopeOpen($query)
    {
        return $query->whereIn('status', ['open', 'in_progress', 'waiting_for_customer']);
    }

    public function scopeClosed($query)
    {
        return $query->where('status', 'closed');
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeAssignedTo($query, $userId)
    {
        return $query->where('assigned_to', $userId);
    }

    public function scopeUnassigned($query)
    {
        return $query->whereNull('assigned_to');
    }

    // Accessors
    public function getStatusBadgeAttribute()
    {
        $classes = [
            'open' => 'badge-primary',
            'in_progress' => 'badge-warning',
            'waiting_for_customer' => 'badge-info',
            'resolved' => 'badge-success',
            'closed' => 'badge-secondary'
        ];

        return $classes[$this->status] ?? 'badge-secondary';
    }

    public function getPriorityBadgeAttribute()
    {
        $classes = [
            'low' => 'badge-success',
            'normal' => 'badge-info',
            'high' => 'badge-warning',
            'urgent' => 'badge-danger'
        ];

        return $classes[$this->priority] ?? 'badge-info';
    }

    public function getResponseTimeAttribute()
    {
        if (!$this->first_response_at) {
            return null;
        }
        
        return $this->created_at->diffInMinutes($this->first_response_at);
    }

    public function getResolutionTimeAttribute()
    {
        if (!$this->resolved_at) {
            return null;
        }
        
        return $this->created_at->diffInMinutes($this->resolved_at);
    }

    // Methods
    public static function getStatuses()
    {
        return [
            'open' => 'Open',
            'in_progress' => 'In Progress',
            'waiting_for_customer' => 'Waiting for Customer',
            'resolved' => 'Resolved',
            'closed' => 'Closed'
        ];
    }

    public static function getPriorities()
    {
        return [
            'low' => 'Low',
            'normal' => 'Normal',
            'high' => 'High',
            'urgent' => 'Urgent'
        ];
    }

    public static function getTypes()
    {
        return [
            'general_inquiry' => 'General Inquiry',
            'technical_support' => 'Technical Support',
            'billing_issue' => 'Billing Issue',
            'feature_request' => 'Feature Request',
            'bug_report' => 'Bug Report',
            'account_issue' => 'Account Issue',
            'payment_issue' => 'Payment Issue',
            'refund_request' => 'Refund Request'
        ];
    }

    public static function getSources()
    {
        return [
            'web' => 'Web',
            'email' => 'Email',
            'phone' => 'Phone',
            'chat' => 'Live Chat',
            'mobile_app' => 'Mobile App',
            'social_media' => 'Social Media'
        ];
    }

    public function isOpen()
    {
        return in_array($this->status, ['open', 'in_progress', 'waiting_for_customer']);
    }

    public function isClosed()
    {
        return $this->status === 'closed';
    }

    public function isOverdue()
    {
        // Define SLA based on priority
        $slaHours = [
            'low' => 48,
            'normal' => 24,
            'high' => 8,
            'urgent' => 4
        ];

        $sla = $slaHours[$this->priority] ?? 24;
        return $this->created_at->addHours($sla)->isPast() && $this->isOpen();
    }

    public function generateTicketNumber()
    {
        return 'TKT-' . date('Y') . '-' . str_pad($this->id, 6, '0', STR_PAD_LEFT);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($ticket) {
            $ticket->last_activity_at = now();
        });

        static::created(function ($ticket) {
            $ticket->ticket_number = $ticket->generateTicketNumber();
            $ticket->save();
        });
    }
}
