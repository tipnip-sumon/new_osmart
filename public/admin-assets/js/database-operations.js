/**
 * Database Operations Manager with SweetAlert Integration
 * Handles all CRUD operations with beautiful confirmations and notifications
 */
class DatabaseManager {
    constructor() {
        this.ajax = new AjaxManager();
        this.setupGlobalEventListeners();
    }

    setupGlobalEventListeners() {
        // Handle delete buttons with data-delete attribute
        document.addEventListener('click', async (e) => {
            if (e.target.matches('[data-delete]') || e.target.closest('[data-delete]')) {
                e.preventDefault();
                const element = e.target.matches('[data-delete]') ? e.target : e.target.closest('[data-delete]');
                await this.handleDelete(element);
            }
        });

        // Handle status toggle switches
        document.addEventListener('change', async (e) => {
            if (e.target.matches('[data-toggle-status]')) {
                await this.handleStatusToggle(e.target);
            }
        });

        // Handle bulk action buttons
        document.addEventListener('click', async (e) => {
            if (e.target.matches('[data-bulk-action]') || e.target.closest('[data-bulk-action]')) {
                e.preventDefault();
                const element = e.target.matches('[data-bulk-action]') ? e.target : e.target.closest('[data-bulk-action]');
                await this.handleBulkAction(element);
            }
        });

        // Handle export buttons
        document.addEventListener('click', async (e) => {
            if (e.target.matches('[data-export]') || e.target.closest('[data-export]')) {
                e.preventDefault();
                const element = e.target.matches('[data-export]') ? e.target : e.target.closest('[data-export]');
                await this.handleExport(element);
            }
        });
    }

    /**
     * Handle delete operations with confirmation
     */
    async handleDelete(element) {
        const url = element.dataset.delete || element.href;
        const itemName = element.dataset.itemName || 'this item';
        const itemType = element.dataset.itemType || 'item';
        
        const result = await Swal.fire({
            title: `Delete ${itemType}?`,
            html: `Are you sure you want to delete <strong>${itemName}</strong>?<br><small class="text-muted">This action cannot be undone!</small>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: `<i class="ti ti-trash me-1"></i>Yes, Delete!`,
            cancelButtonText: `<i class="ti ti-x me-1"></i>Cancel`,
            reverseButtons: true,
            focusCancel: true,
            showLoaderOnConfirm: true,
            preConfirm: async () => {
                try {
                    const response = await this.ajax.delete(url);
                    if (!response.success) {
                        throw new Error(response.error || 'Failed to delete');
                    }
                    return response.data;
                } catch (error) {
                    Swal.showValidationMessage(`Failed to delete: ${error.message}`);
                    return false;
                }
            },
            allowOutsideClick: () => !Swal.isLoading()
        });

        if (result.isConfirmed) {
            await Swal.fire({
                title: 'Deleted!',
                text: result.value.message || `${itemType} has been deleted successfully.`,
                icon: 'success',
                timer: 2000,
                showConfirmButton: false,
                timerProgressBar: true
            });

            // Refresh page or remove element
            if (element.dataset.refreshPage === 'true') {
                window.location.reload();
            } else {
                this.removeElementWithAnimation(element);
            }
        }
    }

    /**
     * Handle status toggle operations
     */
    async handleStatusToggle(toggle) {
        const url = toggle.dataset.toggleStatus;
        const itemName = toggle.dataset.itemName || 'Item';
        const currentStatus = toggle.checked;
        const statusText = currentStatus ? 'activate' : 'deactivate';
        
        const result = await Swal.fire({
            title: `${statusText.charAt(0).toUpperCase() + statusText.slice(1)} ${itemName}?`,
            text: `Are you sure you want to ${statusText} this ${itemName.toLowerCase()}?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: currentStatus ? '#28a745' : '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: `Yes, ${statusText.charAt(0).toUpperCase() + statusText.slice(1)}!`,
            cancelButtonText: 'Cancel',
            reverseButtons: true,
            showLoaderOnConfirm: true,
            preConfirm: async () => {
                try {
                    const response = await this.ajax.patch(url, { 
                        is_active: currentStatus 
                    });
                    if (!response.success) {
                        throw new Error(response.error || 'Failed to update status');
                    }
                    return response.data;
                } catch (error) {
                    Swal.showValidationMessage(`Failed to update status: ${error.message}`);
                    return false;
                }
            },
            allowOutsideClick: () => !Swal.isLoading()
        });

        if (result.isConfirmed) {
            Swal.fire({
                title: 'Status Updated!',
                text: result.value.message || `${itemName} status updated successfully.`,
                icon: 'success',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true
            });
        } else {
            // Revert toggle if cancelled
            toggle.checked = !currentStatus;
        }
    }

