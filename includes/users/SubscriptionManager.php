<?php
namespace ADPM\iTipsterPro\Users;

/**
 * Subscription Management Class
 * 
 * Handles subscription status, upgrades, credit management,
 * trial periods, auto-renewal, and permission calculations.
 * 
 * @package ADPM\iTipsterPro\Users
 * @since 1.0.0
 */
class SubscriptionManager {
    
    /**
     * Subscription plans configuration
     */
    private const SUBSCRIPTION_PLANS = array(
        'free' => array(
            'name' => 'Free Plan',
            'price' => 0,
            'credits_per_month' => 10,
            'max_predictions_per_day' => 3,
            'features' => array('basic_predictions', 'basic_analytics')
        ),
        'basic' => array(
            'name' => 'Basic Plan',
            'price' => 9.99,
            'credits_per_month' => 50,
            'max_predictions_per_day' => 10,
            'features' => array('basic_predictions', 'basic_analytics', 'email_notifications')
        ),
        'premium' => array(
            'name' => 'Premium Plan',
            'price' => 19.99,
            'credits_per_month' => 150,
            'max_predictions_per_day' => 25,
            'features' => array('all_predictions', 'advanced_analytics', 'priority_support', 'api_access')
        ),
        'pro' => array(
            'name' => 'Pro Plan',
            'price' => 39.99,
            'credits_per_month' => 500,
            'max_predictions_per_day' => 100,
            'features' => array('all_predictions', 'advanced_analytics', 'priority_support', 'api_access', 'custom_reports')
        )
    );
    
    /**
     * Constructor
     */
    public function __construct() {
        // Hook into WordPress actions
        add_action('wp_ajax_itipster_check_subscription', array($this, 'ajax_check_subscription_status'));
        add_action('wp_ajax_itipster_upgrade_subscription', array($this, 'ajax_upgrade_subscription'));
        add_action('wp_ajax_itipster_manage_credits', array($this, 'ajax_manage_credits'));
        add_action('wp_ajax_itipster_get_permissions', array($this, 'ajax_get_permissions'));
        
        // Schedule auto-renewal checks
        add_action('itipster_daily_subscription_check', array($this, 'process_auto_renewal'));
        
        // Hook into user registration for free subscription
        add_action('itipster_user_registered', array($this, 'create_free_subscription'));
    }
    
    /**
     * Check comprehensive subscription status
     * 
     * @param int $user_id User ID
     * @return array Subscription status
     * 
     * @throws \Exception On subscription check failures
     */
    public function check_subscription_status(int $user_id): array {
        try {
            global $wpdb;
            
            // Get current subscription
            $subscription = $wpdb->get_row($wpdb->prepare(
                "SELECT * FROM {$wpdb->prefix}itipster_subscriptions WHERE user_id = %d ORDER BY created_at DESC LIMIT 1",
                $user_id
            ), ARRAY_A);
            
            if (!$subscription) {
                // Create free subscription if none exists
                $subscription = $this->create_free_subscription($user_id);
            }
            
            // Check if subscription is active
            $is_active = $this->is_subscription_active($subscription);
            
            // Get plan details
            $plan_details = $this->get_plan_details($subscription['plan_type']);
            
            // Calculate remaining credits
            $remaining_credits = $this->calculate_remaining_credits($user_id, $subscription);
            
            // Check trial status
            $trial_status = $this->check_trial_status($subscription);
            
            // Get next billing date
            $next_billing = $this->get_next_billing_date($subscription);
            
            // Calculate permissions
            $permissions = $this->calculate_permissions($subscription, $remaining_credits);
            
            return array(
                'success' => true,
                'subscription' => array(
                    'id' => $subscription['id'],
                    'plan_type' => $subscription['plan_type'],
                    'plan_name' => $plan_details['name'],
                    'status' => $subscription['status'],
                    'is_active' => $is_active,
                    'credits_remaining' => $remaining_credits,
                    'credits_total' => $plan_details['credits_per_month'],
                    'max_predictions_per_day' => $plan_details['max_predictions_per_day'],
                    'features' => $plan_details['features'],
                    'trial_status' => $trial_status,
                    'next_billing_date' => $next_billing,
                    'auto_renewal' => $subscription['auto_renewal'] ?? false,
                    'created_at' => $subscription['created_at'],
                    'expires_at' => $subscription['expires_at']
                ),
                'permissions' => $permissions
            );
            
        } catch (\Exception $e) {
            error_log('Subscription status check error: ' . $e->getMessage());
            
            return array(
                'success' => false,
                'error' => $e->getMessage()
            );
        }
    }
    
