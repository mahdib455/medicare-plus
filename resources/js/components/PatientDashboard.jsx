import React, { useState, useEffect } from 'react';
import axios from 'axios';
import ReviewSystem from './ReviewSystem';

const PatientDashboard = () => {
    const [user, setUser] = useState(null);
    const [appointments, setAppointments] = useState([]);
    const [consultations, setConsultations] = useState([]);
    const [prescriptions, setPrescriptions] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);

    useEffect(() => {
        loadDashboardData();
    }, []);

    const loadDashboardData = async () => {
        try {
            setLoading(true);
            
            // Load user data
            const userResponse = await axios.get('/api/user');
            setUser(userResponse.data);

            // Load appointments
            const appointmentsResponse = await axios.get('/api/patient/appointments');
            setAppointments(appointmentsResponse.data.appointments || []);

            // Load consultations
            const consultationsResponse = await axios.get('/api/patient/consultations');
            setConsultations(consultationsResponse.data.consultations || []);

            // Load prescriptions
            const prescriptionsResponse = await axios.get('/api/patient/prescriptions');
            setPrescriptions(prescriptionsResponse.data.prescriptions || []);

        } catch (error) {
            console.error('Error loading dashboard data:', error);
            setError('Error loading data');
        } finally {
            setLoading(false);
        }
    };

    const getStatusBadge = (status) => {
        const statusConfig = {
            'pending': { class: 'bg-warning', text: 'Pending' },
            'confirmed': { class: 'bg-info', text: 'Confirmed' },
            'completed': { class: 'bg-success', text: 'Completed' },
            'cancelled': { class: 'bg-danger', text: 'Cancelled' },
            'termine': { class: 'bg-success', text: 'Completed' }
        };
        
        const config = statusConfig[status] || { class: 'bg-secondary', text: status };
        return <span className={`badge ${config.class}`}>{config.text}</span>;
    };

    if (loading) {
        return (
            <div className="d-flex justify-content-center align-items-center" style={{ minHeight: '400px' }}>
                <div className="spinner-border text-primary" role="status">
                    <span className="visually-hidden">Loading...</span>
                </div>
            </div>
        );
    }

    if (error) {
        return (
            <div className="alert alert-danger" role="alert">
                <i className="fas fa-exclamation-triangle me-2"></i>
                {error}
            </div>
        );
    }

    return (
        <div className="container-fluid">
            {/* Welcome Section */}
            <div className="row mb-4">
                <div className="col-12">
                    <div className="card border-primary">
                        <div className="card-body">
                            <div className="row align-items-center">
                                <div className="col-md-8">
                                    <h4 className="card-title mb-1">
                                        <i className="fas fa-user-circle me-2 text-primary"></i>
                                        Welcome, {user?.full_name || 'Patient'}
                                    </h4>
                                    <p className="text-muted mb-0">
                                        <i className="fas fa-envelope me-2"></i>
                                        {user?.email}
                                    </p>
                                </div>
                                <div className="col-md-4 text-end">
                                    <div className="d-flex justify-content-end gap-2">
                                        <span className="badge bg-primary">Patient</span>
                                        <span className="badge bg-success">Active</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {/* Statistics Cards */}
            <div className="row mb-4">
                <div className="col-md-3">
                    <div className="card text-center border-info">
                        <div className="card-body">
                            <i className="fas fa-calendar-alt fa-2x text-info mb-2"></i>
                            <h5 className="card-title">{appointments.length}</h5>
                            <p className="card-text text-muted">Appointments</p>
                        </div>
                    </div>
                </div>
                <div className="col-md-3">
                    <div className="card text-center border-success">
                        <div className="card-body">
                            <i className="fas fa-stethoscope fa-2x text-success mb-2"></i>
                            <h5 className="card-title">{consultations.length}</h5>
                            <p className="card-text text-muted">Consultations</p>
                        </div>
                    </div>
                </div>
                <div className="col-md-3">
                    <div className="card text-center border-warning">
                        <div className="card-body">
                            <i className="fas fa-pills fa-2x text-warning mb-2"></i>
                            <h5 className="card-title">{prescriptions.length}</h5>
                            <p className="card-text text-muted">Prescriptions</p>
                        </div>
                    </div>
                </div>
                <div className="col-md-3">
                    <div className="card text-center border-primary">
                        <div className="card-body">
                            <i className="fas fa-star fa-2x text-primary mb-2"></i>
                            <h5 className="card-title">0</h5>
                            <p className="card-text text-muted">Reviews</p>
                        </div>
                    </div>
                </div>
            </div>

            {/* Review System */}
            <ReviewSystem />

            {/* Recent Appointments */}
            <div className="row">
                <div className="col-md-6">
                    <div className="card">
                        <div className="card-header">
                            <h5 className="mb-0">
                                <i className="fas fa-calendar me-2"></i>
                                Recent Appointments
                            </h5>
                        </div>
                        <div className="card-body">
                            {appointments.length > 0 ? (
                                <div className="list-group list-group-flush">
                                    {appointments.slice(0, 5).map((appointment) => (
                                        <div key={appointment.id} className="list-group-item">
                                            <div className="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <h6 className="mb-1">Dr. {appointment.doctor?.name}</h6>
                                                    <p className="mb-1 text-muted">{appointment.reason}</p>
                                                    <small className="text-muted">
                                                        <i className="fas fa-clock me-1"></i>
                                                        {appointment.formatted_date}
                                                    </small>
                                                </div>
                                                <div>
                                                    {getStatusBadge(appointment.status)}
                                                </div>
                                            </div>
                                        </div>
                                    ))}
                                </div>
                            ) : (
                                <div className="text-center py-4">
                                    <i className="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                    <p className="text-muted">No appointments found</p>
                                </div>
                            )}
                        </div>
                    </div>
                </div>

                {/* Recent Consultations */}
                <div className="col-md-6">
                    <div className="card">
                        <div className="card-header">
                            <h5 className="mb-0">
                                <i className="fas fa-stethoscope me-2"></i>
                                Recent Consultations
                            </h5>
                        </div>
                        <div className="card-body">
                            {consultations.length > 0 ? (
                                <div className="list-group list-group-flush">
                                    {consultations.slice(0, 5).map((consultation) => (
                                        <div key={consultation.id} className="list-group-item">
                                            <div className="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <h6 className="mb-1">Dr. {consultation.doctor?.name}</h6>
                                                    <p className="mb-1 text-muted">{consultation.diagnosis}</p>
                                                    <small className="text-muted">
                                                        <i className="fas fa-clock me-1"></i>
                                                        {consultation.formatted_date}
                                                    </small>
                                                </div>
                                                <div>
                                                    <span className="badge bg-success">Completed</span>
                                                </div>
                                            </div>
                                        </div>
                                    ))}
                                </div>
                            ) : (
                                <div className="text-center py-4">
                                    <i className="fas fa-stethoscope fa-3x text-muted mb-3"></i>
                                    <p className="text-muted">No consultations found</p>
                                </div>
                            )}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default PatientDashboard;
