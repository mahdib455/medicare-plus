<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion CRUD - Rendez-vous Terminé - MediCare Pro</title>
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
            background: linear-gradient(135deg, var(--success-color) 0%, #10b981 100%);
            color: white;
            padding: 2rem;
            border-radius: 20px 20px 0 0;
        }

        .crud-card {
            background: white;
            border-radius: 16px;
            box-shadow: var(--card-shadow);
            border: none;
            margin-bottom: 2rem;
            overflow: hidden;
        }

        .section-header {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            padding: 1rem 1.5rem;
            border-bottom: 2px solid #e2e8f0;
            font-weight: 600;
        }

        .patient-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--success-color), #10b981);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 2rem;
        }

        .action-card {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
        }

        .action-card:hover {
            border-color: var(--primary-color);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), #3b82f6);
            border: none;
            border-radius: 12px;
            padding: 0.75rem 2rem;
            font-weight: 500;
        }

        .btn-info {
            background: linear-gradient(135deg, var(--info-color), #06b6d4);
            border: none;
            border-radius: 12px;
            padding: 0.75rem 2rem;
            font-weight: 500;
        }

        .btn-warning {
            background: linear-gradient(135deg, var(--warning-color), #f59e0b);
            border: none;
            border-radius: 12px;
            padding: 0.75rem 2rem;
            font-weight: 500;
        }

        .btn-danger {
            background: linear-gradient(135deg, var(--danger-color), #ef4444);
            border: none;
            border-radius: 12px;
            padding: 0.75rem 2rem;
            font-weight: 500;
        }

        .btn-secondary {
            background: linear-gradient(135deg, var(--secondary-color), #6b7280);
            border: none;
            border-radius: 12px;
            padding: 0.75rem 2rem;
            font-weight: 500;
        }

        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .status-completed {
            background: #d1fae5;
            color: #065f46;
        }

        .crud-icon {
            font-size: 2rem;
            margin-bottom: 1rem;
        }

        .prescription-summary {
            background: #fef3c7;
            border: 2px solid #f59e0b;
            border-radius: 12px;
            padding: 1rem;
        }

        .consultation-summary {
            background: #dbeafe;
            border: 2px solid #3b82f6;
            border-radius: 12px;
            padding: 1rem;
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
                        <h1 class="mb-2">
                            <i class="fas fa-cogs me-3"></i>
                            Gestion CRUD - Rendez-vous Terminé
                        </h1>
                        <p class="mb-0 opacity-75">
                            <i class="fas fa-user me-2"></i>{{ $appointment->patient->user->full_name }}
                            <span class="mx-3">•</span>
                            <i class="fas fa-calendar me-2"></i>{{ $appointment->appointment_date->format('d/m/Y à H:i') }}
                            <span class="mx-3">•</span>
                            <span class="status-badge status-completed">
                                <i class="fas fa-check-double me-1"></i>Terminé
                            </span>
                        </p>
                    </div>
                    <div class="col-md-4 text-end">
                        <a href="{{ route('doctor.planning') }}" class="btn btn-light">
                            <i class="fas fa-arrow-left me-2"></i>Retour au Planning
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid py-4">
            <!-- Messages -->
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

            <!-- Patient Summary -->
            <div class="crud-card">
                <div class="section-header">
                    <i class="fas fa-user me-2"></i>Résumé du Patient
                </div>
                <div class="p-4">
                    <div class="row align-items-center">
                        <div class="col-md-2">
                            <div class="patient-avatar">
                                {{ substr($appointment->patient->user->full_name, 0, 1) }}
                            </div>
                        </div>
                        <div class="col-md-10">
                            <h3 class="mb-2">{{ $appointment->patient->user->full_name }}</h3>
                            <div class="row">
                                <div class="col-md-4">
                                    <p class="mb-1"><strong>Email:</strong> {{ $appointment->patient->user->email }}</p>
                                </div>
                                <div class="col-md-4">
                                    <p class="mb-1"><strong>Téléphone:</strong> {{ $appointment->patient->user->phone ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-4">
                                    <p class="mb-1"><strong>Date RDV:</strong> {{ $appointment->appointment_date->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                            <p class="mb-0"><strong>Motif:</strong> {{ $appointment->reason ?: 'Non spécifié' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CRUD Actions -->
            <div class="row">
                <!-- Consultation CRUD -->
                <div class="col-md-6">
                    <div class="crud-card">
                        <div class="section-header">
                            <i class="fas fa-stethoscope me-2"></i>Gestion de la Consultation
                        </div>
                        <div class="p-4">
                            <div class="text-center mb-4">
                                <i class="fas fa-stethoscope crud-icon text-primary"></i>
                                <h4>Consultation</h4>
                            </div>

                            @if($appointment->consultation)
                            <div class="consultation-summary mb-4">
                                <h6><i class="fas fa-diagnoses me-2"></i>Diagnostic:</h6>
                                <p class="mb-2">{{ Str::limit($appointment->consultation->diagnosis, 100) }}</p>
                                <small class="text-muted">
                                    <i class="fas fa-clock me-1"></i>{{ $appointment->consultation->consultation_date->format('d/m/Y à H:i') }}
                                </small>
                            </div>

                            <div class="d-grid gap-2">
                                <a href="{{ route('doctor.consultation.show', $appointment->consultation->id) }}" class="btn btn-info">
                                    <i class="fas fa-eye me-2"></i>Voir la Consultation
                                </a>
                                <a href="{{ route('doctor.consultation.edit', $appointment->consultation->id) }}" class="btn btn-primary">
                                    <i class="fas fa-edit me-2"></i>Modifier la Consultation
                                </a>
                                <form action="{{ route('doctor.consultation.delete', $appointment->consultation->id) }}" method="POST" 
                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette consultation ? Cette action supprimera également toutes les prescriptions associées.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger w-100">
                                        <i class="fas fa-trash me-2"></i>Supprimer la Consultation
                                    </button>
                                </form>
                            </div>
                            @else
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>Aucune consultation trouvée pour ce rendez-vous.
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Prescription CRUD -->
                <div class="col-md-6">
                    <div class="crud-card">
                        <div class="section-header">
                            <i class="fas fa-prescription-bottle me-2"></i>Gestion des Prescriptions
                        </div>
                        <div class="p-4">
                            <div class="text-center mb-4">
                                <i class="fas fa-prescription-bottle crud-icon text-warning"></i>
                                <h4>Prescriptions ({{ $appointment->prescriptions->count() }})</h4>
                            </div>

                            @if($appointment->prescriptions->count() > 0)
                                @foreach($appointment->prescriptions as $prescription)
                                <div class="prescription-summary mb-3">
                                    <h6><i class="fas fa-prescription-bottle-alt me-2"></i>Prescription #{{ $prescription->id }}</h6>
                                    <p class="mb-2">
                                        <strong>Statut:</strong> 
                                        <span class="badge bg-{{ $prescription->status === 'active' ? 'success' : ($prescription->status === 'draft' ? 'warning' : 'secondary') }}">
                                            {{ ucfirst($prescription->status) }}
                                        </span>
                                    </p>
                                    <p class="mb-2"><strong>Médicaments:</strong> {{ $prescription->lines->count() }} ligne(s)</p>
                                    <small class="text-muted">
                                        <i class="fas fa-clock me-1"></i>{{ $prescription->prescribed_at->format('d/m/Y à H:i') }}
                                    </small>
                                    
                                    <div class="d-grid gap-1 mt-3">
                                        <a href="{{ route('doctor.prescription.show', $prescription->id) }}" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye me-1"></i>Voir
                                        </a>
                                        <a href="{{ route('doctor.prescription.edit', $prescription->id) }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-edit me-1"></i>Modifier
                                        </a>
                                        <form action="{{ route('doctor.prescription.delete', $prescription->id) }}" method="POST" 
                                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette prescription ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm w-100">
                                                <i class="fas fa-trash me-1"></i>Supprimer
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                @endforeach

                                @if($appointment->consultation)
                                <div class="d-grid mt-3">
                                    <a href="{{ route('doctor.prescription.create', $appointment->consultation->id) }}" class="btn btn-warning">
                                        <i class="fas fa-plus me-2"></i>Ajouter une Prescription
                                    </a>
                                </div>
                                @endif
                            @else
                                @if($appointment->consultation)
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>Aucune prescription créée pour ce rendez-vous.
                                </div>
                                <div class="d-grid">
                                    <a href="{{ route('doctor.prescription.create', $appointment->consultation->id) }}" class="btn btn-warning">
                                        <i class="fas fa-plus me-2"></i>Créer une Prescription
                                    </a>
                                </div>
                                @else
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle me-2"></i>Une consultation est requise avant de créer une prescription.
                                </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="crud-card">
                <div class="section-header">
                    <i class="fas fa-bolt me-2"></i>Actions Rapides
                </div>
                <div class="p-4">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="d-grid">
                                <a href="{{ route('doctor.planning') }}" class="btn btn-secondary">
                                    <i class="fas fa-calendar me-2"></i>Planning
                                </a>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="d-grid">
                                <a href="{{ route('doctor.consultations') }}" class="btn btn-info">
                                    <i class="fas fa-stethoscope me-2"></i>Toutes les Consultations
                                </a>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="d-grid">
                                <a href="{{ route('doctor.prescriptions') }}" class="btn btn-warning">
                                    <i class="fas fa-prescription-bottle me-2"></i>Toutes les Prescriptions
                                </a>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="d-grid">
                                <a href="{{ route('doctor.dashboard') }}" class="btn btn-primary">
                                    <i class="fas fa-home me-2"></i>Dashboard
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