    /**
     * Upgrade user subscription
     * 
     * @param int $user_id User ID
     * @param string $new_plan New plan type
     * @param array $payment_data Payment information
     * @return array Upgrade result
     */
    public function upgrade_subscription(int $user_id, string $new_plan, array $payment_data = array()): array {
        try {
            // Validate plan
            if (!isset(self::SUBSCRIPTION_PLANS[$new_plan])) {
                throw new \Exception('Invalid subscription plan.');
            }
            
            // Get current subscription
            $current_subscription = $this->get_current_subscription($user_id);
            
            // Check if upgrade is allowed
            if (!$this->can_upgrade_subscription($user_id, $new_plan)) {
                throw new \Exception('Subscription upgrade not allowed.');
            }
            
            // Process payment if required
            if (self::SUBSCRIPTION_PLANS[$new_plan]['price'] > 0) {
                $payment_result = $this->process_payment($payment_data, $new_plan);
                if (!$payment_result['success']) {
                    throw new \Exception($payment_result['error']);
                }
            }
            
            // Create new subscription
            $new_subscription = $this->create_subscription($user_id, $new_plan, $payment_data);
            
            // Handle plan transition (credits, features, etc.)
            $this->handle_plan_transition($user_id, $current_subscription, $new_subscription);
            
            // Log upgrade activity
            $this->log_subscription_activity($user_id, 'subscription_upgraded', array(
                'from_plan' => $current_subscription['plan_type'] ?? 'none',
                'to_plan' => $new_plan,
                'payment_amount' => self::SUBSCRIPTION_PLANS[$new_plan]['price']
            ));
            
            return array(
                'success' => true,
                'subscription' => $new_subscription,
                'message' => 'Subscription upgraded successfully.'
            );
            
        } catch (\Exception $e) {
            error_log('Subscription upgrade error: ' . $e->getMessage());
            
            return array(
                'success' => false,
                'error' => $e->getMessage()
            );
        }
    }
    
    /**
     * Manage user credits (add, deduct, check balance)
     * 
     * @param int $user_id User ID
     * @param string $action Action (add, deduct, check)
     * @param int $amount Credit amount
     * @param string $reason Reason for credit change
     * @return array Credit management result
     */
    public function manage_credits(int $user_id, string $action, int $amount = 0, string $reason = ''): array {
        try {
            global $wpdb;
            
            switch ($action) {
                case 'add':
                    $result = $this->add_credits($user_id, $amount, $reason);
                    break;
                    
                case 'deduct':
                    $result = $this->deduct_credits($user_id, $amount, $reason);
                    break;
                    
                case 'check':
                    $result = $this->get_credit_balance($user_id);
                    break;
                    
                case 'history':
                    $result = $this->get_credit_history($user_id);
                    break;
                    
                default:
                    throw new \Exception('Invalid credit action.');
            }
            
            return array(
                'success' => true,
                'action' => $action,
                'result' => $result
            );
            
        } catch (\Exception $e) {
            error_log('Credit management error: ' . $e->getMessage());
            
            return array(
                'success' => false,
                'error' => $e->getMessage()
            );
        }
    }
    
    /**
     * Handle trial period management
     * 
     * @param int $user_id User ID
     * @param string $trial_type Trial type
     * @return array Trial result
     */
    public function handle_trial(int $user_id, string $trial_type = 'standard'): array {
        try {
            // Check if user is eligible for trial
            if (!$this->is_eligible_for_trial($user_id, $trial_type)) {
                throw new \Exception('Not eligible for trial period.');
            }
            
            // Get trial configuration
            $trial_config = $this->get_trial_config($trial_type);
            
            // Create trial subscription
            $trial_subscription = $this->create_trial_subscription($user_id, $trial_config);
            
            // Log trial start
            $this->log_subscription_activity($user_id, 'trial_started', array(
                'trial_type' => $trial_type,
                'duration_days' => $trial_config['duration_days'],
                'plan_type' => $trial_config['plan_type']
            ));
            
            return array(
                'success' => true,
                'trial' => $trial_subscription,
                'message' => 'Trial period started successfully.'
            );
            
        } catch (\Exception $e) {
            error_log('Trial handling error: ' . $e->getMessage());
            
            return array(
                'success' => false,
                'error' => $e->getMessage()
            );
        }
    }
    
