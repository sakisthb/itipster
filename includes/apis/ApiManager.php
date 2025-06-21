<?php
namespace ADPM\iTipsterPro\APIs;

/**
 * API Manager class for handling all API operations
 * 
 * @package ADPM\iTipsterPro
 */
class ApiManager {
    
    /**
     * API base URL
     */
    private $api_base_url = 'https://api.oddalerts.com/v1';
    
    /**
     * API token
     */
    private $api_token;
    
    /**
     * Cache duration in seconds
     */
    private $cache_duration = 900; // 15 minutes
    
    /**
     * Rate limit
     */
    private $rate_limit = 100;
    
    /**
     * Demo mode flag
     */
    private $demo_mode = true;
    
    /**
     * OddAlerts API instance
     */
    private $oddalerts_api;
    
    /**
     * Demo data instance
     */
    private $demo_data;
    
    /**
     * Constructor
     */
    public function __construct() {
        $settings = get_option('itipster_pro_settings', array());
        $this->api_token = $settings['api_token'] ?? '';
        $this->cache_duration = $settings['cache_duration'] ?? 900;
        $this->rate_limit = $settings['rate_limit'] ?? 100;
        $this->demo_mode = $settings['demo_mode'] ?? true;
        $this->oddalerts_api = new OddAlertsApi();
        $this->demo_data = new DemoData();
    }
    
    /**
     * Make API request
     */
    public function make_request($endpoint, $params = array()) {
        // Check rate limit
        if (!$this->check_rate_limit()) {
            return array('error' => 'Rate limit exceeded');
        }
        
        // Check cache first
        $cache_key = 'itipster_api_' . md5($endpoint . serialize($params));
        $cached_result = wp_cache_get($cache_key);
        
        if ($cached_result !== false) {
            return $cached_result;
        }
        
        // If demo mode, return demo data
        if ($this->demo_mode) {
            $demo_data = new DemoData();
            $result = $demo_data->get_demo_data($endpoint, $params);
        } else {
            // Make actual API request
            $api = new OddAlertsApi($this->api_token);
            $result = $api->make_request($endpoint, $params);
        }
        
        // Cache the result
        wp_cache_set($cache_key, $result, '', $this->cache_duration);
        
        return $result;
    }
    
    /**
     * Get AI predictions
     */
    public function get_ai_predictions($league = '', $sport = '') {
        $params = array();
        
        if (!empty($league)) {
            $params['league'] = $league;
        }
        
        if (!empty($sport)) {
            $params['sport'] = $sport;
        }
        
        return $this->make_request('predictions/ai', $params);
    }
    
    /**
     * Get live odds
     */
    public function get_live_odds($fixture_id) {
        return $this->make_request('odds/live', array('fixture_id' => $fixture_id));
    }
    
    /**
     * Get fixtures
     */
    public function get_fixtures($league = '', $date = '') {
        $params = array();
        
        if (!empty($league)) {
            $params['league'] = $league;
        }
        
        if (!empty($date)) {
            $params['date'] = $date;
        }
        
        return $this->make_request('fixtures', $params);
    }
    
    /**
     * Get leagues
     */
    public function get_leagues() {
        return $this->make_request('leagues');
    }
    
    /**
     * Get sports
     */
    public function get_sports() {
        return $this->make_request('sports');
    }
    
    /**
     * Get value bets
     */
    public function get_value_bets($min_value = 7.0) {
        return $this->make_request('bets/value', array('min_value' => $min_value));
    }
    
    /**
     * Get API status
     */
    public function get_api_status() {
        if ($this->demo_mode) {
            return array(
                'status' => 'demo_mode',
                'message' => 'Running in demo mode with sample data'
            );
        }
        
        $result = $this->make_request('status');
        
        if (isset($result['error'])) {
            return array(
                'status' => 'error',
                'message' => $result['error']
            );
        }
        
        return array(
            'status' => 'connected',
            'message' => 'API connection successful'
        );
    }
    
    /**
     * Test API connection
     */
    public function test_connection() {
        $result = $this->get_api_status();
        
        if ($result['status'] === 'connected') {
            return array('success' => true, 'message' => 'API connection successful');
        } else {
            return array('success' => false, 'message' => $result['message']);
        }
    }
    
