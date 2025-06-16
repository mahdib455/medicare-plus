<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'appointment_id',
        'doctor_id',
        'patient_id',
        'prescribed_at',
        'notes',
        'status', // draft, active, completed, cancelled
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'appointment_id' => 'integer',
        'doctor_id' => 'integer',
        'patient_id' => 'integer',
        'prescribed_at' => 'datetime',
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function lines()
    {
        return $this->hasMany(PrescriptionLine::class);
    }

    /**
     * Get all medications through prescription lines.
     */
    public function medications()
    {
        return $this->hasManyThrough(
            Medication::class,
            PrescriptionLine::class,
            'prescription_id', // Foreign key on prescription_lines table
            'id', // Foreign key on medications table
            'id', // Local key on prescriptions table
            'medication_id' // Local key on prescription_lines table
        );
    }

    /**
     * Get the formatted prescribed date.
     */
    public function getFormattedPrescribedDateAttribute()
    {
        return $this->prescribed_at ? $this->prescribed_at->format('d/m/Y à H:i') : null;
    }

    /**
     * Get the total number of medications in this prescription.
     */
    public function getMedicationCountAttribute()
    {
        return $this->lines()->count();
    }

    /**
     * Check if the prescription has any active lines.
     */
    public function hasActiveLines()
    {
        return $this->lines()->where('end_date', '>=', now())->exists();
    }

    /**
     * Get a summary of all medications in this prescription.
     */
    public function getMedicationSummaryAttribute()
    {
        return $this->lines()->with('medication')->get()
            ->map(function ($line) {
                return $line->summary;
            })->implode(', ');
    }

    /**
     * Scope to get prescriptions with active lines.
     */
    public function scopeWithActiveLines($query)
    {
        return $query->whereHas('lines', function ($q) {
            $q->where('end_date', '>=', now());
        });
    }
}