    /**
     * Process auto-renewal for active subscriptions
     * 
     * @return array Renewal results
     */
    public function process_auto_renewal(): array {
        try {
            global $wpdb;
            
            // Get subscriptions due for renewal
            $subscriptions_to_renew = $wpdb->get_results(
                "SELECT * FROM {$wpdb->prefix}itipster_subscriptions 
                 WHERE status = 'active' 
                 AND auto_renewal = 1 
                 AND expires_at <= DATE_ADD(NOW(), INTERVAL 1 DAY)",
                ARRAY_A
            );
            
            $renewal_results = array();
            
            foreach ($subscriptions_to_renew as $subscription) {
                $renewal_result = $this->renew_subscription($subscription);
                $renewal_results[] = $renewal_result;
            }
            
            return array(
                'success' => true,
                'renewals_processed' => count($subscriptions_to_renew),
                'results' => $renewal_results
            );
            
        } catch (\Exception $e) {
            error_log('Auto-renewal processing error: ' . $e->getMessage());
            
            return array(
                'success' => false,
                'error' => $e->getMessage()
            );
        }
    }
    
    /**
     * Calculate user permissions based on subscription
     * 
     * @param array $subscription Subscription data
     * @param int $remaining_credits Remaining credits
     * @return array Permissions
     */
    public function calculate_permissions(array $subscription, int $remaining_credits = null): array {
        try {
            $plan_details = $this->get_plan_details($subscription['plan_type']);
            
            // Base permissions from plan
            $permissions = array(
                'can_make_predictions' => $this->can_make_predictions($subscription, $remaining_credits),
                'can_view_advanced_analytics' => in_array('advanced_analytics', $plan_details['features']),
                'can_access_api' => in_array('api_access', $plan_details['features']),
                'can_export_data' => in_array('custom_reports', $plan_details['features']),
                'can_view_profiles' => $subscription['plan_type'] !== 'free',
                'max_predictions_per_day' => $plan_details['max_predictions_per_day'],
                'credits_remaining' => $remaining_credits ?? 0,
                'features_available' => $plan_details['features']
            );
            
            // Add subscription-specific permissions
            $permissions['subscription_active'] = $this->is_subscription_active($subscription);
            $permissions['in_trial'] = $this->is_in_trial($subscription);
            
            return $permissions;
            
        } catch (\Exception $e) {
            error_log('Permission calculation error: ' . $e->getMessage());
            return array();
        }
    }
    
    /**
     * AJAX handler for checking subscription status
     */
    public function ajax_check_subscription_status(): void {
        check_ajax_referer('itipster_subscription_nonce', 'nonce');
        
        $user_id = intval($_POST['user_id'] ?? get_current_user_id());
        
        $result = $this->check_subscription_status($user_id);
        
        if ($result['success']) {
            wp_send_json_success($result);
        } else {
            wp_send_json_error($result);
        }
    }
    
    /**
     * AJAX handler for upgrading subscription
     */
    public function ajax_upgrade_subscription(): void {
        check_ajax_referer('itipster_subscription_nonce', 'nonce');
        
        $user_id = get_current_user_id();
        $new_plan = sanitize_text_field($_POST['plan'] ?? '');
        $payment_data = $_POST['payment_data'] ?? array();
        
        $result = $this->upgrade_subscription($user_id, $new_plan, $payment_data);
        
        if ($result['success']) {
            wp_send_json_success($result);
        } else {
            wp_send_json_error($result);
        }
    }
    
