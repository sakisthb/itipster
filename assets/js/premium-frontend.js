/**
 * iTipster Pro - Premium Frontend Experience
 * Advanced Animations & Interactive Design
 * 
 * @package ADPM\iTipsterPro
 * @version 1.0.0
 */

(function() {
    'use strict';

    // ========================================
    // PREMIUM FRONTEND MANAGER
    // ========================================
    
    class PremiumFrontend {
        constructor() {
            this.isInitialized = false;
            this.animations = new AnimationManager();
            this.interactions = new InteractionManager();
            this.realTime = new RealTimeManager();
            this.gamification = new GamificationManager();
            this.analytics = new AnalyticsManager();
            
            this.init();
        }

        init() {
            if (this.isInitialized) return;
            
            // Initialize all managers
            this.animations.init();
            this.interactions.init();
            this.realTime.init();
            this.gamification.init();
            this.analytics.init();
            
            // Setup event listeners
            this.setupEventListeners();
            
            // Start real-time updates
            this.startRealTimeUpdates();
            
            this.isInitialized = true;
            console.log('🎯 iTipster Pro Premium Frontend initialized');
        }

        setupEventListeners() {
            // Intersection Observer for animations
            this.setupIntersectionObserver();
            
            // Smooth scrolling
            this.setupSmoothScrolling();
            
            // Mobile gestures
            this.setupMobileGestures();
            
            // Performance optimizations
            this.setupPerformanceOptimizations();
        }

        setupIntersectionObserver() {
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animate-fade-in-up');
                        observer.unobserve(entry.target);
                    }
                });
            }, observerOptions);

            // Observe all elements that should animate
            document.querySelectorAll('.stat-card, .prediction-card, .success-story, .cta-section').forEach(el => {
                observer.observe(el);
            });
        }

        setupSmoothScrolling() {
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });
        }

        setupMobileGestures() {
            if ('ontouchstart' in window) {
                this.setupTouchGestures();
            }
        }

        setupTouchGestures() {
            let startY = 0;
            let startX = 0;

            document.addEventListener('touchstart', (e) => {
                startY = e.touches[0].clientY;
                startX = e.touches[0].clientX;
            });

            document.addEventListener('touchend', (e) => {
                const endY = e.changedTouches[0].clientY;
                const endX = e.changedTouches[0].clientX;
                const deltaY = startY - endY;
                const deltaX = startX - endX;

                // Swipe up for refresh
                if (deltaY > 50 && Math.abs(deltaX) < 50) {
                    this.handlePullToRefresh();
                }

                // Swipe left/right for navigation
                if (Math.abs(deltaX) > 50 && Math.abs(deltaY) < 50) {
                    if (deltaX > 0) {
                        this.handleSwipeLeft();
                    } else {
                        this.handleSwipeRight();
                    }
                }
            });
        }

        setupPerformanceOptimizations() {
            // Lazy load images
            this.setupLazyLoading();
            
            // Preload critical resources
            this.preloadCriticalResources();
            
            // Optimize animations
            this.optimizeAnimations();
        }

        setupLazyLoading() {
            const imageObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src;
                        img.classList.remove('lazy');
                        imageObserver.unobserve(img);
                    }
                });
            });

            document.querySelectorAll('img[data-src]').forEach(img => {
                imageObserver.observe(img);
            });
        }

        preloadCriticalResources() {
            // Preload critical CSS and JS
            const criticalResources = [
                '/wp-content/plugins/itipster-pro/assets/css/premium-frontend.css',
                '/wp-content/plugins/itipster-pro/assets/js/premium-frontend.js'
            ];

            criticalResources.forEach(resource => {
                const link = document.createElement('link');
                link.rel = 'preload';
                link.href = resource;
                link.as = resource.endsWith('.css') ? 'style' : 'script';
                document.head.appendChild(link);
            });
        }

        optimizeAnimations() {
            // Use transform and opacity for better performance
            document.querySelectorAll('.prediction-card, .stat-card').forEach(el => {
                el.style.willChange = 'transform';
            });
        }

        startRealTimeUpdates() {
            // Start real-time counters
            this.animations.startCounterAnimations();
            
            // Start live odds ticker
            this.realTime.startOddsTicker();
            
            // Start success stories carousel
            this.animations.startCarousel();
            
            // Start user activity feed
            this.realTime.startActivityFeed();
        }

        handlePullToRefresh() {
            // Show refresh animation
            this.animations.showRefreshAnimation();
            
            // Reload data
            setTimeout(() => {
                this.realTime.refreshData();
            }, 1000);
        }

        handleSwipeLeft() {
            // Navigate to next prediction
            this.interactions.navigateToNext();
        }

        handleSwipeRight() {
            // Navigate to previous prediction
            this.interactions.navigateToPrevious();
        }
    }

    // ========================================
    // ANIMATION MANAGER
    // ========================================
    
    class AnimationManager {
        constructor() {
            this.counters = [];
            this.carouselIndex = 0;
            this.carouselInterval = null;
        }

        init() {
            this.setupCounters();
            this.setupPredictionCards();
            this.setupConfidenceCircles();
        }

        setupCounters() {
            document.querySelectorAll('.stat-number').forEach(counter => {
                const target = parseInt(counter.dataset.target) || 0;
                this.counters.push({
                    element: counter,
                    target: target,
                    current: 0,
                    increment: target / 100
                });
            });
        }

        startCounterAnimations() {
            this.counters.forEach(counter => {
                this.animateCounter(counter);
            });
        }

        animateCounter(counter) {
            const animate = () => {
                if (counter.current < counter.target) {
                    counter.current += counter.increment;
                    counter.element.textContent = Math.floor(counter.current).toLocaleString();
                    requestAnimationFrame(animate);
                } else {
                    counter.element.textContent = counter.target.toLocaleString();
                }
            };
            animate();
        }

        setupPredictionCards() {
            document.querySelectorAll('.prediction-card').forEach(card => {
                card.addEventListener('mouseenter', () => {
                    this.animateCardHover(card, true);
                });

                card.addEventListener('mouseleave', () => {
                    this.animateCardHover(card, false);
                });

                card.addEventListener('click', () => {
                    this.animateCardClick(card);
                });
            });
        }

        animateCardHover(card, isHovering) {
            if (isHovering) {
                card.style.transform = 'translateY(-15px) scale(1.02)';
                card.style.boxShadow = '0 25px 50px rgba(31, 38, 135, 0.6)';
            } else {
                card.style.transform = 'translateY(0) scale(1)';
                card.style.boxShadow = '0 8px 32px rgba(31, 38, 135, 0.37)';
            }
        }

        animateCardClick(card) {
            // Add click animation
            card.style.transform = 'scale(0.95)';
            setTimeout(() => {
                card.style.transform = 'scale(1)';
            }, 150);

            // Navigate to prediction detail
            const predictionId = card.dataset.predictionId;
            if (predictionId) {
                window.location.href = `/prediction/${predictionId}`;
            }
        }

        setupConfidenceCircles() {
            document.querySelectorAll('.confidence-circle').forEach(circle => {
                const confidence = parseInt(circle.dataset.confidence) || 0;
                this.animateConfidenceCircle(circle, confidence);
            });
        }

        animateConfidenceCircle(circle, confidence) {
            const circumference = 2 * Math.PI * 26; // radius = 26
            const progress = (confidence / 100) * circumference;
            
            circle.style.strokeDasharray = `${progress} ${circumference}`;
            circle.style.strokeDashoffset = circumference - progress;
        }

        startCarousel() {
            const carousel = document.querySelector('.carousel-track');
            if (!carousel) return;

            const stories = carousel.querySelectorAll('.success-story');
            if (stories.length <= 1) return;

            this.carouselInterval = setInterval(() => {
                this.carouselIndex = (this.carouselIndex + 1) % stories.length;
                this.updateCarousel();
            }, 5000);
        }

        updateCarousel() {
            const carousel = document.querySelector('.carousel-track');
            if (!carousel) return;

            const translateX = -this.carouselIndex * 320; // 300px width + 20px margin
            carousel.style.transform = `translateX(${translateX}px)`;
        }

        showRefreshAnimation() {
            // Create refresh indicator
            const refreshIndicator = document.createElement('div');
            refreshIndicator.className = 'refresh-indicator';
            refreshIndicator.innerHTML = `
                <div class="refresh-spinner"></div>
                <span>Refreshing...</span>
            `;
            
            document.body.appendChild(refreshIndicator);
            
            setTimeout(() => {
                refreshIndicator.remove();
            }, 2000);
        }
    }

    // ========================================
    // INTERACTION MANAGER
    // ========================================
    
    class InteractionManager {
        constructor() {
            this.currentPredictionIndex = 0;
            this.predictions = [];
        }

        init() {
            this.setupFilterPanel();
            this.setupSliders();
            this.setupToggleSwitches();
            this.setupPredictionNavigation();
        }

        setupFilterPanel() {
            const filterToggle = document.querySelector('.filter-toggle');
            const filterPanel = document.querySelector('.filter-panel');
            const filterClose = document.querySelector('.filter-close');

            if (filterToggle && filterPanel) {
                filterToggle.addEventListener('click', () => {
                    filterPanel.classList.add('active');
                });
            }

            if (filterClose && filterPanel) {
                filterClose.addEventListener('click', () => {
                    filterPanel.classList.remove('active');
                });
            }

            // Close panel when clicking outside
            document.addEventListener('click', (e) => {
                if (filterPanel && !filterPanel.contains(e.target) && !filterToggle.contains(e.target)) {
                    filterPanel.classList.remove('active');
                }
            });
        }

        setupSliders() {
            document.querySelectorAll('.slider').forEach(slider => {
                slider.addEventListener('input', (e) => {
                    this.handleSliderChange(e.target);
                });
            });
        }

        handleSliderChange(slider) {
            const value = slider.value;
            const label = slider.nextElementSibling;
            if (label) {
                label.textContent = value;
            }

            // Trigger filter update
            this.updateFilters();
        }

        setupToggleSwitches() {
            document.querySelectorAll('.toggle-switch input').forEach(toggle => {
                toggle.addEventListener('change', (e) => {
                    this.handleToggleChange(e.target);
                });
            });
        }

        handleToggleChange(toggle) {
            const isChecked = toggle.checked;
            const filterType = toggle.dataset.filter;

            // Update filter state
            this.updateFilterState(filterType, isChecked);
            
            // Trigger filter update
            this.updateFilters();
        }

        setupPredictionNavigation() {
            this.predictions = Array.from(document.querySelectorAll('.prediction-card'));
            
            // Setup keyboard navigation
            document.addEventListener('keydown', (e) => {
                switch(e.key) {
                    case 'ArrowLeft':
                        this.navigateToPrevious();
                        break;
                    case 'ArrowRight':
                        this.navigateToNext();
                        break;
                }
            });
        }

        navigateToNext() {
            if (this.predictions.length === 0) return;
            
            this.currentPredictionIndex = (this.currentPredictionIndex + 1) % this.predictions.length;
            this.highlightCurrentPrediction();
        }

        navigateToPrevious() {
            if (this.predictions.length === 0) return;
            
            this.currentPredictionIndex = this.currentPredictionIndex === 0 
                ? this.predictions.length - 1 
                : this.currentPredictionIndex - 1;
            this.highlightCurrentPrediction();
        }

        highlightCurrentPrediction() {
            this.predictions.forEach((prediction, index) => {
                if (index === this.currentPredictionIndex) {
                    prediction.classList.add('highlighted');
                    prediction.scrollIntoView({ behavior: 'smooth', block: 'center' });
                } else {
                    prediction.classList.remove('highlighted');
                }
            });
        }

        updateFilters() {
            // Collect all filter values
            const filters = this.collectFilterValues();
            
            // Apply filters to predictions
            this.applyFilters(filters);
        }

        collectFilterValues() {
            const filters = {};
            
            // Collect slider values
            document.querySelectorAll('.slider').forEach(slider => {
                filters[slider.dataset.filter] = slider.value;
            });
            
            // Collect toggle values
            document.querySelectorAll('.toggle-switch input:checked').forEach(toggle => {
                filters[toggle.dataset.filter] = true;
            });
            
            // Collect option values
            document.querySelectorAll('.filter-option.active').forEach(option => {
                filters[option.dataset.filter] = option.dataset.value;
            });
            
            return filters;
        }

        applyFilters(filters) {
            document.querySelectorAll('.prediction-card').forEach(card => {
                let shouldShow = true;
                
                // Apply confidence filter
                if (filters.confidence) {
                    const confidence = parseInt(card.dataset.confidence);
                    if (confidence < filters.confidence) {
                        shouldShow = false;
                    }
                }
                
                // Apply league filter
                if (filters.league) {
                    const league = card.dataset.league;
                    if (league !== filters.league) {
                        shouldShow = false;
                    }
                }
                
                // Show/hide card
                if (shouldShow) {
                    card.style.display = 'block';
                    card.classList.add('animate-scale-in');
                } else {
                    card.style.display = 'none';
                }
            });
        }

        updateFilterState(filterType, value) {
            // Update UI to reflect filter state
            const filterIndicator = document.querySelector(`[data-filter-indicator="${filterType}"]`);
            if (filterIndicator) {
                filterIndicator.textContent = value;
            }
        }
    }

    // ========================================
    // REAL-TIME MANAGER
    // ========================================
    
    class RealTimeManager {
        constructor() {
            this.oddsTickerInterval = null;
            this.activityFeedInterval = null;
            this.updateInterval = 30000; // 30 seconds
        }

        init() {
            this.setupWebSocket();
            this.setupPeriodicUpdates();
        }

        setupWebSocket() {
            // Setup WebSocket for real-time updates
            if ('WebSocket' in window) {
                this.connectWebSocket();
            }
        }

        connectWebSocket() {
            try {
                const ws = new WebSocket('wss://itipster.gr/ws');
                
                ws.onopen = () => {
                    console.log('🔌 WebSocket connected');
                };
                
                ws.onmessage = (event) => {
                    const data = JSON.parse(event.data);
                    this.handleRealTimeUpdate(data);
                };
                
                ws.onclose = () => {
                    console.log('🔌 WebSocket disconnected');
                    // Reconnect after 5 seconds
                    setTimeout(() => this.connectWebSocket(), 5000);
                };
                
                this.ws = ws;
            } catch (error) {
                console.log('WebSocket not available, using polling');
            }
        }

        setupPeriodicUpdates() {
            // Update odds every 30 seconds
            this.oddsTickerInterval = setInterval(() => {
                this.updateOdds();
            }, this.updateInterval);
            
            // Update activity feed every minute
            this.activityFeedInterval = setInterval(() => {
                this.updateActivityFeed();
            }, 60000);
        }

        startOddsTicker() {
            const ticker = document.querySelector('.odds-ticker-content');
            if (!ticker) return;

            // Clone ticker content for seamless loop
            const originalContent = ticker.innerHTML;
            ticker.innerHTML = originalContent + originalContent;
        }

        startActivityFeed() {
            this.updateActivityFeed();
        }

        updateOdds() {
            // Fetch latest odds from API
            fetch('/wp-json/itipster-pro/v1/odds/latest')
                .then(response => response.json())
                .then(data => {
                    this.updateOddsDisplay(data);
                })
                .catch(error => {
                    console.log('Failed to update odds:', error);
                });
        }

        updateOddsDisplay(oddsData) {
            const ticker = document.querySelector('.odds-ticker-content');
            if (!ticker) return;

            // Update ticker with new odds
            oddsData.forEach(odd => {
                const oddElement = ticker.querySelector(`[data-match-id="${odd.match_id}"]`);
                if (oddElement) {
                    const valueElement = oddElement.querySelector('.ticker-odd');
                    if (valueElement) {
                        valueElement.textContent = odd.odds;
                    }
                }
            });
        }

        updateActivityFeed() {
            // Fetch latest activity
            fetch('/wp-json/itipster-pro/v1/activity/latest')
                .then(response => response.json())
                .then(data => {
                    this.updateActivityDisplay(data);
                })
                .catch(error => {
                    console.log('Failed to update activity:', error);
                });
        }

        updateActivityDisplay(activityData) {
            const feed = document.querySelector('.user-activity-feed');
            if (!feed) return;

            // Add new activity items
            activityData.forEach(activity => {
                const activityItem = this.createActivityItem(activity);
                feed.insertBefore(activityItem, feed.firstChild);
                
                // Remove old items if too many
                const items = feed.querySelectorAll('.activity-item');
                if (items.length > 10) {
                    items[items.length - 1].remove();
                }
            });
        }

        createActivityItem(activity) {
            const item = document.createElement('div');
            item.className = 'activity-item';
            item.innerHTML = `
                <div class="activity-avatar">${activity.user.charAt(0).toUpperCase()}</div>
                <div class="activity-content">
                    <div>${activity.message}</div>
                    <div class="activity-time">${this.formatTime(activity.timestamp)}</div>
                </div>
            `;
            return item;
        }

        formatTime(timestamp) {
            const date = new Date(timestamp);
            const now = new Date();
            const diff = now - date;
            
            if (diff < 60000) return 'Just now';
            if (diff < 3600000) return `${Math.floor(diff / 60000)}m ago`;
            if (diff < 86400000) return `${Math.floor(diff / 3600000)}h ago`;
            return date.toLocaleDateString();
        }

        handleRealTimeUpdate(data) {
            switch (data.type) {
                case 'odds_update':
                    this.updateOddsDisplay(data.odds);
                    break;
                case 'new_prediction':
                    this.showNewPredictionNotification(data.prediction);
                    break;
                case 'user_activity':
                    this.addActivityItem(data.activity);
                    break;
            }
        }

        showNewPredictionNotification(prediction) {
            // Create notification
            const notification = document.createElement('div');
            notification.className = 'notification new-prediction';
            notification.innerHTML = `
                <div class="notification-content">
                    <h4>New Prediction Available!</h4>
                    <p>${prediction.teams} - ${prediction.league}</p>
                </div>
                <button class="notification-close">&times;</button>
            `;
            
            document.body.appendChild(notification);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                notification.remove();
            }, 5000);
            
            // Close button
            notification.querySelector('.notification-close').addEventListener('click', () => {
                notification.remove();
            });
        }

        refreshData() {
            // Refresh all data
            this.updateOdds();
            this.updateActivityFeed();
            
            // Show success message
            this.showRefreshSuccess();
        }

        showRefreshSuccess() {
            const success = document.createElement('div');
            success.className = 'refresh-success';
            success.textContent = 'Data refreshed successfully!';
            
            document.body.appendChild(success);
            
            setTimeout(() => {
                success.remove();
            }, 2000);
        }
    }

    // ========================================
    // GAMIFICATION MANAGER
    // ========================================
    
    class GamificationManager {
        constructor() {
            this.userPoints = 0;
            this.userLevel = 1;
            this.achievements = [];
        }

        init() {
            this.loadUserData();
            this.setupAchievements();
            this.setupStreaks();
        }

        loadUserData() {
            // Load user data from localStorage or API
            const savedData = localStorage.getItem('itipster_user_data');
            if (savedData) {
                const data = JSON.parse(savedData);
                this.userPoints = data.points || 0;
                this.userLevel = data.level || 1;
                this.achievements = data.achievements || [];
            }
            
            this.updateUserDisplay();
        }

        setupAchievements() {
            this.achievements = [
                { id: 'first_prediction', name: 'First Prediction', description: 'Make your first prediction', earned: false },
                { id: 'winning_streak', name: 'Winning Streak', description: 'Win 5 predictions in a row', earned: false },
                { id: 'high_confidence', name: 'High Confidence', description: 'Make a prediction with 90%+ confidence', earned: false },
                { id: 'daily_user', name: 'Daily User', description: 'Use the platform for 7 consecutive days', earned: false }
            ];
            
            this.checkAchievements();
        }

        setupStreaks() {
            // Setup streak tracking
            this.currentStreak = 0;
            this.bestStreak = 0;
            
            // Load streak data
            const streakData = localStorage.getItem('itipster_streak');
            if (streakData) {
                const data = JSON.parse(streakData);
                this.currentStreak = data.current || 0;
                this.bestStreak = data.best || 0;
            }
            
            this.updateStreakDisplay();
        }

        addPoints(points, reason) {
            this.userPoints += points;
            
            // Check for level up
            const newLevel = Math.floor(this.userPoints / 100) + 1;
            if (newLevel > this.userLevel) {
                this.levelUp(newLevel);
            }
            
            // Save data
            this.saveUserData();
            
            // Show points animation
            this.showPointsAnimation(points, reason);
        }

        levelUp(newLevel) {
            this.userLevel = newLevel;
            
            // Show level up animation
            this.showLevelUpAnimation();
            
            // Unlock new features
            this.unlockFeatures(newLevel);
        }

        showPointsAnimation(points, reason) {
            const animation = document.createElement('div');
            animation.className = 'points-animation';
            animation.innerHTML = `
                <div class="points-value">+${points}</div>
                <div class="points-reason">${reason}</div>
            `;
            
            document.body.appendChild(animation);
            
            setTimeout(() => {
                animation.remove();
            }, 2000);
        }

        showLevelUpAnimation() {
            const animation = document.createElement('div');
            animation.className = 'level-up-animation';
            animation.innerHTML = `
                <div class="level-up-content">
                    <h2>Level Up!</h2>
                    <p>You reached level ${this.userLevel}</p>
                    <div class="level-up-rewards">
                        <div class="reward">New Features Unlocked</div>
                        <div class="reward">Premium Predictions</div>
                    </div>
                </div>
            `;
            
            document.body.appendChild(animation);
            
            setTimeout(() => {
                animation.remove();
            }, 4000);
        }

        unlockFeatures(level) {
            // Unlock features based on level
            const features = {
                2: ['advanced_filters', 'prediction_history'],
                3: ['live_chat', 'expert_insights'],
                5: ['premium_predictions', 'priority_support'],
                10: ['vip_features', 'exclusive_content']
            };
            
            if (features[level]) {
                features[level].forEach(feature => {
                    this.unlockFeature(feature);
                });
            }
        }

        unlockFeature(feature) {
            // Show feature unlock notification
            const notification = document.createElement('div');
            notification.className = 'feature-unlock-notification';
            notification.innerHTML = `
                <div class="unlock-content">
                    <h4>New Feature Unlocked!</h4>
                    <p>${this.getFeatureName(feature)}</p>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.remove();
            }, 3000);
        }

        getFeatureName(feature) {
            const featureNames = {
                'advanced_filters': 'Advanced Filtering Options',
                'prediction_history': 'Detailed Prediction History',
                'live_chat': 'Live Chat Support',
                'expert_insights': 'Expert Insights & Analysis',
                'premium_predictions': 'Premium Predictions Access',
                'priority_support': 'Priority Customer Support',
                'vip_features': 'VIP Features & Benefits',
                'exclusive_content': 'Exclusive Content Access'
            };
            
            return featureNames[feature] || feature;
        }

        checkAchievements() {
            // Check for achievement progress
            this.achievements.forEach(achievement => {
                if (!achievement.earned) {
                    if (this.checkAchievementCondition(achievement.id)) {
                        this.earnAchievement(achievement.id);
                    }
                }
            });
        }

        checkAchievementCondition(achievementId) {
            // Check if achievement conditions are met
            switch (achievementId) {
                case 'first_prediction':
                    return this.userPoints > 0;
                case 'winning_streak':
                    return this.currentStreak >= 5;
                case 'high_confidence':
                    return true; // This would be checked when making predictions
                case 'daily_user':
                    return this.checkDailyUsage();
                default:
                    return false;
            }
        }

        checkDailyUsage() {
            const lastUsage = localStorage.getItem('itipster_last_usage');
            const today = new Date().toDateString();
            
            if (lastUsage === today) {
                return true;
            }
            
            localStorage.setItem('itipster_last_usage', today);
            return false;
        }

        earnAchievement(achievementId) {
            const achievement = this.achievements.find(a => a.id === achievementId);
            if (achievement) {
                achievement.earned = true;
                
                // Show achievement notification
                this.showAchievementNotification(achievement);
                
                // Add points
                this.addPoints(50, `Achievement: ${achievement.name}`);
                
                // Save data
                this.saveUserData();
            }
        }

        showAchievementNotification(achievement) {
            const notification = document.createElement('div');
            notification.className = 'achievement-notification';
            notification.innerHTML = `
                <div class="achievement-content">
                    <div class="achievement-icon">🏆</div>
                    <div class="achievement-info">
                        <h4>${achievement.name}</h4>
                        <p>${achievement.description}</p>
                    </div>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.remove();
            }, 4000);
        }

        updateUserDisplay() {
            // Update points display
            const pointsElement = document.querySelector('.user-points');
            if (pointsElement) {
                pointsElement.textContent = this.userPoints.toLocaleString();
            }
            
            // Update level display
            const levelElement = document.querySelector('.user-level');
            if (levelElement) {
                levelElement.textContent = `Level ${this.userLevel}`;
            }
            
            // Update achievements display
            this.updateAchievementsDisplay();
        }

        updateAchievementsDisplay() {
            const achievementsContainer = document.querySelector('.achievements-list');
            if (!achievementsContainer) return;
            
            achievementsContainer.innerHTML = this.achievements.map(achievement => `
                <div class="achievement-item ${achievement.earned ? 'earned' : ''}">
                    <div class="achievement-icon">${achievement.earned ? '🏆' : '🔒'}</div>
                    <div class="achievement-info">
                        <h4>${achievement.name}</h4>
                        <p>${achievement.description}</p>
                    </div>
                </div>
            `).join('');
        }

        updateStreakDisplay() {
            const streakElement = document.querySelector('.current-streak');
            if (streakElement) {
                streakElement.textContent = this.currentStreak;
            }
            
            const bestStreakElement = document.querySelector('.best-streak');
            if (bestStreakElement) {
                bestStreakElement.textContent = this.bestStreak;
            }
        }

        saveUserData() {
            const data = {
                points: this.userPoints,
                level: this.userLevel,
                achievements: this.achievements
            };
            
            localStorage.setItem('itipster_user_data', JSON.stringify(data));
        }
    }

    // ========================================
    // ANALYTICS MANAGER
    // ========================================
    
    class AnalyticsManager {
        constructor() {
            this.events = [];
            this.sessionStart = Date.now();
        }

        init() {
            this.trackPageView();
            this.setupEventTracking();
        }

        trackPageView() {
            this.trackEvent('page_view', {
                page: window.location.pathname,
                referrer: document.referrer
            });
        }

        setupEventTracking() {
            // Track button clicks
            document.addEventListener('click', (e) => {
                if (e.target.matches('.btn, .prediction-card, .filter-option')) {
                    this.trackEvent('click', {
                        element: e.target.className,
                        text: e.target.textContent.trim()
                    });
                }
            });
            
            // Track form submissions
            document.addEventListener('submit', (e) => {
                this.trackEvent('form_submit', {
                    form: e.target.className || e.target.id
                });
            });
            
            // Track scroll depth
            this.trackScrollDepth();
            
            // Track time on page
            this.trackTimeOnPage();
        }

        trackScrollDepth() {
            let maxScroll = 0;
            
            window.addEventListener('scroll', () => {
                const scrollPercent = Math.round((window.scrollY / (document.body.scrollHeight - window.innerHeight)) * 100);
                
                if (scrollPercent > maxScroll) {
                    maxScroll = scrollPercent;
                    
                    // Track at 25%, 50%, 75%, 100%
                    if ([25, 50, 75, 100].includes(maxScroll)) {
                        this.trackEvent('scroll_depth', {
                            depth: maxScroll
                        });
                    }
                }
            });
        }

        trackTimeOnPage() {
            // Track time on page every 30 seconds
            setInterval(() => {
                const timeOnPage = Math.round((Date.now() - this.sessionStart) / 1000);
                this.trackEvent('time_on_page', {
                    seconds: timeOnPage
                });
            }, 30000);
        }

        trackEvent(eventName, properties = {}) {
            const event = {
                name: eventName,
                properties: {
                    ...properties,
                    timestamp: Date.now(),
                    url: window.location.href,
                    user_agent: navigator.userAgent
                }
            };
            
            this.events.push(event);
            
            // Send to analytics endpoint
            this.sendAnalytics(event);
        }

        sendAnalytics(event) {
            // Send to WordPress REST API
            fetch('/wp-json/itipster-pro/v1/analytics', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-WP-Nonce': window.itipsterPro?.nonce || ''
                },
                body: JSON.stringify(event)
            }).catch(error => {
                console.log('Analytics error:', error);
            });
        }
    }

    // ========================================
    // INITIALIZATION
    // ========================================
    
    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => {
            new PremiumFrontend();
        });
    } else {
        new PremiumFrontend();
    }

    // Export for global access
    window.iTipsterPro = {
        PremiumFrontend: PremiumFrontend,
        AnimationManager: AnimationManager,
        InteractionManager: InteractionManager,
        RealTimeManager: RealTimeManager,
        GamificationManager: GamificationManager,
        AnalyticsManager: AnalyticsManager
    };

})(); 