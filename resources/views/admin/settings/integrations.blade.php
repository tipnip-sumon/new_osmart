@extends('admin.layouts.app')
@section('title', $pageTitle ?? 'Integration Settings')

@section('content')
<!-- Settings Navigation -->
<x-admin.settings-navigation current="integrations" />

<div class="row mb-4">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-plug me-2"></i>
                    Integration Settings
                </h5>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form action="{{ route('admin.settings.integrations.update') }}" method="POST">
                    @csrf

                    <!-- Payment Gateways -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-credit-card me-2"></i>
                                Payment Gateways
                            </h6>
                        </div>
                    </div>

                    <!-- Stripe -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" name="stripe_enabled" 
                                       id="stripe_enabled" value="1" {{ ($integrations['stripe_enabled'] ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold" for="stripe_enabled">
                                    <i class="fab fa-stripe me-2"></i>Enable Stripe
                                </label>
                            </div>
                            <div class="mb-3">
                                <label for="stripe_public_key" class="form-label">Stripe Public Key</label>
                                <input type="text" class="form-control" name="stripe_public_key" id="stripe_public_key"
                                       value="{{ old('stripe_public_key', $integrations['stripe_public_key'] ?? '') }}"
                                       placeholder="pk_test_...">
                            </div>
                            <div class="mb-3">
                                <label for="stripe_secret_key" class="form-label">Stripe Secret Key</label>
                                <input type="password" class="form-control" name="stripe_secret_key" id="stripe_secret_key"
                                       value="{{ old('stripe_secret_key', $integrations['stripe_secret_key'] ?? '') }}"
                                       placeholder="sk_test_...">
                            </div>
                            <div class="mb-3">
                                <label for="stripe_webhook_secret" class="form-label">Stripe Webhook Secret</label>
                                <input type="password" class="form-control" name="stripe_webhook_secret" id="stripe_webhook_secret"
                                       value="{{ old('stripe_webhook_secret', $integrations['stripe_webhook_secret'] ?? '') }}"
                                       placeholder="whsec_...">
                            </div>
                        </div>

                        <!-- PayPal -->
                        <div class="col-md-6">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" name="paypal_enabled" 
                                       id="paypal_enabled" value="1" {{ ($integrations['paypal_enabled'] ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold" for="paypal_enabled">
                                    <i class="fab fa-paypal me-2"></i>Enable PayPal
                                </label>
                            </div>
                            <div class="mb-3">
                                <label for="paypal_client_id" class="form-label">PayPal Client ID</label>
                                <input type="text" class="form-control" name="paypal_client_id" id="paypal_client_id"
                                       value="{{ old('paypal_client_id', $integrations['paypal_client_id'] ?? '') }}">
                            </div>
                            <div class="mb-3">
                                <label for="paypal_secret" class="form-label">PayPal Secret</label>
                                <input type="password" class="form-control" name="paypal_secret" id="paypal_secret"
                                       value="{{ old('paypal_secret', $integrations['paypal_secret'] ?? '') }}">
                            </div>
                            <div class="mb-3">
                                <label for="paypal_mode" class="form-label">PayPal Mode</label>
                                <select class="form-select" name="paypal_mode" id="paypal_mode">
                                    <option value="sandbox" {{ ($integrations['paypal_mode'] ?? 'sandbox') === 'sandbox' ? 'selected' : '' }}>Sandbox</option>
                                    <option value="live" {{ ($integrations['paypal_mode'] ?? 'sandbox') === 'live' ? 'selected' : '' }}>Live</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Social Media APIs -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-share-alt me-2"></i>
                                Social Media APIs
                            </h6>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="facebook_app_id" class="form-label">
                                    <i class="fab fa-facebook me-2"></i>Facebook App ID
                                </label>
                                <input type="text" class="form-control" name="facebook_app_id" id="facebook_app_id"
                                       value="{{ old('facebook_app_id', $integrations['facebook_app_id'] ?? '') }}">
                            </div>
                            <div class="mb-3">
                                <label for="facebook_app_secret" class="form-label">Facebook App Secret</label>
                                <input type="password" class="form-control" name="facebook_app_secret" id="facebook_app_secret"
                                       value="{{ old('facebook_app_secret', $integrations['facebook_app_secret'] ?? '') }}">
                            </div>
                            <div class="mb-3">
                                <label for="google_client_id" class="form-label">
                                    <i class="fab fa-google me-2"></i>Google Client ID
                                </label>
                                <input type="text" class="form-control" name="google_client_id" id="google_client_id"
                                       value="{{ old('google_client_id', $integrations['google_client_id'] ?? '') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="google_client_secret" class="form-label">Google Client Secret</label>
                                <input type="password" class="form-control" name="google_client_secret" id="google_client_secret"
                                       value="{{ old('google_client_secret', $integrations['google_client_secret'] ?? '') }}">
                            </div>
                            <div class="mb-3">
                                <label for="twitter_api_key" class="form-label">
                                    <i class="fab fa-twitter me-2"></i>Twitter API Key
                                </label>
                                <input type="text" class="form-control" name="twitter_api_key" id="twitter_api_key"
                                       value="{{ old('twitter_api_key', $integrations['twitter_api_key'] ?? '') }}">
                            </div>
                            <div class="mb-3">
                                <label for="twitter_api_secret" class="form-label">Twitter API Secret</label>
                                <input type="password" class="form-control" name="twitter_api_secret" id="twitter_api_secret"
                                       value="{{ old('twitter_api_secret', $integrations['twitter_api_secret'] ?? '') }}">
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Analytics -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-chart-line me-2"></i>
                                Analytics & Tracking
                            </h6>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="google_analytics_id" class="form-label">
                                    <i class="fab fa-google me-2"></i>Google Analytics ID
                                </label>
                                <input type="text" class="form-control" name="google_analytics_id" id="google_analytics_id"
                                       value="{{ old('google_analytics_id', $integrations['google_analytics_id'] ?? '') }}"
                                       placeholder="GA-XXXXXXXXX-X">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="google_tag_manager_id" class="form-label">Google Tag Manager ID</label>
                                <input type="text" class="form-control" name="google_tag_manager_id" id="google_tag_manager_id"
                                       value="{{ old('google_tag_manager_id', $integrations['google_tag_manager_id'] ?? '') }}"
                                       placeholder="GTM-XXXXXXX">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="facebook_pixel_id" class="form-label">
                                    <i class="fab fa-facebook me-2"></i>Facebook Pixel ID
                                </label>
                                <input type="text" class="form-control" name="facebook_pixel_id" id="facebook_pixel_id"
                                       value="{{ old('facebook_pixel_id', $integrations['facebook_pixel_id'] ?? '') }}">
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Other Services -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-cogs me-2"></i>
                                Other Services
                            </h6>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="recaptcha_site_key" class="form-label">
                                    <i class="fas fa-shield-alt me-2"></i>reCAPTCHA Site Key
                                </label>
                                <input type="text" class="form-control" name="recaptcha_site_key" id="recaptcha_site_key"
                                       value="{{ old('recaptcha_site_key', $integrations['recaptcha_site_key'] ?? '') }}">
                            </div>
                            <div class="mb-3">
                                <label for="recaptcha_secret_key" class="form-label">reCAPTCHA Secret Key</label>
                                <input type="password" class="form-control" name="recaptcha_secret_key" id="recaptcha_secret_key"
                                       value="{{ old('recaptcha_secret_key', $integrations['recaptcha_secret_key'] ?? '') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="firebase_server_key" class="form-label">
                                    <i class="fab fa-google me-2"></i>Firebase Server Key
                                </label>
                                <input type="password" class="form-control" name="firebase_server_key" id="firebase_server_key"
                                       value="{{ old('firebase_server_key', $integrations['firebase_server_key'] ?? '') }}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>
                                Save Integration Settings
                            </button>
                            <a href="{{ route('admin.settings.index') }}" class="btn btn-secondary ms-2">
                                <i class="fas fa-times me-2"></i>
                                Cancel
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Toggle payment gateway sections based on checkbox
    $('input[type="checkbox"]').change(function() {
        const target = $(this).attr('id');
        const isChecked = $(this).is(':checked');
        
        // You can add logic here to show/hide related fields
        // when payment gateways are enabled/disabled
    });
});
</script>
@endpush
