/**
 * First we will load all of this project's JavaScript dependencies which
 * includes React and other helpers. It's a great starting point while
 * building robust, powerful web applications using React + Laravel.
 */

import './bootstrap';
import AuthUtils from './utils/auth';
import NotificationUtils from './utils/notifications';

/**
 * Authentication and Navigation Management
 * Handles automatic redirection based on user authentication status and role
 */

// Authentication and navigation utilities
class AuthManager {
    constructor() {
        this.authUtils = AuthUtils;
        this.notifications = NotificationUtils;
        this.init();
    }

    init() {
        // Set up CSRF token for all axios requests
        this.authUtils.setupCSRF();

        // Handle authentication redirects
        this.handleAuthRedirects();

        // Set up global navigation handlers
        this.setupNavigationHandlers();

        // Setup token refresh
        this.authUtils.setupTokenRefresh();
    }

    async checkAuthStatus() {
        const user = await this.authUtils.getCurrentUser();
        return {
            authenticated: user !== null,
            user: user
        };
    }

    async handleAuthRedirects() {
        const currentPath = window.location.pathname;

        // Pages that don't require authentication
        const publicPages = ['/login', '/register'];

        // Pages that require authentication
        const protectedPages = ['/doctor/dashboard', '/patient/dashboard'];

        // Check if we're on a public page
        if (publicPages.includes(currentPath)) {
            // If user is already authenticated, redirect to appropriate dashboard
            const authStatus = await this.checkAuthStatus();
            if (authStatus.authenticated) {
                this.redirectToDashboard(authStatus.user);
            }
            return;
        }

        // Check if we're on a protected page
        if (protectedPages.some(page => currentPath.startsWith(page.split('/')[1]))) {
            const authStatus = await this.checkAuthStatus();
            if (!authStatus.authenticated) {
                this.redirectToLogin();
                return;
            }

            // Check if user is on the correct dashboard for their role
            this.validateRoleAccess(authStatus.user, currentPath);
        }

        // Handle root path
        if (currentPath === '/') {
            const authStatus = await this.checkAuthStatus();
            if (authStatus.authenticated) {
                this.redirectToDashboard(authStatus.user);
            } else {
                this.redirectToLogin();
            }
        }
    }

    validateRoleAccess(user, currentPath) {
        const isDoctorPage = currentPath.startsWith('/doctor');
        const isPatientPage = currentPath.startsWith('/patient');

        if (isDoctorPage && user.role !== 'doctor') {
            this.redirectToDashboard(user);
        } else if (isPatientPage && user.role !== 'patient') {
            this.redirectToDashboard(user);
        }
    }

    redirectToDashboard(user) {
        const dashboardUrl = user.role === 'doctor' ? '/doctor/dashboard' : '/patient/dashboard';
        if (window.location.pathname !== dashboardUrl) {
            window.location.href = dashboardUrl;
        }
    }

    redirectToLogin() {
        if (window.location.pathname !== '/login') {
            window.location.href = '/login';
        }
    }

    setupNavigationHandlers() {
        // Handle logout forms
        document.addEventListener('submit', (e) => {
            if (e.target.action && e.target.action.includes('/logout')) {
                this.authUtils.handleFormSubmission(e.target, 'Déconnexion...');
            }
        });

        // Handle login form
        const loginForm = document.querySelector('form[action*="/login"]');
        if (loginForm) {
            loginForm.addEventListener('submit', () => {
                this.authUtils.handleFormSubmission(loginForm, 'Connexion...');
            });
        }

        // Handle registration form
        const registerForm = document.querySelector('form[action*="/register"]');
        if (registerForm) {
            registerForm.addEventListener('submit', () => {
                this.authUtils.handleFormSubmission(registerForm, 'Inscription...');
            });
        }
    }

    // Utility method to show notifications
    showNotification(message, type = 'info') {
        return this.notifications.show(message, type);
    }

    // Method to handle API errors globally
    handleApiError(error) {
        if (error.response) {
            switch (error.response.status) {
                case 401:
                    this.notifications.warning('Session expirée. Veuillez vous reconnecter.');
                    this.redirectToLogin();
                    break;
                case 403:
                    this.notifications.error('Accès non autorisé.');
                    break;
                case 422:
                    const errors = error.response.data.errors;
                    if (errors) {
                        Object.values(errors).flat().forEach(err => {
                            this.notifications.error(err);
                        });
                    } else if (error.response.data.error) {
                        this.notifications.error(error.response.data.error);
                    }
                    break;
                case 500:
                    this.notifications.error('Erreur serveur. Veuillez réessayer.');
                    break;
                default:
                    this.notifications.error('Une erreur est survenue.');
            }
        } else {
            this.notifications.error('Erreur de connexion.');
        }
    }
}

// Global utility functions
function setupGlobalShortcuts() {
    document.addEventListener('keydown', (e) => {
        // Ctrl/Cmd + L for logout
        if ((e.ctrlKey || e.metaKey) && e.key === 'l' && e.shiftKey) {
            e.preventDefault();
            if (window.authManager) {
                window.authManager.authUtils.logout();
            }
        }

        // Escape to close modals and notifications
        if (e.key === 'Escape') {
            // Close notifications
            NotificationUtils.clearAll();

            // Close modals
            const openModals = document.querySelectorAll('.modal.show');
            openModals.forEach(modal => {
                const bsModal = bootstrap.Modal.getInstance(modal);
                if (bsModal) {
                    bsModal.hide();
                }
            });
        }
    });
}

function setupPageVisibilityHandling() {
    document.addEventListener('visibilitychange', () => {
        if (!document.hidden && window.authManager) {
            // Page became visible, check auth status
            window.authManager.checkAuthStatus().then(status => {
                if (!status.authenticated &&
                    !window.location.pathname.includes('/login') &&
                    !window.location.pathname.includes('/register')) {
                    NotificationUtils.warning('Session expirée. Redirection vers la page de connexion...');
                    setTimeout(() => {
                        window.location.href = '/login';
                    }, 2000);
                }
            });
        }
    });
}

// Initialize the auth manager when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.authManager = new AuthManager();

    // Make utilities globally available
    window.AuthUtils = AuthUtils;
    window.NotificationUtils = NotificationUtils;

    // Setup global shortcuts
    setupGlobalShortcuts();

    // Setup page visibility handling
    setupPageVisibilityHandling();
});

// Set up global axios interceptors for error handling
window.axios.interceptors.response.use(
    response => response,
    error => {
        if (window.authManager) {
            window.authManager.handleApiError(error);
        }
        return Promise.reject(error);
    }
);

/**
 * Next, we will create a fresh React component instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import './components/Example';

// React Components Import
import React from 'react';
import { createRoot } from 'react-dom/client';

// Import React components
import PatientDashboard from './components/PatientDashboard';
import DoctorDashboard from './components/DoctorDashboard';
import ReviewSystem from './components/ReviewSystem';

// Initialize React components when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Patient Dashboard React Component
    const patientDashboardElement = document.getElementById('patient-dashboard-react');
    if (patientDashboardElement) {
        const root = createRoot(patientDashboardElement);
        root.render(<PatientDashboard />);
    }

    // Doctor Dashboard React Component
    const doctorDashboardElement = document.getElementById('doctor-dashboard-react');
    if (doctorDashboardElement) {
        const root = createRoot(doctorDashboardElement);
        root.render(<DoctorDashboard />);
    }

    // Review System React Component
    const reviewSystemElement = document.getElementById('review-system-react');
    if (reviewSystemElement) {
        const root = createRoot(reviewSystemElement);
        root.render(<ReviewSystem />);
    }
});
