@extends('admin.layouts.app')

@section('title', 'Menu Management')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">Menu Management</h1>
            <div class="ms-md-1 ms-0">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Menus</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <!-- Menu Builder -->
            <div class="col-xl-8">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Menu Builder</div>
                        <div class="d-flex gap-2">
                            <select class="form-control" id="menuSelect" style="width: 200px;">
                                <option value="main">Main Menu</option>
                                <option value="footer">Footer Menu</option>
                                <option value="sidebar">Sidebar Menu</option>
                                <option value="mobile">Mobile Menu</option>
                            </select>
                            <button class="btn btn-primary btn-sm" onclick="createMenu()">
                                <i class="bx bx-plus"></i> New Menu
                            </button>
                            <button class="btn btn-success btn-sm" onclick="saveMenu()">
                                <i class="bx bx-save"></i> Save Menu
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="menuBuilder" class="menu-builder">
                            <div class="alert alert-info">
                                <i class="bx bx-info-circle"></i>
                                <strong>Drag and drop</strong> menu items to reorder them. Click on an item to edit its properties.
                            </div>
                            
                            <!-- Menu Items -->
                            <div id="menuItems" class="menu-items">
                                <div class="menu-item" data-id="1">
                                    <div class="menu-item-header">
                                        <div class="menu-item-info">
                                            <i class="bx bx-menu handle"></i>
                                            <span class="menu-title">Home</span>
                                            <small class="text-muted">(/)</small>
                                        </div>
                                        <div class="menu-item-actions">
                                            <button class="btn btn-sm btn-primary-light" onclick="editMenuItem(1)">
                                                <i class="bx bx-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger-light" onclick="deleteMenuItem(1)">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="menu-item" data-id="2">
                                    <div class="menu-item-header">
                                        <div class="menu-item-info">
                                            <i class="bx bx-menu handle"></i>
                                            <span class="menu-title">Products</span>
                                            <small class="text-muted">(/products)</small>
                                        </div>
                                        <div class="menu-item-actions">
                                            <button class="btn btn-sm btn-primary-light" onclick="editMenuItem(2)">
                                                <i class="bx bx-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger-light" onclick="deleteMenuItem(2)">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <!-- Sub-menu items -->
                                    <div class="menu-submenu">
                                        <div class="menu-item sub-item" data-id="2-1">
                                            <div class="menu-item-header">
                                                <div class="menu-item-info">
                                                    <i class="bx bx-menu handle"></i>
                                                    <span class="menu-title">Electronics</span>
                                                    <small class="text-muted">(/products/electronics)</small>
                                                </div>
                                                <div class="menu-item-actions">
                                                    <button class="btn btn-sm btn-primary-light" onclick="editMenuItem('2-1')">
                                                        <i class="bx bx-edit"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-danger-light" onclick="deleteMenuItem('2-1')">
                                                        <i class="bx bx-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="menu-item sub-item" data-id="2-2">
                                            <div class="menu-item-header">
                                                <div class="menu-item-info">
                                                    <i class="bx bx-menu handle"></i>
                                                    <span class="menu-title">Clothing</span>
                                                    <small class="text-muted">(/products/clothing)</small>
                                                </div>
                                                <div class="menu-item-actions">
                                                    <button class="btn btn-sm btn-primary-light" onclick="editMenuItem('2-2')">
                                                        <i class="bx bx-edit"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-danger-light" onclick="deleteMenuItem('2-2')">
                                                        <i class="bx bx-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="menu-item" data-id="3">
                                    <div class="menu-item-header">
                                        <div class="menu-item-info">
                                            <i class="bx bx-menu handle"></i>
                                            <span class="menu-title">About</span>
                                            <small class="text-muted">(/about)</small>
                                        </div>
                                        <div class="menu-item-actions">
                                            <button class="btn btn-sm btn-primary-light" onclick="editMenuItem(3)">
                                                <i class="bx bx-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger-light" onclick="deleteMenuItem(3)">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="menu-item" data-id="4">
                                    <div class="menu-item-header">
                                        <div class="menu-item-info">
                                            <i class="bx bx-menu handle"></i>
                                            <span class="menu-title">Contact</span>
                                            <small class="text-muted">(/contact)</small>
                                        </div>
                                        <div class="menu-item-actions">
                                            <button class="btn btn-sm btn-primary-light" onclick="editMenuItem(4)">
                                                <i class="bx bx-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger-light" onclick="deleteMenuItem(4)">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Menu Options -->
            <div class="col-xl-4">
                <!-- Add Menu Items -->
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Add Menu Items</div>
                    </div>
                    <div class="card-body">
                        <div class="accordion" id="menuOptions">
                            <!-- Pages -->
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#pagesCollapse">
                                        <i class="bx bx-file me-2"></i> Pages
                                    </button>
                                </h2>
                                <div id="pagesCollapse" class="accordion-collapse collapse show" data-bs-parent="#menuOptions">
                                    <div class="accordion-body">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="page1">
                                            <label class="form-check-label" for="page1">Home</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="page2">
                                            <label class="form-check-label" for="page2">About Us</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="page3">
                                            <label class="form-check-label" for="page3">Contact</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="page4">
                                            <label class="form-check-label" for="page4">Privacy Policy</label>
                                        </div>
                                        <button class="btn btn-sm btn-primary mt-2" onclick="addSelectedPages()">
                                            Add Selected
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Categories -->
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#categoriesCollapse">
                                        <i class="bx bx-category me-2"></i> Categories
                                    </button>
                                </h2>
                                <div id="categoriesCollapse" class="accordion-collapse collapse" data-bs-parent="#menuOptions">
                                    <div class="accordion-body">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="cat1">
                                            <label class="form-check-label" for="cat1">Electronics</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="cat2">
                                            <label class="form-check-label" for="cat2">Clothing</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="cat3">
                                            <label class="form-check-label" for="cat3">Books</label>
                                        </div>
                                        <button class="btn btn-sm btn-primary mt-2" onclick="addSelectedCategories()">
                                            Add Selected
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Custom Links -->
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#customCollapse">
                                        <i class="bx bx-link me-2"></i> Custom Links
                                    </button>
                                </h2>
                                <div id="customCollapse" class="accordion-collapse collapse" data-bs-parent="#menuOptions">
                                    <div class="accordion-body">
                                        <div class="mb-3">
                                            <label class="form-label">Link Text</label>
                                            <input type="text" class="form-control" id="customLinkText" placeholder="Menu item text">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">URL</label>
                                            <input type="url" class="form-control" id="customLinkUrl" placeholder="https://example.com">
                                        </div>
                                        <button class="btn btn-sm btn-primary" onclick="addCustomLink()">
                                            Add Custom Link
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Menu Settings -->
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Menu Settings</div>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Menu Name</label>
                            <input type="text" class="form-control" id="menuName" value="Main Menu">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Menu Location</label>
                            <select class="form-control" id="menuLocation">
                                <option value="header">Header</option>
                                <option value="footer">Footer</option>
                                <option value="sidebar">Sidebar</option>
                                <option value="mobile">Mobile</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="autoAdd">
                                <label class="form-check-label" for="autoAdd">
                                    Auto add new pages
                                </label>
                            </div>
                        </div>
                        
                        <button class="btn btn-danger btn-sm w-100" onclick="deleteMenu()">
                            <i class="bx bx-trash"></i> Delete Menu
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Menu Item Editor Modal -->
<div class="modal fade" id="menuItemModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Menu Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="menuItemForm">
                    <div class="mb-3">
                        <label class="form-label">Navigation Label *</label>
                        <input type="text" class="form-control" name="label" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">URL *</label>
                        <input type="text" class="form-control" name="url" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Title Attribute</label>
                        <input type="text" class="form-control" name="title">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">CSS Classes</label>
                        <input type="text" class="form-control" name="classes">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Target</label>
                        <select class="form-control" name="target">
                            <option value="_self">Same window/tab</option>
                            <option value="_blank">New window/tab</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="visible" id="itemVisible" checked>
                            <label class="form-check-label" for="itemVisible">
                                Visible in menu
                            </label>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveMenuItem()">Save Changes</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
