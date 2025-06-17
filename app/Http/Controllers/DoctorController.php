<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class DoctorController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        // Pas d'authentification
    }

    /**
     * Show the doctor dashboard.
     */
    public function dashboard()
    {
        // Get the currently authenticated user
        $currentUser = auth()->user();

        if (!$currentUser || !$currentUser->isDoctor()) {
            return redirect()->route('login')->with('error', 'Access denied. Doctor login required.');
        }

        // Get the doctor profile for the current user
        $doctor = $currentUser->doctor;

        // If no doctor profile exists, create one
        if (!$doctor) {
            $doctor = \App\Models\Doctor::create([
                'user_id' => $currentUser->id,
                'speciality' => 'General Medicine',
                'biography' => 'Medical professional',
                'hospital' => 'General Hospital'
            ]);
        }

        // Get user information from the current authenticated user
        $user = (object) [
            'full_name' => $currentUser->full_name,
            'email' => $currentUser->email,
            'phone' => $currentUser->phone
        ];

        // Get doctor information
        $doctorInfo = (object) [
            'id' => $doctor->id,
            'speciality' => $doctor->speciality,
            'hospital' => $doctor->hospital,
            'biography' => $doctor->biography
        ];

        // Get appointments for THIS doctor only
        $appointments = \App\Models\Appointment::where('doctor_id', $doctor->id)
            ->with(['patient.user', 'doctor.user'])
            ->orderBy('appointment_date', 'desc')
            ->get();

        // Séparer les rendez-vous par statut
        $pendingAppointments = $appointments->where('status', 'pending');
        $confirmedAppointments = $appointments->where('status', 'confirmed');
        $completedAppointments = $appointments->where('status', 'completed');
        $cancelledAppointments = $appointments->where('status', 'cancelled');

        return view('doctor.dashboard', compact(
            'user',
            'doctor',
            'appointments',
            'pendingAppointments',
            'confirmedAppointments',
            'completedAppointments',
            'cancelledAppointments'
        ));
    }

    /**
     * Show the doctor profile.
     */
    public function profile()
    {
        // Get the currently authenticated user
        $currentUser = auth()->user();

        if (!$currentUser || !$currentUser->isDoctor()) {
            return redirect()->route('login')->with('error', 'Access denied. Doctor login required.');
        }

        // Get the doctor profile for the current user
        $doctor = $currentUser->doctor;

        // If no doctor profile exists, create one
        if (!$doctor) {
            $doctor = \App\Models\Doctor::create([
                'user_id' => $currentUser->id,
                'speciality' => 'General Medicine',
                'biography' => 'Medical professional with expertise in patient care.',
                'hospital' => 'General Hospital'
            ]);
        }

        return view('doctor.profile', compact('doctor'));
    }

    /**
     * Update the doctor profile.
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'speciality' => 'required|string|max:255',
            'hospital' => 'required|string|max:255',
            'biography' => 'nullable|string|max:1000',
        ]);

        // Get the currently authenticated user
        $currentUser = auth()->user();

        if (!$currentUser || !$currentUser->isDoctor()) {
            return redirect()->route('login')->with('error', 'Access denied. Doctor login required.');
        }

        // Get the doctor profile for the current user
        $doctor = $currentUser->doctor;

        if (!$doctor) {
            return redirect()->route('doctor.profile')->with('error', 'Doctor profile not found.');
        }

        // Update user information (only for the current user)
        $currentUser->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        // Update doctor information (only for the current doctor)
        $doctor->update([
            'speciality' => $request->speciality,
            'hospital' => $request->hospital,
            'biography' => $request->biography,
        ]);

        return redirect()->route('doctor.profile')->with('success', 'Profile updated successfully!');
    }

    /**
     * Show the doctor planning.
     */
    public function planning()
    {
        // Get the currently authenticated user
        $currentUser = auth()->user();

        if (!$currentUser || !$currentUser->isDoctor()) {
            return redirect()->route('login')->with('error', 'Access denied. Doctor login required.');
        }

        // Get the doctor profile for the current user
        $doctor = $currentUser->doctor;

        if (!$doctor) {
            return redirect()->route('doctor.dashboard')->with('error', 'Doctor profile not found.');
        }

        // Get appointments for THIS doctor only
        $appointments = \App\Models\Appointment::where('doctor_id', $doctor->id)
            ->with(['patient.user', 'doctor.user', 'consultation', 'prescriptions'])
            ->orderBy('appointment_date', 'asc')
            ->get();

        // Organiser les rendez-vous par date
        $appointmentsByDate = $appointments->groupBy(function($appointment) {
            return $appointment->appointment_date->format('Y-m-d');
        });

        // Statistiques du planning
        $totalAppointments = $appointments->count();
        $todayAppointments = $appointments->filter(function($appointment) {
            return $appointment->appointment_date->isToday();
        })->count();

        $upcomingAppointments = $appointments->filter(function($appointment) {
            return $appointment->appointment_date->isFuture();
        })->count();

        $thisWeekAppointments = $appointments->filter(function($appointment) {
            return $appointment->appointment_date->isCurrentWeek();
        })->count();

        return view('doctor.planning', compact(
            'doctor',
            'appointments',
            'appointmentsByDate',
            'totalAppointments',
            'todayAppointments',
            'upcomingAppointments',
            'thisWeekAppointments'
        ));
    }

    /**
     * Show the doctor statistics.
     */
    public function statistics()
    {
        // Get the currently authenticated user
        $currentUser = auth()->user();

        if (!$currentUser || !$currentUser->isDoctor()) {
            return redirect()->route('login')->with('error', 'Access denied. Doctor login required.');
        }

        // Get the doctor profile for the current user
        $doctor = $currentUser->doctor;

        if (!$doctor) {
            return redirect()->route('doctor.dashboard')->with('error', 'Doctor profile not found.');
        }

        // Get consultations for THIS doctor only
        $consultations = \App\Models\Consultation::whereHas('appointment', function($query) use ($doctor) {
            $query->where('doctor_id', $doctor->id);
        })->with(['appointment.patient.user', 'appointment.doctor.user'])
        ->orderBy('consultation_date', 'desc')
        ->get();

        // Statistiques par mois (12 derniers mois)
        $monthlyStats = [];
        $monthlyLabels = [];
        $monthlyData = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthKey = $date->format('Y-m');
            $monthLabel = $date->format('M Y');

            $consultationsCount = $consultations->filter(function($consultation) use ($date) {
                return $consultation->consultation_date->format('Y-m') === $date->format('Y-m');
            })->count();

            $monthlyStats[$monthKey] = [
                'month' => $monthLabel,
                'count' => $consultationsCount,
                'date' => $date
            ];

            $monthlyLabels[] = $monthLabel;
            $monthlyData[] = $consultationsCount;
        }

        // Statistiques générales
        $totalConsultations = $consultations->count();
        $thisMonthConsultations = $consultations->filter(function($consultation) {
            return $consultation->consultation_date->isCurrentMonth();
        })->count();

        $lastMonthConsultations = $consultations->filter(function($consultation) {
            return $consultation->consultation_date->format('Y-m') === now()->subMonth()->format('Y-m');
        })->count();

        $thisYearConsultations = $consultations->filter(function($consultation) {
            return $consultation->consultation_date->isCurrentYear();
        })->count();

        // Calcul de la tendance
        $trend = 0;
        if ($lastMonthConsultations > 0) {
            $trend = (($thisMonthConsultations - $lastMonthConsultations) / $lastMonthConsultations) * 100;
        } elseif ($thisMonthConsultations > 0) {
            $trend = 100;
        }

        // Statistiques par jour de la semaine
        $weeklyStats = collect();
        $daysOfWeek = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];

        foreach ($daysOfWeek as $index => $day) {
            $dayNumber = $index + 1; // Carbon uses 1-7 for Monday-Sunday
            $count = $consultations->filter(function($consultation) use ($dayNumber) {
                return $consultation->consultation_date->dayOfWeek === $dayNumber;
            })->count();

            $weeklyStats->push([
                'day' => $day,
                'count' => $count
            ]);
        }

        // Top 5 des diagnostics les plus fréquents
        $topDiagnoses = $consultations->groupBy('diagnosis')
            ->map(function($group) {
                return [
                    'diagnosis' => $group->first()->diagnosis ?: 'Non spécifié',
                    'count' => $group->count()
                ];
            })
            ->sortByDesc('count')
            ->take(5)
            ->values();

        return view('doctor.statistics', compact(
            'doctor',
            'consultations',
            'monthlyStats',
            'monthlyLabels',
            'monthlyData',
            'totalConsultations',
            'thisMonthConsultations',
            'lastMonthConsultations',
            'thisYearConsultations',
            'trend',
            'weeklyStats',
            'topDiagnoses'
        ));
    }

    /**
     * Show create prescription form for a consultation.
     */
    public function createPrescription($consultationId)
    {
        // Récupérer la consultation
        $consultation = \App\Models\Consultation::with(['appointment.patient.user', 'appointment.doctor.user'])
            ->findOrFail($consultationId);

        // Récupérer tous les médicaments disponibles
        $medications = \App\Models\Medication::all();

        // Récupérer le docteur
        $allDoctors = \App\Models\Doctor::with('user')->get();
        $doctor = $allDoctors->first();

        return view('doctor.prescriptions.create', compact('consultation', 'medications', 'doctor'));
    }

    /**
     * Store a new prescription for a consultation.
     */
    public function storePrescription(Request $request, $consultationId)
    {
        $currentUser = auth()->user();

        if (!$currentUser || !$currentUser->isDoctor()) {
            return redirect()->route('login')->with('error', 'Access denied. Doctor login required.');
        }

        $doctor = $currentUser->doctor;

        $request->validate([
            'notes' => 'nullable|string|max:1000',
            'medications' => 'required|array|min:1',
            'medications.*.medication_id' => 'required|exists:medications,id',
            'medications.*.quantity' => 'required|integer|min:1',
            'medications.*.dosage' => 'required|string|max:255',
            'medications.*.frequency' => 'nullable|string|max:255',
            'medications.*.duration_days' => 'nullable|integer|min:1',
            'medications.*.instructions' => 'nullable|string|max:500',
            'medications.*.start_date' => 'nullable|date',
            'medications.*.end_date' => 'nullable|date',
            'medications.*.notes' => 'nullable|string|max:500',
        ]);

        try {
            // Find consultation for this doctor only
            $consultation = \App\Models\Consultation::whereHas('appointment', function($query) use ($doctor) {
                $query->where('doctor_id', $doctor->id);
            })->with('appointment')->findOrFail($consultationId);

            // Create prescription
            $prescriptionId = \DB::table('prescriptions')->insertGetId([
                'appointment_id' => $consultation->appointment_id,
                'doctor_id' => $doctor->id,
                'patient_id' => $consultation->appointment->patient_id,
                'prescribed_at' => now(),
                'notes' => $request->notes,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Create prescription lines
            foreach ($request->medications as $medicationData) {
                \DB::table('prescription_lines')->insert([
                    'prescription_id' => $prescriptionId,
                    'medication_id' => $medicationData['medication_id'],
                    'quantity' => $medicationData['quantity'],
                    'dosage' => $medicationData['dosage'],
                    'frequency' => $medicationData['frequency'] ?? 'As needed',
                    'duration_days' => $medicationData['duration_days'] ?? 7,
                    'instructions' => $medicationData['instructions'] ?? null,
                    'start_date' => $medicationData['start_date'] ?? now()->toDateString(),
                    'end_date' => $medicationData['end_date'] ?? now()->addDays(7)->toDateString(),
                    'notes' => $medicationData['notes'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Mark appointment as completed now that prescription is created
            $consultation->appointment->update(['status' => \App\Models\Appointment::STATUS_COMPLETED]);

            return redirect()->route('doctor.planning')->with('success', 'Prescription created successfully! Workflow completed. The appointment is now marked as "Terminé".');

        } catch (\Exception $e) {
            \Log::error('Error creating prescription: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error creating prescription: ' . $e->getMessage());
        }
    }

    /**
     * Store a new prescription (old method - keep for compatibility).
     */
    public function storePrescriptionOld(Request $request)
    {
        $request->validate([
            'consultation_id' => 'required|exists:consultations,id',
            'notes' => 'nullable|string|max:1000',
            'medications' => 'required|array|min:1',
            'medications.*.medication_id' => 'required|exists:medications,id',
            'medications.*.quantity' => 'required|integer|min:1',
            'medications.*.dosage' => 'required|string|max:255',
            'medications.*.frequency' => 'nullable|string|max:255',
            'medications.*.duration_days' => 'nullable|integer|min:1',
            'medications.*.instructions' => 'nullable|string|max:500',
            'medications.*.start_date' => 'nullable|date',
            'medications.*.end_date' => 'nullable|date',
            'medications.*.notes' => 'nullable|string|max:500',
        ]);

        try {
            // Récupérer la consultation
            $consultation = \App\Models\Consultation::with('appointment')->findOrFail($request->consultation_id);

            // Récupérer le docteur
            $allDoctors = \App\Models\Doctor::with('user')->get();
            $doctor = $allDoctors->first();

            // Créer la prescription avec la vraie structure de la table
            $prescriptionId = \DB::table('prescriptions')->insertGetId([
                'appointment_id' => $consultation->appointment_id,
                'doctor_id' => $doctor->id,
                'patient_id' => $consultation->appointment->patient_id,
                'prescribed_at' => now(),
                'notes' => $request->notes,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Créer les lignes de prescription avec des requêtes SQL directes
            foreach ($request->medications as $medicationData) {
                \DB::table('prescription_lines')->insert([
                    'prescription_id' => $prescriptionId,
                    'medication_id' => $medicationData['medication_id'],
                    'quantity' => $medicationData['quantity'],
                    'dosage' => $medicationData['dosage'],
                    'frequency' => $medicationData['frequency'] ?? 'As needed', // Valeur par défaut
                    'duration_days' => $medicationData['duration_days'] ?? 7, // Valeur par défaut
                    'instructions' => $medicationData['instructions'] ?? null,
                    'start_date' => $medicationData['start_date'] ?? now()->toDateString(),
                    'end_date' => $medicationData['end_date'] ?? now()->addDays(7)->toDateString(),
                    'notes' => $medicationData['notes'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Mark appointment as completed now that prescription is created
            $consultation->appointment->update(['status' => \App\Models\Appointment::STATUS_COMPLETED]);

            return redirect()->route('doctor.planning')->with('success', 'Prescription created successfully! Workflow completed. The appointment is now marked as "Terminé".');

        } catch (\Exception $e) {
            \Log::error('Error creating prescription: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error creating prescription: ' . $e->getMessage());
        }
    }

    /**
     * Show patients list.
     */
    public function patients()
    {
        // TODO: Implement patients list logic
        return view('doctor.patients');
    }

    /**
     * Show appointments.
     */
    public function appointments()
    {
        // TODO: Implement appointments logic
        return view('doctor.appointments');
    }

    // ==================== NEW APPOINTMENT WORKFLOW ====================

    /**
     * Confirm an appointment.
     */
    public function confirmAppointment(Request $request, $appointmentId)
    {
        try {
            \Log::info('confirmAppointment called', ['appointmentId' => $appointmentId]);

            $currentUser = auth()->user();

            if (!$currentUser) {
                \Log::error('User not authenticated');
                return redirect()->route('login')->with('error', 'Please login first.');
            }

            if (!$currentUser->isDoctor()) {
                \Log::error('User is not a doctor', ['role' => $currentUser->role]);
                return redirect()->route('login')->with('error', 'Access denied. Doctor login required.');
            }

            $doctor = $currentUser->doctor;

            if (!$doctor) {
                \Log::error('Doctor profile not found', ['user_id' => $currentUser->id]);
                return redirect()->route('doctor.dashboard')->with('error', 'Doctor profile not found.');
            }

            \Log::info('Doctor found', ['doctor_id' => $doctor->id]);

            // Find appointment for this doctor only
            $appointment = \App\Models\Appointment::where('id', $appointmentId)
                ->where('doctor_id', $doctor->id)
                ->first();

            if (!$appointment) {
                \Log::error('Appointment not found', ['appointmentId' => $appointmentId, 'doctor_id' => $doctor->id]);
                return redirect()->route('doctor.planning')->with('error', 'Appointment not found or not authorized.');
            }

            \Log::info('Appointment found', ['appointment_id' => $appointment->id, 'status' => $appointment->status]);

            if (!$appointment->isPending()) {
                \Log::error('Appointment is not pending', ['status' => $appointment->status]);
                return redirect()->route('doctor.planning')->with('error', 'Appointment is not pending confirmation.');
            }

            // Confirm the appointment
            $appointment->update(['status' => \App\Models\Appointment::STATUS_CONFIRMED]);

            \Log::info('Appointment confirmed', ['appointment_id' => $appointment->id, 'new_status' => $appointment->status]);

            // Automatically redirect to create consultation
            return redirect()->route('doctor.consultation.create', $appointment->id)
                ->with('success', 'Appointment confirmed! Please complete the consultation.');

        } catch (\Exception $e) {
            \Log::error('Error in confirmAppointment', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return redirect()->route('doctor.planning')->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Start consultation for a confirmed appointment.
     */
    public function startConsultation($appointmentId)
    {
        $currentUser = auth()->user();

        if (!$currentUser || !$currentUser->isDoctor()) {
            return redirect()->route('login')->with('error', 'Access denied. Doctor login required.');
        }

        $doctor = $currentUser->doctor;

        // Find appointment for this doctor only
        $appointment = \App\Models\Appointment::where('id', $appointmentId)
            ->where('doctor_id', $doctor->id)
            ->with(['patient.user', 'consultation'])
            ->first();

        if (!$appointment) {
            return redirect()->route('doctor.planning')->with('error', 'Appointment not found or not authorized.');
        }

        if (!$appointment->isConfirmed()) {
            return redirect()->route('doctor.planning')->with('error', 'Appointment must be confirmed before starting consultation.');
        }

        if ($appointment->hasConsultation()) {
            return redirect()->route('doctor.consultation.edit', $appointment->consultation->id)
                ->with('info', 'Consultation already exists. You can edit it.');
        }

        return view('doctor.consultation.create', compact('appointment'));
    }

    /**
     * Store consultation.
     */
    public function storeConsultation(Request $request, $appointmentId)
    {
        $currentUser = auth()->user();

        if (!$currentUser || !$currentUser->isDoctor()) {
            return redirect()->route('login')->with('error', 'Access denied. Doctor login required.');
        }

        $doctor = $currentUser->doctor;

        $request->validate([
            'diagnosis' => 'required|string|max:2000',
            'treatment' => 'nullable|string|max:2000',
            'notes' => 'nullable|string|max:2000',
        ]);

        // Find appointment for this doctor only
        $appointment = \App\Models\Appointment::where('id', $appointmentId)
            ->where('doctor_id', $doctor->id)
            ->first();

        if (!$appointment) {
            return redirect()->route('doctor.planning')->with('error', 'Appointment not found or not authorized.');
        }

        if (!$appointment->isConfirmed()) {
            return redirect()->route('doctor.planning')->with('error', 'Appointment must be confirmed before creating consultation.');
        }

        if ($appointment->hasConsultation()) {
            return redirect()->route('doctor.planning')->with('error', 'Consultation already exists for this appointment.');
        }

        // Create consultation
        $consultation = \App\Models\Consultation::create([
            'appointment_id' => $appointment->id,
            'consultation_date' => now(),
            'diagnosis' => $request->diagnosis,
            'treatment' => $request->treatment,
            'notes' => $request->notes,
        ]);

        // Keep appointment status as "confirmed" - don't mark as completed until prescription is created
        // The appointment will be marked as completed only when prescription is saved

        // Automatically redirect to create prescription
        return redirect()->route('doctor.prescription.create', $consultation->id)
            ->with('success', 'Consultation created successfully! Please create a prescription to complete the workflow.');
    }

    /**
     * Show consultation details.
     */
    public function showConsultation($consultationId)
    {
        $currentUser = auth()->user();

        if (!$currentUser || !$currentUser->isDoctor()) {
            return redirect()->route('login')->with('error', 'Access denied. Doctor login required.');
        }

        $doctor = $currentUser->doctor;

        // Find consultation for this doctor only
        $consultation = \App\Models\Consultation::whereHas('appointment', function($query) use ($doctor) {
            $query->where('doctor_id', $doctor->id);
        })->with(['appointment.patient.user', 'appointment.prescriptions'])->find($consultationId);

        if (!$consultation) {
            return redirect()->route('doctor.planning')->with('error', 'Consultation not found or not authorized.');
        }

        return view('doctor.consultation.show', compact('consultation'));
    }

    /**
     * Show edit consultation form.
     */
    public function editConsultation($consultationId)
    {
        $currentUser = auth()->user();

        if (!$currentUser || !$currentUser->isDoctor()) {
            return redirect()->route('login')->with('error', 'Access denied. Doctor login required.');
        }

        $doctor = $currentUser->doctor;

        // Find consultation for this doctor only
        $consultation = \App\Models\Consultation::with(['appointment.patient.user', 'appointment.doctor.user'])
            ->whereHas('appointment', function($query) use ($doctor) {
                $query->where('doctor_id', $doctor->id);
            })
            ->findOrFail($consultationId);

        return view('doctor.consultation.edit', compact('consultation'));
    }

    /**
     * Update consultation.
     */
    public function updateConsultation(Request $request, $consultationId)
    {
        $currentUser = auth()->user();

        if (!$currentUser || !$currentUser->isDoctor()) {
            return redirect()->route('login')->with('error', 'Access denied. Doctor login required.');
        }

        $doctor = $currentUser->doctor;

        $request->validate([
            'diagnosis' => 'required|string|max:2000',
            'treatment' => 'nullable|string|max:2000',
            'notes' => 'nullable|string|max:2000',
        ]);

        // Find consultation for this doctor only
        $consultation = \App\Models\Consultation::whereHas('appointment', function($query) use ($doctor) {
            $query->where('doctor_id', $doctor->id);
        })->findOrFail($consultationId);

        $consultation->update([
            'diagnosis' => $request->diagnosis,
            'treatment' => $request->treatment,
            'notes' => $request->notes,
        ]);

        return redirect()->route('doctor.consultation.show', $consultation->id)
            ->with('success', 'Consultation updated successfully!');
    }

    /**
     * Delete consultation.
     */
    public function deleteConsultation($consultationId)
    {
        $currentUser = auth()->user();

        if (!$currentUser || !$currentUser->isDoctor()) {
            return redirect()->route('login')->with('error', 'Access denied. Doctor login required.');
        }

        $doctor = $currentUser->doctor;

        // Find consultation for this doctor only
        $consultation = \App\Models\Consultation::whereHas('appointment', function($query) use ($doctor) {
            $query->where('doctor_id', $doctor->id);
        })->findOrFail($consultationId);

        // Delete related prescriptions first
        $prescriptions = \App\Models\Prescription::where('appointment_id', $consultation->appointment_id)->get();
        foreach ($prescriptions as $prescription) {
            // Delete prescription lines
            \App\Models\PrescriptionLine::where('prescription_id', $prescription->id)->delete();
            // Delete prescription
            $prescription->delete();
        }

        // Delete consultation
        $consultation->delete();

        return redirect()->route('doctor.consultations')
            ->with('success', 'Consultation and related prescriptions deleted successfully!');
    }

    /**
     * Get all consultations for current doctor.
     */
    public function consultations()
    {
        $currentUser = auth()->user();

        if (!$currentUser || !$currentUser->isDoctor()) {
            return redirect()->route('login')->with('error', 'Access denied. Doctor login required.');
        }

        $doctor = $currentUser->doctor;

        // Get consultations for this doctor only
        $consultations = \App\Models\Consultation::whereHas('appointment', function($query) use ($doctor) {
            $query->where('doctor_id', $doctor->id);
        })->with(['appointment.patient.user', 'appointment.prescriptions'])
        ->orderBy('consultation_date', 'desc')
        ->get();

        return view('doctor.consultations', compact('consultations', 'currentUser'))->with('user', $currentUser);
    }

    /**
     * Get all prescriptions for current doctor.
     */
    public function prescriptions()
    {
        $currentUser = auth()->user();

        if (!$currentUser || !$currentUser->isDoctor()) {
            return redirect()->route('login')->with('error', 'Access denied. Doctor login required.');
        }

        $doctor = $currentUser->doctor;

        // Get prescriptions for this doctor only
        $prescriptions = \App\Models\Prescription::where('doctor_id', $doctor->id)
            ->with(['appointment.patient.user', 'lines.medication'])
            ->orderBy('prescribed_at', 'desc')
            ->get();

        return view('doctor.prescriptions', compact('prescriptions'));
    }

    /**
     * Show prescription details.
     */
    public function showPrescription($prescriptionId)
    {
        $currentUser = auth()->user();

        if (!$currentUser || !$currentUser->isDoctor()) {
            return redirect()->route('login')->with('error', 'Access denied. Doctor login required.');
        }

        $doctor = $currentUser->doctor;

        // Find prescription for this doctor only
        $prescription = \App\Models\Prescription::where('doctor_id', $doctor->id)
            ->with(['appointment.patient.user', 'appointment.consultation', 'lines.medication'])
            ->findOrFail($prescriptionId);

        return view('doctor.prescription.show', compact('prescription'));
    }

    /**
     * Show edit prescription form.
     */
    public function editPrescription($prescriptionId)
    {
        $currentUser = auth()->user();

        if (!$currentUser || !$currentUser->isDoctor()) {
            return redirect()->route('login')->with('error', 'Access denied. Doctor login required.');
        }

        $doctor = $currentUser->doctor;

        // Find prescription for this doctor only
        $prescription = \App\Models\Prescription::where('doctor_id', $doctor->id)
            ->with(['appointment.patient.user', 'appointment.consultation', 'lines.medication'])
            ->findOrFail($prescriptionId);

        // Get all medications for the form
        $medications = \App\Models\Medication::all();

        return view('doctor.prescription.edit', compact('prescription', 'medications'));
    }

    /**
     * Update prescription.
     */
    public function updatePrescription(Request $request, $prescriptionId)
    {
        $currentUser = auth()->user();

        if (!$currentUser || !$currentUser->isDoctor()) {
            return redirect()->route('login')->with('error', 'Access denied. Doctor login required.');
        }

        $doctor = $currentUser->doctor;

        $request->validate([
            'notes' => 'nullable|string|max:1000',
            'status' => 'required|in:draft,active,completed,cancelled',
            'medications' => 'required|array|min:1',
            'medications.*.medication_id' => 'required|exists:medications,id',
            'medications.*.quantity' => 'required|integer|min:1',
            'medications.*.dosage' => 'required|string|max:255',
            'medications.*.frequency' => 'nullable|string|max:255',
            'medications.*.duration_days' => 'nullable|integer|min:1',
            'medications.*.instructions' => 'nullable|string|max:500',
            'medications.*.start_date' => 'nullable|date',
            'medications.*.end_date' => 'nullable|date',
            'medications.*.notes' => 'nullable|string|max:500',
        ]);

        try {
            // Find prescription for this doctor only
            $prescription = \App\Models\Prescription::where('doctor_id', $doctor->id)
                ->findOrFail($prescriptionId);

            // Update prescription
            $prescription->update([
                'notes' => $request->notes,
                'status' => $request->status,
            ]);

            // Delete existing prescription lines
            \App\Models\PrescriptionLine::where('prescription_id', $prescription->id)->delete();

            // Create new prescription lines
            foreach ($request->medications as $medicationData) {
                \App\Models\PrescriptionLine::create([
                    'prescription_id' => $prescription->id,
                    'medication_id' => $medicationData['medication_id'],
                    'quantity' => $medicationData['quantity'],
                    'dosage' => $medicationData['dosage'],
                    'frequency' => $medicationData['frequency'] ?? 'As needed',
                    'duration_days' => $medicationData['duration_days'] ?? 7,
                    'instructions' => $medicationData['instructions'] ?? null,
                    'start_date' => $medicationData['start_date'] ?? now()->toDateString(),
                    'end_date' => $medicationData['end_date'] ?? now()->addDays(7)->toDateString(),
                    'notes' => $medicationData['notes'] ?? null,
                ]);
            }

            return redirect()->route('doctor.prescription.show', $prescription->id)
                ->with('success', 'Prescription updated successfully!');

        } catch (\Exception $e) {
            \Log::error('Error updating prescription: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error updating prescription: ' . $e->getMessage());
        }
    }

    /**
     * Delete prescription.
     */
    public function deletePrescription($prescriptionId)
    {
        $currentUser = auth()->user();

        if (!$currentUser || !$currentUser->isDoctor()) {
            return redirect()->route('login')->with('error', 'Access denied. Doctor login required.');
        }

        $doctor = $currentUser->doctor;

        // Find prescription for this doctor only
        $prescription = \App\Models\Prescription::where('doctor_id', $doctor->id)
            ->findOrFail($prescriptionId);

        // Delete prescription lines first
        \App\Models\PrescriptionLine::where('prescription_id', $prescription->id)->delete();

        // Delete prescription
        $prescription->delete();

        return redirect()->route('doctor.prescriptions')
            ->with('success', 'Prescription deleted successfully!');
    }

    /**
     * Show CRUD management page for completed appointment.
     */
    public function appointmentCrud($appointmentId)
    {
        $currentUser = auth()->user();

        if (!$currentUser || !$currentUser->isDoctor()) {
            return redirect()->route('login')->with('error', 'Access denied. Doctor login required.');
        }

        $doctor = $currentUser->doctor;

        // Find appointment for this doctor only
        $appointment = \App\Models\Appointment::where('id', $appointmentId)
            ->where('doctor_id', $doctor->id)
            ->with(['patient.user', 'consultation', 'prescriptions.lines.medication'])
            ->firstOrFail();

        // Check if appointment is completed and has consultation
        if ($appointment->status !== 'completed' || !$appointment->consultation) {
            return redirect()->route('doctor.planning')
                ->with('error', 'This appointment is not completed or does not have a consultation.');
        }

        return view('doctor.appointment.crud', compact('appointment'));
    }

    /**
     * Show all prescriptions written by the doctor.
     */
    public function allPrescriptions()
    {
        $user = Auth::user();

        if ($user->role !== 'doctor') {
            return redirect()->route('login')->with('error', 'Accès non autorisé.');
        }

        $doctor = Doctor::where('user_id', $user->id)->first();

        if (!$doctor) {
            return redirect()->route('login')->with('error', 'Profil docteur non trouvé.');
        }

        // Get all prescriptions written by this doctor
        $prescriptions = Prescription::where('doctor_id', $doctor->id)
            ->with(['appointment.patient.user', 'appointment.doctor.user'])
            ->orderBy('prescribed_at', 'desc')
            ->paginate(20);

        return view('doctor.prescriptions.all', compact('prescriptions', 'doctor'));
    }

    /**
     * Show all consultations done by the doctor.
     */
    public function allConsultations()
    {
        $user = Auth::user();

        if ($user->role !== 'doctor') {
            return redirect()->route('login')->with('error', 'Accès non autorisé.');
        }

        $doctor = Doctor::where('user_id', $user->id)->first();

        if (!$doctor) {
            return redirect()->route('login')->with('error', 'Profil docteur non trouvé.');
        }

        // Get all consultations done by this doctor through appointments
        $consultations = Consultation::whereHas('appointment', function($query) use ($doctor) {
                $query->where('doctor_id', $doctor->id);
            })
            ->with(['appointment.patient.user', 'appointment.doctor.user'])
            ->orderBy('consultation_date', 'desc')
            ->paginate(20);

        return view('doctor.consultations.all', compact('consultations', 'doctor'));
    }
}
