# iTipster Pro - Premium Sports Predictions Platform

A professional WordPress plugin for sports predictions with advanced analytics, live odds, and premium user experience.

## Features

### Core Features
- **Advanced Predictions Engine** - AI-powered sports predictions with confidence scoring
- **Live Odds Integration** - Real-time odds from multiple bookmakers
- **Premium Analytics Dashboard** - Advanced statistics and performance tracking
- **User Management System** - Professional user profiles and subscriptions
- **Glassmorphism UI** - Modern, premium design with blur effects and animations

### Mobile-First Experience
- **Advanced Mobile Navigation** - Touch-optimized navigation with gestures
- **Pull-to-Refresh** - Native mobile refresh functionality
- **Swipe Gestures** - Navigate between predictions with swipe
- **Haptic Feedback** - Tactile feedback for interactions
- **Context Menus** - Long-press for additional options
- **Auto-Hiding Header** - Smart header that hides on scroll
- **Touch Interactions** - Ripple effects and smooth animations
- **Lazy Loading** - Optimized image and content loading
- **Orientation Support** - Responsive to device orientation changes

### Mobile Features Details

#### Touch Interactions
- **Ripple Effects** - Visual feedback on touch
- **Long Press Detection** - Context menus and additional options
- **Smooth Animations** - 60fps animations for premium feel
- **Touch Targets** - 44px minimum touch targets (Apple/Google guidelines)

#### Navigation
- **Mobile Header** - Sticky header with logo and menu toggle
- **Slide-out Menu** - Full-height navigation menu
- **Overlay Background** - Blurred overlay when menu is open
- **Escape Key Support** - Close menu with keyboard

#### Gestures
- **Pull to Refresh** - Pull down to refresh predictions
- **Swipe Navigation** - Swipe left/right to navigate
- **Pinch to Zoom** - Zoom on prediction details
- **Double Tap** - Quick actions on cards

#### Performance
- **Scroll Optimization** - Optimized scroll performance
- **Lazy Loading** - Images and content load as needed
- **Intersection Observer** - Efficient element detection
- **Request Animation Frame** - Smooth animations

#### Accessibility
- **Screen Reader Support** - ARIA labels and descriptions
- **Keyboard Navigation** - Full keyboard support
- **High Contrast** - Support for high contrast mode
- **Reduced Motion** - Respects user motion preferences

## Installation

1. Upload the plugin files to `/wp-content/plugins/itipster-pro/`
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Configure the plugin settings in the admin dashboard
4. The plugin will automatically create necessary pages and shortcodes

## Configuration

### API Setup
1. Get your OddAlerts API token
2. Go to iTipster Pro > Settings
3. Enter your API token
4. Save settings

### Frontend Pages
The plugin automatically creates these pages:
- `/predictions/` - Main predictions page
- `/fixtures/` - Fixtures listing
- `/dashboard/` - User dashboard
- `/fixture/[slug]/` - Individual fixture pages

### Mobile Optimization
The mobile experience is automatically optimized with:
- Responsive design
- Touch-friendly interfaces
- Fast loading times
- Native mobile interactions

## Usage

### For Users
1. Visit the predictions page to see live predictions
2. Use filters to find specific leagues or sports
3. Tap on predictions for detailed analysis
4. Use mobile gestures for navigation

### For Administrators
1. Access the admin dashboard for analytics
2. Manage predictions and fixtures
3. Monitor user activity and performance
4. Configure system settings

## Technical Details

### Mobile JavaScript Features
- **iTipsterMobile Class** - Main mobile interactions handler
- **Touch Event Management** - Advanced touch event handling
- **Gesture Recognition** - Swipe, long press, and pull detection
- **Animation System** - Smooth CSS animations and transitions
- **Performance Monitoring** - Scroll and interaction optimization

### CSS Framework
- **Mobile-First Design** - Responsive from mobile up
- **CSS Variables** - Consistent design tokens
- **Glassmorphism Effects** - Modern blur and transparency
- **Touch Optimizations** - Optimized for touch devices

### Browser Support
- **iOS Safari** - Full support with native features
- **Android Chrome** - Full support with native features
- **Desktop Browsers** - Responsive design with mouse interactions
- **Progressive Enhancement** - Works on all devices

## Development

### File Structure
```
itipster-pro/
├── assets/
│   ├── css/
│   │   ├── glassmorphism-ui.css
│   │   └── mobile-responsive.css
│   └── js/
│       ├── frontend-live.js
│       └── mobile-interactions.js
├── includes/
│   ├── Main.php
│   ├── Frontend.php
│   └── Admin.php
└── templates/
    ├── frontend/
    └── admin/
```

