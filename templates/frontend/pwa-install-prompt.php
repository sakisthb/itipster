<?php
/**
 * iTipster Pro - PWA Install Prompt Template
 * Custom install prompt component with Apple-style design
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="pwa-install-prompt-container" id="pwa-install-prompt">
    <div class="pwa-install-overlay"></div>
    
    <div class="pwa-install-modal">
        <!-- Header -->
        <div class="pwa-install-header">
            <div class="pwa-install-logo">
                <img src="<?php echo plugin_dir_url(__FILE__) . '../../assets/images/pwa/icon-192x192.png'; ?>" 
                     alt="iTipster Pro" class="pwa-logo">
                <div class="pwa-logo-glow"></div>
            </div>
            <button class="pwa-close-btn" id="pwa-close-btn" aria-label="Close">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path d="M18 6L6 18M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </button>
        </div>

        <!-- Content -->
        <div class="pwa-install-content">
            <h2 class="pwa-install-title">Install iTipster Pro</h2>
            <p class="pwa-install-subtitle">Get instant access to AI-powered predictions, live odds, and real-time updates on your device.</p>
            
            <!-- Benefits Section -->
            <div class="pwa-benefits-section">
                <h3 class="pwa-benefits-title">Why install iTipster Pro?</h3>
                <div class="pwa-benefits-grid">
                    <div class="pwa-benefit-item">
                        <div class="pwa-benefit-icon">📱</div>
                        <div class="pwa-benefit-content">
                            <h4>Works Offline</h4>
                            <p>Access your favorite predictions even without internet connection</p>
                        </div>
                    </div>
                    
                    <div class="pwa-benefit-item">
                        <div class="pwa-benefit-icon">⚡</div>
                        <div class="pwa-benefit-content">
                            <h4>Faster Loading</h4>
                            <p>Lightning-fast performance with cached content and optimized loading</p>
                        </div>
                    </div>
                    
                    <div class="pwa-benefit-item">
                        <div class="pwa-benefit-icon">🔔</div>
                        <div class="pwa-benefit-content">
                            <h4>Push Notifications</h4>
                            <p>Get instant alerts for new predictions and live odds updates</p>
                        </div>
                    </div>
                    
                    <div class="pwa-benefit-item">
                        <div class="pwa-benefit-icon">📊</div>
                        <div class="pwa-benefit-content">
                            <h4>Real-time Updates</h4>
                            <p>Live score updates and odds changes in real-time</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Installation Guide -->
            <div class="pwa-install-guide">
                <h3 class="pwa-guide-title">How to install:</h3>
                
                <!-- iOS Instructions -->
                <div class="pwa-platform-guide" id="ios-guide">
                    <div class="pwa-platform-header">
                        <div class="pwa-platform-icon">🍎</div>
                        <h4>iPhone/iPad</h4>
                    </div>
                    <ol class="pwa-install-steps">
                        <li>Tap the <strong>Share</strong> button <span class="pwa-share-icon">⎋</span> in Safari</li>
                        <li>Scroll down and tap <strong>"Add to Home Screen"</strong></li>
                        <li>Tap <strong>"Add"</strong> to confirm</li>
                        <li>Open iTipster Pro from your home screen</li>
                    </ol>
                </div>

                <!-- Android Instructions -->
                <div class="pwa-platform-guide" id="android-guide">
                    <div class="pwa-platform-header">
                        <div class="pwa-platform-icon">🤖</div>
                        <h4>Android</h4>
                    </div>
                    <ol class="pwa-install-steps">
                        <li>Tap the <strong>Menu</strong> button <span class="pwa-menu-icon">⋮</span> in Chrome</li>
                        <li>Tap <strong>"Add to Home screen"</strong></li>
                        <li>Tap <strong>"Add"</strong> to confirm</li>
                        <li>Open iTipster Pro from your home screen</li>
                    </ol>
                </div>

                <!-- Desktop Instructions -->
                <div class="pwa-platform-guide" id="desktop-guide">
                    <div class="pwa-platform-header">
                        <div class="pwa-platform-icon">💻</div>
                        <h4>Desktop</h4>
                    </div>
                    <ol class="pwa-install-steps">
                        <li>Click the <strong>Install</strong> button below</li>
                        <li>Or look for the install icon <span class="pwa-install-icon-small">⬇</span> in your browser's address bar</li>
                        <li>Click <strong>"Install"</strong> when prompted</li>
                        <li>iTipster Pro will open in its own window</li>
                    </ol>
                </div>
            </div>

            <!-- Features Preview -->
            <div class="pwa-features-preview">
                <h3 class="pwa-features-title">What you'll get:</h3>
                <div class="pwa-features-grid">
                    <div class="pwa-feature-card">
                        <div class="pwa-feature-icon">🎯</div>
                        <h4>AI Predictions</h4>
                        <p>84.7% success rate with machine learning algorithms</p>
                    </div>
                    <div class="pwa-feature-card">
                        <div class="pwa-feature-icon">📈</div>
                        <h4>Live Odds</h4>
                        <p>Real-time odds from top bookmakers worldwide</p>
                    </div>
                    <div class="pwa-feature-card">
                        <div class="pwa-feature-icon">📊</div>
                        <h4>Analytics</h4>
                        <p>Advanced statistics and performance tracking</p>
                    </div>
                    <div class="pwa-feature-card">
                        <div class="pwa-feature-icon">🔔</div>
                        <h4>Notifications</h4>
                        <p>Instant alerts for predictions and results</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="pwa-install-footer">
            <div class="pwa-install-actions">
                <button class="btn btn-primary btn-large pwa-install-btn" id="pwa-install-btn">
                    <span class="btn-icon">⬇</span>
                    <span class="btn-text">Install iTipster Pro</span>
                </button>
                <button class="btn btn-ghost btn-large pwa-dismiss-btn" id="pwa-dismiss-btn">
                    Maybe Later
                </button>
            </div>
            
            <div class="pwa-install-info">
                <p class="pwa-info-text">
                    <span class="pwa-info-icon">ℹ️</span>
                    Free to install • No ads • Works offline
                </p>
                <p class="pwa-privacy-text">
                    Your data stays private and secure. 
                    <a href="/privacy-policy" class="pwa-privacy-link">Learn more</a>
                </p>
            </div>
        </div>
    </div>
</div>

<!-- PWA Install Success Modal -->
<div class="pwa-success-modal" id="pwa-success-modal">
    <div class="pwa-success-content">
        <div class="pwa-success-icon">✅</div>
        <h3>Installation Complete!</h3>
        <p>iTipster Pro has been successfully installed on your device.</p>
        <div class="pwa-success-features">
            <p><strong>You can now:</strong></p>
            <ul>
                <li>Access iTipster Pro from your home screen</li>
                <li>Use the app offline</li>
                <li>Receive push notifications</li>
                <li>Enjoy faster loading times</li>
            </ul>
        </div>
        <button class="btn btn-primary pwa-success-btn" id="pwa-success-btn">
            Get Started
        </button>
    </div>
</div>

<!-- PWA Install Error Modal -->
<div class="pwa-error-modal" id="pwa-error-modal">
    <div class="pwa-error-content">
        <div class="pwa-error-icon">⚠️</div>
        <h3>Installation Failed</h3>
        <p id="pwa-error-message">Unable to install iTipster Pro. Please try again.</p>
        <div class="pwa-error-help">
            <p><strong>Need help?</strong></p>
            <ul>
                <li>Make sure you're using a supported browser</li>
                <li>Check your internet connection</li>
                <li>Try refreshing the page</li>
            </ul>
        </div>
        <div class="pwa-error-actions">
            <button class="btn btn-primary pwa-retry-btn" id="pwa-retry-btn">
                Try Again
            </button>
            <button class="btn btn-ghost pwa-cancel-btn" id="pwa-cancel-btn">
                Cancel
            </button>
        </div>
    </div>
</div>

<!-- PWA Manual Install Instructions -->
<div class="pwa-manual-install" id="pwa-manual-install">
    <div class="pwa-manual-content">
        <h3>Manual Installation</h3>
        <p>If the automatic installation doesn't work, follow these steps:</p>
        
        <div class="pwa-manual-platforms">
            <!-- iOS Manual -->
            <div class="pwa-manual-platform">
                <h4>iOS (iPhone/iPad)</h4>
                <div class="pwa-manual-steps">
                    <div class="pwa-manual-step">
                        <span class="step-number">1</span>
                        <span class="step-text">Open Safari browser</span>
                    </div>
                    <div class="pwa-manual-step">
                        <span class="step-number">2</span>
                        <span class="step-text">Tap the Share button (square with arrow)</span>
                    </div>
                    <div class="pwa-manual-step">
                        <span class="step-number">3</span>
                        <span class="step-text">Scroll down and tap "Add to Home Screen"</span>
                    </div>
                    <div class="pwa-manual-step">
                        <span class="step-number">4</span>
                        <span class="step-text">Tap "Add" to confirm</span>
                    </div>
                </div>
            </div>

            <!-- Android Manual -->
            <div class="pwa-manual-platform">
                <h4>Android</h4>
                <div class="pwa-manual-steps">
                    <div class="pwa-manual-step">
                        <span class="step-number">1</span>
                        <span class="step-text">Open Chrome browser</span>
                    </div>
                    <div class="pwa-manual-step">
                        <span class="step-number">2</span>
                        <span class="step-text">Tap the menu button (three dots)</span>
                    </div>
                    <div class="pwa-manual-step">
                        <span class="step-number">3</span>
                        <span class="step-text">Tap "Add to Home screen"</span>
                    </div>
                    <div class="pwa-manual-step">
                        <span class="step-number">4</span>
                        <span class="step-text">Tap "Add" to confirm</span>
                    </div>
                </div>
            </div>

            <!-- Desktop Manual -->
            <div class="pwa-manual-platform">
                <h4>Desktop (Chrome/Edge)</h4>
                <div class="pwa-manual-steps">
                    <div class="pwa-manual-step">
                        <span class="step-number">1</span>
                        <span class="step-text">Look for the install icon in the address bar</span>
                    </div>
                    <div class="pwa-manual-step">
                        <span class="step-number">2</span>
                        <span class="step-text">Click the install icon (⬇)</span>
                    </div>
                    <div class="pwa-manual-step">
                        <span class="step-number">3</span>
                        <span class="step-text">Click "Install" when prompted</span>
                    </div>
                    <div class="pwa-manual-step">
                        <span class="step-number">4</span>
                        <span class="step-text">The app will open in its own window</span>
                    </div>
                </div>
            </div>
        </div>

        <button class="btn btn-primary pwa-manual-close" id="pwa-manual-close">
            Got it!
        </button>
    </div>
</div>

<script>
// PWA Install Prompt JavaScript
document.addEventListener('DOMContentLoaded', function() {
    const pwaManager = window.pwaManager;
    
    if (!pwaManager) {
        console.error('PWA Manager not found');
        return;
    }

    // Show platform-specific guide
    function showPlatformGuide() {
        const userAgent = navigator.userAgent;
        const iosGuide = document.getElementById('ios-guide');
        const androidGuide = document.getElementById('android-guide');
        const desktopGuide = document.getElementById('desktop-guide');

        // Hide all guides first
        [iosGuide, androidGuide, desktopGuide].forEach(guide => {
            if (guide) guide.style.display = 'none';
        });

        // Show appropriate guide
        if (/iPhone|iPad|iPod/.test(userAgent)) {
            if (iosGuide) iosGuide.style.display = 'block';
        } else if (/Android/.test(userAgent)) {
            if (androidGuide) androidGuide.style.display = 'block';
        } else {
            if (desktopGuide) desktopGuide.style.display = 'block';
        }
    }

    // Initialize platform detection
    showPlatformGuide();

    // Handle install button click
    const installBtn = document.getElementById('pwa-install-btn');
    if (installBtn) {
        installBtn.addEventListener('click', function() {
            pwaManager.installApp();
        });
    }

    // Handle dismiss button click
    const dismissBtn = document.getElementById('pwa-dismiss-btn');
    if (dismissBtn) {
        dismissBtn.addEventListener('click', function() {
            pwaManager.hideInstallPrompt();
        });
    }

    // Handle close button click
    const closeBtn = document.getElementById('pwa-close-btn');
    if (closeBtn) {
        closeBtn.addEventListener('click', function() {
            pwaManager.hideInstallPrompt();
        });
    }

    // Handle success modal
    const successBtn = document.getElementById('pwa-success-btn');
    if (successBtn) {
        successBtn.addEventListener('click', function() {
            document.getElementById('pwa-success-modal').style.display = 'none';
        });
    }

    // Handle error modal
    const retryBtn = document.getElementById('pwa-retry-btn');
    if (retryBtn) {
        retryBtn.addEventListener('click', function() {
            pwaManager.installApp();
            document.getElementById('pwa-error-modal').style.display = 'none';
        });
    }

    const cancelBtn = document.getElementById('pwa-cancel-btn');
    if (cancelBtn) {
        cancelBtn.addEventListener('click', function() {
            document.getElementById('pwa-error-modal').style.display = 'none';
        });
    }

    // Handle manual install close
    const manualCloseBtn = document.getElementById('pwa-manual-close');
    if (manualCloseBtn) {
        manualCloseBtn.addEventListener('click', function() {
            document.getElementById('pwa-manual-install').style.display = 'none';
        });
    }

    // Show manual install instructions
    const showManualInstall = () => {
        document.getElementById('pwa-manual-install').style.display = 'flex';
    };

    // Add manual install link
    const manualLink = document.createElement('button');
    manualLink.className = 'pwa-manual-link';
    manualLink.textContent = 'Need help installing?';
    manualLink.addEventListener('click', showManualInstall);
    
    const footer = document.querySelector('.pwa-install-footer');
    if (footer) {
        footer.appendChild(manualLink);
    }
});
</script>

<style>
/* Additional PWA Install Prompt Styles */
.pwa-install-prompt-container {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 10000;
}

