@extends('admin.layouts.app')

@section('title', 'General Settings')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <h1 class="page-title fw-semibold fs-18 mb-0">General Settings</h1>
        <div class="ms-md-1 ms-0">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.settings.general') }}">Settings</a></li>
                    <li class="breadcrumb-item active" aria-current="page">General</li>
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
                            <a class="nav-link active" href="{{ route('admin.settings.general') }}">
                                <i class="ri-settings-line me-1"></i> General
                            </a>
                            <a class="nav-link" href="{{ route('admin.settings.payment') }}">
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

    <form action="{{ route('admin.settings.general.update') }}" method="POST" enctype="multipart/form-data" id="generalSettingsForm">
        @csrf
        @method('PUT')
        
        <div class="row">
            <!-- Main Settings -->
            <div class="col-xl-8">
                <!-- Site Information -->
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Site Information</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="site_name" class="form-label">Site Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('site_name') is-invalid @enderror" 
                                        id="site_name" name="site_name" value="{{ old('site_name', $settings['site_name']) }}" 
                                        placeholder="Enter site name">
                                @error('site_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="site_tagline" class="form-label">Site Tagline</label>
                                <input type="text" class="form-control @error('site_tagline') is-invalid @enderror" 
                                        id="site_tagline" name="site_tagline" value="{{ old('site_tagline', $settings['site_tagline']) }}" 
                                        placeholder="Enter site tagline">
                                @error('site_tagline')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="site_description" class="form-label">Site Description</label>
                            <textarea class="form-control @error('site_description') is-invalid @enderror" 
                                        id="site_description" name="site_description" rows="3" 
                                        placeholder="Enter site description">{{ old('site_description', $settings['site_description']) }}</textarea>
                            @error('site_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="site_url" class="form-label">Site URL</label>
                                <input type="url" class="form-control @error('site_url') is-invalid @enderror" 
                                        id="site_url" name="site_url" value="{{ old('site_url', $settings['site_url']) }}" 
                                        placeholder="https://yourdomain.com">
                                @error('site_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="admin_email" class="form-label">Admin Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('admin_email') is-invalid @enderror" 
                                        id="admin_email" name="admin_email" value="{{ old('admin_email', $settings['admin_email']) }}" 
                                        placeholder="admin@yourdomain.com">
                                @error('admin_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="contact_email" class="form-label">Contact Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('contact_email') is-invalid @enderror" 
                                        id="contact_email" name="contact_email" value="{{ old('contact_email', $settings['contact_email']) }}" 
                                        placeholder="contact@yourdomain.com">
                                @error('contact_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="support_email" class="form-label">Support Email</label>
                                <input type="email" class="form-control @error('support_email') is-invalid @enderror" 
                                        id="support_email" name="support_email" value="{{ old('support_email', $settings['support_email']) }}" 
                                        placeholder="support@yourdomain.com">
                                @error('support_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Company Information -->
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Company Information</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="company_name" class="form-label">Company Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('company_name') is-invalid @enderror" 
                                        id="company_name" name="company_name" value="{{ old('company_name', $settings['company_name']) }}" 
                                        placeholder="Enter company name">
                                @error('company_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="company_phone" class="form-label">Company Phone</label>
                                <input type="text" class="form-control @error('company_phone') is-invalid @enderror" 
                                        id="company_phone" name="company_phone" value="{{ old('company_phone', $settings['company_phone']) }}" 
                                        placeholder="+1 (555) 123-4567">
                                @error('company_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="company_address" class="form-label">Company Address</label>
                            <input type="text" class="form-control @error('company_address') is-invalid @enderror" 
                                    id="company_address" name="company_address" value="{{ old('company_address', $settings['company_address']) }}" 
                                    placeholder="Enter company address">
                            @error('company_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="company_city" class="form-label">City</label>
                                <input type="text" class="form-control @error('company_city') is-invalid @enderror" 
                                        id="company_city" name="company_city" value="{{ old('company_city', $settings['company_city']) }}" 
                                        placeholder="Enter city">
                                @error('company_city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="company_state" class="form-label">State/Province</label>
                                <input type="text" class="form-control @error('company_state') is-invalid @enderror" 
                                        id="company_state" name="company_state" value="{{ old('company_state', $settings['company_state']) }}" 
                                        placeholder="Enter state">
                                @error('company_state')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="company_zip" class="form-label">ZIP/Postal Code</label>
                                <input type="text" class="form-control @error('company_zip') is-invalid @enderror" 
                                        id="company_zip" name="company_zip" value="{{ old('company_zip', $settings['company_zip']) }}" 
                                        placeholder="Enter ZIP code">
                                @error('company_zip')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="company_country" class="form-label">Country</label>
                                <select class="form-select @error('company_country') is-invalid @enderror" id="company_country" name="company_country">
                                    <option value="United States" {{ old('company_country', $settings['company_country']) == 'United States' ? 'selected' : '' }}>United States</option>
                                    <option value="Canada" {{ old('company_country', $settings['company_country']) == 'Canada' ? 'selected' : '' }}>Canada</option>
                                    <option value="United Kingdom" {{ old('company_country', $settings['company_country']) == 'United Kingdom' ? 'selected' : '' }}>United Kingdom</option>
                                    <option value="Australia" {{ old('company_country', $settings['company_country']) == 'Australia' ? 'selected' : '' }}>Australia</option>
                                    <option value="Germany" {{ old('company_country', $settings['company_country']) == 'Germany' ? 'selected' : '' }}>Germany</option>
                                    <option value="France" {{ old('company_country', $settings['company_country']) == 'France' ? 'selected' : '' }}>France</option>
                                    <option value="Other" {{ old('company_country', $settings['company_country']) == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('company_country')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="company_registration" class="form-label">Registration Number</label>
                                <input type="text" class="form-control @error('company_registration') is-invalid @enderror" 
                                        id="company_registration" name="company_registration" value="{{ old('company_registration', $settings['company_registration']) }}" 
                                        placeholder="Enter registration number">
                                @error('company_registration')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Regional Settings -->
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Regional Settings</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="default_language" class="form-label">Default Language</label>
                                <select class="form-select @error('default_language') is-invalid @enderror" id="default_language" name="default_language">
                                    <option value="en" {{ old('default_language', $settings['default_language']) == 'en' ? 'selected' : '' }}>English</option>
                                    <option value="es" {{ old('default_language', $settings['default_language']) == 'es' ? 'selected' : '' }}>Spanish</option>
                                    <option value="fr" {{ old('default_language', $settings['default_language']) == 'fr' ? 'selected' : '' }}>French</option>
                                    <option value="de" {{ old('default_language', $settings['default_language']) == 'de' ? 'selected' : '' }}>German</option>
                                    <option value="it" {{ old('default_language', $settings['default_language']) == 'it' ? 'selected' : '' }}>Italian</option>
                                    <option value="pt" {{ old('default_language', $settings['default_language']) == 'pt' ? 'selected' : '' }}>Portuguese</option>
                                </select>
                                @error('default_language')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="default_currency" class="form-label">Default Currency <span class="text-danger">*</span></label>
                                <select class="form-select @error('default_currency') is-invalid @enderror" id="default_currency" name="default_currency">
                                    <option value="USD" {{ old('default_currency', $settings['default_currency']) == 'USD' ? 'selected' : '' }}>USD - US Dollar</option>
                                    <option value="EUR" {{ old('default_currency', $settings['default_currency']) == 'EUR' ? 'selected' : '' }}>EUR - Euro</option>
                                    <option value="GBP" {{ old('default_currency', $settings['default_currency']) == 'GBP' ? 'selected' : '' }}>GBP - British Pound</option>
                                    <option value="CAD" {{ old('default_currency', $settings['default_currency']) == 'CAD' ? 'selected' : '' }}>CAD - Canadian Dollar</option>
                                    <option value="AUD" {{ old('default_currency', $settings['default_currency']) == 'AUD' ? 'selected' : '' }}>AUD - Australian Dollar</option>
                                    <option value="JPY" {{ old('default_currency', $settings['default_currency']) == 'JPY' ? 'selected' : '' }}>JPY - Japanese Yen</option>
                                </select>
                                @error('default_currency')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="currency_symbol" class="form-label">Currency Symbol</label>
                                <input type="text" class="form-control @error('currency_symbol') is-invalid @enderror" 
                                        id="currency_symbol" name="currency_symbol" value="{{ old('currency_symbol', $settings['currency_symbol']) }}" 
                                        placeholder="$">
                                @error('currency_symbol')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="currency_position" class="form-label">Currency Position</label>
                                <select class="form-select @error('currency_position') is-invalid @enderror" id="currency_position" name="currency_position">
                                    <option value="before" {{ old('currency_position', $settings['currency_position']) == 'before' ? 'selected' : '' }}>Before Amount ($100)</option>
                                    <option value="after" {{ old('currency_position', $settings['currency_position']) == 'after' ? 'selected' : '' }}>After Amount (100$)</option>
                                </select>
                                @error('currency_position')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="default_timezone" class="form-label">Default Timezone <span class="text-danger">*</span></label>
                                <select class="form-select @error('default_timezone') is-invalid @enderror" id="default_timezone" name="default_timezone">
                                    <option value="America/New_York" {{ old('default_timezone', $settings['default_timezone']) == 'America/New_York' ? 'selected' : '' }}>Eastern Time (UTC-5)</option>
                                    <option value="America/Chicago" {{ old('default_timezone', $settings['default_timezone']) == 'America/Chicago' ? 'selected' : '' }}>Central Time (UTC-6)</option>
                                    <option value="America/Denver" {{ old('default_timezone', $settings['default_timezone']) == 'America/Denver' ? 'selected' : '' }}>Mountain Time (UTC-7)</option>
                                    <option value="America/Los_Angeles" {{ old('default_timezone', $settings['default_timezone']) == 'America/Los_Angeles' ? 'selected' : '' }}>Pacific Time (UTC-8)</option>
                                    <option value="UTC" {{ old('default_timezone', $settings['default_timezone']) == 'UTC' ? 'selected' : '' }}>UTC</option>
                                    <option value="Europe/London" {{ old('default_timezone', $settings['default_timezone']) == 'Europe/London' ? 'selected' : '' }}>London (UTC+0)</option>
                                    <option value="Europe/Paris" {{ old('default_timezone', $settings['default_timezone']) == 'Europe/Paris' ? 'selected' : '' }}>Paris (UTC+1)</option>
                                    <option value="Asia/Tokyo" {{ old('default_timezone', $settings['default_timezone']) == 'Asia/Tokyo' ? 'selected' : '' }}>Tokyo (UTC+9)</option>
                                </select>
                                @error('default_timezone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="date_format" class="form-label">Date Format</label>
                                <select class="form-select @error('date_format') is-invalid @enderror" id="date_format" name="date_format">
                                    <option value="M d, Y" {{ old('date_format', $settings['date_format']) == 'M d, Y' ? 'selected' : '' }}>Jan 01, 2025</option>
                                    <option value="d/m/Y" {{ old('date_format', $settings['date_format']) == 'd/m/Y' ? 'selected' : '' }}>01/01/2025</option>
                                    <option value="m/d/Y" {{ old('date_format', $settings['date_format']) == 'm/d/Y' ? 'selected' : '' }}>01/01/2025</option>
                                    <option value="Y-m-d" {{ old('date_format', $settings['date_format']) == 'Y-m-d' ? 'selected' : '' }}>2025-01-01</option>
                                    <option value="d-m-Y" {{ old('date_format', $settings['date_format']) == 'd-m-Y' ? 'selected' : '' }}>01-01-2025</option>
                                </select>
                                @error('date_format')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SEO Settings -->
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">SEO Settings</div>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="meta_title" class="form-label">Meta Title</label>
                            <input type="text" class="form-control @error('meta_title') is-invalid @enderror" 
                                    id="meta_title" name="meta_title" value="{{ old('meta_title', $settings['meta_title']) }}" 
                                    placeholder="SEO friendly title" maxlength="60">
                            @error('meta_title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Recommended length: 50-60 characters</div>
                        </div>

                        <div class="mb-3">
                            <label for="meta_description" class="form-label">Meta Description</label>
                            <textarea class="form-control @error('meta_description') is-invalid @enderror" 
                                        id="meta_description" name="meta_description" rows="3" 
                                        placeholder="SEO friendly description" maxlength="160">{{ old('meta_description', $settings['meta_description']) }}</textarea>
                            @error('meta_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Recommended length: 150-160 characters</div>
                        </div>

                        <div class="mb-3">
                            <label for="meta_keywords" class="form-label">Meta Keywords</label>
                            <input type="text" class="form-control @error('meta_keywords') is-invalid @enderror" 
                                    id="meta_keywords" name="meta_keywords" value="{{ old('meta_keywords', $settings['meta_keywords']) }}" 
                                    placeholder="keyword1, keyword2, keyword3">
                            @error('meta_keywords')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Separate keywords with commas</div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="google_analytics" class="form-label">Google Analytics ID</label>
                                <input type="text" class="form-control @error('google_analytics') is-invalid @enderror" 
                                        id="google_analytics" name="google_analytics" value="{{ old('google_analytics', $settings['google_analytics']) }}" 
                                        placeholder="GA-XXXXXXXXX-X">
                                @error('google_analytics')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="google_tag_manager" class="form-label">Google Tag Manager ID</label>
                                <input type="text" class="form-control @error('google_tag_manager') is-invalid @enderror" 
                                        id="google_tag_manager" name="google_tag_manager" value="{{ old('google_tag_manager', $settings['google_tag_manager']) }}" 
                                        placeholder="GTM-XXXXXXX">
                                @error('google_tag_manager')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar Settings -->
            <div class="col-xl-4">
                <!-- Logo & Branding -->
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Logo & Branding</div>
                    </div>
                    <div class="card-body">
                        <!-- Site Logo -->
                        <div class="mb-4">
                            <label for="site_logo" class="form-label">Site Logo</label>
                            @if($settings['site_logo'])
                            <div class="mb-3 text-center">
                                <img src="{{ $settings['site_logo'] }}" alt="Current Logo" 
                                        class="img-fluid rounded" style="max-height: 100px;">
                                <div class="form-text">Current Logo</div>
                            </div>
                            @endif
                            <input type="file" class="form-control @error('site_logo') is-invalid @enderror" 
                                    id="site_logo" name="site_logo" accept="image/*">
                            @error('site_logo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Recommended size: 200x80px, Max: 2MB</div>
                        </div>

                        <!-- Admin Logo -->
                        <div class="mb-4">
                            <label for="admin_logo" class="form-label">Admin Logo</label>
                            @if($settings['admin_logo'])
                            <div class="mb-3 text-center">
                                <img src="{{ $settings['admin_logo'] }}" alt="Current Admin Logo" 
                                        class="img-fluid rounded" style="max-height: 80px;">
                                <div class="form-text">Current Admin Logo</div>
                            </div>
                            @endif
                            <input type="file" class="form-control @error('admin_logo') is-invalid @enderror" 
                                    id="admin_logo" name="admin_logo" accept="image/*">
                            @error('admin_logo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Recommended size: 150x60px, Max: 2MB</div>
                        </div>

                        <!-- Email Logo -->
                        <div class="mb-4">
                            <label for="email_logo" class="form-label">Email Logo</label>
                            @if($settings['email_logo'])
                            <div class="mb-3 text-center">
                                <img src="{{ $settings['email_logo'] }}" alt="Current Email Logo" 
                                        class="img-fluid rounded" style="max-height: 60px;">
                                <div class="form-text">Current Email Logo</div>
                            </div>
                            @endif
                            <input type="file" class="form-control @error('email_logo') is-invalid @enderror" 
                                    id="email_logo" name="email_logo" accept="image/*">
                            @error('email_logo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Recommended size: 200x60px, Max: 2MB</div>
                        </div>

                        <!-- Favicon -->
                        <div class="mb-3">
                            <label for="site_favicon" class="form-label">Favicon</label>
                            @if($settings['site_favicon'])
                            <div class="mb-3">
                                <img src="{{ $settings['site_favicon'] }}" alt="Current Favicon" 
                                        style="width: 32px; height: 32px;">
                                <span class="form-text ms-2">Current Favicon</span>
                            </div>
                            @endif
                            <input type="file" class="form-control @error('site_favicon') is-invalid @enderror" 
                                    id="site_favicon" name="site_favicon" accept=".ico,.png">
                            @error('site_favicon')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Recommended size: 32x32px or 16x16px</div>
                        </div>
                    </div>
                </div>

                <!-- Social Media Links -->
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Social Media Links</div>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="facebook_url" class="form-label">
                                <i class="ri-facebook-fill text-primary me-1"></i> Facebook
                            </label>
                            <input type="url" class="form-control @error('facebook_url') is-invalid @enderror" 
                                    id="facebook_url" name="facebook_url" value="{{ old('facebook_url', $settings['facebook_url']) }}" 
                                    placeholder="https://facebook.com/yourpage">
                            @error('facebook_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="twitter_url" class="form-label">
                                <i class="ri-twitter-fill text-info me-1"></i> Twitter
                            </label>
                            <input type="url" class="form-control @error('twitter_url') is-invalid @enderror" 
                                    id="twitter_url" name="twitter_url" value="{{ old('twitter_url', $settings['twitter_url']) }}" 
                                    placeholder="https://twitter.com/youraccount">
                            @error('twitter_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="instagram_url" class="form-label">
                                <i class="ri-instagram-line text-danger me-1"></i> Instagram
                            </label>
                            <input type="url" class="form-control @error('instagram_url') is-invalid @enderror" 
                                    id="instagram_url" name="instagram_url" value="{{ old('instagram_url', $settings['instagram_url']) }}" 
                                    placeholder="https://instagram.com/youraccount">
                            @error('instagram_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="linkedin_url" class="form-label">
                                <i class="ri-linkedin-fill text-primary me-1"></i> LinkedIn
                            </label>
                            <input type="url" class="form-control @error('linkedin_url') is-invalid @enderror" 
                                    id="linkedin_url" name="linkedin_url" value="{{ old('linkedin_url', $settings['linkedin_url']) }}" 
                                    placeholder="https://linkedin.com/company/yourcompany">
                            @error('linkedin_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="youtube_url" class="form-label">
                                <i class="ri-youtube-fill text-danger me-1"></i> YouTube
                            </label>
                            <input type="url" class="form-control @error('youtube_url') is-invalid @enderror" 
                                    id="youtube_url" name="youtube_url" value="{{ old('youtube_url', $settings['youtube_url']) }}" 
                                    placeholder="https://youtube.com/yourchannel">
                            @error('youtube_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Site Features -->
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Site Features</div>
                    </div>
                    <div class="card-body">
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="user_registration" name="user_registration" value="1" 
                                    {{ old('user_registration', $settings['user_registration']) ? 'checked' : '' }}>
                            <label class="form-check-label" for="user_registration">
                                User Registration
                            </label>
                        </div>

                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="vendor_registration" name="vendor_registration" value="1" 
                                    {{ old('vendor_registration', $settings['vendor_registration']) ? 'checked' : '' }}>
                            <label class="form-check-label" for="vendor_registration">
                                Vendor Registration
                            </label>
                        </div>

                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="guest_checkout" name="guest_checkout" value="1" 
                                    {{ old('guest_checkout', $settings['guest_checkout']) ? 'checked' : '' }}>
                            <label class="form-check-label" for="guest_checkout">
                                Guest Checkout
                            </label>
                        </div>

                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="wishlist_enabled" name="wishlist_enabled" value="1" 
                                    {{ old('wishlist_enabled', $settings['wishlist_enabled']) ? 'checked' : '' }}>
                            <label class="form-check-label" for="wishlist_enabled">
                                Wishlist Feature
                            </label>
                        </div>

                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="reviews_enabled" name="reviews_enabled" value="1" 
                                    {{ old('reviews_enabled', $settings['reviews_enabled']) ? 'checked' : '' }}>
                            <label class="form-check-label" for="reviews_enabled">
                                Product Reviews
                            </label>
                        </div>

                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="newsletter_enabled" name="newsletter_enabled" value="1" 
                                    {{ old('newsletter_enabled', $settings['newsletter_enabled']) ? 'checked' : '' }}>
                            <label class="form-check-label" for="newsletter_enabled">
                                Newsletter Subscription
                            </label>
                        </div>

                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="maintenance_mode" name="maintenance_mode" value="1" 
                                    {{ old('maintenance_mode', $settings['maintenance_mode']) ? 'checked' : '' }}>
                            <label class="form-check-label" for="maintenance_mode">
                                <span class="text-warning">Maintenance Mode</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- MLM Settings -->
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">MLM Settings</div>
                    </div>
                    <div class="card-body">
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="mlm_enabled" name="mlm_enabled" value="1" 
                                    {{ old('mlm_enabled', $settings['mlm_enabled']) ? 'checked' : '' }}>
                            <label class="form-check-label" for="mlm_enabled">
                                MLM System Enabled
                            </label>
                        </div>

                        <div class="mb-3">
                            <label for="minimum_payout" class="form-label">Minimum Payout Amount <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control @error('minimum_payout') is-invalid @enderror" 
                                        id="minimum_payout" name="minimum_payout" value="{{ old('minimum_payout', $settings['minimum_payout']) }}" 
                                        placeholder="50.00" step="0.01" min="0">
                                @error('minimum_payout')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="commission_payout_day" class="form-label">Commission Payout Day</label>
                            <select class="form-select @error('commission_payout_day') is-invalid @enderror" id="commission_payout_day" name="commission_payout_day">
                                <option value="Monday" {{ old('commission_payout_day', $settings['commission_payout_day']) == 'Monday' ? 'selected' : '' }}>Monday</option>
                                <option value="Tuesday" {{ old('commission_payout_day', $settings['commission_payout_day']) == 'Tuesday' ? 'selected' : '' }}>Tuesday</option>
                                <option value="Wednesday" {{ old('commission_payout_day', $settings['commission_payout_day']) == 'Wednesday' ? 'selected' : '' }}>Wednesday</option>
                                <option value="Thursday" {{ old('commission_payout_day', $settings['commission_payout_day']) == 'Thursday' ? 'selected' : '' }}>Thursday</option>
                                <option value="Friday" {{ old('commission_payout_day', $settings['commission_payout_day']) == 'Friday' ? 'selected' : '' }}>Friday</option>
                                <option value="Saturday" {{ old('commission_payout_day', $settings['commission_payout_day']) == 'Saturday' ? 'selected' : '' }}>Saturday</option>
                                <option value="Sunday" {{ old('commission_payout_day', $settings['commission_payout_day']) == 'Sunday' ? 'selected' : '' }}>Sunday</option>
                            </select>
                            @error('commission_payout_day')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="genealogy_levels" class="form-label">Genealogy Levels</label>
                            <input type="number" class="form-control @error('genealogy_levels') is-invalid @enderror" 
                                    id="genealogy_levels" name="genealogy_levels" value="{{ old('genealogy_levels', $settings['genealogy_levels']) }}" 
                                    placeholder="10" min="1" max="50">
                            @error('genealogy_levels')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">How many levels to show in genealogy tree</div>
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
                            <button type="button" class="btn btn-info" onclick="previewChanges()">
                                <i class="ri-eye-line me-1"></i> Preview
                            </button>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="ri-save-line me-1"></i> Save Settings
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    // Character counters for SEO fields
    function setupCharacterCounter(fieldId, maxLength) {
        const field = document.getElementById(fieldId);
        const counter = document.createElement('div');
        counter.className = 'form-text text-end';
        field.parentNode.appendChild(counter);
        
        function updateCounter() {
            const remaining = maxLength - field.value.length;
            counter.textContent = `${field.value.length}/${maxLength} characters`;
            counter.className = remaining < 0 ? 'form-text text-end text-danger' : 'form-text text-end';
        }
        
        field.addEventListener('input', updateCounter);
        updateCounter();
    }

    // Setup character counters
    setupCharacterCounter('meta_title', 60);
    setupCharacterCounter('meta_description', 160);

    // Reset form function
    function resetForm() {
        if (confirm('Are you sure you want to reset all changes? This will reload the page with original values.')) {
            location.reload();
        }
    }

    // Preview changes function
    function previewChanges() {
        // In a real application, this would show a preview of how the site would look
        alert('Preview functionality would show how the site looks with current settings.');
    }

    // Auto-update currency symbol based on currency selection
    document.getElementById('default_currency').addEventListener('change', function() {
        const symbols = {
            'USD': '$',
            'EUR': '€',
            'GBP': '£',
            'CAD': 'C$',
            'AUD': 'A$',
            'JPY': '¥'
        };
        
        const symbol = symbols[this.value] || '$';
        document.getElementById('currency_symbol').value = symbol;
    });

    // Form validation
    document.getElementById('generalSettingsForm').addEventListener('submit', function(e) {
        const requiredFields = ['site_name', 'admin_email', 'contact_email', 'company_name', 'default_currency', 'default_timezone'];
        let isValid = true;
        
        requiredFields.forEach(fieldName => {
            const field = document.getElementById(fieldName);
            if (!field.value.trim()) {
                isValid = false;
                field.classList.add('is-invalid');
                
                // Remove invalid class after user types
                field.addEventListener('input', function() {
                    this.classList.remove('is-invalid');
                });
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            alert('Please fill in all required fields.');
            return false;
        }
    });

    // File upload preview
    document.querySelectorAll('input[type="file"]').forEach(input => {
        input.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // You could add image preview functionality here
                console.log('File selected:', file.name);
            }
        });
    });

    // Maintenance mode warning
    document.getElementById('maintenance_mode').addEventListener('change', function() {
        if (this.checked) {
            if (!confirm('Enabling maintenance mode will make your site inaccessible to visitors. Are you sure?')) {
                this.checked = false;
            }
        }
    });
</script>
@endpush
@endsection