### Mobile Development
- **Touch Events** - Use passive listeners for performance
- **CSS Animations** - Prefer transform/opacity for 60fps
- **Responsive Design** - Mobile-first approach
- **Performance** - Optimize for mobile networks

## Support

For support and feature requests, please contact the development team.

## License

This plugin is proprietary software. All rights reserved.

## 🚀 Features

### Core Features
- **AI-Powered Predictions**: Advanced machine learning algorithms for accurate sports predictions
- **Real-Time Updates**: Live odds and prediction updates
- **Multi-League Support**: Premier League, La Liga, Bundesliga, Serie A, Ligue 1
- **Multiple Markets**: 1X2, Over/Under, BTTS, Asian Handicap, Corners, First Half Goals
- **Glassmorphism UI**: Modern, premium design with backdrop blur effects
- **Mobile Responsive**: Optimized for all devices

### Premium Features
- **Live Chat Integration**: Real-time customer support
- **Advanced Analytics**: Detailed performance tracking
- **Multi-Tier User System**: Free, Premium, VIP memberships
- **Payment System Ready**: WooCommerce integration ready
- **API Rate Limiting**: Intelligent caching and request management
- **Greek & English Support**: Multi-language ready

### Technical Features
- **OddAlerts API Integration**: Professional sports data
- **Caching System**: 15-minute intelligent caching
- **Error Handling**: Robust fallback mechanisms
- **Security**: WordPress security best practices
- **Performance**: Optimized for speed and scalability

## 📋 Requirements

- WordPress 6.0 or higher
- PHP 8.0 or higher
- MySQL 5.7 or higher
- Modern web browser with CSS backdrop-filter support

## 🛠️ Installation

1. **Download the Plugin**
   ```bash
   git clone https://github.com/adpm-gr/itipster-pro.git
   ```

2. **Upload to WordPress**
   - Upload the `itipster-pro` folder to `/wp-content/plugins/`
   - Or zip the folder and upload via WordPress admin

3. **Activate the Plugin**
   - Go to WordPress Admin → Plugins
   - Find "iTipster Pro" and click "Activate"

4. **Configure Settings**
   - Go to iTipster Pro → Settings
   - Enter your OddAlerts API token
   - Configure demo mode and other options

## ⚙️ Configuration

### API Settings
```php
// config/api-settings.php
define('ODDALERTS_API_URL', 'https://api.oddalerts.com');
define('ODDALERTS_API_TOKEN', 'your-api-token-here');
define('API_RATE_LIMIT', 100); // requests per hour
define('CACHE_DURATION', 900); // 15 minutes
```

### Demo Mode
The plugin includes comprehensive demo data for testing:
- 20 fixtures per league (5 leagues = 100 fixtures)
- Realistic odds and predictions
- Professional team names and venues
- Multiple market types

### Customization
```css
/* assets/css/glassmorphism-ui.css */
:root {
    --primary-color: #6366f1;
    --secondary-color: #8b5cf6;
    --glass-bg: rgba(255, 255, 255, 0.1);
    --glass-backdrop: blur(10px);
}
```

## 📊 Database Structure

### Custom Tables
```sql
-- Predictions table
CREATE TABLE wp_itipster_predictions (
    id mediumint(9) NOT NULL AUTO_INCREMENT,
    fixture_id mediumint(9) NOT NULL,
    prediction_type varchar(50) NOT NULL,
    prediction_value varchar(100) NOT NULL,
    confidence_score decimal(5,2) NOT NULL,
    odds decimal(6,2) NOT NULL,
    value_rating decimal(3,2) NOT NULL,
    created_at datetime DEFAULT CURRENT_TIMESTAMP,
    updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
);

-- User subscriptions table
CREATE TABLE wp_itipster_subscriptions (
    id mediumint(9) NOT NULL AUTO_INCREMENT,
    user_id bigint(20) NOT NULL,
    subscription_type varchar(20) NOT NULL,
    start_date datetime NOT NULL,
    end_date datetime NOT NULL,
    status varchar(20) DEFAULT 'active',
    created_at datetime DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
);
```

### Custom Post Types
- `itipster_prediction`: Prediction posts
- `itipster_fixture`: Match fixtures

### Custom Taxonomies
- `itipster_league`: League categories
- `itipster_sport`: Sport categories

## 🎨 UI Components

### Glassmorphism Cards
```html
<div class="glass-card">
    <div class="prediction-header">
        <div class="prediction-teams">Team A vs Team B</div>
        <div class="prediction-league">Premier League</div>
    </div>
    <div class="prediction-content">
        <!-- Prediction details -->
    </div>
</div>
```

### Live Updates
```javascript
// Real-time odds updates
function updateLiveOdds(fixtureId, odds) {
    // Update odds display
}

// WebSocket connection
const ws = new WebSocket('wss://itipster.gr/ws');
```

