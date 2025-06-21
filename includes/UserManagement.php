<?php
namespace ADPM\iTipsterPro;

/**
 * User Management class for handling user subscriptions and permissions
 * 
 * @package ADPM\iTipsterPro
 */
class UserManagement {
    
    /**
     * Constructor
     */
    public function __construct() {
        add_action('wp_ajax_itipster_check_subscription', array($this, 'ajax_check_subscription'));
        add_action('wp_ajax_itipster_upgrade_subscription', array($this, 'ajax_upgrade_subscription'));
        add_action('wp_ajax_itipster_get_user_data', array($this, 'ajax_get_user_data'));
        add_action('wp_ajax_itipster_send_notification', array($this, 'ajax_send_notification'));
    }
    
    /**
     * Check user subscription
     */
    public function check_subscription($user_id = null) {
        if (!$user_id) {
            $user_id = get_current_user_id();
        }
        
        if (!$user_id) {
            return false;
        }
        
        global $wpdb;
        
        $subscription = $wpdb->get_row($wpdb->prepare("
            SELECT * FROM {$wpdb->prefix}itipster_subscriptions 
            WHERE user_id = %d AND status = 'active' AND end_date > NOW()
            ORDER BY end_date DESC 
            LIMIT 1
        ", $user_id));
        
        return $subscription;
    }
    
    /**
     * Get user subscription level
     */
    public function get_subscription_level($user_id = null) {
        $subscription = $this->check_subscription($user_id);
        
        if (!$subscription) {
            return 'free';
        }
        
        return $subscription->subscription_type;
    }
    
    /**
     * Check if user has premium access
     */
    public function has_premium_access($user_id = null) {
        $level = $this->get_subscription_level($user_id);
        return in_array($level, array('premium', 'pro', 'vip'));
    }
    
    /**
     * Create subscription
     */
    public function create_subscription($user_id, $type, $duration_days = 30) {
        global $wpdb;
        
        $start_date = current_time('mysql');
        $end_date = date('Y-m-d H:i:s', strtotime("+{$duration_days} days"));
        
        $result = $wpdb->insert(
            $wpdb->prefix . 'itipster_subscriptions',
            array(
                'user_id' => $user_id,
                'subscription_type' => $type,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'status' => 'active'
            ),
            array('%d', '%s', '%s', '%s', '%s')
        );
        
        return $result !== false;
    }
    
    /**
     * Update subscription
     */
    public function update_subscription($subscription_id, $data) {
        global $wpdb;
        
        return $wpdb->update(
            $wpdb->prefix . 'itipster_subscriptions',
            $data,
            array('id' => $subscription_id),
            array('%s', '%s', '%s'),
            array('%d')
        );
    }
    
    /**
     * Cancel subscription
     */
    public function cancel_subscription($subscription_id) {
        return $this->update_subscription($subscription_id, array('status' => 'cancelled'));
    }
    
    /**
     * Get user data
     */
    public function get_user_data($user_id = null) {
        if (!$user_id) {
            $user_id = get_current_user_id();
        }
        
        if (!$user_id) {
            return false;
        }
        
        $user = get_userdata($user_id);
        $subscription = $this->check_subscription($user_id);
        
        return array(
            'user_id' => $user_id,
            'username' => $user->user_login,
            'email' => $user->user_email,
            'subscription' => $subscription,
            'subscription_level' => $this->get_subscription_level($user_id),
            'has_premium' => $this->has_premium_access($user_id)
        );
    }
    
    /**
     * Send notification to user
     */
    public function send_notification($user_id, $message, $type = 'info') {
        // This could integrate with WordPress notifications or email
        $notification = array(
            'user_id' => $user_id,
            'message' => $message,
            'type' => $type,
            'created_at' => current_time('mysql')
        );
        
        // For now, we'll just store in user meta
        $notifications = get_user_meta($user_id, 'itipster_notifications', true);
        if (!is_array($notifications)) {
            $notifications = array();
        }
        
        $notifications[] = $notification;
        update_user_meta($user_id, 'itipster_notifications', $notifications);
        
        return true;
    }
    
    /**
     * AJAX check subscription
     */
    public function ajax_check_subscription() {
        check_ajax_referer('itipster_nonce', 'nonce');
        
        $user_id = get_current_user_id();
        $subscription = $this->check_subscription($user_id);
        
        wp_send_json_success(array(
            'has_subscription' => !empty($subscription),
            'subscription' => $subscription,
            'level' => $this->get_subscription_level($user_id)
        ));
    }
    
    /**
     * AJAX upgrade subscription
     */
    public function ajax_upgrade_subscription() {
        check_ajax_referer('itipster_nonce', 'nonce');
        
        $user_id = get_current_user_id();
        $subscription_type = sanitize_text_field($_POST['type']);
        $duration = intval($_POST['duration']);
        
        if (!$user_id) {
            wp_send_json_error('User not logged in');
        }
        
        // Cancel existing subscription
        $existing = $this->check_subscription($user_id);
        if ($existing) {
            $this->cancel_subscription($existing->id);
        }
        
        // Create new subscription
        $result = $this->create_subscription($user_id, $subscription_type, $duration);
        
        if ($result) {
            wp_send_json_success('Subscription upgraded successfully');
        } else {
            wp_send_json_error('Failed to upgrade subscription');
        }
    }
    
    /**
     * AJAX get user data
     */
    public function ajax_get_user_data() {
        check_ajax_referer('itipster_nonce', 'nonce');
        
        $user_id = intval($_POST['user_id'] ?? get_current_user_id());
        $user_data = $this->get_user_data($user_id);
        
        if ($user_data) {
            wp_send_json_success($user_data);
        } else {
            wp_send_json_error('User not found');
        }
    }
    
    /**
     * AJAX send notification
     */
    public function ajax_send_notification() {
        check_ajax_referer('itipster_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Insufficient permissions');
        }
        
        $user_id = intval($_POST['user_id']);
        $message = sanitize_textarea_field($_POST['message']);
        $type = sanitize_text_field($_POST['type']);
        
        $result = $this->send_notification($user_id, $message, $type);
        
        if ($result) {
            wp_send_json_success('Notification sent successfully');
        } else {
            wp_send_json_error('Failed to send notification');
        }
    }
    
    /**
     * Get subscription statistics
     */
    public function get_subscription_stats() {
        global $wpdb;
        
        $stats = array();
        
        // Total subscriptions
        $stats['total'] = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}itipster_subscriptions");
        
        // Active subscriptions
        $stats['active'] = $wpdb->get_var("
            SELECT COUNT(*) FROM {$wpdb->prefix}itipster_subscriptions 
            WHERE status = 'active' AND end_date > NOW()
        ");
        
        // Subscriptions by type
        $stats['by_type'] = $wpdb->get_results("
            SELECT subscription_type, COUNT(*) as count 
            FROM {$wpdb->prefix}itipster_subscriptions 
            WHERE status = 'active' AND end_date > NOW()
            GROUP BY subscription_type
        ");
        
        // Revenue (if tracking payments)
        $stats['revenue'] = $wpdb->get_var("
            SELECT SUM(amount) FROM {$wpdb->prefix}itipster_payments 
            WHERE status = 'completed'
        ");
        
        return $stats;
    }
} 