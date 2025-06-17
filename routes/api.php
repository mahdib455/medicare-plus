<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\PrescriptionController;
use App\Http\Controllers\PrescriptionLineController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Review System API Routes - Only for authenticated patients
Route::middleware('auth:sanctum')->group(function () {
    // Store a new review
    Route::post('/reviews', [App\Http\Controllers\ReviewController::class, 'store']);

    // Get consultations without reviews for the authenticated patient
    Route::get('/consultations/unreviewed', [App\Http\Controllers\ReviewController::class, 'getUnreviewedConsultations']);

    // Get reviews by authenticated patient
    Route::get('/my-reviews', [App\Http\Controllers\ReviewController::class, 'getPatientReviews']);
});

// Doctor Review System API Routes - Simpler logic
Route::middleware('auth:sanctum')->group(function () {
    // Store a new doctor review
    Route::post('/doctor-reviews', [App\Http\Controllers\DoctorReviewController::class, 'store']);

    // Get doctors that can be reviewed by the authenticated patient
    Route::get('/doctors/reviewable', [App\Http\Controllers\DoctorReviewController::class, 'getDoctorsToReview']);

    // Get doctor reviews by authenticated patient
    Route::get('/my-doctor-reviews', [App\Http\Controllers\DoctorReviewController::class, 'getPatientReviews']);
});

// Review System API Routes
Route::middleware('auth:sanctum')->group(function () {
    // Store a new review
    Route::post('/reviews', [App\Http\Controllers\NewReviewController::class, 'store']);

    // Get reviews for a specific doctor
    Route::get('/doctors/{doctorId}/reviews', [App\Http\Controllers\NewReviewController::class, 'getDoctorReviews']);

    // Get reviews by authenticated patient
    Route::get('/my-reviews', [App\Http\Controllers\NewReviewController::class, 'getPatientReviews']);

    // Get consultations for review (from old ReviewController)
    Route::get('/all-consultations', [App\Http\Controllers\ReviewController::class, 'getAllConsultations']);
    Route::get('/my-reviews-old', [App\Http\Controllers\ReviewController::class, 'getMyReviews']);
    Route::post('/consultation-reviews', [App\Http\Controllers\ReviewController::class, 'storeConsultationReview']);
});

// Test consultation API without auth (temporary)
Route::get('/test-consultations/{userId}', function ($userId) {
    try {
        // Simulate logged-in user
        $user = \App\Models\User::find($userId);
        if (!$user || $user->role !== 'patient') {
            return response()->json(['error' => 'User not found or not a patient'], 404);
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
                $review = \App\Models\Review::where('consultation_id', $consultation->id)
                    ->where('patient_id', $patient->id)
                    ->first();

                return [
                    'id' => $consultation->id,
                    'consultation_date' => $consultation->consultation_date->format('m/d/Y'),
                    'consultation_time' => $consultation->consultation_date->format('H:i'),
                    'formatted_date' => $consultation->consultation_date->format('m/d/Y at H:i'),
                    'diagnosis' => $consultation->diagnosis,
                    'treatment' => $consultation->treatment ?? 'No treatment specified',
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
                        'comment' => $review->comment,
                        'created_at' => $review->created_at->format('m/d/Y at H:i')
                    ] : null
                ];
            });

        return response()->json([
            'success' => true,
            'consultations' => $consultations
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'line' => $e->getLine(),
            'file' => basename($e->getFile())
        ], 500);
    }
});

// Appointment Routes (Mixed Authentication)
Route::get('/appointments', [AppointmentController::class, 'index']);
Route::get('/appointments/doctor/{doctorId}', [AppointmentController::class, 'getAppointmentsByDoctor']);
Route::get('/appointments/patient/{patientId}', [AppointmentController::class, 'getAppointmentsByPatient']);
Route::patch('/appointments/{appointment}/status', [AppointmentController::class, 'updateStatus']);
Route::get('/doctors', [AppointmentController::class, 'getDoctors']);

// Note: Appointment creation moved to web routes for better session handling

// Consultation Routes (No Authentication)
Route::get('/consultations', [ConsultationController::class, 'index']);
Route::post('/consultations', [ConsultationController::class, 'store']);
Route::put('/consultations/{consultation}', [ConsultationController::class, 'update']);
Route::delete('/consultations/{consultation}', [ConsultationController::class, 'destroy']);
Route::get('/consultations/doctor/{doctorId}', [ConsultationController::class, 'getConsultationsByDoctor']);
Route::get('/consultations/patient/{patientId}', [ConsultationController::class, 'getConsultationsByPatient']);
Route::get('/consultations/appointment/{appointmentId}', function ($appointmentId) {
    try {
        $consultations = \App\Models\Consultation::where('appointment_id', $appointmentId)
            ->with(['appointment.patient.user', 'appointment.doctor.user'])
            ->get();

        return response()->json([
            'success' => true,
            'consultations' => $consultations
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ], 500);
    }
});

// Prescription Routes (No Authentication)
Route::get('/prescriptions', [PrescriptionController::class, 'index']);
Route::post('/prescriptions', [PrescriptionController::class, 'store']);
Route::put('/prescriptions/{prescription}', [PrescriptionController::class, 'update']);
Route::delete('/prescriptions/{prescription}', [PrescriptionController::class, 'destroy']);
Route::get('/prescriptions/doctor/{doctorId}', [PrescriptionController::class, 'getPrescriptionsByDoctor']);
Route::get('/prescriptions/patient/{patientId}', [PrescriptionController::class, 'getPrescriptionsByPatient']);
Route::get('/prescriptions/patient/{patientId}/active', [PrescriptionController::class, 'getActivePrescriptionsByPatient']);

// Prescription Line Routes (No Authentication)
Route::post('/prescription-lines', [PrescriptionLineController::class, 'store']);
Route::get('/prescription-lines/prescription/{prescriptionId}', [PrescriptionLineController::class, 'getByPrescription']);
Route::put('/prescription-lines/{prescriptionLine}', [PrescriptionLineController::class, 'update']);
Route::delete('/prescription-lines/{prescriptionLine}', [PrescriptionLineController::class, 'destroy']);
Route::get('/prescription-lines/patient/{patientId}/active', [PrescriptionLineController::class, 'getActiveByPatient']);

// Medication Routes (No Authentication)
Route::get('/medications', function() {
    return response()->json([
        'medications' => \App\Models\Medication::active()->get()
    ]);
});
Route::get('/medications/search', function(\Illuminate\Http\Request $request) {
    $search = $request->get('search', '');
    return response()->json([
        'medications' => \App\Models\Medication::active()->search($search)->get()
    ]);
});

// Debug routes
Route::get('/debug/appointments', function() {
    return response()->json([
        'appointments' => \App\Models\Appointment::with(['patient.user', 'doctor.user', 'consultation', 'prescriptions'])->get(),
        'doctors' => \App\Models\Doctor::with('user')->get(),
        'patients' => \App\Models\Patient::with('user')->get(),
        'consultations' => \App\Models\Consultation::with(['appointment.patient.user', 'appointment.doctor.user'])->get(),
        'prescriptions' => \App\Models\Prescription::with(['appointment', 'doctor.user', 'patient.user'])->get()
    ]);
});

// Test prescription creation
Route::post('/debug/test-prescription', function(\Illuminate\Http\Request $request) {
    try {
        \Log::info('Test prescription data:', $request->all());

        $prescription = \App\Models\Prescription::create([
            'doctor_id' => 1,
            'patient_id' => 1,
            'prescribed_at' => now(),
            'notes' => 'Test prescription'
        ]);

        return response()->json([
            'success' => true,
            'prescription' => $prescription
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
});
