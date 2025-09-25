// Service Worker Register 
if ('serviceWorker' in navigator) {
  window.addEventListener('load', function () {
    navigator.serviceWorker.register('/service-worker.js')
      .then(registration => {
        // Store service worker registration globally for logout handling
        window.swRegistration = registration;
        
        // Check current state and force immediate control
        const ensureControl = () => {
          if (!navigator.serviceWorker.controller && registration.active) {
            registration.active.postMessage({ type: 'CLAIM_CLIENTS' });
            
            // If still no controller after a brief delay, reload the page
            setTimeout(() => {
              if (!navigator.serviceWorker.controller) {
                window.location.reload();
              }
            }, 1000);
          }
        };
        
        // Wait for service worker to become active and take control
        if (registration.installing) {
          registration.installing.addEventListener('statechange', function() {
            if (this.state === 'activated') {
              ensureControl();
            }
          });
        } else if (registration.waiting) {
          // If there's a waiting service worker, activate it immediately
          registration.waiting.postMessage({ type: 'SKIP_WAITING' });
        } else if (registration.active) {
          ensureControl();
        }
        
        // Listen for service worker updates
        registration.addEventListener('updatefound', () => {
          const newWorker = registration.installing;
          newWorker.addEventListener('statechange', () => {
            if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
              // New service worker available - show update notification
              showUpdateNotification(newWorker);
            }
          });
        });
      })
      .catch(err => {
        // Silent error handling for production
      });
  });
  
  // Listen for when service worker takes control
  navigator.serviceWorker.addEventListener('controllerchange', () => {
    // If this is a page reload due to service worker activation, don't reload again
    if (!window.swControllerChanged) {
      window.swControllerChanged = true;
      // Hide updating message and show success
      hideUpdatingMessage();
      showUpdateSuccessMessage();
    }
  });
}

// Function to show update notification
function showUpdateNotification(newWorker) {
  // Create update notification if it doesn't exist
  let updateNotification = document.getElementById('updateNotification');
  
  if (!updateNotification) {
    updateNotification = document.createElement('div');
    updateNotification.id = 'updateNotification';
    updateNotification.className = 'update-notification';
    updateNotification.style.cssText = `
      position: fixed;
      top: 20px;
      right: 20px;
      background: #007bff;
      color: white;
      padding: 15px 20px;
      border-radius: 8px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.2);
      z-index: 10000;
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
      font-size: 14px;
      max-width: 300px;
      animation: slideIn 0.3s ease-out;
    `;
    
    updateNotification.innerHTML = `
      <div style="margin-bottom: 10px;">
        <strong>App Update Available!</strong><br>
        A new version is ready to install.
      </div>
      <div style="display: flex; gap: 10px;">
        <button id="updateNow" style="
          background: white;
          color: #007bff;
          border: none;
          padding: 8px 16px;
          border-radius: 4px;
          cursor: pointer;
          font-weight: 600;
          flex: 1;
        ">Update Now</button>
        <button id="updateLater" style="
          background: transparent;
          color: white;
          border: 1px solid white;
          padding: 8px 16px;
          border-radius: 4px;
          cursor: pointer;
          flex: 1;
        ">Later</button>
      </div>
    `;
    
    // Add animation keyframes
    if (!document.getElementById('updateAnimationStyles')) {
      const style = document.createElement('style');
      style.id = 'updateAnimationStyles';
      style.textContent = `
        @keyframes slideIn {
          from { transform: translateX(100%); opacity: 0; }
          to { transform: translateX(0); opacity: 1; }
        }
        @keyframes slideOut {
          from { transform: translateX(0); opacity: 1; }
          to { transform: translateX(100%); opacity: 0; }
        }
        .update-notification.hiding {
          animation: slideOut 0.3s ease-out forwards;
        }
        @keyframes spin {
          0% { transform: rotate(0deg); }
          100% { transform: rotate(360deg); }
        }
      `;
      document.head.appendChild(style);
    }
    
    document.body.appendChild(updateNotification);
  }
  
  // Handle update now button
  document.getElementById('updateNow').onclick = () => {
    // Activate the new service worker
    newWorker.postMessage({ type: 'SKIP_WAITING' });
    hideUpdateNotification();
    
    // Show updating message
    showUpdatingMessage();
  };
  
  // Handle update later button
  document.getElementById('updateLater').onclick = () => {
    hideUpdateNotification();
    // Auto-show again after 5 minutes
    setTimeout(() => {
      if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
        showUpdateNotification(newWorker);
      }
    }, 5 * 60 * 1000);
  };
}

