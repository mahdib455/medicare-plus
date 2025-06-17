<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consultation extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'consultations';

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
        'appointment_id',
        'consultation_date',
        'diagnosis',
        'treatment',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'appointment_id' => 'integer',
        'consultation_date' => 'datetime',
    ];

    /**
     * Get the appointment that owns the consultation.
     */
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    /**
     * Get the patient through the appointment.
     */
    public function patient()
    {
        return $this->hasOneThrough(
            Patient::class,
            Appointment::class,
            'id', // Foreign key on appointments table
            'id', // Foreign key on patients table
            'appointment_id', // Local key on consultations table
            'patient_id' // Local key on appointments table
        );
    }

    /**
     * Get the doctor through the appointment.
     */
    public function doctor()
    {
        return $this->hasOneThrough(
            Doctor::class,
            Appointment::class,
            'id', // Foreign key on appointments table
            'id', // Foreign key on doctors table
            'appointment_id', // Local key on consultations table
            'doctor_id' // Local key on appointments table
        );
    }

    /**
     * Get the reviews for this consultation.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class, 'consultation_id');
    }

    /**
     * Scope a query to only include consultations from today.
     */
    public function scopeToday($query)
    {
        return $query->whereDate('consultation_date', today());
    }

    /**
     * Scope a query to only include consultations from this week.
     */
    public function scopeThisWeek($query)
    {
        return $query->whereBetween('consultation_date', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ]);
    }

    /**
     * Scope a query to only include consultations from this month.
     */
    public function scopeThisMonth($query)
    {
        return $query->whereMonth('consultation_date', now()->month)
                    ->whereYear('consultation_date', now()->year);
    }

    /**
     * Scope a query to only include consultations for a specific doctor.
     */
    public function scopeForDoctor($query, $doctorId)
    {
        return $query->whereHas('appointment', function ($q) use ($doctorId) {
            $q->where('doctor_id', $doctorId);
        });
    }

    /**
     * Scope a query to only include consultations for a specific patient.
     */
    public function scopeForPatient($query, $patientId)
    {
        return $query->whereHas('appointment', function ($q) use ($patientId) {
            $q->where('patient_id', $patientId);
        });
    }

    /**
     * Get the consultation duration in minutes.
     */
    public function getDurationAttribute()
    {
        if ($this->appointment && $this->appointment->appointment_date) {
            return $this->consultation_date->diffInMinutes($this->appointment->appointment_date);
        }
        return null;
    }

    /**
     * Get a formatted consultation date.
     */
    public function getFormattedDateAttribute()
    {
        return $this->consultation_date->format('d/m/Y à H:i');
    }

    /**
     * Get a short version of the diagnosis.
     */
    public function getShortDiagnosisAttribute()
    {
        return \Illuminate\Support\Str::limit($this->diagnosis, 100);
    }

    /**
     * Get a short version of the notes.
     */
    public function getShortNotesAttribute()
    {
        return \Illuminate\Support\Str::limit($this->notes, 100);
    }

    /**
     * Check if the consultation has a diagnosis.
     */
    public function hasDiagnosis()
    {
        return !empty($this->diagnosis);
    }

    /**
     * Check if the consultation has notes.
     */
    public function hasNotes()
    {
        return !empty($this->notes);
    }
}
