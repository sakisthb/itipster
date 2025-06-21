<?php
/**
 * Admin Analytics Template
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

$analytics_data = isset($analytics_data) ? $analytics_data : array();
?>

<div class="wrap itipster-admin-wrap">
    <h1><span class="dashicons dashicons-chart-area"></span> iTipster Pro Analytics</h1>
    
    <div class="itipster-analytics-grid">
        <div class="analytics-card">
            <h3>📊 Prediction Statistics</h3>
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-number"><?php echo isset($analytics_data['total_predictions']) ? $analytics_data['total_predictions'] : '0'; ?></div>
                    <div class="stat-label">Total Predictions</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number"><?php echo isset($analytics_data['avg_confidence']) ? $analytics_data['avg_confidence'] : '0'; ?>%</div>
                    <div class="stat-label">Avg Confidence</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number"><?php echo isset($analytics_data['avg_value_rating']) ? $analytics_data['avg_value_rating'] : '0'; ?>/10</div>
                    <div class="stat-label">Avg Value Rating</div>
                </div>
            </div>
        </div>
        
        <div class="analytics-card">
            <h3>👥 User Statistics</h3>
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-number"><?php echo isset($analytics_data['total_users']) ? $analytics_data['total_users'] : '0'; ?></div>
                    <div class="stat-label">Total Users</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number"><?php echo isset($analytics_data['active_subscriptions']) ? $analytics_data['active_subscriptions'] : '0'; ?></div>
                    <div class="stat-label">Active Subscriptions</div>
                </div>
            </div>
        </div>
        
        <div class="analytics-card full-width">
            <h3>📈 Recent Activity</h3>
            <div class="recent-activity">
                <?php if (isset($analytics_data['recent_predictions']) && !empty($analytics_data['recent_predictions'])): ?>
                    <table class="wp-list-table widefat fixed striped">
                        <thead>
                            <tr>
                                <th>Fixture ID</th>
                                <th>Prediction Type</th>
                                <th>Confidence</th>
                                <th>Value Rating</th>
                                <th>Created</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($analytics_data['recent_predictions'] as $prediction): ?>
                                <tr>
                                    <td><?php echo esc_html($prediction->fixture_id); ?></td>
                                    <td><?php echo esc_html($prediction->prediction_type); ?></td>
                                    <td><?php echo esc_html($prediction->confidence_score); ?>%</td>
                                    <td><?php echo esc_html($prediction->value_rating); ?>/10</td>
                                    <td><?php echo esc_html($prediction->created_at); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No recent predictions found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
.itipster-admin-wrap {
    background: #f1f1f1;
    padding: 20px;
    margin: 20px 0;
    border-radius: 8px;
}

.itipster-analytics-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.analytics-card {
    background: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.analytics-card.full-width {
    grid-column: 1 / -1;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
    gap: 15px;
    margin-top: 15px;
}

.stat-item {
    text-align: center;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 6px;
}

.stat-number {
    font-size: 24px;
    font-weight: bold;
    color: #6366f1;
}

.stat-label {
    font-size: 12px;
    color: #666;
    margin-top: 5px;
}

.recent-activity {
    margin-top: 15px;
}

.recent-activity table {
    width: 100%;
    border-collapse: collapse;
}

.recent-activity th,
.recent-activity td {
    padding: 10px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

.recent-activity th {
    background: #f8f9fa;
    font-weight: bold;
}
</style> 