<?php
namespace ADPM\iTipsterPro;

/**
 * Main plugin class
 * 
 * @package ADPM\iTipsterPro
 */
class Main {
    
    /**
     * Plugin instance
     */
    private static $instance = null;
    
    /**
     * Admin instance
     */
    private $admin;
    
    /**
     * Frontend instance
     */
    private $frontend;
    
    /**
     * Predictions instance
     */
    private $predictions;
    
    /**
     * User Management instance
     */
    private $user_management;
    
    /**
     * API Manager instance
     */
    private $api_manager;
    
    /**
     * Subscription Manager instance
     */
    private $subscription_manager;
    
    /**
     * User Profile instance
     */
    private $user_profile;
    
    /**
     * User Registration instance
     */
    private $user_registration;
    
    /**
     * User Authentication instance
     */
    private $user_authentication;
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->init_hooks();
        $this->load_dependencies();
    }
    
    /**
     * Initialize hooks
     */
    private function init_hooks() {
        add_action('init', array($this, 'init'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_assets'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
        add_action('wp_ajax_itipster_get_predictions', array($this, 'ajax_get_predictions'));
        add_action('wp_ajax_nopriv_itipster_get_predictions', array($this, 'ajax_get_predictions'));
        add_action('wp_ajax_itipster_get_live_odds', array($this, 'ajax_get_live_odds'));
        add_action('wp_ajax_nopriv_itipster_get_live_odds', array($this, 'ajax_get_live_odds'));
        
        // Frontend rewrite rules
        add_action('init', array($this, 'add_frontend_rewrite_rules'));
        add_action('template_redirect', array($this, 'frontend_template_redirect'));
    }
    
    /**
     * Load dependencies
     */
    private function load_dependencies() {
        require_once ITIPSTER_PRO_PLUGIN_PATH . 'includes/Admin.php';
        require_once ITIPSTER_PRO_PLUGIN_PATH . 'includes/Frontend.php';
        require_once ITIPSTER_PRO_PLUGIN_PATH . 'includes/Predictions.php';
        require_once ITIPSTER_PRO_PLUGIN_PATH . 'includes/UserManagement.php';
        require_once ITIPSTER_PRO_PLUGIN_PATH . 'includes/UserManagement_Installer.php';
        
        // Load user management classes
        require_once ITIPSTER_PRO_PLUGIN_PATH . 'includes/users/UserRegistration.php';
        require_once ITIPSTER_PRO_PLUGIN_PATH . 'includes/users/UserAuthentication.php';
        require_once ITIPSTER_PRO_PLUGIN_PATH . 'includes/users/UserProfile.php';
        require_once ITIPSTER_PRO_PLUGIN_PATH . 'includes/users/SubscriptionManager.php';
        
        require_once ITIPSTER_PRO_PLUGIN_PATH . 'includes/apis/ApiManager.php';
        require_once ITIPSTER_PRO_PLUGIN_PATH . 'includes/apis/OddAlertsApi.php';
        require_once ITIPSTER_PRO_PLUGIN_PATH . 'includes/apis/DemoData.php';
        require_once ITIPSTER_PRO_PLUGIN_PATH . 'includes/data/demo-fixtures.php';
        require_once ITIPSTER_PRO_PLUGIN_PATH . 'includes/data/demo-predictions.php';
        
        $this->admin = new Admin();
        $this->frontend = new Frontend();
        $this->predictions = new Predictions();
        $this->user_management = new UserManagement();
        $this->api_manager = new APIs\ApiManager();
        
        // Initialize user management classes
        $this->initialize_user_management();
    }
    
    /**
     * Initialize user management classes
     */
    private function initialize_user_management() {
        // Initialize with dependency injection
        $subscription_manager = new Users\SubscriptionManager();
        $user_profile = new Users\UserProfile($subscription_manager);
        $user_registration = new Users\UserRegistration($user_profile, $subscription_manager);
        $user_authentication = new Users\UserAuthentication($user_profile);
        
        // Store instances for potential use
        $this->subscription_manager = $subscription_manager;
        $this->user_profile = $user_profile;
        $this->user_registration = $user_registration;
        $this->user_authentication = $user_authentication;
    }
    
    /**
     * Initialize plugin
     */
    public function init() {
        // Add custom post types
        $this->register_post_types();
        
        // Add custom taxonomies
        $this->register_taxonomies();
        
        // Add rewrite rules
        $this->add_rewrite_rules();
    }
    
    /**
     * Register custom post types
     */
    private function register_post_types() {
        // Predictions post type
        register_post_type('itipster_prediction', array(
            'labels' => array(
                'name' => __('Predictions', 'itipster-pro'),
                'singular_name' => __('Prediction', 'itipster-pro'),
                'add_new' => __('Add New Prediction', 'itipster-pro'),
                'add_new_item' => __('Add New Prediction', 'itipster-pro'),
                'edit_item' => __('Edit Prediction', 'itipster-pro'),
                'new_item' => __('New Prediction', 'itipster-pro'),
                'view_item' => __('View Prediction', 'itipster-pro'),
                'search_items' => __('Search Predictions', 'itipster-pro'),
                'not_found' => __('No predictions found', 'itipster-pro'),
                'not_found_in_trash' => __('No predictions found in trash', 'itipster-pro'),
            ),
            'public' => true,
            'has_archive' => true,
            'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
            'menu_icon' => 'dashicons-chart-line',
            'rewrite' => array('slug' => 'predictions'),
            'show_in_rest' => true,
        ));
        
        // Fixtures post type
        register_post_type('itipster_fixture', array(
            'labels' => array(
                'name' => __('Fixtures', 'itipster-pro'),
                'singular_name' => __('Fixture', 'itipster-pro'),
                'add_new' => __('Add New Fixture', 'itipster-pro'),
                'add_new_item' => __('Add New Fixture', 'itipster-pro'),
                'edit_item' => __('Edit Fixture', 'itipster-pro'),
                'new_item' => __('New Fixture', 'itipster-pro'),
                'view_item' => __('View Fixture', 'itipster-pro'),
                'search_items' => __('Search Fixtures', 'itipster-pro'),
                'not_found' => __('No fixtures found', 'itipster-pro'),
                'not_found_in_trash' => __('No fixtures found in trash', 'itipster-pro'),
            ),
            'public' => true,
            'has_archive' => true,
            'supports' => array('title', 'editor', 'thumbnail'),
            'menu_icon' => 'dashicons-calendar-alt',
            'rewrite' => array('slug' => 'fixtures'),
            'show_in_rest' => true,
        ));
    }
    
    /**
     * Register custom taxonomies
     */
    private function register_taxonomies() {
        // League taxonomy
        register_taxonomy('itipster_league', array('itipster_prediction', 'itipster_fixture'), array(
            'labels' => array(
                'name' => __('Leagues', 'itipster-pro'),
                'singular_name' => __('League', 'itipster-pro'),
                'search_items' => __('Search Leagues', 'itipster-pro'),
                'all_items' => __('All Leagues', 'itipster-pro'),
                'parent_item' => __('Parent League', 'itipster-pro'),
                'parent_item_colon' => __('Parent League:', 'itipster-pro'),
                'edit_item' => __('Edit League', 'itipster-pro'),
                'update_item' => __('Update League', 'itipster-pro'),
                'add_new_item' => __('Add New League', 'itipster-pro'),
                'new_item_name' => __('New League Name', 'itipster-pro'),
                'menu_name' => __('Leagues', 'itipster-pro'),
            ),
            'hierarchical' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'league'),
            'show_in_rest' => true,
        ));
        
        // Sport taxonomy
        register_taxonomy('itipster_sport', array('itipster_prediction', 'itipster_fixture'), array(
            'labels' => array(
                'name' => __('Sports', 'itipster-pro'),
                'singular_name' => __('Sport', 'itipster-pro'),
                'search_items' => __('Search Sports', 'itipster-pro'),
                'all_items' => __('All Sports', 'itipster-pro'),
                'edit_item' => __('Edit Sport', 'itipster-pro'),
                'update_item' => __('Update Sport', 'itipster-pro'),
                'add_new_item' => __('Add New Sport', 'itipster-pro'),
                'new_item_name' => __('New Sport Name', 'itipster-pro'),
                'menu_name' => __('Sports', 'itipster-pro'),
            ),
            'hierarchical' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'sport'),
            'show_in_rest' => true,
        ));
    }
    
    /**
     * Add rewrite rules
     */
    private function add_rewrite_rules() {
        add_rewrite_rule(
            'predictions/([^/]+)/?$',
            'index.php?post_type=itipster_prediction&name=$matches[1]',
            'top'
        );
        
        add_rewrite_rule(
            'fixtures/([^/]+)/?$',
            'index.php?post_type=itipster_fixture&name=$matches[1]',
            'top'
        );
    }
    
    /**
     * Enqueue frontend assets
     */
    public function enqueue_frontend_assets() {
        // Core Design System CSS
        wp_enqueue_style(
            'itipster-design-system',
            ITIPSTER_PRO_PLUGIN_URL . 'assets/css/design-system.css',
            array(),
            ITIPSTER_PRO_VERSION
        );
        
        // Theme Manager CSS
        wp_enqueue_style(
            'itipster-theme-manager',
            ITIPSTER_PRO_PLUGIN_URL . 'assets/css/theme-manager.css',
            array('itipster-design-system'),
            ITIPSTER_PRO_VERSION
        );
        
        // UI Components CSS
        wp_enqueue_style(
            'itipster-components',
            ITIPSTER_PRO_PLUGIN_URL . 'assets/css/components.css',
            array('itipster-design-system'),
            ITIPSTER_PRO_VERSION
        );
        
        // Premium Frontend CSS
        wp_enqueue_style(
            'itipster-premium-css',
            ITIPSTER_PRO_PLUGIN_URL . 'assets/css/frontend-premium.css',
            array('itipster-components'),
            ITIPSTER_PRO_VERSION
        );
        
        // Glassmorphism UI CSS
        wp_enqueue_style(
            'itipster-glassmorphism-css',
            ITIPSTER_PRO_PLUGIN_URL . 'assets/css/glassmorphism-ui.css',
            array('itipster-components'),
            ITIPSTER_PRO_VERSION
        );
        
        // Mobile-responsive CSS
        wp_enqueue_style(
            'itipster-mobile-css',
            ITIPSTER_PRO_PLUGIN_URL . 'assets/css/mobile-responsive.css',
            array('itipster-glassmorphism-css'),
            ITIPSTER_PRO_VERSION
        );
        
        // PWA-specific CSS
        wp_enqueue_style(
            'itipster-pwa-css',
            ITIPSTER_PRO_PLUGIN_URL . 'assets/css/pwa-styles.css',
            array('itipster-mobile-css'),
            ITIPSTER_PRO_VERSION
        );
        
        // AOS library for animations
        wp_enqueue_style(
            'aos-css',
            'https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css',
            array(),
            '2.3.4'
        );
        wp_enqueue_script(
            'aos-js',
            'https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js',
            array(),
            '2.3.4',
            true
        );
        wp_add_inline_script('aos-js', 'AOS.init({ duration: 600, once: true });');
        
        // Theme Manager JavaScript
        wp_enqueue_script(
            'itipster-theme-manager',
            ITIPSTER_PRO_PLUGIN_URL . 'assets/js/theme-manager.js',
            array(),
            ITIPSTER_PRO_VERSION,
            true
        );
        
        // UI Interactions JavaScript
        wp_enqueue_script(
            'itipster-ui-interactions',
            ITIPSTER_PRO_PLUGIN_URL . 'assets/js/ui-interactions.js',
            array('itipster-theme-manager'),
            ITIPSTER_PRO_VERSION,
            true
        );
        
        // PWA Manager JavaScript
        wp_enqueue_script(
            'itipster-pwa-manager',
            ITIPSTER_PRO_PLUGIN_URL . 'assets/js/pwa-manager.js',
            array('itipster-ui-interactions'),
            ITIPSTER_PRO_VERSION,
            true
        );
        
        // Frontend JavaScript
        wp_enqueue_script(
            'itipster-frontend-js',
            ITIPSTER_PRO_PLUGIN_URL . 'assets/js/frontend-live.js',
            array('jquery', 'itipster-ui-interactions', 'itipster-pwa-manager'),
            ITIPSTER_PRO_VERSION,
            true
        );
        
        // Predictions JavaScript
        wp_enqueue_script(
            'itipster-predictions-js',
            ITIPSTER_PRO_PLUGIN_URL . 'assets/js/predictions-manager.js',
            array('jquery'),
            ITIPSTER_PRO_VERSION,
            true
        );
        
        // Mobile interactions JavaScript
        wp_enqueue_script(
            'itipster-mobile-js',
            ITIPSTER_PRO_PLUGIN_URL . 'assets/js/mobile-interactions.js',
            array('jquery', 'itipster-ui-interactions'),
            ITIPSTER_PRO_VERSION,
            true
        );
        
        // Add PWA meta tags
        add_action('wp_head', array($this, 'add_pwa_meta_tags'));
        
        // Localize script
        wp_localize_script('itipster-frontend-js', 'itipster_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('itipster_nonce'),
            'user_id' => get_current_user_id(),
            'strings' => array(
                'loading' => __('Loading...', 'itipster-pro'),
                'error' => __('An error occurred', 'itipster-pro'),
                'no_predictions' => __('No predictions available', 'itipster-pro'),
            )
        ));
    }
    
    /**
     * Add PWA meta tags to head
     */
    public function add_pwa_meta_tags() {
        ?>
        <!-- PWA Meta Tags -->
        <link rel="manifest" href="<?php echo ITIPSTER_PRO_PLUGIN_URL; ?>manifest.json">
        <meta name="theme-color" content="#007AFF">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="default">
        <meta name="apple-mobile-web-app-title" content="iTipster Pro">
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="application-name" content="iTipster Pro">
        
        <!-- iOS Icons -->
        <link rel="apple-touch-icon" sizes="72x72" href="<?php echo ITIPSTER_PRO_PLUGIN_URL; ?>assets/images/pwa/icon-72x72.png">
        <link rel="apple-touch-icon" sizes="96x96" href="<?php echo ITIPSTER_PRO_PLUGIN_URL; ?>assets/images/pwa/icon-96x96.png">
        <link rel="apple-touch-icon" sizes="128x128" href="<?php echo ITIPSTER_PRO_PLUGIN_URL; ?>assets/images/pwa/icon-128x128.png">
        <link rel="apple-touch-icon" sizes="144x144" href="<?php echo ITIPSTER_PRO_PLUGIN_URL; ?>assets/images/pwa/icon-144x144.png">
        <link rel="apple-touch-icon" sizes="152x152" href="<?php echo ITIPSTER_PRO_PLUGIN_URL; ?>assets/images/pwa/icon-152x152.png">
        <link rel="apple-touch-icon" sizes="192x192" href="<?php echo ITIPSTER_PRO_PLUGIN_URL; ?>assets/images/pwa/icon-192x192.png">
        <link rel="apple-touch-icon" sizes="384x384" href="<?php echo ITIPSTER_PRO_PLUGIN_URL; ?>assets/images/pwa/icon-384x384.png">
        <link rel="apple-touch-icon" sizes="512x512" href="<?php echo ITIPSTER_PRO_PLUGIN_URL; ?>assets/images/pwa/icon-512x512.png">
        
        <!-- iOS Splash Screens -->
        <link rel="apple-touch-startup-image" media="screen and (device-width: 430px) and (device-height: 932px) and (-webkit-device-pixel-ratio: 3)" href="<?php echo ITIPSTER_PRO_PLUGIN_URL; ?>assets/images/pwa/splash-1290x2796.png">
        <link rel="apple-touch-startup-image" media="screen and (device-width: 393px) and (device-height: 852px) and (-webkit-device-pixel-ratio: 3)" href="<?php echo ITIPSTER_PRO_PLUGIN_URL; ?>assets/images/pwa/splash-1179x2556.png">
        <link rel="apple-touch-startup-image" media="screen and (device-width: 428px) and (device-height: 926px) and (-webkit-device-pixel-ratio: 3)" href="<?php echo ITIPSTER_PRO_PLUGIN_URL; ?>assets/images/pwa/splash-1284x2778.png">
        <link rel="apple-touch-startup-image" media="screen and (device-width: 390px) and (device-height: 844px) and (-webkit-device-pixel-ratio: 3)" href="<?php echo ITIPSTER_PRO_PLUGIN_URL; ?>assets/images/pwa/splash-1170x2532.png">
        <link rel="apple-touch-startup-image" media="screen and (device-width: 375px) and (device-height: 812px) and (-webkit-device-pixel-ratio: 3)" href="<?php echo ITIPSTER_PRO_PLUGIN_URL; ?>assets/images/pwa/splash-1125x2436.png">
        <link rel="apple-touch-startup-image" media="screen and (device-width: 414px) and (device-height: 896px) and (-webkit-device-pixel-ratio: 2)" href="<?php echo ITIPSTER_PRO_PLUGIN_URL; ?>assets/images/pwa/splash-828x1792.png">
        <link rel="apple-touch-startup-image" media="screen and (device-width: 375px) and (device-height: 812px) and (-webkit-device-pixel-ratio: 3)" href="<?php echo ITIPSTER_PRO_PLUGIN_URL; ?>assets/images/pwa/splash-1125x2436.png">
        <link rel="apple-touch-startup-image" media="screen and (device-width: 414px) and (device-height: 896px) and (-webkit-device-pixel-ratio: 3)" href="<?php echo ITIPSTER_PRO_PLUGIN_URL; ?>assets/images/pwa/splash-1242x2688.png">
        <link rel="apple-touch-startup-image" media="screen and (device-width: 375px) and (device-height: 667px) and (-webkit-device-pixel-ratio: 2)" href="<?php echo ITIPSTER_PRO_PLUGIN_URL; ?>assets/images/pwa/splash-750x1334.png">
        <link rel="apple-touch-startup-image" media="screen and (device-width: 414px) and (device-height: 736px) and (-webkit-device-pixel-ratio: 3)" href="<?php echo ITIPSTER_PRO_PLUGIN_URL; ?>assets/images/pwa/splash-1242x2208.png">
        <link rel="apple-touch-startup-image" media="screen and (device-width: 320px) and (device-height: 568px) and (-webkit-device-pixel-ratio: 2)" href="<?php echo ITIPSTER_PRO_PLUGIN_URL; ?>assets/images/pwa/splash-640x1136.png">
        
        <!-- Android Icons -->
        <link rel="icon" type="image/png" sizes="32x32" href="<?php echo ITIPSTER_PRO_PLUGIN_URL; ?>assets/images/pwa/icon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="<?php echo ITIPSTER_PRO_PLUGIN_URL; ?>assets/images/pwa/icon-16x16.png">
        
        <!-- Microsoft Tiles -->
        <meta name="msapplication-TileColor" content="#007AFF">
        <meta name="msapplication-TileImage" content="<?php echo ITIPSTER_PRO_PLUGIN_URL; ?>assets/images/pwa/icon-144x144.png">
        <meta name="msapplication-config" content="<?php echo ITIPSTER_PRO_PLUGIN_URL; ?>browserconfig.xml">
        
        <!-- Open Graph -->
        <meta property="og:type" content="website">
        <meta property="og:title" content="iTipster Pro - Premium Sports Predictions">
        <meta property="og:description" content="AI-powered sports predictions with 84.7% success rate. Get live odds, real-time updates, and professional analytics.">
        <meta property="og:image" content="<?php echo ITIPSTER_PRO_PLUGIN_URL; ?>assets/images/pwa/icon-512x512.png">
        <meta property="og:url" content="<?php echo home_url(); ?>">
        
        <!-- Twitter Card -->
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="iTipster Pro - Premium Sports Predictions">
        <meta name="twitter:description" content="AI-powered sports predictions with 84.7% success rate. Get live odds, real-time updates, and professional analytics.">
        <meta name="twitter:image" content="<?php echo ITIPSTER_PRO_PLUGIN_URL; ?>assets/images/pwa/icon-512x512.png">
        
        <!-- Preconnect to external domains -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link rel="preconnect" href="https://cdnjs.cloudflare.com">
        
        <!-- DNS Prefetch -->
        <link rel="dns-prefetch" href="//fonts.googleapis.com">
        <link rel="dns-prefetch" href="//cdnjs.cloudflare.com">
        <?php
    }
    
    /**
     * Enqueue admin assets
     */
    public function enqueue_admin_assets($hook) {
        if (strpos($hook, 'itipster') !== false) {
            // Core Design System CSS
            wp_enqueue_style(
                'itipster-design-system',
                ITIPSTER_PRO_PLUGIN_URL . 'assets/css/design-system.css',
                array(),
                ITIPSTER_PRO_VERSION
            );
            
            // Theme Manager CSS
            wp_enqueue_style(
                'itipster-theme-manager',
                ITIPSTER_PRO_PLUGIN_URL . 'assets/css/theme-manager.css',
                array('itipster-design-system'),
                ITIPSTER_PRO_VERSION
            );
            
            // UI Components CSS
            wp_enqueue_style(
                'itipster-components',
                ITIPSTER_PRO_PLUGIN_URL . 'assets/css/components.css',
                array('itipster-design-system'),
                ITIPSTER_PRO_VERSION
            );
            
            // Modern Admin CSS
            wp_enqueue_style(
                'itipster-admin-modern',
                ITIPSTER_PRO_PLUGIN_URL . 'assets/css/admin-modern.css',
                array('itipster-components'),
                ITIPSTER_PRO_VERSION
            );
            
            // Legacy Admin CSS (fallback)
            wp_enqueue_style(
                'itipster-admin-css',
                ITIPSTER_PRO_PLUGIN_URL . 'assets/css/admin-dashboard.css',
                array('itipster-admin-modern'),
                ITIPSTER_PRO_VERSION
            );
            
            // Theme Manager JavaScript
            wp_enqueue_script(
                'itipster-theme-manager',
                ITIPSTER_PRO_PLUGIN_URL . 'assets/js/theme-manager.js',
                array(),
                ITIPSTER_PRO_VERSION,
                true
            );
            
            // UI Interactions JavaScript
            wp_enqueue_script(
                'itipster-ui-interactions',
                ITIPSTER_PRO_PLUGIN_URL . 'assets/js/ui-interactions.js',
                array('itipster-theme-manager'),
                ITIPSTER_PRO_VERSION,
                true
            );
            
            // Admin JavaScript
            wp_enqueue_script(
                'itipster-admin-js',
                ITIPSTER_PRO_PLUGIN_URL . 'assets/js/admin-analytics.js',
                array('jquery', 'wp-charts', 'itipster-ui-interactions'),
                ITIPSTER_PRO_VERSION,
                true
            );
        }
    }
    
    /**
     * AJAX handler for predictions
     */
    public function ajax_get_predictions() {
        check_ajax_referer('itipster_nonce', 'nonce');
        
        $league = sanitize_text_field($_POST['league'] ?? '');
        $sport = sanitize_text_field($_POST['sport'] ?? '');
        
        $predictions = $this->predictions->get_predictions($league, $sport);
        
        wp_send_json_success($predictions);
    }
    
    /**
     * AJAX handler for live odds
     */
    public function ajax_get_live_odds() {
        check_ajax_referer('itipster_nonce', 'nonce');
        
        $fixture_id = intval($_POST['fixture_id'] ?? 0);
        
        $odds = $this->api_manager->get_live_odds($fixture_id);
        
        wp_send_json_success($odds);
    }
    
    /**
     * Plugin activation
     */
    public static function activate() {
        // Create database tables
        self::create_tables();
        
        // Insert demo data
        self::insert_demo_data();
        
        // Set default options
        self::set_default_options();
        
        // CREATE FRONTEND PAGES AUTOMATICALLY
        self::create_frontend_pages();
        
        // Create user management tables
        \ADPM\iTipsterPro\UserManagement_Installer::install();
        
        // Flush rewrite rules
        flush_rewrite_rules();
    }
    
    /**
     * Create database tables
     */
    private static function create_tables() {
        global $wpdb;
        
        $charset_collate = $wpdb->get_charset_collate();
        
        // Predictions table
        $table_predictions = $wpdb->prefix . 'itipster_predictions';
        $sql_predictions = "CREATE TABLE $table_predictions (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            fixture_id mediumint(9) NOT NULL,
            prediction_type varchar(50) NOT NULL,
            prediction_value varchar(100) NOT NULL,
            confidence_score decimal(5,2) NOT NULL,
            odds decimal(6,2) NOT NULL,
            value_rating decimal(3,2) NOT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY fixture_id (fixture_id),
            KEY prediction_type (prediction_type)
        ) $charset_collate;";
        
        // User subscriptions table
        $table_subscriptions = $wpdb->prefix . 'itipster_subscriptions';
        $sql_subscriptions = "CREATE TABLE $table_subscriptions (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            user_id bigint(20) NOT NULL,
            subscription_type varchar(20) NOT NULL,
            start_date datetime NOT NULL,
            end_date datetime NOT NULL,
            status varchar(20) DEFAULT 'active',
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY user_id (user_id),
            KEY subscription_type (subscription_type)
        ) $charset_collate;";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql_predictions);
        dbDelta($sql_subscriptions);
    }
    
    /**
     * Insert demo data
     */
    private static function insert_demo_data() {
        // This will be handled by the DemoData class
        $demo_data = new APIs\DemoData();
        $demo_data->insert_demo_data();
    }
    
    /**
     * Set default options
     */
    private static function set_default_options() {
        $default_options = array(
            'api_token' => 'your-oddalerts-api-token-here',
            'demo_mode' => true,
            'rate_limit' => 100,
            'cache_duration' => 900, // 15 minutes
            'premium_features' => array(
                'live_odds' => true,
                'advanced_analytics' => true,
                'ai_predictions' => true,
                'value_bets' => true,
            ),
            'ui_settings' => array(
                'glassmorphism' => true,
                'dark_mode' => false,
                'animations' => true,
            )
        );
        
        update_option('itipster_pro_settings', $default_options);
    }
    
    /**
     * Plugin uninstall handler - FIXED: Static method for uninstall hook
     */
    public static function uninstall() {
        global $wpdb;
        
        // Remove custom tables
        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}itipster_predictions");
        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}itipster_subscriptions");
        
        // Remove user management tables
        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}itipster_user_profiles");
        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}itipster_user_activity");
        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}itipster_user_favorites");
        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}itipster_credits");
        
        // Remove options
        delete_option('itipster_pro_version');
        delete_option('itipster_pro_settings');
        delete_option('itipster_api_settings');
        
        // Clear any cached data
        wp_cache_flush();
    }
    
    /**
     * Add frontend rewrite rules
     */
    public function add_frontend_rewrite_rules() {
        // Main predictions page
        add_rewrite_rule(
            '^predictions/?$',
            'index.php?itipster_page=predictions',
            'top'
        );
        
        // Single prediction page
        add_rewrite_rule(
            '^predictions/([^/]+)/?$',
            'index.php?itipster_page=prediction&prediction_slug=$matches[1]',
            'top'
        );
        
        // Fixtures page
        add_rewrite_rule(
            '^fixtures/?$',
            'index.php?itipster_page=fixtures',
            'top'
        );
        
        // Single fixture page
        add_rewrite_rule(
            '^fixtures/([^/]+)/?$',
            'index.php?itipster_page=fixture&fixture_slug=$matches[1]',
            'top'
        );
        
        // Dashboard
        add_rewrite_rule(
            '^dashboard/?$',
            'index.php?itipster_page=dashboard',
            'top'
        );
        
        // Add query vars
        add_filter('query_vars', function($vars) {
            $vars[] = 'itipster_page';
            $vars[] = 'prediction_slug';
            $vars[] = 'fixture_slug';
            return $vars;
        });
    }
    
    /**
     * Frontend template redirect
     */
    public function frontend_template_redirect() {
        $page = get_query_var('itipster_page');
        
        if ($page) {
            switch ($page) {
                case 'predictions':
                    $this->load_predictions_page();
                    break;
                case 'prediction':
                    $this->load_single_prediction_page();
                    break;
                case 'fixtures':
                    $this->load_fixtures_page();
                    break;
                case 'fixture':
                    $this->load_single_fixture_page();
                    break;
                case 'dashboard':
                    $this->load_dashboard_page();
                    break;
            }
        }
    }
    
    /**
     * Load predictions page
     */
    private function load_predictions_page() {
        wp_enqueue_style('itipster-glassmorphism-css');
        wp_enqueue_script('itipster-frontend-js');
        
        // Get demo predictions
        $demo_data = new APIs\DemoData();
        $predictions = $demo_data->get_ai_predictions();
        
        // Load template
        include ITIPSTER_PRO_PLUGIN_PATH . 'templates/frontend/predictions-page.php';
        exit;
    }
    
    /**
     * Load single fixture page
     */
    private function load_single_fixture_page() {
        $fixture_slug = get_query_var('fixture_slug');
        
        wp_enqueue_style('itipster-glassmorphism-css');
        wp_enqueue_script('itipster-frontend-js');
        
        // Get fixture data by slug
        $fixture = $this->get_fixture_by_slug($fixture_slug);
        
        if (!$fixture) {
            wp_redirect(home_url('/predictions/'));
            exit;
        }
        
        // Load template
        include ITIPSTER_PRO_PLUGIN_PATH . 'templates/frontend/single-fixture.php';
        exit;
    }
    
    /**
     * Get fixture by slug
     */
    private function get_fixture_by_slug($slug) {
        // Convert slug to fixture data
        $slug_parts = explode('-vs-', $slug);
        if (count($slug_parts) !== 2) {
            return false;
        }
        
        $home_team = str_replace('-', ' ', $slug_parts[0]);
        $away_team = str_replace('-', ' ', $slug_parts[1]);
        
        // Search in demo data
        $demo_fixtures = \ADPM\iTipsterPro\Data\DemoFixtures::get_fixtures();
        
        foreach ($demo_fixtures as $league_fixtures) {
            foreach ($league_fixtures as $fixture) {
                if (strtolower($fixture['home_team']) === strtolower($home_team) && 
                    strtolower($fixture['away_team']) === strtolower($away_team)) {
                    return $fixture;
                }
            }
        }
        
        return false;
    }
    
    /**
     * Load fixtures page
     */
    private function load_fixtures_page() {
        wp_enqueue_style('itipster-glassmorphism-css');
        wp_enqueue_script('itipster-frontend-js');
        
        // Get demo fixtures
        $fixtures = \ADPM\iTipsterPro\Data\DemoFixtures::get_fixtures();
        
        // Load template
        include ITIPSTER_PRO_PLUGIN_PATH . 'templates/frontend/fixtures-page.php';
        exit;
    }
    
    /**
     * Load dashboard page
     */
    private function load_dashboard_page() {
        wp_enqueue_style('itipster-glassmorphism-css');
        wp_enqueue_script('itipster-frontend-js');
        
        // Load template
        include ITIPSTER_PRO_PLUGIN_PATH . 'templates/frontend/dashboard-page.php';
        exit;
    }
    
    /**
     * Load single prediction page
     */
    private function load_single_prediction_page() {
        $prediction_slug = get_query_var('prediction_slug');
        
        wp_enqueue_style('itipster-glassmorphism-css');
        wp_enqueue_script('itipster-frontend-js');
        
        // Get prediction data by slug
        $prediction = $this->get_prediction_by_slug($prediction_slug);
        
        if (!$prediction) {
            wp_redirect(home_url('/predictions/'));
            exit;
        }
        
        // Load template
        include ITIPSTER_PRO_PLUGIN_PATH . 'templates/frontend/single-prediction.php';
        exit;
    }
    
    /**
     * Get prediction by slug
     */
    private function get_prediction_by_slug($slug) {
        // This would typically query the database
        // For now, return demo data
        $predictions = \ADPM\iTipsterPro\Data\DemoPredictions::get_predictions();
        
        foreach ($predictions as $prediction) {
            if (sanitize_title($prediction['fixture_id']) === $slug) {
                return $prediction;
            }
        }
        
        return false;
    }
    
    /**
     * Manually flush rewrite rules
     */
    public static function flush_rewrite_rules() {
        flush_rewrite_rules();
    }
    
    /**
     * Get total fixtures count
     */
    private function get_total_fixtures_count() {
        $fixtures = \ADPM\iTipsterPro\Data\DemoFixtures::get_fixtures();
        $count = 0;
        
        foreach ($fixtures as $league_fixtures) {
            $count += count($league_fixtures);
        }
        
        return $count;
    }
    
    /**
     * Get fixtures by status
     */
    private function get_fixtures_by_status($status) {
        $fixtures = \ADPM\iTipsterPro\Data\DemoFixtures::get_fixtures();
        $filtered = array();
        
        foreach ($fixtures as $league_fixtures) {
            foreach ($league_fixtures as $fixture) {
                if ($fixture['status'] === $status) {
                    $filtered[] = $fixture;
                }
            }
        }
        
        return $filtered;
    }
    
    /**
     * Create frontend pages automatically
     */
    private static function create_frontend_pages() {
        // Check if pages already exist
        $pages_created = get_option('itipster_pages_created', false);
        if ($pages_created) {
            return; // Pages already created
        }
        
        // 1. CREATE PREDICTIONS PAGE
        $predictions_page = array(
            'post_title'    => 'Predictions',
            'post_content'  => '[itipster_predictions_dashboard]',
            'post_status'   => 'publish',
            'post_type'     => 'page',
            'post_name'     => 'predictions'
        );
        $predictions_id = wp_insert_post($predictions_page);
        
        // 2. CREATE FIXTURES PAGE
        $fixtures_page = array(
            'post_title'    => 'Fixtures',
            'post_content'  => '[itipster_fixtures_dashboard]',
            'post_status'   => 'publish',
            'post_type'     => 'page',
            'post_name'     => 'fixtures'
        );
        $fixtures_id = wp_insert_post($fixtures_page);
        
        // 3. CREATE DASHBOARD PAGE
        $dashboard_page = array(
            'post_title'    => 'Dashboard',
            'post_content'  => '[itipster_user_dashboard]',
            'post_status'   => 'publish',
            'post_type'     => 'page',
            'post_name'     => 'dashboard'
        );
        $dashboard_id = wp_insert_post($dashboard_page);
        
        // 4. CREATE SINGLE FIXTURE PAGE TEMPLATE
        $single_fixture_page = array(
            'post_title'    => 'Single Fixture',
            'post_content'  => '[itipster_single_fixture]',
            'post_status'   => 'publish',
            'post_type'     => 'page',
            'post_name'     => 'single-fixture'
        );
        $single_fixture_id = wp_insert_post($single_fixture_page);
        
        // 5. CREATE HOMEPAGE OVERRIDE (OPTIONAL)
        $homepage = array(
            'post_title'    => 'iTipster Pro - Premium Sports Predictions',
            'post_content'  => '[itipster_homepage_hero][itipster_predictions_dashboard limit="6"]',
            'post_status'   => 'publish',
            'post_type'     => 'page',
            'post_name'     => 'home'
        );
        $homepage_id = wp_insert_post($homepage);
        
        // Save page IDs
        update_option('itipster_page_predictions', $predictions_id);
        update_option('itipster_page_fixtures', $fixtures_id);
        update_option('itipster_page_dashboard', $dashboard_id);
        update_option('itipster_page_single_fixture', $single_fixture_id);
        update_option('itipster_page_homepage', $homepage_id);
        
        // Mark pages as created
        update_option('itipster_pages_created', true);
        
        // Set predictions as homepage (optional)
        // update_option('page_on_front', $homepage_id);
        // update_option('show_on_front', 'page');
    }
} 