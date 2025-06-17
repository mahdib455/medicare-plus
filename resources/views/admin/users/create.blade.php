<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create User - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 0.75rem 1rem;
            margin: 0.25rem 0;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            color: white;
            background: rgba(255,255,255,0.1);
            transform: translateX(5px);
        }
        .form-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .form-section {
            border-left: 4px solid #667eea;
            padding-left: 1rem;
            margin-bottom: 2rem;
        }
        .role-specific {
            display: none;
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1.5rem;
            margin-top: 1rem;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar p-0">
                <div class="p-3">
                    <h4 class="text-white mb-4">
                        <i class="fas fa-user-shield me-2"></i>
                        Admin Panel
                    </h4>
                    <nav class="nav flex-column">
                        <a class="nav-link" href="{{ route('admin.dashboard') }}">
                            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                        </a>
                        <a class="nav-link active" href="{{ route('admin.users') }}">
                            <i class="fas fa-users me-2"></i>Users Management
                        </a>
                        <a class="nav-link" href="{{ route('admin.appointments') }}">
                            <i class="fas fa-calendar-alt me-2"></i>Appointments
                        </a>
                        <a class="nav-link" href="{{ route('admin.symptom-checks') }}">
                            <i class="fas fa-brain me-2"></i>Symptom Checks
                        </a>
                        <hr class="my-3" style="border-color: rgba(255,255,255,0.3);">
                        <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </nav>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h3 mb-0">Create New User</h1>
                    <a href="{{ route('admin.users') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Users
                    </a>
                </div>

                <div class="form-card">
                    <form method="POST" action="{{ route('admin.users.store') }}">
                        @csrf

                        <!-- Basic Information -->
                        <div class="form-section">
                            <h5 class="text-primary mb-3">
                                <i class="fas fa-user me-2"></i>Basic Information
                            </h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="first_name" class="form-label">First Name *</label>
                                    <input type="text" class="form-control @error('first_name') is-invalid @enderror" 
                                           id="first_name" name="first_name" value="{{ old('first_name') }}" required>
                                    @error('first_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="last_name" class="form-label">Last Name *</label>
                                    <input type="text" class="form-control @error('last_name') is-invalid @enderror" 
                                           id="last_name" name="last_name" value="{{ old('last_name') }}" required>
                                    @error('last_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email Address *</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email') }}" required>
                                    @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" name="phone" value="{{ old('phone') }}">
                                    @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="role" class="form-label">Role *</label>
                                    <select class="form-select @error('role') is-invalid @enderror" 
                                            id="role" name="role" required onchange="toggleRoleSpecific()">
                                        <option value="">Select Role</option>
                                        <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                                        <option value="doctor" {{ old('role') === 'doctor' ? 'selected' : '' }}>Doctor</option>
                                        <option value="patient" {{ old('role') === 'patient' ? 'selected' : '' }}>Patient</option>
                                    </select>
                                    @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="gender" class="form-label">Gender</label>
                                    <select class="form-select @error('gender') is-invalid @enderror" 
                                            id="gender" name="gender">
                                        <option value="">Select Gender</option>
                                        <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Female</option>
                                        <option value="other" {{ old('gender') === 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('gender')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="date_of_birth" class="form-label">Date of Birth</label>
                                    <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror" 
                                           id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth') }}">
                                    @error('date_of_birth')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">Address</label>
                                <textarea class="form-control @error('address') is-invalid @enderror" 
                                          id="address" name="address" rows="2">{{ old('address') }}</textarea>
                                @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Password Section -->
                        <div class="form-section">
                            <h5 class="text-primary mb-3">
                                <i class="fas fa-lock me-2"></i>Security
                            </h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="password" class="form-label">Password *</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                           id="password" name="password" required>
                                    @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="password_confirmation" class="form-label">Confirm Password *</label>
                                    <input type="password" class="form-control" 
                                           id="password_confirmation" name="password_confirmation" required>
                                </div>
                            </div>
                        </div>

                        <!-- Doctor Specific Fields -->
                        <div id="doctor-fields" class="role-specific">
                            <h6 class="text-info mb-3">
                                <i class="fas fa-user-md me-2"></i>Doctor Information
                            </h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="specialization" class="form-label">Specialization</label>
                                    <input type="text" class="form-control" id="specialization" name="specialization" 
                                           value="{{ old('specialization', 'General Medicine') }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="license_number" class="form-label">License Number</label>
                                    <input type="text" class="form-control" id="license_number" name="license_number" 
                                           value="{{ old('license_number') }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="years_of_experience" class="form-label">Years of Experience</label>
                                    <input type="number" class="form-control" id="years_of_experience" name="years_of_experience" 
                                           value="{{ old('years_of_experience', 0) }}" min="0">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="consultation_fee" class="form-label">Consultation Fee</label>
                                    <input type="number" class="form-control" id="consultation_fee" name="consultation_fee" 
                                           value="{{ old('consultation_fee', 100) }}" min="0" step="0.01">
                                </div>
                            </div>
                        </div>

                        <!-- Patient Specific Fields -->
                        <div id="patient-fields" class="role-specific">
                            <h6 class="text-success mb-3">
                                <i class="fas fa-user-injured me-2"></i>Patient Information
                            </h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="emergency_contact" class="form-label">Emergency Contact</label>
                                    <input type="text" class="form-control" id="emergency_contact" name="emergency_contact" 
                                           value="{{ old('emergency_contact') }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="blood_type" class="form-label">Blood Type</label>
                                    <select class="form-select" id="blood_type" name="blood_type">
                                        <option value="">Select Blood Type</option>
                                        <option value="A+" {{ old('blood_type') === 'A+' ? 'selected' : '' }}>A+</option>
                                        <option value="A-" {{ old('blood_type') === 'A-' ? 'selected' : '' }}>A-</option>
                                        <option value="B+" {{ old('blood_type') === 'B+' ? 'selected' : '' }}>B+</option>
                                        <option value="B-" {{ old('blood_type') === 'B-' ? 'selected' : '' }}>B-</option>
                                        <option value="AB+" {{ old('blood_type') === 'AB+' ? 'selected' : '' }}>AB+</option>
                                        <option value="AB-" {{ old('blood_type') === 'AB-' ? 'selected' : '' }}>AB-</option>
                                        <option value="O+" {{ old('blood_type') === 'O+' ? 'selected' : '' }}>O+</option>
                                        <option value="O-" {{ old('blood_type') === 'O-' ? 'selected' : '' }}>O-</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="allergies" class="form-label">Allergies</label>
                                <textarea class="form-control" id="allergies" name="allergies" rows="2">{{ old('allergies') }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label for="medical_history" class="form-label">Medical History</label>
                                <textarea class="form-control" id="medical_history" name="medical_history" rows="3">{{ old('medical_history') }}</textarea>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.users') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Create User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleRoleSpecific() {
            const role = document.getElementById('role').value;
            const doctorFields = document.getElementById('doctor-fields');
            const patientFields = document.getElementById('patient-fields');
            
            // Hide all role-specific fields
            doctorFields.style.display = 'none';
            patientFields.style.display = 'none';
            
            // Show relevant fields based on role
            if (role === 'doctor') {
                doctorFields.style.display = 'block';
            } else if (role === 'patient') {
                patientFields.style.display = 'block';
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            toggleRoleSpecific();
        });
    </script>
</body>
</html>
