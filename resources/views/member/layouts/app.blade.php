<!DOCTYPE html>
<html lang="en" dir="ltr" data-nav-layout="vertical" data-theme-mode="light" data-header-styles="light" data-menu-styles="dark" data-toggled="close">
<head>
    <!-- Meta Data -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title', 'User Dashboard') - {{ config('app.name') }}</title>
    <meta name="Description" content="User Dashboard for {{ config('app.name') }}">
    <meta name="Author" content="{{ config('app.name') }}">
    <meta name="keywords" content="user, dashboard, ecommerce, affiliate, vendor, customer">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Favicon -->
    <link rel="icon" href="{{ siteFavicon() }}" type="image/svg+xml">
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/x-icon">
    
    <!-- Choices JS -->
    <script src="{{ asset('admin-assets/libs/choices.js/public/assets/scripts/choices.min.js') }}"></script>

    <!-- Main Theme Js -->
    <script src="{{ asset('admin-assets/js/main.js') }}"></script>
    
    <!-- Bootstrap Css -->
    <link id="style" href="{{ asset('admin-assets/libs/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Style Css -->
    <link href="{{ asset('admin-assets/css/styles.min.css') }}" rel="stylesheet">

    <!-- Icons Css -->
    <link href="{{ asset('admin-assets/css/icons.css') }}" rel="stylesheet">

    <!-- Node Waves Css -->
    <link href="{{ asset('admin-assets/libs/node-waves/waves.min.css') }}" rel="stylesheet"> 

    <!-- Simplebar Css -->
    <link href="{{ asset('admin-assets/libs/simplebar/simplebar.min.css') }}" rel="stylesheet">
    
    <!-- Color Picker Css -->
    <link rel="stylesheet" href="{{ asset('admin-assets/libs/flatpickr/flatpickr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-assets/libs/@simonwep/pickr/themes/nano.min.css') }}">
    
    <!-- Choices Css -->
    <link rel="stylesheet" href="{{ asset('admin-assets/libs/choices.js/public/assets/styles/choices.min.css') }}">

    <!-- Modern Header CSS -->
    <link href="{{ asset('admin-assets/css/modern-header.css') }}" rel="stylesheet">

    <!-- Member Header Custom CSS -->
    <link href="{{ asset('assets/css/member-header.css') }}" rel="stylesheet">

    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    
    <!-- Custom Flash Message Styles -->
    <style>
        /* SweetAlert2 Custom Styles */
        .swal-wide {
            width: 600px !important;
            max-width: 90vw !important;
        }
        
        /* Enhanced Alert Styles */
        .alert {
            border: none;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 1rem;
        }
        
        .alert-success {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            border-left: 4px solid #28a745;
        }
        
        .alert-danger {
            background: linear-gradient(135deg, #f8d7da 0%, #f1b0b7 100%);
            border-left: 4px solid #dc3545;
        }
        
        .alert-warning {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
            border-left: 4px solid #ffc107;
        }
        
        .alert-info {
            background: linear-gradient(135deg, #cce7ff 0%, #b8daff 100%);
            border-left: 4px solid #17a2b8;
        }
        
        /* Toast positioning improvements */
        .swal2-toast .swal2-title {
            font-size: 14px !important;
            font-weight: 600 !important;
        }
        
        .swal2-toast .swal2-html-container {
            font-size: 13px !important;
        }
        
        /* Notification Badge Pulse Animation */
        .pulse-animation {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.7);
            }
            70% {
                transform: scale(1.1);
                box-shadow: 0 0 0 10px rgba(220, 53, 69, 0);
            }
            100% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(220, 53, 69, 0);
            }
        }
        
        /* Enhanced notification badge */
        .header-icon-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            min-width: 18px;
            height: 18px;
            font-size: 10px;
            font-weight: bold;
            line-height: 18px;
            text-align: center;
        }
        
        /* Animation improvements */
        .alert {
            animation: slideInRight 0.3s ease-out;
        }
        
        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(100%);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
    </style>

    <!-- Additional CSS -->
    @stack('styles')
</head>

