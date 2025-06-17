<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>React Test - MediCare+</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 2rem 0;
        }
        
        .react-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            margin: 2rem auto;
            max-width: 1400px;
        }
        
        .react-header {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 2rem;
            border-radius: 20px 20px 0 0;
            text-align: center;
        }
        
        .test-section {
            border: 2px solid #e9ecef;
            border-radius: 15px;
            padding: 2rem;
            margin: 1rem 0;
            background: #f8f9fa;
        }
        
        .react-badge {
            background: linear-gradient(45deg, #61dafb, #21a9c7);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="react-container">
            <div class="react-header">
                <h1 class="display-4 mb-3">⚛️ React.js Integration</h1>
                <p class="lead mb-0">Laravel + React Components Test</p>
                <div class="mt-3">
                    <span class="react-badge">✅ React Components Active</span>
                </div>
            </div>
            
            <div class="p-4">
                <!-- Test Instructions -->
                <div class="test-section">
                    <h3><i class="fas fa-rocket me-3"></i>React Components Test</h3>
                    <p>Cette page teste l'intégration React.js avec Laravel. Les composants React remplacent les sections Blade/JavaScript vanilla.</p>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <h5>🎯 Composants Créés :</h5>
                            <ul>
                                <li><strong>PatientDashboard.jsx</strong> - Dashboard patient complet</li>
                                <li><strong>ReviewSystem.jsx</strong> - Système d'évaluation des consultations</li>
                                <li><strong>DoctorDashboard.jsx</strong> - Dashboard docteur</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h5>🔧 Technologies :</h5>
                            <ul>
                                <li><strong>React 18</strong> - Composants modernes avec hooks</li>
                                <li><strong>Axios</strong> - Requêtes API</li>
                                <li><strong>Bootstrap 5</strong> - Styling</li>
                                <li><strong>Vite</strong> - Build tool</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Patient Dashboard Test -->
                <div class="test-section">
                    <h4><i class="fas fa-user me-2"></i>Patient Dashboard React Component</h4>
                    <p>Composant React pour le dashboard patient avec système d'évaluation intégré.</p>
                    
                    <!-- React Component Container -->
                    <div id="patient-dashboard-react" class="border rounded p-3" style="min-height: 400px; background: white;">
                        <div class="text-center py-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading React Component...</span>
                            </div>
                            <p class="mt-2 text-muted">Chargement du composant React...</p>
                        </div>
                    </div>
                </div>

                <!-- Doctor Dashboard Test -->
                <div class="test-section">
                    <h4><i class="fas fa-user-md me-2"></i>Doctor Dashboard React Component</h4>
                    <p>Composant React pour le dashboard docteur avec statistiques et actions rapides.</p>
                    
                    <!-- React Component Container -->
                    <div id="doctor-dashboard-react" class="border rounded p-3" style="min-height: 400px; background: white;">
                        <div class="text-center py-5">
                            <div class="spinner-border text-success" role="status">
                                <span class="visually-hidden">Loading React Component...</span>
                            </div>
                            <p class="mt-2 text-muted">Chargement du composant React...</p>
                        </div>
                    </div>
                </div>

                <!-- Review System Test -->
                <div class="test-section">
                    <h4><i class="fas fa-star me-2"></i>Review System React Component</h4>
                    <p>Composant React standalone pour le système d'évaluation des consultations.</p>
                    
                    <!-- React Component Container -->
                    <div id="review-system-react" class="border rounded p-3" style="min-height: 300px; background: white;">
                        <div class="text-center py-5">
                            <div class="spinner-border text-warning" role="status">
                                <span class="visually-hidden">Loading React Component...</span>
                            </div>
                            <p class="mt-2 text-muted">Chargement du composant React...</p>
                        </div>
                    </div>
                </div>

                <!-- Integration Info -->
                <div class="test-section">
                    <h4><i class="fas fa-info-circle me-2"></i>Integration Details</h4>
                    <div class="row">
                        <div class="col-md-6">
                            <h6>📁 File Structure:</h6>
                            <div style="background: #f8f9fa; padding: 1rem; border-radius: 8px; font-family: monospace; font-size: 0.9rem;">
                                resources/js/<br>
                                ├── app.js (React setup)<br>
                                ├── components/<br>
                                │   ├── PatientDashboard.jsx<br>
                                │   ├── DoctorDashboard.jsx<br>
                                │   └── ReviewSystem.jsx<br>
                                └── bootstrap.js
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6>🔗 How It Works:</h6>
                            <ol>
                                <li><strong>Vite</strong> compiles React components</li>
                                <li><strong>Laravel</strong> serves the compiled assets</li>
                                <li><strong>React</strong> mounts to specific DOM elements</li>
                                <li><strong>APIs</strong> provide data to React components</li>
                                <li><strong>State management</strong> with React hooks</li>
                            </ol>
                        </div>
                    </div>
                </div>

                <!-- Next Steps -->
                <div class="test-section">
                    <h4><i class="fas fa-arrow-right me-2"></i>Next Steps</h4>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card border-primary">
                                <div class="card-header bg-primary text-white">
                                    <h6 class="mb-0">🔨 Build Assets</h6>
                                </div>
                                <div class="card-body">
                                    <p class="small">Compile React components:</p>
                                    <code>npm run dev</code><br>
                                    <small class="text-muted">or</small><br>
                                    <code>npm run build</code>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-success">
                                <div class="card-header bg-success text-white">
                                    <h6 class="mb-0">🧪 Test Components</h6>
                                </div>
                                <div class="card-body">
                                    <p class="small">Test in real dashboards:</p>
                                    <a href="/patient/dashboard" class="btn btn-sm btn-outline-success">Patient Dashboard</a><br>
                                    <a href="/doctor/dashboard" class="btn btn-sm btn-outline-success mt-1">Doctor Dashboard</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-warning">
                                <div class="card-header bg-warning text-dark">
                                    <h6 class="mb-0">⚙️ Configure</h6>
                                </div>
                                <div class="card-body">
                                    <p class="small">Add React containers to Blade templates:</p>
                                    <code>&lt;div id="patient-dashboard-react"&gt;&lt;/div&gt;</code>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.jsx'])
</body>
</html>
