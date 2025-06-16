<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        // Debug: Vérifier tous les rendez-vous existants
        $allAppointments = \App\Models\Appointment::with(['patient.user', 'doctor.user'])->get();

        // Debug: Vérifier tous les docteurs existants
        $allDoctors = \App\Models\Doctor::with('user')->get();

        // Trouver le docteur qui a des rendez-vous ou utiliser le premier disponible
        $doctorWithAppointments = $allAppointments->first();
        $doctorId = $doctorWithAppointments ? $doctorWithAppointments->doctor_id : ($allDoctors->first() ? $allDoctors->first()->id : 1);

        // Récupérer les infos du docteur
        $doctor = \App\Models\Doctor::with('user')->find($doctorId);

        $user = $doctor && $doctor->user ? (object) [
            'full_name' => $doctor->user->full_name,
            'email' => $doctor->user->email,
            'phone' => $doctor->user->phone
        ] : (object) [
            'full_name' => 'Dr. Test',
            'email' => 'doctor@test.com',
            'phone' => '0123456789'
        ];

        $doctorInfo = $doctor ? (object) [
            'id' => $doctor->id,
            'speciality' => $doctor->speciality,
            'hospital' => $doctor->hospital
        ] : (object) [
            'id' => $doctorId,
            'speciality' => 'Cardiologie',
            'hospital' => 'CHU Test'
        ];

        // Récupérer les rendez-vous pour ce docteur
        $appointments = \App\Models\Appointment::where('doctor_id', $doctorId)
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
        $user = Auth::user();
        $doctor = $user->doctor;

        return view('doctor.profile', compact('user', 'doctor'));
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
}
