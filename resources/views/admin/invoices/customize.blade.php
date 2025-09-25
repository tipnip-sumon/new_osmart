@extends('layouts.admin')

@section('title', 'Customize Invoice')

@section('styles')
<style>
    .customize-container {
        background: #f8f9fa;
        min-height: 100vh;
        padding: 20px;
    }
    
    .customize-header {
        background: white;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    .header-title {
        margin: 0;
        color: #333;
        font-size: 24px;
        font-weight: 600;
    }
    
    .header-subtitle {
        color: #666;
        margin-top: 5px;
    }
    
    .customization-panel {
        background: white;
        border-radius: 10px;
        padding: 25px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        height: 85vh;
        overflow-y: auto;
    }
    
    .panel-section {
        margin-bottom: 30px;
        border-bottom: 1px solid #eee;
        padding-bottom: 25px;
    }
    
    .panel-section:last-child {
        border-bottom: none;
        margin-bottom: 0;
    }
    
    .section-title {
        font-size: 18px;
        font-weight: 600;
        color: #333;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .section-icon {
        width: 24px;
        height: 24px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 12px;
    }
    
    .form-group {
        margin-bottom: 20px;
    }
    
    .form-label {
        font-weight: 600;
        color: #555;
        margin-bottom: 8px;
        display: block;
    }
    
    .form-control {
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 12px 15px;
        transition: all 0.3s ease;
        font-size: 14px;
    }
    
    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        outline: none;
    }
    
    .color-picker-wrapper {
        display: flex;
        gap: 10px;
        align-items: center;
    }
    
    .color-picker {
        width: 50px;
        height: 40px;
        border: 1px solid #ddd;
        border-radius: 8px;
        cursor: pointer;
        padding: 0;
    }
    
    .color-preview {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        border: 2px solid #fff;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }
    
    .template-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 15px;
    }
    
    .template-card {
        border: 2px solid #e9ecef;
        border-radius: 10px;
        padding: 15px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        background: #f8f9fa;
    }
    
    .template-card:hover {
        border-color: #667eea;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    .template-card.active {
        border-color: #667eea;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    
    .template-icon {
        font-size: 32px;
        margin-bottom: 10px;
    }
    
    .template-name {
        font-weight: 600;
        margin-bottom: 5px;
    }
    
    .template-description {
        font-size: 12px;
        opacity: 0.8;
    }
    
    .font-selector {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }
    
    .font-option {
        padding: 8px 15px;
        border: 1px solid #ddd;
        border-radius: 20px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 14px;
        background: white;
    }
    
    .font-option:hover {
        border-color: #667eea;
        background: #f0f2ff;
    }
    
    .font-option.active {
        border-color: #667eea;
        background: #667eea;
        color: white;
    }
    
    .upload-area {
        border: 2px dashed #ddd;
        border-radius: 10px;
        padding: 30px;
        text-align: center;
        transition: all 0.3s ease;
        cursor: pointer;
        background: #fafafa;
    }
    
    .upload-area:hover {
        border-color: #667eea;
        background: #f0f2ff;
    }
    
    .upload-area.dragover {
        border-color: #667eea;
        background: #e8f0ff;
    }
    
    .upload-icon {
        font-size: 48px;
        color: #667eea;
        margin-bottom: 15px;
    }
    
    .upload-text {
        color: #666;
        margin-bottom: 10px;
    }
    
    .upload-hint {
        color: #999;
        font-size: 12px;
    }
    
    .logo-preview {
        max-width: 200px;
        max-height: 100px;
        border-radius: 8px;
        border: 1px solid #ddd;
        margin-top: 10px;
    }
    
    .action-buttons {
        display: flex;
        gap: 15px;
        justify-content: center;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid #eee;
    }
    
    .btn-custom {
        padding: 12px 30px;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    
    .btn-success {
        background: linear-gradient(135deg, #56ab2f 0%, #a8e6cf 100%);
        color: white;
    }
    
    .btn-secondary {
        background: linear-gradient(135deg, #757f9a 0%, #d7dde8 100%);
        color: white;
    }
    
    .btn-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        text-decoration: none;
        color: white;
    }
    
    .preview-frame {
        background: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        border: 1px solid #e0e0e0;
        height: 85vh;
    }
    
    .preview-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 15px 20px;
        display: flex;
        align-items: center;
        justify-content: between;
    }
    
    .preview-title {
        font-size: 16px;
        font-weight: 600;
        margin: 0;
    }
    
    .preview-content {
        height: calc(100% - 60px);
        overflow-y: auto;
        padding: 20px;
    }
    
    .preview-iframe {
        width: 100%;
        height: 100%;
        border: none;
        background: white;
    }
    
    .range-input {
        width: 100%;
        margin: 10px 0;
    }
    
    .range-value {
        background: #667eea;
        color: white;
        padding: 4px 8px;
        border-radius: 15px;
        font-size: 12px;
        font-weight: 600;
        margin-left: 10px;
    }
    
    .toggle-switch {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 24px;
    }
    
    .toggle-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }
    
    .toggle-slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: .4s;
        border-radius: 24px;
    }
    
    .toggle-slider:before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }
    
    input:checked + .toggle-slider {
        background-color: #667eea;
    }
    
    input:checked + .toggle-slider:before {
        transform: translateX(26px);
    }
    
    @media (max-width: 768px) {
        .customize-container {
            padding: 10px;
        }
        
        .customization-panel {
            height: auto;
            overflow-y: visible;
        }
        
        .preview-frame {
            height: 60vh;
            margin-top: 20px;
        }
        
        .template-grid {
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        }
        
        .action-buttons {
            flex-direction: column;
        }
    }
