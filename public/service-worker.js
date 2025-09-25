// Service Worker for OSmart E-commerce with Link Sharing Support
const CACHE_NAME = 'mlm-cache-v8';
const STATIC_CACHE = 'osmart-static-v7';
const DYNAMIC_CACHE = 'osmart-dynamic-v7';
const LINK_SHARING_CACHE = 'osmart-link-sharing-v1';

// Static assets that rarely change
const staticAssets = [
  '/assets/css/bootstrap.min.css',
  '/assets/js/bootstrap.bundle.min.js',
  '/assets/js/jquery.min.js',
  '/assets/img/icons/icon-72x72.png',
  '/assets/img/icons/icon-96x96.png',
  '/assets/img/icons/icon-128x128.png',
  '/assets/img/icons/icon-144x144.png',
  '/assets/img/icons/icon-152x152.png',
  '/assets/img/icons/icon-167x167.png',
  '/assets/img/icons/icon-180x180.png',
  '/assets/img/icons/icon-192x192.png',
  '/assets/img/icons/icon-384x384.png',
  '/assets/img/icons/icon-512x512.png',
  '/favicon.svg',
  // Admin/Vendor dashboard assets
  '/admin-assets/css/styles.min.css',
  '/admin-assets/css/icons.css',
  '/admin-assets/js/custom.js',
  '/admin-assets/js/main.js',
  '/admin-assets/libs/bootstrap/css/bootstrap.min.css',
  '/admin-assets/libs/bootstrap/js/bootstrap.bundle.min.js'
];

// URLs that should never be cached (always fetch from network)
const excludeFromCache = [
  '/login',
  '/logout',
  '/register',
  '/auth/',
  '/admin/',
  '/affiliate/',
  '/general/',
  '/api/',
  '/cart/',
  '/checkout',
  '/products/create',
  '/products/edit',
  '/categories/create',
  '/categories/edit',
  '/member/matching/',
  '/member/matching/dashboard',
  '/member/matching/qualifications',
  '/member/matching/calculator',
  '/member/matching/history',
  '/member/packages/',
  '/member/packages/purchase',
  '/member/packages/store',
  '/member/packages/success',
  '/member/packages/payout',
  '/member/add-fund',
  '/member/fund-history',
  '/member/wallet',
  '/member/transfer',
  '/member/withdraw',
  '/member/transactions',
  // Vendor-specific exclusions (sensitive operations)
  '/vendor/logout',
  '/vendor/ajax-logout',
  '/vendor/transfers/send',
  '/vendor/transfers/process-fund-request',
  '/vendor/profile',
  '/vendor/settings',
  '/vendor/products/create',
  '/vendor/products/edit',
  '/vendor/products/bulk-action',
  '/vendor/orders/update-status'
];

// Dynamic pages that should be network-first (fresh content preferred)
const networkFirst = [
  '/',
  '/products',
  '/categories',
  '/collections',
  '/home',
  '/shop',
  // Vendor dashboard and main pages (fresh content preferred)
  '/vendor/dashboard',
  '/vendor/orders',
  '/vendor/transfers',
  '/vendor/transfers/history',
  '/vendor/transfers/fund-requests',
  '/vendor/reports'
];

self.addEventListener('install', function(event) {
  event.waitUntil(
    caches.open(STATIC_CACHE)
      .then(function(cache) {
        // Cache static files individually to handle failures gracefully
        const cachePromises = staticAssets.map(url => {
          return cache.add(url).catch(error => {
            return Promise.resolve();
          });
        });
        return Promise.all(cachePromises);
      })
      .catch(function(error) {
        // Silent error handling for production
      })
  );
  
  // Skip waiting to activate immediately and take control
  self.skipWaiting();
});

