<?php
namespace ADPM\iTipsterPro\Users;

/**
 * User Registration Management Class
 * 
 * Handles user registration, email verification, referral system,
 * and initial profile creation with security measures.
 * 
 * @package ADPM\iTipsterPro\Users
 * @since 1.0.0
 */
class UserRegistration {
    
    /**
     * @var \ADPM\iTipsterPro\Users\UserProfile
     */
    private $user_profile;
    
    /**
     * @var \ADPM\iTipsterPro\Users\SubscriptionManager
     */
    private $subscription_manager;
    
    /**
     * Rate limiting settings
     */
    private const MAX_REGISTRATIONS_PER_HOUR = 5;
    private const MAX_REGISTRATIONS_PER_IP = 10;
    
    /**
     * Constructor with dependency injection
     * 
     * @param UserProfile $user_profile
     * @param SubscriptionManager $subscription_manager
     */
    public function __construct(UserProfile $user_profile = null, SubscriptionManager $subscription_manager = null) {
        $this->user_profile = $user_profile;
        $this->subscription_manager = $subscription_manager;
        
        // Hook into WordPress registration process
        add_action('wp_ajax_nopriv_itipster_register', array($this, 'ajax_register_user'));
        add_action('wp_ajax_itipster_verify_email', array($this, 'ajax_verify_email'));
        add_action('wp_ajax_itipster_resend_verification', array($this, 'ajax_resend_verification'));
    }
    
    /**
     * Register a new user with comprehensive validation and security
     * 
     * @param array $user_data User registration data
     * @return array|WP_Error Registration result or error
     * 
     * @throws \Exception On critical registration failures
     */
    public function register_user(array $user_data): array {
        try {
            // Security: Rate limiting check
            if (!$this->check_registration_rate_limit()) {
                throw new \Exception('Registration rate limit exceeded. Please try again later.');
            }
            
            // Validate input data
            $validation_result = $this->validate_registration_data($user_data);
            if (is_wp_error($validation_result)) {
                return array('success' => false, 'error' => $validation_result->get_error_message());
            }
            
            // Security: Check for spam/bot indicators
            if ($this->detect_suspicious_registration($user_data)) {
                throw new \Exception('Registration blocked due to suspicious activity.');
            }
            
            // Create WordPress user
            $user_id = wp_create_user(
                $user_data['email'],
                $user_data['password'],
                $user_data['email']
            );
            
            if (is_wp_error($user_id)) {
                throw new \Exception($user_id->get_error_message());
            }
            
            // Create user profile
            $profile_result = $this->create_user_profile($user_id, $user_data);
            if (!$profile_result) {
                // Rollback user creation if profile fails
                wp_delete_user($user_id);
                throw new \Exception('Failed to create user profile.');
            }
            
            // Handle referral system
            if (!empty($user_data['referral_code'])) {
                $this->handle_referral($user_id, $user_data['referral_code']);
            }
            
            // Create free subscription
            if ($this->subscription_manager) {
                $this->subscription_manager->create_free_subscription($user_id);
            }
            
            // Generate and send verification email
            $verification_sent = $this->send_verification_email($user_id, $user_data['email']);
            
            // Log registration activity
            $this->log_registration_activity($user_id, $user_data);
            
            return array(
                'success' => true,
                'user_id' => $user_id,
                'verification_sent' => $verification_sent,
                'message' => 'Registration successful. Please check your email for verification.'
            );
            
        } catch (\Exception $e) {
            // Log error for debugging
            error_log('UserRegistration Error: ' . $e->getMessage());
            
            return array(
                'success' => false,
                'error' => $e->getMessage()
            );
        }
    }
    
