<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Patient;

class RegisterController extends Controller
{
    /**
     * Show the registration form.
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle registration request.
     */
    public function register(Request $request)
    {
        // Base validation rules
        $rules = [
            'role' => 'required|in:doctor,patient',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ];

        // Add role-specific validation rules
        if ($request->role === 'doctor') {
            $rules = array_merge($rules, [
                'speciality' => 'required|string|max:255',
                'hospital' => 'required|string|max:255',
                'biography' => 'nullable|string|max:1000',
            ]);
        } elseif ($request->role === 'patient') {
            $rules = array_merge($rules, [
                'birth_date' => 'required|date|before:today',
                'gender' => 'required|in:male,female,other',
            ]);
        }

        // Validate the request
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Create the user
            $user = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'phone' => $request->phone,
                'address' => $request->address,
            ]);

            // Create role-specific profile
            if ($request->role === 'doctor') {
                Doctor::create([
                    'user_id' => $user->id,
                    'speciality' => $request->speciality,
                    'hospital' => $request->hospital,
                    'biography' => $request->biography,
                ]);

                $redirectRoute = 'doctor.dashboard';
                $message = 'Compte docteur créé avec succès ! Bienvenue sur MediCare+.';
            } elseif ($request->role === 'patient') {
                Patient::create([
                    'user_id' => $user->id,
                    'birth_date' => $request->birth_date,
                    'gender' => $request->gender,
                    'phone' => $request->phone,
                    'address' => $request->address,
                ]);

                $redirectRoute = 'patient.dashboard';
                $message = 'Compte patient créé avec succès ! Bienvenue sur MediCare+.';
            }

            // Log the user in
            auth()->login($user);

            return redirect()->route($redirectRoute)->with('success', $message);

        } catch (\Exception $e) {
            \Log::error('Registration error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'An error occurred during registration. Please try again.')
                ->withInput();
        }
    }

    /**
     * Get role-specific fields via AJAX.
     */
    public function getRoleFields(Request $request)
    {
        $role = $request->input('role');
        
        if ($role === 'doctor') {
            return response()->json([
                'success' => true,
                'fields' => [
                    [
                        'name' => 'speciality',
                        'label' => 'Spécialité',
                        'type' => 'text',
                        'required' => true,
                        'placeholder' => 'ex: Cardiologie, Neurologie, Médecine Générale',
                        'icon' => 'fas fa-stethoscope'
                    ],
                    [
                        'name' => 'hospital',
                        'label' => 'Hôpital/Clinique',
                        'type' => 'text',
                        'required' => true,
                        'placeholder' => 'Nom de l\'hôpital ou clinique',
                        'icon' => 'fas fa-hospital'
                    ],
                    [
                        'name' => 'biography',
                        'label' => 'Biographie',
                        'type' => 'textarea',
                        'required' => false,
                        'placeholder' => 'Brève biographie professionnelle (optionnel)',
                        'icon' => 'fas fa-user-md',
                        'rows' => 3
                    ]
                ]
            ]);
        } elseif ($role === 'patient') {
            return response()->json([
                'success' => true,
                'fields' => [
                    [
                        'name' => 'birth_date',
                        'label' => 'Date de Naissance',
                        'type' => 'date',
                        'required' => true,
                        'placeholder' => '',
                        'icon' => 'fas fa-birthday-cake'
                    ],
                    [
                        'name' => 'gender',
                        'label' => 'Genre',
                        'type' => 'select',
                        'required' => true,
                        'placeholder' => 'Sélectionnez votre genre',
                        'icon' => 'fas fa-venus-mars',
                        'options' => [
                            ['value' => 'male', 'label' => 'Homme'],
                            ['value' => 'female', 'label' => 'Femme'],
                            ['value' => 'other', 'label' => 'Autre']
                        ]
                    ]
                ]
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Invalid role specified'
        ]);
    }
}
