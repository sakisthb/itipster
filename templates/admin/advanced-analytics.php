<?php
/**
 * Advanced Analytics Dashboard
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Get analytics data (this would be populated by the backend)
$analytics_data = isset($analytics_data) ? $analytics_data : array();
?>

<div class="itipster-analytics-pro">
    <div class="analytics-background">
        <div class="floating-elements"></div>
    </div>
    
    <div class="analytics-container">
        <div class="analytics-header">
            <div class="header-content">
                <h1><span class="icon">📊</span> Advanced Analytics</h1>
                <p class="subtitle">Real-time insights & performance metrics</p>
            </div>
            <div class="header-controls">
                <div class="date-range-picker">
                    <select id="analytics-period" class="glass-select">
                        <option value="7">Last 7 days</option>
                        <option value="30" selected>Last 30 days</option>
                        <option value="90">Last 90 days</option>
                        <option value="365">Last year</option>
                        <option value="custom">Custom range</option>
                    </select>
                </div>
                <button class="refresh-btn glass-button" onclick="refreshAnalytics()">
                    <span class="refresh-icon">🔄</span>
                    Refresh
                </button>
            </div>
        </div>
        
        <div class="analytics-grid">
            <!-- KPI Cards Section -->
            <div class="kpi-section">
                <div class="kpi-card revenue glass-card">
                    <div class="kpi-header">
                        <div class="kpi-title">
                            <h3>💰 Total Revenue</h3>
                            <div class="kpi-period">Last 30 days</div>
                        </div>
                        <div class="kpi-icon">💎</div>
                    </div>
                    <div class="kpi-main">
                        <div class="kpi-value">€12,847</div>
                        <div class="kpi-change positive">
                            <span class="change-icon">↗</span>
                            +23.5% vs last month
                        </div>
                    </div>
                    <div class="kpi-chart">
                        <canvas id="revenue-chart"></canvas>
                    </div>
                </div>
                
                <div class="kpi-card predictions glass-card">
                    <div class="kpi-header">
                        <div class="kpi-title">
                            <h3>🎯 Predictions Success</h3>
                            <div class="kpi-period">All time</div>
                        </div>
                        <div class="kpi-icon">🏆</div>
                    </div>
                    <div class="kpi-main">
                        <div class="kpi-value">84.7%</div>
                        <div class="kpi-change positive">
                            <span class="change-icon">↗</span>
                            +2.1% this month
                        </div>
                    </div>
                    <div class="kpi-chart">
                        <canvas id="success-chart"></canvas>
                    </div>
                </div>
                
                <div class="kpi-card users glass-card">
                    <div class="kpi-header">
                        <div class="kpi-title">
                            <h3>👥 Active Users</h3>
                            <div class="kpi-period">Current</div>
                        </div>
                        <div class="kpi-icon">👤</div>
                    </div>
                    <div class="kpi-main">
                        <div class="kpi-value">2,847</div>
                        <div class="kpi-change positive">
                            <span class="change-icon">↗</span>
                            +156 this week
                        </div>
                    </div>
                    <div class="kpi-chart">
                        <canvas id="users-chart"></canvas>
                    </div>
                </div>
                
                <div class="kpi-card profit glass-card">
                    <div class="kpi-header">
                        <div class="kpi-title">
                            <h3>📈 Avg ROI</h3>
                            <div class="kpi-period">Per user</div>
                        </div>
                        <div class="kpi-icon">📊</div>
                    </div>
                    <div class="kpi-main">
                        <div class="kpi-value">+18.3%</div>
                        <div class="kpi-change positive">
                            <span class="change-icon">↗</span>
                            +3.2% vs benchmark
                        </div>
                    </div>
                    <div class="kpi-chart">
                        <canvas id="roi-chart"></canvas>
                    </div>
                </div>
            </div>
            
            <!-- Main Charts Section -->
            <div class="charts-section">
                <div class="chart-card main-chart glass-card">
                    <div class="chart-header">
                        <h3>Performance Overview</h3>
                        <div class="chart-tabs">
                            <button class="tab active" data-chart="revenue">Revenue</button>
                            <button class="tab" data-chart="predictions">Predictions</button>
                            <button class="tab" data-chart="users">Users</button>
                            <button class="tab" data-chart="roi">ROI</button>
                        </div>
                    </div>
                    <div class="chart-container">
                        <canvas id="main-analytics-chart"></canvas>
                    </div>
                </div>
                
                <div class="chart-card league-performance glass-card">
                    <div class="chart-header">
                        <h3>League Performance</h3>
                        <div class="chart-legend">
                            <span class="legend-item">
                                <span class="legend-color success"></span>
                                High Success
                            </span>
                            <span class="legend-item">
                                <span class="legend-color medium"></span>
                                Medium
                            </span>
                        </div>
                    </div>
                    <div class="league-stats">
                        <div class="league-item">
                            <div class="league-info">
                                <div class="league-logo">🏴󠁧󠁢󠁥󠁮󠁧󠁿</div>
                                <div class="league-details">
                                    <div class="league-name">Premier League</div>
                                    <div class="league-stats-text">87 predictions, 76% success</div>
                                </div>
                            </div>
                            <div class="league-performance-bar">
                                <div class="performance-fill success" style="width: 76%"></div>
                            </div>
                            <div class="league-percentage">76%</div>
                        </div>
                        
                        <div class="league-item">
                            <div class="league-info">
                                <div class="league-logo">🇪🇸</div>
                                <div class="league-details">
                                    <div class="league-name">La Liga</div>
                                    <div class="league-stats-text">92 predictions, 82% success</div>
                                </div>
                            </div>
                            <div class="league-performance-bar">
                                <div class="performance-fill success" style="width: 82%"></div>
                            </div>
                            <div class="league-percentage">82%</div>
                        </div>
                        
                        <div class="league-item">
                            <div class="league-info">
                                <div class="league-logo">🇩🇪</div>
                                <div class="league-details">
                                    <div class="league-name">Bundesliga</div>
                                    <div class="league-stats-text">78 predictions, 71% success</div>
                                </div>
                            </div>
                            <div class="league-performance-bar">
                                <div class="performance-fill medium" style="width: 71%"></div>
                            </div>
                            <div class="league-percentage">71%</div>
                        </div>
                        
                        <div class="league-item">
                            <div class="league-info">
                                <div class="league-logo">🇮🇹</div>
                                <div class="league-details">
                                    <div class="league-name">Serie A</div>
                                    <div class="league-stats-text">85 predictions, 79% success</div>
                                </div>
                            </div>
                            <div class="league-performance-bar">
                                <div class="performance-fill success" style="width: 79%"></div>
                            </div>
                            <div class="league-percentage">79%</div>
                        </div>
                        
                        <div class="league-item">
                            <div class="league-info">
                                <div class="league-logo">🇫🇷</div>
                                <div class="league-details">
                                    <div class="league-name">Ligue 1</div>
                                    <div class="league-stats-text">73 predictions, 68% success</div>
                                </div>
                            </div>
                            <div class="league-performance-bar">
                                <div class="performance-fill medium" style="width: 68%"></div>
                            </div>
                            <div class="league-percentage">68%</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Advanced Metrics Section -->
            <div class="metrics-section">
                <div class="metric-card risk-analysis glass-card">
                    <div class="metric-header">
                        <h3>🎲 Risk Analysis</h3>
                        <div class="risk-indicator low">Low Risk</div>
                    </div>
                    <div class="risk-gauge">
                        <canvas id="risk-gauge"></canvas>
                    </div>
                    <div class="risk-details">
                        <div class="risk-item">
                            <span class="risk-label">Volatility</span>
                            <span class="risk-value low">12%</span>
                        </div>
                        <div class="risk-item">
                            <span class="risk-label">Sharpe Ratio</span>
                            <span class="risk-value high">2.4</span>
                        </div>
                        <div class="risk-item">
                            <span class="risk-label">Max Drawdown</span>
                            <span class="risk-value medium">-8.3%</span>
                        </div>
                    </div>
                </div>
                
                <div class="metric-card value-bets glass-card">
                    <div class="metric-header">
                        <h3>💎 Value Bets Distribution</h3>
                        <div class="value-summary">232 total bets</div>
                    </div>
                    <div class="value-metrics">
                        <div class="value-item high-value">
                            <div class="value-info">
                                <span class="value-label">High Value (8.0+)</span>
                                <span class="value-percentage">9.9%</span>
                            </div>
                            <div class="value-bar">
                                <div class="value-fill high" style="width: 9.9%"></div>
                            </div>
                            <span class="value-count">23</span>
                        </div>
                        
                        <div class="value-item medium-value">
                            <div class="value-info">
                                <span class="value-label">Medium Value (6.0-7.9)</span>
                                <span class="value-percentage">28.9%</span>
                            </div>
                            <div class="value-bar">
                                <div class="value-fill medium" style="width: 28.9%"></div>
                            </div>
                            <span class="value-count">67</span>
                        </div>
                        
                        <div class="value-item standard-value">
                            <div class="value-info">
                                <span class="value-label">Standard (5.0-5.9)</span>
                                <span class="value-percentage">61.2%</span>
                            </div>
                            <div class="value-bar">
                                <div class="value-fill standard" style="width: 61.2%"></div>
                            </div>
                            <span class="value-count">142</span>
                        </div>
                    </div>
                </div>
                
                <div class="metric-card user-engagement glass-card">
                    <div class="metric-header">
                        <h3>📱 User Engagement</h3>
                        <div class="engagement-period">Last 7 days</div>
                    </div>
                    <div class="engagement-metrics">
                        <div class="engagement-item">
                            <div class="engagement-icon">👀</div>
                            <div class="engagement-details">
                                <div class="engagement-value">12,847</div>
                                <div class="engagement-label">Page Views</div>
                            </div>
                            <div class="engagement-change positive">+15%</div>
                        </div>
                        
                        <div class="engagement-item">
                            <div class="engagement-icon">⏱️</div>
                            <div class="engagement-details">
                                <div class="engagement-value">4m 32s</div>
                                <div class="engagement-label">Avg Session</div>
                            </div>
                            <div class="engagement-change positive">+8%</div>
                        </div>
                        
                        <div class="engagement-item">
                            <div class="engagement-icon">🔄</div>
                            <div class="engagement-details">
                                <div class="engagement-value">3.2</div>
                                <div class="engagement-label">Pages/Session</div>
                            </div>
                            <div class="engagement-change positive">+12%</div>
                        </div>
                        
                        <div class="engagement-item">
                            <div class="engagement-icon">📈</div>
                            <div class="engagement-details">
                                <div class="engagement-value">67.3%</div>
                                <div class="engagement-label">Bounce Rate</div>
                            </div>
                            <div class="engagement-change negative">-5%</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Advanced Analytics Implementation
document.addEventListener('DOMContentLoaded', function() {
    initAdvancedAnalytics();
    setupEventListeners();
});

function initAdvancedAnalytics() {
    // Revenue Chart (Mini)
    const revenueCtx = document.getElementById('revenue-chart').getContext('2d');
    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            datasets: [{
                data: [1200, 1350, 1100, 1450, 1600, 1800, 1750],
                borderColor: '#10b981',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                tension: 0.4,
                fill: true,
                pointRadius: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { 
                x: { display: false }, 
                y: { display: false } 
            },
            elements: {
                point: { radius: 0 },
                line: { borderWidth: 2 }
            }
        }
    });
    
    // Success Rate Chart (Doughnut)
    const successCtx = document.getElementById('success-chart').getContext('2d');
    new Chart(successCtx, {
        type: 'doughnut',
        data: {
            datasets: [{
                data: [84.7, 15.3],
                backgroundColor: ['#6366f1', 'rgba(255,255,255,0.1)'],
                borderWidth: 0,
                cutout: '70%'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } }
        }
    });
    
    // Users Chart (Area)
    const usersCtx = document.getElementById('users-chart').getContext('2d');
    new Chart(usersCtx, {
        type: 'line',
        data: {
            labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            datasets: [{
                data: [2800, 2850, 2900, 2950, 3000, 3050, 2847],
                borderColor: '#8b5cf6',
                backgroundColor: 'rgba(139, 92, 246, 0.1)',
                tension: 0.4,
                fill: true,
                pointRadius: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { 
                x: { display: false }, 
                y: { display: false } 
            },
            elements: {
                point: { radius: 0 },
                line: { borderWidth: 2 }
            }
        }
    });
    
    // ROI Chart (Bar)
    const roiCtx = document.getElementById('roi-chart').getContext('2d');
    new Chart(roiCtx, {
        type: 'bar',
        data: {
            labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
            datasets: [{
                data: [15.2, 16.8, 17.5, 18.3],
                backgroundColor: 'rgba(16, 185, 129, 0.8)',
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { 
                x: { display: false }, 
                y: { display: false } 
            }
        }
    });
    
    // Main Analytics Chart
    const mainCtx = document.getElementById('main-analytics-chart').getContext('2d');
    window.mainChart = new Chart(mainCtx, {
        type: 'line',
        data: {
            labels: Array.from({length: 30}, (_, i) => i + 1),
            datasets: [
                {
                    label: 'Revenue (€)',
                    data: generateRandomData(30, 300, 600),
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    tension: 0.4,
                    fill: true,
                    yAxisID: 'y'
                },
                {
                    label: 'Predictions',
                    data: generateRandomData(30, 50, 120),
                    borderColor: '#6366f1',
                    backgroundColor: 'rgba(99, 102, 241, 0.1)',
                    tension: 0.4,
                    fill: true,
                    yAxisID: 'y1'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { intersect: false, mode: 'index' },
            plugins: {
                legend: { 
                    display: true,
                    labels: { 
                        color: 'rgba(255,255,255,0.8)',
                        usePointStyle: true,
                        padding: 20
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0,0,0,0.8)',
                    titleColor: 'white',
                    bodyColor: 'white',
                    borderColor: 'rgba(255,255,255,0.1)',
                    borderWidth: 1
                }
            },
            scales: {
                x: { 
                    grid: { color: 'rgba(255,255,255,0.1)' },
                    ticks: { color: 'rgba(255,255,255,0.7)' }
                },
                y: { 
                    type: 'linear',
                    display: true,
                    position: 'left',
                    grid: { color: 'rgba(255,255,255,0.1)' },
                    ticks: { color: 'rgba(255,255,255,0.7)' }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    grid: { drawOnChartArea: false },
                    ticks: { color: 'rgba(255,255,255,0.7)' }
                }
            }
        }
    });
    
    // Risk Gauge Chart
    const riskCtx = document.getElementById('risk-gauge').getContext('2d');
    new Chart(riskCtx, {
        type: 'doughnut',
        data: {
            datasets: [{
                data: [75, 25],
                backgroundColor: ['#10b981', 'rgba(255,255,255,0.1)'],
                borderWidth: 0,
                cutout: '80%'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } }
        }
    });
}

function setupEventListeners() {
    // Chart tabs
    document.querySelectorAll('.tab').forEach(tab => {
        tab.addEventListener('click', function() {
            document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            
            const chartType = this.dataset.chart;
            updateMainChart(chartType);
        });
    });
    
    // Period selector
    document.getElementById('analytics-period').addEventListener('change', function() {
        refreshAnalytics();
    });
}

function updateMainChart(chartType) {
    const chart = window.mainChart;
    const datasets = chart.data.datasets;
    
    // Update data based on chart type
    switch(chartType) {
        case 'revenue':
            datasets[0].data = generateRandomData(30, 300, 600);
            datasets[0].label = 'Revenue (€)';
            datasets[0].borderColor = '#10b981';
            datasets[0].backgroundColor = 'rgba(16, 185, 129, 0.1)';
            datasets[1].data = generateRandomData(30, 50, 120);
            datasets[1].label = 'Predictions';
            datasets[1].borderColor = '#6366f1';
            datasets[1].backgroundColor = 'rgba(99, 102, 241, 0.1)';
            break;
        case 'predictions':
            datasets[0].data = generateRandomData(30, 50, 120);
            datasets[0].label = 'Total Predictions';
            datasets[0].borderColor = '#6366f1';
            datasets[0].backgroundColor = 'rgba(99, 102, 241, 0.1)';
            datasets[1].data = generateRandomData(30, 30, 80);
            datasets[1].label = 'Successful';
            datasets[1].borderColor = '#10b981';
            datasets[1].backgroundColor = 'rgba(16, 185, 129, 0.1)';
            break;
        case 'users':
            datasets[0].data = generateRandomData(30, 2000, 3000);
            datasets[0].label = 'Active Users';
            datasets[0].borderColor = '#8b5cf6';
            datasets[0].backgroundColor = 'rgba(139, 92, 246, 0.1)';
            datasets[1].data = generateRandomData(30, 100, 300);
            datasets[1].label = 'New Users';
            datasets[1].borderColor = '#f59e0b';
            datasets[1].backgroundColor = 'rgba(245, 158, 11, 0.1)';
            break;
        case 'roi':
            datasets[0].data = generateRandomData(30, 10, 25);
            datasets[0].label = 'ROI (%)';
            datasets[0].borderColor = '#10b981';
            datasets[0].backgroundColor = 'rgba(16, 185, 129, 0.1)';
            datasets[1].data = generateRandomData(30, 5, 15);
            datasets[1].label = 'Benchmark';
            datasets[1].borderColor = '#6b7280';
            datasets[1].backgroundColor = 'rgba(107, 114, 128, 0.1)';
            break;
    }
    
    chart.update();
}

function refreshAnalytics() {
    // Show loading state
    const refreshBtn = document.querySelector('.refresh-btn');
    const originalText = refreshBtn.innerHTML;
    refreshBtn.innerHTML = '<span class="refresh-icon spinning">🔄</span> Loading...';
    refreshBtn.disabled = true;
    
    // Simulate API call
    setTimeout(() => {
        // Update all charts with new data
        updateAllCharts();
        
        // Reset button
        refreshBtn.innerHTML = originalText;
        refreshBtn.disabled = false;
        
        // Show success message
        showNotification('Analytics refreshed successfully!', 'success');
    }, 2000);
}

function updateAllCharts() {
    // Update main chart
    if (window.mainChart) {
        const activeTab = document.querySelector('.tab.active');
        if (activeTab) {
            updateMainChart(activeTab.dataset.chart);
        }
    }
    
    // Update KPI charts with new data
    // This would typically fetch new data from the server
}

function generateRandomData(count, min, max) {
    return Array.from({length: count}, () => 
        Math.floor(Math.random() * (max - min + 1)) + min
    );
}

function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.innerHTML = `
        <div class="notification-content">
            <span class="notification-icon">${type === 'success' ? '✅' : 'ℹ️'}</span>
            <span class="notification-text">${message}</span>
        </div>
    `;
    
    // Add to page
    document.body.appendChild(notification);
    
    // Remove after 3 seconds
    setTimeout(() => {
        notification.remove();
    }, 3000);
}
</script>

<style>
/* Advanced Analytics Premium Styles */
.itipster-analytics-pro {
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #334155 100%);
    min-height: 100vh;
    padding: 40px;
    color: white;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    position: relative;
    overflow: hidden;
    margin: -20px;
}

