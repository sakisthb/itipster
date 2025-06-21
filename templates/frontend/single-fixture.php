<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo esc_html($fixture['home_team'] . ' vs ' . $fixture['away_team']); ?> - iTipster Pro</title>
    <?php wp_head(); ?>
</head>
<body class="itipster-frontend single-fixture">

<div class="fixture-page">
    <!-- Header -->
    <header class="site-header">
        <div class="header-container">
            <div class="logo">
                <h1><span class="icon">⚽</span> iTipster Pro</h1>
            </div>
            <nav class="main-nav">
                <a href="<?php echo home_url('/predictions/'); ?>" class="nav-link">← Back to Predictions</a>
            </nav>
        </div>
    </header>

    <!-- Fixture Details -->
    <section class="fixture-hero">
        <div class="hero-container">
            <div class="match-header">
                <div class="teams-display">
                    <div class="team home-team">
                        <div class="team-name"><?php echo esc_html($fixture['home_team']); ?></div>
                        <div class="team-badge">🏠</div>
                    </div>
                    <div class="vs-separator">
                        <span class="vs-text">VS</span>
                        <div class="match-time"><?php echo date('M j, H:i', strtotime($fixture['date'])); ?></div>
                    </div>
                    <div class="team away-team">
                        <div class="team-name"><?php echo esc_html($fixture['away_team']); ?></div>
                        <div class="team-badge">✈️</div>
                    </div>
                </div>
                
                <div class="match-details">
                    <div class="detail-item">
                        <span class="detail-label">League:</span>
                        <span class="detail-value"><?php echo esc_html($fixture['league']); ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Venue:</span>
                        <span class="detail-value"><?php echo esc_html($fixture['venue']); ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Status:</span>
                        <span class="detail-value status-<?php echo $fixture['status']; ?>"><?php echo ucfirst($fixture['status']); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Predictions for this fixture -->
    <section class="fixture-predictions">
        <div class="predictions-container">
            <h3>🎯 AI Predictions for this Match</h3>
            
            <div class="predictions-grid">
                <?php
                // Get predictions for this fixture
                $fixture_predictions = \ADPM\iTipsterPro\Data\DemoPredictions::get_predictions_by_fixture($fixture['id']);
                
                foreach ($fixture_predictions as $prediction):
                ?>
                <div class="prediction-card detailed">
                    <div class="prediction-header">
                        <h4><?php echo esc_html($prediction['market']); ?></h4>
                        <div class="confidence-meter">
                            <div class="meter-fill" style="width: <?php echo $prediction['confidence_score']; ?>%"></div>
                            <span class="confidence-text"><?php echo number_format($prediction['confidence_score'], 1); ?>% Confidence</span>
                        </div>
                    </div>
                    
                    <div class="prediction-main">
                        <div class="prediction-value-large"><?php echo esc_html($prediction['prediction_value']); ?></div>
                        <div class="odds-display">
                            <span class="odds-label">Best Odds:</span>
                            <span class="odds-value"><?php echo number_format($prediction['odds'], 2); ?></span>
                        </div>
                        <div class="value-rating-display">
                            <span class="rating-label">Value Rating:</span>
                            <span class="rating-stars"><?php echo str_repeat('★', floor($prediction['value_rating'])); ?></span>
                            <span class="rating-number"><?php echo number_format($prediction['value_rating'], 1); ?>/10</span>
                        </div>
                    </div>
                    
                    <div class="prediction-analysis-detailed">
                        <h5>📊 Analysis</h5>
                        <p><?php echo esc_html($prediction['analysis']); ?></p>
                        
                        <?php if (isset($prediction['trends'])): ?>
                        <div class="trends-section">
                            <h6>📈 Key Trends</h6>
                            <div class="trends-grid">
                                <?php foreach ($prediction['trends'] as $key => $value): ?>
                                <div class="trend-item">
                                    <span class="trend-label"><?php echo ucfirst(str_replace('_', ' ', $key)); ?>:</span>
                                    <span class="trend-value"><?php echo is_array($value) ? implode(', ', $value) : $value; ?></span>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="prediction-actions">
                        <button class="btn-primary btn-place-bet">Place Bet</button>
                        <button class="btn-secondary btn-add-watchlist">Add to Watchlist</button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
</div>

<style>
/* Single Fixture Page Styles */
.fixture-page {
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

.nav-link:hover {
    color: white;
    background: rgba(255,255,255,0.1);
}

.fixture-hero {
    background: rgba(255,255,255,0.02);
    padding: 60px 0;
}

.hero-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

.match-header {
    text-align: center;
}

.teams-display {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 40px;
    margin-bottom: 40px;
    flex-wrap: wrap;
}

.team {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 15px;
}

.team-name {
    font-size: 2rem;
    font-weight: 700;
    text-align: center;
    max-width: 200px;
}

.team-badge {
    font-size: 3rem;
    background: rgba(255,255,255,0.1);
    border-radius: 50%;
    width: 80px;
    height: 80px;
    display: flex;
    align-items: center;
    justify-content: center;
    backdrop-filter: blur(10px);
}

.vs-separator {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
}

.vs-text {
    font-size: 1.5rem;
    font-weight: 800;
    color: #6366f1;
    background: rgba(99, 102, 241, 0.1);
    padding: 10px 20px;
    border-radius: 25px;
    border: 1px solid rgba(99, 102, 241, 0.3);
}

.match-time {
    font-size: 1rem;
    color: rgba(255,255,255,0.7);
    font-weight: 500;
}

.match-details {
    display: flex;
    justify-content: center;
    gap: 40px;
    flex-wrap: wrap;
}

.detail-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 5px;
}

