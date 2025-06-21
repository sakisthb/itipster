<?php
/**
 * Premium User Dashboard Template
 * Personal analytics, interactive charts, and gamification
 * 
 * @package ADPM\iTipsterPro
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Check if user is logged in
if (!is_user_logged_in()) {
    wp_redirect(home_url('/login'));
    exit;
}

$current_user = wp_get_current_user();
$user_id = get_current_user_id();

get_header();
?>

<!-- ========================================
   DASHBOARD HERO SECTION
   ======================================== -->
<section class="dashboard-hero">
    <div class="container">
        <div class="hero-content">
            <div class="user-welcome">
                <div class="user-avatar">
                    <?php echo esc_html(strtoupper(substr($current_user->display_name, 0, 1))); ?>
                </div>
                <div class="user-info">
                    <h1 class="welcome-title">Welcome back, <?php echo esc_html($current_user->display_name); ?>!</h1>
                    <p class="welcome-subtitle">Here's your performance overview</p>
                </div>
            </div>
            
            <div class="user-stats">
                <div class="stat-item">
                    <div class="stat-number" data-target="67">0</div>
                    <div class="stat-label">Win Rate (%)</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number" data-target="1240">0</div>
                    <div class="stat-label">Total Profit (€)</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number" data-target="15">0</div>
                    <div class="stat-label">Current Streak</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number" data-target="5">0</div>
                    <div class="stat-label">User Level</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ========================================
   PERFORMANCE OVERVIEW
   ======================================== -->
<section class="performance-overview">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Performance Analytics</h2>
            <div class="time-filter">
                <button class="time-btn active" data-period="week">Week</button>
                <button class="time-btn" data-period="month">Month</button>
                <button class="time-btn" data-period="year">Year</button>
                <button class="time-btn" data-period="all">All Time</button>
            </div>
        </div>
        
        <div class="analytics-grid">
            <!-- Profit/Loss Chart -->
            <div class="chart-card large">
                <div class="card-header">
                    <h3>Profit/Loss Trend</h3>
                    <div class="chart-controls">
                        <button class="chart-btn active" data-chart="profit">Profit</button>
                        <button class="chart-btn" data-chart="roi">ROI</button>
                    </div>
                </div>
                <div class="card-content">
                    <div class="chart-container">
                        <canvas id="profitChart" width="400" height="200"></canvas>
                    </div>
                    <div class="chart-summary">
                        <div class="summary-item">
                            <span class="summary-label">Total Profit</span>
                            <span class="summary-value positive">+€1,240</span>
                        </div>
                        <div class="summary-item">
                            <span class="summary-label">Best Day</span>
                            <span class="summary-value">+€180</span>
                        </div>
                        <div class="summary-item">
                            <span class="summary-label">Worst Day</span>
                            <span class="summary-value negative">-€45</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Win Rate Chart -->
            <div class="chart-card">
                <div class="card-header">
                    <h3>Win Rate</h3>
                </div>
                <div class="card-content">
                    <div class="donut-chart">
                        <svg width="120" height="120">
                            <circle class="background" cx="60" cy="60" r="50" stroke-width="8"/>
                            <circle class="progress" cx="60" cy="60" r="50" stroke-width="8" data-percentage="67"/>
                        </svg>
                        <div class="chart-center">
                            <div class="chart-value">67%</div>
                            <div class="chart-label">Win Rate</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Streak Counter -->
            <div class="chart-card">
                <div class="card-header">
                    <h3>Current Streak</h3>
                </div>
                <div class="card-content">
                    <div class="streak-counter">
                        <div class="streak-number">15</div>
                        <div class="streak-label">Wins in a row</div>
                        <div class="streak-fire">🔥</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ========================================
   RECENT ACTIVITY
   ======================================== -->
<section class="recent-activity">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Recent Activity</h2>
            <a href="<?php echo esc_url(home_url('/activity')); ?>" class="btn btn-secondary">
                View All Activity
            </a>
        </div>
        
        <div class="activity-timeline">
            <div class="activity-item win">
                <div class="activity-icon">✅</div>
                <div class="activity-content">
                    <div class="activity-header">
                        <div class="activity-title">Won €45 on Manchester City vs Liverpool</div>
                        <div class="activity-amount positive">+€45</div>
                    </div>
                    <div class="activity-details">
                        <span class="activity-time">2 hours ago</span>
                        <span class="activity-confidence">Confidence: 85%</span>
                    </div>
                </div>
            </div>
            
            <div class="activity-item loss">
                <div class="activity-icon">❌</div>
                <div class="activity-content">
                    <div class="activity-header">
                        <div class="activity-title">Lost €20 on Arsenal vs Chelsea</div>
                        <div class="activity-amount negative">-€20</div>
                    </div>
                    <div class="activity-details">
                        <span class="activity-time">1 day ago</span>
                        <span class="activity-confidence">Confidence: 72%</span>
                    </div>
                </div>
            </div>
            
            <div class="activity-item win">
                <div class="activity-icon">✅</div>
                <div class="activity-content">
                    <div class="activity-header">
                        <div class="activity-title">Won €32 on Real Madrid vs Barcelona</div>
                        <div class="activity-amount positive">+€32</div>
                    </div>
                    <div class="activity-details">
                        <span class="activity-time">2 days ago</span>
                        <span class="activity-confidence">Confidence: 88%</span>
                    </div>
                </div>
            </div>
            
            <div class="activity-item achievement">
                <div class="activity-icon">🏆</div>
                <div class="activity-content">
                    <div class="activity-header">
                        <div class="activity-title">Earned "Winning Streak" Achievement</div>
                        <div class="activity-amount">+50 XP</div>
                    </div>
                    <div class="activity-details">
                        <span class="activity-time">3 days ago</span>
                        <span class="activity-description">5 wins in a row</span>
                    </div>
                </div>
            </div>
            
            <div class="activity-item level-up">
                <div class="activity-icon">⭐</div>
                <div class="activity-content">
                    <div class="activity-header">
                        <div class="activity-title">Reached Level 5</div>
                        <div class="activity-amount">Unlocked Premium</div>
                    </div>
                    <div class="activity-details">
                        <span class="activity-time">1 week ago</span>
                        <span class="activity-description">New features available</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ========================================
   ACHIEVEMENTS & GAMIFICATION
   ======================================== -->
<section class="achievements-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Achievements & Progress</h2>
            <div class="progress-overview">
                <span class="progress-text">750 / 1000 XP to Level 6</span>
                <div class="progress-bar">
                    <div class="progress-fill" style="width: 75%;"></div>
                </div>
            </div>
        </div>
        
        <div class="achievements-grid">
            <div class="achievement-card earned">
                <div class="achievement-icon">🏆</div>
                <div class="achievement-info">
                    <h4>First Win</h4>
                    <p>Win your first prediction</p>
                    <div class="achievement-progress">
                        <span class="progress-text">Completed</span>
                        <span class="earned-date">2 weeks ago</span>
                    </div>
                </div>
                <div class="achievement-reward">+25 XP</div>
            </div>
            
            <div class="achievement-card earned">
                <div class="achievement-icon">🔥</div>
                <div class="achievement-info">
                    <h4>Winning Streak</h4>
                    <p>Win 5 predictions in a row</p>
                    <div class="achievement-progress">
                        <span class="progress-text">Completed</span>
                        <span class="earned-date">3 days ago</span>
                    </div>
                </div>
                <div class="achievement-reward">+50 XP</div>
            </div>
            
            <div class="achievement-card earned">
                <div class="achievement-icon">💎</div>
                <div class="achievement-info">
                    <h4>Premium User</h4>
                    <p>Upgrade to premium membership</p>
                    <div class="achievement-progress">
                        <span class="progress-text">Completed</span>
                        <span class="earned-date">1 week ago</span>
                    </div>
                </div>
                <div class="achievement-reward">+100 XP</div>
            </div>
            
            <div class="achievement-card">
                <div class="achievement-icon">🎯</div>
                <div class="achievement-info">
                    <h4>High Accuracy</h4>
                    <p>Achieve 90% win rate</p>
                    <div class="achievement-progress">
                        <span class="progress-text">67% / 90%</span>
                        <div class="mini-progress">
                            <div class="mini-fill" style="width: 74%;"></div>
                        </div>
                    </div>
                </div>
                <div class="achievement-reward">+75 XP</div>
            </div>
            
            <div class="achievement-card">
                <div class="achievement-icon">💰</div>
                <div class="achievement-info">
                    <h4>Big Winner</h4>
                    <p>Win €500 in a single day</p>
                    <div class="achievement-progress">
                        <span class="progress-text">€180 / €500</span>
                        <div class="mini-progress">
                            <div class="mini-fill" style="width: 36%;"></div>
                        </div>
                    </div>
                </div>
                <div class="achievement-reward">+150 XP</div>
            </div>
            
            <div class="achievement-card">
                <div class="achievement-icon">📅</div>
                <div class="achievement-info">
                    <h4>Daily User</h4>
                    <p>Use the platform for 30 consecutive days</p>
                    <div class="achievement-progress">
                        <span class="progress-text">15 / 30 days</span>
                        <div class="mini-progress">
                            <div class="mini-fill" style="width: 50%;"></div>
                        </div>
                    </div>
                </div>
                <div class="achievement-reward">+200 XP</div>
            </div>
        </div>
    </div>
</section>

<!-- ========================================
   FAVORITE PREDICTIONS
   ======================================== -->
<section class="favorites-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Favorite Predictions</h2>
            <div class="favorites-filter">
                <button class="filter-btn active" data-filter="all">All</button>
                <button class="filter-btn" data-filter="active">Active</button>
                <button class="filter-btn" data-filter="completed">Completed</button>
            </div>
        </div>
        
        <div class="favorites-grid">
            <div class="favorite-card active">
                <div class="card-header">
                    <div class="match-info">
                        <div class="teams">Manchester City vs Liverpool</div>
                        <div class="league">Premier League</div>
                    </div>
                    <div class="confidence">85%</div>
                </div>
                <div class="card-content">
                    <div class="prediction-tip">
                        <strong>Tip:</strong> Manchester City to win
                    </div>
                    <div class="prediction-odds">
                        <span class="odd">1.85</span>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn btn-primary btn-sm">View Details</button>
                    <button class="btn btn-secondary btn-sm remove-favorite">Remove</button>
                </div>
            </div>
            
            <div class="favorite-card active">
                <div class="card-header">
                    <div class="match-info">
                        <div class="teams">Real Madrid vs Barcelona</div>
                        <div class="league">La Liga</div>
                    </div>
                    <div class="confidence">88%</div>
                </div>
                <div class="card-content">
                    <div class="prediction-tip">
                        <strong>Tip:</strong> Both teams to score
                    </div>
                    <div class="prediction-odds">
                        <span class="odd">1.65</span>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn btn-primary btn-sm">View Details</button>
                    <button class="btn btn-secondary btn-sm remove-favorite">Remove</button>
                </div>
            </div>
            
            <div class="favorite-card completed win">
                <div class="card-header">
                    <div class="match-info">
                        <div class="teams">Bayern Munich vs Dortmund</div>
                        <div class="league">Bundesliga</div>
                    </div>
                    <div class="confidence">82%</div>
                </div>
                <div class="card-content">
                    <div class="prediction-tip">
                        <strong>Tip:</strong> Bayern Munich to win
                    </div>
                    <div class="prediction-result">
                        <span class="result win">✅ Won €32</span>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn btn-secondary btn-sm">View Details</button>
                    <button class="btn btn-secondary btn-sm remove-favorite">Remove</button>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ========================================
   RECOMMENDATIONS
   ======================================== -->
<section class="recommendations-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Recommended for You</h2>
            <p class="section-subtitle">Based on your betting history and preferences</p>
        </div>
        
        <div class="recommendations-grid">
            <div class="recommendation-card">
                <div class="card-header">
                    <div class="recommendation-badge">🎯 Perfect Match</div>
                    <div class="confidence">92%</div>
                </div>
                <div class="card-content">
                    <div class="match-info">
                        <div class="teams">PSG vs Marseille</div>
                        <div class="league">Ligue 1</div>
                        <div class="time">Today, 20:00</div>
                    </div>
                    <div class="prediction-tip">
                        <strong>Tip:</strong> PSG to win & Over 2.5 goals
                    </div>
                    <div class="prediction-odds">
                        <span class="odd">2.15</span>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn btn-primary">Place Bet</button>
                    <button class="btn btn-secondary">View Analysis</button>
                </div>
            </div>
            
            <div class="recommendation-card">
                <div class="card-header">
                    <div class="recommendation-badge">🔥 Hot Pick</div>
                    <div class="confidence">87%</div>
                </div>
                <div class="card-content">
                    <div class="match-info">
                        <div class="teams">Juventus vs Inter</div>
                        <div class="league">Serie A</div>
                        <div class="time">Tomorrow, 20:45</div>
                    </div>
                    <div class="prediction-tip">
                        <strong>Tip:</strong> Under 2.5 goals
                    </div>
                    <div class="prediction-odds">
                        <span class="odd">1.75</span>
                    </div>
                </div>
                <div class="card-actions">
                    <button class="btn btn-primary">Place Bet</button>
                    <button class="btn btn-secondary">View Analysis</button>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ========================================
   SETTINGS & PREFERENCES
   ======================================== -->
<section class="settings-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Settings & Preferences</h2>
        </div>
        
        <div class="settings-grid">
            <div class="settings-card">
                <div class="card-header">
                    <h3>Notification Preferences</h3>
                </div>
                <div class="card-content">
                    <div class="setting-item">
                        <div class="setting-info">
                            <span class="setting-label">New Predictions</span>
                            <span class="setting-description">Get notified when new predictions are available</span>
                        </div>
                        <div class="toggle-switch">
                            <input type="checkbox" id="notifyPredictions" checked>
                            <span class="toggle-slider"></span>
                        </div>
                    </div>
                    
                    <div class="setting-item">
                        <div class="setting-info">
                            <span class="setting-label">Odds Changes</span>
                            <span class="setting-description">Receive alerts for significant odds movements</span>
                        </div>
                        <div class="toggle-switch">
                            <input type="checkbox" id="notifyOdds" checked>
                            <span class="toggle-slider"></span>
                        </div>
                    </div>
                    
                    <div class="setting-item">
                        <div class="setting-info">
                            <span class="setting-label">Achievement Unlocks</span>
                            <span class="setting-description">Celebrate your achievements with notifications</span>
                        </div>
                        <div class="toggle-switch">
                            <input type="checkbox" id="notifyAchievements" checked>
                            <span class="toggle-slider"></span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="settings-card">
                <div class="card-header">
                    <h3>Privacy Settings</h3>
                </div>
                <div class="card-content">
                    <div class="setting-item">
                        <div class="setting-info">
                            <span class="setting-label">Public Profile</span>
                            <span class="setting-description">Allow others to see your achievements</span>
                        </div>
                        <div class="toggle-switch">
                            <input type="checkbox" id="publicProfile">
                            <span class="toggle-slider"></span>
                        </div>
                    </div>
                    
                    <div class="setting-item">
                        <div class="setting-info">
                            <span class="setting-label">Activity Feed</span>
                            <span class="setting-description">Share your wins in the community feed</span>
                        </div>
                        <div class="toggle-switch">
                            <input type="checkbox" id="activityFeed" checked>
                            <span class="toggle-slider"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
// Enqueue premium frontend assets
wp_enqueue_style('itipster-premium-frontend', ITIPSTER_PRO_PLUGIN_URL . 'assets/css/premium-frontend.css', array(), ITIPSTER_PRO_VERSION);
wp_enqueue_script('itipster-premium-frontend', ITIPSTER_PRO_PLUGIN_URL . 'assets/js/premium-frontend.js', array('jquery'), ITIPSTER_PRO_VERSION, true);

// Enqueue Chart.js for analytics
wp_enqueue_script('chartjs', 'https://cdn.jsdelivr.net/npm/chart.js', array(), '3.9.1', true);

// Localize script with AJAX URL and nonce
wp_localize_script('itipster-premium-frontend', 'itipsterPro', array(
    'ajaxUrl' => admin_url('admin-ajax.php'),
    'nonce' => wp_create_nonce('itipster_pro_nonce'),
    'pluginUrl' => ITIPSTER_PRO_PLUGIN_URL,
    'userId' => $user_id,
    'userData' => array(
        'name' => $current_user->display_name,
        'email' => $current_user->user_email,
        'level' => 5,
        'xp' => 750,
        'xpNeeded' => 1000
    )
));

get_footer();
?> 