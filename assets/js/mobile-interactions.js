/**
 * iTipster Pro - Mobile Touch Interactions & Navigation
 * Advanced mobile UX with gestures, haptic feedback, and smooth animations
 */

class iTipsterMobile {
    constructor() {
        this.isInitialized = false;
        this.touchStartX = 0;
        this.touchStartY = 0;
        this.touchEndX = 0;
        this.touchEndY = 0;
        this.swipeThreshold = 50;
        this.longPressTimer = null;
        this.longPressThreshold = 500;
        
        this.init();
    }
    
    init() {
        if (this.isInitialized) return;
        
        // Wait for DOM to be ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => this.setupMobile());
        } else {
            this.setupMobile();
        }
        
        this.isInitialized = true;
    }
    
    setupMobile() {
        this.setupNavigation();
        this.setupTouchInteractions();
        this.setupSwipeGestures();
        this.setupPullToRefresh();
        this.setupHapticFeedback();
        this.setupLazyLoading();
        this.setupOrientationChange();
        this.setupScrollOptimization();
        
        console.log('iTipster Mobile initialized');
    }
    
    /**
     * Setup Mobile Navigation
     */
    setupNavigation() {
        // Create mobile header if not exists
        this.createMobileHeader();
        
        // Mobile menu toggle
        const menuToggle = document.querySelector('.menu-toggle-mobile');
        const navMobile = document.querySelector('.nav-mobile');
        const navOverlay = document.querySelector('.nav-overlay-mobile');
        
        if (menuToggle && navMobile) {
            menuToggle.addEventListener('click', () => this.toggleMobileMenu());
            
            // Close menu when clicking overlay
            if (navOverlay) {
                navOverlay.addEventListener('click', () => this.closeMobileMenu());
            }
            
            // Close menu when clicking nav links
            const navLinks = document.querySelectorAll('.nav-link-mobile');
            navLinks.forEach(link => {
                link.addEventListener('click', () => this.closeMobileMenu());
            });
        }
        
        // Escape key to close menu
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.closeMobileMenu();
            }
        });
    }
    
    createMobileHeader() {
        // Check if mobile header already exists
        if (document.querySelector('.header-mobile')) return;
        
        const header = document.createElement('div');
        header.className = 'header-mobile';
        header.innerHTML = `
            <div class="header-content-mobile">
                <div class="logo-mobile">
                    <span class="icon">⚽</span> iTipster Pro
                </div>
                <button class="menu-toggle-mobile" aria-label="Toggle Menu">
                    <span class="hamburger-icon">☰</span>
                </button>
            </div>
        `;
        
        // Create mobile navigation
        const nav = document.createElement('nav');
        nav.className = 'nav-mobile';
        nav.innerHTML = `
            <a href="${window.location.origin}/predictions/" class="nav-link-mobile">
                📊 Predictions
            </a>
            <a href="${window.location.origin}/fixtures/" class="nav-link-mobile">
                ⚽ Fixtures
            </a>
            <a href="${window.location.origin}/dashboard/" class="nav-link-mobile">
                📈 Dashboard
            </a>
            <a href="#" class="nav-link-mobile">
                💎 Premium
            </a>
            <a href="#" class="nav-link-mobile">
                👤 Profile
            </a>
            <a href="#" class="nav-link-mobile">
                ⚙️ Settings
            </a>
        `;
        
        // Create overlay
        const overlay = document.createElement('div');
        overlay.className = 'nav-overlay-mobile';
        
        // Insert at beginning of body
        document.body.insertBefore(header, document.body.firstChild);
        document.body.appendChild(nav);
        document.body.appendChild(overlay);
    }
    
    toggleMobileMenu() {
        const nav = document.querySelector('.nav-mobile');
        const overlay = document.querySelector('.nav-overlay-mobile');
        const toggle = document.querySelector('.menu-toggle-mobile .hamburger-icon');
        
        if (nav && overlay) {
            const isOpen = nav.classList.contains('active');
            
            if (isOpen) {
                this.closeMobileMenu();
            } else {
                this.openMobileMenu();
            }
        }
    }
    
    openMobileMenu() {
        const nav = document.querySelector('.nav-mobile');
        const overlay = document.querySelector('.nav-overlay-mobile');
        const toggle = document.querySelector('.menu-toggle-mobile .hamburger-icon');
        
        nav.classList.add('active');
        overlay.classList.add('active');
        toggle.textContent = '✕';
        
        // Prevent body scroll
        document.body.style.overflow = 'hidden';
        
        // Haptic feedback
        this.hapticFeedback('medium');
        
        // Focus management
        nav.focus();
    }
    
    closeMobileMenu() {
        const nav = document.querySelector('.nav-mobile');
        const overlay = document.querySelector('.nav-overlay-mobile');
        const toggle = document.querySelector('.menu-toggle-mobile .hamburger-icon');
        
        if (nav) nav.classList.remove('active');
        if (overlay) overlay.classList.remove('active');
        if (toggle) toggle.textContent = '☰';
        
        // Restore body scroll
        document.body.style.overflow = '';
        
        // Haptic feedback
        this.hapticFeedback('light');
    }
    
    /**
     * Setup Touch Interactions
     */
    setupTouchInteractions() {
        // Enhanced touch feedback for buttons
        const buttons = document.querySelectorAll('.btn-mobile, .btn-bet-mobile, .btn-details-mobile');
        
        buttons.forEach(button => {
            // Touch start
            button.addEventListener('touchstart', (e) => {
                button.classList.add('touch-active');
                this.hapticFeedback('light');
                
                // Long press detection
                this.longPressTimer = setTimeout(() => {
                    this.handleLongPress(button, e);
                }, this.longPressThreshold);
            }, { passive: true });
            
            // Touch end
            button.addEventListener('touchend', () => {
                button.classList.remove('touch-active');
                clearTimeout(this.longPressTimer);
            }, { passive: true });
            
            // Touch cancel
            button.addEventListener('touchcancel', () => {
                button.classList.remove('touch-active');
                clearTimeout(this.longPressTimer);
            }, { passive: true });
        });
        
        // Card interactions
        const cards = document.querySelectorAll('.prediction-card, .prediction-card-mobile');
        cards.forEach(card => {
            this.setupCardTouchInteractions(card);
        });
    }
    
    setupCardTouchInteractions(card) {
        let touchStartTime = 0;
        let touchStartPos = { x: 0, y: 0 };
        
        card.addEventListener('touchstart', (e) => {
            touchStartTime = Date.now();
            touchStartPos = {
                x: e.touches[0].clientX,
                y: e.touches[0].clientY
            };
            
            card.classList.add('touch-active');
        }, { passive: true });
        
        card.addEventListener('touchend', (e) => {
            const touchEndTime = Date.now();
            const touchDuration = touchEndTime - touchStartTime;
            
            card.classList.remove('touch-active');
            
            // If it's a quick tap, handle click
            if (touchDuration < 200) {
                this.handleCardTap(card, e);
            }
        }, { passive: true });
        
        card.addEventListener('touchcancel', () => {
            card.classList.remove('touch-active');
        }, { passive: true });
    }
    
    handleCardTap(card, event) {
        // Add ripple effect
        this.createRippleEffect(card, event);
        
        // Haptic feedback
        this.hapticFeedback('medium');
        
        // Expand card or navigate
        const detailsBtn = card.querySelector('.btn-details, .btn-details-mobile');
        if (detailsBtn) {
            // Small delay for visual feedback
            setTimeout(() => {
                detailsBtn.click();
            }, 150);
        }
    }
    
    handleLongPress(element, event) {
        // Haptic feedback for long press
        this.hapticFeedback('heavy');
        
        // Show context menu or additional options
        this.showContextMenu(element, event);
    }
    
    createRippleEffect(element, event) {
        const ripple = document.createElement('div');
        ripple.className = 'ripple-effect';
        
        const rect = element.getBoundingClientRect();
        const size = Math.max(rect.width, rect.height);
        const x = (event.changedTouches ? event.changedTouches[0].clientX : event.clientX) - rect.left - size / 2;
        const y = (event.changedTouches ? event.changedTouches[0].clientY : event.clientY) - rect.top - size / 2;
        
        ripple.style.cssText = `
            position: absolute;
            width: ${size}px;
            height: ${size}px;
            left: ${x}px;
            top: ${y}px;
            background: rgba(255,255,255,0.3);
            border-radius: 50%;
            transform: scale(0);
            animation: ripple 0.6s ease-out;
            pointer-events: none;
            z-index: 10;
        `;
        
        element.style.position = 'relative';
        element.style.overflow = 'hidden';
        element.appendChild(ripple);
        
        setTimeout(() => {
            ripple.remove();
        }, 600);
    }
    
    /**
     * Setup Swipe Gestures
     */
    setupSwipeGestures() {
        // Swipe between predictions
        const predictionsContainer = document.querySelector('.predictions-grid');
        if (predictionsContainer) {
            this.setupPredictionSwipe(predictionsContainer);
        }
        
        // Swipe to open/close filters
        const filtersContainer = document.querySelector('.filters-mobile');
        if (filtersContainer) {
            this.setupFilterSwipe(filtersContainer);
        }
    }
    
    setupPredictionSwipe(container) {
        container.addEventListener('touchstart', (e) => {
            this.touchStartX = e.touches[0].clientX;
            this.touchStartY = e.touches[0].clientY;
        }, { passive: true });
        
        container.addEventListener('touchend', (e) => {
            this.touchEndX = e.changedTouches[0].clientX;
            this.touchEndY = e.changedTouches[0].clientY;
            
            this.handlePredictionSwipe();
        }, { passive: true });
    }
    
    handlePredictionSwipe() {
        const deltaX = this.touchEndX - this.touchStartX;
        const deltaY = this.touchEndY - this.touchStartY;
        
        // Check if it's a horizontal swipe
        if (Math.abs(deltaX) > Math.abs(deltaY) && Math.abs(deltaX) > this.swipeThreshold) {
            if (deltaX > 0) {
                // Swipe right - previous page
                this.handleSwipeRight();
            } else {
                // Swipe left - next page
                this.handleSwipeLeft();
            }
        }
    }
    
    handleSwipeRight() {
        // Load previous predictions or navigate back
        this.hapticFeedback('medium');
        console.log('Swipe right - Previous');
        // Implement navigation logic
    }
    
    handleSwipeLeft() {
        // Load next predictions or navigate forward
        this.hapticFeedback('medium');
        console.log('Swipe left - Next');
        // Implement navigation logic
    }
    
    /**
     * Setup Pull to Refresh
     */
    setupPullToRefresh() {
        let startY = 0;
        let currentY = 0;
        let isPulling = false;
        let pullDistance = 0;
        const threshold = 100;
        
        // Create pull to refresh indicator
        const refreshIndicator = document.createElement('div');
        refreshIndicator.className = 'pull-refresh-indicator';
        refreshIndicator.innerHTML = `
            <div class="refresh-spinner"></div>
            <span class="refresh-text">Pull to refresh</span>
        `;
        
        document.body.insertBefore(refreshIndicator, document.body.firstChild);
        
        document.addEventListener('touchstart', (e) => {
            if (window.scrollY === 0) {
                startY = e.touches[0].clientY;
                isPulling = true;
            }
        }, { passive: true });
        
        document.addEventListener('touchmove', (e) => {
            if (!isPulling) return;
            
            currentY = e.touches[0].clientY;
            pullDistance = currentY - startY;
            
            if (pullDistance > 0 && window.scrollY === 0) {
                e.preventDefault();
                
                const opacity = Math.min(pullDistance / threshold, 1);
                const scale = Math.min(pullDistance / threshold, 1);
                
                refreshIndicator.style.opacity = opacity;
                refreshIndicator.style.transform = `translateY(${Math.min(pullDistance - 50, 50)}px) scale(${scale})`;
                
                if (pullDistance > threshold) {
                    refreshIndicator.querySelector('.refresh-text').textContent = 'Release to refresh';
                    this.hapticFeedback('medium');
                } else {
                    refreshIndicator.querySelector('.refresh-text').textContent = 'Pull to refresh';
                }
            }
        }, { passive: false });
        
        document.addEventListener('touchend', () => {
            if (isPulling && pullDistance > threshold) {
                this.triggerRefresh();
            }
            
            isPulling = false;
            pullDistance = 0;
            refreshIndicator.style.opacity = '0';
            refreshIndicator.style.transform = 'translateY(-50px) scale(0.8)';
        }, { passive: true });
    }
    
    triggerRefresh() {
        const refreshIndicator = document.querySelector('.pull-refresh-indicator');
        const refreshText = refreshIndicator.querySelector('.refresh-text');
        
        refreshText.textContent = 'Refreshing...';
        refreshIndicator.querySelector('.refresh-spinner').classList.add('spinning');
        
        this.hapticFeedback('heavy');
        
        // Simulate refresh
        setTimeout(() => {
            refreshText.textContent = 'Updated!';
            this.hapticFeedback('light');
            
            setTimeout(() => {
                refreshIndicator.style.opacity = '0';
                refreshIndicator.style.transform = 'translateY(-50px) scale(0.8)';
                refreshIndicator.querySelector('.refresh-spinner').classList.remove('spinning');
            }, 1000);
            
            // Trigger actual refresh logic
            this.refreshPredictions();
        }, 2000);
    }
    
    refreshPredictions() {
        // Emit custom event for refresh
        const refreshEvent = new CustomEvent('iTipsterRefresh', {
            detail: { source: 'pullToRefresh' }
        });
        document.dispatchEvent(refreshEvent);
    }
    
    /**
     * Haptic Feedback
     */
    hapticFeedback(type = 'light') {
        if ('vibrate' in navigator) {
            switch (type) {
                case 'light':
                    navigator.vibrate(10);
                    break;
                case 'medium':
                    navigator.vibrate(20);
                    break;
                case 'heavy':
                    navigator.vibrate([30, 10, 30]);
                    break;
                default:
                    navigator.vibrate(10);
            }
        }
    }
    
    /**
     * Lazy Loading for Images and Content
     */
    setupLazyLoading() {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const element = entry.target;
                    
                    // Load images
                    if (element.tagName === 'IMG' && element.dataset.src) {
                        element.src = element.dataset.src;
                        element.classList.add('loaded');
                    }
                    
                    // Load content
                    if (element.classList.contains('lazy-content')) {
                        this.loadLazyContent(element);
                    }
                    
                    observer.unobserve(element);
                }
            });
        }, {
            rootMargin: '50px'
        });
        
        // Observe lazy elements
        document.querySelectorAll('img[data-src], .lazy-content').forEach(el => {
            observer.observe(el);
        });
    }
    
    loadLazyContent(element) {
        element.classList.add('loading');
        
        // Simulate content loading
        setTimeout(() => {
            element.classList.remove('loading');
            element.classList.add('loaded');
        }, 500);
    }
    
    /**
     * Orientation Change Handler
     */
    setupOrientationChange() {
        window.addEventListener('orientationchange', () => {
            // Small delay to ensure viewport changes are complete
            setTimeout(() => {
                this.handleOrientationChange();
            }, 100);
        });
    }
    
    handleOrientationChange() {
        // Recalculate layouts
        const cards = document.querySelectorAll('.prediction-card-mobile');
        cards.forEach(card => {
            card.style.height = 'auto';
        });
        
        // Close mobile menu if open
        this.closeMobileMenu();
        
        // Trigger custom event
        const orientationEvent = new CustomEvent('iTipsterOrientationChange', {
            detail: { orientation: screen.orientation ? screen.orientation.angle : window.orientation }
        });
        document.dispatchEvent(orientationEvent);
    }
    
    /**
     * Scroll Optimization
     */
    setupScrollOptimization() {
        let ticking = false;
        let lastScrollY = window.scrollY;
        
        const header = document.querySelector('.header-mobile');
        
        function updateScroll() {
            const currentScrollY = window.scrollY;
            const scrollDirection = currentScrollY > lastScrollY ? 'down' : 'up';
            
            // Hide/show header based on scroll direction
            if (header) {
                if (scrollDirection === 'down' && currentScrollY > 100) {
                    header.style.transform = 'translateY(-100%)';
                } else {
                    header.style.transform = 'translateY(0)';
                }
            }
            
            lastScrollY = currentScrollY;
            ticking = false;
        }
        
        function requestTick() {
            if (!ticking) {
                requestAnimationFrame(updateScroll);
                ticking = true;
            }
        }
        
        window.addEventListener('scroll', requestTick, { passive: true });
    }
    
    /**
     * Show Context Menu
     */
    showContextMenu(element, event) {
        // Create context menu
        const contextMenu = document.createElement('div');
        contextMenu.className = 'context-menu-mobile';
        contextMenu.innerHTML = `
            <div class="context-menu-item" data-action="favorite">
                ⭐ Add to Favorites
            </div>
            <div class="context-menu-item" data-action="share">
                📤 Share Prediction
            </div>
            <div class="context-menu-item" data-action="info">
                ℹ️ More Info
            </div>
        `;
        
        document.body.appendChild(contextMenu);
        
        // Position menu
        const rect = element.getBoundingClientRect();
        contextMenu.style.top = rect.bottom + 'px';
        contextMenu.style.left = rect.left + 'px';
        
        // Handle menu actions
        contextMenu.addEventListener('click', (e) => {
            const action = e.target.dataset.action;
            if (action) {
                this.handleContextAction(action, element);
                contextMenu.remove();
            }
        });
        
        // Remove menu when clicking outside
        setTimeout(() => {
            document.addEventListener('click', () => {
                contextMenu.remove();
            }, { once: true });
        }, 100);
    }
    
    handleContextAction(action, element) {
        switch (action) {
            case 'favorite':
                this.toggleFavorite(element);
                break;
            case 'share':
                this.sharePrediction(element);
                break;
            case 'info':
                this.showMoreInfo(element);
                break;
        }
    }
    
    toggleFavorite(element) {
        element.classList.toggle('favorited');
        this.hapticFeedback('light');
        
        const message = element.classList.contains('favorited') ? 
            'Added to favorites' : 'Removed from favorites';
        this.showToast(message);
    }
    
    sharePrediction(element) {
        if (navigator.share) {
            const teams = element.querySelector('.teams, .teams-mobile')?.textContent || 'Prediction';
            navigator.share({
                title: `iTipster Pro - ${teams}`,
                text: 'Check out this prediction from iTipster Pro!',
                url: window.location.href
            });
        } else {
            // Fallback - copy to clipboard
            navigator.clipboard.writeText(window.location.href);
            this.showToast('Link copied to clipboard');
        }
        this.hapticFeedback('medium');
    }
    
    showMoreInfo(element) {
        // Show additional prediction details
        const modal = this.createInfoModal(element);
        document.body.appendChild(modal);
    }
    
    createInfoModal(element) {
        const modal = document.createElement('div');
        modal.className = 'info-modal-mobile';
        modal.innerHTML = `
            <div class="modal-overlay-mobile"></div>
            <div class="modal-content-mobile">
                <div class="modal-header-mobile">
                    <h3>Prediction Details</h3>
                    <button class="modal-close-mobile">✕</button>
                </div>
                <div class="modal-body-mobile">
                    <p>Detailed prediction analysis would go here...</p>
                </div>
            </div>
        `;
        
        // Close modal functionality
        const closeBtn = modal.querySelector('.modal-close-mobile');
        const overlay = modal.querySelector('.modal-overlay-mobile');
        
        [closeBtn, overlay].forEach(el => {
            el.addEventListener('click', () => modal.remove());
        });
        
        return modal;
    }
    
    showToast(message, duration = 3000) {
        const toast = document.createElement('div');
        toast.className = 'toast-mobile';
        toast.textContent = message;
        
        document.body.appendChild(toast);
        
        // Animate in
        setTimeout(() => toast.classList.add('show'), 100);
        
        // Remove after duration
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        }, duration);
    }
}

