<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PatientController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        // Require authentication for all patient routes
        $this->middleware('auth');
    }

    /**
     * Show the patient dashboard.
     */
    public function dashboard()
    {
        $currentUser = auth()->user();

        if (!$currentUser || !$currentUser->isPatient()) {
            return redirect()->route('login')->with('error', 'Access denied. Patient login required.');
        }

        $patient = $currentUser->patient;

        if (!$patient) {
            return redirect()->route('login')->with('error', 'Patient profile not found.');
        }

        $user = $currentUser;

        // Récupérer les rendez-vous de CE patient uniquement
        $upcomingAppointments = \App\Models\Appointment::where('patient_id', $patient->id)
            ->where('appointment_date', '>=', now())
            ->with(['doctor.user'])
            ->orderBy('appointment_date', 'asc')
            ->get();

        $recentAppointments = \App\Models\Appointment::where('patient_id', $patient->id)
            ->with(['doctor.user'])
            ->orderBy('appointment_date', 'desc')
            ->get();

        // Récupérer les consultations de CE patient uniquement
        $consultations = \App\Models\Consultation::whereHas('appointment', function($query) use ($patient) {
            $query->where('patient_id', $patient->id);
        })
        ->with(['appointment.doctor.user'])
        ->orderBy('consultation_date', 'desc')
        ->get();

        // Récupérer les prescriptions de CE patient uniquement
        $prescriptions = \App\Models\Prescription::where('patient_id', $patient->id)
            ->with(['appointment.doctor.user', 'lines.medication'])
            ->orderBy('prescribed_at', 'desc')
            ->limit(5)
            ->get();

        // Récupérer les analyses de symptômes de CE patient uniquement
        $symptomChecks = \App\Models\SymptomCheck::where('user_id', $user->id)
            ->orderBy('id', 'desc')
            ->limit(5)
            ->get();

        return view('patient.dashboard', compact('user', 'patient', 'upcomingAppointments', 'recentAppointments', 'consultations', 'prescriptions', 'symptomChecks'));
    }

    /**
     * Show the patient profile.
     */
    public function profile()
    {
        $currentUser = auth()->user();

        if (!$currentUser || !$currentUser->isPatient()) {
            return redirect()->route('login')->with('error', 'Access denied. Patient login required.');
        }

        $patient = $currentUser->patient;

        if (!$patient) {
            return redirect()->route('login')->with('error', 'Patient profile not found.');
        }

        return view('patient.profile', compact('patient'));
    }

    /**
     * Update the patient profile.
     */
    public function updateProfile(Request $request)
    {
        $currentUser = auth()->user();

        if (!$currentUser || !$currentUser->isPatient()) {
            return redirect()->route('login')->with('error', 'Access denied. Patient login required.');
        }

        $patient = $currentUser->patient;

        if (!$patient) {
            return redirect()->route('login')->with('error', 'Patient profile not found.');
        }

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'birth_date' => 'required|date',
            'gender' => 'required|in:male,female,other',
        ]);

        try {
            // Mettre à jour les informations utilisateur de CE patient uniquement
            $currentUser->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
            ]);

            // Mettre à jour les informations patient de CE patient uniquement
            \DB::table('patients')
                ->where('id', $patient->id)
                ->update([
                    'birth_date' => $request->birth_date,
                    'gender' => $request->gender,
                    'phone' => $request->phone,
                    'address' => $request->address,
                    'updated_at' => now(),
                ]);

            return redirect()->route('patient.profile')->with('success', 'Profile updated successfully!');

        } catch (\Exception $e) {
            \Log::error('Error updating patient profile: ' . $e->getMessage());
            return redirect()->route('patient.profile')->with('error', 'Error updating profile: ' . $e->getMessage());
        }
    }

    /**
     * Show appointments for current patient.
     */
    public function appointments()
    {
        $currentUser = auth()->user();

        if (!$currentUser || !$currentUser->isPatient()) {
            return redirect()->route('login')->with('error', 'Access denied. Patient login required.');
        }

        $patient = $currentUser->patient;

        if (!$patient) {
            return redirect()->route('login')->with('error', 'Patient profile not found.');
        }

        // Get appointments for THIS patient only
        $appointments = \App\Models\Appointment::where('patient_id', $patient->id)
            ->with(['doctor.user', 'consultation', 'prescriptions'])
            ->orderBy('appointment_date', 'desc')
            ->get();

        return view('patient.appointments', compact('appointments', 'patient'));
    }

    /**
     * Show consultations for current patient.
     */
    public function consultations()
    {
        $currentUser = auth()->user();

        if (!$currentUser || !$currentUser->isPatient()) {
            return redirect()->route('login')->with('error', 'Access denied. Patient login required.');
        }

        $patient = $currentUser->patient;

        if (!$patient) {
            return redirect()->route('login')->with('error', 'Patient profile not found.');
        }

        // Get consultations for THIS patient only
        $consultations = \App\Models\Consultation::whereHas('appointment', function($query) use ($patient) {
            $query->where('patient_id', $patient->id);
        })
        ->with(['appointment.doctor.user'])
        ->orderBy('consultation_date', 'desc')
        ->get();

        return view('patient.consultations', compact('consultations', 'patient'));
    }

    /**
     * Show prescriptions for current patient.
     */
    public function prescriptions()
    {
        $currentUser = auth()->user();

        if (!$currentUser || !$currentUser->isPatient()) {
            return redirect()->route('login')->with('error', 'Access denied. Patient login required.');
        }

        $patient = $currentUser->patient;

        if (!$patient) {
            return redirect()->route('login')->with('error', 'Patient profile not found.');
        }

        // Get prescriptions for THIS patient only
        $prescriptions = \App\Models\Prescription::where('patient_id', $patient->id)
            ->with(['appointment.doctor.user', 'lines.medication'])
            ->orderBy('prescribed_at', 'desc')
            ->get();

        return view('patient.prescriptions', compact('prescriptions', 'patient'));
    }

    /**
     * Download prescription as PDF.
     */
    public function downloadPrescription($prescriptionId)
    {
        $currentUser = auth()->user();

        if (!$currentUser || !$currentUser->isPatient()) {
            return redirect()->route('login')->with('error', 'Access denied. Patient login required.');
        }

        $patient = $currentUser->patient;

        if (!$patient) {
            return redirect()->route('login')->with('error', 'Patient profile not found.');
        }

        // Find prescription for THIS patient only
        $prescription = \App\Models\Prescription::where('patient_id', $patient->id)
            ->with(['appointment.doctor.user', 'appointment.patient.user', 'lines.medication'])
            ->findOrFail($prescriptionId);

        // Generate PDF
        $pdf = \PDF::loadView('patient.prescription-pdf', compact('prescription'));

        $filename = 'prescription_' . $prescription->id . '_' . now()->format('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Print prescription (returns printable view).
     */
    public function printPrescription($prescriptionId)
    {
        $currentUser = auth()->user();

        if (!$currentUser || !$currentUser->isPatient()) {
            return redirect()->route('login')->with('error', 'Access denied. Patient login required.');
        }

        $patient = $currentUser->patient;

        if (!$patient) {
            return redirect()->route('login')->with('error', 'Patient profile not found.');
        }

        // Find prescription for THIS patient only
        $prescription = \App\Models\Prescription::where('patient_id', $patient->id)
            ->with(['appointment.doctor.user', 'appointment.patient.user', 'lines.medication'])
            ->findOrFail($prescriptionId);

        return view('patient.prescription-print', compact('prescription'));
    }

    /**
     * Show doctors list.
     */
    public function doctors()
    {
        $currentUser = auth()->user();

        if (!$currentUser || !$currentUser->isPatient()) {
            return redirect()->route('login')->with('error', 'Access denied. Patient login required.');
        }

        $patient = $currentUser->patient;

        if (!$patient) {
            return redirect()->route('login')->with('error', 'Patient profile not found.');
        }

        // Get all doctors (this is public information)
        $doctors = \App\Models\Doctor::with('user')->get();

        return view('patient.doctors', compact('doctors', 'patient'));
    }
}