.menu-builder {
    min-height: 400px;
}

.menu-items {
    list-style: none;
    padding: 0;
}

.menu-item {
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 4px;
    margin-bottom: 5px;
    padding: 0;
    cursor: move;
}

.menu-item-header {
    display: flex;
    justify-content: between;
    align-items: center;
    padding: 10px 15px;
}

.menu-item-info {
    display: flex;
    align-items: center;
    flex: 1;
}

.menu-item-info .handle {
    margin-right: 10px;
    color: #6c757d;
    cursor: grab;
}

.menu-item-info .menu-title {
    font-weight: 500;
    margin-right: 10px;
}

.menu-item-actions {
    display: flex;
    gap: 5px;
}

.menu-submenu {
    padding-left: 30px;
    padding-bottom: 10px;
}

.sub-item {
    background: #ffffff;
    border-left: 3px solid #007bff;
}

.sub-item .menu-item-header {
    padding: 8px 15px;
}

.menu-item:hover {
    background: #e9ecef;
}

.sub-item:hover {
    background: #f8f9fa;
}

.accordion-button {
    padding: 0.5rem 1rem;
}

.accordion-body {
    padding: 1rem;
}

.form-check {
    margin-bottom: 0.5rem;
}
</style>
@endsection

@section('scripts')
<script>
let currentMenuItemId = null;

