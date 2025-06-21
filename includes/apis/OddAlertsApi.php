<?php
namespace ADPM\iTipsterPro\APIs;

/**
 * OddAlerts API class
 * 
 * @package ADPM\iTipsterPro
 */
class OddAlertsApi {
    
    /**
     * API base URL
     */
    private $api_base_url = 'https://api.oddalerts.com/v1';
    
    /**
     * API token
     */
    private $api_token;
    
    /**
     * Constructor
     */
    public function __construct($api_token = '') {
        $this->api_token = $api_token;
    }
    
    /**
     * Get upcoming fixtures
     */
    public function get_upcoming_fixtures($league = '', $limit = 20) {
        $endpoint = '/fixtures/upcoming';
        $params = [
            'limit' => $limit
        ];
        
        if (!empty($league)) {
            $params['league'] = $league;
        }
        
        return $this->make_request($endpoint, $params);
    }
    
    /**
     * Get live odds
     */
    public function get_live_odds($fixture_id) {
        return $this->make_request('odds/live', array('fixture_id' => $fixture_id));
    }
    
    /**
     * Get AI predictions
     */
    public function get_ai_predictions($fixture_id) {
        return $this->make_request('predictions/ai', array('fixture_id' => $fixture_id));
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
    public function get_value_bets($league = '', $limit = 10) {
        $params = array('limit' => $limit);
        
        if (!empty($league)) {
            $params['league'] = $league;
        }
        
        return $this->make_request('bets/value', $params);
    }
    
    /**
     * Get team trends
     */
    public function get_team_trends($team_id) {
        $endpoint = '/trends';
        $params = [
            'team_id' => $team_id
        ];
        
        return $this->make_request($endpoint, $params);
    }
    
    /**
     * Get probability data
     */
    public function get_probability($fixture_id) {
        $endpoint = '/probability';
        $params = [
            'fixture_id' => $fixture_id
        ];
        
        return $this->make_request($endpoint, $params);
    }
    
    /**
     * Get betslips
     */
    public function get_betslips($type = 'trending', $limit = 5) {
        $endpoint = '/betslips';
        $params = [
            'type' => $type,
            'limit' => $limit
        ];
        
        return $this->make_request($endpoint, $params);
    }
    
    /**
     * Make API request
     */
    public function make_request($endpoint, $params = array()) {
        $url = $this->api_base_url . '/' . $endpoint;
        
        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }
        
        $args = array(
            'headers' => array(
                'Authorization' => 'Bearer ' . $this->api_token,
                'Content-Type' => 'application/json',
                'User-Agent' => 'iTipster-Pro-WordPress/1.0.0'
            ),
            'timeout' => 30,
            'sslverify' => true
        );
        
        $response = wp_remote_get($url, $args);
        
        if (is_wp_error($response)) {
            return array('error' => $response->get_error_message());
        }
        
        $status_code = wp_remote_retrieve_response_code($response);
        $body = wp_remote_retrieve_body($response);
        
        if ($status_code !== 200) {
            return array('error' => 'API request failed with status ' . $status_code);
        }
        
        $data = json_decode($body, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            return array('error' => 'Invalid JSON response from API');
        }
        
        return $data;
    }
    
    /**
     * Test API connection
     */
    public function test_connection() {
        $result = $this->get_status();
        
        if (isset($result['error'])) {
            return array('success' => false, 'message' => $result['error']);
        }
        
        return array('success' => true, 'message' => 'API connection successful');
    }
    
    /**
     * Get API status
     */
    public function get_status() {
        return $this->make_request('status');
    }
    
    /**
     * Get API usage statistics
     */
    public function get_usage_stats() {
        $endpoint = '/usage';
        
        $result = $this->make_request($endpoint);
        
        if ($result['success']) {
            return $result['data'];
        } else {
            return [
                'total_requests' => 0,
                'requests_today' => 0,
                'requests_this_month' => 0,
                'rate_limit' => 0,
                'requests_remaining' => 0
            ];
        }
    }
} 