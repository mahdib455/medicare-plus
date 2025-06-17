<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointments Management - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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
        .appointment-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        .appointment-card:hover {
            transform: translateY(-2px);
        }
        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 500;
        }
        .status-scheduled { background: #e3f2fd; color: #1565c0; }
        .status-confirmed { background: #e8f5e8; color: #2e7d32; }
        .status-completed { background: #f3e5f5; color: #7b1fa2; }
        .status-cancelled { background: #ffebee; color: #c62828; }
        .search-box {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
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
                        <a class="nav-link" href="{{ route('admin.dashboard') }}">
                            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                        </a>
                        <a class="nav-link" href="{{ route('admin.users') }}">
                            <i class="fas fa-users me-2"></i>Users Management
                        </a>
                        <a class="nav-link active" href="{{ route('admin.appointments') }}">
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
                    <h1 class="h3 mb-0">Appointments Management</h1>
                    <a href="{{ route('admin.appointments.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>New Appointment
                    </a>
                </div>

                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                <!-- Search and Filter -->
                <div class="search-box">
                    <form method="GET" action="{{ route('admin.appointments') }}">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-search"></i>
                                    </span>
                                    <input type="text" class="form-control" name="search" 
                                           placeholder="Search by doctor or patient..." 
                                           value="{{ request('search') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <select class="form-select" name="status">
                                    <option value="">All Status</option>
                                    <option value="scheduled" {{ request('status') === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                    <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="date" class="form-control" name="date" value="{{ request('date') }}">
                            </div>
                            <div class="col-md-2">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-outline-primary">
                                        <i class="fas fa-filter"></i>
                                    </button>
                                    <a href="{{ route('admin.appointments') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-times"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Appointments Table -->
                <div class="appointment-card">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Patient</th>
                                    <th>Doctor</th>
                                    <th>Date & Time</th>
                                    <th>Reason</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($appointments as $appointment)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle me-3" style="width: 35px; height: 35px; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 0.8rem;">
                                                {{ strtoupper(substr($appointment->patient->user->first_name, 0, 1) . substr($appointment->patient->user->last_name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <strong>{{ $appointment->patient->user->full_name }}</strong>
                                                <br><small class="text-muted">{{ $appointment->patient->user->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle me-3" style="width: 35px; height: 35px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 0.8rem;">
                                                {{ strtoupper(substr($appointment->doctor->user->first_name, 0, 1) . substr($appointment->doctor->user->last_name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <strong>Dr. {{ $appointment->doctor->user->full_name }}</strong>
                                                <br><small class="text-muted">{{ $appointment->doctor->specialization }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <strong>{{ $appointment->appointment_date->format('M d, Y') }}</strong>
                                        <br><small class="text-muted">{{ $appointment->appointment_time }}</small>
                                    </td>
                                    <td>
                                        <span title="{{ $appointment->reason }}">
                                            {{ \Illuminate\Support\Str::limit($appointment->reason, 30) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="status-badge status-{{ $appointment->status }}">
                                            <i class="fas fa-{{ $appointment->status === 'completed' ? 'check' : ($appointment->status === 'cancelled' ? 'times' : ($appointment->status === 'confirmed' ? 'check-circle' : 'clock')) }} me-1"></i>
                                            {{ ucfirst($appointment->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.appointments.edit', $appointment) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-danger" 
                                                    onclick="confirmDelete({{ $appointment->id }}, '{{ $appointment->patient->user->full_name }} - {{ $appointment->appointment_date->format('M d, Y') }}')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">No appointments found</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($appointments->hasPages())
                    <div class="p-3 border-top">
                        {{ $appointments->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete appointment: <strong id="appointmentInfo"></strong>?</p>
                    <p class="text-danger"><small>This action cannot be undone.</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form id="deleteForm" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete Appointment</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function confirmDelete(appointmentId, appointmentInfo) {
            document.getElementById('appointmentInfo').textContent = appointmentInfo;
            document.getElementById('deleteForm').action = `/admin/appointments/${appointmentId}`;
            new bootstrap.Modal(document.getElementById('deleteModal')).show();
        }
    </script>
</body>
</html>