.detail-label {
    font-size: 0.9rem;
    color: rgba(255,255,255,0.6);
    text-transform: uppercase;
    letter-spacing: 1px;
}

.detail-value {
    font-size: 1.1rem;
    font-weight: 600;
    color: white;
}

.status-scheduled {
    color: #6366f1;
}

.status-live {
    color: #10b981;
    animation: pulse 2s infinite;
}

.status-finished {
    color: #6b7280;
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}

.fixture-predictions {
    padding: 60px 0;
}

.predictions-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

.predictions-container h3 {
    font-size: 2rem;
    margin: 0 0 40px 0;
    font-weight: 700;
    text-align: center;
}

.predictions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(500px, 1fr));
    gap: 30px;
}

.prediction-card.detailed {
    background: rgba(255,255,255,0.05);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 20px;
    padding: 30px;
    transition: all 0.4s ease;
    position: relative;
    overflow: hidden;
}

.prediction-card.detailed:hover {
    transform: translateY(-5px);
    box-shadow: 0 25px 50px rgba(0,0,0,0.3);
    border-color: rgba(99, 102, 241, 0.4);
}

.prediction-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
    flex-wrap: wrap;
    gap: 15px;
}

.prediction-header h4 {
    font-size: 1.3rem;
    margin: 0;
    font-weight: 700;
    color: white;
}

.confidence-meter {
    position: relative;
    background: rgba(255,255,255,0.1);
    border-radius: 20px;
    height: 30px;
    min-width: 150px;
    overflow: hidden;
}

.meter-fill {
    height: 100%;
    background: linear-gradient(90deg, #10b981, #059669);
    border-radius: 20px;
    transition: width 0.3s ease;
}

.confidence-text {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-size: 0.8rem;
    font-weight: 600;
    color: white;
    text-shadow: 0 1px 2px rgba(0,0,0,0.5);
}

.prediction-main {
    text-align: center;
    margin-bottom: 25px;
}

.prediction-value-large {
    font-size: 2.5rem;
    font-weight: 800;
    color: #10b981;
    margin-bottom: 20px;
}

.odds-display,
.value-rating-display {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 10px;
    margin-bottom: 10px;
}

.odds-label,
.rating-label {
    font-size: 0.9rem;
    color: rgba(255,255,255,0.7);
}

.odds-value {
    font-size: 1.2rem;
    font-weight: 700;
    color: #6366f1;
}

.rating-stars {
    color: #fbbf24;
    font-size: 1.1rem;
}

.rating-number {
    font-size: 1rem;
    font-weight: 600;
    color: #fbbf24;
}

.prediction-analysis-detailed {
    margin-bottom: 25px;
}

.prediction-analysis-detailed h5 {
    font-size: 1.1rem;
    margin: 0 0 15px 0;
    color: white;
    font-weight: 600;
}

.prediction-analysis-detailed p {
    font-size: 0.95rem;
    color: rgba(255,255,255,0.8);
    line-height: 1.6;
    margin: 0 0 20px 0;
}

.trends-section h6 {
    font-size: 1rem;
    margin: 0 0 15px 0;
    color: white;
    font-weight: 600;
}

.trends-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 10px;
}

.trend-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 12px;
    background: rgba(255,255,255,0.05);
    border-radius: 8px;
    font-size: 0.85rem;
}

.trend-label {
    color: rgba(255,255,255,0.7);
    font-weight: 500;
}

.trend-value {
    color: white;
    font-weight: 600;
}

.prediction-actions {
    display: flex;
    gap: 15px;
}

.btn-primary,
.btn-secondary {
    flex: 1;
    padding: 12px 20px;
    border: none;
    border-radius: 10px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 14px;
}

.btn-primary {
    background: #10b981;
    color: white;
}

.btn-secondary {
    background: rgba(255,255,255,0.1);
    color: white;
    border: 1px solid rgba(255,255,255,0.2);
}

.btn-primary:hover {
    background: #059669;
    transform: translateY(-2px);
}

.btn-secondary:hover {
    background: rgba(255,255,255,0.2);
    transform: translateY(-2px);
}

/* Responsive Design */
@media (max-width: 768px) {
    .header-container {
        flex-direction: column;
        gap: 20px;
    }
    
    .teams-display {
        flex-direction: column;
        gap: 20px;
    }
    
    .team-name {
        font-size: 1.5rem;
    }
    
    .match-details {
        flex-direction: column;
        gap: 20px;
    }
    
    .predictions-grid {
        grid-template-columns: 1fr;
    }
    
    .prediction-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .prediction-actions {
        flex-direction: column;
    }
}
</style>

<?php wp_footer(); ?>
</body>
</html> 