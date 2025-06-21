<?php
/**
 * Premium Admin Dashboard Template
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="itipster-premium-admin">
    <div class="admin-background">
        <div class="floating-particles"></div>
    </div>
    
    <div class="admin-container">
        <div class="admin-header">
            <h1><span class="icon">🎯</span> iTipster Pro Dashboard</h1>
            <p class="subtitle">Premium Sports Predictions Platform</p>
        </div>
        
        <div class="dashboard-grid">
            <div class="glass-card stats-card">
                <div class="card-header">
                    <h3><span class="emoji">📊</span> Live Statistics</h3>
                    <div class="status-badge active">Live</div>
                </div>
                <div class="stats-grid">
                    <div class="stat-item">
                        <div class="stat-number">100</div>
                        <div class="stat-label">Demo Fixtures</div>
                        <div class="stat-trend up">+12%</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">85.3%</div>
                        <div class="stat-label">Success Rate</div>
                        <div class="stat-trend up">+5.2%</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">8.7</div>
                        <div class="stat-label">Avg Rating</div>
                        <div class="stat-trend up">+0.3</div>
                    </div>
                </div>
            </div>
            
            <div class="glass-card actions-card">
                <div class="card-header">
                    <h3><span class="emoji">⚡</span> Quick Actions</h3>
                </div>
                <div class="actions-grid">
                    <a href="<?php echo admin_url('admin.php?page=itipster-settings'); ?>" class="action-btn primary">
                        <span class="btn-icon">⚙️</span>
                        <span class="btn-text">Settings</span>
                        <span class="btn-arrow">→</span>
                    </a>
                    <a href="<?php echo admin_url('edit.php?post_type=itipster_prediction'); ?>" class="action-btn secondary">
                        <span class="btn-icon">🔮</span>
                        <span class="btn-text">Predictions</span>
                        <span class="btn-arrow">→</span>
                    </a>
                    <a href="<?php echo admin_url('edit.php?post_type=itipster_fixture'); ?>" class="action-btn tertiary">
                        <span class="btn-icon">⚽</span>
                        <span class="btn-text">Fixtures</span>
                        <span class="btn-arrow">→</span>
                    </a>
                </div>
            </div>
            
            <div class="glass-card api-status-card">
                <div class="card-header">
                    <h3><span class="emoji">🌐</span> API Status</h3>
                </div>
                <div class="api-status">
                    <div class="status-indicator demo">
                        <div class="status-dot"></div>
                        <span>Demo Mode Active</span>
                    </div>
                    <div class="api-details">
                        <div class="detail-item">
                            <span class="label">Requests Today:</span>
                            <span class="value">0/100</span>
                        </div>
                        <div class="detail-item">
                            <span class="label">Last Update:</span>
                            <span class="value">Just now</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="glass-card premium-features-card">
                <div class="card-header">
                    <h3><span class="emoji">✨</span> Premium Features</h3>
                </div>
                <div class="features-list">
                    <div class="feature-item active">
                        <span class="feature-icon">✅</span>
                        <span class="feature-text">Glassmorphism UI</span>
                    </div>
                    <div class="feature-item active">
                        <span class="feature-icon">✅</span>
                        <span class="feature-text">Demo Data Loaded</span>
                    </div>
                    <div class="feature-item active">
                        <span class="feature-icon">✅</span>
                        <span class="feature-text">Modern Analytics</span>
                    </div>
                    <div class="feature-item pending">
                        <span class="feature-icon">🔄</span>
                        <span class="feature-text">Live API Integration</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Premium Glassmorphism Admin Styles */
.itipster-premium-admin {
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f172a 100%);
    min-height: 100vh;
    padding: 40px 20px;
    position: relative;
    overflow: hidden;
    margin: -20px;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.admin-background {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    overflow: hidden;
    z-index: 1;
}

.floating-particles::before,
.floating-particles::after {
    content: '';
    position: absolute;
    width: 200px;
    height: 200px;
    background: radial-gradient(circle, rgba(99, 102, 241, 0.1) 0%, transparent 70%);
    border-radius: 50%;
    animation: float 20s infinite ease-in-out;
}

.floating-particles::before {
    top: 10%;
    left: 10%;
    animation-delay: 0s;
}

.floating-particles::after {
    top: 60%;
    right: 10%;
    animation-delay: -10s;
    background: radial-gradient(circle, rgba(139, 92, 246, 0.1) 0%, transparent 70%);
}

