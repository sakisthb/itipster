/**
 * iTipster Pro - UI Interactions
 * Smooth animations, micro-interactions, and Apple-inspired interactions
 */

class UIInteractions {
    constructor() {
        this.isAnimating = false;
        this.touchStartY = 0;
        this.touchStartX = 0;
        this.init();
    }

    /**
     * Initialize UI interactions
     */
    init() {
        this.setupButtonInteractions();
        this.setupCardInteractions();
        this.setupFormInteractions();
        this.setupNavigationInteractions();
        this.setupScrollInteractions();
        this.setupTouchInteractions();
        this.setupKeyboardInteractions();
        this.setupHoverEffects();
        this.setupLoadingStates();
        this.setupParallaxEffects();
    }

    /**
     * Setup button interactions
     */
    setupButtonInteractions() {
        document.addEventListener('click', (e) => {
            const button = e.target.closest('.btn');
            if (button && !button.disabled) {
                this.animateButtonPress(button);
            }
        });

        // Button hover effects
        document.addEventListener('mouseenter', (e) => {
            if (e.target.matches('.btn')) {
                this.addButtonHoverEffect(e.target);
            }
        });

        document.addEventListener('mouseleave', (e) => {
            if (e.target.matches('.btn')) {
                this.removeButtonHoverEffect(e.target);
            }
        });
    }

    /**
     * Animate button press
     * @param {HTMLElement} button - Button element
     */
    animateButtonPress(button) {
        if (this.isAnimating) return;

        this.isAnimating = true;
        
        // Add press effect
        button.style.transform = 'scale(0.95)';
        button.style.transition = 'transform 0.1s ease';

        // Remove press effect
        setTimeout(() => {
            button.style.transform = '';
            button.style.transition = '';
            this.isAnimating = false;
        }, 100);

        // Add ripple effect
        this.createRippleEffect(button, event);
    }

    /**
     * Create ripple effect
     * @param {HTMLElement} button - Button element
     * @param {Event} event - Click event
     */
    createRippleEffect(button, event) {
        const ripple = document.createElement('span');
        const rect = button.getBoundingClientRect();
        const size = Math.max(rect.width, rect.height);
        const x = event.clientX - rect.left - size / 2;
        const y = event.clientY - rect.top - size / 2;

        ripple.style.cssText = `
            position: absolute;
            width: ${size}px;
            height: ${size}px;
            left: ${x}px;
            top: ${y}px;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            transform: scale(0);
            animation: ripple 0.6s linear;
            pointer-events: none;
        `;

        button.style.position = 'relative';
        button.style.overflow = 'hidden';
        button.appendChild(ripple);

        setTimeout(() => {
            ripple.remove();
        }, 600);
    }

    /**
     * Add button hover effect
     * @param {HTMLElement} button - Button element
     */
    addButtonHoverEffect(button) {
        button.style.transform = 'translateY(-2px)';
        button.style.boxShadow = 'var(--shadow-lg)';
    }

    /**
     * Remove button hover effect
     * @param {HTMLElement} button - Button element
     */
    removeButtonHoverEffect(button) {
        button.style.transform = '';
        button.style.boxShadow = '';
    }

    /**
     * Setup card interactions
     */
    setupCardInteractions() {
        // Card hover effects
        document.addEventListener('mouseenter', (e) => {
            if (e.target.matches('.card')) {
                this.addCardHoverEffect(e.target);
            }
        });

        document.addEventListener('mouseleave', (e) => {
            if (e.target.matches('.card')) {
                this.removeCardHoverEffect(e.target);
            }
        });

        // Interactive cards
        document.addEventListener('click', (e) => {
            const card = e.target.closest('.card-interactive');
            if (card) {
                this.animateCardClick(card);
            }
        });
    }

    /**
     * Add card hover effect
     * @param {HTMLElement} card - Card element
     */
    addCardHoverEffect(card) {
        card.style.transform = 'translateY(-4px) scale(1.02)';
        card.style.boxShadow = 'var(--shadow-xl)';
        card.style.transition = 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
    }

    /**
     * Remove card hover effect
     * @param {HTMLElement} card - Card element
     */
    removeCardHoverEffect(card) {
        card.style.transform = '';
        card.style.boxShadow = '';
    }

    /**
     * Animate card click
     * @param {HTMLElement} card - Card element
     */
    animateCardClick(card) {
        card.style.transform = 'scale(0.98)';
        setTimeout(() => {
            card.style.transform = '';
        }, 150);
    }

