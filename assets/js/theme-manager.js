/**
 * iTipster Pro - Theme Manager
 * Handles dark/light mode switching with system preference detection
 */

class ThemeManager {
    constructor() {
        this.theme = 'auto';
        this.systemTheme = 'light';
        this.currentTheme = 'light';
        this.transitioning = false;
        this.init();
    }

    /**
     * Initialize the theme manager
     */
    init() {
        this.loadTheme();
        this.detectSystemTheme();
        this.applyTheme();
        this.setupEventListeners();
        this.setupSystemThemeListener();
    }

    /**
     * Load theme from localStorage
     */
    loadTheme() {
        const savedTheme = localStorage.getItem('itipster-theme');
        if (savedTheme && ['light', 'dark', 'auto'].includes(savedTheme)) {
            this.theme = savedTheme;
        }
    }

    /**
     * Save theme to localStorage
     */
    saveTheme() {
        localStorage.setItem('itipster-theme', this.theme);
    }

    /**
     * Detect system theme preference
     */
    detectSystemTheme() {
        this.systemTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
    }

    /**
     * Setup system theme change listener
     */
    setupSystemThemeListener() {
        const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
        
        const handleChange = (e) => {
            this.systemTheme = e.matches ? 'dark' : 'light';
            if (this.theme === 'auto') {
                this.applyTheme();
            }
        };

        // Modern browsers
        if (mediaQuery.addEventListener) {
            mediaQuery.addEventListener('change', handleChange);
        } else {
            // Fallback for older browsers
            mediaQuery.addListener(handleChange);
        }
    }

    /**
     * Setup event listeners
     */
    setupEventListeners() {
        // Theme toggle buttons
        document.addEventListener('click', (e) => {
            if (e.target.matches('.theme-toggle')) {
                this.toggleTheme();
            }
            
            if (e.target.matches('.theme-option')) {
                const theme = e.target.dataset.theme;
                this.setTheme(theme);
            }
        });

        // Keyboard shortcuts
        document.addEventListener('keydown', (e) => {
            // Ctrl/Cmd + Shift + T to toggle theme
            if ((e.ctrlKey || e.metaKey) && e.shiftKey && e.key === 'T') {
                e.preventDefault();
                this.toggleTheme();
            }
        });

        // Prevent theme flash on page load
        document.addEventListener('DOMContentLoaded', () => {
            document.documentElement.classList.remove('theme-loading');
        });
    }

    /**
     * Set theme
     * @param {string} theme - 'light', 'dark', or 'auto'
     */
    setTheme(theme) {
        if (!['light', 'dark', 'auto'].includes(theme)) {
            console.warn('Invalid theme:', theme);
            return;
        }

        if (this.theme === theme) return;

        this.theme = theme;
        this.saveTheme();
        this.applyTheme();
        this.updateUI();
        this.dispatchThemeChangeEvent();
    }

    /**
     * Toggle between light and dark themes
     */
    toggleTheme() {
        if (this.theme === 'auto') {
            this.setTheme(this.systemTheme === 'light' ? 'dark' : 'light');
        } else if (this.theme === 'light') {
            this.setTheme('dark');
        } else {
            this.setTheme('light');
        }
    }

    /**
     * Apply the current theme to the document
     */
    applyTheme() {
        if (this.transitioning) return;

        const newTheme = this.getEffectiveTheme();
        
        if (newTheme === this.currentTheme) return;

        this.transitioning = true;

        // Add transition overlay
        this.addTransitionOverlay();

        // Update document attributes
        document.documentElement.setAttribute('data-theme', newTheme);
        document.documentElement.classList.add('theme-transition');

        // Update current theme
        this.currentTheme = newTheme;

        // Remove transition overlay after animation
        setTimeout(() => {
            this.removeTransitionOverlay();
            document.documentElement.classList.remove('theme-transition');
            this.transitioning = false;
        }, 300);
    }

    /**
     * Get the effective theme (resolves 'auto' to system theme)
     * @returns {string} 'light' or 'dark'
     */
    getEffectiveTheme() {
        if (this.theme === 'auto') {
            return this.systemTheme;
        }
        return this.theme;
    }

    /**
     * Add transition overlay
     */
    addTransitionOverlay() {
        const overlay = document.createElement('div');
        overlay.className = 'theme-transition-overlay';
        overlay.id = 'theme-transition-overlay';
        document.body.appendChild(overlay);

        // Trigger reflow
        overlay.offsetHeight;

        // Show overlay
        requestAnimationFrame(() => {
            overlay.classList.add('active');
        });
    }

