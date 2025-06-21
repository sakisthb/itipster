<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - iTipster Pro</title>
    <?php wp_head(); ?>
</head>
<body class="itipster-frontend">

<div class="dashboard-page">
    <!-- Header -->
    <header class="site-header">
        <div class="header-container">
            <div class="logo">
                <h1><span class="icon">⚽</span> iTipster Pro</h1>
                <p class="tagline">Premium Sports Predictions</p>
            </div>
            <nav class="main-nav">
                <a href="<?php echo home_url('/predictions/'); ?>" class="nav-link">Predictions</a>
                <a href="<?php echo home_url('/fixtures/'); ?>" class="nav-link">Fixtures</a>
                <a href="<?php echo home_url('/dashboard/'); ?>" class="nav-link active">Dashboard</a>
            </nav>
        </div>
    </header>

    <!-- Dashboard Content -->
    <section class="dashboard-content">
        <div class="dashboard-container">
            <!-- Welcome Section -->
            <div class="welcome-section">
                <div class="welcome-content">
                    <h2>Welcome back, User!</h2>
                    <p>Here's your personalized dashboard with your betting performance and latest predictions</p>
                </div>
                <div class="user-avatar">
                    <div class="avatar-circle">👤</div>
                </div>
            </div>

            <!-- Stats Overview -->
            <div class="stats-overview">
                <div class="stat-card">
                    <div class="stat-icon">📈</div>
                    <div class="stat-content">
                        <div class="stat-number">84.7%</div>
                        <div class="stat-label">Success Rate</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">💰</div>
                    <div class="stat-content">
                        <div class="stat-number">€2,847</div>
                        <div class="stat-label">Total Winnings</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">🎯</div>
                    <div class="stat-content">
                        <div class="stat-number">156</div>
                        <div class="stat-label">Predictions Made</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">🔥</div>
                    <div class="stat-content">
                        <div class="stat-number">12</div>
                        <div class="stat-label">Win Streak</div>
                    </div>
                </div>
            </div>

            <!-- Main Dashboard Grid -->
            <div class="dashboard-grid">
                <!-- Recent Predictions -->
                <div class="dashboard-card recent-predictions">
                    <div class="card-header">
                        <h3>🎯 Recent Predictions</h3>
                        <a href="<?php echo home_url('/predictions/'); ?>" class="view-all">View All</a>
                    </div>
                    <div class="predictions-list">
                        <?php
                        // Get recent predictions
                        $recent_predictions = \ADPM\iTipsterPro\Data\DemoPredictions::get_predictions();
                        $demo_fixtures = \ADPM\iTipsterPro\Data\DemoFixtures::get_fixtures();
                        
                        // Get fixture data for each prediction
                        $fixtures_by_id = array();
                        foreach ($demo_fixtures as $league_fixtures) {
                            foreach ($league_fixtures as $fixture) {
                                $fixtures_by_id[$fixture['id']] = $fixture;
                            }
                        }
                        
                        // Display first 5 predictions
                        $displayed = 0;
                        foreach ($recent_predictions as $prediction) {
                            if ($displayed >= 5) break;
                            
                            $fixture = $fixtures_by_id[$prediction['fixture_id']] ?? null;
                            if (!$fixture) continue;
                            
                            $displayed++;
                        ?>
                        <div class="prediction-item">
                            <div class="prediction-match">
                                <div class="teams"><?php echo esc_html($fixture['home_team'] . ' vs ' . $fixture['away_team']); ?></div>
                                <div class="prediction-value"><?php echo esc_html($prediction['prediction_value']); ?></div>
                            </div>
                            <div class="prediction-details">
                                <div class="confidence"><?php echo number_format($prediction['confidence_score'], 1); ?>%</div>
                                <div class="status <?php echo $prediction['confidence_score'] >= 80 ? 'won' : ($prediction['confidence_score'] >= 70 ? 'pending' : 'lost'); ?>">
                                    <?php echo $prediction['confidence_score'] >= 80 ? 'Won' : ($prediction['confidence_score'] >= 70 ? 'Pending' : 'Lost'); ?>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                </div>

                <!-- Performance Chart -->
                <div class="dashboard-card performance-chart">
                    <div class="card-header">
                        <h3>📊 Performance Trend</h3>
                        <div class="chart-period">
                            <button class="period-btn active" data-period="7d">7D</button>
                            <button class="period-btn" data-period="30d">30D</button>
                            <button class="period-btn" data-period="90d">90D</button>
                        </div>
                    </div>
                    <div class="chart-container">
                        <canvas id="performanceChart" width="400" height="200"></canvas>
                    </div>
                </div>

                <!-- Favorite Teams -->
                <div class="dashboard-card favorite-teams">
                    <div class="card-header">
                        <h3>⭐ Favorite Teams</h3>
                        <button class="btn-add-team">+ Add</button>
                    </div>
                    <div class="teams-list">
                        <div class="team-item">
                            <div class="team-info">
                                <div class="team-name">Manchester City</div>
                                <div class="team-league">Premier League</div>
                            </div>
                            <div class="team-stats">
                                <div class="stat">Wins: 8</div>
                                <div class="stat">Predictions: 12</div>
                            </div>
                        </div>
                        <div class="team-item">
                            <div class="team-info">
                                <div class="team-name">Real Madrid</div>
                                <div class="team-league">La Liga</div>
                            </div>
                            <div class="team-stats">
                                <div class="stat">Wins: 6</div>
                                <div class="stat">Predictions: 9</div>
                            </div>
                        </div>
                        <div class="team-item">
                            <div class="team-info">
                                <div class="team-name">Bayern Munich</div>
                                <div class="team-league">Bundesliga</div>
                            </div>
                            <div class="team-stats">
                                <div class="stat">Wins: 7</div>
                                <div class="stat">Predictions: 10</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Upcoming Matches -->
                <div class="dashboard-card upcoming-matches">
                    <div class="card-header">
                        <h3>⏰ Upcoming Matches</h3>
                        <a href="<?php echo home_url('/fixtures/'); ?>" class="view-all">View All</a>
                    </div>
                    <div class="matches-list">
                        <?php
                        // Display upcoming matches
                        $upcoming_count = 0;
                        foreach ($demo_fixtures as $league_fixtures) {
                            foreach ($league_fixtures as $fixture) {
                                if ($upcoming_count >= 4) break;
                                
                                if ($fixture['status'] === 'scheduled') {
                                    $upcoming_count++;
                        ?>
                        <div class="match-item">
                            <div class="match-teams">
                                <div class="team"><?php echo esc_html($fixture['home_team']); ?></div>
                                <div class="vs">vs</div>
                                <div class="team"><?php echo esc_html($fixture['away_team']); ?></div>
                            </div>
                            <div class="match-info">
                                <div class="match-time"><?php echo date('H:i', strtotime($fixture['date'])); ?></div>
                                <div class="match-date"><?php echo date('M j', strtotime($fixture['date'])); ?></div>
                            </div>
                        </div>
                        <?php 
                                }
                            }
                        } 
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
/* Dashboard Page Styles */
.dashboard-page {
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
    min-height: 100vh;
    color: white;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.site-header {
    background: rgba(255,255,255,0.05);
    backdrop-filter: blur(20px);
    border-bottom: 1px solid rgba(255,255,255,0.1);
    padding: 20px 0;
    position: sticky;
    top: 0;
    z-index: 100;
}

.header-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.logo h1 {
    margin: 0;
    font-size: 1.8rem;
    font-weight: 800;
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.tagline {
    margin: 5px 0 0 0;
    font-size: 0.9rem;
    color: rgba(255,255,255,0.7);
}

.main-nav {
    display: flex;
    gap: 30px;
}

.nav-link {
    color: rgba(255,255,255,0.8);
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
    padding: 8px 16px;
    border-radius: 8px;
}

.nav-link:hover,
.nav-link.active {
    color: white;
    background: rgba(255,255,255,0.1);
}

.dashboard-content {
    padding: 40px 0;
}

.dashboard-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

.welcome-section {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 40px;
    background: rgba(255,255,255,0.05);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 20px;
    padding: 30px;
}

.welcome-content h2 {
    font-size: 2.5rem;
    font-weight: 800;
    margin: 0 0 10px 0;
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.welcome-content p {
    font-size: 1.1rem;
    color: rgba(255,255,255,0.8);
    margin: 0;
}

.user-avatar {
    display: flex;
    align-items: center;
}

.avatar-circle {
    width: 80px;
    height: 80px;
    background: rgba(255,255,255,0.1);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    backdrop-filter: blur(10px);
    border: 2px solid rgba(255,255,255,0.2);
}

.stats-overview {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 40px;
}

.stat-card {
    background: rgba(255,255,255,0.05);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 16px;
    padding: 25px;
    display: flex;
    align-items: center;
    gap: 20px;
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
    border-color: rgba(99, 102, 241, 0.4);
}

.stat-icon {
    font-size: 2.5rem;
    background: rgba(255,255,255,0.1);
    border-radius: 12px;
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.stat-number {
    font-size: 2rem;
    font-weight: 800;
    color: #6366f1;
    margin-bottom: 5px;
}

.stat-label {
    font-size: 0.9rem;
    color: rgba(255,255,255,0.7);
    text-transform: uppercase;
    letter-spacing: 1px;
}

.dashboard-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 25px;
}

.dashboard-card {
    background: rgba(255,255,255,0.05);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 20px;
    padding: 25px;
    transition: all 0.3s ease;
}

.dashboard-card:hover {
    transform: translateY(-5px);
    border-color: rgba(99, 102, 241, 0.4);
}

.card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.card-header h3 {
    font-size: 1.3rem;
    margin: 0;
    font-weight: 700;
}

.view-all {
    color: #6366f1;
    text-decoration: none;
    font-size: 0.9rem;
    font-weight: 600;
    transition: color 0.3s ease;
}

.view-all:hover {
    color: #8b5cf6;
}

.btn-add-team {
    background: rgba(255,255,255,0.1);
    border: 1px solid rgba(255,255,255,0.2);
    color: white;
    padding: 6px 12px;
    border-radius: 8px;
    font-size: 0.8rem;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-add-team:hover {
    background: rgba(255,255,255,0.2);
}

.predictions-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.prediction-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px;
    background: rgba(255,255,255,0.05);
    border-radius: 12px;
    transition: all 0.3s ease;
}

.prediction-item:hover {
    background: rgba(255,255,255,0.1);
}

.prediction-match {
    flex: 1;
}

.teams {
    font-size: 0.9rem;
    font-weight: 600;
    color: white;
    margin-bottom: 5px;
}

.prediction-value {
    font-size: 0.8rem;
    color: #10b981;
    font-weight: 600;
}

.prediction-details {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 5px;
}

.confidence {
    font-size: 0.8rem;
    color: rgba(255,255,255,0.7);
}

.status {
    font-size: 0.7rem;
    font-weight: 600;
    padding: 3px 8px;
    border-radius: 12px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status.won {
    background: rgba(16, 185, 129, 0.2);
    color: #10b981;
}

.status.pending {
    background: rgba(245, 158, 11, 0.2);
    color: #f59e0b;
}

.status.lost {
    background: rgba(239, 68, 68, 0.2);
    color: #ef4444;
}

.chart-period {
    display: flex;
    gap: 5px;
}

.period-btn {
    background: rgba(255,255,255,0.1);
    border: 1px solid rgba(255,255,255,0.2);
    color: white;
    padding: 5px 10px;
    border-radius: 6px;
    font-size: 0.8rem;
    cursor: pointer;
    transition: all 0.3s ease;
}

.period-btn.active,
.period-btn:hover {
    background: #6366f1;
    border-color: #6366f1;
}

.chart-container {
    height: 200px;
    position: relative;
}

.teams-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.team-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px;
    background: rgba(255,255,255,0.05);
    border-radius: 12px;
    transition: all 0.3s ease;
}

.team-item:hover {
    background: rgba(255,255,255,0.1);
}

.team-info {
    flex: 1;
}

.team-name {
    font-size: 1rem;
    font-weight: 600;
    color: white;
    margin-bottom: 3px;
}

.team-league {
    font-size: 0.8rem;
    color: rgba(255,255,255,0.7);
}

.team-stats {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 3px;
}

.team-stats .stat {
    font-size: 0.8rem;
    color: rgba(255,255,255,0.7);
}

.matches-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.match-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px;
    background: rgba(255,255,255,0.05);
    border-radius: 12px;
    transition: all 0.3s ease;
}

.match-item:hover {
    background: rgba(255,255,255,0.1);
}

.match-teams {
    display: flex;
    align-items: center;
    gap: 10px;
    flex: 1;
}

.team {
    font-size: 0.9rem;
    font-weight: 600;
    color: white;
}

.vs {
    font-size: 0.8rem;
    color: rgba(255,255,255,0.5);
    font-weight: 600;
}

.match-info {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 3px;
}

.match-time {
    font-size: 0.9rem;
    font-weight: 600;
    color: #6366f1;
}

.match-date {
    font-size: 0.8rem;
    color: rgba(255,255,255,0.7);
}

/* Responsive Design */
@media (max-width: 768px) {
    .header-container {
        flex-direction: column;
        gap: 20px;
    }
    
    .main-nav {
        gap: 15px;
    }
    
    .welcome-section {
        flex-direction: column;
        text-align: center;
        gap: 20px;
    }
    
    .welcome-content h2 {
        font-size: 2rem;
    }
    
    .stats-overview {
        grid-template-columns: 1fr;
    }
    
    .dashboard-grid {
        grid-template-columns: 1fr;
    }
    
    .prediction-item,
    .team-item,
    .match-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }
    
    .prediction-details,
    .team-stats,
    .match-info {
        align-items: flex-start;
    }
}
</style>

<script>
// Performance Chart
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('performanceChart').getContext('2d');
    
    // Sample data for the chart
    const data = {
        labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
        datasets: [{
            label: 'Success Rate',
            data: [75, 82, 78, 85, 90, 88, 92],
            borderColor: '#6366f1',
            backgroundColor: 'rgba(99, 102, 241, 0.1)',
            borderWidth: 2,
            fill: true,
            tension: 0.4
        }]
    };
    
    const config = {
        type: 'line',
        data: data,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    grid: {
                        color: 'rgba(255, 255, 255, 0.1)'
                    },
                    ticks: {
                        color: 'rgba(255, 255, 255, 0.7)'
                    }
                },
                x: {
                    grid: {
                        color: 'rgba(255, 255, 255, 0.1)'
                    },
                    ticks: {
                        color: 'rgba(255, 255, 255, 0.7)'
                    }
                }
            }
        }
    };
    
    // Create chart if Chart.js is available
    if (typeof Chart !== 'undefined') {
        new Chart(ctx, config);
    }
    
    // Period button functionality
    const periodBtns = document.querySelectorAll('.period-btn');
    periodBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            periodBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            // Here you would update the chart data based on the selected period
        });
    });
});
</script>

<?php wp_footer(); ?>
</body>
</html> 