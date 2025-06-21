<?php
/**
 * iTipster Pro - Design System Demo
 * Comprehensive showcase of Apple-inspired design system
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}
?>

<!DOCTYPE html>
<html lang="<?php echo get_locale(); ?>" data-theme="auto">
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo esc_html(get_bloginfo('name')); ?> - Design System Demo</title>
    <?php wp_head(); ?>
</head>
<body class="design-system-demo">
    
    <!-- Theme Toggle -->
    <div class="theme-toggle-container">
        <button class="theme-toggle" aria-label="Toggle theme">
            <span class="icon icon-sun"></span>
            <span class="icon icon-moon"></span>
        </button>
    </div>

    <!-- Header -->
    <header class="demo-header">
        <div class="container">
            <div class="demo-header-content">
                <h1 class="demo-title">iTipster Pro Design System</h1>
                <p class="demo-subtitle">Apple-inspired components and interactions</p>
            </div>
        </div>
    </header>

    <!-- Navigation -->
    <nav class="demo-nav">
        <div class="container">
            <ul class="demo-nav-list">
                <li><a href="#typography" class="demo-nav-link">Typography</a></li>
                <li><a href="#buttons" class="demo-nav-link">Buttons</a></li>
                <li><a href="#cards" class="demo-nav-link">Cards</a></li>
                <li><a href="#forms" class="demo-nav-link">Forms</a></li>
                <li><a href="#navigation" class="demo-nav-link">Navigation</a></li>
                <li><a href="#data-display" class="demo-nav-link">Data Display</a></li>
                <li><a href="#modals" class="demo-nav-link">Modals</a></li>
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="demo-main">
        <div class="container">
            
            <!-- Typography Section -->
            <section id="typography" class="demo-section">
                <h2 class="section-title">Typography</h2>
                <div class="demo-grid">
                    <div class="demo-card">
                        <h1 class="h1">Heading 1 - Main Title</h1>
                        <h2 class="h2">Heading 2 - Section Title</h2>
                        <h3 class="h3">Heading 3 - Subsection</h3>
                        <h4 class="h4">Heading 4 - Card Title</h4>
                        <h5 class="h5">Heading 5 - Small Title</h5>
                        <h6 class="h6">Heading 6 - Caption Title</h6>
                    </div>
                    <div class="demo-card">
                        <p class="text-body-large">Body Large - This is larger body text for important content.</p>
                        <p class="text-body">Body - This is standard body text for regular content.</p>
                        <p class="text-caption">Caption - This is smaller text for captions and metadata.</p>
                        <p class="text-button">Button Text - This is text used in buttons.</p>
                    </div>
                </div>
            </section>

            <!-- Buttons Section -->
            <section id="buttons" class="demo-section">
                <h2 class="section-title">Buttons</h2>
                <div class="demo-grid">
                    <div class="demo-card">
                        <h3 class="card-title">Button Variants</h3>
                        <div class="button-group">
                            <button class="btn btn-primary">Primary Button</button>
                            <button class="btn btn-secondary">Secondary Button</button>
                            <button class="btn btn-ghost">Ghost Button</button>
                            <button class="btn btn-destructive">Destructive Button</button>
                        </div>
                    </div>
                    <div class="demo-card">
                        <h3 class="card-title">Button Sizes</h3>
                        <div class="button-group">
                            <button class="btn btn-primary btn-sm">Small</button>
                            <button class="btn btn-primary">Default</button>
                            <button class="btn btn-primary btn-lg">Large</button>
                            <button class="btn btn-primary btn-xl">Extra Large</button>
                        </div>
                    </div>
                    <div class="demo-card">
                        <h3 class="card-title">Button States</h3>
                        <div class="button-group">
                            <button class="btn btn-primary">Normal</button>
                            <button class="btn btn-primary btn-loading">Loading</button>
                            <button class="btn btn-primary" disabled>Disabled</button>
                        </div>
                    </div>
                    <div class="demo-card">
                        <h3 class="card-title">Icon Buttons</h3>
                        <div class="button-group">
                            <button class="btn btn-primary btn-icon">
                                <span class="icon">+</span>
                            </button>
                            <button class="btn btn-secondary btn-icon">
                                <span class="icon">⚙️</span>
                            </button>
                            <button class="btn btn-ghost btn-icon">
                                <span class="icon">❤️</span>
                            </button>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Cards Section -->
            <section id="cards" class="demo-section">
                <h2 class="section-title">Cards</h2>
                <div class="demo-grid">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Basic Card</h3>
                            <p class="card-subtitle">Simple card with header and body</p>
                        </div>
                        <div class="card-body">
                            <p>This is a basic card component with clean design and subtle shadows.</p>
                        </div>
                    </div>
                    
                    <div class="card card-elevated">
                        <div class="card-body">
                            <h3 class="card-title">Elevated Card</h3>
                            <p>This card has enhanced shadows for more prominence.</p>
                        </div>
                    </div>
                    
                    <div class="card card-interactive">
                        <div class="card-body">
                            <h3 class="card-title">Interactive Card</h3>
                            <p>This card responds to hover and click interactions.</p>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-value">2,847</div>
                        <div class="stat-label">Total Predictions</div>
                        <div class="stat-change positive">
                            <span>↗</span>
                            +12.5%
                        </div>
                    </div>
                </div>
            </section>

            <!-- Forms Section -->
            <section id="forms" class="demo-section">
                <h2 class="section-title">Forms</h2>
                <div class="demo-grid">
                    <div class="demo-card">
                        <h3 class="card-title">Input Fields</h3>
                        <form class="demo-form">
                            <div class="form-group">
                                <label class="form-label" for="name">Full Name</label>
                                <input type="text" id="name" class="form-input" placeholder="Enter your full name">
                                <div class="form-helper">This is helper text for the input field.</div>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label" for="email">Email Address</label>
                                <input type="email" id="email" class="form-input" placeholder="Enter your email">
                                <div class="form-error">Please enter a valid email address.</div>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label" for="message">Message</label>
                                <textarea id="message" class="form-input form-textarea" placeholder="Enter your message"></textarea>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label" for="country">Country</label>
                                <select id="country" class="form-input form-select">
                                    <option value="">Select a country</option>
                                    <option value="gr">Greece</option>
                                    <option value="uk">United Kingdom</option>
                                    <option value="us">United States</option>
                                </select>
                            </div>
                        </form>
                    </div>
                    
                    <div class="demo-card">
                        <h3 class="card-title">Form Controls</h3>
                        <div class="form-group">
                            <div class="form-checkbox">
                                <input type="checkbox" id="newsletter">
                                <label for="newsletter">Subscribe to newsletter</label>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="form-radio">
                                <input type="radio" id="option1" name="options">
                                <label for="option1">Option 1</label>
                            </div>
                            <div class="form-radio">
                                <input type="radio" id="option2" name="options">
                                <label for="option2">Option 2</label>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="form-toggle">
                                <input type="checkbox" id="notifications">
                                <label for="notifications">Enable notifications</label>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Navigation Section -->
            <section id="navigation" class="demo-section">
                <h2 class="section-title">Navigation</h2>
                <div class="demo-grid">
                    <div class="demo-card">
                        <h3 class="card-title">Tab Navigation</h3>
                        <div class="tabs">
                            <button class="tab active" data-target="#tab1">Overview</button>
                            <button class="tab" data-target="#tab2">Analytics</button>
                            <button class="tab" data-target="#tab3">Settings</button>
                        </div>
                        <div class="tab-content active" id="tab1">
                            <p>This is the overview tab content.</p>
                        </div>
                        <div class="tab-content" id="tab2">
                            <p>This is the analytics tab content.</p>
                        </div>
                        <div class="tab-content" id="tab3">
                            <p>This is the settings tab content.</p>
                        </div>
                    </div>
                    
                    <div class="demo-card">
                        <h3 class="card-title">Breadcrumbs</h3>
                        <nav class="breadcrumbs">
                            <div class="breadcrumb-item">
                                <a href="#" class="breadcrumb-link">Home</a>
                            </div>
                            <div class="breadcrumb-item">
                                <a href="#" class="breadcrumb-link">Predictions</a>
                            </div>
                            <div class="breadcrumb-item">
                                <span class="breadcrumb-current">Design System</span>
                            </div>
                        </nav>
                    </div>
                    
                    <div class="demo-card">
                        <h3 class="card-title">Pagination</h3>
                        <div class="pagination">
                            <a href="#" class="pagination-item">Previous</a>
                            <a href="#" class="pagination-item">1</a>
                            <a href="#" class="pagination-item active">2</a>
                            <a href="#" class="pagination-item">3</a>
                            <a href="#" class="pagination-item">Next</a>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Data Display Section -->
            <section id="data-display" class="demo-section">
                <h2 class="section-title">Data Display</h2>
                <div class="demo-grid">
                    <div class="demo-card">
                        <h3 class="card-title">Table</h3>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Team</th>
                                        <th>League</th>
                                        <th>Points</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Olympiacos</td>
                                        <td>Super League</td>
                                        <td>85</td>
                                        <td><span class="badge badge-success">Active</span></td>
                                    </tr>
                                    <tr>
                                        <td>Panathinaikos</td>
                                        <td>Super League</td>
                                        <td>82</td>
                                        <td><span class="badge badge-warning">Pending</span></td>
                                    </tr>
                                    <tr>
                                        <td>AEK Athens</td>
                                        <td>Super League</td>
                                        <td>78</td>
                                        <td><span class="badge badge-primary">Active</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <div class="demo-card">
                        <h3 class="card-title">Progress Bars</h3>
                        <div class="progress-group">
                            <div class="progress">
                                <div class="progress-bar" style="width: 75%"></div>
                            </div>
                            <div class="progress progress-lg">
                                <div class="progress-bar" style="width: 60%"></div>
                            </div>
                            <div class="progress progress-sm">
                                <div class="progress-bar" style="width: 90%"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="demo-card">
                        <h3 class="card-title">Badges</h3>
                        <div class="badge-group">
                            <span class="badge badge-primary">Primary</span>
                            <span class="badge badge-success">Success</span>
                            <span class="badge badge-warning">Warning</span>
                            <span class="badge badge-danger">Danger</span>
                            <span class="badge badge-secondary">Secondary</span>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Modals Section -->
            <section id="modals" class="demo-section">
                <h2 class="section-title">Modals & Overlays</h2>
                <div class="demo-grid">
                    <div class="demo-card">
                        <h3 class="card-title">Modal Trigger</h3>
                        <button class="btn btn-primary" onclick="openDemoModal()">Open Modal</button>
                    </div>
                    
                    <div class="demo-card">
                        <h3 class="card-title">Notifications</h3>
                        <div class="notification-group">
                            <button class="btn btn-success" onclick="showNotification('success')">Success</button>
                            <button class="btn btn-danger" onclick="showNotification('error')">Error</button>
                            <button class="btn btn-warning" onclick="showNotification('warning')">Warning</button>
                            <button class="btn btn-primary" onclick="showNotification('info')">Info</button>
                        </div>
                    </div>
                </div>
            </section>

        </div>
    </main>

    <!-- Demo Modal -->
    <div class="modal-backdrop" id="demoModalBackdrop">
        <div class="modal modal-md" id="demoModal">
            <div class="modal-header">
                <h3 class="modal-title">Demo Modal</h3>
                <button class="modal-close" onclick="closeDemoModal()">×</button>
            </div>
            <div class="modal-body">
                <p>This is a demo modal showcasing the Apple-inspired design system.</p>
                <p>The modal includes backdrop blur, smooth animations, and clean typography.</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeDemoModal()">Cancel</button>
                <button class="btn btn-primary" onclick="closeDemoModal()">Confirm</button>
            </div>
        </div>
    </div>

    <!-- Notifications Container -->
    <div class="admin-notifications" id="notificationsContainer"></div>

    <!-- Footer -->
    <footer class="demo-footer">
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> iTipster Pro. All rights reserved.</p>
        </div>
    </footer>

    <script>
        // Demo Modal Functions
        function openDemoModal() {
            const backdrop = document.getElementById('demoModalBackdrop');
            const modal = document.getElementById('demoModal');
            
            backdrop.classList.add('active');
            setTimeout(() => {
                modal.classList.add('active');
            }, 10);
        }

        function closeDemoModal() {
            const backdrop = document.getElementById('demoModalBackdrop');
            const modal = document.getElementById('demoModal');
            
            modal.classList.remove('active');
            setTimeout(() => {
                backdrop.classList.remove('active');
            }, 300);
        }

        // Notification Functions
        function showNotification(type) {
            const container = document.getElementById('notificationsContainer');
            const notification = document.createElement('div');
            notification.className = `admin-notification ${type}`;
            
            const messages = {
                success: 'Operation completed successfully!',
                error: 'An error occurred. Please try again.',
                warning: 'Please review your input before proceeding.',
                info: 'Here is some helpful information.'
            };
            
            notification.innerHTML = `
                <div class="admin-notification-icon">${getNotificationIcon(type)}</div>
                <div class="admin-notification-content">
                    <div class="admin-notification-title">${type.charAt(0).toUpperCase() + type.slice(1)}</div>
                    <div class="admin-notification-message">${messages[type]}</div>
                </div>
                <button class="admin-notification-close" onclick="this.parentElement.remove()">×</button>
            `;
            
            container.appendChild(notification);
            
            // Show notification
            setTimeout(() => {
                notification.classList.add('show');
            }, 10);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                notification.classList.remove('show');
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.parentNode.removeChild(notification);
                    }
                }, 300);
            }, 5000);
        }

        function getNotificationIcon(type) {
            const icons = {
                success: '✓',
                error: '✕',
                warning: '⚠',
                info: 'ℹ'
            };
            return icons[type] || 'ℹ';
        }

        // Tab Navigation
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('.tab');
            const tabContents = document.querySelectorAll('.tab-content');
            
            tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    const target = this.dataset.target;
                    
                    // Update active tab
                    tabs.forEach(t => t.classList.remove('active'));
                    this.classList.add('active');
                    
                    // Update active content
                    tabContents.forEach(content => {
                        content.classList.remove('active');
                        if (content.id === target) {
                            content.classList.add('active');
                        }
                    });
                });
            });
        });
    </script>

    <?php wp_footer(); ?>
</body>
</html>

<style>
/* Demo-specific styles */
.design-system-demo {
    background-color: var(--color-primary-bg);
    color: var(--color-text-primary);
    line-height: var(--leading-relaxed);
}

