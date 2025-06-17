<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvelle Consultation - MediCare Pro</title>
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

        .patient-info {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            padding: 1.5rem;
            border-bottom: 2px solid #e2e8f0;
        }

        .form-control, .form-select {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.25);
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
                            Nouvelle Consultation
                        </h1>
                        <p class="mb-0 opacity-75">
                            <i class="fas fa-calendar me-2"></i>{{ $appointment->appointment_date->format('d/m/Y à H:i') }}
                            <span class="mx-3">•</span>
                            <i class="fas fa-user me-2"></i>{{ $appointment->patient->user->full_name }}
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
                <div class="patient-info">
                    <div class="row align-items-center">
                        <div class="col-md-2">
                            <div class="patient-avatar">
                                {{ substr($appointment->patient->user->full_name, 0, 1) }}
                            </div>
                        </div>
                        <div class="col-md-10">
                            <h4 class="mb-1">{{ $appointment->patient->user->full_name }}</h4>
                            <div class="row">
                                <div class="col-md-4">
                                    <small class="text-muted">
                                        <i class="fas fa-envelope me-1"></i>{{ $appointment->patient->user->email }}
                                    </small>
                                </div>
                                <div class="col-md-4">
                                    <small class="text-muted">
                                        <i class="fas fa-phone me-1"></i>{{ $appointment->patient->user->phone ?? 'N/A' }}
                                    </small>
                                </div>
                                <div class="col-md-4">
                                    <small class="text-muted">
                                        <i class="fas fa-calendar me-1"></i>{{ $appointment->appointment_date->format('d/m/Y H:i') }}
                                    </small>
                                </div>
                            </div>
                            <div class="mt-2">
                                <strong>Motif du rendez-vous:</strong> {{ $appointment->reason ?: 'Non spécifié' }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Consultation Form -->
                <div class="p-4">
                    <form action="{{ route('doctor.consultation.store', $appointment->id) }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-12 mb-4">
                                <label for="diagnosis" class="form-label fw-semibold">
                                    <i class="fas fa-diagnoses me-2 text-primary"></i>Diagnostic *
                                </label>
                                <textarea class="form-control" id="diagnosis" name="diagnosis" rows="4" 
                                          placeholder="Décrivez le diagnostic médical..." required>{{ old('diagnosis') }}</textarea>
                                @error('diagnosis')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-4">
                                <label for="treatment" class="form-label fw-semibold">
                                    <i class="fas fa-pills me-2 text-success"></i>Traitement
                                </label>
                                <textarea class="form-control" id="treatment" name="treatment" rows="4" 
                                          placeholder="Décrivez le traitement recommandé...">{{ old('treatment') }}</textarea>
                                @error('treatment')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-4">
                                <label for="notes" class="form-label fw-semibold">
                                    <i class="fas fa-notes-medical me-2 text-info"></i>Notes additionnelles
                                </label>
                                <textarea class="form-control" id="notes" name="notes" rows="3" 
                                          placeholder="Notes complémentaires, observations...">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex gap-3">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Enregistrer la Consultation
                                    </button>
                                    <a href="{{ route('doctor.planning') }}" class="btn btn-secondary">
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
