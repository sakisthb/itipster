<?php
/**
 * iTipster Pro - Offline Page Template
 * Displayed when users are offline
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>iTipster Pro - Offline</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
            padding: 20px;
        }
        
        .offline-container {
            max-width: 400px;
            width: 100%;
        }
        
        .offline-icon {
            font-size: 80px;
            margin-bottom: 24px;
            opacity: 0.8;
        }
        
        .offline-title {
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 16px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }
        
        .offline-subtitle {
            font-size: 18px;
            margin-bottom: 32px;
            opacity: 0.9;
            line-height: 1.5;
        }
        
        .offline-features {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 32px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .offline-features h3 {
            font-size: 20px;
            margin-bottom: 16px;
            font-weight: 600;
        }
        
        .offline-features ul {
            list-style: none;
            text-align: left;
        }
        
        .offline-features li {
            padding: 8px 0;
            font-size: 16px;
            opacity: 0.9;
            position: relative;
            padding-left: 24px;
        }
        
        .offline-features li::before {
            content: '✓';
            position: absolute;
            left: 0;
            color: #4ade80;
            font-weight: bold;
            font-size: 18px;
        }
        
        .retry-button {
            background: rgba(255, 255, 255, 0.2);
            border: 2px solid rgba(255, 255, 255, 0.3);
            color: white;
            padding: 16px 32px;
            border-radius: 12px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
            margin-bottom: 16px;
            width: 100%;
        }
        
        .retry-button:hover {
            background: rgba(255, 255, 255, 0.3);
            border-color: rgba(255, 255, 255, 0.5);
            transform: translateY(-2px);
        }
        
        .offline-info {
            font-size: 14px;
            opacity: 0.7;
            line-height: 1.4;
        }
        
        .connection-status {
            position: fixed;
            top: 20px;
            right: 20px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            padding: 8px 16px;
            font-size: 14px;
            font-weight: 500;
        }
        
        .connection-status.connected {
            background: rgba(74, 222, 128, 0.2);
            border-color: rgba(74, 222, 128, 0.3);
        }
        
        @media (max-width: 480px) {
            .offline-title {
                font-size: 28px;
            }
            
            .offline-subtitle {
                font-size: 16px;
            }
            
            .offline-features {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="connection-status" id="connection-status">
        📡 Offline
    </div>
    
    <div class="offline-container">
        <div class="offline-icon">📱</div>
        
        <h1 class="offline-title">You're Offline</h1>
        
        <p class="offline-subtitle">
            Don't worry! iTipster Pro works offline too. You can still access your saved predictions and favorites.
        </p>
        
        <div class="offline-features">
            <h3>Available Offline:</h3>
            <ul>
                <li>View saved predictions</li>
                <li>Access your favorites</li>
                <li>Check your profile</li>
                <li>Review past results</li>
                <li>Browse cached content</li>
            </ul>
        </div>
        
        <button class="retry-button" onclick="checkConnection()">
            🔄 Try Again
        </button>
        
        <p class="offline-info">
            Your data will sync automatically when you're back online. 
            Make sure you have a stable internet connection.
        </p>
    </div>
    
    <script>
        // Check connection status
        function checkConnection() {
            const statusElement = document.getElementById('connection-status');
            const retryButton = document.querySelector('.retry-button');
            
            if (navigator.onLine) {
                statusElement.textContent = '✅ Online';
                statusElement.className = 'connection-status connected';
                retryButton.textContent = '🔄 Redirecting...';
                
                // Redirect to main page after a short delay
                setTimeout(() => {
                    window.location.href = '/';
                }, 1000);
            } else {
                statusElement.textContent = '📡 Offline';
                statusElement.className = 'connection-status';
                retryButton.textContent = '🔄 Try Again';
                
                // Show error message
                alert('Still offline. Please check your internet connection.');
            }
        }
        
        // Listen for online/offline events
        window.addEventListener('online', () => {
            const statusElement = document.getElementById('connection-status');
            statusElement.textContent = '✅ Online';
            statusElement.className = 'connection-status connected';
            
            // Auto-redirect when back online
            setTimeout(() => {
                window.location.href = '/';
            }, 2000);
        });
        
        window.addEventListener('offline', () => {
            const statusElement = document.getElementById('connection-status');
            statusElement.textContent = '📡 Offline';
            statusElement.className = 'connection-status';
        });
        
        // Check connection on page load
        document.addEventListener('DOMContentLoaded', () => {
            if (navigator.onLine) {
                const statusElement = document.getElementById('connection-status');
                statusElement.textContent = '✅ Online';
                statusElement.className = 'connection-status connected';
                
                // Redirect if already online
                setTimeout(() => {
                    window.location.href = '/';
                }, 1000);
            }
        });
        
        // Service Worker registration
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/sw.js')
                .then(registration => {
                    console.log('Service Worker registered:', registration);
                })
                .catch(error => {
                    console.log('Service Worker registration failed:', error);
                });
        }
    </script>
</body>
</html> 