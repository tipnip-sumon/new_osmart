@extends('admin.layouts.app')
    @section('top_title', $pageTitle)
    @section('title',$pageTitle)

@section('content')
<!-- Settings Navigation -->
<x-admin.settings-navigation current="sms-config" />

<div class="row mb-4 my-4">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-sms me-2"></i>
                    {{ $pageTitle }}
                </h5>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="row">
                    <div class="col-lg-8">
                        <form action="{{ route('admin.general-settings.sms-config.update') }}" method="POST">
                            @csrf

                            <!-- SMS Configuration -->
                            <div class="mb-4">
                                <h5 class="text-primary border-bottom pb-2">
                                    <i class="fas fa-cogs me-2"></i>SMS Gateway Configuration
                                </h5>
                            </div>

                            <div class="row">
                                <!-- SMS Gateway -->
                                <div class="col-md-6 mb-3">
                                    <label for="gateway" class="form-label">
                                        <i class="fas fa-server me-1"></i>SMS Gateway
                                    </label>
                                    <select class="form-select @error('gateway') is-invalid @enderror" 
                                            id="gateway" name="gateway" required>
                                        <option value="mram" {{ (old('gateway', $smsConfig['gateway'] ?? 'mram') == 'mram') ? 'selected' : '' }}>MRAM SMS Gateway (Bangladesh)</option>
                                        <option value="twilio" {{ (old('gateway', $smsConfig['gateway'] ?? 'mram') == 'twilio') ? 'selected' : '' }}>Twilio</option>
                                        <option value="nexmo" {{ (old('gateway', $smsConfig['gateway'] ?? 'mram') == 'nexmo') ? 'selected' : '' }}>Nexmo (Vonage)</option>
                                        <option value="textlocal" {{ (old('gateway', $smsConfig['gateway'] ?? 'mram') == 'textlocal') ? 'selected' : '' }}>TextLocal</option>
                                        <option value="msg91" {{ (old('gateway', $smsConfig['gateway'] ?? 'mram') == 'msg91') ? 'selected' : '' }}>MSG91</option>
                                        <option value="clickatell" {{ (old('gateway', $smsConfig['gateway'] ?? 'mram') == 'clickatell') ? 'selected' : '' }}>Clickatell</option>
                                        <option value="custom" {{ (old('gateway', $smsConfig['gateway'] ?? 'mram') == 'custom') ? 'selected' : '' }}>Custom API</option>
                                    </select>
                                    @error('gateway')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Enable/Disable SMS -->
                                <div class="col-md-6 mb-3">
                                    <label for="enabled" class="form-label">
                                        <i class="fas fa-toggle-on me-1"></i>SMS Enabled
                                    </label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="enabled" name="enabled" value="1" 
                                               {{ old('enabled', $smsConfig['enabled'] ?? false) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="enabled">
                                            Enable SMS notifications
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <!-- API Key -->
                                <div class="col-md-6 mb-3">
                                    <label for="api_key" class="form-label">
                                        <i class="fas fa-key me-1"></i>API Key <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('api_key') is-invalid @enderror" 
                                           id="api_key" 
                                           name="api_key" 
                                           value="{{ old('api_key', $smsConfig['api_key'] ?? 'C300238768cd82a4899006.97231254') }}" 
                                           placeholder="C300238768cd82a4899006.97231254" 
                                           required>
                                    @error('api_key')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        MRAM SMS API Key (Your API: C300238768cd82a4899006.97231254)
                                    </small>
                                </div>

                                <!-- API Secret -->
                                <div class="col-md-6 mb-3">
                                    <label for="api_secret" class="form-label">
                                        <i class="fas fa-lock me-1"></i>API Secret / Token
                                    </label>
                                    <div class="input-group">
                                        <input type="password" 
                                               class="form-control @error('api_secret') is-invalid @enderror" 
                                               id="api_secret" 
                                               name="api_secret" 
                                               value="{{ old('api_secret', $smsConfig['api_secret'] ?? '') }}" 
                                               placeholder="Enter your API Secret or Auth Token">
                                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    @error('api_secret')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <!-- Sender ID -->
                                <div class="col-md-6 mb-3">
                                    <label for="sender_id" class="form-label">
                                        <i class="fas fa-id-card me-1"></i>Sender ID <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('sender_id') is-invalid @enderror" 
                                           id="sender_id" 
                                           name="sender_id" 
                                           value="{{ old('sender_id', $smsConfig['sender_id'] ?? 'O-Smart') }}" 
                                           placeholder="O-Smart" 
                                           maxlength="11" 
                                           required>
                                    @error('sender_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Sender ID (max 11 characters) for MRAM SMS Gateway<br>
                                        <span class="text-info">💡 Options: Phone number (e.g., "01602273694") or Brand name (requires MRAM approval)</span>
                                    </small>
                                </div>

                                <!-- From Number -->
                                <div class="col-md-6 mb-3">
                                    <label for="from_number" class="form-label">
                                        <i class="fas fa-phone me-1"></i>From Number
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('from_number') is-invalid @enderror" 
                                           id="from_number" 
                                           name="from_number" 
                                           value="{{ old('from_number', $smsConfig['from_number'] ?? '') }}" 
                                           placeholder="+1234567890">
                                    @error('from_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Phone number in E.164 format (for services like Twilio)
                                    </small>
                                </div>
                            </div>

                            <!-- Test SMS Section -->
                            <div class="mb-4">
                                <h5 class="text-primary border-bottom pb-2">
                                    <i class="fas fa-vial me-2"></i>Test SMS Configuration
                                </h5>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="test_phone" class="form-label">
                                        <i class="fas fa-mobile-alt me-1"></i>Test Phone Number
                                    </label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="test_phone" 
                                           name="test_phone" 
                                           placeholder="+8801XXXXXXXXX or 01XXXXXXXXX">
                                    <small class="form-text text-muted">
                                        Enter a Bangladesh phone number to test SMS configuration
                                    </small>
                                </div>
                                                                <div class="col-12">
                                    <div class="d-flex gap-2">
                                        <button type="button" id="testSmsBtn" class="btn btn-info">
                                            <i class="fas fa-paper-plane me-1"></i>Test SMS
                                        </button>
                                        <button type="button" id="checkBalanceBtn" class="btn btn-secondary">
                                            <i class="fas fa-wallet me-1"></i>Check Balance
                                        </button>
                                        <button type="button" id="diagnoseSmsBtn" class="btn btn-warning">
                                            <i class="fas fa-stethoscope me-1"></i>Diagnose Issues
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Bulk SMS Section -->
                            <div class="mb-4">
                                <h5 class="text-success border-bottom pb-2">
                                    <i class="fas fa-users me-2"></i>Bulk SMS - One To Many
                                </h5>
                                <small class="text-muted">Send SMS to multiple contacts using MRAM One To Many format</small>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="bulk_contacts" class="form-label">
                                        <i class="fas fa-address-book me-1"></i>Phone Numbers <span class="text-danger">*</span>
                                    </label>
                                    <textarea class="form-control" 
                                              id="bulk_contacts" 
                                              name="bulk_contacts" 
                                              rows="4"
                                              placeholder="Enter phone numbers separated by comma or plus sign:&#10;88017xxxxxxxx,88018xxxxxxxx&#10;or&#10;88017xxxxxxxx+88018xxxxxxxx"></textarea>
                                    <small class="form-text text-muted">
                                        Format: 88017xxxxxxxx,88018xxxxxxxx or 88017xxxxxxxx+88018xxxxxxxx
                                    </small>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="bulk_message" class="form-label">
                                        <i class="fas fa-comment me-1"></i>Message <span class="text-danger">*</span>
                                    </label>
                                    <textarea class="form-control" 
                                              id="bulk_message" 
                                              name="bulk_message" 
                                              rows="4"
                                              maxlength="1000"
                                              placeholder="Enter your bulk SMS message here..."></textarea>
                                    <small class="form-text text-muted">
                                        <span id="bulk_char_count">0</span>/1000 characters
                                    </small>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="bulk_type" class="form-label">
                                        <i class="fas fa-text-height me-1"></i>Message Type
                                    </label>
                                    <select class="form-select" id="bulk_type" name="bulk_type">
                                        <option value="text">Text (English/Numbers)</option>
                                        <option value="unicode">Unicode (Bengali/Special Characters)</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="scheduled_datetime" class="form-label">
                                        <i class="fas fa-clock me-1"></i>Scheduled DateTime <small class="text-muted">(Optional)</small>
                                    </label>
                                    <input type="datetime-local" 
                                           class="form-control" 
                                           id="scheduled_datetime" 
                                           name="scheduled_datetime">
                                    <small class="form-text text-muted">
                                        Leave empty to send immediately, or schedule for future delivery
                                    </small>
                                </div>
                                <div class="col-12">
                                    <button type="button" id="sendBulkSmsBtn" class="btn btn-success">
                                        <i class="fas fa-paper-plane me-1"></i>Send Bulk SMS
                                    </button>
                                    <button type="button" id="validateContactsBtn" class="btn btn-outline-info">
                                        <i class="fas fa-check-circle me-1"></i>Validate Contacts
                                    </button>
                                </div>
                            </div>

                            <hr class="my-4">

                            <!-- Many To Many SMS Section -->
                            <div class="mb-4">
                                <h5 class="text-warning border-bottom pb-2">
                                    <i class="fas fa-exchange-alt me-2"></i>Many To Many SMS
                                </h5>
                                <small class="text-muted">Send different messages to different contacts (personalized messaging)</small>
                            </div>

                            <div class="row" id="manyToManySection">
                                <div class="col-12 mb-3">
                                    <div class="card">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0">Message Recipients</h6>
                                            <button type="button" id="addRecipientBtn" class="btn btn-sm btn-success">
                                                <i class="fas fa-plus me-1"></i>Add Recipient
                                            </button>
                                        </div>
                                        <div class="card-body" id="recipientsContainer">
                                            <!-- Recipients will be added dynamically -->
                                            <div class="recipient-item mb-3 border rounded p-3" data-index="0">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label class="form-label">Phone Number</label>
                                                        <input type="text" class="form-control recipient-phone" 
                                                               placeholder="+8801XXXXXXXXX or 01XXXXXXXXX" 
                                                               data-index="0">
                                                    </div>
                                                    <div class="col-md-7">
                                                        <label class="form-label">Message</label>
                                                        <textarea class="form-control recipient-message" 
                                                                  rows="2" maxlength="1000" 
                                                                  placeholder="Enter personalized message..." 
                                                                  data-index="0"></textarea>
                                                        <small class="form-text text-muted">
                                                            <span class="char-count">0</span>/1000 characters
                                                        </small>
                                                    </div>
                                                    <div class="col-md-1 d-flex align-items-center">
                                                        <button type="button" class="btn btn-sm btn-danger remove-recipient" 
                                                                data-index="0" style="display: none;">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer">
                                            <button type="button" id="sendManyToManyBtn" class="btn btn-warning">
                                                <i class="fas fa-paper-plane me-1"></i>Send Many To Many SMS
                                            </button>
                                            <button type="button" id="validateManyToManyBtn" class="btn btn-outline-warning">
                                                <i class="fas fa-check-circle me-1"></i>Validate All
                                            </button>
                                            <button type="button" id="clearAllBtn" class="btn btn-outline-secondary">
                                                <i class="fas fa-eraser me-1"></i>Clear All
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>
                                    Update SMS Configuration
                                </button>
                                <a href="{{ route('admin.general-settings.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>
                                    Back to General Settings
                                </a>
                            </div>
                        </form>
                    </div>

                    <!-- SMS Configuration Status -->
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title mb-0">
                                    <i class="fas fa-info-circle me-2"></i>SMS Configuration Status
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <strong>Current Gateway:</strong>
                                    <span class="badge bg-primary">{{ ucfirst($smsConfig['gateway'] ?? 'Not Set') }}</span>
                                </div>
                                <div class="mb-3">
                                    <strong>SMS Status:</strong>
                                    @if($smsConfig['enabled'] ?? false)
                                        <span class="badge bg-success">Enabled</span>
                                    @else
                                        <span class="badge bg-warning">Disabled</span>
                                    @endif
                                </div>
                                <div class="mb-3">
                                    <strong>API Key:</strong>
                                    @if(!empty($smsConfig['api_key']))
                                        <span class="badge bg-success">Configured</span>
                                    @else
                                        <span class="badge bg-danger">Not Set</span>
                                    @endif
                                </div>
                                <div class="mb-3">
                                    <strong>API Secret:</strong>
                                    @if(!empty($smsConfig['api_secret']))
                                        <span class="badge bg-success">Configured</span>
                                    @else
                                        <span class="badge bg-danger">Not Set</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- SMS Gateway Documentation -->
                        <div class="card mt-4">
                            <div class="card-header">
                                <h6 class="card-title mb-0">
                                    <i class="fas fa-book me-2"></i>MRAM SMS Gateway Documentation
                                </h6>
                            </div>
                            <div class="card-body">
                                <div id="gatewayInfo">
                                    <div class="gateway-info" data-gateway="mram">
                                        <h6 class="text-primary">MRAM SMS API Setup:</h6>
                                        <ul class="small mb-3">
                                            <li><strong>API Key:</strong> C300238768cd82a4899006.97231254</li>
                                            <li><strong>API URL:</strong> https://sms.mram.com.bd/smsapi</li>
                                            <li><strong>Message Types:</strong> text (English), unicode (Bengali)</li>
                                            <li><strong>Label:</strong> transactional/promotional</li>
                                        </ul>
                                        
                                        <h6 class="text-success">One To Many Format:</h6>
                                        <div class="bg-light p-2 rounded mb-3">
                                            <code class="small">88017XXXXXXXX+88018XXXXXXXX+88019XXXXXXXX</code>
                                        </div>
                                        
                                        <h6 class="text-info">Available APIs:</h6>
                                        <ul class="small mb-3">
                                            <li><strong>Balance Check:</strong> /miscapi/{API_KEY}/getBalance</li>
                                            <li><strong>Price Check:</strong> /miscapi/{API_KEY}/getPrice</li>
                                            <li><strong>Delivery Report:</strong> /miscapi/{API_KEY}/getDLR/getAll</li>
                                            <li><strong>Inbox Reply:</strong> /miscapi/{API_KEY}/getUnreadReplies</li>
                                        </ul>

                                        <h6 class="text-warning">Common Error Codes:</h6>
                                        <div class="table-responsive">
                                            <table class="table table-sm table-bordered">
                                                <tr><td><code>1002</code></td><td>Sender Id/Masking Not Found</td></tr>
                                                <tr><td><code>1007</code></td><td>Balance Insufficient</td></tr>
                                                <tr><td><code>1008</code></td><td>Message is empty</td></tr>
                                                <tr><td><code>1012</code></td><td>Invalid Number</td></tr>
                                                <tr><td><code>1016</code></td><td>IP address not allowed</td></tr>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="gateway-info" data-gateway="twilio" style="display: none;">
                                        <h6>Twilio Setup:</h6>
                                        <ul class="small">
                                            <li>API Key: Account SID</li>
                                            <li>API Secret: Auth Token</li>
                                            <li>From Number: Your Twilio phone number</li>
                                        </ul>
                                        <a href="https://www.twilio.com/docs" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-external-link-alt"></i> Documentation
                                        </a>
                                    </div>
                                    <div class="gateway-info" data-gateway="nexmo" style="display: none;">
                                        <h6>Nexmo (Vonage) Setup:</h6>
                                        <ul class="small">
                                            <li>API Key: Your Nexmo API key</li>
                                            <li>API Secret: Your Nexmo API secret</li>
                                            <li>Sender ID: Your brand name (if approved)</li>
                                        </ul>
                                        <a href="https://developer.nexmo.com/" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-external-link-alt"></i> Documentation
                                        </a>
                                    </div>
                                    <div class="gateway-info" data-gateway="msg91" style="display: none;">
                                        <h6>MSG91 Setup:</h6>
                                        <ul class="small">
                                            <li>API Key: Your MSG91 auth key</li>
                                            <li>Sender ID: Your approved sender ID</li>
                                            <li>Route: Promotional/Transactional</li>
                                        </ul>
                                        <a href="https://docs.msg91.com/" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-external-link-alt"></i> Documentation
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle password visibility
    const togglePassword = document.getElementById('togglePassword');
    const passwordField = document.getElementById('api_secret');
    
    if (togglePassword && passwordField) {
        togglePassword.addEventListener('click', function() {
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);
            
            const icon = this.querySelector('i');
            icon.classList.toggle('fa-eye');
            icon.classList.toggle('fa-eye-slash');
        });
    }
    
    // Gateway selection change
    const gatewaySelect = document.getElementById('gateway');
    const gatewayInfos = document.querySelectorAll('.gateway-info');
    
    if (gatewaySelect) {
        gatewaySelect.addEventListener('change', function() {
            const selectedGateway = this.value;
            
            // Hide all gateway info sections
            gatewayInfos.forEach(function(info) {
                info.style.display = 'none';
            });
            
            // Show selected gateway info
            const selectedInfo = document.getElementById(selectedGateway + '-info');
            if (selectedInfo) {
                selectedInfo.style.display = 'block';
            }
            
            // Update placeholders based on gateway
            const placeholders = {
                'mram': {
                    'api_key': 'C300238768cd82a4899006.97231254',
                    'sender_id': 'O-Smart',
                    'test_phone': '+8801XXXXXXXXX or 01XXXXXXXXX'
                },
                'twilio': {
                    'api_key': 'Your Twilio Account SID',
                    'sender_id': 'Twilio Phone Number',
                    'test_phone': '+1234567890'
                },
                'nexmo': {
                    'api_key': 'Your Vonage API Key',
                    'sender_id': 'Vonage Brand Name',
                    'test_phone': '+1234567890'
                }
            };
            
            if (placeholders[selectedGateway]) {
                const apiKey = document.getElementById('api_key');
                const senderId = document.getElementById('sender_id');
                const testPhone = document.getElementById('test_phone');
                
                if (apiKey) apiKey.placeholder = placeholders[selectedGateway].api_key;
                if (senderId) senderId.placeholder = placeholders[selectedGateway].sender_id;
                if (testPhone) testPhone.placeholder = placeholders[selectedGateway].test_phone;
            }
        });
        
        // Trigger change event on page load
        gatewaySelect.dispatchEvent(new Event('change'));
    }
    
    // Test SMS functionality
    const testSmsBtn = document.getElementById('testSmsBtn');
    if (testSmsBtn) {
        testSmsBtn.addEventListener('click', function() {
            const testPhone = document.getElementById('test_phone').value;
            const gateway = document.getElementById('gateway').value;
            const apiKey = document.getElementById('api_key').value;
            const senderId = document.getElementById('sender_id').value;
            
            if (!testPhone) {
                alert('Please enter a phone number to test.');
                return;
            }
            
            this.disabled = true;
            this.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Sending...';
            
            fetch('{{ route("admin.general-settings.test-sms") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    phone: testPhone,
                    gateway: gateway,
                    api_key: apiKey,
                    sender_id: senderId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    let message = 'Test SMS sent successfully!';
                    if (data.message_id) {
                        message += `\\nMessage ID: ${data.message_id}`;
                    }
                    if (data.balance) {
                        message += `\\nRemaining Balance: ${data.balance}`;
                    }
                    alert(message);
                } else {
                    alert('Failed to send test SMS: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                alert('Error sending test SMS: ' + error.message);
            })
            .finally(() => {
                this.disabled = false;
                this.innerHTML = '<i class="fas fa-paper-plane me-1"></i>Test SMS';
            });
        });
    }
    
    // Check Balance functionality
    const checkBalanceBtn = document.getElementById('checkBalanceBtn');
    if (checkBalanceBtn) {
        checkBalanceBtn.addEventListener('click', function() {
            const gateway = document.getElementById('gateway').value;
            const apiKey = document.getElementById('api_key').value;
            
            this.disabled = true;
            this.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Checking...';
            
            fetch('{{ route("admin.general-settings.check-sms-balance") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    gateway: gateway,
                    api_key: apiKey
                })
            })
            .then(response => response.json())
            .then(data => {
                console.log('Balance check response:', data); // Debug log
                
                if (data.success) {
                    let message = `Balance Information:\\nGateway: ${data.gateway || 'MRAM SMS'}\\nBalance: ${data.balance || '0'}`;
                    if (data.sender_id) {
                        message += `\\nSender ID: ${data.sender_id}`;
                    }
                    if (data.enabled !== undefined) {
                        message += `\\nStatus: ${data.enabled ? 'Enabled' : 'Disabled'}`;
                    }
                    if (data.api_key) {
                        message += `\\nAPI Key: ${data.api_key}`;
                    }
                    alert(message);
                } else {
                    let errorMessage = 'SMS Service Status:\\n';
                    errorMessage += `Gateway: ${data.gateway || 'MRAM SMS'}\\n`;
                    errorMessage += `Status: ${data.enabled ? 'Enabled ✓' : 'Disabled ✗'}\\n`;
                    if (data.sender_id) {
                        errorMessage += `Sender ID: ${data.sender_id}\\n`;
                    }
                    if (data.api_key) {
                        errorMessage += `API Key: ${data.api_key}\\n`;
                    }
                    errorMessage += `\\nBalance Check: ${data.error || 'Unknown error'}`;
                    if (data.note) {
                        errorMessage += `\\n\\n${data.note}`;
                    }
                    alert(errorMessage);
                }
            })
            .catch(error => {
                alert('Error checking balance: ' + error.message);
            })
            .finally(() => {
                this.disabled = false;
                this.innerHTML = '<i class="fas fa-wallet me-1"></i>Check Balance';
            });
        });
    }
    
    // Diagnose SMS Issues functionality
    const diagnoseSmsBtn = document.getElementById('diagnoseSmsBtn');
    if (diagnoseSmsBtn) {
        diagnoseSmsBtn.addEventListener('click', function() {
            this.disabled = true;
            this.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Diagnosing...';
            
            fetch('{{ route("admin.general-settings.diagnose-sms") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                console.log('SMS Diagnostic:', data); // Debug log
                
                let message = 'SMS Service Diagnostic Results:\\n\\n';
                
                if (data.error) {
                    message += `❌ Error: ${data.error}\\n`;
                } else {
                    message += `📊 Service Status: ${data.service_enabled ? '✅ Enabled' : '❌ Disabled'}\\n`;
                    message += `🔑 API Key: ${data.api_key_configured ? '✅ Configured' : '❌ Missing'} (${data.api_key_preview || 'Not set'})\\n`;
                    message += `📧 Sender ID: ${data.sender_id || 'Not set'} ${data.sender_id_valid ? '✅' : '⚠️ Invalid'}\\n`;
                    message += `🌐 Base URL: ${data.base_url || 'Not set'}\\n`;
                    message += `🖥️ Server IP: ${data.server_ip || 'Unknown'}\\n`;
                    message += `🌍 Public IP: ${data.public_ip || 'Unknown'}\\n\\n`;
                    
                    // Show recommendations first if any
                    if (data.recommendations && data.recommendations.length > 0) {
                        message += '🎯 Immediate Action Required:\\n';
                        data.recommendations.forEach((recommendation) => {
                            message += `${recommendation}\\n`;
                        });
                        message += '\\n';
                    }
                    
                    if (data.common_issues) {
                        message += '🔍 Common Issue Solutions:\\n';
                        Object.entries(data.common_issues).forEach(([code, solution]) => {
                            message += `• ${code.toUpperCase()}: ${solution}\\n`;
                        });
                    }
                    
                    // Special attention to IP whitelisting
                    if (data.public_ip && data.public_ip !== 'Unknown') {
                        message += `\\n🔒 IMPORTANT: If you get Error 1016, contact MRAM to whitelist IP: ${data.public_ip}`;
                    }
                }
                
                alert(message);
            })
            .catch(error => {
                alert('Error running diagnostic: ' + error.message);
            })
            .finally(() => {
                this.disabled = false;
                this.innerHTML = '<i class="fas fa-stethoscope me-1"></i>Diagnose Issues';
            });
        });
    }
    
    // Bulk SMS functionality
    const bulkMessageTextarea = document.getElementById('bulk_message');
    const bulkCharCount = document.getElementById('bulk_char_count');
    
    if (bulkMessageTextarea && bulkCharCount) {
        bulkMessageTextarea.addEventListener('input', function() {
            const charCount = this.value.length;
            bulkCharCount.textContent = charCount;
            
            if (charCount > 900) {
                bulkCharCount.style.color = '#dc3545';
            } else if (charCount > 700) {
                bulkCharCount.style.color = '#ffc107';
            } else {
                bulkCharCount.style.color = '#28a745';
            }
        });
    }
    
    // Send Bulk SMS functionality
    const sendBulkSmsBtn = document.getElementById('sendBulkSmsBtn');
    if (sendBulkSmsBtn) {
        sendBulkSmsBtn.addEventListener('click', function() {
            const contacts = document.getElementById('bulk_contacts').value.trim();
            const message = document.getElementById('bulk_message').value.trim();
            const type = document.getElementById('bulk_type').value;
            const scheduledDateTime = document.getElementById('scheduled_datetime').value;
            
            if (!contacts) {
                alert('Please enter phone numbers to send bulk SMS.');
                return;
            }
            
            if (!message) {
                alert('Please enter a message to send.');
                return;
            }
            
            if (message.length > 1000) {
                alert('Message is too long. Maximum 1000 characters allowed.');
                return;
            }
            
            // Validate scheduled datetime if provided
            if (scheduledDateTime) {
                const scheduledDate = new Date(scheduledDateTime);
                const now = new Date();
                if (scheduledDate <= now) {
                    alert('Scheduled date must be in the future.');
                    return;
                }
            }
            
            // Confirm before sending
            const contactCount = contacts.split(/[,+]/).filter(c => c.trim()).length;
            let confirmMessage = `Are you sure you want to send SMS to ${contactCount} contacts?`;
            if (scheduledDateTime) {
                confirmMessage += `\\nScheduled for: ${new Date(scheduledDateTime).toLocaleString()}`;
            }
            
            if (!confirm(confirmMessage)) {
                return;
            }
            
            this.disabled = true;
            this.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Sending...';
            
            const requestData = {
                contacts: contacts,
                message: message,
                type: type
            };
            
            if (scheduledDateTime) {
                requestData.scheduledDateTime = scheduledDateTime;
            }
            
            fetch('{{ route("admin.general-settings.send-bulk-sms") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(requestData)
            })
            .then(response => response.json())
            .then(data => {
                console.log('Bulk SMS Response:', data); // Debug log
                
                if (data.success) {
                    let alertMessage = `Bulk SMS ${scheduledDateTime ? 'scheduled' : 'sent'} successfully!\\nContacts: ${data.contacts_count || 0}`;
                    if (data.message_id) {
                        alertMessage += `\\nMessage ID: ${data.message_id}`;
                    }
                    if (data.sms_id) {
                        alertMessage += `\\nSMS ID: ${data.sms_id}`;
                    }
                    if (scheduledDateTime) {
                        alertMessage += `\\nScheduled for: ${new Date(scheduledDateTime).toLocaleString()}`;
                    }
                    alert(alertMessage);
                    
                    // Clear form
                    document.getElementById('bulk_contacts').value = '';
                    document.getElementById('bulk_message').value = '';
                    document.getElementById('scheduled_datetime').value = '';
                    bulkCharCount.textContent = '0';
                } else {
                    // Enhanced error handling for MRAM API errors
                    let errorMessage = 'Failed to send bulk SMS:\\n';
                    errorMessage += (data.error || 'Unknown error');
                    
                    // Add specific error information if available
                    if (data.code) {
                        errorMessage += `\\nError Code: ${data.code}`;
                    }
                    
                    if (data.contacts_sent_to) {
                        errorMessage += `\\nContacts attempted: ${data.contacts_sent_to}`;
                    }
                    
                    // Add helpful hints for common errors
                    if (data.code === '1016') {
                        errorMessage += `\\n\\n🔒 IP Address Issue: Contact MRAM to whitelist your IP address.`;
                        errorMessage += `\\n📞 MRAM Support: Contact them with your API key.`;
                    } else if (data.code === '1007') {
                        errorMessage += `\\n\\n💰 Balance Issue: Please top up your MRAM account balance.`;
                    } else if (data.code === '1012') {
                        errorMessage += `\\n\\n📱 Number Format Issue: Check phone number format (must include country code 88).`;
                    }
                    
                    if (data.note) {
                        errorMessage += `\\n\\nNote: ${data.note}`;
                    }
                    
                    alert(errorMessage);
                }
            })
            .catch(error => {
                alert('Error sending bulk SMS: ' + error.message);
            })
            .finally(() => {
                this.disabled = false;
                this.innerHTML = '<i class="fas fa-paper-plane me-1"></i>Send Bulk SMS';
            });
        });
    }
    
    // Validate Contacts functionality
    const validateContactsBtn = document.getElementById('validateContactsBtn');
    if (validateContactsBtn) {
        validateContactsBtn.addEventListener('click', function() {
            const contacts = document.getElementById('bulk_contacts').value.trim();
            
            if (!contacts) {
                alert('Please enter phone numbers to validate.');
                return;
            }
            
            const contactArray = contacts.split(/[,+]/).filter(c => c.trim());
            const validNumbers = [];
            const invalidNumbers = [];
            
            contactArray.forEach(contact => {
                const cleaned = contact.trim().replace(/[^0-9]/g, '');
                
                // Basic Bangladesh number validation
                if (cleaned.length >= 10 && 
                    (cleaned.startsWith('01') || cleaned.startsWith('8801') || cleaned.startsWith('880'))) {
                    validNumbers.push(contact.trim());
                } else {
                    invalidNumbers.push(contact.trim());
                }
            });
            
            let message = `Validation Results:\\n`;
            message += `Total numbers: ${contactArray.length}\\n`;
            message += `Valid numbers: ${validNumbers.length}\\n`;
            message += `Invalid numbers: ${invalidNumbers.length}`;
            
            if (invalidNumbers.length > 0) {
                message += `\\n\\nInvalid numbers:\\n${invalidNumbers.join(', ')}`;
            }
            
            alert(message);
        });
    }
    
    // Many To Many SMS functionality
    let recipientIndex = 1;
    
    // Add recipient functionality
    const addRecipientBtn = document.getElementById('addRecipientBtn');
    if (addRecipientBtn) {
        addRecipientBtn.addEventListener('click', function() {
            const container = document.getElementById('recipientsContainer');
            const newRecipient = document.createElement('div');
            newRecipient.className = 'recipient-item mb-3 border rounded p-3';
            newRecipient.dataset.index = recipientIndex;
            
            newRecipient.innerHTML = `
                <div class="row">
                    <div class="col-md-4">
                        <label class="form-label">Phone Number</label>
                        <input type="text" class="form-control recipient-phone" 
                               placeholder="+8801XXXXXXXXX or 01XXXXXXXXX" 
                               data-index="${recipientIndex}">
                    </div>
                    <div class="col-md-7">
                        <label class="form-label">Message</label>
                        <textarea class="form-control recipient-message" 
                                  rows="2" maxlength="1000" 
                                  placeholder="Enter personalized message..." 
                                  data-index="${recipientIndex}"></textarea>
                        <small class="form-text text-muted">
                            <span class="char-count">0</span>/1000 characters
                        </small>
                    </div>
                    <div class="col-md-1 d-flex align-items-center">
                        <button type="button" class="btn btn-sm btn-danger remove-recipient" 
                                data-index="${recipientIndex}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            `;
            
            container.appendChild(newRecipient);
            recipientIndex++;
            
            // Add event listeners for the new recipient
            attachRecipientEvents(newRecipient);
            updateRemoveButtons();
        });
    }
    
    // Remove recipient functionality
    function attachRecipientEvents(recipientElement) {
        const removeBtn = recipientElement.querySelector('.remove-recipient');
        const messageTextarea = recipientElement.querySelector('.recipient-message');
        const charCount = recipientElement.querySelector('.char-count');
        
        if (removeBtn) {
            removeBtn.addEventListener('click', function() {
                recipientElement.remove();
                updateRemoveButtons();
            });
        }
        
        if (messageTextarea && charCount) {
            messageTextarea.addEventListener('input', function() {
                const count = this.value.length;
                charCount.textContent = count;
                
                if (count > 900) {
                    charCount.style.color = '#dc3545';
                } else if (count > 700) {
                    charCount.style.color = '#ffc107';
                } else {
                    charCount.style.color = '#28a745';
                }
            });
        }
    }
    
    // Update remove buttons visibility
    function updateRemoveButtons() {
        const recipients = document.querySelectorAll('.recipient-item');
        recipients.forEach((recipient, index) => {
            const removeBtn = recipient.querySelector('.remove-recipient');
            if (removeBtn) {
                removeBtn.style.display = recipients.length > 1 ? 'block' : 'none';
            }
        });
    }
    
    // Initialize event listeners for existing recipients
    document.querySelectorAll('.recipient-item').forEach(recipient => {
        attachRecipientEvents(recipient);
    });
    updateRemoveButtons();
    
    // Send Many To Many SMS
    const sendManyToManyBtn = document.getElementById('sendManyToManyBtn');
    if (sendManyToManyBtn) {
        sendManyToManyBtn.addEventListener('click', function() {
            const recipients = document.querySelectorAll('.recipient-item');
            const messages = [];
            let hasErrors = false;
            
            recipients.forEach(recipient => {
                const phone = recipient.querySelector('.recipient-phone').value.trim();
                const message = recipient.querySelector('.recipient-message').value.trim();
                
                if (phone && message) {
                    messages.push({
                        to: phone,
                        message: message
                    });
                } else if (phone || message) {
                    hasErrors = true;
                }
            });
            
            if (hasErrors) {
                alert('Please fill in both phone number and message for all recipients, or remove incomplete entries.');
                return;
            }
            
            if (messages.length === 0) {
                alert('Please add at least one recipient with phone number and message.');
                return;
            }
            
            // Confirm before sending
            if (!confirm(`Are you sure you want to send personalized SMS to ${messages.length} recipients?`)) {
                return;
            }
            
            this.disabled = true;
            this.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Sending...';
            
            fetch('{{ route("admin.general-settings.send-many-to-many") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    messages: messages
                })
            })
            .then(response => response.json())
            .then(data => {
                console.log('Many To Many SMS Response:', data); // Debug log
                
                if (data.success) {
                    let alertMessage = `Many To Many SMS sent successfully!\\nRecipients: ${data.messages_count || 0}`;
                    if (data.message_id) {
                        alertMessage += `\\nMessage ID: ${data.message_id}`;
                    }
                    if (data.sms_id) {
                        alertMessage += `\\nSMS ID: ${data.sms_id}`;
                    }
                    alert(alertMessage);
                    
                    // Clear form
                    document.querySelectorAll('.recipient-phone').forEach(input => input.value = '');
                    document.querySelectorAll('.recipient-message').forEach(textarea => textarea.value = '');
                    document.querySelectorAll('.char-count').forEach(span => span.textContent = '0');
                } else {
                    // Enhanced error handling for MRAM API errors
                    let errorMessage = 'Failed to send Many To Many SMS:\\n';
                    errorMessage += (data.error || 'Unknown error');
                    
                    // Add specific error information if available
                    if (data.code) {
                        errorMessage += `\\nError Code: ${data.code}`;
                    }
                    
                    if (data.messages_count !== undefined) {
                        errorMessage += `\\nMessages attempted: ${data.messages_count}`;
                    }
                    
                    // Add helpful hints for common errors
                    if (data.code === '1016') {
                        errorMessage += `\\n\\n🔒 IP Address Issue: Contact MRAM to whitelist your IP address.`;
                        errorMessage += `\\n📞 MRAM Support: Contact them with your API key.`;
                    } else if (data.code === '1007') {
                        errorMessage += `\\n\\n💰 Balance Issue: Please top up your MRAM account balance.`;
                    } else if (data.code === '1012') {
                        errorMessage += `\\n\\n📱 Number Format Issue: Check phone number format (must include country code 88).`;
                    }
                    
                    if (data.note) {
                        errorMessage += `\\n\\nNote: ${data.note}`;
                    }
                    
                    alert(errorMessage);
                }
            })
            .catch(error => {
                alert('Error sending Many To Many SMS: ' + error.message);
            })
            .finally(() => {
                this.disabled = false;
                this.innerHTML = '<i class="fas fa-paper-plane me-1"></i>Send Many To Many SMS';
            });
        });
    }
    
    // Validate Many To Many
    const validateManyToManyBtn = document.getElementById('validateManyToManyBtn');
    if (validateManyToManyBtn) {
        validateManyToManyBtn.addEventListener('click', function() {
            const recipients = document.querySelectorAll('.recipient-item');
            let validCount = 0;
            let invalidCount = 0;
            let emptyCount = 0;
            const invalidNumbers = [];
            
            recipients.forEach(recipient => {
                const phone = recipient.querySelector('.recipient-phone').value.trim();
                const message = recipient.querySelector('.recipient-message').value.trim();
                
                if (!phone && !message) {
                    emptyCount++;
                } else if (!phone || !message) {
                    invalidCount++;
                } else {
                    const cleaned = phone.replace(/[^0-9]/g, '');
                    if (cleaned.length >= 10 && 
                        (cleaned.startsWith('01') || cleaned.startsWith('8801') || cleaned.startsWith('880'))) {
                        validCount++;
                    } else {
                        invalidCount++;
                        invalidNumbers.push(phone);
                    }
                }
            });
            
            let message = `Many To Many Validation Results:\\n`;
            message += `Total recipients: ${recipients.length}\\n`;
            message += `Valid entries: ${validCount}\\n`;
            message += `Invalid entries: ${invalidCount}\\n`;
            message += `Empty entries: ${emptyCount}`;
            
            if (invalidNumbers.length > 0) {
                message += `\\n\\nInvalid numbers:\\n${invalidNumbers.join(', ')}`;
            }
            
            alert(message);
        });
    }
    
    // Clear All functionality
    const clearAllBtn = document.getElementById('clearAllBtn');
    if (clearAllBtn) {
        clearAllBtn.addEventListener('click', function() {
            if (confirm('Are you sure you want to clear all recipients?')) {
                // Keep only the first recipient and clear its values
                const container = document.getElementById('recipientsContainer');
                const recipients = container.querySelectorAll('.recipient-item');
                
                // Remove all except the first one
                for (let i = 1; i < recipients.length; i++) {
                    recipients[i].remove();
                }
                
                // Clear the first recipient's values
                if (recipients.length > 0) {
                    recipients[0].querySelector('.recipient-phone').value = '';
                    recipients[0].querySelector('.recipient-message').value = '';
                    recipients[0].querySelector('.char-count').textContent = '0';
                }
                
                updateRemoveButtons();
            }
        });
    }
});
</script>
@endsection