    /**
     * Setup form interactions
     */
    setupFormInteractions() {
        // Floating label animations
        document.addEventListener('focusin', (e) => {
            if (e.target.matches('.form-input')) {
                this.animateInputFocus(e.target);
            }
        });

        document.addEventListener('focusout', (e) => {
            if (e.target.matches('.form-input')) {
                this.animateInputBlur(e.target);
            }
        });

        // Form validation animations
        document.addEventListener('input', (e) => {
            if (e.target.matches('.form-input')) {
                this.validateInput(e.target);
            }
        });
    }

    /**
     * Animate input focus
     * @param {HTMLElement} input - Input element
     */
    animateInputFocus(input) {
        const label = input.nextElementSibling;
        if (label && label.classList.contains('form-label')) {
            label.style.color = 'var(--color-accent-blue)';
            label.style.transform = 'scale(0.85) translateY(-8px)';
        }

        input.style.borderColor = 'var(--color-accent-blue)';
        input.style.boxShadow = '0 0 0 3px rgba(0, 122, 255, 0.1)';
    }

    /**
     * Animate input blur
     * @param {HTMLElement} input - Input element
     */
    animateInputBlur(input) {
        const label = input.nextElementSibling;
        if (label && label.classList.contains('form-label')) {
            if (!input.value) {
                label.style.color = 'var(--color-text-secondary)';
                label.style.transform = '';
            }
        }

        input.style.borderColor = '';
        input.style.boxShadow = '';
    }

    /**
     * Validate input
     * @param {HTMLElement} input - Input element
     */
    validateInput(input) {
        const isValid = input.checkValidity();
        const errorClass = 'error';
        
        if (!isValid && input.value) {
            input.classList.add(errorClass);
            this.shakeElement(input);
        } else {
            input.classList.remove(errorClass);
        }
    }

    /**
     * Shake element animation
     * @param {HTMLElement} element - Element to shake
     */
    shakeElement(element) {
        element.style.animation = 'shake 0.5s ease-in-out';
        setTimeout(() => {
            element.style.animation = '';
        }, 500);
    }

    /**
     * Setup navigation interactions
     */
    setupNavigationInteractions() {
        // Tab navigation
        document.addEventListener('click', (e) => {
            if (e.target.matches('.tab')) {
                this.switchTab(e.target);
            }
        });

        // Mobile navigation
        document.addEventListener('click', (e) => {
            if (e.target.matches('.mobile-nav-toggle')) {
                this.toggleMobileNav();
            }
        });
    }

    /**
     * Switch tab
     * @param {HTMLElement} tab - Tab element
     */
    switchTab(tab) {
        const tabContainer = tab.closest('.tabs');
        const tabContent = document.querySelector(tab.dataset.target);
        
        if (!tabContainer || !tabContent) return;

        // Update active tab
        tabContainer.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
        tab.classList.add('active');

        // Update active content
        tabContainer.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
        tabContent.classList.add('active');

        // Animate content transition
        this.animateContentTransition(tabContent);
    }

    /**
     * Animate content transition
     * @param {HTMLElement} content - Content element
     */
    animateContentTransition(content) {
        content.style.opacity = '0';
        content.style.transform = 'translateY(20px)';
        
        requestAnimationFrame(() => {
            content.style.transition = 'all 0.3s ease';
            content.style.opacity = '1';
            content.style.transform = 'translateY(0)';
        });
    }

