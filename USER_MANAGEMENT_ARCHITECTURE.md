# iTipster Pro - User Management System Architecture

## Overview

The iTipster Pro plugin now includes a comprehensive user management system with modular architecture, security features, and extensible design. The system is built with proper dependency injection, error handling, and WordPress integration.

## Directory Structure

```
includes/
├── users/
│   ├── UserRegistration.php      # User registration and email verification
│   ├── UserAuthentication.php    # Login, logout, password reset
│   ├── UserProfile.php          # Profile management and statistics
│   └── SubscriptionManager.php   # Subscription and credit management
├── UserManagement.php           # Legacy user management class
└── UserManagement_Installer.php # Database table creation and migration
```

## Core Classes

### 1. UserRegistration Class

**Namespace:** `ADPM\iTipsterPro\Users`

**Purpose:** Handles user registration, email verification, referral system, and initial profile creation.

**Key Features:**
- Comprehensive input validation
- Rate limiting and security measures
- Email verification with secure tokens
- Referral system integration
- Automatic profile creation
- Spam/bot detection placeholders

**Main Methods:**
- `register_user()` - Complete registration process
- `validate_registration_data()` - Input validation
- `send_verification_email()` - Email verification
- `verify_email_token()` - Token verification
- `handle_referral()` - Referral system
- `create_user_profile()` - Profile initialization

**Security Features:**
- Rate limiting (5 registrations per hour per IP)
- Password strength validation
- Email verification required
- Suspicious activity detection
- Input sanitization

### 2. UserAuthentication Class

**Namespace:** `ADPM\iTipsterPro\Users`

**Purpose:** Manages user login, session management, password reset, and security.

**Key Features:**
- Secure session management
- Rate limiting for login attempts
- Account lockout protection
- Password reset functionality
- Activity logging
- WordPress integration

**Main Methods:**
- `authenticate_user()` - Login authentication
- `create_session()` - Secure session creation
- `logout_user()` - Session cleanup
- `reset_password()` - Password reset
- `check_rate_limits()` - Rate limiting
- `handle_failed_attempts()` - Security measures

**Security Features:**
- Maximum 5 failed attempts before lockout
- 15-minute lockout duration
- IP-based rate limiting
- Secure session tokens
- Activity monitoring

### 3. UserProfile Class

**Namespace:** `ADPM\iTipsterPro\Users`

**Purpose:** Manages user profiles, statistics, favorites, and activity tracking.

**Key Features:**
- Comprehensive profile management
- Statistics calculation
- Favorites system
- Activity tracking
- Avatar upload handling
- Permission-based access

**Main Methods:**
- `get_user_profile()` - Profile retrieval
- `update_profile()` - Profile updates
- `upload_avatar()` - Avatar management
- `calculate_user_stats()` - Statistics
- `manage_favorites()` - Favorites system
- `get_user_activity()` - Activity tracking

**Features:**
- Betting strategy preferences
- Timezone management
- Notification preferences
- Social media links
- Activity history
- Performance statistics

### 4. SubscriptionManager Class

**Namespace:** `ADPM\iTipsterPro\Users`

**Purpose:** Handles subscription plans, credit management, trials, and permissions.

**Key Features:**
- Multiple subscription tiers
- Credit-based system
- Trial period management
- Auto-renewal processing
- Permission calculations
- Payment integration placeholders

**Subscription Plans:**
- **Free:** 10 credits/month, 3 predictions/day
- **Basic:** €9.99/month, 50 credits, 10 predictions/day
- **Premium:** €19.99/month, 150 credits, 25 predictions/day
- **Pro:** €39.99/month, 500 credits, 100 predictions/day

**Main Methods:**
- `check_subscription_status()` - Status checking
- `upgrade_subscription()` - Plan upgrades
- `manage_credits()` - Credit operations
- `handle_trial()` - Trial management
- `process_auto_renewal()` - Renewal processing
- `calculate_permissions()` - Permission system

## Database Schema

### Core Tables

1. **itipster_subscriptions**
   - Extended with referral codes, auto-renewal, trial periods
   - Supports multiple subscription types and statuses

