<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Appointment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    /**
     * Display a listing of reviews for the authenticated user.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        if ($user->role === 'patient') {
            return $this->patientReviews($request);
        } elseif ($user->role === 'doctor') {
            return $this->doctorReviews($request);
        }
        
        return redirect()->back()->with('error', 'Unauthorized access');
    }

    /**
     * Get reviews for patient (reviews they've written).
     */
    private function patientReviews(Request $request)
    {
        $user = Auth::user();
        
        $reviews = Review::byPatient($user->id)
            ->with(['appointment', 'doctor'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('patient.reviews.index', compact('reviews'));
    }

    /**
     * Get reviews for doctor (reviews they've received).
     */
    private function doctorReviews(Request $request)
    {
        $user = Auth::user();
        
        $reviews = Review::forDoctor($user->id)
            ->with(['appointment'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        $stats = Review::getDoctorStats($user->id);
        
        return view('doctor.reviews.index', compact('reviews', 'stats'));
    }

    /**
     * Show the form for creating a new review.
     */
    public function create(Request $request)
    {
        $appointmentId = $request->get('appointment_id');
        
        if (!$appointmentId) {
            return redirect()->back()->with('error', 'Appointment ID is required');
        }
        
        $appointment = Appointment::with('doctor')->find($appointmentId);
        
        if (!$appointment) {
            return redirect()->back()->with('error', 'Appointment not found');
        }
        
        // Check if appointment belongs to current patient
        if ($appointment->patient_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Unauthorized access');
        }
        
        // Check if appointment is completed
        if ($appointment->status !== 'termine') {
            return redirect()->back()->with('error', 'You can only review completed appointments');
        }
        
        // Check if review already exists
        $existingReview = Review::where('appointment_id', $appointmentId)
            ->where('patient_id', Auth::id())
            ->first();
            
        if ($existingReview) {
            return redirect()->back()->with('error', 'You have already reviewed this appointment');
        }
        
        return view('patient.reviews.create', compact('appointment'));
    }

    /**
     * Store a newly created review in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);
        
        $appointment = Appointment::find($request->appointment_id);
        
        // Verify appointment belongs to current patient
        if ($appointment->patient_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized access'], 403);
        }
        
        // Verify appointment is completed
        if ($appointment->status !== 'termine') {
            return response()->json(['error' => 'You can only review completed appointments'], 400);
        }
        
        // Check if review already exists
        $existingReview = Review::where('appointment_id', $request->appointment_id)
            ->where('patient_id', Auth::id())
            ->first();
            
        if ($existingReview) {
            return response()->json(['error' => 'You have already reviewed this appointment'], 400);
        }
        
        try {
            $review = Review::create([
                'appointment_id' => $request->appointment_id,
                'patient_id' => Auth::id(),
                'doctor_id' => $appointment->doctor_id,
                'rating' => $request->rating,
                'comment' => $request->comment,
                'is_anonymous' => true, // Always anonymous for doctors
                'status' => 'active'
            ]);
            
            Log::info('Review created successfully', [
                'review_id' => $review->id,
                'patient_id' => Auth::id(),
                'doctor_id' => $appointment->doctor_id,
                'rating' => $request->rating
            ]);
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Review submitted successfully',
                    'review' => $review
                ]);
            }
            
            return redirect()->route('patient.dashboard')
                ->with('success', 'Review submitted successfully');
                
        } catch (\Exception $e) {
            Log::error('Error creating review: ' . $e->getMessage());
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Failed to submit review'
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', 'Failed to submit review')
                ->withInput();
        }
    }

    /**
     * Display the specified review.
     */
    public function show(Review $review)
    {
        $user = Auth::user();
        
        // Check authorization
        if ($user->role === 'patient' && $review->patient_id !== $user->id) {
            return redirect()->back()->with('error', 'Unauthorized access');
        }
        
        if ($user->role === 'doctor' && $review->doctor_id !== $user->id) {
            return redirect()->back()->with('error', 'Unauthorized access');
        }
        
        $review->load(['appointment', 'patient', 'doctor']);
        
        return view('reviews.show', compact('review'));
    }

    /**
     * Store a review for a consultation.
     */
    public function storeConsultationReview(Request $request)
    {
        $request->validate([
            'consultation_id' => 'required|exists:consultations,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000'
        ]);

        $user = Auth::user();

        if ($user->role !== 'patient') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Get consultation with appointment
        $consultation = \App\Models\Consultation::with('appointment')
            ->where('id', $request->consultation_id)
            ->first();

        if (!$consultation) {
            return response()->json(['error' => 'Consultation not found'], 404);
        }

        // Check if consultation belongs to this patient
        if ($consultation->appointment->patient_id !== $user->id) {
            return response()->json(['error' => 'Consultation not found or unauthorized'], 404);
        }

        // Check if already reviewed
        $existingReview = Review::where('consultation_id', $request->consultation_id)
            ->where('patient_id', $user->id)
            ->first();

        if ($existingReview) {
            return response()->json(['error' => 'Consultation already reviewed'], 400);
        }

        try {
            // Create review (only consultation_id needed now)
            $review = Review::create([
                'consultation_id' => $request->consultation_id,
                'patient_id' => $user->id,
                'doctor_id' => $consultation->appointment->doctor_id,
                'rating' => $request->rating,
                'comment' => $request->comment,
                'is_anonymous' => true,
                'status' => 'active'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Review submitted successfully',
                'review' => [
                    'id' => $review->id,
                    'rating' => $review->rating,
                    'stars' => $review->stars,
                    'comment' => $review->comment,
                    'formatted_date' => $review->formatted_date
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error creating consultation review: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'error' => 'Failed to submit review'
            ], 500);
        }
    }

    /**
     * Get all consultations for patient (for review selection).
     */
    public function getAllConsultations(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'patient') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Get patient record
        $patient = \App\Models\Patient::where('user_id', $user->id)->first();
        if (!$patient) {
            return response()->json(['error' => 'No patient record found'], 404);
        }

        // Get all consultations for this patient through appointments
        $consultations = \App\Models\Consultation::whereHas('appointment', function($query) use ($patient) {
                $query->where('patient_id', $patient->id);
            })
            ->with(['appointment.doctor.user', 'appointment.prescription'])
            ->orderBy('consultation_date', 'desc')
            ->get()
            ->map(function($consultation) use ($patient) {
                // Check if this consultation has been reviewed
                $review = Review::where('consultation_id', $consultation->id)
                    ->where('patient_id', $patient->id)
                    ->first();

                return [
                    'id' => $consultation->id,
                    'consultation_date' => $consultation->consultation_date->format('m/d/Y'),
                    'consultation_time' => $consultation->consultation_date->format('H:i'),
                    'formatted_date' => $consultation->consultation_date->format('m/d/Y at H:i'),
                    'diagnosis' => $consultation->diagnosis,
                    'treatment' => $consultation->treatment,
                    'notes' => $consultation->notes,
                    'appointment' => [
                        'id' => $consultation->appointment->id,
                        'status' => $consultation->appointment->status,
                        'reason' => $consultation->appointment->reason
                    ],
                    'doctor' => [
                        'id' => $consultation->appointment->doctor->id,
                        'name' => $consultation->appointment->doctor->user->full_name,
                        'speciality' => $consultation->appointment->doctor->speciality ?? 'General Practitioner'
                    ],
                    'has_prescription' => $consultation->appointment->prescription ? true : false,
                    'is_reviewed' => $review ? true : false,
                    'review' => $review ? [
                        'id' => $review->id,
                        'rating' => $review->rating,
                        'stars' => $review->stars,
                        'comment' => $review->comment,
                        'created_at' => $review->formatted_date
                    ] : null
                ];
            });

        return response()->json([
            'success' => true,
            'consultations' => $consultations
        ]);
    }

    /**
     * Get all appointments for patient (for review selection).
     */
    public function getAllAppointments(Request $request)
    {
        // Step 1: Check authenticated user
        $user = Auth::user();
        \Log::info('🔍 Step 1 - Authenticated User Check', [
            'user_exists' => !!$user,
            'user_id' => $user ? $user->id : null,
            'user_role' => $user ? $user->role : null,
            'user_name' => $user ? $user->full_name : null
        ]);

        if (!$user) {
            \Log::error('❌ No authenticated user found');
            return response()->json(['error' => 'Not authenticated'], 401);
        }

        if ($user->role !== 'patient') {
            \Log::error('❌ User is not a patient', ['role' => $user->role]);
            return response()->json(['error' => 'Unauthorized - not a patient'], 403);
        }

        // Step 2: Check patient record
        $patient = \App\Models\Patient::where('user_id', $user->id)->first();
        \Log::info('🔍 Step 2 - Patient Record Check', [
            'patient_exists' => !!$patient,
            'patient_id' => $patient ? $patient->id : null,
            'user_id' => $user->id
        ]);

        if (!$patient) {
            \Log::error('❌ No patient record found for user', ['user_id' => $user->id]);
            return response()->json(['error' => 'No patient record found'], 404);
        }

        // Step 3: Query appointments
        \Log::info('🔍 Step 3 - Querying appointments for patient', ['patient_id' => $patient->id]);

        $appointmentsQuery = Appointment::where('patient_id', $patient->id)
            ->with(['doctor.user', 'consultation', 'prescription']);

        $totalAppointments = $appointmentsQuery->count();
        \Log::info('📊 Total appointments found', ['count' => $totalAppointments]);

        // Step 4: Check completed appointments specifically
        $completedAppointments = Appointment::where('patient_id', $patient->id)
            ->whereIn('status', ['completed', 'termine'])
            ->count();
        \Log::info('📊 Completed appointments found', ['count' => $completedAppointments]);

        // Step 5: Get all appointments with details
        $appointments = $appointmentsQuery
            ->orderBy('appointment_date', 'desc')
            ->get();

        \Log::info('📊 Appointments retrieved', [
            'count' => $appointments->count(),
            'statuses' => $appointments->pluck('status')->toArray()
        ]);

        // Step 6: Process appointments data
        $processedAppointments = $appointments->map(function($appointment) use ($patient) {
            // Check if this appointment has been reviewed through consultation
            $review = null;
            if ($appointment->consultation) {
                $review = Review::where('consultation_id', $appointment->consultation->id)
                    ->where('patient_id', $patient->id)
                    ->first();
            }

            $processed = [
                'id' => $appointment->id,
                'appointment_date' => $appointment->appointment_date->format('m/d/Y'),
                'appointment_time' => $appointment->appointment_date->format('H:i'),
                'formatted_date' => $appointment->appointment_date->format('m/d/Y at H:i'),
                'status' => $appointment->status,
                'reason' => $appointment->reason,
                'doctor' => [
                    'id' => $appointment->doctor->id,
                    'name' => $appointment->doctor->user->full_name,
                    'speciality' => $appointment->doctor->speciality ?? 'General Practitioner'
                ],
                'has_consultation' => $appointment->consultation ? true : false,
                'has_prescription' => $appointment->prescription ? true : false,
                'is_reviewed' => $review ? true : false,
                'review' => $review ? [
                    'id' => $review->id,
                    'rating' => $review->rating,
                    'stars' => $review->stars,
                    'comment' => $review->comment,
                    'created_at' => $review->formatted_date
                ] : null
            ];

            \Log::info('📋 Processed appointment', [
                'id' => $appointment->id,
                'status' => $appointment->status,
                'doctor' => $appointment->doctor->user->full_name,
                'has_consultation' => $processed['has_consultation'],
                'has_prescription' => $processed['has_prescription'],
                'is_reviewed' => $processed['is_reviewed']
            ]);

            return $processed;
        });

        \Log::info('✅ Final response prepared', [
            'total_appointments' => $processedAppointments->count(),
            'completed_appointments' => $completedAppointments,
            'reviewed_appointments' => $processedAppointments->where('is_reviewed', true)->count(),
            'unreviewed_appointments' => $processedAppointments->where('is_reviewed', false)->count()
        ]);

        return response()->json([
            'success' => true,
            'appointments' => $processedAppointments,
            'debug_info' => [
                'user_id' => $user->id,
                'patient_id' => $patient->id,
                'total_count' => $processedAppointments->count(),
                'completed_count' => $completedAppointments,
                'reviewed_count' => $processedAppointments->where('is_reviewed', true)->count()
            ]
        ]);
    }

    /**
     * Get completed appointments for patient.
     */
    public function getCompletedAppointments(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'patient') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Get all completed appointments with review status
        // Try both 'completed' and 'termine' status values
        $appointments = Appointment::where('patient_id', $user->id)
            ->whereIn('status', ['completed', 'termine'])
            ->with(['doctor.user', 'consultation', 'prescription'])
            ->orderBy('appointment_date', 'desc')
            ->get()
            ->map(function($appointment) use ($user) {
                // Check if this appointment has been reviewed through consultation
                $review = null;
                if ($appointment->consultation) {
                    $review = Review::where('consultation_id', $appointment->consultation->id)
                        ->where('patient_id', $user->id)
                        ->first();
                }

                return [
                    'id' => $appointment->id,
                    'appointment_date' => $appointment->appointment_date->format('d/m/Y'),
                    'appointment_time' => $appointment->appointment_date->format('H:i'),
                    'formatted_date' => $appointment->appointment_date->format('d/m/Y à H:i'),
                    'reason' => $appointment->reason,
                    'doctor' => [
                        'id' => $appointment->doctor->id,
                        'name' => $appointment->doctor->user->full_name,
                        'speciality' => $appointment->doctor->speciality ?? 'General Practitioner'
                    ],
                    'has_consultation' => $appointment->consultation ? true : false,
                    'has_prescription' => $appointment->prescription ? true : false,
                    'is_reviewed' => $review ? true : false,
                    'review' => $review ? [
                        'id' => $review->id,
                        'rating' => $review->rating,
                        'stars' => $review->stars,
                        'comment' => $review->comment,
                        'created_at' => $review->formatted_date
                    ] : null
                ];
            });

        return response()->json([
            'success' => true,
            'appointments' => $appointments
        ]);
    }

    /**
     * Get reviewable appointments for patient.
     */
    public function getReviewableAppointments(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'patient') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Get completed appointments that haven't been reviewed
        // Try both 'completed' and 'termine' status values
        $appointments = Appointment::where('patient_id', $user->id)
            ->whereIn('status', ['completed', 'termine'])
            ->whereDoesntHave('consultation.reviews', function($query) use ($user) {
                $query->where('patient_id', $user->id);
            })
            ->with('doctor.user')
            ->orderBy('appointment_date', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'appointments' => $appointments
        ]);
    }

    /**
     * Get doctor review statistics.
     */
    public function getDoctorStats(Request $request)
    {
        $user = Auth::user();
        
        if ($user->role !== 'doctor') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $stats = Review::getDoctorStats($user->id);
        
        return response()->json([
            'success' => true,
            'stats' => $stats
        ]);
    }

    /**
     * Get patient's own reviews.
     */
    public function getMyReviews(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'patient') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $reviews = Review::byPatient($user->id)
            ->with(['consultation.appointment.doctor.user'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($review) {
                return [
                    'id' => $review->id,
                    'rating' => $review->rating,
                    'stars' => $review->stars,
                    'rating_text' => $review->rating_text,
                    'comment' => $review->comment,
                    'short_comment' => $review->short_comment,
                    'formatted_date' => $review->formatted_date,
                    'time_ago' => $review->time_ago,
                    'consultation' => [
                        'id' => $review->consultation->id,
                        'date' => $review->consultation->consultation_date->format('d/m/Y à H:i'),
                        'diagnosis' => $review->consultation->diagnosis,
                        'treatment' => $review->consultation->treatment
                    ],
                    'appointment' => [
                        'id' => $review->consultation->appointment->id,
                        'date' => $review->consultation->appointment->appointment_date->format('d/m/Y à H:i'),
                        'reason' => $review->consultation->appointment->reason,
                        'doctor' => [
                            'name' => $review->consultation->appointment->doctor->user->full_name,
                            'speciality' => $review->consultation->appointment->doctor->speciality ?? 'General Practitioner'
                        ]
                    ]
                ];
            });

        return response()->json([
            'success' => true,
            'reviews' => $reviews
        ]);
    }

    /**
     * Get anonymous reviews for doctor.
     */
    public function getAnonymousReviews(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'doctor') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $reviews = Review::forDoctor($user->id)
            ->withComments()
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get()
            ->map(function($review) {
                return [
                    'id' => $review->id,
                    'rating' => $review->rating,
                    'stars' => $review->stars,
                    'rating_text' => $review->rating_text,
                    'rating_color' => $review->rating_color,
                    'comment' => $review->comment,
                    'short_comment' => $review->short_comment,
                    'anonymous_name' => $review->anonymous_name,
                    'formatted_date' => $review->formatted_date,
                    'time_ago' => $review->time_ago,
                ];
            });

        return response()->json([
            'success' => true,
            'reviews' => $reviews
        ]);
    }
}
