<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle login request with role-based redirection.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        // Find user by email
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'email' => ['User not found with this email address.'],
            ]);
        }

        // Check password - support both hashed and plain text passwords
        $passwordMatch = false;

        // First try hashed password (for admin users)
        if (Hash::check($request->password, $user->password)) {
            $passwordMatch = true;
        }
        // Fallback to plain text comparison (for existing users)
        elseif ($user->password === $request->password) {
            $passwordMatch = true;
        }

        if ($passwordMatch) {
            // Login the user manually
            Auth::login($user);

            // Regenerate session to prevent session fixation
            $request->session()->regenerate();

            // Smart redirection based on user type

            // Check if user has admin profile (admin inherits from users table)
            if ($user->admin) {
                // Update admin login statistics
                $user->admin->updateLoginStats();
                // Redirect to admin dashboard
                return redirect()->intended('/admin/dashboard')->with('success', 'Welcome Administrator ' . $user->full_name . '!');
            }
            // Role-based redirection for regular users
            elseif ($user->isDoctor()) {
                return redirect()->intended('/doctor/dashboard')->with('success', 'Welcome Dr. ' . $user->full_name . '!');
            } elseif ($user->isPatient()) {
                return redirect()->intended('/patient/dashboard')->with('success', 'Welcome ' . $user->full_name . '!');
            }

            // Default fallback
            return redirect()->intended('/dashboard')->with('success', 'Welcome ' . $user->full_name . '!');
        }

        throw ValidationException::withMessages([
            'email' => ['The provided credentials do not match our records.'],
        ]);
    }

    /**
     * Handle logout request.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

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
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'role' => 'required|in:doctor,patient', // Admin not allowed in registration
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Prevent admin role creation through registration
        if ($request->role === 'admin') {
            throw ValidationException::withMessages([
                'role' => ['Admin accounts cannot be created through registration.'],
            ]);
        }

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'role' => $request->role,
            'password' => $request->password, // Plain text password for compatibility
        ]);

        Auth::login($user);

        // Role-based redirection after registration
        if ($user->isDoctor()) {
            return redirect('/doctor/dashboard');
        } elseif ($user->isPatient()) {
            return redirect('/patient/dashboard');
        }

        return redirect('/dashboard');
    }
}