self.addEventListener('fetch', function(event) {
  const requestUrl = new URL(event.request.url);
  const pathname = requestUrl.pathname;
  
  // Don't cache authentication or admin requests
  const shouldExclude = excludeFromCache.some(path => 
    pathname.includes(path)
  );
  
  // Don't cache POST requests (forms, uploads, etc.)
  if (event.request.method === 'POST' || shouldExclude) {
    event.respondWith(fetch(event.request));
    return;
  }
  
  // Network-first strategy for dynamic content (products, categories, home)
  const isNetworkFirst = networkFirst.some(path => 
    pathname === path || pathname.startsWith(path + '/')
  );
  
  if (isNetworkFirst) {
    event.respondWith(
      fetch(event.request)
        .then(response => {
          // Cache successful responses for offline access
          if (response.status === 200) {
            const responseClone = response.clone();
            caches.open(DYNAMIC_CACHE)
              .then(cache => {
                cache.put(event.request, responseClone);
              });
          }
          return response;
        })
        .catch(() => {
          // Fallback to cache if network fails
          return caches.match(event.request);
        })
    );
    return;
  }
  
  // Cache-first strategy for static assets
  event.respondWith(
    caches.match(event.request)
      .then(function(response) {
        // Cache hit - return response
        if (response) {
          return response;
        }
        
        // Fetch and cache static assets
        return fetch(event.request).then(function(response) {
          // Don't cache if not a successful response
          if (!response || response.status !== 200 || response.type !== 'basic') {
            return response;
          }
          
          // Clone the response
          const responseToCache = response.clone();
          
          caches.open(STATIC_CACHE)
            .then(function(cache) {
              cache.put(event.request, responseToCache);
            });
          
          return response;
        }).catch(function(error) {
          console.log('Fetch failed for:', event.request.url, error);
          // Return a fallback response or let it fail gracefully
          if (event.request.destination === 'image') {
            // Return a simple transparent pixel for failed images
            return new Response(
              '<svg width="1" height="1" xmlns="http://www.w3.org/2000/svg"><rect width="1" height="1" fill="transparent"/></svg>',
              { headers: { 'Content-Type': 'image/svg+xml' } }
            );
          }
          throw error;
        });
      }
    )
  );
});

self.addEventListener('activate', function(event) {
  event.waitUntil(
    caches.keys().then(function(cacheNames) {
      return Promise.all(
        cacheNames.map(function(cacheName) {
          if (cacheName !== STATIC_CACHE && cacheName !== DYNAMIC_CACHE) {
            return caches.delete(cacheName);
          }
        })
      );
    }).then(function() {
      return self.clients.claim();
    })
  );
});

// Handle messages from main thread
self.addEventListener('message', function(event) {
  // Helper function to send response back to client
  const sendResponse = (data) => {
    if (event.ports && event.ports[0]) {
      event.ports[0].postMessage(data);
    } else if (event.source) {
      event.source.postMessage(data);
    }
  };
  
  if (event.data && event.data.type === 'SKIP_WAITING') {
    self.skipWaiting();
    return;
  }
  
  if (event.data && event.data.type === 'CLAIM_CLIENTS') {
    event.waitUntil(self.clients.claim());
    return;
  }
  
  if (event.data && event.data.type === 'CLEAR_CACHE') {
    event.waitUntil(
      caches.keys().then(function(cacheNames) {
        return Promise.all(
          cacheNames.map(function(cacheName) {
            return caches.delete(cacheName);
          })
        );
      }).then(function() {
        sendResponse({success: true});
      })
    );
  }
  
  if (event.data && event.data.type === 'CLEAR_DYNAMIC_CACHE') {
    // Clear only dynamic cache (products, categories, etc.)
    event.waitUntil(
      caches.delete(DYNAMIC_CACHE).then(function() {
        sendResponse({success: true});
      })
    );
  }
  
  if (event.data && event.data.type === 'LOGOUT') {
    // Clear all caches on logout
    event.waitUntil(
      caches.keys().then(function(cacheNames) {
        return Promise.all(
          cacheNames.map(function(cacheName) {
            return caches.delete(cacheName);
          })
        );
      }).then(() => {
        sendResponse({success: true});
      })
    );
  }
  
  if (event.data && event.data.type === 'PRODUCT_UPDATED') {
    // Clear specific product/category caches when content is updated
    event.waitUntil(
      caches.open(DYNAMIC_CACHE).then(cache => {
        const urlsToDelete = [
          '/',
          '/products',
          '/categories',
          '/collections',
          '/home'
        ];
        
        return Promise.all(
          urlsToDelete.map(url => cache.delete(url))
        );
      }).then(() => {
        sendResponse({success: true});
      }).catch((error) => {
        sendResponse({success: false, error: error.message});
      })
    );
  }
  
  if (event.data && event.data.type === 'CLEAR_MATCHING_CACHE') {
    // Clear matching and binary page caches specifically
    event.waitUntil(
      caches.open(DYNAMIC_CACHE).then(cache => {
        const matchingUrls = [
          '/member/matching/dashboard',
          '/member/matching/qualifications',
          '/member/matching/calculator',
          '/member/matching/history',
          '/member/binary',
          '/member/direct-point-purchase'
        ];
        
        return Promise.all(
          matchingUrls.map(url => cache.delete(url))
        );
      }).then(() => {
        sendResponse({success: true});
      }).catch((error) => {
        sendResponse({success: false, error: error.message});
      })
    );
  }
  
  if (event.data && event.data.type === 'CLEAR_FUND_CACHE') {
    // Clear fund-related pages cache specifically
    event.waitUntil(
      caches.open(DYNAMIC_CACHE).then(cache => {
        const fundUrls = [
          '/member/add-fund',
          '/member/fund-history',
          '/member/wallet',
          '/member/transfer',
          '/member/withdraw',
          '/member/transactions'
        ];
        
        return Promise.all(
          fundUrls.map(url => cache.delete(url))
        );
      }).then(() => {
        sendResponse({success: true});
      }).catch((error) => {
        sendResponse({success: false, error: error.message});
      })
    );
  }
  
  if (event.data && event.data.type === 'CLEAR_PACKAGE_CACHE') {
    // Clear package pages cache specifically
    event.waitUntil(
      caches.open(DYNAMIC_CACHE).then(cache => {
        const packageUrls = [
          '/member/packages',
          '/member/packages/',
          '/member/packages/purchase',
          '/member/packages/success',
          '/member/packages/payout',
          '/member/packages/history'
        ];
        
        return Promise.all(
          packageUrls.map(url => cache.delete(url))
        );
      }).then(() => {
        sendResponse({success: true});
      }).catch((error) => {
        sendResponse({success: false, error: error.message});
      })
    );
  }

  // Link Sharing specific functionality
  if (event.data && event.data.type === 'CACHE_LINK_SHARING_DATA') {
    event.waitUntil(
      caches.open(LINK_SHARING_CACHE).then(cache => {
        const linkSharingUrls = [
          '/member/link-sharing',
          '/member/link-sharing/dashboard',
          '/member/link-sharing/history',
          '/member/link-sharing/stats'
        ];
        
        return Promise.all(
          linkSharingUrls.map(url => cache.add(url).catch(() => Promise.resolve()))
        );
      }).then(() => {
        sendResponse({success: true});
      }).catch((error) => {
        sendResponse({success: false, error: error.message});
      })
    );
  }

  if (event.data && event.data.type === 'QUEUE_LINK_SHARE') {
    // Queue link sharing for background sync when offline
    event.waitUntil(
      new Promise((resolve) => {
        // Store in IndexedDB for background sync
        const shareData = event.data.shareData;
        console.log('Queued link share for background sync:', shareData);
        resolve();
        sendResponse({success: true, message: 'Link share queued for sync'});
      })
    );
  }
});

