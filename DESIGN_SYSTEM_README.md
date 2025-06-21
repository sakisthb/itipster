# iTipster Pro - Apple-Inspired Design System

## Overview

Το iTipster Pro Design System είναι ένα comprehensive σύστημα σχεδιασμού εμπνευσμένο από την Apple aesthetic, που παρέχει όλα τα απαραίτητα components, styles, και interactions για τη δημιουργία ενός premium sports predictions platform.

## 🎨 Design Philosophy

- **Apple Aesthetic**: Clean, minimal design με subtle shadows και smooth animations
- **Professional Feel**: Premium appearance που δημιουργεί trust και credibility
- **Addictive UX**: Smooth interactions που κάνουν τους users να θέλουν να επιστρέψουν
- **Accessibility First**: WCAG 2.1 AA compliance με proper contrast ratios
- **Mobile-First**: Responsive design με touch-friendly interactions

## 📁 File Structure

```
assets/
├── css/
│   ├── design-system.css      # Core design system variables & utilities
│   ├── theme-manager.css      # Dark/light mode management
│   ├── components.css         # Reusable UI components
│   ├── admin-modern.css       # Modern admin interface
│   ├── glassmorphism-ui.css   # Glassmorphism effects
│   └── mobile-responsive.css  # Mobile-first framework
├── js/
│   ├── theme-manager.js       # Theme switching functionality
│   ├── ui-interactions.js     # Smooth interactions & animations
│   ├── frontend-live.js       # Frontend functionality
│   └── mobile-interactions.js # Mobile gestures & interactions
└── images/
    └── sport-icons/           # Sport-specific icons
```

## 🎯 Core Features

### 1. Design System Foundation
- **CSS Variables**: Complete theming system με light/dark mode support
- **Typography**: SF Pro Display-inspired font stack με consistent hierarchy
- **Spacing**: 8pt grid system για perfect alignment
- **Colors**: Apple-inspired color palette με semantic naming
- **Shadows**: Subtle shadows που δημιουργούν depth

### 2. Theme Management
- **System Preference Detection**: Αυτόματη ανίχνευση system theme
- **Local Storage Persistence**: Αποθήκευση user preferences
- **Smooth Transitions**: 0.3s ease transitions μεταξύ themes
- **Keyboard Shortcuts**: Ctrl/Cmd + Shift + T για theme toggle

### 3. UI Components
- **Buttons**: Primary, secondary, ghost, destructive variants
- **Cards**: Clean cards με hover effects και animations
- **Forms**: Floating labels, validation states, accessibility
- **Navigation**: Tabs, breadcrumbs, pagination
- **Data Display**: Tables, progress bars, badges, statistics
- **Modals**: Backdrop blur, smooth animations, responsive

### 4. Mobile-First Design
- **Touch-Friendly**: 44px minimum touch targets
- **Gesture Support**: Swipe, pull-to-refresh, haptic feedback
- **Responsive Grid**: Flexible grid system για όλα τα screen sizes
- **Mobile Navigation**: Auto-hiding header, smooth transitions

## 🚀 Quick Start

### 1. Enqueue Assets
```php
// Frontend
wp_enqueue_style('itipster-design-system');
wp_enqueue_style('itipster-theme-manager');
wp_enqueue_style('itipster-components');
wp_enqueue_script('itipster-theme-manager');
wp_enqueue_script('itipster-ui-interactions');

// Admin
wp_enqueue_style('itipster-admin-modern');
```

### 2. Use Components
```html
<!-- Button -->
<button class="btn btn-primary">Primary Action</button>

<!-- Card -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Card Title</h3>
    </div>
    <div class="card-body">
        <p>Card content goes here.</p>
    </div>
</div>

<!-- Form Input -->
<div class="form-group">
    <label class="form-label" for="email">Email</label>
    <input type="email" id="email" class="form-input" placeholder="Enter your email">
</div>
```

### 3. Theme Toggle
```html
<button class="theme-toggle" aria-label="Toggle theme">
    <span class="icon icon-sun"></span>
    <span class="icon icon-moon"></span>
</button>
```

## 🎨 Color Palette

### Light Mode
```css
--color-primary-bg: #FFFFFF
--color-secondary-bg: #F5F5F7
--color-card-bg: #FFFFFF
--color-border: #E5E5E7
--color-text-primary: #1D1D1F
--color-text-secondary: #6E6E73
--color-accent-blue: #007AFF
--color-accent-green: #34C759
--color-accent-orange: #FF9500
--color-accent-red: #FF3B30
```

### Dark Mode
```css
--color-primary-bg: #000000
--color-secondary-bg: #1C1C1E
--color-card-bg: #2C2C2E
--color-border: #38383A
--color-text-primary: #FFFFFF
--color-text-secondary: #8E8E93
--color-accent-blue: #0A84FF
--color-accent-green: #30D158
--color-accent-orange: #FF9F0A
--color-accent-red: #FF453A
```

## 📱 Responsive Breakpoints

```css
--breakpoint-sm: 375px   /* iPhone SE */
--breakpoint-md: 768px   /* iPad */
--breakpoint-lg: 1024px  /* iPad Pro */
--breakpoint-xl: 1440px  /* Desktop */
```

## 🎭 Typography Scale