    /**
     * Validate registration data with comprehensive checks
     * 
     * @param array $data Registration data
     * @return true|WP_Error Validation result
     */
    public function validate_registration_data(array $data): bool|\WP_Error {
        // Required fields check
        $required_fields = array('email', 'password', 'password_confirm', 'terms_accepted');
        foreach ($required_fields as $field) {
            if (empty($data[$field])) {
                return new \WP_Error('missing_field', "Field '$field' is required.");
            }
        }
        
        // Email validation
        if (!is_email($data['email'])) {
            return new \WP_Error('invalid_email', 'Please enter a valid email address.');
        }
        
        // Check if email already exists
        if (email_exists($data['email'])) {
            return new \WP_Error('email_exists', 'This email address is already registered.');
        }
        
        // Password strength validation
        $password_strength = $this->validate_password_strength($data['password']);
        if (is_wp_error($password_strength)) {
            return $password_strength;
        }
        
        // Password confirmation
        if ($data['password'] !== $data['password_confirm']) {
            return new \WP_Error('password_mismatch', 'Passwords do not match.');
        }
        
        // Terms acceptance
        if (!$data['terms_accepted']) {
            return new \WP_Error('terms_not_accepted', 'You must accept the terms and conditions.');
        }
        
        // Referral code validation (if provided)
        if (!empty($data['referral_code'])) {
            $referral_valid = $this->validate_referral_code($data['referral_code']);
            if (is_wp_error($referral_valid)) {
                return $referral_valid;
            }
        }
        
        return true;
    }
    