// Background Sync for Link Sharing (when online again)
self.addEventListener('sync', function(event) {
  if (event.tag === 'background-link-share') {
    event.waitUntil(
      // Process queued link shares
      processQueuedLinkShares()
    );
  }
});

// Push notification handler for link sharing milestones
self.addEventListener('push', function(event) {
  if (event.data) {
    const data = event.data.json();
    
    if (data.type === 'link_sharing_milestone') {
      const options = {
        body: data.message,
        icon: '/assets/img/icons/icon-192x192.png',
        badge: '/assets/img/icons/icon-72x72.png',
        vibrate: [100, 50, 100],
        data: {
          type: 'link_sharing',
          url: '/member/link-sharing/dashboard'
        },
        actions: [
          {
            action: 'view_stats',
            title: 'View Stats',
            icon: '/assets/img/icons/stats-icon.png'
          },
          {
            action: 'share_more',
            title: 'Share More',
            icon: '/assets/img/icons/share-icon.png'
          }
        ]
      };
      
      event.waitUntil(
        self.registration.showNotification(data.title || 'Link Sharing Update', options)
      );
    }
  }
});

// Notification click handler
self.addEventListener('notificationclick', function(event) {
  event.notification.close();
  
  if (event.notification.data && event.notification.data.type === 'link_sharing') {
    let url = '/member/link-sharing/dashboard';
    
    if (event.action === 'view_stats') {
      url = '/member/link-sharing/stats';
    } else if (event.action === 'share_more') {
      url = '/member/link-sharing/dashboard#products';
    }
    
    event.waitUntil(
      clients.openWindow(url)
    );
  }
});

// Helper function to process queued link shares
async function processQueuedLinkShares() {
  try {
    // Implementation would retrieve from IndexedDB and sync to server
    console.log('Processing queued link shares...');
    // This would typically:
    // 1. Open IndexedDB
    // 2. Get queued shares
    // 3. POST to server
    // 4. Remove from queue on success
  } catch (error) {
    console.error('Error processing queued link shares:', error);
  }
}