// Edit menu item
function editMenuItem(itemId) {
    currentMenuItemId = itemId;
    
    // Sample data (in real app, fetch from server)
    const items = {
        1: { label: 'Home', url: '/', title: 'Home Page', classes: '', target: '_self', visible: true },
        2: { label: 'Products', url: '/products', title: 'Products', classes: '', target: '_self', visible: true },
        '2-1': { label: 'Electronics', url: '/products/electronics', title: 'Electronics', classes: '', target: '_self', visible: true },
        '2-2': { label: 'Clothing', url: '/products/clothing', title: 'Clothing', classes: '', target: '_self', visible: true },
        3: { label: 'About', url: '/about', title: 'About Us', classes: '', target: '_self', visible: true },
        4: { label: 'Contact', url: '/contact', title: 'Contact Us', classes: '', target: '_self', visible: true }
    };
    
    const item = items[itemId];
    if (item) {
        document.querySelector('input[name="label"]').value = item.label;
        document.querySelector('input[name="url"]').value = item.url;
        document.querySelector('input[name="title"]').value = item.title;
        document.querySelector('input[name="classes"]').value = item.classes;
        document.querySelector('select[name="target"]').value = item.target;
        document.querySelector('input[name="visible"]').checked = item.visible;
        
        $('#menuItemModal').modal('show');
    }
}

function saveMenuItem() {
    const form = document.getElementById('menuItemForm');
    const formData = new FormData(form);
    
    if (!formData.get('label') || !formData.get('url')) {
        if (typeof Swal !== 'undefined') {
            Swal.fire('Error', 'Please fill in all required fields', 'error');
        } else {
            alert('Please fill in all required fields');
        }
        return;
    }
    
    if (typeof Swal !== 'undefined') {
        Swal.fire('Saved!', 'Menu item has been updated.', 'success').then(() => {
            $('#menuItemModal').modal('hide');
            // Update the menu item in the UI
            updateMenuItemDisplay(currentMenuItemId, formData);
        });
    } else {
        alert('Menu item updated successfully!');
        $('#menuItemModal').modal('hide');
        updateMenuItemDisplay(currentMenuItemId, formData);
    }
}

function updateMenuItemDisplay(itemId, formData) {
    const menuItem = document.querySelector(`[data-id="${itemId}"]`);
    if (menuItem) {
        const titleSpan = menuItem.querySelector('.menu-title');
        const urlSpan = menuItem.querySelector('small');
        titleSpan.textContent = formData.get('label');
        urlSpan.textContent = `(${formData.get('url')})`;
    }
}

// Delete menu item
function deleteMenuItem(itemId) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Delete Menu Item',
            text: 'Are you sure you want to delete this menu item?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, Delete',
            confirmButtonColor: '#dc3545'
        }).then((result) => {
            if (result.isConfirmed) {
                document.querySelector(`[data-id="${itemId}"]`).remove();
                Swal.fire('Deleted!', 'Menu item has been deleted.', 'success');
            }
        });
    } else {
        if (confirm('Delete this menu item?')) {
            document.querySelector(`[data-id="${itemId}"]`).remove();
            alert('Menu item deleted successfully!');
        }
    }
}

