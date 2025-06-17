<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Symptom Checks - Admin Panel</title>
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
        .symptom-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        .symptom-card:hover {
            transform: translateY(-2px);
        }
        .urgency-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 500;
        }
        .urgency-low { background: #e8f5e8; color: #2e7d32; }
        .urgency-medium { background: #fff3cd; color: #856404; }
        .urgency-high { background: #f8d7da; color: #721c24; }
        .urgency-critical { background: #f5c6cb; color: #721c24; font-weight: bold; }
        .search-box {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        .category-tag {
            display: inline-block;
            background: #e9ecef;
            color: #495057;
            padding: 0.2rem 0.5rem;
            border-radius: 12px;
            font-size: 0.7rem;
            margin: 0.1rem;
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
                        <a class="nav-link" href="{{ route('admin.appointments') }}">
                            <i class="fas fa-calendar-alt me-2"></i>Appointments
                        </a>
                        <a class="nav-link active" href="{{ route('admin.symptom-checks') }}">
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
                    <h1 class="h3 mb-0">AI Symptom Checks</h1>
                    <div class="text-muted">
                        <i class="fas fa-brain me-2"></i>AI-Powered Medical Analysis
                    </div>
                </div>

                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                <!-- Search and Filter -->
                <div class="search-box">
                    <form method="GET" action="{{ route('admin.symptom-checks') }}">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-search"></i>
                                    </span>
                                    <input type="text" class="form-control" name="search" 
                                           placeholder="Search symptoms or results..." 
                                           value="{{ request('search') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <select class="form-select" name="urgency">
                                    <option value="">All Urgency Levels</option>
                                    <option value="1" {{ request('urgency') === '1' ? 'selected' : '' }}>1 - Very Low</option>
                                    <option value="2" {{ request('urgency') === '2' ? 'selected' : '' }}>2 - Low</option>
                                    <option value="3" {{ request('urgency') === '3' ? 'selected' : '' }}>3 - Low-Medium</option>
                                    <option value="4" {{ request('urgency') === '4' ? 'selected' : '' }}>4 - Medium</option>
                                    <option value="5" {{ request('urgency') === '5' ? 'selected' : '' }}>5 - Medium</option>
                                    <option value="6" {{ request('urgency') === '6' ? 'selected' : '' }}>6 - Medium-High</option>
                                    <option value="7" {{ request('urgency') === '7' ? 'selected' : '' }}>7 - High</option>
                                    <option value="8" {{ request('urgency') === '8' ? 'selected' : '' }}>8 - Very High</option>
                                    <option value="9" {{ request('urgency') === '9' ? 'selected' : '' }}>9 - Critical</option>
                                    <option value="10" {{ request('urgency') === '10' ? 'selected' : '' }}>10 - Emergency</option>
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
                                    <a href="{{ route('admin.symptom-checks') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-times"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Symptom Checks Table -->
                <div class="symptom-card">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Patient</th>
                                    <th>Symptoms</th>
                                    <th>AI Analysis</th>
                                    <th>Urgency</th>
                                    <th>Categories</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($symptomChecks as $check)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle me-3" style="width: 35px; height: 35px; background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 0.8rem;">
                                                {{ strtoupper(substr($check->user->first_name, 0, 1) . substr($check->user->last_name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <strong>{{ $check->user->full_name }}</strong>
                                                <br><small class="text-muted">{{ $check->user->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span title="{{ $check->symptom_text }}">
                                            {{ $check->short_symptom_text }}
                                        </span>
                                    </td>
                                    <td>
                                        <span title="{{ $check->result }}">
                                            {{ $check->short_result }}
                                        </span>
                                        @if($check->recommended_doctor)
                                        <br><small class="text-muted">
                                            <i class="fas fa-user-md me-1"></i>{{ $check->recommended_doctor }}
                                        </small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="urgency-badge urgency-{{ $check->urgency_level >= 8 ? 'critical' : ($check->urgency_level >= 6 ? 'high' : ($check->urgency_level >= 4 ? 'medium' : 'low')) }}">
                                            {{ $check->urgency_level }}/10
                                        </span>
                                        <br><small class="text-muted">{{ $check->urgency_level_text }}</small>
                                    </td>
                                    <td>
                                        @if($check->detected_categories && is_array($check->detected_categories))
                                            @foreach(array_slice($check->detected_categories, 0, 3) as $category)
                                                <span class="category-tag">{{ $category }}</span>
                                            @endforeach
                                            @if(count($check->detected_categories) > 3)
                                                <span class="category-tag">+{{ count($check->detected_categories) - 3 }} more</span>
                                            @endif
                                        @else
                                            <span class="text-muted">No categories</span>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $check->created_at->format('M d, Y') }}</strong>
                                        <br><small class="text-muted">{{ $check->created_at->format('H:i') }}</small>
                                        <br><small class="text-muted">{{ $check->created_at->diffForHumans() }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.symptom-checks.show', $check) }}" class="btn btn-sm btn-outline-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-danger" 
                                                    onclick="confirmDelete({{ $check->id }}, '{{ $check->user->full_name }} - {{ $check->created_at->format('M d, Y') }}')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <i class="fas fa-brain fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">No symptom checks found</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($symptomChecks->hasPages())
                    <div class="p-3 border-top">
                        {{ $symptomChecks->links() }}
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
                    <p>Are you sure you want to delete symptom check: <strong id="checkInfo"></strong>?</p>
                    <p class="text-danger"><small>This action cannot be undone.</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form id="deleteForm" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete Check</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function confirmDelete(checkId, checkInfo) {
            document.getElementById('checkInfo').textContent = checkInfo;
            document.getElementById('deleteForm').action = `/admin/symptom-checks/${checkId}`;
            new bootstrap.Modal(document.getElementById('deleteModal')).show();
        }
    </script>
</body>
</html>