.theme-toggle-container {
    position: fixed;
    top: var(--space-lg);
    right: var(--space-lg);
    z-index: var(--z-fixed);
}

.demo-header {
    background: var(--gradient-primary);
    color: var(--color-text-inverse);
    padding: var(--space-xxxl) 0;
    text-align: center;
}

.demo-title {
    font-size: var(--text-5xl);
    font-weight: var(--font-bold);
    margin-bottom: var(--space-sm);
}

.demo-subtitle {
    font-size: var(--text-lg);
    opacity: 0.9;
}

.demo-nav {
    background-color: var(--color-card-bg);
    border-bottom: 1px solid var(--color-border);
    position: sticky;
    top: 0;
    z-index: var(--z-sticky);
}

.demo-nav-list {
    display: flex;
    list-style: none;
    margin: 0;
    padding: 0;
    overflow-x: auto;
}

.demo-nav-link {
    display: block;
    padding: var(--space-md) var(--space-lg);
    color: var(--color-text-secondary);
    text-decoration: none;
    font-weight: var(--font-medium);
    transition: all var(--transition-normal);
    white-space: nowrap;
}

.demo-nav-link:hover {
    color: var(--color-text-primary);
    background-color: var(--color-secondary-bg);
}

.demo-main {
    padding: var(--space-xxxl) 0;
}

