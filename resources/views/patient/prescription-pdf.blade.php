<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prescription Médicale - {{ $prescription->appointment->patient->user->full_name }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 20px;
        }

        .header {
            border-bottom: 3px solid #059669;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .logo-section {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #059669;
            margin-bottom: 5px;
        }

        .subtitle {
            font-size: 14px;
            color: #666;
        }

        .doctor-info {
            float: left;
            width: 48%;
        }

        .patient-info {
            float: right;
            width: 48%;
        }

        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }

        .info-box {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 20px;
        }

        .info-title {
            font-weight: bold;
            font-size: 14px;
            color: #059669;
            margin-bottom: 10px;
            border-bottom: 1px solid #e9ecef;
            padding-bottom: 5px;
        }

        .prescription-header {
            text-align: center;
            margin: 30px 0;
            padding: 15px;
            background: #059669;
            color: white;
            border-radius: 5px;
        }

        .prescription-title {
            font-size: 18px;
            font-weight: bold;
            margin: 0;
        }

        .prescription-date {
            font-size: 12px;
            margin-top: 5px;
        }

        .medications-section {
            margin: 30px 0;
        }

        .medication-item {
            border: 1px solid #e9ecef;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 15px;
            background: white;
        }

        .medication-name {
            font-size: 16px;
            font-weight: bold;
            color: #059669;
            margin-bottom: 10px;
        }

        .medication-details {
            display: table;
            width: 100%;
        }

        .medication-detail {
            display: table-cell;
            width: 25%;
            padding: 5px;
            vertical-align: top;
        }

        .detail-label {
            font-weight: bold;
            color: #666;
            font-size: 10px;
            text-transform: uppercase;
        }

        .detail-value {
            color: #333;
            font-size: 12px;
            margin-top: 2px;
        }

        .instructions {
            margin-top: 10px;
            padding: 10px;
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 3px;
        }

        .instructions-label {
            font-weight: bold;
            color: #856404;
            font-size: 11px;
        }

        .instructions-text {
            color: #856404;
            font-size: 11px;
            margin-top: 3px;
        }

        .notes-section {
            margin: 30px 0;
            padding: 15px;
            background: #e3f2fd;
            border: 1px solid #bbdefb;
            border-radius: 5px;
        }

        .notes-title {
            font-weight: bold;
            color: #1565c0;
            margin-bottom: 10px;
        }

        .footer {
            margin-top: 50px;
            border-top: 2px solid #e9ecef;
            padding-top: 20px;
        }

        .signature-section {
            float: right;
            width: 200px;
            text-align: center;
        }

        .signature-line {
            border-top: 1px solid #333;
            margin-top: 40px;
            padding-top: 5px;
            font-size: 10px;
            color: #666;
        }

        .footer-info {
            font-size: 10px;
            color: #666;
            margin-top: 20px;
        }

        .status-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 10px;
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

        @page {
            margin: 2cm;
            @bottom-center {
                content: "Page " counter(page) " sur " counter(pages);
                font-size: 10px;
                color: #666;
            }
        }

        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="logo-section">
            <div class="logo">MediCare+</div>
            <div class="subtitle">Système de Gestion Médicale</div>
        </div>

        <div class="clearfix">
            <div class="doctor-info">
                <div class="info-box">
                    <div class="info-title">👨‍⚕️ MÉDECIN PRESCRIPTEUR</div>
                    <div><strong>Dr. {{ $prescription->appointment->doctor->user->full_name }}</strong></div>
                    <div>{{ $prescription->appointment->doctor->speciality ?? 'Médecin Généraliste' }}</div>
                    <div>{{ $prescription->appointment->doctor->hospital ?? 'Hôpital' }}</div>
                    <div>{{ $prescription->appointment->doctor->user->email }}</div>
                    <div>{{ $prescription->appointment->doctor->user->phone ?? 'N/A' }}</div>
                </div>
            </div>

            <div class="patient-info">
                <div class="info-box">
                    <div class="info-title">👤 PATIENT</div>
                    <div><strong>{{ $prescription->appointment->patient->user->full_name }}</strong></div>
                    <div>{{ $prescription->appointment->patient->user->email }}</div>
                    <div>{{ $prescription->appointment->patient->user->phone ?? 'N/A' }}</div>
                    <div>Date de naissance: {{ $prescription->appointment->patient->date_of_birth ?? 'N/A' }}</div>
                    <div>Sexe: {{ $prescription->appointment->patient->gender ?? 'N/A' }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Prescription Header -->
    <div class="prescription-header">
        <div class="prescription-title">ORDONNANCE MÉDICALE</div>
        <div class="prescription-date">
            Prescrite le {{ $prescription->prescribed_at->format('d/m/Y à H:i') }}
            <span class="status-badge status-{{ $prescription->status }}">{{ strtoupper($prescription->status) }}</span>
        </div>
    </div>

    <!-- Medications Section -->
    <div class="medications-section">
        <h3 style="color: #059669; border-bottom: 2px solid #059669; padding-bottom: 10px;">
            💊 MÉDICAMENTS PRESCRITS ({{ $prescription->lines->count() }})
        </h3>

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
                    <div class="instructions">
                        <div class="instructions-label">📋 Instructions spéciales:</div>
                        <div class="instructions-text">{{ $line->instructions }}</div>
                    </div>
                @endif

                @if($line->notes)
                    <div class="instructions">
                        <div class="instructions-label">📝 Notes:</div>
                        <div class="instructions-text">{{ $line->notes }}</div>
                    </div>
                @endif
            </div>
        @endforeach
    </div>

    <!-- Notes Section -->
    @if($prescription->notes)
        <div class="notes-section">
            <div class="notes-title">📋 NOTES DU MÉDECIN</div>
            <div>{{ $prescription->notes }}</div>
        </div>
    @endif

    <!-- Footer -->
    <div class="footer clearfix">
        <div class="signature-section">
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
</body>
</html>
