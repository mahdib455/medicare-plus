<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Docteur - MediCare Pro</title>
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
        }

        .header-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, #3b82f6 100%);
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

        .patient-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), #3b82f6);
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
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .medication-line {
            background: #f8fafc;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .medication-line:hover {
            border-color: var(--primary-color);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
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
                        <div class="doctor-info">
                            <h1 class="mb-2">
                                <i class="fas fa-stethoscope me-3"></i>
                                Bonjour, Dr. {{ $user->full_name }}
                            </h1>
                            <p class="mb-0 opacity-75">
                                <i class="fas fa-hospital me-2"></i>{{ $doctor->hospital ?? 'Hôpital Principal' }}
                                <span class="mx-3">•</span>
                                <i class="fas fa-user-md me-2"></i>{{ $doctor->speciality ?? 'Médecine Générale' }}
                            </p>
                        </div>
                    </div>
                    <div class="col-md-4 text-end">
                        <div class="d-flex justify-content-end align-items-center">
                            <div class="me-3">
                                <small class="opacity-75">Dernière connexion</small><br>
                                <span class="fw-semibold">{{ now()->format('d/m/Y à H:i') }}</span>
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-user-circle me-2"></i>Menu
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="#"><i class="fas fa-user-edit me-2"></i>Mon Profil</a></li>
                                    <li><a class="dropdown-item" href="#"><i class="fas fa-calendar-alt me-2"></i>Planning</a></li>
                                    <li><a class="dropdown-item" href="#"><i class="fas fa-chart-bar me-2"></i>Statistiques</a></li>
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

            <!-- Stats Cards -->
            <div class="row mb-4">
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="stats-card">
                        <div class="d-flex align-items-center">
                            <div class="stats-icon bg-primary text-white">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h3 class="mb-1 fw-bold text-primary">{{ $appointments->count() }}</h3>
                                <p class="mb-0 text-muted">Total Rendez-vous</p>
                                <small class="text-success">
                                    <i class="fas fa-arrow-up"></i> +12% ce mois
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
                                <h3 class="mb-1 fw-bold" style="color: var(--warning-color);">{{ $pendingAppointments->count() }}</h3>
                                <p class="mb-0 text-muted">En attente</p>
                                <small class="text-warning">
                                    <i class="fas fa-exclamation-circle"></i> Nécessite action
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="stats-card">
                        <div class="d-flex align-items-center">
                            <div class="stats-icon" style="background: linear-gradient(135deg, var(--success-color), #10b981); color: white;">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h3 class="mb-1 fw-bold text-success">{{ $confirmedAppointments->count() }}</h3>
                                <p class="mb-0 text-muted">Confirmés</p>
                                <small class="text-success">
                                    <i class="fas fa-calendar-check"></i> Planifiés
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="stats-card">
                        <div class="d-flex align-items-center">
                            <div class="stats-icon" style="background: linear-gradient(135deg, var(--info-color), #06b6d4); color: white;">
                                <i class="fas fa-stethoscope"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h3 class="mb-1 fw-bold text-info">{{ $completedAppointments->count() }}</h3>
                                <p class="mb-0 text-muted">Terminés</p>
                                <small class="text-info">
                                    <i class="fas fa-check-double"></i> Consultations
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Area -->
            <div class="row">
                <div class="col-12">
                    <!-- Demandes en attente -->
                    @if($pendingAppointments->count() > 0)
                    <div class="appointment-card">
                        <div class="card-header-custom" style="background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); color: #92400e;">
                            <h5 class="mb-0">
                                <i class="fas fa-clock me-2"></i>
                                Demandes en attente
                                <span class="status-badge" style="background: #f59e0b; color: white;">{{ $pendingAppointments->count() }}</span>
                            </h5>
                        </div>
                        <div class="p-0">
                            @foreach($pendingAppointments as $appointment)
                            <div class="appointment-row">
                                <div class="row align-items-center">
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
                                        <div class="text-center">
                                            <div class="fw-semibold text-primary">{{ $appointment->appointment_date->format('d/m/Y') }}</div>
                                            <small class="text-muted">{{ $appointment->appointment_date->format('H:i') }}</small>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <p class="mb-1">{{ Str::limit($appointment->reason, 50) ?: 'Aucun motif spécifié' }}</p>
                                        <small class="text-muted">
                                            <i class="fas fa-phone me-1"></i>{{ $appointment->patient->user->phone ?? 'N/A' }}
                                        </small>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="d-flex flex-wrap gap-1">
                                            <button class="btn btn-success btn-action btn-sm" onclick="updateAppointmentStatus({{ $appointment->id }}, 'confirmed')">
                                                <i class="fas fa-check"></i> Confirmer
                                            </button>
                                            <button class="btn btn-info btn-action btn-sm" onclick="openConsultationModal({{ $appointment->id }})">
                                                <i class="fas fa-stethoscope"></i> Consultation
                                            </button>
                                            <button class="btn btn-warning btn-action btn-sm" onclick="openPrescriptionModal({{ $appointment->id }})">
                                                <i class="fas fa-prescription-bottle-alt"></i> Prescription
                                            </button>
                                            <button class="btn btn-danger btn-action btn-sm" onclick="updateAppointmentStatus({{ $appointment->id }}, 'cancelled')">
                                                <i class="fas fa-times"></i> Refuser
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Rendez-vous confirmés -->
                    @if($confirmedAppointments->count() > 0)
                    <div class="appointment-card">
                        <div class="card-header-custom" style="background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%); color: #065f46;">
                            <h5 class="mb-0">
                                <i class="fas fa-check-circle me-2"></i>
                                Rendez-vous confirmés
                                <span class="status-badge" style="background: #059669; color: white;">{{ $confirmedAppointments->count() }}</span>
                            </h5>
                        </div>
                        <div class="p-0">
                            @foreach($confirmedAppointments as $appointment)
                            <div class="appointment-row">
                                <div class="row align-items-center">
                                    <div class="col-md-3">
                                        <div class="d-flex align-items-center">
                                            <div class="patient-avatar" style="background: linear-gradient(135deg, #059669, #10b981);">
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
                                        <div class="text-center">
                                            <div class="fw-semibold text-success">{{ $appointment->appointment_date->format('d/m/Y') }}</div>
                                            <small class="text-muted">{{ $appointment->appointment_date->format('H:i') }}</small>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <p class="mb-1">{{ Str::limit($appointment->reason, 50) ?: 'Aucun motif spécifié' }}</p>
                                        <small class="text-muted">
                                            <i class="fas fa-phone me-1"></i>{{ $appointment->patient->user->phone ?? 'N/A' }}
                                        </small>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="d-flex flex-wrap gap-1">
                                            <button class="btn btn-info btn-action btn-sm" onclick="openConsultationModal({{ $appointment->id }})">
                                                <i class="fas fa-stethoscope"></i> Consultation
                                            </button>
                                            <button class="btn btn-warning btn-action btn-sm" onclick="openPrescriptionModal({{ $appointment->id }})">
                                                <i class="fas fa-prescription-bottle-alt"></i> Prescription
                                            </button>
                                            <button class="btn btn-success btn-action btn-sm" onclick="updateAppointmentStatus({{ $appointment->id }}, 'completed')">
                                                <i class="fas fa-check-double"></i> Terminer
                                            </button>
                                            <button class="btn btn-danger btn-action btn-sm" onclick="updateAppointmentStatus({{ $appointment->id }}, 'cancelled')">
                                                <i class="fas fa-ban"></i> Annuler
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Historique (terminés et annulés) -->
                    @if($completedAppointments->count() > 0 || $cancelledAppointments->count() > 0)
                    <div class="appointment-card">
                        <div class="card-header-custom">
                            <h5 class="mb-0">
                                <i class="fas fa-history me-2"></i>
                                Historique des rendez-vous
                                <span class="status-badge" style="background: var(--secondary-color); color: white;">
                                    {{ $completedAppointments->count() + $cancelledAppointments->count() }}
                                </span>
                            </h5>
                        </div>
                        <div class="p-0">
                            @foreach($completedAppointments->concat($cancelledAppointments)->sortByDesc('appointment_date')->take(5) as $appointment)
                            <div class="appointment-row">
                                <div class="row align-items-center">
                                    <div class="col-md-4">
                                        <div class="d-flex align-items-center">
                                            <div class="patient-avatar" style="background: var(--secondary-color);">
                                                {{ substr($appointment->patient->user->full_name ?? 'P', 0, 1) }}
                                            </div>
                                            <div>
                                                <h6 class="mb-1">{{ $appointment->patient->user->full_name ?? 'Patient inconnu' }}</h6>
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
                                        <p class="mb-0">{{ Str::limit($appointment->reason, 60) ?: 'Aucun motif spécifié' }}</p>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            @if($completedAppointments->count() + $cancelledAppointments->count() > 5)
                            <div class="text-center p-3">
                                <button class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-eye me-2"></i>Voir tout l'historique
                                </button>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Message si aucun rendez-vous -->
                    @if($appointments->count() == 0)
                    <div class="appointment-card">
                        <div class="empty-state">
                            <i class="fas fa-calendar-plus"></i>
                            <h4 class="mt-3 mb-2">Aucun rendez-vous pour le moment</h4>
                            <p class="mb-4">Vous n'avez pas encore de demandes de rendez-vous. Les nouvelles demandes apparaîtront ici.</p>
                            <button class="btn btn-primary">
                                <i class="fas fa-refresh me-2"></i>Actualiser
                            </button>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Consultation -->
    <div class="modal fade" id="consultationModal" tabindex="-1" aria-labelledby="consultationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="consultationModalLabel">
                        <i class="fas fa-stethoscope"></i> Nouvelle Consultation
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="consultationForm">
                        <input type="hidden" id="consultationAppointmentId" name="appointment_id">

                        <div class="mb-3">
                            <label for="consultationDate" class="form-label">Date de consultation</label>
                            <input type="datetime-local" class="form-control" id="consultationDate" name="consultation_date" required>
                        </div>

                        <div class="mb-3">
                            <label for="diagnosis" class="form-label">Diagnostic</label>
                            <textarea class="form-control" id="diagnosis" name="diagnosis" rows="4" required
                                      placeholder="Saisissez le diagnostic médical..."></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="consultationNotes" class="form-label">Notes de consultation</label>
                            <textarea class="form-control" id="consultationNotes" name="notes" rows="4"
                                      placeholder="Notes additionnelles, observations, recommandations..."></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-success" id="submitConsultation">
                        <i class="fas fa-save"></i> Enregistrer la consultation
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Prescription -->
    <div class="modal fade" id="prescriptionModal" tabindex="-1" aria-labelledby="prescriptionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="prescriptionModalLabel">
                        <i class="fas fa-prescription-bottle-alt"></i> Nouvelle Prescription
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="prescriptionForm">
                        <input type="hidden" id="prescriptionAppointmentId" name="appointment_id">
                        <input type="hidden" id="prescriptionDoctorId" name="doctor_id" value="{{ $doctor->id }}">
                        <input type="hidden" id="prescriptionPatientId" name="patient_id">

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="prescribedAt" class="form-label">Date de prescription</label>
                                <input type="datetime-local" class="form-control" id="prescribedAt" name="prescribed_at" required>
                            </div>
                            <div class="col-md-6">
                                <label for="prescriptionNotes" class="form-label">Notes générales</label>
                                <input type="text" class="form-control" id="prescriptionNotes" name="notes"
                                       placeholder="Notes sur la prescription...">
                            </div>
                        </div>

                        <hr>
                        <h6><i class="fas fa-pills"></i> Médicaments prescrits</h6>

                        <div id="medicationLines">
                            <!-- Les lignes de médicaments seront ajoutées ici -->
                        </div>

                        <button type="button" class="btn btn-outline-primary" id="addMedicationLine">
                            <i class="fas fa-plus"></i> Ajouter un médicament
                        </button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-warning" id="submitPrescription">
                        <i class="fas fa-prescription-bottle-alt"></i> Créer la prescription
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <script>
        let currentAppointmentData = null;
        let medicationLineCounter = 0;
        let availableMedications = [];

        // Charger les médicaments disponibles au chargement de la page
        document.addEventListener('DOMContentLoaded', async function() {
            try {
                const response = await axios.get('/api/medications');
                availableMedications = response.data.medications;
            } catch (error) {
                console.error('Erreur lors du chargement des médicaments:', error);
            }
        });

        // Fonction pour mettre à jour le statut d'un rendez-vous
        async function updateAppointmentStatus(appointmentId, newStatus) {
            const statusLabels = {
                'confirmed': 'confirmer',
                'cancelled': 'annuler',
                'completed': 'terminer'
            };

            const action = statusLabels[newStatus] || 'modifier';

            if (!confirm(`Êtes-vous sûr de vouloir ${action} ce rendez-vous ?`)) {
                return;
            }

            try {
                const response = await axios.patch(`/api/appointments/${appointmentId}/status`, {
                    status: newStatus
                });

                if (response.data.message) {
                    alert(response.data.message);
                    // Recharger la page pour voir les changements
                    location.reload();
                }
            } catch (error) {
                console.error('Erreur:', error);
                if (error.response && error.response.data && error.response.data.error) {
                    alert('Erreur: ' + error.response.data.error);
                } else {
                    alert('Erreur lors de la mise à jour du statut');
                }
            }
        }

        // Fonction pour ouvrir le modal de consultation
        async function openConsultationModal(appointmentId) {
            try {
                // Récupérer les détails du rendez-vous
                const response = await axios.get(`/api/appointments/doctor/{{ $doctor->id }}`);
                const appointment = response.data.appointments.find(apt => apt.id === appointmentId);

                if (appointment) {
                    currentAppointmentData = appointment;

                    // Pré-remplir le formulaire
                    document.getElementById('consultationAppointmentId').value = appointmentId;
                    document.getElementById('consultationDate').value = new Date().toISOString().slice(0, 16);

                    // Ouvrir le modal
                    const modal = new bootstrap.Modal(document.getElementById('consultationModal'));
                    modal.show();
                }
            } catch (error) {
                console.error('Erreur:', error);
                alert('Erreur lors de l\'ouverture du modal de consultation');
            }
        }

        // Fonction pour ouvrir le modal de prescription
        async function openPrescriptionModal(appointmentId) {
            try {
                // Récupérer les détails du rendez-vous
                const response = await axios.get(`/api/appointments/doctor/{{ $doctor->id }}`);
                const appointment = response.data.appointments.find(apt => apt.id === appointmentId);

                if (appointment) {
                    currentAppointmentData = appointment;

                    // Pré-remplir le formulaire
                    document.getElementById('prescriptionAppointmentId').value = appointmentId;
                    document.getElementById('prescriptionPatientId').value = appointment.patient.id;
                    document.getElementById('prescribedAt').value = new Date().toISOString().slice(0, 16);

                    // Réinitialiser les lignes de médicaments
                    document.getElementById('medicationLines').innerHTML = '';
                    medicationLineCounter = 0;

                    // Ajouter une première ligne
                    addMedicationLine();

                    // Ouvrir le modal
                    const modal = new bootstrap.Modal(document.getElementById('prescriptionModal'));
                    modal.show();
                }
            } catch (error) {
                console.error('Erreur:', error);
                alert('Erreur lors de l\'ouverture du modal de prescription');
            }
        }

        // Fonction pour ajouter une ligne de médicament
        function addMedicationLine() {
            medicationLineCounter++;
            const lineHtml = `
                <div class="medication-line border rounded p-3 mb-3" data-line="${medicationLineCounter}">
                    <div class="row">
                        <div class="col-md-4">
                            <label class="form-label">Médicament</label>
                            <select class="form-select medication-select" name="lines[${medicationLineCounter}][medication_id]" required>
                                <option value="">Sélectionner un médicament</option>
                                ${availableMedications.map(med =>
                                    `<option value="${med.id}">${med.display_name || med.full_name || med.name}</option>`
                                ).join('')}
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Dosage</label>
                            <input type="text" class="form-control" name="lines[${medicationLineCounter}][dosage]"
                                   placeholder="Ex: 500mg" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Fréquence</label>
                            <input type="text" class="form-control" name="lines[${medicationLineCounter}][frequency]"
                                   placeholder="Ex: 3x/jour" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Durée (jours)</label>
                            <input type="number" class="form-control" name="lines[${medicationLineCounter}][duration_days]"
                                   placeholder="7" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Action</label>
                            <button type="button" class="btn btn-danger btn-sm w-100" onclick="removeMedicationLine(${medicationLineCounter})">
                                <i class="fas fa-trash"></i> Supprimer
                            </button>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-6">
                            <label class="form-label">Instructions</label>
                            <input type="text" class="form-control" name="lines[${medicationLineCounter}][instructions]"
                                   placeholder="Ex: Prendre après les repas">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Date début</label>
                            <input type="date" class="form-control" name="lines[${medicationLineCounter}][start_date]" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Date fin</label>
                            <input type="date" class="form-control" name="lines[${medicationLineCounter}][end_date]" required>
                        </div>
                    </div>
                </div>
            `;

            document.getElementById('medicationLines').insertAdjacentHTML('beforeend', lineHtml);

            // Définir les dates par défaut
            const today = new Date().toISOString().split('T')[0];
            const endDate = new Date();
            endDate.setDate(endDate.getDate() + 7);
            const endDateStr = endDate.toISOString().split('T')[0];

            const currentLine = document.querySelector(`[data-line="${medicationLineCounter}"]`);
            currentLine.querySelector('input[name*="[start_date]"]').value = today;
            currentLine.querySelector('input[name*="[end_date]"]').value = endDateStr;
        }

        // Fonction pour supprimer une ligne de médicament
        function removeMedicationLine(lineNumber) {
            const line = document.querySelector(`[data-line="${lineNumber}"]`);
            if (line) {
                line.remove();
            }
        }

        // Fonction pour afficher les détails d'un rendez-vous
        function showAppointmentDetails(appointmentId) {
            console.log('Affichage des détails pour le rendez-vous:', appointmentId);
        }

        // Auto-refresh de la page toutes les 5 minutes pour voir les nouvelles demandes
        setInterval(() => {
            location.reload();
        }, 5 * 60 * 1000); // 5 minutes

        // Notification au chargement de la page s'il y a des demandes en attente
        document.addEventListener('DOMContentLoaded', function() {
            const pendingCount = {{ $pendingAppointments->count() }};
            if (pendingCount > 0) {
                // Créer une notification discrète
                const notification = document.createElement('div');
                notification.className = 'alert alert-warning alert-dismissible fade show position-fixed';
                notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
                notification.innerHTML = `
                    <strong><i class="fas fa-bell"></i> Nouvelles demandes !</strong><br>
                    Vous avez ${pendingCount} demande(s) de rendez-vous en attente.
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;

                document.body.appendChild(notification);

                // Auto-remove après 10 secondes
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.remove();
                    }
                }, 10000);
            }
        });

        // Fonction pour soumettre la consultation
        async function submitConsultation() {
            const form = document.getElementById('consultationForm');
            const formData = new FormData(form);

            try {
                const response = await axios.post('/api/consultations', {
                    appointment_id: formData.get('appointment_id'),
                    consultation_date: formData.get('consultation_date'),
                    diagnosis: formData.get('diagnosis'),
                    notes: formData.get('notes')
                });

                alert('Consultation enregistrée avec succès!');
                bootstrap.Modal.getInstance(document.getElementById('consultationModal')).hide();
                location.reload();
            } catch (error) {
                console.error('Erreur:', error);
                if (error.response && error.response.data && error.response.data.error) {
                    alert('Erreur: ' + error.response.data.error);
                } else {
                    alert('Erreur lors de l\'enregistrement de la consultation');
                }
            }
        }

        // Fonction pour soumettre la prescription
        async function submitPrescription() {
            const form = document.getElementById('prescriptionForm');
            const formData = new FormData(form);

            // Collecter les données de prescription
            const prescriptionData = {
                appointment_id: formData.get('appointment_id'),
                doctor_id: formData.get('doctor_id'),
                patient_id: formData.get('patient_id'),
                prescribed_at: formData.get('prescribed_at'),
                notes: formData.get('notes'),
                lines: []
            };

            // Collecter les lignes de médicaments
            const medicationLines = document.querySelectorAll('.medication-line');
            medicationLines.forEach((line, index) => {
                const lineData = {
                    medication_id: line.querySelector('select[name*="[medication_id]"]').value,
                    dosage: line.querySelector('input[name*="[dosage]"]').value,
                    frequency: line.querySelector('input[name*="[frequency]"]').value,
                    duration_days: line.querySelector('input[name*="[duration_days]"]').value,
                    instructions: line.querySelector('input[name*="[instructions]"]').value,
                    start_date: line.querySelector('input[name*="[start_date]"]').value,
                    end_date: line.querySelector('input[name*="[end_date]"]').value
                };

                if (lineData.medication_id && lineData.dosage && lineData.frequency) {
                    prescriptionData.lines.push(lineData);
                }
            });

            if (prescriptionData.lines.length === 0) {
                alert('Veuillez ajouter au moins un médicament à la prescription');
                return;
            }

            console.log('Données à envoyer:', prescriptionData);

            try {
                // Créer d'abord la prescription
                const response = await axios.post('/api/prescriptions', {
                    appointment_id: prescriptionData.appointment_id,
                    doctor_id: prescriptionData.doctor_id,
                    patient_id: prescriptionData.patient_id,
                    prescribed_at: prescriptionData.prescribed_at,
                    notes: prescriptionData.notes
                });

                console.log('Prescription créée:', response.data);
                const prescriptionId = response.data.prescription.id;

                // Créer les lignes de prescription
                let linesCreated = 0;
                for (const line of prescriptionData.lines) {
                    try {
                        await axios.post('/api/prescription-lines', {
                            prescription_id: prescriptionId,
                            ...line
                        });
                        linesCreated++;
                    } catch (lineError) {
                        console.error('Erreur ligne:', lineError);
                    }
                }

                alert(`Prescription créée avec succès! ${linesCreated} médicament(s) ajouté(s).`);
                bootstrap.Modal.getInstance(document.getElementById('prescriptionModal')).hide();
                location.reload();
            } catch (error) {
                console.error('Erreur complète:', error);
                console.error('Réponse erreur:', error.response);

                if (error.response && error.response.data) {
                    if (error.response.data.errors) {
                        // Erreurs de validation Laravel
                        const errors = Object.values(error.response.data.errors).flat();
                        alert('Erreurs de validation:\n' + errors.join('\n'));
                    } else if (error.response.data.message) {
                        alert('Erreur: ' + error.response.data.message);
                    } else {
                        alert('Erreur: ' + JSON.stringify(error.response.data));
                    }
                } else {
                    alert('Erreur lors de la création de la prescription');
                }
            }
        }

        // Event listeners
        document.getElementById('submitConsultation').addEventListener('click', submitConsultation);
        document.getElementById('submitPrescription').addEventListener('click', submitPrescription);
        document.getElementById('addMedicationLine').addEventListener('click', addMedicationLine);
    </script>
</body>
</html>
