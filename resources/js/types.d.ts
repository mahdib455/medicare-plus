// Global type declarations for the application

declare global {
    interface Window {
        authManager: AuthManager;
        AuthUtils: any;
        NotificationUtils: any;
        axios: any;
        bootstrap: any;
    }
}

// AuthManager class interface
interface AuthManager {
    init(): void;
    setupCSRF(): void;
    checkAuthStatus(): Promise<AuthStatus>;
    handleAuthRedirects(): Promise<void>;
    validateRoleAccess(user: User, currentPath: string): void;
    redirectToDashboard(user: User): void;
    redirectToLogin(): void;
    setupNavigationHandlers(): void;
    showNotification(message: string, type?: string): void;
    handleApiError(error: any): void;
}

// User interface
interface User {
    id: number;
    first_name: string;
    last_name: string;
    email: string;
    phone: string;
    address: string;
    role: 'doctor' | 'patient';
    full_name: string;
}

// Auth status interface
interface AuthStatus {
    authenticated: boolean;
    user: User | null;
}

export {};