## 🔌 API Integration

### OddAlerts API Endpoints
- `/value/upcoming` - Value bets
- `/odds/live` - Live odds
- `/probability` - AI probability
- `/predictions` - AI predictions
- `/fixtures` - Match fixtures
- `/trends` - Team trends
- `/betslips` - Accumulators

### Example API Call
```php
$api_manager = new ADPM\iTipsterPro\APIs\ApiManager();
$predictions = $api_manager->get_ai_predictions($fixture_id);
$live_odds = $api_manager->get_live_odds($fixture_id);
```

## 🎯 Usage Examples

### Display Predictions
```php
// Get predictions for a specific league
$predictions = $api_manager->get_predictions_by_league('Premier League');

// Display in template
foreach ($predictions as $prediction) {
    echo '<div class="prediction-card">';
    echo '<h3>' . $prediction['home_team'] . ' vs ' . $prediction['away_team'] . '</h3>';
    echo '<p>Prediction: ' . $prediction['prediction_value'] . '</p>';
    echo '<p>Confidence: ' . $prediction['confidence_score'] . '%</p>';
    echo '</div>';
}
```

### Live Odds Widget
```php
// Get live odds for a fixture
$odds = $api_manager->get_live_odds($fixture_id);

// Display odds
echo '<div class="odds-card">';
echo '<div class="odds-values">';
echo '<div class="odds-option"><span class="odds-number">' . $odds['home'] . '</span></div>';
echo '<div class="odds-option"><span class="odds-number">' . $odds['draw'] . '</span></div>';
echo '<div class="odds-option"><span class="odds-number">' . $odds['away'] . '</span></div>';
echo '</div>';
echo '</div>';
```

## 🚀 Performance Optimization

### Caching Strategy
- **API Responses**: 15-minute cache for predictions
- **Live Odds**: 5-minute cache for real-time data
- **User Data**: Session-based caching
- **Static Assets**: Browser caching enabled

### Database Optimization
- Indexed queries for fast lookups
- Efficient joins for related data
- Regular cleanup of old data

### Frontend Optimization
- Lazy loading for images
- Minified CSS and JavaScript
- CDN-ready asset delivery

## 🔒 Security Features

- **Nonce Verification**: All AJAX requests
- **Input Sanitization**: All user inputs
- **SQL Prepared Statements**: Database queries
- **XSS Protection**: Output escaping
- **CSRF Protection**: Form submissions

## 🌐 Internationalization

### Supported Languages
- English (en_US)
- Greek (el_GR)

### Translation Files
```
languages/
├── itipster-pro-en_US.po
├── itipster-pro-en_US.mo
├── itipster-pro-el_GR.po
└── itipster-pro-el_GR.mo
```

## 📱 Mobile Responsiveness

### Breakpoints
- **Desktop**: 1200px+
- **Tablet**: 768px - 1199px
- **Mobile**: 320px - 767px

### Touch Optimizations
- Large touch targets
- Swipe gestures
- Optimized scrolling

## 🧪 Testing

### Unit Tests
```bash
# Run PHP unit tests
composer test

# Run JavaScript tests
npm test
```

### Browser Testing
- Chrome 90+
- Firefox 88+
- Safari 14+
- Edge 90+

## 📈 Analytics

### Built-in Analytics
- Prediction accuracy tracking
- User engagement metrics
- Revenue analytics
- Performance monitoring

### Integration Ready
- Google Analytics
- Facebook Pixel
- Custom tracking

## 🔧 Development

### Local Development
```bash
# Clone repository
git clone https://github.com/adpm-gr/itipster-pro.git

# Install dependencies
composer install
npm install

# Start development server
npm run dev
```

### Code Standards
- PSR-4 autoloading
- WordPress coding standards
- ESLint for JavaScript
- PHP_CodeSniffer for PHP

## 📄 License

This plugin is licensed under the GPL v2 or later.

## 🤝 Support

### Documentation
- [User Guide](https://itipster.gr/docs)
- [API Documentation](https://itipster.gr/api-docs)
- [Developer Guide](https://itipster.gr/dev-docs)

### Support Channels
- **Email**: support@adpm.gr
- **Live Chat**: Available on itipster.gr
- **GitHub Issues**: Bug reports and feature requests

### Premium Support
- Priority support for premium users
- Custom development services
- White-label solutions

## 🏆 Credits

**Developed by**: ADPM.gr - Ads Pro Digital Marketing  
**Website**: https://adpm.gr  
**Platform**: https://itipster.gr

---

**Version**: 1.0.0  
**Last Updated**: January 2024  
**Compatibility**: WordPress 6.0+, PHP 8.0+ 