    /**
     * Get rate limit info
     */
    public function get_rate_limit_info() {
        $current_usage = wp_cache_get('itipster_rate_limit_usage');
        
        if ($current_usage === false) {
            $current_usage = 0;
        }
        
        return array(
            'limit' => $this->rate_limit,
            'used' => $current_usage,
            'remaining' => $this->rate_limit - $current_usage
        );
    }
    
    /**
     * Check rate limit
     */
    private function check_rate_limit() {
        $current_usage = wp_cache_get('itipster_rate_limit_usage');
        
        if ($current_usage === false) {
            $current_usage = 0;
        }
        
        if ($current_usage >= $this->rate_limit) {
            return false;
        }
        
        wp_cache_set('itipster_rate_limit_usage', $current_usage + 1, '', 3600);
        return true;
    }
    
    /**
     * Clear cache
     */
    public function clear_cache() {
        wp_cache_flush();
        wp_cache_set('itipster_rate_limit_usage', 0, '', 3600);
    }
    
    /**
     * Get API settings
     */
    public function get_api_settings() {
        return array(
            'api_token' => $this->api_token,
            'cache_duration' => $this->cache_duration,
            'rate_limit' => $this->rate_limit,
            'demo_mode' => $this->demo_mode
        );
    }
    
    /**
     * Get upcoming fixtures
     */
    public function get_upcoming_fixtures($league = '', $limit = 20) {
        $cache_key = "itipster_upcoming_fixtures_{$league}_{$limit}";
        $cached_data = $this->get_cached_data($cache_key);
        
        if ($cached_data !== false) {
            return $cached_data;
        }
        
        if ($this->is_demo_mode()) {
            $data = $this->demo_data->get_upcoming_fixtures($league, $limit);
        } else {
            $data = $this->oddalerts_api->get_upcoming_fixtures($league, $limit);
        }
        
        $this->set_cached_data($cache_key, $data);
        return $data;
    }
    
    /**
     * Get team trends
     */
    public function get_team_trends($team_id) {
        $cache_key = "itipster_team_trends_{$team_id}";
        $cached_data = $this->get_cached_data($cache_key);
        
        if ($cached_data !== false) {
            return $cached_data;
        }
        
        if ($this->is_demo_mode()) {
            $data = $this->demo_data->get_team_trends($team_id);
        } else {
            $data = $this->oddalerts_api->get_team_trends($team_id);
        }
        
        $this->set_cached_data($cache_key, $data);
        return $data;
    }
    
    /**
     * Get probability data
     */
    public function get_probability($fixture_id) {
        $cache_key = "itipster_probability_{$fixture_id}";
        $cached_data = $this->get_cached_data($cache_key);
        
        if ($cached_data !== false) {
            return $cached_data;
        }
        
        if ($this->is_demo_mode()) {
            $data = $this->demo_data->get_probability($fixture_id);
        } else {
            $data = $this->oddalerts_api->get_probability($fixture_id);
        }
        
        $this->set_cached_data($cache_key, $data);
        return $data;
    }
    
    /**
     * Get betslips (accumulators)
     */
    public function get_betslips($type = 'trending', $limit = 5) {
        $cache_key = "itipster_betslips_{$type}_{$limit}";
        $cached_data = $this->get_cached_data($cache_key);
        
        if ($cached_data !== false) {
            return $cached_data;
        }
        
        if ($this->is_demo_mode()) {
            $data = $this->demo_data->get_betslips($type, $limit);
        } else {
            $data = $this->oddalerts_api->get_betslips($type, $limit);
        }
        
        $this->set_cached_data($cache_key, $data);
        return $data;
    }
    
    /**
     * Check if demo mode is enabled
     */
    private function is_demo_mode() {
        return isset($this->settings['demo_mode']) && $this->settings['demo_mode'];
    }
    
    /**
     * Get cached data
     */
    private function get_cached_data($key, $duration = null) {
        if ($duration === null) {
            $duration = isset($this->settings['cache_duration']) ? $this->settings['cache_duration'] : 900;
        }
        
        $cached = get_transient($key);
        if ($cached !== false) {
            return $cached;
        }
        
        return false;
    }
    
    /**
     * Set cached data
     */
    private function set_cached_data($key, $data, $duration = null) {
        if ($duration === null) {
            $duration = isset($this->settings['cache_duration']) ? $this->settings['cache_duration'] : 900;
        }
        
        set_transient($key, $data, $duration);
    }
} 