// Function to hide update notification
function hideUpdateNotification() {
  const updateNotification = document.getElementById('updateNotification');
  if (updateNotification) {
    updateNotification.classList.add('hiding');
    setTimeout(() => {
      updateNotification.remove();
    }, 300);
  }
}

// Function to show updating message
function showUpdatingMessage() {
  let updatingMessage = document.getElementById('updatingMessage');
  
  if (!updatingMessage) {
    updatingMessage = document.createElement('div');
    updatingMessage.id = 'updatingMessage';
    updatingMessage.style.cssText = `
      position: fixed;
      top: 20px;
      right: 20px;
      background: #28a745;
      color: white;
      padding: 15px 20px;
      border-radius: 8px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.2);
      z-index: 10000;
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
      font-size: 14px;
      animation: slideIn 0.3s ease-out;
    `;
    
    updatingMessage.innerHTML = `
      <div style="display: flex; align-items: center; gap: 10px;">
        <div style="
          width: 20px;
          height: 20px;
          border: 2px solid white;
          border-top: 2px solid transparent;
          border-radius: 50%;
          animation: spin 1s linear infinite;
        "></div>
        <span><strong>Updating app...</strong><br>Please wait a moment</span>
      </div>
    `;
    
    document.body.appendChild(updatingMessage);
  }
}

// Function to hide updating message
function hideUpdatingMessage() {
  const updatingMessage = document.getElementById('updatingMessage');
  if (updatingMessage) {
    updatingMessage.classList.add('hiding');
    setTimeout(() => {
      updatingMessage.remove();
    }, 300);
  }
}

// Function to show update success message
function showUpdateSuccessMessage() {
  let successMessage = document.getElementById('updateSuccessMessage');
  
  if (!successMessage) {
    successMessage = document.createElement('div');
    successMessage.id = 'updateSuccessMessage';
    successMessage.style.cssText = `
      position: fixed;
      top: 20px;
      right: 20px;
      background: #28a745;
      color: white;
      padding: 15px 20px;
      border-radius: 8px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.2);
      z-index: 10000;
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
      font-size: 14px;
      animation: slideIn 0.3s ease-out;
    `;
    
    successMessage.innerHTML = `
      <div style="display: flex; align-items: center; gap: 10px;">
        <span style="font-size: 20px;">✅</span>
        <span><strong>App Updated!</strong><br>You're now using the latest version</span>
      </div>
    `;
    
    document.body.appendChild(successMessage);
    
    // Auto-hide after 3 seconds
    setTimeout(() => {
      successMessage.classList.add('hiding');
      setTimeout(() => {
        successMessage.remove();
      }, 300);
    }, 3000);
  }
}

// Function to clear service worker cache on logout
window.clearServiceWorkerCache = function() {
  return new Promise((resolve, reject) => {
    if ('serviceWorker' in navigator && window.swRegistration) {
      // Send logout message to service worker
      navigator.serviceWorker.controller?.postMessage({ type: 'LOGOUT' });
      
      // Also try to clear caches directly
      if ('caches' in window) {
        caches.keys().then(cacheNames => {
          return Promise.all(
            cacheNames.map(cacheName => caches.delete(cacheName))
          );
        }).then(() => {
          resolve();
        }).catch(error => {
          resolve(); // Don't fail logout on cache clear error
        });
      } else {
        resolve();
      }
    } else {
      resolve();
    }
  });
};

// Function to wait for service worker controller with enhanced detection
function waitForServiceWorkerController(maxAttempts = 5, delay = 200) {
  return new Promise((resolve, reject) => {
    let attempts = 0;
    
    // First check if controller is already available
    if (navigator.serviceWorker.controller) {
      resolve(navigator.serviceWorker.controller);
      return;
    }
    
    // Set up controller change listener for immediate detection
    const onControllerChange = () => {
      navigator.serviceWorker.removeEventListener('controllerchange', onControllerChange);
      resolve(navigator.serviceWorker.controller);
    };
    
    navigator.serviceWorker.addEventListener('controllerchange', onControllerChange);
    
    function checkController() {
      attempts++;
      
      if (navigator.serviceWorker.controller) {
        navigator.serviceWorker.removeEventListener('controllerchange', onControllerChange);
        resolve(navigator.serviceWorker.controller);
      } else if (attempts >= maxAttempts) {
        navigator.serviceWorker.removeEventListener('controllerchange', onControllerChange);
        // Don't reject - we'll use fallback methods
        resolve(null);
      } else {
        setTimeout(checkController, delay);
      }
    }
    
    // Start checking after a short delay to let the service worker initialize
    setTimeout(checkController, 100);
  });
}

