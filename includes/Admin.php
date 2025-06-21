<?php
namespace ADPM\iTipsterPro;

/**
 * Admin class for WordPress admin functionality
 * 
 * @package ADPM\iTipsterPro
 */
class Admin {
    
    /**
     * Constructor
     */
    public function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'init_admin'));
        add_action('wp_ajax_itipster_save_settings', array($this, 'save_settings'));
        add_action('wp_ajax_itipster_test_api', array($this, 'test_api'));
        add_action('wp_ajax_itipster_clear_cache', array($this, 'clear_cache'));
    }
    
    /**
     * Initialize admin
     */
    public function init_admin() {
        // Register settings
        register_setting('itipster_pro_settings', 'itipster_pro_settings');
        
        // Add settings sections
        add_settings_section(
            'itipster_api_settings',
            __('API Settings', 'itipster-pro'),
            array($this, 'api_settings_section_callback'),
            'itipster_pro_settings'
        );
        
        add_settings_section(
            'itipster_general_settings',
            __('General Settings', 'itipster-pro'),
            array($this, 'general_settings_section_callback'),
            'itipster_pro_settings'
        );
        
        // Add settings fields
        add_settings_field(
            'api_token',
            __('OddAlerts API Token', 'itipster-pro'),
            array($this, 'api_token_field_callback'),
            'itipster_pro_settings',
            'itipster_api_settings'
        );
        
        add_settings_field(
            'demo_mode',
            __('Demo Mode', 'itipster-pro'),
            array($this, 'demo_mode_field_callback'),
            'itipster_pro_settings',
            'itipster_general_settings'
        );
        
        add_settings_field(
            'rate_limit',
            __('API Rate Limit', 'itipster-pro'),
            array($this, 'rate_limit_field_callback'),
            'itipster_pro_settings',
            'itipster_general_settings'
        );
    }
    
    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_menu_page(
            __('iTipster Pro', 'itipster-pro'),
            __('iTipster Pro', 'itipster-pro'),
            'manage_options',
            'itipster-pro',
            array($this, 'admin_dashboard_page'),
            'dashicons-chart-line',
            30
        );
        
        add_submenu_page(
            'itipster-pro',
            __('Dashboard', 'itipster-pro'),
            __('Dashboard', 'itipster-pro'),
            'manage_options',
            'itipster-pro',
            array($this, 'admin_dashboard_page')
        );
        
        add_submenu_page(
            'itipster-pro',
            __('Analytics', 'itipster-pro'),
            __('Analytics', 'itipster-pro'),
            'manage_options',
            'itipster-analytics',
            array($this, 'admin_analytics_page')
        );
        
        add_submenu_page(
            'itipster-pro',
            __('Settings', 'itipster-pro'),
            __('Settings', 'itipster-pro'),
            'manage_options',
            'itipster-settings',
            array($this, 'admin_settings_page')
        );
        
        add_submenu_page(
            'itipster-pro',
            __('Predictions', 'itipster-pro'),
            __('Predictions', 'itipster-pro'),
            'manage_options',
            'edit.php?post_type=itipster_prediction'
        );
        
        add_submenu_page(
            'itipster-pro',
            __('Fixtures', 'itipster-pro'),
            __('Fixtures', 'itipster-pro'),
            'manage_options',
            'edit.php?post_type=itipster_fixture'
        );
    }
    
    /**
     * Admin dashboard page
     */
    public function admin_dashboard_page() {
        $api_manager = new APIs\ApiManager();
        $api_status = $api_manager->get_api_status();
        $rate_limit_info = $api_manager->get_rate_limit_info();
        
        include ITIPSTER_PRO_PLUGIN_PATH . 'templates/admin/dashboard.php';
    }
    
    /**
     * Admin analytics page
     */
    public function admin_analytics_page() {
        $analytics_data = $this->get_analytics_data();
        include ITIPSTER_PRO_PLUGIN_PATH . 'templates/admin/analytics.php';
    }
    
    /**
     * Admin settings page
     */
    public function admin_settings_page() {
        include ITIPSTER_PRO_PLUGIN_PATH . 'templates/admin/settings.php';
    }
    
    /**
     * API settings section callback
     */
    public function api_settings_section_callback() {
        echo '<p>' . __('Configure your OddAlerts API settings below.', 'itipster-pro') . '</p>';
    }
    
    /**
     * General settings section callback
     */
    public function general_settings_section_callback() {
        echo '<p>' . __('Configure general plugin settings.', 'itipster-pro') . '</p>';
    }
    
    /**
     * API token field callback
     */
    public function api_token_field_callback() {
        $options = get_option('itipster_pro_settings', array());
        $api_token = isset($options['api_token']) ? $options['api_token'] : '';
        
        echo '<input type="text" id="api_token" name="itipster_pro_settings[api_token]" value="' . esc_attr($api_token) . '" class="regular-text" />';
        echo '<p class="description">' . __('Enter your OddAlerts API token. Get one from <a href="https://oddalerts.com" target="_blank">oddalerts.com</a>', 'itipster-pro') . '</p>';
    }
    
    /**
     * Demo mode field callback
     */
    public function demo_mode_field_callback() {
        $options = get_option('itipster_pro_settings', array());
        $demo_mode = isset($options['demo_mode']) ? $options['demo_mode'] : true;
        
        echo '<input type="checkbox" id="demo_mode" name="itipster_pro_settings[demo_mode]" value="1" ' . checked(1, $demo_mode, false) . ' />';
        echo '<label for="demo_mode">' . __('Enable demo mode (uses sample data instead of API calls)', 'itipster-pro') . '</label>';
    }
    
    /**
     * Rate limit field callback
     */
    public function rate_limit_field_callback() {
        $options = get_option('itipster_pro_settings', array());
        $rate_limit = isset($options['rate_limit']) ? $options['rate_limit'] : 100;
        
        echo '<input type="number" id="rate_limit" name="itipster_pro_settings[rate_limit]" value="' . esc_attr($rate_limit) . '" min="1" max="1000" />';
        echo '<p class="description">' . __('Maximum API requests per hour', 'itipster-pro') . '</p>';
    }
    
    /**
     * Save settings via AJAX
     */
    public function save_settings() {
        check_ajax_referer('itipster_admin_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Insufficient permissions');
        }
        
        $settings = $_POST['settings'] ?? array();
        $sanitized_settings = $this->sanitize_settings($settings);
        
        update_option('itipster_pro_settings', $sanitized_settings);
        
        wp_send_json_success('Settings saved successfully');
    }
    
    /**
     * Test API connection
     */
    public function test_api() {
        check_ajax_referer('itipster_admin_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Insufficient permissions');
        }
        
        $api_manager = new APIs\ApiManager();
        $result = $api_manager->test_connection();
        
        wp_send_json($result);
    }
    
    /**
     * Clear cache
     */
    public function clear_cache() {
        check_ajax_referer('itipster_admin_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Insufficient permissions');
        }
        
        $api_manager = new APIs\ApiManager();
        $api_manager->clear_cache();
        
        wp_send_json_success('Cache cleared successfully');
    }
    
    /**
     * Sanitize settings
     */
    private function sanitize_settings($settings) {
        $sanitized = array();
        
        if (isset($settings['api_token'])) {
            $sanitized['api_token'] = sanitize_text_field($settings['api_token']);
        }
        
        if (isset($settings['demo_mode'])) {
            $sanitized['demo_mode'] = (bool) $settings['demo_mode'];
        }
        
        if (isset($settings['rate_limit'])) {
            $sanitized['rate_limit'] = intval($settings['rate_limit']);
        }
        
        if (isset($settings['cache_duration'])) {
            $sanitized['cache_duration'] = intval($settings['cache_duration']);
        }
        
        return $sanitized;
    }
    
    /**
     * Get analytics data
     */
    private function get_analytics_data() {
        global $wpdb;
        
        // Get prediction statistics
        $total_predictions = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}itipster_predictions");
        $avg_confidence = $wpdb->get_var("SELECT AVG(confidence_score) FROM {$wpdb->prefix}itipster_predictions");
        $avg_value_rating = $wpdb->get_var("SELECT AVG(value_rating) FROM {$wpdb->prefix}itipster_predictions");
        
        // Get user statistics
        $total_users = $wpdb->get_var("SELECT COUNT(DISTINCT user_id) FROM {$wpdb->prefix}itipster_subscriptions");
        $active_subscriptions = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}itipster_subscriptions WHERE status = 'active'");
        
        // Get recent activity
        $recent_predictions = $wpdb->get_results("
            SELECT * FROM {$wpdb->prefix}itipster_predictions 
            ORDER BY created_at DESC 
            LIMIT 10
        ");
        
        return array(
            'total_predictions' => $total_predictions,
            'avg_confidence' => round($avg_confidence, 2),
            'avg_value_rating' => round($avg_value_rating, 2),
            'total_users' => $total_users,
            'active_subscriptions' => $active_subscriptions,
            'recent_predictions' => $recent_predictions
        );
    }
} 