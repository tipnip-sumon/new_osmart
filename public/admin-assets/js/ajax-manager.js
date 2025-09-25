/**
 * AJAX Utility Class for Laravel Applications
 * Provides standardized methods for server-database communication
 */
class AjaxManager {
    constructor() {
        this.baseUrl = window.location.origin;
        this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        this.defaultHeaders = {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': this.csrfToken,
            'Accept': 'application/json'
        };
    }

    /**
     * Generic AJAX request method
     */
    async request(url, options = {}) {
        const config = {
            method: 'GET',
            headers: { ...this.defaultHeaders },
            ...options
        };

        // Add Content-Type for non-FormData requests
        if (!(config.body instanceof FormData)) {
            config.headers['Content-Type'] = 'application/json';
        }

        try {
            this.showLoader();
            const response = await fetch(url, config);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            this.hideLoader();
            
            return {
                success: true,
                data: data,
                status: response.status
            };
        } catch (error) {
            this.hideLoader();
            console.error('AJAX Error:', error);
            
            return {
                success: false,
                error: error.message,
                status: error.status || 500
            };
        }
    }

    /**
     * GET request
     */
    async get(url, params = {}) {
        const urlParams = new URLSearchParams(params);
        const fullUrl = urlParams.toString() ? `${url}?${urlParams}` : url;
        
        return this.request(fullUrl, { method: 'GET' });
    }

    /**
     * POST request
     */
    async post(url, data = {}) {
        const body = data instanceof FormData ? data : JSON.stringify(data);
        
        return this.request(url, {
            method: 'POST',
            body: body
        });
    }

    /**
     * PUT request
     */
    async put(url, data = {}) {
        const body = data instanceof FormData ? data : JSON.stringify(data);
        
        return this.request(url, {
            method: 'PUT',
            body: body
        });
    }

    /**
     * DELETE request
     */
    async delete(url, data = {}) {
        return this.request(url, {
            method: 'DELETE',
            body: JSON.stringify(data)
        });
    }

    /**
     * PATCH request
     */
    async patch(url, data = {}) {
        const body = data instanceof FormData ? data : JSON.stringify(data);
        
        return this.request(url, {
            method: 'PATCH',
            body: body
        });
    }

