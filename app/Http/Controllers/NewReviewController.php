<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Consultation;
use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class NewReviewController extends Controller
{
    /**
     * Store a new review
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            // Get authenticated user
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'error' => 'Authentication required'
                ], 401);
            }

            // Get patient record
            $patient = Patient::where('user_id', $user->id)->first();
            
            if (!$patient) {
                return response()->json([
                    'success' => false,
                    'error' => 'Patient record not found'
                ], 404);
            }

            // Validate input
            $validatedData = $request->validate(Review::validationRules());

            // If consultation_id is provided, verify ownership and prevent duplicates
            if ($request->consultation_id) {
                $consultation = Consultation::with('appointment')->find($request->consultation_id);
                
                if (!$consultation) {
                    return response()->json([
                        'success' => false,
                        'error' => 'Consultation not found'
                    ], 404);
                }

                // Ensure patient owns the consultation
                if ($consultation->appointment->patient_id !== $patient->id) {
                    return response()->json([
                        'success' => false,
                        'error' => 'Unauthorized: You can only review your own consultations'
                    ], 403);
                }

                // Check for duplicate review on same consultation
                $existingReview = Review::where('consultation_id', $request->consultation_id)
                    ->where('patient_id', $patient->id)
                    ->first();

                if ($existingReview) {
                    return response()->json([
                        'success' => false,
                        'error' => 'You have already reviewed this consultation'
                    ], 409);
                }
            }

            // Verify doctor exists
            $doctor = Doctor::find($request->doctor_id);
            if (!$doctor) {
                return response()->json([
                    'success' => false,
                    'error' => 'Doctor not found'
                ], 404);
            }

            // Create the review
            $review = Review::create([
                'consultation_id' => $request->consultation_id,
                'doctor_id' => $request->doctor_id,
                'patient_id' => $patient->id,
                'rating' => $request->rating,
                'comment' => $request->comment,
                'is_anonymous' => $request->is_anonymous ?? false,
                'status' => 'active' // Default status
            ]);

            // Load relationships for response
            $review->load(['consultation', 'doctor.user', 'patient.user']);

            return response()->json([
                'success' => true,
                'message' => 'Review submitted successfully',
                'data' => [
                    'review' => [
                        'id' => $review->id,
                        'consultation_id' => $review->consultation_id,
                        'doctor_id' => $review->doctor_id,
                        'patient_id' => $review->patient_id,
                        'rating' => $review->rating,
                        'comment' => $review->comment,
                        'is_anonymous' => $review->is_anonymous,
                        'status' => $review->status,
                        'created_at' => $review->created_at->format('Y-m-d H:i:s'),
                        'doctor' => [
                            'id' => $review->doctor->id,
                            'name' => $review->doctor->user->full_name,
                            'speciality' => $review->doctor->speciality ?? 'General Practitioner'
                        ],
                        'consultation' => $review->consultation ? [
                            'id' => $review->consultation->id,
                            'date' => $review->consultation->consultation_date->format('Y-m-d H:i:s'),
                            'diagnosis' => $review->consultation->diagnosis
                        ] : null
                    ]
                ]
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'error' => 'Validation failed',
                'details' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Review creation failed: ' . $e->getMessage(), [
                'user_id' => $user->id ?? null,
                'request_data' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Failed to create review. Please try again.'
            ], 500);
        }
    }

    /**
     * Get reviews for a specific doctor
     *
     * @param Request $request
     * @param int $doctorId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDoctorReviews(Request $request, $doctorId)
    {
        try {
            $doctor = Doctor::find($doctorId);
            
            if (!$doctor) {
                return response()->json([
                    'success' => false,
                    'error' => 'Doctor not found'
                ], 404);
            }

            $reviews = Review::forDoctor($doctorId)
                ->active()
                ->with(['consultation', 'patient.user'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            return response()->json([
                'success' => true,
                'data' => [
                    'doctor' => [
                        'id' => $doctor->id,
                        'name' => $doctor->user->full_name,
                        'speciality' => $doctor->speciality ?? 'General Practitioner'
                    ],
                    'reviews' => $reviews->items(),
                    'pagination' => [
                        'current_page' => $reviews->currentPage(),
                        'total_pages' => $reviews->lastPage(),
                        'total_reviews' => $reviews->total()
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to fetch reviews'
            ], 500);
        }
    }

    /**
     * Get reviews by a specific patient
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPatientReviews(Request $request)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'error' => 'Authentication required'
                ], 401);
            }

            $patient = Patient::where('user_id', $user->id)->first();
            
            if (!$patient) {
                return response()->json([
                    'success' => false,
                    'error' => 'Patient record not found'
                ], 404);
            }

            $reviews = Review::byPatient($patient->id)
                ->with(['consultation', 'doctor.user'])
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'reviews' => $reviews
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to fetch patient reviews'
            ], 500);
        }
    }
}
