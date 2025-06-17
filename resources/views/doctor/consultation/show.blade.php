<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultation - MediCare Pro</title>
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
            background: linear-gradient(135deg, var(--primary-color) 0%, #3b82f6 100%);
            color: white;
            padding: 2rem;
            border-radius: 20px 20px 0 0;
        }

        .consultation-card {
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
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), #3b82f6);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 1.5rem;
        }

        .info-item {
            padding: 1rem 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), #3b82f6);
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

        .btn-secondary {
            background: linear-gradient(135deg, var(--secondary-color), #6b7280);
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

        .btn-danger {
            background: linear-gradient(135deg, var(--danger-color), #ef4444);
            border: none;
            border-radius: 12px;
            padding: 0.75rem 2rem;
            font-weight: 500;
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
                            <i class="fas fa-stethoscope me-3"></i>
                            Consultation
                        </h1>
                        <p class="mb-0 opacity-75">
                            <i class="fas fa-calendar me-2"></i>{{ $consultation->consultation_date->format('d/m/Y à H:i') }}
                            <span class="mx-3">•</span>
                            <i class="fas fa-user me-2"></i>{{ $consultation->appointment->patient->user->full_name }}
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
            <!-- Patient Info Card -->
            <div class="consultation-card">
                <div class="section-header">
                    <i class="fas fa-user me-2"></i>Informations Patient
                </div>
                <div class="p-4">
                    <div class="row align-items-center">
                        <div class="col-md-2">
                            <div class="patient-avatar">
                                {{ substr($consultation->appointment->patient->user->full_name, 0, 1) }}
                            </div>
                        </div>
                        <div class="col-md-10">
                            <h4 class="mb-1">{{ $consultation->appointment->patient->user->full_name }}</h4>
                            <div class="row">
                                <div class="col-md-4">
                                    <small class="text-muted">
                                        <i class="fas fa-envelope me-1"></i>{{ $consultation->appointment->patient->user->email }}
                                    </small>
                                </div>
                                <div class="col-md-4">
                                    <small class="text-muted">
                                        <i class="fas fa-phone me-1"></i>{{ $consultation->appointment->patient->user->phone ?? 'N/A' }}
                                    </small>
                                </div>
                                <div class="col-md-4">
                                    <small class="text-muted">
                                        <i class="fas fa-calendar me-1"></i>RDV: {{ $consultation->appointment->appointment_date->format('d/m/Y H:i') }}
                                    </small>
                                </div>
                            </div>
                            <div class="mt-2">
                                <strong>Motif du rendez-vous:</strong> {{ $consultation->appointment->reason ?: 'Non spécifié' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Consultation Details Card -->
            <div class="consultation-card">
                <div class="section-header">
                    <i class="fas fa-stethoscope me-2"></i>Détails de la Consultation
                </div>
                <div class="p-4">
                    <div class="info-item">
                        <h6 class="text-primary mb-2">
                            <i class="fas fa-diagnoses me-2"></i>Diagnostic
                        </h6>
                        <p class="mb-0">{{ $consultation->diagnosis }}</p>
                    </div>

                    @if($consultation->treatment)
                    <div class="info-item">
                        <h6 class="text-success mb-2">
                            <i class="fas fa-pills me-2"></i>Traitement
                        </h6>
                        <p class="mb-0">{{ $consultation->treatment }}</p>
                    </div>
                    @endif

                    @if($consultation->notes)
                    <div class="info-item">
                        <h6 class="text-info mb-2">
                            <i class="fas fa-notes-medical me-2"></i>Notes
                        </h6>
                        <p class="mb-0">{{ $consultation->notes }}</p>
                    </div>
                    @endif

                    <div class="info-item">
                        <h6 class="text-secondary mb-2">
                            <i class="fas fa-clock me-2"></i>Date de consultation
                        </h6>
                        <p class="mb-0">{{ $consultation->consultation_date->format('d/m/Y à H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Prescriptions Card -->
            @if($consultation->appointment->prescriptions->count() > 0)
            <div class="consultation-card">
                <div class="section-header">
                    <i class="fas fa-prescription-bottle me-2"></i>Prescriptions ({{ $consultation->appointment->prescriptions->count() }})
                </div>
                <div class="p-4">
                    @foreach($consultation->appointment->prescriptions as $prescription)
                    <div class="info-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">Prescription #{{ $prescription->id }}</h6>
                                <small class="text-muted">
                                    <i class="fas fa-calendar me-1"></i>{{ $prescription->prescribed_at->format('d/m/Y à H:i') }}
                                    <span class="mx-2">•</span>
                                    <span class="badge bg-{{ $prescription->status === 'active' ? 'success' : ($prescription->status === 'draft' ? 'warning' : 'secondary') }}">
                                        {{ ucfirst($prescription->status) }}
                                    </span>
                                </small>
                                @if($prescription->notes)
                                <p class="mt-2 mb-0">{{ $prescription->notes }}</p>
                                @endif
                            </div>
                            <div>
                                <a href="{{ route('doctor.prescription.show', $prescription->id) }}" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-eye me-1"></i>Voir
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Actions -->
            <div class="consultation-card">
                <div class="p-4">
                    <div class="d-flex gap-3 flex-wrap">
                        @if($consultation->appointment->prescriptions->count() == 0)
                        <a href="{{ route('doctor.prescription.create', $consultation->id) }}" class="btn btn-warning">
                            <i class="fas fa-prescription-bottle me-2"></i>Créer une Prescription
                        </a>
                        @endif

                        <a href="{{ route('doctor.consultation.edit', $consultation->id) }}" class="btn btn-info">
                            <i class="fas fa-edit me-2"></i>Modifier
                        </a>

                        <a href="{{ route('doctor.planning') }}" class="btn btn-secondary">
                            <i class="fas fa-calendar me-2"></i>Retour au Planning
                        </a>

                        <a href="{{ route('doctor.consultations') }}" class="btn btn-primary">
                            <i class="fas fa-list me-2"></i>Toutes les Consultations
                        </a>

                        <form action="{{ route('doctor.consultation.delete', $consultation->id) }}" method="POST" style="display: inline;"
                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette consultation ? Cette action ne peut pas être annulée et supprimera également toutes les prescriptions associées.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash me-2"></i>Supprimer
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
