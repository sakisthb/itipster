/**
 * iTipster Pro - PWA Manager
 * Handles PWA installation, service worker management, and app-like features
 */

class PWAManager {
    constructor() {
        this.isInstalled = false;
        this.isStandalone = false;
        this.deferredPrompt = null;
        this.swRegistration = null;
        this.updateAvailable = false;
        this.offlineStatus = false;
        this.init();
    }

    /**
     * Initialize PWA manager
     */
    async init() {
        this.checkInstallationStatus();
        this.setupEventListeners();
        await this.registerServiceWorker();
        this.setupOfflineDetection();
        this.setupAppLikeFeatures();
        this.checkForUpdates();
    }

    /**
     * Check if app is installed and in standalone mode
     */
    checkInstallationStatus() {
        // Check if running in standalone mode
        this.isStandalone = window.matchMedia('(display-mode: standalone)').matches ||
                           window.navigator.standalone === true;

        // Check if app is installed
        this.isInstalled = this.isStandalone || 
                          localStorage.getItem('itipster-installed') === 'true';

        // Add body classes
        document.body.classList.toggle('pwa-installed', this.isInstalled);
        document.body.classList.toggle('pwa-standalone', this.isStandalone);

        console.log('[PWA] Installation status:', {
            installed: this.isInstalled,
            standalone: this.isStandalone
        });
    }

