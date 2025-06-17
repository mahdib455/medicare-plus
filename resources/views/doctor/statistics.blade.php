<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiques - MediCare Pro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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

        .stats-card {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
            border: none;
            height: 100%;
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--card-shadow-hover);
        }

        .stats-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .chart-card {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            box-shadow: var(--card-shadow);
            margin-bottom: 2rem;
        }

        .chart-container {
            position: relative;
            height: 400px;
            margin-top: 1rem;
        }

        .trend-up {
            color: var(--success-color);
        }

        .trend-down {
            color: var(--danger-color);
        }

        .trend-neutral {
            color: var(--secondary-color);
        }

        .diagnosis-item {
            background: #f8fafc;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 0.5rem;
            border-left: 4px solid var(--primary-color);
        }

        .progress-custom {
            height: 8px;
            border-radius: 4px;
            background: #e2e8f0;
        }

        .progress-bar-custom {
            background: linear-gradient(135deg, var(--primary-color), #3b82f6);
            border-radius: 4px;
        }

        /* Dropdown Menu Fixes */
        .dropdown-menu {
            border: none;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            padding: 0.5rem 0;
            min-width: 200px;
            z-index: 9999 !important;
            margin-top: 0.5rem;
            position: absolute !important;
        }

        .dropdown-item {
            padding: 0.75rem 1.25rem;
            font-size: 0.9rem;
            transition: all 0.2s ease;
            border: none;
        }

        .dropdown-item:hover {
            background-color: #f8fafc;
            color: var(--primary-color);
            transform: translateX(5px);
        }

        .dropdown-item i {
            width: 20px;
            text-align: center;
        }

        .dropdown-divider {
            margin: 0.5rem 0;
            border-color: #e2e8f0;
        }

        /* Ensure dropdown appears above other content */
        .header-section {
            position: relative;
            z-index: 1000;
        }

        .header-section .dropdown {
            position: static;
        }

        .header-section .dropdown .dropdown-menu {
            z-index: 9999 !important;
        }

        /* Fix for dropdown positioning */
        .dropdown-menu-end {
            right: 0 !important;
            left: auto !important;
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

            .chart-container {
                height: 300px;
            }

            .dropdown-menu {
                min-width: 180px;
                margin-top: 0.25rem;
            }

            .dropdown-item {
                padding: 0.6rem 1rem;
                font-size: 0.85rem;
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
                                <i class="fas fa-chart-bar me-3"></i>
                                Statistiques Médicales
                            </h1>
                            <p class="mb-0 opacity-75">
                                <i class="fas fa-analytics me-2"></i>Analyse de vos consultations et performances
                                <span class="mx-3">•</span>
                                <i class="fas fa-user-md me-2"></i>Dr. {{ $doctor->user->full_name ?? 'Docteur' }}
                            </p>
                        </div>
                    </div>
                    <div class="col-md-4 text-end">
                        <div class="d-flex justify-content-end align-items-center">
                            <a href="{{ route('doctor.dashboard') }}" class="btn btn-light me-3">
                                <i class="fas fa-arrow-left me-2"></i>Retour au Dashboard
                            </a>
                            <div class="dropdown">
                                <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-user-circle me-2"></i>Menu
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="{{ route('doctor.profile') }}"><i class="fas fa-user-edit me-2"></i>My Profile</a></li>
                                    <li><a class="dropdown-item" href="{{ route('doctor.planning') }}"><i class="fas fa-calendar-alt me-2"></i>Schedule</a></li>
                                    <li><a class="dropdown-item" href="{{ route('doctor.statistics') }}"><i class="fas fa-chart-bar me-2"></i>Statistics</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-danger" href="{{ route('logout.get') }}"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid py-4">
            <!-- Stats Cards -->
            <div class="row mb-4">
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="stats-card">
                        <div class="d-flex align-items-center">
                            <div class="stats-icon bg-primary text-white">
                                <i class="fas fa-stethoscope"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h3 class="mb-1 fw-bold text-primary">{{ $totalConsultations }}</h3>
                                <p class="mb-0 text-muted">Total Consultations</p>
                                <small class="text-info">
                                    <i class="fas fa-calendar"></i> Toutes périodes
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="stats-card">
                        <div class="d-flex align-items-center">
                            <div class="stats-icon" style="background: linear-gradient(135deg, var(--success-color), #10b981); color: white;">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h3 class="mb-1 fw-bold text-success">{{ $thisMonthConsultations }}</h3>
                                <p class="mb-0 text-muted">Ce mois</p>
                                <small class="text-success">
                                    <i class="fas fa-calendar-day"></i> {{ now()->format('M Y') }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="stats-card">
                        <div class="d-flex align-items-center">
                            <div class="stats-icon" style="background: linear-gradient(135deg, var(--info-color), #06b6d4); color: white;">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h3 class="mb-1 fw-bold text-info">{{ $thisYearConsultations }}</h3>
                                <p class="mb-0 text-muted">Cette année</p>
                                <small class="text-info">
                                    <i class="fas fa-calendar-alt"></i> {{ now()->format('Y') }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="stats-card">
                        <div class="d-flex align-items-center">
                            <div class="stats-icon" style="background: linear-gradient(135deg, var(--warning-color), #f59e0b); color: white;">
                                <i class="fas fa-trending-{{ $trend >= 0 ? 'up' : 'down' }}"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h3 class="mb-1 fw-bold {{ $trend >= 0 ? 'trend-up' : 'trend-down' }}">
                                    {{ $trend >= 0 ? '+' : '' }}{{ number_format($trend, 1) }}%
                                </h3>
                                <p class="mb-0 text-muted">Tendance</p>
                                <small class="{{ $trend >= 0 ? 'text-success' : 'text-danger' }}">
                                    <i class="fas fa-{{ $trend >= 0 ? 'arrow-up' : 'arrow-down' }}"></i> vs mois dernier
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="row">
                <!-- Monthly Consultations Chart -->
                <div class="col-lg-8 mb-4">
                    <div class="chart-card">
                        <h4 class="mb-3">
                            <i class="fas fa-chart-line me-2 text-primary"></i>
                            Consultations par Mois (12 derniers mois)
                        </h4>
                        <div class="chart-container">
                            <canvas id="monthlyChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Weekly Distribution -->
                <div class="col-lg-4 mb-4">
                    <div class="chart-card">
                        <h4 class="mb-3">
                            <i class="fas fa-calendar-week me-2 text-primary"></i>
                            Répartition par Jour
                        </h4>
                        <div class="chart-container">
                            <canvas id="weeklyChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Diagnoses -->
            <div class="row">
                <div class="col-12">
                    <div class="chart-card">
                        <h4 class="mb-4">
                            <i class="fas fa-list-alt me-2 text-primary"></i>
                            Top 5 des Diagnostics les Plus Fréquents
                        </h4>
                        
                        @if($topDiagnoses->count() > 0)
                            @foreach($topDiagnoses as $index => $diagnosis)
                                <div class="diagnosis-item">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div>
                                            <h6 class="mb-1">{{ $diagnosis['diagnosis'] }}</h6>
                                            <small class="text-muted">{{ $diagnosis['count'] }} consultation(s)</small>
                                        </div>
                                        <div class="text-end">
                                            <span class="badge bg-primary">{{ number_format(($diagnosis['count'] / $totalConsultations) * 100, 1) }}%</span>
                                        </div>
                                    </div>
                                    <div class="progress-custom">
                                        <div class="progress-bar-custom" style="width: {{ ($diagnosis['count'] / $totalConsultations) * 100 }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-chart-pie fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">Aucune donnée de diagnostic disponible</h5>
                                <p class="text-muted">Les diagnostics apparaîtront ici une fois que vous aurez effectué des consultations.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Monthly Consultations Chart
        const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
        const monthlyChart = new Chart(monthlyCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($monthlyLabels) !!},
                datasets: [{
                    label: 'Consultations',
                    data: {!! json_encode($monthlyData) !!},
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37, 99, 235, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#2563eb',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 6,
                    pointHoverRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        },
                        grid: {
                            color: '#f1f5f9'
                        }
                    },
                    x: {
                        grid: {
                            color: '#f1f5f9'
                        }
                    }
                },
                elements: {
                    point: {
                        hoverBackgroundColor: '#1d4ed8'
                    }
                }
            }
        });

        // Weekly Distribution Chart
        const weeklyCtx = document.getElementById('weeklyChart').getContext('2d');
        const weeklyChart = new Chart(weeklyCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($weeklyStats->pluck('day')) !!},
                datasets: [{
                    data: {!! json_encode($weeklyStats->pluck('count')) !!},
                    backgroundColor: [
                        '#2563eb',
                        '#059669',
                        '#d97706',
                        '#dc2626',
                        '#0891b2',
                        '#7c3aed',
                        '#be185d'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true
                        }
                    }
                }
            }
        });

        // Fix dropdown positioning on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Ensure all dropdowns have proper positioning
            const dropdowns = document.querySelectorAll('.dropdown-toggle');
            dropdowns.forEach(dropdown => {
                dropdown.addEventListener('click', function(e) {
                    // Force dropdown to stay within viewport
                    setTimeout(() => {
                        const dropdownMenu = this.nextElementSibling;
                        if (dropdownMenu && dropdownMenu.classList.contains('dropdown-menu')) {
                            const rect = dropdownMenu.getBoundingClientRect();
                            const viewportHeight = window.innerHeight;
                            
                            // If dropdown goes below viewport, adjust position
                            if (rect.bottom > viewportHeight) {
                                dropdownMenu.style.transform = `translateY(-${rect.height + 10}px)`;
                            }
                        }
                    }, 10);
                });
            });
        });
    </script>
</body>
</html>