.analytics-background {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    overflow: hidden;
    z-index: 1;
}

.floating-elements::before,
.floating-elements::after {
    content: '';
    position: absolute;
    width: 300px;
    height: 300px;
    background: radial-gradient(circle, rgba(99, 102, 241, 0.05) 0%, transparent 70%);
    border-radius: 50%;
    animation: float 25s infinite ease-in-out;
}

.floating-elements::before {
    top: 5%;
    left: 5%;
    animation-delay: 0s;
}

.floating-elements::after {
    top: 70%;
    right: 5%;
    animation-delay: -12s;
    background: radial-gradient(circle, rgba(139, 92, 246, 0.05) 0%, transparent 70%);
}

@keyframes float {
    0%, 100% { transform: translateY(0px) scale(1) rotate(0deg); }
    50% { transform: translateY(-30px) scale(1.1) rotate(180deg); }
}

.analytics-container {
    position: relative;
    z-index: 2;
    max-width: 1600px;
    margin: 0 auto;
}

.analytics-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 40px;
    flex-wrap: wrap;
    gap: 20px;
}

.header-content h1 {
    font-size: 3rem;
    font-weight: 800;
    margin: 0;
    background: linear-gradient(135deg, #6366f1, #8b5cf6, #10b981);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    text-shadow: 0 4px 20px rgba(0,0,0,0.3);
}

.header-content .subtitle {
    color: rgba(255,255,255,0.8);
    font-size: 1.1rem;
    margin: 10px 0 0 0;
    font-weight: 500;
}

.header-controls {
    display: flex;
    gap: 15px;
    align-items: center;
}

.glass-select {
    background: rgba(255,255,255,0.1);
    border: 1px solid rgba(255,255,255,0.2);
    color: white;
    padding: 12px 20px;
    border-radius: 12px;
    font-size: 14px;
    backdrop-filter: blur(10px);
    min-width: 150px;
}

.glass-button {
    background: rgba(255,255,255,0.1);
    border: 1px solid rgba(255,255,255,0.2);
    color: white;
    padding: 12px 20px;
    border-radius: 12px;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
    display: flex;
    align-items: center;
    gap: 8px;
}

.glass-button:hover {
    background: rgba(255,255,255,0.2);
    transform: translateY(-2px);
}

.refresh-icon.spinning {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.analytics-grid {
    display: grid;
    gap: 30px;
}

/* KPI Section */
.kpi-section {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 25px;
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
    transform: translateY(-8px);
    box-shadow: 0 25px 50px rgba(0,0,0,0.3);
    border-color: rgba(99, 102, 241, 0.4);
}

.glass-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, #6366f1, #8b5cf6, #10b981);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.glass-card:hover::before {
    opacity: 1;
}

.kpi-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 20px;
}

