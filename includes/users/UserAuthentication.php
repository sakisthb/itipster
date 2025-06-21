<?php
namespace ADPM\iTipsterPro\Users;

/**
 * User Authentication Management Class
 * 
 * Handles user login, session management, password reset,
 * rate limiting, and security measures.
 * 
 * @package ADPM\iTipsterPro\Users
 * @since 1.0.0
 */
class UserAuthentication {
    
    /**
     * @var \ADPM\iTipsterPro\Users\UserProfile
     */
    private $user_profile;
    
    /**
     * Rate limiting settings
     */
    private const MAX_LOGIN_ATTEMPTS = 5;
    private const LOCKOUT_DURATION = 900; // 15 minutes
    private const MAX_LOGIN_ATTEMPTS_PER_IP = 20;
    
    /**
     * Constructor with dependency injection
     * 
     * @param UserProfile $user_profile
     */
    public function __construct(UserProfile $user_profile = null) {
        $this->user_profile = $user_profile;
        
        // Hook into WordPress authentication
        add_action('wp_ajax_nopriv_itipster_login', array($this, 'ajax_authenticate_user'));
        add_action('wp_ajax_itipster_logout', array($this, 'ajax_logout_user'));
        add_action('wp_ajax_nopriv_itipster_reset_password', array($this, 'ajax_reset_password'));
        add_action('wp_ajax_nopriv_itipster_verify_reset_token', array($this, 'ajax_verify_reset_token'));
        
        // Security hooks
        add_action('wp_login_failed', array($this, 'handle_failed_login'));
        add_action('wp_login', array($this, 'handle_successful_login'));
    }
    
    /**
     * Authenticate user with comprehensive security checks
     * 
     * @param string $username Username or email
     * @param string $password Password
     * @param bool $remember_me Remember me option
     * @return array Authentication result
     * 
     * @throws \Exception On authentication failures
     */
    public function authenticate_user(string $username, string $password, bool $remember_me = false): array {
        try {
            // Security: Rate limiting check
            if (!$this->check_login_rate_limit()) {
                throw new \Exception('Too many login attempts. Please try again later.');
            }
            
            // Security: Check for account lockout
            if ($this->is_account_locked($username)) {
                throw new \Exception('Account temporarily locked due to multiple failed attempts.');
            }
            
            // Validate input
            if (empty($username) || empty($password)) {
                throw new \Exception('Username and password are required.');
            }
            
            // Attempt WordPress authentication
            $user = wp_authenticate($username, $password);
            
            if (is_wp_error($user)) {
                $this->handle_failed_attempt($username);
                throw new \Exception('Invalid username or password.');
            }
            
            // Check if email is verified
            if (!$this->is_email_verified($user->ID)) {
                throw new \Exception('Please verify your email address before logging in.');
            }
            
            // Check if account is active
            if (!$this->is_account_active($user->ID)) {
                throw new \Exception('Account is not active. Please contact support.');
            }
            
            // Create secure session
            $session_data = $this->create_session($user->ID, $remember_me);
            
            // Log successful login
            $this->log_login_activity($user->ID, true);
            
            // Clear failed attempts
            $this->clear_failed_attempts($username);
            
            return array(
                'success' => true,
                'user_id' => $user->ID,
                'session_token' => $session_data['token'],
                'redirect_url' => $this->get_login_redirect_url($user->ID)
            );
            
        } catch (\Exception $e) {
            // Log error for debugging
            error_log('UserAuthentication Error: ' . $e->getMessage());
            
            return array(
                'success' => false,
                'error' => $e->getMessage()
            );
        }
    }
    
    /**
     * Create secure user session
     * 
     * @param int $user_id User ID
     * @param bool $remember_me Remember me option
     * @return array Session data
     */
    public function create_session(int $user_id, bool $remember_me = false): array {
        try {
            // Generate secure session token
            $session_token = $this->generate_session_token($user_id);
            
            // Set session expiration
            $expiration = $remember_me ? time() + (30 * DAY_IN_SECONDS) : time() + (2 * HOUR_IN_SECONDS);
            
            // Store session data
            $session_data = array(
                'user_id' => $user_id,
                'token' => $session_token,
                'expires' => $expiration,
                'ip_address' => $this->get_client_ip(),
                'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
                'created_at' => current_time('mysql')
            );
            
            update_user_meta($user_id, 'itipster_session', $session_data);
            
            // Set WordPress cookies
            wp_set_auth_cookie($user_id, $remember_me);
            
            return $session_data;
            
        } catch (\Exception $e) {
            error_log('Session creation error: ' . $e->getMessage());
            throw new \Exception('Failed to create user session.');
        }
    }
    
