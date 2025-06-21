<?php
namespace ADPM\iTipsterPro\Users;

/**
 * User Profile Management Class
 * 
 * Handles user profile data, statistics calculation,
 * favorites management, and activity tracking.
 * 
 * @package ADPM\iTipsterPro\Users
 * @since 1.0.0
 */
class UserProfile {
    
    /**
     * @var \ADPM\iTipsterPro\Users\SubscriptionManager
     */
    private $subscription_manager;
    
    /**
     * Constructor with dependency injection
     * 
     * @param SubscriptionManager $subscription_manager
     */
    public function __construct(SubscriptionManager $subscription_manager = null) {
        $this->subscription_manager = $subscription_manager;
        
        // Hook into WordPress profile actions
        add_action('wp_ajax_itipster_get_profile', array($this, 'ajax_get_user_profile'));
        add_action('wp_ajax_itipster_update_profile', array($this, 'ajax_update_profile'));
        add_action('wp_ajax_itipster_upload_avatar', array($this, 'ajax_upload_avatar'));
        add_action('wp_ajax_itipster_get_stats', array($this, 'ajax_get_user_stats'));
        add_action('wp_ajax_itipster_manage_favorites', array($this, 'ajax_manage_favorites'));
        add_action('wp_ajax_itipster_get_activity', array($this, 'ajax_get_user_activity'));
    }
    
    /**
     * Get comprehensive user profile data
     * 
     * @param int $user_id User ID
     * @return array|WP_Error Profile data or error
     * 
     * @throws \Exception On profile retrieval failures
     */
    public function get_user_profile(int $user_id): array {
        try {
            // Security: Check if user can access this profile
            if (!$this->can_access_profile($user_id)) {
                throw new \Exception('Access denied to profile data.');
            }
            
            // Get WordPress user data
            $user = get_userdata($user_id);
            if (!$user) {
                throw new \Exception('User not found.');
            }
            
            // Get extended profile data
            global $wpdb;
            $profile_data = $wpdb->get_row($wpdb->prepare(
                "SELECT * FROM {$wpdb->prefix}itipster_user_profiles WHERE user_id = %d",
                $user_id
            ), ARRAY_A);
            
            if (!$profile_data) {
                // Create default profile if doesn't exist
                $profile_data = $this->create_default_profile($user_id);
            }
            
            // Get subscription data
            $subscription_data = array();
            if ($this->subscription_manager) {
                $subscription_data = $this->subscription_manager->get_user_subscription($user_id);
            }
            
            // Get user statistics
            $user_stats = $this->calculate_user_stats($user_id);
            
            // Get recent activity
            $recent_activity = $this->get_user_activity($user_id, 10);
            
            // Get favorites
            $favorites = $this->get_user_favorites($user_id);
            
            return array(
                'success' => true,
                'profile' => array(
                    'user_id' => $user_id,
                    'email' => $user->user_email,
                    'display_name' => $user->display_name,
                    'avatar_url' => get_avatar_url($user_id, array('size' => 150)),
                    'registration_date' => $user->user_registered,
                    'last_login' => get_user_meta($user_id, 'last_login', true),
                    'betting_strategy' => $profile_data['betting_strategy'] ?? 'moderate',
                    'timezone' => $profile_data['timezone'] ?? wp_timezone_string(),
                    'preferred_notifications' => json_decode($profile_data['preferred_notifications'] ?? '{}', true),
                    'favorite_leagues' => json_decode($profile_data['favorite_leagues'] ?? '[]', true),
                    'bio' => $profile_data['bio'] ?? '',
                    'location' => $profile_data['location'] ?? '',
                    'website' => $profile_data['website'] ?? '',
                    'social_links' => json_decode($profile_data['social_links'] ?? '{}', true),
                    'created_at' => $profile_data['created_at'] ?? '',
                    'updated_at' => $profile_data['updated_at'] ?? ''
                ),
                'subscription' => $subscription_data,
                'statistics' => $user_stats,
                'recent_activity' => $recent_activity,
                'favorites' => $favorites
            );
            
        } catch (\Exception $e) {
            error_log('UserProfile Error: ' . $e->getMessage());
            
            return array(
                'success' => false,
                'error' => $e->getMessage()
            );
        }
    }
    