    /**
     * Setup event listeners
     */
    setupEventListeners() {
        // Before install prompt
        window.addEventListener('beforeinstallprompt', (e) => {
            console.log('[PWA] Before install prompt triggered');
            e.preventDefault();
            this.deferredPrompt = e;
            this.showInstallPrompt();
        });

        // App installed
        window.addEventListener('appinstalled', (e) => {
            console.log('[PWA] App installed successfully');
            this.isInstalled = true;
            this.isStandalone = true;
            localStorage.setItem('itipster-installed', 'true');
            document.body.classList.add('pwa-installed', 'pwa-standalone');
            this.hideInstallPrompt();
            this.trackInstallation();
        });

        // Display mode change
        window.matchMedia('(display-mode: standalone)').addEventListener('change', (e) => {
            this.isStandalone = e.matches;
            document.body.classList.toggle('pwa-standalone', this.isStandalone);
        });

        // Online/offline status
        window.addEventListener('online', () => {
            this.offlineStatus = false;
            this.updateOfflineStatus();
            this.syncOfflineData();
        });

        window.addEventListener('offline', () => {
            this.offlineStatus = true;
            this.updateOfflineStatus();
        });

        // Hardware back button (Android)
        window.addEventListener('popstate', (e) => {
            if (this.isStandalone && history.length <= 1) {
                e.preventDefault();
                this.showExitPrompt();
            }
        });

        // Keyboard shortcuts
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.isStandalone) {
                this.showExitPrompt();
            }
        });
    }

    /**
     * Register service worker
     */
    async registerServiceWorker() {
        if ('serviceWorker' in navigator) {
            try {
                this.swRegistration = await navigator.serviceWorker.register('/sw.js', {
                    scope: '/'
                });

                console.log('[PWA] Service worker registered:', this.swRegistration);

                // Listen for updates
                this.swRegistration.addEventListener('updatefound', () => {
                    const newWorker = this.swRegistration.installing;
                    newWorker.addEventListener('statechange', () => {
                        if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
                            this.updateAvailable = true;
                            this.showUpdatePrompt();
                        }
                    });
                });

                // Listen for controller change
                navigator.serviceWorker.addEventListener('controllerchange', () => {
                    console.log('[PWA] Service worker controller changed');
                    this.updateAvailable = false;
                    this.hideUpdatePrompt();
                    location.reload();
                });

            } catch (error) {
                console.error('[PWA] Service worker registration failed:', error);
            }
        }
    }

    /**
     * Show install prompt
     */
    showInstallPrompt() {
        if (this.isInstalled || !this.deferredPrompt) return;

        const prompt = document.createElement('div');
        prompt.className = 'pwa-install-prompt';
        prompt.innerHTML = `
            <div class="pwa-install-content">
                <div class="pwa-install-icon">
                    <img src="/assets/images/pwa/icon-192x192.png" alt="iTipster Pro">
                </div>
                <div class="pwa-install-text">
                    <h3>Install iTipster Pro</h3>
                    <p>Get instant access to AI-powered predictions, live odds, and real-time updates.</p>
                    <ul class="pwa-install-benefits">
                        <li>📱 Works offline</li>
                        <li>⚡ Faster loading</li>
                        <li>🔔 Push notifications</li>
                        <li>📊 Real-time updates</li>
                    </ul>
                </div>
                <div class="pwa-install-actions">
                    <button class="btn btn-primary pwa-install-btn">Install App</button>
                    <button class="btn btn-ghost pwa-dismiss-btn">Not Now</button>
                </div>
            </div>
        `;

        document.body.appendChild(prompt);

        // Add event listeners
        prompt.querySelector('.pwa-install-btn').addEventListener('click', () => {
            this.installApp();
        });

        prompt.querySelector('.pwa-dismiss-btn').addEventListener('click', () => {
            this.hideInstallPrompt();
        });

        // Auto-hide after 10 seconds
        setTimeout(() => {
            this.hideInstallPrompt();
        }, 10000);
    }

    /**
     * Hide install prompt
     */
    hideInstallPrompt() {
        const prompt = document.querySelector('.pwa-install-prompt');
        if (prompt) {
            prompt.remove();
        }
    }

    /**
     * Install app
     */
    async installApp() {
        if (!this.deferredPrompt) return;

        try {
            this.deferredPrompt.prompt();
            const { outcome } = await this.deferredPrompt.userChoice;
            
            console.log('[PWA] Install prompt outcome:', outcome);
            
            if (outcome === 'accepted') {
                console.log('[PWA] User accepted install prompt');
            } else {
                console.log('[PWA] User dismissed install prompt');
            }
            
            this.deferredPrompt = null;
            this.hideInstallPrompt();
            
        } catch (error) {
            console.error('[PWA] Install prompt failed:', error);
        }
    }

    /**
     * Show update prompt
     */
    showUpdatePrompt() {
        if (!this.updateAvailable) return;

        const prompt = document.createElement('div');
        prompt.className = 'pwa-update-prompt';
        prompt.innerHTML = `
            <div class="pwa-update-content">
                <div class="pwa-update-icon">🔄</div>
                <div class="pwa-update-text">
                    <h3>Update Available</h3>
                    <p>A new version of iTipster Pro is available with improved features and performance.</p>
                </div>
                <div class="pwa-update-actions">
                    <button class="btn btn-primary pwa-update-btn">Update Now</button>
                    <button class="btn btn-ghost pwa-update-later-btn">Later</button>
                </div>
            </div>
        `;

        document.body.appendChild(prompt);

        // Add event listeners
        prompt.querySelector('.pwa-update-btn').addEventListener('click', () => {
            this.updateApp();
        });

        prompt.querySelector('.pwa-update-later-btn').addEventListener('click', () => {
            this.hideUpdatePrompt();
        });
    }

    /**
     * Hide update prompt
     */
    hideUpdatePrompt() {
        const prompt = document.querySelector('.pwa-update-prompt');
        if (prompt) {
            prompt.remove();
        }
    }

    /**
     * Update app
     */
    updateApp() {
        if (this.swRegistration && this.swRegistration.waiting) {
            this.swRegistration.waiting.postMessage({ type: 'SKIP_WAITING' });
        }
        this.hideUpdatePrompt();
    }

    /**
     * Setup offline detection
     */
    setupOfflineDetection() {
        this.offlineStatus = !navigator.onLine;
        this.updateOfflineStatus();
    }

    /**
     * Update offline status UI
     */
    updateOfflineStatus() {
        document.body.classList.toggle('offline', this.offlineStatus);
        
        // Show/hide offline indicator
        let indicator = document.querySelector('.offline-indicator');
        if (this.offlineStatus && !indicator) {
            indicator = document.createElement('div');
            indicator.className = 'offline-indicator';
            indicator.innerHTML = `
                <div class="offline-content">
                    <span class="offline-icon">📡</span>
                    <span class="offline-text">You're offline. Some features may be limited.</span>
                </div>
            `;
            document.body.appendChild(indicator);
        } else if (!this.offlineStatus && indicator) {
            indicator.remove();
        }
    }

    /**
     * Sync offline data
     */
    async syncOfflineData() {
        if (this.swRegistration && 'sync' in this.swRegistration) {
            try {
                await this.swRegistration.sync.register('background-sync-predictions');
                await this.swRegistration.sync.register('background-sync-favorites');
                await this.swRegistration.sync.register('background-sync-user-data');
                console.log('[PWA] Background sync registered');
            } catch (error) {
                console.error('[PWA] Background sync failed:', error);
            }
        }
    }

    /**
     * Setup app-like features
     */
    setupAppLikeFeatures() {
        if (this.isStandalone) {
            // Hide browser UI elements
            this.hideBrowserUI();
            
            // Setup app-like navigation
            this.setupAppNavigation();
            
            // Setup splash screen
            this.setupSplashScreen();
        }
    }

    /**
     * Hide browser UI elements
     */
    hideBrowserUI() {
        // Add meta tags for full-screen experience
        const metaViewport = document.querySelector('meta[name="viewport"]');
        if (metaViewport) {
            metaViewport.setAttribute('content', 'width=device-width, initial-scale=1.0, user-scalable=no, viewport-fit=cover');
        }

        // Add status bar styling
        const statusBarMeta = document.createElement('meta');
        statusBarMeta.name = 'apple-mobile-web-app-status-bar-style';
        statusBarMeta.content = 'default';
        document.head.appendChild(statusBarMeta);
    }

    /**
     * Setup app-like navigation
     */
    setupAppNavigation() {
        // Handle back button
        let backButton = document.querySelector('.pwa-back-btn');
        if (!backButton) {
            backButton = document.createElement('button');
            backButton.className = 'pwa-back-btn';
            backButton.innerHTML = '←';
            backButton.addEventListener('click', () => {
                if (history.length > 1) {
                    history.back();
                } else {
                    this.showExitPrompt();
                }
            });
            
            // Add to header if exists
            const header = document.querySelector('header, .header, .main-header');
            if (header) {
                header.insertBefore(backButton, header.firstChild);
            }
        }
    }

    /**
     * Setup splash screen
     */
    setupSplashScreen() {
        const splash = document.createElement('div');
        splash.className = 'pwa-splash-screen';
        splash.innerHTML = `
            <div class="pwa-splash-content">
                <div class="pwa-splash-icon">
                    <img src="/assets/images/pwa/icon-192x192.png" alt="iTipster Pro">
                </div>
                <div class="pwa-splash-title">iTipster Pro</div>
                <div class="pwa-splash-subtitle">Premium Sports Predictions</div>
                <div class="pwa-splash-loader">
                    <div class="pwa-splash-spinner"></div>
                </div>
            </div>
        `;

        document.body.appendChild(splash);

        // Hide splash screen after page load
        window.addEventListener('load', () => {
            setTimeout(() => {
                splash.classList.add('pwa-splash-hidden');
                setTimeout(() => {
                    splash.remove();
                }, 300);
            }, 1000);
        });
    }

    /**
     * Show exit prompt
     */
    showExitPrompt() {
        const prompt = document.createElement('div');
        prompt.className = 'pwa-exit-prompt';
        prompt.innerHTML = `
            <div class="pwa-exit-content">
                <div class="pwa-exit-icon">🚪</div>
                <div class="pwa-exit-text">
                    <h3>Exit App?</h3>
                    <p>Are you sure you want to exit iTipster Pro?</p>
                </div>
                <div class="pwa-exit-actions">
                    <button class="btn btn-primary pwa-exit-confirm-btn">Exit</button>
                    <button class="btn btn-ghost pwa-exit-cancel-btn">Cancel</button>
                </div>
            </div>
        `;

        document.body.appendChild(prompt);

        // Add event listeners
        prompt.querySelector('.pwa-exit-confirm-btn').addEventListener('click', () => {
            window.close();
            // Fallback for browsers that don't support window.close()
            window.location.href = 'about:blank';
        });

        prompt.querySelector('.pwa-exit-cancel-btn').addEventListener('click', () => {
            prompt.remove();
        });

        // Auto-hide after 5 seconds
        setTimeout(() => {
            prompt.remove();
        }, 5000);
    }

    /**
     * Request push notification permission
     */
    async requestNotificationPermission() {
        if (!('Notification' in window)) {
            console.log('[PWA] Notifications not supported');
            return false;
        }

        if (Notification.permission === 'granted') {
            return true;
        }

        if (Notification.permission === 'denied') {
            console.log('[PWA] Notifications denied');
            return false;
        }

        try {
            const permission = await Notification.requestPermission();
            return permission === 'granted';
        } catch (error) {
            console.error('[PWA] Notification permission request failed:', error);
            return false;
        }
    }

    /**
     * Subscribe to push notifications
     */
    async subscribeToPushNotifications() {
        if (!this.swRegistration) {
            console.error('[PWA] Service worker not registered');
            return false;
        }

        try {
            const permission = await this.requestNotificationPermission();
            if (!permission) return false;

            const subscription = await this.swRegistration.pushManager.subscribe({
                userVisibleOnly: true,
                applicationServerKey: this.urlBase64ToUint8Array('YOUR_VAPID_PUBLIC_KEY')
            });

            console.log('[PWA] Push subscription:', subscription);
            
            // Send subscription to server
            await this.sendSubscriptionToServer(subscription);
            
            return true;
        } catch (error) {
            console.error('[PWA] Push subscription failed:', error);
            return false;
        }
    }

    /**
     * Send subscription to server
     */
    async sendSubscriptionToServer(subscription) {
        try {
            const response = await fetch('/wp-json/itipster/v1/push-subscription', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-WP-Nonce': itipster_ajax.nonce
                },
                body: JSON.stringify({
                    subscription: subscription,
                    user_id: itipster_ajax.user_id || 0
                })
            });

            if (response.ok) {
                console.log('[PWA] Subscription sent to server');
            }
        } catch (error) {
            console.error('[PWA] Failed to send subscription to server:', error);
        }
    }

    /**
     * Convert VAPID key
     */
    urlBase64ToUint8Array(base64String) {
        const padding = '='.repeat((4 - base64String.length % 4) % 4);
        const base64 = (base64String + padding)
            .replace(/-/g, '+')
            .replace(/_/g, '/');

        const rawData = window.atob(base64);
        const outputArray = new Uint8Array(rawData.length);

        for (let i = 0; i < rawData.length; ++i) {
            outputArray[i] = rawData.charCodeAt(i);
        }
        return outputArray;
    }

    /**
     * Check for updates
     */
    checkForUpdates() {
        if (this.swRegistration) {
            this.swRegistration.update();
        }
    }

    /**
     * Track installation
     */
    trackInstallation() {
        // Send analytics event
        if (typeof gtag !== 'undefined') {
            gtag('event', 'pwa_install', {
                event_category: 'PWA',
                event_label: 'App Installation'
            });
        }

        // Send to server
        fetch('/wp-json/itipster/v1/analytics/pwa-install', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-WP-Nonce': itipster_ajax.nonce
            },
            body: JSON.stringify({
                timestamp: Date.now(),
                user_agent: navigator.userAgent,
                platform: this.getPlatform()
            })
        }).catch(error => {
            console.error('[PWA] Failed to track installation:', error);
        });
    }

    /**
     * Get platform information
     */
    getPlatform() {
        const userAgent = navigator.userAgent;
        
        if (/iPhone|iPad|iPod/.test(userAgent)) {
            return 'iOS';
        } else if (/Android/.test(userAgent)) {
            return 'Android';
        } else if (/Windows/.test(userAgent)) {
            return 'Windows';
        } else if (/Mac/.test(userAgent)) {
            return 'macOS';
        } else if (/Linux/.test(userAgent)) {
            return 'Linux';
        }
        
        return 'Unknown';
    }

    /**
     * Get PWA status
     */
    getStatus() {
        return {
            installed: this.isInstalled,
            standalone: this.isStandalone,
            offline: this.offlineStatus,
            updateAvailable: this.updateAvailable,
            serviceWorker: !!this.swRegistration,
            notifications: Notification.permission
        };
    }
}

// Initialize PWA manager when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    window.pwaManager = new PWAManager();
});

// Export for module usage
if (typeof module !== 'undefined' && module.exports) {
    module.exports = PWAManager;
} 