// Auto-initialize when script loads
document.addEventListener('DOMContentLoaded', () => {
    window.iTipsterMobile = new iTipsterMobile();
});

// Additional CSS for mobile interactions
const mobileInteractionStyles = `
    <style>
    .touch-active {
        transform: scale(0.98) !important;
        opacity: 0.8 !important;
        transition: all 0.1s ease !important;
    }
    
    @keyframes ripple {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }
    
    .pull-refresh-indicator {
        position: fixed;
        top: -50px;
        left: 50%;
        transform: translateX(-50%) translateY(-50px) scale(0.8);
        background: rgba(255,255,255,0.9);
        backdrop-filter: blur(10px);
        padding: 15px 25px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
        opacity: 0;
        transition: all 0.3s ease;
        z-index: 1000;
        color: #333;
        font-weight: 600;
    }
    
    .refresh-spinner {
        width: 20px;
        height: 20px;
        border: 2px solid #ddd;
        border-top-color: #6366f1;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }
    
    .refresh-spinner.spinning {
        animation: spin 0.5s linear infinite;
    }
    
    .context-menu-mobile {
        position: fixed;
        background: rgba(0,0,0,0.9);
        backdrop-filter: blur(20px);
        border-radius: 12px;
        padding: 10px;
        z-index: 1000;
        min-width: 200px;
        animation: slideUpMobile 0.2s ease;
    }
    
    .context-menu-item {
        padding: 12px 16px;
        color: white;
        cursor: pointer;
        border-radius: 8px;
        transition: background 0.2s ease;
    }
    
    .context-menu-item:hover {
        background: rgba(255,255,255,0.1);
    }
    
    .info-modal-mobile {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 2000;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }
    
    .modal-overlay-mobile {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.8);
        backdrop-filter: blur(5px);
    }
    
    .modal-content-mobile {
        background: rgba(255,255,255,0.1);
        backdrop-filter: blur(20px);
        border-radius: 20px;
        padding: 0;
        width: 100%;
        max-width: 400px;
        position: relative;
        animation: slideUpMobile 0.3s ease;
        border: 1px solid rgba(255,255,255,0.2);
    }
    
    .modal-header-mobile {
        padding: 20px;
        border-bottom: 1px solid rgba(255,255,255,0.1);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .modal-header-mobile h3 {
        color: white;
        margin: 0;
    }
    
    .modal-close-mobile {
        background: none;
        border: none;
        color: white;
        font-size: 1.5rem;
        cursor: pointer;
        padding: 5px;
        border-radius: 50%;
        width: 35px;
        height: 35px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .modal-body-mobile {
        padding: 20px;
        color: white;
    }
    
    .toast-mobile {
        position: fixed;
        bottom: 100px;
        left: 50%;
        transform: translateX(-50%) translateY(100px);
        background: rgba(0,0,0,0.9);
        color: white;
        padding: 12px 20px;
        border-radius: 25px;
        font-weight: 600;
        z-index: 1500;
        opacity: 0;
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
    }
    
    .toast-mobile.show {
        opacity: 1;
        transform: translateX(-50%) translateY(0);
    }
    
    .header-mobile {
        transition: transform 0.3s ease;
    }
    
    img.loaded {
        opacity: 1;
        transition: opacity 0.3s ease;
    }
    
    .lazy-content.loading::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 20px;
        height: 20px;
        border: 2px solid rgba(255,255,255,0.3);
        border-top-color: #6366f1;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        transform: translate(-50%, -50%);
    }
    
    .favorited {
        border-color: #fbbf24 !important;
    }
    
    .favorited::before {
        content: '⭐';
        position: absolute;
        top: 10px;
        right: 10px;
        font-size: 1.2rem;
        z-index: 10;
    }
    
    @media (max-width: 768px) {
        .header-mobile {
            position: sticky;
            top: 0;
            z-index: 100;
        }
    }
    </style>
`;

// Inject styles
document.head.insertAdjacentHTML('beforeend', mobileInteractionStyles); 