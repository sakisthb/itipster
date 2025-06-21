/**
 * iTipster Pro - Service Worker
 * Handles caching, offline support, background sync, and push notifications
 */

const CACHE_NAME = 'itipster-pro-v1.0.0';
const STATIC_CACHE = 'itipster-static-v1.0.0';
const DYNAMIC_CACHE = 'itipster-dynamic-v1.0.0';
const API_CACHE = 'itipster-api-v1.0.0';

// Cache strategies
const CACHE_STRATEGIES = {
  STATIC: 'cache-first',
  DYNAMIC: 'stale-while-revalidate',
  API: 'network-first',
  IMAGES: 'cache-first'
};

// Critical resources to cache immediately
const CRITICAL_RESOURCES = [
  '/',
  '/predictions/',
  '/dashboard/',
  '/login/',
  '/offline/',
  '/manifest.json'
];

// Static assets to cache
const STATIC_ASSETS = [
  '/assets/css/design-system.css',
  '/assets/css/theme-manager.css',
  '/assets/css/components.css',
  '/assets/css/mobile-responsive.css',
  '/assets/js/theme-manager.js',
  '/assets/js/ui-interactions.js',
  '/assets/js/pwa-manager.js',
  '/assets/js/frontend-live.js',
  '/assets/images/pwa/icon-192x192.png',
  '/assets/images/pwa/icon-512x512.png'
];

// API endpoints to cache
const API_ENDPOINTS = [
  '/wp-json/wp/v2/posts',
  '/wp-json/itipster/v1/predictions',
  '/wp-json/itipster/v1/fixtures',
  '/wp-json/itipster/v1/odds'
];

// Install event - cache critical resources
self.addEventListener('install', (event) => {
  console.log('[SW] Installing service worker...');
  
  event.waitUntil(
    Promise.all([
      // Cache critical resources
      caches.open(STATIC_CACHE).then((cache) => {
        console.log('[SW] Caching critical resources');
        return cache.addAll(CRITICAL_RESOURCES);
      }),
      
      // Cache static assets
      caches.open(STATIC_CACHE).then((cache) => {
        console.log('[SW] Caching static assets');
        return cache.addAll(STATIC_ASSETS);
      }),
      
      // Skip waiting to activate immediately
      self.skipWaiting()
    ])
  );
});

// Activate event - clean up old caches
self.addEventListener('activate', (event) => {
  console.log('[SW] Activating service worker...');
  
  event.waitUntil(
    Promise.all([
      // Clean up old caches
      caches.keys().then((cacheNames) => {
        return Promise.all(
          cacheNames.map((cacheName) => {
            if (cacheName !== STATIC_CACHE && 
                cacheName !== DYNAMIC_CACHE && 
                cacheName !== API_CACHE) {
              console.log('[SW] Deleting old cache:', cacheName);
              return caches.delete(cacheName);
            }
          })
        );
      }),
      
      // Claim all clients
      self.clients.claim()
    ])
  );
});

// Fetch event - handle different caching strategies
self.addEventListener('fetch', (event) => {
  const { request } = event;
  const url = new URL(request.url);
  
  // Skip non-GET requests
  if (request.method !== 'GET') {
    return;
  }
  
  // Handle different types of requests
  if (isStaticAsset(request)) {
    event.respondWith(cacheFirst(request, STATIC_CACHE));
  } else if (isAPIRequest(request)) {
    event.respondWith(networkFirst(request, API_CACHE));
  } else if (isImage(request)) {
    event.respondWith(cacheFirst(request, DYNAMIC_CACHE));
  } else {
    event.respondWith(staleWhileRevalidate(request, DYNAMIC_CACHE));
  }
});

// Background sync for offline actions
self.addEventListener('sync', (event) => {
  console.log('[SW] Background sync triggered:', event.tag);
  
  if (event.tag === 'background-sync-predictions') {
    event.waitUntil(syncPredictions());
  } else if (event.tag === 'background-sync-favorites') {
    event.waitUntil(syncFavorites());
  } else if (event.tag === 'background-sync-user-data') {
    event.waitUntil(syncUserData());
  }
});

