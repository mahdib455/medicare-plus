@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 sidebar">
            <div class="sidebar-sticky">
                <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                    <span>Navigation</span>
                </h6>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('doctor.dashboard') }}">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('doctor.planning') }}">
                            <i class="fas fa-calendar-alt"></i> Planning
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('doctor.consultations.all') }}">
                            <i class="fas fa-stethoscope"></i> View Consultations
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('doctor.prescriptions.all') }}">
                            <i class="fas fa-pills"></i> All Prescriptions
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('doctor.reviews') }}">
                            <i class="fas fa-star"></i> Reviews
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Main content -->
        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">
                    <i class="fas fa-stethoscope me-2"></i>All My Consultations
                </h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">
                        <span class="badge bg-success fs-6">{{ $consultations->total() }} consultations</span>
                    </div>
                </div>
            </div>

            <!-- Doctor Info -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card border-success">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h5 class="card-title mb-1">Dr. {{ $doctor->user->full_name }}</h5>
                                    <p class="text-muted mb-0">{{ $doctor->speciality ?? 'General Practitioner' }}</p>
                                </div>
                                <div class="col-md-4 text-end">
                                    <div class="d-flex justify-content-end gap-2">
                                        <span class="badge bg-success">{{ $consultations->total() }} consultations performed</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Consultations List -->
            <div class="row">
                <div class="col-12">
                    @if($consultations->count() > 0)
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-list me-2"></i>Consultations List
                                </h5>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Patient</th>
                                                <th>Consultation Date</th>
                                                <th>Diagnosis</th>
                                                <th>Treatment</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($consultations as $consultation)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-sm me-3">
                                                            <div class="avatar-title bg-success rounded-circle">
                                                                {{ substr($consultation->appointment->patient->user->first_name, 0, 1) }}{{ substr($consultation->appointment->patient->user->last_name, 0, 1) }}
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-0">{{ $consultation->appointment->patient->user->full_name }}</h6>
                                                            <small class="text-muted">{{ $consultation->appointment->patient->user->email }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <strong>{{ $consultation->consultation_date->format('d/m/Y') }}</strong>
                                                        <br>
                                                        <small class="text-muted">{{ $consultation->consultation_date->format('H:i') }}</small>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div style="max-width: 250px;">
                                                        @if($consultation->diagnosis)
                                                            <p class="mb-0">{{ Str::limit($consultation->diagnosis, 80) }}</p>
                                                        @else
                                                            <span class="text-muted">No diagnosis</span>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    <div style="max-width: 250px;">
                                                        @if($consultation->treatment)
                                                            <p class="mb-0">{{ Str::limit($consultation->treatment, 80) }}</p>
                                                        @else
                                                            <span class="text-muted">No treatment</span>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <button type="button" class="btn btn-sm btn-outline-success" 
                                                                onclick="viewConsultationDetails({{ $consultation->id }})"
                                                                title="View details">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-outline-info"
                                                                onclick="viewAppointmentDetails({{ $consultation->appointment_id }})"
                                                                title="View appointment">
                                                            <i class="fas fa-calendar"></i>
                                                        </button>
                                                        @if($consultation->appointment->prescription)
                                                        <button type="button" class="btn btn-sm btn-outline-primary"
                                                                onclick="viewPrescriptionDetails({{ $consultation->appointment->prescription->id }})"
                                                                title="View prescription">
                                                            <i class="fas fa-pills"></i>
                                                        </button>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $consultations->links() }}
                        </div>
                    @else
                        <div class="card">
                            <div class="card-body text-center py-5">
                                <i class="fas fa-stethoscope fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No consultations found</h5>
                                <p class="text-muted">You haven't performed any consultations yet.</p>
                                <a href="{{ route('doctor.planning') }}" class="btn btn-success">
                                    <i class="fas fa-calendar-plus me-2"></i>View Schedule
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Consultation Details Modal -->
<div class="modal fade" id="consultationModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-stethoscope me-2"></i>Détails de la Consultation
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="consultationModalBody">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
</div>

<style>
.sidebar {
    position: fixed;
    top: 0;
    bottom: 0;
    left: 0;
    z-index: 100;
    padding: 48px 0 0;
    box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1);
    background-color: #f8f9fa;
}

.sidebar-sticky {
    position: relative;
    top: 0;
    height: calc(100vh - 48px);
    padding-top: .5rem;
    overflow-x: hidden;
    overflow-y: auto;
}

.nav-link {
    color: #333;
    padding: 0.75rem 1rem;
}

.nav-link:hover {
    color: #007bff;
    background-color: rgba(0,123,255,.1);
}

.nav-link.active {
    color: #007bff;
    background-color: rgba(0,123,255,.1);
    border-right: 3px solid #007bff;
}

.avatar-sm {
    width: 40px;
    height: 40px;
}

.avatar-title {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    color: white;
}

main {
    margin-left: 16.66667%;
}

@media (max-width: 768px) {
    .sidebar {
        position: relative;
        height: auto;
    }
    
    main {
        margin-left: 0;
    }
}
</style>

<script>
function viewConsultationDetails(consultationId) {
    // You can implement this to show consultation details
    alert('Voir détails de la consultation #' + consultationId);
}

function viewAppointmentDetails(appointmentId) {
    // You can implement this to show appointment details
    alert('Voir détails du RDV #' + appointmentId);
}

function viewPrescriptionDetails(prescriptionId) {
    // You can implement this to show prescription details
    alert('Voir détails de la prescription #' + prescriptionId);
}
</script>
@endsection