// Add selected pages
function addSelectedPages() {
    const checkboxes = document.querySelectorAll('#pagesCollapse input[type="checkbox"]:checked');
    let addedCount = 0;
    
    checkboxes.forEach(checkbox => {
        const label = checkbox.nextElementSibling.textContent;
        addMenuItem(label, `/${label.toLowerCase().replace(/\s+/g, '-')}`);
        checkbox.checked = false;
        addedCount++;
    });
    
    if (addedCount > 0) {
        if (typeof Swal !== 'undefined') {
            Swal.fire('Added!', `${addedCount} pages added to menu.`, 'success');
        } else {
            alert(`${addedCount} pages added to menu.`);
        }
    }
}

// Add selected categories
function addSelectedCategories() {
    const checkboxes = document.querySelectorAll('#categoriesCollapse input[type="checkbox"]:checked');
    let addedCount = 0;
    
    checkboxes.forEach(checkbox => {
        const label = checkbox.nextElementSibling.textContent;
        addMenuItem(label, `/category/${label.toLowerCase()}`);
        checkbox.checked = false;
        addedCount++;
    });
    
    if (addedCount > 0) {
        if (typeof Swal !== 'undefined') {
            Swal.fire('Added!', `${addedCount} categories added to menu.`, 'success');
        } else {
            alert(`${addedCount} categories added to menu.`);
        }
    }
}

// Add custom link
function addCustomLink() {
    const text = document.getElementById('customLinkText').value;
    const url = document.getElementById('customLinkUrl').value;
    
    if (!text || !url) {
        if (typeof Swal !== 'undefined') {
            Swal.fire('Error', 'Please fill in both link text and URL', 'error');
        } else {
            alert('Please fill in both link text and URL');
        }
        return;
    }
    
    addMenuItem(text, url);
    document.getElementById('customLinkText').value = '';
    document.getElementById('customLinkUrl').value = '';
    
    if (typeof Swal !== 'undefined') {
        Swal.fire('Added!', 'Custom link added to menu.', 'success');
    } else {
        alert('Custom link added to menu.');
    }
}

// Add menu item to the list
function addMenuItem(label, url) {
    const menuItems = document.getElementById('menuItems');
    const newId = Date.now();
    
    const menuItemHtml = `
        <div class="menu-item" data-id="${newId}">
            <div class="menu-item-header">
                <div class="menu-item-info">
                    <i class="bx bx-menu handle"></i>
                    <span class="menu-title">${label}</span>
                    <small class="text-muted">(${url})</small>
                </div>
                <div class="menu-item-actions">
                    <button class="btn btn-sm btn-primary-light" onclick="editMenuItem('${newId}')">
                        <i class="bx bx-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-danger-light" onclick="deleteMenuItem('${newId}')">
                        <i class="bx bx-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    `;
    
    menuItems.insertAdjacentHTML('beforeend', menuItemHtml);
}

// Save menu
function saveMenu() {
    if (typeof Swal !== 'undefined') {
        Swal.fire('Saved!', 'Menu has been saved successfully.', 'success');
    } else {
        alert('Menu saved successfully!');
    }
}

// Create new menu
function createMenu() {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Create New Menu',
            input: 'text',
            inputLabel: 'Menu Name',
            inputPlaceholder: 'Enter menu name',
            showCancelButton: true,
            confirmButtonText: 'Create'
        }).then((result) => {
            if (result.isConfirmed && result.value) {
                const option = new Option(result.value, result.value.toLowerCase().replace(/\s+/g, '-'));
                document.getElementById('menuSelect').add(option);
                document.getElementById('menuSelect').value = option.value;
                Swal.fire('Created!', 'New menu has been created.', 'success');
            }
        });
    } else {
        const menuName = prompt('Enter menu name:');
        if (menuName) {
            const option = new Option(menuName, menuName.toLowerCase().replace(/\s+/g, '-'));
            document.getElementById('menuSelect').add(option);
            document.getElementById('menuSelect').value = option.value;
            alert('New menu created successfully!');
        }
    }
}

// Delete menu
function deleteMenu() {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Delete Menu',
            text: 'Are you sure you want to delete this menu?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, Delete',
            confirmButtonColor: '#dc3545'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire('Deleted!', 'Menu has been deleted.', 'success');
            }
        });
    } else {
        if (confirm('Delete this menu?')) {
            alert('Menu deleted successfully!');
        }
    }
}

// Make menu items sortable (basic implementation)
document.addEventListener('DOMContentLoaded', function() {
    // Add drag and drop functionality here if needed
    // This would require a library like Sortable.js for full functionality
});
</script>
@endsection