// Push notification event
self.addEventListener('push', (event) => {
  console.log('[SW] Push notification received');
  
  if (event.data) {
    const data = event.data.json();
    const options = {
      body: data.body,
      icon: '/assets/images/pwa/icon-192x192.png',
      badge: '/assets/images/pwa/badge-72x72.png',
      image: data.image,
      data: data.data,
      actions: data.actions || [],
      requireInteraction: data.requireInteraction || false,
      tag: data.tag || 'default',
      renotify: data.renotify || false,
      silent: data.silent || false,
      vibrate: data.vibrate || [200, 100, 200],
      sound: data.sound,
      dir: 'ltr',
      lang: 'en',
      timestamp: Date.now()
    };
    
    event.waitUntil(
      self.registration.showNotification(data.title, options)
    );
  }
});

// Notification click event
self.addEventListener('notificationclick', (event) => {
  console.log('[SW] Notification clicked:', event.notification.tag);
  
  event.notification.close();
  
  if (event.action) {
    // Handle notification actions
    handleNotificationAction(event.action, event.notification.data);
  } else {
    // Default action - open the app
    event.waitUntil(
      clients.openWindow('/')
    );
  }
});

// Message event for communication with main thread
self.addEventListener('message', (event) => {
  console.log('[SW] Message received:', event.data);
  
  if (event.data && event.data.type) {
    switch (event.data.type) {
      case 'SKIP_WAITING':
        self.skipWaiting();
        break;
      case 'GET_VERSION':
        event.ports[0].postMessage({ version: CACHE_NAME });
        break;
      case 'CLEAR_CACHE':
        clearAllCaches();
        break;
      case 'UPDATE_CACHE':
        updateCache(event.data.url, event.data.response);
        break;
    }
  }
});

// Cache strategies
async function cacheFirst(request, cacheName) {
  const cache = await caches.open(cacheName);
  const cachedResponse = await cache.match(request);
  
  if (cachedResponse) {
    return cachedResponse;
  }
  
  try {
    const networkResponse = await fetch(request);
    if (networkResponse.ok) {
      cache.put(request, networkResponse.clone());
    }
    return networkResponse;
  } catch (error) {
    // Return offline page for navigation requests
    if (request.destination === 'document') {
      return cache.match('/offline/');
    }
    throw error;
  }
}

async function networkFirst(request, cacheName) {
  try {
    const networkResponse = await fetch(request);
    const cache = await caches.open(cacheName);
    cache.put(request, networkResponse.clone());
    return networkResponse;
  } catch (error) {
    const cache = await caches.open(cacheName);
    const cachedResponse = await cache.match(request);
    
    if (cachedResponse) {
      return cachedResponse;
    }
    
    // Return offline data for API requests
    return new Response(JSON.stringify({
      error: 'offline',
      message: 'You are currently offline. Please check your connection.',
      timestamp: Date.now()
    }), {
      headers: { 'Content-Type': 'application/json' }
    });
  }
}

async function staleWhileRevalidate(request, cacheName) {
  const cache = await caches.open(cacheName);
  const cachedResponse = await cache.match(request);
  
  const fetchPromise = fetch(request).then((networkResponse) => {
    if (networkResponse.ok) {
      cache.put(request, networkResponse.clone());
    }
    return networkResponse;
  }).catch(() => {
    // Network failed, return cached response if available
    return cachedResponse;
  });
  
  return cachedResponse || fetchPromise;
}

// Helper functions
function isStaticAsset(request) {
  return STATIC_ASSETS.some(asset => request.url.includes(asset)) ||
         request.url.includes('/assets/css/') ||
         request.url.includes('/assets/js/') ||
         request.url.includes('/assets/fonts/');
}

