<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>AI Symptom Analyzer - MediCare+</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #2563eb;
            --secondary-color: #64748b;
            --success-color: #059669;
            --warning-color: #d97706;
            --danger-color: #dc2626;
            --info-color: #0891b2;
            --light-bg: #f8fafc;
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
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .header-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, #3b82f6 100%);
            color: white;
            padding: 2rem;
            border-radius: 20px 20px 0 0;
        }

        .analyzer-card {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }

        .form-control {
            border-radius: 8px;
            border: 2px solid #e2e8f0;
            padding: 0.75rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), #3b82f6);
            border: none;
            border-radius: 8px;
            padding: 0.75rem 2rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgba(37, 99, 235, 0.3);
        }

        .result-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            border-left: 4px solid var(--primary-color);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .urgency-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .urgency-low { background: #dcfce7; color: #166534; }
        .urgency-medium { background: #fef3c7; color: #92400e; }
        .urgency-high { background: #fee2e2; color: #991b1b; }
        .urgency-critical { background: #fecaca; color: #7f1d1d; }

        .category-tag {
            background: #e0e7ff;
            color: #3730a3;
            padding: 0.25rem 0.5rem;
            border-radius: 6px;
            font-size: 0.75rem;
            margin-right: 0.5rem;
            margin-bottom: 0.25rem;
            display: inline-block;
        }

        .loading {
            display: none;
        }

        .loading.show {
            display: block;
        }

        .result-section {
            display: none;
        }

        .result-section.show {
            display: block;
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
                        <h1 class="mb-2">
                            <i class="fas fa-brain me-3"></i>
                            AI Symptom Analyzer
                        </h1>
                        <p class="mb-0 opacity-75">
                            <i class="fas fa-stethoscope me-2"></i>Describe your symptoms and get AI-powered health insights
                        </p>
                    </div>
                    <div class="col-md-4 text-end">
                        <a href="{{ route('patient.dashboard') }}" class="btn btn-light">
                            <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid py-4">
            <!-- Symptom Input Form -->
            <div class="analyzer-card">
                <h4 class="mb-3">
                    <i class="fas fa-edit me-2 text-primary"></i>
                    Describe Your Symptoms
                </h4>
                
                <form id="symptomForm">
                    <div class="mb-3">
                        <label for="symptomText" class="form-label">
                            <i class="fas fa-comment-medical me-2"></i>Symptom Description
                        </label>
                        <textarea 
                            class="form-control" 
                            id="symptomText" 
                            name="symptom_text" 
                            rows="4" 
                            placeholder="Please describe your symptoms in detail. For example: 'I have been experiencing severe headaches for the past 3 days, along with nausea and sensitivity to light...'"
                            required
                            minlength="10"
                            maxlength="1000"></textarea>
                        <div class="form-text">
                            <i class="fas fa-info-circle me-1"></i>
                            Be as detailed as possible. Include duration, severity, and any related symptoms.
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary" id="analyzeBtn">
                        <i class="fas fa-search me-2"></i>Analyze Symptoms
                    </button>
                </form>

                <!-- Loading State -->
                <div class="loading text-center py-4" id="loadingState">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Analyzing...</span>
                    </div>
                    <p class="mt-2 text-muted">AI is analyzing your symptoms...</p>
                </div>
            </div>

            <!-- Results Section -->
            <div class="result-section" id="resultSection">
                <div class="analyzer-card">
                    <h4 class="mb-3">
                        <i class="fas fa-chart-line me-2 text-success"></i>
                        Analysis Results
                    </h4>
                    
                    <div id="analysisResults">
                        <!-- Results will be populated here -->
                    </div>
                </div>
            </div>

            <!-- Error Section -->
            <div class="alert alert-danger" id="errorSection" style="display: none;">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <span id="errorMessage"></span>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    
    <script>
        document.getElementById('symptomForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const symptomText = document.getElementById('symptomText').value;
            const loadingState = document.getElementById('loadingState');
            const resultSection = document.getElementById('resultSection');
            const errorSection = document.getElementById('errorSection');
            const analyzeBtn = document.getElementById('analyzeBtn');
            
            // Show loading state
            loadingState.classList.add('show');
            resultSection.classList.remove('show');
            errorSection.style.display = 'none';
            analyzeBtn.disabled = true;
            
            try {
                const response = await axios.post('/ai/analyze-symptoms', {
                    symptom_text: symptomText
                }, {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                });
                
                if (response.data.success) {
                    displayResults(response.data);
                    resultSection.classList.add('show');
                } else {
                    throw new Error(response.data.error || 'Analysis failed');
                }
                
            } catch (error) {
                console.error('Error:', error);
                const errorMsg = error.response?.data?.error || error.message || 'Failed to analyze symptoms';
                document.getElementById('errorMessage').textContent = errorMsg;
                errorSection.style.display = 'block';
            } finally {
                loadingState.classList.remove('show');
                analyzeBtn.disabled = false;
            }
        });
        
        function displayResults(data) {
            const analysis = data.analysis;
            const resultsContainer = document.getElementById('analysisResults');
            
            const urgencyClass = getUrgencyClass(analysis.urgency_level);
            const severityClass = getSeverityClass(analysis.severity);
            
            resultsContainer.innerHTML = `
                <div class="row">
                    <div class="col-md-6">
                        <div class="result-card">
                            <h6 class="text-primary mb-2">
                                <i class="fas fa-diagnoses me-2"></i>Diagnosis
                            </h6>
                            <p class="mb-0">${analysis.diagnosis}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="result-card">
                            <h6 class="text-info mb-2">
                                <i class="fas fa-user-md me-2"></i>Recommended Doctor
                            </h6>
                            <p class="mb-0">${analysis.recommended_doctor}</p>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-4">
                        <div class="result-card">
                            <h6 class="text-warning mb-2">
                                <i class="fas fa-exclamation-triangle me-2"></i>Urgency Level
                            </h6>
                            <span class="urgency-badge ${urgencyClass}">
                                ${analysis.urgency_level}/10 - ${analysis.urgency_text}
                            </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="result-card">
                            <h6 class="text-danger mb-2">
                                <i class="fas fa-thermometer-half me-2"></i>Severity
                            </h6>
                            <span class="urgency-badge ${severityClass}">
                                ${analysis.severity}/10 - ${analysis.severity_text}
                            </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="result-card">
                            <h6 class="text-success mb-2">
                                <i class="fas fa-percentage me-2"></i>Confidence
                            </h6>
                            <span class="urgency-badge urgency-medium">
                                ${analysis.confidence}%
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="result-card">
                    <h6 class="text-secondary mb-2">
                        <i class="fas fa-tags me-2"></i>Categories
                    </h6>
                    <div>
                        ${Array.isArray(analysis.categories) ? analysis.categories.map(cat => `<span class="category-tag">${cat}</span>`).join('') : `<span class="category-tag">${analysis.categories || 'General'}</span>`}
                    </div>
                </div>
                
                <div class="result-card">
                    <h6 class="text-primary mb-2">
                        <i class="fas fa-lightbulb me-2"></i>Recommendations
                    </h6>
                    <ul class="mb-0">
                        ${Array.isArray(analysis.recommendations) ? analysis.recommendations.map(rec => `<li>${rec}</li>`).join('') : `<li>${analysis.recommendations || 'Consult healthcare provider'}</li>`}
                    </ul>
                </div>
                
                ${analysis.warning_signs && (Array.isArray(analysis.warning_signs) ? analysis.warning_signs.length > 0 : analysis.warning_signs) ? `
                <div class="result-card border-danger">
                    <h6 class="text-danger mb-2">
                        <i class="fas fa-exclamation-circle me-2"></i>Warning Signs to Watch For
                    </h6>
                    <ul class="mb-0 text-danger">
                        ${Array.isArray(analysis.warning_signs) ? analysis.warning_signs.map(sign => `<li>${sign}</li>`).join('') : `<li>${analysis.warning_signs}</li>`}
                    </ul>
                </div>
                ` : ''}
                
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Disclaimer:</strong> This AI analysis is for informational purposes only and should not replace professional medical advice. Please consult with a healthcare provider for proper diagnosis and treatment.
                </div>
            `;
        }
        
        function getUrgencyClass(level) {
            if (level >= 8) return 'urgency-critical';
            if (level >= 6) return 'urgency-high';
            if (level >= 4) return 'urgency-medium';
            return 'urgency-low';
        }
        
        function getSeverityClass(level) {
            if (level >= 8) return 'urgency-critical';
            if (level >= 6) return 'urgency-high';
            if (level >= 4) return 'urgency-medium';
            return 'urgency-low';
        }
    </script>
</body>
</html>
