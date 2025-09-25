<!-- Modal Login -->
<div class="modal fade modalDemo" id="login">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="header">
                <div class="demo-title">Log in</div>
                <span class="icon-close icon-close-popup" data-bs-dismiss="modal"></span>
            </div>
            <div class="tf-login-form">
                <form id="login-form" action="{{ route('login') }}" method="POST" accept-charset="utf-8">
                    @csrf
                    <div class="tf-field style-1">
                        <input class="tf-field-input tf-input" placeholder=" " type="email" id="property1" name="email" required>
                        <label class="tf-field-label" for="property1">Email *</label>
                    </div>
                    <div class="tf-field style-1">
                        <input class="tf-field-input tf-input" placeholder=" " type="password" id="property2" name="password" required>
                        <label class="tf-field-label" for="property2">Password *</label>
                    </div>
                    <div>
                        <a href="{{ route('password.request') }}" class="btn-link link">Forgot your password?</a>
                    </div>
                    <div class="bottom">
                        <div class="w-100">
                            <button type="submit" class="tf-btn btn-fill animate-hover-btn radius-3 w-100 justify-content-center">
                                <span>Log in</span>
                            </button>
                        </div>
                        <div class="w-100">
                            <a href="{{ route('register') }}" class="btn-link fw-6 w-100 link">
                                New customer? Create your account
                                <i class="icon icon-arrow1-top-left"></i>
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- /Modal Login -->

<!-- Modal Register -->
<div class="modal fade modalDemo" id="register">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="header">
                <div class="demo-title">Register</div>
                <span class="icon-close icon-close-popup" data-bs-dismiss="modal"></span>
            </div>
            <div class="tf-login-form">
                <form id="register-form" action="{{ route('register') }}" method="POST" accept-charset="utf-8">
                    @csrf
                    <div class="tf-field style-1">
                        <input class="tf-field-input tf-input" placeholder=" " type="text" id="property3" name="name" required>
                        <label class="tf-field-label" for="property3">First Name *</label>
                    </div>
                    <div class="tf-field style-1">
                        <input class="tf-field-input tf-input" placeholder=" " type="text" id="property4" name="username" required>
                        <label class="tf-field-label" for="property4">Username *</label>
                    </div>
                    <div class="tf-field style-1">
                        <input class="tf-field-input tf-input" placeholder=" " type="email" id="property5" name="email" required>
                        <label class="tf-field-label" for="property5">Email *</label>
                    </div>
                    <div class="tf-field style-1">
                        <input class="tf-field-input tf-input" placeholder=" " type="password" id="property6" name="password" required>
                        <label class="tf-field-label" for="property6">Password *</label>
                    </div>
                    <div class="tf-field style-1">
                        <input class="tf-field-input tf-input" placeholder=" " type="password" id="property7" name="password_confirmation" required>
                        <label class="tf-field-label" for="property7">Confirm Password *</label>
                    </div>
                    <div class="bottom">
                        <div class="w-100">
                            <button type="submit" class="tf-btn btn-fill animate-hover-btn radius-3 w-100 justify-content-center">
                                <span>Register</span>
                            </button>
                        </div>
                        <div class="w-100">
                            <a href="#login" data-bs-toggle="modal" class="btn-link fw-6 w-100 link">
                                Already have an account? Log in
                                <i class="icon icon-arrow1-top-left"></i>
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- /Modal Register -->