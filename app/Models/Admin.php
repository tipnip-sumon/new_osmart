<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        // Basic information
        'name',
        'email',
        'username',
        'password',
        'image',
        'phone',
        'address',
        
        // Role and permissions
        'role',
        'permissions',
        'is_active',
        'is_super_admin',
        
        // Financial fields
        'balance',
        'total_deposited',
        'total_withdrawn',
        'total_transferred',
        
        // Two-factor authentication
        'two_factor_secret',
        'two_factor_recovery_codes',
        'two_factor_confirmed_at',
        
        // Login tracking
        'last_login_at',
        'last_login_ip',
        'last_login_user_agent',
        'login_attempts',
        'locked_until',
        
        // Additional fields
        'notes',
        'email_verified_at',
        'phone_verified_at',
        'status',
        'department',
        'designation',
        'employee_id',
        'date_of_birth',
        'gender',
        'emergency_contact',
        'emergency_phone',
        'hire_date',
        'salary',
        'commission_rate',
        'preferences',
        'session_id',
        'session_created_at',
        'last_activity_at',
        'password_changed_at',
        'must_change_password',
        'api_access_enabled',
        'api_rate_limit',
        'country',
        'state',
        'city',
        'postal_code',
        'timezone',
        'language',
        'theme_preference'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'emergency_phone',
        'salary',
        'session_id'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            // Date/time fields
            'email_verified_at' => 'datetime',
            'phone_verified_at' => 'datetime',
            'two_factor_confirmed_at' => 'datetime',
            'last_login_at' => 'datetime',
            'locked_until' => 'datetime',
            'date_of_birth' => 'date',
            'hire_date' => 'date',
            'session_created_at' => 'datetime',
            'last_activity_at' => 'datetime',
            'password_changed_at' => 'datetime',
            
            // Password and security
            'password' => 'hashed',
            
            // Boolean fields
            'is_active' => 'boolean',
            'is_super_admin' => 'boolean',
            'must_change_password' => 'boolean',
            'api_access_enabled' => 'boolean',
            
            // Financial fields
            'balance' => 'decimal:2',
            'total_deposited' => 'decimal:2',
            'total_withdrawn' => 'decimal:2',
            'total_transferred' => 'decimal:2',
            'salary' => 'decimal:2',
            'commission_rate' => 'decimal:4',
            
            // Integer fields
            'login_attempts' => 'integer',
            'api_rate_limit' => 'integer',
            
            // Array/JSON fields
            'permissions' => 'array',
            'two_factor_recovery_codes' => 'array',
            'preferences' => 'array'
        ];
    }

    // Admin roles
    const ROLES = [
        'super_admin' => 'Super Admin',
        'admin' => 'Admin',
        'manager' => 'Manager',
        'supervisor' => 'Supervisor',
        'moderator' => 'Moderator',
        'support' => 'Support Staff',
        'finance' => 'Finance Manager',
        'marketing' => 'Marketing Manager',
        'hr' => 'HR Manager',
        'developer' => 'Developer'
    ];

    // Admin statuses
    const STATUSES = [
        'active' => 'Active',
        'inactive' => 'Inactive',
        'suspended' => 'Suspended',
        'on_leave' => 'On Leave',
        'terminated' => 'Terminated',
        'pending' => 'Pending Approval'
    ];

    // Departments
    const DEPARTMENTS = [
        'administration' => 'Administration',
        'finance' => 'Finance',
        'marketing' => 'Marketing',
        'hr' => 'Human Resources',
        'it' => 'Information Technology',
        'support' => 'Customer Support',
        'operations' => 'Operations',
        'sales' => 'Sales',
        'legal' => 'Legal',
        'compliance' => 'Compliance'
    ];

    // Gender options
    const GENDERS = [
        'male' => 'Male',
        'female' => 'Female',
        'other' => 'Other',
        'prefer_not_to_say' => 'Prefer not to say'
    ];

    // Default permissions
    const PERMISSIONS = [
        // User management
        'manage_users',
        'view_users',
        'create_users',
        'edit_users',
        'delete_users',
        'ban_users',
        
        // Admin management
        'manage_admins',
        'view_admins',
        'create_admins',
        'edit_admins',
        'delete_admins',
        
        // Product management
        'manage_products',
        'view_products',
        'create_products',
        'edit_products',
        'delete_products',
        'approve_products',
        
        // Order management
        'manage_orders',
        'view_orders',
        'edit_orders',
        'cancel_orders',
        'refund_orders',
        
        // Category management
        'manage_categories',
        'view_categories',
        'create_categories',
        'edit_categories',
        'delete_categories',
        
        // Financial management
        'manage_finances',
        'view_finances',
        'manage_withdrawals',
        'approve_withdrawals',
        'manage_commissions',
        
        // Report access
        'view_reports',
        'view_analytics',
        'export_data',
        
        // System settings
        'manage_settings',
        'view_settings',
        'edit_settings',
        'manage_themes',
        'manage_plugins',
        
        // Support management
        'manage_support',
        'view_tickets',
        'respond_tickets',
        'close_tickets',
        
        // Marketing tools
        'manage_marketing',
        'send_emails',
        'manage_promotions',
        'manage_coupons',
        
        // Content management
        'manage_content',
        'edit_pages',
        'manage_blog',
        'manage_media',
        
        // System maintenance
        'view_logs',
        'manage_backups',
        'clear_cache',
        'manage_database'
    ];

    // Relationships
    public function createdUsers()
    {
        return $this->hasMany(User::class, 'created_by');
    }

    public function transactions()
    {
        return $this->hasMany(AdminTransaction::class);
    }

    public function activityLogs()
    {
        return $this->hasMany(AdminActivityLog::class);
    }

    public function notifications()
    {
        return $this->hasMany(AdminNotification::class);
    }

    public function supervisor()
    {
        return $this->belongsTo(Admin::class, 'supervisor_id');
    }

    public function subordinates()
    {
        return $this->hasMany(Admin::class, 'supervisor_id');
    }

    // Accessors
    public function getRoleNameAttribute()
    {
        return self::ROLES[$this->role] ?? $this->role;
    }

    public function getStatusNameAttribute()
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function getDepartmentNameAttribute()
    {
        return self::DEPARTMENTS[$this->department] ?? $this->department;
    }

    public function getGenderNameAttribute()
    {
        return self::GENDERS[$this->gender] ?? $this->gender;
    }

    public function getFullNameAttribute()
    {
        return $this->name;
    }

    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=7F9CF5&background=EBF4FF';
    }

    public function getFullAddressAttribute()
    {
        $parts = array_filter([
            $this->address,
            $this->city,
            $this->state,
            $this->postal_code,
            $this->country
        ]);

        return implode(', ', $parts);
    }

    public function getAgeAttribute()
    {
        if ($this->date_of_birth) {
            return $this->date_of_birth->age;
        }
        
        return null;
    }

    public function getIsSuperAdminAttribute()
    {
        return $this->is_super_admin || $this->role === 'super_admin';
    }

    public function getIsEmailVerifiedAttribute()
    {
        return $this->email_verified_at !== null;
    }

    public function getIsPhoneVerifiedAttribute()
    {
        return $this->phone_verified_at !== null;
    }

    public function getCanWithdrawAttribute()
    {
        return $this->balance > 0 && 
               $this->is_active && 
               !$this->locked_until;
    }

    public function getHasTwoFactorAttribute()
    {
        return $this->two_factor_confirmed_at !== null;
    }

    public function getYearsOfServiceAttribute()
    {
        if ($this->hire_date) {
            return $this->hire_date->diffInYears(now());
        }
        
        return 0;
    }

    public function getTotalBalanceAttribute()
    {
        return $this->balance + $this->total_deposited;
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->where('status', 'active');
    }

    public function scopeInactive($query)
    {
        return $query->where('is_active', false)->orWhere('status', '!=', 'active');
    }

    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }

    public function scopeSuperAdmins($query)
    {
        return $query->where('is_super_admin', true)->orWhere('role', 'super_admin');
    }

    public function scopeByDepartment($query, $department)
    {
        return $query->where('department', $department);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeEmailVerified($query)
    {
        return $query->whereNotNull('email_verified_at');
    }

    public function scopePhoneVerified($query)
    {
        return $query->whereNotNull('phone_verified_at');
    }

    public function scopeWithTwoFactor($query)
    {
        return $query->whereNotNull('two_factor_confirmed_at');
    }

    public function scopeNotLocked($query)
    {
        return $query->where(function($q) {
            $q->whereNull('locked_until')->orWhere('locked_until', '<', now());
        });
    }

    public function scopeOnline($query)
    {
        return $query->where('last_activity_at', '>', now()->subMinutes(5));
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'LIKE', "%{$search}%")
              ->orWhere('username', 'LIKE', "%{$search}%")
              ->orWhere('email', 'LIKE', "%{$search}%")
              ->orWhere('phone', 'LIKE', "%{$search}%")
              ->orWhere('employee_id', 'LIKE', "%{$search}%")
              ->orWhere('department', 'LIKE', "%{$search}%")
              ->orWhere('designation', 'LIKE', "%{$search}%");
        });
    }

    // Methods
    public function hasPermission($permission)
    {
        if ($this->is_super_admin) {
            return true;
        }

        return in_array($permission, $this->permissions ?? []);
    }

    public function hasAnyPermission($permissions)
    {
        if ($this->is_super_admin) {
            return true;
        }

        foreach ((array) $permissions as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }

        return false;
    }

    public function hasAllPermissions($permissions)
    {
        if ($this->is_super_admin) {
            return true;
        }

        foreach ((array) $permissions as $permission) {
            if (!$this->hasPermission($permission)) {
                return false;
            }
        }

        return true;
    }

    public function grantPermission($permission)
    {
        $permissions = $this->permissions ?? [];
        
        if (!in_array($permission, $permissions)) {
            $permissions[] = $permission;
            $this->permissions = $permissions;
            $this->save();
        }

        return $this;
    }

    public function revokePermission($permission)
    {
        $permissions = $this->permissions ?? [];
        
        if (($key = array_search($permission, $permissions)) !== false) {
            unset($permissions[$key]);
            $this->permissions = array_values($permissions);
            $this->save();
        }

        return $this;
    }

    public function syncPermissions($permissions)
    {
        $this->permissions = is_array($permissions) ? $permissions : [$permissions];
        $this->save();

        return $this;
    }

    public function recordLogin($ip = null, $userAgent = null)
    {
        $this->last_login_at = now();
        $this->last_login_ip = $ip ?: request()->ip();
        $this->last_login_user_agent = $userAgent ?: request()->userAgent();
        $this->login_attempts = 0; // Reset failed attempts
        $this->last_activity_at = now();
        $this->save();

        return $this;
    }

    public function incrementLoginAttempts()
    {
        $this->login_attempts = ($this->login_attempts ?? 0) + 1;
        
        // Lock account after 5 failed attempts
        if ($this->login_attempts >= 5) {
            $this->lockAccount(60, 'Too many failed login attempts');
        }
        
        $this->save();

        return $this;
    }

    public function resetLoginAttempts()
    {
        $this->login_attempts = 0;
        $this->save();

        return $this;
    }

    public function lockAccount($minutes = 30, $reason = 'Security lock')
    {
        $this->locked_until = now()->addMinutes($minutes);
        $this->notes = $this->notes ? $this->notes . "\nLocked: " . $reason : "Locked: " . $reason;
        $this->save();

        return $this;
    }

    public function unlockAccount()
    {
        $this->locked_until = null;
        $this->login_attempts = 0;
        $this->save();

        return $this;
    }

    public function activate()
    {
        $this->is_active = true;
        $this->status = 'active';
        $this->save();

        return $this;
    }

    public function deactivate()
    {
        $this->is_active = false;
        $this->status = 'inactive';
        $this->save();

        return $this;
    }

    public function suspend($reason = null)
    {
        $this->is_active = false;
        $this->status = 'suspended';
        $this->notes = $this->notes ? $this->notes . "\nSuspended: " . $reason : "Suspended: " . $reason;
        $this->save();

        return $this;
    }

    public function addBalance($amount, $description = null)
    {
        $this->balance += $amount;
        $this->total_deposited += $amount;
        $this->save();

        // Create transaction record
        $this->transactions()->create([
            'type' => 'deposit',
            'amount' => $amount,
            'description' => $description,
            'balance_after' => $this->balance,
            'created_at' => now()
        ]);

        return $this;
    }

    public function subtractBalance($amount, $description = null)
    {
        if ($amount > $this->balance) {
            throw new \Exception('Insufficient balance');
        }

        $this->balance -= $amount;
        $this->total_withdrawn += $amount;
        $this->save();

        // Create transaction record
        $this->transactions()->create([
            'type' => 'withdrawal',
            'amount' => $amount,
            'description' => $description,
            'balance_after' => $this->balance,
            'created_at' => now()
        ]);

        return $this;
    }

    public function transferBalance($amount, Admin $recipient, $description = null)
    {
        if ($amount > $this->balance) {
            throw new \Exception('Insufficient balance');
        }

        $this->balance -= $amount;
        $this->total_transferred += $amount;
        $this->save();

        $recipient->balance += $amount;
        $recipient->total_deposited += $amount;
        $recipient->save();

        // Create transaction records
        $this->transactions()->create([
            'type' => 'transfer_out',
            'amount' => $amount,
            'description' => $description,
            'recipient_id' => $recipient->id,
            'balance_after' => $this->balance,
            'created_at' => now()
        ]);

        $recipient->transactions()->create([
            'type' => 'transfer_in',
            'amount' => $amount,
            'description' => $description,
            'sender_id' => $this->id,
            'balance_after' => $recipient->balance,
            'created_at' => now()
        ]);

        return $this;
    }

    public function updateActivity()
    {
        $this->last_activity_at = now();
        $this->save();

        return $this;
    }

    public function verifyEmail()
    {
        $this->email_verified_at = now();
        $this->save();

        return $this;
    }

    public function verifyPhone()
    {
        $this->phone_verified_at = now();
        $this->save();

        return $this;
    }

    public function confirmTwoFactor()
    {
        $this->two_factor_confirmed_at = now();
        $this->save();

        return $this;
    }

    public function disableTwoFactor()
    {
        $this->two_factor_secret = null;
        $this->two_factor_recovery_codes = null;
        $this->two_factor_confirmed_at = null;
        $this->save();

        return $this;
    }

    public function generateUsername()
    {
        $baseUsername = strtolower(str_replace(' ', '', $this->name));
        $username = $baseUsername;
        $counter = 1;
        
        while (static::where('username', $username)->exists()) {
            $username = $baseUsername . $counter;
            $counter++;
        }
        
        $this->username = $username;
        $this->save();

        return $username;
    }

    // Boot method
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($admin) {
            // Set default values
            $admin->role = $admin->role ?? 'admin';
            $admin->status = $admin->status ?? 'pending';
            $admin->is_active = $admin->is_active ?? false;
            $admin->is_super_admin = $admin->is_super_admin ?? false;
            
            // Financial defaults
            $admin->balance = $admin->balance ?? 0;
            $admin->total_deposited = $admin->total_deposited ?? 0;
            $admin->total_withdrawn = $admin->total_withdrawn ?? 0;
            $admin->total_transferred = $admin->total_transferred ?? 0;
            $admin->commission_rate = $admin->commission_rate ?? 0;
            
            // Security defaults
            $admin->login_attempts = 0;
            $admin->api_access_enabled = $admin->api_access_enabled ?? false;
            $admin->api_rate_limit = $admin->api_rate_limit ?? 1000;
            $admin->must_change_password = $admin->must_change_password ?? true;

            // Generate username if not provided
            if (!$admin->username) {
                $admin->generateUsername();
            }

            // Generate employee ID if not provided
            if (!$admin->employee_id) {
                $admin->employee_id = 'EMP' . str_pad(static::count() + 1, 4, '0', STR_PAD_LEFT);
            }

            // Set default permissions based on role
            if (!$admin->permissions) {
                $admin->permissions = $admin->getDefaultPermissionsByRole($admin->role);
            }
        });

        static::created(function ($admin) {
            // Create default preferences
            $admin->preferences = [
                'email_notifications' => true,
                'sms_notifications' => false,
                'dashboard_layout' => 'default',
                'items_per_page' => 25,
                'date_format' => 'Y-m-d',
                'time_format' => '24h',
                'auto_logout' => 120 // minutes
            ];
            $admin->save();
        });
    }

    private function getDefaultPermissionsByRole($role)
    {
        $rolePermissions = [
            'super_admin' => self::PERMISSIONS,
            'admin' => [
                'manage_users', 'view_users', 'edit_users',
                'manage_products', 'view_products', 'approve_products',
                'manage_orders', 'view_orders', 'edit_orders',
                'view_reports', 'view_analytics',
                'manage_support', 'view_tickets', 'respond_tickets'
            ],
            'manager' => [
                'view_users', 'edit_users',
                'view_products', 'approve_products',
                'view_orders', 'edit_orders',
                'view_reports', 'manage_support'
            ],
            'supervisor' => [
                'view_users', 'view_products', 'view_orders',
                'view_reports', 'view_tickets', 'respond_tickets'
            ],
            'moderator' => [
                'view_users', 'view_products', 'approve_products',
                'view_tickets', 'respond_tickets'
            ],
            'support' => [
                'view_tickets', 'respond_tickets', 'close_tickets'
            ],
            'finance' => [
                'manage_finances', 'view_finances', 'manage_withdrawals',
                'approve_withdrawals', 'view_reports'
            ],
            'marketing' => [
                'manage_marketing', 'send_emails', 'manage_promotions',
                'manage_coupons', 'view_reports'
            ],
            'hr' => [
                'manage_admins', 'view_admins', 'create_admins',
                'edit_admins', 'view_reports'
            ],
            'developer' => [
                'manage_settings', 'view_settings', 'edit_settings',
                'view_logs', 'manage_backups', 'clear_cache'
            ]
        ];

        return $rolePermissions[$role] ?? ['view_reports'];
    }
}
