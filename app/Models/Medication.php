<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medication extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'generic_name',
        'brand_name',
        'description',
        'category',
        'unit',
        'strength',
        'form', // tablet, capsule, syrup, injection, etc.
        'manufacturer',
        'active_ingredient',
        'contraindications',
        'side_effects',
        'storage_conditions',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the prescription lines for the medication.
     */
    public function prescriptionLines()
    {
        return $this->hasMany(PrescriptionLine::class);
    }

    /**
     * Scope a query to only include active medications.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to filter by category.
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope a query to search by name.
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', "%{$search}%")
                    ->orWhere('generic_name', 'like', "%{$search}%")
                    ->orWhere('brand_name', 'like', "%{$search}%");
    }

    /**
     * Get the full medication name with strength.
     */
    public function getFullNameAttribute()
    {
        $name = $this->brand_name ?: $this->name;
        return $this->strength ? "{$name} {$this->strength}" : $name;
    }

    /**
     * Get the display name for the medication.
     */
    public function getDisplayNameAttribute()
    {
        $parts = [];
        
        if ($this->brand_name) {
            $parts[] = $this->brand_name;
        }
        
        if ($this->generic_name && $this->generic_name !== $this->brand_name) {
            $parts[] = "({$this->generic_name})";
        }
        
        if ($this->strength) {
            $parts[] = $this->strength;
        }
        
        if ($this->form) {
            $parts[] = "- {$this->form}";
        }
        
        return implode(' ', $parts);
    }
}
