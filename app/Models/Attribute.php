<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attribute extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'type',
        'display_name',
        'description',
        'is_required',
        'is_filterable',
        'is_variation',
        'is_global',
        'sort_order',
        'status',
        'validation_rules',
        'default_value',
        'placeholder',
        'help_text',
        'admin_only',
        'frontend_type',
        'options',
        'metadata'
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'is_filterable' => 'boolean',
        'is_variation' => 'boolean',
        'is_global' => 'boolean',
        'admin_only' => 'boolean',
        'validation_rules' => 'array',
        'options' => 'array',
        'metadata' => 'array'
    ];

    // Relationships
    public function values()
    {
        return $this->hasMany(AttributeValue::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_attributes');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeRequired($query)
    {
        return $query->where('is_required', true);
    }

    public function scopeFilterable($query)
    {
        return $query->where('is_filterable', true);
    }

    public function scopeVariation($query)
    {
        return $query->where('is_variation', true);
    }

    public function scopeGlobal($query)
    {
        return $query->where('is_global', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    // Accessors
    public function getStatusBadgeAttribute()
    {
        $classes = [
            'active' => 'badge-success',
            'inactive' => 'badge-danger',
            'draft' => 'badge-warning'
        ];

        return $classes[$this->status] ?? 'badge-secondary';
    }

    // Methods
    public static function getTypes()
    {
        return [
            'text' => 'Text',
            'textarea' => 'Textarea',
            'number' => 'Number',
            'decimal' => 'Decimal',
            'boolean' => 'Boolean',
            'date' => 'Date',
            'datetime' => 'DateTime',
            'select' => 'Select',
            'multiselect' => 'Multi Select',
            'radio' => 'Radio',
            'checkbox' => 'Checkbox',
            'color' => 'Color',
            'image' => 'Image',
            'file' => 'File',
            'url' => 'URL',
            'email' => 'Email'
        ];
    }

    public static function getFrontendTypes()
    {
        return [
            'text' => 'Text Input',
            'textarea' => 'Textarea',
            'number' => 'Number Input',
            'select' => 'Dropdown',
            'multiselect' => 'Multi Select',
            'radio' => 'Radio Buttons',
            'checkbox' => 'Checkboxes',
            'color' => 'Color Picker',
            'date' => 'Date Picker',
            'datetime' => 'DateTime Picker',
            'file' => 'File Upload',
            'image' => 'Image Upload',
            'slider' => 'Range Slider',
            'toggle' => 'Toggle Switch'
        ];
    }

    public static function getStatuses()
    {
        return [
            'active' => 'Active',
            'inactive' => 'Inactive',
            'draft' => 'Draft'
        ];
    }

    public function getValuesForSelect()
    {
        return $this->values()
            ->where('status', 'active')
            ->orderBy('sort_order')
            ->pluck('display_name', 'id')
            ->toArray();
    }

    public function canHaveValues()
    {
        return in_array($this->type, ['select', 'multiselect', 'radio', 'checkbox']);
    }

    public function validateValue($value)
    {
        if ($this->is_required && empty($value)) {
            return false;
        }

        // Add custom validation logic based on type
        switch ($this->type) {
            case 'number':
                return is_numeric($value);
            case 'email':
                return filter_var($value, FILTER_VALIDATE_EMAIL);
            case 'url':
                return filter_var($value, FILTER_VALIDATE_URL);
            case 'boolean':
                return in_array($value, [true, false, 0, 1, '0', '1']);
            default:
                return true;
        }
    }
}