    /**
     * AJAX handler for managing credits
     */
    public function ajax_manage_credits(): void {
        check_ajax_referer('itipster_subscription_nonce', 'nonce');
        
        $user_id = get_current_user_id();
        $action = sanitize_text_field($_POST['action'] ?? '');
        $amount = intval($_POST['amount'] ?? 0);
        $reason = sanitize_text_field($_POST['reason'] ?? '');
        
        $result = $this->manage_credits($user_id, $action, $amount, $reason);
        
        if ($result['success']) {
            wp_send_json_success($result);
        } else {
            wp_send_json_error($result);
        }
    }
    
    /**
     * AJAX handler for getting permissions
     */
    public function ajax_get_permissions(): void {
        check_ajax_referer('itipster_subscription_nonce', 'nonce');
        
        $user_id = intval($_POST['user_id'] ?? get_current_user_id());
        
        $subscription = $this->get_current_subscription($user_id);
        $remaining_credits = $this->calculate_remaining_credits($user_id, $subscription);
        $permissions = $this->calculate_permissions($subscription, $remaining_credits);
        
        wp_send_json_success(array('permissions' => $permissions));
    }
    
    // ============================================================================
    // PRIVATE HELPER METHODS
    // ============================================================================
    
    /**
     * Create free subscription for new user
     * 
     * @param int $user_id User ID
     * @return array Subscription data
     */
    public function create_free_subscription(int $user_id): array {
        return $this->create_subscription($user_id, 'free');
    }
    
