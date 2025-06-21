<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>iTipster Pro - Premium Sports Predictions</title>
    <?php wp_head(); ?>
</head>
<body class="itipster-frontend">

<div class="itipster-predictions-page">
    <!-- Header -->
    <header class="site-header">
        <div class="header-container">
            <div class="logo">
                <h1><span class="icon">⚽</span> iTipster Pro</h1>
                <p class="tagline">Premium Sports Predictions</p>
            </div>
            <nav class="main-nav">
                <a href="<?php echo home_url('/predictions/'); ?>" class="nav-link active">Predictions</a>
                <a href="<?php echo home_url('/fixtures/'); ?>" class="nav-link">Fixtures</a>
                <a href="<?php echo home_url('/dashboard/'); ?>" class="nav-link">Dashboard</a>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-container">
            <div class="hero-content">
                <h2>Live Sports Predictions</h2>
                <p>AI-powered predictions with 84.7% success rate</p>
                <div class="hero-stats">
                    <div class="stat">
                        <span class="number">847</span>
                        <span class="label">Live Predictions</span>
                    </div>
                    <div class="stat">
                        <span class="number">84.7%</span>
                        <span class="label">Success Rate</span>
                    </div>
                    <div class="stat">
                        <span class="number">€2.1M</span>
                        <span class="label">Total Winnings</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Predictions Section -->
    <section class="predictions-section">
        <div class="predictions-container">
            <div class="section-header">
                <h3>🎯 Today's Top Predictions</h3>
                <div class="filters">
                    <select class="filter-select" data-filter="league">
                        <option value="">All Leagues</option>
                        <option value="premier-league">Premier League</option>
                        <option value="la-liga">La Liga</option>
                        <option value="bundesliga">Bundesliga</option>
                        <option value="serie-a">Serie A</option>
                    </select>
                </div>
            </div>

            <div class="predictions-grid">
                <?php
                // Display demo predictions
                $demo_data = new \ADPM\iTipsterPro\APIs\DemoData();
                $all_predictions = \ADPM\iTipsterPro\Data\DemoPredictions::get_predictions();
                $demo_fixtures = \ADPM\iTipsterPro\Data\DemoFixtures::get_fixtures();
                
                // Get fixture data for each prediction
                $fixtures_by_id = array();
                foreach ($demo_fixtures as $league_fixtures) {
                    foreach ($league_fixtures as $fixture) {
                        $fixtures_by_id[$fixture['id']] = $fixture;
                    }
                }
                
                // Display first 12 predictions
                $displayed = 0;
                foreach ($all_predictions as $prediction) {
                    if ($displayed >= 12) break;
                    
                    $fixture = $fixtures_by_id[$prediction['fixture_id']] ?? null;
                    if (!$fixture) continue;
                    
                    $displayed++;
                ?>
                <div class="prediction-card" data-fixture-id="<?php echo $prediction['fixture_id']; ?>">
                    <div class="card-header">
                        <div class="match-info">
                            <div class="teams"><?php echo esc_html($fixture['home_team'] . ' vs ' . $fixture['away_team']); ?></div>
                            <div class="league"><?php echo esc_html($fixture['league']); ?></div>
                            <div class="time"><?php echo date('H:i', strtotime($fixture['date'])); ?></div>
                        </div>
                        <div class="confidence-badge confidence-<?php echo $prediction['confidence_score'] >= 80 ? 'high' : ($prediction['confidence_score'] >= 70 ? 'medium' : 'low'); ?>">
                            <?php echo number_format($prediction['confidence_score'], 1); ?>%
                        </div>
                    </div>
                    
                    <div class="prediction-content">
                        <div class="market-type"><?php echo esc_html($prediction['market']); ?></div>
                        <div class="prediction-value"><?php echo esc_html($prediction['prediction_value']); ?></div>
                        <div class="odds-info">
                            <span class="odds">Odds: <?php echo number_format($prediction['odds'], 2); ?></span>
                            <span class="value-rating"><?php echo number_format($prediction['value_rating'], 1); ?>/10</span>
                        </div>
                    </div>
                    
                    <div class="prediction-analysis">
                        <p><?php echo esc_html($prediction['analysis']); ?></p>
                    </div>
                    
                    <div class="card-footer">
                        <div class="trends">
                            <?php if (isset($prediction['trends']['home_form'])): ?>
                            <span class="trend">Form: <?php echo esc_html($prediction['trends']['home_form']); ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="card-actions">
                            <a href="<?php echo home_url('/fixtures/' . sanitize_title($fixture['home_team']) . '-vs-' . sanitize_title($fixture['away_team']) . '/'); ?>" class="btn-details">View Details</a>
                            <button class="btn-bet" data-prediction-id="<?php echo $prediction['fixture_id']; ?>">Place Bet</button>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
            
            <div class="load-more-section">
                <button class="btn-load-more">Load More Predictions</button>
            </div>
        </div>
    </section>
</div>

<style>
/* Predictions Page Styles */
.itipster-predictions-page {
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

.hero-section {
    background: rgba(255,255,255,0.02);
    padding: 80px 0;
    text-align: center;
}

.hero-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

.hero-content h2 {
    font-size: 3rem;
    font-weight: 800;
    margin: 0 0 20px 0;
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.hero-content p {
    font-size: 1.2rem;
    color: rgba(255,255,255,0.8);
    margin: 0 0 40px 0;
}

.hero-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 30px;
    margin-top: 40px;
}

.stat {
    background: rgba(255,255,255,0.05);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 16px;
    padding: 30px;
    transition: all 0.3s ease;
}

.stat:hover {
    transform: translateY(-5px);
    border-color: rgba(99, 102, 241, 0.4);
}

.stat .number {
    display: block;
    font-size: 2.5rem;
    font-weight: 800;
    color: #6366f1;
    margin-bottom: 10px;
}

.stat .label {
    font-size: 0.9rem;
    color: rgba(255,255,255,0.7);
    text-transform: uppercase;
    letter-spacing: 1px;
}

.predictions-section {
    padding: 60px 0;
}

.predictions-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 40px;
    flex-wrap: wrap;
    gap: 20px;
}

.section-header h3 {
    font-size: 2rem;
    margin: 0;
    font-weight: 700;
}

.filters {
    display: flex;
    gap: 15px;
}

.filter-select {
    background: rgba(255,255,255,0.1);
    border: 1px solid rgba(255,255,255,0.2);
    color: white;
    padding: 10px 15px;
    border-radius: 8px;
    font-size: 14px;
    min-width: 150px;
}

.predictions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
    gap: 25px;
    margin-bottom: 40px;
}