</style>
@endsection

@section('content')
<div class="customize-container">
    <!-- Header -->
    <div class="customize-header">
        <h1 class="header-title">🎨 Customize Invoice</h1>
        <p class="header-subtitle">Personalize your invoice template with your brand colors, logo, and styling preferences.</p>
    </div>
    
    <div class="row">
        <!-- Customization Panel -->
        <div class="col-lg-6">
            <div class="customization-panel">
                <!-- Template Selection -->
                <div class="panel-section">
                    <h3 class="section-title">
                        <div class="section-icon">🎭</div>
                        Template Style
                    </h3>
                    <div class="template-grid">
                        <div class="template-card active" data-template="default">
                            <div class="template-icon">📄</div>
                            <div class="template-name">Default</div>
                            <div class="template-description">Clean and professional</div>
                        </div>
                        <div class="template-card" data-template="modern">
                            <div class="template-icon">✨</div>
                            <div class="template-name">Modern</div>
                            <div class="template-description">Sleek and contemporary</div>
                        </div>
                        <div class="template-card" data-template="classic">
                            <div class="template-icon">🏛️</div>
                            <div class="template-name">Classic</div>
                            <div class="template-description">Traditional business style</div>
                        </div>
                        <div class="template-card" data-template="minimal">
                            <div class="template-icon">⚪</div>
                            <div class="template-name">Minimal</div>
                            <div class="template-description">Simple and elegant</div>
                        </div>
                    </div>
                </div>
                
                <!-- Color Scheme -->
                <div class="panel-section">
                    <h3 class="section-title">
                        <div class="section-icon">🎨</div>
                        Color Scheme
                    </h3>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Primary Color</label>
                                <div class="color-picker-wrapper">
                                    <input type="color" class="color-picker" id="primaryColor" value="#667eea">
                                    <span class="color-preview" style="background: #667eea;"></span>
                                    <span class="color-code">#667eea</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Secondary Color</label>
                                <div class="color-picker-wrapper">
                                    <input type="color" class="color-picker" id="secondaryColor" value="#764ba2">
                                    <span class="color-preview" style="background: #764ba2;"></span>
                                    <span class="color-code">#764ba2</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Typography -->
                <div class="panel-section">
                    <h3 class="section-title">
                        <div class="section-icon">📝</div>
                        Typography
                    </h3>
                    <div class="form-group">
                        <label class="form-label">Font Family</label>
                        <div class="font-selector">
                            <div class="font-option active" data-font="Arial" style="font-family: Arial;">Arial</div>
                            <div class="font-option" data-font="Helvetica" style="font-family: Helvetica;">Helvetica</div>
                            <div class="font-option" data-font="Times" style="font-family: Times;">Times</div>
                            <div class="font-option" data-font="Georgia" style="font-family: Georgia;">Georgia</div>
                            <div class="font-option" data-font="Verdana" style="font-family: Verdana;">Verdana</div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Font Size</label>
                        <input type="range" class="range-input" id="fontSize" min="10" max="16" value="12">
                        <span class="range-value">12px</span>
                    </div>
                </div>
                
                <!-- Company Logo -->
                <div class="panel-section">
                    <h3 class="section-title">
                        <div class="section-icon">🏢</div>
                        Company Logo
                    </h3>
                    <div class="upload-area" id="logoUpload">
                        <div class="upload-icon">📤</div>
                        <div class="upload-text">Click to upload or drag and drop</div>
                        <div class="upload-hint">PNG, JPG, SVG up to 2MB</div>
                        <input type="file" id="logoFile" accept="image/*" style="display: none;">
                    </div>
                    <img id="logoPreview" class="logo-preview" style="display: none;">
                </div>
                
                <!-- Company Information -->
                <div class="panel-section">
                    <h3 class="section-title">
                        <div class="section-icon">ℹ️</div>
                        Company Information
                    </h3>
                    <div class="form-group">
                        <label class="form-label">Company Name</label>
                        <input type="text" class="form-control" id="companyName" value="MultiVendor Marketplace">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Address</label>
                        <textarea class="form-control" id="companyAddress" rows="3">789 Business Avenue, Suite 100
