<?php
/**
 * Frontend Predictions Dashboard Template
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

$predictions = isset($predictions) ? $predictions : array();
?>

<div class="itipster-predictions-dashboard glass-card">
    <div class="dashboard-header">
        <h2>🎯 AI Predictions Dashboard</h2>
        <div class="dashboard-filters">
            <select id="league-filter" class="glass-select">
                <option value="">All Leagues</option>
                <option value="Premier League">Premier League</option>
                <option value="La Liga">La Liga</option>
                <option value="Bundesliga">Bundesliga</option>
                <option value="Serie A">Serie A</option>
                <option value="Ligue 1">Ligue 1</option>
            </select>
            <select id="market-filter" class="glass-select">
                <option value="">All Markets</option>
                <option value="Match Winner">Match Winner</option>
                <option value="Total Goals">Total Goals</option>
                <option value="Both Teams to Score">Both Teams to Score</option>
                <option value="Over/Under">Over/Under</option>
            </select>
            <input type="number" id="confidence-filter" class="glass-input" placeholder="Min Confidence %" min="0" max="100">
        </div>
    </div>
    
    <div class="predictions-grid" id="predictions-container">
        <?php if (!empty($predictions['predictions'])): ?>
            <?php foreach ($predictions['predictions'] as $prediction): ?>
                <div class="prediction-card glass-card" data-league="<?php echo esc_attr($prediction['league'] ?? ''); ?>" data-market="<?php echo esc_attr($prediction['market'] ?? ''); ?>" data-confidence="<?php echo esc_attr($prediction['confidence'] ?? 0); ?>">
                    <div class="prediction-header">
                        <div class="fixture-info">
                            <h3><?php echo esc_html($prediction['home_team'] ?? ''); ?> vs <?php echo esc_html($prediction['away_team'] ?? ''); ?></h3>
                            <span class="league-badge"><?php echo esc_html($prediction['league'] ?? ''); ?></span>
                        </div>
                        <div class="confidence-badge" style="background: <?php echo $prediction['confidence'] >= 80 ? '#10b981' : ($prediction['confidence'] >= 70 ? '#f59e0b' : '#ef4444'); ?>">
                            <?php echo esc_html($prediction['confidence'] ?? 0); ?>%
                        </div>
                    </div>
                    
                    <div class="prediction-details">
                        <div class="prediction-main">
                            <div class="prediction-type"><?php echo esc_html($prediction['market'] ?? ''); ?></div>
                            <div class="prediction-value"><?php echo esc_html($prediction['prediction'] ?? ''); ?></div>
                        </div>
                        
                        <div class="prediction-stats">
                            <div class="stat-item">
                                <span class="stat-label">Odds:</span>
                                <span class="stat-value"><?php echo esc_html($prediction['odds'] ?? ''); ?></span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-label">Value Rating:</span>
                                <span class="stat-value"><?php echo esc_html($prediction['value_rating'] ?? ''); ?>/10</span>
                            </div>
                        </div>
                        
                        <div class="prediction-analysis">
                            <p><?php echo esc_html($prediction['analysis'] ?? ''); ?></p>
                        </div>
                        
                        <?php if (isset($prediction['trends'])): ?>
                            <div class="prediction-trends">
                                <div class="trend-item">
                                    <span class="trend-label">Form:</span>
                                    <span class="trend-value"><?php echo esc_html($prediction['trends']['home_form'] ?? ''); ?> vs <?php echo esc_html($prediction['trends']['away_form'] ?? ''); ?></span>
                                </div>
                                <div class="trend-item">
                                    <span class="trend-label">H2H:</span>
                                    <span class="trend-value"><?php echo esc_html($prediction['trends']['h2h'] ?? ''); ?></span>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="prediction-actions">
                        <button class="glass-button primary" onclick="addToBetslip(<?php echo esc_attr($prediction['id'] ?? 0); ?>)">
                            Add to Betslip
                        </button>
                        <button class="glass-button secondary" onclick="viewDetails(<?php echo esc_attr($prediction['id'] ?? 0); ?>)">
                            View Details
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="no-predictions glass-card">
                <div class="no-data-icon">🎯</div>
                <h3>No Predictions Available</h3>
                <p>Check back later for new AI-powered predictions.</p>
            </div>
        <?php endif; ?>
    </div>
    
    <div class="dashboard-footer">
        <div class="pagination">
            <button class="glass-button" id="load-more">Load More Predictions</button>
        </div>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    // Filter functionality
    $('#league-filter, #market-filter').on('change', filterPredictions);
    $('#confidence-filter').on('input', filterPredictions);
    
    function filterPredictions() {
        var league = $('#league-filter').val();
        var market = $('#market-filter').val();
        var confidence = $('#confidence-filter').val();
        
        $('.prediction-card').each(function() {
            var card = $(this);
            var cardLeague = card.data('league');
            var cardMarket = card.data('market');
            var cardConfidence = card.data('confidence');
            
            var showCard = true;
            
            if (league && cardLeague !== league) showCard = false;
            if (market && cardMarket !== market) showCard = false;
            if (confidence && cardConfidence < confidence) showCard = false;
            
            card.toggle(showCard);
        });
    }
    
    // Load more functionality
    $('#load-more').on('click', function() {
        var button = $(this);
        button.prop('disabled', true).text('Loading...');
        
        $.ajax({
            url: itipster_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'itipster_get_predictions',
                nonce: itipster_ajax.nonce,
                offset: $('.prediction-card').length
            },
            success: function(response) {
                if (response.success && response.data.predictions) {
                    // Add new predictions to the grid
                    response.data.predictions.forEach(function(prediction) {
                        // Create prediction card HTML and append
                        var cardHtml = createPredictionCard(prediction);
                        $('#predictions-container').append(cardHtml);
                    });
                }
            },
            complete: function() {
                button.prop('disabled', false).text('Load More Predictions');
            }
        });
    });
});

function createPredictionCard(prediction) {
    // This function would create the HTML for a prediction card
    // Implementation would be similar to the PHP template above
    return '<div class="prediction-card glass-card">...</div>';
}

function addToBetslip(predictionId) {
    // Add prediction to betslip functionality
    console.log('Adding prediction ' + predictionId + ' to betslip');
}

function viewDetails(predictionId) {
    // View prediction details functionality
    console.log('Viewing details for prediction ' + predictionId);
}
</script>

<style>
.itipster-predictions-dashboard {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

.dashboard-header {
    margin-bottom: 30px;
}

.dashboard-header h2 {
    margin-bottom: 20px;
    color: #1f2937;
}

.dashboard-filters {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
}

.glass-select,
.glass-input {
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 8px;
    padding: 10px 15px;
    backdrop-filter: blur(10px);
    color: #1f2937;
}

.predictions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.prediction-card {
    padding: 20px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.prediction-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
}

.prediction-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 15px;
}

.fixture-info h3 {
    margin: 0 0 5px 0;
    font-size: 16px;
    color: #1f2937;
}

.league-badge {
    background: rgba(99, 102, 241, 0.1);
    color: #6366f1;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 500;
}

.confidence-badge {
    color: white;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 14px;
    font-weight: bold;
}

.prediction-main {
    margin-bottom: 15px;
}

.prediction-type {
    font-size: 14px;
    color: #6b7280;
    margin-bottom: 5px;
}

.prediction-value {
    font-size: 18px;
    font-weight: bold;
    color: #1f2937;
}

.prediction-stats {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
    margin-bottom: 15px;
}

.stat-item {
    display: flex;
    justify-content: space-between;
    padding: 8px 12px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 6px;
}

.stat-label {
    color: #6b7280;
    font-size: 12px;
}

.stat-value {
    font-weight: bold;
    color: #1f2937;
}

.prediction-analysis {
    margin-bottom: 15px;
}

.prediction-analysis p {
    font-size: 14px;
    color: #4b5563;
    line-height: 1.5;
    margin: 0;
}

.prediction-trends {
    margin-bottom: 15px;
}

.trend-item {
    display: flex;
    justify-content: space-between;
    margin-bottom: 5px;
    font-size: 12px;
}

.trend-label {
    color: #6b7280;
}

.trend-value {
    color: #1f2937;
    font-weight: 500;
}

.prediction-actions {
    display: flex;
    gap: 10px;
}

.glass-button {
    flex: 1;
    padding: 10px 15px;
    border: none;
    border-radius: 8px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
}

.glass-button.primary {
    background: rgba(99, 102, 241, 0.1);
    color: #6366f1;
    border: 1px solid rgba(99, 102, 241, 0.2);
}

.glass-button.secondary {
    background: rgba(107, 114, 128, 0.1);
    color: #6b7280;
    border: 1px solid rgba(107, 114, 128, 0.2);
}

.glass-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
}

.no-predictions {
    text-align: center;
    padding: 60px 20px;
    grid-column: 1 / -1;
}

.no-data-icon {
    font-size: 48px;
    margin-bottom: 20px;
}

.no-predictions h3 {
    margin-bottom: 10px;
    color: #1f2937;
}

.no-predictions p {
    color: #6b7280;
}

.dashboard-footer {
    text-align: center;
}

.pagination {
    margin-top: 30px;
}
</style> 