.prediction-card {
    background: rgba(255,255,255,0.05);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 20px;
    padding: 25px;
    transition: all 0.4s ease;
    position: relative;
    overflow: hidden;
}

.prediction-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 25px 50px rgba(0,0,0,0.3);
    border-color: rgba(99, 102, 241, 0.4);
}

.prediction-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, #6366f1, #8b5cf6);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.prediction-card:hover::before {
    opacity: 1;
}

.card-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 20px;
}

.teams {
    font-size: 1.2rem;
    font-weight: 700;
    margin-bottom: 5px;
    color: white;
}

.league {
    font-size: 0.9rem;
    color: rgba(255,255,255,0.7);
    margin-bottom: 3px;
}

.time {
    font-size: 0.8rem;
    color: #6366f1;
    font-weight: 600;
}

.confidence-badge {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
}

.confidence-high {
    background: rgba(16, 185, 129, 0.2);
    color: #10b981;
    border: 1px solid rgba(16, 185, 129, 0.3);
}

.confidence-medium {
    background: rgba(245, 158, 11, 0.2);
    color: #f59e0b;
    border: 1px solid rgba(245, 158, 11, 0.3);
}

.confidence-low {
    background: rgba(239, 68, 68, 0.2);
    color: #ef4444;
    border: 1px solid rgba(239, 68, 68, 0.3);
}

.prediction-content {
    margin-bottom: 20px;
}

.market-type {
    font-size: 0.9rem;
    color: rgba(255,255,255,0.7);
    margin-bottom: 8px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.prediction-value {
    font-size: 1.5rem;
    font-weight: 800;
    color: #10b981;
    margin-bottom: 15px;
}

.odds-info {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px;
    background: rgba(255,255,255,0.05);
    border-radius: 8px;
}

.odds {
    color: #6366f1;
    font-weight: 600;
}

.value-rating {
    color: #fbbf24;
    font-weight: 600;
}

.prediction-analysis {
    margin-bottom: 20px;
}

.prediction-analysis p {
    font-size: 0.9rem;
    color: rgba(255,255,255,0.8);
    line-height: 1.5;
    margin: 0;
}

.card-footer {
    border-top: 1px solid rgba(255,255,255,0.1);
    padding-top: 15px;
}

.trends {
    margin-bottom: 15px;
}

.trend {
    display: inline-block;
    font-size: 0.8rem;
    color: rgba(255,255,255,0.7);
    background: rgba(255,255,255,0.05);
    padding: 4px 8px;
    border-radius: 12px;
    margin-right: 8px;
}

.card-actions {
    display: flex;
    gap: 10px;
}

.btn-details,
.btn-bet {
    flex: 1;
    padding: 10px 15px;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    text-align: center;
    font-size: 14px;
}

.btn-details {
    background: rgba(255,255,255,0.1);
    color: white;
}

.btn-bet {
    background: #10b981;
    color: white;
}

.btn-details:hover {
    background: rgba(255,255,255,0.2);
    color: white;
}

.btn-bet:hover {
    background: #059669;
    transform: translateY(-2px);
}

.load-more-section {
    text-align: center;
}

.btn-load-more {
    background: rgba(255,255,255,0.1);
    border: 1px solid rgba(255,255,255,0.2);
    color: white;
    padding: 15px 30px;
    border-radius: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 16px;
}

.btn-load-more:hover {
    background: rgba(255,255,255,0.2);
    transform: translateY(-2px);
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
    
    .hero-content h2 {
        font-size: 2rem;
    }
    
    .hero-stats {
        grid-template-columns: 1fr;
    }
    
    .section-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .predictions-grid {
        grid-template-columns: 1fr;
    }
    
    .card-actions {
        flex-direction: column;
    }
}
</style>

<?php wp_footer(); ?>
</body>
</html> 