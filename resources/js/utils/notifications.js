/**
 * Notification utilities
 * Functions for displaying user notifications and alerts
 */

export const NotificationUtils = {
    /**
     * Show a notification toast
     */
    show(message, type = 'info', duration = 5000) {
        const notification = this.createNotification(message, type);
        document.body.appendChild(notification);

        // Trigger animation
        setTimeout(() => {
            notification.classList.add('show');
        }, 100);

        // Auto remove
        setTimeout(() => {
            this.remove(notification);
        }, duration);

        return notification;
    },

    /**
     * Create notification element
     */
    createNotification(message, type) {
        const notification = document.createElement('div');
        notification.className = `alert alert-${type} alert-dismissible fade position-fixed`;
        notification.style.cssText = `
            top: 20px; 
            right: 20px; 
            z-index: 9999; 
            min-width: 300px;
            max-width: 400px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        `;

        const icon = this.getIcon(type);
        notification.innerHTML = `
            <div class="d-flex align-items-center">
                <i class="${icon} me-2"></i>
                <div class="flex-grow-1">${message}</div>
                <button type="button" class="btn-close" onclick="this.parentElement.parentElement.remove()"></button>
            </div>
        `;

        return notification;
    },

    /**
     * Get icon for notification type
     */
    getIcon(type) {
        const icons = {
            'success': 'fas fa-check-circle',
            'danger': 'fas fa-exclamation-circle',
            'warning': 'fas fa-exclamation-triangle',
            'info': 'fas fa-info-circle',
            'primary': 'fas fa-info-circle',
            'secondary': 'fas fa-info-circle'
        };
        return icons[type] || icons['info'];
    },

    /**
     * Remove notification
     */
    remove(notification) {
        if (notification && notification.parentNode) {
            notification.classList.remove('show');
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.remove();
                }
            }, 300);
        }
    },

    /**
     * Show success notification
     */
    success(message, duration = 5000) {
        return this.show(message, 'success', duration);
    },

    /**
     * Show error notification
     */
    error(message, duration = 7000) {
        return this.show(message, 'danger', duration);
    },

    /**
     * Show warning notification
     */
    warning(message, duration = 6000) {
        return this.show(message, 'warning', duration);
    },

    /**
     * Show info notification
     */
    info(message, duration = 5000) {
        return this.show(message, 'info', duration);
    },

    /**
     * Show loading notification
     */
    loading(message = 'Chargement...') {
        const notification = this.createLoadingNotification(message);
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.classList.add('show');
        }, 100);

        return notification;
    },

    /**
     * Create loading notification
     */
    createLoadingNotification(message) {
        const notification = document.createElement('div');
        notification.className = 'alert alert-info fade position-fixed';
        notification.style.cssText = `
            top: 20px; 
            right: 20px; 
            z-index: 9999; 
            min-width: 300px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        `;

        notification.innerHTML = `
            <div class="d-flex align-items-center">
                <i class="fas fa-spinner fa-spin me-2"></i>
                <div class="flex-grow-1">${message}</div>
            </div>
        `;

        return notification;
    },

    /**
     * Show confirmation dialog
     */
    confirm(message, title = 'Confirmation') {
        return new Promise((resolve) => {
            const modal = this.createConfirmModal(message, title, resolve);
            document.body.appendChild(modal);
            
            // Show modal using Bootstrap
            const bsModal = new bootstrap.Modal(modal);
            bsModal.show();
        });
    },

    /**
     * Create confirmation modal
     */
    createConfirmModal(message, title, resolve) {
        const modalId = 'confirmModal_' + Date.now();
        const modal = document.createElement('div');
        modal.className = 'modal fade';
        modal.id = modalId;
        modal.innerHTML = `
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">${title}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>${message}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="button" class="btn btn-primary confirm-btn">Confirmer</button>
                    </div>
                </div>
            </div>
        `;

        // Handle confirm button
        modal.querySelector('.confirm-btn').addEventListener('click', () => {
            resolve(true);
            bootstrap.Modal.getInstance(modal).hide();
        });

        // Handle modal close
        modal.addEventListener('hidden.bs.modal', () => {
            resolve(false);
            modal.remove();
        });

        return modal;
    },

    /**
     * Clear all notifications
     */
    clearAll() {
        const notifications = document.querySelectorAll('.alert.position-fixed');
        notifications.forEach(notification => {
            this.remove(notification);
        });
    }
};

export default NotificationUtils;