    /**
     * Handle bulk operations
     */
    async handleBulkAction(element) {
        const action = element.dataset.bulkAction;
        const checkboxes = document.querySelectorAll('input[type="checkbox"]:checked:not(#selectAll)');
        const selectedIds = Array.from(checkboxes).map(cb => cb.value).filter(Boolean);

        if (selectedIds.length === 0) {
            Swal.fire({
                title: 'No Items Selected',
                text: 'Please select at least one item to perform bulk action.',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
            return;
        }

        const actionConfig = this.getBulkActionConfig(action, selectedIds.length);
        
        const result = await Swal.fire({
            title: actionConfig.title,
            text: actionConfig.text,
            icon: actionConfig.icon,
            showCancelButton: true,
            confirmButtonColor: actionConfig.confirmColor,
            cancelButtonColor: '#6c757d',
            confirmButtonText: actionConfig.confirmText,
            cancelButtonText: 'Cancel',
            reverseButtons: true,
            showLoaderOnConfirm: true,
            preConfirm: async () => {
                try {
                    const url = element.dataset.bulkUrl || '/admin/bulk-action';
                    const response = await this.ajax.post(url, {
                        action: action,
                        ids: selectedIds
                    });
                    if (!response.success) {
                        throw new Error(response.error || 'Bulk action failed');
                    }
                    return response.data;
                } catch (error) {
                    Swal.showValidationMessage(`Bulk action failed: ${error.message}`);
                    return false;
                }
            },
            allowOutsideClick: () => !Swal.isLoading()
        });

        if (result.isConfirmed) {
            await Swal.fire({
                title: 'Bulk Action Complete!',
                text: result.value.message || `Bulk ${action} completed successfully.`,
                icon: 'success',
                timer: 2000,
                showConfirmButton: false,
                timerProgressBar: true
            });

            // Refresh page
            window.location.reload();
        }
    }

    /**
     * Handle export operations
     */
    async handleExport(element) {
        const exportType = element.dataset.export;
        const exportUrl = element.dataset.exportUrl || element.href;
        
        const result = await Swal.fire({
            title: `Export ${exportType.toUpperCase()}?`,
            text: 'This will download the data in the selected format.',
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#007bff',
            cancelButtonColor: '#6c757d',
            confirmButtonText: `<i class="ti ti-download me-1"></i>Export ${exportType.toUpperCase()}`,
            cancelButtonText: 'Cancel',
            showLoaderOnConfirm: true,
            preConfirm: async () => {
                try {
                    // Create download link
                    const link = document.createElement('a');
                    link.href = exportUrl;
                    link.download = `export_${Date.now()}.${exportType}`;
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                    
                    return { success: true };
                } catch (error) {
                    Swal.showValidationMessage(`Export failed: ${error.message}`);
                    return false;
                }
            },
            allowOutsideClick: () => !Swal.isLoading()
        });

        if (result.isConfirmed) {
            Swal.fire({
                title: 'Export Started!',
                text: 'Your download should begin shortly.',
                icon: 'success',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        }
    }

    /**
     * Get configuration for bulk actions
     */
    getBulkActionConfig(action, count) {
        const configs = {
            delete: {
                title: `Delete ${count} Items?`,
                text: `Are you sure you want to delete ${count} selected items? This action cannot be undone!`,
                icon: 'warning',
                confirmColor: '#dc3545',
                confirmText: `<i class="ti ti-trash me-1"></i>Delete ${count} Items`
            },
            activate: {
                title: `Activate ${count} Items?`,
                text: `Are you sure you want to activate ${count} selected items?`,
                icon: 'question',
                confirmColor: '#28a745',
                confirmText: `<i class="ti ti-check me-1"></i>Activate ${count} Items`
            },
            deactivate: {
                title: `Deactivate ${count} Items?`,
                text: `Are you sure you want to deactivate ${count} selected items?`,
                icon: 'question',
                confirmColor: '#ffc107',
                confirmText: `<i class="ti ti-x me-1"></i>Deactivate ${count} Items`
            },
            archive: {
                title: `Archive ${count} Items?`,
                text: `Are you sure you want to archive ${count} selected items?`,
                icon: 'info',
                confirmColor: '#17a2b8',
                confirmText: `<i class="ti ti-archive me-1"></i>Archive ${count} Items`
            }
        };

        return configs[action] || {
            title: `Perform Action on ${count} Items?`,
            text: `Are you sure you want to perform this action on ${count} selected items?`,
            icon: 'question',
            confirmColor: '#007bff',
            confirmText: `<i class="ti ti-check me-1"></i>Proceed`
        };
    }

    /**
     * Remove element with smooth animation
     */
    removeElementWithAnimation(element) {
        const row = element.closest('tr') || element.closest('.card') || element.closest('.list-item');
        if (row) {
            row.style.transition = 'all 0.3s ease';
            row.style.opacity = '0';
            row.style.transform = 'translateX(-100%)';
            
            setTimeout(() => {
                row.remove();
            }, 300);
        }
    }

    /**
     * Create a new record with form dialog
     */
    async showCreateDialog(config = {}) {
        const defaultConfig = {
            title: 'Create New Record',
            fields: [],
            submitUrl: '',
            successMessage: 'Record created successfully!'
        };

        const finalConfig = { ...defaultConfig, ...config };
        
        // Build form HTML
        let formHtml = '<div class="swal-form">';
        finalConfig.fields.forEach(field => {
            formHtml += this.buildFormField(field);
        });
        formHtml += '</div>';

        const result = await Swal.fire({
            title: finalConfig.title,
            html: formHtml,
            showCancelButton: true,
            confirmButtonText: 'Create',
            cancelButtonText: 'Cancel',
            width: '600px',
            preConfirm: () => {
                return this.getFormData(finalConfig.fields);
            }
        });

        if (result.isConfirmed) {
            try {
                const response = await this.ajax.post(finalConfig.submitUrl, result.value);
                if (response.success) {
                    Swal.fire({
                        title: 'Success!',
                        text: finalConfig.successMessage,
                        icon: 'success',
                        timer: 2000
                    });
                    return response.data;
                }
            } catch (error) {
                Swal.fire({
                    title: 'Error!',
                    text: error.message || 'Failed to create record',
                    icon: 'error'
                });
            }
        }

        return null;
    }

    /**
     * Build form field HTML
     */
    buildFormField(field) {
        const { type, name, label, placeholder, required, options } = field;
        
        let fieldHtml = `<div class="mb-3 text-start">`;
        fieldHtml += `<label class="form-label">${label}${required ? ' <span class="text-danger">*</span>' : ''}</label>`;
        
        switch (type) {
            case 'select':
                fieldHtml += `<select class="form-select" name="${name}" ${required ? 'required' : ''}>`;
                if (placeholder) fieldHtml += `<option value="">${placeholder}</option>`;
                options.forEach(opt => {
                    fieldHtml += `<option value="${opt.value}">${opt.text}</option>`;
                });
                fieldHtml += `</select>`;
                break;
            
            case 'textarea':
                fieldHtml += `<textarea class="form-control" name="${name}" placeholder="${placeholder || ''}" ${required ? 'required' : ''}></textarea>`;
                break;
            
            default:
                fieldHtml += `<input type="${type}" class="form-control" name="${name}" placeholder="${placeholder || ''}" ${required ? 'required' : ''}>`;
        }
        
        fieldHtml += `</div>`;
        return fieldHtml;
    }

    /**
     * Get form data from SweetAlert dialog
     */
    getFormData(fields) {
        const data = {};
        fields.forEach(field => {
            const element = Swal.getPopup().querySelector(`[name="${field.name}"]`);
            if (element) {
                data[field.name] = element.value;
            }
        });
        return data;
    }

    /**
     * Show data table with search and pagination
     */
    async showDataTable(config = {}) {
        const { title, url, columns, actions } = config;
        
        try {
            const response = await this.ajax.get(url);
            if (response.success) {
                const tableHtml = this.buildDataTable(response.data, columns, actions);
                
                Swal.fire({
                    title: title,
                    html: tableHtml,
                    width: '90%',
                    showConfirmButton: false,
                    showCloseButton: true
                });
            }
        } catch (error) {
            Swal.fire({
                title: 'Error!',
                text: 'Failed to load data',
                icon: 'error'
            });
        }
    }

    /**
     * Build data table HTML
     */
    buildDataTable(data, columns, actions) {
        let html = '<div class="table-responsive">';
        html += '<table class="table table-striped">';
        
        // Header
        html += '<thead><tr>';
        columns.forEach(col => {
            html += `<th>${col.title}</th>`;
        });
        if (actions) html += '<th>Actions</th>';
        html += '</tr></thead>';
        
        // Body
        html += '<tbody>';
        data.forEach(row => {
            html += '<tr>';
            columns.forEach(col => {
                html += `<td>${row[col.field] || ''}</td>`;
            });
            if (actions) {
                html += '<td>';
                actions.forEach(action => {
                    html += `<button class="btn btn-sm btn-${action.class} me-1" onclick="${action.onclick}(${row.id})">${action.text}</button>`;
                });
                html += '</td>';
            }
            html += '</tr>';
        });
        html += '</tbody></table></div>';
        
        return html;
    }
}

/**
 * Enhanced Search Manager with SweetAlert
 */
class SearchManager {
    constructor() {
        this.ajax = new AjaxManager();
        this.setupSearchListeners();
    }

    setupSearchListeners() {
        // Advanced search modal
        document.addEventListener('click', (e) => {
            if (e.target.matches('[data-advanced-search]') || e.target.closest('[data-advanced-search]')) {
                e.preventDefault();
                this.showAdvancedSearch();
            }
        });
    }

    async showAdvancedSearch() {
        const { value: searchParams } = await Swal.fire({
            title: 'Advanced Search',
            html: `
                <div class="text-start">
                    <div class="mb-3">
                        <label class="form-label">Search Term</label>
                        <input type="text" class="form-control" id="search-term" placeholder="Enter search term...">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Category</label>
                        <select class="form-select" id="search-category">
                            <option value="">All Categories</option>
                            <option value="electronics">Electronics</option>
                            <option value="fashion">Fashion</option>
                            <option value="home">Home & Garden</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-select" id="search-status">
                            <option value="">All Status</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Date Range</label>
                        <div class="row">
                            <div class="col-6">
                                <input type="date" class="form-control" id="search-date-from" placeholder="From">
                            </div>
                            <div class="col-6">
                                <input type="date" class="form-control" id="search-date-to" placeholder="To">
                            </div>
                        </div>
                    </div>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Search',
            cancelButtonText: 'Clear Filters',
            preConfirm: () => {
                return {
                    term: document.getElementById('search-term').value,
                    category: document.getElementById('search-category').value,
                    status: document.getElementById('search-status').value,
                    dateFrom: document.getElementById('search-date-from').value,
                    dateTo: document.getElementById('search-date-to').value
                };
            }
        });

        if (searchParams) {
            this.performAdvancedSearch(searchParams);
        }
    }

    async performAdvancedSearch(params) {
        try {
            Swal.fire({
                title: 'Searching...',
                text: 'Please wait while we search the database',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Simulate search delay
            await new Promise(resolve => setTimeout(resolve, 1000));

            const response = await this.ajax.get('/admin/search', params);
            
            Swal.close();

            if (response.success) {
                Swal.fire({
                    title: 'Search Results',
                    text: `Found ${response.data.length} results`,
                    icon: 'success',
                    timer: 2000
                });

                // Update page with results
                this.displaySearchResults(response.data);
            }
        } catch (error) {
            Swal.fire({
                title: 'Search Failed',
                text: error.message || 'Search operation failed',
                icon: 'error'
            });
        }
    }

    displaySearchResults(results) {
        // Implementation depends on your specific UI
        console.log('Search results:', results);
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    window.dbManager = new DatabaseManager();
    window.searchManager = new SearchManager();
    
    // Setup select all functionality
    const selectAllCheckbox = document.getElementById('selectAll');
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('input[type="checkbox"]:not(#selectAll)');
            checkboxes.forEach(cb => {
                cb.checked = this.checked;
            });
            
            // Show toast notification
            if (this.checked) {
                Swal.fire({
                    title: 'All Selected',
                    text: `${checkboxes.length} items selected`,
                    icon: 'info',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true
                });
            }
        });
    }
});

// Export for global access
window.DatabaseManager = DatabaseManager;
window.SearchManager = SearchManager;