.pwa-install-prompt-container.show {
    display: flex;
    align-items: center;
    justify-content: center;
}

.pwa-install-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.8);
    backdrop-filter: blur(10px);
}

.pwa-install-modal {
    position: relative;
    background: var(--card-bg);
    border-radius: var(--border-radius-xl);
    max-width: 500px;
    width: 90%;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: var(--shadow-2xl);
    border: 1px solid var(--border-color);
    animation: pwa-slide-up 0.4s ease-out;
}

.pwa-install-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 24px 24px 0 24px;
}

.pwa-install-logo {
    position: relative;
    display: flex;
    align-items: center;
}

.pwa-logo {
    width: 48px;
    height: 48px;
    border-radius: var(--border-radius-lg);
    box-shadow: var(--shadow-lg);
}

.pwa-logo-glow {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    border-radius: var(--border-radius-lg);
    background: linear-gradient(45deg, var(--color-primary), var(--color-secondary));
    opacity: 0.3;
    filter: blur(8px);
    z-index: -1;
}

.pwa-close-btn {
    background: none;
    border: none;
    color: var(--text-secondary);
    cursor: pointer;
    padding: 8px;
    border-radius: var(--border-radius-md);
    transition: all 0.2s ease;
}

.pwa-close-btn:hover {
    background: var(--hover-bg);
    color: var(--text-primary);
}