    /**
     * Upload file with progress tracking
     */
    async uploadFile(url, formData, onProgress = null) {
        return new Promise((resolve, reject) => {
            const xhr = new XMLHttpRequest();

            // Track upload progress
            if (onProgress && typeof onProgress === 'function') {
                xhr.upload.addEventListener('progress', (e) => {
                    if (e.lengthComputable) {
                        const percentComplete = (e.loaded / e.total) * 100;
                        onProgress(percentComplete);
                    }
                });
            }

            xhr.onload = () => {
                if (xhr.status >= 200 && xhr.status < 300) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        resolve({ success: true, data: response });
                    } catch (e) {
                        resolve({ success: true, data: xhr.responseText });
                    }
                } else {
                    reject({ success: false, error: xhr.statusText, status: xhr.status });
                }
            };

            xhr.onerror = () => {
                reject({ success: false, error: 'Network error', status: 0 });
            };

            xhr.open('POST', url);
            xhr.setRequestHeader('X-CSRF-TOKEN', this.csrfToken);
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            xhr.send(formData);
        });
    }

    /**
     * Show loading indicator using SweetAlert
     */
    showLoader(title = 'Processing...', text = 'Please wait...') {
        return this.showSwalLoader(title, text);
    }

    /**
     * Hide loading indicator
     */
    hideLoader() {
        this.hideSwalLoader();
    }

    /**
     * Display notification using SweetAlert
     */
    showNotification(message, type = 'success', title = null, options = {}) {
        const swalConfig = {
            title: title || (type === 'success' ? 'Success!' : type === 'error' ? 'Error!' : type === 'warning' ? 'Warning!' : 'Info'),
            text: message,
            icon: type === 'danger' ? 'error' : type,
            timer: options.timer || 3000,
            showConfirmButton: options.showConfirmButton !== false,
            timerProgressBar: true,
            toast: options.toast !== false,
            position: options.position || 'top-end',
            showCloseButton: options.showCloseButton !== false,
            ...options
        };

        return Swal.fire(swalConfig);
    }

    /**
     * Show confirmation dialog using SweetAlert
     */
    showConfirmation(options = {}) {
        const defaultOptions = {
            title: 'Are you sure?',
            text: 'This action cannot be undone!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, proceed!',
            cancelButtonText: 'Cancel',
            reverseButtons: true,
            focusCancel: true
        };

        return Swal.fire({ ...defaultOptions, ...options });
    }

    /**
     * Show loading dialog using SweetAlert
     */
    showSwalLoader(title = 'Processing...', text = 'Please wait while we process your request.') {
        return Swal.fire({
            title: title,
            text: text,
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
    }

    /**
     * Hide SweetAlert loader
     */
    hideSwalLoader() {
        Swal.close();
    }

    /**
     * Show input dialog using SweetAlert
     */
    showInputDialog(options = {}) {
        const defaultOptions = {
            title: 'Enter value',
            input: 'text',
            inputPlaceholder: 'Enter your input...',
            showCancelButton: true,
            confirmButtonText: 'Submit',
            cancelButtonText: 'Cancel',
            inputValidator: (value) => {
                if (!value) {
                    return 'You need to enter a value!';
                }
            }
        };

        return Swal.fire({ ...defaultOptions, ...options });
    }

    /**
     * Handle form submission with AJAX and SweetAlert
     */
    async submitForm(form, options = {}) {
        const formData = new FormData(form);
        const method = form.getAttribute('method') || 'POST';
        const action = form.getAttribute('action') || window.location.href;

        // Add method spoofing for Laravel
        if (['PUT', 'PATCH', 'DELETE'].includes(method.toUpperCase())) {
            formData.append('_method', method.toUpperCase());
        }

        const response = await this.request(action, {
            method: 'POST',
            body: formData
        });

        if (response.success) {
            if (options.onSuccess) {
                options.onSuccess(response.data);
            } else {
                this.showNotification(
                    response.data.message || 'Operation completed successfully', 
                    'success',
                    'Success!',
                    { timer: 2000 }
                );
            }
        } else {
            if (options.onError) {
                options.onError(response.error);
            } else {
                this.showNotification(
                    response.error || 'An error occurred', 
                    'error',
                    'Error!',
                    { timer: 4000 }
                );
            }
        }

        return response;
    }

    /**
     * Debounce function for search/input events
     */
    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    /**
     * Live search functionality
     */
    setupLiveSearch(inputElement, searchUrl, resultContainer, options = {}) {
        const searchFn = this.debounce(async (query) => {
            if (query.length < (options.minLength || 2)) {
                resultContainer.innerHTML = '';
                return;
            }

            const response = await this.get(searchUrl, { q: query, ...options.params });
            
            if (response.success && options.renderResults) {
                options.renderResults(response.data, resultContainer);
            }
        }, options.delay || 300);

        inputElement.addEventListener('input', (e) => {
            searchFn(e.target.value.trim());
        });
    }
}

/**
 * Database-specific operations for Categories
 */
class CategoryManager {
    constructor() {
        this.ajax = new AjaxManager();
        this.baseUrl = '/admin/categories';
    }

    /**
     * Fetch all categories with optional filters
     */
    async getCategories(filters = {}) {
        return this.ajax.get(this.baseUrl, filters);
    }

    /**
     * Get single category by ID
     */
    async getCategory(id) {
        return this.ajax.get(`${this.baseUrl}/${id}`);
    }

    /**
     * Create new category
     */
    async createCategory(formData) {
        return this.ajax.post(this.baseUrl, formData);
    }

    /**
     * Update existing category
     */
    async updateCategory(id, formData) {
        return this.ajax.put(`${this.baseUrl}/${id}`, formData);
    }

    /**
     * Delete category
     */
    async deleteCategory(id) {
        return this.ajax.delete(`${this.baseUrl}/${id}`);
    }

    /**
     * Toggle category status
     */
    async toggleStatus(id, status) {
        return this.ajax.patch(`${this.baseUrl}/${id}/toggle-status`, { is_active: status });
    }

    /**
     * Bulk operations
     */
    async bulkOperation(operation, categoryIds, data = {}) {
        return this.ajax.post(`${this.baseUrl}/bulk`, {
            operation,
            ids: categoryIds,
            ...data
        });
    }

    /**
     * Get category tree structure
     */
    async getCategoryTree() {
        return this.ajax.get(`${this.baseUrl}/tree`);
    }

    /**
     * Reorder categories
     */
    async reorderCategories(orderData) {
        return this.ajax.post(`${this.baseUrl}/reorder`, { order: orderData });
    }

    /**
     * Search categories
     */
    async searchCategories(query, filters = {}) {
        return this.ajax.get(`${this.baseUrl}/search`, { q: query, ...filters });
    }

    /**
     * Get category statistics
     */
    async getCategoryStats(id = null) {
        const url = id ? `${this.baseUrl}/${id}/stats` : `${this.baseUrl}/stats`;
        return this.ajax.get(url);
    }
}

/**
 * Initialize when DOM is ready
 */
document.addEventListener('DOMContentLoaded', function() {
    // Make instances globally available
    window.ajax = new AjaxManager();
    window.categoryManager = new CategoryManager();

    // Setup global AJAX error handling
    window.addEventListener('unhandledrejection', function(event) {
        console.error('Unhandled promise rejection:', event.reason);
        window.ajax.showNotification('An unexpected error occurred', 'danger');
    });

    // Setup CSRF token refresh
    setInterval(() => {
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (token && window.ajax) {
            window.ajax.csrfToken = token;
            window.ajax.defaultHeaders['X-CSRF-TOKEN'] = token;
        }
    }, 300000); // Refresh every 5 minutes
});

// Export for module systems
if (typeof module !== 'undefined' && module.exports) {
    module.exports = { AjaxManager, CategoryManager };
}
