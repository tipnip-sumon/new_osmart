/**
 * Browser Cache and Storage Cleaner for Logout
 * Handles complete browser storage cleanup to prevent CSRF 419 issues
 */

class LogoutCacheCleaner {
    
    /**
     * Clear all browser storage and cache
     */
    static clearAllBrowserStorage() {
        try {
            // Clear localStorage
            if (typeof(Storage) !== "undefined" && localStorage) {
                localStorage.clear();
                console.log('✅ LocalStorage cleared');
            }
            
            // Clear sessionStorage
            if (typeof(Storage) !== "undefined" && sessionStorage) {
                sessionStorage.clear();
                console.log('✅ SessionStorage cleared');
            }
            
            // Clear IndexedDB if available
            if ('indexedDB' in window) {
                this.clearIndexedDB();
            }
            
            // Clear any cached service worker data
            if ('serviceWorker' in navigator) {
                this.clearServiceWorkerCache();
            }
            
            // Clear any application cache (deprecated but still might exist)
            if (window.applicationCache) {
                try {
                    window.applicationCache.swapCache();
                } catch (e) {
                    console.log('ApplicationCache not available or error:', e.message);
                }
            }
            
            console.log('🧹 Browser storage cleanup completed');
            
        } catch (error) {
            console.error('❌ Error clearing browser storage:', error);
        }
    }
    
    /**
     * Clear IndexedDB databases
     */
    static clearIndexedDB() {
        try {
            if (!window.indexedDB) return;
            
            // Get all database names (modern browsers)
            if (indexedDB.databases) {
                indexedDB.databases().then(databases => {
                    databases.forEach(db => {
                        if (db.name) {
                            const deleteReq = indexedDB.deleteDatabase(db.name);
                            deleteReq.onsuccess = () => console.log(`🗄️ IndexedDB '${db.name}' cleared`);
                            deleteReq.onerror = (e) => console.log(`❌ Error clearing IndexedDB '${db.name}':`, e);
                        }
                    });
                });
            }
        } catch (error) {
            console.log('IndexedDB clearing error:', error.message);
        }
    }
    
    /**
     * Clear Service Worker cache
     */
    static clearServiceWorkerCache() {
        try {
            if ('caches' in window) {
                caches.keys().then(cacheNames => {
                    cacheNames.forEach(cacheName => {
                        caches.delete(cacheName).then(() => {
                            console.log(`🗂️ Cache '${cacheName}' cleared`);
                        });
                    });
                });
            }
        } catch (error) {
            console.log('Service Worker cache clearing error:', error.message);
        }
    }
    
    /**
     * Perform logout with complete cleanup
     */
    static performSecureLogout(logoutUrl, method = 'POST') {
        return new Promise((resolve, reject) => {
            try {
                // First clear all browser storage
                this.clearAllBrowserStorage();
                
                // Get CSRF token
                const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
                             document.querySelector('input[name="_token"]')?.value;
                
                // Prepare form data
                const formData = new FormData();
                if (token) {
                    formData.append('_token', token);
                }
                formData.append('_method', method);
                
                // Send logout request
                fetch(logoutUrl, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': token || ''
                    },
                    credentials: 'same-origin',
                    cache: 'no-cache'
                })
                .then(response => {
                    if (response.ok) {
                        return response.json().catch(() => ({})); // Handle non-JSON responses
                    }
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                })
                .then(data => {
                    console.log('✅ Logout successful:', data);
                    
                    // Final cleanup after logout
                    setTimeout(() => {
                        this.clearAllBrowserStorage();
                        
                        // Redirect to login page
                        const redirectUrl = data.redirect || '/affiliate/login';
                        window.location.replace(redirectUrl);
                    }, 100);
                    
                    resolve(data);
                })
                .catch(error => {
                    console.error('❌ Logout error:', error);
                    
                    // Even if logout fails, clear storage and redirect
                    this.clearAllBrowserStorage();
                    setTimeout(() => {
                        window.location.replace('/affiliate/login');
                    }, 500);
                    
                    reject(error);
                });
                
            } catch (error) {
                console.error('❌ Secure logout error:', error);
                reject(error);
            }
        });
    }
}

/**
 * Initialize logout handlers when DOM is ready
 */
document.addEventListener('DOMContentLoaded', function() {
    
    // Handle logout buttons/links
    const logoutButtons = document.querySelectorAll('[data-logout], .logout-btn, #logout-btn');
    
    logoutButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const logoutUrl = this.getAttribute('href') || 
                             this.getAttribute('data-url') || 
                             '/member/logout';
            
            // Show loading state
            if (this.textContent) {
                this.textContent = 'Logging out...';
                this.disabled = true;
            }
            
            // Perform secure logout
            LogoutCacheCleaner.performSecureLogout(logoutUrl)
                .catch(error => {
                    console.error('Logout failed:', error);
                    // Fallback: force redirect after clearing storage
                    setTimeout(() => {
                        window.location.replace('/affiliate/login');
                    }, 1000);
                });
        });
    });
    
    // Handle logout forms
    const logoutForms = document.querySelectorAll('form[action*="logout"]');
    
    logoutForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const logoutUrl = this.getAttribute('action');
            const method = this.querySelector('input[name="_method"]')?.value || 'POST';
            
            LogoutCacheCleaner.performSecureLogout(logoutUrl, method);
        });
    });
});

/**
 * Handle browser back button after logout
 */
window.addEventListener('pageshow', function(event) {
    // If page is loaded from cache after logout, redirect to login
    if (event.persisted && 
        (window.location.pathname.includes('/member/') || 
         window.location.pathname.includes('/dashboard'))) {
        
        // Check if user is actually logged out
        fetch('/api/auth-check', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin',
            cache: 'no-cache'
        })
        .then(response => response.json())
        .catch(() => ({ authenticated: false }))
        .then(data => {
            if (!data.authenticated) {
                LogoutCacheCleaner.clearAllBrowserStorage();
                window.location.replace('/affiliate/login');
            }
        });
    }
});

// Export for global usage
window.LogoutCacheCleaner = LogoutCacheCleaner;