.pwa-install-content {
    padding: 24px;
}

.pwa-install-title {
    font-size: var(--text-2xl);
    font-weight: var(--font-weight-bold);
    color: var(--text-primary);
    margin-bottom: 8px;
    text-align: center;
}

.pwa-install-subtitle {
    color: var(--text-secondary);
    text-align: center;
    margin-bottom: 32px;
    line-height: 1.5;
}

.pwa-benefits-section {
    margin-bottom: 32px;
}

.pwa-benefits-title {
    font-size: var(--text-lg);
    font-weight: var(--font-weight-semibold);
    color: var(--text-primary);
    margin-bottom: 16px;
}

.pwa-benefits-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
}

.pwa-benefit-item {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 16px;
    background: var(--hover-bg);
    border-radius: var(--border-radius-lg);
    border: 1px solid var(--border-color);
}

.pwa-benefit-icon {
    font-size: 24px;
    flex-shrink: 0;
}

.pwa-benefit-content h4 {
    font-size: var(--text-sm);
    font-weight: var(--font-weight-semibold);
    color: var(--text-primary);
    margin-bottom: 4px;
}

.pwa-benefit-content p {
    font-size: var(--text-xs);
    color: var(--text-secondary);
    line-height: 1.4;
    margin: 0;
}

.pwa-install-guide {
    margin-bottom: 32px;
}