    /**
     * Update user profile with validation
     * 
     * @param int $user_id User ID
     * @param array $profile_data Profile data to update
     * @return array Update result
     */
    public function update_profile(int $user_id, array $profile_data): array {
        try {
            // Security: Check if user can update this profile
            if (!$this->can_update_profile($user_id)) {
                throw new \Exception('Access denied to update profile.');
            }
            
            // Validate profile data
            $validation_result = $this->validate_profile_data($profile_data);
            if (is_wp_error($validation_result)) {
                return array('success' => false, 'error' => $validation_result->get_error_message());
            }
            
            // Sanitize profile data
            $sanitized_data = $this->sanitize_profile_data($profile_data);
            
            // Update WordPress user data
            $wp_user_data = array('ID' => $user_id);
            
            if (!empty($sanitized_data['display_name'])) {
                $wp_user_data['display_name'] = $sanitized_data['display_name'];
            }
            
            if (!empty($sanitized_data['website'])) {
                $wp_user_data['user_url'] = $sanitized_data['website'];
            }
            
            $wp_result = wp_update_user($wp_user_data);
            if (is_wp_error($wp_result)) {
                throw new \Exception($wp_result->get_error_message());
            }
            
            // Update extended profile data
            global $wpdb;
            $update_data = array(
                'betting_strategy' => $sanitized_data['betting_strategy'] ?? 'moderate',
                'timezone' => $sanitized_data['timezone'] ?? wp_timezone_string(),
                'preferred_notifications' => json_encode($sanitized_data['preferred_notifications'] ?? array()),
                'favorite_leagues' => json_encode($sanitized_data['favorite_leagues'] ?? array()),
                'bio' => $sanitized_data['bio'] ?? '',
                'location' => $sanitized_data['location'] ?? '',
                'website' => $sanitized_data['website'] ?? '',
                'social_links' => json_encode($sanitized_data['social_links'] ?? array()),
                'updated_at' => current_time('mysql')
            );
            
            $result = $wpdb->update(
                $wpdb->prefix . 'itipster_user_profiles',
                $update_data,
                array('user_id' => $user_id),
                array('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s'),
                array('%d')
            );
            
            if ($result === false) {
                throw new \Exception('Failed to update profile data.');
            }
            
            // Log profile update activity
            $this->log_user_activity($user_id, 'profile_updated', array(
                'updated_fields' => array_keys($sanitized_data)
            ));
            
            return array(
                'success' => true,
                'message' => 'Profile updated successfully.',
                'profile' => $this->get_user_profile($user_id)
            );
            
        } catch (\Exception $e) {
            error_log('Profile update error: ' . $e->getMessage());
            
            return array(
                'success' => false,
                'error' => $e->getMessage()
            );
        }
    }
    
    /**
     * Upload and process user avatar
     * 
     * @param int $user_id User ID
     * @param array $file_data File upload data
     * @return array Upload result
     */
    public function upload_avatar(int $user_id, array $file_data): array {
        try {
            // Security: Check if user can upload avatar
            if (!$this->can_update_profile($user_id)) {
                throw new \Exception('Access denied to upload avatar.');
            }
            
            // Validate file upload
            if (!isset($file_data['tmp_name']) || !is_uploaded_file($file_data['tmp_name'])) {
                throw new \Exception('Invalid file upload.');
            }
            
            // Check file type
            $allowed_types = array('image/jpeg', 'image/png', 'image/gif');
            $file_type = mime_content_type($file_data['tmp_name']);
            
            if (!in_array($file_type, $allowed_types)) {
                throw new \Exception('Invalid file type. Only JPEG, PNG, and GIF are allowed.');
            }
            
            // Check file size (max 2MB)
            if ($file_data['size'] > 2 * 1024 * 1024) {
                throw new \Exception('File size too large. Maximum size is 2MB.');
            }
            
            // Process and resize image
            $avatar_url = $this->process_avatar_image($user_id, $file_data);
            
            // Update user meta
            update_user_meta($user_id, 'custom_avatar_url', $avatar_url);
            
            // Log avatar upload activity
            $this->log_user_activity($user_id, 'avatar_uploaded', array(
                'avatar_url' => $avatar_url
            ));
            
            return array(
                'success' => true,
                'avatar_url' => $avatar_url,
                'message' => 'Avatar uploaded successfully.'
            );
            
        } catch (\Exception $e) {
            error_log('Avatar upload error: ' . $e->getMessage());
            
            return array(
                'success' => false,
                'error' => $e->getMessage()
            );
        }
    }
    