    /**
     * Get current subscription for user
     * 
     * @param int $user_id User ID
     * @return array|null Subscription data
     */
    public function get_current_subscription(int $user_id): ?array {
        global $wpdb;
        
        return $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}itipster_subscriptions WHERE user_id = %d ORDER BY created_at DESC LIMIT 1",
            $user_id
        ), ARRAY_A);
    }
    
    /**
     * Get user subscription statistics
     * 
     * @param int $user_id User ID
     * @return array Subscription stats
     */
    public function get_user_subscription_stats(int $user_id): array {
        global $wpdb;
        
        $stats = $wpdb->get_row($wpdb->prepare(
            "SELECT 
                COUNT(*) as total_subscriptions,
                MAX(created_at) as last_subscription_date,
                SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active_subscriptions
            FROM {$wpdb->prefix}itipster_subscriptions 
            WHERE user_id = %d",
            $user_id
        ), ARRAY_A);
        
        return $stats ?? array();
    }
    
    /**
     * Check if user can view other profiles
     * 
     * @param int $user_id User ID
     * @return bool Permission status
     */
    public function can_view_profiles(int $user_id): bool {
        $subscription = $this->get_current_subscription($user_id);
        return $subscription && $subscription['plan_type'] !== 'free';
    }
    
    /**
     * Check if subscription is active
     * 
     * @param array $subscription Subscription data
     * @return bool Active status
     */
    private function is_subscription_active(array $subscription): bool {
        if ($subscription['status'] !== 'active') {
            return false;
        }
        
        // Check if subscription has expired
        if (!empty($subscription['expires_at'])) {
            $expires = strtotime($subscription['expires_at']);
            if ($expires && $expires < time()) {
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * Get plan details
     * 
     * @param string $plan_type Plan type
     * @return array Plan details
     */
    private function get_plan_details(string $plan_type): array {
        return self::SUBSCRIPTION_PLANS[$plan_type] ?? self::SUBSCRIPTION_PLANS['free'];
    }
    
    /**
     * Calculate remaining credits
     * 
     * @param int $user_id User ID
     * @param array $subscription Subscription data
     * @return int Remaining credits
     */
    private function calculate_remaining_credits(int $user_id, array $subscription): int {
        global $wpdb;
        
        // Get total credits allocated
        $allocated = $wpdb->get_var($wpdb->prepare(
            "SELECT SUM(amount) FROM {$wpdb->prefix}itipster_credits 
             WHERE user_id = %d AND type = 'allocation'",
            $user_id
        ));
        
        // Get total credits used
        $used = $wpdb->get_var($wpdb->prepare(
            "SELECT SUM(amount) FROM {$wpdb->prefix}itipster_credits 
             WHERE user_id = %d AND type = 'usage'",
            $user_id
        ));
        
        $allocated = intval($allocated ?? 0);
        $used = intval($used ?? 0);
        
        return max(0, $allocated - $used);
    }
    
    /**
     * Check trial status
     * 
     * @param array $subscription Subscription data
     * @return array Trial status
     */
    private function check_trial_status(array $subscription): array {
        if (empty($subscription['trial_ends_at'])) {
            return array('in_trial' => false);
        }
        
        $trial_ends = strtotime($subscription['trial_ends_at']);
        $now = time();
        
        return array(
            'in_trial' => $trial_ends > $now,
            'trial_ends_at' => $subscription['trial_ends_at'],
            'days_remaining' => max(0, ceil(($trial_ends - $now) / DAY_IN_SECONDS))
        );
    }
    
    /**
     * Get next billing date
     * 
     * @param array $subscription Subscription data
     * @return string|null Next billing date
     */
    private function get_next_billing_date(array $subscription): ?string {
        if (empty($subscription['expires_at'])) {
            return null;
        }
        
        return $subscription['expires_at'];
    }
    
    /**
     * Check if user can upgrade subscription
     * 
     * @param int $user_id User ID
     * @param string $new_plan New plan type
     * @return bool Upgrade permission
     */
    private function can_upgrade_subscription(int $user_id, string $new_plan): bool {
        $current_subscription = $this->get_current_subscription($user_id);
        
        // Free users can upgrade to any plan
        if (!$current_subscription || $current_subscription['plan_type'] === 'free') {
            return true;
        }
        
        // Check if new plan is higher tier
        $plan_hierarchy = array('free', 'basic', 'premium', 'pro');
        $current_index = array_search($current_subscription['plan_type'], $plan_hierarchy);
        $new_index = array_search($new_plan, $plan_hierarchy);
        
        return $new_index > $current_index;
    }
    
    /**
     * Process payment for subscription
     * 
     * @param array $payment_data Payment data
     * @param string $plan Plan type
     * @return array Payment result
     */
    private function process_payment(array $payment_data, string $plan): array {
        // This would integrate with payment gateways (Stripe, PayPal, etc.)
        // Placeholder for payment processing
        return array('success' => true);
    }
    
    /**
     * Create new subscription
     * 
     * @param int $user_id User ID
     * @param string $plan_type Plan type
     * @param array $payment_data Payment data
     * @return array Subscription data
     */
    private function create_subscription(int $user_id, string $plan_type, array $payment_data = array()): array {
        global $wpdb;
        
        $plan_details = $this->get_plan_details($plan_type);
        $expires_at = $plan_type === 'free' ? null : date('Y-m-d H:i:s', strtotime('+1 month'));
        
        $subscription_data = array(
            'user_id' => $user_id,
            'plan_type' => $plan_type,
            'status' => 'active',
            'amount' => $plan_details['price'],
            'currency' => 'EUR',
            'expires_at' => $expires_at,
            'auto_renewal' => $plan_type !== 'free',
            'payment_method' => $payment_data['payment_method'] ?? null,
            'created_at' => current_time('mysql')
        );
        
        $result = $wpdb->insert(
            $wpdb->prefix . 'itipster_subscriptions',
            $subscription_data,
            array('%d', '%s', '%s', '%f', '%s', '%s', '%d', '%s', '%s')
        );
        
        if ($result === false) {
            throw new \Exception('Failed to create subscription.');
        }
        
        $subscription_data['id'] = $wpdb->insert_id;
        
        // Allocate credits for new subscription
        $this->allocate_credits($user_id, $plan_details['credits_per_month'], 'subscription_creation');
        
        return $subscription_data;
    }
    
    /**
     * Handle plan transition
     * 
     * @param int $user_id User ID
     * @param array $old_subscription Old subscription
     * @param array $new_subscription New subscription
     */
    private function handle_plan_transition(int $user_id, array $old_subscription, array $new_subscription): void {
        // Deactivate old subscription
        if ($old_subscription) {
            $this->deactivate_subscription($old_subscription['id']);
        }
        
        // Handle credit transition
        $this->handle_credit_transition($user_id, $old_subscription, $new_subscription);
    }
    
    /**
     * Add credits to user account
     * 
     * @param int $user_id User ID
     * @param int $amount Credit amount
     * @param string $reason Reason for adding credits
     * @return bool Success status
     */
    private function add_credits(int $user_id, int $amount, string $reason): bool {
        global $wpdb;
        
        $result = $wpdb->insert(
            $wpdb->prefix . 'itipster_credits',
            array(
                'user_id' => $user_id,
                'type' => 'allocation',
                'amount' => $amount,
                'reason' => $reason,
                'created_at' => current_time('mysql')
            ),
            array('%d', '%s', '%d', '%s', '%s')
        );
        
        return $result !== false;
    }
    
    /**
     * Deduct credits from user account
     * 
     * @param int $user_id User ID
     * @param int $amount Credit amount
     * @param string $reason Reason for deducting credits
     * @return bool Success status
     */
    private function deduct_credits(int $user_id, int $amount, string $reason): bool {
        $remaining_credits = $this->get_credit_balance($user_id);
        
        if ($remaining_credits < $amount) {
            throw new \Exception('Insufficient credits.');
        }
        
        global $wpdb;
        
        $result = $wpdb->insert(
            $wpdb->prefix . 'itipster_credits',
            array(
                'user_id' => $user_id,
                'type' => 'usage',
                'amount' => $amount,
                'reason' => $reason,
                'created_at' => current_time('mysql')
            ),
            array('%d', '%s', '%d', '%s', '%s')
        );
        
        return $result !== false;
    }
    
    /**
     * Get credit balance
     * 
     * @param int $user_id User ID
     * @return int Credit balance
     */
    private function get_credit_balance(int $user_id): int {
        return $this->calculate_remaining_credits($user_id, $this->get_current_subscription($user_id));
    }
    
    /**
     * Get credit history
     * 
     * @param int $user_id User ID
     * @return array Credit history
     */
    private function get_credit_history(int $user_id): array {
        global $wpdb;
        
        $history = $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}itipster_credits 
             WHERE user_id = %d 
             ORDER BY created_at DESC 
             LIMIT 50",
            $user_id
        ), ARRAY_A);
        
        return $history ?? array();
    }
    
    /**
     * Check if user can make predictions
     * 
     * @param array $subscription Subscription data
     * @param int $remaining_credits Remaining credits
     * @return bool Prediction permission
     */
    private function can_make_predictions(array $subscription, int $remaining_credits = null): bool {
        if (!$this->is_subscription_active($subscription)) {
            return false;
        }
        
        if ($remaining_credits !== null && $remaining_credits <= 0) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Check if subscription is in trial
     * 
     * @param array $subscription Subscription data
     * @return bool Trial status
     */
    private function is_in_trial(array $subscription): bool {
        $trial_status = $this->check_trial_status($subscription);
        return $trial_status['in_trial'];
    }
    
    /**
     * Check if user is eligible for trial
     * 
     * @param int $user_id User ID
     * @param string $trial_type Trial type
     * @return bool Eligibility status
     */
    private function is_eligible_for_trial(int $user_id, string $trial_type): bool {
        global $wpdb;
        
        // Check if user has had a trial before
        $previous_trial = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM {$wpdb->prefix}itipster_subscriptions 
             WHERE user_id = %d AND trial_ends_at IS NOT NULL",
            $user_id
        ));
        
        return $previous_trial == 0;
    }
    
    /**
     * Get trial configuration
     * 
     * @param string $trial_type Trial type
     * @return array Trial config
     */
    private function get_trial_config(string $trial_type): array {
        $configs = array(
            'standard' => array(
                'duration_days' => 7,
                'plan_type' => 'premium',
                'credits' => 50
            ),
            'extended' => array(
                'duration_days' => 14,
                'plan_type' => 'premium',
                'credits' => 100
            )
        );
        
        return $configs[$trial_type] ?? $configs['standard'];
    }
    
    /**
     * Create trial subscription
     * 
     * @param int $user_id User ID
     * @param array $trial_config Trial configuration
     * @return array Trial subscription
     */
    private function create_trial_subscription(int $user_id, array $trial_config): array {
        global $wpdb;
        
        $trial_ends = date('Y-m-d H:i:s', strtotime('+' . $trial_config['duration_days'] . ' days'));
        
        $subscription_data = array(
            'user_id' => $user_id,
            'plan_type' => $trial_config['plan_type'],
            'status' => 'active',
            'amount' => 0,
            'currency' => 'EUR',
            'trial_ends_at' => $trial_ends,
            'expires_at' => $trial_ends,
            'auto_renewal' => false,
            'created_at' => current_time('mysql')
        );
        
        $wpdb->insert(
            $wpdb->prefix . 'itipster_subscriptions',
            $subscription_data,
            array('%d', '%s', '%s', '%f', '%s', '%s', '%s', '%d', '%s')
        );
        
        $subscription_data['id'] = $wpdb->insert_id;
        
        // Allocate trial credits
        $this->allocate_credits($user_id, $trial_config['credits'], 'trial_allocation');
        
        return $subscription_data;
    }
    
    /**
     * Renew subscription
     * 
     * @param array $subscription Subscription data
     * @return array Renewal result
     */
    private function renew_subscription(array $subscription): array {
        try {
            // Process payment for renewal
            $payment_result = $this->process_renewal_payment($subscription);
            
            if (!$payment_result['success']) {
                // Mark subscription as expired
                $this->deactivate_subscription($subscription['id']);
                return array('success' => false, 'error' => 'Payment failed');
            }
            
            // Extend subscription
            $new_expires = date('Y-m-d H:i:s', strtotime('+1 month', strtotime($subscription['expires_at'])));
            
            global $wpdb;
            $wpdb->update(
                $wpdb->prefix . 'itipster_subscriptions',
                array('expires_at' => $new_expires),
                array('id' => $subscription['id']),
                array('%s'),
                array('%d')
            );
            
            // Allocate new month credits
            $plan_details = $this->get_plan_details($subscription['plan_type']);
            $this->allocate_credits($subscription['user_id'], $plan_details['credits_per_month'], 'monthly_renewal');
            
            return array('success' => true, 'expires_at' => $new_expires);
            
        } catch (\Exception $e) {
            error_log('Subscription renewal error: ' . $e->getMessage());
            return array('success' => false, 'error' => $e->getMessage());
        }
    }
    
    /**
     * Process renewal payment
     * 
     * @param array $subscription Subscription data
     * @return array Payment result
     */
    private function process_renewal_payment(array $subscription): array {
        // This would process the renewal payment
        // Placeholder for payment processing
        return array('success' => true);
    }
    
    /**
     * Deactivate subscription
     * 
     * @param int $subscription_id Subscription ID
     */
    private function deactivate_subscription(int $subscription_id): void {
        global $wpdb;
        
        $wpdb->update(
            $wpdb->prefix . 'itipster_subscriptions',
            array('status' => 'inactive'),
            array('id' => $subscription_id),
            array('%s'),
            array('%d')
        );
    }
    
    /**
     * Handle credit transition between plans
     * 
     * @param int $user_id User ID
     * @param array $old_subscription Old subscription
     * @param array $new_subscription New subscription
     */
    private function handle_credit_transition(int $user_id, array $old_subscription, array $new_subscription): void {
        // This would handle credit transitions between different plans
        // Placeholder for credit transition logic
    }
    
    /**
     * Allocate credits to user
     * 
     * @param int $user_id User ID
     * @param int $amount Credit amount
     * @param string $reason Reason for allocation
     */
    private function allocate_credits(int $user_id, int $amount, string $reason): void {
        $this->add_credits($user_id, $amount, $reason);
    }
    
    /**
     * Log subscription activity
     * 
     * @param int $user_id User ID
     * @param string $activity_type Activity type
     * @param array $activity_data Activity data
     */
    private function log_subscription_activity(int $user_id, string $activity_type, array $activity_data = array()): void {
        global $wpdb;
        
        $wpdb->insert(
            $wpdb->prefix . 'itipster_user_activity',
            array(
                'user_id' => $user_id,
                'activity_type' => $activity_type,
                'activity_data' => json_encode($activity_data),
                'ip_address' => $this->get_client_ip(),
                'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
                'created_at' => current_time('mysql')
            ),
            array('%d', '%s', '%s', '%s', '%s', '%s')
        );
    }
    
    /**
     * Get client IP address
     * 
     * @return string IP address
     */
    private function get_client_ip(): string {
        $ip_keys = array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR');
        
        foreach ($ip_keys as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip);
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                        return $ip;
                    }
                }
            }
        }
        
        return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }
} 