.pwa-guide-title {
    font-size: var(--text-lg);
    font-weight: var(--font-weight-semibold);
    color: var(--text-primary);
    margin-bottom: 16px;
}

.pwa-platform-guide {
    margin-bottom: 20px;
    padding: 16px;
    background: var(--hover-bg);
    border-radius: var(--border-radius-lg);
    border: 1px solid var(--border-color);
}

.pwa-platform-header {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 12px;
}

.pwa-platform-icon {
    font-size: 20px;
}

.pwa-platform-header h4 {
    font-size: var(--text-md);
    font-weight: var(--font-weight-semibold);
    color: var(--text-primary);
    margin: 0;
}

.pwa-install-steps {
    list-style: none;
    padding: 0;
    margin: 0;
}

.pwa-install-steps li {
    display: flex;
    align-items: flex-start;
    gap: 8px;
    padding: 4px 0;
    font-size: var(--text-sm);
    color: var(--text-secondary);
    line-height: 1.4;
}

.pwa-install-steps li::before {
    content: counter(step-counter);
    counter-increment: step-counter;
    background: var(--color-primary);
    color: white;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: var(--text-xs);
    font-weight: var(--font-weight-bold);
    flex-shrink: 0;
    margin-top: 2px;
}

.pwa-install-steps {
    counter-reset: step-counter;
}

