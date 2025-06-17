import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo';

// import Pusher from 'pusher-js';
// window.Pusher = Pusher;

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: import.meta.env.VITE_PUSHER_APP_KEY,
//     wsHost: import.meta.env.VITE_PUSHER_HOST ?? `ws-${import.meta.env.VITE_PUSHER_APP_CLUSTER}.pusher-channels.com`,
//     wsPort: import.meta.env.VITE_PUSHER_PORT ?? 80,
//     wssPort: import.meta.env.VITE_PUSHER_PORT ?? 443,
//     forceTLS: (import.meta.env.VITE_PUSHER_SCHEME ?? 'https') === 'https',
//     enabledTransports: ['ws', 'wss'],
// });

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
import CreateReview from './components/CreateReview';
import ReviewDoctor from './components/ReviewDoctor';

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

    // Create Review React Component
    const createReviewElement = document.getElementById('create-review-react');
    if (createReviewElement) {
        const root = createRoot(createReviewElement);
        root.render(<CreateReview />);
    }

    // Review Doctor React Component
    const reviewDoctorElement = document.getElementById('review-doctor-react');
    if (reviewDoctorElement) {
        const root = createRoot(reviewDoctorElement);
        root.render(<ReviewDoctor />);
    }
});
