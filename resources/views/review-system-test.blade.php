<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Review System Test - MediCare+</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.jsx'])
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Header -->
            <div class="col-12">
                <div class="bg-primary text-white p-4 mb-4">
                    <div class="container">
                        <h1 class="display-4">
                            <i class="fas fa-star me-3"></i>
                            Complete Review System Test
                        </h1>
                        <p class="lead mb-0">Laravel + React Review System with Sanctum Authentication</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="row">
                <!-- System Overview -->
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-check-circle me-2"></i>
                                System Features
                            </h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <i class="fas fa-shield-alt text-success me-2"></i>
                                    <strong>Sanctum Authentication</strong>
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-user-md text-primary me-2"></i>
                                    <strong>Patient-Only Reviews</strong>
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-star text-warning me-2"></i>
                                    <strong>1-5 Star Rating</strong>
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-comment text-info me-2"></i>
                                    <strong>Optional Comments</strong>
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-user-secret text-secondary me-2"></i>
                                    <strong>Anonymous Option</strong>
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-ban text-danger me-2"></i>
                                    <strong>Duplicate Prevention</strong>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- API Endpoints -->
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-api me-2"></i>
                                API Endpoints
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <h6>POST /api/reviews</h6>
                                <small class="text-muted">Submit a new review</small>
                            </div>
                            <div class="mb-3">
                                <h6>GET /api/consultations/unreviewed</h6>
                                <small class="text-muted">Get consultations without reviews</small>
                            </div>
                            <div class="mb-3">
                                <h6>GET /api/my-reviews</h6>
                                <small class="text-muted">Get patient's reviews</small>
                            </div>
                            <div class="alert alert-warning">
                                <small>
                                    <i class="fas fa-lock me-1"></i>
                                    All endpoints require <code>auth:sanctum</code>
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Database Schema -->
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="mb-0">
                                <i class="fas fa-database me-2"></i>
                                Database Schema
                            </h5>
                        </div>
                        <div class="card-body">
                            <div style="font-family: monospace; font-size: 0.85rem;">
                                <strong>reviews table:</strong><br>
                                • id (primary)<br>
                                • consultation_id<br>
                                • doctor_id<br>
                                • patient_id<br>
                                • rating (1-5)<br>
                                • comment (nullable)<br>
                                • is_anonymous (boolean)<br>
                                • status (enum)<br>
                                • timestamps<br><br>
                                
                                <strong>Unique constraint:</strong><br>
                                consultation_id + patient_id
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- React Component Test -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">
                                <i class="fab fa-react me-2"></i>
                                React CreateReview Component Test
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info">
                                <h6>
                                    <i class="fas fa-info-circle me-2"></i>
                                    Authentication Required
                                </h6>
                                <p class="mb-0">
                                    To test the review system, you need to be logged in as a patient. 
                                    The component will automatically fetch your consultations and allow you to submit reviews.
                                </p>
                            </div>

                            <!-- React Component Container -->
                            <div id="create-review-react"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Testing Instructions -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-secondary text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-clipboard-list me-2"></i>
                                Testing Instructions
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Prerequisites:</h6>
                                    <ol>
                                        <li>Be logged in as a patient user</li>
                                        <li>Have at least one consultation in the database</li>
                                        <li>Consultation should not be reviewed yet</li>
                                        <li>Valid CSRF token in meta tag</li>
                                    </ol>
                                </div>
                                <div class="col-md-6">
                                    <h6>Test Steps:</h6>
                                    <ol>
                                        <li>Click "Select Consultation to Review"</li>
                                        <li>Choose a consultation from the modal</li>
                                        <li>Rate the consultation (1-5 stars)</li>
                                        <li>Add optional comment</li>
                                        <li>Choose anonymity option</li>
                                        <li>Submit the review</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="row mt-4 mb-4">
                <div class="col-md-3">
                    <a href="/patient/dashboard" class="btn btn-success w-100">
                        <i class="fas fa-user me-2"></i>
                        Patient Dashboard
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="/doctor/dashboard" class="btn btn-info w-100">
                        <i class="fas fa-user-md me-2"></i>
                        Doctor Dashboard
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="/api/consultations/unreviewed" class="btn btn-warning w-100" target="_blank">
                        <i class="fas fa-api me-2"></i>
                        Test API
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="/review-fix-demo" class="btn btn-secondary w-100">
                        <i class="fas fa-tools me-2"></i>
                        Debug Tools
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
