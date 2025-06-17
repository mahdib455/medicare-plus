<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrescriptionLine extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'prescription_lines';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'prescription_id',
        'medication_id',
        'quantity',
        'dosage',
        'frequency',
        'duration_days',
        'instructions',
        'start_date',
        'end_date',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'prescription_id' => 'integer',
        'medication_id' => 'integer',
        'quantity' => 'integer',
        'duration_days' => 'integer',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Get the prescription that owns the line.
     */
    public function prescription()
    {
        return $this->belongsTo(Prescription::class);
    }

    /**
     * Get the medication for this line.
     */
    public function medication()
    {
        return $this->belongsTo(Medication::class);
    }

    /**
     * Check if the prescription line is currently active.
     */
    public function isActive()
    {
        return $this->start_date <= now() && $this->end_date >= now();
    }

    /**
     * Check if the prescription line has expired.
     */
    public function isExpired()
    {
        return $this->end_date < now();
    }

    /**
     * Get the remaining days for this prescription line.
     */
    public function getRemainingDaysAttribute()
    {
        if ($this->isExpired()) {
            return 0;
        }

        return now()->diffInDays($this->end_date) + 1;
    }

    /**
     * Get the status of the prescription line.
     */
    public function getStatusAttribute()
    {
        if ($this->start_date > now()) {
            return 'future';
        } elseif ($this->isActive()) {
            return 'active';
        } else {
            return 'expired';
        }
    }

    /**
     * Get a formatted summary of the prescription line.
     */
    public function getSummaryAttribute()
    {
        $medication = $this->medication ? $this->medication->full_name : 'Médicament inconnu';
        return "{$medication} - {$this->dosage} - {$this->frequency}";
    }
}