// Function to clear dynamic cache when products are updated
window.clearDynamicCache = function() {
  return new Promise(async (resolve, reject) => {
    if ('serviceWorker' in navigator) {
      try {
        let controller = null;
        
        try {
          controller = await waitForServiceWorkerController();
        } catch (error) {
          // Fallback to direct cache manipulation
        }
        
        if (controller) {
          // Use service worker for cache management
          const channel = new MessageChannel();
          channel.port1.onmessage = function(event) {
            if (event.data.success) {
              resolve();
            } else {
              reject(new Error(event.data.error || 'Failed to clear cache'));
            }
          };
          
          controller.postMessage(
            { type: 'CLEAR_DYNAMIC_CACHE' },
            [channel.port2]
          );
          
          // Timeout fallback
          setTimeout(() => {
            resolve(); // Assume success if no response
          }, 5000);
        } else {
          // Direct cache manipulation fallback
          try {
            if ('caches' in window) {
              await caches.delete('osmart-dynamic-v4');
            }
            resolve();
          } catch (error) {
            reject(error);
          }
        }
      } catch (error) {
        reject(error);
      }
    } else {
      reject(new Error('Service Worker not supported'));
    }
  });
};

// Function to check for updates periodically
function checkForUpdates() {
  if ('serviceWorker' in navigator) {
    navigator.serviceWorker.getRegistration().then(registration => {
      if (registration) {
        registration.update();
      }
    });
  }
}

// Check for updates every 30 minutes when app is active
setInterval(checkForUpdates, 30 * 60 * 1000);

// Check for updates when page becomes visible
document.addEventListener('visibilitychange', () => {
  if (!document.hidden) {
    setTimeout(checkForUpdates, 1000);
  }
});

// Function to refresh product-related caches
window.refreshProductCache = function() {
  return new Promise(async (resolve, reject) => {
    if ('serviceWorker' in navigator) {
      try {
        // Wait for service worker to be ready
        await navigator.serviceWorker.ready;
        
        // Try to get controller, wait if necessary
        let controller;
        try {
          controller = await waitForServiceWorkerController();
        } catch (error) {
          // Fallback to direct cache operations
          if ('caches' in window) {
            try {
              // Clear specific URLs from dynamic cache
              const cache = await caches.open('osmart-dynamic-v4');
              const urlsToDelete = ['/', '/products', '/categories', '/collections', '/home'];
              await Promise.all(urlsToDelete.map(url => cache.delete(url)));
              resolve({ success: true, method: 'direct' });
              return;
            } catch (cacheError) {
              resolve({ success: false, reason: 'Direct cache refresh failed', error: cacheError });
              return;
            }
          }
          resolve({ success: false, reason: 'No cache refresh method available' });
          return;
        }
        
        // Create a message channel for two-way communication
        const messageChannel = new MessageChannel();
        
        messageChannel.port1.onmessage = function(event) {
          if (event.data.success) {
            resolve(event.data);
          } else {
            reject(new Error(event.data.error || 'Failed to refresh cache'));
          }
        };
        
        controller.postMessage({
          type: 'PRODUCT_UPDATED'
        }, [messageChannel.port2]);
        
        // Fallback timeout
        setTimeout(() => {
          resolve({ success: true, fallback: true });
        }, 3000);
        
      } catch (error) {
        resolve({ success: false, reason: 'Service worker not ready', error });
      }
    } else {
      resolve({ success: false, reason: 'Service worker not supported' });
    }
  });
};

// PWA Installation - Smart Logic
let deferredPrompt;
let isAppInstalled = false;