.kpi-title h3 {
    font-size: 1.1rem;
    margin: 0 0 5px 0;
    color: rgba(255,255,255,0.9);
    font-weight: 600;
}

.kpi-period {
    font-size: 0.8rem;
    color: rgba(255,255,255,0.6);
}

.kpi-icon {
    font-size: 1.5rem;
    opacity: 0.8;
}

.kpi-main {
    margin-bottom: 20px;
}

.kpi-value {
    font-size: 2.5rem;
    font-weight: 800;
    color: white;
    margin-bottom: 8px;
    line-height: 1;
}

.kpi-change {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 0.9rem;
    font-weight: 500;
}

.kpi-change.positive {
    color: #10b981;
}

.kpi-change.negative {
    color: #ef4444;
}

.change-icon {
    font-size: 1rem;
}

.kpi-chart {
    height: 60px;
    position: relative;
}

/* Charts Section */
.charts-section {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 30px;
}

.chart-card {
    background: rgba(255,255,255,0.05);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 24px;
    padding: 30px;
}

.chart-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
    flex-wrap: wrap;
    gap: 15px;
}

.chart-header h3 {
    margin: 0;
    font-size: 1.3rem;
    font-weight: 700;
}

.chart-tabs {
    display: flex;
    gap: 8px;
    background: rgba(255,255,255,0.05);
    border-radius: 12px;
    padding: 4px;
}

