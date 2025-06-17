<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvelle Prescription - MediCare Pro</title>
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

        .form-control, .form-select {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--warning-color);
            box-shadow: 0 0 0 0.2rem rgba(217, 119, 6, 0.25);
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

        .patient-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--warning-color), #f59e0b);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 1.2rem;
        }

        .consultation-summary {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            border-left: 4px solid var(--warning-color);
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
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
                            Nouvelle Prescription
                        </h1>
                        <p class="mb-0 opacity-75">
                            <i class="fas fa-user me-2"></i>{{ $consultation->appointment->patient->user->full_name }}
                            <span class="mx-3">•</span>
                            <i class="fas fa-calendar me-2"></i>{{ $consultation->consultation_date->format('d/m/Y') }}
                        </p>
                    </div>
                    <div class="col-md-4 text-end">
                        <a href="{{ route('doctor.consultation.show', $consultation->id) }}" class="btn btn-light">
                            <i class="fas fa-arrow-left me-2"></i>Retour à la Consultation
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid py-4">
            <!-- Patient & Consultation Summary -->
            <div class="prescription-card">
                <div class="section-header">
                    <i class="fas fa-user me-2"></i>Résumé Patient & Consultation
                </div>
                <div class="p-4">
                    <div class="row align-items-center mb-3">
                        <div class="col-md-1">
                            <div class="patient-avatar">
                                {{ substr($consultation->appointment->patient->user->full_name, 0, 1) }}
                            </div>
                        </div>
                        <div class="col-md-11">
                            <h5 class="mb-1">{{ $consultation->appointment->patient->user->full_name }}</h5>
                            <small class="text-muted">
                                <i class="fas fa-envelope me-1"></i>{{ $consultation->appointment->patient->user->email }}
                                <span class="mx-2">•</span>
                                <i class="fas fa-phone me-1"></i>{{ $consultation->appointment->patient->user->phone ?? 'N/A' }}
                            </small>
                        </div>
                    </div>

                    <div class="consultation-summary">
                        <h6 class="text-warning mb-2">
                            <i class="fas fa-stethoscope me-2"></i>Consultation du {{ $consultation->consultation_date->format('d/m/Y à H:i') }}
                        </h6>
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Diagnostic:</strong>
                                <p class="mb-2">{{ Str::limit($consultation->diagnosis, 100) }}</p>
                            </div>
                            <div class="col-md-6">
                                @if($consultation->treatment)
                                <strong>Traitement:</strong>
                                <p class="mb-2">{{ Str::limit($consultation->treatment, 100) }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Prescription Form -->
            <div class="prescription-card">
                <div class="section-header">
                    <i class="fas fa-prescription-bottle me-2"></i>Détails de la Prescription
                </div>
                <div class="p-4">
                    <form action="{{ route('doctor.prescription.store', $consultation->id) }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="status" class="form-label fw-semibold">
                                    <i class="fas fa-flag me-2 text-warning"></i>Statut de la Prescription *
                                </label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="">Sélectionner un statut</option>
                                    <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Brouillon</option>
                                    <option value="active" {{ old('status') === 'active' ? 'selected' : 'selected' }}>Active</option>
                                    <option value="completed" {{ old('status') === 'completed' ? 'selected' : '' }}>Terminée</option>
                                </select>
                                @error('status')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-calendar me-2 text-info"></i>Date de Prescription
                                </label>
                                <input type="text" class="form-control" value="{{ now()->format('d/m/Y à H:i') }}" readonly>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-4">
                                <label for="notes" class="form-label fw-semibold">
                                    <i class="fas fa-pills me-2 text-success"></i>Médicaments et Instructions
                                </label>
                                <textarea class="form-control" id="notes" name="notes" rows="8" 
                                          placeholder="Exemple:&#10;&#10;1. Paracétamol 500mg&#10;   - 1 comprimé 3 fois par jour&#10;   - Après les repas&#10;   - Durée: 7 jours&#10;&#10;2. Ibuprofène 200mg&#10;   - 1 comprimé si douleur&#10;   - Maximum 3 par jour&#10;   - Avec de la nourriture&#10;&#10;Instructions générales:&#10;- Boire beaucoup d'eau&#10;- Repos recommandé&#10;- Retour si symptômes persistent">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                                <small class="text-muted mt-1">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Incluez les médicaments, dosages, fréquence, durée et instructions spéciales
                                </small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <h6><i class="fas fa-info-circle me-2"></i>Information</h6>
                                    <p class="mb-0">Cette prescription sera visible par le patient dans son dashboard et pourra être consultée à tout moment.</p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex gap-3">
                                    <button type="submit" class="btn btn-warning">
                                        <i class="fas fa-save me-2"></i>Créer la Prescription
                                    </button>
                                    <a href="{{ route('doctor.consultation.show', $consultation->id) }}" class="btn btn-secondary">
                                        <i class="fas fa-times me-2"></i>Annuler
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