// Check if app is already installed
function checkAppInstallation() {
    // Check if running in standalone mode (installed PWA)
    if (window.matchMedia('(display-mode: standalone)').matches || 
        window.navigator.standalone === true ||
        document.referrer.includes('android-app://')) {
        isAppInstalled = true;
        return true;
    }
    return false;
}

// Check user preferences from localStorage
function getUserPWAPreference() {
    const dismissed = localStorage.getItem('pwa-install-dismissed');
    const dismissedTime = localStorage.getItem('pwa-install-dismissed-time');
    
    if (dismissed === 'permanent') {
        return 'permanent';
    }
    
    if (dismissed === 'later' && dismissedTime) {
        const dismissTime = new Date(dismissedTime);
        const now = new Date();
        const daysSinceDismiss = (now - dismissTime) / (1000 * 60 * 60 * 24);
        
        // Show again after 7 days if dismissed with "Maybe Later"
        if (daysSinceDismiss < 7) {
            return 'later';
        } else {
            // Clear the temporary dismissal
            localStorage.removeItem('pwa-install-dismissed');
            localStorage.removeItem('pwa-install-dismissed-time');
            return 'show';
        }
    }
    
    return 'show';
}

// Show PWA install prompt
function showPWAInstallPrompt() {
    const installWrap = document.getElementById('installWrap');
    if (installWrap) {
        installWrap.style.display = 'block';
        installWrap.classList.add('show');
        
        // Animate in
        setTimeout(() => {
            installWrap.style.opacity = '1';
            installWrap.style.transform = 'translateY(0)';
        }, 100);
    }
}

// Hide PWA install prompt
function hidePWAInstallPrompt() {
    const installWrap = document.getElementById('installWrap');
    if (installWrap) {
        installWrap.style.opacity = '0';
        installWrap.style.transform = 'translateY(-20px)';
        
        setTimeout(() => {
            installWrap.style.display = 'none';
            installWrap.classList.remove('show');
        }, 300);
    }
}

// Dismiss PWA prompt with options
function dismissPWAPrompt(type = 'permanent') {
    if (type === 'later') {
        localStorage.setItem('pwa-install-dismissed', 'later');
        localStorage.setItem('pwa-install-dismissed-time', new Date().toISOString());
    } else {
        localStorage.setItem('pwa-install-dismissed', 'permanent');
    }
    
    hidePWAInstallPrompt();
}

// Make dismissPWAPrompt globally available
window.dismissPWAPrompt = dismissPWAPrompt;

// For testing - manually trigger PWA prompt
window.testPWAPrompt = function() {
    console.log('🧪 Manually testing PWA prompt');
    localStorage.removeItem('pwa-install-dismissed');
    localStorage.removeItem('pwa-install-dismissed-time');
    showPWAInstallPrompt();
};

// For testing - reset PWA preferences
window.resetPWAPreferences = function() {
    console.log('🔄 Resetting PWA preferences');
    localStorage.removeItem('pwa-install-dismissed');
    localStorage.removeItem('pwa-install-dismissed-time');
    console.log('✅ PWA preferences cleared - reload page to see prompt');
};

// For testing - check PWA status
window.checkPWAStatus = function() {
    console.log('📊 PWA Status Check:');
    console.log('- App installed:', checkAppInstallation());
    console.log('- User preference:', getUserPWAPreference());
    console.log('- Deferred prompt available:', !!deferredPrompt);
    console.log('- installWrap element exists:', !!document.getElementById('installWrap'));
    
    // Show localStorage values for debugging
    const dismissed = localStorage.getItem('pwa-install-dismissed');
    const dismissedTime = localStorage.getItem('pwa-install-dismissed-time');
    console.log('- localStorage dismissed:', dismissed);
    console.log('- localStorage dismissed time:', dismissedTime);
    
    if (dismissed === 'later' && dismissedTime) {
        const dismissTime = new Date(dismissedTime);
        const now = new Date();
        const daysSinceDismiss = (now - dismissTime) / (1000 * 60 * 60 * 24);
        console.log('- Days since dismissal:', daysSinceDismiss.toFixed(2));
    }
    
    // Service Worker status
    if ('serviceWorker' in navigator) {
        console.log('- Service worker supported: ✅');
        console.log('- Service worker controller:', navigator.serviceWorker.controller ? '✅' : '❌');
        
        navigator.serviceWorker.getRegistration().then(registration => {
            if (registration) {
                console.log('- Service worker registered: ✅');
                console.log('- Service worker state:', registration.active ? registration.active.state : 'Not active');
                console.log('- Service worker scope:', registration.scope);
                
                if (registration.waiting) {
                    console.log('- Service worker waiting to activate: ⏳');
                    console.log('💡 Run window.activateServiceWorker() to force activation');
                }
            } else {
                console.log('- Service worker registered: ❌');
            }
        });
    } else {
        console.log('- Service worker supported: ❌');
    }
};

