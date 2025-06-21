<?php
/**
 * Frontend User Profile Template
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

$user_data = isset($user_data) ? $user_data : array();
$subscription = isset($subscription) ? $subscription : array();
$betting_history = isset($betting_history) ? $betting_history : array();
?>

<div class="itipster-user-profile glass-card">
    <div class="profile-header">
        <h2>👤 User Profile</h2>
        <div class="profile-actions">
            <button id="edit-profile" class="glass-button">Edit Profile</button>
            <button id="change-password" class="glass-button secondary">Change Password</button>
        </div>
    </div>
    
    <div class="profile-grid">
        <div class="profile-section glass-card">
            <h3>📋 Personal Information</h3>
            <div class="profile-info">
                <div class="info-item">
                    <span class="info-label">Username:</span>
                    <span class="info-value"><?php echo esc_html($user_data['username'] ?? ''); ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Email:</span>
                    <span class="info-value"><?php echo esc_html($user_data['email'] ?? ''); ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">First Name:</span>
                    <span class="info-value"><?php echo esc_html($user_data['first_name'] ?? ''); ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Last Name:</span>
                    <span class="info-value"><?php echo esc_html($user_data['last_name'] ?? ''); ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Member Since:</span>
                    <span class="info-value"><?php echo esc_html($user_data['registration_date'] ?? ''); ?></span>
                </div>
            </div>
        </div>
        
        <div class="profile-section glass-card">
            <h3>💎 Subscription Status</h3>
            <div class="subscription-info">
                <?php if (!empty($subscription)): ?>
                    <div class="subscription-status active">
                        <div class="status-icon">✅</div>
                        <div class="status-details">
                            <div class="status-title">Active Subscription</div>
                            <div class="status-plan"><?php echo esc_html($subscription['plan_name'] ?? ''); ?></div>
                            <div class="status-expiry">Expires: <?php echo esc_html($subscription['expiry_date'] ?? ''); ?></div>
                        </div>
                    </div>
                    
                    <div class="subscription-features">
                        <h4>Plan Features:</h4>
                        <ul>
                            <?php if (isset($subscription['features'])): ?>
                                <?php foreach ($subscription['features'] as $feature): ?>
                                    <li>✅ <?php echo esc_html($feature); ?></li>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </ul>
                    </div>
                    
                    <div class="subscription-actions">
                        <button class="glass-button primary" onclick="manageSubscription()">Manage Subscription</button>
                        <button class="glass-button secondary" onclick="upgradePlan()">Upgrade Plan</button>
                    </div>
                <?php else: ?>
                    <div class="subscription-status inactive">
                        <div class="status-icon">❌</div>
                        <div class="status-details">
                            <div class="status-title">No Active Subscription</div>
                            <div class="status-description">Upgrade to access premium predictions and features</div>
                        </div>
                    </div>
                    
                    <div class="subscription-actions">
                        <button class="glass-button primary" onclick="subscribeNow()">Subscribe Now</button>
                        <button class="glass-button secondary" onclick="viewPlans()">View Plans</button>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="profile-section glass-card full-width">
            <h3>📊 Betting Statistics</h3>
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number"><?php echo esc_html($user_data['total_bets'] ?? '0'); ?></div>
                    <div class="stat-label">Total Bets</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo esc_html($user_data['win_rate'] ?? '0'); ?>%</div>
                    <div class="stat-label">Win Rate</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo esc_html($user_data['total_profit'] ?? '0'); ?></div>
                    <div class="stat-label">Total Profit</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo esc_html($user_data['avg_odds'] ?? '0'); ?></div>
                    <div class="stat-label">Average Odds</div>
                </div>
            </div>
        </div>
        
        <div class="profile-section glass-card full-width">
            <h3>📈 Recent Betting History</h3>
            <div class="betting-history">
                <?php if (!empty($betting_history)): ?>
                    <div class="history-table">
                        <table>
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Fixture</th>
                                    <th>Prediction</th>
                                    <th>Odds</th>
                                    <th>Stake</th>
                                    <th>Result</th>
                                    <th>Profit/Loss</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($betting_history as $bet): ?>
                                    <tr class="bet-row <?php echo $bet['result'] === 'Won' ? 'bet-won' : ($bet['result'] === 'Lost' ? 'bet-lost' : 'bet-pending'); ?>">
                                        <td><?php echo esc_html($bet['date']); ?></td>
                                        <td><?php echo esc_html($bet['fixture']); ?></td>
                                        <td><?php echo esc_html($bet['prediction']); ?></td>
                                        <td><?php echo esc_html($bet['odds']); ?></td>
                                        <td><?php echo esc_html($bet['stake']); ?></td>
                                        <td>
                                            <span class="result-badge <?php echo $bet['result'] === 'Won' ? 'won' : ($bet['result'] === 'Lost' ? 'lost' : 'pending'); ?>">
                                                <?php echo esc_html($bet['result']); ?>
                                            </span>
                                        </td>
                                        <td class="<?php echo $bet['profit_loss'] >= 0 ? 'positive' : 'negative'; ?>">
                                            <?php echo esc_html($bet['profit_loss']); ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="no-history">
                        <div class="no-data-icon">📈</div>
                        <h4>No Betting History</h4>
                        <p>Start placing bets to see your history here.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Edit Profile Modal -->
<div id="edit-profile-modal" class="modal">
    <div class="modal-content glass-card">
        <div class="modal-header">
            <h3>Edit Profile</h3>
            <span class="close">&times;</span>
        </div>
        <form id="edit-profile-form">
            <div class="form-group">
                <label for="edit-first-name">First Name</label>
                <input type="text" id="edit-first-name" name="first_name" value="<?php echo esc_attr($user_data['first_name'] ?? ''); ?>" class="glass-input">
            </div>
            <div class="form-group">
                <label for="edit-last-name">Last Name</label>
                <input type="text" id="edit-last-name" name="last_name" value="<?php echo esc_attr($user_data['last_name'] ?? ''); ?>" class="glass-input">
            </div>
            <div class="form-group">
                <label for="edit-email">Email</label>
                <input type="email" id="edit-email" name="email" value="<?php echo esc_attr($user_data['email'] ?? ''); ?>" class="glass-input">
            </div>
            <div class="form-actions">
                <button type="submit" class="glass-button primary">Save Changes</button>
                <button type="button" class="glass-button secondary" onclick="closeModal()">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    // Edit profile modal
    $('#edit-profile').on('click', function() {
        $('#edit-profile-modal').show();
    });
    
    $('.close').on('click', function() {
        closeModal();
    });
    
    $(window).on('click', function(event) {
        if (event.target == $('#edit-profile-modal')[0]) {
            closeModal();
        }
    });
    
    // Edit profile form submission
    $('#edit-profile-form').on('submit', function(e) {
        e.preventDefault();
        
        var formData = $(this).serialize();
        
        $.ajax({
            url: itipster_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'itipster_update_profile',
                nonce: itipster_ajax.nonce,
                form_data: formData
            },
            success: function(response) {
                if (response.success) {
                    alert('Profile updated successfully!');
                    location.reload();
                } else {
                    alert('Error updating profile: ' + response.data);
                }
            },
            error: function() {
                alert('Error updating profile. Please try again.');
            }
        });
    });
    
    // Change password
    $('#change-password').on('click', function() {
        var newPassword = prompt('Enter new password:');
        if (newPassword) {
            var confirmPassword = prompt('Confirm new password:');
            if (newPassword === confirmPassword) {
                $.ajax({
                    url: itipster_ajax.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'itipster_change_password',
                        nonce: itipster_ajax.nonce,
                        new_password: newPassword
                    },
                    success: function(response) {
                        if (response.success) {
                            alert('Password changed successfully!');
                        } else {
                            alert('Error changing password: ' + response.data);
                        }
                    },
                    error: function() {
                        alert('Error changing password. Please try again.');
                    }
                });
            } else {
                alert('Passwords do not match!');
            }
        }
    });
});

function closeModal() {
    $('#edit-profile-modal').hide();
}

function manageSubscription() {
    // Redirect to subscription management page
    window.location.href = '<?php echo esc_url(home_url('/subscription-management')); ?>';
}

function upgradePlan() {
    // Redirect to plan upgrade page
    window.location.href = '<?php echo esc_url(home_url('/upgrade-plan')); ?>';
}

function subscribeNow() {
    // Redirect to subscription page
    window.location.href = '<?php echo esc_url(home_url('/subscribe')); ?>';
}

function viewPlans() {
    // Redirect to plans page
    window.location.href = '<?php echo esc_url(home_url('/plans')); ?>';
}
</script>

<style>
.itipster-user-profile {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

.profile-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    flex-wrap: wrap;
    gap: 20px;
}

.profile-header h2 {
    margin: 0;
    color: #1f2937;
}

.profile-actions {
    display: flex;
    gap: 10px;
}

.profile-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 20px;
}

.profile-section {
    padding: 20px;
}

.profile-section.full-width {
    grid-column: 1 / -1;
}

.profile-section h3 {
    margin-top: 0;
    margin-bottom: 20px;
    color: #1f2937;
    border-bottom: 2px solid #6366f1;
    padding-bottom: 10px;
}

.profile-info {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.info-item {
    display: flex;
    justify-content: space-between;
    padding: 10px 0;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.info-label {
    font-weight: 500;
    color: #6b7280;
}

.info-value {
    color: #1f2937;
    font-weight: 500;
}

.subscription-status {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 20px;
}

.subscription-status.active {
    background: rgba(16, 185, 129, 0.1);
    border: 1px solid rgba(16, 185, 129, 0.2);
}

.subscription-status.inactive {
    background: rgba(239, 68, 68, 0.1);
    border: 1px solid rgba(239, 68, 68, 0.2);
}

.status-icon {
    font-size: 24px;
}

.status-title {
    font-weight: bold;
    color: #1f2937;
    margin-bottom: 5px;
}

.status-plan {
    color: #6366f1;
    font-weight: 500;
    margin-bottom: 5px;
}

.status-expiry,
.status-description {
    color: #6b7280;
    font-size: 14px;
}

.subscription-features {
    margin-bottom: 20px;
}

.subscription-features h4 {
    margin-bottom: 10px;
    color: #1f2937;
}

.subscription-features ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.subscription-features li {
    padding: 5px 0;
    color: #4b5563;
}

.subscription-actions {
    display: flex;
    gap: 10px;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 20px;
}

.stat-card {
    text-align: center;
    padding: 20px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 8px;
}

.stat-number {
    font-size: 24px;
    font-weight: bold;
    color: #6366f1;
    margin-bottom: 5px;
}

.stat-label {
    color: #6b7280;
    font-size: 14px;
}

.betting-history {
    margin-top: 20px;
}

.history-table {
    overflow-x: auto;
}

.history-table table {
    width: 100%;
    border-collapse: collapse;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 8px;
    overflow: hidden;
}

.history-table th,
.history-table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.history-table th {
    background: rgba(99, 102, 241, 0.1);
    color: #6366f1;
    font-weight: bold;
}

.bet-row.bet-won {
    background: rgba(16, 185, 129, 0.05);
}

.bet-row.bet-lost {
    background: rgba(239, 68, 68, 0.05);
}

.bet-row.bet-pending {
    background: rgba(245, 158, 11, 0.05);
}

.result-badge {
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 500;
}

.result-badge.won {
    background: rgba(16, 185, 129, 0.1);
    color: #10b981;
}

.result-badge.lost {
    background: rgba(239, 68, 68, 0.1);
    color: #ef4444;
}

.result-badge.pending {
    background: rgba(245, 158, 11, 0.1);
    color: #f59e0b;
}

.positive {
    color: #10b981;
    font-weight: bold;
}

.negative {
    color: #ef4444;
    font-weight: bold;
}

.no-history {
    text-align: center;
    padding: 40px 20px;
}

.no-data-icon {
    font-size: 48px;
    margin-bottom: 20px;
}

.no-history h4 {
    margin-bottom: 10px;
    color: #1f2937;
}

.no-history p {
    color: #6b7280;
}

/* Modal Styles */
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(5px);
}

.modal-content {
    background: white;
    margin: 5% auto;
    padding: 0;
    border-radius: 12px;
    width: 90%;
    max-width: 500px;
    animation: modalSlideIn 0.3s ease;
}

@keyframes modalSlideIn {
    from {
        opacity: 0;
        transform: translateY(-50px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.modal-header h3 {
    margin: 0;
    color: #1f2937;
}

.close {
    color: #6b7280;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
    transition: color 0.3s ease;
}

.close:hover {
    color: #1f2937;
}

.form-group {
    margin-bottom: 20px;
    padding: 0 20px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    color: #1f2937;
    font-weight: 500;
}

.glass-input {
    width: 100%;
    padding: 10px 15px;
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 8px;
    background: rgba(255, 255, 255, 0.1);
    color: #1f2937;
    font-size: 14px;
}

.form-actions {
    display: flex;
    gap: 10px;
    padding: 20px;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
}

.glass-button {
    padding: 10px 20px;
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

@media (max-width: 768px) {
    .profile-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .profile-grid {
        grid-template-columns: 1fr;
    }
    
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .subscription-actions {
        flex-direction: column;
    }
    
    .form-actions {
        flex-direction: column;
    }
}
</style> 