<body>
    <!-- Start Switcher -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="switcher-canvas" aria-labelledby="offcanvasRightLabel">
        <div class="offcanvas-header border-bottom">
            <h5 class="offcanvas-title text-default" id="offcanvasRightLabel">Switcher</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <nav class="border-bottom border-block-end-dashed">
                <div class="nav nav-tabs nav-justified" id="switcher-main-tab" role="tablist">
                    <button class="nav-link active" id="switcher-home-tab" data-bs-toggle="tab" data-bs-target="#switcher-home"
                        type="button" role="tab" aria-controls="switcher-home" aria-selected="true">Theme Styles</button>
                    <button class="nav-link" id="switcher-profile-tab" data-bs-toggle="tab" data-bs-target="#switcher-profile"
                        type="button" role="tab" aria-controls="switcher-profile" aria-selected="false">Theme Colors</button>
                </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active border-0" id="switcher-home" role="tabpanel" aria-labelledby="switcher-home-tab"
                    tabindex="0">
                    <div class="">
                        <p class="switcher-style-head">Theme Color Mode:</p>
                        <div class="row switcher-style gx-0">
                            <div class="col-4">
                                <div class="form-check switch-select">
                                    <label class="form-check-label" for="switcher-light-theme">
                                        Light
                                    </label>
                                    <input class="form-check-input" type="radio" name="theme-style" id="switcher-light-theme"
                                        checked>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-check switch-select">
                                    <label class="form-check-label" for="switcher-dark-theme">
                                        Dark
                                    </label>
                                    <input class="form-check-input" type="radio" name="theme-style" id="switcher-dark-theme">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="">
                        <p class="switcher-style-head">Directions:</p>
                        <div class="row switcher-style gx-0">
                            <div class="col-4">
                                <div class="form-check switch-select">
                                    <label class="form-check-label" for="switcher-ltr">
                                        LTR
                                    </label>
                                    <input class="form-check-input" type="radio" name="direction" id="switcher-ltr" checked>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-check switch-select">
                                    <label class="form-check-label" for="switcher-rtl">
                                        RTL
                                    </label>
                                    <input class="form-check-input" type="radio" name="direction" id="switcher-rtl">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="">
                        <p class="switcher-style-head">Navigation Styles:</p>
                        <div class="row switcher-style gx-0">
                            <div class="col-4">
                                <div class="form-check switch-select">
                                    <label class="form-check-label" for="switcher-vertical">
                                        Vertical
                                    </label>
                                    <input class="form-check-input" type="radio" name="navigation-style" id="switcher-vertical"
                                        checked>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-check switch-select">
                                    <label class="form-check-label" for="switcher-horizontal">
                                        Horizontal
                                    </label>
                                    <input class="form-check-input" type="radio" name="navigation-style"
                                        id="switcher-horizontal">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="navigation-menu-styles">
                        <p class="switcher-style-head">Vertical & Horizontal Menu Styles:</p>
                        <div class="row switcher-style gx-0 pb-2 gy-2">
                            <div class="col-4">
                                <div class="form-check switch-select">
                                    <label class="form-check-label" for="switcher-menu-click">
                                        Menu Click
                                    </label>
                                    <input class="form-check-input" type="radio" name="navigation-menu-styles"
                                        id="switcher-menu-click">
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-check switch-select">
                                    <label class="form-check-label" for="switcher-menu-hover">
                                        Menu Hover
                                    </label>
                                    <input class="form-check-input" type="radio" name="navigation-menu-styles"
                                        id="switcher-menu-hover">
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-check switch-select">
                                    <label class="form-check-label" for="switcher-icon-click">
                                        Icon Click
                                    </label>
                                    <input class="form-check-input" type="radio" name="navigation-menu-styles"
                                        id="switcher-icon-click">
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-check switch-select">
                                    <label class="form-check-label" for="switcher-icon-hover">
                                        Icon Hover
                                    </label>
                                    <input class="form-check-input" type="radio" name="navigation-menu-styles"
                                        id="switcher-icon-hover">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="sidemenu-layout-styles">
                        <p class="switcher-style-head">Sidemenu Layout Styles:</p>
                        <div class="row switcher-style gx-0 pb-2 gy-2">
                            <div class="col-sm-6">
                                <div class="form-check switch-select">
                                    <label class="form-check-label" for="switcher-default-menu">
                                        Default Menu
                                    </label>
                                    <input class="form-check-input" type="radio" name="sidemenu-layout-styles"
                                        id="switcher-default-menu" checked>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-check switch-select">
                                    <label class="form-check-label" for="switcher-closed-menu">
                                        Closed Menu
                                    </label>
                                    <input class="form-check-input" type="radio" name="sidemenu-layout-styles"
                                        id="switcher-closed-menu">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-check switch-select">
                                    <label class="form-check-label" for="switcher-icontext-menu">
                                        Icon Text
                                    </label>
                                    <input class="form-check-input" type="radio" name="sidemenu-layout-styles"
                                        id="switcher-icontext-menu">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-check switch-select">
                                    <label class="form-check-label" for="switcher-icon-overlay">
                                        Icon Overlay
                                    </label>
                                    <input class="form-check-input" type="radio" name="sidemenu-layout-styles"
                                        id="switcher-icon-overlay">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-check switch-select">
                                    <label class="form-check-label" for="switcher-detached">
                                        Detached
                                    </label>
                                    <input class="form-check-input" type="radio" name="sidemenu-layout-styles"
                                        id="switcher-detached">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-check switch-select">
                                    <label class="form-check-label" for="switcher-double-menu">
                                        Double Menu
                                    </label>
                                    <input class="form-check-input" type="radio" name="sidemenu-layout-styles"
                                        id="switcher-double-menu">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="">
                        <p class="switcher-style-head">Page Styles:</p>
                        <div class="row switcher-style gx-0">
                            <div class="col-4">
                                <div class="form-check switch-select">
                                    <label class="form-check-label" for="switcher-regular">
                                        Regular
                                    </label>
                                    <input class="form-check-input" type="radio" name="page-styles" id="switcher-regular"
                                        checked>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-check switch-select">
                                    <label class="form-check-label" for="switcher-classic">
                                        Classic
                                    </label>
                                    <input class="form-check-input" type="radio" name="page-styles" id="switcher-classic">
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-check switch-select">
                                    <label class="form-check-label" for="switcher-modern">
                                        Modern
                                    </label>
                                    <input class="form-check-input" type="radio" name="page-styles" id="switcher-modern">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="">
                        <p class="switcher-style-head">Layout Width Styles:</p>
                        <div class="row switcher-style gx-0">
                            <div class="col-4">
                                <div class="form-check switch-select">
                                    <label class="form-check-label" for="switcher-full-width">
                                        Full Width
                                    </label>
                                    <input class="form-check-input" type="radio" name="layout-width" id="switcher-full-width"
                                        checked>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-check switch-select">
                                    <label class="form-check-label" for="switcher-boxed">
                                        Boxed
                                    </label>
                                    <input class="form-check-input" type="radio" name="layout-width" id="switcher-boxed">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="">
                        <p class="switcher-style-head">Menu Positions:</p>
                        <div class="row switcher-style gx-0">
                            <div class="col-4">
                                <div class="form-check switch-select">
                                    <label class="form-check-label" for="switcher-menu-fixed">
                                        Fixed
                                    </label>
                                    <input class="form-check-input" type="radio" name="menu-positions" id="switcher-menu-fixed"
                                        checked>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-check switch-select">
                                    <label class="form-check-label" for="switcher-menu-scroll">
                                        Scrollable
                                    </label>
                                    <input class="form-check-input" type="radio" name="menu-positions" id="switcher-menu-scroll">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="">
                        <p class="switcher-style-head">Header Positions:</p>
                        <div class="row switcher-style gx-0">
                            <div class="col-4">
                                <div class="form-check switch-select">
                                    <label class="form-check-label" for="switcher-header-fixed">
                                        Fixed
                                    </label>
                                    <input class="form-check-input" type="radio" name="header-positions"
                                        id="switcher-header-fixed" checked>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-check switch-select">
                                    <label class="form-check-label" for="switcher-header-scroll">
                                        Scrollable
                                    </label>
                                    <input class="form-check-input" type="radio" name="header-positions"
                                        id="switcher-header-scroll">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="">
                        <p class="switcher-style-head">Loader:</p>
                        <div class="row switcher-style gx-0">
                            <div class="col-4">
                                <div class="form-check switch-select">
                                    <label class="form-check-label" for="switcher-loader-enable">
                                        Enable
                                    </label>
                                    <input class="form-check-input" type="radio" name="page-loader"
                                        id="switcher-loader-enable" checked>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-check switch-select">
                                    <label class="form-check-label" for="switcher-loader-disable">
                                        Disable
                                    </label>
                                    <input class="form-check-input" type="radio" name="page-loader"
                                        id="switcher-loader-disable" checked>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade border-0" id="switcher-profile" role="tabpanel" aria-labelledby="switcher-profile-tab" tabindex="0">
                    <div>
                        <div class="theme-colors">
                            <p class="switcher-style-head">Menu Colors:</p>
                            <div class="d-flex switcher-style pb-2">
                                <div class="form-check switch-select me-3">
                                    <input class="form-check-input color-input color-white" data-bs-toggle="tooltip"
                                        data-bs-placement="top" title="Light Menu" type="radio" name="menu-colors"
                                        id="switcher-menu-light">
                                </div>
                                <div class="form-check switch-select me-3">
                                    <input class="form-check-input color-input color-dark" data-bs-toggle="tooltip"
                                        data-bs-placement="top" title="Dark Menu" type="radio" name="menu-colors"
                                        id="switcher-menu-dark" checked>
                                </div>
                                <div class="form-check switch-select me-3">
                                    <input class="form-check-input color-input color-primary" data-bs-toggle="tooltip"
                                        data-bs-placement="top" title="Color Menu" type="radio" name="menu-colors"
                                        id="switcher-menu-primary">
                                </div>
                                <div class="form-check switch-select me-3">
                                    <input class="form-check-input color-input color-gradient" data-bs-toggle="tooltip"
                                        data-bs-placement="top" title="Gradient Menu" type="radio" name="menu-colors"
                                        id="switcher-menu-gradient">
                                </div>
                                <div class="form-check switch-select me-3">
                                    <input class="form-check-input color-input color-transparent"
                                        data-bs-toggle="tooltip" data-bs-placement="top" title="Transparent Menu"
                                        type="radio" name="menu-colors" id="switcher-menu-transparent">
                                </div>
                            </div>
                            <div class="px-4 pb-3 text-muted fs-11">Note:If you want to change color Menu dynamically change from below Theme Primary color picker</div>
                        </div>
                        <div class="theme-colors">
                            <p class="switcher-style-head">Header Colors:</p>
                            <div class="d-flex switcher-style pb-2">
                                <div class="form-check switch-select me-3">
                                    <input class="form-check-input color-input color-white" data-bs-toggle="tooltip"
                                        data-bs-placement="top" title="Light Header" type="radio" name="header-colors"
                                        id="switcher-header-light" checked>
                                </div>
                                <div class="form-check switch-select me-3">
                                    <input class="form-check-input color-input color-dark" data-bs-toggle="tooltip"
                                        data-bs-placement="top" title="Dark Header" type="radio" name="header-colors"
                                        id="switcher-header-dark">
                                </div>
                                <div class="form-check switch-select me-3">
                                    <input class="form-check-input color-input color-primary" data-bs-toggle="tooltip"
                                        data-bs-placement="top" title="Color Header" type="radio" name="header-colors"
                                        id="switcher-header-primary">
                                </div>
                                <div class="form-check switch-select me-3">
                                    <input class="form-check-input color-input color-gradient" data-bs-toggle="tooltip"
                                        data-bs-placement="top" title="Gradient Header" type="radio" name="header-colors"
                                        id="switcher-header-gradient">
                                </div>
                                <div class="form-check switch-select me-3">
                                    <input class="form-check-input color-input color-transparent" data-bs-toggle="tooltip"
                                        data-bs-placement="top" title="Transparent Header" type="radio" name="header-colors"
                                        id="switcher-header-transparent">
                                </div>
                            </div>
                            <div class="px-4 pb-3 text-muted fs-11">Note:If you want to change color Header dynamically change from below Theme Primary color picker</div>
                        </div>
                        <div class="theme-colors">
                            <p class="switcher-style-head">Theme Primary:</p>
                            <div class="d-flex flex-wrap align-items-center switcher-style">
                                <div class="form-check switch-select me-3">
                                    <input class="form-check-input color-input color-primary-1" type="radio"
                                        name="theme-primary" id="switcher-primary">
                                </div>
                                <div class="form-check switch-select me-3">
                                    <input class="form-check-input color-input color-primary-2" type="radio"
                                        name="theme-primary" id="switcher-primary1">
                                </div>
                                <div class="form-check switch-select me-3">
                                    <input class="form-check-input color-input color-primary-3" type="radio" name="theme-primary"
                                        id="switcher-primary2">
                                </div>
                                <div class="form-check switch-select me-3">
                                    <input class="form-check-input color-input color-primary-4" type="radio" name="theme-primary"
                                        id="switcher-primary3">
                                </div>
                                <div class="form-check switch-select me-3">
                                    <input class="form-check-input color-input color-primary-5" type="radio" name="theme-primary"
                                        id="switcher-primary4">
                                </div>
                                <div class="form-check switch-select ps-0 mt-1 color-primary-light">
                                    <div class="theme-container-primary"></div>
                                    <div class="pickr-container-primary"></div>
                                </div>
                            </div>
                        </div>
                        <div class="theme-colors">
                            <p class="switcher-style-head">Theme Background:</p>
                            <div class="d-flex flex-wrap align-items-center switcher-style">
                                <div class="form-check switch-select me-3">
                                    <input class="form-check-input color-input color-bg-1" type="radio"
                                        name="theme-background" id="switcher-background">
                                </div>
                                <div class="form-check switch-select me-3">
                                    <input class="form-check-input color-input color-bg-2" type="radio"
                                        name="theme-background" id="switcher-background1">
                                </div>
                                <div class="form-check switch-select me-3">
                                    <input class="form-check-input color-input color-bg-3" type="radio" name="theme-background"
                                        id="switcher-background2">
                                </div>
                                <div class="form-check switch-select me-3">
                                    <input class="form-check-input color-input color-bg-4" type="radio"
                                        name="theme-background" id="switcher-background3">
                                </div>
                                <div class="form-check switch-select me-3">
                                    <input class="form-check-input color-input color-bg-5" type="radio"
                                        name="theme-background" id="switcher-background4">
                                </div>
                                <div class="form-check switch-select ps-0 mt-1 tooltip-static-demo color-bg-transparent">
                                    <div class="theme-container-background"></div>
                                    <div class="pickr-container-background"></div>
                                </div>
                            </div>
                        </div>
                        <div class="menu-image mb-3">
                            <p class="switcher-style-head">Menu With Background Image:</p>
                            <div class="d-flex flex-wrap align-items-center switcher-style">
                                <div class="form-check switch-select m-2">
                                    <input class="form-check-input bgimage-input bg-img1" type="radio"
                                        name="theme-background" id="switcher-bg-img">
                                </div>
                                <div class="form-check switch-select m-2">
                                    <input class="form-check-input bgimage-input bg-img2" type="radio"
                                        name="theme-background" id="switcher-bg-img1">
                                </div>
                                <div class="form-check switch-select m-2">
                                    <input class="form-check-input bgimage-input bg-img3" type="radio" name="theme-background"
                                        id="switcher-bg-img2">
                                </div>
                                <div class="form-check switch-select m-2">
                                    <input class="form-check-input bgimage-input bg-img4" type="radio"
                                        name="theme-background" id="switcher-bg-img3">
                                </div>
                                <div class="form-check switch-select m-2">
                                    <input class="form-check-input bgimage-input bg-img5" type="radio"
                                        name="theme-background" id="switcher-bg-img4">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-block justify-content-between canvas-footer flex-wrap">
                    <a href="javascript:void(0);" id="reset-all" class="btn btn-danger d-grid my-1 mx-0">Reset</a> 
                </div>
            </div>
        </div>
    </div>
    <!-- End Switcher -->

    <!-- Loader -->
    @include('member.layouts.partials.loader')
    <!-- Loader -->

    <div class="page">
        <!-- app-header -->
        @include('member.layouts.partials.header')
        <!-- /app-header -->

        <!-- Start::app-sidebar -->
        @include('member.layouts.partials.sidebar')
        <!-- End::app-sidebar -->

        <!-- Start::app-content -->
        <div class="main-content app-content">
            <div class="container-fluid">
                <!-- Flash Messages - Using SweetAlert2 toasts only (removed duplicate Bootstrap alerts) -->
                
                @yield('content')
            </div>
        </div>
        <!-- End::app-content -->

        <!-- Footer Start -->
        @include('admin.layouts.partials.footer')
        <!-- Footer End -->
    </div>

    <!-- Scroll To Top -->
    <div class="scrollToTop">
        <span class="arrow"><i class="fe fe-arrow-up"></i></span>
    </div>
    <div id="responsive-overlay"></div>
    <!-- Scroll To Top End -->

    <!-- jQuery JS -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>

    <!-- Popper JS -->
    <script src="{{ asset('admin-assets/libs/@popperjs/core/umd/popper.min.js') }}"></script>

    <!-- Bootstrap JS -->
    <script src="{{ asset('admin-assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Defaultmenu JS -->
    <script src="{{ asset('admin-assets/js/defaultmenu.min.js') }}"></script>

    <!-- Node Waves JS-->
    <script src="{{ asset('admin-assets/libs/node-waves/waves.min.js') }}"></script>

    <!-- Sticky JS -->
    <script src="{{ asset('admin-assets/js/sticky.js') }}"></script>

    <!-- Simplebar JS -->
    <script src="{{ asset('admin-assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('admin-assets/js/simplebar.js') }}"></script>

    <!-- Color Picker JS -->
    <script src="{{ asset('admin-assets/libs/@simonwep/pickr/pickr.es5.min.js') }}"></script>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Flash Messages with SweetAlert2 -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('success'))
                @php
                    $successData = session('success');
                    
                    // Handle both string and array success messages
                    if (is_array($successData)) {
                        // For array data (like product purchases), create a meaningful message
                        $successMessage = '';
                        
                        if (isset($successData['purchase_type']) && $successData['purchase_type'] === 'product_purchase_pending') {
                            $successMessage = 'Product purchase successful! Your points are pending activation.';
                        } elseif (isset($successData['purchase_type']) && $successData['purchase_type'] === 'product_purchase') {
                            $successMessage = 'Product purchased successfully! Points have been added to your account.';
                        } elseif (isset($successData['purchase_type']) && $successData['purchase_type'] === 'product_purchase_reserve') {
                            $successMessage = 'Product purchased successfully! ' . ($successData['points_purchased'] ?? 0) . ' points added to your reserve balance.';
                        } elseif (isset($successData['product_name'])) {
                            $successMessage = 'Purchase completed successfully for: ' . $successData['product_name'];
                        } else {
                            $successMessage = 'Operation completed successfully!';
                        }
                        
                        // Use the array as identifier for duplicate prevention
                        $messageId = md5(json_encode($successData));
                    } else {
                        // Handle string messages
                        $successMessage = (string) $successData;
                        $messageId = $successMessage;
                    }
                @endphp
                
                // Check if this is the same message from last display to prevent duplicates
                const successMessage = {!! json_encode($successMessage) !!};
                const messageId = {!! json_encode($messageId) !!};
                const lastDisplayedMessage = sessionStorage.getItem('last_success_message');
                const lastDisplayTime = sessionStorage.getItem('last_success_time');
                const currentTime = Date.now();
                
                // Only show if it's a different message or more than 30 seconds have passed
                if (messageId !== lastDisplayedMessage || !lastDisplayTime || (currentTime - parseInt(lastDisplayTime)) > 30000) {
                    Swal.fire({
                        title: 'Success!',
                        text: successMessage,
                        icon: 'success',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 4000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer);
                            toast.addEventListener('mouseleave', Swal.resumeTimer);
                        }
                    });
                    
                    // Store the message ID and timestamp to prevent duplicates
                    sessionStorage.setItem('last_success_message', messageId);
                    sessionStorage.setItem('last_success_time', currentTime.toString());
                }
            @endif

            @if(session('error'))
                Swal.fire({
                    title: 'Error!',
                    text: '{{ session('error') }}',
                    icon: 'error',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 5000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer);
                        toast.addEventListener('mouseleave', Swal.resumeTimer);
                    }
                });
            @endif

            @if(session('warning'))
                Swal.fire({
                    title: 'Warning!',
                    text: '{{ session('warning') }}',
                    icon: 'warning',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 4500,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer);
                        toast.addEventListener('mouseleave', Swal.resumeTimer);
                    }
                });
            @endif

            @if(session('info'))
                Swal.fire({
                    title: 'Info',
                    text: '{{ session('info') }}',
                    icon: 'info',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 4000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer);
                        toast.addEventListener('mouseleave', Swal.resumeTimer);
                    }
                });
            @endif

            @if($errors->any())
                let errorMessages = [
                    @foreach($errors->all() as $error)
                        '{{ addslashes($error) }}',
                    @endforeach
                ];
                
                Swal.fire({
                    title: 'Validation Errors',
                    html: '<ul style="text-align: left; margin: 0; padding-left: 20px;">' + 
                          errorMessages.map(error => '<li>' + error + '</li>').join('') + 
                          '</ul>',
                    icon: 'error',
                    confirmButtonText: 'Fix Errors',
                    customClass: {
                        popup: 'swal-wide'
                    }
                });
            @endif
        });
    </script>

    <!-- Modern Header JS -->
    {{-- <script src="{{ asset('admin-assets/js/modern-header.js') }}"></script> --}}

    <!-- Custom-Switcher JS -->
    <script>
        // Defensive wrapper for custom-switcher to prevent null element errors
        document.addEventListener('DOMContentLoaded', function() {
            try {
                // Only load custom-switcher if required elements exist
                const switcherElements = document.querySelectorAll('[data-bs-toggle="switcher"]');
                if (switcherElements.length > 0) {
                    // Load the script only if switcher elements exist
                    const script = document.createElement('script');
                    script.src = "{{ asset('admin-assets/js/custom-switcher.min.js') }}";
                    script.onerror = function() {
                        console.warn('Custom switcher script could not be loaded');
                    };
                    document.head.appendChild(script);
                }
            } catch (error) {
                console.warn('Custom switcher initialization failed:', error);
            }
        });
    </script>

    <!-- Additional JavaScript -->
    @stack('scripts')

    <!-- Custom JS -->
    <script src="{{ asset('admin-assets/js/custom.js') }}"></script>

    <!-- Real-time Notification System -->
    <script>
        // Notification System Variables
        let notificationPollingInterval;
        let currentFilter = 'all';
        let notifications = [];
        let unreadCount = 0;

        // Initialize notification system when page loads
        document.addEventListener('DOMContentLoaded', function() {
            initializeNotificationSystem();
        });

        // Initialize the notification system
        function initializeNotificationSystem() {
            // Set up filter buttons
            setupNotificationFilters();
            
            // Load initial notifications
            loadNotifications();
            
            // Start real-time polling every 30 seconds
            startNotificationPolling();
            
            // Set up notification dropdown events
            setupNotificationEvents();

            console.log('🔔 Real-time notification system initialized');
        }

        // Set up notification filter buttons
        function setupNotificationFilters() {
            const filterButtons = document.querySelectorAll('input[name="notificationFilter"]');
            filterButtons.forEach(button => {
                button.addEventListener('change', function() {
                    if (this.checked) {
                        currentFilter = this.id.replace('filter-', '');
                        loadNotifications();
                    }
                });
            });
        }

        // Load notifications from server
        function loadNotifications() {
            fetch('{{ route('member.notifications.header') }}', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    notifications = data.notifications;
                    unreadCount = data.unread_count;
                    updateNotificationUI();
                    console.log(`📬 Loaded ${notifications.length} notifications, ${unreadCount} unread`);
                }
            })
            .catch(error => {
                console.error('❌ Failed to load notifications:', error);
            });
        }

        // Start polling for new notifications
        function startNotificationPolling() {
            // Clear existing interval if any
            if (notificationPollingInterval) {
                clearInterval(notificationPollingInterval);
            }

            // Poll every 30 seconds for real-time updates
            notificationPollingInterval = setInterval(function() {
                loadNotifications();
            }, 30000);

            console.log('🔄 Started real-time notification polling (30s interval)');
        }

        // Stop notification polling
        function stopNotificationPolling() {
            if (notificationPollingInterval) {
                clearInterval(notificationPollingInterval);
                notificationPollingInterval = null;
                console.log('⏹️ Stopped notification polling');
            }
        }

        // Update notification UI
        function updateNotificationUI() {
            updateNotificationBadge();
            updateNotificationDropdown();
        }

        // Update notification badge in header
        function updateNotificationBadge() {
            const badge = document.getElementById('notification-count');
            const unreadSpan = document.getElementById('unread-count');

            // Always show badge, even when count is 0
            badge.textContent = unreadCount > 99 ? '99+' : unreadCount;
            badge.style.display = 'block';
            
            if (unreadCount > 0) {
                badge.className = 'badge bg-danger rounded-pill header-icon-badge pulse-animation';
                unreadSpan.textContent = `${unreadCount} New`;
                unreadSpan.className = 'badge bg-danger-transparent';
            } else {
                badge.className = 'badge bg-secondary rounded-pill header-icon-badge';
                unreadSpan.textContent = 'All Read';
                unreadSpan.className = 'badge bg-success-transparent';
            }
        }

        // Update notification dropdown content
        function updateNotificationDropdown() {
            const notificationsList = document.getElementById('notifications-list');
            const noNotifications = document.getElementById('no-notifications');

            // Filter notifications based on current filter
            let filteredNotifications = filterNotifications(notifications, currentFilter);

            if (filteredNotifications.length === 0) {
                noNotifications.style.display = 'block';
                // Hide existing notification items
                const existingItems = notificationsList.querySelectorAll('.notification-item');
                existingItems.forEach(item => item.remove());
                return;
            }

            noNotifications.style.display = 'none';

            // Clear existing notification items
            const existingItems = notificationsList.querySelectorAll('.notification-item');
            existingItems.forEach(item => item.remove());

            // Add new notification items
            filteredNotifications.forEach(notification => {
                const notificationItem = createNotificationElement(notification);
                notificationsList.appendChild(notificationItem);
            });
        }

        // Filter notifications based on type
        function filterNotifications(notifications, filter) {
            switch (filter) {
                case 'unread':
                    return notifications.filter(n => !n.is_read);
                case 'important':
                    return notifications.filter(n => n.is_important);
                default:
                    return notifications;
            }
        }

        // Create notification HTML element
        function createNotificationElement(notification) {
            const li = document.createElement('li');
            li.className = `notification-item dropdown-item px-3 py-2 ${notification.is_read ? '' : 'bg-light'}`;
            li.style.cursor = 'pointer';
            li.dataset.notificationId = notification.id;

            const categoryColorMap = {
                'success': 'success',
                'warning': 'warning', 
                'info': 'info',
                'danger': 'danger'
            };

            const categoryColor = categoryColorMap[notification.category] || 'primary';
            
            li.innerHTML = `
                <div class="d-flex align-items-start">
                    <div class="me-3">
                        <span class="avatar avatar-md avatar-rounded bg-${categoryColor}-transparent">
                            <i class="${notification.icon}"></i>
                        </span>
                    </div>
                    <div class="flex-fill">
                        <div class="d-flex align-items-center justify-content-between">
                            <h6 class="mb-1 fw-medium ${notification.is_read ? 'text-muted' : ''}">${notification.title}</h6>
                            ${!notification.is_read ? '<span class="badge bg-primary rounded-pill">New</span>' : ''}
                            ${notification.is_important ? '<i class="fe fe-star text-warning" title="Important"></i>' : ''}
                        </div>
                        <p class="mb-1 fs-13 ${notification.is_read ? 'text-muted' : ''}">${notification.message}</p>
                        <div class="d-flex align-items-center justify-content-between">
                            <small class="text-muted">${notification.time_ago}</small>
                            ${notification.action_url ? `<a href="${notification.action_url}" class="btn btn-sm btn-outline-primary">${notification.action_text || 'View'}</a>` : ''}
                        </div>
                    </div>
                    <div class="ms-2">
                        <button class="btn btn-sm btn-light" onclick="markNotificationRead(${notification.id}, event)" title="Mark as read">
                            <i class="fe fe-check"></i>
                        </button>
                    </div>
                </div>
            `;

            // Add click handler for the notification item
            li.addEventListener('click', function(e) {
                // Don't trigger if clicking on action buttons
                if (e.target.closest('button') || e.target.closest('a')) {
                    return;
                }

                if (!notification.is_read) {
                    markNotificationRead(notification.id, e);
                }

                if (notification.action_url) {
                    window.location.href = notification.action_url;
                }
            });

            return li;
        }

        // Mark notification as read
        function markNotificationRead(notificationId, event) {
            if (event) {
                event.stopPropagation();
            }

            fetch(`{{ url('member/notifications') }}/${notificationId}/read`, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update the notification in local array
                    const notification = notifications.find(n => n.id == notificationId);
                    if (notification) {
                        notification.is_read = true;
                    }
                    
                    // Update UI
                    updateNotificationUI();
                    showToast('Notification marked as read', 'success');
                }
            })
            .catch(error => {
                console.error('❌ Failed to mark notification as read:', error);
                showToast('Failed to update notification', 'error');
            });
        }

        // Mark all notifications as read
        function markAllNotificationsRead() {
            if (unreadCount === 0) {
                showToast('All notifications are already read', 'info');
                return;
            }

            fetch('{{ route('member.notifications.mark-all-read') }}', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Mark all notifications as read in local array
                    notifications.forEach(n => n.is_read = true);
                    unreadCount = 0;
                    
                    // Update UI
                    updateNotificationUI();
                    showToast(data.message, 'success');
                }
            })
            .catch(error => {
                console.error('❌ Failed to mark all notifications as read:', error);
                showToast('Failed to update notifications', 'error');
            });
        }

        // Set up notification dropdown events
        function setupNotificationEvents() {
            const dropdown = document.getElementById('notificationsDropdown');
            
            // Refresh notifications when dropdown is opened
            dropdown.addEventListener('show.bs.dropdown', function() {
                loadNotifications();
            });

            // Stop polling when page is hidden (battery optimization)
            document.addEventListener('visibilitychange', function() {
                if (document.hidden) {
                    stopNotificationPolling();
                } else {
                    startNotificationPolling();
                }
            });
        }

        // Simple toast notification function
        function showToast(message, type = 'info') {
            // Create toast element
            const toast = document.createElement('div');
            toast.className = `alert alert-${type} position-fixed top-0 end-0 m-3`;
            toast.style.zIndex = '9999';
            toast.style.minWidth = '300px';
            toast.innerHTML = `
                <div class="d-flex align-items-center">
                    <i class="fe fe-${type === 'success' ? 'check-circle' : type === 'error' ? 'alert-circle' : 'info'} me-2"></i>
                    <span>${message}</span>
                    <button type="button" class="btn-close ms-auto" aria-label="Close"></button>
                </div>
            `;

            // Add to page
            document.body.appendChild(toast);

            // Auto remove after 3 seconds
            setTimeout(() => {
                if (toast.parentNode) {
                    toast.parentNode.removeChild(toast);
                }
            }, 3000);

            // Add click handler for close button
            const closeBtn = toast.querySelector('.btn-close');
            if (closeBtn) {
                closeBtn.addEventListener('click', () => {
                    if (toast.parentNode) {
                        toast.parentNode.removeChild(toast);
                    }
                });
            }
        }

        // Function to manually trigger notification check (for testing)
        function refreshNotifications() {
            loadNotifications();
            showToast('Notifications refreshed!', 'success');
        }

        // Test function for development
        function testNotifications() {
            fetch('{{ route('member.notifications.test') }}', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                loadNotifications();
                showToast('Test notifications created!', 'success');
            })
            .catch(error => {
                console.error('Failed to create test notifications:', error);
            });
        }

        // Expose functions to global scope for console testing
        window.notificationSystem = {
            refresh: refreshNotifications,
            test: testNotifications,
            markAllRead: markAllNotificationsRead,
            start: startNotificationPolling,
            stop: stopNotificationPolling
        };
    </script>

    <!-- Service Worker Registration -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('/service-worker.js')
                .then(function(registration) {
                    console.log('Service Worker registered successfully:', registration.scope);
                    
                    // Handle package cache clearing
                    if (window.location.pathname.includes('/member/packages')) {
                        registration.active && registration.active.postMessage({
                            type: 'CLEAR_PACKAGE_CACHE'
                        });
                    }
                })
                .catch(function(error) {
                    console.warn('Service Worker registration failed:', error);
                });
            });
        }
    </script>
</body>
</html>