.demo-section {
    margin-bottom: var(--space-xxxl);
}

.section-title {
    font-size: var(--text-3xl);
    font-weight: var(--font-bold);
    margin-bottom: var(--space-xl);
    text-align: center;
}

.demo-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: var(--space-lg);
}

.demo-card {
    background-color: var(--color-card-bg);
    border: 1px solid var(--color-border);
    border-radius: var(--radius-lg);
    padding: var(--space-xl);
    box-shadow: var(--shadow-sm);
}

.card-title {
    font-size: var(--text-lg);
    font-weight: var(--font-semibold);
    margin-bottom: var(--space-lg);
    color: var(--color-text-primary);
}

.button-group {
    display: flex;
    flex-wrap: wrap;
    gap: var(--space-sm);
    margin-bottom: var(--space-lg);
}

.progress-group {
    display: flex;
    flex-direction: column;
    gap: var(--space-md);
}

.badge-group {
    display: flex;
    flex-wrap: wrap;
    gap: var(--space-sm);
}

.notification-group {
    display: flex;
    flex-wrap: wrap;
    gap: var(--space-sm);
}

.demo-form {
    display: flex;
    flex-direction: column;
    gap: var(--space-lg);
}

.demo-footer {
    background-color: var(--color-secondary-bg);
    border-top: 1px solid var(--color-border);
    padding: var(--space-xl) 0;
    text-align: center;
    color: var(--color-text-secondary);
}

/* Responsive Design */
@media (max-width: 768px) {
    .demo-title {
        font-size: var(--text-3xl);
    }
    
    .demo-nav-list {
        justify-content: flex-start;
    }
    
    .demo-grid {
        grid-template-columns: 1fr;
    }
    
    .button-group {
        flex-direction: column;
    }
    
    .theme-toggle-container {
        top: var(--space-sm);
        right: var(--space-sm);
    }
}
</style> 