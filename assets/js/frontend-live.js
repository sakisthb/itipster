/**
 * iTipster Pro - Frontend Live JavaScript
 * Handles real-time updates, AJAX calls, and interactive features
 */

(function($) {
    'use strict';

    // Global variables
    let updateInterval;
    let currentFilters = {
        league: '',
        sport: '',
        market: ''
    };

    // Initialize when document is ready
    $(document).ready(function() {
        initFrontend();
    });

    /**
     * Initialize frontend functionality
     */
    function initFrontend() {
        setupEventListeners();
        loadInitialData();
        startLiveUpdates();
        setupRealTimeFeatures();
    }

    /**
     * Setup event listeners
     */
    function setupEventListeners() {
        // Filter changes
        $('.filter-select').on('change', function() {
            const filterType = $(this).data('filter');
            const value = $(this).val();
            
            currentFilters[filterType] = value;
            loadPredictions();
        });

        // Refresh button
        $('.refresh-btn').on('click', function() {
            loadPredictions();
        });

        // Prediction card interactions
        $(document).on('click', '.prediction-card', function() {
            const fixtureId = $(this).data('fixture-id');
            showPredictionDetails(fixtureId);
        });

        // Live odds toggle
        $('.live-odds-toggle').on('click', function() {
            toggleLiveOdds();
        });

        // Value bet filters
        $('.value-filter').on('click', function() {
            const minValue = $(this).data('value');
            filterByValue(minValue);
        });

        // Confidence score filters
        $('.confidence-filter').on('click', function() {
            const minConfidence = $(this).data('confidence');
            filterByConfidence(minConfidence);
        });

        // Search functionality
        $('.search-input').on('input', debounce(function() {
            const query = $(this).val();
            searchPredictions(query);
        }, 300));

        // Mobile menu toggle
        $('.mobile-menu-toggle').on('click', function() {
            $('.mobile-menu').toggleClass('active');
        });

        // Close modals
        $(document).on('click', '.modal-close, .modal-overlay', function() {
            $('.modal').removeClass('active');
        });

        // Keyboard shortcuts
        $(document).on('keydown', function(e) {
            if (e.ctrlKey || e.metaKey) {
                switch(e.key) {
                    case 'r':
                        e.preventDefault();
                        loadPredictions();
                        break;
                    case 'f':
                        e.preventDefault();
                        $('.search-input').focus();
                        break;
                    case 'l':
                        e.preventDefault();
                        toggleLiveOdds();
                        break;
                }
            }
        });
    }

    /**
     * Load initial data
     */
    function loadInitialData() {
        showLoading();
        
        // Load predictions
        loadPredictions();
        
        // Load live odds for upcoming matches
        loadLiveOdds();
        
        // Load value bets
        loadValueBets();
        
        // Load user stats
        loadUserStats();
        
        hideLoading();
    }

    /**
     * Load predictions via AJAX
     */
    function loadPredictions() {
        showLoading('.predictions-container');
        
        $.ajax({
            url: itipster_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'itipster_get_predictions',
                nonce: itipster_ajax.nonce,
                league: currentFilters.league,
                sport: currentFilters.sport,
                market: currentFilters.market
            },
            success: function(response) {
                if (response.success) {
                    renderPredictions(response.data);
                    updatePredictionCount(response.data.length);
                } else {
                    showError('Failed to load predictions');
                }
            },
            error: function() {
                showError('Network error occurred');
            },
            complete: function() {
                hideLoading('.predictions-container');
            }
        });
    }

    /**
     * Load live odds
     */
    function loadLiveOdds() {
        const upcomingFixtures = getUpcomingFixtures();
        
        upcomingFixtures.forEach(fixture => {
            $.ajax({
                url: itipster_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'itipster_get_live_odds',
                    nonce: itipster_ajax.nonce,
                    fixture_id: fixture.id
                },
                success: function(response) {
                    if (response.success) {
                        updateLiveOdds(fixture.id, response.data);
                    }
                }
            });
        });
    }

    /**
     * Load value bets
     */
    function loadValueBets() {
        $.ajax({
            url: itipster_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'itipster_get_value_bets',
                nonce: itipster_ajax.nonce,
                league: currentFilters.league
            },
            success: function(response) {
                if (response.success) {
                    renderValueBets(response.data);
                }
            }
        });
    }

    /**
     * Load user statistics
     */
    function loadUserStats() {
        // This would typically come from user profile data
        const stats = {
            total_predictions: 156,
            success_rate: 78.5,
            total_profit: 1247.50,
            streak: 8
        };
        
        renderUserStats(stats);
    }

    /**
     * Render predictions
     */
    function renderPredictions(predictions) {
        const container = $('.predictions-container');
        container.empty();
        
        if (predictions.length === 0) {
            container.html('<div class="no-predictions">No predictions available for the selected filters</div>');
            return;
        }
        
        predictions.forEach(prediction => {
            const predictionHtml = createPredictionCard(prediction);
            container.append(predictionHtml);
        });
        
        // Add fade-in animation
        container.find('.prediction-card').addClass('fade-in-up');
    }

    /**
     * Create prediction card HTML
     */
    function createPredictionCard(prediction) {
        const confidenceClass = getConfidenceClass(prediction.confidence_score);
        const valueStars = getValueStars(prediction.value_rating);
        
        return `
            <div class="prediction-card" data-fixture-id="${prediction.fixture_id}">
                <div class="prediction-header">
                    <div>
                        <div class="prediction-teams">${prediction.home_team} vs ${prediction.away_team}</div>
                        <div class="prediction-league">${prediction.league}</div>
                    </div>
                    <div class="prediction-badges">
                        ${prediction.is_premium ? '<span class="premium-badge">Premium</span>' : ''}
                        ${prediction.is_live ? '<span class="live-indicator">Live</span>' : ''}
                    </div>
                </div>
                
                <div class="prediction-content">
                    <div class="prediction-info">
                        <div class="prediction-market">${prediction.market}</div>
                        <div class="prediction-value">${prediction.prediction_value}</div>
                        <div class="prediction-analysis">${prediction.analysis}</div>
                    </div>
                    
                    <div class="prediction-stats">
                        <div class="confidence-score ${confidenceClass}">${prediction.confidence_score}%</div>
                        <div class="odds-value">${prediction.odds}</div>
                        <div class="value-rating">
                            <span class="stars">${valueStars}</span>
                            <span>${prediction.value_rating}/10</span>
                        </div>
                    </div>
                </div>
                
                <div class="prediction-trends">
                    <div class="trend-item">
                        <span class="trend-label">Form:</span>
                        <span class="trend-value">${prediction.trends.home_form} vs ${prediction.trends.away_form}</span>
                    </div>
                    <div class="trend-item">
                        <span class="trend-label">H2H:</span>
                        <span class="trend-value">${prediction.trends.h2h}</span>
                    </div>
                </div>
            </div>
        `;
    }

    /**
     * Get confidence class based on score
     */
    function getConfidenceClass(score) {
        if (score >= 85) return 'high-confidence';
        if (score >= 70) return 'medium-confidence';
        return 'low-confidence';
    }

    /**
     * Get value stars
     */
    function getValueStars(rating) {
        const fullStars = Math.floor(rating);
        const hasHalfStar = rating % 1 >= 0.5;
        const emptyStars = 10 - fullStars - (hasHalfStar ? 1 : 0);
        
        return '★'.repeat(fullStars) + (hasHalfStar ? '☆' : '') + '☆'.repeat(emptyStars);
    }

    /**
     * Update live odds
     */
    function updateLiveOdds(fixtureId, odds) {
        const oddsContainer = $(`.odds-container[data-fixture-id="${fixtureId}"]`);
        
        if (oddsContainer.length) {
            oddsContainer.find('.odds-number').each(function() {
                const market = $(this).data('market');
                const newOdds = odds[market];
                
                if (newOdds) {
                    $(this).text(newOdds).addClass('odds-updated');
                    setTimeout(() => {
                        $(this).removeClass('odds-updated');
                    }, 2000);
                }
            });
        }
    }

    /**
     * Render value bets
     */
    function renderValueBets(valueBets) {
        const container = $('.value-bets-container');
        container.empty();
        
        valueBets.forEach(bet => {
            const betHtml = `
                <div class="value-bet-card glass-card">
                    <div class="bet-header">
                        <div class="bet-teams">${bet.home_team} vs ${bet.away_team}</div>
                        <div class="bet-value">${bet.value_rating}/10</div>
                    </div>
                    <div class="bet-details">
                        <div class="bet-market">${bet.market}</div>
                        <div class="bet-prediction">${bet.prediction}</div>
                        <div class="bet-odds">${bet.odds}</div>
                    </div>
                </div>
            `;
            container.append(betHtml);
        });
    }

    /**
     * Render user statistics
     */
    function renderUserStats(stats) {
        $('.user-stats').html(`
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-number">${stats.total_predictions}</div>
                    <div class="stat-label">Total Predictions</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">${stats.success_rate}%</div>
                    <div class="stat-label">Success Rate</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">€${stats.total_profit}</div>
                    <div class="stat-label">Total Profit</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">${stats.streak}</div>
                    <div class="stat-label">Current Streak</div>
                </div>
            </div>
        `);
    }

    /**
     * Start live updates
     */
    function startLiveUpdates() {
        // Update live odds every 30 seconds
        updateInterval = setInterval(function() {
            if ($('.live-odds-toggle').hasClass('active')) {
                loadLiveOdds();
            }
        }, 30000);
        
        // Update predictions every 5 minutes
        setInterval(function() {
            loadPredictions();
        }, 300000);
    }

    /**
     * Setup real-time features
     */
    function setupRealTimeFeatures() {
        // WebSocket connection for real-time updates (if available)
        if (typeof WebSocket !== 'undefined') {
            setupWebSocket();
        }
        
        // Server-sent events fallback
        setupEventSource();
    }

    /**
     * Setup WebSocket connection
     */
    function setupWebSocket() {
        try {
            const ws = new WebSocket('wss://itipster.gr/ws');
            
            ws.onopen = function() {
                console.log('WebSocket connected');
            };
            
            ws.onmessage = function(event) {
                const data = JSON.parse(event.data);
                handleRealTimeUpdate(data);
            };
            
            ws.onerror = function(error) {
                console.error('WebSocket error:', error);
            };
            
            ws.onclose = function() {
                console.log('WebSocket disconnected');
                // Attempt to reconnect after 5 seconds
                setTimeout(setupWebSocket, 5000);
            };
        } catch (error) {
            console.log('WebSocket not available, using polling');
        }
    }

    /**
     * Setup EventSource for server-sent events
     */
    function setupEventSource() {
        try {
            const eventSource = new EventSource('/api/events');
            
            eventSource.onmessage = function(event) {
                const data = JSON.parse(event.data);
                handleRealTimeUpdate(data);
            };
            
            eventSource.onerror = function(error) {
                console.error('EventSource error:', error);
                eventSource.close();
            };
        } catch (error) {
            console.log('EventSource not available');
        }
    }

    /**
     * Handle real-time updates
     */
    function handleRealTimeUpdate(data) {
        switch (data.type) {
            case 'odds_update':
                updateLiveOdds(data.fixture_id, data.odds);
                break;
            case 'prediction_update':
                updatePrediction(data.prediction);
                break;
            case 'match_start':
                showMatchStarted(data.fixture_id);
                break;
            case 'match_end':
                showMatchResult(data.fixture_id, data.result);
                break;
        }
    }

    /**
     * Show prediction details modal
     */
    function showPredictionDetails(fixtureId) {
        $.ajax({
            url: itipster_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'itipster_get_prediction_details',
                nonce: itipster_ajax.nonce,
                fixture_id: fixtureId
            },
            success: function(response) {
                if (response.success) {
                    renderPredictionModal(response.data);
                    $('.prediction-modal').addClass('active');
                }
            }
        });
    }

    /**
     * Toggle live odds
     */
    function toggleLiveOdds() {
        const toggle = $('.live-odds-toggle');
        toggle.toggleClass('active');
        
        if (toggle.hasClass('active')) {
            loadLiveOdds();
            showMessage('Live odds enabled');
        } else {
            showMessage('Live odds disabled');
        }
    }

    /**
     * Filter by value rating
     */
    function filterByValue(minValue) {
        $('.prediction-card').each(function() {
            const valueRating = parseFloat($(this).find('.value-rating span:last').text());
            if (valueRating >= minValue) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    }

    /**
     * Filter by confidence score
     */
    function filterByConfidence(minConfidence) {
        $('.prediction-card').each(function() {
            const confidence = parseFloat($(this).find('.confidence-score').text());
            if (confidence >= minConfidence) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    }

    /**
     * Search predictions
     */
    function searchPredictions(query) {
        if (query.length < 2) {
            $('.prediction-card').show();
            return;
        }
        
        $('.prediction-card').each(function() {
            const text = $(this).text().toLowerCase();
            if (text.includes(query.toLowerCase())) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    }

    /**
     * Show loading state
     */
    function showLoading(selector = 'body') {
        $(selector).append('<div class="loading-spinner"></div>');
    }

    /**
     * Hide loading state
     */
    function hideLoading(selector = 'body') {
        $(selector).find('.loading-spinner').remove();
    }

    /**
     * Show error message
     */
    function showError(message) {
        const errorHtml = `<div class="error-message">${message}</div>`;
        $('.messages-container').append(errorHtml);
        
        setTimeout(() => {
            $('.error-message').fadeOut();
        }, 5000);
    }

    /**
     * Show success message
     */
    function showMessage(message, type = 'success') {
        const messageHtml = `<div class="${type}-message">${message}</div>`;
        $('.messages-container').append(messageHtml);
        
        setTimeout(() => {
            $(`.${type}-message`).fadeOut();
        }, 3000);
    }

    /**
     * Debounce function
     */
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    /**
     * Get upcoming fixtures
     */
    function getUpcomingFixtures() {
        // This would typically come from the API
        return [
            { id: 1, home_team: 'Manchester City', away_team: 'Arsenal' },
            { id: 2, home_team: 'Liverpool', away_team: 'Chelsea' },
            { id: 3, home_team: 'Real Madrid', away_team: 'Barcelona' }
        ];
    }

    /**
     * Update prediction count
     */
    function updatePredictionCount(count) {
        $('.prediction-count').text(count);
    }

    /**
     * Show match started notification
     */
    function showMatchStarted(fixtureId) {
        const fixture = getFixtureById(fixtureId);
        showMessage(`${fixture.home_team} vs ${fixture.away_team} has started!`, 'info');
    }

    /**
     * Show match result
     */
    function showMatchResult(fixtureId, result) {
        const fixture = getFixtureById(fixtureId);
        showMessage(`Match ended: ${fixture.home_team} ${result.home_score} - ${result.away_score} ${fixture.away_team}`, 'info');
    }

    /**
     * Get fixture by ID
     */
    function getFixtureById(fixtureId) {
        // This would typically come from cached data
        return {
            home_team: 'Team A',
            away_team: 'Team B'
        };
    }

})(jQuery); 