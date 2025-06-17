<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Prescription - MediCare Pro</title>
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

        .btn-success {
            background: linear-gradient(135deg, var(--success-color), #10b981);
            border: none;
            border-radius: 12px;
            padding: 0.5rem 1rem;
            font-weight: 500;
        }

        .btn-danger {
            background: linear-gradient(135deg, var(--danger-color), #ef4444);
            border: none;
            border-radius: 12px;
            padding: 0.5rem 1rem;
            font-weight: 500;
        }

        .medication-item {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            position: relative;
        }

        .medication-header {
            background: linear-gradient(135deg, var(--primary-color), #3b82f6);
            color: white;
            padding: 1rem;
            border-radius: 8px 8px 0 0;
            margin: -1.5rem -1.5rem 1rem -1.5rem;
        }

        .remove-medication {
            position: absolute;
            top: 10px;
            right: 10px;
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
                            Edit Prescription #{{ $prescription->id }}
                        </h1>
                        <p class="mb-0 opacity-75">
                            <i class="fas fa-user me-2"></i>{{ $prescription->appointment->patient->user->full_name }}
                            <span class="mx-3">•</span>
                            <i class="fas fa-calendar me-2"></i>{{ $prescription->prescribed_at->format('d/m/Y à H:i') }}
                        </p>
                    </div>
                    <div class="col-md-4 text-end">
                        <a href="{{ route('doctor.prescription.show', $prescription->id) }}" class="btn btn-light me-2">
                            <i class="fas fa-eye me-2"></i>View
                        </a>
                        <a href="{{ route('doctor.prescriptions') }}" class="btn btn-light">
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
            <div class="prescription-card">
                <div class="section-header">
                    <i class="fas fa-user me-2"></i>Patient Information
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

            <!-- Edit Prescription Form -->
            <div class="prescription-card">
                <div class="section-header">
                    <i class="fas fa-prescription-bottle me-2"></i>Edit Prescription Details
                </div>
                <div class="p-4">
                    <form action="{{ route('doctor.prescription.update', $prescription->id) }}" method="POST" id="prescriptionForm">
                        @csrf
                        @method('PUT')
                        
                        <!-- Prescription Notes -->
                        <div class="row mb-4">
                            <div class="col-md-8">
                                <label for="notes" class="form-label">
                                    <i class="fas fa-sticky-note me-2"></i>Prescription Notes
                                </label>
                                <textarea class="form-control @error('notes') is-invalid @enderror" 
                                          id="notes" name="notes" rows="3" 
                                          placeholder="General notes about this prescription...">{{ old('notes', $prescription->notes) }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="status" class="form-label">
                                    <i class="fas fa-flag me-2"></i>Status
                                </label>
                                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                    <option value="draft" {{ old('status', $prescription->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="active" {{ old('status', $prescription->status) === 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="completed" {{ old('status', $prescription->status) === 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="cancelled" {{ old('status', $prescription->status) === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Medications Section -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5><i class="fas fa-pills me-2"></i>Medications</h5>
                                <button type="button" class="btn btn-success" id="addMedication">
                                    <i class="fas fa-plus me-2"></i>Add Medication
                                </button>
                            </div>
                            
                            <div id="medicationsContainer">
                                @foreach($prescription->lines as $index => $line)
                                <div class="medication-item" data-index="{{ $index }}">
                                    <div class="medication-header">
                                        <h6 class="mb-0">
                                            <i class="fas fa-pill me-2"></i>Medication {{ $index + 1 }}
                                        </h6>
                                    </div>
                                    <button type="button" class="btn btn-danger btn-sm remove-medication" onclick="removeMedication({{ $index }})">
                                        <i class="fas fa-times"></i>
                                    </button>
                                    
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Medication</label>
                                            <select class="form-select" name="medications[{{ $index }}][medication_id]" required>
                                                <option value="">Select Medication</option>
                                                @foreach($medications as $med)
                                                <option value="{{ $med->id }}" {{ $line->medication_id == $med->id ? 'selected' : '' }}>
                                                    {{ $med->name }} ({{ $med->dosage }})
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">Quantity</label>
                                            <input type="number" class="form-control" name="medications[{{ $index }}][quantity]" min="1" value="{{ $line->quantity }}" required>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">Dosage</label>
                                            <input type="text" class="form-control" name="medications[{{ $index }}][dosage]" placeholder="e.g., 500mg" value="{{ $line->dosage }}" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Frequency</label>
                                            <input type="text" class="form-control" name="medications[{{ $index }}][frequency]" placeholder="e.g., Twice daily" value="{{ $line->frequency }}" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Duration (days)</label>
                                            <input type="number" class="form-control" name="medications[{{ $index }}][duration_days]" min="1" value="{{ $line->duration_days }}" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Start Date</label>
                                            <input type="date" class="form-control" name="medications[{{ $index }}][start_date]" value="{{ $line->start_date ? $line->start_date->format('Y-m-d') : '' }}" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">End Date</label>
                                            <input type="date" class="form-control" name="medications[{{ $index }}][end_date]" value="{{ $line->end_date ? $line->end_date->format('Y-m-d') : '' }}" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Notes</label>
                                            <input type="text" class="form-control" name="medications[{{ $index }}][notes]" placeholder="Additional notes" value="{{ $line->notes }}">
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label class="form-label">Instructions</label>
                                            <textarea class="form-control" name="medications[{{ $index }}][instructions]" rows="2" placeholder="e.g., Take twice daily after meals">{{ $line->instructions }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="d-flex gap-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Update Prescription
                            </button>
                            <a href="{{ route('doctor.prescription.show', $prescription->id) }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Danger Zone -->
            <div class="prescription-card">
                <div class="section-header text-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>Danger Zone
                </div>
                <div class="p-4">
                    <div class="alert alert-warning">
                        <h6><i class="fas fa-warning me-2"></i>Delete Prescription</h6>
                        <p class="mb-3">This action cannot be undone. This will permanently delete the prescription and all medication lines.</p>
                        <form action="{{ route('doctor.prescription.delete', $prescription->id) }}" method="POST" 
                              onsubmit="return confirm('Are you sure you want to delete this prescription? This action cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash me-2"></i>Delete Prescription
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        let medicationIndex = {{ $prescription->lines->count() }};
        const medications = @json($medications);

        // Add medication function
        function addMedication() {
            const container = document.getElementById('medicationsContainer');
            const medicationHtml = `
                <div class="medication-item" data-index="${medicationIndex}">
                    <div class="medication-header">
                        <h6 class="mb-0">
                            <i class="fas fa-pill me-2"></i>Medication ${medicationIndex + 1}
                        </h6>
                    </div>
                    <button type="button" class="btn btn-danger btn-sm remove-medication" onclick="removeMedication(${medicationIndex})">
                        <i class="fas fa-times"></i>
                    </button>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Medication</label>
                            <select class="form-select" name="medications[${medicationIndex}][medication_id]" required>
                                <option value="">Select Medication</option>
                                ${medications.map(med => `<option value="${med.id}">${med.name} (${med.dosage})</option>`).join('')}
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Quantity</label>
                            <input type="number" class="form-control" name="medications[${medicationIndex}][quantity]" min="1" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Dosage</label>
                            <input type="text" class="form-control" name="medications[${medicationIndex}][dosage]" placeholder="e.g., 500mg" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Frequency</label>
                            <input type="text" class="form-control" name="medications[${medicationIndex}][frequency]" placeholder="e.g., Twice daily" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Duration (days)</label>
                            <input type="number" class="form-control" name="medications[${medicationIndex}][duration_days]" min="1" value="7" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Start Date</label>
                            <input type="date" class="form-control" name="medications[${medicationIndex}][start_date]" value="${new Date().toISOString().split('T')[0]}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">End Date</label>
                            <input type="date" class="form-control" name="medications[${medicationIndex}][end_date]" value="${new Date(Date.now() + 7*24*60*60*1000).toISOString().split('T')[0]}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Notes</label>
                            <input type="text" class="form-control" name="medications[${medicationIndex}][notes]" placeholder="Additional notes">
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Instructions</label>
                            <textarea class="form-control" name="medications[${medicationIndex}][instructions]" rows="2" placeholder="e.g., Take twice daily after meals"></textarea>
                        </div>
                    </div>
                </div>
            `;
            
            container.insertAdjacentHTML('beforeend', medicationHtml);
            medicationIndex++;
        }

        // Remove medication function
        function removeMedication(index) {
            const medicationItem = document.querySelector(`[data-index="${index}"]`);
            if (medicationItem) {
                medicationItem.remove();
            }
        }

        // Event listeners
        document.getElementById('addMedication').addEventListener('click', addMedication);

        // Form validation
        document.getElementById('prescriptionForm').addEventListener('submit', function(e) {
            const medications = document.querySelectorAll('.medication-item');
            if (medications.length === 0) {
                e.preventDefault();
                alert('Please add at least one medication to the prescription.');
                return false;
            }
        });
    </script>
</body>
</html>
