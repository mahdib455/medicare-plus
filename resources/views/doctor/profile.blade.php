<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil - MediCare Pro</title>
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
            --light-bg: #f8fafc;
            --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --card-shadow-hover: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            background: linear-gradient(135deg, var(--primary-color) 0%, #3b82f6 100%);
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

        .doctor-info {
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
            background: linear-gradient(135deg, var(--primary-color), #3b82f6);
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
            background: linear-gradient(135deg, var(--primary-color), #3b82f6);
            border: none;
            border-radius: 8px;
            padding: 0.75rem 2rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgba(37, 99, 235, 0.3);
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
                        <div class="doctor-info">
                            <h1 class="mb-2">
                                <i class="fas fa-user-md me-3"></i>
                                Mon Profil Médical
                            </h1>
                            <p class="mb-0 opacity-75">
                                <i class="fas fa-edit me-2"></i>Gérez vos informations professionnelles
                            </p>
                        </div>
                    </div>
                    <div class="col-md-4 text-end">
                        <div class="d-flex justify-content-end align-items-center">
                            <a href="{{ route('doctor.dashboard') }}" class="btn btn-light me-3">
                                <i class="fas fa-arrow-left me-2"></i>Retour au Dashboard
                            </a>
                            <div class="dropdown">
                                <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-user-circle me-2"></i>Menu
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="{{ route('doctor.profile') }}"><i class="fas fa-user-edit me-2"></i>My Profile</a></li>
                                    <li><a class="dropdown-item" href="{{ route('doctor.planning') }}"><i class="fas fa-calendar-alt me-2"></i>Schedule</a></li>
                                    <li><a class="dropdown-item" href="{{ route('doctor.statistics') }}"><i class="fas fa-chart-bar me-2"></i>Statistics</a></li>
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
                <!-- Informations actuelles -->
                <div class="col-lg-4">
                    <div class="profile-card">
                        <div class="text-center">
                            <div class="profile-avatar">
                                {{ substr($doctor->user->full_name ?? 'D', 0, 1) }}
                            </div>
                            <h3 class="mb-1">{{ $doctor->user->full_name ?? 'Dr. Nom' }}</h3>
                            <p class="text-muted mb-3">{{ $doctor->speciality ?? 'Spécialité' }}</p>
                        </div>

                        <div class="info-item">
                            <div class="info-label">Email</div>
                            <div class="info-value">{{ $doctor->user->email ?? 'email@example.com' }}</div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">Téléphone</div>
                            <div class="info-value">{{ $doctor->user->phone ?? 'Non renseigné' }}</div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">Hôpital</div>
                            <div class="info-value">{{ $doctor->hospital ?? 'Non renseigné' }}</div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">Biographie</div>
                            <div class="info-value">{{ $doctor->biography ?? 'Aucune biographie' }}</div>
                        </div>
                    </div>
                </div>

                <!-- Formulaire de modification -->
                <div class="col-lg-8">
                    <div class="profile-card">
                        <h4 class="mb-4">
                            <i class="fas fa-edit me-2 text-primary"></i>
                            Modifier mes informations
                        </h4>

                        <form action="{{ route('doctor.profile.update') }}" method="POST">
                            @csrf
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="first_name" class="form-label">Prénom</label>
                                    <input type="text" class="form-control @error('first_name') is-invalid @enderror" 
                                           id="first_name" name="first_name" 
                                           value="{{ old('first_name', $doctor->user->first_name ?? '') }}" required>
                                    @error('first_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="last_name" class="form-label">Nom</label>
                                    <input type="text" class="form-control @error('last_name') is-invalid @enderror" 
                                           id="last_name" name="last_name" 
                                           value="{{ old('last_name', $doctor->user->last_name ?? '') }}" required>
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
                                           value="{{ old('email', $doctor->user->email ?? '') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="phone" class="form-label">Téléphone</label>
                                    <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" name="phone" 
                                           value="{{ old('phone', $doctor->user->phone ?? '') }}">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="speciality" class="form-label">Spécialité</label>
                                    <input type="text" class="form-control @error('speciality') is-invalid @enderror" 
                                           id="speciality" name="speciality" 
                                           value="{{ old('speciality', $doctor->speciality ?? '') }}" required>
                                    @error('speciality')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="hospital" class="form-label">Hôpital</label>
                                    <input type="text" class="form-control @error('hospital') is-invalid @enderror" 
                                           id="hospital" name="hospital" 
                                           value="{{ old('hospital', $doctor->hospital ?? '') }}" required>
                                    @error('hospital')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="biography" class="form-label">Biographie</label>
                                <textarea class="form-control @error('biography') is-invalid @enderror" 
                                          id="biography" name="biography" rows="4" 
                                          placeholder="Décrivez votre parcours, vos spécialisations, votre expérience...">{{ old('biography', $doctor->biography ?? '') }}</textarea>
                                @error('biography')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Enregistrer les modifications
                                </button>
                                <a href="{{ route('doctor.dashboard') }}" class="btn btn-secondary">
                                    <i class="fas fa-times me-2"></i>Annuler
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
