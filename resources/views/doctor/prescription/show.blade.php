<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prescription - MediCare Pro</title>
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
            background: linear-gradient(135deg, var(--warning-color) 0%, #f59e0b 100%);
            color: white;
            padding: 2rem;
            border-radius: 20px 20px 0 0;
        }

        .prescription-card {
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
            background: linear-gradient(135deg, var(--warning-color), #f59e0b);
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

        .prescription-content {
            background: #f8fafc;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 1.5rem;
            font-family: 'Courier New', monospace;
            white-space: pre-line;
            line-height: 1.6;
        }

        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .status-draft {
            background: #fef3c7;
            color: #92400e;
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

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), #3b82f6);
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

        .btn-success {
            background: linear-gradient(135deg, var(--success-color), #10b981);
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

        @media print {
            body {
                background: white !important;
            }
            .main-container {
                box-shadow: none !important;
                border-radius: 0 !important;
                margin: 0 !important;
            }
            .header-section {
                background: #2563eb !important;
                -webkit-print-color-adjust: exact;
            }
            .btn {
                display: none !important;
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
                        <h1 class="mb-2">
                            <i class="fas fa-prescription-bottle me-3"></i>
                            Prescription #{{ $prescription->id }}
                        </h1>
                        <p class="mb-0 opacity-75">
                            <i class="fas fa-user me-2"></i>{{ $prescription->appointment->patient->user->full_name }}
                            <span class="mx-3">•</span>
                            <i class="fas fa-calendar me-2"></i>{{ $prescription->prescribed_at->format('d/m/Y à H:i') }}
                        </p>
                    </div>
                    <div class="col-md-4 text-end">
                        <button onclick="window.print()" class="btn btn-light me-2">
                            <i class="fas fa-print me-2"></i>Imprimer
                        </button>
                        <a href="{{ route('doctor.planning') }}" class="btn btn-light">
                            <i class="fas fa-arrow-left me-2"></i>Retour
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid py-4">
            <!-- Patient Info Card -->
            <div class="prescription-card">
                <div class="section-header">
                    <i class="fas fa-user me-2"></i>Informations Patient
                </div>
                <div class="p-4">
                    <div class="row align-items-center">
                        <div class="col-md-2">
                            <div class="patient-avatar">
                                {{ substr($prescription->appointment->patient->user->full_name, 0, 1) }}
                            </div>
                        </div>
                        <div class="col-md-10">
                            <h4 class="mb-1">{{ $prescription->appointment->patient->user->full_name }}</h4>
                            <div class="row">
                                <div class="col-md-4">
                                    <small class="text-muted">
                                        <i class="fas fa-envelope me-1"></i>{{ $prescription->appointment->patient->user->email }}
                                    </small>
                                </div>
                                <div class="col-md-4">
                                    <small class="text-muted">
                                        <i class="fas fa-phone me-1"></i>{{ $prescription->appointment->patient->user->phone ?? 'N/A' }}
                                    </small>
                                </div>
                                <div class="col-md-4">
                                    <small class="text-muted">
                                        <i class="fas fa-calendar me-1"></i>RDV: {{ $prescription->appointment->appointment_date->format('d/m/Y H:i') }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Prescription Details Card -->
            <div class="prescription-card">
                <div class="section-header">
                    <i class="fas fa-prescription-bottle me-2"></i>Détails de la Prescription
                </div>
                <div class="p-4">
                    <div class="info-item">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-warning mb-2">
                                    <i class="fas fa-calendar me-2"></i>Date de Prescription
                                </h6>
                                <p class="mb-0">{{ $prescription->prescribed_at->format('d/m/Y à H:i') }}</p>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-info mb-2">
                                    <i class="fas fa-flag me-2"></i>Statut
                                </h6>
                                <span class="status-badge status-{{ $prescription->status }}">
                                    <i class="fas fa-{{ $prescription->status === 'active' ? 'check' : ($prescription->status === 'draft' ? 'edit' : ($prescription->status === 'completed' ? 'check-double' : 'times')) }} me-1"></i>
                                    {{ ucfirst($prescription->status) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    @if($prescription->notes)
                    <div class="info-item">
                        <h6 class="text-success mb-3">
                            <i class="fas fa-pills me-2"></i>Médicaments et Instructions
                        </h6>
                        <div class="prescription-content">{{ $prescription->notes }}</div>
                    </div>
                    @endif

                    <div class="info-item">
                        <h6 class="text-secondary mb-2">
                            <i class="fas fa-user-md me-2"></i>Prescrit par
                        </h6>
                        <p class="mb-0">
                            Dr. {{ $prescription->appointment->doctor->user->full_name }}
                            <br>
                            <small class="text-muted">{{ $prescription->appointment->doctor->speciality ?? 'Médecin' }}</small>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Related Consultation Card -->
            @if($prescription->appointment->consultation)
            <div class="prescription-card">
                <div class="section-header">
                    <i class="fas fa-stethoscope me-2"></i>Consultation Associée
                </div>
                <div class="p-4">
                    <div class="info-item">
                        <h6 class="text-primary mb-2">
                            <i class="fas fa-diagnoses me-2"></i>Diagnostic
                        </h6>
                        <p class="mb-0">{{ $prescription->appointment->consultation->diagnosis }}</p>
                    </div>

                    @if($prescription->appointment->consultation->treatment)
                    <div class="info-item">
                        <h6 class="text-success mb-2">
                            <i class="fas fa-pills me-2"></i>Traitement
                        </h6>
                        <p class="mb-0">{{ $prescription->appointment->consultation->treatment }}</p>
                    </div>
                    @endif

                    <div class="info-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-info mb-1">Date de Consultation</h6>
                                <small class="text-muted">{{ $prescription->appointment->consultation->consultation_date->format('d/m/Y à H:i') }}</small>
                            </div>
                            <div>
                                <a href="{{ route('doctor.consultation.show', $prescription->appointment->consultation->id) }}" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-eye me-1"></i>Voir Consultation
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Actions -->
            <div class="prescription-card">
                <div class="p-4">
                    <div class="d-flex gap-3 flex-wrap">
                        <button onclick="window.print()" class="btn btn-success">
                            <i class="fas fa-print me-2"></i>Imprimer la Prescription
                        </button>

                        <a href="{{ route('doctor.prescription.edit', $prescription->id) }}" class="btn btn-info">
                            <i class="fas fa-edit me-2"></i>Modifier
                        </a>

                        <a href="{{ route('doctor.prescriptions') }}" class="btn btn-primary">
                            <i class="fas fa-list me-2"></i>Toutes les Prescriptions
                        </a>

                        <a href="{{ route('doctor.planning') }}" class="btn btn-secondary">
                            <i class="fas fa-calendar me-2"></i>Retour au Planning
                        </a>

                        <form action="{{ route('doctor.prescription.delete', $prescription->id) }}" method="POST" style="display: inline;"
                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette prescription ? Cette action ne peut pas être annulée.')">
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