    /**
     * Toggle mobile navigation
     */
    toggleMobileNav() {
        const nav = document.querySelector('.mobile-nav');
        if (!nav) return;

        const isOpen = nav.classList.contains('active');
        
        if (isOpen) {
            nav.classList.remove('active');
            document.body.style.overflow = '';
        } else {
            nav.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
    }

    /**
     * Setup scroll interactions
     */
    setupScrollInteractions() {
        let ticking = false;

        const updateScrollEffects = () => {
            this.updateParallaxEffects();
            this.updateScrollProgress();
            this.updateStickyElements();
            ticking = false;
        };

        const requestTick = () => {
            if (!ticking) {
                requestAnimationFrame(updateScrollEffects);
                ticking = true;
            }
        };

        window.addEventListener('scroll', requestTick, { passive: true });
    }

    /**
     * Update parallax effects
     */
    updateParallaxEffects() {
        const parallaxElements = document.querySelectorAll('[data-parallax]');
        const scrollY = window.pageYOffset;

        parallaxElements.forEach(element => {
            const speed = parseFloat(element.dataset.parallax) || 0.5;
            const yPos = -(scrollY * speed);
            element.style.transform = `translateY(${yPos}px)`;
        });
    }

    /**
     * Update scroll progress
     */
    updateScrollProgress() {
        const progressBar = document.querySelector('.scroll-progress');
        if (!progressBar) return;

        const scrollTop = window.pageYOffset;
        const docHeight = document.documentElement.scrollHeight - window.innerHeight;
        const scrollPercent = (scrollTop / docHeight) * 100;

        progressBar.style.width = `${scrollPercent}%`;
    }

    /**
     * Update sticky elements
     */
    updateStickyElements() {
        const stickyElements = document.querySelectorAll('.sticky');
        const scrollY = window.pageYOffset;

        stickyElements.forEach(element => {
            const offset = element.offsetTop;
            const height = element.offsetHeight;
            
            if (scrollY >= offset) {
                element.classList.add('stuck');
            } else {
                element.classList.remove('stuck');
            }
        });
    }

    /**
     * Setup touch interactions
     */
    setupTouchInteractions() {
        // Touch start
        document.addEventListener('touchstart', (e) => {
            this.touchStartY = e.touches[0].clientY;
            this.touchStartX = e.touches[0].clientX;
        }, { passive: true });

        // Touch move
        document.addEventListener('touchmove', (e) => {
            if (e.target.closest('.scrollable')) return;

            const touchY = e.touches[0].clientY;
            const touchX = e.touches[0].clientX;
            const deltaY = touchY - this.touchStartY;
            const deltaX = touchX - this.touchStartX;

            // Prevent horizontal scroll on vertical gestures
            if (Math.abs(deltaY) > Math.abs(deltaX) && Math.abs(deltaY) > 10) {
                e.preventDefault();
            }
        }, { passive: false });

        // Swipe gestures
        document.addEventListener('touchend', (e) => {
            const touchEndY = e.changedTouches[0].clientY;
            const touchEndX = e.changedTouches[0].clientX;
            const deltaY = touchEndY - this.touchStartY;
            const deltaX = touchEndX - this.touchStartX;
            const minSwipeDistance = 50;

            if (Math.abs(deltaY) > minSwipeDistance) {
                if (deltaY > 0) {
                    this.handleSwipeDown(e.target);
                } else {
                    this.handleSwipeUp(e.target);
                }
            }

            if (Math.abs(deltaX) > minSwipeDistance) {
                if (deltaX > 0) {
                    this.handleSwipeRight(e.target);
                } else {
                    this.handleSwipeLeft(e.target);
                }
            }
        });
    }

    /**
     * Handle swipe down
     * @param {HTMLElement} element - Swiped element
     */
    handleSwipeDown(element) {
        // Pull to refresh or close modals
        if (element.closest('.modal')) {
            this.closeModal(element.closest('.modal'));
        }
    }

    /**
     * Handle swipe up
     * @param {HTMLElement} element - Swiped element
     */
    handleSwipeUp(element) {
        // Open modals or expand content
        const expandable = element.closest('.expandable');
        if (expandable) {
            this.expandElement(expandable);
        }
    }

    /**
     * Handle swipe right
     * @param {HTMLElement} element - Swiped element
     */
    handleSwipeRight(element) {
        // Navigate back or close panels
        const panel = element.closest('.panel');
        if (panel) {
            this.closePanel(panel);
        }
    }

    /**
     * Handle swipe left
     * @param {HTMLElement} element - Swiped element
     */
    handleSwipeLeft(element) {
        // Navigate forward or open panels
        const panel = element.closest('.panel');
        if (panel) {
            this.openPanel(panel);
        }
    }

    /**
     * Setup keyboard interactions
     */
    setupKeyboardInteractions() {
        document.addEventListener('keydown', (e) => {
            // Escape key
            if (e.key === 'Escape') {
                this.handleEscapeKey();
            }

            // Arrow keys for navigation
            if (e.key.startsWith('Arrow')) {
                this.handleArrowKeys(e);
            }

            // Enter key for form submission
            if (e.key === 'Enter' && e.target.matches('.form-input')) {
                this.handleEnterKey(e);
            }
        });
    }

    /**
     * Handle escape key
     */
    handleEscapeKey() {
        // Close modals
        const modal = document.querySelector('.modal.active');
        if (modal) {
            this.closeModal(modal);
        }

        // Close dropdowns
        const dropdown = document.querySelector('.dropdown.active');
        if (dropdown) {
            this.closeDropdown(dropdown);
        }
    }

    /**
     * Handle arrow keys
     * @param {KeyboardEvent} event - Keyboard event
     */
    handleArrowKeys(event) {
        const target = event.target;
        
        // Tab navigation
        if (target.matches('.tab')) {
            const tabs = Array.from(target.closest('.tabs').querySelectorAll('.tab'));
            const currentIndex = tabs.indexOf(target);
            
            if (event.key === 'ArrowRight' || event.key === 'ArrowDown') {
                const nextTab = tabs[currentIndex + 1] || tabs[0];
                nextTab.focus();
                this.switchTab(nextTab);
            } else if (event.key === 'ArrowLeft' || event.key === 'ArrowUp') {
                const prevTab = tabs[currentIndex - 1] || tabs[tabs.length - 1];
                prevTab.focus();
                this.switchTab(prevTab);
            }
        }
    }

    /**
     * Handle enter key
     * @param {KeyboardEvent} event - Keyboard event
     */
    handleEnterKey(event) {
        const form = event.target.closest('form');
        if (form) {
            const submitButton = form.querySelector('button[type="submit"]');
            if (submitButton) {
                submitButton.click();
            }
        }
    }

    /**
     * Setup hover effects
     */
    setupHoverEffects() {
        // Add hover effects to interactive elements
        const hoverElements = document.querySelectorAll('.btn, .card, .nav-item, .menu-item');
        
        hoverElements.forEach(element => {
            element.addEventListener('mouseenter', () => {
                this.addHoverEffect(element);
            });

            element.addEventListener('mouseleave', () => {
                this.removeHoverEffect(element);
            });
        });
    }

    /**
     * Add hover effect
     * @param {HTMLElement} element - Element to add hover effect to
     */
    addHoverEffect(element) {
        element.style.transform = 'scale(1.02)';
        element.style.transition = 'transform 0.2s ease';
    }

    /**
     * Remove hover effect
     * @param {HTMLElement} element - Element to remove hover effect from
     */
    removeHoverEffect(element) {
        element.style.transform = '';
    }

    /**
     * Setup loading states
     */
    setupLoadingStates() {
        // Add loading states to buttons
        document.addEventListener('click', (e) => {
            const button = e.target.closest('.btn');
            if (button && button.dataset.loading !== 'true') {
                this.addLoadingState(button);
            }
        });

        // Remove loading states when AJAX completes
        document.addEventListener('ajaxComplete', (e) => {
            const button = e.target.querySelector('.btn-loading');
            if (button) {
                this.removeLoadingState(button);
            }
        });
    }

    /**
     * Add loading state
     * @param {HTMLElement} button - Button element
     */
    addLoadingState(button) {
        button.dataset.loading = 'true';
        button.classList.add('btn-loading');
        button.disabled = true;
    }

    /**
     * Remove loading state
     * @param {HTMLElement} button - Button element
     */
    removeLoadingState(button) {
        button.dataset.loading = 'false';
        button.classList.remove('btn-loading');
        button.disabled = false;
    }

    /**
     * Setup parallax effects
     */
    setupParallaxEffects() {
        // Add parallax data attributes to elements
        const parallaxElements = document.querySelectorAll('.parallax');
        parallaxElements.forEach((element, index) => {
            element.dataset.parallax = 0.1 + (index * 0.05);
        });
    }

    /**
     * Close modal
     * @param {HTMLElement} modal - Modal element
     */
    closeModal(modal) {
        modal.classList.remove('active');
        const backdrop = modal.previousElementSibling;
        if (backdrop && backdrop.classList.contains('modal-backdrop')) {
            backdrop.classList.remove('active');
        }
    }

    /**
     * Close dropdown
     * @param {HTMLElement} dropdown - Dropdown element
     */
    closeDropdown(dropdown) {
        dropdown.classList.remove('active');
    }

    /**
     * Close panel
     * @param {HTMLElement} panel - Panel element
     */
    closePanel(panel) {
        panel.classList.remove('active');
    }

    /**
     * Open panel
     * @param {HTMLElement} panel - Panel element
     */
    openPanel(panel) {
        panel.classList.add('active');
    }

    /**
     * Expand element
     * @param {HTMLElement} element - Element to expand
     */
    expandElement(element) {
        element.classList.add('expanded');
    }
}

// Add CSS animations
const style = document.createElement('style');
style.textContent = `
    @keyframes ripple {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }

    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
        20%, 40%, 60%, 80% { transform: translateX(5px); }
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes slideInRight {
        from {
            opacity: 0;
            transform: translateX(30px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .animate-fade-in-up {
        animation: fadeInUp 0.6s ease-out;
    }

    .animate-slide-in-right {
        animation: slideInRight 0.6s ease-out;
    }

    .scroll-progress {
        position: fixed;
        top: 0;
        left: 0;
        width: 0;
        height: 3px;
        background: var(--gradient-primary);
        z-index: var(--z-fixed);
        transition: width 0.1s ease;
    }

    .sticky.stuck {
        position: fixed;
        top: 0;
        z-index: var(--z-sticky);
        box-shadow: var(--shadow-md);
    }
`;

document.head.appendChild(style);

// Initialize UI interactions when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    window.uiInteractions = new UIInteractions();
});

// Export for module usage
if (typeof module !== 'undefined' && module.exports) {
    module.exports = UIInteractions;
} 