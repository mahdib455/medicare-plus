import React, { useState, useEffect } from 'react';
import axios from 'axios';

const ReviewSystem = () => {
    const [consultations, setConsultations] = useState([]);
    const [myReviews, setMyReviews] = useState([]);
    const [loading, setLoading] = useState(false);
    const [showConsultationModal, setShowConsultationModal] = useState(false);
    const [showReviewModal, setShowReviewModal] = useState(false);
    const [selectedConsultation, setSelectedConsultation] = useState(null);
    const [rating, setRating] = useState(0);
    const [comment, setComment] = useState('');
    const [submitting, setSubmitting] = useState(false);

    useEffect(() => {
        loadMyReviews();
    }, []);

    const loadConsultations = async () => {
        try {
            setLoading(true);
            const response = await axios.get('/api/all-consultations');
            if (response.data.success) {
                setConsultations(response.data.consultations);
            }
        } catch (error) {
            console.error('Error loading consultations:', error);
        } finally {
            setLoading(false);
        }
    };

    const loadMyReviews = async () => {
        try {
            const response = await axios.get('/api/my-reviews');
            if (response.data.success) {
                setMyReviews(response.data.reviews);
            }
        } catch (error) {
            console.error('Error loading reviews:', error);
        }
    };

    const openConsultationModal = () => {
        loadConsultations();
        setShowConsultationModal(true);
    };

    const selectConsultation = (consultation) => {
        if (consultation.is_reviewed) {
            alert('This consultation has already been reviewed.');
            return;
        }
        setSelectedConsultation(consultation);
        setShowConsultationModal(false);
        setShowReviewModal(true);
        setRating(0);
        setComment('');
    };

    const submitReview = async () => {
        if (rating === 0) {
            alert('Please select a rating.');
            return;
        }

        try {
            setSubmitting(true);
            const response = await axios.post('/api/consultation-reviews', {
                consultation_id: selectedConsultation.id,
                rating: rating,
                comment: comment
            });

            if (response.data.success) {
                alert('Review submitted successfully!');
                setShowReviewModal(false);
                loadMyReviews();
            }
        } catch (error) {
            console.error('Error submitting review:', error);
            alert('Error submitting review.');
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
                    className={`fas fa-star ${i <= currentRating ? 'text-warning' : 'text-muted'}`}
                    style={{ cursor: interactive ? 'pointer' : 'default' }}
                    onClick={interactive ? () => setRating(i) : undefined}
                />
            );
        }
        return stars;
    };

    const getRatingText = (rating) => {
        const texts = {
            1: 'Very Dissatisfied',
            2: 'Dissatisfied',
            3: 'Neutral',
            4: 'Satisfied',
            5: 'Very Satisfied'
        };
        return texts[rating] || 'Click stars to rate';
    };

    return (
        <div className="row mb-4">
            {/* Add Review Section */}
            <div className="col-md-6">
                <div className="card border-warning">
                    <div className="card-header" style={{ background: 'linear-gradient(135deg, #fef3c7 0%, #fde68a 100%)', color: '#92400e' }}>
                        <h5 className="mb-0">
                            <i className="fas fa-star me-2"></i>
                            Add Review
                        </h5>
                    </div>
                    <div className="card-body text-center">
                        <i className="fas fa-star fa-3x text-warning mb-3"></i>
                        <h6 className="mb-2">Rate your consultations</h6>
                        <p className="text-muted mb-3">Share your experience with your doctors</p>
                        <button
                            className="btn btn-warning"
                            onClick={openConsultationModal}
                        >
                            <i className="fas fa-plus me-2"></i>
                            Add Review
                        </button>
                    </div>
                </div>
            </div>

            {/* My Reviews Section */}
            <div className="col-md-6">
                <div className="card border-primary">
                    <div className="card-header" style={{ background: 'linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%)', color: '#3730a3' }}>
                        <h5 className="mb-0">
                            <i className="fas fa-history me-2"></i>
                            My Reviews ({myReviews.length})
                        </h5>
                    </div>
                    <div className="card-body">
                        {myReviews.length > 0 ? (
                            <div style={{ maxHeight: '300px', overflowY: 'auto' }}>
                                {myReviews.map((review) => (
                                    <div key={review.id} className="mb-3 p-3" style={{ background: '#f8fafc', borderRadius: '6px', borderLeft: '3px solid #3730a3' }}>
                                        <div className="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 className="mb-1">Dr. {review.appointment.doctor.name}</h6>
                                                <small className="text-muted">{review.appointment.doctor.speciality}</small>
                                                <div className="mt-1">
                                                    {renderStars(review.rating)}
                                                    <small className="text-muted ms-2">{review.rating_text}</small>
                                                </div>
                                            </div>
                                            <small className="text-muted">{review.time_ago}</small>
                                        </div>
                                        {review.comment && (
                                            <div className="mt-2">
                                                <small className="text-dark">
                                                    <i className="fas fa-quote-left me-1"></i>
                                                    {review.comment}
                                                </small>
                                            </div>
                                        )}
                                    </div>
                                ))}
                            </div>
                        ) : (
                            <div className="text-center py-4">
                                <i className="fas fa-star fa-3x text-muted mb-3"></i>
                                <p className="text-muted">No reviews found</p>
                                <small className="text-muted">Start by reviewing a consultation</small>
                            </div>
                        )}
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
                                
                                {loading ? (
                                    <div className="text-center py-4">
                                        <div className="spinner-border text-primary" role="status">
                                            <span className="visually-hidden">Loading...</span>
                                        </div>
                                    </div>
                                ) : consultations.length > 0 ? (
                                    <div style={{ maxHeight: '400px', overflowY: 'auto' }}>
                                        {consultations.map((consultation) => (
                                            <div 
                                                key={consultation.id}
                                                className={`consultation-item p-3 mb-2 border rounded ${consultation.is_reviewed ? 'bg-light' : 'bg-white'}`}
                                                style={{ cursor: consultation.is_reviewed ? 'not-allowed' : 'pointer' }}
                                                onClick={() => selectConsultation(consultation)}
                                            >
                                                <div className="row align-items-center">
                                                    <div className="col-md-6">
                                                        <div className="d-flex align-items-center">
                                                            <div className="me-3" style={{ width: '40px', height: '40px', background: '#28a745', borderRadius: '50%', display: 'flex', alignItems: 'center', justifyContent: 'center', color: 'white', fontWeight: 'bold' }}>
                                                                {consultation.doctor.name.charAt(0)}
                                                            </div>
                                                            <div>
                                                                <h6 className="mb-1">Dr. {consultation.doctor.name}</h6>
                                                                <small className="text-muted">{consultation.doctor.speciality}</small>
                                                                <div className="mt-1">
                                                                    <small className="text-primary">
                                                                        <i className="fas fa-calendar me-1"></i>
                                                                        {consultation.formatted_date}
                                                                    </small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div className="col-md-3 text-center">
                                                        <span className="badge bg-success mb-1">Consultation</span>
                                                        {consultation.has_prescription && (
                                                            <>
                                                                <br />
                                                                <small className="text-info">
                                                                    <i className="fas fa-pills me-1"></i>
                                                                    Prescription
                                                                </small>
                                                            </>
                                                        )}
                                                    </div>
                                                    <div className="col-md-3 text-end">
                                                        {consultation.is_reviewed ? (
                                                            <span className="badge bg-success">
                                                                <i className="fas fa-star me-1"></i>
                                                                Reviewed
                                                            </span>
                                                        ) : (
                                                            <span className="badge bg-warning">
                                                                <i className="fas fa-clock me-1"></i>
                                                                Not Reviewed
                                                            </span>
                                                        )}
                                                    </div>
                                                </div>
                                                {consultation.diagnosis && (
                                                    <div className="row mt-2">
                                                        <div className="col-12">
                                                            <small className="text-muted">
                                                                <i className="fas fa-diagnoses me-1"></i>
                                                                <strong>Diagnosis:</strong> {consultation.diagnosis}
                                                            </small>
                                                        </div>
                                                    </div>
                                                )}
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
            )}

            {/* Review Modal */}
            {showReviewModal && selectedConsultation && (
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
                                    onClick={() => setShowReviewModal(false)}
                                ></button>
                            </div>
                            <div className="modal-body">
                                <div className="text-center mb-4">
                                    <h6>Dr. {selectedConsultation.doctor.name}</h6>
                                    <small className="text-muted">{selectedConsultation.formatted_date}</small>
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
                                    ></textarea>
                                    <div className="form-text">Your comment will be anonymous to the doctor.</div>
                                </div>
                            </div>
                            <div className="modal-footer">
                                <button
                                    type="button"
                                    className="btn btn-secondary"
                                    onClick={() => setShowReviewModal(false)}
                                >
                                    Cancel
                                </button>
                                <button
                                    type="button"
                                    className="btn btn-warning"
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
        </div>
    );
};

export default ReviewSystem;