function isAPIRequest(request) {
  return API_ENDPOINTS.some(endpoint => request.url.includes(endpoint)) ||
         request.url.includes('/wp-json/') ||
         request.url.includes('/api/');
}

function isImage(request) {
  return request.destination === 'image' ||
         /\.(jpg|jpeg|png|gif|webp|svg)$/i.test(request.url);
}

// Background sync functions
async function syncPredictions() {
  try {
    const response = await fetch('/wp-json/itipster/v1/predictions', {
      method: 'GET',
      headers: {
        'Content-Type': 'application/json'
      }
    });
    
    if (response.ok) {
      const predictions = await response.json();
      
      // Store in IndexedDB for offline access
      const db = await openDB();
      await db.put('predictions', predictions);
      
      console.log('[SW] Predictions synced successfully');
    }
  } catch (error) {
    console.error('[SW] Failed to sync predictions:', error);
  }
}

async function syncFavorites() {
  try {
    const db = await openDB();
    const offlineFavorites = await db.getAll('offline-favorites');
    
    for (const favorite of offlineFavorites) {
      await fetch('/wp-json/itipster/v1/favorites', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-WP-Nonce': favorite.nonce
        },
        body: JSON.stringify(favorite.data)
      });
    }
    
    // Clear offline favorites after sync
    await db.clear('offline-favorites');
    
    console.log('[SW] Favorites synced successfully');
  } catch (error) {
    console.error('[SW] Failed to sync favorites:', error);
  }
}

async function syncUserData() {
  try {
    const db = await openDB();
    const offlineActions = await db.getAll('offline-actions');
    
    for (const action of offlineActions) {
      await fetch(action.url, {
        method: action.method,
        headers: action.headers,
        body: action.body
      });
    }
    
    // Clear offline actions after sync
    await db.clear('offline-actions');
    
    console.log('[SW] User data synced successfully');
  } catch (error) {
    console.error('[SW] Failed to sync user data:', error);
  }
}

// IndexedDB helper
async function openDB() {
  return new Promise((resolve, reject) => {
    const request = indexedDB.open('itipster-pwa', 1);
    
    request.onerror = () => reject(request.error);
    request.onsuccess = () => resolve(request.result);
    
    request.onupgradeneeded = (event) => {
      const db = event.target.result;
      
      // Create object stores
      if (!db.objectStoreNames.contains('predictions')) {
        db.createObjectStore('predictions', { keyPath: 'id' });
      }
      if (!db.objectStoreNames.contains('offline-favorites')) {
        db.createObjectStore('offline-favorites', { keyPath: 'id', autoIncrement: true });
      }
      if (!db.objectStoreNames.contains('offline-actions')) {
        db.createObjectStore('offline-actions', { keyPath: 'id', autoIncrement: true });
      }
    };
  });
}

// Notification action handler
function handleNotificationAction(action, data) {
  switch (action) {
    case 'view_prediction':
      clients.openWindow(`/predictions/${data.prediction_id}/`);
      break;
    case 'view_odds':
      clients.openWindow(`/live-odds/${data.fixture_id}/`);
      break;
    case 'dismiss':
      // Just close the notification
      break;
    default:
      clients.openWindow('/');
  }
}

// Cache management
async function clearAllCaches() {
  const cacheNames = await caches.keys();
  await Promise.all(
    cacheNames.map(cacheName => caches.delete(cacheName))
  );
  console.log('[SW] All caches cleared');
}

async function updateCache(url, response) {
  const cache = await caches.open(DYNAMIC_CACHE);
  await cache.put(url, response);
  console.log('[SW] Cache updated for:', url);
}

// Periodic background sync (if supported)
if ('periodicSync' in self.registration) {
  self.addEventListener('periodicsync', (event) => {
    if (event.tag === 'periodic-predictions-update') {
      event.waitUntil(syncPredictions());
    }
  });
}

console.log('[SW] Service worker loaded successfully'); 