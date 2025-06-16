<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Patient - MediCare Pro</title>
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
            overflow: hidden;
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
                                    <li><a class="dropdown-item" href="#"><i class="fas fa-user-edit me-2"></i>Mon Profil</a></li>
                                    <li><a class="dropdown-item" href="#"><i class="fas fa-file-medical me-2"></i>Dossier Médical</a></li>
                                    <li><a class="dropdown-item" href="#"><i class="fas fa-pills me-2"></i>Mes Prescriptions</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Paramètres</a></li>
                                    <li><a class="dropdown-item text-danger" href="#"><i class="fas fa-sign-out-alt me-2"></i>Déconnexion</a></li>
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
                                        <h3 class="mb-1 fw-bold text-warning">0</h3>
                                        <p class="mb-0 text-muted">Prescriptions</p>
                                        <small class="text-warning">
                                            <i class="fas fa-pills"></i> Actives
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
                            <div class="appointment-card">
                                <div class="card-header-custom" style="background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); color: #92400e;">
                                    <h6 class="mb-0">
                                        <i class="fas fa-pills me-2"></i>Mes Prescriptions
                                    </h6>
                                </div>
                                <div class="p-3">
                                    <div class="empty-state py-3">
                                        <i class="fas fa-prescription-bottle-alt" style="font-size: 2.5rem;"></i>
                                        <h6 class="mt-2 mb-1">Aucune prescription active</h6>
                                        <p class="mb-0 small">Vos prescriptions apparaîtront ici après vos consultations.</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Rappels de santé -->
                            <div class="appointment-card">
                                <div class="card-header-custom" style="background: linear-gradient(135deg, #fecaca 0%, #fca5a5 100%); color: #991b1b;">
                                    <h6 class="mb-0">
                                        <i class="fas fa-bell me-2"></i>Rappels de Santé
                                    </h6>
                                </div>
                                <div class="p-3">
                                    <div class="empty-state py-3">
                                        <i class="fas fa-heart-pulse" style="font-size: 2.5rem;"></i>
                                        <h6 class="mt-2 mb-1">Aucun rappel</h6>
                                        <p class="mb-0 small">Nous vous rappellerons vos prochains examens et vaccins.</p>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <script>
        // Set minimum date to tomorrow
        document.addEventListener('DOMContentLoaded', function() {
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            const minDate = tomorrow.toISOString().slice(0, 16);
            document.getElementById('appointmentDate').min = minDate;
        });

        let selectedDoctor = null;

        // Configure axios defaults (sans CSRF)
        axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

        // Search doctors function
        async function searchDoctors() {
            const search = document.getElementById('doctorSearch').value;
            const speciality = document.getElementById('specialityFilter').value;
            const hospital = document.getElementById('hospitalFilter').value;

            try {
                const response = await axios.get('/api/doctors', {
                    params: { search, speciality, hospital }
                });

                displayDoctors(response.data.doctors);
            } catch (error) {
                console.error('Erreur lors de la recherche:', error);
                document.getElementById('doctorsList').innerHTML =
                    '<div class="text-center text-danger"><i class="fas fa-exclamation-triangle"></i> Erreur lors de la recherche</div>';
            }
        }

        // Display doctors function
        function displayDoctors(doctors) {
            const doctorsList = document.getElementById('doctorsList');

            if (doctors.length === 0) {
                doctorsList.innerHTML =
                    '<div class="text-center text-muted"><i class="fas fa-user-md fa-2x mb-2"></i><p>Aucun docteur trouvé</p></div>';
                return;
            }

            doctorsList.innerHTML = doctors.map(doctor => `
                <div class="doctor-item border rounded p-3 mb-2 cursor-pointer" data-doctor-id="${doctor.id}" onclick="selectDoctor(${doctor.id}, '${doctor.name}')">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="mb-1">Dr. ${doctor.name}</h6>
                            <p class="mb-1 text-muted">${doctor.speciality}</p>
                            <small class="text-muted">${doctor.hospital}</small>
                        </div>
                        <div class="text-end">
                            <small class="text-muted">${doctor.phone}</small>
                        </div>
                    </div>
                    ${doctor.biography ? `<p class="mt-2 mb-0 small">${doctor.biography}</p>` : ''}
                </div>
            `).join('');
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
            const form = document.getElementById('appointmentForm');
            const formData = new FormData(form);

            if (!selectedDoctor) {
                alert('Veuillez sélectionner un docteur');
                return;
            }

            try {
                const response = await axios.post('/api/appointments', {
                    patient_id: 1, // ID patient fixe pour test
                    doctor_id: formData.get('doctor_id'),
                    appointment_date: formData.get('appointment_date'),
                    reason: formData.get('reason')
                });

                alert('Demande de rendez-vous envoyée avec succès!');
                location.reload(); // Refresh page to show new appointment
            } catch (error) {
                console.error('Erreur:', error);
                if (error.response && error.response.data && error.response.data.error) {
                    alert('Erreur: ' + error.response.data.error);
                } else {
                    alert('Erreur lors de la création du rendez-vous');
                }
            }
        }

        // Event listeners
        document.getElementById('searchBtn').addEventListener('click', searchDoctors);
        document.getElementById('doctorSearch').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                searchDoctors();
            }
        });
        document.getElementById('specialityFilter').addEventListener('change', searchDoctors);
        document.getElementById('hospitalFilter').addEventListener('change', searchDoctors);
        document.getElementById('submitAppointment').addEventListener('click', submitAppointment);

        // Load all doctors on modal open
        document.getElementById('appointmentModal').addEventListener('shown.bs.modal', function() {
            searchDoctors();
        });
    </script>
</body>
</html>
