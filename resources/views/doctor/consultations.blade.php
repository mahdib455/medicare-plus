<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Mes Consultations - MediCare+</title>
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
            --light-bg: #f8fafc;
            --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--light-bg);
            color: #1f2937;
        }

        .header-section {
            background: linear-gradient(135deg, var(--primary-color), #3b82f6);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
        }

        .consultation-card {
            background: white;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
            border: 1px solid #e5e7eb;
        }

        .consultation-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .consultation-header {
            background: linear-gradient(135deg, #f8fafc, #e2e8f0);
            border-radius: 12px 12px 0 0;
            padding: 1.5rem;
            border-bottom: 1px solid #e5e7eb;
        }

        .consultation-body {
            padding: 1.5rem;
        }

        .consultation-footer {
            background: #f9fafb;
            border-radius: 0 0 12px 12px;
            padding: 1rem 1.5rem;
            border-top: 1px solid #e5e7eb;
        }

        .status-badge {
            padding: 0.375rem 0.75rem;
            border-radius: 6px;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .status-completed {
            background: #d1fae5;
            color: #065f46;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .status-cancelled {
            background: #fee2e2;
            color: #991b1b;
        }

        .btn-action {
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.3s ease;
            border: none;
            margin: 0.25rem;
        }

        .btn-view {
            background: var(--primary-color);
            color: white;
        }

        .btn-view:hover {
            background: #1d4ed8;
            color: white;
        }

        .btn-edit {
            background: var(--warning-color);
            color: white;
        }

        .btn-edit:hover {
            background: #b45309;
            color: white;
        }

        .btn-prescription {
            background: var(--success-color);
            color: white;
        }

        .btn-prescription:hover {
            background: #047857;
            color: white;
        }

        .search-section {
            background: white;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .stats-card {
            background: white;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            padding: 1.5rem;
            text-align: center;
            border: 1px solid #e5e7eb;
        }

        .stats-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-color);
        }

        .stats-label {
            color: var(--secondary-color);
            font-size: 0.875rem;
            margin-top: 0.5rem;
        }

        .patient-info {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
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

        .diagnosis-text {
            background: #f0f9ff;
            border-left: 4px solid var(--primary-color);
            padding: 1rem;
            border-radius: 0 8px 8px 0;
            margin: 1rem 0;
        }

        .filter-section {
            display: flex;
            gap: 1rem;
            align-items: center;
            flex-wrap: wrap;
        }

        @media (max-width: 768px) {
            .filter-section {
                flex-direction: column;
                align-items: stretch;
            }
            
            .consultation-header {
                padding: 1rem;
            }
            
            .consultation-body {
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
    <!-- Header Section -->
    <div class="header-section">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-1">
                    <x-logo size="small" :showText="false" class="justify-content-start" />
                </div>
                <div class="col-md-8">
                    <h1 class="mb-2">
                        <i class="fas fa-notes-medical me-3"></i>
                        Mes Consultations
                    </h1>
                    <p class="mb-0 opacity-75">
                        <i class="fas fa-user-md me-2"></i>Dr. {{ $user->full_name }}
                        <span class="mx-3">•</span>
                        <i class="fas fa-calendar me-2"></i>{{ $consultations->count() }} consultation(s) au total
                    </p>
                </div>
                <div class="col-md-3 text-end">
                    <a href="{{ route('doctor.dashboard') }}" class="btn btn-light">
                        <i class="fas fa-arrow-left me-2"></i>Retour au Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <!-- Statistics Row -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-number">{{ $consultations->count() }}</div>
                    <div class="stats-label">Total Consultations</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-number">{{ $consultations->where('status', 'completed')->count() }}</div>
                    <div class="stats-label">Terminées</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-number">{{ $consultations->filter(function($c) { return $c->consultation_date->isCurrentMonth(); })->count() }}</div>
                    <div class="stats-label">Ce Mois</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-number">{{ $consultations->filter(function($c) { return $c->appointment->prescriptions->count() > 0; })->count() }}</div>
                    <div class="stats-label">Avec Prescription</div>
                </div>
            </div>
        </div>

        <!-- Search and Filter Section -->
        <div class="search-section">
            <div class="row">
                <div class="col-md-12">
                    <h5 class="mb-3">
                        <i class="fas fa-search me-2"></i>Rechercher et Filtrer
                    </h5>
                    <div class="filter-section">
                        <div class="flex-grow-1">
                            <input type="text" class="form-control" id="searchInput" placeholder="Rechercher par nom de patient, diagnostic...">
                        </div>
                        <select class="form-select" id="statusFilter" style="width: auto;">
                            <option value="">Tous les statuts</option>
                            <option value="completed">Terminées</option>
                            <option value="pending">En attente</option>
                            <option value="cancelled">Annulées</option>
                        </select>
                        <select class="form-select" id="monthFilter" style="width: auto;">
                            <option value="">Tous les mois</option>
                            @for($i = 0; $i < 12; $i++)
                                @php $month = now()->subMonths($i) @endphp
                                <option value="{{ $month->format('Y-m') }}">{{ $month->format('F Y') }}</option>
                            @endfor
                        </select>
                        <button class="btn btn-outline-secondary" onclick="clearFilters()">
                            <i class="fas fa-times me-1"></i>Effacer
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Consultations List -->
        <div class="row">
            <div class="col-md-12">
                <div id="consultationsList">
                    @forelse($consultations as $consultation)
                        <div class="consultation-card" data-consultation-id="{{ $consultation->id }}">
                            <div class="consultation-header">
                                <div class="row align-items-center">
                                    <div class="col-md-8">
                                        <div class="patient-info">
                                            <div class="patient-avatar">
                                                {{ substr($consultation->appointment->patient->user->first_name, 0, 1) }}{{ substr($consultation->appointment->patient->user->last_name, 0, 1) }}
                                            </div>
                                            <div>
                                                <h6 class="mb-1">{{ $consultation->appointment->patient->user->full_name }}</h6>
                                                <small class="text-muted">
                                                    <i class="fas fa-calendar me-1"></i>{{ $consultation->consultation_date->format('d/m/Y à H:i') }}
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 text-end">
                                        <span class="status-badge status-{{ $consultation->status }}">
                                            @if($consultation->status === 'completed')
                                                <i class="fas fa-check-circle me-1"></i>Terminée
                                            @elseif($consultation->status === 'pending')
                                                <i class="fas fa-clock me-1"></i>En attente
                                            @else
                                                <i class="fas fa-times-circle me-1"></i>Annulée
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="consultation-body">
                                @if($consultation->diagnosis)
                                    <div class="diagnosis-text">
                                        <strong><i class="fas fa-stethoscope me-2"></i>Diagnostic :</strong>
                                        {{ $consultation->diagnosis }}
                                    </div>
                                @endif

                                @if($consultation->treatment_plan)
                                    <div class="mb-3">
                                        <strong><i class="fas fa-prescription me-2"></i>Plan de traitement :</strong>
                                        <p class="mb-0 mt-1">{{ $consultation->treatment_plan }}</p>
                                    </div>
                                @endif

                                @if($consultation->notes)
                                    <div class="mb-3">
                                        <strong><i class="fas fa-sticky-note me-2"></i>Notes :</strong>
                                        <p class="mb-0 mt-1">{{ $consultation->notes }}</p>
                                    </div>
                                @endif

                                <!-- Prescription Info -->
                                @if($consultation->appointment->prescriptions->count() > 0)
                                    <div class="alert alert-success">
                                        <i class="fas fa-prescription-bottle-alt me-2"></i>
                                        <strong>Prescription créée</strong> - {{ $consultation->appointment->prescriptions->count() }} prescription(s)
                                    </div>
                                @endif
                            </div>

                            <div class="consultation-footer">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <small class="text-muted">
                                            <i class="fas fa-clock me-1"></i>Créée le {{ $consultation->created_at->format('d/m/Y à H:i') }}
                                        </small>
                                    </div>
                                    <div>
                                        <a href="{{ route('doctor.consultation.show', $consultation->id) }}" class="btn btn-action btn-view">
                                            <i class="fas fa-eye me-1"></i>Voir
                                        </a>
                                        <a href="{{ route('doctor.consultation.edit', $consultation->id) }}" class="btn btn-action btn-edit">
                                            <i class="fas fa-edit me-1"></i>Modifier
                                        </a>
                                        @if($consultation->appointment->prescriptions->count() === 0)
                                            <a href="{{ route('doctor.prescription.create', $consultation->id) }}" class="btn btn-action btn-prescription">
                                                <i class="fas fa-prescription-bottle me-1"></i>Prescription
                                            </a>
                                        @else
                                            <a href="{{ route('doctor.prescription.show', $consultation->appointment->prescriptions->first()->id) }}" class="btn btn-action btn-prescription">
                                                <i class="fas fa-prescription-bottle-alt me-1"></i>Voir Prescription
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="consultation-card">
                            <div class="consultation-body text-center py-5">
                                <i class="fas fa-notes-medical fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">Aucune consultation trouvée</h5>
                                <p class="text-muted">Vous n'avez pas encore de consultations enregistrées.</p>
                                <a href="{{ route('doctor.dashboard') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>Commencer une consultation
                                </a>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Search and filter functionality
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const statusFilter = document.getElementById('statusFilter');
            const monthFilter = document.getElementById('monthFilter');
            const consultationCards = document.querySelectorAll('.consultation-card');

            function filterConsultations() {
                const searchTerm = searchInput.value.toLowerCase();
                const statusValue = statusFilter.value;
                const monthValue = monthFilter.value;

                consultationCards.forEach(card => {
                    const consultationText = card.textContent.toLowerCase();
                    const statusBadge = card.querySelector('.status-badge');
                    const consultationDate = card.querySelector('small').textContent;

                    let showCard = true;

                    // Search filter
                    if (searchTerm && !consultationText.includes(searchTerm)) {
                        showCard = false;
                    }

                    // Status filter
                    if (statusValue && !statusBadge.classList.contains('status-' + statusValue)) {
                        showCard = false;
                    }

                    // Month filter (simplified - you might want to improve this)
                    if (monthValue && !consultationDate.includes(monthValue.split('-')[1])) {
                        showCard = false;
                    }

                    card.style.display = showCard ? 'block' : 'none';
                });
            }

            // Event listeners
            searchInput.addEventListener('input', filterConsultations);
            statusFilter.addEventListener('change', filterConsultations);
            monthFilter.addEventListener('change', filterConsultations);
        });

        function clearFilters() {
            document.getElementById('searchInput').value = '';
            document.getElementById('statusFilter').value = '';
            document.getElementById('monthFilter').value = '';
            
            // Show all cards
            document.querySelectorAll('.consultation-card').forEach(card => {
                card.style.display = 'block';
            });
        }
    </script>
</body>
</html>