    /**
     * Logout user and clear session
     * 
     * @param int $user_id User ID
     * @return bool Success status
     */
    public function logout_user(int $user_id): bool {
        try {
            // Clear session data
            delete_user_meta($user_id, 'itipster_session');
            
            // Log logout activity
            $this->log_user_activity($user_id, 'user_logout', array(
                'ip' => $this->get_client_ip()
            ));
            
            // WordPress logout
            wp_logout();
            
            return true;
            
        } catch (\Exception $e) {
            error_log('Logout error: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Reset password with secure token system
     * 
     * @param string $email User email
     * @return array Reset result
     */
    public function reset_password(string $email): array {
        try {
            // Validate email
            if (!is_email($email)) {
                throw new \Exception('Please enter a valid email address.');
            }
            
            // Find user by email
            $user = get_user_by('email', $email);
            if (!$user) {
                // Don't reveal if email exists or not for security
                return array('success' => true, 'message' => 'If the email exists, a reset link has been sent.');
            }
            
            // Check rate limiting for password reset
            if (!$this->check_reset_rate_limit($email)) {
                throw new \Exception('Too many password reset attempts. Please try again later.');
            }
            
            // Generate secure reset token
            $reset_token = $this->generate_reset_token($user->ID);
            
            // Store reset token with expiration
            update_user_meta($user->ID, 'password_reset_token', $reset_token);
            update_user_meta($user->ID, 'password_reset_expires', time() + (60 * 60)); // 1 hour
            
            // Send reset email
            $reset_url = add_query_arg(array(
                'action' => 'reset_password',
                'token' => $reset_token,
                'user_id' => $user->ID
            ), home_url('/wp-admin/admin-ajax.php'));
            
            $sent = $this->send_password_reset_email($user->user_email, $reset_url);
            
            if (!$sent) {
                throw new \Exception('Failed to send password reset email.');
            }
            
            // Log reset request
            $this->log_user_activity($user->ID, 'password_reset_requested', array(
                'ip' => $this->get_client_ip()
            ));
            
            return array('success' => true, 'message' => 'Password reset link sent to your email.');
            
        } catch (\Exception $e) {
            error_log('Password reset error: ' . $e->getMessage());
            return array('success' => false, 'error' => $e->getMessage());
        }
    }
    
    /**
     * Check rate limits for login attempts
     * 
     * @return bool Rate limit status
     */
    public function check_rate_limits(): bool {
        $ip = $this->get_client_ip();
        $hour_key = 'login_attempts_' . $ip . '_' . date('Y-m-d-H');
        
        $attempts = get_transient($hour_key) ?: 0;
        
        return $attempts < self::MAX_LOGIN_ATTEMPTS_PER_IP;
    }
    
    /**
     * Handle failed login attempts
     * 
     * @param string $username Username or email
     * @return void
     */
    public function handle_failed_attempts(string $username): void {
        try {
            // Increment failed attempts counter
            $attempts_key = 'failed_attempts_' . sanitize_user($username);
            $attempts = get_transient($attempts_key) ?: 0;
            $attempts++;
            
            set_transient($attempts_key, $attempts, self::LOCKOUT_DURATION);
            
            // Log failed attempt
            $this->log_failed_attempt($username);
            
            // Lock account if too many attempts
            if ($attempts >= self::MAX_LOGIN_ATTEMPTS) {
                $this->lock_account($username);
            }
            
        } catch (\Exception $e) {
            error_log('Failed attempt handling error: ' . $e->getMessage());
        }
    }
    
    /**
     * AJAX handler for user login
     */
    public function ajax_authenticate_user(): void {
        check_ajax_referer('itipster_login_nonce', 'nonce');
        
        $username = sanitize_text_field($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $remember_me = !empty($_POST['remember_me']);
        
        $result = $this->authenticate_user($username, $password, $remember_me);
        
        if ($result['success']) {
            wp_send_json_success($result);
        } else {
            wp_send_json_error($result);
        }
    }
    
    /**
     * AJAX handler for user logout
     */
    public function ajax_logout_user(): void {
        check_ajax_referer('itipster_logout_nonce', 'nonce');
        
        $user_id = get_current_user_id();
        
        if (!$user_id) {
            wp_send_json_error(array('error' => 'User not logged in.'));
        }
        
        $result = $this->logout_user($user_id);
        
        if ($result) {
            wp_send_json_success(array('message' => 'Logged out successfully.'));
        } else {
            wp_send_json_error(array('error' => 'Logout failed.'));
        }
    }
    
    /**
     * AJAX handler for password reset request
     */
    public function ajax_reset_password(): void {
        check_ajax_referer('itipster_reset_nonce', 'nonce');
        
        $email = sanitize_email($_POST['email'] ?? '');
        
        $result = $this->reset_password($email);
        
        if ($result['success']) {
            wp_send_json_success($result);
        } else {
            wp_send_json_error($result);
        }
    }
    
    /**
     * AJAX handler for password reset token verification
     */
    public function ajax_verify_reset_token(): void {
        check_ajax_referer('itipster_reset_nonce', 'nonce');
        
        $token = sanitize_text_field($_POST['token'] ?? '');
        $user_id = intval($_POST['user_id'] ?? 0);
        $new_password = $_POST['new_password'] ?? '';
        
        $result = $this->verify_reset_token($token, $user_id, $new_password);
        
        if ($result['success']) {
            wp_send_json_success($result);
        } else {
            wp_send_json_error($result);
        }
    }
    
    /**
     * WordPress hook: Handle failed login
     */
    public function handle_failed_login(string $username): void {
        $this->handle_failed_attempts($username);
    }
    
    /**
     * WordPress hook: Handle successful login
     */
    public function handle_successful_login(string $username, \WP_User $user): void {
        $this->log_login_activity($user->ID, true);
        $this->clear_failed_attempts($username);
    }
    
    // ============================================================================
    // PRIVATE HELPER METHODS
    // ============================================================================
    
    /**
     * Check login rate limit
     * 
     * @return bool Rate limit status
     */
    private function check_login_rate_limit(): bool {
        $ip = $this->get_client_ip();
        $hour_key = 'login_attempts_' . $ip . '_' . date('Y-m-d-H');
        
        $attempts = get_transient($hour_key) ?: 0;
        
        if ($attempts >= self::MAX_LOGIN_ATTEMPTS_PER_IP) {
            return false;
        }
        
        set_transient($hour_key, $attempts + 1, HOUR_IN_SECONDS);
        return true;
    }
    
    /**
     * Check if account is locked
     * 
     * @param string $username Username or email
     * @return bool Lock status
     */
    private function is_account_locked(string $username): bool {
        $lock_key = 'account_locked_' . sanitize_user($username);
        return get_transient($lock_key) !== false;
    }
    
    /**
     * Check if email is verified
     * 
     * @param int $user_id User ID
     * @return bool Verification status
     */
    private function is_email_verified(int $user_id): bool {
        $verification_token = get_user_meta($user_id, 'email_verification_token', true);
        return empty($verification_token);
    }
    
    /**
     * Check if account is active
     * 
     * @param int $user_id User ID
     * @return bool Active status
     */
    private function is_account_active(int $user_id): bool {
        $user = get_userdata($user_id);
        return $user && $user->user_status == 0;
    }
    
    /**
     * Handle failed login attempt
     * 
     * @param string $username Username or email
     */
    private function handle_failed_attempt(string $username): void {
        $this->handle_failed_attempts($username);
    }
    
    /**
     * Clear failed attempts for username
     * 
     * @param string $username Username or email
     */
    private function clear_failed_attempts(string $username): void {
        $attempts_key = 'failed_attempts_' . sanitize_user($username);
        $lock_key = 'account_locked_' . sanitize_user($username);
        
        delete_transient($attempts_key);
        delete_transient($lock_key);
    }
    
    /**
     * Lock account temporarily
     * 
     * @param string $username Username or email
     */
    private function lock_account(string $username): void {
        $lock_key = 'account_locked_' . sanitize_user($username);
        set_transient($lock_key, true, self::LOCKOUT_DURATION);
    }
    
    /**
     * Check reset rate limit
     * 
     * @param string $email Email address
     * @return bool Rate limit status
     */
    private function check_reset_rate_limit(string $email): bool {
        $reset_key = 'reset_attempts_' . sanitize_email($email) . '_' . date('Y-m-d-H');
        $attempts = get_transient($reset_key) ?: 0;
        
        if ($attempts >= 3) { // Max 3 reset attempts per hour
            return false;
        }
        
        set_transient($reset_key, $attempts + 1, HOUR_IN_SECONDS);
        return true;
    }
    
    /**
     * Generate secure session token
     * 
     * @param int $user_id User ID
     * @return string Session token
     */
    private function generate_session_token(int $user_id): string {
        return wp_hash($user_id . time() . wp_salt('auth') . rand());
    }
    
    /**
     * Generate secure reset token
     * 
     * @param int $user_id User ID
     * @return string Reset token
     */
    private function generate_reset_token(int $user_id): string {
        return wp_hash($user_id . time() . wp_salt('auth') . 'reset');
    }
    
    /**
     * Send password reset email
     * 
     * @param string $email User email
     * @param string $reset_url Reset URL
     * @return bool Success status
     */
    private function send_password_reset_email(string $email, string $reset_url): bool {
        $subject = 'Reset your iTipster Pro password';
        $message = "
        <div style='max-width: 600px; margin: 0 auto; padding: 20px;'>
            <h2>Password Reset Request</h2>
            <p>You requested to reset your password. Click the link below to proceed:</p>
            <a href='{$reset_url}' style='background: #6366f1; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px;'>
                Reset Password
            </a>
            <p>This link will expire in 1 hour.</p>
            <p>If you didn't request this, please ignore this email.</p>
        </div>";
        
        $headers = array('Content-Type: text/html; charset=UTF-8');
        return wp_mail($email, $subject, $message, $headers);
    }
    
    /**
     * Verify reset token and update password
     * 
     * @param string $token Reset token
     * @param int $user_id User ID
     * @param string $new_password New password
     * @return array Verification result
     */
    private function verify_reset_token(string $token, int $user_id, string $new_password): array {
        try {
            // Get stored token and expiration
            $stored_token = get_user_meta($user_id, 'password_reset_token', true);
            $expires = get_user_meta($user_id, 'password_reset_expires', true);
            
            // Validate token
            if (!$stored_token || $token !== $stored_token) {
                return array('success' => false, 'error' => 'Invalid reset token.');
            }
            
            // Check expiration
            if (time() > $expires) {
                return array('success' => false, 'error' => 'Reset token has expired.');
            }
            
            // Validate new password
            if (strlen($new_password) < 8) {
                return array('success' => false, 'error' => 'Password must be at least 8 characters long.');
            }
            
            // Update password
            wp_set_password($new_password, $user_id);
            
            // Clear reset data
            delete_user_meta($user_id, 'password_reset_token');
            delete_user_meta($user_id, 'password_reset_expires');
            
            // Log password change
            $this->log_user_activity($user_id, 'password_changed', array(
                'ip' => $this->get_client_ip()
            ));
            
            return array('success' => true, 'message' => 'Password updated successfully!');
            
        } catch (\Exception $e) {
            error_log('Password reset verification error: ' . $e->getMessage());
            return array('success' => false, 'error' => 'Password reset failed. Please try again.');
        }
    }
    
    /**
     * Get login redirect URL
     * 
     * @param int $user_id User ID
     * @return string Redirect URL
     */
    private function get_login_redirect_url(int $user_id): string {
        // Check if user has a specific redirect URL stored
        $redirect_url = get_user_meta($user_id, 'login_redirect_url', true);
        
        if ($redirect_url) {
            delete_user_meta($user_id, 'login_redirect_url');
            return $redirect_url;
        }
        
        // Default redirect based on user role or subscription
        return home_url('/dashboard/');
    }
    
    /**
     * Log login activity
     * 
     * @param int $user_id User ID
     * @param bool $success Login success status
     */
    private function log_login_activity(int $user_id, bool $success): void {
        $this->log_user_activity($user_id, $success ? 'login_success' : 'login_failed', array(
            'ip' => $this->get_client_ip(),
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? ''
        ));
    }
    
    /**
     * Log failed attempt
     * 
     * @param string $username Username or email
     */
    private function log_failed_attempt(string $username): void {
        // Log failed attempt for security monitoring
        error_log("Failed login attempt for username: $username from IP: " . $this->get_client_ip());
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