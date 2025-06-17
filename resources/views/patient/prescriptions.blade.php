<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Mes Prescriptions - MediCare+</title>
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
            background: linear-gradient(135deg, var(--success-color), #10b981);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
        }

        .prescription-card {
            background: white;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
            border: 1px solid #e5e7eb;
            overflow: hidden;
        }

        .prescription-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .prescription-header {
            background: linear-gradient(135deg, #f0f9ff, #e0f2fe);
            padding: 1.5rem;
            border-bottom: 1px solid #e5e7eb;
        }

        .prescription-body {
            padding: 1.5rem;
        }

        .prescription-footer {
            background: #f9fafb;
            padding: 1rem 1.5rem;
            border-top: 1px solid #e5e7eb;
        }

        .status-badge {
            padding: 0.375rem 0.75rem;
            border-radius: 6px;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .status-active {
            background: #d1fae5;
            color: #065f46;
        }

        .status-completed {
            background: #dbeafe;
            color: #1e40af;
        }

        .status-cancelled {
            background: #fee2e2;
            color: #991b1b;
        }

        .status-draft {
            background: #fef3c7;
            color: #92400e;
        }

        .doctor-info {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
        }

        .doctor-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--success-color), #10b981);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            margin-right: 1rem;
        }

        .medication-item {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 0.75rem;
        }

        .medication-name {
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        .medication-details {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .medication-detail {
            background: white;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.875rem;
            border: 1px solid #e2e8f0;
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
            color: var(--success-color);
        }

        .stats-label {
            color: var(--secondary-color);
            font-size: 0.875rem;
            margin-top: 0.5rem;
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

        .btn-download {
            background: var(--primary-color);
            color: white;
        }

        .btn-download:hover {
            background: #1d4ed8;
            color: white;
        }

        .btn-print {
            background: var(--secondary-color);
            color: white;
        }

        .btn-print:hover {
            background: #475569;
            color: white;
        }

        .filter-section {
            display: flex;
            gap: 1rem;
            align-items: center;
            flex-wrap: wrap;
        }

        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
            color: var(--secondary-color);
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        @media (max-width: 768px) {
            .filter-section {
                flex-direction: column;
                align-items: stretch;
            }
            
            .prescription-header {
                padding: 1rem;
            }
            
            .prescription-body {
                padding: 1rem;
            }
            
            .medication-details {
                flex-direction: column;
                gap: 0.5rem;
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
                        <i class="fas fa-prescription-bottle-alt me-3"></i>
                        Mes Prescriptions
                    </h1>
                    <p class="mb-0 opacity-75">
                        <i class="fas fa-user me-2"></i>{{ $patient->user->full_name }}
                        <span class="mx-3">•</span>
                        <i class="fas fa-pills me-2"></i>{{ $prescriptions->count() }} prescription(s) au total
                    </p>
                </div>
                <div class="col-md-3 text-end">
                    <a href="{{ route('patient.dashboard') }}" class="btn btn-light">
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
                    <div class="stats-number">{{ $prescriptions->count() }}</div>
                    <div class="stats-label">Total Prescriptions</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-number">{{ $prescriptions->where('status', 'active')->count() }}</div>
                    <div class="stats-label">Actives</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-number">{{ $prescriptions->filter(function($p) { return $p->prescribed_at->isCurrentMonth(); })->count() }}</div>
                    <div class="stats-label">Ce Mois</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-number">{{ $prescriptions->sum(function($p) { return $p->lines->count(); }) }}</div>
                    <div class="stats-label">Médicaments Total</div>
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
                            <input type="text" class="form-control" id="searchInput" placeholder="Rechercher par docteur, médicament...">
                        </div>
                        <select class="form-select" id="statusFilter" style="width: auto;">
                            <option value="">Tous les statuts</option>
                            <option value="active">Actives</option>
                            <option value="completed">Terminées</option>
                            <option value="cancelled">Annulées</option>
                            <option value="draft">Brouillons</option>
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

        <!-- Prescriptions List -->
        <div class="row">
            <div class="col-md-12">
                <div id="prescriptionsList">
                    @forelse($prescriptions as $prescription)
                        <div class="prescription-card" data-prescription-id="{{ $prescription->id }}">
                            <div class="prescription-header">
                                <div class="row align-items-center">
                                    <div class="col-md-8">
                                        <div class="doctor-info">
                                            <div class="doctor-avatar">
                                                {{ substr($prescription->appointment->doctor->user->first_name, 0, 1) }}{{ substr($prescription->appointment->doctor->user->last_name, 0, 1) }}
                                            </div>
                                            <div>
                                                <h6 class="mb-1">Dr. {{ $prescription->appointment->doctor->user->full_name }}</h6>
                                                <small class="text-muted">
                                                    <i class="fas fa-stethoscope me-1"></i>{{ $prescription->appointment->doctor->speciality ?? 'Médecin' }}
                                                    <span class="mx-2">•</span>
                                                    <i class="fas fa-calendar me-1"></i>{{ $prescription->prescribed_at->format('d/m/Y à H:i') }}
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 text-end">
                                        <span class="status-badge status-{{ $prescription->status }}">
                                            @if($prescription->status === 'active')
                                                <i class="fas fa-check-circle me-1"></i>Active
                                            @elseif($prescription->status === 'completed')
                                                <i class="fas fa-flag-checkered me-1"></i>Terminée
                                            @elseif($prescription->status === 'cancelled')
                                                <i class="fas fa-times-circle me-1"></i>Annulée
                                            @else
                                                <i class="fas fa-edit me-1"></i>Brouillon
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="prescription-body">
                                @if($prescription->notes)
                                    <div class="mb-3">
                                        <strong><i class="fas fa-sticky-note me-2"></i>Notes du médecin :</strong>
                                        <p class="mb-0 mt-1">{{ $prescription->notes }}</p>
                                    </div>
                                @endif

                                <div class="mb-3">
                                    <strong><i class="fas fa-pills me-2"></i>Médicaments prescrits ({{ $prescription->lines->count() }}) :</strong>
                                    <div class="mt-2">
                                        @foreach($prescription->lines as $line)
                                            <div class="medication-item">
                                                <div class="medication-name">
                                                    <i class="fas fa-capsules me-2"></i>
                                                    {{ $line->medication->name ?? 'Médicament inconnu' }}
                                                </div>
                                                <div class="medication-details">
                                                    <span class="medication-detail">
                                                        <i class="fas fa-weight me-1"></i>
                                                        <strong>Dosage:</strong> {{ $line->dosage }}
                                                    </span>
                                                    <span class="medication-detail">
                                                        <i class="fas fa-clock me-1"></i>
                                                        <strong>Fréquence:</strong> {{ $line->frequency }}
                                                    </span>
                                                    <span class="medication-detail">
                                                        <i class="fas fa-calendar-days me-1"></i>
                                                        <strong>Durée:</strong> {{ $line->duration }} jours
                                                    </span>
                                                    @if($line->instructions)
                                                        <span class="medication-detail">
                                                            <i class="fas fa-info-circle me-1"></i>
                                                            <strong>Instructions:</strong> {{ $line->instructions }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="prescription-footer">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <small class="text-muted">
                                            <i class="fas fa-hospital me-1"></i>{{ $prescription->appointment->doctor->hospital ?? 'Hôpital' }}
                                            <span class="mx-2">•</span>
                                            <i class="fas fa-clock me-1"></i>Prescrite le {{ $prescription->prescribed_at->format('d/m/Y à H:i') }}
                                        </small>
                                    </div>
                                    <div>
                                        <a href="{{ route('patient.prescriptions.download', $prescription->id) }}" class="btn btn-action btn-download">
                                            <i class="fas fa-download me-1"></i>Télécharger
                                        </a>
                                        <a href="{{ route('patient.prescriptions.print', $prescription->id) }}" class="btn btn-action btn-print" target="_blank">
                                            <i class="fas fa-print me-1"></i>Imprimer
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="prescription-card">
                            <div class="empty-state">
                                <i class="fas fa-prescription-bottle-alt"></i>
                                <h5 class="text-muted">Aucune prescription trouvée</h5>
                                <p class="text-muted">Vous n'avez pas encore de prescriptions médicales.</p>
                                <a href="{{ route('patient.dashboard') }}" class="btn btn-primary">
                                    <i class="fas fa-calendar-plus me-2"></i>Prendre un rendez-vous
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
            const prescriptionCards = document.querySelectorAll('.prescription-card');

            function filterPrescriptions() {
                const searchTerm = searchInput.value.toLowerCase();
                const statusValue = statusFilter.value;
                const monthValue = monthFilter.value;

                prescriptionCards.forEach(card => {
                    const prescriptionText = card.textContent.toLowerCase();
                    const statusBadge = card.querySelector('.status-badge');
                    const prescriptionDate = card.querySelector('small').textContent;

                    let showCard = true;

                    // Search filter
                    if (searchTerm && !prescriptionText.includes(searchTerm)) {
                        showCard = false;
                    }

                    // Status filter
                    if (statusValue && !statusBadge.classList.contains('status-' + statusValue)) {
                        showCard = false;
                    }

                    // Month filter (simplified)
                    if (monthValue && !prescriptionDate.includes(monthValue.split('-')[1])) {
                        showCard = false;
                    }

                    card.style.display = showCard ? 'block' : 'none';
                });
            }

            // Event listeners
            searchInput.addEventListener('input', filterPrescriptions);
            statusFilter.addEventListener('change', filterPrescriptions);
            monthFilter.addEventListener('change', filterPrescriptions);
        });

        function clearFilters() {
            document.getElementById('searchInput').value = '';
            document.getElementById('statusFilter').value = '';
            document.getElementById('monthFilter').value = '';
            
            // Show all cards
            document.querySelectorAll('.prescription-card').forEach(card => {
                card.style.display = 'block';
            });
        }


    </script>
</body>
</html>
