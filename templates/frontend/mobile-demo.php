<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>iTipster Pro - Mobile Demo</title>
    <?php wp_head(); ?>
</head>
<body class="itipster-mobile-demo">

<div class="itipster-container">
    <!-- Mobile Demo Header -->
    <div class="mobile-demo-header">
        <h1>📱 Mobile Navigation Demo</h1>
        <p>Test all mobile features and interactions</p>
    </div>

    <!-- Mobile Features Demo -->
    <div class="mobile-features-demo">
        
        <!-- Touch Interactions Demo -->
        <div class="demo-section">
            <h2>👆 Touch Interactions</h2>
            <div class="demo-cards">
                <div class="demo-card touch-demo" data-demo="ripple">
                    <h3>Ripple Effect</h3>
                    <p>Tap to see ripple animation</p>
                </div>
                <div class="demo-card touch-demo" data-demo="longpress">
                    <h3>Long Press</h3>
                    <p>Long press for context menu</p>
                </div>
                <div class="demo-card touch-demo" data-demo="haptic">
                    <h3>Haptic Feedback</h3>
                    <p>Tap for vibration feedback</p>
                </div>
            </div>
        </div>

        <!-- Gestures Demo -->
        <div class="demo-section">
            <h2>🤏 Gestures</h2>
            <div class="gesture-demo">
                <div class="swipe-area">
                    <h3>Swipe Area</h3>
                    <p>Swipe left/right to navigate</p>
                    <div class="swipe-indicator">
                        <span>← Swipe →</span>
                    </div>
                </div>
                <div class="pull-refresh-demo">
                    <h3>Pull to Refresh</h3>
                    <p>Pull down from top to refresh</p>
                </div>
            </div>
        </div>

        <!-- Navigation Demo -->
        <div class="demo-section">
            <h2>🧭 Navigation</h2>
            <div class="nav-demo">
                <button class="btn-mobile" onclick="window.iTipsterMobile.openMobileMenu()">
                    📱 Open Mobile Menu
                </button>
                <button class="btn-mobile" onclick="window.iTipsterMobile.closeMobileMenu()">
                    ❌ Close Mobile Menu
                </button>
            </div>
        </div>

        <!-- Performance Demo -->
        <div class="demo-section">
            <h2>⚡ Performance</h2>
            <div class="performance-demo">
                <div class="lazy-content" data-content="This content loads lazily">
                    <h3>Lazy Loading</h3>
                    <p>Content loads as you scroll</p>
                </div>
                <div class="scroll-demo">
                    <h3>Scroll Optimization</h3>
                    <p>Header hides/shows on scroll</p>
                </div>
            </div>
        </div>

        <!-- Accessibility Demo -->
        <div class="demo-section">
            <h2>♿ Accessibility</h2>
            <div class="accessibility-demo">
                <button class="btn-mobile" aria-label="Accessible button">
                    Accessible Button
                </button>
                <div class="high-contrast-demo">
                    <h3>High Contrast Support</h3>
                    <p>Works with system preferences</p>
                </div>
            </div>
        </div>

    </div>

    <!-- Mobile Status -->
    <div class="mobile-status">
        <h3>📊 Mobile Status</h3>
        <div class="status-grid">
            <div class="status-item">
                <span class="status-label">Touch Support:</span>
                <span class="status-value" id="touch-support">Checking...</span>
            </div>
            <div class="status-item">
                <span class="status-label">Haptic Feedback:</span>
                <span class="status-value" id="haptic-support">Checking...</span>
            </div>
            <div class="status-item">
                <span class="status-label">Orientation:</span>
                <span class="status-value" id="orientation">Checking...</span>
            </div>
            <div class="status-item">
                <span class="status-label">Screen Size:</span>
                <span class="status-value" id="screen-size">Checking...</span>
            </div>
        </div>
    </div>

</div>

<style>
/* Mobile Demo Styles */
.itipster-mobile-demo {
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
    min-height: 100vh;
    color: white;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    padding: 20px 0;
}

.mobile-demo-header {
    text-align: center;
    margin-bottom: 40px;
    padding: 20px;
}

.mobile-demo-header h1 {
    font-size: 2rem;
    font-weight: 800;
    margin-bottom: 10px;
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.mobile-demo-header p {
    color: rgba(255,255,255,0.7);
    font-size: 1.1rem;
}

.demo-section {
    margin-bottom: 40px;
    padding: 20px;
}

.demo-section h2 {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 20px;
    color: #10b981;
}

.demo-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}

