<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - MediCare+</title>
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
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 0;
        }

        .login-container {
            background: white;
            border-radius: 20px;
            box-shadow: var(--card-shadow);
            overflow: hidden;
            max-width: 500px;
            width: 100%;
            margin: 0 1rem;
        }

        .login-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, #3b82f6 100%);
            color: white;
            padding: 2.5rem 2rem;
            text-align: center;
            position: relative;
        }

        .login-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="0.5"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
            opacity: 0.3;
        }

        .login-header h1 {
            position: relative;
            z-index: 1;
            margin-bottom: 0.5rem;
            font-weight: 700;
        }

        .login-header p {
            position: relative;
            z-index: 1;
            margin: 0;
            opacity: 0.9;
        }

        .logo-container {
            position: relative;
            z-index: 1;
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

        .form-section {
            padding: 2.5rem 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-weight: 500;
            margin-bottom: 0.5rem;
            color: #374151;
            display: flex;
            align-items: center;
        }

        .form-control {
            border-radius: 12px;
            border: 2px solid #e2e8f0;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
            font-size: 1rem;
        }

        .form-control:focus {
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
            color: var(--secondary-color);
        }

        .input-group .form-control {
            border-left: none;
            border-radius: 0 12px 12px 0;
        }

        .input-group:focus-within .input-group-text {
            border-color: var(--primary-color);
            color: var(--primary-color);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), #3b82f6);
            border: none;
            border-radius: 12px;
            padding: 0.875rem 2rem;
            font-weight: 500;
            font-size: 1rem;
            transition: all 0.3s ease;
            width: 100%;
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
            color: white;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(100, 116, 139, 0.3);
            color: white;
        }

        .alert {
            border-radius: 12px;
            border: none;
            margin-bottom: 1.5rem;
        }

        .alert-danger {
            background: linear-gradient(135deg, #fef2f2, #fee2e2);
            color: var(--danger-color);
            border-left: 4px solid var(--danger-color);
        }

        .alert-success {
            background: linear-gradient(135deg, #f0fdf4, #dcfce7);
            color: var(--success-color);
            border-left: 4px solid var(--success-color);
        }

        .form-check {
            margin: 1.5rem 0;
        }

        .form-check-input {
            border-radius: 6px;
            border: 2px solid #e2e8f0;
        }

        .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .form-check-label {
            color: #6b7280;
            font-weight: 400;
        }

        .register-link {
            text-align: center;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid #e2e8f0;
        }

        .register-link a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .register-link a:hover {
            color: #1d4ed8;
            text-decoration: underline;
        }

        .quick-login {
            background: #f8fafc;
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            border: 2px dashed #e2e8f0;
        }

        .quick-login h6 {
            color: #6b7280;
            margin-bottom: 0.75rem;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .quick-login-btn {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 0.5rem 1rem;
            margin: 0.25rem;
            font-size: 0.875rem;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .quick-login-btn:hover {
            border-color: var(--primary-color);
            color: var(--primary-color);
            transform: translateY(-1px);
        }

        @media (max-width: 768px) {
            .login-container {
                margin: 0 1rem;
            }

            .form-section {
                padding: 2rem 1.5rem;
            }

            .login-header {
                padding: 2rem 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Header -->
        <div class="login-header">
            <div class="mb-4">
                <x-logo size="medium" class="justify-content-center" :showText="true" />
            </div>
            <h1>
                <i class="fas fa-sign-in-alt me-3"></i>
                Connexion
            </h1>
            <p>Accédez à votre compte MediCare+</p>
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
                <h6><i class="fas fa-exclamation-triangle me-2"></i>Erreurs de connexion :</h6>
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Quick Login Options -->
            <div class="quick-login">
                <h6><i class="fas fa-bolt me-2"></i>Connexion Rapide</h6>
                <div class="d-flex flex-wrap">
                    <button type="button" class="quick-login-btn" onclick="fillLogin('admin@medical.com', 'admin123')">
                        <i class="fas fa-user-shield me-1"></i>Admin
                    </button>
                    <button type="button" class="quick-login-btn" onclick="fillLogin('doctor@test.com', 'password123')">
                        <i class="fas fa-user-md me-1"></i>Docteur
                    </button>
                    <button type="button" class="quick-login-btn" onclick="fillLogin('patient@test.com', 'password123')">
                        <i class="fas fa-user-injured me-1"></i>Patient
                    </button>
                </div>
            </div>

            <form action="{{ route('login') }}" method="POST" id="loginForm">
                @csrf

                <!-- Email Field -->
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-envelope me-2"></i>
                        Adresse Email
                    </label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-envelope"></i>
                        </span>
                        <input type="email" name="email" class="form-control"
                               value="{{ old('email') }}" placeholder="votre@email.com" required>
                    </div>
                </div>

                <!-- Password Field -->
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-lock me-2"></i>
                        Mot de Passe
                    </label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-lock"></i>
                        </span>
                        <input type="password" name="password" class="form-control"
                               placeholder="Votre mot de passe" required>
                        <button type="button" class="btn btn-outline-secondary" onclick="togglePassword()">
                            <i class="fas fa-eye" id="passwordToggleIcon"></i>
                        </button>
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label" for="remember">
                        <i class="fas fa-clock me-2"></i>
                        Se souvenir de moi
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-sign-in-alt me-2"></i>
                    Se Connecter
                </button>
            </form>

            <!-- Register Link -->
            <div class="register-link">
                <p class="text-muted mb-2">Pas encore de compte ?</p>
                <a href="{{ route('register') }}" class="btn btn-secondary">
                    <i class="fas fa-user-plus me-2"></i>
                    Créer un Compte
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Quick login functionality
        function fillLogin(email, password) {
            document.querySelector('input[name="email"]').value = email;
            document.querySelector('input[name="password"]').value = password;

            // Add visual feedback
            const emailInput = document.querySelector('input[name="email"]');
            const passwordInput = document.querySelector('input[name="password"]');

            emailInput.style.borderColor = '#059669';
            passwordInput.style.borderColor = '#059669';

            setTimeout(() => {
                emailInput.style.borderColor = '';
                passwordInput.style.borderColor = '';
            }, 2000);
        }

        // Password toggle functionality
        function togglePassword() {
            const passwordInput = document.querySelector('input[name="password"]');
            const toggleIcon = document.getElementById('passwordToggleIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        // Form validation and enhancement
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('loginForm');
            const emailInput = document.querySelector('input[name="email"]');
            const passwordInput = document.querySelector('input[name="password"]');

            // Add real-time validation
            emailInput.addEventListener('blur', function() {
                if (this.value && !this.value.includes('@')) {
                    this.style.borderColor = '#dc2626';
                } else if (this.value) {
                    this.style.borderColor = '#059669';
                }
            });

            passwordInput.addEventListener('input', function() {
                if (this.value.length >= 6) {
                    this.style.borderColor = '#059669';
                } else if (this.value.length > 0) {
                    this.style.borderColor = '#d97706';
                }
            });

            // Form submission enhancement
            form.addEventListener('submit', function(e) {
                const submitBtn = form.querySelector('button[type="submit"]');
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Connexion...';
                submitBtn.disabled = true;
            });
        });
    </script>
</body>
</html>
