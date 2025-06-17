<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Mes Évaluations - MediCare+</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --doctor-gradient: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
            --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--doctor-gradient);
            min-height: 100vh;
        }

        .main-container {
            background: #f8fafc;
            min-height: 100vh;
            border-radius: 20px 20px 0 0;
            margin-top: 2rem;
            box-shadow: var(--card-shadow);
        }

        .header-section {
            background: var(--doctor-gradient);
            color: white;
            padding: 2rem;
            border-radius: 20px 20px 0 0;
        }

        .stats-card {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: var(--card-shadow);
            margin-bottom: 1.5rem;
            border: none;
        }

        .rating-overview {
            text-align: center;
            padding: 2rem;
        }

        .rating-number {
            font-size: 4rem;
            font-weight: bold;
            color: #f59e0b;
            margin-bottom: 0.5rem;
        }

        .rating-stars {
            font-size: 1.5rem;
            color: #f59e0b;
            margin-bottom: 1rem;
        }

        .rating-bar {
            display: flex;
            align-items: center;
            margin-bottom: 0.5rem;
        }

        .rating-bar-fill {
            height: 8px;
            background: #f59e0b;
            border-radius: 4px;
            transition: width 0.3s ease;
        }

        .rating-bar-bg {
            height: 8px;
            background: #e5e7eb;
            border-radius: 4px;
            flex: 1;
            margin: 0 1rem;
        }

        .review-item {
            background: #f8fafc;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            border-left: 4px solid #f59e0b;
        }

        .review-stars {
            color: #f59e0b;
            font-size: 1rem;
        }

        .anonymous-badge {
            background: #e0e7ff;
            color: #3730a3;
            padding: 0.25rem 0.5rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="main-container">
        <!-- Header -->
        <div class="header-section">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h1 class="mb-2">
                            <i class="fas fa-star me-3"></i>
                            Mes Évaluations Patients
                        </h1>
                        <p class="mb-0 opacity-75">
                            <i class="fas fa-chart-line me-2"></i>Statistiques et commentaires anonymes
                        </p>
                    </div>
                    <div class="col-md-4 text-end">
                        <a href="{{ route('doctor.dashboard') }}" class="btn btn-light">
                            <i class="fas fa-arrow-left me-2"></i>Retour au Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid py-4">
            <div class="row">
                <!-- Statistics Overview -->
                <div class="col-lg-4 mb-4">
                    <div class="stats-card">
                        <div class="rating-overview">
                            <div class="rating-number" id="averageRating">0.0</div>
                            <div class="rating-stars" id="averageStars">
                                ☆☆☆☆☆
                            </div>
                            <p class="text-muted mb-0">Note moyenne</p>
                            <small class="text-muted" id="totalReviews">0 évaluations</small>
                        </div>
                    </div>

                    <!-- Rating Distribution -->
                    <div class="stats-card">
                        <h6 class="mb-3">
                            <i class="fas fa-chart-bar me-2"></i>Répartition des notes
                        </h6>
                        <div id="ratingDistribution">
                            <!-- Will be populated by JavaScript -->
                        </div>
                    </div>

                    <!-- Quick Stats -->
                    <div class="stats-card">
                        <h6 class="mb-3">
                            <i class="fas fa-info-circle me-2"></i>Statistiques rapides
                        </h6>
                        <div class="row text-center">
                            <div class="col-6">
                                <h4 class="text-success mb-1" id="recentReviews">0</h4>
                                <small class="text-muted">Ce mois</small>
                            </div>
                            <div class="col-6">
                                <h4 class="text-info mb-1" id="withComments">0</h4>
                                <small class="text-muted">Avec commentaires</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Reviews List -->
                <div class="col-lg-8">
                    <div class="stats-card">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0">
                                <i class="fas fa-comments me-2"></i>Commentaires Anonymes
                            </h5>
                            <button class="btn btn-outline-primary btn-sm" onclick="loadReviews()">
                                <i class="fas fa-sync-alt me-2"></i>Actualiser
                            </button>
                        </div>
                        
                        <div id="reviewsList">
                            <div class="text-center py-4">
                                <i class="fas fa-spinner fa-spin fa-2x text-muted mb-3"></i>
                                <p class="text-muted">Chargement des évaluations...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            loadDoctorStats();
            loadReviews();
        });

        // Load doctor statistics
        async function loadDoctorStats() {
            try {
                const response = await axios.get('/api/doctor-stats');
                if (response.data.success) {
                    displayStats(response.data.stats);
                }
            } catch (error) {
                console.error('Error loading stats:', error);
            }
        }

        // Display statistics
        function displayStats(stats) {
            // Average rating
            document.getElementById('averageRating').textContent = stats.average_rating || '0.0';
            document.getElementById('totalReviews').textContent = `${stats.total_reviews} évaluation${stats.total_reviews > 1 ? 's' : ''}`;
            
            // Stars display
            const starsElement = document.getElementById('averageStars');
            const rating = Math.round(stats.average_rating || 0);
            let starsHtml = '';
            for (let i = 1; i <= 5; i++) {
                starsHtml += i <= rating ? '★' : '☆';
            }
            starsElement.textContent = starsHtml;

            // Quick stats
            document.getElementById('recentReviews').textContent = stats.recent_reviews || 0;
            document.getElementById('withComments').textContent = stats.with_comments || 0;

            // Rating distribution
            displayRatingDistribution(stats.rating_distribution);
        }

        // Display rating distribution
        function displayRatingDistribution(distribution) {
            const container = document.getElementById('ratingDistribution');
            const total = Object.values(distribution).reduce((sum, count) => sum + count, 0);
            
            let html = '';
            for (let i = 5; i >= 1; i--) {
                const count = distribution[i] || 0;
                const percentage = total > 0 ? (count / total) * 100 : 0;
                
                html += `
                    <div class="rating-bar">
                        <span style="width: 20px;">${i}★</span>
                        <div class="rating-bar-bg">
                            <div class="rating-bar-fill" style="width: ${percentage}%"></div>
                        </div>
                        <span style="width: 30px; text-align: right;">${count}</span>
                    </div>
                `;
            }
            
            container.innerHTML = html;
        }

        // Load reviews
        async function loadReviews() {
            try {
                const response = await axios.get('/api/anonymous-reviews');
                if (response.data.success) {
                    displayReviews(response.data.reviews);
                }
            } catch (error) {
                console.error('Error loading reviews:', error);
                document.getElementById('reviewsList').innerHTML = `
                    <div class="text-center py-4">
                        <i class="fas fa-exclamation-triangle fa-2x text-warning mb-3"></i>
                        <p class="text-muted">Erreur lors du chargement des évaluations</p>
                    </div>
                `;
            }
        }

        // Display reviews
        function displayReviews(reviews) {
            const container = document.getElementById('reviewsList');
            
            if (reviews.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-4">
                        <i class="fas fa-star fa-2x text-muted mb-3"></i>
                        <h6 class="text-muted">Aucune évaluation pour le moment</h6>
                        <p class="text-muted small">Les évaluations de vos patients apparaîtront ici.</p>
                    </div>
                `;
                return;
            }

            const reviewsHtml = reviews.map(review => `
                <div class="review-item">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="d-flex align-items-center">
                            <div class="review-stars me-2">
                                ${generateStars(review.rating)}
                            </div>
                            <span class="anonymous-badge">${review.anonymous_name}</span>
                        </div>
                        <small class="text-muted">${review.time_ago}</small>
                    </div>
                    
                    ${review.comment ? `
                        <p class="mb-0 text-dark">${review.comment}</p>
                    ` : `
                        <p class="mb-0 text-muted fst-italic">Aucun commentaire</p>
                    `}
                </div>
            `).join('');

            container.innerHTML = reviewsHtml;
        }

        // Generate stars HTML
        function generateStars(rating) {
            let stars = '';
            for (let i = 1; i <= 5; i++) {
                stars += i <= rating ? '★' : '☆';
            }
            return stars;
        }
    </script>
</body>
</html>