    /**
     * Calculate comprehensive user statistics
     * 
     * @param int $user_id User ID
     * @return array User statistics
     */
    public function calculate_user_stats(int $user_id): array {
        try {
            global $wpdb;
            
            // Get prediction statistics
            $prediction_stats = $wpdb->get_row($wpdb->prepare(
                "SELECT 
                    COUNT(*) as total_predictions,
                    SUM(CASE WHEN result = 'win' THEN 1 ELSE 0 END) as wins,
                    SUM(CASE WHEN result = 'loss' THEN 1 ELSE 0 END) as losses,
                    SUM(CASE WHEN result = 'draw' THEN 1 ELSE 0 END) as draws,
                    AVG(CASE WHEN result = 'win' THEN 1 WHEN result = 'loss' THEN 0 ELSE 0.5 END) as win_rate
                FROM {$wpdb->prefix}itipster_predictions 
                WHERE user_id = %d",
                $user_id
            ), ARRAY_A);
            
            // Get subscription statistics
            $subscription_stats = array();
            if ($this->subscription_manager) {
                $subscription_stats = $this->subscription_manager->get_user_subscription_stats($user_id);
            }
            
            // Get activity statistics
            $activity_stats = $wpdb->get_row($wpdb->prepare(
                "SELECT 
                    COUNT(*) as total_activities,
                    MAX(created_at) as last_activity
                FROM {$wpdb->prefix}itipster_user_activity 
                WHERE user_id = %d",
                $user_id
            ), ARRAY_A);
            
            // Get favorites count
            $favorites_count = $wpdb->get_var($wpdb->prepare(
                "SELECT COUNT(*) FROM {$wpdb->prefix}itipster_user_favorites WHERE user_id = %d",
                $user_id
            ));
            
            // Calculate additional metrics
            $total_predictions = intval($prediction_stats['total_predictions'] ?? 0);
            $wins = intval($prediction_stats['wins'] ?? 0);
            $losses = intval($prediction_stats['losses'] ?? 0);
            $draws = intval($prediction_stats['draws'] ?? 0);
            
            $win_rate = $total_predictions > 0 ? ($wins / $total_predictions) * 100 : 0;
            $profit_loss = $this->calculate_profit_loss($user_id);
            
            return array(
                'predictions' => array(
                    'total' => $total_predictions,
                    'wins' => $wins,
                    'losses' => $losses,
                    'draws' => $draws,
                    'win_rate' => round($win_rate, 2),
                    'profit_loss' => $profit_loss
                ),
                'subscription' => $subscription_stats,
                'activity' => array(
                    'total_activities' => intval($activity_stats['total_activities'] ?? 0),
                    'last_activity' => $activity_stats['last_activity'] ?? null
                ),
                'favorites' => array(
                    'total_favorites' => intval($favorites_count ?? 0)
                ),
                'rankings' => $this->calculate_user_rankings($user_id)
            );
            
        } catch (\Exception $e) {
            error_log('Stats calculation error: ' . $e->getMessage());
            return array();
        }
    }
    