.demo-card {
    background: rgba(255,255,255,0.08);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255,255,255,0.15);
    border-radius: 16px;
    padding: 20px;
    text-align: center;
    transition: all 0.3s ease;
    cursor: pointer;
    position: relative;
    overflow: hidden;
}

.demo-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.3);
}

.demo-card h3 {
    font-size: 1.2rem;
    font-weight: 600;
    margin-bottom: 10px;
}

.demo-card p {
    color: rgba(255,255,255,0.7);
    font-size: 0.9rem;
}

.gesture-demo {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.swipe-area, .pull-refresh-demo {
    background: rgba(255,255,255,0.08);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255,255,255,0.15);
    border-radius: 16px;
    padding: 20px;
    text-align: center;
}

.swipe-indicator {
    margin-top: 15px;
    padding: 10px;
    background: rgba(99, 102, 241, 0.2);
    border-radius: 8px;
    font-weight: 600;
    color: #6366f1;
}

.nav-demo {
    display: flex;
    gap: 15px;
    justify-content: center;
    flex-wrap: wrap;
}

.performance-demo, .accessibility-demo {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.lazy-content, .scroll-demo, .high-contrast-demo {
    background: rgba(255,255,255,0.08);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255,255,255,0.15);
    border-radius: 16px;
    padding: 20px;
    text-align: center;
}

.mobile-status {
    background: rgba(255,255,255,0.08);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255,255,255,0.15);
    border-radius: 16px;
    padding: 20px;
    margin-top: 40px;
}

.mobile-status h3 {
    font-size: 1.3rem;
    font-weight: 600;
    margin-bottom: 20px;
    text-align: center;
}

.status-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
}

.status-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px;
    background: rgba(255,255,255,0.05);
    border-radius: 8px;
}

.status-label {
    font-weight: 500;
    color: rgba(255,255,255,0.8);
}

.status-value {
    font-weight: 600;
    color: #10b981;
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .gesture-demo, .performance-demo, .accessibility-demo {
        grid-template-columns: 1fr;
    }
    
    .nav-demo {
        flex-direction: column;
    }
    
    .demo-cards {
        grid-template-columns: 1fr;
    }
    
    .status-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
// Mobile Demo JavaScript
document.addEventListener('DOMContentLoaded', function() {
    
    // Check mobile features
    function checkMobileFeatures() {
        // Touch support
        const touchSupport = 'ontouchstart' in window || navigator.maxTouchPoints > 0;
        document.getElementById('touch-support').textContent = touchSupport ? '✅ Supported' : '❌ Not Supported';
        
        // Haptic feedback
        const hapticSupport = 'vibrate' in navigator;
        document.getElementById('haptic-support').textContent = hapticSupport ? '✅ Supported' : '❌ Not Supported';
        
        // Orientation
        const orientation = window.orientation || (screen.orientation ? screen.orientation.angle : 'Unknown');
        document.getElementById('orientation').textContent = orientation + '°';
        
        // Screen size
        const screenSize = `${window.innerWidth} × ${window.innerHeight}`;
        document.getElementById('screen-size').textContent = screenSize;
    }
    
    // Demo interactions
    document.querySelectorAll('.touch-demo').forEach(card => {
        card.addEventListener('click', function() {
            const demo = this.dataset.demo;
            
            switch(demo) {
                case 'ripple':
                    if (window.iTipsterMobile) {
                        window.iTipsterMobile.createRippleEffect(this, { clientX: 100, clientY: 100 });
                    }
                    break;
                case 'longpress':
                    alert('Long press detected! Context menu would appear here.');
                    break;
                case 'haptic':
                    if (window.iTipsterMobile) {
                        window.iTipsterMobile.hapticFeedback('medium');
                    }
                    break;
            }
        });
    });
    
    // Check features on load
    checkMobileFeatures();
    
    // Update on orientation change
    window.addEventListener('orientationchange', function() {
        setTimeout(checkMobileFeatures, 100);
    });
    
    // Update on resize
    window.addEventListener('resize', checkMobileFeatures);
    
    console.log('Mobile Demo loaded! Check the mobile navigation features.');
});
</script>

<?php wp_footer(); ?>
</body>
</html> 