```css
--text-xs: 11px    /* Captions */
--text-sm: 13px    /* Small text */
--text-base: 15px  /* Body text */
--text-lg: 17px    /* Large body */
--text-xl: 19px    /* Subheadings */
--text-2xl: 24px   /* H3 */
--text-3xl: 28px   /* H2 */
--text-4xl: 32px   /* H1 */
--text-5xl: 40px   /* Hero titles */
```

## 🎪 Animation System

### Transitions
```css
--transition-fast: 0.15s ease
--transition-normal: 0.3s ease
--transition-slow: 0.5s ease
```

### Keyframe Animations
- `fadeIn`: Smooth opacity transitions
- `slideUp`: Content entrance animations
- `ripple`: Button press effects
- `shake`: Form validation feedback
- `loading`: Skeleton loading states

## 🔧 JavaScript API

### Theme Manager
```javascript
// Get theme info
const themeInfo = window.themeManager.getThemeInfo();

// Set theme
window.themeManager.setTheme('dark');

// Toggle theme
window.themeManager.toggleTheme();

// Listen for theme changes
document.addEventListener('themechange', (e) => {
    console.log('Theme changed:', e.detail);
});
```

### UI Interactions
```javascript
// Trigger animation
window.uiInteractions.triggerAnimation(element, 'fadeIn');

// Show notification
window.themeManager.showStatus('Success!', 'success');
```

## 🎯 Component Examples

### Statistics Card
```html
<div class="stat-card">
    <div class="stat-value">2,847</div>
    <div class="stat-label">Total Predictions</div>
    <div class="stat-change positive">
        <span>↗</span>
        +12.5%
    </div>
</div>
```

### Progress Bar
```html
<div class="progress">
    <div class="progress-bar" style="width: 75%"></div>
</div>
```

### Badge
```html
<span class="badge badge-success">Active</span>
<span class="badge badge-warning">Pending</span>
<span class="badge badge-danger">Error</span>
```

### Modal
```html
<div class="modal-backdrop">
    <div class="modal modal-md">
        <div class="modal-header">
            <h3 class="modal-title">Modal Title</h3>
            <button class="modal-close">×</button>
        </div>
        <div class="modal-body">
            <p>Modal content goes here.</p>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary">Cancel</button>
            <button class="btn btn-primary">Confirm</button>
        </div>
    </div>
</div>
```

## 🎨 Demo Page

Για να δεις το design system in action, χρησιμοποίησε το shortcode:

```
[itipster_design_system]
```

Ή δημιούργησε μια νέα σελίδα με αυτό το shortcode για να δεις όλα τα components.

## 🔧 Customization

### Custom Colors
```css
:root {
    --color-accent-blue: #your-blue;
    --color-accent-green: #your-green;
    /* Add more custom colors */
}
```

### Custom Components
```css
.your-custom-component {
    background-color: var(--color-card-bg);
    border-radius: var(--radius-md);
    box-shadow: var(--shadow-sm);
    transition: all var(--transition-normal);
}
```

## 📱 Mobile Features

### Touch Interactions
- **Swipe Navigation**: Swipe left/right για navigation
- **Pull to Refresh**: Pull down για refresh content
- **Haptic Feedback**: Vibration feedback για interactions
- **Gesture Recognition**: Pinch, rotate, long press

### Mobile Optimizations
- **Touch Targets**: 44px minimum για accessibility
- **Auto-hiding Header**: Header κρύβεται κατά το scroll
- **Smooth Scrolling**: Native-like scrolling experience
- **Viewport Optimization**: Proper viewport meta tags

## ♿ Accessibility Features

### WCAG 2.1 AA Compliance
- **Color Contrast**: Proper contrast ratios για όλα τα colors
- **Focus Indicators**: Clear focus states για keyboard navigation
- **Screen Reader Support**: Proper ARIA labels και semantic HTML
- **Reduced Motion**: Respect user's motion preferences

### Keyboard Navigation
- **Tab Order**: Logical tab order για όλα τα interactive elements
- **Keyboard Shortcuts**: Theme toggle, navigation shortcuts
- **Escape Key**: Close modals και dropdowns
- **Arrow Keys**: Navigate tabs και menus

## 🚀 Performance

### Optimizations
- **CSS Variables**: Efficient theming χωρίς CSS generation
- **Minimal Bundle**: Optimized file sizes
- **Lazy Loading**: Components load on demand
- **Critical CSS**: Inline critical styles για fast rendering

### Best Practices
- **60fps Animations**: Smooth animations με requestAnimationFrame
- **Debounced Events**: Optimized event handling
- **Memory Management**: Proper cleanup για event listeners
- **Progressive Enhancement**: Works χωρίς JavaScript

## 🔮 Future Enhancements

### Planned Features
- **Component Library**: React/Vue component wrappers
- **Design Tokens**: Export για Figma/Sketch
- **Animation Library**: Advanced animation presets
- **Icon System**: Comprehensive icon library
- **Micro-interactions**: Advanced interaction patterns

### Roadmap
- **Q1 2024**: Component documentation
- **Q2 2024**: Advanced animations
- **Q3 2024**: Design tokens export
- **Q4 2024**: Component library

## 🤝 Contributing

### Guidelines
1. Follow Apple design principles
2. Maintain accessibility standards
3. Test on multiple devices
4. Document new components
5. Update this README

### Code Style
- Use CSS variables για theming
- Follow BEM naming convention
- Include proper comments
- Add JSDoc για JavaScript functions

## 📄 License

This design system is part of iTipster Pro and follows the same licensing terms.

---

**Created with ❤️ for iTipster.gr** 