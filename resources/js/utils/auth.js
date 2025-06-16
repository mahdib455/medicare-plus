/**
 * Authentication utilities
 * Reusable functions for authentication and navigation
 */

export const AuthUtils = {
    /**
     * Get current user from API
     */
    async getCurrentUser() {
        try {
            const response = await window.axios.get('/api/user');
            return response.data;
        } catch (error) {
            return null;
        }
    },

    /**
     * Check if user is authenticated
     */
    async isAuthenticated() {
        const user = await this.getCurrentUser();
        return user !== null;
    },

    /**
     * Get user role
     */
    async getUserRole() {
        const user = await this.getCurrentUser();
        return user ? user.role : null;
    },

    /**
     * Check if current user is a doctor
     */
    async isDoctor() {
        const role = await this.getUserRole();
        return role === 'doctor';
    },

    /**
     * Check if current user is a patient
     */
    async isPatient() {
        const role = await this.getUserRole();
        return role === 'patient';
    },

    /**
     * Redirect to appropriate dashboard based on user role
     */
    async redirectToDashboard() {
        const user = await this.getCurrentUser();
        if (user) {
            const dashboardUrl = user.role === 'doctor' ? '/doctor/dashboard' : '/patient/dashboard';
            window.location.href = dashboardUrl;
        } else {
            window.location.href = '/login';
        }
    },

    /**
     * Logout user
     */
    async logout() {
        try {
            await window.axios.post('/logout');
            window.location.href = '/login';
        } catch (error) {
            console.error('Logout error:', error);
            // Force redirect to login even if logout fails
            window.location.href = '/login';
        }
    },

    /**
     * Show loading state on button
     */
    setButtonLoading(button, loadingText = 'Chargement...') {
        if (button) {
            button.dataset.originalText = button.innerHTML;
            button.innerHTML = `<i class="fas fa-spinner fa-spin"></i> ${loadingText}`;
            button.disabled = true;
        }
    },

    /**
     * Reset button from loading state
     */
    resetButtonLoading(button) {
        if (button && button.dataset.originalText) {
            button.innerHTML = button.dataset.originalText;
            button.disabled = false;
            delete button.dataset.originalText;
        }
    },

    /**
     * Handle form submission with loading state
     */
    handleFormSubmission(form, loadingText = 'Envoi...') {
        const submitBtn = form.querySelector('button[type="submit"]');
        this.setButtonLoading(submitBtn, loadingText);

        // Reset loading state if form submission fails
        setTimeout(() => {
            this.resetButtonLoading(submitBtn);
        }, 10000); // 10 seconds timeout
    },

    /**
     * Validate access to protected routes
     */
    async validateRouteAccess(requiredRole = null) {
        const user = await this.getCurrentUser();
        
        if (!user) {
            window.location.href = '/login';
            return false;
        }

        if (requiredRole && user.role !== requiredRole) {
            await this.redirectToDashboard();
            return false;
        }

        return true;
    },

    /**
     * Setup automatic token refresh
     */
    setupTokenRefresh() {
        // Refresh token every 30 minutes
        setInterval(async () => {
            try {
                await window.axios.get('/api/user');
            } catch (error) {
                if (error.response && error.response.status === 401) {
                    window.location.href = '/login';
                }
            }
        }, 30 * 60 * 1000); // 30 minutes
    },

    /**
     * Get CSRF token from meta tag
     */
    getCSRFToken() {
        const token = document.querySelector('meta[name="csrf-token"]');
        return token ? token.getAttribute('content') : null;
    },

    /**
     * Setup CSRF token for axios
     */
    setupCSRF() {
        const token = this.getCSRFToken();
        if (token) {
            window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token;
        }
    }
};

export default AuthUtils;
