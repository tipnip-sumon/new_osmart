@extends('admin.layouts.app')

@section('title', 'Email Settings')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <h1 class="page-title fw-semibold fs-18 mb-0">Email Settings</h1>
        <div class="ms-md-1 ms-0">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.settings.index') }}">Settings</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Email</li>
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
                            <a class="nav-link" href="{{ route('admin.settings.payment') }}">
                                <i class="ri-bank-card-line me-1"></i> Payment
                            </a>
                            <a class="nav-link" href="{{ route('admin.settings.shipping') }}">
                                <i class="ri-truck-line me-1"></i> Shipping
                            </a>
                            <a class="nav-link" href="{{ route('admin.settings.tax') }}">
                                <i class="ri-calculator-line me-1"></i> Tax
                            </a>
                            <a class="nav-link active" href="{{ route('admin.settings.email') }}">
                                <i class="ri-mail-line me-1"></i> Email
                            </a>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.settings.email.update') }}" method="POST" id="emailSettingsForm">
        @csrf
        @method('PUT')
        
        <div class="row">
            <!-- Main Email Settings -->
            <div class="col-xl-8">
                <!-- SMTP Configuration -->
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">SMTP Configuration</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="mail_driver" class="form-label">Mail Driver</label>
                                <select class="form-select @error('mail_driver') is-invalid @enderror" 
                                        id="mail_driver" name="mail_driver">
                                    <option value="smtp" {{ old('mail_driver', $emailSettings['mail_driver']) == 'smtp' ? 'selected' : '' }}>SMTP</option>
                                    <option value="mailgun" {{ old('mail_driver', $emailSettings['mail_driver']) == 'mailgun' ? 'selected' : '' }}>Mailgun</option>
                                    <option value="ses" {{ old('mail_driver', $emailSettings['mail_driver']) == 'ses' ? 'selected' : '' }}>Amazon SES</option>
                                    <option value="sendmail" {{ old('mail_driver', $emailSettings['mail_driver']) == 'sendmail' ? 'selected' : '' }}>Sendmail</option>
                                    <option value="log" {{ old('mail_driver', $emailSettings['mail_driver']) == 'log' ? 'selected' : '' }}>Log (Development)</option>
                                </select>
                                @error('mail_driver')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="mail_encryption" class="form-label">Encryption</label>
                                <select class="form-select @error('mail_encryption') is-invalid @enderror" 
                                        id="mail_encryption" name="mail_encryption">
                                    <option value="tls" {{ old('mail_encryption', $emailSettings['mail_encryption']) == 'tls' ? 'selected' : '' }}>TLS</option>
                                    <option value="ssl" {{ old('mail_encryption', $emailSettings['mail_encryption']) == 'ssl' ? 'selected' : '' }}>SSL</option>
                                    <option value="none" {{ old('mail_encryption', $emailSettings['mail_encryption']) == 'none' ? 'selected' : '' }}>None</option>
                                </select>
                                @error('mail_encryption')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="mail_host" class="form-label">SMTP Host</label>
                                <input type="text" class="form-control @error('mail_host') is-invalid @enderror" 
                                        id="mail_host" name="mail_host" value="{{ old('mail_host', $emailSettings['mail_host']) }}" 
                                        placeholder="smtp.gmail.com">
                                @error('mail_host')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="mail_port" class="form-label">SMTP Port</label>
                                <input type="number" class="form-control @error('mail_port') is-invalid @enderror" 
                                        id="mail_port" name="mail_port" value="{{ old('mail_port', $emailSettings['mail_port']) }}" 
                                        placeholder="587">
                                @error('mail_port')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="mail_username" class="form-label">SMTP Username</label>
                                <input type="text" class="form-control @error('mail_username') is-invalid @enderror" 
                                        id="mail_username" name="mail_username" value="{{ old('mail_username', $emailSettings['mail_username']) }}" 
                                        placeholder="your-email@gmail.com">
                                @error('mail_username')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="mail_password" class="form-label">SMTP Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control @error('mail_password') is-invalid @enderror" 
                                            id="mail_password" name="mail_password" value="{{ old('mail_password', $emailSettings['mail_password']) }}" 
                                            placeholder="Your SMTP password">
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('mail_password')">
                                        <i class="ri-eye-line" id="mail_password_icon"></i>
                                    </button>
                                    @error('mail_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="mail_from_address" class="form-label">From Address</label>
                                <input type="email" class="form-control @error('mail_from_address') is-invalid @enderror" 
                                        id="mail_from_address" name="mail_from_address" value="{{ old('mail_from_address', $emailSettings['mail_from_address']) }}" 
                                        placeholder="noreply@yourstore.com">
                                @error('mail_from_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="mail_from_name" class="form-label">From Name</label>
                                <input type="text" class="form-control @error('mail_from_name') is-invalid @enderror" 
                                        id="mail_from_name" name="mail_from_name" value="{{ old('mail_from_name', $emailSettings['mail_from_name']) }}" 
                                        placeholder="Your Store Name">
                                @error('mail_from_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Email Templates -->
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Email Templates</div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Template</th>
                                        <th>Subject</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="ri-user-add-line me-2 text-primary"></i>
                                                <div>
                                                    <strong>Welcome Email</strong>
                                                    <small class="text-muted d-block">Sent to new users</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control form-control-sm" 
                                                    name="email_templates[welcome][subject]" 
                                                    value="{{ old('email_templates.welcome.subject', 'Welcome to ' . config('app.name')) }}" 
                                                    placeholder="Email subject">
                                        </td>
                                        <td>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" 
                                                        name="email_templates[welcome][enabled]" value="1" checked>
                                            </div>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-info" onclick="editTemplate('welcome')">
                                                <i class="ri-edit-line"></i> Edit
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="ri-shopping-cart-line me-2 text-success"></i>
                                                <div>
                                                    <strong>Order Confirmation</strong>
                                                    <small class="text-muted d-block">Order placed confirmation</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control form-control-sm" 
                                                    name="email_templates[order_confirmation][subject]" 
                                                    value="{{ old('email_templates.order_confirmation.subject', 'Order Confirmation - #{order_number}') }}" 
                                                    placeholder="Email subject">
                                        </td>
                                        <td>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" 
                                                        name="email_templates[order_confirmation][enabled]" value="1" checked>
                                            </div>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-info" onclick="editTemplate('order_confirmation')">
                                                <i class="ri-edit-line"></i> Edit
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="ri-truck-line me-2 text-warning"></i>
                                                <div>
                                                    <strong>Shipping Notification</strong>
                                                    <small class="text-muted d-block">Order shipped notification</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control form-control-sm" 
                                                    name="email_templates[shipping_notification][subject]" 
                                                    value="{{ old('email_templates.shipping_notification.subject', 'Your Order Has Shipped - #{order_number}') }}" 
                                                    placeholder="Email subject">
                                        </td>
                                        <td>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" 
                                                        name="email_templates[shipping_notification][enabled]" value="1" checked>
                                            </div>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-info" onclick="editTemplate('shipping_notification')">
                                                <i class="ri-edit-line"></i> Edit
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="ri-key-line me-2 text-danger"></i>
                                                <div>
                                                    <strong>Password Reset</strong>
                                                    <small class="text-muted d-block">Password reset request</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control form-control-sm" 
                                                    name="email_templates[password_reset][subject]" 
                                                    value="{{ old('email_templates.password_reset.subject', 'Reset Your Password') }}" 
                                                    placeholder="Email subject">
                                        </td>
                                        <td>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" 
                                                        name="email_templates[password_reset][enabled]" value="1" checked>
                                            </div>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-info" onclick="editTemplate('password_reset')">
                                                <i class="ri-edit-line"></i> Edit
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="ri-money-dollar-circle-line me-2 text-primary"></i>
                                                <div>
                                                    <strong>Commission Payout</strong>
                                                    <small class="text-muted d-block">MLM commission notification</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control form-control-sm" 
                                                    name="email_templates[commission_payout][subject]" 
                                                    value="{{ old('email_templates.commission_payout.subject', 'Commission Payout - ${amount}') }}" 
                                                    placeholder="Email subject">
                                        </td>
                                        <td>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" 
                                                        name="email_templates[commission_payout][enabled]" value="1" checked>
                                            </div>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-info" onclick="editTemplate('commission_payout')">
                                                <i class="ri-edit-line"></i> Edit
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Email Queue Settings -->
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Email Queue Settings</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" id="queue_emails" name="queue_emails" value="1" 
                                            {{ old('queue_emails', $emailSettings['queue_emails']) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="queue_emails">
                                        Queue Emails
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="queue_connection" class="form-label">Queue Connection</label>
                                <select class="form-select @error('queue_connection') is-invalid @enderror" 
                                        id="queue_connection" name="queue_connection">
                                    <option value="sync" {{ old('queue_connection', $emailSettings['queue_connection']) == 'sync' ? 'selected' : '' }}>Sync</option>
                                    <option value="database" {{ old('queue_connection', $emailSettings['queue_connection']) == 'database' ? 'selected' : '' }}>Database</option>
                                    <option value="redis" {{ old('queue_connection', $emailSettings['queue_connection']) == 'redis' ? 'selected' : '' }}>Redis</option>
                                    <option value="sqs" {{ old('queue_connection', $emailSettings['queue_connection']) == 'sqs' ? 'selected' : '' }}>Amazon SQS</option>
                                </select>
                                @error('queue_connection')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="emails_per_batch" class="form-label">Emails Per Batch</label>
                                <input type="number" class="form-control @error('emails_per_batch') is-invalid @enderror" 
                                        id="emails_per_batch" name="emails_per_batch" value="{{ old('emails_per_batch', $emailSettings['emails_per_batch']) }}" 
                                        placeholder="50" min="1" max="1000">
                                @error('emails_per_batch')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email_retry_attempts" class="form-label">Retry Attempts</label>
                                <input type="number" class="form-control @error('email_retry_attempts') is-invalid @enderror" 
                                        id="email_retry_attempts" name="email_retry_attempts" value="{{ old('email_retry_attempts', $emailSettings['email_retry_attempts']) }}" 
                                        placeholder="3" min="1" max="10">
                                @error('email_retry_attempts')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar Settings -->
            <div class="col-xl-4">
                <!-- Email Providers -->
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Email Providers</div>
                    </div>
                    <div class="card-body">
                        <!-- Gmail -->
                        <div class="provider-section mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="d-flex align-items-center">
                                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/7/7e/Gmail_icon_%282020%29.svg/320px-Gmail_icon_%282020%29.svg.png" alt="Gmail" style="height: 25px;" class="me-3">
                                    <h6 class="mb-0">Gmail SMTP</h6>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="configureGmail()">
                                    Configure
                                </button>
                            </div>
                            <small class="text-muted">smtp.gmail.com:587 (TLS)</small>
                        </div>

                        <!-- SendGrid -->
                        <div class="provider-section mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="d-flex align-items-center">
                                    <img src="https://sendgrid.com/wp-content/themes/sgdotcom/pages/resource/brand/2016/SendGrid-Logomark.png" alt="SendGrid" style="height: 25px;" class="me-3">
                                    <h6 class="mb-0">SendGrid</h6>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="configureSendGrid()">
                                    Configure
                                </button>
                            </div>
                            <small class="text-muted">smtp.sendgrid.net:587 (TLS)</small>
                        </div>

                        <!-- Mailgun -->
                        <div class="provider-section">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="d-flex align-items-center">
                                    <img src="https://avatars.githubusercontent.com/u/431013?s=200&v=4" alt="Mailgun" style="height: 25px;" class="me-3">
                                    <h6 class="mb-0">Mailgun</h6>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="configureMailgun()">
                                    Configure
                                </button>
                            </div>
                            <small class="text-muted">smtp.mailgun.org:587 (TLS)</small>
                        </div>
                    </div>
                </div>

                <!-- Email Testing -->
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Email Testing</div>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="test_email" class="form-label">Test Email Address</label>
                            <input type="email" class="form-control" id="test_email" name="test_email" 
                                    placeholder="test@example.com">
                        </div>
                        
                        <div class="mb-3">
                            <label for="test_template" class="form-label">Test Template</label>
                            <select class="form-select" id="test_template">
                                <option value="welcome">Welcome Email</option>
                                <option value="order_confirmation">Order Confirmation</option>
                                <option value="password_reset">Password Reset</option>
                            </select>
                        </div>

                        <button type="button" class="btn btn-primary w-100" onclick="sendTestEmail()">
                            <i class="ri-mail-send-line me-1"></i> Send Test Email
                        </button>
                    </div>
                </div>

                <!-- MLM Email Settings -->
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">MLM Email Settings</div>
                    </div>
                    <div class="card-body">
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="commission_emails" name="commission_emails" value="1" 
                                    {{ old('commission_emails', $emailSettings['commission_emails']) ? 'checked' : '' }}>
                            <label class="form-check-label" for="commission_emails">
                                Commission Emails
                            </label>
                        </div>

                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="downline_emails" name="downline_emails" value="1" 
                                    {{ old('downline_emails', $emailSettings['downline_emails']) ? 'checked' : '' }}>
                            <label class="form-check-label" for="downline_emails">
                                Downline Activity Emails
                            </label>
                        </div>

                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="rank_advancement_emails" name="rank_advancement_emails" value="1" 
                                    {{ old('rank_advancement_emails', $emailSettings['rank_advancement_emails']) ? 'checked' : '' }}>
                            <label class="form-check-label" for="rank_advancement_emails">
                                Rank Advancement Emails
                            </label>
                        </div>

                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="vendor_notifications" name="vendor_notifications" value="1" 
                                    {{ old('vendor_notifications', $emailSettings['vendor_notifications']) ? 'checked' : '' }}>
                            <label class="form-check-label" for="vendor_notifications">
                                Vendor Notifications
                            </label>
                        </div>

                        <div class="mb-3">
                            <label for="commission_email_frequency" class="form-label">Commission Email Frequency</label>
                            <select class="form-select @error('commission_email_frequency') is-invalid @enderror" 
                                    id="commission_email_frequency" name="commission_email_frequency">
                                <option value="instant" {{ old('commission_email_frequency', $emailSettings['commission_email_frequency']) == 'instant' ? 'selected' : '' }}>Instant</option>
                                <option value="daily" {{ old('commission_email_frequency', $emailSettings['commission_email_frequency']) == 'daily' ? 'selected' : '' }}>Daily</option>
                                <option value="weekly" {{ old('commission_email_frequency', $emailSettings['commission_email_frequency']) == 'weekly' ? 'selected' : '' }}>Weekly</option>
                                <option value="monthly" {{ old('commission_email_frequency', $emailSettings['commission_email_frequency']) == 'monthly' ? 'selected' : '' }}>Monthly</option>
                            </select>
                            @error('commission_email_frequency')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Email Statistics -->
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Email Statistics</div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Emails Sent Today:</span>
                            <span class="fw-semibold">245</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Delivery Rate:</span>
                            <span class="text-success fw-semibold">98.5%</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Bounce Rate:</span>
                            <span class="text-warning fw-semibold">1.2%</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Open Rate:</span>
                            <span class="text-info fw-semibold">24.8%</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Queue Status:</span>
                            <span class="badge bg-success">Active</span>
                        </div>
                    </div>
                </div>

                <!-- Email Logs -->
                <div class="card custom-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div class="card-title">Recent Email Logs</div>
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="viewAllLogs()">
                            View All
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2">
                            <div class="avatar avatar-sm bg-success-transparent me-2">
                                <i class="ri-check-line text-success"></i>
                            </div>
                            <div class="flex-fill">
                                <small class="d-block">Welcome email sent</small>
                                <small class="text-muted">user@example.com</small>
                            </div>
                            <small class="text-muted">2m ago</small>
                        </div>
                        
                        <div class="d-flex align-items-center mb-2">
                            <div class="avatar avatar-sm bg-info-transparent me-2">
                                <i class="ri-shopping-cart-line text-info"></i>
                            </div>
                            <div class="flex-fill">
                                <small class="d-block">Order confirmation</small>
                                <small class="text-muted">customer@email.com</small>
                            </div>
                            <small class="text-muted">5m ago</small>
                        </div>
                        
                        <div class="d-flex align-items-center">
                            <div class="avatar avatar-sm bg-warning-transparent me-2">
                                <i class="ri-error-warning-line text-warning"></i>
                            </div>
                            <div class="flex-fill">
                                <small class="d-block">Delivery failed</small>
                                <small class="text-muted">invalid@domain.com</small>
                            </div>
                            <small class="text-muted">10m ago</small>
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
                            <button type="button" class="btn btn-info" onclick="testConnection()">
                                <i class="ri-wifi-line me-1"></i> Test Connection
                            </button>
                            <button type="button" class="btn btn-warning" onclick="clearQueue()">
                                <i class="ri-delete-bin-line me-1"></i> Clear Queue
                            </button>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="ri-save-line me-1"></i> Save Email Settings
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    // Toggle password visibility
    function togglePassword(inputId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(inputId + '_icon');
        
        if (input.type === 'password') {
            input.type = 'text';
            icon.className = 'ri-eye-off-line';
        } else {
            input.type = 'password';
            icon.className = 'ri-eye-line';
        }
    }

    // Configure email providers
    function configureGmail() {
        document.getElementById('mail_host').value = 'smtp.gmail.com';
        document.getElementById('mail_port').value = '587';
        document.getElementById('mail_encryption').value = 'tls';
        alert('Gmail SMTP configuration applied. Please enter your Gmail credentials.');
    }

    function configureSendGrid() {
        document.getElementById('mail_host').value = 'smtp.sendgrid.net';
        document.getElementById('mail_port').value = '587';
        document.getElementById('mail_encryption').value = 'tls';
        document.getElementById('mail_username').value = 'apikey';
        alert('SendGrid configuration applied. Please enter your SendGrid API key as password.');
    }

    function configureMailgun() {
        document.getElementById('mail_driver').value = 'mailgun';
        alert('Mailgun configuration selected. Please configure your Mailgun credentials in the .env file.');
    }

    // Email testing and management
    function sendTestEmail() {
        const testEmail = document.getElementById('test_email').value;
        const testTemplate = document.getElementById('test_template').value;
        
        if (!testEmail) {
            alert('Please enter a test email address.');
            return;
        }
        
        // In a real implementation, this would make an AJAX call
        alert(`Test email (${testTemplate}) would be sent to: ${testEmail}`);
    }

    function testConnection() {
        alert('Testing SMTP connection... This would verify the email server connection.');
    }

    function clearQueue() {
        if (confirm('Are you sure you want to clear the email queue? This will remove all pending emails.')) {
            alert('Email queue would be cleared.');
        }
    }

    function editTemplate(templateName) {
        alert(`Email template editor for "${templateName}" would open here.`);
    }

    function viewAllLogs() {
        alert('Full email logs viewer would open here.');
    }

    // Reset form function
    function resetForm() {
        if (confirm('Are you sure you want to reset all changes? This will reload the page with original values.')) {
            location.reload();
        }
    }
</script>
@endpush
@endsection
