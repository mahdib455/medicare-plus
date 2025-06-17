<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Consultation - MediCare Pro</title>
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
            background: linear-gradient(135deg, var(--info-color) 0%, #06b6d4 100%);
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

        .form-control, .form-select {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
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

        .btn-danger {
            background: linear-gradient(135deg, var(--danger-color), #ef4444);
            border: none;
            border-radius: 12px;
            padding: 0.75rem 2rem;
            font-weight: 500;
        }

        .patient-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--info-color), #06b6d4);
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
                            <i class="fas fa-edit me-3"></i>
                            Edit Consultation
                        </h1>
                        <p class="mb-0 opacity-75">
                            <i class="fas fa-user me-2"></i>{{ $consultation->appointment->patient->user->full_name }}
                            <span class="mx-3">•</span>
                            <i class="fas fa-calendar me-2"></i>{{ $consultation->consultation_date->format('d/m/Y à H:i') }}
                        </p>
                    </div>
                    <div class="col-md-4 text-end">
                        <a href="{{ route('doctor.consultation.show', $consultation->id) }}" class="btn btn-light me-2">
                            <i class="fas fa-eye me-2"></i>View
                        </a>
                        <a href="{{ route('doctor.planning') }}" class="btn btn-light">
                            <i class="fas fa-arrow-left me-2"></i>Back
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

            <!-- Patient Info Card -->
            <div class="consultation-card">
                <div class="section-header">
                    <i class="fas fa-user me-2"></i>Patient Information
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
                        </div>
                    </div>
                </div>
            </div>

            <!-- Edit Consultation Form -->
            <div class="consultation-card">
                <div class="section-header">
                    <i class="fas fa-stethoscope me-2"></i>Edit Consultation Details
                </div>
                <div class="p-4">
                    <form action="{{ route('doctor.consultation.update', $consultation->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-12 mb-4">
                                <label for="diagnosis" class="form-label">
                                    <i class="fas fa-diagnoses me-2 text-primary"></i>Diagnosis *
                                </label>
                                <textarea class="form-control @error('diagnosis') is-invalid @enderror" 
                                          id="diagnosis" name="diagnosis" rows="4" required
                                          placeholder="Enter the diagnosis...">{{ old('diagnosis', $consultation->diagnosis) }}</textarea>
                                @error('diagnosis')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-4">
                                <label for="treatment" class="form-label">
                                    <i class="fas fa-pills me-2 text-success"></i>Treatment Plan
                                </label>
                                <textarea class="form-control @error('treatment') is-invalid @enderror" 
                                          id="treatment" name="treatment" rows="4"
                                          placeholder="Enter the treatment plan...">{{ old('treatment', $consultation->treatment) }}</textarea>
                                @error('treatment')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-4">
                                <label for="notes" class="form-label">
                                    <i class="fas fa-notes-medical me-2 text-info"></i>Additional Notes
                                </label>
                                <textarea class="form-control @error('notes') is-invalid @enderror" 
                                          id="notes" name="notes" rows="3"
                                          placeholder="Any additional notes...">{{ old('notes', $consultation->notes) }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex gap-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Update Consultation
                            </button>
                            <a href="{{ route('doctor.consultation.show', $consultation->id) }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Danger Zone -->
            <div class="consultation-card">
                <div class="section-header text-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>Danger Zone
                </div>
                <div class="p-4">
                    <div class="alert alert-warning">
                        <h6><i class="fas fa-warning me-2"></i>Delete Consultation</h6>
                        <p class="mb-3">This action cannot be undone. This will permanently delete the consultation and all related prescriptions.</p>
                        <form action="{{ route('doctor.consultation.delete', $consultation->id) }}" method="POST" 
                              onsubmit="return confirm('Are you sure you want to delete this consultation? This action cannot be undone and will also delete all related prescriptions.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash me-2"></i>Delete Consultation
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