.pwa-share-icon,
.pwa-menu-icon,
.pwa-install-icon-small {
    background: var(--color-primary);
    color: white;
    padding: 2px 6px;
    border-radius: var(--border-radius-sm);
    font-size: var(--text-xs);
    font-weight: var(--font-weight-bold);
}

.pwa-features-preview {
    margin-bottom: 32px;
}

.pwa-features-title {
    font-size: var(--text-lg);
    font-weight: var(--font-weight-semibold);
    color: var(--text-primary);
    margin-bottom: 16px;
}

.pwa-features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    gap: 12px;
}

.pwa-feature-card {
    text-align: center;
    padding: 16px;
    background: var(--hover-bg);
    border-radius: var(--border-radius-lg);
    border: 1px solid var(--border-color);
}

.pwa-feature-icon {
    font-size: 32px;
    margin-bottom: 8px;
}

.pwa-feature-card h4 {
    font-size: var(--text-sm);
    font-weight: var(--font-weight-semibold);
    color: var(--text-primary);
    margin-bottom: 4px;
}

.pwa-feature-card p {
    font-size: var(--text-xs);
    color: var(--text-secondary);
    line-height: 1.4;
    margin: 0;
}

.pwa-install-footer {
    padding: 24px;
    border-top: 1px solid var(--border-color);
    background: var(--hover-bg);
    border-radius: 0 0 var(--border-radius-xl) var(--border-radius-xl);
}

.pwa-install-actions {
    display: flex;
    gap: 12px;
    margin-bottom: 16px;
}

.pwa-install-btn {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.btn-icon {
    font-size: 16px;
}

.pwa-dismiss-btn {
    flex: 1;
}

.pwa-install-info {
    text-align: center;
}

.pwa-info-text {
    font-size: var(--text-sm);
    color: var(--text-secondary);
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 4px;
}

.pwa-info-icon {
    font-size: 14px;
}

.pwa-privacy-text {
    font-size: var(--text-xs);
    color: var(--text-tertiary);
    margin: 0;
}

.pwa-privacy-link {
    color: var(--color-primary);
    text-decoration: none;
}

.pwa-privacy-link:hover {
    text-decoration: underline;
}

.pwa-manual-link {
    background: none;
    border: none;
    color: var(--color-primary);
    font-size: var(--text-sm);
    cursor: pointer;
    text-decoration: underline;
    margin-top: 12px;
    width: 100%;
    padding: 8px;
}

.pwa-manual-link:hover {
    color: var(--color-primary-dark);
}

/* Success Modal */
.pwa-success-modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.8);
    backdrop-filter: blur(10px);
    z-index: 10001;
    align-items: center;
    justify-content: center;
    padding: 20px;
}

.pwa-success-content {
    background: var(--card-bg);
    border-radius: var(--border-radius-xl);
    padding: 32px;
    max-width: 400px;
    width: 100%;
    text-align: center;
    box-shadow: var(--shadow-2xl);
    border: 1px solid var(--border-color);
    animation: pwa-slide-up 0.4s ease-out;
}

.pwa-success-icon {
    font-size: 48px;
    margin-bottom: 16px;
}

.pwa-success-content h3 {
    font-size: var(--text-xl);
    font-weight: var(--font-weight-bold);
    color: var(--text-primary);
    margin-bottom: 8px;
}

.pwa-success-content p {
    color: var(--text-secondary);
    margin-bottom: 24px;
}

.pwa-success-features {
    text-align: left;
    margin-bottom: 24px;
}

.pwa-success-features p {
    font-weight: var(--font-weight-semibold);
    color: var(--text-primary);
    margin-bottom: 8px;
}

.pwa-success-features ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.pwa-success-features li {
    padding: 4px 0;
    color: var(--text-secondary);
    font-size: var(--text-sm);
    position: relative;
    padding-left: 20px;
}

.pwa-success-features li::before {
    content: '✓';
    position: absolute;
    left: 0;
    color: var(--color-success);
    font-weight: var(--font-weight-bold);
}

.pwa-success-btn {
    width: 100%;
}

/* Error Modal */
.pwa-error-modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.8);
    backdrop-filter: blur(10px);
    z-index: 10001;
    align-items: center;
    justify-content: center;
    padding: 20px;
}

