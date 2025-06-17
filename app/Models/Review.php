<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'reviews';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'consultation_id',
        'doctor_id',
        'patient_id',
        'rating',
        'comment',
        'is_anonymous',
        'status'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'consultation_id' => 'integer',
        'doctor_id' => 'integer',
        'patient_id' => 'integer',
        'rating' => 'integer',
        'is_anonymous' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * The attributes that should have default values.
     *
     * @var array
     */
    protected $attributes = [
        'is_anonymous' => false,
        'status' => 'active',
    ];

    /**
     * Relationship: Review belongs to Consultation
     */
    public function consultation()
    {
        return $this->belongsTo(Consultation::class, 'consultation_id');
    }

    /**
     * Relationship: Review belongs to Doctor
     */
    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }

    /**
     * Relationship: Review belongs to Patient
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    /**
     * Validation rules for creating a review
     */
    public static function validationRules()
    {
        return [
            'consultation_id' => 'nullable|exists:consultations,id',
            'doctor_id' => 'required|exists:doctors,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'is_anonymous' => 'boolean',
        ];
    }

    /**
     * Scope a query to only include reviews for a specific doctor.
     */
    public function scopeForDoctor($query, $doctorId)
    {
        return $query->where('doctor_id', $doctorId);
    }

    /**
     * Scope a query to only include reviews by a specific patient.
     */
    public function scopeByPatient($query, $patientId)
    {
        return $query->where('patient_id', $patientId);
    }

    /**
     * Scope a query to only include active reviews.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Get the rating as stars (for display).
     */
    public function getStarsAttribute()
    {
        $stars = '';
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $this->rating) {
                $stars .= '★';
            } else {
                $stars .= '☆';
            }
        }
        return $stars;
    }

    /**
     * Get the rating text description.
     */
    public function getRatingTextAttribute()
    {
        $ratings = [
            1 => 'Very Poor',
            2 => 'Poor',
            3 => 'Average',
            4 => 'Good',
            5 => 'Excellent'
        ];

        return $ratings[$this->rating] ?? 'Unknown';
    }

    /**
     * Get the rating color for UI display.
     */
    public function getRatingColorAttribute()
    {
        if ($this->rating >= 4) return 'success';
        if ($this->rating >= 3) return 'warning';
        return 'danger';
    }

    /**
     * Get a short version of the comment.
     */
    public function getShortCommentAttribute()
    {
        return \Illuminate\Support\Str::limit($this->comment, 100);
    }

    /**
     * Get the formatted created date.
     */
    public function getFormattedDateAttribute()
    {
        return $this->created_at->format('m/d/Y at H:i');
    }

    /**
     * Get the relative time (e.g., "2 days ago").
     */
    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Check if the review has a comment.
     */
    public function hasComment()
    {
        return !empty($this->comment);
    }

    /**
     * Get anonymous display name for the review.
     */
    public function getAnonymousNameAttribute()
    {
        return 'Patient #' . str_pad($this->id, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Scope a query to only include reviews with comments.
     */
    public function scopeWithComments($query)
    {
        return $query->whereNotNull('comment')->where('comment', '!=', '');
    }

    /**
     * Scope a query to only include high-rated reviews (4-5 stars).
     */
    public function scopeHighRated($query)
    {
        return $query->where('rating', '>=', 4);
    }

    /**
     * Scope a query to only include low-rated reviews (1-2 stars).
     */
    public function scopeLowRated($query)
    {
        return $query->where('rating', '<=', 2);
    }

    /**
     * Get doctor statistics for reviews.
     */
    public static function getDoctorStats($doctorId)
    {
        $reviews = self::forDoctor($doctorId);
        
        return [
            'total_reviews' => $reviews->count(),
            'average_rating' => round($reviews->avg('rating'), 1),
            'rating_distribution' => [
                5 => $reviews->withRating(5)->count(),
                4 => $reviews->withRating(4)->count(),
                3 => $reviews->withRating(3)->count(),
                2 => $reviews->withRating(2)->count(),
                1 => $reviews->withRating(1)->count(),
            ],
            'recent_reviews' => $reviews->recent(30)->count(),
            'with_comments' => $reviews->withComments()->count(),
        ];
    }
}
