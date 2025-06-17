<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - MediCare+</title>
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
            --light-bg: #f8fafc;
            --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 2rem 0;
        }

        .register-container {
            background: white;
            border-radius: 20px;
            box-shadow: var(--card-shadow);
            overflow: hidden;
            max-width: 800px;
            margin: 0 auto;
        }

        .register-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, #3b82f6 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }

        .form-section {
            padding: 2rem;
        }

        .role-selector {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .role-option {
            flex: 1;
            padding: 1.5rem;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: white;
        }

        .role-option:hover {
            border-color: var(--primary-color);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .role-option.active {
            border-color: var(--primary-color);
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        }

        .role-option .icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: var(--primary-color);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-weight: 500;
            margin-bottom: 0.5rem;
            color: #374151;
        }

        .form-control, .form-select {
            border-radius: 12px;
            border: 2px solid #e2e8f0;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.25);
        }

        .input-group {
            position: relative;
        }

        .input-group-text {
            background: var(--light-bg);
            border: 2px solid #e2e8f0;
            border-right: none;
            border-radius: 12px 0 0 12px;
        }

        .input-group .form-control {
            border-left: none;
            border-radius: 0 12px 12px 0;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), #3b82f6);
            border: none;
            border-radius: 12px;
            padding: 0.75rem 2rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(37, 99, 235, 0.3);
        }

        .btn-secondary {
            background: linear-gradient(135deg, var(--secondary-color), #6b7280);
            border: none;
            border-radius: 12px;
            padding: 0.75rem 2rem;
            font-weight: 500;
        }

        .dynamic-fields {
            background: #f8fafc;
            border-radius: 12px;
            padding: 1.5rem;
            margin-top: 1rem;
            border: 2px dashed #e2e8f0;
            transition: all 0.3s ease;
        }

        .dynamic-fields.loaded {
            border-style: solid;
            border-color: var(--success-color);
            background: #f0fdf4;
        }

        .loading-spinner {
            display: none;
            text-align: center;
            padding: 2rem;
        }

        .alert {
            border-radius: 12px;
            border: none;
        }

        .required {
            color: var(--danger-color);
        }

        .logo-container {
            text-align: center;
        }

        .platform-logo {
            max-height: 80px;
            max-width: 200px;
            height: auto;
            width: auto;
            filter: brightness(1.1) contrast(1.1);
            transition: all 0.3s ease;
        }

        .platform-logo:hover {
            transform: scale(1.05);
            filter: brightness(1.2) contrast(1.2);
        }

        @media (max-width: 768px) {
            .role-selector {
                flex-direction: column;
            }

            .register-container {
                margin: 0 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="register-container">
            <!-- Header -->
            <div class="register-header">
                <div class="mb-4">
                    <x-logo size="medium" class="justify-content-center" :showText="true" />
                </div>
                <h1 class="mb-2">
                    <i class="fas fa-user-plus me-3"></i>
                    Créer un Compte
                </h1>
                <p class="mb-0 opacity-75">Rejoignez MediCare+ en tant que docteur ou patient</p>
            </div>

            <!-- Form Section -->
            <div class="form-section">
                <!-- Messages -->
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                @if($errors->any())
                <div class="alert alert-danger">
                    <h6><i class="fas fa-exclamation-triangle me-2"></i>Erreurs de validation :</h6>
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form action="{{ route('register') }}" method="POST" id="registerForm">
                    @csrf

                    <!-- Role Selection -->
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-user-tag me-2"></i>
                            Je souhaite m'inscrire en tant que <span class="required">*</span>
                        </label>
                        <div class="role-selector">
                            <div class="role-option" data-role="doctor">
                                <div class="icon">
                                    <i class="fas fa-user-md"></i>
                                </div>
                                <h5>Docteur</h5>
                                <p class="text-muted mb-0">Professionnel de santé</p>
                            </div>
                            <div class="role-option" data-role="patient">
                                <div class="icon">
                                    <i class="fas fa-user-injured"></i>
                                </div>
                                <h5>Patient</h5>
                                <p class="text-muted mb-0">Recherche de soins médicaux</p>
                            </div>
                        </div>
                        <input type="hidden" name="role" id="selectedRole" value="{{ old('role') }}">
                    </div>

                    <!-- Basic Information -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Prénom <span class="required">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-user"></i>
                                    </span>
                                    <input type="text" name="first_name" class="form-control"
                                           value="{{ old('first_name') }}" placeholder="Votre prénom" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Nom <span class="required">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-user"></i>
                                    </span>
                                    <input type="text" name="last_name" class="form-control"
                                           value="{{ old('last_name') }}" placeholder="Votre nom" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Email <span class="required">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-envelope"></i>
                                    </span>
                                    <input type="email" name="email" class="form-control"
                                           value="{{ old('email') }}" placeholder="votre@email.com" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Téléphone</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-phone"></i>
                                    </span>
                                    <input type="tel" name="phone" class="form-control"
                                           value="{{ old('phone') }}" placeholder="Numéro de téléphone">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Mot de passe <span class="required">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    <input type="password" name="password" class="form-control"
                                           placeholder="Mot de passe sécurisé" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Confirmer le mot de passe <span class="required">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    <input type="password" name="password_confirmation" class="form-control"
                                           placeholder="Confirmer le mot de passe" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Adresse</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-map-marker-alt"></i>
                            </span>
                            <input type="text" name="address" class="form-control"
                                   value="{{ old('address') }}" placeholder="Adresse complète">
                        </div>
                    </div>

                    <!-- Dynamic Role-Specific Fields -->
                    <div id="dynamicFields" class="dynamic-fields" style="display: none;">
                        <div class="loading-spinner">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Chargement...</span>
                            </div>
                            <p class="mt-2 text-muted">Chargement des champs spécifiques...</p>
                        </div>
                        <div id="roleSpecificFields"></div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="d-flex gap-3 mt-4">
                        <button type="submit" class="btn btn-primary flex-fill" id="submitBtn" disabled>
                            <i class="fas fa-user-plus me-2"></i>
                            Créer mon compte
                        </button>
                        <a href="{{ route('login') }}" class="btn btn-secondary">
                            <i class="fas fa-sign-in-alt me-2"></i>
                            Se connecter
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const roleOptions = document.querySelectorAll('.role-option');
            const selectedRoleInput = document.getElementById('selectedRole');
            const dynamicFields = document.getElementById('dynamicFields');
            const roleSpecificFields = document.getElementById('roleSpecificFields');
            const loadingSpinner = document.querySelector('.loading-spinner');
            const submitBtn = document.getElementById('submitBtn');

            // Role selection functionality
            roleOptions.forEach(option => {
                option.addEventListener('click', function() {
                    // Remove active class from all options
                    roleOptions.forEach(opt => opt.classList.remove('active'));

                    // Add active class to clicked option
                    this.classList.add('active');

                    // Set hidden input value
                    const role = this.dataset.role;
                    selectedRoleInput.value = role;

                    // Load role-specific fields
                    loadRoleFields(role);

                    // Enable submit button
                    submitBtn.disabled = false;
                });
            });

            // Set initial role if there's an old value
            const oldRole = '{{ old("role") }}';
            if (oldRole) {
                const oldRoleOption = document.querySelector(`[data-role="${oldRole}"]`);
                if (oldRoleOption) {
                    oldRoleOption.classList.add('active');
                    loadRoleFields(oldRole);
                    submitBtn.disabled = false;
                }
            }

            // Function to load role-specific fields
            function loadRoleFields(role) {
                if (!role) return;

                // Show dynamic fields container
                dynamicFields.style.display = 'block';
                dynamicFields.classList.remove('loaded');

                // Show loading spinner
                loadingSpinner.style.display = 'block';
                roleSpecificFields.innerHTML = '';

                // Make AJAX request to get role-specific fields
                axios.get('/register/role-fields', {
                    params: { role: role }
                })
                .then(function(response) {
                    if (response.data.success) {
                        renderFields(response.data.fields);
                        dynamicFields.classList.add('loaded');
                    } else {
                        showError('Erreur lors du chargement des champs spécifiques.');
                    }
                })
                .catch(function(error) {
                    console.error('Error loading role fields:', error);
                    showError('Erreur de connexion. Veuillez réessayer.');
                })
                .finally(function() {
                    loadingSpinner.style.display = 'none';
                });
            }

            // Function to render dynamic fields
            function renderFields(fields) {
                let html = '<h6 class="mb-3"><i class="fas fa-cog me-2"></i>Informations spécifiques</h6>';

                fields.forEach(field => {
                    html += '<div class="form-group">';
                    html += `<label class="form-label">`;
                    html += `<i class="${field.icon} me-2"></i>`;
                    html += field.label;
                    if (field.required) {
                        html += ' <span class="required">*</span>';
                    }
                    html += '</label>';

                    html += '<div class="input-group">';
                    html += `<span class="input-group-text"><i class="${field.icon}"></i></span>`;

                    if (field.type === 'textarea') {
                        html += `<textarea name="${field.name}" class="form-control" placeholder="${field.placeholder}" ${field.required ? 'required' : ''} rows="${field.rows || 3}"></textarea>`;
                    } else if (field.type === 'select') {
                        html += `<select name="${field.name}" class="form-control" ${field.required ? 'required' : ''}>`;
                        html += `<option value="">${field.placeholder}</option>`;
                        field.options.forEach(option => {
                            html += `<option value="${option.value}">${option.label}</option>`;
                        });
                        html += '</select>';
                    } else if (field.type === 'number') {
                        html += `<input type="number" name="${field.name}" class="form-control" placeholder="${field.placeholder}" ${field.required ? 'required' : ''} min="${field.min || 0}" max="${field.max || 100}">`;
                    } else {
                        html += `<input type="${field.type}" name="${field.name}" class="form-control" placeholder="${field.placeholder}" ${field.required ? 'required' : ''}">`;
                    }

                    html += '</div>';
                    html += '</div>';
                });

                roleSpecificFields.innerHTML = html;
            }

            // Function to show error message
            function showError(message) {
                roleSpecificFields.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>${message}
                    </div>
                `;
            }

            // Form validation
            document.getElementById('registerForm').addEventListener('submit', function(e) {
                if (!selectedRoleInput.value) {
                    e.preventDefault();
                    alert('Veuillez sélectionner un rôle (Docteur ou Patient).');
                    return false;
                }
            });
        });
    </script>
</body>
</html>
