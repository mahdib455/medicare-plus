# Système d'Authentification et Navigation - Frontend

Ce système gère automatiquement l'authentification et la navigation dans l'application médicale Laravel.

## 🚀 Fonctionnalités

### ✅ Gestion automatique de l'authentification
- **Redirection automatique** vers login si non authentifié
- **Redirection role-based** vers les dashboards appropriés
- **Validation des accès** selon les rôles (docteur/patient)
- **Gestion des sessions expirées**

### ✅ Navigation intelligente
- **Détection automatique** de l'état d'authentification
- **Redirection appropriée** selon le rôle utilisateur
- **Protection des routes** sensibles
- **Gestion des erreurs** d'accès

### ✅ Interface utilisateur améliorée
- **Notifications toast** pour les messages
- **États de chargement** sur les boutons
- **Gestion des erreurs API** avec messages explicites
- **Raccourcis clavier** pour actions courantes

## 📁 Structure des fichiers

```
resources/js/
├── app.js                 # Point d'entrée principal
├── utils/
│   ├── auth.js           # Utilitaires d'authentification
│   └── notifications.js  # Système de notifications
├── types.d.ts            # Déclarations TypeScript
└── README.md             # Cette documentation
```

## 🔧 Configuration

### 1. Compilation des assets
```bash
# Développement
npm run dev

# Production
npm run build

# Watch mode
npm run watch
```

### 2. Inclusion dans les vues Blade
```blade
@vite(['resources/js/app.js', 'resources/sass/app.scss'])
```

### 3. Meta tags requis
```blade
<meta name="csrf-token" content="{{ csrf_token() }}">
```

## 🎯 Utilisation

### AuthManager (Automatique)
Le système s'initialise automatiquement au chargement de la page :

```javascript
// Accessible globalement
window.authManager.checkAuthStatus()
window.authManager.redirectToDashboard(user)
window.authManager.showNotification(message, type)
```

### AuthUtils (Utilitaires)
```javascript
// Vérifier l'authentification
const isAuth = await AuthUtils.isAuthenticated();

// Obtenir l'utilisateur actuel
const user = await AuthUtils.getCurrentUser();

// Vérifier le rôle
const isDoctor = await AuthUtils.isDoctor();
const isPatient = await AuthUtils.isPatient();

// Déconnexion
await AuthUtils.logout();
```

### NotificationUtils (Notifications)
```javascript
// Types de notifications
NotificationUtils.success('Opération réussie !');
NotificationUtils.error('Une erreur est survenue');
NotificationUtils.warning('Attention !');
NotificationUtils.info('Information');

// Notification de chargement
const loading = NotificationUtils.loading('Chargement...');
// ... opération async ...
NotificationUtils.remove(loading);

// Confirmation
const confirmed = await NotificationUtils.confirm('Êtes-vous sûr ?');
if (confirmed) {
    // Action confirmée
}
```

## ⌨️ Raccourcis clavier

- **Ctrl/Cmd + Shift + L** : Déconnexion rapide
- **Escape** : Fermer notifications et modals

## 🔒 Sécurité

### Protection automatique
- **Token CSRF** configuré automatiquement
- **Validation des sessions** en temps réel
- **Gestion des erreurs 401/403** avec redirections
- **Refresh automatique** des tokens

### Gestion des erreurs API
```javascript
// Intercepteur global configuré automatiquement
axios.interceptors.response.use(
    response => response,
    error => {
        // Gestion automatique des erreurs
        window.authManager.handleApiError(error);
        return Promise.reject(error);
    }
);
```

## 📱 Responsive et UX

### États de chargement
```javascript
// Boutons avec état de chargement automatique
AuthUtils.setButtonLoading(button, 'Envoi...');
// ... opération ...
AuthUtils.resetButtonLoading(button);
```

### Visibilité de la page
- **Vérification automatique** de l'auth quand la page redevient visible
- **Notifications d'expiration** de session
- **Redirection automatique** si nécessaire

## 🎨 Personnalisation

### Styles des notifications
Les notifications utilisent les classes Bootstrap 5 :
- `.alert-success` (vert)
- `.alert-danger` (rouge)
- `.alert-warning` (orange)
- `.alert-info` (bleu)

### Configuration des durées
```javascript
// Durées par défaut (modifiables)
NotificationUtils.success(message, 5000);  // 5 secondes
NotificationUtils.error(message, 7000);    // 7 secondes
NotificationUtils.warning(message, 6000);  // 6 secondes
```

## 🚨 Gestion d'erreurs

### Codes d'erreur HTTP
- **401** : Session expirée → Redirection login
- **403** : Accès non autorisé → Notification
- **422** : Erreurs de validation → Affichage détaillé
- **500** : Erreur serveur → Notification générique

### Erreurs réseau
- **Timeout** : Notification d'erreur de connexion
- **Offline** : Détection automatique (future feature)

## 🔄 Workflow d'authentification

1. **Page chargée** → Vérification auth automatique
2. **Non authentifié** → Redirection `/login`
3. **Authentifié** → Validation du rôle
4. **Rôle incorrect** → Redirection dashboard approprié
5. **Accès valide** → Chargement de la page

## 📊 Monitoring

### Logs automatiques
- Erreurs d'authentification
- Redirections automatiques
- Erreurs API
- Actions utilisateur

### Métriques disponibles
- Temps de réponse API
- Taux d'erreur par type
- Fréquence des redirections

## 🛠️ Développement

### Ajout de nouvelles fonctionnalités
1. Créer dans `/utils/` si réutilisable
2. Ajouter au `AuthManager` si global
3. Documenter dans ce README
4. Tester sur tous les rôles

### Tests recommandés
- Navigation entre pages
- Expiration de session
- Erreurs réseau
- Différents rôles utilisateur
