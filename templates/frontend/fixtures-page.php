<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Fixtures - iTipster Pro</title>
    <?php wp_head(); ?>
</head>
<body class="itipster-frontend">

<div class="fixtures-page">
    <!-- Header -->
    <header class="site-header">
        <div class="header-container">
            <div class="logo">
                <h1><span class="icon">⚽</span> iTipster Pro</h1>
                <p class="tagline">Premium Sports Predictions</p>
            </div>
            <nav class="main-nav">
                <a href="<?php echo home_url('/predictions/'); ?>" class="nav-link">Predictions</a>
                <a href="<?php echo home_url('/fixtures/'); ?>" class="nav-link active">Fixtures</a>
                <a href="<?php echo home_url('/dashboard/'); ?>" class="nav-link">Dashboard</a>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-container">
            <div class="hero-content">
                <h2>Live Fixtures</h2>
                <p>Browse all upcoming matches and get detailed predictions</p>
                <div class="hero-stats">
                    <div class="stat">
                        <span class="number"><?php 
                            $total_count = 0;
                            foreach ($fixtures as $league_fixtures) {
                                $total_count += count($league_fixtures);
                            }
                            echo $total_count;
                        ?></span>
                        <span class="label">Total Fixtures</span>
                    </div>
                    <div class="stat">
                        <span class="number"><?php 
                            $live_count = 0;
                            foreach ($fixtures as $league_fixtures) {
                                foreach ($league_fixtures as $fixture) {
                                    if ($fixture['status'] === 'live') {
                                        $live_count++;
                                    }
                                }
                            }
                            echo $live_count;
                        ?></span>
                        <span class="label">Live Matches</span>
                    </div>
                    <div class="stat">
                        <span class="number"><?php 
                            $scheduled_count = 0;
                            foreach ($fixtures as $league_fixtures) {
                                foreach ($league_fixtures as $fixture) {
                                    if ($fixture['status'] === 'scheduled') {
                                        $scheduled_count++;
                                    }
                                }
                            }
                            echo $scheduled_count;
                        ?></span>
                        <span class="label">Upcoming</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Fixtures Section -->
    <section class="fixtures-section">
        <div class="fixtures-container">
            <div class="section-header">
                <h3>📅 All Fixtures</h3>
                <div class="filters">
                    <select class="filter-select" data-filter="league">
                        <option value="">All Leagues</option>
                        <option value="Premier League">Premier League</option>
                        <option value="La Liga">La Liga</option>
                        <option value="Bundesliga">Bundesliga</option>
                        <option value="Serie A">Serie A</option>
                        <option value="Ligue 1">Ligue 1</option>
                    </select>
                    <select class="filter-select" data-filter="status">
                        <option value="">All Status</option>
                        <option value="scheduled">Scheduled</option>
                        <option value="live">Live</option>
                        <option value="finished">Finished</option>
                    </select>
                    <input type="date" class="filter-date" data-filter="date" placeholder="Select Date">
                </div>
            </div>

            <div class="fixtures-grid">
                <?php
                // Display all fixtures
                foreach ($fixtures as $league_name => $league_fixtures) {
                    foreach ($league_fixtures as $fixture) {
                ?>
                <div class="fixture-card" data-league="<?php echo esc_attr($fixture['league']); ?>" data-status="<?php echo esc_attr($fixture['status']); ?>" data-date="<?php echo date('Y-m-d', strtotime($fixture['date'])); ?>">
                    <div class="card-header">
                        <div class="league-info">
                            <span class="league-name"><?php echo esc_html($fixture['league']); ?></span>
                            <span class="fixture-date"><?php echo date('M j, Y', strtotime($fixture['date'])); ?></span>
                        </div>
                        <div class="status-badge status-<?php echo $fixture['status']; ?>">
                            <?php echo ucfirst($fixture['status']); ?>
                        </div>
                    </div>
                    
                    <div class="match-info">
                        <div class="team home-team">
                            <div class="team-name"><?php echo esc_html($fixture['home_team']); ?></div>
                            <div class="team-badge">🏠</div>
                        </div>
                        
                        <div class="match-center">
                            <div class="match-time"><?php echo date('H:i', strtotime($fixture['date'])); ?></div>
                            <div class="vs-text">VS</div>
                            <?php if ($fixture['status'] === 'live'): ?>
                            <div class="live-indicator">
                                <span class="live-dot"></span>
                                LIVE
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="team away-team">
                            <div class="team-name"><?php echo esc_html($fixture['away_team']); ?></div>
                            <div class="team-badge">✈️</div>
                        </div>
                    </div>
                    
                    <div class="fixture-details">
                        <div class="venue">
                            <span class="detail-label">Venue:</span>
                            <span class="detail-value"><?php echo esc_html($fixture['venue']); ?></span>
                        </div>
                        
                        <?php if (isset($fixture['odds'])): ?>
                        <div class="odds-preview">
                            <span class="detail-label">Best Odds:</span>
                            <span class="detail-value"><?php echo number_format($fixture['odds']['home'] ?? 0, 2); ?> / <?php echo number_format($fixture['odds']['draw'] ?? 0, 2); ?> / <?php echo number_format($fixture['odds']['away'] ?? 0, 2); ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="card-actions">
                        <a href="<?php echo home_url('/fixtures/' . sanitize_title($fixture['home_team']) . '-vs-' . sanitize_title($fixture['away_team']) . '/'); ?>" class="btn-details">View Details</a>
                        <button class="btn-predictions" data-fixture-id="<?php echo $fixture['id']; ?>">Get Predictions</button>
                    </div>
                </div>
                <?php 
                    }
                } 
                ?>
            </div>
            
            <div class="load-more-section">
                <button class="btn-load-more">Load More Fixtures</button>
            </div>
        </div>
    </section>