.pwa-error-content {
    background: var(--card-bg);
    border-radius: var(--border-radius-xl);
    padding: 32px;
    max-width: 400px;
    width: 100%;
    text-align: center;
    box-shadow: var(--shadow-2xl);
    border: 1px solid var(--border-color);
    animation: pwa-slide-up 0.4s ease-out;
}

.pwa-error-icon {
    font-size: 48px;
    margin-bottom: 16px;
}

.pwa-error-content h3 {
    font-size: var(--text-xl);
    font-weight: var(--font-weight-bold);
    color: var(--text-primary);
    margin-bottom: 8px;
}

.pwa-error-content p {
    color: var(--text-secondary);
    margin-bottom: 24px;
}

.pwa-error-help {
    text-align: left;
    margin-bottom: 24px;
}

.pwa-error-help p {
    font-weight: var(--font-weight-semibold);
    color: var(--text-primary);
    margin-bottom: 8px;
}

.pwa-error-help ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.pwa-error-help li {
    padding: 4px 0;
    color: var(--text-secondary);
    font-size: var(--text-sm);
    position: relative;
    padding-left: 20px;
}

.pwa-error-help li::before {
    content: '•';
    position: absolute;
    left: 0;
    color: var(--color-primary);
    font-weight: var(--font-weight-bold);
}

.pwa-error-actions {
    display: flex;
    gap: 12px;
}

.pwa-retry-btn {
    flex: 1;
}

.pwa-cancel-btn {
    flex: 1;
}

/* Manual Install */
.pwa-manual-install {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.8);
    backdrop-filter: blur(10px);
    z-index: 10001;
    align-items: center;
    justify-content: center;
    padding: 20px;
}

.pwa-manual-content {
    background: var(--card-bg);
    border-radius: var(--border-radius-xl);
    padding: 32px;
    max-width: 600px;
    width: 100%;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: var(--shadow-2xl);
    border: 1px solid var(--border-color);
    animation: pwa-slide-up 0.4s ease-out;
}

.pwa-manual-content h3 {
    font-size: var(--text-xl);
    font-weight: var(--font-weight-bold);
    color: var(--text-primary);
    margin-bottom: 8px;
    text-align: center;
}

.pwa-manual-content > p {
    color: var(--text-secondary);
    text-align: center;
    margin-bottom: 24px;
}

.pwa-manual-platforms {
    display: grid;
    gap: 24px;
    margin-bottom: 24px;
}

.pwa-manual-platform {
    padding: 20px;
    background: var(--hover-bg);
    border-radius: var(--border-radius-lg);
    border: 1px solid var(--border-color);
}

.pwa-manual-platform h4 {
    font-size: var(--text-lg);
    font-weight: var(--font-weight-semibold);
    color: var(--text-primary);
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.pwa-manual-steps {
    display: grid;
    gap: 12px;
}

.pwa-manual-step {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px;
    background: var(--card-bg);
    border-radius: var(--border-radius-md);
    border: 1px solid var(--border-color);
}

.step-number {
    background: var(--color-primary);
    color: white;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: var(--text-xs);
    font-weight: var(--font-weight-bold);
    flex-shrink: 0;
}

.step-text {
    font-size: var(--text-sm);
    color: var(--text-secondary);
    line-height: 1.4;
}

.pwa-manual-close {
    width: 100%;
}

/* Responsive Design */
@media (max-width: 480px) {
    .pwa-install-modal {
        width: 95%;
        margin: 10px;
    }
    
    .pwa-install-actions {
        flex-direction: column;
    }
    
    .pwa-benefits-grid {
        grid-template-columns: 1fr;
    }
    
    .pwa-features-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .pwa-manual-platforms {
        grid-template-columns: 1fr;
    }
}

/* Dark Mode Support */
[data-theme="dark"] .pwa-install-modal,
[data-theme="dark"] .pwa-success-content,
[data-theme="dark"] .pwa-error-content,
[data-theme="dark"] .pwa-manual-content {
    background: var(--card-bg-dark);
    border-color: var(--border-color-dark);
}

[data-theme="dark"] .pwa-benefit-item,
[data-theme="dark"] .pwa-platform-guide,
[data-theme="dark"] .pwa-feature-card,
[data-theme="dark"] .pwa-manual-platform {
    background: var(--hover-bg-dark);
    border-color: var(--border-color-dark);
}

[data-theme="dark"] .pwa-manual-step {
    background: var(--card-bg-dark);
    border-color: var(--border-color-dark);
}
</style> 