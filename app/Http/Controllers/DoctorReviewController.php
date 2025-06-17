<?php

namespace App\Http\Controllers;

use App\Models\DoctorReview;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class DoctorReviewController extends Controller
{
    /**
     * Store a new doctor review
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
            $validatedData = $request->validate(DoctorReview::validationRules());

            // Verify doctor exists
            $doctor = Doctor::find($request->doctor_id);
            if (!$doctor) {
                return response()->json([
                    'success' => false,
                    'error' => 'Doctor not found'
                ], 404);
            }

            // Check if patient has had an appointment with this doctor
            $hasAppointment = Appointment::where('patient_id', $patient->id)
                ->where('doctor_id', $doctor->id)
                ->exists();

            if (!$hasAppointment) {
                return response()->json([
                    'success' => false,
                    'error' => 'You can only review doctors you have had appointments with'
                ], 403);
            }

            // Check for duplicate review
            $existingReview = DoctorReview::where('doctor_id', $request->doctor_id)
                ->where('patient_id', $patient->id)
                ->first();

            if ($existingReview) {
                return response()->json([
                    'success' => false,
                    'error' => 'You have already reviewed this doctor'
                ], 409);
            }

            // Create the review
            $review = DoctorReview::create([
                'doctor_id' => $request->doctor_id,
                'patient_id' => $patient->id,
                'rating' => $request->rating,
                'comment' => $request->comment,
                'is_anonymous' => $request->is_anonymous ?? false,
                'status' => 'active'
            ]);

            // Load relationships for response
            $review->load(['doctor.user', 'patient.user']);

            return response()->json([
                'success' => true,
                'message' => 'Doctor review submitted successfully',
                'data' => [
                    'review' => [
                        'id' => $review->id,
                        'doctor_id' => $review->doctor_id,
                        'patient_id' => $review->patient_id,
                        'rating' => $review->rating,
                        'comment' => $review->comment,
                        'is_anonymous' => $review->is_anonymous,
                        'status' => $review->status,
                        'stars' => $review->stars,
                        'rating_text' => $review->rating_text,
                        'created_at' => $review->created_at->format('Y-m-d H:i:s'),
                        'doctor' => [
                            'id' => $review->doctor->id,
                            'name' => $review->doctor->user->full_name,
                            'speciality' => $review->doctor->speciality ?? 'General Practitioner'
                        ]
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
            \Log::error('Doctor review creation failed: ' . $e->getMessage(), [
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
     * Get doctors that the authenticated patient can review
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDoctorsToReview(Request $request)
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

            // Get doctors that the patient has had appointments with but hasn't reviewed
            $doctors = Doctor::whereHas('appointments', function($query) use ($patient) {
                    $query->where('patient_id', $patient->id);
                })
                ->whereDoesntHave('doctorReviews', function($query) use ($patient) {
                    $query->where('patient_id', $patient->id);
                })
                ->with(['user', 'appointments' => function($query) use ($patient) {
                    $query->where('patient_id', $patient->id)
                          ->orderBy('appointment_date', 'desc')
                          ->limit(1);
                }])
                ->get()
                ->map(function($doctor) {
                    $lastAppointment = $doctor->appointments->first();
                    return [
                        'id' => $doctor->id,
                        'name' => $doctor->user->full_name,
                        'speciality' => $doctor->speciality ?? 'General Practitioner',
                        'last_appointment' => $lastAppointment ? [
                            'id' => $lastAppointment->id,
                            'date' => $lastAppointment->appointment_date->format('M d, Y'),
                            'reason' => $lastAppointment->reason,
                            'status' => $lastAppointment->status
                        ] : null
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => [
                    'doctors' => $doctors,
                    'count' => $doctors->count()
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Failed to fetch doctors to review: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'error' => 'Failed to fetch doctors'
            ], 500);
        }
    }

    /**
     * Get reviews by the authenticated patient
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

            $reviews = DoctorReview::byPatient($patient->id)
                ->with(['doctor.user'])
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function($review) {
                    return [
                        'id' => $review->id,
                        'rating' => $review->rating,
                        'comment' => $review->comment,
                        'is_anonymous' => $review->is_anonymous,
                        'status' => $review->status,
                        'stars' => $review->stars,
                        'rating_text' => $review->rating_text,
                        'created_at' => $review->created_at->format('M d, Y at H:i'),
                        'doctor' => [
                            'id' => $review->doctor->id,
                            'name' => $review->doctor->user->full_name,
                            'speciality' => $review->doctor->speciality ?? 'General Practitioner'
                        ]
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => [
                    'reviews' => $reviews,
                    'count' => $reviews->count()
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
