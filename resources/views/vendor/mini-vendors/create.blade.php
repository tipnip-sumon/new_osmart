@extends('admin.layouts.app')

@section('title', 'Assign Mini Vendor')

@section('page-header')
<h3 class="page-title">Assign New Mini Vendor</h3>
<ul class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('vendor.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('vendor.mini-vendors.index') }}">Mini Vendors</a></li>
    <li class="breadcrumb-item active">Assign New</li>
</ul>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Assign New Mini Vendor</h1>
            <p class="text-muted">Select an affiliate user from your district to assign as mini vendor</p>
        </div>
        <a href="{{ route('vendor.mini-vendors.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Mini Vendors
        </a>
    </div>

    <!-- Assignment Form -->
    <div class="row justify-content-center">
        <div class="col-xl-8 col-lg-10">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Mini Vendor Assignment Form</h6>
                </div>
                <div class="card-body">
                    @if($potentialMiniVendors->count() > 0)
                        <!-- District-based selection form -->
                        <form action="{{ route('vendor.mini-vendors.store') }}" method="POST" id="assignmentForm">
                            @csrf
                            
                            <div class="form-group">
                                <label for="affiliate_id" class="form-label">
                                    <strong>Select Affiliate User</strong>
                                    <small class="text-muted">(From your district: {{ auth()->user()->district }})</small>
                                </label>
                                <select name="affiliate_id" id="affiliate_id" class="form-control @error('affiliate_id') is-invalid @enderror" required>
                                    <option value="">Choose an affiliate user...</option>
                                    @foreach($potentialMiniVendors as $affiliate)
                                    <option value="{{ $affiliate->id }}" {{ old('affiliate_id') == $affiliate->id ? 'selected' : '' }}>
                                        {{ $affiliate->name }} ({{ $affiliate->email }}) - {{ $affiliate->phone }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('affiliate_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="commission_rate" class="form-label">
                                    <strong>Commission Rate (%)</strong>
                                    <small class="text-muted">(Default: 3%)</small>
                                </label>
                                <input type="number" 
                                       name="commission_rate" 
                                       id="commission_rate" 
                                       class="form-control @error('commission_rate') is-invalid @enderror"
                                       step="0.01" 
                                       min="0" 
                                       max="100" 
                                       value="{{ old('commission_rate', '3.00') }}"
                                       placeholder="3.00">
                                @error('commission_rate')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">
                                    This commission will be automatically added to transfers of ৳100 or more made to this mini vendor
                                </small>
                            </div>

                            <!-- Selected User Info (will be populated via JavaScript) -->
                            <div id="userInfo" class="card bg-light mt-4" style="display: none;">
                                <div class="card-header">
                                    <h6 class="mb-0">Selected User Information</h6>
                                </div>
                                <div class="card-body" id="userInfoContent">
                                    <!-- Content will be populated by JavaScript -->
                                </div>
                            </div>

                            <div class="form-group mt-4">
                                <div class="row">
                                    <div class="col-md-6">
                                        <button type="submit" class="btn btn-primary btn-block">
                                            <i class="fas fa-user-plus"></i> Assign as Mini Vendor
                                        </button>
                                    </div>
                                    <div class="col-md-6">
                                        <a href="{{ route('vendor.mini-vendors.index') }}" class="btn btn-secondary btn-block">
                                            <i class="fas fa-times"></i> Cancel
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    @elseif(empty(auth()->user()->district))
                        <!-- Search Form for vendors without district -->
                        <form action="{{ route('vendor.mini-vendors.store') }}" method="POST" id="assignmentForm">
                            @csrf
                            
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle"></i>
                                <strong>District Not Set:</strong> Since your district is not set, you can search and select any affiliate user to assign as mini vendor.
                            </div>
                            
                            <div class="form-group">
                                <label for="user_search" class="form-label">
                                    <strong>Search Affiliate User</strong>
                                    <small class="text-muted">(Type name, email, or phone to search)</small>
                                </label>
                                <div class="input-group">
                                    <input type="text" 
                                           id="user_search" 
                                           class="form-control" 
                                           placeholder="Type to search affiliate users..." 
                                           autocomplete="off">
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-outline-secondary" id="clearSearch">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                                <div id="searchResults" class="list-group mt-2" style="display: none; max-height: 300px; overflow-y: auto;">
                                    <!-- Search results will appear here -->
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="affiliate_id" class="form-label">
                                    <strong>Selected User</strong>
                                </label>
                                <input type="hidden" name="affiliate_id" id="affiliate_id" required>
                                <div id="selectedUser" class="alert alert-info" style="display: none;">
                                    <!-- Selected user info will appear here -->
                                </div>
                                <div id="noSelection" class="text-muted">
                                    <i class="fas fa-search"></i> Please search and select an affiliate user above
                                </div>
                                @error('affiliate_id')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="commission_rate" class="form-label">
                                    <strong>Commission Rate (%)</strong>
                                    <small class="text-muted">(Default: 3%)</small>
                                </label>
                                <input type="number" 
                                       name="commission_rate" 
                                       id="commission_rate" 
                                       class="form-control @error('commission_rate') is-invalid @enderror"
                                       step="0.01" 
                                       min="0" 
                                       max="100" 
                                       value="{{ old('commission_rate', '3.00') }}"
                                       placeholder="3.00">
                                @error('commission_rate')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">
                                    This commission will be automatically added to transfers of ৳100 or more made to this mini vendor
                                </small>
                            </div>

                            <div class="form-group mt-4">
                                <div class="row">
                                    <div class="col-md-6">
                                        <button type="submit" class="btn btn-primary btn-block">
                                            <i class="fas fa-user-plus"></i> Assign as Mini Vendor
                                        </button>
                                    </div>
                                    <div class="col-md-6">
                                        <a href="{{ route('vendor.mini-vendors.index') }}" class="btn btn-secondary btn-block">
                                            <i class="fas fa-times"></i> Cancel
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    @else
                        <!-- No available users message -->
                        <div class="text-center py-5">
                            <i class="fas fa-users fa-3x text-gray-300 mb-3"></i>
                            <h5 class="text-gray-600">No Available Affiliate Users</h5>
                            <p class="text-muted">
                                There are no affiliate users in your district ({{ auth()->user()->district }}) who can be assigned as mini vendors.
                                <br>
                                All eligible users may already be assigned or there might be no affiliate users in your area.
                            </p>
                            <a href="{{ route('vendor.mini-vendors.index') }}" class="btn btn-secondary mt-3">
                                <i class="fas fa-arrow-left"></i> Back to Mini Vendors
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Info Card -->
    <div class="row justify-content-center mt-4">
        <div class="col-xl-8 col-lg-10">
            <div class="card border-left-info shadow">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="text-info">
                                <i class="fas fa-info-circle"></i> How Mini Vendor System Works
                            </h5>
                            <ul class="text-muted mb-0">
                                <li><strong>Role Upgrade:</strong> When you assign an affiliate as mini vendor, their role automatically changes to "vendor"</li>
                                <li><strong>Email Notification:</strong> The assigned user will receive a welcome email with their new vendor access details</li>
                                <li><strong>Role Revert:</strong> When you remove a mini vendor assignment, their role changes back to "affiliate"</li>
                                <li><strong>Removal Notice:</strong> The user will receive an email notification about the assignment removal</li>
                                <li>If your district is set, you can only assign affiliate users from your same district</li>
                                <li>If your district is not set, you can search and assign any affiliate user as mini vendor</li>
                                <li>When you transfer ৳100 or more to a mini vendor, commission is automatically added</li>
                                <li>Default commission rate is 3%, but you can customize it during assignment</li>
                                <li>Mini vendors can be activated, deactivated, or suspended at any time</li>
                                <li>Commission earnings are tracked and displayed in the mini vendor dashboard</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // User selection data for district-based selection
    const usersData = @json($potentialMiniVendors->keyBy('id'));
    
    // Handle normal dropdown change (when district is set)
    $('#affiliate_id').change(function() {
        const selectedId = $(this).val();
        if (selectedId && usersData[selectedId]) {
            const user = usersData[selectedId];
            showUserInfo(user);
        } else {
            hideUserInfo();
        }
    });
    
    // Handle user search (when district is not set)
    let searchTimeout;
    $('#user_search').on('input', function() {
        const query = $(this).val().trim();
        
        clearTimeout(searchTimeout);
        
        if (query.length < 2) {
            $('#searchResults').hide().empty();
            return;
        }
        
        searchTimeout = setTimeout(function() {
            searchUsers(query);
        }, 300);
    });
    
    // Clear search
    $('#clearSearch').click(function() {
        $('#user_search').val('');
        $('#searchResults').hide().empty();
        clearSelectedUser();
    });
    
    // Search users via AJAX
    function searchUsers(query) {
        $.ajax({
            url: '{{ route("vendor.mini-vendors.search-users") }}',
            method: 'GET',
            data: { q: query },
            success: function(users) {
                displaySearchResults(users);
            },
            error: function() {
                $('#searchResults').html('<div class="list-group-item text-danger"><i class="fas fa-exclamation-triangle"></i> Search failed. Please try again.</div>').show();
            }
        });
    }
    
    // Display search results
    function displaySearchResults(users) {
        const resultsHtml = users.map(user => `
            <a href="#" class="list-group-item list-group-item-action user-select-item" data-user-id="${user.id}" data-user='${JSON.stringify(user)}'>
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <strong>${user.name}</strong><br>
                        <small class="text-muted">${user.email} ${user.phone ? '• ' + user.phone : ''}</small><br>
                        <small class="text-info">District: ${user.district || 'Not set'}</small>
                    </div>
                    <span class="badge badge-info">${user.role}</span>
                </div>
            </a>
        `).join('');
        
        if (users.length === 0) {
            $('#searchResults').html('<div class="list-group-item text-muted"><i class="fas fa-search"></i> No affiliate users found matching your search.</div>').show();
        } else {
            $('#searchResults').html(resultsHtml).show();
        }
    }
    
    // Handle user selection from search results
    $(document).on('click', '.user-select-item', function(e) {
        e.preventDefault();
        const userId = $(this).data('user-id');
        const userData = $(this).data('user');
        
        selectUser(userId, userData);
        $('#searchResults').hide();
        $('#user_search').val(userData.name);
    });
    
    // Select a user
    function selectUser(userId, userData) {
        $('#affiliate_id').val(userId);
        
        const selectedUserHtml = `
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <strong>${userData.name}</strong><br>
                    <small><strong>Email:</strong> ${userData.email}</small><br>
                    <small><strong>Phone:</strong> ${userData.phone || 'N/A'}</small><br>
                    <small><strong>District:</strong> ${userData.district || 'Not set'}</small><br>
                    <small><strong>Role:</strong> <span class="badge badge-info">${userData.role}</span></small>
                </div>
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="clearSelectedUser()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        
        $('#selectedUser').html(selectedUserHtml).show();
        $('#noSelection').hide();
    }
    
    // Clear selected user
    function clearSelectedUser() {
        $('#affiliate_id').val('');
        $('#selectedUser').hide();
        $('#noSelection').show();
        $('#user_search').val('');
    }
    
    // Make clearSelectedUser global
    window.clearSelectedUser = clearSelectedUser;
    
    function showUserInfo(user) {
        const userInfoHtml = `
            <div class="row">
                <div class="col-md-8">
                    <h6 class="mb-2">${user.name}</h6>
                    <p class="mb-1"><strong>Email:</strong> ${user.email}</p>
                    <p class="mb-1"><strong>Phone:</strong> ${user.phone || 'N/A'}</p>
                    <p class="mb-1"><strong>District:</strong> ${user.district || 'N/A'}</p>
                    <p class="mb-0"><strong>Role:</strong> <span class="badge badge-info">${user.role}</span></p>
                </div>
                <div class="col-md-4 text-right">
                    <img src="${user.avatar ? '/uploads/users/' + user.avatar : '/admin-assets/img/undraw_profile.svg'}" 
                         class="img-thumbnail" style="width: 80px; height: 80px; object-fit: cover;" alt="User Avatar">
                </div>
            </div>
        `;
        
        $('#userInfoContent').html(userInfoHtml);
        $('#userInfo').show();
    }
    
    function hideUserInfo() {
        $('#userInfo').hide();
    }
    
    // Form submission confirmation
    $('#assignmentForm').submit(function(e) {
        const selectedUserId = $('#affiliate_id').val();
        if (!selectedUserId) {
            alert('Please select an affiliate user to assign as mini vendor.');
            e.preventDefault();
            return;
        }
        
        const selectedUserText = $('#user_search').val() || $('#affiliate_id option:selected').text();
        const commissionRate = $('#commission_rate').val();
        
        if (!confirm(`Are you sure you want to assign "${selectedUserText}" as mini vendor with ${commissionRate}% commission rate?`)) {
            e.preventDefault();
        }
    });
});
</script>
@endpush