    /**
     * Remove transition overlay
     */
    removeTransitionOverlay() {
        const overlay = document.getElementById('theme-transition-overlay');
        if (overlay) {
            overlay.classList.remove('active');
            setTimeout(() => {
                if (overlay.parentNode) {
                    overlay.parentNode.removeChild(overlay);
                }
            }, 300);
        }
    }

    /**
     * Update UI elements to reflect current theme
     */
    updateUI() {
        // Update theme toggle states
        const toggles = document.querySelectorAll('.theme-toggle');
        toggles.forEach(toggle => {
            const isDark = this.getEffectiveTheme() === 'dark';
            toggle.setAttribute('aria-checked', isDark);
        });

        // Update theme option states
        const options = document.querySelectorAll('.theme-option');
        options.forEach(option => {
            const theme = option.dataset.theme;
            if (theme === this.theme) {
                option.classList.add('active');
            } else {
                option.classList.remove('active');
            }
        });

        // Update theme context menus
        const menus = document.querySelectorAll('.theme-context-menu');
        menus.forEach(menu => {
            const isActive = menu.classList.contains('active');
            if (isActive) {
                this.closeThemeMenu(menu);
            }
        });

        // Update theme preference indicators
        const indicators = document.querySelectorAll('.theme-preference');
        indicators.forEach(indicator => {
            const icon = indicator.querySelector('.icon');
            if (icon) {
                icon.className = `icon theme-icon-${this.theme}`;
            }
            
            const text = indicator.querySelector('.text');
            if (text) {
                text.textContent = this.getThemeDisplayName(this.theme);
            }
        });
    }

    /**
     * Get display name for theme
     * @param {string} theme - Theme name
     * @returns {string} Display name
     */
    getThemeDisplayName(theme) {
        const names = {
            light: 'Light',
            dark: 'Dark',
            auto: 'Auto'
        };
        return names[theme] || theme;
    }

    /**
     * Close theme context menu
     * @param {HTMLElement} menu - Menu element
     */
    closeThemeMenu(menu) {
        menu.classList.remove('active');
    }

    /**
     * Show theme status notification
     * @param {string} message - Status message
     * @param {string} type - 'success', 'error', 'warning', 'info'
     */
    showStatus(message, type = 'info') {
        const statusBar = document.createElement('div');
        statusBar.className = `theme-status-bar theme-${type}`;
        statusBar.textContent = message;
        
        document.body.appendChild(statusBar);

        // Show status
        requestAnimationFrame(() => {
            statusBar.classList.add('show');
        });

        // Hide status after 3 seconds
        setTimeout(() => {
            statusBar.classList.remove('show');
            setTimeout(() => {
                if (statusBar.parentNode) {
                    statusBar.parentNode.removeChild(statusBar);
                }
            }, 300);
        }, 3000);
    }

    /**
     * Dispatch theme change event
     */
    dispatchThemeChangeEvent() {
        const event = new CustomEvent('themechange', {
            detail: {
                theme: this.theme,
                effectiveTheme: this.getEffectiveTheme(),
                systemTheme: this.systemTheme
            }
        });
        document.dispatchEvent(event);
    }

    /**
     * Get current theme information
     * @returns {Object} Theme information
     */
    getThemeInfo() {
        return {
            theme: this.theme,
            effectiveTheme: this.getEffectiveTheme(),
            systemTheme: this.systemTheme,
            transitioning: this.transitioning
        };
    }

    /**
     * Check if theme is supported
     * @param {string} theme - Theme to check
     * @returns {boolean} Whether theme is supported
     */
    isThemeSupported(theme) {
        return ['light', 'dark', 'auto'].includes(theme);
    }

    /**
     * Reset theme to default
     */
    resetTheme() {
        this.setTheme('auto');
    }

    /**
     * Export theme settings
     * @returns {Object} Theme settings
     */
    exportSettings() {
        return {
            theme: this.theme,
            timestamp: new Date().toISOString()
        };
    }

    /**
     * Import theme settings
     * @param {Object} settings - Theme settings
     */
    importSettings(settings) {
        if (settings && settings.theme && this.isThemeSupported(settings.theme)) {
            this.setTheme(settings.theme);
            return true;
        }
        return false;
    }
}

/**
 * Theme Context Menu Manager
 */
class ThemeContextMenu {
    constructor(themeManager) {
        this.themeManager = themeManager;
        this.activeMenu = null;
        this.setupEventListeners();
    }

