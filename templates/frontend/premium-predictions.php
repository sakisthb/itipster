<?php
/**
 * Premium Predictions Page Template
 * Advanced filtering, interactive cards, and real-time updates
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
   PREDICTIONS HERO SECTION
   ======================================== -->
<section class="predictions-hero">
    <div class="container">
        <div class="hero-content">
            <h1 class="hero-title">Premium Predictions</h1>
            <p class="hero-subtitle">
                AI-powered predictions with confidence ratings and expert analysis
            </p>
            
            <div class="hero-stats">
                <div class="stat-item">
                    <div class="stat-number" data-target="89">0</div>
                    <div class="stat-label">Success Rate</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number" data-target="15420">0</div>
                    <div class="stat-label">Total Predictions</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number" data-target="2847500">0</div>
                    <div class="stat-label">Total Winnings (€)</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ========================================
   ADVANCED FILTERING SECTION
   ======================================== -->
<section class="filtering-section">
    <div class="container">
        <div class="filter-controls">
            <div class="filter-group">
                <label class="filter-label">Confidence Level</label>
                <div class="slider-container">
                    <input type="range" class="slider" id="confidenceSlider" min="50" max="100" value="70">
                    <span class="slider-value">70%</span>
                </div>
            </div>
            
            <div class="filter-group">
                <label class="filter-label">League</label>
                <div class="filter-options">
                    <div class="filter-option active" data-league="all">All Leagues</div>
                    <div class="filter-option" data-league="premier-league">Premier League</div>
                    <div class="filter-option" data-league="la-liga">La Liga</div>
                    <div class="filter-option" data-league="bundesliga">Bundesliga</div>
                    <div class="filter-option" data-league="serie-a">Serie A</div>
                    <div class="filter-option" data-league="ligue-1">Ligue 1</div>
                </div>
            </div>
            
            <div class="filter-group">
                <label class="filter-label">Betting Market</label>
                <div class="filter-options">
                    <div class="filter-option active" data-market="all">All Markets</div>
                    <div class="filter-option" data-market="match-winner">Match Winner</div>
                    <div class="filter-option" data-market="both-teams-score">Both Teams Score</div>
                    <div class="filter-option" data-market="over-under">Over/Under</div>
                    <div class="filter-option" data-market="correct-score">Correct Score</div>
                </div>
            </div>
            
            <div class="filter-group">
                <label class="filter-label">Time Range</label>
                <div class="filter-options">
                    <div class="filter-option active" data-time="today">Today</div>
                    <div class="filter-option" data-time="tomorrow">Tomorrow</div>
                    <div class="filter-option" data-time="week">This Week</div>
                    <div class="filter-option" data-time="month">This Month</div>
                </div>
            </div>
            
            <div class="filter-group">
                <label class="filter-label">Premium Only</label>
                <div class="toggle-switch">
                    <input type="checkbox" id="premiumToggle">
                    <span class="toggle-slider"></span>
                </div>
            </div>
        </div>
        
        <div class="filter-actions">
            <button class="btn btn-primary" id="applyFilters">Apply Filters</button>
            <button class="btn btn-secondary" id="resetFilters">Reset</button>
            <button class="btn btn-secondary" id="advancedFilters">
                <span class="btn-icon">🔍</span>
                Advanced
            </button>
        </div>
    </div>
</section>

<!-- ========================================
   PREDICTIONS GRID
   ======================================== -->
<section class="predictions-section">
    <div class="container">
        <div class="predictions-header">
            <div class="predictions-info">
                <h2 class="section-title">Live Predictions</h2>
                <p class="section-subtitle">
                    <span id="predictionsCount">0</span> predictions available
                </p>
            </div>
            
            <div class="predictions-sort">
                <label class="sort-label">Sort by:</label>
                <select class="sort-select" id="sortPredictions">
                    <option value="confidence">Confidence (High to Low)</option>
                    <option value="time">Match Time</option>
                    <option value="odds">Best Odds</option>
                    <option value="league">League</option>
                </select>
            </div>
        </div>
        
        <div class="predictions-grid" id="predictionsGrid">
            <?php
            // Get predictions from database
            $predictions = ADPM\iTipsterPro\Predictions::get_all_predictions();
            
            if (!empty($predictions)) {
                foreach ($predictions as $prediction) {
                    $confidence_class = $prediction['confidence'] >= 90 ? 'high-confidence' : 
                                      ($prediction['confidence'] >= 75 ? 'medium-confidence' : 'low-confidence');
                    ?>
                    <div class="prediction-card <?php echo esc_attr($confidence_class); ?>" 
                         data-prediction-id="<?php echo esc_attr($prediction['id']); ?>"
                         data-confidence="<?php echo esc_attr($prediction['confidence']); ?>"
                         data-league="<?php echo esc_attr($prediction['league']); ?>"
                         data-market="<?php echo esc_attr($prediction['market']); ?>"
                         data-time="<?php echo esc_attr($prediction['match_time']); ?>"
                         data-premium="<?php echo esc_attr($prediction['is_premium']); ?>">
                        
                        <div class="prediction-header">
                            <div class="prediction-teams">
                                <div class="team home-team">
                                    <span class="team-name"><?php echo esc_html($prediction['home_team']); ?></span>
                                    <span class="team-odds"><?php echo esc_html($prediction['home_odds']); ?></span>
                                </div>
                                <div class="match-vs">vs</div>
                                <div class="team away-team">
                                    <span class="team-name"><?php echo esc_html($prediction['away_team']); ?></span>
                                    <span class="team-odds"><?php echo esc_html($prediction['away_odds']); ?></span>
                                </div>
                            </div>
                            
                            <div class="prediction-confidence">
                                <div class="confidence-circle" data-confidence="<?php echo esc_attr($prediction['confidence']); ?>">
                                    <svg width="60" height="60">
                                        <circle class="background" cx="30" cy="30" r="26" stroke-width="4"/>
                                        <circle class="progress" cx="30" cy="30" r="26" stroke-width="4"/>
                                    </svg>
                                    <div class="confidence-text"><?php echo esc_html($prediction['confidence']); ?>%</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="prediction-details">
                            <div class="prediction-meta">
                                <span class="prediction-league"><?php echo esc_html($prediction['league']); ?></span>
                                <span class="prediction-time"><?php echo esc_html($prediction['match_time']); ?></span>
                                <?php if ($prediction['is_premium']): ?>
                                    <span class="premium-badge">💎 Premium</span>
                                <?php endif; ?>
                            </div>
                            
                            <div class="prediction-tip">
                                <div class="tip-label">Our Tip:</div>
                                <div class="tip-content"><?php echo esc_html($prediction['tip']); ?></div>
                            </div>
                            
                            <div class="prediction-reasoning">
                                <div class="reasoning-toggle">
                                    <span class="toggle-text">View Analysis</span>
                                    <span class="toggle-icon">▼</span>
                                </div>
                                <div class="reasoning-content" style="display: none;">
                                    <p><?php echo esc_html($prediction['reasoning']); ?></p>
                                    
                                    <div class="analysis-stats">
                                        <div class="stat">
                                            <span class="stat-label">Form</span>
                                            <span class="stat-value"><?php echo esc_html($prediction['home_form']); ?> - <?php echo esc_html($prediction['away_form']); ?></span>
                                        </div>
                                        <div class="stat">
                                            <span class="stat-label">H2H</span>
                                            <span class="stat-value"><?php echo esc_html($prediction['h2h_record']); ?></span>
                                        </div>
                                        <div class="stat">
                                            <span class="stat-label">Goals</span>
                                            <span class="stat-value"><?php echo esc_html($prediction['avg_goals']); ?> avg</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="prediction-actions">
                            <a href="<?php echo esc_url($prediction['url']); ?>" class="btn btn-primary">
                                <span class="btn-icon">👁️</span>
                                View Details
                            </a>
                            <button class="btn btn-secondary favorite-btn" data-prediction-id="<?php echo esc_attr($prediction['id']); ?>">
                                <span class="btn-icon">❤️</span>
                                <span class="btn-text">Favorite</span>
                            </button>
                            <button class="btn btn-secondary share-btn" data-prediction-id="<?php echo esc_attr($prediction['id']); ?>">
                                <span class="btn-icon">📤</span>
                                Share
                            </button>
                        </div>
                        
                        <?php if ($prediction['is_premium'] && !is_user_logged_in()): ?>
                            <div class="premium-overlay">
                                <div class="premium-content">
                                    <div class="premium-icon">💎</div>
                                    <h4>Premium Prediction</h4>
                                    <p>Upgrade to access this exclusive prediction</p>
                                    <a href="<?php echo esc_url(home_url('/register')); ?>" class="btn btn-premium">
                                        Upgrade Now
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php
                }
            } else {
                // Show empty state
                ?>
                <div class="empty-state">
                    <div class="empty-icon">🎯</div>
                    <h3>No Predictions Available</h3>
                    <p>Check back later for new predictions or adjust your filters.</p>
                    <button class="btn btn-primary" id="refreshPredictions">
                        <span class="btn-icon">🔄</span>
                        Refresh
                    </button>
                </div>
                <?php
            }
            ?>
        </div>
        
        <!-- Infinite Scroll Loader -->
        <div class="infinite-scroll-loader" id="infiniteLoader" style="display: none;">
            <div class="loader-spinner"></div>
            <p>Loading more predictions...</p>
        </div>
    </div>
</section>

<!-- ========================================
   USER DASHBOARD SECTION
   ======================================== -->
<?php if (is_user_logged_in()): ?>
<section class="user-dashboard">
    <div class="container">
        <div class="dashboard-header">
            <h2 class="section-title">Your Performance</h2>
            <div class="dashboard-actions">
                <button class="btn btn-secondary" id="exportData">
                    <span class="btn-icon">📊</span>
                    Export Data
                </button>
            </div>
        </div>
        
        <div class="dashboard-grid">
            <div class="dashboard-card">
                <div class="card-header">
                    <h3>Performance Overview</h3>
                </div>
                <div class="card-content">
                    <div class="performance-stats">
                        <div class="stat">
                            <div class="stat-number" data-target="67">0</div>
                            <div class="stat-label">Win Rate (%)</div>
                        </div>
                        <div class="stat">
                            <div class="stat-number" data-target="1240">0</div>
                            <div class="stat-label">Total Profit (€)</div>
                        </div>
                        <div class="stat">
                            <div class="stat-number" data-target="15">0</div>
                            <div class="stat-label">Current Streak</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="dashboard-card">
                <div class="card-header">
                    <h3>Recent Activity</h3>
                </div>
                <div class="card-content">
                    <div class="activity-timeline">
                        <div class="activity-item">
                            <div class="activity-icon win">✅</div>
                            <div class="activity-content">
                                <div class="activity-title">Won €45 on Manchester City</div>
                                <div class="activity-time">2 hours ago</div>
                            </div>
                        </div>
                        <div class="activity-item">
                            <div class="activity-icon loss">❌</div>
                            <div class="activity-content">
                                <div class="activity-title">Lost €20 on Liverpool</div>
                                <div class="activity-time">1 day ago</div>
                            </div>
                        </div>
                        <div class="activity-item">
                            <div class="activity-icon win">✅</div>
                            <div class="activity-content">
                                <div class="activity-title">Won €32 on Real Madrid</div>
                                <div class="activity-time">2 days ago</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="dashboard-card">
                <div class="card-header">
                    <h3>Achievements</h3>
                </div>
                <div class="card-content">
                    <div class="achievements-grid">
                        <div class="achievement-badge earned">
                            <span class="achievement-icon">🏆</span>
                            <span class="achievement-name">First Win</span>
                        </div>
                        <div class="achievement-badge earned">
                            <span class="achievement-icon">🔥</span>
                            <span class="achievement-name">5 Win Streak</span>
                        </div>
                        <div class="achievement-badge">
                            <span class="achievement-icon">💎</span>
                            <span class="achievement-name">Premium User</span>
                        </div>
                        <div class="achievement-badge">
                            <span class="achievement-icon">🎯</span>
                            <span class="achievement-name">90% Accuracy</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ========================================
   GAMIFICATION SECTION
   ======================================== -->
<section class="gamification-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Level Up Your Game</h2>
            <p class="section-subtitle">Earn points, unlock achievements, and climb the leaderboard</p>
        </div>
        
        <div class="gamification-grid">
            <div class="level-card">
                <div class="level-header">
                    <div class="level-icon">⭐</div>
                    <div class="level-info">
                        <h3>Level <?php echo is_user_logged_in() ? '5' : '1'; ?></h3>
                        <div class="level-progress">
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: 75%;"></div>
                            </div>
                            <span class="progress-text">750 / 1000 XP</span>
                        </div>
                    </div>
                </div>
                <div class="level-rewards">
                    <div class="reward-item">
                        <span class="reward-icon">🎯</span>
                        <span class="reward-text">Premium Predictions</span>
                    </div>
                    <div class="reward-item">
                        <span class="reward-icon">📊</span>
                        <span class="reward-text">Advanced Analytics</span>
                    </div>
                </div>
            </div>
            
            <div class="leaderboard-card">
                <div class="card-header">
                    <h3>Top Performers</h3>
                </div>
                <div class="leaderboard-list">
                    <div class="leaderboard-item">
                        <div class="rank">1</div>
                        <div class="user-info">
                            <div class="user-avatar">A</div>
                            <div class="user-details">
                                <div class="user-name">Alex K.</div>
                                <div class="user-stats">€2,450 profit</div>
                            </div>
                        </div>
                        <div class="user-level">Level 8</div>
                    </div>
                    <div class="leaderboard-item">
                        <div class="rank">2</div>
                        <div class="user-info">
                            <div class="user-avatar">M</div>
                            <div class="user-details">
                                <div class="user-name">Maria S.</div>
                                <div class="user-stats">€1,850 profit</div>
                            </div>
                        </div>
                        <div class="user-level">Level 6</div>
                    </div>
                    <div class="leaderboard-item">
                        <div class="rank">3</div>
                        <div class="user-info">
                            <div class="user-avatar">D</div>
                            <div class="user-details">
                                <div class="user-name">Dimitris P.</div>
                                <div class="user-stats">€1,680 profit</div>
                            </div>
                        </div>
                        <div class="user-level">Level 5</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ========================================
   SHARE MODAL
   ======================================== -->
<div class="share-modal" id="shareModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Share Prediction</h3>
            <button class="modal-close" id="shareModalClose">&times;</button>
        </div>
        <div class="modal-body">
            <div class="share-options">
                <button class="share-option" data-platform="facebook">
                    <span class="share-icon">📘</span>
                    <span class="share-text">Facebook</span>
                </button>
                <button class="share-option" data-platform="twitter">
                    <span class="share-icon">🐦</span>
                    <span class="share-text">Twitter</span>
                </button>
                <button class="share-option" data-platform="whatsapp">
                    <span class="share-icon">📱</span>
                    <span class="share-text">WhatsApp</span>
                </button>
                <button class="share-option" data-platform="telegram">
                    <span class="share-icon">📬</span>
                    <span class="share-text">Telegram</span>
                </button>
            </div>
            <div class="share-link">
                <input type="text" id="shareLink" readonly>
                <button class="btn btn-secondary" id="copyLink">Copy</button>
            </div>
        </div>
    </div>
</div>

<?php
// Enqueue premium frontend assets
wp_enqueue_style('itipster-premium-frontend', ITIPSTER_PRO_PLUGIN_URL . 'assets/css/premium-frontend.css', array(), ITIPSTER_PRO_VERSION);
wp_enqueue_script('itipster-premium-frontend', ITIPSTER_PRO_PLUGIN_URL . 'assets/js/premium-frontend.js', array('jquery'), ITIPSTER_PRO_VERSION, true);

// Localize script with AJAX URL and nonce
wp_localize_script('itipster-premium-frontend', 'itipsterPro', array(
    'ajaxUrl' => admin_url('admin-ajax.php'),
    'nonce' => wp_create_nonce('itipster_pro_nonce'),
    'pluginUrl' => ITIPSTER_PRO_PLUGIN_URL,
    'isLoggedIn' => is_user_logged_in(),
    'userId' => get_current_user_id()
));

get_footer();
?> 