<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - MediCare Pro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #2563eb;
            --secondary-color: #64748b;
            --success-color: #059669;
            --warning-color: #d97706;
            --danger-color: #dc2626;
            --info-color: #0891b2;
            --patient-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --light-bg: #f8fafc;
            --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --card-shadow-hover: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--patient-gradient);
            min-height: 100vh;
        }

        .main-container {
            background: var(--light-bg);
            min-height: 100vh;
            border-radius: 20px 20px 0 0;
            margin-top: 2rem;
            box-shadow: var(--card-shadow);
            position: relative;
            z-index: 1;
        }

        .header-section {
            background: var(--patient-gradient);
            color: white;
            padding: 2rem;
            border-radius: 20px 20px 0 0;
            position: relative;
            overflow: visible;
        }

        .header-section::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            transform: translate(50%, -50%);
        }

        .patient-info {
            position: relative;
            z-index: 2;
        }

        .profile-card {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            box-shadow: var(--card-shadow);
            margin-bottom: 2rem;
        }

        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: var(--patient-gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 3rem;
            font-weight: 600;
            margin: 0 auto 1.5rem;
        }

        .form-control, .form-select {
            border-radius: 8px;
            border: 2px solid #e2e8f0;
            padding: 0.75rem;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .btn-primary {
            background: var(--patient-gradient);
            border: none;
            border-radius: 8px;
            padding: 0.75rem 2rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgba(102, 126, 234, 0.3);
        }

        .btn-secondary {
            background: var(--secondary-color);
            border: none;
            border-radius: 8px;
            padding: 0.75rem 2rem;
            font-weight: 500;
        }

        .info-item {
            background: #f8fafc;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
            border-left: 4px solid var(--primary-color);
        }

        .info-label {
            font-weight: 600;
            color: var(--secondary-color);
            font-size: 0.875rem;
            margin-bottom: 0.25rem;
        }

        .info-value {
            color: #1e293b;
            font-size: 1rem;
        }

        /* Dropdown Menu Fixes */
        .dropdown-menu {
            border: none;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            padding: 0.5rem 0;
            min-width: 200px;
            z-index: 9999 !important;
            margin-top: 0.5rem;
            position: absolute !important;
        }

        .dropdown-item {
            padding: 0.75rem 1.25rem;
            font-size: 0.9rem;
            transition: all 0.2s ease;
            border: none;
        }

        .dropdown-item:hover {
            background-color: #f8fafc;
            color: var(--primary-color);
            transform: translateX(5px);
        }

        .dropdown-item i {
            width: 20px;
            text-align: center;
        }

        .dropdown-divider {
            margin: 0.5rem 0;
            border-color: #e2e8f0;
        }

        /* Ensure dropdown appears above other content */
        .header-section {
            position: relative;
            z-index: 1000;
        }

        .header-section .dropdown {
            position: static;
        }

        .header-section .dropdown .dropdown-menu {
            z-index: 9999 !important;
        }

        /* Fix for dropdown positioning */
        .dropdown-menu-end {
            right: 0 !important;
            left: auto !important;
        }

        .gender-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .gender-male {
            background: #dbeafe;
            color: #1e40af;
        }

        .gender-female {
            background: #fce7f3;
            color: #be185d;
        }

        .gender-other {
            background: #f3e8ff;
            color: #7c3aed;
        }

        @media (max-width: 768px) {
            .main-container {
                margin-top: 0;
                border-radius: 0;
            }

            .header-section {
                border-radius: 0;
                padding: 1.5rem;
            }

            .profile-card {
                padding: 1.5rem;
            }

            .dropdown-menu {
                min-width: 180px;
                margin-top: 0.25rem;
            }

            .dropdown-item {
                padding: 0.6rem 1rem;
                font-size: 0.85rem;
            }
        }
    </style>
</head>
<body>
    <div class="main-container">
        <!-- Header Section -->
        <div class="header-section">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="patient-info">
                            <h1 class="mb-2">
                                <i class="fas fa-user me-3"></i>
                                My Profile
                            </h1>
                            <p class="mb-0 opacity-75">
                                <i class="fas fa-edit me-2"></i>Manage your personal information
                            </p>
                        </div>
                    </div>
                    <div class="col-md-4 text-end">
                        <div class="d-flex justify-content-end align-items-center">
                            <a href="{{ route('patient.dashboard') }}" class="btn btn-light me-3">
                                <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                            </a>
                            <div class="dropdown">
                                <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-user-circle me-2"></i>Menu
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="{{ route('patient.profile') }}"><i class="fas fa-user-edit me-2"></i>My Profile</a></li>
                                    <li><a class="dropdown-item" href="#"><i class="fas fa-file-medical me-2"></i>Medical Records</a></li>
                                    <li><a class="dropdown-item" href="#"><i class="fas fa-pills me-2"></i>My Prescriptions</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-danger" href="{{ route('logout.get') }}"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid py-4">
            <!-- Messages de succès/erreur -->
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            <div class="row">
                <!-- Current Information -->
                <div class="col-lg-4">
                    <div class="profile-card">
                        <div class="text-center">
                            <div class="profile-avatar">
                                {{ substr($patient->user->full_name ?? 'P', 0, 1) }}
                            </div>
                            <h3 class="mb-1">{{ $patient->user->full_name ?? 'Patient Name' }}</h3>
                            <p class="text-muted mb-3">
                                <span class="gender-badge gender-{{ $patient->gender ?? 'other' }}">
                                    <i class="fas fa-{{ $patient->gender === 'male' ? 'mars' : ($patient->gender === 'female' ? 'venus' : 'genderless') }} me-1"></i>
                                    {{ ucfirst($patient->gender ?? 'Not specified') }}
                                </span>
                            </p>
                        </div>

                        <div class="info-item">
                            <div class="info-label">Email</div>
                            <div class="info-value">{{ $patient->user->email ?? 'email@example.com' }}</div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">Phone</div>
                            <div class="info-value">{{ $patient->user->phone ?? 'Not provided' }}</div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">Birth Date</div>
                            <div class="info-value">
                                {{ $patient->birth_date ? $patient->birth_date->format('F d, Y') : 'Not provided' }}
                                @if($patient->birth_date)
                                    <small class="text-muted d-block">{{ $patient->birth_date->age }} years old</small>
                                @endif
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">Address</div>
                            <div class="info-value">{{ $patient->user->address ?? 'Not provided' }}</div>
                        </div>
                    </div>
                </div>

                <!-- Edit Form -->
                <div class="col-lg-8">
                    <div class="profile-card">
                        <h4 class="mb-4">
                            <i class="fas fa-edit me-2 text-primary"></i>
                            Edit My Information
                        </h4>

                        <form action="{{ route('patient.profile.update') }}" method="POST">
                            @csrf
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="first_name" class="form-label">First Name</label>
                                    <input type="text" class="form-control @error('first_name') is-invalid @enderror" 
                                           id="first_name" name="first_name" 
                                           value="{{ old('first_name', $patient->user->first_name ?? '') }}" required>
                                    @error('first_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="last_name" class="form-label">Last Name</label>
                                    <input type="text" class="form-control @error('last_name') is-invalid @enderror" 
                                           id="last_name" name="last_name" 
                                           value="{{ old('last_name', $patient->user->last_name ?? '') }}" required>
                                    @error('last_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" 
                                           value="{{ old('email', $patient->user->email ?? '') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="phone" class="form-label">Phone</label>
                                    <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" name="phone" 
                                           value="{{ old('phone', $patient->user->phone ?? '') }}">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="birth_date" class="form-label">Birth Date</label>
                                    <input type="date" class="form-control @error('birth_date') is-invalid @enderror" 
                                           id="birth_date" name="birth_date" 
                                           value="{{ old('birth_date', $patient->birth_date ? $patient->birth_date->format('Y-m-d') : '') }}" required>
                                    @error('birth_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="gender" class="form-label">Gender</label>
                                    <select class="form-select @error('gender') is-invalid @enderror" 
                                            id="gender" name="gender" required>
                                        <option value="">Select Gender</option>
                                        <option value="male" {{ old('gender', $patient->gender ?? '') === 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ old('gender', $patient->gender ?? '') === 'female' ? 'selected' : '' }}>Female</option>
                                        <option value="other" {{ old('gender', $patient->gender ?? '') === 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('gender')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="address" class="form-label">Address</label>
                                <textarea class="form-control @error('address') is-invalid @enderror" 
                                          id="address" name="address" rows="3" 
                                          placeholder="Enter your full address...">{{ old('address', $patient->user->address ?? '') }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Save Changes
                                </button>
                                <a href="{{ route('patient.dashboard') }}" class="btn btn-secondary">
                                    <i class="fas fa-times me-2"></i>Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Fix dropdown positioning on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Ensure all dropdowns have proper positioning
            const dropdowns = document.querySelectorAll('.dropdown-toggle');
            dropdowns.forEach(dropdown => {
                dropdown.addEventListener('click', function(e) {
                    // Force dropdown to stay within viewport
                    setTimeout(() => {
                        const dropdownMenu = this.nextElementSibling;
                        if (dropdownMenu && dropdownMenu.classList.contains('dropdown-menu')) {
                            const rect = dropdownMenu.getBoundingClientRect();
                            const viewportHeight = window.innerHeight;
                            
                            // If dropdown goes below viewport, adjust position
                            if (rect.bottom > viewportHeight) {
                                dropdownMenu.style.transform = `translateY(-${rect.height + 10}px)`;
                            }
                        }
                    }, 10);
                });
            });
        });
    </script>
</body>
</html>