    /**
     * Send verification email with secure token
     * 
     * @param int $user_id User ID
     * @param string $email User email
     * @return bool Success status
     */
    public function send_verification_email(int $user_id, string $email): bool {
        try {
            // Generate secure verification token
            $token = $this->generate_verification_token($user_id);
            
            // Store token with expiration
            update_user_meta($user_id, 'email_verification_token', $token);
            update_user_meta($user_id, 'email_verification_expires', time() + (24 * 60 * 60)); // 24 hours
            
            // Prepare email content
            $verification_url = add_query_arg(array(
                'action' => 'verify_email',
                'token' => $token,
                'user_id' => $user_id
            ), home_url('/wp-admin/admin-ajax.php'));
            
            $subject = 'Verify your iTipster Pro account';
            $message = $this->get_verification_email_template($verification_url);
            
            // Send email
            $headers = array('Content-Type: text/html; charset=UTF-8');
            $sent = wp_mail($email, $subject, $message, $headers);
            
            if (!$sent) {
                error_log("Failed to send verification email to user $user_id");
                return false;
            }
            
            return true;
            
        } catch (\Exception $e) {
            error_log('Email verification error: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Verify email token and activate account
     * 
     * @param string $token Verification token
     * @param int $user_id User ID
     * @return array Verification result
     */
    public function verify_email_token(string $token, int $user_id): array {
        try {
            // Get stored token and expiration
            $stored_token = get_user_meta($user_id, 'email_verification_token', true);
            $expires = get_user_meta($user_id, 'email_verification_expires', true);
            
            // Validate token
            if (!$stored_token || $token !== $stored_token) {
                return array('success' => false, 'error' => 'Invalid verification token.');
            }
            
            // Check expiration
            if (time() > $expires) {
                return array('success' => false, 'error' => 'Verification token has expired.');
            }
            
            // Activate user account
            wp_update_user(array(
                'ID' => $user_id,
                'user_status' => 0 // Activate account
            ));
            
            // Clear verification data
            delete_user_meta($user_id, 'email_verification_token');
            delete_user_meta($user_id, 'email_verification_expires');
            
            // Log verification activity
            $this->log_user_activity($user_id, 'email_verified', array('ip' => $this->get_client_ip()));
            
            return array('success' => true, 'message' => 'Email verified successfully!');
            
        } catch (\Exception $e) {
            error_log('Email verification error: ' . $e->getMessage());
            return array('success' => false, 'error' => 'Verification failed. Please try again.');
        }
    }
    
    /**
     * Handle referral system and rewards
     * 
     * @param int $user_id New user ID
     * @param string $referral_code Referral code
     * @return bool Success status
     */
    public function handle_referral(int $user_id, string $referral_code): bool {
        try {
            // Find referrer by code
            global $wpdb;
            $referrer_id = $wpdb->get_var($wpdb->prepare(
                "SELECT user_id FROM {$wpdb->prefix}itipster_subscriptions 
                 WHERE referral_code = %s AND status = 'active'",
                $referral_code
            ));
            
            if (!$referrer_id) {
                return false;
            }
            
            // Update new user's referral info
            $wpdb->update(
                $wpdb->prefix . 'itipster_subscriptions',
                array('referred_by' => $referrer_id),
                array('user_id' => $user_id),
                array('%d'),
                array('%d')
            );
            
            // Award referrer (implement reward logic)
            $this->award_referrer($referrer_id, $user_id);
            
            // Award new user (bonus for using referral)
            $this->award_referred_user($user_id);
            
            return true;
            
        } catch (\Exception $e) {
            error_log('Referral handling error: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Create initial user profile
     * 
     * @param int $user_id User ID
     * @param array $user_data Registration data
     * @return bool Success status
     */
    public function create_user_profile(int $user_id, array $user_data): bool {
        try {
            global $wpdb;
            
            $profile_data = array(
                'user_id' => $user_id,
                'betting_strategy' => 'moderate',
                'timezone' => wp_timezone_string(),
                'preferred_notifications' => json_encode(array(
                    'email' => true,
                    'push' => false,
                    'sms' => false
                )),
                'favorite_leagues' => json_encode(array()),
                'created_at' => current_time('mysql'),
                'updated_at' => current_time('mysql')
            );
            
            $result = $wpdb->insert(
                $wpdb->prefix . 'itipster_user_profiles',
                $profile_data,
                array('%d', '%s', '%s', '%s', '%s', '%s', '%s')
            );
            
            return $result !== false;
            
        } catch (\Exception $e) {
            error_log('Profile creation error: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * AJAX handler for user registration
     */
    public function ajax_register_user(): void {
        check_ajax_referer('itipster_registration_nonce', 'nonce');
        
        $user_data = array(
            'email' => sanitize_email($_POST['email'] ?? ''),
            'password' => $_POST['password'] ?? '',
            'password_confirm' => $_POST['password_confirm'] ?? '',
            'terms_accepted' => !empty($_POST['terms_accepted']),
            'newsletter_signup' => !empty($_POST['newsletter_signup']),
            'referral_code' => sanitize_text_field($_POST['referral_code'] ?? '')
        );
        
        $result = $this->register_user($user_data);
        
        if ($result['success']) {
            wp_send_json_success($result);
        } else {
            wp_send_json_error($result);
        }
    }
    
    /**
     * AJAX handler for email verification
     */
    public function ajax_verify_email(): void {
        check_ajax_referer('itipster_verification_nonce', 'nonce');
        
        $token = sanitize_text_field($_POST['token'] ?? '');
        $user_id = intval($_POST['user_id'] ?? 0);
        
        $result = $this->verify_email_token($token, $user_id);
        
        if ($result['success']) {
            wp_send_json_success($result);
        } else {
            wp_send_json_error($result);
        }
    }
    
    /**
     * AJAX handler for resending verification email
     */
    public function ajax_resend_verification(): void {
        check_ajax_referer('itipster_verification_nonce', 'nonce');
        
        $user_id = intval($_POST['user_id'] ?? 0);
        $user = get_userdata($user_id);
        
        if (!$user) {
            wp_send_json_error(array('error' => 'User not found.'));
        }
        
        $sent = $this->send_verification_email($user_id, $user->user_email);
        
        if ($sent) {
            wp_send_json_success(array('message' => 'Verification email sent.'));
        } else {
            wp_send_json_error(array('error' => 'Failed to send verification email.'));
        }
    }
    
    // ============================================================================
    // PRIVATE HELPER METHODS
    // ============================================================================
    
    /**
     * Check registration rate limits
     * 
     * @return bool Rate limit status
     */
    private function check_registration_rate_limit(): bool {
        $ip = $this->get_client_ip();
        $hour_key = 'registration_attempts_' . $ip . '_' . date('Y-m-d-H');
        
        $attempts = get_transient($hour_key) ?: 0;
        
        if ($attempts >= self::MAX_REGISTRATIONS_PER_HOUR) {
            return false;
        }
        
        set_transient($hour_key, $attempts + 1, HOUR_IN_SECONDS);
        return true;
    }
    
    /**
     * Detect suspicious registration patterns
     * 
     * @param array $user_data Registration data
     * @return bool Suspicious status
     */
    private function detect_suspicious_registration(array $user_data): bool {
        // Implement bot detection, spam patterns, etc.
        // This is a placeholder for future implementation
        
        return false;
    }
    
    /**
     * Validate password strength
     * 
     * @param string $password Password to validate
     * @return true|WP_Error Validation result
     */
    private function validate_password_strength(string $password): bool|\WP_Error {
        if (strlen($password) < 8) {
            return new \WP_Error('weak_password', 'Password must be at least 8 characters long.');
        }
        
        if (!preg_match('/[A-Z]/', $password)) {
            return new \WP_Error('weak_password', 'Password must contain at least one uppercase letter.');
        }
        
        if (!preg_match('/[a-z]/', $password)) {
            return new \WP_Error('weak_password', 'Password must contain at least one lowercase letter.');
        }
        
        if (!preg_match('/[0-9]/', $password)) {
            return new \WP_Error('weak_password', 'Password must contain at least one number.');
        }
        
        return true;
    }
    
    /**
     * Validate referral code
     * 
     * @param string $code Referral code
     * @return true|WP_Error Validation result
     */
    private function validate_referral_code(string $code): bool|\WP_Error {
        global $wpdb;
        
        $exists = $wpdb->get_var($wpdb->prepare(
            "SELECT user_id FROM {$wpdb->prefix}itipster_subscriptions 
             WHERE referral_code = %s AND status = 'active'",
            $code
        ));
        
        if (!$exists) {
            return new \WP_Error('invalid_referral', 'Invalid referral code.');
        }
        
        return true;
    }
    
    /**
     * Generate secure verification token
     * 
     * @param int $user_id User ID
     * @return string Verification token
     */
    private function generate_verification_token(int $user_id): string {
        return wp_hash($user_id . time() . wp_salt('auth'));
    }
    
    /**
     * Get verification email template
     * 
     * @param string $verification_url Verification URL
     * @return string Email HTML content
     */
    private function get_verification_email_template(string $verification_url): string {
        // This would be a proper HTML email template
        return "
        <div style='max-width: 600px; margin: 0 auto; padding: 20px;'>
            <h2>Welcome to iTipster Pro!</h2>
            <p>Please verify your email address by clicking the link below:</p>
            <a href='{$verification_url}' style='background: #6366f1; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px;'>
                Verify Email Address
            </a>
            <p>This link will expire in 24 hours.</p>
        </div>";
    }
    
    /**
     * Award referrer for successful referral
     * 
     * @param int $referrer_id Referrer user ID
     * @param int $new_user_id New user ID
     */
    private function award_referrer(int $referrer_id, int $new_user_id): void {
        // Implement referral rewards (credits, subscription extension, etc.)
        // This is a placeholder for future implementation
    }
    
    /**
     * Award new user for using referral code
     * 
     * @param int $user_id New user ID
     */
    private function award_referred_user(int $user_id): void {
        // Implement new user bonuses
        // This is a placeholder for future implementation
    }
    
    /**
     * Log registration activity
     * 
     * @param int $user_id User ID
     * @param array $user_data Registration data
     */
    private function log_registration_activity(int $user_id, array $user_data): void {
        $this->log_user_activity($user_id, 'user_registered', array(
            'ip' => $this->get_client_ip(),
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
            'referral_code' => $user_data['referral_code'] ?? null
        ));
    }
    
    /**
     * Log user activity
     * 
     * @param int $user_id User ID
     * @param string $activity_type Activity type
     * @param array $activity_data Activity data
     */
    private function log_user_activity(int $user_id, string $activity_type, array $activity_data = array()): void {
        global $wpdb;
        
        $wpdb->insert(
            $wpdb->prefix . 'itipster_user_activity',
            array(
                'user_id' => $user_id,
                'activity_type' => $activity_type,
                'activity_data' => json_encode($activity_data),
                'ip_address' => $activity_data['ip'] ?? $this->get_client_ip(),
                'user_agent' => $activity_data['user_agent'] ?? ($_SERVER['HTTP_USER_AGENT'] ?? ''),
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