.tab {
    background: transparent;
    border: none;
    color: rgba(255,255,255,0.7);
    padding: 8px 16px;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.9rem;
    font-weight: 500;
}

.tab.active,
.tab:hover {
    background: #6366f1;
    color: white;
}

.chart-container {
    height: 400px;
    position: relative;
}

.chart-legend {
    display: flex;
    gap: 15px;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 0.8rem;
    color: rgba(255,255,255,0.7);
}

.legend-color {
    width: 12px;
    height: 12px;
    border-radius: 2px;
}

.legend-color.success {
    background: #10b981;
}

.legend-color.medium {
    background: #f59e0b;
}

/* League Performance */
.league-stats {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.league-item {
    display: grid;
    grid-template-columns: 1fr 200px 60px;
    gap: 20px;
    align-items: center;
    padding: 15px;
    background: rgba(255,255,255,0.05);
    border-radius: 12px;
    transition: all 0.3s ease;
}

.league-item:hover {
    background: rgba(255,255,255,0.1);
    transform: translateX(5px);
}

.league-info {
    display: flex;
    align-items: center;
    gap: 15px;
}

.league-logo {
    font-size: 1.5rem;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255,255,255,0.1);
    border-radius: 8px;
}

.league-name {
    font-weight: 600;
    margin-bottom: 4px;
    color: white;
}

