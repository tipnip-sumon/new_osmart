@extends('admin.layouts.app')

@section('title', 'Payment Settings')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <h1 class="page-title fw-semibold fs-18 mb-0">Payment Settings</h1>
        <div class="ms-md-1 ms-0">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.settings.index') }}">Settings</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Payment</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header Close -->

    <!-- Settings Navigation -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-body p-0">
                    <div class="d-flex">
                        <nav class="nav nav-tabs flex-nowrap" id="settingsTabs" role="tablist">
                            <a class="nav-link" href="{{ route('admin.settings.general') }}">
                                <i class="ri-settings-line me-1"></i> General
                            </a>
                            <a class="nav-link active" href="{{ route('admin.settings.payment') }}">
                                <i class="ri-bank-card-line me-1"></i> Payment
                            </a>
                            <a class="nav-link" href="{{ route('admin.settings.shipping') }}">
                                <i class="ri-truck-line me-1"></i> Shipping
                            </a>
                            <a class="nav-link" href="{{ route('admin.settings.tax') }}">
                                <i class="ri-calculator-line me-1"></i> Tax
                            </a>
                            <a class="nav-link" href="{{ route('admin.settings.email') }}">
                                <i class="ri-mail-line me-1"></i> Email
                            </a>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.settings.payment.update') }}" method="POST" id="paymentSettingsForm">
        @csrf
        @method('PUT')
        
        <div class="row">
            <!-- Main Payment Settings -->
            <div class="col-xl-8">
                <!-- Payment Gateways -->
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Payment Gateways</div>
                    </div>
                    <div class="card-body">
                        <!-- PayPal Settings -->
                        <div class="payment-gateway-section mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="d-flex align-items-center">
                                    <img src="https://upload.wikimedia.org/wikipedia/commons/b/b5/PayPal.svg" alt="PayPal" style="height: 30px;" class="me-3">
                                    <h6 class="mb-0">PayPal</h6>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="paypal_enabled" name="paypal_enabled" value="1" 
                                            {{ old('paypal_enabled', $paymentSettings['paypal_enabled']) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="paypal_enabled">Enable</label>
                                </div>
                            </div>
                            
                            <div class="row" id="paypal_settings" style="{{ old('paypal_enabled', $paymentSettings['paypal_enabled']) ? '' : 'display: none;' }}">
                                <div class="col-md-6 mb-3">
                                    <label for="paypal_mode" class="form-label">Mode</label>
                                    <select class="form-select @error('paypal_mode') is-invalid @enderror" id="paypal_mode" name="paypal_mode">
                                        <option value="sandbox" {{ old('paypal_mode', $paymentSettings['paypal_mode']) == 'sandbox' ? 'selected' : '' }}>Sandbox (Testing)</option>
                                        <option value="live" {{ old('paypal_mode', $paymentSettings['paypal_mode']) == 'live' ? 'selected' : '' }}>Live (Production)</option>
                                    </select>
                                    @error('paypal_mode')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="paypal_client_id" class="form-label">Client ID</label>
                                    <input type="text" class="form-control @error('paypal_client_id') is-invalid @enderror" 
                                            id="paypal_client_id" name="paypal_client_id" value="{{ old('paypal_client_id', $paymentSettings['paypal_client_id']) }}" 
                                            placeholder="Enter PayPal Client ID">
                                    @error('paypal_client_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label for="paypal_client_secret" class="form-label">Client Secret</label>
                                    <input type="password" class="form-control @error('paypal_client_secret') is-invalid @enderror" 
                                            id="paypal_client_secret" name="paypal_client_secret" value="{{ old('paypal_client_secret', $paymentSettings['paypal_client_secret']) }}" 
                                            placeholder="Enter PayPal Client Secret">
                                    @error('paypal_client_secret')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <hr>

                        <!-- Stripe Settings -->
                        <div class="payment-gateway-section mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="d-flex align-items-center">
                                    <img src="https://upload.wikimedia.org/wikipedia/commons/b/ba/Stripe_Logo%2C_revised_2016.svg" alt="Stripe" style="height: 25px;" class="me-3">
                                    <h6 class="mb-0">Stripe</h6>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="stripe_enabled" name="stripe_enabled" value="1" 
                                            {{ old('stripe_enabled', $paymentSettings['stripe_enabled']) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="stripe_enabled">Enable</label>
                                </div>
                            </div>
                            
                            <div class="row" id="stripe_settings" style="{{ old('stripe_enabled', $paymentSettings['stripe_enabled']) ? '' : 'display: none;' }}">
                                <div class="col-md-6 mb-3">
                                    <label for="stripe_publishable_key" class="form-label">Publishable Key</label>
                                    <input type="text" class="form-control @error('stripe_publishable_key') is-invalid @enderror" 
                                            id="stripe_publishable_key" name="stripe_publishable_key" value="{{ old('stripe_publishable_key', $paymentSettings['stripe_publishable_key']) }}" 
                                            placeholder="pk_test_...">
                                    @error('stripe_publishable_key')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="stripe_secret_key" class="form-label">Secret Key</label>
                                    <input type="password" class="form-control @error('stripe_secret_key') is-invalid @enderror" 
                                            id="stripe_secret_key" name="stripe_secret_key" value="{{ old('stripe_secret_key', $paymentSettings['stripe_secret_key']) }}" 
                                            placeholder="sk_test_...">
                                    @error('stripe_secret_key')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label for="stripe_webhook_secret" class="form-label">Webhook Secret</label>
                                    <input type="password" class="form-control @error('stripe_webhook_secret') is-invalid @enderror" 
                                            id="stripe_webhook_secret" name="stripe_webhook_secret" value="{{ old('stripe_webhook_secret', $paymentSettings['stripe_webhook_secret']) }}" 
                                            placeholder="whsec_...">
                                    @error('stripe_webhook_secret')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <hr>

                        <!-- Razorpay Settings -->
                        <div class="payment-gateway-section mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="d-flex align-items-center">
                                    <img src="https://razorpay.com/assets/razorpay-logo.svg" alt="Razorpay" style="height: 25px;" class="me-3">
                                    <h6 class="mb-0">Razorpay</h6>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="razorpay_enabled" name="razorpay_enabled" value="1" 
                                            {{ old('razorpay_enabled', $paymentSettings['razorpay_enabled']) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="razorpay_enabled">Enable</label>
                                </div>
                            </div>
                            
                            <div class="row" id="razorpay_settings" style="{{ old('razorpay_enabled', $paymentSettings['razorpay_enabled']) ? '' : 'display: none;' }}">
                                <div class="col-md-6 mb-3">
                                    <label for="razorpay_key_id" class="form-label">Key ID</label>
                                    <input type="text" class="form-control @error('razorpay_key_id') is-invalid @enderror" 
                                            id="razorpay_key_id" name="razorpay_key_id" value="{{ old('razorpay_key_id', $paymentSettings['razorpay_key_id']) }}" 
                                            placeholder="rzp_test_...">
                                    @error('razorpay_key_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="razorpay_key_secret" class="form-label">Key Secret</label>
                                    <input type="password" class="form-control @error('razorpay_key_secret') is-invalid @enderror" 
                                            id="razorpay_key_secret" name="razorpay_key_secret" value="{{ old('razorpay_key_secret', $paymentSettings['razorpay_key_secret']) }}" 
                                            placeholder="Enter Razorpay Key Secret">
                                    @error('razorpay_key_secret')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <hr>

                        <!-- Bank Transfer Settings -->
                        <div class="payment-gateway-section">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="d-flex align-items-center">
                                    <i class="ri-bank-line fs-24 text-primary me-3"></i>
                                    <h6 class="mb-0">Bank Transfer</h6>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="bank_transfer_enabled" name="bank_transfer_enabled" value="1" 
                                            {{ old('bank_transfer_enabled', $paymentSettings['bank_transfer_enabled']) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="bank_transfer_enabled">Enable</label>
                                </div>
                            </div>
                            
                            <div class="row" id="bank_settings" style="{{ old('bank_transfer_enabled', $paymentSettings['bank_transfer_enabled']) ? '' : 'display: none;' }}">
                                <div class="col-md-6 mb-3">
                                    <label for="bank_name" class="form-label">Bank Name</label>
                                    <input type="text" class="form-control @error('bank_name') is-invalid @enderror" 
                                            id="bank_name" name="bank_name" value="{{ old('bank_name', $paymentSettings['bank_name']) }}" 
                                            placeholder="Enter bank name">
                                    @error('bank_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="bank_account_name" class="form-label">Account Name</label>
                                    <input type="text" class="form-control @error('bank_account_name') is-invalid @enderror" 
                                            id="bank_account_name" name="bank_account_name" value="{{ old('bank_account_name', $paymentSettings['bank_account_name']) }}" 
                                            placeholder="Enter account name">
                                    @error('bank_account_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="bank_account_number" class="form-label">Account Number</label>
                                    <input type="text" class="form-control @error('bank_account_number') is-invalid @enderror" 
                                            id="bank_account_number" name="bank_account_number" value="{{ old('bank_account_number', $paymentSettings['bank_account_number']) }}" 
                                            placeholder="XXXX-XXXX-XXXX-1234">
                                    @error('bank_account_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="bank_routing_number" class="form-label">Routing Number</label>
                                    <input type="text" class="form-control @error('bank_routing_number') is-invalid @enderror" 
                                            id="bank_routing_number" name="bank_routing_number" value="{{ old('bank_routing_number', $paymentSettings['bank_routing_number']) }}" 
                                            placeholder="123456789">
                                    @error('bank_routing_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Configuration -->
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Payment Configuration</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="default_payment_method" class="form-label">Default Payment Method</label>
                                <select class="form-select @error('default_payment_method') is-invalid @enderror" id="default_payment_method" name="default_payment_method">
                                    <option value="stripe" {{ old('default_payment_method', $paymentSettings['default_payment_method']) == 'stripe' ? 'selected' : '' }}>Stripe</option>
                                    <option value="paypal" {{ old('default_payment_method', $paymentSettings['default_payment_method']) == 'paypal' ? 'selected' : '' }}>PayPal</option>
                                    <option value="razorpay" {{ old('default_payment_method', $paymentSettings['default_payment_method']) == 'razorpay' ? 'selected' : '' }}>Razorpay</option>
                                    <option value="bank_transfer" {{ old('default_payment_method', $paymentSettings['default_payment_method']) == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                </select>
                                @error('default_payment_method')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="payment_timeout" class="form-label">Payment Timeout (minutes)</label>
                                <input type="number" class="form-control @error('payment_timeout') is-invalid @enderror" 
                                        id="payment_timeout" name="payment_timeout" value="{{ old('payment_timeout', $paymentSettings['payment_timeout']) }}" 
                                        placeholder="30" min="5" max="120">
                                @error('payment_timeout')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="minimum_order_amount" class="form-label">Minimum Order Amount</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" class="form-control @error('minimum_order_amount') is-invalid @enderror" 
                                            id="minimum_order_amount" name="minimum_order_amount" value="{{ old('minimum_order_amount', $paymentSettings['minimum_order_amount']) }}" 
                                            placeholder="10.00" step="0.01" min="0">
                                    @error('minimum_order_amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="transaction_fee" class="form-label">Transaction Fee (%)</label>
                                <div class="input-group">
                                    <input type="number" class="form-control @error('transaction_fee') is-invalid @enderror" 
                                            id="transaction_fee" name="transaction_fee" value="{{ old('transaction_fee', $paymentSettings['transaction_fee']) }}" 
                                            placeholder="2.9" step="0.1" min="0" max="10">
                                    <span class="input-group-text">%</span>
                                    @error('transaction_fee')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" id="auto_capture" name="auto_capture" value="1" 
                                            {{ old('auto_capture', $paymentSettings['auto_capture']) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="auto_capture">
                                        Auto Capture Payments
                                    </label>
                                    <div class="form-text">Automatically capture payments when orders are placed</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" id="save_cards" name="save_cards" value="1" 
                                            {{ old('save_cards', $paymentSettings['save_cards']) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="save_cards">
                                        Allow Saving Cards
                                    </label>
                                    <div class="form-text">Allow customers to save payment methods</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Currency Settings -->
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Currency Settings</div>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="accepted_currencies" class="form-label">Accepted Currencies</label>
                            <select class="form-select" id="accepted_currencies" name="accepted_currencies[]" multiple>
                                @php
                                    $acceptedCurrencies = old('accepted_currencies', $paymentSettings['accepted_currencies']);
                                @endphp
                                <option value="USD" {{ in_array('USD', $acceptedCurrencies) ? 'selected' : '' }}>USD - US Dollar</option>
                                <option value="EUR" {{ in_array('EUR', $acceptedCurrencies) ? 'selected' : '' }}>EUR - Euro</option>
                                <option value="GBP" {{ in_array('GBP', $acceptedCurrencies) ? 'selected' : '' }}>GBP - British Pound</option>
                                <option value="CAD" {{ in_array('CAD', $acceptedCurrencies) ? 'selected' : '' }}>CAD - Canadian Dollar</option>
                                <option value="AUD" {{ in_array('AUD', $acceptedCurrencies) ? 'selected' : '' }}>AUD - Australian Dollar</option>
                            </select>
                            <div class="form-text">Hold Ctrl (Cmd on Mac) to select multiple currencies</div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" id="auto_currency_conversion" name="auto_currency_conversion" value="1" 
                                            {{ old('auto_currency_conversion', $paymentSettings['auto_currency_conversion']) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="auto_currency_conversion">
                                        Auto Currency Conversion
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="exchange_rate_provider" class="form-label">Exchange Rate Provider</label>
                                <select class="form-select @error('exchange_rate_provider') is-invalid @enderror" id="exchange_rate_provider" name="exchange_rate_provider">
                                    <option value="fixer.io" {{ old('exchange_rate_provider', $paymentSettings['exchange_rate_provider']) == 'fixer.io' ? 'selected' : '' }}>Fixer.io</option>
                                    <option value="openexchangerates" {{ old('exchange_rate_provider', $paymentSettings['exchange_rate_provider']) == 'openexchangerates' ? 'selected' : '' }}>Open Exchange Rates</option>
                                    <option value="currencylayer" {{ old('exchange_rate_provider', $paymentSettings['exchange_rate_provider']) == 'currencylayer' ? 'selected' : '' }}>CurrencyLayer</option>
                                </select>
                                @error('exchange_rate_provider')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar Settings -->
            <div class="col-xl-4">
                <!-- MLM Payment Settings -->
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">MLM Payment Settings</div>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="commission_payout_method" class="form-label">Commission Payout Method</label>
                            <select class="form-select @error('commission_payout_method') is-invalid @enderror" id="commission_payout_method" name="commission_payout_method">
                                <option value="bank_transfer" {{ old('commission_payout_method', $paymentSettings['commission_payout_method']) == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                <option value="paypal" {{ old('commission_payout_method', $paymentSettings['commission_payout_method']) == 'paypal' ? 'selected' : '' }}>PayPal</option>
                                <option value="stripe" {{ old('commission_payout_method', $paymentSettings['commission_payout_method']) == 'stripe' ? 'selected' : '' }}>Stripe</option>
                                <option value="check" {{ old('commission_payout_method', $paymentSettings['commission_payout_method']) == 'check' ? 'selected' : '' }}>Check</option>
                            </select>
                            @error('commission_payout_method')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="payout_schedule" class="form-label">Payout Schedule</label>
                            <select class="form-select @error('payout_schedule') is-invalid @enderror" id="payout_schedule" name="payout_schedule">
                                <option value="weekly" {{ old('payout_schedule', $paymentSettings['payout_schedule']) == 'weekly' ? 'selected' : '' }}>Weekly</option>
                                <option value="biweekly" {{ old('payout_schedule', $paymentSettings['payout_schedule']) == 'biweekly' ? 'selected' : '' }}>Bi-weekly</option>
                                <option value="monthly" {{ old('payout_schedule', $paymentSettings['payout_schedule']) == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                <option value="quarterly" {{ old('payout_schedule', $paymentSettings['payout_schedule']) == 'quarterly' ? 'selected' : '' }}>Quarterly</option>
                            </select>
                            @error('payout_schedule')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="minimum_commission_payout" class="form-label">Minimum Commission Payout</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control @error('minimum_commission_payout') is-invalid @enderror" 
                                        id="minimum_commission_payout" name="minimum_commission_payout" value="{{ old('minimum_commission_payout', $paymentSettings['minimum_commission_payout']) }}" 
                                        placeholder="50.00" step="0.01" min="0">
                                @error('minimum_commission_payout')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="hold_period" class="form-label">Hold Period (days)</label>
                            <input type="number" class="form-control @error('hold_period') is-invalid @enderror" 
                                    id="hold_period" name="hold_period" value="{{ old('hold_period', $paymentSettings['hold_period']) }}" 
                                    placeholder="7" min="0" max="90">
                            @error('hold_period')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Days to hold commissions before payout</div>
                        </div>
                    </div>
                </div>

                <!-- Security Settings -->
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Security Settings</div>
                    </div>
                    <div class="card-body">
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="ssl_required" name="ssl_required" value="1" 
                                    {{ old('ssl_required', $paymentSettings['ssl_required']) ? 'checked' : '' }}>
                            <label class="form-check-label" for="ssl_required">
                                SSL Required
                            </label>
                        </div>

                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="fraud_detection" name="fraud_detection" value="1" 
                                    {{ old('fraud_detection', $paymentSettings['fraud_detection']) ? 'checked' : '' }}>
                            <label class="form-check-label" for="fraud_detection">
                                Fraud Detection
                            </label>
                        </div>

                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="cvv_verification" name="cvv_verification" value="1" 
                                    {{ old('cvv_verification', $paymentSettings['cvv_verification']) ? 'checked' : '' }}>
                            <label class="form-check-label" for="cvv_verification">
                                CVV Verification
                            </label>
                        </div>

                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="address_verification" name="address_verification" value="1" 
                                    {{ old('address_verification', $paymentSettings['address_verification']) ? 'checked' : '' }}>
                            <label class="form-check-label" for="address_verification">
                                Address Verification
                            </label>
                        </div>

                        <div class="mb-3">
                            <label for="max_payment_attempts" class="form-label">Max Payment Attempts</label>
                            <input type="number" class="form-control @error('max_payment_attempts') is-invalid @enderror" 
                                    id="max_payment_attempts" name="max_payment_attempts" value="{{ old('max_payment_attempts', $paymentSettings['max_payment_attempts']) }}" 
                                    placeholder="3" min="1" max="10">
                            @error('max_payment_attempts')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Gateway Status -->
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Gateway Status</div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">PayPal:</span>
                            <span class="badge bg-success">Active</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Stripe:</span>
                            <span class="badge bg-success">Active</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Razorpay:</span>
                            <span class="badge bg-secondary">Inactive</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Bank Transfer:</span>
                            <span class="badge bg-success">Active</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-body d-flex justify-content-between">
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-secondary" onclick="resetForm()">
                                <i class="ri-refresh-line me-1"></i> Reset
                            </button>
                            <button type="button" class="btn btn-info" onclick="testPaymentGateways()">
                                <i class="ri-test-tube-line me-1"></i> Test Gateways
                            </button>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="ri-save-line me-1"></i> Save Payment Settings
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    // Toggle payment gateway settings based on enable/disable
    document.addEventListener('DOMContentLoaded', function() {
        const gateways = ['paypal', 'stripe', 'razorpay', 'bank_transfer'];
        
        gateways.forEach(function(gateway) {
            const enabledCheckbox = document.getElementById(gateway + '_enabled');
            const settingsDiv = document.getElementById(gateway === 'bank_transfer' ? 'bank_settings' : gateway + '_settings');
            
            if (enabledCheckbox && settingsDiv) {
                enabledCheckbox.addEventListener('change', function() {
                    settingsDiv.style.display = this.checked ? '' : 'none';
                });
            }
        });
    });

    // Reset form function
    function resetForm() {
        if (confirm('Are you sure you want to reset all changes? This will reload the page with original values.')) {
            location.reload();
        }
    }

    // Test payment gateways
    function testPaymentGateways() {
        alert('Payment gateway testing functionality would verify connections to all enabled gateways.');
    }

    // Form validation
    document.getElementById('paymentSettingsForm').addEventListener('submit', function(e) {
        // Check if at least one payment method is enabled
        const enabledMethods = document.querySelectorAll('input[type="checkbox"][id$="_enabled"]:checked');
        
        if (enabledMethods.length === 0) {
            e.preventDefault();
            alert('Please enable at least one payment method.');
            return false;
        }
    });

    // Update gateway status indicators
    function updateGatewayStatus() {
        const gateways = ['paypal', 'stripe', 'razorpay', 'bank_transfer'];
        
        gateways.forEach(function(gateway) {
            const enabledCheckbox = document.getElementById(gateway + '_enabled');
            // You could update status indicators here based on checkbox state
        });
    }
</script>
@endpush
@endsection