    /**
     * Manage user favorites (add/remove)
     * 
     * @param int $user_id User ID
     * @param string $action Action (add/remove)
     * @param array $favorite_data Favorite data
     * @return array Management result
     */
    public function manage_favorites(int $user_id, string $action, array $favorite_data): array {
        try {
            // Security: Check if user can manage favorites
            if (!$this->can_access_profile($user_id)) {
                throw new \Exception('Access denied to manage favorites.');
            }
            
            global $wpdb;
            
            switch ($action) {
                case 'add':
                    $result = $this->add_favorite($user_id, $favorite_data);
                    break;
                    
                case 'remove':
                    $result = $this->remove_favorite($user_id, $favorite_data);
                    break;
                    
                case 'list':
                    $result = $this->get_user_favorites($user_id);
                    break;
                    
                default:
                    throw new \Exception('Invalid action specified.');
            }
            
            return array(
                'success' => true,
                'action' => $action,
                'result' => $result
            );
            
        } catch (\Exception $e) {
            error_log('Favorites management error: ' . $e->getMessage());
            
            return array(
                'success' => false,
                'error' => $e->getMessage()
            );
        }
    }
    
    /**
     * Get user activity with pagination
     * 
     * @param int $user_id User ID
     * @param int $limit Number of activities to retrieve
     * @param int $offset Offset for pagination
     * @return array User activity
     */
    public function get_user_activity(int $user_id, int $limit = 20, int $offset = 0): array {
        try {
            // Security: Check if user can access activity
            if (!$this->can_access_profile($user_id)) {
                throw new \Exception('Access denied to view activity.');
            }
            
            global $wpdb;
            
            $activities = $wpdb->get_results($wpdb->prepare(
                "SELECT * FROM {$wpdb->prefix}itipster_user_activity 
                 WHERE user_id = %d 
                 ORDER BY created_at DESC 
                 LIMIT %d OFFSET %d",
                $user_id, $limit, $offset
            ), ARRAY_A);
            
            // Format activity data
            $formatted_activities = array();
            foreach ($activities as $activity) {
                $formatted_activities[] = array(
                    'id' => $activity['id'],
                    'activity_type' => $activity['activity_type'],
                    'activity_data' => json_decode($activity['activity_data'], true),
                    'ip_address' => $activity['ip_address'],
                    'created_at' => $activity['created_at'],
                    'formatted_date' => $this->format_activity_date($activity['created_at'])
                );
            }
            
            return $formatted_activities;
            
        } catch (\Exception $e) {
            error_log('Activity retrieval error: ' . $e->getMessage());
            return array();
        }
    }
    
    /**
     * AJAX handler for getting user profile
     */
    public function ajax_get_user_profile(): void {
        check_ajax_referer('itipster_profile_nonce', 'nonce');
        
        $user_id = intval($_POST['user_id'] ?? get_current_user_id());
        
        $result = $this->get_user_profile($user_id);
        
        if ($result['success']) {
            wp_send_json_success($result);
        } else {
            wp_send_json_error($result);
        }
    }
    
    /**
     * AJAX handler for updating profile
     */
    public function ajax_update_profile(): void {
        check_ajax_referer('itipster_profile_nonce', 'nonce');
        
        $user_id = get_current_user_id();
        $profile_data = array(
            'display_name' => sanitize_text_field($_POST['display_name'] ?? ''),
            'betting_strategy' => sanitize_text_field($_POST['betting_strategy'] ?? ''),
            'timezone' => sanitize_text_field($_POST['timezone'] ?? ''),
            'bio' => sanitize_textarea_field($_POST['bio'] ?? ''),
            'location' => sanitize_text_field($_POST['location'] ?? ''),
            'website' => esc_url_raw($_POST['website'] ?? ''),
            'preferred_notifications' => $_POST['preferred_notifications'] ?? array(),
            'favorite_leagues' => $_POST['favorite_leagues'] ?? array(),
            'social_links' => $_POST['social_links'] ?? array()
        );
        
        $result = $this->update_profile($user_id, $profile_data);
        
        if ($result['success']) {
            wp_send_json_success($result);
        } else {
            wp_send_json_error($result);
        }
    }
    
