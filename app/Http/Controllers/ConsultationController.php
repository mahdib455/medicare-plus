<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use App\Models\Appointment;
use Illuminate\Http\Request;

class ConsultationController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        // Pas d'authentification
    }

    /**
     * Get all consultations.
     */
    public function index(Request $request)
    {
        $consultations = Consultation::with(['appointment.patient.user', 'appointment.doctor.user'])
            ->orderBy('consultation_date', 'desc')
            ->get();

        return response()->json([
            'consultations' => $consultations->map(function ($consultation) {
                return [
                    'id' => $consultation->id,
                    'appointment_id' => $consultation->appointment_id,
                    'consultation_date' => $consultation->consultation_date->format('Y-m-d H:i'),
                    'consultation_date_formatted' => $consultation->formatted_date,
                    'diagnosis' => $consultation->diagnosis,
                    'short_diagnosis' => $consultation->short_diagnosis,
                    'notes' => $consultation->notes,
                    'short_notes' => $consultation->short_notes,
                    'duration' => $consultation->duration,
                    'appointment' => $consultation->appointment ? [
                        'id' => $consultation->appointment->id,
                        'appointment_date' => $consultation->appointment->appointment_date->format('Y-m-d H:i'),
                        'status' => $consultation->appointment->status,
                        'reason' => $consultation->appointment->reason,
                        'patient' => $consultation->appointment->patient ? [
                            'id' => $consultation->appointment->patient->id,
                            'name' => $consultation->appointment->patient->user->full_name,
                            'email' => $consultation->appointment->patient->user->email,
                        ] : null,
                        'doctor' => $consultation->appointment->doctor ? [
                            'id' => $consultation->appointment->doctor->id,
                            'name' => $consultation->appointment->doctor->user->full_name,
                            'speciality' => $consultation->appointment->doctor->speciality,
                        ] : null,
                    ] : null,
                ];
            })
        ]);
    }

    /**
     * Store a new consultation.
     */
    public function store(Request $request)
    {
        $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'consultation_date' => 'required|date',
            'diagnosis' => 'required|string|max:2000',
            'notes' => 'nullable|string|max:2000',
        ]);

        // Vérifier que l'appointment existe et n'a pas déjà de consultation
        $appointment = Appointment::find($request->appointment_id);
        if (!$appointment) {
            return response()->json(['error' => 'Rendez-vous non trouvé'], 404);
        }

        if ($appointment->hasConsultation()) {
            return response()->json(['error' => 'Ce rendez-vous a déjà une consultation'], 422);
        }

        $consultation = Consultation::create([
            'appointment_id' => $request->appointment_id,
            'consultation_date' => $request->consultation_date,
            'diagnosis' => $request->diagnosis,
            'notes' => $request->notes,
        ]);

        // Marquer le rendez-vous comme terminé
        $appointment->update(['status' => Appointment::STATUS_COMPLETED]);

        $consultation->load(['appointment.patient.user', 'appointment.doctor.user']);

        return response()->json([
            'message' => 'Consultation créée avec succès',
            'consultation' => [
                'id' => $consultation->id,
                'appointment_id' => $consultation->appointment_id,
                'consultation_date' => $consultation->consultation_date->format('Y-m-d H:i'),
                'diagnosis' => $consultation->diagnosis,
                'notes' => $consultation->notes,
                'appointment' => [
                    'id' => $consultation->appointment->id,
                    'patient_name' => $consultation->appointment->patient->user->full_name,
                    'doctor_name' => $consultation->appointment->doctor->user->full_name,
                ]
            ]
        ], 201);
    }

    /**
     * Update an existing consultation.
     */
    public function update(Request $request, Consultation $consultation)
    {
        $request->validate([
            'consultation_date' => 'sometimes|date',
            'diagnosis' => 'sometimes|string|max:2000',
            'notes' => 'sometimes|string|max:2000',
        ]);

        $consultation->update($request->only(['consultation_date', 'diagnosis', 'notes']));

        return response()->json([
            'message' => 'Consultation mise à jour avec succès',
            'consultation' => [
                'id' => $consultation->id,
                'consultation_date' => $consultation->consultation_date->format('Y-m-d H:i'),
                'diagnosis' => $consultation->diagnosis,
                'notes' => $consultation->notes,
            ]
        ]);
    }

    /**
     * Get consultations for a specific doctor.
     */
    public function getConsultationsByDoctor($doctorId)
    {
        $consultations = Consultation::forDoctor($doctorId)
            ->with(['appointment.patient.user', 'appointment.doctor.user'])
            ->orderBy('consultation_date', 'desc')
            ->get();

        return response()->json([
            'consultations' => $consultations->map(function ($consultation) {
                return [
                    'id' => $consultation->id,
                    'consultation_date' => $consultation->formatted_date,
                    'diagnosis' => $consultation->short_diagnosis,
                    'notes' => $consultation->short_notes,
                    'patient_name' => $consultation->appointment->patient->user->full_name,
                    'appointment_reason' => $consultation->appointment->reason,
                ];
            })
        ]);
    }

    /**
     * Get consultations for a specific patient.
     */
    public function getConsultationsByPatient($patientId)
    {
        $consultations = Consultation::forPatient($patientId)
            ->with(['appointment.patient.user', 'appointment.doctor.user'])
            ->orderBy('consultation_date', 'desc')
            ->get();

        return response()->json([
            'consultations' => $consultations->map(function ($consultation) {
                return [
                    'id' => $consultation->id,
                    'consultation_date' => $consultation->formatted_date,
                    'diagnosis' => $consultation->diagnosis,
                    'notes' => $consultation->notes,
                    'doctor_name' => $consultation->appointment->doctor->user->full_name,
                    'doctor_speciality' => $consultation->appointment->doctor->speciality,
                    'appointment_reason' => $consultation->appointment->reason,
                ];
            })
        ]);
    }

    /**
     * Delete a consultation.
     */
    public function destroy(Consultation $consultation)
    {
        // Remettre le statut du rendez-vous à "confirmed"
        if ($consultation->appointment) {
            $consultation->appointment->update(['status' => Appointment::STATUS_CONFIRMED]);
        }

        $consultation->delete();

        return response()->json([
            'message' => 'Consultation supprimée avec succès'
        ]);
    }
}