</div>

<style>
/* Fixtures Page Styles */
.fixtures-page {
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

.fixtures-section {
    padding: 60px 0;
}

.fixtures-container {
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
    flex-wrap: wrap;
}

.filter-select,
.filter-date {
    background: rgba(255,255,255,0.1);
    border: 1px solid rgba(255,255,255,0.2);
    color: white;
    padding: 10px 15px;
    border-radius: 8px;
    font-size: 14px;
    min-width: 150px;
}

.filter-date {
    min-width: 140px;
}

.fixtures-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(450px, 1fr));
    gap: 25px;
    margin-bottom: 40px;
}

.fixture-card {
    background: rgba(255,255,255,0.05);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 20px;
    padding: 25px;
    transition: all 0.4s ease;
    position: relative;
    overflow: hidden;
}

.fixture-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 25px 50px rgba(0,0,0,0.3);
    border-color: rgba(99, 102, 241, 0.4);
}

.fixture-card::before {
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

.fixture-card:hover::before {
    opacity: 1;
}

.card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.league-info {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.league-name {
    font-size: 1rem;
    font-weight: 600;
    color: white;
}

.fixture-date {
    font-size: 0.8rem;
    color: rgba(255,255,255,0.7);
}

.status-badge {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
}

.status-scheduled {
    background: rgba(99, 102, 241, 0.2);
    color: #6366f1;
    border: 1px solid rgba(99, 102, 241, 0.3);
}

.status-live {
    background: rgba(16, 185, 129, 0.2);
    color: #10b981;
    border: 1px solid rgba(16, 185, 129, 0.3);
}

.status-finished {
    background: rgba(107, 114, 128, 0.2);
    color: #6b7280;
    border: 1px solid rgba(107, 114, 128, 0.3);
}

.match-info {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    gap: 20px;
}

.team {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
    flex: 1;
}

.team-name {
    font-size: 1.1rem;
    font-weight: 600;
    text-align: center;
    color: white;
}

.team-badge {
    font-size: 2rem;
    background: rgba(255,255,255,0.1);
    border-radius: 50%;
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    backdrop-filter: blur(10px);
}

.match-center {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
    min-width: 80px;
}

.match-time {
    font-size: 1.2rem;
    font-weight: 700;
    color: #6366f1;
}

.vs-text {
    font-size: 1rem;
    font-weight: 600;
    color: rgba(255,255,255,0.7);
}

.live-indicator {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 0.8rem;
    font-weight: 600;
    color: #10b981;
}

.live-dot {
    width: 8px;
    height: 8px;
    background: #10b981;
    border-radius: 50%;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}

.fixture-details {
    border-top: 1px solid rgba(255,255,255,0.1);
    padding-top: 15px;
    margin-bottom: 20px;
}

.venue,
.odds-preview {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 8px;
}

.detail-label {
    font-size: 0.85rem;
    color: rgba(255,255,255,0.7);
    font-weight: 500;
}

.detail-value {
    font-size: 0.9rem;
    color: white;
    font-weight: 600;
}

.card-actions {
    display: flex;
    gap: 10px;
}

.btn-details,
.btn-predictions {
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

.btn-predictions {
    background: #6366f1;
    color: white;
}

.btn-details:hover {
    background: rgba(255,255,255,0.2);
    color: white;
}

.btn-predictions:hover {
    background: #4f46e5;
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
    
    .filters {
        width: 100%;
    }
    
    .filter-select,
    .filter-date {
        flex: 1;
        min-width: auto;
    }
    
    .fixtures-grid {
        grid-template-columns: 1fr;
    }
    
    .match-info {
        flex-direction: column;
        gap: 15px;
    }
    
    .card-actions {
        flex-direction: column;
    }
}
</style>

<script>
// Filter functionality
document.addEventListener('DOMContentLoaded', function() {
    const filterSelects = document.querySelectorAll('.filter-select, .filter-date');
    const fixtureCards = document.querySelectorAll('.fixture-card');
    
    function filterFixtures() {
        const leagueFilter = document.querySelector('[data-filter="league"]').value;
        const statusFilter = document.querySelector('[data-filter="status"]').value;
        const dateFilter = document.querySelector('[data-filter="date"]').value;
        
        fixtureCards.forEach(card => {
            let show = true;
            
            if (leagueFilter && card.dataset.league !== leagueFilter) {
                show = false;
            }
            
            if (statusFilter && card.dataset.status !== statusFilter) {
                show = false;
            }
            
            if (dateFilter && card.dataset.date !== dateFilter) {
                show = false;
            }
            
            card.style.display = show ? 'block' : 'none';
        });
    }
    
    filterSelects.forEach(select => {
        select.addEventListener('change', filterFixtures);
    });
});
</script>

<?php wp_footer(); ?>
</body>
</html> 