    /**
     * AJAX handler for avatar upload
     */
    public function ajax_upload_avatar(): void {
        check_ajax_referer('itipster_profile_nonce', 'nonce');
        
        $user_id = get_current_user_id();
        
        if (!isset($_FILES['avatar'])) {
            wp_send_json_error(array('error' => 'No file uploaded.'));
        }
        
        $result = $this->upload_avatar($user_id, $_FILES['avatar']);
        
        if ($result['success']) {
            wp_send_json_success($result);
        } else {
            wp_send_json_error($result);
        }
    }
    
    /**
     * AJAX handler for getting user stats
     */
    public function ajax_get_user_stats(): void {
        check_ajax_referer('itipster_profile_nonce', 'nonce');
        
        $user_id = intval($_POST['user_id'] ?? get_current_user_id());
        
        $stats = $this->calculate_user_stats($user_id);
        
        wp_send_json_success(array('statistics' => $stats));
    }
    
    /**
     * AJAX handler for managing favorites
     */
    public function ajax_manage_favorites(): void {
        check_ajax_referer('itipster_profile_nonce', 'nonce');
        
        $user_id = get_current_user_id();
        $action = sanitize_text_field($_POST['action'] ?? '');
        $favorite_data = $_POST['favorite_data'] ?? array();
        
        $result = $this->manage_favorites($user_id, $action, $favorite_data);
        
        if ($result['success']) {
            wp_send_json_success($result);
        } else {
            wp_send_json_error($result);
        }
    }
    
    /**
     * AJAX handler for getting user activity
     */
    public function ajax_get_user_activity(): void {
        check_ajax_referer('itipster_profile_nonce', 'nonce');
        
        $user_id = intval($_POST['user_id'] ?? get_current_user_id());
        $limit = intval($_POST['limit'] ?? 20);
        $offset = intval($_POST['offset'] ?? 0);
        
        $activity = $this->get_user_activity($user_id, $limit, $offset);
        
        wp_send_json_success(array('activity' => $activity));
    }
    
    // ============================================================================
    // PRIVATE HELPER METHODS
    // ============================================================================
    
    /**
     * Check if user can access profile
     * 
     * @param int $user_id User ID
     * @return bool Access permission
     */
    private function can_access_profile(int $user_id): bool {
        $current_user_id = get_current_user_id();
        
        // Users can always access their own profile
        if ($current_user_id === $user_id) {
            return true;
        }
        
        // Admins can access any profile
        if (current_user_can('manage_options')) {
            return true;
        }
        
        // Check subscription permissions for viewing other profiles
        if ($this->subscription_manager) {
            return $this->subscription_manager->can_view_profiles($current_user_id);
        }
        
        return false;
    }
    