.league-stats-text {
    font-size: 0.9rem;
    color: rgba(255,255,255,0.7);
}

.league-performance-bar {
    background: rgba(255,255,255,0.1);
    height: 8px;
    border-radius: 4px;
    position: relative;
    overflow: hidden;
}

.performance-fill {
    height: 100%;
    border-radius: 4px;
    transition: width 0.5s ease;
}

.performance-fill.success {
    background: linear-gradient(90deg, #10b981, #6366f1);
}

.performance-fill.medium {
    background: linear-gradient(90deg, #f59e0b, #ef4444);
}

.league-percentage {
    text-align: right;
    font-weight: 600;
    color: #10b981;
}

/* Metrics Section */
.metrics-section {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 25px;
}

.metric-card {
    background: rgba(255,255,255,0.05);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 24px;
    padding: 25px;
}

.metric-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.metric-header h3 {
    margin: 0;
    font-size: 1.2rem;
    font-weight: 700;
}

.risk-indicator {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
}

.risk-indicator.low {
    background: rgba(16, 185, 129, 0.2);
    color: #10b981;
    border: 1px solid rgba(16, 185, 129, 0.3);
}

.value-summary {
    font-size: 0.9rem;
    color: rgba(255,255,255,0.7);
}

.engagement-period {
    font-size: 0.8rem;
    color: rgba(255,255,255,0.6);
}

.risk-gauge {
    height: 150px;
    margin-bottom: 20px;
}

.risk-details {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.risk-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px;
    background: rgba(255,255,255,0.05);
    border-radius: 8px;
}

.risk-label {
    color: rgba(255,255,255,0.8);
    font-size: 0.9rem;
}

.risk-value {
    font-weight: 600;
    font-size: 0.9rem;
}

.risk-value.low {
    color: #10b981;
}

.risk-value.medium {
    color: #f59e0b;
}

.risk-value.high {
    color: #ef4444;
}

.value-metrics {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.value-item {
    display: grid;
    grid-template-columns: 1fr 100px 40px;
    gap: 15px;
    align-items: center;
}

.value-info {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.value-label {
    font-size: 0.9rem;
    color: rgba(255,255,255,0.8);
}

.value-percentage {
    font-size: 0.8rem;
    color: rgba(255,255,255,0.6);
}

.value-bar {
    background: rgba(255,255,255,0.1);
    height: 6px;
    border-radius: 3px;
    position: relative;
    overflow: hidden;
}

.value-fill {
    height: 100%;
    border-radius: 3px;
    transition: width 0.5s ease;
}

.value-fill.high {
    background: #10b981;
}

.value-fill.medium {
    background: #f59e0b;
}

.value-fill.standard {
    background: #6366f1;
}

.value-count {
    font-weight: 600;
    color: #6366f1;
    text-align: right;
}

.engagement-metrics {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.engagement-item {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 12px;
    background: rgba(255,255,255,0.05);
    border-radius: 12px;
    transition: all 0.3s ease;
}

.engagement-item:hover {
    background: rgba(255,255,255,0.1);
}

.engagement-icon {
    font-size: 1.2rem;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255,255,255,0.1);
    border-radius: 8px;
}

.engagement-details {
    flex: 1;
}

.engagement-value {
    font-size: 1.1rem;
    font-weight: 700;
    color: white;
    margin-bottom: 2px;
}

.engagement-label {
    font-size: 0.8rem;
    color: rgba(255,255,255,0.7);
}

.engagement-change {
    font-size: 0.9rem;
    font-weight: 600;
    padding: 4px 8px;
    border-radius: 6px;
}

.engagement-change.positive {
    color: #10b981;
    background: rgba(16, 185, 129, 0.1);
}

.engagement-change.negative {
    color: #ef4444;
    background: rgba(239, 68, 68, 0.1);
}

/* Notifications */
.notification {
    position: fixed;
    top: 20px;
    right: 20px;
    background: rgba(255,255,255,0.1);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255,255,255,0.2);
    border-radius: 12px;
    padding: 15px 20px;
    color: white;
    z-index: 1000;
    animation: slideIn 0.3s ease;
}

@keyframes slideIn {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

.notification-content {
    display: flex;
    align-items: center;
    gap: 10px;
}

.notification-icon {
    font-size: 1.2rem;
}

/* Responsive Design */
@media (max-width: 1400px) {
    .charts-section {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .itipster-analytics-pro {
        padding: 20px;
    }
    
    .analytics-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .header-content h1 {
        font-size: 2rem;
    }
    
    .kpi-section {
        grid-template-columns: 1fr;
    }
    
    .metrics-section {
        grid-template-columns: 1fr;
    }
    
    .league-item {
        grid-template-columns: 1fr;
        text-align: center;
    }
    
    .value-item {
        grid-template-columns: 1fr;
        text-align: center;
    }
    
    .glass-card {
        padding: 20px;
    }
}
</style> 