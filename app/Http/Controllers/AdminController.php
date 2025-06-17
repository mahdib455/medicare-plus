<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Admin;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Consultation;
use App\Models\SymptomCheck;
use App\Models\Prescription;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Show the admin dashboard with statistics.
     */
    public function dashboard()
    {
        // Get overall statistics
        $stats = [
            'total_users' => User::count(),
            'total_admins' => Admin::count(),
            'total_doctors' => Doctor::count(),
            'total_patients' => Patient::count(),
            'total_appointments' => Appointment::count(),
            'total_consultations' => Consultation::count(),
            'total_symptom_checks' => SymptomCheck::count(),
            'total_prescriptions' => Prescription::count(),
            
            // Recent activity
            'recent_appointments' => Appointment::with(['doctor.user', 'patient.user'])
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get(),
            'recent_symptom_checks' => SymptomCheck::with('user')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get(),
            'recent_users' => User::orderBy('created_at', 'desc')
                ->limit(5)
                ->get(),
                
            // Today's statistics
            'today_appointments' => Appointment::whereDate('appointment_date', today())->count(),
            'today_symptom_checks' => SymptomCheck::whereDate('created_at', today())->count(),
            'today_consultations' => Consultation::whereDate('consultation_date', today())->count(),
            
            // This week's statistics
            'week_appointments' => Appointment::whereBetween('appointment_date', [
                now()->startOfWeek(),
                now()->endOfWeek()
            ])->count(),
            'week_symptom_checks' => SymptomCheck::whereBetween('created_at', [
                now()->startOfWeek(),
                now()->endOfWeek()
            ])->count(),
            
            // Urgency statistics
            'high_urgency_symptoms' => SymptomCheck::where('urgency_level', '>=', 8)->count(),
            'pending_appointments' => Appointment::where('status', 'scheduled')->count(),
            'completed_consultations' => Consultation::whereNotNull('consultation_date')->count(),
        ];

        // Chart data for dashboard
        $chartData = [
            'appointments_by_day' => $this->getAppointmentsByDay(),
            'symptom_checks_by_day' => $this->getSymptomChecksByDay(),
            'users_by_role' => $this->getUsersByRole(),
            'urgency_distribution' => $this->getUrgencyDistribution(),
        ];

        return view('admin.dashboard', compact('stats', 'chartData'));
    }

    /**
     * Get appointments data for the last 7 days.
     */
    private function getAppointmentsByDay()
    {
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $data[] = [
                'date' => $date->format('M d'),
                'count' => Appointment::whereDate('appointment_date', $date)->count()
            ];
        }
        return $data;
    }

    /**
     * Get symptom checks data for the last 7 days.
     */
    private function getSymptomChecksByDay()
    {
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $data[] = [
                'date' => $date->format('M d'),
                'count' => SymptomCheck::whereDate('created_at', $date)->count()
            ];
        }
        return $data;
    }

    /**
     * Get users distribution by role.
     */
    private function getUsersByRole()
    {
        return User::select('role', DB::raw('count(*) as count'))
            ->groupBy('role')
            ->get()
            ->pluck('count', 'role')
            ->toArray();
    }

    /**
     * Get symptom checks urgency distribution.
     */
    private function getUrgencyDistribution()
    {
        return SymptomCheck::select('urgency_level', DB::raw('count(*) as count'))
            ->groupBy('urgency_level')
            ->orderBy('urgency_level')
            ->get()
            ->pluck('count', 'urgency_level')
            ->toArray();
    }

    // ==================== USERS MANAGEMENT ====================

    /**
     * Display a listing of users.
     */
    public function users(Request $request)
    {
        $query = User::query();

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($request->has('role') && $request->role) {
            $query->where('role', $request->role);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function createUser()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user.
     */
    public function storeUser(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,doctor,patient',
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'address' => 'nullable|string|max:500',
        ]);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'phone' => $request->phone,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'address' => $request->address,
        ]);

        // Create associated doctor or patient record
        if ($request->role === 'doctor') {
            Doctor::create([
                'user_id' => $user->id,
                'specialization' => $request->specialization ?? 'General Medicine',
                'license_number' => $request->license_number ?? 'LIC' . str_pad($user->id, 6, '0', STR_PAD_LEFT),
                'years_of_experience' => $request->years_of_experience ?? 0,
                'consultation_fee' => $request->consultation_fee ?? 100,
            ]);
        } elseif ($request->role === 'patient') {
            Patient::create([
                'user_id' => $user->id,
                'emergency_contact' => $request->emergency_contact,
                'blood_type' => $request->blood_type,
                'allergies' => $request->allergies,
                'medical_history' => $request->medical_history,
            ]);
        }

        return redirect()->route('admin.users')->with('success', 'User created successfully!');
    }

    /**
     * Show the form for editing a user.
     */
    public function editUser(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user.
     */
    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,doctor,patient',
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'address' => 'nullable|string|max:500',
        ]);

        $user->update($request->only([
            'first_name', 'last_name', 'email', 'role', 'phone', 
            'date_of_birth', 'gender', 'address'
        ]));

        // Update password if provided
        if ($request->password) {
            $request->validate(['password' => 'string|min:8|confirmed']);
            $user->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('admin.users')->with('success', 'User updated successfully!');
    }

    /**
     * Remove the specified user.
     */
    public function destroyUser(User $user)
    {
        // Prevent admin from deleting themselves
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users')->with('error', 'You cannot delete your own account!');
        }

        $user->delete();

        return redirect()->route('admin.users')->with('success', 'User deleted successfully!');
    }

    // ==================== APPOINTMENTS MANAGEMENT ====================

    /**
     * Display a listing of appointments.
     */
    public function appointments(Request $request)
    {
        $query = Appointment::with(['doctor.user', 'patient.user']);

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->whereHas('doctor.user', function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%");
            })->orWhereHas('patient.user', function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by date
        if ($request->has('date') && $request->date) {
            $query->whereDate('appointment_date', $request->date);
        }

        $appointments = $query->orderBy('appointment_date', 'desc')->paginate(15);

        return view('admin.appointments.index', compact('appointments'));
    }

    /**
     * Show the form for creating a new appointment.
     */
    public function createAppointment()
    {
        $doctors = Doctor::with('user')->get();
        $patients = Patient::with('user')->get();

        return view('admin.appointments.create', compact('doctors', 'patients'));
    }

    /**
     * Store a newly created appointment.
     */
    public function storeAppointment(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'patient_id' => 'required|exists:patients,id',
            'appointment_date' => 'required|date|after:now',
            'appointment_time' => 'required|date_format:H:i',
            'reason' => 'required|string|max:500',
            'status' => 'required|in:scheduled,confirmed,completed,cancelled',
        ]);

        Appointment::create($request->all());

        return redirect()->route('admin.appointments')->with('success', 'Appointment created successfully!');
    }

    /**
     * Show the form for editing an appointment.
     */
    public function editAppointment(Appointment $appointment)
    {
        $doctors = Doctor::with('user')->get();
        $patients = Patient::with('user')->get();

        return view('admin.appointments.edit', compact('appointment', 'doctors', 'patients'));
    }

    /**
     * Update the specified appointment.
     */
    public function updateAppointment(Request $request, Appointment $appointment)
    {
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'patient_id' => 'required|exists:patients,id',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required|date_format:H:i',
            'reason' => 'required|string|max:500',
            'status' => 'required|in:scheduled,confirmed,completed,cancelled',
        ]);

        $appointment->update($request->all());

        return redirect()->route('admin.appointments')->with('success', 'Appointment updated successfully!');
    }

    /**
     * Remove the specified appointment.
     */
    public function destroyAppointment(Appointment $appointment)
    {
        $appointment->delete();

        return redirect()->route('admin.appointments')->with('success', 'Appointment deleted successfully!');
    }

    // ==================== SYMPTOM CHECKS MANAGEMENT ====================

    /**
     * Display a listing of symptom checks.
     */
    public function symptomChecks(Request $request)
    {
        $query = SymptomCheck::with('user');

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where('symptom_text', 'like', "%{$search}%")
                  ->orWhere('result', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%");
                  });
        }

        // Filter by urgency level
        if ($request->has('urgency') && $request->urgency) {
            $query->where('urgency_level', $request->urgency);
        }

        // Filter by date
        if ($request->has('date') && $request->date) {
            $query->whereDate('created_at', $request->date);
        }

        $symptomChecks = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.symptom-checks.index', compact('symptomChecks'));
    }

    /**
     * Show the specified symptom check.
     */
    public function showSymptomCheck(SymptomCheck $symptomCheck)
    {
        return view('admin.symptom-checks.show', compact('symptomCheck'));
    }

    /**
     * Remove the specified symptom check.
     */
    public function destroySymptomCheck(SymptomCheck $symptomCheck)
    {
        $symptomCheck->delete();

        return redirect()->route('admin.symptom-checks')->with('success', 'Symptom check deleted successfully!');
    }

    // ==================== ADMINS MANAGEMENT ====================

    /**
     * Display a listing of admins.
     */
    public function admins(Request $request)
    {
        $query = Admin::with('user');

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by admin level
        if ($request->has('level') && $request->level) {
            $query->where('admin_level', $request->level);
        }

        $admins = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.admins.index', compact('admins'));
    }

    /**
     * Show the form for creating a new admin.
     */
    public function createAdmin()
    {
        $users = User::where('role', '!=', 'admin')->get();

        return view('admin.admins.create', compact('users'));
    }

    /**
     * Store a newly created admin.
     */
    public function storeAdmin(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'admin_level' => 'required|in:super,regular,moderator',
            'department' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
            'permissions' => 'nullable|array'
        ]);

        // Update user role to admin
        $user = User::findOrFail($request->user_id);
        $user->update(['role' => 'admin']);

        // Create admin profile
        $admin = Admin::createAdmin(
            $user->id,
            $request->admin_level,
            auth()->id()
        );

        // Update with custom settings
        $admin->update([
            'department' => $request->department ?? 'Administration',
            'notes' => $request->notes,
            'permissions' => $request->permissions ?? Admin::getDefaultPermissions($request->admin_level)
        ]);

        return redirect()->route('admin.admins')->with('success', 'Admin created successfully!');
    }

    /**
     * Show the form for editing an admin.
     */
    public function editAdmin(Admin $admin)
    {
        $allPermissions = Admin::getAllPermissions();

        return view('admin.admins.edit', compact('admin', 'allPermissions'));
    }

    /**
     * Update the specified admin.
     */
    public function updateAdmin(Request $request, Admin $admin)
    {
        $request->validate([
            'admin_level' => 'required|in:super,regular,moderator',
            'department' => 'nullable|string|max:255',
            'access_level' => 'required|integer|min:1|max:10',
            'notes' => 'nullable|string|max:1000',
            'permissions' => 'nullable|array'
        ]);

        $admin->update([
            'admin_level' => $request->admin_level,
            'department' => $request->department,
            'access_level' => $request->access_level,
            'notes' => $request->notes,
            'permissions' => $request->permissions ?? []
        ]);

        return redirect()->route('admin.admins')->with('success', 'Admin updated successfully!');
    }

    /**
     * Remove the specified admin.
     */
    public function destroyAdmin(Admin $admin)
    {
        // Prevent admin from deleting themselves
        if ($admin->user_id === auth()->id()) {
            return redirect()->route('admin.admins')->with('error', 'You cannot delete your own admin profile!');
        }

        // Update user role back to patient
        $admin->user->update(['role' => 'patient']);

        // Delete admin profile
        $admin->delete();

        return redirect()->route('admin.admins')->with('success', 'Admin deleted successfully!');
    }

    /**
     * Show admin profile details.
     */
    public function showAdmin(Admin $admin)
    {
        return view('admin.admins.show', compact('admin'));
    }
}