    /**
     * Check if user can update profile
     * 
     * @param int $user_id User ID
     * @return bool Update permission
     */
    private function can_update_profile(int $user_id): bool {
        $current_user_id = get_current_user_id();
        
        // Users can only update their own profile
        if ($current_user_id === $user_id) {
            return true;
        }
        
        // Admins can update any profile
        if (current_user_can('manage_options')) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Create default profile for user
     * 
     * @param int $user_id User ID
     * @return array Default profile data
     */
    private function create_default_profile(int $user_id): array {
        global $wpdb;
        
        $default_data = array(
            'user_id' => $user_id,
            'betting_strategy' => 'moderate',
            'timezone' => wp_timezone_string(),
            'preferred_notifications' => json_encode(array(
                'email' => true,
                'push' => false,
                'sms' => false
            )),
            'favorite_leagues' => json_encode(array()),
            'bio' => '',
            'location' => '',
            'website' => '',
            'social_links' => json_encode(array()),
            'created_at' => current_time('mysql'),
            'updated_at' => current_time('mysql')
        );
        
        $wpdb->insert(
            $wpdb->prefix . 'itipster_user_profiles',
            $default_data,
            array('%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')
        );
        
        return $default_data;
    }
    
    /**
     * Validate profile data
     * 
     * @param array $profile_data Profile data
     * @return true|WP_Error Validation result
     */
    private function validate_profile_data(array $profile_data): bool|\WP_Error {
        // Validate display name
        if (!empty($profile_data['display_name']) && strlen($profile_data['display_name']) > 50) {
            return new \WP_Error('invalid_display_name', 'Display name must be less than 50 characters.');
        }
        
        // Validate betting strategy
        $valid_strategies = array('conservative', 'moderate', 'aggressive');
        if (!empty($profile_data['betting_strategy']) && !in_array($profile_data['betting_strategy'], $valid_strategies)) {
            return new \WP_Error('invalid_strategy', 'Invalid betting strategy.');
        }
        
        // Validate timezone
        if (!empty($profile_data['timezone']) && !in_array($profile_data['timezone'], timezone_identifiers_list())) {
            return new \WP_Error('invalid_timezone', 'Invalid timezone.');
        }
        
        // Validate website URL
        if (!empty($profile_data['website']) && !filter_var($profile_data['website'], FILTER_VALIDATE_URL)) {
            return new \WP_Error('invalid_website', 'Invalid website URL.');
        }
        
        return true;
    }
    
    /**
     * Sanitize profile data
     * 
     * @param array $profile_data Profile data
     * @return array Sanitized data
     */
    private function sanitize_profile_data(array $profile_data): array {
        return array(
            'display_name' => sanitize_text_field($profile_data['display_name'] ?? ''),
            'betting_strategy' => sanitize_text_field($profile_data['betting_strategy'] ?? ''),
            'timezone' => sanitize_text_field($profile_data['timezone'] ?? ''),
            'bio' => sanitize_textarea_field($profile_data['bio'] ?? ''),
            'location' => sanitize_text_field($profile_data['location'] ?? ''),
            'website' => esc_url_raw($profile_data['website'] ?? ''),
            'preferred_notifications' => $this->sanitize_notifications($profile_data['preferred_notifications'] ?? array()),
            'favorite_leagues' => $this->sanitize_leagues($profile_data['favorite_leagues'] ?? array()),
            'social_links' => $this->sanitize_social_links($profile_data['social_links'] ?? array())
        );
    }
    
    /**
     * Process and resize avatar image
     * 
     * @param int $user_id User ID
     * @param array $file_data File data
     * @return string Avatar URL
     */
    private function process_avatar_image(int $user_id, array $file_data): string {
        // Create upload directory if it doesn't exist
        $upload_dir = wp_upload_dir();
        $avatar_dir = $upload_dir['basedir'] . '/itipster-avatars/';
        
        if (!file_exists($avatar_dir)) {
            wp_mkdir_p($avatar_dir);
        }
        
        // Generate unique filename
        $file_extension = pathinfo($file_data['name'], PATHINFO_EXTENSION);
        $filename = 'avatar-' . $user_id . '-' . time() . '.' . $file_extension;
        $filepath = $avatar_dir . $filename;
        
        // Move uploaded file
        if (!move_uploaded_file($file_data['tmp_name'], $filepath)) {
            throw new \Exception('Failed to save avatar file.');
        }
        
        // Resize image if needed
        $this->resize_avatar_image($filepath, 150, 150);
        
        return $upload_dir['baseurl'] . '/itipster-avatars/' . $filename;
    }
    
    /**
     * Resize avatar image
     * 
     * @param string $filepath File path
     * @param int $width Target width
     * @param int $height Target height
     */
    private function resize_avatar_image(string $filepath, int $width, int $height): void {
        // This would use WordPress image editing functions
        // Placeholder for image resizing functionality
    }
    
    /**
     * Calculate profit/loss for user
     * 
     * @param int $user_id User ID
     * @return float Profit/loss amount
     */
    private function calculate_profit_loss(int $user_id): float {
        global $wpdb;
        
        $result = $wpdb->get_var($wpdb->prepare(
            "SELECT SUM(profit_loss) FROM {$wpdb->prefix}itipster_predictions WHERE user_id = %d",
            $user_id
        ));
        
        return floatval($result ?? 0);
    }
    
    /**
     * Calculate user rankings
     * 
     * @param int $user_id User ID
     * @return array Rankings data
     */
    private function calculate_user_rankings(int $user_id): array {
        // This would calculate user rankings compared to other users
        // Placeholder for ranking functionality
        return array(
            'overall_rank' => 0,
            'win_rate_rank' => 0,
            'profit_rank' => 0
        );
    }
    
    /**
     * Add favorite item
     * 
     * @param int $user_id User ID
     * @param array $favorite_data Favorite data
     * @return bool Success status
     */
    private function add_favorite(int $user_id, array $favorite_data): bool {
        global $wpdb;
        
        $data = array(
            'user_id' => $user_id,
            'item_type' => sanitize_text_field($favorite_data['type'] ?? ''),
            'item_id' => intval($favorite_data['id'] ?? 0),
            'item_data' => json_encode($favorite_data['data'] ?? array()),
            'created_at' => current_time('mysql')
        );
        
        $result = $wpdb->insert(
            $wpdb->prefix . 'itipster_user_favorites',
            $data,
            array('%d', '%s', '%d', '%s', '%s')
        );
        
        return $result !== false;
    }
    
    /**
     * Remove favorite item
     * 
     * @param int $user_id User ID
     * @param array $favorite_data Favorite data
     * @return bool Success status
     */
    private function remove_favorite(int $user_id, array $favorite_data): bool {
        global $wpdb;
        
        $result = $wpdb->delete(
            $wpdb->prefix . 'itipster_user_favorites',
            array(
                'user_id' => $user_id,
                'item_type' => sanitize_text_field($favorite_data['type'] ?? ''),
                'item_id' => intval($favorite_data['id'] ?? 0)
            ),
            array('%d', '%s', '%d')
        );
        
        return $result !== false;
    }
    
    /**
     * Get user favorites
     * 
     * @param int $user_id User ID
     * @return array Favorites list
     */
    private function get_user_favorites(int $user_id): array {
        global $wpdb;
        
        $favorites = $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}itipster_user_favorites WHERE user_id = %d ORDER BY created_at DESC",
            $user_id
        ), ARRAY_A);
        
        $formatted_favorites = array();
        foreach ($favorites as $favorite) {
            $formatted_favorites[] = array(
                'id' => $favorite['id'],
                'type' => $favorite['item_type'],
                'item_id' => $favorite['item_id'],
                'data' => json_decode($favorite['item_data'], true),
                'created_at' => $favorite['created_at']
            );
        }
        
        return $formatted_favorites;
    }
    
    /**
     * Sanitize notifications data
     * 
     * @param array $notifications Notifications data
     * @return array Sanitized notifications
     */
    private function sanitize_notifications(array $notifications): array {
        $sanitized = array();
        $valid_types = array('email', 'push', 'sms');
        
        foreach ($valid_types as $type) {
            $sanitized[$type] = !empty($notifications[$type]);
        }
        
        return $sanitized;
    }
    
    /**
     * Sanitize leagues data
     * 
     * @param array $leagues Leagues data
     * @return array Sanitized leagues
     */
    private function sanitize_leagues(array $leagues): array {
        return array_map('sanitize_text_field', array_filter($leagues));
    }
    
    /**
     * Sanitize social links data
     * 
     * @param array $social_links Social links data
     * @return array Sanitized social links
     */
    private function sanitize_social_links(array $social_links): array {
        $sanitized = array();
        $valid_platforms = array('facebook', 'twitter', 'instagram', 'linkedin');
        
        foreach ($valid_platforms as $platform) {
            if (!empty($social_links[$platform])) {
                $sanitized[$platform] = esc_url_raw($social_links[$platform]);
            }
        }
        
        return $sanitized;
    }
    
    /**
     * Format activity date
     * 
     * @param string $date Date string
     * @return string Formatted date
     */
    private function format_activity_date(string $date): string {
        $timestamp = strtotime($date);
        $now = time();
        $diff = $now - $timestamp;
        
        if ($diff < 60) {
            return 'Just now';
        } elseif ($diff < 3600) {
            $minutes = floor($diff / 60);
            return $minutes . ' minute' . ($minutes > 1 ? 's' : '') . ' ago';
        } elseif ($diff < 86400) {
            $hours = floor($diff / 3600);
            return $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ago';
        } else {
            return date('M j, Y', $timestamp);
        }
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