<?php
/**
 * Premium Homepage Template
 * Stunning hero section with animated statistics and interactive elements
 * 
 * @package ADPM\iTipsterPro
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<!-- ========================================
   HERO SECTION - WOW FACTOR
   ======================================== -->
<section class="hero-section">
    <div class="hero-background"></div>
    
    <div class="hero-content">
        <h1 class="hero-title">
            <span class="gradient-text">iTipster Pro</span>
            <br>
            <span class="hero-subtitle">Premium Sports Predictions Platform</span>
        </h1>
        
        <p class="hero-description">
            Experience the future of sports betting with AI-powered predictions, 
            real-time odds, and professional insights from top tipsters.
        </p>
        
        <div class="hero-actions">
            <a href="<?php echo esc_url(home_url('/predictions')); ?>" class="btn btn-premium btn-large">
                <span class="btn-icon">🎯</span>
                View Predictions
            </a>
            <a href="<?php echo esc_url(home_url('/register')); ?>" class="btn btn-secondary btn-large">
                <span class="btn-icon">🚀</span>
                Start Free Trial
            </a>
        </div>
    </div>
</section>

<!-- ========================================
   ANIMATED STATISTICS COUNTERS
   ======================================== -->
<section class="stats-section">
    <div class="container">
        <h2 class="section-title">Platform Statistics</h2>
        
        <div class="stats-grid">
            <div class="stat-card" data-aos="fade-up" data-aos-delay="100">
                <div class="stat-icon">📊</div>
                <div class="stat-number" data-target="15420">0</div>
                <div class="stat-label">Successful Predictions</div>
                <div class="stat-trend positive">+12.5% this month</div>
            </div>
            
            <div class="stat-card" data-aos="fade-up" data-aos-delay="200">
                <div class="stat-icon">💰</div>
                <div class="stat-number" data-target="2847500">0</div>
                <div class="stat-label">Total Winnings (€)</div>
                <div class="stat-trend positive">+8.3% this week</div>
            </div>
            
            <div class="stat-card" data-aos="fade-up" data-aos-delay="300">
                <div class="stat-icon">👥</div>
                <div class="stat-number" data-target="12580">0</div>
                <div class="stat-label">Active Users</div>
                <div class="stat-trend positive">+15.2% this month</div>
            </div>
            
            <div class="stat-card" data-aos="fade-up" data-aos-delay="400">
                <div class="stat-icon">🎯</div>
                <div class="stat-number" data-target="89">0</div>
                <div class="stat-label">Success Rate (%)</div>
                <div class="stat-trend positive">+2.1% this month</div>
            </div>
        </div>
    </div>
</section>

<!-- ========================================
   LIVE ODDS TICKER
   ======================================== -->
<section class="odds-ticker-section">
    <div class="odds-ticker">
        <div class="odds-ticker-content">
            <?php
            // Get live odds from API
            $live_odds = ADPM\iTipsterPro\ApiManager::get_live_odds();
            
            if (!empty($live_odds)) {
                foreach ($live_odds as $odd) {
                    ?>
                    <div class="ticker-item" data-match-id="<?php echo esc_attr($odd['match_id']); ?>">
                        <span class="ticker-teams"><?php echo esc_html($odd['home_team']); ?> vs <?php echo esc_html($odd['away_team']); ?></span>
                        <span class="ticker-odd"><?php echo esc_html($odd['odds']); ?></span>
                        <span class="ticker-time"><?php echo esc_html($odd['time']); ?></span>
                    </div>
                    <?php
                }
            } else {
                // Demo data
                $demo_odds = [
                    ['home' => 'Manchester City', 'away' => 'Liverpool', 'odds' => '2.15', 'time' => '20:45'],
                    ['home' => 'Real Madrid', 'away' => 'Barcelona', 'odds' => '1.85', 'time' => '21:00'],
                    ['home' => 'Bayern Munich', 'away' => 'Dortmund', 'odds' => '1.95', 'time' => '19:30'],
                    ['home' => 'PSG', 'away' => 'Marseille', 'odds' => '1.65', 'time' => '20:00'],
                    ['home' => 'Juventus', 'away' => 'Inter', 'odds' => '2.25', 'time' => '20:45']
                ];
                
                foreach ($demo_odds as $odd) {
                    ?>
                    <div class="ticker-item">
                        <span class="ticker-teams"><?php echo esc_html($odd['home']); ?> vs <?php echo esc_html($odd['away']); ?></span>
                        <span class="ticker-odd"><?php echo esc_html($odd['odds']); ?></span>
                        <span class="ticker-time"><?php echo esc_html($odd['time']); ?></span>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
    </div>
</section>

<!-- ========================================
   FEATURED PREDICTIONS
   ======================================== -->
<section class="featured-predictions">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Featured Predictions</h2>
            <p class="section-subtitle">Today's top picks from our expert tipsters</p>
        </div>
        
        <div class="predictions-grid">
            <?php
            // Get featured predictions
            $featured_predictions = ADPM\iTipsterPro\Predictions::get_featured_predictions();
            
            if (!empty($featured_predictions)) {
                foreach ($featured_predictions as $prediction) {
                    ?>
                    <div class="prediction-card" data-prediction-id="<?php echo esc_attr($prediction['id']); ?>" 
                         data-confidence="<?php echo esc_attr($prediction['confidence']); ?>"
                         data-league="<?php echo esc_attr($prediction['league']); ?>">
                        
                        <div class="prediction-header">
                            <div class="prediction-teams">
                                <?php echo esc_html($prediction['home_team']); ?> vs <?php echo esc_html($prediction['away_team']); ?>
                            </div>
                            <div class="prediction-confidence">
                                <div class="confidence-circle" data-confidence="<?php echo esc_attr($prediction['confidence']); ?>">
                                    <div class="confidence-text"><?php echo esc_html($prediction['confidence']); ?>%</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="prediction-details">
                            <div class="prediction-league"><?php echo esc_html($prediction['league']); ?></div>
                            <div class="prediction-match"><?php echo esc_html($prediction['match_time']); ?></div>
                            
                            <div class="prediction-odds">
                                <div class="odd-item">
                                    <div class="odd-label">Home</div>
                                    <div class="odd-value"><?php echo esc_html($prediction['home_odds']); ?></div>
                                </div>
                                <div class="odd-item">
                                    <div class="odd-label">Draw</div>
                                    <div class="odd-value"><?php echo esc_html($prediction['draw_odds']); ?></div>
                                </div>
                                <div class="odd-item">
                                    <div class="odd-label">Away</div>
                                    <div class="odd-value"><?php echo esc_html($prediction['away_odds']); ?></div>
                                </div>
                            </div>
                            
                            <div class="prediction-tip">
                                <strong>Tip:</strong> <?php echo esc_html($prediction['tip']); ?>
                            </div>
                        </div>
                        
                        <div class="prediction-actions">
                            <a href="<?php echo esc_url($prediction['url']); ?>" class="btn btn-primary">
                                View Details
                            </a>
                            <button class="btn btn-secondary favorite-btn" data-prediction-id="<?php echo esc_attr($prediction['id']); ?>">
                                <span class="btn-icon">❤️</span>
                                Favorite
                            </button>
                        </div>
                    </div>
                    <?php
                }
            } else {
                // Show loading skeleton
                for ($i = 0; $i < 6; $i++) {
                    ?>
                    <div class="prediction-card loading-skeleton">
                        <div class="skeleton-header">
                            <div class="skeleton-teams"></div>
                            <div class="skeleton-confidence"></div>
                        </div>
                        <div class="skeleton-details">
                            <div class="skeleton-league"></div>
                            <div class="skeleton-match"></div>
                            <div class="skeleton-odds">
                                <div class="skeleton-odd"></div>
                                <div class="skeleton-odd"></div>
                                <div class="skeleton-odd"></div>
                            </div>
                        </div>
                        <div class="skeleton-actions">
                            <div class="skeleton-btn"></div>
                            <div class="skeleton-btn"></div>
                        </div>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
        
        <div class="predictions-actions">
            <a href="<?php echo esc_url(home_url('/predictions')); ?>" class="btn btn-premium btn-large">
                View All Predictions
            </a>
        </div>
    </div>
</section>

<!-- ========================================
   SUCCESS STORIES CAROUSEL
   ======================================== -->
<section class="success-stories">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Success Stories</h2>
            <p class="section-subtitle">Real wins from our community members</p>
        </div>
        
        <div class="success-carousel">
            <div class="carousel-track">
                <?php
                $success_stories = [
                    [
                        'user' => 'Alex K.',
                        'profit' => '+€2,450',
                        'story' => 'Followed iTipster predictions for 3 months and turned €500 into €2,950! The AI analysis is incredible.',
                        'avatar' => 'A'
                    ],
                    [
                        'user' => 'Maria S.',
                        'profit' => '+€1,850',
                        'story' => 'Started with €200 and now I\'m consistently winning. The confidence ratings are spot on!',
                        'avatar' => 'M'
                    ],
                    [
                        'user' => 'Dimitris P.',
                        'profit' => '+€3,200',
                        'story' => 'Best betting platform I\'ve ever used. The live odds and real-time updates are game changers.',
                        'avatar' => 'D'
                    ],
                    [
                        'user' => 'Elena T.',
                        'profit' => '+€1,680',
                        'story' => 'The premium predictions are worth every euro. 85% success rate in my first month!',
                        'avatar' => 'E'
                    ]
                ];
                
                foreach ($success_stories as $story) {
                    ?>
                    <div class="success-story">
                        <div class="story-header">
                            <div class="story-avatar"><?php echo esc_html($story['avatar']); ?></div>
                            <div class="story-user"><?php echo esc_html($story['user']); ?></div>
                        </div>
                        <div class="story-profit"><?php echo esc_html($story['profit']); ?></div>
                        <div class="story-text"><?php echo esc_html($story['story']); ?></div>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
</section>

<!-- ========================================
   PREMIUM FEATURES
   ======================================== -->
<section class="premium-features">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Premium Features</h2>
            <p class="section-subtitle">Unlock the full potential of iTipster Pro</p>
        </div>
        
        <div class="features-grid">
            <div class="feature-card" data-aos="fade-up" data-aos-delay="100">
                <div class="feature-icon">🤖</div>
                <h3 class="feature-title">AI-Powered Analysis</h3>
                <p class="feature-description">
                    Advanced machine learning algorithms analyze thousands of data points 
                    to provide accurate predictions with confidence ratings.
                </p>
            </div>
            
            <div class="feature-card" data-aos="fade-up" data-aos-delay="200">
                <div class="feature-icon">⚡</div>
                <h3 class="feature-title">Real-Time Updates</h3>
                <p class="feature-description">
                    Live odds updates, injury news, and team changes delivered instantly 
                    to keep you ahead of the game.
                </p>
            </div>
            
            <div class="feature-card" data-aos="fade-up" data-aos-delay="300">
                <div class="feature-icon">📱</div>
                <h3 class="feature-title">Mobile Optimized</h3>
                <p class="feature-description">
                    Fully responsive design with PWA support for seamless betting 
                    experience on any device.
                </p>
            </div>
            
            <div class="feature-card" data-aos="fade-up" data-aos-delay="400">
                <div class="feature-icon">🎯</div>
                <h3 class="feature-title">Expert Tipsters</h3>
                <p class="feature-description">
                    Access predictions from professional tipsters with proven track records 
                    and detailed analysis.
                </p>
            </div>
            
            <div class="feature-card" data-aos="fade-up" data-aos-delay="500">
                <div class="feature-icon">📊</div>
                <h3 class="feature-title">Advanced Analytics</h3>
                <p class="feature-description">
                    Comprehensive statistics, performance tracking, and detailed 
                    insights to improve your betting strategy.
                </p>
            </div>
            
            <div class="feature-card" data-aos="fade-up" data-aos-delay="600">
                <div class="feature-icon">🔒</div>
                <h3 class="feature-title">Secure & Reliable</h3>
                <p class="feature-description">
                    Bank-level security, encrypted data transmission, and 99.9% 
                    uptime guarantee for peace of mind.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- ========================================
   PREMIUM CALL-TO-ACTION
   ======================================== -->
<section class="cta-section">
    <div class="container">
        <div class="cta-content">
            <h2 class="cta-title">Ready to Start Winning?</h2>
            <p class="cta-description">
                Join thousands of successful bettors who trust iTipster Pro for their 
                sports predictions. Start your winning journey today!
            </p>
            
            <div class="cta-buttons">
                <a href="<?php echo esc_url(home_url('/register')); ?>" class="btn btn-premium btn-large">
                    <span class="btn-icon">🚀</span>
                    Start Free Trial
                </a>
                <a href="<?php echo esc_url(home_url('/pricing')); ?>" class="btn btn-secondary btn-large">
                    <span class="btn-icon">💎</span>
                    View Pricing
                </a>
            </div>
            
            <div class="cta-features">
                <div class="cta-feature">
                    <span class="feature-icon">✅</span>
                    <span>7-Day Free Trial</span>
                </div>
                <div class="cta-feature">
                    <span class="feature-icon">✅</span>
                    <span>No Credit Card Required</span>
                </div>
                <div class="cta-feature">
                    <span class="feature-icon">✅</span>
                    <span>Cancel Anytime</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ========================================
   USER ACTIVITY FEED
   ======================================== -->
<section class="activity-feed-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Live Community Activity</h2>
            <p class="section-subtitle">See what's happening in real-time</p>
        </div>
        
        <div class="user-activity-feed">
            <div class="activity-item">
                <div class="activity-avatar">J</div>
                <div class="activity-content">
                    <div>John D. just won €450 on Manchester City vs Liverpool</div>
                    <div class="activity-time">2 minutes ago</div>
                </div>
            </div>
            
            <div class="activity-item">
                <div class="activity-avatar">S</div>
                <div class="activity-content">
                    <div>Sarah M. placed a bet on Real Madrid with 92% confidence</div>
                    <div class="activity-time">5 minutes ago</div>
                </div>
            </div>
            
            <div class="activity-item">
                <div class="activity-avatar">M</div>
                <div class="activity-content">
                    <div>Mike R. reached Level 5 and unlocked premium features</div>
                    <div class="activity-time">8 minutes ago</div>
                </div>
            </div>
            
            <div class="activity-item">
                <div class="activity-avatar">E</div>
                <div class="activity-content">
                    <div>Elena K. earned the "Winning Streak" achievement</div>
                    <div class="activity-time">12 minutes ago</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ========================================
   FILTER PANEL (HIDDEN BY DEFAULT)
   ======================================== -->
<div class="filter-panel" id="filterPanel">
    <div class="filter-header">
        <h3 class="filter-title">Advanced Filters</h3>
        <button class="filter-close" id="filterClose">&times;</button>
    </div>
    
    <div class="filter-content">
        <div class="filter-section">
            <label class="filter-label">Confidence Level</label>
            <div class="slider-container">
                <input type="range" class="slider" id="confidenceSlider" min="50" max="100" value="70" data-filter="confidence">
                <span class="slider-value">70%</span>
            </div>
        </div>
        
        <div class="filter-section">
            <label class="filter-label">League</label>
            <div class="filter-options">
                <div class="filter-option" data-filter="league" data-value="premier-league">Premier League</div>
                <div class="filter-option" data-filter="league" data-value="la-liga">La Liga</div>
                <div class="filter-option" data-filter="league" data-value="bundesliga">Bundesliga</div>
                <div class="filter-option" data-filter="league" data-value="serie-a">Serie A</div>
                <div class="filter-option" data-filter="league" data-value="ligue-1">Ligue 1</div>
            </div>
        </div>
        
        <div class="filter-section">
            <label class="filter-label">Betting Market</label>
            <div class="filter-options">
                <div class="filter-option" data-filter="market" data-value="match-winner">Match Winner</div>
                <div class="filter-option" data-filter="market" data-value="both-teams-score">Both Teams Score</div>
                <div class="filter-option" data-filter="market" data-value="over-under">Over/Under</div>
                <div class="filter-option" data-filter="market" data-value="correct-score">Correct Score</div>
            </div>
        </div>
        
        <div class="filter-section">
            <label class="filter-label">Premium Only</label>
            <div class="toggle-switch">
                <input type="checkbox" id="premiumToggle" data-filter="premium">
                <span class="toggle-slider"></span>
            </div>
        </div>
        
        <div class="filter-actions">
            <button class="btn btn-primary" id="applyFilters">Apply Filters</button>
            <button class="btn btn-secondary" id="resetFilters">Reset</button>
        </div>
    </div>
</div>

<!-- ========================================
   FILTER TOGGLE BUTTON
   ======================================== -->
<button class="filter-toggle" id="filterToggle">
    <span class="filter-icon">🔍</span>
    <span class="filter-text">Filters</span>
</button>

<?php
// Enqueue premium frontend assets
wp_enqueue_style('itipster-premium-frontend', ITIPSTER_PRO_PLUGIN_URL . 'assets/css/premium-frontend.css', array(), ITIPSTER_PRO_VERSION);
wp_enqueue_script('itipster-premium-frontend', ITIPSTER_PRO_PLUGIN_URL . 'assets/js/premium-frontend.js', array('jquery'), ITIPSTER_PRO_VERSION, true);

// Localize script with AJAX URL and nonce
wp_localize_script('itipster-premium-frontend', 'itipsterPro', array(
    'ajaxUrl' => admin_url('admin-ajax.php'),
    'nonce' => wp_create_nonce('itipster_pro_nonce'),
    'pluginUrl' => ITIPSTER_PRO_PLUGIN_URL
));

get_footer();
?> 