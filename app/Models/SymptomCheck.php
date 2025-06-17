<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SymptomCheck extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'symptomcheck';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The name of the "created at" column.
     *
     * @var string
     */
    const CREATED_AT = 'created_at';

    /**
     * The name of the "updated at" column.
     *
     * @var string
     */
    const UPDATED_AT = 'updated_at';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'symptom_text',
        'result',
        'recommended_doctor',
        'urgency_level',
        'severity',
        'detected_categories',
        'analysis',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'user_id' => 'integer',
        'detected_categories' => 'array',
        'analysis' => 'array',
        'urgency_level' => 'integer',
        'severity' => 'integer',
    ];

    /**
     * Get the user that owns the symptom check.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include high urgency symptom checks.
     */
    public function scopeHighUrgency($query)
    {
        return $query->where('urgency_level', '>=', 8);
    }

    /**
     * Scope a query to only include symptom checks by urgency level.
     */
    public function scopeByUrgencyLevel($query, $level)
    {
        return $query->where('urgency_level', $level);
    }

    /**
     * Scope a query to only include symptom checks by severity.
     */
    public function scopeBySeverity($query, $severity)
    {
        return $query->where('severity', $severity);
    }

    /**
     * Scope a query to filter by detected categories.
     */
    public function scopeByCategory($query, $category)
    {
        return $query->whereJsonContains('detected_categories', $category);
    }

    /**
     * Get the urgency level as a human-readable string.
     */
    public function getUrgencyLevelTextAttribute()
    {
        $levels = [
            1 => 'Very Low',
            2 => 'Low',
            3 => 'Low-Medium',
            4 => 'Medium',
            5 => 'Medium',
            6 => 'Medium-High',
            7 => 'High',
            8 => 'Very High',
            9 => 'Critical',
            10 => 'Emergency'
        ];

        return $levels[$this->urgency_level] ?? 'Unknown';
    }

    /**
     * Get the severity as a human-readable string.
     */
    public function getSeverityTextAttribute()
    {
        $severities = [
            1 => 'Mild',
            2 => 'Mild',
            3 => 'Mild-Moderate',
            4 => 'Moderate',
            5 => 'Moderate',
            6 => 'Moderate-Severe',
            7 => 'Severe',
            8 => 'Very Severe',
            9 => 'Critical',
            10 => 'Life-threatening'
        ];

        return $severities[$this->severity] ?? 'Unknown';
    }

    /**
     * Get the urgency level color for UI display.
     */
    public function getUrgencyColorAttribute()
    {
        if ($this->urgency_level >= 9) return 'danger';
        if ($this->urgency_level >= 7) return 'warning';
        if ($this->urgency_level >= 5) return 'info';
        return 'success';
    }

    /**
     * Get the severity color for UI display.
     */
    public function getSeverityColorAttribute()
    {
        if ($this->severity >= 8) return 'danger';
        if ($this->severity >= 6) return 'warning';
        if ($this->severity >= 4) return 'info';
        return 'success';
    }

    /**
     * Check if the symptom check requires immediate attention.
     */
    public function requiresImmediateAttention()
    {
        return $this->urgency_level >= 8 || $this->severity >= 8;
    }

    /**
     * Get a formatted summary of detected categories.
     */
    public function getCategoriesSummaryAttribute()
    {
        if (!$this->detected_categories || !is_array($this->detected_categories)) {
            return 'No categories detected';
        }

        return implode(', ', $this->detected_categories);
    }

    /**
     * Get the primary detected category.
     */
    public function getPrimaryCategoryAttribute()
    {
        if (!$this->detected_categories || !is_array($this->detected_categories) || empty($this->detected_categories)) {
            return null;
        }

        return $this->detected_categories[0];
    }

    /**
     * Get a short version of the symptom text.
     */
    public function getShortSymptomTextAttribute()
    {
        return \Illuminate\Support\Str::limit($this->symptom_text, 100);
    }

    /**
     * Get a short version of the result.
     */
    public function getShortResultAttribute()
    {
        return \Illuminate\Support\Str::limit($this->result, 150);
    }

    /**
     * Scope a query to only include recent symptom checks.
     */
    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Scope a query to only include symptom checks from today.
     */
    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    /**
     * Scope a query to only include symptom checks from this week.
     */
    public function scopeThisWeek($query)
    {
        return $query->whereBetween('created_at', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ]);
    }

    /**
     * Get the formatted created date.
     */
    public function getFormattedDateAttribute()
    {
        return $this->created_at->format('d/m/Y à H:i');
    }
}
