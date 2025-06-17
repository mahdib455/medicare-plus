<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Prescription - MediCare Pro</title>
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
            position: relative;
            z-index: 1;
        }

        .header-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, #3b82f6 100%);
            color: white;
            padding: 2rem;
            border-radius: 20px 20px 0 0;
            position: relative;
            overflow: visible;
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

        .prescription-card {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            box-shadow: var(--card-shadow);
            margin-bottom: 2rem;
        }

        .consultation-info {
            background: #f8fafc;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            border-left: 4px solid var(--primary-color);
        }

        .medication-item {
            background: #f8fafc;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            border: 2px solid #e2e8f0;
            transition: all 0.3s ease;
        }

        .medication-item:hover {
            border-color: var(--primary-color);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.1);
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

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), #3b82f6);
            border: none;
            border-radius: 8px;
            padding: 0.75rem 2rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgba(37, 99, 235, 0.3);
        }

        .btn-secondary {
            background: var(--secondary-color);
            border: none;
            border-radius: 8px;
            padding: 0.75rem 2rem;
            font-weight: 500;
        }

        .btn-success {
            background: var(--success-color);
            border: none;
            border-radius: 8px;
            padding: 0.5rem 1rem;
            font-weight: 500;
        }

        .btn-danger {
            background: var(--danger-color);
            border: none;
            border-radius: 8px;
            padding: 0.5rem 1rem;
            font-weight: 500;
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

        @media (max-width: 768px) {
            .main-container {
                margin-top: 0;
                border-radius: 0;
            }

            .header-section {
                border-radius: 0;
                padding: 1.5rem;
            }

            .prescription-card {
                padding: 1.5rem;
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
                                <i class="fas fa-prescription-bottle-alt me-3"></i>
                                Create Prescription
                            </h1>
                            <p class="mb-0 opacity-75">
                                <i class="fas fa-pills me-2"></i>Create a new prescription after consultation
                                <span class="mx-3">•</span>
                                <i class="fas fa-user-md me-2"></i>Dr. {{ $doctor->user->full_name ?? 'Doctor' }}
                            </p>
                        </div>
                    </div>
                    <div class="col-md-4 text-end">
                        <div class="d-flex justify-content-end align-items-center">
                            <a href="{{ route('doctor.dashboard') }}" class="btn btn-light me-3">
                                <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                            </a>
                        </div>
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

            <!-- Consultation Information -->
            <div class="prescription-card">
                <h4 class="mb-3">
                    <i class="fas fa-stethoscope me-2 text-primary"></i>
                    Consultation Information
                </h4>
                
                <div class="consultation-info">
                    <div class="row">
                        <div class="col-md-6">
                            <h6><i class="fas fa-user me-2"></i>Patient</h6>
                            <p class="mb-2">{{ $consultation->appointment->patient->user->full_name ?? 'Unknown Patient' }}</p>
                            
                            <h6><i class="fas fa-calendar me-2"></i>Consultation Date</h6>
                            <p class="mb-2">{{ $consultation->consultation_date->format('F d, Y at H:i') }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6><i class="fas fa-diagnoses me-2"></i>Diagnosis</h6>
                            <p class="mb-2">{{ $consultation->diagnosis ?? 'No diagnosis provided' }}</p>
                            
                            <h6><i class="fas fa-notes-medical me-2"></i>Treatment</h6>
                            <p class="mb-2">{{ $consultation->treatment ?? 'No treatment specified' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Prescription Form -->
            <div class="prescription-card">
                <h4 class="mb-4">
                    <i class="fas fa-prescription me-2 text-primary"></i>
                    Create Prescription
                </h4>

                <form action="{{ route('doctor.prescription.store', $consultation->id) }}" method="POST" id="prescriptionForm">
                    @csrf
                    
                    <!-- Prescription Notes -->
                    <div class="mb-4">
                        <label for="notes" class="form-label">
                            <i class="fas fa-sticky-note me-2"></i>Prescription Notes
                        </label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" 
                                  id="notes" name="notes" rows="3" 
                                  placeholder="General notes about this prescription...">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
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
                            <!-- Medications will be added here dynamically -->
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Create Prescription
                        </button>
                        <a href="{{ route('doctor.dashboard') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        let medicationIndex = 0;
        const medications = @json($medications);

        // Add medication function
        function addMedication() {
            const container = document.getElementById('medicationsContainer');
            const medicationHtml = `
                <div class="medication-item position-relative" data-index="${medicationIndex}">
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

        // Add first medication by default
        document.addEventListener('DOMContentLoaded', function() {
            addMedication();
        });

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