@keyframes float {
    0%, 100% { transform: translateY(0px) scale(1); }
    50% { transform: translateY(-20px) scale(1.1); }
}

.admin-container {
    position: relative;
    z-index: 2;
    max-width: 1400px;
    margin: 0 auto;
}

.admin-header {
    text-align: center;
    margin-bottom: 40px;
}

.admin-header h1 {
    color: white;
    font-size: 3rem;
    font-weight: 800;
    margin: 0;
    text-shadow: 0 4px 20px rgba(0,0,0,0.3);
    background: linear-gradient(135deg, #6366f1, #8b5cf6, #10b981);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.admin-header .subtitle {
    color: rgba(255,255,255,0.8);
    font-size: 1.2rem;
    margin: 10px 0 0 0;
    font-weight: 500;
}

.dashboard-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 30px;
}

.glass-card {
    background: rgba(255, 255, 255, 0.08);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.15);
    border-radius: 24px;
    padding: 30px;
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    position: relative;
    overflow: hidden;
}

.glass-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 25px 50px rgba(0,0,0,0.3);
    border-color: rgba(99, 102, 241, 0.4);
}

.glass-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 2px;
    background: linear-gradient(90deg, #6366f1, #8b5cf6, #10b981);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.glass-card:hover::before {
    opacity: 1;
}

.card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
}

.card-header h3 {
    color: white;
    font-size: 1.4rem;
    font-weight: 700;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.status-badge {
    background: rgba(16, 185, 129, 0.2);
    color: #10b981;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    border: 1px solid rgba(16, 185, 129, 0.3);
    animation: pulse 2s infinite;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    gap: 20px;
}

.stat-item {
    text-align: center;
    padding: 20px;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 16px;
    border: 1px solid rgba(255, 255, 255, 0.1);
    transition: all 0.3s ease;
}

.stat-item:hover {
    background: rgba(255, 255, 255, 0.1);
    transform: scale(1.05);
}

.stat-number {
    font-size: 2rem;
    font-weight: 800;
    color: #6366f1;
    margin-bottom: 8px;
}

.stat-label {
    color: rgba(255, 255, 255, 0.8);
    font-size: 0.9rem;
    margin-bottom: 5px;
}

.stat-trend {
    font-size: 0.8rem;
    font-weight: 600;
    padding: 3px 8px;
    border-radius: 12px;
}

.stat-trend.up {
    color: #10b981;
    background: rgba(16, 185, 129, 0.1);
}

.actions-grid {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.action-btn {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 18px 24px;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 16px;
    color: white;
    text-decoration: none;
    transition: all 0.3s ease;
    font-weight: 600;
}

.action-btn:hover {
    background: rgba(255, 255, 255, 0.1);
    transform: translateX(10px);
    border-color: rgba(99, 102, 241, 0.4);
    color: white;
}

.action-btn.primary:hover {
    box-shadow: 0 0 20px rgba(99, 102, 241, 0.3);
}

.btn-icon {
    font-size: 1.2rem;
}

.btn-arrow {
    font-size: 1.1rem;
    opacity: 0.6;
    transition: all 0.3s ease;
}

.action-btn:hover .btn-arrow {
    opacity: 1;
    transform: translateX(5px);
}

.api-status {
    color: white;
}

.status-indicator {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 20px;
    font-weight: 600;
}

.status-dot {
    width: 12px;
    height: 12px;
    background: #10b981;
    border-radius: 50%;
    animation: pulse 2s infinite;
}

.api-details {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.detail-item {
    display: flex;
    justify-content: space-between;
    padding: 12px;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 10px;
}

.label {
    color: rgba(255, 255, 255, 0.7);
}

.value {
    color: #6366f1;
    font-weight: 600;
}

.features-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.feature-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 12px;
    color: white;
    transition: all 0.3s ease;
}

.feature-item:hover {
    background: rgba(255, 255, 255, 0.1);
}

.feature-item.active .feature-icon {
    color: #10b981;
}

.feature-item.pending .feature-icon {
    color: #f59e0b;
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}

/* Responsive */
@media (max-width: 768px) {
    .dashboard-grid {
        grid-template-columns: 1fr;
        gap: 20px;
    }
    
    .admin-header h1 {
        font-size: 2rem;
    }
    
    .glass-card {
        padding: 20px;
    }
}
</style> 