2. **itipster_user_profiles**
   - User preferences and settings
   - Betting strategy, timezone, notifications
   - Social links and bio information

3. **itipster_user_activity**
   - Comprehensive activity logging
   - IP tracking and user agent storage
   - JSON-based activity data

4. **itipster_user_favorites**
   - Flexible favorites system
   - Supports multiple item types
   - JSON-based item data

5. **itipster_credits**
   - Credit allocation and usage tracking
   - Reason-based credit management
   - Historical credit data

## Integration Points

### WordPress Integration
- AJAX handlers for all operations
- WordPress user system integration
- Proper nonce verification
- Hook integration for events

### Security Implementation
- Rate limiting at multiple levels
- Input validation and sanitization
- Secure token generation
- Activity monitoring
- Permission-based access control

### Extensibility Features
- Dependency injection architecture
- Modular class design
- Hook-based event system
- Configurable subscription plans
- Extensible permission system

## AJAX Endpoints

### Registration
- `wp_ajax_nopriv_itipster_register` - User registration
- `wp_ajax_itipster_verify_email` - Email verification
- `wp_ajax_itipster_resend_verification` - Resend verification

### Authentication
- `wp_ajax_nopriv_itipster_login` - User login
- `wp_ajax_itipster_logout` - User logout
- `wp_ajax_nopriv_itipster_reset_password` - Password reset
- `wp_ajax_nopriv_itipster_verify_reset_token` - Reset verification

### Profile Management
- `wp_ajax_itipster_get_profile` - Get user profile
- `wp_ajax_itipster_update_profile` - Update profile
- `wp_ajax_itipster_upload_avatar` - Avatar upload
- `wp_ajax_itipster_get_stats` - User statistics
- `wp_ajax_itipster_manage_favorites` - Favorites management
- `wp_ajax_itipster_get_activity` - Activity history

### Subscription Management
- `wp_ajax_itipster_check_subscription` - Check status
- `wp_ajax_itipster_upgrade_subscription` - Plan upgrade
- `wp_ajax_itipster_manage_credits` - Credit management
- `wp_ajax_itipster_get_permissions` - Get permissions

## Security Features

### Rate Limiting
- Registration: 5 per hour per IP
- Login: 20 attempts per hour per IP
- Password reset: 3 attempts per hour per email
- Account lockout: 5 failed attempts, 15-minute duration

### Input Validation
- Email format validation
- Password strength requirements
- Referral code validation
- Profile data sanitization
- File upload restrictions

### Session Security
- Secure session tokens
- IP address tracking
- User agent logging
- Automatic session expiration
- Secure logout procedures

## Future Enhancements

### Planned Features
1. **Payment Gateway Integration**
   - Stripe/PayPal integration
   - Subscription billing
   - Payment history tracking

2. **Advanced Security**
   - Two-factor authentication
   - Advanced bot detection
   - Fraud prevention systems

3. **Enhanced Analytics**
   - User behavior tracking
   - Performance analytics
   - Predictive modeling

4. **Social Features**
   - User following system
   - Social sharing
   - Community features

5. **Mobile Optimization**
   - Push notifications
   - Mobile-specific features
   - App-like experience

## Implementation Notes

### Dependencies
- WordPress 5.0+
- PHP 7.4+ (for type declarations)
- MySQL 5.7+ (for JSON support)

### Configuration
- All settings stored in WordPress options
- Configurable subscription plans
- Customizable rate limits
- Flexible permission system

### Performance
- Efficient database queries
- Caching strategies
- Optimized activity logging
- Minimal WordPress hooks

### Maintenance
- Automatic table creation
- Data migration support
- Cleanup procedures
- Backup strategies

## Conclusion

The user management system provides a solid foundation for the iTipster Pro platform with:

- **Security-first approach** with comprehensive protection measures
- **Modular architecture** for easy maintenance and extension
- **WordPress integration** following best practices
- **Scalable design** supporting future growth
- **Professional features** matching enterprise requirements

The system is ready for production use and provides a complete user management solution for sports prediction platforms. 