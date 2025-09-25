<!DOCTYPE html>
<html lang="en" dir="ltr" data-nav-layout="vertical" data-theme-mode="light" data-header-styles="light" data-menu-styles="dark" data-toggled="close">

<head>
    <!-- Meta Data -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=no'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Admin Login - {{ config('app.name') }}</title>
    <meta name="Description" content="Admin panel login for {{ config('app.name') }}">
    <meta name="Author" content="{{ config('app.name') }}">
    <meta name="keywords" content="admin, dashboard, login, authentication">

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('admin-assets/images/brand-logos/favicon.ico') }}" type="image/x-icon">

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

    <style>
        .authentication-page {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .auth-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .admin-logo {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        }
        
        .btn-admin-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 12px 30px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-admin-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }
        
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        .floating-particles {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            pointer-events: none;
        }
        
        .particle {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); opacity: 0.7; }
            50% { transform: translateY(-20px) rotate(180deg); opacity: 1; }
        }
    </style>
</head>

<body>
    <div class="authentication-page">
        <!-- Floating Particles Background -->
        <div class="floating-particles">
            <div class="particle" style="left: 10%; width: 20px; height: 20px; animation-delay: 0s;"></div>
            <div class="particle" style="left: 20%; width: 15px; height: 15px; animation-delay: 1s;"></div>
            <div class="particle" style="left: 30%; width: 25px; height: 25px; animation-delay: 2s;"></div>
            <div class="particle" style="left: 40%; width: 18px; height: 18px; animation-delay: 3s;"></div>
            <div class="particle" style="left: 50%; width: 22px; height: 22px; animation-delay: 4s;"></div>
            <div class="particle" style="left: 60%; width: 16px; height: 16px; animation-delay: 5s;"></div>
            <div class="particle" style="left: 70%; width: 24px; height: 24px; animation-delay: 6s;"></div>
            <div class="particle" style="left: 80%; width: 19px; height: 19px; animation-delay: 7s;"></div>
            <div class="particle" style="left: 90%; width: 21px; height: 21px; animation-delay: 8s;"></div>
        </div>

        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-4 col-lg-5 col-md-6 col-sm-8">
                    <div class="card auth-card border-0">
                        <div class="card-body p-5">
                            <!-- Admin Logo -->
                            <div class="admin-logo">
                                <i class="ri-admin-line text-white" style="font-size: 2rem;"></i>
                            </div>

                            <!-- Header -->
                            <div class="text-center mb-4">
                                <h3 class="fw-bold text-dark">Admin Panel</h3>
                                <p class="text-muted mb-0">Sign in to access your dashboard</p>
                            </div>

                            <!-- Alert Messages -->
                            @if (session('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <i class="ri-error-warning-line me-2"></i>{{ session('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <i class="ri-check-line me-2"></i>{{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            <!-- Login Form -->
                            <form action="{{ route('admin.login.submit') }}" method="POST">
                                @csrf

                                <!-- Email Field -->
                                <div class="mb-3">
                                    <label for="email" class="form-label text-default fw-semibold">Email Address</label>
                                    <div class="input-group">
                                        <div class="input-group-text">
                                            <i class="ri-mail-line text-muted"></i>
                                        </div>
                                        <input type="email" 
                                               class="form-control @error('email') is-invalid @enderror" 
                                               id="email" 
                                               name="email" 
                                               value="{{ old('email') }}" 
                                               placeholder="Enter your email"
                                               required 
                                               autocomplete="email" 
                                               autofocus>
                                        @error('email')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Password Field -->
                                <div class="mb-3">
                                    <label for="password" class="form-label text-default fw-semibold">Password</label>
                                    <div class="input-group">
                                        <div class="input-group-text">
                                            <i class="ri-lock-line text-muted"></i>
                                        </div>
                                        <input type="password" 
                                               class="form-control @error('password') is-invalid @enderror" 
                                               id="password" 
                                               name="password" 
                                               placeholder="Enter your password"
                                               required 
                                               autocomplete="current-password">
                                        <button type="button" class="input-group-text" id="toggle-password">
                                            <i class="ri-eye-line" id="toggle-icon"></i>
                                        </button>
                                        @error('password')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Remember Me & Forgot Password -->
                                <div class="d-flex align-items-center justify-content-between mb-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                        <label class="form-check-label text-muted" for="remember">
                                            Remember me
                                        </label>
                                    </div>
                                    <a href="{{ route('admin.password.request') }}" class="text-decoration-none">
                                        <small>Forgot password?</small>
                                    </a>
                                </div>

                                <!-- Login Button -->
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary btn-admin-login">
                                        <i class="ri-login-box-line me-2"></i>Sign In
                                    </button>
                                </div>
                            </form>

                            <!-- Footer Links -->
                            <div class="text-center mt-4">
                                <p class="text-muted mb-2">
                                    <small>Protected by advanced security</small>
                                </p>
                                <a href="{{ route('home') }}" class="text-decoration-none">
                                    <small><i class="ri-arrow-left-line me-1"></i>Back to Website</small>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Copyright -->
                    <div class="text-center mt-4">
                        <p class="text-white-50 mb-0">
                            <small>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</small>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="{{ asset('admin-assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Show/Hide Password -->
    <script>
        document.getElementById('toggle-password').addEventListener('click', function() {
            const password = document.getElementById('password');
            const icon = document.getElementById('toggle-icon');
            
            if (password.type === 'password') {
                password.type = 'text';
                icon.className = 'ri-eye-off-line';
            } else {
                password.type = 'password';
                icon.className = 'ri-eye-line';
            }
        });

        // Auto-hide alerts
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                if (alert) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }
            });
        }, 5000);

        // Prevent form resubmission
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>
</body>

</html>
