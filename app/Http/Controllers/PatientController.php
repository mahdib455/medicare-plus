<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PatientController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        // Pas d'authentification
    }

    /**
     * Show the patient dashboard.
     */
    public function dashboard()
    {
        // Données factices pour test
        $user = (object) [
            'full_name' => 'Patient Test',
            'email' => 'patient@test.com',
            'phone' => '0123456789'
        ];

        $patient = null;
        $upcomingAppointments = collect();
        $recentAppointments = collect();

        return view('patient.dashboard', compact('user', 'patient', 'upcomingAppointments', 'recentAppointments'));
    }

    /**
     * Show the patient profile.
     */
    public function profile()
    {
        $user = Auth::user();
        $patient = $user->patient;

        return view('patient.profile', compact('user', 'patient'));
    }

    /**
     * Show appointments.
     */
    public function appointments()
    {
        // TODO: Implement appointments logic
        return view('patient.appointments');
    }

    /**
     * Show doctors list.
     */
    public function doctors()
    {
        // TODO: Implement doctors list logic
        return view('patient.doctors');
    }
}
