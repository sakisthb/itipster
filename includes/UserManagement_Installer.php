<?php
namespace ADPM\iTipsterPro;

/**
 * Handles creation and migration of user management related tables
 */
class UserManagement_Installer {
    public static function install() {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        $prefix = $wpdb->prefix;

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        // 1. Extend wp_itipster_subscriptions
        $subscriptions_table = $prefix . 'itipster_subscriptions';
        $sql_subscriptions = "CREATE TABLE $subscriptions_table (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            user_id bigint(20) NOT NULL,
            subscription_type varchar(20) NOT NULL,
            start_date datetime NOT NULL,
            end_date datetime NOT NULL,
            status varchar(20) DEFAULT 'active',
            subscription_tier varchar(20) DEFAULT 'free',
            credits_remaining int DEFAULT 0,
            auto_renewal tinyint(1) DEFAULT 0,
            payment_method_id varchar(64) DEFAULT NULL,
            trial_used tinyint(1) DEFAULT 0,
            referral_code varchar(32) DEFAULT NULL,
            referred_by bigint(20) DEFAULT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY user_id (user_id),
            KEY subscription_type (subscription_type),
            KEY subscription_tier (subscription_tier)
        ) $charset_collate;";
        dbDelta($sql_subscriptions);

        // 2. User Profiles Table
        $profiles_table = $prefix . 'itipster_user_profiles';
        $sql_profiles = "CREATE TABLE $profiles_table (
            user_id bigint(20) NOT NULL,
            avatar_url varchar(255) DEFAULT NULL,
            bio text DEFAULT NULL,
            favorite_leagues text DEFAULT NULL,
            betting_strategy varchar(20) DEFAULT 'moderate',
            total_predictions_purchased int DEFAULT 0,
            total_winnings decimal(12,2) DEFAULT 0.00,
            win_rate decimal(5,2) DEFAULT 0.00,
            longest_streak int DEFAULT 0,
            preferred_notifications text DEFAULT NULL,
            timezone varchar(64) DEFAULT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (user_id)
        ) $charset_collate;";
        dbDelta($sql_profiles);

        // 3. User Activity Table
        $activity_table = $prefix . 'itipster_user_activity';
        $sql_activity = "CREATE TABLE $activity_table (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            user_id bigint(20) NOT NULL,
            activity_type varchar(32) NOT NULL,
            activity_data text DEFAULT NULL,
            ip_address varchar(45) DEFAULT NULL,
            user_agent varchar(255) DEFAULT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY user_id (user_id),
            KEY activity_type (activity_type)
        ) $charset_collate;";
        dbDelta($sql_activity);

        // 4. Favorites Table
        $favorites_table = $prefix . 'itipster_favorites';
        $sql_favorites = "CREATE TABLE $favorites_table (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            user_id bigint(20) NOT NULL,
            fixture_id bigint(20) NOT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY user_id (user_id),
            KEY fixture_id (fixture_id)
        ) $charset_collate;";
        dbDelta($sql_favorites);
    }
} 