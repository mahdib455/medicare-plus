<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AppointmentController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        // Pas d'authentification
    }

    /**
     * Get all appointments.
     */
    public function index(Request $request)
    {
        // Retourner tous les appointments sans vérification d'authentification
        $appointments = Appointment::with(['doctor.user', 'patient.user'])
            ->orderBy('appointment_date', 'desc')
            ->get();

        return response()->json([
            'appointments' => $appointments->map(function ($appointment) {
                return [
                    'id' => $appointment->id,
                    'appointment_date' => $appointment->appointment_date->format('Y-m-d H:i'),
                    'status' => $appointment->status,
                    'status_label' => $appointment->status_label,
                    'status_badge_color' => $appointment->status_badge_color,
                    'reason' => $appointment->reason,
                    'doctor' => $appointment->doctor ? [
                        'id' => $appointment->doctor->id,
                        'name' => $appointment->doctor->user->full_name,
                        'speciality' => $appointment->doctor->speciality,
                        'hospital' => $appointment->doctor->hospital,
                    ] : null,
                    'patient' => $appointment->patient ? [
                        'id' => $appointment->patient->id,
                        'name' => $appointment->patient->user->full_name,
                        'phone' => $appointment->patient->user->phone,
                    ] : null,
                ];
            })
        ]);
    }

    /**
     * Store a new appointment.
     */
    public function store(Request $request)
    {
        // Vérification d'authentification pour les patients
        $currentUser = auth()->user();

        if (!$currentUser) {
            return response()->json([
                'error' => 'Authentication required. Please login first.'
            ], 401);
        }

        // Vérifier que l'utilisateur est un patient
        if (!$currentUser->isPatient()) {
            return response()->json([
                'error' => 'Only patients can book appointments.'
            ], 403);
        }

        $patient = $currentUser->patient;

        if (!$patient) {
            return response()->json([
                'error' => 'Patient profile not found.'
            ], 404);
        }

        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'appointment_date' => 'required|date|after:now',
            'reason' => 'nullable|string|max:1000',
        ]);

        // Utiliser l'ID du patient connecté au lieu de celui envoyé dans la requête
        $appointment = Appointment::create([
            'patient_id' => $patient->id, // ID du patient connecté
            'doctor_id' => $request->doctor_id,
            'appointment_date' => $request->appointment_date,
            'status' => Appointment::STATUS_PENDING,
            'reason' => $request->reason,
        ]);

        $appointment->load(['doctor.user', 'patient.user']);

        return response()->json([
            'message' => 'Demande de rendez-vous créée avec succès',
            'appointment' => [
                'id' => $appointment->id,
                'appointment_date' => $appointment->appointment_date->format('Y-m-d H:i'),
                'appointment_date_formatted' => $appointment->appointment_date->format('d/m/Y à H:i'),
                'status' => $appointment->status,
                'status_label' => $appointment->status_label,
                'reason' => $appointment->reason,
                'patient' => [
                    'id' => $appointment->patient->id,
                    'name' => $appointment->patient->user->full_name,
                    'email' => $appointment->patient->user->email,
                ],
                'doctor' => [
                    'id' => $appointment->doctor->id,
                    'name' => $appointment->doctor->user->full_name,
                    'speciality' => $appointment->doctor->speciality,
                    'hospital' => $appointment->doctor->hospital,
                ]
            ]
        ], 201);
    }

    /**
     * Get appointments for a specific patient.
     */
    public function getAppointmentsByPatient($patientId)
    {
        $appointments = Appointment::where('patient_id', $patientId)
            ->with(['patient.user', 'doctor.user', 'consultation', 'prescriptions'])
            ->orderBy('appointment_date', 'desc')
            ->get();

        return response()->json([
            'appointments' => $appointments->map(function ($appointment) {
                return [
                    'id' => $appointment->id,
                    'appointment_date' => $appointment->appointment_date->format('Y-m-d H:i'),
                    'appointment_date_formatted' => $appointment->appointment_date->format('d/m/Y à H:i'),
                    'status' => $appointment->status,
                    'status_label' => $appointment->status_label,
                    'status_badge_color' => $appointment->status_badge_color,
                    'reason' => $appointment->reason,
                    'has_consultation' => $appointment->consultation ? true : false,
                    'prescriptions_count' => $appointment->prescriptions->count(),
                    'doctor' => $appointment->doctor ? [
                        'id' => $appointment->doctor->id,
                        'name' => $appointment->doctor->user->full_name,
                        'speciality' => $appointment->doctor->speciality,
                        'hospital' => $appointment->doctor->hospital,
                        'email' => $appointment->doctor->user->email,
                        'phone' => $appointment->doctor->user->phone,
                    ] : null,
                    'patient' => $appointment->patient ? [
                        'id' => $appointment->patient->id,
                        'name' => $appointment->patient->user->full_name,
                        'email' => $appointment->patient->user->email,
                        'phone' => $appointment->patient->user->phone,
                        'address' => $appointment->patient->user->address,
                    ] : null,
                ];
            })
        ]);
    }

    /**
     * Get appointments for a specific doctor.
     */
    public function getAppointmentsByDoctor($doctorId)
    {
        $appointments = Appointment::where('doctor_id', $doctorId)
            ->with(['patient.user', 'doctor.user'])
            ->orderBy('appointment_date', 'desc')
            ->get();

        return response()->json([
            'appointments' => $appointments->map(function ($appointment) {
                return [
                    'id' => $appointment->id,
                    'appointment_date' => $appointment->appointment_date->format('Y-m-d H:i'),
                    'appointment_date_formatted' => $appointment->appointment_date->format('d/m/Y à H:i'),
                    'status' => $appointment->status,
                    'status_label' => $appointment->status_label,
                    'status_badge_color' => $appointment->status_badge_color,
                    'reason' => $appointment->reason,
                    'patient' => $appointment->patient ? [
                        'id' => $appointment->patient->id,
                        'name' => $appointment->patient->user->full_name,
                        'email' => $appointment->patient->user->email,
                        'phone' => $appointment->patient->user->phone,
                        'address' => $appointment->patient->user->address,
                    ] : null,
                    'doctor' => $appointment->doctor ? [
                        'id' => $appointment->doctor->id,
                        'name' => $appointment->doctor->user->full_name,
                        'speciality' => $appointment->doctor->speciality,
                        'hospital' => $appointment->doctor->hospital,
                    ] : null,
                ];
            })
        ]);
    }

    /**
     * Get all doctors with search and filter capabilities.
     */
    public function getDoctors(Request $request)
    {
        $query = Doctor::with('user');

        // Search by name
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        // Filter by speciality
        if ($request->has('speciality') && !empty($request->speciality)) {
            $query->where('speciality', 'like', "%{$request->speciality}%");
        }

        // Filter by hospital
        if ($request->has('hospital') && !empty($request->hospital)) {
            $query->where('hospital', 'like', "%{$request->hospital}%");
        }

        $doctors = $query->get();

        return response()->json([
            'doctors' => $doctors->map(function ($doctor) {
                return [
                    'id' => $doctor->id,
                    'name' => $doctor->user->full_name,
                    'speciality' => $doctor->speciality,
                    'hospital' => $doctor->hospital,
                    'biography' => $doctor->biography,
                    'email' => $doctor->user->email,
                    'phone' => $doctor->user->phone,
                ];
            })
        ]);
    }

    /**
     * Update appointment status.
     */
    public function updateStatus(Request $request, Appointment $appointment)
    {
        // Pas de vérification d'authentification
        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled,completed',
        ]);

        $appointment->update([
            'status' => $request->status
        ]);

        return response()->json([
            'message' => 'Statut du rendez-vous mis à jour avec succès',
            'appointment' => [
                'id' => $appointment->id,
                'status' => $appointment->status,
                'status_label' => $appointment->status_label,
            ]
        ]);
    }
}
