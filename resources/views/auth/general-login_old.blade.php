@extends('layouts.a                            <div class="login-header mb-4">
                                <h4 class="text-dark mb-2">Customer Login</h4>
                                <p class="text-muted">Sign in to shop and purchase products</p>
                                <div class="text-center mb-3">
                                    <a href="{{ route('affiliate.login') }}" class="btn btn-outline-primary btn-sm">
                                        <i class="ti-user me-1"></i> Affiliate Login
                                    </a>
                                </div>
                            </div>

@section('title', 'General Login')

@section('content')
<div class="login-wrapper d-flex align-items-center justify-content-center text-center">
    <!-- Background Shape-->
    <div class="background-shape"></div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-10 col-lg-8">
                <!-- Logo-->
                <a class="login-logo" href="{{ route('home') }}">
                    <img src="{{ asset('assets/img/core-img/logo-white.png') }}" alt="">
                </a>
                <!-- Login Form Card-->
                <div class="login-form-card">
                    <div class="card shadow-lg border-0">
                        <div class="card-body p-4">
                            <div class="login-header mb-4">
                                <h4 class="mb-2">General Login</h4>
                                <p class="text-muted">Sign in to your account</p>
                            </div>

                            @if($errors->any())
                                <div class="alert alert-danger">
                                    @foreach($errors->all() as $error)
                                        <div>{{ $error }}</div>
                                    @endforeach
                                </div>
                            @endif

                            @if(session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif

                            <form method="POST" action="{{ route('general.login') }}">
                                @csrf
                                <div class="form-group mb-3">
                                    <label class="form-label" for="email">Email Address</label>
                                    <input class="form-control @error('email') is-invalid @enderror" 
                                           id="email" 
                                           type="email" 
                                           name="email" 
                                           value="{{ old('email') }}" 
                                           placeholder="Enter your email"
                                           required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label" for="password">Password</label>
                                    <input class="form-control @error('password') is-invalid @enderror" 
                                           id="password" 
                                           type="password" 
                                           name="password" 
                                           placeholder="Enter your password"
                                           required>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group d-flex align-items-center justify-content-between mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" id="remember" type="checkbox" name="remember">
                                        <label class="form-check-label" for="remember">Remember me</label>
                                    </div>
                                    <a class="text-primary" href="{{ route('password.request') }}">Forgot Password?</a>
                                </div>

                                <button class="btn btn-primary btn-lg w-100 mb-3" type="submit">
                                    <i class="ti ti-login me-2"></i>Sign In
                                </button>
                            </form>

                            <div class="login-meta-data">
                                <div class="text-center">
                                    <p class="mb-2">Don't have an account? 
                                        <a class="text-primary" href="{{ route('register') }}">Sign Up</a>
                                    </p>
                                    <div class="divider">
                                        <span>OR</span>
                                    </div>
                                    <p class="mb-0">
                                        <a class="btn btn-outline-success btn-sm" href="{{ route('affiliate.login') }}">
                                            <i class="ti ti-users me-1"></i>Affiliate Login
                                        </a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.login-wrapper {
    min-height: 100vh;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    position: relative;
}

.background-shape {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.1'%3E%3Ccircle cx='30' cy='30' r='4'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}

.login-logo img {
    max-height: 60px;
    margin-bottom: 2rem;
}

.login-form-card {
    position: relative;
    z-index: 1;
}

.card {
    border-radius: 15px;
    backdrop-filter: blur(10px);
}

.divider {
    position: relative;
    text-align: center;
    margin: 1rem 0;
}

.divider::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    height: 1px;
    background: #dee2e6;
}

.divider span {
    background: white;
    padding: 0 1rem;
    color: #6c757d;
    font-size: 0.875rem;
}

.form-control {
    border-radius: 10px;
    border: 1px solid #e0e6ed;
    padding: 0.75rem 1rem;
}

.form-control:focus {
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    border-color: #667eea;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    border-radius: 10px;
    padding: 0.75rem 1.5rem;
}

.btn-outline-success {
    border-radius: 10px;
}
</style>
@endsection
