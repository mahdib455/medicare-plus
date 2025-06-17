import React, { useState, useEffect } from 'react';
import axios from 'axios';

const CreateReview = () => {
    const [user, setUser] = useState(null);
    const [consultations, setConsultations] = useState([]);
    const [selectedConsultation, setSelectedConsultation] = useState(null);
    const [rating, setRating] = useState(0);
    const [comment, setComment] = useState('');
    const [isAnonymous, setIsAnonymous] = useState(false);
    const [loading, setLoading] = useState(true);
    const [submitting, setSubmitting] = useState(false);
    const [error, setError] = useState('');
    const [success, setSuccess] = useState('');
    const [showConsultationModal, setShowConsultationModal] = useState(false);
    const [showReviewForm, setShowReviewForm] = useState(false);

    // Configure axios for Sanctum authentication
    useEffect(() => {
        // Set up CSRF token
        axios.defaults.withCredentials = true;
        axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
        
        // Get CSRF token
        const token = document.querySelector('meta[name="csrf-token"]');
        if (token) {
            axios.defaults.headers.common['X-CSRF-TOKEN'] = token.getAttribute('content');
        }

        loadUserAndConsultations();
    }, []);

    const loadUserAndConsultations = async () => {
        try {
            setLoading(true);
            setError('');

            // Get authenticated user
            const userResponse = await axios.get('/api/user');
            setUser(userResponse.data);

            // Get unreviewed consultations
            const consultationsResponse = await axios.get('/api/consultations/unreviewed');
            setConsultations(consultationsResponse.data.data.consultations);

        } catch (error) {
            console.error('Error loading data:', error);
            if (error.response?.status === 401) {
                setError('Please log in to submit reviews');
            } else {
                setError('Error loading data. Please try again.');
            }
        } finally {
            setLoading(false);
        }
    };

    const selectConsultation = (consultation) => {
        setSelectedConsultation(consultation);
        setShowConsultationModal(false);
        setShowReviewForm(true);
        setRating(0);
        setComment('');
        setIsAnonymous(false);
        setError('');
        setSuccess('');
    };

    const submitReview = async () => {
        if (!selectedConsultation) {
            setError('Please select a consultation to review');
            return;
        }

        if (rating === 0) {
            setError('Please select a rating');
            return;
        }

        try {
            setSubmitting(true);
            setError('');

            const reviewData = {
                consultation_id: selectedConsultation.id,
                doctor_id: selectedConsultation.doctor.id,
                rating: rating,
                comment: comment,
                is_anonymous: isAnonymous
            };

            const response = await axios.post('/api/reviews', reviewData);

            if (response.data.success) {
                setSuccess('Review submitted successfully!');
                setShowReviewForm(false);
                setSelectedConsultation(null);
                // Reload consultations to remove the reviewed one
                loadUserAndConsultations();
            }

        } catch (error) {
            console.error('Error submitting review:', error);
            if (error.response?.data?.error) {
                setError(error.response.data.error);
            } else if (error.response?.data?.details) {
                const validationErrors = Object.values(error.response.data.details).flat();
                setError(validationErrors.join(', '));
            } else {
                setError('Error submitting review. Please try again.');
            }
        } finally {
            setSubmitting(false);
        }
    };

    const renderStars = (currentRating, interactive = false) => {
        const stars = [];
        for (let i = 1; i <= 5; i++) {
            stars.push(
                <i
                    key={i}
                    className={`fas fa-star ${i <= currentRating ? 'text-warning' : 'text-muted'} ${interactive ? 'star-interactive' : ''}`}
                    style={{ 
                        cursor: interactive ? 'pointer' : 'default',
                        fontSize: '1.5rem',
                        marginRight: '0.25rem'
                    }}
                    onClick={interactive ? () => setRating(i) : undefined}
                ></i>
            );
        }
        return stars;
    };

    const getRatingText = (rating) => {
        const texts = {
            1: 'Very Poor',
            2: 'Poor',
            3: 'Average',
            4: 'Good',
            5: 'Excellent'
        };
        return texts[rating] || 'Click stars to rate';
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

    return (
        <div className="container mt-4">
            <div className="row justify-content-center">
                <div className="col-md-8">
                    <div className="card shadow">
                        <div className="card-header bg-primary text-white">
                            <h4 className="mb-0">
                                <i className="fas fa-star me-2"></i>
                                Submit Review
                            </h4>
                        </div>
                        <div className="card-body">
                            {error && (
                                <div className="alert alert-danger">
                                    <i className="fas fa-exclamation-triangle me-2"></i>
                                    {error}
                                </div>
                            )}

                            {success && (
                                <div className="alert alert-success">
                                    <i className="fas fa-check-circle me-2"></i>
                                    {success}
                                </div>
                            )}

                            {user && (
                                <div className="mb-4">
                                    <h6>Welcome, {user.full_name}</h6>
                                    <p className="text-muted">You have {consultations.length} consultation(s) available for review.</p>
                                </div>
                            )}

                            {consultations.length === 0 ? (
                                <div className="text-center py-4">
                                    <i className="fas fa-check-circle fa-3x text-success mb-3"></i>
                                    <h5>All consultations reviewed!</h5>
                                    <p className="text-muted">You have reviewed all your consultations.</p>
                                </div>
                            ) : (
                                <div className="text-center">
                                    <button 
                                        className="btn btn-primary btn-lg"
                                        onClick={() => setShowConsultationModal(true)}
                                    >
                                        <i className="fas fa-plus me-2"></i>
                                        Select Consultation to Review
                                    </button>
                                </div>
                            )}
                        </div>
                    </div>
                </div>
            </div>

            {/* Consultation Selection Modal */}
            {showConsultationModal && (
                <div className="modal show d-block" style={{ backgroundColor: 'rgba(0,0,0,0.5)' }}>
                    <div className="modal-dialog modal-lg">
                        <div className="modal-content">
                            <div className="modal-header">
                                <h5 className="modal-title">
                                    <i className="fas fa-stethoscope me-2"></i>
                                    Select a Consultation to Review
                                </h5>
                                <button 
                                    type="button" 
                                    className="btn-close"
                                    onClick={() => setShowConsultationModal(false)}
                                ></button>
                            </div>
                            <div className="modal-body">
                                <p className="text-muted mb-3">Choose the consultation you want to review:</p>
                                
                                {consultations.map((consultation) => (
                                    <div 
                                        key={consultation.id}
                                        className="card mb-3 consultation-card"
                                        style={{ cursor: 'pointer' }}
                                        onClick={() => selectConsultation(consultation)}
                                    >
                                        <div className="card-body">
                                            <div className="row">
                                                <div className="col-md-8">
                                                    <h6 className="card-title">
                                                        Dr. {consultation.doctor.name}
                                                    </h6>
                                                    <p className="text-muted mb-1">
                                                        <i className="fas fa-calendar me-1"></i>
                                                        {consultation.formatted_date}
                                                    </p>
                                                    <p className="text-muted mb-1">
                                                        <i className="fas fa-user-md me-1"></i>
                                                        {consultation.doctor.speciality}
                                                    </p>
                                                    {consultation.diagnosis && (
                                                        <p className="text-muted mb-0">
                                                            <i className="fas fa-diagnoses me-1"></i>
                                                            <strong>Diagnosis:</strong> {consultation.diagnosis}
                                                        </p>
                                                    )}
                                                </div>
                                                <div className="col-md-4 text-end">
                                                    <button className="btn btn-outline-primary">
                                                        <i className="fas fa-star me-1"></i>
                                                        Review
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                ))}
                            </div>
                        </div>
                    </div>
                </div>
            )}

            {/* Review Form Modal */}
            {showReviewForm && selectedConsultation && (
                <div className="modal show d-block" style={{ backgroundColor: 'rgba(0,0,0,0.5)' }}>
                    <div className="modal-dialog">
                        <div className="modal-content">
                            <div className="modal-header">
                                <h5 className="modal-title">
                                    Review Consultation - Dr. {selectedConsultation.doctor.name}
                                </h5>
                                <button 
                                    type="button" 
                                    className="btn-close"
                                    onClick={() => setShowReviewForm(false)}
                                ></button>
                            </div>
                            <div className="modal-body">
                                <div className="mb-3">
                                    <p><strong>Date:</strong> {selectedConsultation.formatted_date}</p>
                                    <p><strong>Doctor:</strong> Dr. {selectedConsultation.doctor.name}</p>
                                    <p><strong>Speciality:</strong> {selectedConsultation.doctor.speciality}</p>
                                </div>

                                <div className="mb-3">
                                    <label className="form-label">Rating *</label>
                                    <div className="text-center">
                                        <div className="rating-stars mb-2">
                                            {renderStars(rating, true)}
                                        </div>
                                        <small className="text-muted">{getRatingText(rating)}</small>
                                    </div>
                                </div>

                                <div className="mb-3">
                                    <label className="form-label">Comment (optional)</label>
                                    <textarea 
                                        className="form-control" 
                                        rows="3"
                                        value={comment}
                                        onChange={(e) => setComment(e.target.value)}
                                        placeholder="Share your experience..."
                                        maxLength="1000"
                                    ></textarea>
                                    <div className="form-text">
                                        {comment.length}/1000 characters
                                    </div>
                                </div>

                                <div className="mb-3">
                                    <div className="form-check">
                                        <input 
                                            className="form-check-input" 
                                            type="checkbox" 
                                            id="anonymousCheck"
                                            checked={isAnonymous}
                                            onChange={(e) => setIsAnonymous(e.target.checked)}
                                        />
                                        <label className="form-check-label" htmlFor="anonymousCheck">
                                            Submit anonymously
                                        </label>
                                        <div className="form-text">
                                            Your name will be hidden from the doctor if checked.
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div className="modal-footer">
                                <button 
                                    type="button" 
                                    className="btn btn-secondary"
                                    onClick={() => setShowReviewForm(false)}
                                >
                                    Cancel
                                </button>
                                <button 
                                    type="button" 
                                    className="btn btn-primary"
                                    onClick={submitReview}
                                    disabled={submitting || rating === 0}
                                >
                                    {submitting ? (
                                        <>
                                            <i className="fas fa-spinner fa-spin me-2"></i>
                                            Submitting...
                                        </>
                                    ) : (
                                        <>
                                            <i className="fas fa-paper-plane me-2"></i>
                                            Submit Review
                                        </>
                                    )}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            )}

            <style jsx>{`
                .consultation-card:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
                    transition: all 0.3s ease;
                }
                
                .star-interactive:hover {
                    transform: scale(1.1);
                    transition: transform 0.2s ease;
                }
            `}</style>
        </div>
    );
};

export default CreateReview;
