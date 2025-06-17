<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Imprimer Prescription - {{ $prescription->appointment->patient->user->full_name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
        @media print {
            .no-print {
                display: none !important;
            }
            
            body {
                font-size: 12px;
                line-height: 1.4;
            }
            
            .container-fluid {
                padding: 0;
                margin: 0;
            }
            
            .prescription-card {
                box-shadow: none;
                border: 1px solid #000;
                page-break-inside: avoid;
            }
            
            .btn {
                display: none;
            }
        }

        body {
            font-family: 'Arial', sans-serif;
            background: white;
        }

        .prescription-card {
            background: white;
            border: 2px solid #059669;
            border-radius: 10px;
            margin: 20px auto;
            max-width: 800px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .prescription-header {
            background: linear-gradient(135deg, #059669, #10b981);
            color: white;
            padding: 20px;
            border-radius: 8px 8px 0 0;
            text-align: center;
        }

        .prescription-title {
            font-size: 24px;
            font-weight: bold;
            margin: 0;
        }

        .prescription-subtitle {
            font-size: 14px;
            margin-top: 5px;
            opacity: 0.9;
        }

        .info-section {
            padding: 20px;
            border-bottom: 1px solid #e9ecef;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .info-box {
            flex: 1;
            margin: 0 10px;
            padding: 15px;
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 8px;
        }

        .info-title {
            font-weight: bold;
            color: #059669;
            font-size: 14px;
            margin-bottom: 10px;
            border-bottom: 1px solid #e9ecef;
            padding-bottom: 5px;
        }

        .info-content {
            font-size: 13px;
            line-height: 1.5;
        }

        .medications-section {
            padding: 20px;
        }

        .medications-title {
            color: #059669;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 20px;
            border-bottom: 2px solid #059669;
            padding-bottom: 10px;
        }

        .medication-item {
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            background: #fafafa;
        }

        .medication-name {
            font-size: 16px;
            font-weight: bold;
            color: #059669;
            margin-bottom: 10px;
        }

        .medication-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 10px;
            margin-bottom: 10px;
        }

        .medication-detail {
            background: white;
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #e9ecef;
        }

        .detail-label {
            font-weight: bold;
            color: #666;
            font-size: 11px;
            text-transform: uppercase;
        }

        .detail-value {
            color: #333;
            font-size: 13px;
            margin-top: 2px;
        }

        .instructions-box {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 5px;
            padding: 10px;
            margin-top: 10px;
        }

        .instructions-label {
            font-weight: bold;
            color: #856404;
            font-size: 12px;
        }

        .instructions-text {
            color: #856404;
            font-size: 12px;
            margin-top: 3px;
        }

        .notes-section {
            padding: 20px;
            background: #e3f2fd;
            border-top: 1px solid #e9ecef;
        }

        .notes-title {
            font-weight: bold;
            color: #1565c0;
            margin-bottom: 10px;
        }

        .footer-section {
            padding: 20px;
            border-top: 1px solid #e9ecef;
            background: #f8f9fa;
            border-radius: 0 0 8px 8px;
        }

        .signature-area {
            text-align: right;
            margin-top: 30px;
        }

        .signature-line {
            border-top: 1px solid #333;
            width: 200px;
            margin: 30px 0 5px auto;
            padding-top: 5px;
            font-size: 12px;
            color: #666;
        }

        .footer-info {
            font-size: 11px;
            color: #666;
            margin-top: 20px;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
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

        .print-controls {
            text-align: center;
            margin: 20px 0;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
        }

        @media (max-width: 768px) {
            .info-row {
                flex-direction: column;
            }
            
            .info-box {
                margin: 5px 0;
            }
            
            .medication-details {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Print Controls -->
    <div class="container-fluid no-print">
        <div class="print-controls">
            <h4><i class="fas fa-print me-2"></i>Impression de l'Ordonnance</h4>
            <p class="text-muted">Cliquez sur "Imprimer" pour imprimer cette ordonnance ou utilisez Ctrl+P</p>
            <div class="d-flex gap-2 justify-content-center">
                <button onclick="window.print()" class="btn btn-primary">
                    <i class="fas fa-print me-2"></i>Imprimer
                </button>
                <a href="{{ route('patient.prescriptions') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Retour aux Prescriptions
                </a>
                <a href="{{ route('patient.prescriptions.download', $prescription->id) }}" class="btn btn-success">
                    <i class="fas fa-download me-2"></i>Télécharger PDF
                </a>
            </div>
        </div>
    </div>

    <!-- Prescription Content -->
    <div class="prescription-card">
        <!-- Header -->
        <div class="prescription-header">
            <div class="prescription-title">
                <i class="fas fa-prescription-bottle-alt me-2"></i>
                ORDONNANCE MÉDICALE
            </div>
            <div class="prescription-subtitle">
                MediCare+ - Système de Gestion Médicale
            </div>
        </div>

        <!-- Information Section -->
        <div class="info-section">
            <div class="info-row">
                <div class="info-box">
                    <div class="info-title">
                        <i class="fas fa-user-md me-2"></i>MÉDECIN PRESCRIPTEUR
                    </div>
                    <div class="info-content">
                        <div><strong>Dr. {{ $prescription->appointment->doctor->user->full_name }}</strong></div>
                        <div>{{ $prescription->appointment->doctor->speciality ?? 'Médecin Généraliste' }}</div>
                        <div>{{ $prescription->appointment->doctor->hospital ?? 'Hôpital' }}</div>
                        <div><i class="fas fa-envelope me-1"></i>{{ $prescription->appointment->doctor->user->email }}</div>
                        <div><i class="fas fa-phone me-1"></i>{{ $prescription->appointment->doctor->user->phone ?? 'N/A' }}</div>
                    </div>
                </div>

                <div class="info-box">
                    <div class="info-title">
                        <i class="fas fa-user me-2"></i>PATIENT
                    </div>
                    <div class="info-content">
                        <div><strong>{{ $prescription->appointment->patient->user->full_name }}</strong></div>
                        <div><i class="fas fa-envelope me-1"></i>{{ $prescription->appointment->patient->user->email }}</div>
                        <div><i class="fas fa-phone me-1"></i>{{ $prescription->appointment->patient->user->phone ?? 'N/A' }}</div>
                        <div><i class="fas fa-birthday-cake me-1"></i>{{ $prescription->appointment->patient->date_of_birth ?? 'N/A' }}</div>
                        <div><i class="fas fa-venus-mars me-1"></i>{{ $prescription->appointment->patient->gender ?? 'N/A' }}</div>
                    </div>
                </div>
            </div>

            <div class="text-center">
                <div><strong>Date de prescription:</strong> {{ $prescription->prescribed_at->format('d/m/Y à H:i') }}</div>
                <div class="mt-2">
                    <span class="status-badge status-{{ $prescription->status }}">
                        {{ strtoupper($prescription->status) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Medications Section -->
        <div class="medications-section">
            <div class="medications-title">
                <i class="fas fa-pills me-2"></i>
                MÉDICAMENTS PRESCRITS ({{ $prescription->lines->count() }})
            </div>

            @foreach($prescription->lines as $index => $line)
                <div class="medication-item">
                    <div class="medication-name">
                        {{ $index + 1 }}. {{ $line->medication->name ?? 'Médicament inconnu' }}
                    </div>

                    <div class="medication-details">
                        <div class="medication-detail">
                            <div class="detail-label">Dosage</div>
                            <div class="detail-value">{{ $line->dosage }}</div>
                        </div>
                        <div class="medication-detail">
                            <div class="detail-label">Fréquence</div>
                            <div class="detail-value">{{ $line->frequency }}</div>
                        </div>
                        <div class="medication-detail">
                            <div class="detail-label">Durée</div>
                            <div class="detail-value">{{ $line->duration_days ?? $line->duration ?? 'N/A' }} jours</div>
                        </div>
                        <div class="medication-detail">
                            <div class="detail-label">Quantité</div>
                            <div class="detail-value">{{ $line->quantity ?? 'N/A' }}</div>
                        </div>
                    </div>

                    @if($line->instructions)
                        <div class="instructions-box">
                            <div class="instructions-label">
                                <i class="fas fa-info-circle me-1"></i>Instructions spéciales:
                            </div>
                            <div class="instructions-text">{{ $line->instructions }}</div>
                        </div>
                    @endif

                    @if($line->notes)
                        <div class="instructions-box">
                            <div class="instructions-label">
                                <i class="fas fa-sticky-note me-1"></i>Notes:
                            </div>
                            <div class="instructions-text">{{ $line->notes }}</div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        <!-- Notes Section -->
        @if($prescription->notes)
            <div class="notes-section">
                <div class="notes-title">
                    <i class="fas fa-clipboard me-2"></i>NOTES DU MÉDECIN
                </div>
                <div>{{ $prescription->notes }}</div>
            </div>
        @endif

        <!-- Footer -->
        <div class="footer-section">
            <div class="signature-area">
                <div>Signature du médecin</div>
                <div class="signature-line">Dr. {{ $prescription->appointment->doctor->user->full_name }}</div>
            </div>

            <div class="footer-info">
                <div><strong>Informations importantes:</strong></div>
                <div>• Cette ordonnance est valable 3 mois à partir de la date de prescription</div>
                <div>• Respectez scrupuleusement les dosages et fréquences prescrits</div>
                <div>• En cas d'effets secondaires, consultez immédiatement votre médecin</div>
                <div>• Ne modifiez pas le traitement sans avis médical</div>
                <br>
                <div><strong>Document généré le:</strong> {{ now()->format('d/m/Y à H:i') }}</div>
                <div><strong>Référence:</strong> PRESC-{{ $prescription->id }}-{{ now()->format('Ymd') }}</div>
            </div>
        </div>
    </div>

    <script>
        // Auto-print when page loads (optional)
        // window.onload = function() { window.print(); }
        
        // Print function
        function printPrescription() {
            window.print();
        }
        
        // Keyboard shortcut
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey && e.key === 'p') {
                e.preventDefault();
                window.print();
            }
        });
    </script>
</body>
</html>
