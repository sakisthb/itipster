<?php
/**
 * Professional Frontend Dashboard
 */
?>

<div class="itipster-pro-dashboard">
    <!-- Header με Live Stats -->
    <div class="dashboard-hero">
        <div class="hero-content">
            <h1>iTipster Pro Predictions</h1>
            <div class="live-stats">
                <div class="stat-item">
                    <span class="stat-number" data-target="847">0</span>
                    <span class="stat-label">Live Predictions</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number" data-target="84.7">0</span>
                    <span class="stat-label">Success Rate %</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number" data-target="18.3">0</span>
                    <span class="stat-label">Avg ROI %</span>
                </div>
            </div>
        </div>
        <div class="hero-visual">
            <canvas id="live-chart"></canvas>
        </div>
    </div>
    
    <!-- Advanced Filters -->
    <div class="filters-section">
        <div class="filters-container">
            <div class="filter-group">
                <label>League</label>
                <select class="filter-select" data-filter="league">
                    <option value="">All Leagues</option>
                    <option value="premier-league">Premier League</option>
                    <option value="la-liga">La Liga</option>
                    <option value="bundesliga">Bundesliga</option>
                    <option value="serie-a">Serie A</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label>Market</label>
                <select class="filter-select" data-filter="market">
                    <option value="">All Markets</option>
                    <option value="1x2">1X2</option>
                    <option value="over-under">Over/Under</option>
                    <option value="btts">Both Teams Score</option>
                    <option value="handicap">Asian Handicap</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label>Min Confidence</label>
                <input type="range" class="confidence-slider" min="50" max="95" value="70" data-filter="confidence">
                <span class="confidence-value">70%</span>
            </div>
            
            <div class="filter-group">
                <label>Min Value</label>
                <input type="range" class="value-slider" min="5" max="10" value="7" step="0.1" data-filter="value">
                <span class="value-rating">7.0</span>
            </div>
            
            <div class="filter-actions">
                <button class="btn-reset-filters">Reset</button>
                <button class="btn-save-filters">Save</button>
            </div>
        </div>
    </div>
    
    <!-- Predictions Grid -->
    <div class="predictions-container">
        <div class="predictions-header">
            <h2>Live Predictions</h2>
            <div class="view-controls">
                <button class="view-btn active" data-view="cards">Cards</button>
                <button class="view-btn" data-view="table">Table</button>
                <button class="view-btn" data-view="compact">Compact</button>
            </div>
        </div>
        
        <div class="predictions-grid" id="predictions-grid">
            <!-- Predictions will be loaded here via AJAX -->
        </div>
        
        <div class="load-more-container">
            <button class="btn-load-more">Load More Predictions</button>
        </div>
    </div>
    
    <!-- Personal Stats Sidebar -->
    <div class="personal-stats-sidebar">
        <div class="stats-card">
            <h3>Your Performance</h3>
            <div class="personal-metrics">
                <div class="metric">
                    <span class="metric-label">Total Bets</span>
                    <span class="metric-value">156</span>
                </div>
                <div class="metric">
                    <span class="metric-label">Win Rate</span>
                    <span class="metric-value success">78.2%</span>
                </div>
                <div class="metric">
                    <span class="metric-label">Total Profit</span>
                    <span class="metric-value profit">+€1,247</span>
                </div>
                <div class="metric">
                    <span class="metric-label">Avg Odds</span>
                    <span class="metric-value">2.15</span>
                </div>
            </div>
            <canvas id="personal-chart"></canvas>
        </div>
        
        <div class="watchlist-card">
            <h3>Watchlist</h3>
            <div class="watchlist-items">
                <!-- Watchlist predictions -->
            </div>
        </div>
    </div>
</div>

<!-- The rest of the JavaScript and CSS is as provided in your prompt, including AJAX, animated counters, glassmorphism, and mobile-first design. --> 