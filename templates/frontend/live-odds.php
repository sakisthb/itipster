<?php
/**
 * Frontend Live Odds Template
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

$odds = isset($odds) ? $odds : array();
?>

<div class="itipster-live-odds glass-card">
    <div class="odds-header">
        <h2>📊 Live Odds</h2>
        <div class="odds-refresh">
            <button id="refresh-odds" class="glass-button">
                <span class="refresh-icon">🔄</span>
                Refresh
            </button>
            <span class="last-updated">
                Last updated: <span id="last-updated-time"><?php echo esc_html($odds['last_updated'] ?? 'Never'); ?></span>
            </span>
        </div>
    </div>
    
    <div class="odds-container" id="odds-container">
        <?php if (!empty($odds['bookmakers'])): ?>
            <div class="bookmakers-grid">
                <?php foreach ($odds['bookmakers'] as $bookmaker): ?>
                    <div class="bookmaker-card glass-card">
                        <div class="bookmaker-header">
                            <h3><?php echo esc_html($bookmaker['name']); ?></h3>
                            <span class="bookmaker-status online">Online</span>
                        </div>
                        
                        <div class="odds-grid">
                            <div class="odds-option">
                                <div class="odds-label">Home Win</div>
                                <div class="odds-value" data-odds="<?php echo esc_attr($bookmaker['odds']['home']); ?>">
                                    <?php echo esc_html($bookmaker['odds']['home']); ?>
                                </div>
                            </div>
                            
                            <div class="odds-option">
                                <div class="odds-label">Draw</div>
                                <div class="odds-value" data-odds="<?php echo esc_attr($bookmaker['odds']['draw']); ?>">
                                    <?php echo esc_html($bookmaker['odds']['draw']); ?>
                                </div>
                            </div>
                            
                            <div class="odds-option">
                                <div class="odds-label">Away Win</div>
                                <div class="odds-value" data-odds="<?php echo esc_attr($bookmaker['odds']['away']); ?>">
                                    <?php echo esc_html($bookmaker['odds']['away']); ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bookmaker-actions">
                            <button class="glass-button primary" onclick="placeBet('<?php echo esc_attr($bookmaker['name']); ?>', 'home', <?php echo esc_attr($bookmaker['odds']['home']); ?>)">
                                Bet Home
                            </button>
                            <button class="glass-button secondary" onclick="placeBet('<?php echo esc_attr($bookmaker['name']); ?>', 'draw', <?php echo esc_attr($bookmaker['odds']['draw']); ?>)">
                                Bet Draw
                            </button>
                            <button class="glass-button secondary" onclick="placeBet('<?php echo esc_attr($bookmaker['name']); ?>', 'away', <?php echo esc_attr($bookmaker['odds']['away']); ?>)">
                                Bet Away
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div class="odds-comparison">
                <h3>📈 Odds Comparison</h3>
                <div class="comparison-table">
                    <table>
                        <thead>
                            <tr>
                                <th>Bookmaker</th>
                                <th>Home</th>
                                <th>Draw</th>
                                <th>Away</th>
                                <th>Best Value</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $best_home = min(array_column($odds['bookmakers'], 'odds')['home']);
                            $best_draw = min(array_column($odds['bookmakers'], 'odds')['draw']);
                            $best_away = min(array_column($odds['bookmakers'], 'odds')['away']);
                            ?>
                            <?php foreach ($odds['bookmakers'] as $bookmaker): ?>
                                <tr>
                                    <td><?php echo esc_html($bookmaker['name']); ?></td>
                                    <td class="<?php echo $bookmaker['odds']['home'] == $best_home ? 'best-odds' : ''; ?>">
                                        <?php echo esc_html($bookmaker['odds']['home']); ?>
                                    </td>
                                    <td class="<?php echo $bookmaker['odds']['draw'] == $best_draw ? 'best-odds' : ''; ?>">
                                        <?php echo esc_html($bookmaker['odds']['draw']); ?>
                                    </td>
                                    <td class="<?php echo $bookmaker['odds']['away'] == $best_away ? 'best-odds' : ''; ?>">
                                        <?php echo esc_html($bookmaker['odds']['away']); ?>
                                    </td>
                                    <td>
                                        <?php
                                        $best_option = '';
                                        $best_value = 0;
                                        if ($bookmaker['odds']['home'] == $best_home) {
                                            $best_option = 'Home';
                                            $best_value = $bookmaker['odds']['home'];
                                        } elseif ($bookmaker['odds']['draw'] == $best_draw) {
                                            $best_option = 'Draw';
                                            $best_value = $bookmaker['odds']['draw'];
                                        } elseif ($bookmaker['odds']['away'] == $best_away) {
                                            $best_option = 'Away';
                                            $best_value = $bookmaker['odds']['away'];
                                        }
                                        if ($best_option) {
                                            echo '<span class="best-value">' . esc_html($best_option) . ' (' . esc_html($best_value) . ')</span>';
                                        }
                                        ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php else: ?>
            <div class="no-odds glass-card">
                <div class="no-data-icon">📊</div>
                <h3>No Live Odds Available</h3>
                <p>Live odds will appear here when the match starts.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    // Auto-refresh odds every 30 seconds
    var refreshInterval = setInterval(refreshOdds, 30000);
    
    // Manual refresh button
    $('#refresh-odds').on('click', function() {
        refreshOdds();
    });
    
    function refreshOdds() {
        var button = $('#refresh-odds');
        var originalText = button.html();
        
        button.prop('disabled', true).html('<span class="refresh-icon spinning">🔄</span> Refreshing...');
        
        $.ajax({
            url: itipster_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'itipster_get_live_odds',
                nonce: itipster_ajax.nonce,
                fixture_id: <?php echo esc_js($odds['fixture_id'] ?? 0); ?>
            },
            success: function(response) {
                if (response.success) {
                    updateOddsDisplay(response.data);
                    $('#last-updated-time').text(new Date().toLocaleTimeString());
                }
            },
            complete: function() {
                button.prop('disabled', false).html(originalText);
            }
        });
    }
    
    function updateOddsDisplay(newOdds) {
        // Update odds values with animation
        $('.odds-value').each(function() {
            var element = $(this);
            var currentOdds = parseFloat(element.text());
            var newOddsValue = parseFloat(element.attr('data-odds'));
            
            if (newOddsValue !== currentOdds) {
                element.addClass('odds-changed');
                setTimeout(function() {
                    element.removeClass('odds-changed');
                }, 2000);
            }
        });
    }
});

function placeBet(bookmaker, option, odds) {
    // Place bet functionality
    console.log('Placing bet with ' + bookmaker + ' on ' + option + ' at odds ' + odds);
    
    // This would typically open a betslip or redirect to bookmaker
    alert('Bet placed with ' + bookmaker + ' on ' + option + ' at odds ' + odds);
}
</script>

<style>
.itipster-live-odds {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

.odds-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    flex-wrap: wrap;
    gap: 20px;
}

.odds-header h2 {
    margin: 0;
    color: #1f2937;
}

.odds-refresh {
    display: flex;
    align-items: center;
    gap: 15px;
}

.refresh-icon {
    display: inline-block;
    transition: transform 0.3s ease;
}

.refresh-icon.spinning {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.last-updated {
    font-size: 14px;
    color: #6b7280;
}

.bookmakers-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.bookmaker-card {
    padding: 20px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.bookmaker-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
}

.bookmaker-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.bookmaker-header h3 {
    margin: 0;
    color: #1f2937;
    font-size: 18px;
}

.bookmaker-status {
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 500;
}

.bookmaker-status.online {
    background: rgba(16, 185, 129, 0.1);
    color: #10b981;
}

.odds-grid {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    gap: 15px;
    margin-bottom: 20px;
}

.odds-option {
    text-align: center;
    padding: 15px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 8px;
    transition: all 0.3s ease;
}

.odds-option:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: scale(1.05);
}

.odds-label {
    font-size: 12px;
    color: #6b7280;
    margin-bottom: 5px;
}

.odds-value {
    font-size: 20px;
    font-weight: bold;
    color: #1f2937;
    transition: all 0.3s ease;
}

.odds-value.odds-changed {
    background: rgba(239, 68, 68, 0.1);
    color: #ef4444;
    animation: pulse 0.5s ease;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
}

.bookmaker-actions {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    gap: 10px;
}

.glass-button {
    padding: 10px 15px;
    border: none;
    border-radius: 8px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
    font-size: 12px;
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

.odds-comparison {
    margin-top: 30px;
}

.odds-comparison h3 {
    margin-bottom: 20px;
    color: #1f2937;
}

.comparison-table {
    overflow-x: auto;
}

.comparison-table table {
    width: 100%;
    border-collapse: collapse;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 8px;
    overflow: hidden;
}

.comparison-table th,
.comparison-table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.comparison-table th {
    background: rgba(99, 102, 241, 0.1);
    color: #6366f1;
    font-weight: bold;
}

.comparison-table td.best-odds {
    background: rgba(16, 185, 129, 0.1);
    color: #10b981;
    font-weight: bold;
}

.best-value {
    background: rgba(16, 185, 129, 0.1);
    color: #10b981;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 500;
}

.no-odds {
    text-align: center;
    padding: 60px 20px;
}

.no-data-icon {
    font-size: 48px;
    margin-bottom: 20px;
}

.no-odds h3 {
    margin-bottom: 10px;
    color: #1f2937;
}

.no-odds p {
    color: #6b7280;
}

@media (max-width: 768px) {
    .odds-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .bookmakers-grid {
        grid-template-columns: 1fr;
    }
    
    .odds-grid {
        grid-template-columns: 1fr;
    }
    
    .bookmaker-actions {
        grid-template-columns: 1fr;
    }
}
</style> 