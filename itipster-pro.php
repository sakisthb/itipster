<?php
/**
 * Plugin Name: iTipster Pro - Premium Sports Predictions
 * Plugin URI: https://itipster.gr
 * Description: Professional AI-powered sports predictions platform with OddAlerts API integration and glassmorphism UI
 * Version: 1.0.0
 * Author: ADPM.gr - Ads Pro Digital Marketing
 * Author URI: https://adpm.gr
 * License: GPL v2 or later
 * Text Domain: itipster-pro
 * Requires at least: 6.0
 * Requires PHP: 8.0
 * Network: false
 * 
 * @package ADPM\iTipsterPro
 * @author ADPM.gr
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('ITIPSTER_PRO_VERSION', '1.0.0');
define('ITIPSTER_PRO_PLUGIN_URL', plugin_dir_url(__FILE__));
define('ITIPSTER_PRO_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('ITIPSTER_PRO_PLUGIN_BASENAME', plugin_basename(__FILE__));

// Autoloader for classes
spl_autoload_register('itipster_pro_autoloader');

function itipster_pro_autoloader($class) {
    $prefix = 'ADPM\\iTipsterPro\\';
    $base_dir = ITIPSTER_PRO_PLUGIN_PATH . 'includes/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    
    // Handle Data namespace specially
    if (strpos($relative_class, 'Data\\') === 0) {
        $file = $base_dir . 'data/' . str_replace('Data\\', '', $relative_class) . '.php';
    } else {
        $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    }

    if (file_exists($file)) {
        require $file;
    }
}

// Initialize the plugin
function itipster_pro_init() {
    // Load text domain for translations
    load_plugin_textdomain('itipster-pro', false, dirname(ITIPSTER_PRO_PLUGIN_BASENAME) . '/languages');
    
    // Initialize main plugin class
    new ADPM\iTipsterPro\Main();
}
add_action('plugins_loaded', 'itipster_pro_init');

// Activation hook
register_activation_hook(__FILE__, 'itipster_pro_activate');

function itipster_pro_activate() {
    // Create necessary database tables
    ADPM\iTipsterPro\Main::activate();
    
    // Flush rewrite rules
    flush_rewrite_rules();
}

// Deactivation hook
register_deactivation_hook(__FILE__, 'itipster_pro_deactivate');

function itipster_pro_deactivate() {
    // Cleanup if needed
    flush_rewrite_rules();
}

// Uninstall hook - FIXED: Using static method instead of closure
register_uninstall_hook(__FILE__, array('ADPM\\iTipsterPro\\Main', 'uninstall')); 