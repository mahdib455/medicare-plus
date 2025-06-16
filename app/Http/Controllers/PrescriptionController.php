<?php

namespace App\Http\Controllers;

use App\Models\Prescription;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Http\Request;

class PrescriptionController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        // Pas d'authentification
    }

    /**
     * Get all prescriptions.
     */
    public function index(Request $request)
    {
        $prescriptions = Prescription::with(['appointment', 'doctor.user', 'patient.user', 'lines.medication'])
            ->orderBy('prescribed_at', 'desc')
            ->get();

        return response()->json([
            'prescriptions' => $prescriptions->map(function ($prescription) {
                return [
                    'id' => $prescription->id,
                    'appointment_id' => $prescription->appointment_id,
                    'medication' => $prescription->medication,
                    'dosage' => $prescription->dosage,
                    'instructions' => $prescription->instructions,
                    'short_instructions' => $prescription->short_instructions,
                    'notes' => $prescription->notes,
                    'short_notes' => $prescription->short_notes,
                    'start_date' => $prescription->formatted_start_date,
                    'end_date' => $prescription->formatted_end_date,
                    'prescribed_at' => $prescription->formatted_prescribed_date,
                    'duration' => $prescription->duration,
                    'remaining_days' => $prescription->remaining_days,
                    'status' => $prescription->status,
                    'status_label' => $prescription->status_label,
                    'status_badge_color' => $prescription->status_badge_color,
                    'summary' => $prescription->summary,
                    'doctor' => $prescription->doctor ? [
                        'id' => $prescription->doctor->id,
                        'name' => $prescription->doctor->user->full_name,
                        'speciality' => $prescription->doctor->speciality,
                        'hospital' => $prescription->doctor->hospital,
                    ] : null,
                    'patient' => $prescription->patient ? [
                        'id' => $prescription->patient->id,
                        'name' => $prescription->patient->user->full_name,
                        'email' => $prescription->patient->user->email,
                        'phone' => $prescription->patient->user->phone,
                    ] : null,
                    'appointment' => $prescription->appointment ? [
                        'id' => $prescription->appointment->id,
                        'appointment_date' => $prescription->appointment->appointment_date->format('d/m/Y à H:i'),
                        'reason' => $prescription->appointment->reason,
                        'status' => $prescription->appointment->status,
                    ] : null,
                ];
            })
        ]);
    }

    /**
     * Store a new prescription.
     */
    public function store(Request $request)
    {
        // Debug: Log des données reçues
        \Log::info('Prescription data received:', $request->all());

        $request->validate([
            'appointment_id' => 'nullable|exists:appointments,id',
            'doctor_id' => 'required|exists:doctors,id',
            'patient_id' => 'required|exists:patients,id',
            'prescribed_at' => 'nullable|date',
            'notes' => 'nullable|string|max:2000',
        ]);

        $prescription = Prescription::create([
            'appointment_id' => $request->appointment_id,
            'doctor_id' => $request->doctor_id,
            'patient_id' => $request->patient_id,
            'prescribed_at' => $request->prescribed_at ?? now(),
            'notes' => $request->notes,
        ]);

        $prescription->load(['appointment', 'doctor.user', 'patient.user']);

        return response()->json([
            'message' => 'Prescription créée avec succès',
            'prescription' => [
                'id' => $prescription->id,
                'prescribed_at' => $prescription->formatted_prescribed_date,
                'notes' => $prescription->notes,
                'doctor_name' => $prescription->doctor->user->full_name,
                'patient_name' => $prescription->patient->user->full_name,
            ]
        ], 201);
    }

    /**
     * Update an existing prescription.
     */
    public function update(Request $request, Prescription $prescription)
    {
        $request->validate([
            'medication' => 'sometimes|string|max:255',
            'instructions' => 'sometimes|string|max:2000',
            'dosage' => 'sometimes|string|max:255',
            'start_date' => 'sometimes|date',
            'end_date' => 'sometimes|date|after_or_equal:start_date',
            'notes' => 'sometimes|string|max:2000',
        ]);

        $prescription->update($request->only([
            'medication', 'instructions', 'dosage', 'start_date', 'end_date', 'notes'
        ]));

        return response()->json([
            'message' => 'Prescription mise à jour avec succès',
            'prescription' => [
                'id' => $prescription->id,
                'medication' => $prescription->medication,
                'dosage' => $prescription->dosage,
                'status' => $prescription->status_label,
                'summary' => $prescription->summary,
            ]
        ]);
    }

    /**
     * Get prescriptions for a specific doctor.
     */
    public function getPrescriptionsByDoctor($doctorId)
    {
        $prescriptions = Prescription::forDoctor($doctorId)
            ->with(['appointment', 'doctor.user', 'patient.user'])
            ->orderBy('prescribed_at', 'desc')
            ->get();

        return response()->json([
            'prescriptions' => $prescriptions->map(function ($prescription) {
                return [
                    'id' => $prescription->id,
                    'medication' => $prescription->medication,
                    'dosage' => $prescription->dosage,
                    'patient_name' => $prescription->patient->user->full_name,
                    'prescribed_at' => $prescription->formatted_prescribed_date,
                    'status' => $prescription->status_label,
                    'status_badge_color' => $prescription->status_badge_color,
                    'summary' => $prescription->summary,
                ];
            })
        ]);
    }

    /**
     * Get prescriptions for a specific patient.
     */
    public function getPrescriptionsByPatient($patientId)
    {
        $prescriptions = Prescription::forPatient($patientId)
            ->with(['appointment', 'doctor.user', 'patient.user'])
            ->orderBy('prescribed_at', 'desc')
            ->get();

        return response()->json([
            'prescriptions' => $prescriptions->map(function ($prescription) {
                return [
                    'id' => $prescription->id,
                    'medication' => $prescription->medication,
                    'dosage' => $prescription->dosage,
                    'instructions' => $prescription->instructions,
                    'notes' => $prescription->notes,
                    'start_date' => $prescription->formatted_start_date,
                    'end_date' => $prescription->formatted_end_date,
                    'prescribed_at' => $prescription->formatted_prescribed_date,
                    'duration' => $prescription->duration,
                    'remaining_days' => $prescription->remaining_days,
                    'status' => $prescription->status_label,
                    'status_badge_color' => $prescription->status_badge_color,
                    'doctor_name' => $prescription->doctor->user->full_name,
                    'doctor_speciality' => $prescription->doctor->speciality,
                    'summary' => $prescription->summary,
                ];
            })
        ]);
    }

    /**
     * Get active prescriptions for a specific patient.
     */
    public function getActivePrescriptionsByPatient($patientId)
    {
        $prescriptions = Prescription::forPatient($patientId)
            ->active()
            ->with(['doctor.user'])
            ->orderBy('end_date', 'asc')
            ->get();

        return response()->json([
            'active_prescriptions' => $prescriptions->map(function ($prescription) {
                return [
                    'id' => $prescription->id,
                    'medication' => $prescription->medication,
                    'dosage' => $prescription->dosage,
                    'instructions' => $prescription->instructions,
                    'remaining_days' => $prescription->remaining_days,
                    'end_date' => $prescription->formatted_end_date,
                    'doctor_name' => $prescription->doctor->user->full_name,
                    'summary' => $prescription->summary,
                ];
            })
        ]);
    }

    /**
     * Delete a prescription.
     */
    public function destroy(Prescription $prescription)
    {
        $prescription->delete();

        return response()->json([
            'message' => 'Prescription supprimée avec succès'
        ]);
    }
}
