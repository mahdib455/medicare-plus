<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Medical System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 0.75rem 1rem;
            margin: 0.25rem 0;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            color: white;
            background: rgba(255,255,255,0.1);
            transform: translateX(5px);
        }
        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }
        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
        }
        .chart-container {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        .recent-activity {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .activity-item {
            padding: 0.75rem 0;
            border-bottom: 1px solid #eee;
        }
        .activity-item:last-child {
            border-bottom: none;
        }
        .urgency-badge {
            padding: 0.25rem 0.5rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 500;
        }
        .urgency-low { background: #dcfce7; color: #166534; }
        .urgency-medium { background: #fef3c7; color: #92400e; }
        .urgency-high { background: #fee2e2; color: #991b1b; }
        .urgency-critical { background: #fecaca; color: #7f1d1d; }
    </style>
</head>
<body class="bg-light">
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar p-0">
                <div class="p-3">
                    <h4 class="text-white mb-4">
                        <i class="fas fa-user-shield me-2"></i>
                        Admin Panel
                    </h4>
                    <nav class="nav flex-column">
                        <a class="nav-link active" href="{{ route('admin.dashboard') }}">
                            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                        </a>
                        <a class="nav-link" href="{{ route('admin.users') }}">
                            <i class="fas fa-users me-2"></i>Users Management
                        </a>
                        <a class="nav-link" href="{{ route('admin.appointments') }}">
                            <i class="fas fa-calendar-alt me-2"></i>Appointments
                        </a>
                        <a class="nav-link" href="{{ route('admin.symptom-checks') }}">
                            <i class="fas fa-brain me-2"></i>Symptom Checks
                        </a>
                        <hr class="my-3" style="border-color: rgba(255,255,255,0.3);">
                        <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </nav>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h3 mb-0">Admin Dashboard</h1>
                    <div class="text-muted">
                        <i class="fas fa-calendar me-2"></i>{{ now()->format('F d, Y') }}
                    </div>
                </div>

                <!-- Statistics Cards -->
                <div class="row mb-4">
                    <div class="col-md-3 mb-3">
                        <div class="stat-card">
                            <div class="d-flex align-items-center">
                                <div class="stat-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                    <i class="fas fa-users"></i>
                                </div>
                                <div class="ms-3">
                                    <h3 class="mb-0">{{ $stats['total_users'] }}</h3>
                                    <p class="text-muted mb-0">Total Users</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="stat-card">
                            <div class="d-flex align-items-center">
                                <div class="stat-icon" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                                    <i class="fas fa-user-md"></i>
                                </div>
                                <div class="ms-3">
                                    <h3 class="mb-0">{{ $stats['total_doctors'] }}</h3>
                                    <p class="text-muted mb-0">Doctors</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="stat-card">
                            <div class="d-flex align-items-center">
                                <div class="stat-icon" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                                    <i class="fas fa-calendar-check"></i>
                                </div>
                                <div class="ms-3">
                                    <h3 class="mb-0">{{ $stats['today_appointments'] }}</h3>
                                    <p class="text-muted mb-0">Today's Appointments</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="stat-card">
                            <div class="d-flex align-items-center">
                                <div class="stat-icon" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                                    <i class="fas fa-brain"></i>
                                </div>
                                <div class="ms-3">
                                    <h3 class="mb-0">{{ $stats['today_symptom_checks'] }}</h3>
                                    <p class="text-muted mb-0">Today's AI Checks</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Stats Row -->
                <div class="row mb-4">
                    <div class="col-md-3 mb-3">
                        <div class="stat-card">
                            <div class="d-flex align-items-center">
                                <div class="stat-icon" style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);">
                                    <i class="fas fa-user-injured"></i>
                                </div>
                                <div class="ms-3">
                                    <h3 class="mb-0">{{ $stats['total_patients'] }}</h3>
                                    <p class="text-muted mb-0">Patients</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="stat-card">
                            <div class="d-flex align-items-center">
                                <div class="stat-icon" style="background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);">
                                    <i class="fas fa-stethoscope"></i>
                                </div>
                                <div class="ms-3">
                                    <h3 class="mb-0">{{ $stats['total_consultations'] }}</h3>
                                    <p class="text-muted mb-0">Consultations</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="stat-card">
                            <div class="d-flex align-items-center">
                                <div class="stat-icon" style="background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                                <div class="ms-3">
                                    <h3 class="mb-0">{{ $stats['high_urgency_symptoms'] }}</h3>
                                    <p class="text-muted mb-0">High Urgency</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="stat-card">
                            <div class="d-flex align-items-center">
                                <div class="stat-icon" style="background: linear-gradient(135deg, #a1c4fd 0%, #c2e9fb 100%);">
                                    <i class="fas fa-prescription-bottle-alt"></i>
                                </div>
                                <div class="ms-3">
                                    <h3 class="mb-0">{{ $stats['total_prescriptions'] }}</h3>
                                    <p class="text-muted mb-0">Prescriptions</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts Row -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="chart-container">
                            <h5 class="mb-3">Appointments This Week</h5>
                            <canvas id="appointmentsChart" height="200"></canvas>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="chart-container">
                            <h5 class="mb-3">Symptom Checks This Week</h5>
                            <canvas id="symptomChecksChart" height="200"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="recent-activity">
                            <h5 class="mb-3">Recent Appointments</h5>
                            @forelse($stats['recent_appointments'] as $appointment)
                            <div class="activity-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $appointment->patient->user->full_name }}</strong>
                                        <br>
                                        <small class="text-muted">
                                            Dr. {{ $appointment->doctor->user->full_name }} - 
                                            {{ $appointment->appointment_date->format('M d, Y') }}
                                        </small>
                                    </div>
                                    <span class="badge bg-{{ $appointment->status === 'completed' ? 'success' : ($appointment->status === 'cancelled' ? 'danger' : 'primary') }}">
                                        {{ ucfirst($appointment->status) }}
                                    </span>
                                </div>
                            </div>
                            @empty
                            <p class="text-muted">No recent appointments</p>
                            @endforelse
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="recent-activity">
                            <h5 class="mb-3">Recent Symptom Checks</h5>
                            @forelse($stats['recent_symptom_checks'] as $check)
                            <div class="activity-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $check->user->full_name }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $check->short_symptom_text }}</small>
                                    </div>
                                    <div class="text-end">
                                        <span class="urgency-badge urgency-{{ $check->urgency_level >= 8 ? 'critical' : ($check->urgency_level >= 6 ? 'high' : ($check->urgency_level >= 4 ? 'medium' : 'low')) }}">
                                            {{ $check->urgency_level }}/10
                                        </span>
                                        <br>
                                        <small class="text-muted">{{ $check->created_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <p class="text-muted">No recent symptom checks</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Appointments Chart
        const appointmentsCtx = document.getElementById('appointmentsChart').getContext('2d');
        new Chart(appointmentsCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode(array_column($chartData['appointments_by_day'], 'date')) !!},
                datasets: [{
                    label: 'Appointments',
                    data: {!! json_encode(array_column($chartData['appointments_by_day'], 'count')) !!},
                    borderColor: 'rgb(102, 126, 234)',
                    backgroundColor: 'rgba(102, 126, 234, 0.1)',
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Symptom Checks Chart
        const symptomChecksCtx = document.getElementById('symptomChecksChart').getContext('2d');
        new Chart(symptomChecksCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode(array_column($chartData['symptom_checks_by_day'], 'date')) !!},
                datasets: [{
                    label: 'Symptom Checks',
                    data: {!! json_encode(array_column($chartData['symptom_checks_by_day'], 'count')) !!},
                    backgroundColor: 'rgba(245, 87, 108, 0.8)',
                    borderColor: 'rgb(245, 87, 108)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>
