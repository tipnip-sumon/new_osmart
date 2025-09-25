{{-- Logout Confirmation Modal with Cache Clearing --}}
<div class="modal fade" id="logoutConfirmModal" tabindex="-1" aria-labelledby="logoutConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title" id="logoutConfirmModalLabel">
                    <i class="fas fa-sign-out-alt me-2"></i>Confirm Logout
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <div class="mb-3">
                        <i class="fas fa-question-circle text-warning" style="font-size: 3rem;"></i>
                    </div>
                    <h6 class="mb-3">Are you sure you want to logout?</h6>
                    <p class="text-muted small">
                        This will clear your browser session and cache to prevent any login issues.
                    </p>
                </div>
                
                {{-- Logout Progress --}}
                <div id="logout-progress" class="d-none">
                    <div class="text-center">
                        <div class="spinner-border text-primary mb-3" role="status">
                            <span class="visually-hidden">Logging out...</span>
                        </div>
                        <div id="logout-status">
                            <p class="mb-1"><i class="fas fa-sync-alt fa-spin"></i> Clearing browser cache...</p>
                            <div class="progress" style="height: 4px;">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" 
                                     role="progressbar" style="width: 0%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" id="logout-buttons">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Cancel
                </button>
                <button type="button" class="btn btn-warning" id="confirm-logout-btn">
                    <i class="fas fa-sign-out-alt me-1"></i>Yes, Logout
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Enhanced Logout Form --}}
<form id="secure-logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
    @method('POST')
</form>

{{-- JavaScript for Secure Logout --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const logoutModal = document.getElementById('logoutConfirmModal');
    const confirmBtn = document.getElementById('confirm-logout-btn');
    const logoutProgress = document.getElementById('logout-progress');
    const logoutButtons = document.getElementById('logout-buttons');
    const logoutForm = document.getElementById('secure-logout-form');
    
    // Handle logout confirmation
    if (confirmBtn) {
        confirmBtn.addEventListener('click', function() {
            performSecureLogout();
        });
    }
    
    // Handle all logout triggers
    document.addEventListener('click', function(e) {
        const logoutTrigger = e.target.closest('[data-logout], .logout-btn, #logout-btn, [href*="logout"]');
        
        if (logoutTrigger && !logoutTrigger.dataset.processed) {
            e.preventDefault();
            logoutTrigger.dataset.processed = 'true';
            
            // Show confirmation modal
            const modal = new bootstrap.Modal(logoutModal);
            modal.show();
        }
    });
    
    function performSecureLogout() {
        // Show progress
        logoutButtons.classList.add('d-none');
        logoutProgress.classList.remove('d-none');
        
        const progressBar = logoutProgress.querySelector('.progress-bar');
        const statusText = document.getElementById('logout-status');
        
        // Step 1: Clear browser storage
        updateLogoutProgress(20, 'Clearing browser storage...', progressBar, statusText);
        
        setTimeout(() => {
            if (window.LogoutCacheCleaner) {
                LogoutCacheCleaner.clearAllBrowserStorage();
            } else {
                // Fallback manual clearing
                try {
                    localStorage.clear();
                    sessionStorage.clear();
                } catch (e) {
                    console.log('Storage clearing error:', e);
                }
            }
            
            // Step 2: Prepare logout request
            updateLogoutProgress(40, 'Preparing logout request...', progressBar, statusText);
            
            setTimeout(() => {
                // Step 3: Send logout request
                updateLogoutProgress(60, 'Sending logout request...', progressBar, statusText);
                
                const formData = new FormData(logoutForm);
                
                fetch(logoutForm.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    },
                    credentials: 'same-origin',
                    cache: 'no-cache'
                })
                .then(response => {
                    updateLogoutProgress(80, 'Processing logout...', progressBar, statusText);
                    
                    if (response.ok) {
                        return response.json().catch(() => ({}));
                    }
                    throw new Error(`HTTP ${response.status}`);
                })
                .then(data => {
                    // Step 4: Final cleanup
                    updateLogoutProgress(100, 'Finalizing logout...', progressBar, statusText);
                    
                    setTimeout(() => {
                        // Final storage clear
                        if (window.LogoutCacheCleaner) {
                            LogoutCacheCleaner.clearAllBrowserStorage();
                        }
                        
                        // Redirect
                        const redirectUrl = data.redirect || '{{ route("affiliate.login") }}';
                        window.location.replace(redirectUrl);
                    }, 500);
                })
                .catch(error => {
                    console.error('Logout error:', error);
                    
                    // Even if logout fails, clear storage and redirect
                    updateLogoutProgress(100, 'Completing logout...', progressBar, statusText);
                    
                    setTimeout(() => {
                        if (window.LogoutCacheCleaner) {
                            LogoutCacheCleaner.clearAllBrowserStorage();
                        }
                        window.location.replace('{{ route("affiliate.login") }}');
                    }, 1000);
                });
            }, 500);
        }, 300);
    }
    
    function updateLogoutProgress(percent, message, progressBar, statusText) {
        if (progressBar) {
            progressBar.style.width = percent + '%';
        }
        if (statusText) {
            statusText.innerHTML = `<p class="mb-1"><i class="fas fa-sync-alt fa-spin"></i> ${message}</p>`;
        }
    }
});

// Handle browser navigation after logout
window.addEventListener('pageshow', function(event) {
    if (event.persisted && window.location.pathname.includes('/member/')) {
        // Clear storage and redirect if accessing member area after logout
        try {
            localStorage.clear();
            sessionStorage.clear();
        } catch (e) {}
        
        window.location.replace('{{ route("affiliate.login") }}');
    }
});
</script>

<style>
#logoutConfirmModal .modal-content {
    border-radius: 15px;
    border: none;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
}

#logoutConfirmModal .modal-header {
    border-radius: 15px 15px 0 0;
    border-bottom: none;
}

#logoutConfirmModal .modal-footer {
    border-top: none;
    border-radius: 0 0 15px 15px;
}

#logout-progress .progress {
    background-color: #e9ecef;
    border-radius: 10px;
    overflow: hidden;
}

#logout-progress .progress-bar {
    background: linear-gradient(45deg, #ffc107, #fd7e14);
    transition: width 0.3s ease;
}

.spinner-border {
    width: 3rem;
    height: 3rem;
}
</style>