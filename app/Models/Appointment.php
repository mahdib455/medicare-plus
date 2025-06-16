<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    /**
     * The possible values for the status enum.
     */
    const STATUS_PENDING = 'pending';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_COMPLETED = 'completed';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'patient_id',
        'doctor_id',
        'appointment_date',
        'status',
        'reason',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'patient_id' => 'integer',
        'doctor_id' => 'integer',
        'appointment_date' => 'datetime',
        'status' => 'string',
    ];

    /**
     * Get the patient that owns the appointment.
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Get the doctor that owns the appointment.
     */
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    /**
     * Get the consultation associated with the appointment.
     */
    public function consultation()
    {
        return $this->hasOne(Consultation::class);
    }

    /**
     * Get the prescriptions for the appointment.
     */
    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }

    /**
     * Check if the appointment is pending.
     */
    public function isPending()
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Check if the appointment is confirmed.
     */
    public function isConfirmed()
    {
        return $this->status === self::STATUS_CONFIRMED;
    }

    /**
     * Check if the appointment is cancelled.
     */
    public function isCancelled()
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    /**
     * Check if the appointment is completed.
     */
    public function isCompleted()
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    /**
     * Get the status badge color for display.
     */
    public function getStatusBadgeColorAttribute()
    {
        return match($this->status) {
            self::STATUS_PENDING => 'warning',
            self::STATUS_CONFIRMED => 'success',
            self::STATUS_CANCELLED => 'danger',
            self::STATUS_COMPLETED => 'info',
            default => 'secondary'
        };
    }

    /**
     * Get the status label in French.
     */
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            self::STATUS_PENDING => 'En attente',
            self::STATUS_CONFIRMED => 'Confirmé',
            self::STATUS_CANCELLED => 'Annulé',
            self::STATUS_COMPLETED => 'Terminé',
            default => 'Inconnu'
        };
    }

    /**
     * Scope a query to only include appointments for a specific status.
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to only include appointments for today.
     */
    public function scopeToday($query)
    {
        return $query->whereDate('appointment_date', today());
    }

    /**
     * Scope a query to only include upcoming appointments.
     */
    public function scopeUpcoming($query)
    {
        return $query->where('appointment_date', '>', now());
    }

    /**
     * Scope a query to only include past appointments.
     */
    public function scopePast($query)
    {
        return $query->where('appointment_date', '<', now());
    }

    /**
     * Check if the appointment has a consultation.
     */
    public function hasConsultation()
    {
        return $this->consultation !== null;
    }

    /**
     * Check if the appointment is ready for consultation.
     */
    public function isReadyForConsultation()
    {
        return $this->isConfirmed() && !$this->hasConsultation();
    }

    /**
     * Get the consultation or create a new one.
     */
    public function getOrCreateConsultation()
    {
        if ($this->hasConsultation()) {
            return $this->consultation;
        }

        return $this->consultation()->create([
            'consultation_date' => now(),
            'diagnosis' => '',
            'notes' => ''
        ]);
    }
}
