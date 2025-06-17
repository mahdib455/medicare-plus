<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard Patient - MediCare+</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #059669;
            --secondary-color: #64748b;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --info-color: #3b82f6;
            --light-bg: #f8fafc;
            --patient-gradient: linear-gradient(135deg, #10b981 0%, #059669 100%);
            --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --card-shadow-hover: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            min-height: 100vh;
        }

        .main-container {
            background: var(--light-bg);
            min-height: 100vh;
            border-radius: 20px 20px 0 0;
            margin-top: 2rem;
            box-shadow: var(--card-shadow);
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

        .stats-card {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
            border: none;
            height: 100%;
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--card-shadow-hover);
        }

        .stats-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .appointment-card {
            background: white;
            border-radius: 16px;
            box-shadow: var(--card-shadow);
            border: none;
            margin-bottom: 1.5rem;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .appointment-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--card-shadow-hover);
        }

        .card-header-custom {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            border-bottom: 2px solid #e2e8f0;
            padding: 1.5rem;
            font-weight: 600;
        }

        .btn-action {
            border-radius: 8px;
            padding: 0.5rem 1rem;
            font-weight: 500;
            transition: all 0.3s ease;
            border: none;
            margin: 0.2rem;
        }

        .btn-action:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .doctor-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: var(--patient-gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            margin-right: 1rem;
        }

        .appointment-row {
            padding: 1.5rem;
            border-bottom: 1px solid #f1f5f9;
            transition: background-color 0.3s ease;
        }

        .appointment-row:hover {
            background-color: #f8fafc;
        }

        .appointment-row:last-child {
            border-bottom: none;
        }

        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .modal-content {
            border-radius: 16px;
            border: none;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }

        .modal-header {
            border-bottom: 2px solid #f1f5f9;
            padding: 1.5rem;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            border-radius: 16px 16px 0 0;
        }

        .form-control, .form-select {
            border-radius: 8px;
            border: 2px solid #e2e8f0;
            padding: 0.75rem;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1);
        }

        .doctor-item {
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .doctor-item:hover {
            background-color: #f8fafc !important;
            border-color: var(--primary-color) !important;
        }

        .empty-state {
            text-align: center;
            padding: 3rem;
            color: var(--secondary-color);
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .consultation-notes {
            background: #f8fafc;
            border-radius: 6px;
            padding: 0.75rem;
            border-left: 3px solid #3b82f6;
            margin-top: 0.5rem;
        }

        .consultation-notes small {
            line-height: 1.4;
        }

        .urgency-badge {
            padding: 0.25rem 0.5rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 500;
            text-transform: uppercase;
        }

        .urgency-low { background: #dcfce7; color: #166534; }
        .urgency-medium { background: #fef3c7; color: #92400e; }
        .urgency-high { background: #fee2e2; color: #991b1b; }
        .urgency-critical { background: #fecaca; color: #7f1d1d; }

        .urgency-success { background: #dcfce7; color: #166534; }
        .urgency-info { background: #dbeafe; color: #1e40af; }
        .urgency-warning { background: #fef3c7; color: #92400e; }
        .urgency-danger { background: #fee2e2; color: #991b1b; }

        .category-tag {
            background: #e0e7ff;
            color: #3730a3;
            padding: 0.2rem 0.4rem;
            border-radius: 4px;
            font-size: 0.7rem;
            margin-right: 0.3rem;
            display: inline-block;
        }

        .categories-section {
            background: #f8fafc;
            border-radius: 6px;
            padding: 0.5rem;
            margin-top: 0.5rem;
        }

        /* Rating Stars Styles */
        .rating-stars {
            font-size: 2rem;
            color: #e5e7eb;
            cursor: pointer;
        }

        .rating-stars .star {
            transition: color 0.2s ease;
            margin: 0 0.1rem;
        }

        .rating-stars .star:hover,
        .rating-stars .star.active {
            color: #fbbf24;
        }

        .rating-stars .star.active {
            color: #f59e0b;
        }

        .review-item {
            background: #f8fafc;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 0.5rem;
            border-left: 4px solid #fbbf24;
        }

        .review-stars {
            color: #f59e0b;
            font-size: 0.9rem;
        }

        .reviewable-item {
            background: #fef3c7;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 0.5rem;
            border-left: 4px solid #f59e0b;
        }

        .completed-item {
            background: #f0fdf4;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 0.5rem;
            border-left: 4px solid #22c55e;
            transition: all 0.3s ease;
        }

        .completed-item:hover {
            background: #dcfce7;
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(34, 197, 94, 0.2);
        }

        .completed-badge {
            background: #22c55e;
            color: white;
            padding: 0.25rem 0.5rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .review-status {
            font-size: 0.8rem;
            padding: 0.2rem 0.5rem;
            border-radius: 8px;
            font-weight: 500;
        }

        .review-status.reviewed {
            background: #d1fae5;
            color: #065f46;
        }

        .review-status.pending {
            background: #fef3c7;
            color: #92400e;
        }

        /* Appointment Selection Modal Styles */
        .appointment-selection-item {
            background: #f8fafc;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 0.5rem;
            border: 2px solid transparent;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .appointment-selection-item:hover {
            border-color: #f59e0b;
            background: #fef3c7;
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(245, 158, 11, 0.2);
        }

        .appointment-selection-item.reviewed {
            background: #f0fdf4;
            border-color: #22c55e;
        }

        .appointment-selection-item.reviewed:hover {
            background: #dcfce7;
            border-color: #16a34a;
        }

        .review-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
            border-radius: 12px;
            font-weight: 500;
        }

        .review-badge.reviewed {
            background: #d1fae5;
            color: #065f46;
        }

        .review-badge.not-reviewed {
            background: #fef3c7;
            color: #92400e;
        }

        /* My Reviews Section Styles */
        .my-review-item {
            background: #f8fafc;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 0.5rem;
            border-left: 4px solid #3730a3;
        }

        .review-stars-display {
            color: #f59e0b;
            font-size: 1rem;
        }

        .sidebar-card {
            background: white;
            border-radius: 16px;
            box-shadow: var(--card-shadow);
            border: none;
            margin-bottom: 1.5rem;
            overflow: hidden;
        }

        .next-appointment {
            background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
            border-radius: 12px;
            padding: 1rem;
            margin-top: 1rem;
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
            z-index: 100;
        }

        .header-section .dropdown {
            position: relative;
            z-index: 1051;
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

            .btn-action {
                font-size: 0.875rem;
                padding: 0.4rem 0.8rem;
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
                    <div class="col-md-1">
                        <x-logo size="small" :showText="false" class="justify-content-start" />
                    </div>
                    <div class="col-md-7">
                        <div class="patient-info">
                            <h1 class="mb-2">
                                <i class="fas fa-heart me-3"></i>
                                Bonjour, {{ $user->full_name }}
                            </h1>
                            <p class="mb-0 opacity-75">
                                <i class="fas fa-user-circle me-2"></i>Espace Patient
                                <span class="mx-3">•</span>
                                <i class="fas fa-shield-alt me-2"></i>Vos données sont sécurisées
                            </p>
                        </div>
                    </div>
                    <div class="col-md-4 text-end">
                        <div class="d-flex justify-content-end align-items-center">
                            <div class="me-3">
                                <small class="opacity-75">Dernière visite</small><br>
                                <span class="fw-semibold">{{ now()->format('d/m/Y à H:i') }}</span>
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-user-circle me-2"></i>Menu
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="{{ route('patient.profile') }}"><i class="fas fa-user-edit me-2"></i>My Profile</a></li>
                                    <li><a class="dropdown-item" href="{{ route('patient.prescriptions') }}"><i class="fas fa-pills me-2"></i>My Prescriptions</a></li>
                                    <li><a class="dropdown-item" href="#"><i class="fas fa-file-medical me-2"></i>Medical Records</a></li>
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
            <div class="row">
                <!-- Sidebar -->
                <div class="col-lg-3 col-md-4 mb-4">
                    <div class="sidebar-card">
                        <div class="card-header-custom">
                            <h6 class="mb-0">
                                <i class="fas fa-user me-2"></i>Mes Informations
                            </h6>
                        </div>
                        <div class="p-3">
                            <div class="text-center mb-3">
                                <div class="doctor-avatar mx-auto mb-2" style="width: 80px; height: 80px; font-size: 2rem;">
                                    {{ substr($user->full_name, 0, 1) }}
                                </div>
                                <h6 class="mb-1">{{ $user->full_name }}</h6>
                                <span class="status-badge" style="background: var(--primary-color); color: white;">
                                    <i class="fas fa-user me-1"></i>Patient
                                </span>
                            </div>
                            <div class="small">
                                <p class="mb-2">
                                    <i class="fas fa-envelope text-muted me-2"></i>
                                    {{ $user->email }}
                                </p>
                                <p class="mb-0">
                                    <i class="fas fa-phone text-muted me-2"></i>
                                    {{ $user->phone ?? 'Non renseigné' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- AI Symptom Checker -->
                    @if(isset($symptomChecks) && $symptomChecks->count() > 0)
                    <div class="sidebar-card">
                        <div class="card-header-custom" style="background: linear-gradient(135deg, #ddd6fe 0%, #c4b5fd 100%); color: #5b21b6;">
                            <h6 class="mb-0">
                                <i class="fas fa-brain me-2"></i>AI Symptom Analysis
                                <span class="status-badge" style="background: #5b21b6; color: white; font-size: 0.7rem;">{{ $symptomChecks->count() }}</span>
                            </h6>
                        </div>
                        <div class="p-3">
                            @php $latestCheck = $symptomChecks->first() @endphp
                            <div class="text-center mb-3">
                                <div class="doctor-avatar mx-auto mb-2" style="width: 50px; height: 50px; font-size: 1.2rem; background: #5b21b6;">
                                    <i class="fas fa-robot"></i>
                                </div>
                                <h6 class="mb-1" style="color: #5b21b6;">Latest Analysis</h6>
                                <small class="text-muted">{{ $latestCheck->formatted_date }}</small>
                            </div>
                            <div class="small">
                                <p class="mb-2">
                                    <i class="fas fa-stethoscope text-muted me-2"></i>
                                    <strong>Symptoms:</strong> {{ Str::limit($latestCheck->short_symptom_text, 40) }}
                                </p>
                                <p class="mb-2">
                                    <i class="fas fa-diagnoses text-muted me-2"></i>
                                    <strong>Result:</strong> {{ Str::limit($latestCheck->short_result, 40) }}
                                </p>
                                <div class="text-center">
                                    <span class="urgency-badge urgency-{{ $latestCheck->urgency_color }}" style="font-size: 0.75rem;">
                                        {{ $latestCheck->urgency_level_text }}
                                    </span>
                                </div>
                            </div>
                            <div class="d-grid gap-2 mt-3">
                                <a href="{{ route('ai.symptom-analyzer') }}" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-brain me-2"></i>New Analysis
                                </a>
                                <button class="btn btn-outline-secondary btn-sm" onclick="showAllSymptomChecks()">
                                    <i class="fas fa-history me-2"></i>View All ({{ $symptomChecks->count() }})
                                </button>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="sidebar-card">
                        <div class="card-header-custom" style="background: linear-gradient(135deg, #ddd6fe 0%, #c4b5fd 100%); color: #5b21b6;">
                            <h6 class="mb-0">
                                <i class="fas fa-brain me-2"></i>AI Symptom Checker
                            </h6>
                        </div>
                        <div class="p-3">
                            <div class="text-center">
                                <div class="doctor-avatar mx-auto mb-2" style="width: 50px; height: 50px; font-size: 1.2rem; background: #5b21b6;">
                                    <i class="fas fa-brain"></i>
                                </div>
                                <h6 class="mb-1" style="color: #5b21b6;">AI Health Assistant</h6>
                                <p class="mb-3 small text-muted">Get instant health insights with our AI-powered symptom checker.</p>
                                <a href="{{ route('ai.symptom-analyzer') }}" class="btn btn-primary btn-sm w-100">
                                    <i class="fas fa-brain me-2"></i>Start Analysis
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="sidebar-card">
                        <div class="card-header-custom">
                            <h6 class="mb-0">
                                <i class="fas fa-calendar-check me-2"></i>Prochain RDV
                            </h6>
                        </div>
                        <div class="p-3">
                            @if($upcomingAppointments->count() > 0)
                                @php $nextAppointment = $upcomingAppointments->first() @endphp
                                <div class="next-appointment">
                                    <div class="text-center">
                                        <h5 class="text-success mb-1">{{ $nextAppointment->appointment_date->format('d/m/Y') }}</h5>
                                        <p class="mb-2">{{ $nextAppointment->appointment_date->format('H:i') }}</p>
                                        <div class="d-flex align-items-center justify-content-center mb-2">
                                            <div class="doctor-avatar" style="width: 30px; height: 30px; font-size: 0.8rem;">
                                                {{ substr($nextAppointment->doctor->user->full_name, 0, 1) }}
                                            </div>
                                            <small class="ms-2">Dr. {{ $nextAppointment->doctor->user->full_name }}</small>
                                        </div>
                                        <span class="status-badge bg-{{ $nextAppointment->status === 'confirmed' ? 'success' : 'warning' }}">
                                            {{ $nextAppointment->status === 'confirmed' ? 'Confirmé' : 'En attente' }}
                                        </span>
                                    </div>
                                </div>
                            @else
                                <div class="text-center py-3">
                                    <i class="fas fa-calendar-plus fa-2x text-muted mb-2"></i>
                                    <p class="text-muted mb-0">Aucun RDV programmé</p>
                                </div>
                            @endif
                            <button class="btn btn-success w-100 mt-3" data-bs-toggle="modal" data-bs-target="#appointmentModal">
                                <i class="fas fa-plus me-2"></i>Prendre RDV
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Main Dashboard Content -->
                <div class="col-lg-9 col-md-8">

                    <!-- Stats Cards -->
                    <div class="row mb-4">
                        <div class="col-lg-3 col-md-6 mb-3">
                            <div class="stats-card">
                                <div class="d-flex align-items-center">
                                    <div class="stats-icon" style="background: var(--patient-gradient); color: white;">
                                        <i class="fas fa-calendar-alt"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h3 class="mb-1 fw-bold text-success">{{ $upcomingAppointments->count() + $recentAppointments->count() }}</h3>
                                        <p class="mb-0 text-muted">Mes Rendez-vous</p>
                                        <small class="text-success">
                                            <i class="fas fa-calendar-check"></i> {{ $upcomingAppointments->count() }} à venir
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-3">
                            <div class="stats-card">
                                <div class="d-flex align-items-center">
                                    <div class="stats-icon" style="background: linear-gradient(135deg, var(--info-color), #60a5fa); color: white;">
                                        <i class="fas fa-stethoscope"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h3 class="mb-1 fw-bold text-info">{{ $recentAppointments->where('status', 'completed')->count() }}</h3>
                                        <p class="mb-0 text-muted">Consultations</p>
                                        <small class="text-info">
                                            <i class="fas fa-check-circle"></i> Terminées
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-3">
                            <div class="stats-card">
                                <div class="d-flex align-items-center">
                                    <div class="stats-icon" style="background: linear-gradient(135deg, var(--warning-color), #fbbf24); color: white;">
                                        <i class="fas fa-prescription-bottle-alt"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h3 class="mb-1 fw-bold text-warning">{{ $prescriptions->count() }}</h3>
                                        <p class="mb-0 text-muted">Prescriptions</p>
                                        <small class="text-warning">
                                            <i class="fas fa-pills"></i> {{ $prescriptions->where('status', 'active')->count() }} Actives
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-3">
                            <div class="stats-card">
                                <div class="d-flex align-items-center">
                                    <div class="stats-icon" style="background: linear-gradient(135deg, var(--danger-color), #f87171); color: white;">
                                        <i class="fas fa-bell"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h3 class="mb-1 fw-bold text-danger">0</h3>
                                        <p class="mb-0 text-muted">Rappels</p>
                                        <small class="text-danger">
                                            <i class="fas fa-exclamation-circle"></i> Importants
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Content Area -->
                    <div class="row">
                        <div class="col-lg-8 mb-4">
                            <!-- Rendez-vous à venir -->
                            @if($upcomingAppointments->count() > 0)
                            <div class="appointment-card">
                                <div class="card-header-custom" style="background: linear-gradient(135d, #dbeafe 0%, #bfdbfe 100%); color: #1e40af;">
                                    <h5 class="mb-0">
                                        <i class="fas fa-clock me-2"></i>
                                        Rendez-vous à venir
                                        <span class="status-badge" style="background: var(--info-color); color: white;">{{ $upcomingAppointments->count() }}</span>
                                    </h5>
                                </div>
                                <div class="p-0">
                                    @foreach($upcomingAppointments as $appointment)
                                    <div class="appointment-row">
                                        <div class="row align-items-center">
                                            <div class="col-md-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="doctor-avatar">
                                                        {{ substr($appointment->doctor->user->full_name, 0, 1) }}
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-1 fw-semibold">Dr. {{ $appointment->doctor->user->full_name }}</h6>
                                                        <small class="text-muted">{{ $appointment->doctor->speciality ?? 'Médecin' }}</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="text-center">
                                                    <div class="fw-semibold text-primary">{{ $appointment->appointment_date->format('d/m/Y') }}</div>
                                                    <small class="text-muted">{{ $appointment->appointment_date->format('H:i') }}</small>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <span class="status-badge bg-{{ $appointment->status === 'confirmed' ? 'success' : 'warning' }}">
                                                    <i class="fas fa-{{ $appointment->status === 'confirmed' ? 'check-circle' : 'clock' }} me-1"></i>
                                                    {{ $appointment->status === 'confirmed' ? 'Confirmé' : 'En attente' }}
                                                </span>
                                            </div>
                                            <div class="col-md-3">
                                                <p class="mb-0">{{ Str::limit($appointment->reason, 40) ?: 'Consultation générale' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            <!-- Historique des rendez-vous -->
                            @if($recentAppointments->count() > 0)
                            <div class="appointment-card">
                                <div class="card-header-custom">
                                    <h5 class="mb-0">
                                        <i class="fas fa-history me-2"></i>
                                        Historique des consultations
                                        <span class="status-badge" style="background: var(--secondary-color); color: white;">{{ $recentAppointments->count() }}</span>
                                    </h5>
                                </div>
                                <div class="p-0">
                                    @foreach($recentAppointments->take(5) as $appointment)
                                    <div class="appointment-row">
                                        <div class="row align-items-center">
                                            <div class="col-md-4">
                                                <div class="d-flex align-items-center">
                                                    <div class="doctor-avatar" style="background: var(--secondary-color);">
                                                        {{ substr($appointment->doctor->user->full_name, 0, 1) }}
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-1">Dr. {{ $appointment->doctor->user->full_name }}</h6>
                                                        <small class="text-muted">{{ $appointment->appointment_date->format('d/m/Y à H:i') }}</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <span class="status-badge bg-{{ $appointment->status === 'completed' ? 'success' : 'danger' }}">
                                                    <i class="fas fa-{{ $appointment->status === 'completed' ? 'check-double' : 'times' }} me-1"></i>
                                                    {{ $appointment->status === 'completed' ? 'Terminé' : 'Annulé' }}
                                                </span>
                                            </div>
                                            <div class="col-md-5">
                                                <p class="mb-0">{{ Str::limit($appointment->reason, 50) ?: 'Consultation générale' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                    @if($recentAppointments->count() > 5)
                                    <div class="text-center p-3">
                                        <button class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-eye me-2"></i>Voir tout l'historique
                                        </button>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @endif

                            <!-- État vide -->
                            @if($upcomingAppointments->count() == 0 && $recentAppointments->count() == 0)
                            <div class="appointment-card">
                                <div class="empty-state">
                                    <i class="fas fa-calendar-plus"></i>
                                    <h4 class="mt-3 mb-2">Commencez votre suivi médical</h4>
                                    <p class="mb-4">Prenez votre premier rendez-vous avec un professionnel de santé.</p>
                                    <button class="btn btn-success btn-lg" data-bs-toggle="modal" data-bs-target="#appointmentModal">
                                        <i class="fas fa-plus me-2"></i>Prendre mon premier RDV
                                    </button>
                                </div>
                            </div>
                            @endif
                        </div>

                        <div class="col-lg-4">
                            <!-- Prescriptions actives -->
                            @if(isset($prescriptions) && $prescriptions->count() > 0)
                            <div class="appointment-card">
                                <div class="card-header-custom" style="background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); color: #92400e;">
                                    <h5 class="mb-0">
                                        <i class="fas fa-pills me-2"></i>
                                        Mes Prescriptions
                                        <span class="status-badge" style="background: #92400e; color: white;">{{ $prescriptions->count() }}</span>
                                    </h5>
                                </div>
                                <div class="p-0">
                                    @foreach($prescriptions->take(5) as $prescription)
                                    <div class="appointment-row">
                                        <div class="row align-items-center">
                                            <div class="col-md-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="doctor-avatar" style="background: #92400e;">
                                                        {{ substr($prescription->appointment->doctor->user->full_name ?? 'Dr', 0, 1) }}
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-1">Dr. {{ $prescription->appointment->doctor->user->full_name ?? 'Docteur' }}</h6>
                                                        <small class="text-muted">{{ $prescription->prescribed_at->format('d/m/Y à H:i') }}</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div>
                                                    <h6 class="mb-1 text-warning">Médicaments</h6>
                                                    <p class="mb-0 small">{{ $prescription->lines->count() }} médicament(s) prescrit(s)</p>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <span class="status-badge bg-{{ $prescription->status === 'active' ? 'success' : ($prescription->status === 'completed' ? 'primary' : 'secondary') }}">
                                                    <i class="fas fa-{{ $prescription->status === 'active' ? 'check-circle' : ($prescription->status === 'completed' ? 'flag-checkered' : 'clock') }} me-1"></i>
                                                    {{ ucfirst($prescription->status) }}
                                                </span>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="d-flex gap-1">
                                                    <a href="{{ route('patient.prescriptions.print', $prescription->id) }}" class="btn btn-outline-primary btn-sm" target="_blank" title="Imprimer">
                                                        <i class="fas fa-print"></i>
                                                    </a>
                                                    <a href="{{ route('patient.prescriptions.download', $prescription->id) }}" class="btn btn-outline-success btn-sm" title="Télécharger">
                                                        <i class="fas fa-download"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        @if($prescription->notes)
                                        <div class="row mt-2">
                                            <div class="col-12">
                                                <div class="consultation-notes">
                                                    <small class="text-muted">
                                                        <i class="fas fa-sticky-note me-1"></i>
                                                        <strong>Notes:</strong> {{ Str::limit($prescription->notes, 100) }}
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                    @endforeach

                                    @if($prescriptions->count() > 5)
                                    <div class="text-center p-3 border-top">
                                        <a href="{{ route('patient.prescriptions') }}" class="btn btn-outline-warning btn-sm">
                                            <i class="fas fa-plus me-2"></i>Voir toutes les prescriptions ({{ $prescriptions->count() - 5 }} de plus)
                                        </a>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @else
                            <div class="appointment-card">
                                <div class="card-header-custom" style="background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); color: #92400e;">
                                    <h6 class="mb-0">
                                        <i class="fas fa-pills me-2"></i>Mes Prescriptions
                                    </h6>
                                </div>
                                <div class="p-3">
                                    <div class="empty-state py-3">
                                        <i class="fas fa-prescription-bottle-alt" style="font-size: 2.5rem; color: #92400e;"></i>
                                        <h6 class="mt-2 mb-1">Aucune prescription</h6>
                                        <p class="mb-0 small">Vos prescriptions apparaîtront ici après vos consultations.</p>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- Consultations détaillées -->
                            @if(isset($consultations) && $consultations->count() > 0)
                            <div class="appointment-card">
                                <div class="card-header-custom" style="background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%); color: #1e40af;">
                                    <h5 class="mb-0">
                                        <i class="fas fa-stethoscope me-2"></i>
                                        Mes Consultations
                                        <span class="status-badge" style="background: #1e40af; color: white;">{{ $consultations->count() }}</span>
                                    </h5>
                                </div>
                                <div class="p-0">
                                    @foreach($consultations->take(5) as $consultation)
                                    <div class="appointment-row">
                                        <div class="row align-items-center">
                                            <div class="col-md-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="doctor-avatar" style="background: #1e40af;">
                                                        {{ substr($consultation->appointment->doctor->user->full_name ?? 'Dr', 0, 1) }}
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-1">Dr. {{ $consultation->appointment->doctor->user->full_name ?? 'Docteur' }}</h6>
                                                        <small class="text-muted">{{ $consultation->consultation_date->format('d/m/Y à H:i') }}</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div>
                                                    <h6 class="mb-1 text-primary">Diagnostic</h6>
                                                    <p class="mb-0 small">{{ Str::limit($consultation->diagnosis, 60) ?: 'Diagnostic non spécifié' }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div>
                                                    <h6 class="mb-1 text-success">Traitement</h6>
                                                    <p class="mb-0 small">{{ Str::limit($consultation->treatment, 60) ?: 'Traitement non spécifié' }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                <button class="btn btn-outline-primary btn-sm" onclick="showConsultationDetails({{ $consultation->id }})">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                        </div>
                                        @if($consultation->notes)
                                        <div class="row mt-2">
                                            <div class="col-12">
                                                <div class="consultation-notes">
                                                    <small class="text-muted">
                                                        <i class="fas fa-sticky-note me-1"></i>
                                                        <strong>Notes:</strong> {{ Str::limit($consultation->notes, 100) }}
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                    @endforeach

                                    @if($consultations->count() > 5)
                                    <div class="text-center p-3 border-top">
                                        <button class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-plus me-2"></i>Voir toutes les consultations ({{ $consultations->count() - 5 }} de plus)
                                        </button>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @else
                            <div class="appointment-card">
                                <div class="card-header-custom" style="background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%); color: #1e40af;">
                                    <h6 class="mb-0">
                                        <i class="fas fa-stethoscope me-2"></i>Mes Consultations
                                    </h6>
                                </div>
                                <div class="p-3">
                                    <div class="empty-state py-3">
                                        <i class="fas fa-stethoscope" style="font-size: 2.5rem; color: #1e40af;"></i>
                                        <h6 class="mt-2 mb-1">Aucune consultation</h6>
                                        <p class="mb-0 small">Vos consultations apparaîtront ici après vos rendez-vous.</p>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- Add Review Section -->
                            <div class="appointment-card">
                                <div class="card-header-custom" style="background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); color: #92400e;">
                                    <h5 class="mb-0">
                                        <i class="fas fa-star me-2"></i>
                                        Ajouter une Évaluation
                                    </h5>
                                </div>
                                <div class="p-3 text-center">
                                    <i class="fas fa-star fa-3x text-warning mb-3"></i>
                                    <h6 class="text-dark mb-3">Évaluez vos consultations</h6>
                                    <p class="text-muted small mb-3">Partagez votre expérience avec vos docteurs</p>
                                    <button class="btn btn-warning" id="addReviewBtn" onclick="openAppointmentSelectionModal()">
                                        <i class="fas fa-plus me-2"></i>Ajouter une Évaluation
                                    </button>
                                </div>
                            </div>

                            <!-- My Reviews Section -->
                            <div class="appointment-card">
                                <div class="card-header-custom" style="background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%); color: #3730a3;">
                                    <h5 class="mb-0">
                                        <i class="fas fa-history me-2"></i>
                                        Mes Évaluations
                                        <span class="status-badge" style="background: #3730a3; color: white;" id="myReviewsCount">0</span>
                                    </h5>
                                </div>
                                <div class="p-3">
                                    <!-- My Reviews List -->
                                    <div id="myReviewsList" style="display: none;">
                                        <!-- Reviews will be loaded here -->
                                    </div>

                                    <!-- Empty State -->
                                    <div id="myReviewsEmptyState" class="text-center py-4">
                                        <i class="fas fa-star fa-3x text-muted mb-3"></i>
                                        <h6 class="text-muted">Aucune évaluation</h6>
                                        <p class="text-muted small mb-0">Vos évaluations apparaîtront ici après avoir évalué vos consultations.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Appointment Modal -->
    <div class="modal fade" id="appointmentModal" tabindex="-1" aria-labelledby="appointmentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="appointmentModalLabel">
                        <i class="fas fa-calendar-plus"></i> Demander un rendez-vous
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="appointmentForm">
                        @csrf
                        <!-- Doctor Selection -->
                        <div class="mb-3">
                            <label for="doctorSearch" class="form-label">Rechercher un docteur</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="doctorSearch" placeholder="Nom du docteur ou spécialité...">
                                <button class="btn btn-outline-secondary" type="button" id="searchBtn">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Filters -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="specialityFilter" class="form-label">Spécialité</label>
                                <input type="text" class="form-control" id="specialityFilter" placeholder="Ex: Cardiologie">
                            </div>
                            <div class="col-md-6">
                                <label for="hospitalFilter" class="form-label">Hôpital</label>
                                <input type="text" class="form-control" id="hospitalFilter" placeholder="Ex: CHU Mohammed VI">
                            </div>
                        </div>

                        <!-- Doctor Selection Results -->
                        <div class="mb-3">
                            <label class="form-label">Sélectionner un docteur</label>
                            <div id="doctorsList" class="border rounded p-3" style="max-height: 300px; overflow-y: auto;">
                                <div class="text-center text-muted">
                                    <i class="fas fa-search fa-2x mb-2"></i>
                                    <p>Utilisez la recherche pour trouver un docteur</p>
                                </div>
                            </div>
                            <input type="hidden" id="selectedDoctorId" name="doctor_id" required>
                        </div>

                        <!-- Date Selection -->
                        <div class="mb-3">
                            <label for="appointmentDate" class="form-label">Date et heure souhaitées</label>
                            <input type="datetime-local" class="form-control" id="appointmentDate" name="appointment_date" required>
                        </div>

                        <!-- Reason -->
                        <div class="mb-3">
                            <label for="reason" class="form-label">Motif de consultation (optionnel)</label>
                            <textarea class="form-control" id="reason" name="reason" rows="3" placeholder="Décrivez brièvement le motif de votre consultation..."></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-success" id="submitAppointment">
                        <i class="fas fa-calendar-check"></i> Demander le rendez-vous
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Détails Consultation -->
    <div class="modal fade" id="consultationDetailsModal" tabindex="-1" aria-labelledby="consultationDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%); color: white;">
                    <h5 class="modal-title" id="consultationDetailsModalLabel">
                        <i class="fas fa-stethoscope me-2"></i>Détails de la Consultation
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="consultationDetailsContent">
                    <!-- Le contenu sera chargé dynamiquement -->
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Chargement...</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Appointment Selection Modal -->
    <div class="modal fade" id="appointmentSelectionModal" tabindex="-1" aria-labelledby="appointmentSelectionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="appointmentSelectionModalLabel">
                        <i class="fas fa-stethoscope"></i> Sélectionner une Consultation à Évaluer
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <p class="text-muted">Choisissez la consultation que vous souhaitez évaluer :</p>
                    </div>

                    <!-- Loading State -->
                    <div id="appointmentsLoading" class="text-center py-4">
                        <i class="fas fa-spinner fa-spin fa-2x text-primary mb-3"></i>
                        <p class="text-muted">Chargement de vos rendez-vous...</p>
                    </div>

                    <!-- Appointments List -->
                    <div id="appointmentsList" style="display: none;">
                        <!-- Appointments will be loaded here -->
                    </div>

                    <!-- Empty State -->
                    <div id="appointmentsEmptyState" style="display: none;" class="text-center py-4">
                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                        <h6 class="text-muted">Aucun rendez-vous trouvé</h6>
                        <p class="text-muted small">Vous n'avez pas encore de rendez-vous à évaluer.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Review Modal -->
    <div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reviewModalLabel">
                        <i class="fas fa-star"></i> Évaluer votre consultation
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="reviewForm">
                        @csrf
                        <input type="hidden" id="reviewAppointmentId" name="appointment_id">

                        <!-- Doctor Info -->
                        <div class="text-center mb-4">
                            <div class="doctor-avatar mx-auto mb-2" style="width: 60px; height: 60px; font-size: 1.5rem;" id="reviewDoctorAvatar">
                                Dr
                            </div>
                            <h6 id="reviewDoctorName">Dr. Nom du Docteur</h6>
                            <small class="text-muted" id="reviewAppointmentDate">Date du rendez-vous</small>
                        </div>

                        <!-- Rating -->
                        <div class="mb-3">
                            <label class="form-label">Note globale *</label>
                            <div class="text-center">
                                <div class="rating-stars" id="ratingStars">
                                    <i class="fas fa-star star" data-rating="1"></i>
                                    <i class="fas fa-star star" data-rating="2"></i>
                                    <i class="fas fa-star star" data-rating="3"></i>
                                    <i class="fas fa-star star" data-rating="4"></i>
                                    <i class="fas fa-star star" data-rating="5"></i>
                                </div>
                                <div class="mt-2">
                                    <span id="ratingText" class="text-muted">Cliquez sur les étoiles pour noter</span>
                                </div>
                            </div>
                            <input type="hidden" id="rating" name="rating" required>
                        </div>

                        <!-- Comment -->
                        <div class="mb-3">
                            <label for="comment" class="form-label">Commentaire (optionnel)</label>
                            <textarea class="form-control" id="comment" name="comment" rows="4"
                                placeholder="Partagez votre expérience avec ce docteur..."></textarea>
                            <div class="form-text">Votre commentaire sera anonyme pour le docteur.</div>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid">
                            <button type="submit" class="btn btn-warning" id="submitReview">
                                <i class="fas fa-paper-plane me-2"></i>Soumettre l'évaluation
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <script>
        // Reviews Management
        let currentRating = 0;
        let reviewModal;
        let appointmentSelectionModal;

        document.addEventListener('DOMContentLoaded', function() {
            console.log('🚀 DOM Content Loaded - Initializing modals...');

            const reviewModalEl = document.getElementById('reviewModal');
            const appointmentSelectionModalEl = document.getElementById('appointmentSelectionModal');

            console.log('📋 Modal elements check:', {
                reviewModal: !!reviewModalEl,
                appointmentSelectionModal: !!appointmentSelectionModalEl
            });

            if (reviewModalEl) {
                reviewModal = new bootstrap.Modal(reviewModalEl);
                console.log('✅ Review modal initialized');
            } else {
                console.error('❌ Review modal element not found');
            }

            if (appointmentSelectionModalEl) {
                appointmentSelectionModal = new bootstrap.Modal(appointmentSelectionModalEl);
                console.log('✅ Appointment selection modal initialized');
            } else {
                console.error('❌ Appointment selection modal element not found');
            }

            loadMyReviews();
            initializeRatingStars();
        });

        // Open appointment selection modal
        function openAppointmentSelectionModal() {
            console.log('🔄 Opening appointment selection modal...');

            if (!appointmentSelectionModal) {
                console.error('❌ Appointment selection modal not initialized!');
                alert('Erreur: Modal non initialisé. Veuillez recharger la page.');
                return;
            }

            console.log('✅ Modal found, showing...');
            appointmentSelectionModal.show();
            loadAllAppointments();
        }

        // Load all consultations for selection
        async function loadAllAppointments() {
            try {
                console.log('🔄 Loading all consultations for selection...');

                // Show loading state
                const loadingEl = document.getElementById('appointmentsLoading');
                const listEl = document.getElementById('appointmentsList');
                const emptyEl = document.getElementById('appointmentsEmptyState');

                console.log('📋 DOM Elements check:', {
                    loading: !!loadingEl,
                    list: !!listEl,
                    empty: !!emptyEl
                });

                if (loadingEl) loadingEl.style.display = 'block';
                if (listEl) listEl.style.display = 'none';
                if (emptyEl) emptyEl.style.display = 'none';

                console.log('🌐 Making API call to /api/all-consultations...');
                const response = await axios.get('/api/all-consultations');
                console.log('📊 All consultations response:', response.data);
                console.log('📊 Response status:', response.status);
                console.log('📊 Consultations count:', response.data.consultations?.length || 0);

                if (response.data.success && response.data.consultations) {
                    console.log('✅ Success! Processing', response.data.consultations.length, 'consultations');
                    displayConsultationSelection(response.data.consultations);
                } else {
                    console.log('❌ No consultations or success=false');
                    showAppointmentsEmptyState();
                }
            } catch (error) {
                console.error('❌ Error loading consultations:', error);
                console.error('❌ Error details:', error.response?.data);
                console.error('❌ Error status:', error.response?.status);
                showAppointmentsEmptyState();
            }
        }

        // Display consultations for selection
        function displayConsultationSelection(consultations) {
            console.log('🎨 Displaying consultation selection for', consultations.length, 'consultations');
            console.log('🎨 Consultations data:', consultations);

            const appointmentsList = document.getElementById('appointmentsList');
            const appointmentsLoading = document.getElementById('appointmentsLoading');
            const appointmentsEmptyState = document.getElementById('appointmentsEmptyState');

            console.log('📋 Display DOM Elements check:', {
                loading: !!appointmentsLoading,
                list: !!appointmentsList,
                empty: !!appointmentsEmptyState
            });

            if (appointmentsLoading) appointmentsLoading.style.display = 'none';

            if (consultations.length > 0) {
                console.log('✅ Displaying', consultations.length, 'consultations');
                appointmentsList.style.display = 'block';
                appointmentsEmptyState.style.display = 'none';

                appointmentsList.innerHTML = consultations.map(consultation => `
                    <div class="appointment-selection-item ${consultation.is_reviewed ? 'reviewed' : ''}"
                         onclick="selectConsultationForReview(${consultation.id}, '${consultation.doctor.name}', '${consultation.formatted_date}', ${consultation.is_reviewed})">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <div class="doctor-avatar me-3" style="width: 40px; height: 40px; font-size: 1rem;">
                                        ${consultation.doctor.name.charAt(0)}
                                    </div>
                                    <div>
                                        <h6 class="mb-1">Dr. ${consultation.doctor.name}</h6>
                                        <small class="text-muted">${consultation.doctor.speciality}</small>
                                        <div class="mt-1">
                                            <small class="text-primary">
                                                <i class="fas fa-calendar me-1"></i>${consultation.formatted_date}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center">
                                    <span class="badge bg-success mb-1">Consultation</span>
                                    ${consultation.has_prescription ? '<br><small class="text-info"><i class="fas fa-pills me-1"></i>Prescription</small>' : ''}
                                    <br><small class="text-success"><i class="fas fa-stethoscope me-1"></i>Terminée</small>
                                </div>
                            </div>
                            <div class="col-md-3 text-end">
                                ${consultation.is_reviewed ? `
                                    <span class="review-badge reviewed">
                                        <i class="fas fa-star me-1"></i>Évalué
                                    </span>
                                    <div class="review-stars-display mt-1">
                                        ${consultation.review.stars}
                                    </div>
                                ` : `
                                    <span class="review-badge not-reviewed">
                                        <i class="fas fa-clock me-1"></i>Non évalué
                                    </span>
                                    <div class="mt-1">
                                        <small class="text-warning">Cliquez pour évaluer</small>
                                    </div>
                                `}
                            </div>
                        </div>
                        ${consultation.diagnosis ? `
                            <div class="row mt-2">
                                <div class="col-12">
                                    <small class="text-muted">
                                        <i class="fas fa-diagnoses me-1"></i>
                                        <strong>Diagnostic:</strong> ${consultation.diagnosis}
                                    </small>
                                </div>
                            </div>
                        ` : ''}
                    </div>
                `).join('');
            } else {
                appointmentsList.style.display = 'none';
                appointmentsEmptyState.style.display = 'block';
            }
        }

        // Show empty state for appointments
        function showAppointmentsEmptyState() {
            document.getElementById('appointmentsLoading').style.display = 'none';
            document.getElementById('appointmentsList').style.display = 'none';
            document.getElementById('appointmentsEmptyState').style.display = 'block';
        }

        // Get status color for badge
        function getStatusColor(status) {
            switch(status) {
                case 'pending': return 'warning';
                case 'confirmed': return 'info';
                case 'completed': return 'success';
                case 'cancelled': return 'danger';
                default: return 'secondary';
            }
        }

        // Get status text
        function getStatusText(status) {
            switch(status) {
                case 'pending': return 'En attente';
                case 'confirmed': return 'Confirmé';
                case 'completed': return 'Terminé';
                case 'cancelled': return 'Annulé';
                default: return status;
            }
        }

        // Select consultation for review
        function selectConsultationForReview(consultationId, doctorName, consultationDate, isReviewed) {
            if (isReviewed) {
                alert('Cette consultation a déjà été évaluée.');
                return;
            }

            // Close appointment selection modal
            appointmentSelectionModal.hide();

            // Open review modal for this consultation
            setTimeout(() => {
                openConsultationReviewModal(consultationId, doctorName, consultationDate);
            }, 300);
        }

        // Open consultation review modal
        function openConsultationReviewModal(consultationId, doctorName, consultationDate) {
            console.log('🔄 Opening consultation review modal for consultation:', consultationId);

            // Set modal data
            document.getElementById('reviewModalLabel').textContent = `Évaluer la Consultation - Dr. ${doctorName}`;
            document.getElementById('reviewDoctorName').textContent = `Dr. ${doctorName}`;
            document.getElementById('reviewAppointmentDate').textContent = consultationDate;

            // Store consultation ID for submission
            window.currentConsultationId = consultationId;
            window.currentReviewType = 'consultation';

            // Reset form
            resetReviewForm();

            // Show modal
            reviewModal.show();
        }

        // Load my reviews
        async function loadMyReviews() {
            try {
                console.log('🔄 Loading my reviews...');
                const response = await axios.get('/api/my-reviews');
                console.log('📊 My reviews response:', response.data);

                if (response.data.success) {
                    displayMyReviews(response.data.reviews);
                }
            } catch (error) {
                console.error('❌ Error loading my reviews:', error);
                showMyReviewsEmptyState();
            }
        }

        // Display my reviews
        function displayMyReviews(reviews) {
            const myReviewsList = document.getElementById('myReviewsList');
            const myReviewsEmptyState = document.getElementById('myReviewsEmptyState');
            const myReviewsCount = document.getElementById('myReviewsCount');

            if (reviews.length > 0) {
                myReviewsList.style.display = 'block';
                myReviewsEmptyState.style.display = 'none';
                myReviewsCount.textContent = reviews.length;

                myReviewsList.innerHTML = reviews.map(review => `
                    <div class="my-review-item">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <div class="doctor-avatar me-3" style="width: 40px; height: 40px; font-size: 1rem; background: #3730a3;">
                                        ${review.appointment.doctor.name.charAt(0)}
                                    </div>
                                    <div>
                                        <h6 class="mb-1">Dr. ${review.appointment.doctor.name}</h6>
                                        <small class="text-muted">${review.appointment.doctor.speciality}</small>
                                        <div class="mt-1">
                                            <small class="text-primary">
                                                <i class="fas fa-calendar me-1"></i>${review.appointment.date}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 text-center">
                                <div class="review-stars-display mb-1">
                                    ${review.stars}
                                </div>
                                <small class="text-muted">${review.rating_text}</small>
                            </div>
                            <div class="col-md-3 text-end">
                                <small class="text-muted">
                                    <i class="fas fa-clock me-1"></i>${review.time_ago}
                                </small>
                                <div class="mt-1">
                                    <small class="text-success">
                                        <i class="fas fa-check me-1"></i>Évalué
                                    </small>
                                </div>
                            </div>
                        </div>
                        ${review.comment ? `
                            <div class="row mt-2">
                                <div class="col-12">
                                    <div style="background: #f8fafc; padding: 0.5rem; border-radius: 6px; border-left: 3px solid #3730a3;">
                                        <small class="text-dark">
                                            <i class="fas fa-quote-left me-1"></i>
                                            ${review.comment}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        ` : ''}
                    </div>
                `).join('');
            } else {
                showMyReviewsEmptyState();
            }
        }

        // Show empty state for my reviews
        function showMyReviewsEmptyState() {
            const myReviewsList = document.getElementById('myReviewsList');
            const myReviewsEmptyState = document.getElementById('myReviewsEmptyState');
            const myReviewsCount = document.getElementById('myReviewsCount');

            if (myReviewsList) myReviewsList.style.display = 'none';
            if (myReviewsEmptyState) myReviewsEmptyState.style.display = 'block';
            if (myReviewsCount) myReviewsCount.textContent = '0';
        }

        // Load completed appointments
        async function loadCompletedAppointments() {
            try {
                console.log('🔄 Loading completed appointments...');
                const response = await axios.get('/api/completed-appointments');
                console.log('📊 API Response:', response.data);

                if (response.data.success) {
                    console.log('✅ Success! Appointments found:', response.data.appointments.length);
                    displayCompletedAppointments(response.data.appointments);
                } else {
                    console.log('❌ API returned success=false:', response.data);
                    showCompletedEmptyState();
                }
            } catch (error) {
                console.error('❌ Error loading completed appointments:', error);
                console.error('Error details:', error.response?.data);
                showCompletedEmptyState();
            }
        }

        // Show empty state for completed appointments
        function showCompletedEmptyState() {
            const completedSection = document.getElementById('completedAppointments');
            const emptyState = document.getElementById('completedEmptyState');
            const completedCount = document.getElementById('completedCount');

            if (completedSection) completedSection.style.display = 'none';
            if (emptyState) emptyState.style.display = 'block';
            if (completedCount) completedCount.textContent = '0';
        }

        // Display completed appointments
        function displayCompletedAppointments(appointments) {
            console.log('🎨 Displaying completed appointments:', appointments);

            const completedList = document.getElementById('completedList');
            const completedSection = document.getElementById('completedAppointments');
            const emptyState = document.getElementById('completedEmptyState');
            const completedCount = document.getElementById('completedCount');

            console.log('📋 DOM Elements found:', {
                completedList: !!completedList,
                completedSection: !!completedSection,
                emptyState: !!emptyState,
                completedCount: !!completedCount
            });

            if (appointments.length > 0) {
                console.log('✅ Found', appointments.length, 'completed appointments');
                completedSection.style.display = 'block';
                emptyState.style.display = 'none';
                completedCount.textContent = appointments.length;

                completedList.innerHTML = appointments.map(appointment => `
                    <div class="completed-item">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <div class="doctor-avatar me-3" style="width: 40px; height: 40px; font-size: 1rem; background: #22c55e;">
                                        ${appointment.doctor.name.charAt(0)}
                                    </div>
                                    <div>
                                        <h6 class="mb-1">Dr. ${appointment.doctor.name}</h6>
                                        <small class="text-muted">${appointment.doctor.speciality}</small>
                                        <div class="mt-1">
                                            <small class="text-success">
                                                <i class="fas fa-calendar me-1"></i>${appointment.formatted_date}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center">
                                    <span class="completed-badge">
                                        <i class="fas fa-check me-1"></i>Terminé
                                    </span>
                                    <div class="mt-2">
                                        ${appointment.has_consultation ? '<small class="text-success"><i class="fas fa-stethoscope me-1"></i>Consultation</small>' : ''}
                                        ${appointment.has_prescription ? '<br><small class="text-info"><i class="fas fa-pills me-1"></i>Prescription</small>' : ''}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 text-end">
                                ${appointment.is_reviewed ? `
                                    <div class="review-status reviewed mb-2">
                                        <i class="fas fa-star me-1"></i>Évalué
                                    </div>
                                    <div class="review-stars">
                                        ${appointment.review.stars}
                                    </div>
                                    <small class="text-muted d-block">${appointment.review.created_at}</small>
                                ` : `
                                    <div class="review-status pending mb-2">
                                        <i class="fas fa-clock me-1"></i>À évaluer
                                    </div>
                                    <button class="btn btn-warning btn-sm" onclick="openReviewModal(${appointment.id}, '${appointment.doctor.name}', '${appointment.appointment_date} ${appointment.appointment_time}')">
                                        <i class="fas fa-star me-2"></i>Évaluer
                                    </button>
                                `}
                            </div>
                        </div>
                        ${appointment.reason ? `
                            <div class="row mt-2">
                                <div class="col-12">
                                    <small class="text-muted">
                                        <i class="fas fa-comment-medical me-1"></i>
                                        <strong>Motif:</strong> ${appointment.reason}
                                    </small>
                                </div>
                            </div>
                        ` : ''}
                    </div>
                `).join('');
            } else {
                console.log('❌ No completed appointments found, showing empty state');
                if (completedSection) completedSection.style.display = 'none';
                if (emptyState) emptyState.style.display = 'block';
                if (completedCount) completedCount.textContent = '0';
            }
        }

        // Load reviews data
        async function loadReviewsData() {
            try {
                // Load reviewable appointments
                const reviewableResponse = await axios.get('/api/reviewable-appointments');
                if (reviewableResponse.data.success) {
                    displayReviewableAppointments(reviewableResponse.data.appointments);
                }
            } catch (error) {
                console.error('Error loading reviews data:', error);
            }
        }

        // Display reviewable appointments
        function displayReviewableAppointments(appointments) {
            const reviewableList = document.getElementById('reviewableList');
            const reviewableSection = document.getElementById('reviewableAppointments');
            const emptyState = document.getElementById('reviewsEmptyState');
            const reviewsCount = document.getElementById('reviewsCount');

            if (appointments.length > 0) {
                reviewableSection.style.display = 'block';
                emptyState.style.display = 'none';
                reviewsCount.textContent = appointments.length;

                reviewableList.innerHTML = appointments.map(appointment => `
                    <div class="reviewable-item">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <div class="doctor-avatar me-3" style="width: 40px; height: 40px; font-size: 1rem;">
                                        ${appointment.doctor.user.full_name.charAt(0)}
                                    </div>
                                    <div>
                                        <h6 class="mb-1">Dr. ${appointment.doctor.user.full_name}</h6>
                                        <small class="text-muted">${new Date(appointment.appointment_date).toLocaleDateString('fr-FR')} à ${new Date(appointment.appointment_date).toLocaleTimeString('fr-FR', {hour: '2-digit', minute: '2-digit'})}</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 text-end">
                                <button class="btn btn-warning btn-sm" onclick="openReviewModal(${appointment.id}, '${appointment.doctor.user.full_name}', '${appointment.appointment_date}')">
                                    <i class="fas fa-star me-2"></i>Évaluer
                                </button>
                            </div>
                        </div>
                    </div>
                `).join('');
            } else {
                reviewableSection.style.display = 'none';
                emptyState.style.display = 'block';
                reviewsCount.textContent = '0';
            }
        }

        // Open review modal
        function openReviewModal(appointmentId, doctorName, appointmentDate) {
            document.getElementById('reviewAppointmentId').value = appointmentId;
            document.getElementById('reviewDoctorName').textContent = 'Dr. ' + doctorName;
            document.getElementById('reviewDoctorAvatar').textContent = doctorName.charAt(0);
            document.getElementById('reviewAppointmentDate').textContent = new Date(appointmentDate).toLocaleDateString('fr-FR');

            // Reset form
            resetReviewForm();

            reviewModal.show();
        }

        // Initialize rating stars
        function initializeRatingStars() {
            const stars = document.querySelectorAll('.rating-stars .star');
            const ratingInput = document.getElementById('rating');
            const ratingText = document.getElementById('ratingText');

            const ratingTexts = {
                1: 'Très insatisfait',
                2: 'Insatisfait',
                3: 'Correct',
                4: 'Satisfait',
                5: 'Très satisfait'
            };

            stars.forEach(star => {
                star.addEventListener('click', function() {
                    const rating = parseInt(this.dataset.rating);
                    currentRating = rating;
                    ratingInput.value = rating;
                    ratingText.textContent = ratingTexts[rating];

                    // Update star display
                    stars.forEach((s, index) => {
                        if (index < rating) {
                            s.classList.add('active');
                        } else {
                            s.classList.remove('active');
                        }
                    });
                });

                star.addEventListener('mouseenter', function() {
                    const rating = parseInt(this.dataset.rating);
                    stars.forEach((s, index) => {
                        if (index < rating) {
                            s.style.color = '#f59e0b';
                        } else {
                            s.style.color = '#e5e7eb';
                        }
                    });
                });
            });

            // Reset on mouse leave
            document.querySelector('.rating-stars').addEventListener('mouseleave', function() {
                stars.forEach((s, index) => {
                    if (index < currentRating) {
                        s.style.color = '#f59e0b';
                    } else {
                        s.style.color = '#e5e7eb';
                    }
                });
            });
        }

        // Reset review form
        function resetReviewForm() {
            currentRating = 0;
            document.getElementById('rating').value = '';
            document.getElementById('comment').value = '';
            document.getElementById('ratingText').textContent = 'Cliquez sur les étoiles pour noter';

            // Reset stars
            const stars = document.querySelectorAll('.rating-stars .star');
            stars.forEach(star => {
                star.classList.remove('active');
                star.style.color = '#e5e7eb';
            });
        }

        // Submit review
        document.getElementById('reviewForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const submitBtn = document.getElementById('submitReview');
            const originalText = submitBtn.innerHTML;

            // Validate rating
            if (currentRating === 0) {
                alert('Veuillez sélectionner une note');
                return;
            }

            try {
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Envoi...';
                submitBtn.disabled = true;

                let response;

                if (window.currentReviewType === 'consultation') {
                    // Submit consultation review
                    const formData = new FormData(this);
                    response = await axios.post('/api/consultation-reviews', {
                        consultation_id: window.currentConsultationId,
                        rating: currentRating,
                        comment: formData.get('comment')
                    }, {
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json'
                        }
                    });
                } else {
                    // Submit appointment review (legacy)
                    const formData = new FormData(this);
                    response = await axios.post('/reviews', formData, {
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'multipart/form-data'
                        }
                    });
                }

                if (response.data.success) {
                    reviewModal.hide();

                    // Show success message
                    showAlert('Évaluation soumise avec succès !', 'success');

                    // Reload my reviews data
                    setTimeout(() => {
                        loadMyReviews();
                    }, 1000);
                } else {
                    showAlert(response.data.error || 'Erreur lors de la soumission', 'danger');
                }

            } catch (error) {
                console.error('Error submitting review:', error);
                showAlert(error.response?.data?.error || 'Erreur lors de la soumission', 'danger');
            } finally {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }
        });

        // Show alert function
        function showAlert(message, type) {
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
            alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
            alertDiv.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;

            document.body.appendChild(alertDiv);

            // Auto remove after 5 seconds
            setTimeout(() => {
                if (alertDiv.parentNode) {
                    alertDiv.remove();
                }
            }, 5000);
        }
    </script>

    <script>
        // Set minimum date to tomorrow
        document.addEventListener('DOMContentLoaded', function() {
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            const minDate = tomorrow.toISOString().slice(0, 16);
            document.getElementById('appointmentDate').min = minDate;
        });

        let selectedDoctor = null;

        // Configure axios defaults with authentication
        axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
        axios.defaults.withCredentials = true; // Include cookies for session authentication

        // Add CSRF token if available
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (csrfToken) {
            axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken.getAttribute('content');
        }

        // Search doctors function
        async function searchDoctors() {
            console.log('🔍 Starting doctor search...');

            const searchInput = document.getElementById('doctorSearch');
            const specialityInput = document.getElementById('specialityFilter');
            const hospitalInput = document.getElementById('hospitalFilter');
            const doctorsList = document.getElementById('doctorsList');

            if (!searchInput || !specialityInput || !hospitalInput || !doctorsList) {
                console.error('❌ Missing required elements for search');
                return;
            }

            const search = searchInput.value;
            const speciality = specialityInput.value;
            const hospital = hospitalInput.value;

            console.log('📝 Search parameters:', { search, speciality, hospital });

            // Show loading state
            doctorsList.innerHTML = '<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Recherche en cours...</div>';

            try {
                console.log('🌐 Making API request to /api/doctors');
                const response = await axios.get('/api/doctors', {
                    params: { search, speciality, hospital }
                });

                console.log('✅ API response received:', response.data);

                if (response.data && response.data.doctors) {
                    displayDoctors(response.data.doctors);
                } else {
                    console.error('❌ Invalid response format:', response.data);
                    doctorsList.innerHTML = '<div class="text-center text-danger"><i class="fas fa-exclamation-triangle"></i> Format de réponse invalide</div>';
                }
            } catch (error) {
                console.error('❌ Erreur lors de la recherche:', error);
                console.error('Error details:', {
                    message: error.message,
                    response: error.response?.data,
                    status: error.response?.status
                });

                doctorsList.innerHTML = `
                    <div class="text-center text-danger">
                        <i class="fas fa-exclamation-triangle"></i>
                        Erreur lors de la recherche
                        <br><small>Vérifiez la console pour plus de détails</small>
                    </div>
                `;
            }
        }

        // Display doctors function
        function displayDoctors(doctors) {
            console.log('👨‍⚕️ Displaying doctors:', doctors);

            const doctorsList = document.getElementById('doctorsList');

            if (!doctorsList) {
                console.error('❌ doctorsList element not found');
                return;
            }

            if (!doctors || doctors.length === 0) {
                console.log('📭 No doctors found');
                doctorsList.innerHTML = `
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-user-md fa-2x mb-2"></i>
                        <p>Aucun docteur trouvé</p>
                        <small>Essayez de modifier vos critères de recherche</small>
                    </div>
                `;
                return;
            }

            console.log(`📋 Displaying ${doctors.length} doctors`);

            try {
                doctorsList.innerHTML = doctors.map(doctor => {
                    // Validate doctor data
                    if (!doctor.id || !doctor.name) {
                        console.warn('⚠️ Invalid doctor data:', doctor);
                        return '';
                    }

                    return `
                        <div class="doctor-item border rounded p-3 mb-2"
                             data-doctor-id="${doctor.id}"
                             onclick="selectDoctor(${doctor.id}, '${doctor.name.replace(/'/g, "\\'")}')">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1">
                                        <i class="fas fa-user-md me-2 text-primary"></i>
                                        Dr. ${doctor.name}
                                    </h6>
                                    <p class="mb-1 text-muted">
                                        <i class="fas fa-stethoscope me-1"></i>
                                        ${doctor.speciality || 'Spécialité non spécifiée'}
                                    </p>
                                    <small class="text-muted">
                                        <i class="fas fa-hospital me-1"></i>
                                        ${doctor.hospital || 'Hôpital non spécifié'}
                                    </small>
                                </div>
                                <div class="text-end">
                                    <small class="text-muted">
                                        <i class="fas fa-phone me-1"></i>
                                        ${doctor.phone || 'N/A'}
                                    </small>
                                    <br>
                                    <small class="text-muted">
                                        <i class="fas fa-envelope me-1"></i>
                                        ${doctor.email || 'N/A'}
                                    </small>
                                </div>
                            </div>
                            ${doctor.biography ? `
                                <div class="mt-2 p-2 bg-light rounded">
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle me-1"></i>
                                        ${doctor.biography}
                                    </small>
                                </div>
                            ` : ''}
                            <div class="mt-2">
                                <button class="btn btn-outline-primary btn-sm w-100">
                                    <i class="fas fa-hand-pointer me-1"></i>
                                    Sélectionner ce docteur
                                </button>
                            </div>
                        </div>
                    `;
                }).filter(html => html !== '').join('');

                console.log('✅ Doctors displayed successfully');

            } catch (error) {
                console.error('❌ Error displaying doctors:', error);
                doctorsList.innerHTML = `
                    <div class="text-center text-danger">
                        <i class="fas fa-exclamation-triangle"></i>
                        Erreur lors de l'affichage des docteurs
                    </div>
                `;
            }
        }

        // Select doctor function
        function selectDoctor(doctorId, doctorName) {
            selectedDoctor = { id: doctorId, name: doctorName };
            document.getElementById('selectedDoctorId').value = doctorId;

            // Highlight selected doctor
            document.querySelectorAll('.doctor-item').forEach(item => {
                item.classList.remove('border-success', 'bg-light');
            });

            document.querySelector(`[data-doctor-id="${doctorId}"]`).classList.add('border-success', 'bg-light');
        }

        // Submit appointment
        async function submitAppointment() {
            console.log('📅 Starting appointment submission...');

            const form = document.getElementById('appointmentForm');
            const formData = new FormData(form);

            if (!selectedDoctor) {
                alert('Veuillez sélectionner un docteur');
                return;
            }

            const appointmentData = {
                doctor_id: selectedDoctor.id, // Utiliser l'ID du docteur sélectionné
                appointment_date: formData.get('appointment_date'),
                reason: formData.get('reason')
            };

            console.log('📝 Appointment data:', appointmentData);

            try {
                console.log('🌐 Sending appointment request...');
                const response = await axios.post('/appointments', appointmentData);

                console.log('✅ Appointment created successfully:', response.data);

                // Afficher un message de succès avec les détails
                const appointment = response.data.appointment;
                alert(`Demande de rendez-vous envoyée avec succès!

Détails:
• Patient: ${appointment.patient.name}
• Docteur: Dr. ${appointment.doctor.name}
• Date: ${appointment.appointment_date_formatted}
• Statut: ${appointment.status_label}

Votre demande sera traitée par le docteur.`);

                // Recharger la page pour afficher le nouveau rendez-vous
                location.reload();

            } catch (error) {
                console.error('❌ Erreur lors de la création du rendez-vous:', error);

                let errorMessage = 'Erreur lors de la création du rendez-vous';

                if (error.response && error.response.data) {
                    if (error.response.data.error) {
                        errorMessage = error.response.data.error;
                    } else if (error.response.data.message) {
                        errorMessage = error.response.data.message;
                    }

                    // Afficher les erreurs de validation
                    if (error.response.data.errors) {
                        const validationErrors = Object.values(error.response.data.errors).flat();
                        errorMessage += '\n\nDétails:\n' + validationErrors.join('\n');
                    }
                }

                alert('Erreur: ' + errorMessage);
            }
        }

        // Show consultation details function
        async function showConsultationDetails(consultationId) {
            try {
                const modal = new bootstrap.Modal(document.getElementById('consultationDetailsModal'));
                modal.show();

                // Simuler le chargement des détails (vous pouvez créer une API pour cela)
                const consultations = @json($consultations ?? collect());
                const consultation = consultations.find(c => c.id === consultationId);

                if (consultation) {
                    const content = `
                        <div class="row">
                            <div class="col-md-6">
                                <div class="consultation-detail-card">
                                    <h6 class="text-primary mb-3">
                                        <i class="fas fa-user-md me-2"></i>Informations Docteur
                                    </h6>
                                    <p><strong>Nom:</strong> Dr. ${consultation.appointment.doctor.user.full_name || 'Non spécifié'}</p>
                                    <p><strong>Spécialité:</strong> ${consultation.appointment.doctor.speciality || 'Non spécifiée'}</p>
                                    <p><strong>Hôpital:</strong> ${consultation.appointment.doctor.hospital || 'Non spécifié'}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="consultation-detail-card">
                                    <h6 class="text-info mb-3">
                                        <i class="fas fa-calendar me-2"></i>Informations Consultation
                                    </h6>
                                    <p><strong>Date:</strong> ${new Date(consultation.consultation_date).toLocaleDateString('fr-FR', {
                                        year: 'numeric',
                                        month: 'long',
                                        day: 'numeric',
                                        hour: '2-digit',
                                        minute: '2-digit'
                                    })}</p>
                                    <p><strong>Durée:</strong> ${consultation.duration || 'Non spécifiée'}</p>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="consultation-detail-card">
                                    <h6 class="text-success mb-3">
                                        <i class="fas fa-diagnoses me-2"></i>Diagnostic
                                    </h6>
                                    <div class="diagnosis-content">
                                        ${consultation.diagnosis || 'Aucun diagnostic spécifié'}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="consultation-detail-card">
                                    <h6 class="text-warning mb-3">
                                        <i class="fas fa-pills me-2"></i>Traitement
                                    </h6>
                                    <div class="treatment-content">
                                        ${consultation.treatment || 'Aucun traitement spécifié'}
                                    </div>
                                </div>
                            </div>
                        </div>

                        ${consultation.notes ? `
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="consultation-detail-card">
                                    <h6 class="text-secondary mb-3">
                                        <i class="fas fa-sticky-note me-2"></i>Notes Additionnelles
                                    </h6>
                                    <div class="notes-content">
                                        ${consultation.notes}
                                    </div>
                                </div>
                            </div>
                        </div>
                        ` : ''}

                        <style>
                            .consultation-detail-card {
                                background: #f8fafc;
                                border-radius: 8px;
                                padding: 1rem;
                                margin-bottom: 1rem;
                                border-left: 4px solid #e2e8f0;
                            }
                            .diagnosis-content, .treatment-content, .notes-content {
                                background: white;
                                padding: 1rem;
                                border-radius: 6px;
                                border: 1px solid #e2e8f0;
                                line-height: 1.6;
                            }
                        </style>
                    `;

                    document.getElementById('consultationDetailsContent').innerHTML = content;
                } else {
                    document.getElementById('consultationDetailsContent').innerHTML = `
                        <div class="text-center py-4">
                            <i class="fas fa-exclamation-triangle text-warning" style="font-size: 3rem;"></i>
                            <h5 class="mt-3">Consultation non trouvée</h5>
                            <p class="text-muted">Les détails de cette consultation ne sont pas disponibles.</p>
                        </div>
                    `;
                }
            } catch (error) {
                console.error('Erreur:', error);
                document.getElementById('consultationDetailsContent').innerHTML = `
                    <div class="text-center py-4">
                        <i class="fas fa-exclamation-circle text-danger" style="font-size: 3rem;"></i>
                        <h5 class="mt-3">Erreur de chargement</h5>
                        <p class="text-muted">Impossible de charger les détails de la consultation.</p>
                    </div>
                `;
            }
        }

        // Show all symptom checks function
        async function showAllSymptomChecks() {
            try {
                const response = await axios.get('/ai/symptom-history');

                if (response.data.success) {
                    const symptomChecks = response.data.data;

                    let content = `
                        <div class="row">
                            <div class="col-12">
                                <h5 class="mb-3">
                                    <i class="fas fa-brain me-2 text-primary"></i>
                                    All Symptom Analyses (${symptomChecks.length})
                                </h5>
                            </div>
                        </div>
                    `;

                    if (symptomChecks.length > 0) {
                        symptomChecks.forEach(check => {
                            const urgencyClass = getUrgencyClass(check.urgency_level);
                            const date = new Date(check.created_at).toLocaleDateString('fr-FR', {
                                year: 'numeric',
                                month: 'short',
                                day: 'numeric',
                                hour: '2-digit',
                                minute: '2-digit'
                            });

                            content += `
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <h6 class="card-title text-primary">
                                                    <i class="fas fa-robot me-2"></i>Analysis from ${date}
                                                </h6>
                                                <p class="card-text"><strong>Symptoms:</strong> ${check.symptom_text}</p>
                                                <p class="card-text"><strong>Result:</strong> ${check.result}</p>
                                                <p class="card-text">
                                                    <strong>Recommended Doctor:</strong> ${check.recommended_doctor}
                                                </p>
                                            </div>
                                            <div class="col-md-4 text-end">
                                                <span class="urgency-badge ${urgencyClass} mb-2 d-block">
                                                    Urgency: ${check.urgency_level}/10
                                                </span>
                                                <span class="urgency-badge ${urgencyClass}">
                                                    Severity: ${check.severity}/10
                                                </span>
                                                ${check.detected_categories ? `
                                                <div class="mt-2">
                                                    ${Array.isArray(check.detected_categories) ? check.detected_categories.map(cat => `<span class="category-tag">${cat}</span>`).join('') : `<span class="category-tag">${check.detected_categories}</span>`}
                                                </div>
                                                ` : ''}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;
                        });
                    } else {
                        content += `
                            <div class="text-center py-4">
                                <i class="fas fa-brain text-muted" style="font-size: 3rem;"></i>
                                <h5 class="mt-3">No Symptom Analyses</h5>
                                <p class="text-muted">You haven't used the AI symptom checker yet.</p>
                                <a href="/ai/symptom-analyzer" class="btn btn-primary">
                                    <i class="fas fa-brain me-2"></i>Start Analysis
                                </a>
                            </div>
                        `;
                    }

                    document.getElementById('consultationDetailsContent').innerHTML = content;
                    const modal = new bootstrap.Modal(document.getElementById('consultationDetailsModal'));
                    document.getElementById('consultationDetailsModalLabel').innerHTML =
                        '<i class="fas fa-brain me-2"></i>Symptom Analysis History';
                    modal.show();
                } else {
                    alert('Error loading symptom history');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error loading symptom history');
            }
        }

        // Event listeners with debugging
        console.log('🔧 Setting up event listeners...');

        // Search button
        const searchBtn = document.getElementById('searchBtn');
        if (searchBtn) {
            searchBtn.addEventListener('click', function() {
                console.log('🔍 Search button clicked');
                searchDoctors();
            });
            console.log('✅ Search button listener added');
        } else {
            console.error('❌ Search button not found');
        }

        // Doctor search input
        const doctorSearchInput = document.getElementById('doctorSearch');
        if (doctorSearchInput) {
            doctorSearchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    console.log('⌨️ Enter key pressed in search input');
                    e.preventDefault();
                    searchDoctors();
                }
            });

            // Also add input event for real-time search
            doctorSearchInput.addEventListener('input', function() {
                console.log('📝 Search input changed:', this.value);
                // Debounce the search
                clearTimeout(window.searchTimeout);
                window.searchTimeout = setTimeout(searchDoctors, 500);
            });
            console.log('✅ Doctor search input listeners added');
        } else {
            console.error('❌ Doctor search input not found');
        }

        // Speciality filter
        const specialityFilter = document.getElementById('specialityFilter');
        if (specialityFilter) {
            specialityFilter.addEventListener('change', function() {
                console.log('🏥 Speciality filter changed:', this.value);
                searchDoctors();
            });
            specialityFilter.addEventListener('input', function() {
                console.log('📝 Speciality input changed:', this.value);
                clearTimeout(window.specialityTimeout);
                window.specialityTimeout = setTimeout(searchDoctors, 500);
            });
            console.log('✅ Speciality filter listeners added');
        } else {
            console.error('❌ Speciality filter not found');
        }

        // Hospital filter
        const hospitalFilter = document.getElementById('hospitalFilter');
        if (hospitalFilter) {
            hospitalFilter.addEventListener('change', function() {
                console.log('🏥 Hospital filter changed:', this.value);
                searchDoctors();
            });
            hospitalFilter.addEventListener('input', function() {
                console.log('📝 Hospital input changed:', this.value);
                clearTimeout(window.hospitalTimeout);
                window.hospitalTimeout = setTimeout(searchDoctors, 500);
            });
            console.log('✅ Hospital filter listeners added');
        } else {
            console.error('❌ Hospital filter not found');
        }

        // Submit appointment button
        const submitBtn = document.getElementById('submitAppointment');
        if (submitBtn) {
            submitBtn.addEventListener('click', function() {
                console.log('📅 Submit appointment clicked');
                submitAppointment();
            });
            console.log('✅ Submit appointment listener added');
        } else {
            console.error('❌ Submit appointment button not found');
        }

        // Load all doctors on modal open
        const appointmentModal = document.getElementById('appointmentModal');
        if (appointmentModal) {
            appointmentModal.addEventListener('shown.bs.modal', function() {
                console.log('📋 Appointment modal opened - loading doctors');
                searchDoctors();
            });
            console.log('✅ Modal event listener added');
        } else {
            console.error('❌ Appointment modal not found');
        }

        console.log('🎉 All event listeners setup complete');

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
