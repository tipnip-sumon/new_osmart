<!DOCTYPE html>
<html lang="en" dir="ltr" data-nav-layout="vertical" data-theme-mode="light" data-header-styles="light" data-menu-styles="dark" data-toggled="close">

<head>
    <!-- Meta Data -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=no'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Admin Password Reset - {{ config('app.name') }}</title>
    <meta name="Description" content="Admin password reset for {{ config('app.name') }}">
    <meta name="Author" content="{{ config('app.name') }}">

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('admin-assets/images/brand-logos/favicon.ico') }}" type="image/x-icon">

    <!-- Bootstrap Css -->
    <link id="style" href="{{ asset('admin-assets/libs/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Style Css -->
    <link href="{{ asset('admin-assets/css/styles.min.css') }}" rel="stylesheet">

    <!-- Icons Css -->
    <link href="{{ asset('admin-assets/css/icons.css') }}" rel="stylesheet">

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
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            box-shadow: 0 10px 30px rgba(240, 147, 251, 0.3);
        }
        
        .btn-admin-reset {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            border: none;
            padding: 12px 30px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-admin-reset:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(240, 147, 251, 0.4);
        }
    </style>
</head>

<body>
    <div class="authentication-page">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-4 col-lg-5 col-md-6 col-sm-8">
                    <div class="card auth-card border-0">
                        <div class="card-body p-5">
                            <!-- Admin Logo -->
                            <div class="admin-logo">
                                <i class="ri-lock-unlock-line text-white" style="font-size: 2rem;"></i>
                            </div>

                            <!-- Header -->
                            <div class="text-center mb-4">
                                <h3 class="fw-bold text-dark">Reset Password</h3>
                                <p class="text-muted mb-0">Enter your email to receive reset instructions</p>
                            </div>

                            <!-- Alert Messages -->
                            @if (session('status'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <i class="ri-check-line me-2"></i>{{ session('status') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            @if (session('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <i class="ri-error-warning-line me-2"></i>{{ session('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            <!-- Reset Form -->
                            <form action="{{ route('admin.password.email') }}" method="POST">
                                @csrf

                                <!-- Email Field -->
                                <div class="mb-4">
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
                                               placeholder="Enter your admin email"
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

                                <!-- Submit Button -->
                                <div class="d-grid mb-3">
                                    <button type="submit" class="btn btn-primary btn-admin-reset">
                                        <i class="ri-mail-send-line me-2"></i>Send Reset Link
                                    </button>
                                </div>
                            </form>

                            <!-- Footer Links -->
                            <div class="text-center">
                                <a href="{{ route('admin.login') }}" class="text-decoration-none">
                                    <small><i class="ri-arrow-left-line me-1"></i>Back to Login</small>
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

    <script>
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
    </script>
</body>

</html>
