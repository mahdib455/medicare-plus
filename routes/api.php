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

// Appointment Routes (No Authentication)
Route::get('/appointments', [AppointmentController::class, 'index']);
Route::get('/appointments/doctor/{doctorId}', [AppointmentController::class, 'getAppointmentsByDoctor']);
Route::post('/appointments', [AppointmentController::class, 'store']);
Route::patch('/appointments/{appointment}/status', [AppointmentController::class, 'updateStatus']);
Route::get('/doctors', [AppointmentController::class, 'getDoctors']);

// Consultation Routes (No Authentication)
Route::get('/consultations', [ConsultationController::class, 'index']);
Route::post('/consultations', [ConsultationController::class, 'store']);
Route::put('/consultations/{consultation}', [ConsultationController::class, 'update']);
Route::delete('/consultations/{consultation}', [ConsultationController::class, 'destroy']);
Route::get('/consultations/doctor/{doctorId}', [ConsultationController::class, 'getConsultationsByDoctor']);
Route::get('/consultations/patient/{patientId}', [ConsultationController::class, 'getConsultationsByPatient']);

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