Business City, BC 12345</textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Phone</label>
                                <input type="text" class="form-control" id="companyPhone" value="+1 (555) 987-6543">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" id="companyEmail" value="invoices@multivendor.com">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Website</label>
                        <input type="url" class="form-control" id="companyWebsite" value="www.multivendor.com">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tax ID</label>
                        <input type="text" class="form-control" id="companyTaxId" value="">
                    </div>
                </div>
                
                <!-- Invoice Settings -->
                <div class="panel-section">
                    <h3 class="section-title">
                        <div class="section-icon">⚙️</div>
                        Invoice Settings
                    </h3>
                    <div class="form-group">
                        <label class="form-label">Show Commission Details</label>
                        <label class="toggle-switch">
                            <input type="checkbox" id="showCommission" checked>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Show PV Points</label>
                        <label class="toggle-switch">
                            <input type="checkbox" id="showPV" checked>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Show Watermark</label>
                        <label class="toggle-switch">
                            <input type="checkbox" id="showWatermark" checked>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Footer Text</label>
                        <textarea class="form-control" id="footerText" rows="2">Thank you for your business!</textarea>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="action-buttons">
                    <button class="btn-custom btn-primary" onclick="applyChanges()">
                        <i class="fas fa-check"></i> Apply Changes
                    </button>
                    <button class="btn-custom btn-success" onclick="saveTemplate()">
                        <i class="fas fa-save"></i> Save Template
                    </button>
                    <a href="{{ route('admin.invoices.preview', $order['id']) }}" class="btn-custom btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Preview
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Live Preview -->
        <div class="col-lg-6">
            <div class="preview-frame">
                <div class="preview-header">
                    <h3 class="preview-title">🔍 Live Preview</h3>
                </div>
                <div class="preview-content">
                    <iframe id="previewIframe" class="preview-iframe" src="about:blank"></iframe>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let currentSettings = {
        template: 'default',
        primaryColor: '#667eea',
        secondaryColor: '#764ba2',
        fontFamily: 'Arial',
        fontSize: 12,
        companyName: 'MultiVendor Marketplace',
        companyAddress: '789 Business Avenue, Suite 100\nBusiness City, BC 12345',
        companyPhone: '+1 (555) 987-6543',
        companyEmail: 'invoices@multivendor.com',
        companyWebsite: 'www.multivendor.com',
        companyTaxId: '',
        showCommission: true,
        showPV: true,
        showWatermark: true,
        footerText: 'Thank you for your business!',
        logo: null
    };
    
    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        updatePreview();
        setupEventListeners();
    });
    
    function setupEventListeners() {
        // Template selection
        document.querySelectorAll('.template-card').forEach(card => {
            card.addEventListener('click', function() {
                document.querySelectorAll('.template-card').forEach(c => c.classList.remove('active'));
                this.classList.add('active');
                currentSettings.template = this.dataset.template;
                updatePreview();
            });
        });
        
        // Font selection
        document.querySelectorAll('.font-option').forEach(option => {
            option.addEventListener('click', function() {
                document.querySelectorAll('.font-option').forEach(o => o.classList.remove('active'));
                this.classList.add('active');
                currentSettings.fontFamily = this.dataset.font;
                updatePreview();
            });
        });
        
        // Color pickers
        document.getElementById('primaryColor').addEventListener('change', function() {
            currentSettings.primaryColor = this.value;
            this.nextElementSibling.style.background = this.value;
            this.nextElementSibling.nextElementSibling.textContent = this.value;
            updatePreview();
        });
        
        document.getElementById('secondaryColor').addEventListener('change', function() {
            currentSettings.secondaryColor = this.value;
            this.nextElementSibling.style.background = this.value;
            this.nextElementSibling.nextElementSibling.textContent = this.value;
            updatePreview();
        });
        
        // Font size
        document.getElementById('fontSize').addEventListener('input', function() {
            currentSettings.fontSize = this.value;
            this.nextElementSibling.textContent = this.value + 'px';
            updatePreview();
        });
        
        // Company information
        ['companyName', 'companyAddress', 'companyPhone', 'companyEmail', 'companyWebsite', 'companyTaxId', 'footerText'].forEach(id => {
            document.getElementById(id).addEventListener('input', function() {
                currentSettings[id] = this.value;
                updatePreview();
            });
        });
        
        // Toggles
        ['showCommission', 'showPV', 'showWatermark'].forEach(id => {
            document.getElementById(id).addEventListener('change', function() {
                currentSettings[id] = this.checked;
                updatePreview();
            });
        });
        
        // Logo upload
        document.getElementById('logoUpload').addEventListener('click', function() {
            document.getElementById('logoFile').click();
        });
        
        document.getElementById('logoFile').addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    currentSettings.logo = e.target.result;
                    document.getElementById('logoPreview').src = e.target.result;
                    document.getElementById('logoPreview').style.display = 'block';
                    updatePreview();
                };
                reader.readAsDataURL(file);
            }
        });
        
        // Drag and drop
        const uploadArea = document.getElementById('logoUpload');
        
        uploadArea.addEventListener('dragover', function(e) {
            e.preventDefault();
            this.classList.add('dragover');
        });
        
        uploadArea.addEventListener('dragleave', function() {
            this.classList.remove('dragover');
        });
        
        uploadArea.addEventListener('drop', function(e) {
            e.preventDefault();
            this.classList.remove('dragover');
            
            const files = e.dataTransfer.files;
            if (files.length > 0 && files[0].type.startsWith('image/')) {
                const file = files[0];
                const reader = new FileReader();
                reader.onload = function(e) {
                    currentSettings.logo = e.target.result;
                    document.getElementById('logoPreview').src = e.target.result;
                    document.getElementById('logoPreview').style.display = 'block';
                    updatePreview();
                };
                reader.readAsDataURL(file);
            }
        });
    }
    
    function updatePreview() {
        // Generate preview HTML with current settings
        const previewHtml = generateInvoiceHtml(currentSettings);
        const iframe = document.getElementById('previewIframe');
        iframe.srcdoc = previewHtml;
    }
    
    function generateInvoiceHtml(settings) {
        // This would generate the actual invoice HTML with applied settings
        // For now, returning a simplified version
        return `
            <!DOCTYPE html>
            <html>
            <head>
                <style>
                    body { 
                        font-family: ${settings.fontFamily}; 
                        font-size: ${settings.fontSize}px; 
                        margin: 20px;
                        color: #333;
                    }
                    .header { 
                        background: linear-gradient(135deg, ${settings.primaryColor} 0%, ${settings.secondaryColor} 100%);
                        color: white;
                        padding: 20px;
                        border-radius: 8px;
                        margin-bottom: 20px;
                    }
                    .company-name { 
                        font-size: 24px; 
                        font-weight: bold; 
                        margin-bottom: 10px;
                    }
                    .invoice-title {
                        font-size: 32px;
                        font-weight: bold;
                        text-align: right;
                        margin-top: -60px;
                    }
                    .content {
                        line-height: 1.6;
                    }
                    .footer {
                        margin-top: 40px;
                        text-align: center;
                        padding: 20px;
                        background: #f8f9fa;
                        border-radius: 8px;
                        font-style: italic;
                    }
                    .logo {
                        max-height: 60px;
                        margin-bottom: 10px;
                    }
                    ${settings.showWatermark ? '.watermark { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%) rotate(-45deg); font-size: 100px; color: rgba(102, 126, 234, 0.1); z-index: -1; }' : ''}
                </style>
            </head>
            <body>
                ${settings.showWatermark ? '<div class="watermark">INVOICE</div>' : ''}
                <div class="header">
                    ${settings.logo ? `<img src="${settings.logo}" class="logo" alt="Logo">` : ''}
                    <div class="company-name">${settings.companyName}</div>
                    <div>${settings.companyAddress.replace(/\n/g, '<br>')}</div>
                    <div>📞 ${settings.companyPhone} | ✉️ ${settings.companyEmail}</div>
                    <div class="invoice-title">INVOICE</div>
                </div>
                
                <div class="content">
                    <h3>Sample Invoice Preview</h3>
                    <p>This is a live preview of how your invoice will look with the current settings.</p>
                    
                    <table style="width: 100%; border-collapse: collapse; margin: 20px 0;">
                        <tr style="background: ${settings.primaryColor}; color: white;">
                            <th style="padding: 10px; text-align: left;">Product</th>
                            <th style="padding: 10px; text-align: right;">Amount</th>
                            ${settings.showPV ? '<th style="padding: 10px; text-align: center;">PV</th>' : ''}
                        </tr>
                        <tr>
                            <td style="padding: 10px; border-bottom: 1px solid #eee;">Sample Product</td>
                            <td style="padding: 10px; border-bottom: 1px solid #eee; text-align: right;">$99.99</td>
                            ${settings.showPV ? '<td style="padding: 10px; border-bottom: 1px solid #eee; text-align: center;">50 PV</td>' : ''}
                        </tr>
                    </table>
                    
                    ${settings.showCommission ? '<div style="background: #f0f8ff; padding: 15px; border-radius: 8px; margin: 20px 0;"><strong>Commission Details:</strong> Direct: $10.00 | Level 2: $5.00</div>' : ''}
                </div>
                
                <div class="footer">
                    ${settings.footerText}
                </div>
            </body>
            </html>
        `;
    }
    
    function applyChanges() {
        updatePreview();
        
        Swal.fire({
            icon: 'success',
            title: 'Changes Applied!',
            text: 'Your invoice customizations have been applied successfully.',
            timer: 2000,
            showConfirmButton: false
        });
    }
    
    function saveTemplate() {
        Swal.fire({
            title: 'Save Template',
            input: 'text',
            inputLabel: 'Template Name',
            inputPlaceholder: 'Enter a name for your template',
            showCancelButton: true,
            confirmButtonText: 'Save',
            inputValidator: (value) => {
                if (!value) {
                    return 'Please enter a template name!';
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Here you would save the template to the server
                Swal.fire({
                    icon: 'success',
                    title: 'Template Saved!',
                    text: `Template "${result.value}" has been saved successfully.`,
                    timer: 3000,
                    showConfirmButton: false
                });
            }
        });
    }
</script>
@endsection
