# PWA Icons Directory

This directory contains all the PWA icons and images required for the iTipster Pro Progressive Web App.

## Required Icons

### App Icons (PNG format)
- `icon-16x16.png` - Favicon
- `icon-32x32.png` - Favicon
- `icon-72x72.png` - Android icon
- `icon-96x96.png` - Android icon
- `icon-128x128.png` - Android icon
- `icon-144x144.png` - iOS icon
- `icon-152x152.png` - iOS icon
- `icon-192x192.png` - PWA icon
- `icon-384x384.png` - PWA icon
- `icon-512x512.png` - PWA icon

### Microsoft Tiles
- `icon-70x70.png` - Microsoft tile
- `icon-150x150.png` - Microsoft tile
- `icon-310x310.png` - Microsoft tile

### Shortcut Icons
- `shortcut-predictions.png` - Predictions shortcut
- `shortcut-odds.png` - Live odds shortcut
- `shortcut-dashboard.png` - Dashboard shortcut
- `shortcut-favorites.png` - Favorites shortcut

### Badge Icons
- `badge-72x72.png` - Notification badge

### Screenshots
- `screenshot-mobile-1.png` - Mobile dashboard screenshot
- `screenshot-mobile-2.png` - Mobile predictions screenshot
- `screenshot-desktop-1.png` - Desktop dashboard screenshot

### iOS Splash Screens
- `splash-640x1136.png` - iPhone 5/SE
- `splash-750x1334.png` - iPhone 6/7/8
- `splash-828x1792.png` - iPhone XR/11
- `splash-1125x2436.png` - iPhone X/XS/11 Pro
- `splash-1170x2532.png` - iPhone 12/13
- `splash-1179x2556.png` - iPhone 14
- `splash-1242x2208.png` - iPhone 6/7/8 Plus
- `splash-1242x2688.png` - iPhone XS Max/11 Pro Max
- `splash-1284x2778.png` - iPhone 12/13 Pro Max
- `splash-1290x2796.png` - iPhone 14 Pro Max

## Icon Specifications

### Design Guidelines
- **Style**: Apple-inspired, clean, modern design
- **Colors**: Primary blue (#007AFF) with white background
- **Shape**: Rounded corners (20% border radius)
- **Padding**: 10% padding around the icon
- **Format**: PNG with transparency support

### Technical Requirements
- **Format**: PNG
- **Color Space**: sRGB
- **Transparency**: Supported
- **Compression**: Optimized for web
- **File Size**: Under 50KB each

## Generation Tools

### Online Tools
- [PWA Builder](https://www.pwabuilder.com/) - Generate all PWA assets
- [App Icon Generator](https://appicon.co/) - Generate app icons
- [Favicon Generator](https://realfavicongenerator.net/) - Generate favicons
- [Splash Screen Generator](https://appsco.pe/developer/splash-screens) - Generate splash screens

### Design Software
- **Figma**: Create vector icons and export
- **Sketch**: Design and export icons
- **Adobe Illustrator**: Create scalable icons
- **Photoshop**: Edit and optimize images

### Command Line Tools
```bash
# Using ImageMagick
convert icon-512x512.png -resize 192x192 icon-192x192.png
convert icon-512x512.png -resize 144x144 icon-144x144.png

# Using Sharp (Node.js)
npx sharp icon-512x512.png resize 192 192 icon-192x192.png
```

## Icon Design

### Base Icon Design
The iTipster Pro icon should feature:
- **Symbol**: Sports-related icon (football, chart, or trophy)
- **Typography**: Clean, modern font for "iTipster"
- **Colors**: Blue gradient (#007AFF to #0056CC)
- **Background**: White or transparent
- **Effects**: Subtle shadow and glow

### Brand Guidelines
- **Primary Color**: #007AFF (Apple Blue)
- **Secondary Color**: #0056CC (Dark Blue)
- **Accent Color**: #4ADE80 (Success Green)
- **Background**: #FFFFFF (White)
- **Text**: #1F2937 (Dark Gray)

## Testing

### PWA Testing
1. **Lighthouse Audit**: Run PWA audit in Chrome DevTools
2. **Install Test**: Test app installation on different devices
3. **Icon Display**: Verify icons display correctly in app launcher
4. **Splash Screen**: Test splash screen on iOS devices

### Browser Testing
- **Chrome**: Full PWA support
- **Safari**: iOS home screen icons
- **Firefox**: Progressive enhancement
- **Edge**: Windows tile support

## Deployment

### Production Checklist
- [ ] All icon sizes generated
- [ ] Icons optimized for web
- [ ] Manifest.json updated with correct paths
- [ ] Browserconfig.xml updated
- [ ] Meta tags include correct icon paths
- [ ] Icons tested on multiple devices

### CDN Integration
Consider hosting icons on a CDN for better performance:
```html
<link rel="icon" href="https://cdn.itipster.gr/icons/icon-32x32.png">
<link rel="apple-touch-icon" href="https://cdn.itipster.gr/icons/icon-192x192.png">
```

## Maintenance

### Regular Updates
- **Quarterly Review**: Update icons if brand changes
- **Performance Check**: Monitor icon loading performance
- **Compatibility**: Test with new browser versions
- **Analytics**: Track icon usage and performance

### Version Control
- Keep original design files in version control
- Document icon changes and updates
- Maintain backup of all icon assets
- Use semantic versioning for icon updates

## Resources

### Documentation
- [PWA Icon Guidelines](https://web.dev/app-icon/)
- [iOS Icon Guidelines](https://developer.apple.com/design/human-interface-guidelines/ios/icons-and-images/app-icon/)
- [Android Icon Guidelines](https://developer.android.com/guide/practices/ui_guidelines/icon_design)

### Tools
- [PWA Builder](https://www.pwabuilder.com/)
- [App Icon Generator](https://appicon.co/)
- [Favicon Generator](https://realfavicongenerator.net/)
- [Splash Screen Generator](https://appsco.pe/developer/splash-screens)

### Design Resources
- [Material Design Icons](https://material.io/resources/icons/)
- [Feather Icons](https://feathericons.com/)
- [Heroicons](https://heroicons.com/)
- [Font Awesome](https://fontawesome.com/) 