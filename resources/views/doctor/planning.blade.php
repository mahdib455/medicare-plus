<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Planning - MediCare Pro</title>
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

        .planning-card {
            background: white;
            border-radius: 16px;
            box-shadow: var(--card-shadow);
            border: none;
            margin-bottom: 2rem;
            overflow: hidden;
        }

        .date-header {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            padding: 1.5rem;
            border-bottom: 2px solid #e2e8f0;
            font-weight: 600;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .appointment-item {
            padding: 1.5rem;
            border-bottom: 1px solid #f1f5f9;
            transition: background-color 0.3s ease;
        }

        .appointment-item:hover {
            background-color: #f8fafc;
        }

        .appointment-item:last-child {
            border-bottom: none;
        }

        .time-badge {
            background: linear-gradient(135deg, var(--primary-color), #3b82f6);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 500;
            font-size: 0.875rem;
            min-width: 80px;
            text-align: center;
        }

        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .status-confirmed {
            background: #d1fae5;
            color: #065f46;
        }

        .status-completed {
            background: #d1fae5;
            color: #065f46;
            transition: all 0.3s ease;
        }

        .status-completed:hover {
            background: #a7f3d0;
            color: #064e3b;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .status-completed.text-decoration-none {
            text-decoration: none !important;
        }

        .status-completed.text-decoration-none:hover {
            text-decoration: none !important;
        }

        .status-cancelled {
            background: #fee2e2;
            color: #991b1b;
        }

        .patient-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), #3b82f6);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            margin-right: 1rem;
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: var(--secondary-color);
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.5;
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

        .calendar-view {
            background: white;
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 2rem;
        }

        .today-highlight {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            border-left: 4px solid var(--warning-color);
        }

        .future-date {
            background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
            border-left: 4px solid var(--success-color);
        }

        .past-date {
            background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
            border-left: 4px solid var(--secondary-color);
            opacity: 0.8;
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

            .appointment-item {
                padding: 1rem;
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
                                <i class="fas fa-calendar-alt me-3"></i>
                                Mon Planning
                            </h1>
                            <p class="mb-0 opacity-75">
                                <i class="fas fa-clock me-2"></i>Gérez vos rendez-vous et consultations
                                <span class="mx-3">•</span>
                                <i class="fas fa-user-md me-2"></i>Dr. {{ $doctor->user->full_name ?? 'Docteur' }}
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
            <!-- Stats Cards -->
            <div class="row mb-4">
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="stats-card">
                        <div class="d-flex align-items-center">
                            <div class="stats-icon bg-primary text-white">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h3 class="mb-1 fw-bold text-primary">{{ $totalAppointments }}</h3>
                                <p class="mb-0 text-muted">Total Rendez-vous</p>
                                <small class="text-info">
                                    <i class="fas fa-info-circle"></i> Tous statuts
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="stats-card">
                        <div class="d-flex align-items-center">
                            <div class="stats-icon" style="background: linear-gradient(135deg, var(--warning-color), #f59e0b); color: white;">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h3 class="mb-1 fw-bold" style="color: var(--warning-color);">{{ $todayAppointments }}</h3>
                                <p class="mb-0 text-muted">Aujourd'hui</p>
                                <small class="text-warning">
                                    <i class="fas fa-calendar-day"></i> {{ now()->format('d/m/Y') }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="stats-card">
                        <div class="d-flex align-items-center">
                            <div class="stats-icon" style="background: linear-gradient(135deg, var(--success-color), #10b981); color: white;">
                                <i class="fas fa-arrow-up"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h3 class="mb-1 fw-bold text-success">{{ $upcomingAppointments }}</h3>
                                <p class="mb-0 text-muted">À venir</p>
                                <small class="text-success">
                                    <i class="fas fa-calendar-plus"></i> Futurs
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="stats-card">
                        <div class="d-flex align-items-center">
                            <div class="stats-icon" style="background: linear-gradient(135deg, var(--info-color), #06b6d4); color: white;">
                                <i class="fas fa-calendar-week"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h3 class="mb-1 fw-bold text-info">{{ $thisWeekAppointments }}</h3>
                                <p class="mb-0 text-muted">Cette semaine</p>
                                <small class="text-info">
                                    <i class="fas fa-calendar"></i> 7 jours
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Planning Content -->
            <div class="row">
                <div class="col-12">
                    @if($appointmentsByDate->count() > 0)
                        @foreach($appointmentsByDate as $date => $dayAppointments)
                            @php
                                $dateObj = \Carbon\Carbon::parse($date);
                                $isToday = $dateObj->isToday();
                                $isFuture = $dateObj->isFuture();
                                $isPast = $dateObj->isPast() && !$isToday;
                                
                                $dateClass = '';
                                if ($isToday) $dateClass = 'today-highlight';
                                elseif ($isFuture) $dateClass = 'future-date';
                                else $dateClass = 'past-date';
                            @endphp
                            
                            <div class="planning-card">
                                <div class="date-header {{ $dateClass }}">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h5 class="mb-1">
                                                <i class="fas fa-calendar me-2"></i>
                                                {{ $dateObj->format('l d F Y') }}
                                                @if($isToday)
                                                    <span class="badge bg-warning text-dark ms-2">Aujourd'hui</span>
                                                @elseif($isFuture)
                                                    <span class="badge bg-success ms-2">À venir</span>
                                                @else
                                                    <span class="badge bg-secondary ms-2">Passé</span>
                                                @endif
                                            </h5>
                                            <small class="text-muted">{{ $dayAppointments->count() }} rendez-vous</small>
                                        </div>
                                        <div class="text-end">
                                            <small class="text-muted">
                                                @if($isToday)
                                                    <i class="fas fa-clock me-1"></i>{{ now()->format('H:i') }}
                                                @else
                                                    <i class="fas fa-calendar-day me-1"></i>{{ $dateObj->diffForHumans() }}
                                                @endif
                                            </small>
                                        </div>
                                    </div>
                                </div>
                                
                                @foreach($dayAppointments->sortBy('appointment_date') as $appointment)
                                    <div class="appointment-item">
                                        <div class="row align-items-center">
                                            <div class="col-md-1">
                                                <div class="time-badge">
                                                    {{ $appointment->appointment_date->format('H:i') }}
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="patient-avatar">
                                                        {{ substr($appointment->patient->user->full_name ?? 'P', 0, 1) }}
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-1 fw-semibold">{{ $appointment->patient->user->full_name ?? 'Patient inconnu' }}</h6>
                                                        <small class="text-muted">
                                                            <i class="fas fa-envelope me-1"></i>{{ $appointment->patient->user->email ?? 'N/A' }}
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <p class="mb-1">{{ Str::limit($appointment->reason, 40) ?: 'Aucun motif spécifié' }}</p>
                                                <small class="text-muted">
                                                    <i class="fas fa-phone me-1"></i>{{ $appointment->patient->user->phone ?? 'N/A' }}
                                                </small>
                                            </div>
                                            <div class="col-md-2">
                                                @if($appointment->status === 'completed' && $appointment->consultation)
                                                    <!-- Clickable "Terminé" badge for CRUD access -->
                                                    <a href="{{ route('doctor.appointment.crud', $appointment->id) }}" class="status-badge status-{{ $appointment->status }} text-decoration-none" title="Cliquez pour gérer consultation et prescription">
                                                        <i class="fas fa-check-double me-1"></i>
                                                        Terminé
                                                        <i class="fas fa-external-link-alt ms-1" style="font-size: 0.8em;"></i>
                                                    </a>
                                                @else
                                                    <span class="status-badge status-{{ $appointment->status }}">
                                                        <i class="fas fa-{{ $appointment->status === 'pending' ? 'clock' : ($appointment->status === 'confirmed' ? 'check' : ($appointment->status === 'completed' ? 'check-double' : 'times')) }} me-1"></i>
                                                        {{ ucfirst($appointment->status) }}
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="col-md-4">
                                                <div class="d-flex gap-2 align-items-center">
                                                    <!-- Status badges -->
                                                    <div class="d-flex gap-1">
                                                        @if($appointment->consultation)
                                                            <span class="badge bg-info" title="Consultation effectuée">
                                                                <i class="fas fa-stethoscope"></i>
                                                            </span>
                                                        @endif
                                                        @if($appointment->prescriptions->count() > 0)
                                                            <span class="badge bg-warning" title="{{ $appointment->prescriptions->count() }} prescription(s)">
                                                                <i class="fas fa-prescription-bottle-alt"></i> {{ $appointment->prescriptions->count() }}
                                                            </span>
                                                        @endif
                                                    </div>

                                                    <!-- Action buttons -->
                                                    <div class="d-flex gap-1">
                                                        @if($appointment->status === 'pending')
                                                            <!-- Confirm appointment button -->
                                                            <form action="{{ route('doctor.appointment.confirm', $appointment->id) }}" method="POST" style="display: inline;">
                                                                @csrf
                                                                <button type="submit" class="btn btn-success btn-sm" title="Confirmer le rendez-vous">
                                                                    <i class="fas fa-check"></i> Confirmer
                                                                </button>
                                                            </form>
                                                        @elseif($appointment->status === 'confirmed' && !$appointment->consultation)
                                                            <!-- Start consultation button -->
                                                            <a href="{{ route('doctor.consultation.create', $appointment->id) }}" class="btn btn-primary btn-sm" title="Démarrer la consultation">
                                                                <i class="fas fa-stethoscope"></i> Consultation
                                                            </a>
                                                        @elseif($appointment->consultation)
                                                            <!-- View consultation button -->
                                                            <a href="{{ route('doctor.consultation.show', $appointment->consultation->id) }}" class="btn btn-info btn-sm" title="Voir la consultation">
                                                                <i class="fas fa-eye"></i> Voir
                                                            </a>

                                                            @if($appointment->prescriptions->count() == 0)
                                                                <!-- Create prescription button -->
                                                                <a href="{{ route('doctor.prescription.create', $appointment->consultation->id) }}" class="btn btn-warning btn-sm" title="Créer une prescription">
                                                                    <i class="fas fa-prescription-bottle"></i> Prescription
                                                                </a>
                                                            @else
                                                                <!-- View prescription button -->
                                                                <a href="{{ route('doctor.prescription.show', $appointment->prescriptions->first()->id) }}" class="btn btn-outline-warning btn-sm" title="Voir la prescription">
                                                                    <i class="fas fa-prescription-bottle-alt"></i> Voir Rx
                                                                </a>
                                                            @endif
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    @else
                        <div class="planning-card">
                            <div class="empty-state">
                                <i class="fas fa-calendar-times"></i>
                                <h4 class="mt-3 mb-2">Aucun rendez-vous planifié</h4>
                                <p class="mb-4">Votre planning est vide pour le moment. Les nouveaux rendez-vous apparaîtront ici.</p>
                                <a href="{{ route('doctor.dashboard') }}" class="btn btn-primary">
                                    <i class="fas fa-arrow-left me-2"></i>Retour au Dashboard
                                </a>
                            </div>
                        </div>
                    @endif
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

            // Auto-refresh every 5 minutes to show new appointments
            setInterval(() => {
                location.reload();
            }, 5 * 60 * 1000);
        });
    </script>
</body>
</html>
