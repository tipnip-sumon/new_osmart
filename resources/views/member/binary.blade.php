@extends('member.layouts.app')

@section('title', 'Binary Tree Structure')

@section('content')
<div class="main-content">
    <div class="container-fluid">
        
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">
                Binary Tree Structure
                @if(isset($rootUser) && $rootUser && $rootUser->id !== Auth::user()->id)
                    <small class="text-info">- Viewing {{ $rootUser->name ?? $rootUser->username }}'s Tree</small>
                @endif
            </h1>
            <div class="ms-md-1 ms-0">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('member.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Binary Tree</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- Page Header Close -->

        <!-- Binary Tree Search -->
        <div class="row mb-4">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="fe fe-search me-2"></i>Binary Tree Navigation
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row align-items-end">
                            <div class="col-md-8">
                                <label class="form-label">Search User in Binary Tree</label>
                                <input type="text" class="form-control" id="binaryTreeSearchInput" 
                                       placeholder="Enter username or referral code to view their tree..." 
                                       value="{{ request('tree_user') }}">
                            </div>
                            <div class="col-md-4">
                                <button class="btn btn-primary w-100" onclick="searchUserTree()">
                                    <i class="fe fe-git-branch me-1"></i> View User's Tree
                                </button>
                            </div>
                        </div>
                        <small class="text-muted">
                            <i class="fe fe-info me-1"></i>
                            Search for any user in your network to view their binary tree structure as root
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notifications -->
        @if(session('error'))
            <div class="row mb-4">
                <div class="col-xl-12">
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fe fe-alert-circle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            </div>
        @endif

        @if(session('success'))
            <div class="row mb-4">
                <div class="col-xl-12">
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fe fe-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            </div>
        @endif

        <!-- Binary Tree Structure -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="fe fe-git-branch me-2"></i><span class="d-none d-md-inline">2-Level Binary Tree Network</span><span class="d-md-none">Binary Tree</span>
                            @if(isset($rootUser) && $rootUser && $rootUser->id !== Auth::user()->id)
                                <span class="badge bg-info ms-2">{{ $rootUser->name ?? $rootUser->username }}'s Tree</span>
                            @elseif(request('root_user'))
                                <span class="badge bg-info ms-2">Subtree View</span>
                            @endif
                        </div>
                        <div class="ms-auto d-flex align-items-center flex-wrap gap-2">
                            <!-- Zoom Controls -->
                            <div class="zoom-controls d-flex align-items-center me-2">
                                <button class="btn btn-sm btn-outline-secondary" onclick="zoomOut()" title="Zoom Out">
                                    <i class="fe fe-zoom-out"></i>
                                </button>
                                <span class="zoom-level mx-2 small">100%</span>
                                <button class="btn btn-sm btn-outline-secondary" onclick="zoomIn()" title="Zoom In">
                                    <i class="fe fe-zoom-in"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-secondary ms-1" onclick="resetZoom()" title="Reset Zoom">
                                    <i class="fe fe-maximize-2"></i>
                                </button>
                            </div>
                            
                            @if(isset($rootUser) && $rootUser && $rootUser->id !== Auth::user()->id)
                                <button class="btn btn-sm btn-secondary me-2 d-none d-md-inline-block" onclick="backToMainTree()">
                                    <i class="fe fe-arrow-left me-1"></i>Back to My Tree
                                </button>
                                <button class="btn btn-sm btn-secondary me-2 d-md-none" onclick="backToMainTree()" title="Back to My Tree">
                                    <i class="fe fe-arrow-left"></i>
                                </button>
                            @endif
                            <button class="btn btn-sm btn-primary d-none d-md-inline-block" onclick="refreshBinaryTree()">
                                <i class="fe fe-refresh-cw me-1"></i>Refresh
                            </button>
                            <button class="btn btn-sm btn-primary d-md-none" onclick="refreshBinaryTree()" title="Refresh">
                                <i class="fe fe-refresh-cw"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Mobile Scroll Hint -->
                        <div class="alert alert-info d-md-none mb-3" role="alert">
                            <small><i class="fe fe-info me-1"></i>Pinch to zoom or use zoom controls. Swipe to navigate the tree.</small>
                        </div>
                        
                        <!-- Color Legend for Point Status -->
                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="d-flex align-items-center justify-content-center flex-wrap gap-3 p-2 bg-light rounded">
                                    <small class="fw-semibold text-muted me-2">User Status Legend:</small>
                                    <div class="d-flex align-items-center">
                                        <div class="legend-circle qualified me-1"></div>
                                        <small class="text-success fw-medium">100+ Points (Qualified)</small>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <div class="legend-circle partial me-1"></div>
                                        <small class="text-warning fw-medium">1-99 Points (Partial)</small>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <div class="legend-circle none me-1"></div>
                                        <small class="text-danger fw-medium">0 Points (Inactive)</small>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <div class="legend-circle available me-1"></div>
                                        <small class="text-muted fw-medium">Available Position</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Binary Tree Visualization -->
                        <div class="binary-tree-wrapper">
                            <div class="binary-tree-container" id="binaryTreeContainer">
                            
                            <!-- Level 0 - Main User (Root) -->
                            <div class="tree-level level-0">
                                <div class="tree-node main-user" data-user-id="{{ $binaryTree['main_user']['id'] }}" data-user-name="{{ $binaryTree['main_user']['name'] }}" data-left-count="{{ $binaryTree['main_user']['left_count'] }}" data-right-count="{{ $binaryTree['main_user']['right_count'] }}" data-total-business="{{ number_format($binaryTree['main_user']['total_points'], 0) }}" data-rank="{{ $binaryTree['main_user']['rank'] }}">
                                    <div class="tooltip-content">
                                        {{ $binaryTree['main_user']['name'] }}
                                        Level: ROOT
                                        Left: {{ $binaryTree['main_user']['left_count'] }} | Right: {{ $binaryTree['main_user']['right_count'] }}
                                        Points: {{ number_format($binaryTree['main_user']['total_points'], 0) }} pts
                                        Active Points: {{ number_format($binaryTree['main_user']['active_points'], 0) }} pts
                                        Rank: {{ $binaryTree['main_user']['rank'] }}
                                        Status: {{ $binaryTree['main_user']['point_status'] ?? 'none' }}
                                    </div>
                                    <div class="user-circle main {{ $binaryTree['main_user']['exists'] ? $binaryTree['main_user']['point_status'] ?? 'none' : 'empty' }}">
                                        @if($binaryTree['main_user']['exists'])
                                            <img src="{{ $binaryTree['main_user']['avatar'] ? asset($binaryTree['main_user']['avatar']) : asset('assets/images/avatars/default-customer.svg') }}" 
                                                 alt="{{ $binaryTree['main_user']['name'] }}" 
                                                 class="user-avatar" 
                                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                            <div class="image-fallback" style="display: none; align-items: center; justify-content: center; font-size: 24px; width: 100%; height: 100%;">👤</div>
                                        @else
                                            <i class="fe fe-user empty-icon"></i>
                                        @endif
                                        <div class="user-level">ROOT</div>
                                    </div>
                                    <div class="user-name">{{ $binaryTree['main_user']['name'] }}</div>
                                    @if($binaryTree['main_user']['exists'])
                                        <div class="user-stats">
                                            <span class="left-count">L: {{ $binaryTree['main_user']['left_count'] }}</span>
                                            <span class="right-count">R: {{ $binaryTree['main_user']['right_count'] }}</span>
                                        </div>
                                    @else
                                        <div class="empty-label">Available Position</div>
                                    @endif
                                </div>
                            </div>

                            <!-- Level 1 - Left and Right Users -->
                            <div class="tree-level level-1">
                                <!-- Left User -->
                                <div class="tree-node left-user" data-user-id="{{ $binaryTree['level_1']['left']['id'] }}" data-user-name="{{ $binaryTree['level_1']['left']['name'] }}" data-left-count="{{ $binaryTree['level_1']['left']['left_count'] }}" data-right-count="{{ $binaryTree['level_1']['left']['right_count'] }}" data-total-business="{{ number_format($binaryTree['level_1']['left']['total_points'], 0) }}" data-rank="{{ $binaryTree['level_1']['left']['rank'] }}">
                                    <div class="tooltip-content">
                                        {{ $binaryTree['level_1']['left']['name'] }}
                                        Level: L1 (Left)
                                        Left: {{ $binaryTree['level_1']['left']['left_count'] }} | Right: {{ $binaryTree['level_1']['left']['right_count'] }}
                                        Points: {{ number_format($binaryTree['level_1']['left']['total_points'], 0) }} pts
                                        Active Points: {{ number_format($binaryTree['level_1']['left']['active_points'], 0) }} pts
                                        Rank: {{ $binaryTree['level_1']['left']['rank'] }}
                                        Status: {{ $binaryTree['level_1']['left']['point_status'] ?? 'none' }}
                                    </div>
                                    <div class="user-circle left {{ $binaryTree['level_1']['left']['exists'] ? $binaryTree['level_1']['left']['point_status'] ?? 'none' : 'empty' }}">
                                        @if($binaryTree['level_1']['left']['exists'])
                                            <img src="{{ $binaryTree['level_1']['left']['avatar'] ? asset($binaryTree['level_1']['left']['avatar']) : asset('assets/images/avatars/default-customer.svg') }}" 
                                                 alt="{{ $binaryTree['level_1']['left']['name'] }}" 
                                                 class="user-avatar" 
                                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                            <div class="image-fallback" style="display: none; align-items: center; justify-content: center; font-size: 24px; width: 100%; height: 100%;">👤</div>
                                        @else
                                            <i class="fe fe-user empty-icon"></i>
                                        @endif
                                        <div class="user-level">L1</div>
                                    </div>
                                    <div class="user-name">{{ $binaryTree['level_1']['left']['name'] }}</div>
                                    @if($binaryTree['level_1']['left']['exists'])
                                        <div class="user-stats">
                                            <span class="left-count">L: {{ $binaryTree['level_1']['left']['left_count'] }}</span>
                                            <span class="right-count">R: {{ $binaryTree['level_1']['left']['right_count'] }}</span>
                                        </div>
                                    @else
                                        <div class="empty-label">Available</div>
                                    @endif
                                </div>

                                <!-- Right User -->
                                <div class="tree-node right-user" data-user-id="{{ $binaryTree['level_1']['right']['id'] }}" data-user-name="{{ $binaryTree['level_1']['right']['name'] }}" data-left-count="{{ $binaryTree['level_1']['right']['left_count'] }}" data-right-count="{{ $binaryTree['level_1']['right']['right_count'] }}" data-total-business="{{ number_format($binaryTree['level_1']['right']['total_points'], 0) }}" data-rank="{{ $binaryTree['level_1']['right']['rank'] }}">
                                    <div class="tooltip-content">
                                        {{ $binaryTree['level_1']['right']['name'] }}
                                        Level: L1 (Right)
                                        Left: {{ $binaryTree['level_1']['right']['left_count'] }} | Right: {{ $binaryTree['level_1']['right']['right_count'] }}
                                        Points: {{ number_format($binaryTree['level_1']['right']['total_points'], 0) }} pts
                                        Active Points: {{ number_format($binaryTree['level_1']['right']['active_points'], 0) }} pts
                                        Rank: {{ $binaryTree['level_1']['right']['rank'] }}
                                        Status: {{ $binaryTree['level_1']['right']['point_status'] ?? 'none' }}
                                    </div>
                                    <div class="user-circle right {{ $binaryTree['level_1']['right']['exists'] ? $binaryTree['level_1']['right']['point_status'] ?? 'none' : 'empty' }}">
                                        @if($binaryTree['level_1']['right']['exists'])
                                            <img src="{{ $binaryTree['level_1']['right']['avatar'] ? asset($binaryTree['level_1']['right']['avatar']) : asset('assets/images/avatars/default-customer.svg') }}" 
                                                 alt="{{ $binaryTree['level_1']['right']['name'] }}" 
                                                 class="user-avatar" 
                                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                            <div class="image-fallback" style="display: none; align-items: center; justify-content: center; font-size: 24px; width: 100%; height: 100%;">👤</div>
                                        @else
                                            <i class="fe fe-user empty-icon"></i>
                                        @endif
                                        <div class="user-level">L1</div>
                                    </div>
                                    <div class="user-name">{{ $binaryTree['level_1']['right']['name'] }}</div>
                                    @if($binaryTree['level_1']['right']['exists'])
                                        <div class="user-stats">
                                            <span class="left-count">L: {{ $binaryTree['level_1']['right']['left_count'] }}</span>
                                            <span class="right-count">R: {{ $binaryTree['level_1']['right']['right_count'] }}</span>
                                        </div>
                                    @else
                                        <div class="empty-label">Available</div>
                                    @endif
                                </div>
                            </div>

                            <!-- Level 2 - 4 Users (2 under each Level 1 user) -->
                            <div class="tree-level level-2">
                                <!-- Left-Left User -->
                                <div class="tree-node left-left" data-user-id="{{ $binaryTree['level_2']['left_left']['id'] }}" data-user-name="{{ $binaryTree['level_2']['left_left']['name'] }}" data-left-count="{{ $binaryTree['level_2']['left_left']['left_count'] }}" data-right-count="{{ $binaryTree['level_2']['left_left']['right_count'] }}" data-total-business="{{ number_format($binaryTree['level_2']['left_left']['total_points'], 0) }}" data-rank="{{ $binaryTree['level_2']['left_left']['rank'] }}">
                                    <div class="tooltip-content">
                                        {{ $binaryTree['level_2']['left_left']['name'] }}
                                        Level: L2 (Left-Left)
                                        Left: {{ $binaryTree['level_2']['left_left']['left_count'] }} | Right: {{ $binaryTree['level_2']['left_left']['right_count'] }}
                                        Points: {{ number_format($binaryTree['level_2']['left_left']['total_points'], 0) }} pts
                                        Active Points: {{ number_format($binaryTree['level_2']['left_left']['active_points'], 0) }} pts
                                        Rank: {{ $binaryTree['level_2']['left_left']['rank'] }}
                                        Status: {{ $binaryTree['level_2']['left_left']['point_status'] ?? 'none' }}
                                    </div>
                                    <div class="user-circle left-child {{ $binaryTree['level_2']['left_left']['exists'] ? $binaryTree['level_2']['left_left']['point_status'] ?? 'none' : 'empty' }}">
                                        @if($binaryTree['level_2']['left_left']['exists'])
                                            <img src="{{ $binaryTree['level_2']['left_left']['avatar'] ? asset($binaryTree['level_2']['left_left']['avatar']) : asset('assets/images/avatars/default-customer.svg') }}" 
                                                 alt="{{ $binaryTree['level_2']['left_left']['name'] }}" 
                                                 class="user-avatar" 
                                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                            <div class="image-fallback" style="display: none; align-items: center; justify-content: center; font-size: 20px; width: 100%; height: 100%;">👤</div>
                                        @else
                                            <i class="fe fe-user empty-icon"></i>
                                        @endif
                                        <div class="user-level">L2</div>
                                    </div>
                                    <div class="user-name">{{ $binaryTree['level_2']['left_left']['name'] }}</div>
                                    @if($binaryTree['level_2']['left_left']['exists'])
                                        <div class="user-stats">
                                            <span class="left-count">L: {{ $binaryTree['level_2']['left_left']['left_count'] }}</span>
                                            <span class="right-count">R: {{ $binaryTree['level_2']['left_left']['right_count'] }}</span>
                                        </div>
                                        <button class="btn btn-sm btn-outline-primary expand-btn" onclick="expandUser('{{ $binaryTree['level_2']['left_left']['id'] }}')">
                                            <i class="fe fe-chevron-down"></i>
                                        </button>
                                    @else
                                        <div class="empty-label">Available</div>
                                        <button class="btn btn-sm btn-outline-secondary" disabled>
                                            <i class="fe fe-plus"></i>
                                        </button>
                                    @endif
                                </div>

                                <!-- Left-Right User -->
                                <div class="tree-node left-right" data-user-id="{{ $binaryTree['level_2']['left_right']['id'] }}" data-user-name="{{ $binaryTree['level_2']['left_right']['name'] }}" data-left-count="{{ $binaryTree['level_2']['left_right']['left_count'] }}" data-right-count="{{ $binaryTree['level_2']['left_right']['right_count'] }}" data-total-business="{{ number_format($binaryTree['level_2']['left_right']['total_points'], 0) }}" data-rank="{{ $binaryTree['level_2']['left_right']['rank'] }}">
                                    <div class="tooltip-content">
                                        {{ $binaryTree['level_2']['left_right']['name'] }}
                                        Level: L2 (Left-Right)
                                        Left: {{ $binaryTree['level_2']['left_right']['left_count'] }} | Right: {{ $binaryTree['level_2']['left_right']['right_count'] }}
                                        Points: {{ number_format($binaryTree['level_2']['left_right']['total_points'], 0) }} pts
                                        Active Points: {{ number_format($binaryTree['level_2']['left_right']['active_points'], 0) }} pts
                                        Rank: {{ $binaryTree['level_2']['left_right']['rank'] }}
                                        Status: {{ $binaryTree['level_2']['left_right']['point_status'] ?? 'none' }}
                                    </div>
                                    <div class="user-circle left-child {{ $binaryTree['level_2']['left_right']['exists'] ? $binaryTree['level_2']['left_right']['point_status'] ?? 'none' : 'empty' }}">
                                        @if($binaryTree['level_2']['left_right']['exists'])
                                            <img src="{{ $binaryTree['level_2']['left_right']['avatar'] ? asset($binaryTree['level_2']['left_right']['avatar']) : asset('assets/images/avatars/default-customer.svg') }}" 
                                                 alt="{{ $binaryTree['level_2']['left_right']['name'] }}" 
                                                 class="user-avatar" 
                                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                            <div class="image-fallback" style="display: none; align-items: center; justify-content: center; font-size: 20px; width: 100%; height: 100%;">👤</div>
                                        @else
                                            <i class="fe fe-user empty-icon"></i>
                                        @endif
                                        <div class="user-level">L2</div>
                                    </div>
                                    <div class="user-name">{{ $binaryTree['level_2']['left_right']['name'] }}</div>
                                    @if($binaryTree['level_2']['left_right']['exists'])
                                        <div class="user-stats">
                                            <span class="left-count">L: {{ $binaryTree['level_2']['left_right']['left_count'] }}</span>
                                            <span class="right-count">R: {{ $binaryTree['level_2']['left_right']['right_count'] }}</span>
                                        </div>
                                        <button class="btn btn-sm btn-outline-primary expand-btn" onclick="expandUser('{{ $binaryTree['level_2']['left_right']['id'] }}')">
                                            <i class="fe fe-chevron-down"></i>
                                        </button>
                                    @else
                                        <div class="empty-label">Available</div>
                                        <button class="btn btn-sm btn-outline-secondary" disabled>
                                            <i class="fe fe-plus"></i>
                                        </button>
                                    @endif
                                </div>

                                <!-- Right-Left User -->
                                <div class="tree-node right-left" data-user-id="{{ $binaryTree['level_2']['right_left']['id'] }}" data-user-name="{{ $binaryTree['level_2']['right_left']['name'] }}" data-left-count="{{ $binaryTree['level_2']['right_left']['left_count'] }}" data-right-count="{{ $binaryTree['level_2']['right_left']['right_count'] }}" data-total-business="{{ number_format($binaryTree['level_2']['right_left']['total_points'], 0) }}" data-rank="{{ $binaryTree['level_2']['right_left']['rank'] }}">
                                    <div class="tooltip-content">
                                        {{ $binaryTree['level_2']['right_left']['name'] }}
                                        Level: L2 (Right-Left)
                                        Left: {{ $binaryTree['level_2']['right_left']['left_count'] }} | Right: {{ $binaryTree['level_2']['right_left']['right_count'] }}
                                        Points: {{ number_format($binaryTree['level_2']['right_left']['total_points'], 0) }} pts
                                        Active Points: {{ number_format($binaryTree['level_2']['right_left']['active_points'], 0) }} pts
                                        Rank: {{ $binaryTree['level_2']['right_left']['rank'] }}
                                        Status: {{ $binaryTree['level_2']['right_left']['point_status'] ?? 'none' }}
                                    </div>
                                    <div class="user-circle right-child {{ $binaryTree['level_2']['right_left']['exists'] ? $binaryTree['level_2']['right_left']['point_status'] ?? 'none' : 'empty' }}">
                                        @if($binaryTree['level_2']['right_left']['exists'])
                                            <img src="{{ $binaryTree['level_2']['right_left']['avatar'] ? asset($binaryTree['level_2']['right_left']['avatar']) : asset('assets/images/avatars/default-customer.svg') }}" 
                                                 alt="{{ $binaryTree['level_2']['right_left']['name'] }}" 
                                                 class="user-avatar" 
                                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                            <div class="image-fallback" style="display: none; align-items: center; justify-content: center; font-size: 20px; width: 100%; height: 100%;">👤</div>
                                        @else
                                            <i class="fe fe-user empty-icon"></i>
                                        @endif
                                        <div class="user-level">L2</div>
                                    </div>
                                    <div class="user-name">{{ $binaryTree['level_2']['right_left']['name'] }}</div>
                                    @if($binaryTree['level_2']['right_left']['exists'])
                                        <div class="user-stats">
                                            <span class="left-count">L: {{ $binaryTree['level_2']['right_left']['left_count'] }}</span>
                                            <span class="right-count">R: {{ $binaryTree['level_2']['right_left']['right_count'] }}</span>
                                        </div>
                                        <button class="btn btn-sm btn-outline-primary expand-btn" onclick="expandUser('{{ $binaryTree['level_2']['right_left']['id'] }}')">
                                            <i class="fe fe-chevron-down"></i>
                                        </button>
                                    @else
                                        <div class="empty-label">Available</div>
                                        <button class="btn btn-sm btn-outline-secondary" disabled>
                                            <i class="fe fe-plus"></i>
                                        </button>
                                    @endif
                                </div>

                                <!-- Right-Right User -->
                                <div class="tree-node right-right" data-user-id="{{ $binaryTree['level_2']['right_right']['id'] }}" data-user-name="{{ $binaryTree['level_2']['right_right']['name'] }}" data-left-count="{{ $binaryTree['level_2']['right_right']['left_count'] }}" data-right-count="{{ $binaryTree['level_2']['right_right']['right_count'] }}" data-total-business="{{ number_format($binaryTree['level_2']['right_right']['total_points'], 0) }}" data-rank="{{ $binaryTree['level_2']['right_right']['rank'] }}">
                                    <div class="tooltip-content">
                                        {{ $binaryTree['level_2']['right_right']['name'] }}
                                        Level: L2 (Right-Right)
                                        Left: {{ $binaryTree['level_2']['right_right']['left_count'] }} | Right: {{ $binaryTree['level_2']['right_right']['right_count'] }}
                                        Points: {{ number_format($binaryTree['level_2']['right_right']['total_points'], 0) }} pts
                                        Active Points: {{ number_format($binaryTree['level_2']['right_right']['active_points'], 0) }} pts
                                        Rank: {{ $binaryTree['level_2']['right_right']['rank'] }}
                                        Status: {{ $binaryTree['level_2']['right_right']['point_status'] ?? 'none' }}
                                    </div>
                                    <div class="user-circle right-child {{ $binaryTree['level_2']['right_right']['exists'] ? $binaryTree['level_2']['right_right']['point_status'] ?? 'none' : 'empty' }}">
                                        @if($binaryTree['level_2']['right_right']['exists'])
                                            <img src="{{ $binaryTree['level_2']['right_right']['avatar'] ? asset($binaryTree['level_2']['right_right']['avatar']) : asset('assets/images/avatars/default-customer.svg') }}" 
                                                 alt="{{ $binaryTree['level_2']['right_right']['name'] }}" 
                                                 class="user-avatar" 
                                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                            <div class="image-fallback" style="display: none; align-items: center; justify-content: center; font-size: 20px; width: 100%; height: 100%;">👤</div>
                                        @else
                                            <i class="fe fe-user empty-icon"></i>
                                        @endif
                                        <div class="user-level">L2</div>
                                    </div>
                                    <div class="user-name">{{ $binaryTree['level_2']['right_right']['name'] }}</div>
                                    @if($binaryTree['level_2']['right_right']['exists'])
                                        <div class="user-stats">
                                            <span class="left-count">L: {{ $binaryTree['level_2']['right_right']['left_count'] }}</span>
                                            <span class="right-count">R: {{ $binaryTree['level_2']['right_right']['right_count'] }}</span>
                                        </div>
                                        <button class="btn btn-sm btn-outline-primary expand-btn" onclick="expandUser('{{ $binaryTree['level_2']['right_right']['id'] }}')">
                                            <i class="fe fe-chevron-down"></i>
                                        </button>
                                    @else
                                        <div class="empty-label">Available</div>
                                        <button class="btn btn-sm btn-outline-secondary" disabled>
                                            <i class="fe fe-plus"></i>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>

        <!-- Team Summary -->
        <div class="row mt-4">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="fe fe-users me-2"></i>Team Performance Summary
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                                <div class="text-center p-3 border rounded h-100">
                                    <div class="mb-2">
                                        <i class="fe fe-users text-primary fs-24"></i>
                                    </div>
                                    <h5 class="text-primary mb-1 fw-bold">{{ $binaryTree['main_user']['left_count'] + $binaryTree['main_user']['right_count'] + 1 }}</h5>
                                    <small class="text-muted">Total Team Members</small>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                                <div class="text-center p-3 border rounded h-100">
                                    <div class="mb-2">
                                        <i class="fe fe-arrow-left text-success fs-24"></i>
                                    </div>
                                    <h5 class="text-success mb-1 fw-bold">{{ $binaryTree['main_user']['left_count'] }}</h5>
                                    <small class="text-muted">Left Side Team</small>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                                <div class="text-center p-3 border rounded h-100">
                                    <div class="mb-2">
                                        <i class="fe fe-arrow-right text-warning fs-24"></i>
                                    </div>
                                    <h5 class="text-warning mb-1 fw-bold">{{ $binaryTree['main_user']['right_count'] }}</h5>
                                    <small class="text-muted">Right Side Team</small>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                                <div class="text-center p-3 border rounded h-100">
                                    <div class="mb-2">
                                        <i class="fe fe-dollar-sign text-info fs-24"></i>
                                    </div>
                                    <h5 class="text-info mb-1 fw-bold">{{ number_format($binaryTree['main_user']['total_points'], 0) }}</h5>
                                    <small class="text-muted">Total Points Earned</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Point-Based Matching Statistics -->
        <div class="row mb-4">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="fe fe-trending-up me-2"></i>Point-Based Matching Overview
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-3">
                                <div class="border rounded p-3 h-100">
                                    <div class="mb-2">
                                        <i class="fe fe-arrow-left text-success fs-24"></i>
                                    </div>
                                    <h5 class="text-success mb-1 fw-bold">{{ number_format($binaryTree['main_user']['left_leg_points'], 0) }}</h5>
                                    <small class="text-muted">Left Leg Points</small>
                                    <div class="mt-2">
                                        <span class="badge bg-success">{{ $binaryTree['main_user']['left_count'] }} Members</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-3">
                                <div class="border rounded p-3 h-100">
                                    <div class="mb-2">
                                        <i class="fe fe-arrow-right text-primary fs-24"></i>
                                    </div>
                                    <h5 class="text-primary mb-1 fw-bold">{{ number_format($binaryTree['main_user']['right_leg_points'], 0) }}</h5>
                                    <small class="text-muted">Right Leg Points</small>
                                    <div class="mt-2">
                                        <span class="badge bg-primary">{{ $binaryTree['main_user']['right_count'] }} Members</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-3">
                                <div class="border rounded p-3 h-100">
                                    <div class="mb-2">
                                        <i class="fe fe-zap text-warning fs-24"></i>
                                    </div>
                                    <h5 class="text-warning mb-1 fw-bold">{{ number_format($binaryTree['main_user']['active_points'], 0) }}</h5>
                                    <small class="text-muted">Active Points</small>
                                    <div class="mt-2">
                                        <small class="text-info">Available for matching</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-3">
                                <div class="border rounded p-3 h-100">
                                    <div class="mb-2">
                                        <i class="fe fe-target text-info fs-24"></i>
                                    </div>
                                    @php
                                        $matchablePoints = min($binaryTree['main_user']['left_leg_points'], $binaryTree['main_user']['right_leg_points']);
                                        $matchingPotential = floor($matchablePoints / 100) * 6; // 100 points = 6 Tk
                                    @endphp
                                    <h5 class="text-info mb-1 fw-bold">{{ number_format($matchablePoints, 0) }}</h5>
                                    <small class="text-muted">Matchable Points</small>
                                    <div class="mt-2">
                                        <small class="text-success">≈ {{ number_format($matchingPotential, 0) }} Tk potential</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Point System Information -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fe fe-info me-2"></i>
                                        <strong>Point-Based Matching System</strong>
                                    </div>
                                    <div class="row small">
                                        <div class="col-md-6">
                                            <ul class="mb-0 ps-3">
                                                <li><strong>Conversion Rate:</strong> 1 Point = 6 Tk</li>
                                                <li><strong>Matching Rate:</strong> 10% on binary matching</li>
                                                <li><strong>Minimum Match:</strong> 100 points per leg required</li>
                                            </ul>
                                        </div>
                                        <div class="col-md-6">
                                            <ul class="mb-0 ps-3">
                                                <li><strong>Leg Balance:</strong> Both legs need points for matching</li>
                                                <li><strong>Point Sources:</strong> Product purchases, commissions</li>
                                                <li><strong>Auto Matching:</strong> Processes every 24 hours</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Details Modal -->
        <div class="modal fade" id="userDetailsModal" tabindex="-1" aria-labelledby="userDetailsModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="userDetailsModalLabel">Team Member Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="userDetailsContent">
                        <!-- User details will be loaded here -->
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('styles')
<style>
/* Binary Tree Container - Remove any default styling */
.binary-tree-wrapper {
    position: relative;
    width: 100%;
    height: 600px;
    overflow: auto;
    border-radius: 10px;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    touch-action: manipulation;
}

.binary-tree-container {
    position: relative;
    min-height: 500px;
    min-width: 800px;
    padding: 20px;
    transform-origin: center top;
    transition: transform 0.3s ease;
    touch-action: pan-x pan-y;
}

/* Zoom Controls Styling */
.zoom-controls {
    background: rgba(255, 255, 255, 0.9);
    border-radius: 20px;
    padding: 2px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.zoom-level {
    font-size: 12px;
    font-weight: 600;
    color: #666;
    min-width: 40px;
    text-align: center;
}

.zoom-controls .btn {
    border: none;
    background: transparent;
    width: 30px;
    height: 30px;
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0;
}

.zoom-controls .btn:hover {
    background: rgba(0,123,255,0.1);
    color: #007bff;
}

.binary-tree-container {
    position: relative;
    min-height: 500px;
    overflow-x: auto;
    padding: 20px;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 10px;
}

/* Override any default framework styles that might create black lines */
.binary-tree-container *,
.binary-tree-container *::before,
.binary-tree-container *::after {
    border-color: transparent !important;
}

/* Ensure only our blue lines show */
.binary-tree-container .tree-level::before,
.binary-tree-container .tree-level::after,
.binary-tree-container .tree-node::before,
.binary-tree-container .tree-node::after {
    background-color: #4a90e2 !important;
    border: none !important;
    outline: none !important;
    box-shadow: none !important;
}

.tree-lines {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1;
    pointer-events: none;
}

/* CLEAN BINARY TREE CONNECTING LINES - FIXED CONNECTIONS */
.tree-level {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-bottom: 70px;
    position: relative;
    z-index: 2;
}

/* Reset all pseudo-elements to prevent conflicts */
.tree-level::before,
.tree-level::after,
.tree-node::before,
.tree-node::after {
    content: '';
    position: absolute;
    background: #4a90e2 !important;
    border: none !important;
    outline: none !important;
    box-shadow: none !important;
    z-index: 1;
}

/* LEVEL 0 - ROOT USER */
.tree-level.level-0 {
    margin-bottom: 80px;
}

/* Main vertical line from root DOWN to horizontal connector */
.tree-level.level-0::after {
    top: 100%;
    left: 50%;
    width: 3px;
    height: 30px;
    transform: translateX(-50%);
    background: #4a90e2 !important;
}

/* LEVEL 1 CONNECTIONS */
/* Horizontal line connecting left and right positions - extended for 50% increased gap */
.tree-level.level-1::before {
    top: -30px;
    left: 15%;
    width: 70%;
    height: 3px;
    background: #4a90e2 !important;
}

/* Left user vertical connection UP to horizontal line */
.tree-level.level-1 .tree-node:first-child::before {
    top: -30px;
    left: 50%;
    width: 3px;
    height: 30px;
    transform: translateX(-50%);
    background: #4a90e2 !important;
}

/* Right user vertical connection UP to horizontal line */
.tree-level.level-1 .tree-node:last-child::before {
    top: -30px;
    left: 50%;
    width: 3px;
    height: 30px;
    transform: translateX(-50%);
    background: #4a90e2 !important;
}

/* LEVEL 2 CONNECTIONS - IMPROVED WITH PROPER BRANCHING */
/* Vertical lines down from Level 1 users to Level 2 horizontal lines */
.tree-level.level-1 .tree-node:first-child::after {
    top: 100%;
    left: 50%;
    width: 3px;
    height: 30px;
    transform: translateX(-50%);
    background: #4a90e2 !important;
}

.tree-level.level-1 .tree-node:last-child::after {
    top: 100%;
    left: 50%;
    width: 3px;
    height: 30px;
    transform: translateX(-50%);
    background: #4a90e2 !important;
}

/* LEFT SIDE Level 2 Horizontal Connector - wider gaps for better separation */
.tree-level.level-2::before {
    top: -30px;
    left: 8%;
    width: 28%;
    height: 3px;
    background: #4a90e2 !important;
}

/* RIGHT SIDE Level 2 Horizontal Connector - wider gaps for better separation */
.tree-level.level-2::after {
    top: -30px;
    left: 62%;
    width: 29%;
    height: 3px;
    background: #4a90e2 !important;
}

/* Vertical connections from horizontal lines to Level 2 users */
.tree-level.level-2 .tree-node:nth-child(1)::before,
.tree-level.level-2 .tree-node:nth-child(2)::before {
    top: -30px;
    left: 50%;
    width: 3px;
    height: 30px;
    transform: translateX(-50%);
    background: #4a90e2 !important;
}

.tree-level.level-2 .tree-node:nth-child(3)::before,
.tree-level.level-2 .tree-node:nth-child(4)::before {
    top: -30px;
    left: 50%;
    width: 3px;
    height: 30px;
    transform: translateX(-50%);
    background: #4a90e2 !important;
}

.tree-level.level-1 {
    justify-content: space-between;
    max-width: 750px;
    margin: 0 auto 80px auto;
    padding: 0 75px;
}

.tree-level.level-2 {
    justify-content: space-between;
    max-width: 1000px;
    margin: 0 auto 80px auto;
    padding: 0 40px;
}

.tree-level.level-3 {
    justify-content: space-around;
    max-width: 1200px;
    margin: 0 auto 50px auto;
    flex-wrap: wrap;
    gap: 30px;
}

.tree-node {
    display: flex;
    flex-direction: column;
    align-items: center;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
}

.tree-node:hover {
    transform: translateY(-5px);
}

.user-circle {
    width: 70px;
    height: 80px;
    border-radius: 50%;
    position: relative;
    margin-bottom: 10px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    overflow: hidden;
    border: 4px solid;
}

.user-circle.main {
    border-color: #dc3545;
    background: linear-gradient(135deg, #dc3545, #e74c3c);
}

.user-circle.left, .user-circle.left-child {
    border-color: #28a745;
    background: linear-gradient(135deg, #28a745, #20c997);
}

.user-circle.right, .user-circle.right-child {
    border-color: #007bff;
    background: linear-gradient(135deg, #007bff, #0056b3);
}

.user-circle.leaf {
    border-color: #6f42c1;
    background: linear-gradient(135deg, #6f42c1, #563d7c);
    width: 60px;
    height: 60px;
}

.user-circle.level3 {
    border-color: #fd7e14;
    background: linear-gradient(135deg, #fd7e14, #e55a4e);
    width: 70px;
    height: 70px;
}

/* Point Status Based Colors - Active Points Qualification System */
.user-circle.qualified {
    opacity: 1;
    box-shadow: 0 4px 15px rgba(40,167,69,0.3) !important;
    background: linear-gradient(135deg, #28a745, #20c997) !important;
    border-color: #28a745 !important;
    border-style: solid !important;
}

.user-circle.partial {
    opacity: 0.85;
    box-shadow: 0 4px 15px rgba(255,193,7,0.3) !important;
    background: linear-gradient(135deg, #ffc107, #ff8c00) !important;
    border-color: #ffc107 !important;
    border-style: solid !important;
}

.user-circle.none {
    opacity: 0.7;
    box-shadow: 0 4px 15px rgba(220,53,69,0.3) !important;
    background: linear-gradient(135deg, #dc3545, #c82333) !important;
    border-color: #dc3545 !important;
    border-style: solid !important;
}

.user-circle.available, .user-circle.empty {
    opacity: 0.6;
    background: linear-gradient(135deg, #f8f9fa, #e9ecef) !important;
    border-color: #dee2e6 !important;
    border-style: dashed !important;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1) !important;
}

/* Override position-based colors when point status is set */
.user-circle.main.qualified, .user-circle.left.qualified, .user-circle.right.qualified, 
.user-circle.left-child.qualified, .user-circle.right-child.qualified {
    background: linear-gradient(135deg, #28a745, #20c997) !important;
    border-color: #28a745 !important;
}

.user-circle.main.partial, .user-circle.left.partial, .user-circle.right.partial, 
.user-circle.left-child.partial, .user-circle.right-child.partial {
    background: linear-gradient(135deg, #ffc107, #ff8c00) !important;
    border-color: #ffc107 !important;
}

.user-circle.main.none, .user-circle.left.none, .user-circle.right.none, 
.user-circle.left-child.none, .user-circle.right-child.none {
    background: linear-gradient(135deg, #dc3545, #c82333) !important;
    border-color: #dc3545 !important;
}

/* Legend Circle Styles */
.legend-circle {
    width: 16px;
    height: 16px;
    border-radius: 50%;
    border: 2px solid;
    display: inline-block;
}

.legend-circle.qualified {
    background: linear-gradient(135deg, #28a745, #20c997);
    border-color: #28a745;
}

.legend-circle.partial {
    background: linear-gradient(135deg, #ffc107, #ff8c00);
    border-color: #ffc107;
}

.legend-circle.none {
    background: linear-gradient(135deg, #dc3545, #c82333);
    border-color: #dc3545;
}

.legend-circle.available {
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    border-color: #dee2e6;
    border-style: dashed;
}

/* Filled vs Empty User States - DEPRECATED, replaced with point status */
.user-circle.filled {
    opacity: 1;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}

.user-circle.empty {
    opacity: 0.6;
    background: linear-gradient(135deg, #f8f9fa, #e9ecef) !important;
    border-color: #dee2e6 !important;
    border-style: dashed !important;
}

.user-circle.empty .empty-icon {
    font-size: 24px;
    color: #6c757d;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}

.user-circle.empty .user-level {
    background: #6c757d !important;
}

/* Empty label styling */
.empty-label {
    font-size: 10px;
    color: #6c757d;
    text-align: center;
    margin-top: 5px;
    font-style: italic;
}

/* Level 3 Container Styling */
.level-3-container {
    display: flex;
    justify-content: center;
    align-items: flex-start;
    flex-wrap: wrap;
    gap: 40px;
    max-width: 1400px;
    margin: 0 auto;
    padding: 20px;
}

/* Level 3 Animation */
.tree-level.level-3 {
    opacity: 0;
    transform: translateY(-20px);
    transition: all 0.5s ease;
}

.tree-level.level-3.show {
    opacity: 1;
    transform: translateY(0);
}

/* Expand button animation */
.expand-btn i {
    transition: transform 0.3s ease;
}

.expand-btn.expanded i {
    transform: rotate(180deg);
}
    height: 60px;
}

.user-circle:hover {
    transform: scale(1.1);
    box-shadow: 0 6px 20px rgba(0,0,0,0.2);
}

.user-avatar {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 50%;
    display: block;
}

/* Fallback for missing images */
.user-avatar[src=""]:after,
.user-avatar:not([src]):after {
    content: "👤";
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
}

.image-fallback {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    border-radius: 50%;
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    color: #6c757d;
    font-weight: bold;
}

/* Ensure fallback styling for error handling */
.user-circle img[style*="display: none"] + .image-fallback {
    display: flex !important;
}

.user-level {
    position: absolute;
    bottom: -5px;
    right: -5px;
    background: #fff;
    color: #333;
    font-size: 10px;
    font-weight: bold;
    padding: 2px 6px;
    border-radius: 10px;
    border: 2px solid #ddd;
    min-width: 25px;
    text-align: center;
}

.user-name {
    font-weight: 600;
    font-size: 12px;
    text-align: center;
    margin-bottom: 5px;
    color: #333;
    max-width: 100px;
    word-wrap: break-word;
}

.user-stats {
    display: flex;
    gap: 10px;
    font-size: 10px;
}

.left-count {
    background: #28a745;
    color: white;
    padding: 2px 6px;
    border-radius: 8px;
    font-weight: bold;
}

.right-count {
    background: #007bff;
    color: white;
    padding: 2px 6px;
    border-radius: 8px;
    font-weight: bold;
}

/* Tooltip styles */
.tree-node .tooltip-content {
    position: absolute;
    bottom: 120%;
    left: 50%;
    transform: translateX(-50%);
    background: rgba(0,0,0,0.9);
    color: white;
    padding: 10px;
    border-radius: 6px;
    font-size: 12px;
    white-space: pre-line;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
    z-index: 1000;
    min-width: 200px;
    text-align: center;
    margin-bottom: 10px;
    pointer-events: none;
}

.tree-node:hover .tooltip-content {
    opacity: 1;
    visibility: visible;
}

/* Responsive Design */
@media (max-width: 1200px) {
    .binary-tree-wrapper {
        height: 500px;
    }
    
    .binary-tree-container {
        padding: 15px;
        min-width: 900px;
    }
    
    .tree-level.level-1 {
        max-width: 700px;
    }
    
    .tree-level.level-2 {
        max-width: 900px;
    }
}

@media (max-width: 992px) {
    .binary-tree-wrapper {
        height: 450px;
    }
    
    .binary-tree-container {
        padding: 10px;
        min-width: 700px;
    }
    
    .user-circle {
        width: 70px;
        height: 70px;
    }
    
    .user-circle.leaf {
        width: 55px;
        height: 55px;
    }
    
    .user-name {
        font-size: 11px;
        max-width: 80px;
    }
    
    .tree-level {
        margin-bottom: 60px;
    }
    
    .tree-level.level-1 {
        max-width: 600px;
        padding: 0 60px;
    }
    
    .tree-level.level-2 {
        max-width: 750px;
        padding: 0 30px;
    }
    
    /* Zoom controls responsive */
    .zoom-controls {
        order: -1;
        width: 100%;
        justify-content: center;
        margin-bottom: 10px;
    }
    
    .ms-auto {
        flex-direction: column;
        align-items: stretch !important;
        gap: 10px;
    }
}

@media (max-width: 768px) {
    .binary-tree-wrapper {
        height: 400px;
        margin: 0 -15px;
        border-radius: 0;
    }
    
    .binary-tree-container {
        padding: 10px;
        min-width: 600px;
    }
    
    .user-circle {
        width: 60px;
        height: 60px;
    }
    
    .user-circle.leaf {
        width: 50px;
        height: 50px;
    }
    
    .user-circle.level3 {
        width: 55px;
        height: 55px;
    }
    
    .user-name {
        font-size: 10px;
        max-width: 70px;
    }
    
    .tree-level {
        margin-bottom: 50px;
    }
    
    .tree-level.level-1 {
        max-width: 525px;
        margin-bottom: 65px;
        padding: 0 45px;
    }
    
    .tree-level.level-2 {
        max-width: 450px;
        margin-bottom: 65px;
        padding: 0 15px;
    }
    
    .user-stats {
        font-size: 9px;
        gap: 5px;
    }
    
    .left-count, .right-count {
        padding: 1px 4px;
        font-size: 8px;
    }
    
    .user-level {
        font-size: 8px;
        padding: 1px 4px;
        min-width: 20px;
    }
    
    .expand-btn {
        font-size: 10px;
        padding: 2px 6px;
    }
    
    /* Mobile zoom controls */
    .zoom-controls {
        flex-wrap: nowrap;
        justify-content: center;
    }
    
    .zoom-controls .btn {
        width: 35px;
        height: 35px;
        font-size: 14px;
    }
    
    .zoom-level {
        font-size: 11px;
        min-width: 35px;
    }
    
    /* Adjust connecting lines for mobile */
    .tree-level.level-0::after {
        height: 25px;
    }
    
    .tree-level.level-1::before {
        top: -25px;
        left: 15%;
        width: 70%;
    }
    
    .tree-level.level-1 .tree-node::before {
        height: 25px;
        top: -25px;
    }
    
    .tree-level.level-1 .tree-node::after {
        height: 25px;
    }
    
    .tree-level.level-2::before {
        top: -25px;
        left: 8%;
        width: 17%;
    }
    
    .tree-level.level-2::after {
        top: -25px;
        left: 75%;
        width: 17%;
    }
    
    .tree-level.level-2 .tree-node::before {
        height: 25px;
        top: -25px;
    }
}

@media (max-width: 576px) {
    .binary-tree-wrapper {
        height: 350px;
        margin: 0 -20px;
    }
    
    .binary-tree-container {
        padding: 5px;
        min-width: 500px;
    }
    
    .user-circle {
        width: 50px;
        height: 50px;
    }
    
    .user-circle.leaf {
        width: 45px;
        height: 45px;
    }
    
    .user-circle.level3 {
        width: 50px;
        height: 50px;
    }
    
    .user-name {
        font-size: 9px;
        max-width: 60px;
        line-height: 1.2;
    }
    
    .tree-level {
        margin-bottom: 45px;
    }
    
    .tree-level.level-1 {
        max-width: 480px;
        margin-bottom: 55px;
        padding: 0 60px;
    }
    
    .tree-level.level-2 {
        max-width: 400px;
        margin-bottom: 55px;
        padding: 0 20px;
    }
    
    .user-stats {
        font-size: 8px;
        gap: 3px;
        flex-direction: column;
        align-items: center;
    }
    
    .left-count, .right-count {
        padding: 1px 3px;
        font-size: 7px;
        border-radius: 4px;
    }
    
    .user-level {
        font-size: 7px;
        padding: 1px 3px;
        min-width: 18px;
        bottom: -3px;
        right: -3px;
    }
    
    .expand-btn {
        font-size: 8px;
        padding: 1px 4px;
        margin-top: 2px;
    }
    
    .expand-btn i {
        font-size: 8px;
    }
    
    /* Ultra-small mobile zoom controls */
    .zoom-controls {
        padding: 1px;
    }
    
    .zoom-controls .btn {
        width: 30px;
        height: 30px;
        font-size: 12px;
    }
    
    .zoom-level {
        font-size: 10px;
        min-width: 30px;
        margin: 0 5px;
    }
    
    /* Further adjust connecting lines for small mobile */
    .tree-level.level-0::after {
        height: 22px;
    }
    
    .tree-level.level-1::before {
        top: -22px;
        left: 15%;
        width: 70%;
    }
    
    .tree-level.level-1 .tree-node::before {
        height: 22px;
        top: -22px;
    }
    
    .tree-level.level-1 .tree-node::after {
        height: 22px;
    }
    
    .tree-level.level-2::before {
        top: -22px;
        width: 18%;
        left: 7%;
    }
    
    .tree-level.level-2::after {
        top: -22px;
        width: 18%;
        left: 75%;
    }
    
    .tree-level.level-2 .tree-node::before {
        height: 22px;
        top: -22px;
    }
    
    /* Mobile card header adjustments */
    .card-header {
        padding: 0.75rem 1rem;
        flex-direction: column;
        align-items: stretch;
        gap: 10px;
    }
    
    .card-header .card-title {
        font-size: 14px;
        text-align: center;
    }
    
    .btn-sm {
        font-size: 11px;
        padding: 0.25rem 0.5rem;
    }
    
    /* Mobile team summary responsive improvements */
    .text-center.p-3 {
        padding: 1rem !important;
    }
    
    .text-center.p-3 h5 {
        font-size: 1.1rem;
        margin-bottom: 0.5rem;
    }
    
    .text-center.p-3 small {
        font-size: 0.75rem;
        line-height: 1.2;
    }
    
    .text-center.p-3 i {
        font-size: 1.5rem !important;
        margin-bottom: 0.5rem;
    }
}

/* Animation for tree loading */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.tree-node {
    animation: fadeInUp 0.6s ease-out;
}

.tree-level.level-1 .tree-node {
    animation-delay: 0.2s;
}

.tree-level.level-2 .tree-node {
    animation-delay: 0.4s;
}

.tree-level.level-3 .tree-node {
    animation-delay: 0.6s;
}
</style>
@endpush

@push('scripts')
<script>
// Zoom functionality
let currentZoom = 1;
const minZoom = 0.5;
const maxZoom = 3;
const zoomStep = 0.2;

function zoomIn() {
    if (currentZoom < maxZoom) {
        currentZoom += zoomStep;
        applyZoom();
    }
}

function zoomOut() {
    if (currentZoom > minZoom) {
        currentZoom -= zoomStep;
        applyZoom();
    }
}

function resetZoom() {
    currentZoom = 1;
    applyZoom();
}

function applyZoom() {
    const container = document.getElementById('binaryTreeContainer');
    const zoomLevel = document.querySelector('.zoom-level');
    
    if (container) {
        container.style.transform = `scale(${currentZoom})`;
        zoomLevel.textContent = Math.round(currentZoom * 100) + '%';
    }
}

// Touch zoom support for mobile
let initialDistance = 0;
let initialZoom = 1;

function getTouchDistance(touches) {
    const dx = touches[0].clientX - touches[1].clientX;
    const dy = touches[0].clientY - touches[1].clientY;
    return Math.sqrt(dx * dx + dy * dy);
}

document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('binaryTreeContainer');
    
    if (container) {
        // Touch events for pinch zoom
        container.addEventListener('touchstart', function(e) {
            if (e.touches.length === 2) {
                e.preventDefault();
                initialDistance = getTouchDistance(e.touches);
                initialZoom = currentZoom;
            }
        }, { passive: false });
        
        container.addEventListener('touchmove', function(e) {
            if (e.touches.length === 2) {
                e.preventDefault();
                const currentDistance = getTouchDistance(e.touches);
                const scale = currentDistance / initialDistance;
                const newZoom = Math.max(minZoom, Math.min(maxZoom, initialZoom * scale));
                
                currentZoom = newZoom;
                applyZoom();
            }
        }, { passive: false });
        
        // Mouse wheel zoom
        container.addEventListener('wheel', function(e) {
            e.preventDefault();
            
            if (e.deltaY < 0) {
                zoomIn();
            } else {
                zoomOut();
            }
        }, { passive: false });
    }
    
    // Initialize search functionality
    const searchInput = document.getElementById('binaryTreeSearchInput');
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                searchUserTree();
            }
        });
    }
    
    // Initialize other functionality
    initializeBinaryTree();
});

// Expand user function - creates NEW tree with selected user as root
function expandUser(userId) {
    if (!userId) {
        console.error('User ID is required for expansion');
        return;
    }
    
    Swal.fire({
        title: 'Loading Subtree',
        text: 'Creating new tree with selected user as root...',
        icon: 'info',
        allowOutsideClick: false,
        showConfirmButton: false,
        willOpen: () => {
            Swal.showLoading();
        }
    });
    
    // Redirect to binary tree with new root user
    setTimeout(() => {
        window.location.href = `{{ route('member.binary') }}?root_user=${userId}`;
    }, 1000);
}

// Back to main tree function
function backToMainTree() {
    Swal.fire({
        title: 'Loading Main Tree',
        text: 'Returning to main binary tree...',
        icon: 'info',
        allowOutsideClick: false,
        showConfirmButton: false,
        willOpen: () => {
            Swal.showLoading();
        }
    });
    
    setTimeout(() => {
        window.location.href = `{{ route('member.binary') }}`;
    }, 1000);
}

// Binary tree search function
function searchUserTree() {
    const searchInput = document.getElementById('binaryTreeSearchInput');
    const searchTerm = searchInput.value.trim();
    
    if (!searchTerm) {
        Swal.fire({
            title: 'Search Required',
            text: 'Please enter a username or referral code',
            icon: 'warning',
            confirmButtonText: 'OK'
        });
        return;
    }
    
    Swal.fire({
        title: 'Searching User',
        text: `Looking for ${searchTerm} in your binary network...`,
        icon: 'info',
        allowOutsideClick: false,
        showConfirmButton: false,
        willOpen: () => {
            Swal.showLoading();
        }
    });
    
    // Redirect to binary tree with searched user as root
    setTimeout(() => {
        window.location.href = `{{ route('member.binary') }}?root_user=${encodeURIComponent(searchTerm)}`;
    }, 1500);
}

// Refresh binary tree
function refreshBinaryTree() {
    const currentUrl = window.location.href;
    
    Swal.fire({
        title: 'Refreshing Tree',
        text: 'Updating binary tree structure...',
        icon: 'info',
        allowOutsideClick: false,
        showConfirmButton: false,
        willOpen: () => {
            Swal.showLoading();
        }
    });
    
    setTimeout(() => {
        window.location.reload();
    }, 1500);
}

document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips and user interactions
    initializeBinaryTree();
});

function initializeBinaryTree() {
    const treeNodes = document.querySelectorAll('.tree-node');
    
    treeNodes.forEach(node => {
        // Add click event for detailed view
        node.addEventListener('click', function() {
            const userId = this.getAttribute('data-user-id');
            const userName = this.getAttribute('data-user-name');
            const leftCount = this.getAttribute('data-left-count');
            const rightCount = this.getAttribute('data-right-count');
            const totalPoints = this.getAttribute('data-total-business');
            const rank = this.getAttribute('data-rank');
            
            showUserDetails(userId, userName, leftCount, rightCount, totalPoints, rank);
        });
        
        // Add hover effects
        node.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px) scale(1.05)';
        });
        
        node.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });
}

function showUserDetails(userId, userName, leftCount, rightCount, totalPoints, rank) {
    // Get actual user avatar if available from data attributes or use fallback
    const userNode = document.querySelector(`[data-user-id="${userId}"]`);
    let userAvatar = '{{ asset("assets/images/avatars/default-customer.svg") }}'; // Default fallback
    
    if (userNode) {
        const avatarImg = userNode.querySelector('.user-avatar');
        if (avatarImg && avatarImg.src && !avatarImg.src.includes('default')) {
            userAvatar = avatarImg.src;
        }
    }
    
    const modalContent = `
        <div class="user-detail-card">
            <div class="row">
                <div class="col-md-4 text-center">
                    <img src="${userAvatar}" 
                         alt="${userName}" 
                         class="img-fluid rounded-circle mb-3" 
                         style="width: 100px; height: 100px; object-fit: cover;"
                         onerror="this.src='{{ asset("assets/images/avatars/default-customer.svg") }}';">
                    <h5>${userName}</h5>
                    <span class="badge bg-primary">${rank}</span>
                </div>
                <div class="col-md-8">
                    <h6>Team Information</h6>
                    <div class="row mb-3">
                        <div class="col-6">
                            <div class="border p-2 rounded text-center">
                                <h6 class="text-success mb-1">${leftCount}</h6>
                                <small class="text-muted">Left Team</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border p-2 rounded text-center">
                                <h6 class="text-primary mb-1">${rightCount}</h6>
                                <small class="text-muted">Right Team</small>
                            </div>
                        </div>
                    </div>
                    <div class="border p-3 rounded">
                        <h6 class="text-warning mb-1">${totalPoints}</h6>
                        <small class="text-muted">Total Points Earned</small>
                    </div>
                    <div class="mt-3">
                        <small class="text-muted">Member ID: ${userId}</small>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    document.getElementById('userDetailsContent').innerHTML = modalContent;
    const modal = new bootstrap.Modal(document.getElementById('userDetailsModal'));
    modal.show();
}

function getRandomAvatar() {
    const avatars = [
        '{{ asset("admin-assets/images/users/1.jpg") }}',
        '{{ asset("admin-assets/images/users/2.jpg") }}',
        '{{ asset("admin-assets/images/users/3.jpg") }}',
        '{{ asset("admin-assets/images/users/4.jpg") }}',
        '{{ asset("admin-assets/images/users/5.jpg") }}',
        '{{ asset("admin-assets/images/users/6.jpg") }}',
        '{{ asset("admin-assets/images/users/7.jpg") }}',
        '{{ asset("admin-assets/images/users/8.jpg") }}',
        '{{ asset("assets/images/avatars/default-customer.svg") }}'
    ];
    return avatars[Math.floor(Math.random() * avatars.length)];
}

function refreshBinaryTree() {
    // Show loading animation
    Swal.fire({
        title: 'Refreshing Binary Tree',
        text: 'Updating team structure...',
        icon: 'info',
        allowOutsideClick: false,
        showConfirmButton: false,
        willOpen: () => {
            Swal.showLoading();
        }
    });
    
    // Simulate API call
    setTimeout(() => {
        Swal.fire({
            title: 'Updated!',
            text: 'Binary tree structure has been refreshed',
            icon: 'success',
            timer: 2000,
            showConfirmButton: false
        });
        
        // Reload the page or update data
        location.reload();
    }, 2000);
}

// Add keyboard navigation
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const modal = bootstrap.Modal.getInstance(document.getElementById('userDetailsModal'));
        if (modal) {
            modal.hide();
        }
    }
});

// Service Worker cache clearing for binary pages
document.addEventListener('DOMContentLoaded', function() {
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.ready.then(function(registration) {
            if (registration.active) {
                // Send message to clear matching/binary page cache
                registration.active.postMessage({
                    type: 'CLEAR_MATCHING_CACHE'
                });
            }
        });
        
        // Also force a hard refresh if page was loaded from cache
        if (performance.navigation.type === 2) {
            // Page was loaded from back/forward cache
            window.location.reload(true);
        }
    }
});
</script>
@endpush