// For testing - force service worker activation
window.activateServiceWorker = function() {
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.getRegistration().then(registration => {
            if (registration && registration.waiting) {
                registration.waiting.postMessage({ type: 'SKIP_WAITING' });
            }
        });
    }
};

window.addEventListener('beforeinstallprompt', (e) => {
    // Prevent the default behavior but store the event
    e.preventDefault();
    deferredPrompt = e;
    
    // Check conditions
    const appInstalled = checkAppInstallation();
    const userPreference = getUserPWAPreference();
    
    // Only show if conditions are met and user hasn't permanently dismissed
    if (!appInstalled && (userPreference === 'show' || userPreference === 'later')) {
        if (userPreference === 'show') {
            // Show our custom banner
            setTimeout(() => {
                showPWAInstallPrompt();
            }, 2000);
        }
    }
});

// Handle install button
document.addEventListener('DOMContentLoaded', function() {
    const installButton = document.getElementById('installSuha');
    
    // Initial check
    isAppInstalled = checkAppInstallation();
    
    if (installButton) {
        function updateInstallButton() {
            if (isAppInstalled || checkAppInstallation()) {
                isAppInstalled = true;
                installButton.innerHTML = '<i class="ti ti-check me-1"></i>Installed';
                installButton.disabled = true;
                installButton.classList.remove('btn-primary');
                installButton.classList.add('btn-success');
                
                // Hide the entire prompt if already installed
                hidePWAInstallPrompt();
                localStorage.setItem('pwa-install-dismissed', 'permanent');
            } else {
                installButton.innerHTML = '<i class="ti ti-download me-1"></i>Install Now';
                installButton.disabled = false;
            }
        }

        installButton.addEventListener('click', async () => {
            if (isAppInstalled) {
                return;
            }

            if (deferredPrompt) {
                deferredPrompt.prompt();
                const { outcome } = await deferredPrompt.userChoice;
                
                if (outcome === 'accepted') {
                    isAppInstalled = true;
                    updateInstallButton();
                    localStorage.setItem('pwa-install-dismissed', 'permanent');
                    
                    // Show success message
                    setTimeout(() => {
                        hidePWAInstallPrompt();
                    }, 2000);
                } else {
                    // User declined, dismiss for a while
                    dismissPWAPrompt('later');
                }
                deferredPrompt = null;
            } else {
                // Fallback: try to guide user to install manually
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Install App',
                        html: `
                            <p>To install this app:</p>
                            <ul style="text-align: left; margin: 0 auto; display: inline-block;">
                                <li><strong>Chrome/Edge:</strong> Click the install icon in the address bar</li>
                                <li><strong>Safari:</strong> Tap Share → Add to Home Screen</li>
                                <li><strong>Firefox:</strong> Tap Menu → Install</li>
                            </ul>
                        `,
                        icon: 'info',
                        confirmButtonText: 'Got it!',
                        customClass: {
                            popup: 'install-guide-popup'
                        }
                    });
                } else {
                    alert('To install: Look for the install option in your browser menu or address bar');
                }
            }
        });

        updateInstallButton();
        
        // Listen for app installation
        window.addEventListener('appinstalled', () => {
            isAppInstalled = true;
            updateInstallButton();
            localStorage.setItem('pwa-install-dismissed', 'permanent');
        });
        
        // Listen for display mode changes
        window.matchMedia('(display-mode: standalone)').addEventListener('change', updateInstallButton);
    }
    
    // Initial check for showing prompt
    if (!isAppInstalled && getUserPWAPreference() === 'show' && !deferredPrompt) {
        // If beforeinstallprompt hasn't fired yet, wait a bit
        setTimeout(() => {
            if (deferredPrompt && !isAppInstalled) {
                showPWAInstallPrompt();
            }
        }, 3000);
    }
});