    /**
     * Setup event listeners
     */
    setupEventListeners() {
        document.addEventListener('click', (e) => {
            // Close menu when clicking outside
            if (!e.target.closest('.theme-context-menu') && !e.target.closest('.theme-toggle')) {
                this.closeAllMenus();
            }
        });

        // Handle theme toggle clicks
        document.addEventListener('click', (e) => {
            if (e.target.matches('.theme-toggle')) {
                e.preventDefault();
                this.toggleMenu(e.target);
            }
        });

        // Handle escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.closeAllMenus();
            }
        });
    }

    /**
     * Toggle theme context menu
     * @param {HTMLElement} toggle - Toggle button
     */
    toggleMenu(toggle) {
        const menu = toggle.nextElementSibling;
        
        if (!menu || !menu.classList.contains('theme-context-menu')) {
            return;
        }

        if (menu.classList.contains('active')) {
            this.closeMenu(menu);
        } else {
            this.openMenu(menu, toggle);
        }
    }

    /**
     * Open theme context menu
     * @param {HTMLElement} menu - Menu element
     * @param {HTMLElement} toggle - Toggle button
     */
    openMenu(menu, toggle) {
        // Close other menus
        this.closeAllMenus();

        // Position menu
        this.positionMenu(menu, toggle);

        // Show menu
        menu.classList.add('active');
        this.activeMenu = menu;

        // Focus first option
        const firstOption = menu.querySelector('.theme-option');
        if (firstOption) {
            firstOption.focus();
        }
    }

    /**
     * Close theme context menu
     * @param {HTMLElement} menu - Menu element
     */
    closeMenu(menu) {
        menu.classList.remove('active');
        if (this.activeMenu === menu) {
            this.activeMenu = null;
        }
    }

    /**
     * Close all theme context menus
     */
    closeAllMenus() {
        const menus = document.querySelectorAll('.theme-context-menu.active');
        menus.forEach(menu => this.closeMenu(menu));
    }

    /**
     * Position menu relative to toggle button
     * @param {HTMLElement} menu - Menu element
     * @param {HTMLElement} toggle - Toggle button
     */
    positionMenu(menu, toggle) {
        const toggleRect = toggle.getBoundingClientRect();
        const menuRect = menu.getBoundingClientRect();
        
        // Calculate position
        let left = toggleRect.left;
        let top = toggleRect.bottom + 8;

        // Adjust for viewport boundaries
        if (left + menuRect.width > window.innerWidth) {
            left = window.innerWidth - menuRect.width - 16;
        }

        if (top + menuRect.height > window.innerHeight) {
            top = toggleRect.top - menuRect.height - 8;
        }

        // Apply position
        menu.style.left = `${left}px`;
        menu.style.top = `${top}px`;
    }
}

/**
 * Theme Animation Manager
 */
class ThemeAnimationManager {
    constructor() {
        this.setupAnimations();
    }

    /**
     * Setup theme animations
     */
    setupAnimations() {
        // Add animation classes to elements
        this.addAnimationClasses();

        // Setup intersection observer for animations
        this.setupIntersectionObserver();
    }

    /**
     * Add animation classes to elements
     */
    addAnimationClasses() {
        // Add fade-in animations to cards
        const cards = document.querySelectorAll('.card, .stat-card, .chart-card');
        cards.forEach((card, index) => {
            card.classList.add('theme-fade-in');
            card.style.animationDelay = `${index * 0.1}s`;
        });

        // Add slide-up animations to sections
        const sections = document.querySelectorAll('.dashboard-stats, .dashboard-charts, .admin-form-section');
        sections.forEach((section, index) => {
            section.classList.add('theme-slide-up');
            section.style.animationDelay = `${index * 0.2}s`;
        });
    }

    /**
     * Setup intersection observer for animations
     */
    setupIntersectionObserver() {
        if (!window.IntersectionObserver) return;

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-in');
                }
            });
        }, {
            threshold: 0.1,
            rootMargin: '50px'
        });

        // Observe animated elements
        const animatedElements = document.querySelectorAll('.theme-fade-in, .theme-slide-up');
        animatedElements.forEach(el => observer.observe(el));
    }

    /**
     * Trigger animation on element
     * @param {HTMLElement} element - Element to animate
     * @param {string} animation - Animation class
     */
    triggerAnimation(element, animation = 'theme-fade-in') {
        element.classList.add(animation);
        
        // Remove animation class after animation completes
        setTimeout(() => {
            element.classList.remove(animation);
        }, 300);
    }
}

// Initialize theme manager when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    // Add loading class to prevent theme flash
    document.documentElement.classList.add('theme-loading');

    // Initialize theme manager
    window.themeManager = new ThemeManager();
    
    // Initialize context menu manager
    window.themeContextMenu = new ThemeContextMenu(window.themeManager);
    
    // Initialize animation manager
    window.themeAnimationManager = new ThemeAnimationManager();

    // Remove loading class
    setTimeout(() => {
        document.documentElement.classList.remove('theme-loading');
    }, 100);
});

// Export for module usage
if (typeof module !== 'undefined' && module.exports) {
    module.exports = { ThemeManager, ThemeContextMenu, ThemeAnimationManager };
} 