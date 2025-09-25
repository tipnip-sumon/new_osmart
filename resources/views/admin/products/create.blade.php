@extends('admin.layouts.app')

@section('title', 'Create Product')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
<style>
    /* International Standard Form Styles */
    .form-control:focus, .form-select:focus {
        border-color: #0066cc;
        box-shadow: 0 0 0 0.15rem rgba(0, 102, 204, 0.15);
        transition: all 0.2s ease-in-out;
    }

    .form-control.is-invalid {
        border-color: #dc3545;
        box-shadow: 0 0 0 0.15rem rgba(220, 53, 69, 0.15);
    }

    .form-control.is-valid {
        border-color: #28a745;
        box-shadow: 0 0 0 0.15rem rgba(40, 167, 69, 0.15);
    }

    /* Standard Form Section Styling */
    .form-section {
        background: #ffffff;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        margin-bottom: 1.5rem;
    }

    .form-section-header {
        background: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
        padding: 1rem 1.25rem;
        border-radius: 8px 8px 0 0;
    }

    .form-section-title {
        font-weight: 600;
        font-size: 1.1rem;
        color: #495057;
        margin: 0;
    }

    .form-section-body {
        padding: 1.25rem;
    }

    /* Field Labels with Standard Marking */
    .field-required {
        color: #dc3545;
        font-weight: 500;
    }

    .field-optional {
        color: #6c757d;
        font-weight: normal;
        font-size: 0.875rem;
    }

    .field-help {
        color: #6c757d;
        font-size: 0.8125rem;
        margin-top: 0.25rem;
    }

    /* Standard Button Styling */
    .btn-suggestion {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
        margin: 0.125rem;
        border-radius: 4px;
        border: 1px solid #dee2e6;
        background: #ffffff;
        color: #495057;
        transition: all 0.15s ease-in-out;
    }

    .btn-suggestion:hover {
        background: #e9ecef;
        border-color: #adb5bd;
        color: #495057;
    }

    /* Standard Color Display */
    .color-display-area {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        padding: 0.75rem;
    }

    .color-swatch {
        width: 20px;
        height: 20px;
        border: 1px solid #adb5bd;
        border-radius: 3px;
        display: inline-block;
        margin: 0.125rem;
        transition: border-color 0.15s ease-in-out;
    }

    .color-swatch:hover {
        border-color: #495057;
    }

    .color-swatch-container {
        text-align: center;
        margin: 0.25rem;
    }

    .color-name {
        font-size: 0.75rem;
        color: #6c757d;
        margin-top: 0.25rem;
    }

    /* Progress Indicator */
    .form-progress {
        background: #ffffff;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        padding: 0.75rem;
        margin-bottom: 1rem;
    }

    .progress-bar {
        background: linear-gradient(90deg, #28a745 0%, #20c997 100%);
    }

    /* Color Picker Modal Styles */
    .color-btn {
        margin: 2px;
        transition: all 0.2s ease;
    }

    .color-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }

    .color-btn.selected {
        background-color: #007bff;
        color: white;
        border-color: #007bff;
        transform: scale(1.05);
    }

    /* Clickable field indicators */
    .form-control[placeholder*="Auto"], .form-control[placeholder*="Suggested"] {
        background: linear-gradient(90deg, #f8f9fa 0%, #e9ecef 100%);
        cursor: pointer;
    }

    .form-control[placeholder*="Auto"]:hover, .form-control[placeholder*="Suggested"]:hover {
        background: linear-gradient(90deg, #e9ecef 0%, #dee2e6 100%);
        transform: translateY(-1px);
    }

    /* Required field styling */
    .form-label .text-danger {
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.5; }
        100% { opacity: 1; }
    }

    /* Tooltip styling enhancements */
    .custom-tooltip {
        animation: fadeInUp 0.3s ease;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Interactive button styling */
    #generate-slug-btn:hover {
        background-color: #0066cc;
        color: white;
        transition: all 0.2s ease-in-out;
    }

    /* International Standard Color System */
    :root {
        --primary-color: #0066cc;
        --success-color: #28a745;
        --warning-color: #ffc107;
        --danger-color: #dc3545;
        --info-color: #17a2b8;
        --light-color: #f8f9fa;
        --dark-color: #343a40;
        --border-color: #dee2e6;
        --text-muted: #6c757d;
    }

    /* Accessibility and Mobile Improvements */
    @media (max-width: 768px) {
        .form-section {
            margin-bottom: 1rem;
        }
        
        .form-section-body {
            padding: 1rem;
        }
        
        .btn-suggestion {
            font-size: 0.7rem;
        }
    }

    /* Starter kit tier highlighting */
    #starter_kit_tier_section.highlight {
        background: linear-gradient(45deg, #fff3cd, #ffeaa7);
        padding: 15px;
        border-radius: 8px;
        border: 2px solid #ffc107;
        animation: glow 1.5s ease-in-out infinite alternate;
    }

    @keyframes glow {
        from { box-shadow: 0 0 5px #ffc107; }
        to { box-shadow: 0 0 20px #ffc107, 0 0 30px #ffc107; }
    }

    /* Responsive Image Upload Styles */
    .image-upload-container {
        border: 2px dashed #dee2e6;
        border-radius: 8px;
        padding: 2rem;
        text-align: center;
        background: #f8f9fa;
        transition: all 0.3s ease;
        cursor: pointer;
        min-height: 200px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .image-upload-container:hover {
        border-color: var(--primary-color);
        background: #e3f2fd;
        transform: translateY(-2px);
    }

    .image-upload-container.drag-over {
        border-color: var(--success-color);
        background: #e8f5e8;
        transform: scale(1.02);
    }

    .image-preview-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        gap: 1rem;
        margin-top: 1rem;
    }

    .image-preview-item {
        position: relative;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        overflow: hidden;
        background: white;
        aspect-ratio: 1;
        transition: all 0.2s ease;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    .image-preview-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    }

    .image-preview-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .image-actions {
        position: absolute;
        top: 4px;
        right: 4px;
        display: flex;
        gap: 2px;
        opacity: 0;
        transition: opacity 0.2s ease;
    }

    .image-preview-item:hover .image-actions {
        opacity: 1;
    }

    .btn-primary-image, .btn-remove-image {
        width: 18px;
        height: 18px;
        border-radius: 50%;
        border: none;
        font-size: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }

    .btn-primary-image {
        background: var(--warning-color);
        color: white;
    }

    .btn-remove-image {
        background: var(--danger-color);
        color: white;
    }

    .primary-badge {
        position: absolute;
        bottom: 2px;
        left: 2px;
        background: var(--success-color);
        color: white;
        font-size: 8px;
        padding: 2px 4px;
        border-radius: 3px;
        font-weight: 500;
    }

    .image-info {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(0,0,0,0.7);
        color: white;
        font-size: 9px;
        padding: 2px 4px;
        text-align: center;
    }

    /* Responsive Design for Mobile */
    @media (max-width: 768px) {
        .col-lg-9 {
            order: 1;
        }
        
        .col-lg-3 {
            order: 2;
        }
        
        .image-upload-container {
            padding: 1.5rem;
            min-height: 150px;
        }
        
        .image-preview-grid {
            grid-template-columns: repeat(3, 1fr);
            gap: 0.5rem;
        }
        
        .btn-group {
            width: 100%;
        }
        
        .btn-group .btn {
            flex: 1;
        }
    }

    @media (max-width: 576px) {
        .image-upload-container {
            padding: 1rem;
            min-height: 120px;
        }
        
        .upload-content h5 {
            font-size: 1rem;
        }
        
        .upload-content p {
            font-size: 0.8rem;
        }
        
        .d-flex.gap-2 {
            flex-direction: column;
            gap: 0.5rem !important;
        }
        
        .image-preview-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    .image-remove-btn {
        position: absolute;
        top: 5px;
        right: 5px;
        background: rgba(220, 53, 69, 0.9);
        color: white;
        border: none;
        border-radius: 50%;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .image-remove-btn:hover {
        background: #dc3545;
        transform: scale(1.1);
    }

    /* MLM Styles */
    .mlm-badge {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 8px 15px;
        border-radius: 20px;
        font-size: 0.875rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .commission-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
    }

    .commission-card {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
        padding: 15px;
        border-radius: 10px;
        text-align: center;
    }

    /* Form Enhancements */
    .form-label {
        font-weight: 500;
        color: #2c384e;
        margin-bottom: 8px;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #80bdff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    .form-control.is-valid,
    .form-select.is-valid {
        border-color: #28a745;
        animation: successPulse 0.6s ease-in-out;
    }

    @keyframes successPulse {
        0% {
            box-shadow: 0 0 0 0 rgba(40, 167, 69, 0.4);
        }
        70% {
            box-shadow: 0 0 0 10px rgba(40, 167, 69, 0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(40, 167, 69, 0);
        }
    }

    .card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-bottom: none;
    }

    .card-header .card-title {
        color: white;
        font-weight: 600;
        margin: 0;
    }

    /* Status badges */
    .status-active {
        background: #28a745;
        color: white;
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 0.75rem;
    }

    .status-inactive {
        background: #6c757d;
        color: white;
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 0.75rem;
    }
}

.loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
}

.loading-content {
    background: white;
    padding: 30px;
    border-radius: 8px;
    text-align: center;
}

.points-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 8px;
    padding: 15px;
    margin: 15px 0;
    color: white;
}

.points-section h6 {
    color: white;
    margin-bottom: 15px;
}

.points-section .form-label {
    color: rgba(255, 255, 255, 0.9);
    font-weight: 500;
}

.points-section .text-muted {
    color: rgba(255, 255, 255, 0.7) !important;
}

.point-calculation-card {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 8px;
}

.mlm-toggle-switch .form-check-input:checked {
    background-color: #28a745;
    border-color: #28a745;
}

.commission-badge {
    background: linear-gradient(45deg, #FF6B6B, #4ECDC4);
    color: white;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 500;
}

/* Starter Kit specific styles */
.starter-kit-section {
    border-left: 4px solid #28a745;
    background: linear-gradient(90deg, rgba(40, 167, 69, 0.05) 0%, transparent 100%);
    border-radius: 0 8px 8px 0;
    padding-left: 15px;
    margin-left: -15px;
    transition: all 0.3s ease;
}

.starter-kit-badge {
    background: linear-gradient(135deg, #28a745, #20c997);
    color: white;
    padding: 6px 12px;
    border-radius: 15px;
    font-size: 0.8rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.tier-option {
    padding: 10px 15px;
    margin: 5px 0;
    border-radius: 8px;
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    border: 2px solid transparent;
    transition: all 0.3s ease;
}

.tier-option:hover {
    border-color: #007bff;
    background: linear-gradient(135deg, #e7f3ff, #cce7ff);
}
/* Primary badge styling fix */
.primary-badge {
    position: absolute;
    top: 8px;
    left: 8px;
    background: linear-gradient(135deg, #28a745, #20c997);
    color: white;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 4px;
    z-index: 10;
    box-shadow: 0 2px 6px rgba(40, 167, 69, 0.3);
}

.primary-badge::before {
    content: '★';
    font-size: 12px;
}

/* Modern Tags Input Styling */
.tags-input-wrapper {
    position: relative;
    margin-bottom: 1rem;
}

.tags-select {
    min-height: 50px !important;
    border: 2px solid #e3e6f0 !important;
    border-radius: 15px !important;
    transition: all 0.3s ease !important;
    background: #ffffff !important;
    font-size: 14px !important;
}

.tags-select:focus {
    border-color: #4e73df !important;
    box-shadow: 0 0 0 3px rgba(78, 115, 223, 0.1) !important;
    background: #ffffff !important;
}

/* Select2 Enhanced Styling */
.select2-container {
    width: 100% !important;
    font-size: 14px !important;
}

.select2-container .select2-selection--multiple {
    min-height: 50px !important;
    border: 2px solid #e3e6f0 !important;
    border-radius: 15px !important;
    padding: 10px 15px !important;
    background: #ffffff !important;
    transition: all 0.3s ease !important;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1) !important;
}

.select2-container--focus .select2-selection--multiple {
    border-color: #4e73df !important;
    box-shadow: 0 0 0 3px rgba(78, 115, 223, 0.1), 0 2px 8px rgba(0, 0, 0, 0.15) !important;
    background: #ffffff !important;
}

.select2-container .select2-selection--multiple .select2-selection__rendered {
    padding: 0 !important;
    display: flex !important;
    flex-wrap: wrap !important;
    gap: 8px !important;
    align-items: center !important;
    min-height: 30px !important;
}

.select2-container .select2-selection--multiple .select2-selection__choice {
    background: linear-gradient(135deg, #4e73df, #3653d3) !important;
    border: none !important;
    color: white !important;
    border-radius: 25px !important;
    padding: 8px 15px !important;
    margin: 0 !important;
    font-size: 13px !important;
    font-weight: 500 !important;
    box-shadow: 0 2px 6px rgba(78, 115, 223, 0.3) !important;
    transition: all 0.2s ease !important;
    display: flex !important;
    align-items: center !important;
    gap: 6px !important;
    max-height: 32px !important;
}

.select2-container .select2-selection--multiple .select2-selection__choice:hover {
    transform: translateY(-1px) !important;
    box-shadow: 0 4px 12px rgba(78, 115, 223, 0.4) !important;
    background: linear-gradient(135deg, #3653d3, #2e59d9) !important;
}

.select2-container .select2-selection--multiple .select2-selection__choice__remove {
    color: rgba(255, 255, 255, 0.9) !important;
    margin-right: 0 !important;
    margin-left: 8px !important;
    font-size: 16px !important;
    font-weight: bold !important;
    transition: all 0.2s ease !important;
    width: 20px !important;
    height: 20px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    border-radius: 50% !important;
}

.select2-container .select2-selection--multiple .select2-selection__choice__remove:hover {
    color: #ffffff !important;
    background: rgba(255, 255, 255, 0.2) !important;
    transform: scale(1.1) !important;
}

.select2-container .select2-search--inline .select2-search__field {
    margin-top: 0 !important;
    padding: 8px 0 !important;
    border: none !important;
    outline: none !important;
    font-size: 14px !important;
    background: transparent !important;
    min-width: 200px !important;
    height: 32px !important;
    line-height: 32px !important;
}

.select2-container .select2-search--inline .select2-search__field::placeholder {
    color: #6c757d !important;
    font-style: italic !important;
}

/* Dropdown Styling */
.select2-dropdown {
    border: 2px solid #4e73df !important;
    border-radius: 15px !important;
    box-shadow: 0 10px 30px rgba(78, 115, 223, 0.2) !important;
    background: white !important;
    overflow: hidden !important;
    margin-top: 5px !important;
}

.select2-results {
    padding: 10px 0 !important;
    max-height: 250px !important;
}

.select2-results__option {
    padding: 15px 20px !important;
    font-size: 14px !important;
    transition: all 0.2s ease !important;
    border-bottom: 1px solid #f1f3f4 !important;
    cursor: pointer !important;
}

.select2-results__option:last-child {
    border-bottom: none !important;
}

.select2-results__option--highlighted {
    background: linear-gradient(135deg, #4e73df, #3653d3) !important;
    color: white !important;
}

.select2-results__option[aria-selected="true"] {
    background: #e7f0ff !important;
    color: #4e73df !important;
    position: relative !important;
    font-weight: 500 !important;
}

.select2-results__option[aria-selected="true"]:before {
    content: '✓' !important;
    position: absolute !important;
    right: 20px !important;
    color: #28a745 !important;
    font-weight: bold !important;
    font-size: 16px !important;
}

/* Selected Tags Preview */
.selected-tags-preview {
    background: linear-gradient(135deg, #f8f9fc, #eaecf4) !important;
    border: 1px solid #e3e6f0 !important;
    border-radius: 15px !important;
    padding: 15px !important;
    margin-top: 15px !important;
}

.selected-tags-container {
    display: flex !important;
    flex-wrap: wrap !important;
    gap: 10px !important;
}

.preview-tag {
    background: linear-gradient(135deg, #1cc88a, #17a673) !important;
    color: white !important;
    padding: 6px 12px !important;
    border-radius: 20px !important;
    font-size: 12px !important;
    font-weight: 500 !important;
    display: flex !important;
    align-items: center !important;
    gap: 6px !important;
    box-shadow: 0 2px 4px rgba(28, 200, 138, 0.2) !important;
}

/* Loading state */
.select2-results__message {
    padding: 20px !important;
    text-align: center !important;
    color: #6c757d !important;
    font-style: italic !important;
    font-size: 14px !important;
}

/* No results */
.select2-results__option--highlighted.select2-results__message {
    background: transparent !important;
    color: #6c757d !important;
}

/* Form text styling for tags */
.tags-input-wrapper .form-text {
    margin-top: 8px !important;
    color: #6c757d !important;
    font-size: 13px !important;
    display: flex !important;
    align-items: center !important;
    gap: 5px !important;
}

/* Image preview item improvements */
.image-preview-item {
    position: relative;
    border: 2px solid #e3e6f0;
    border-radius: 12px;
    overflow: hidden;
    background: white;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.image-preview-item:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.15);
    border-color: #4e73df;
}

.image-actions {
    position: absolute;
    top: 8px;
    right: 8px;
    display: flex;
    gap: 6px;
    opacity: 1; /* Always visible instead of 0 */
    transition: opacity 0.3s ease;
}

.image-preview-item:hover .image-actions {
    opacity: 1;
}

.btn-primary-image, .btn-remove-image {
    background: rgba(0, 0, 0, 0.8);
    color: white !important;
    border: none;
    border-radius: 50%; /* Make buttons circular */
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px; /* Larger font for better visibility */
    cursor: pointer;
    font-weight: bold;
    transition: all 0.2s ease;
    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    line-height: 1;
}

.btn-primary-image {
    background: rgba(40, 167, 69, 0.9) !important;
    color: white !important;
}

.btn-primary-image:hover {
    background: #28a745 !important;
    transform: scale(1.1);
    box-shadow: 0 4px 8px rgba(40, 167, 69, 0.4);
}

.btn-remove-image {
    background: rgba(220, 53, 69, 0.9) !important;
    color: white !important;
}

.btn-remove-image:hover {
    background: #dc3545 !important;
    transform: scale(1.1);
    box-shadow: 0 4px 8px rgba(220, 53, 69, 0.4);
}

/* Image info styling */
.image-info {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
    color: white;
    padding: 12px 8px 8px 8px;
    border-radius: 0 0 8px 8px;
    font-size: 11px;
}

.image-info small {
    color: rgba(255,255,255,0.9) !important;
}

/* Select2 Custom Styling */
.select2-container--bootstrap-5 .select2-selection {
    min-height: 38px;
    border-radius: 6px;
    border: 1px solid #dee2e6;
    font-size: 0.875rem;
}

.select2-container--bootstrap-5 .select2-selection--multiple {
    min-height: 42px;
    padding: 2px 8px;
}

.select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__choice {
    background-color: #007bff;
    border: 1px solid #007bff;
    border-radius: 4px;
    color: #fff;
    font-size: 0.75rem;
    padding: 2px 8px;
    margin: 2px 4px 2px 0;
}

.select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__choice__remove {
    color: #fff;
    margin-right: 4px;
}

.select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__choice__remove:hover {
    color: #ffdddd;
}

.select2-container--bootstrap-5.select2-container--focus .select2-selection {
    border-color: #80bdff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.select2-dropdown.select2-dropdown--below {
    border-top: none;
    border-radius: 0 0 6px 6px;
}

.select2-dropdown.select2-dropdown--above {
    border-bottom: none;
    border-radius: 6px 6px 0 0;
}

.select2-results__option {
    padding: 8px 12px;
    font-size: 0.875rem;
}

.select2-results__option--highlighted {
    background-color: #007bff !important;
    color: #fff;
}

.select2-results__option[aria-selected="true"] {
    background-color: #e7f3ff;
    color: #004085;
}

/* Tags specific styling */
.tags-input-wrapper {
    position: relative;
}

.tags-input-wrapper .select2-container {
    width: 100% !important;
}

.tags-select {
    width: 100%;
}

/* Enhanced tag styling */
.select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__choice {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    color: white;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

.select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__choice i {
    font-size: 0.7rem;
}

.select2-results__option .text-success {
    font-weight: 600;
}

</style>
@endpush

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <div>
                <h1 class="page-title fw-semibold fs-20 mb-1 text-dark">Create New Product</h1>
                <p class="text-muted mb-0">Add a new product to your inventory with complete details</p>
            </div>
            <div class="ms-md-1 ms-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}" class="text-decoration-none">Products</a></li>
                        <li class="breadcrumb-item active text-primary" aria-current="page">Create</li>
                    </ol>
                </nav>
            </div>
        </div>
        
        <!-- Field Legend -->
        <div class="alert alert-light border border-primary-subtle mb-4">
            <div class="d-flex align-items-center">
                <i class="ti ti-info-circle text-primary me-2"></i>
                <div>
                    <strong class="text-dark">Field Requirements:</strong>
                    <span class="field-required ms-3">* Required Field</span>
                    <span class="field-optional ms-3">(Optional)</span>
                    <span class="text-muted ms-3">• Standard fields follow international e-commerce standards</span>
                </div>
            </div>
        </div>
        <!-- Page Header Close -->

        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" id="productForm">
            @csrf
            <div class="row">
                <!-- Main Content -->
                <div class="col-lg-12 col-md-12">
                    <div class="form-section">
                        <div class="form-section-header">
                            <h4 class="form-section-title">
                                <i class="ti ti-package text-primary me-2"></i>
                                Basic Information
                            </h4>
                        </div>
                        <div class="form-section-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label fw-medium">
                                        Product Name <span class="field-required">*</span>
                                    </label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" placeholder="Enter product name" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="field-help">Enter a clear, descriptive product name</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="slug" class="form-label fw-medium">
                                        URL Slug <span class="field-optional">(Optional)</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="text" class="form-control @error('slug') is-invalid @enderror" 
                                               id="slug" name="slug" value="{{ old('slug') }}" placeholder="Auto-generated from name">
                                        <button class="btn btn-outline-secondary" type="button" id="generate-slug-btn" title="Generate slug from product name">
                                            <i class="ti ti-refresh"></i>
                                        </button>
                                    </div>
                                    @error('slug')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="field-help">Leave empty to auto-generate, or customize (e.g., "my-product-name")</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="sku" class="form-label fw-medium">
                                        SKU <span class="field-optional">(Optional)</span>
                                    </label>
                                    <input type="text" class="form-control @error('sku') is-invalid @enderror" 
                                           id="sku" name="sku" value="{{ old('sku') }}" placeholder="Auto-generated">
                                    @error('sku')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="field-help">Stock Keeping Unit - Leave empty to auto-generate</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="barcode" class="form-label fw-medium">
                                        Barcode <span class="field-optional">(Optional)</span>
                                    </label>
                                    <input type="text" class="form-control @error('barcode') is-invalid @enderror" 
                                           id="barcode" name="barcode" value="{{ old('barcode') }}" placeholder="Product barcode">
                                    @error('barcode')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="field-help">Product barcode or UPC code</div>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label for="short_description" class="form-label fw-medium">
                                        Short Description <span class="field-optional">(Optional)</span>
                                    </label>
                                    <textarea class="form-control @error('short_description') is-invalid @enderror" 
                                              id="short_description" name="short_description" rows="3" 
                                              placeholder="Brief product description for listings">{{ old('short_description') }}</textarea>
                                    @error('short_description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="field-help">Appears in product listings (recommended: 150-300 characters)</div>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label for="description" class="form-label fw-medium">
                                        Detailed Description <span class="field-optional">(Optional)</span>
                                    </label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" name="description" rows="6" 
                                              placeholder="Comprehensive product description with features and benefits">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="field-help">Detailed product information for customers</div>
                                </div>
                        </div>
                    </div>

                    <!-- Pricing Information -->
                    <div class="form-section">
                        <div class="form-section-header">
                            <h4 class="form-section-title">
                                <i class="ti ti-currency-dollar text-success me-2"></i>
                                Pricing Information
                            </h4>
                        </div>
                        <div class="form-section-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="price" class="form-label fw-medium">
                                        Regular Price <span class="field-required">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">৳</span>
                                        <input type="number" class="form-control @error('price') is-invalid @enderror" 
                                               id="price" name="price" value="{{ old('price') }}" step="0.01" min="0" 
                                               placeholder="0.00" required>
                                    </div>
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="field-help">Base selling price of the product</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="sale_price" class="form-label fw-medium">
                                        Sale Price <span class="field-optional">(Optional)</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">৳</span>
                                        <input type="number" class="form-control @error('sale_price') is-invalid @enderror" 
                                               id="sale_price" name="sale_price" value="{{ old('sale_price') }}" step="0.01" min="0" 
                                               placeholder="0.00">
                                    </div>
                                    @error('sale_price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="field-help">Discounted price (must be less than regular price)</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="cost_price" class="form-label">Cost Price</label>
                                    <input type="number" class="form-control @error('cost_price') is-invalid @enderror" 
                                           id="cost_price" name="cost_price" value="{{ old('cost_price') }}" step="0.01" min="0" placeholder="0.00">
                                    @error('cost_price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Your cost for this product</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="compare_price" class="form-label">Compare at Price</label>
                                    <input type="number" class="form-control @error('compare_price') is-invalid @enderror" 
                                           id="compare_price" name="compare_price" value="{{ old('compare_price') }}" step="0.01" min="0" placeholder="0.00">
                                    @error('compare_price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Original price before discount</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Inventory Management -->
                    <div class="card custom-card">
                        <div class="card-header">
                            <div class="card-title">Inventory Management</div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="stock_quantity" class="form-label">Stock Quantity <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('stock_quantity') is-invalid @enderror" 
                                           id="stock_quantity" name="stock_quantity" value="{{ old('stock_quantity', 0) }}" min="0" required>
                                    @error('stock_quantity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="min_stock_level" class="form-label">Minimum Stock Level</label>
                                    <input type="number" class="form-control @error('min_stock_level') is-invalid @enderror" 
                                           id="min_stock_level" name="min_stock_level" value="{{ old('min_stock_level', 5) }}" min="0">
                                    @error('min_stock_level')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Alert when stock falls below this level</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="track_quantity" name="track_quantity" value="1" 
                                               {{ old('track_quantity', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="track_quantity">
                                            Track Quantity
                                        </label>
                                    </div>
                                    <div class="form-text">Automatically reduce stock when orders are placed</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="allow_backorder" name="allow_backorder" value="1" 
                                               {{ old('allow_backorder') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="allow_backorder">
                                            Allow Backorders
                                        </label>
                                    </div>
                                    <div class="form-text">Allow sales when out of stock</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- MLM Commission Settings -->
                    <div class="card custom-card">
                        <div class="card-header">
                            <div class="card-title">
                                <span class="mlm-badge">
                                    <i class="ti ti-network me-1"></i>
                                    MLM Commission Settings
                                </span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="generates_commission" name="generates_commission" value="1" 
                                               {{ old('generates_commission', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="generates_commission">
                                            Generates Commission
                                        </label>
                                    </div>
                                    <div class="form-text">Enable MLM commission for this product</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="is_starter_kit" name="is_starter_kit" value="1" 
                                               {{ old('is_starter_kit') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_starter_kit">
                                            Starter Kit Product
                                        </label>
                                    </div>
                                    <div class="form-text">Special product for new member registration</div>
                                </div>
                                <div class="col-md-6 mb-3" id="starter_kit_tier_section" style="{{ old('is_starter_kit') ? '' : 'display: none;' }}">
                                    <label for="starter_kit_tier" class="form-label">Starter Kit Tier</label>
                                    <select class="form-select @error('starter_kit_tier') is-invalid @enderror" 
                                            id="starter_kit_tier" name="starter_kit_tier">
                                        <option value="">Select Tier</option>
                                        <option value="basic" {{ old('starter_kit_tier') == 'basic' ? 'selected' : '' }}>Basic (2,000 TK)</option>
                                        <option value="standard" {{ old('starter_kit_tier') == 'standard' ? 'selected' : '' }}>Standard (5,000 TK)</option>
                                        <option value="premium" {{ old('starter_kit_tier') == 'premium' ? 'selected' : '' }}>Premium (10,000 TK)</option>
                                        <option value="platinum" {{ old('starter_kit_tier') == 'platinum' ? 'selected' : '' }}>Platinum (20,000 TK)</option>
                                    </select>
                                    @error('starter_kit_tier')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Select the tier for this starter kit</div>
                                </div>
                                <div class="col-md-12 mb-3" id="starter_kit_level_section" style="{{ old('is_starter_kit') ? '' : 'display: none;' }}">
                                    <label for="starter_kit_level" class="form-label">Custom Starter Kit Level</label>
                                    <input type="text" class="form-control @error('starter_kit_level') is-invalid @enderror" 
                                           id="starter_kit_level" name="starter_kit_level" value="{{ old('starter_kit_level') }}" 
                                           placeholder="e.g., Silver Package, Gold Package, etc.">
                                    @error('starter_kit_level')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Optional: Enter a custom level name for this starter kit</div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="pv_points" class="form-label">PV Points</label>
                                    <input type="number" class="form-control @error('pv_points') is-invalid @enderror" 
                                           id="pv_points" name="pv_points" value="{{ old('pv_points') }}" step="0.01" min="0" placeholder="Auto-calculated">
                                    @error('pv_points')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Personal Volume points</div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="bv_points" class="form-label">BV Points</label>
                                    <input type="number" class="form-control @error('bv_points') is-invalid @enderror" 
                                           id="bv_points" name="bv_points" value="{{ old('bv_points') }}" step="0.01" min="0" placeholder="Auto-calculated">
                                    @error('bv_points')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Business Volume points</div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="direct_commission_rate" class="form-label">Direct Commission (%)</label>
                                    <input type="number" class="form-control @error('direct_commission_rate') is-invalid @enderror" 
                                           id="direct_commission_rate" name="direct_commission_rate" value="{{ old('direct_commission_rate', 10) }}" 
                                           step="0.01" min="0" max="100">
                                    @error('direct_commission_rate')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SEO Information -->
                    <div class="form-section">
                        <div class="form-section-header">
                            <h4 class="form-section-title">
                                <i class="ti ti-search text-success me-2"></i>
                                SEO Information
                            </h4>
                        </div>
                        <div class="form-section-body">
                            <div class="mb-3">
                                <label for="meta_title" class="form-label fw-medium">
                                    Meta Title <span class="field-optional">(Optional)</span>
                                </label>
                                <input type="text" class="form-control @error('meta_title') is-invalid @enderror" 
                                       id="meta_title" name="meta_title" value="{{ old('meta_title') }}" 
                                       placeholder="SEO friendly title">
                                @error('meta_title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="field-help">Recommended length: 50-60 characters</div>
                            </div>

                            <div class="mb-3">
                                <label for="meta_description" class="form-label fw-medium">
                                    Meta Description <span class="field-optional">(Optional)</span>
                                </label>
                                <textarea class="form-control @error('meta_description') is-invalid @enderror" 
                                          id="meta_description" name="meta_description" rows="3" 
                                          placeholder="SEO friendly description">{{ old('meta_description') }}</textarea>
                                @error('meta_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="field-help">Recommended length: 150-160 characters</div>
                            </div>

                            <div class="mb-3">
                                <label for="meta_keywords" class="form-label fw-medium">
                                    Meta Keywords <span class="field-optional">(Optional)</span>
                                </label>
                                <input type="text" class="form-control @error('meta_keywords') is-invalid @enderror" 
                                       id="meta_keywords" name="meta_keywords" value="{{ old('meta_keywords') }}" 
                                       placeholder="keyword1, keyword2, keyword3">
                                @error('meta_keywords')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="field-help">Separate keywords with commas</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar - Settings & Media -->
                <div class="col-lg-12 col-md-12">
                    <!-- Product Images -->
                    <div class="form-section">
                        <div class="form-section-header">
                            <h4 class="form-section-title">
                                <i class="ti ti-image text-info me-2"></i>
                                Product Images
                            </h4>
                            <small class="text-muted">Upload high-quality product images</small>
                        </div>
                        <div class="form-section-body">
                            <div class="image-upload-container" id="image-upload-area">
                                <div class="upload-content text-center">
                                    <i class="ti ti-cloud-upload" style="font-size: 32px; color: #6c757d; margin-bottom: 10px;"></i>
                                    <h6 class="mb-2">Drop images or click to upload</h6>
                                    <p class="text-muted small mb-3">JPG, PNG, GIF, WEBP (Max: 10MB)</p>
                                    <div class="d-grid gap-1">
                                        <button type="button" class="btn btn-primary btn-sm" onclick="document.getElementById('image-input').click()">
                                            <i class="ti ti-upload"></i> Choose Files
                                        </button>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <button type="button" class="btn btn-outline-secondary" onclick="openResizeOptions()">
                                                <i class="ti ti-crop"></i> Resize
                                            </button>
                                            <a href="{{ route('admin.image-upload.demo') }}" target="_blank" class="btn btn-outline-success">
                                                <i class="ti ti-external-link"></i> Demo
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <input type="file" id="image-input" name="images[]" multiple accept="image/*" style="display: none;">
                            
                            <!-- Resize Options Panel -->
                            <div id="resize-options-panel" class="mt-3" style="display: none;">
                                <div class="alert alert-info p-2">
                                    <h6 class="mb-2"><i class="ti ti-info-circle me-1"></i>Resize Options</h6>
                                    <div class="row g-1">
                                        <div class="col-6">
                                            <label class="form-label small">Width</label>
                                            <input type="number" class="form-control form-control-sm" id="resize-width" value="800" min="50" max="2000">
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label small">Height</label>
                                            <input type="number" class="form-control form-control-sm" id="resize-height" value="600" min="50" max="2000">
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label small">Quality (%)</label>
                                            <input type="number" class="form-control form-control-sm" id="resize-quality" value="85" min="10" max="100">
                                        </div>
                                        <div class="col-6">
                                            <div class="form-check mt-3">
                                                <input class="form-check-input" type="checkbox" id="maintain-ratio" checked>
                                                <label class="form-check-label small" for="maintain-ratio">
                                                    Keep Ratio
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-2 d-grid gap-1">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <button type="button" class="btn btn-primary" onclick="resizeUploadedImages()">
                                                <i class="ti ti-crop"></i> Resize
                                            </button>
                                            <button type="button" class="btn btn-secondary" onclick="generateThumbnails()">
                                                <i class="ti ti-photo"></i> Thumbnails
                                            </button>
                                            <button type="button" class="btn btn-warning" onclick="optimizeImages()">
                                                <i class="ti ti-zap"></i> Optimize
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Image Preview Grid -->
                            <div id="image-preview-grid" class="image-preview-grid"></div>
                            
                            @error('images.*')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Product Organization -->
                    <div class="form-section">
                        <div class="form-section-header">
                            <h4 class="form-section-title">
                                <i class="ti ti-category text-info me-2"></i>
                                Product Organization
                            </h4>
                        </div>
                        <div class="form-section-body">
                            <div class="mb-3">
                                <label for="category_id" class="form-label fw-medium">
                                    Category <span class="field-required">*</span>
                                </label>
                                <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="field-help">Choose the most relevant category for your product</div>
                            </div>

                            <div class="mb-3">
                                <label for="vendor_id" class="form-label fw-medium">
                                    Vendor <span class="field-required">*</span>
                                </label>
                                <select class="form-select @error('vendor_id') is-invalid @enderror" id="vendor_id" name="vendor_id" required>
                                    <option value="">Select Vendor</option>
                                    @foreach($vendors as $vendor)
                                        <option value="{{ $vendor->id }}" {{ old('vendor_id') == $vendor->id ? 'selected' : '' }}>
                                            {{ $vendor->firstname }} {{ $vendor->lastname }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('vendor_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="field-help">Select the vendor who supplies this product</div>
                            </div>

                            @if($brands->isNotEmpty())
                            <div class="mb-3">
                                <label for="brand_id" class="form-label">Brand</label>
                                <select class="form-select @error('brand_id') is-invalid @enderror" id="brand_id" name="brand_id">
                                    <option value="">Select Brand (Optional)</option>
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                            {{ $brand->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('brand_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            @endif
                            
                            <div class="mb-4">
                                <label for="tags" class="form-label fw-semibold mb-2">
                                    <i class="ti ti-tags me-2 text-primary"></i>Product Tags
                                </label>
                                <div class="tags-input-wrapper">
                                    <select class="form-select tags-select @error('tags') is-invalid @enderror" 
                                            id="tags" name="tags[]" multiple data-placeholder="Search and select tags...">
                                        @foreach($tags as $tag)
                                            <option value="{{ $tag->id }}" 
                                                {{ in_array($tag->id, old('tags', [])) ? 'selected' : '' }}>
                                                {{ $tag->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('tags')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text mt-2">
                                        <i class="ti ti-info-circle me-1"></i>
                                        Start typing to search for tags or select from the dropdown
                                    </div>
                                </div>
                                <div class="selected-tags-preview mt-3" id="selected-tags-preview" style="display: none;">
                                    <small class="text-muted d-block mb-2">Selected Tags:</small>
                                    <div class="selected-tags-container" id="selected-tags-container"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Product Settings -->
                    <div class="form-section">
                        <div class="form-section-header">
                            <h4 class="form-section-title">
                                <i class="ti ti-settings text-warning me-2"></i>
                                Product Settings
                            </h4>
                        </div>
                        <div class="form-section-body">
                            <div class="mb-3">
                                <label for="status" class="form-label fw-medium">
                                    Status <span class="field-required">*</span>
                                </label>
                                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                    <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="field-help">Choose product visibility status</div>
                            </div>

                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1" 
                                       {{ old('is_featured') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_featured">
                                    Featured Product
                                </label>
                                <div class="form-text">Featured products appear in special sections</div>
                            </div>

                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="is_digital" name="is_digital" value="1" 
                                       {{ old('is_digital') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_digital">
                                    Digital Product
                                </label>
                                <div class="form-text">No shipping required for digital products</div>
                            </div>

                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="is_downloadable" name="is_downloadable" value="1" 
                                       {{ old('is_downloadable') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_downloadable">
                                    Downloadable
                                </label>
                                <div class="form-text">Customer can download this product</div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="weight" class="form-label">Weight (kg)</label>
                                    <input type="number" class="form-control @error('weight') is-invalid @enderror" 
                                           id="weight" name="weight" value="{{ old('weight') }}" step="0.01" min="0" placeholder="0.00">
                                    @error('weight')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="shipping_weight" class="form-label">Shipping Weight (kg)</label>
                                    <input type="number" class="form-control @error('shipping_weight') is-invalid @enderror" 
                                           id="shipping_weight" name="shipping_weight" value="{{ old('shipping_weight') }}" step="0.01" min="0" placeholder="0.00">
                                    @error('shipping_weight')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="unit_id" class="form-label">Unit</label>
                                    <select class="form-control select2 @error('unit_id') is-invalid @enderror" 
                                            id="unit_id" name="unit_id">
                                        <option value="">Select Unit</option>
                                        @if(isset($units))
                                            @foreach($units as $unit)
                                                <option value="{{ $unit->id }}" {{ old('unit_id') == $unit->id ? 'selected' : '' }}>
                                                    {{ $unit->name }} ({{ $unit->symbol }})
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @error('unit_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Dimensions -->
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <label for="length" class="form-label">Length (cm)</label>
                                    <input type="number" class="form-control @error('length') is-invalid @enderror" 
                                           id="length" name="length" value="{{ old('length') }}" step="0.01" min="0" placeholder="0.00">
                                    @error('length')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="width" class="form-label">Width (cm)</label>
                                    <input type="number" class="form-control @error('width') is-invalid @enderror" 
                                           id="width" name="width" value="{{ old('width') }}" step="0.01" min="0" placeholder="0.00">
                                    @error('width')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="height" class="form-label">Height (cm)</label>
                                    <input type="number" class="form-control @error('height') is-invalid @enderror" 
                                           id="height" name="height" value="{{ old('height') }}" step="0.01" min="0" placeholder="0.00">
                                    @error('height')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="dimensions" class="form-label">Dimensions (Text)</label>
                                    <input type="text" class="form-control @error('dimensions') is-invalid @enderror" 
                                           id="dimensions" name="dimensions" value="{{ old('dimensions') }}" placeholder="e.g., 50x30x20 cm">
                                    @error('dimensions')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Product Properties -->
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="condition" class="form-label">Condition</label>
                                    <select class="form-control @error('condition') is-invalid @enderror" 
                                            id="condition" name="condition">
                                        <option value="new" {{ old('condition', 'new') == 'new' ? 'selected' : '' }}>New</option>
                                        <option value="used" {{ old('condition') == 'used' ? 'selected' : '' }}>Used</option>
                                        <option value="refurbished" {{ old('condition') == 'refurbished' ? 'selected' : '' }}>Refurbished</option>
                                        <option value="damaged" {{ old('condition') == 'damaged' ? 'selected' : '' }}>Damaged</option>
                                    </select>
                                    @error('condition')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="size" class="form-label">Size</label>
                                    <input type="text" class="form-control @error('size') is-invalid @enderror" 
                                           id="size" name="size" value="{{ old('size') }}" placeholder="e.g., XL, 42, Large">
                                    @error('size')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="color" class="form-label">Color</label>
                                    <input type="text" class="form-control @error('color') is-invalid @enderror" 
                                           id="color" name="color" value="{{ old('color') }}" placeholder="e.g., Red, Blue, Black">
                                    @error('color')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Material and Options -->
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="material" class="form-label">Material</label>
                                    <input type="text" class="form-control @error('material') is-invalid @enderror" 
                                           id="material" name="material" value="{{ old('material') }}" placeholder="e.g., Cotton, Steel, Plastic">
                                    @error('material')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="size_chart" class="form-label">Size Chart</label>
                                    <textarea class="form-control @error('size_chart') is-invalid @enderror" 
                                              id="size_chart" name="size_chart" rows="2" placeholder="Size chart information">{{ old('size_chart') }}</textarea>
                                    @error('size_chart')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text mb-2">Add size measurements and fitting guide</div>
                                    
                                    <!-- Size Chart Suggestions -->
                                    <div class="size-suggestions">
                                        <small class="text-muted d-block mb-1">Quick Templates:</small>
                                        <div class="btn-group-sm d-flex flex-wrap gap-1" role="group">
                                            <button type="button" class="btn btn-outline-secondary btn-sm size-suggestion" data-template="clothing">Clothing Sizes</button>
                                            <button type="button" class="btn btn-outline-secondary btn-sm size-suggestion" data-template="shoes">Shoe Sizes</button>
                                            <button type="button" class="btn btn-outline-secondary btn-sm size-suggestion" data-template="electronics">Device Specs</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="color_options" class="form-label fw-medium">
                                        Color Options <span class="field-optional">(Optional)</span>
                                    </label>
                                    <textarea class="form-control @error('color_options') is-invalid @enderror" 
                                              id="color_options" name="color_options" rows="2" 
                                              placeholder="Available colors (e.g., Red, Blue, Green)">{{ old('color_options') }}</textarea>
                                    @error('color_options')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="field-help mb-2">List available color variants</div>
                                    
                                    <!-- Standard Color Suggestions -->
                                    <div class="color-display-area">
                                        <small class="text-muted d-block mb-2">Standard Colors:</small>
                                        <div class="d-flex flex-wrap gap-1" role="group">
                                            <button type="button" class="btn-suggestion color-suggestion" data-colors="Black, White, Gray">Basic</button>
                                            <button type="button" class="btn-suggestion color-suggestion" data-colors="Red, Blue, Green">Primary</button>
                                            <button type="button" class="btn-suggestion color-suggestion" data-colors="Navy, Brown, Beige">Natural</button>
                                            <button type="button" class="btn-suggestion color-suggestion" data-colors="Pink, Purple, Orange">Bright</button>
                                        </div>
                                        <div class="mt-2">
                                            <button type="button" class="btn btn-sm btn-outline-primary" id="custom-color-picker">
                                                <i class="ti ti-palette"></i> Color Picker
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-secondary" id="clear-colors">
                                                <i class="ti ti-x"></i> Clear
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <!-- Color Preview -->
                                    <div id="color-preview" class="mt-2" style="display: none;">
                                        <small class="text-muted d-block mb-2">Selected Colors:</small>
                                        <div id="color-swatches" class="d-flex flex-wrap gap-1 mb-2"></div>
                                        <div class="color-preview-actions">
                                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="copyColorsToClipboard()">
                                                <i class="ti ti-copy"></i> Copy
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Shipping -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="free_shipping" name="free_shipping" value="1" 
                                               {{ old('free_shipping') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="free_shipping">
                                            Free Shipping
                                        </label>
                                        <div class="form-text">Enable free shipping for this product</div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="shipping_cost" class="form-label">Shipping Cost (৳)</label>
                                    <input type="number" class="form-control @error('shipping_cost') is-invalid @enderror" 
                                           id="shipping_cost" name="shipping_cost" value="{{ old('shipping_cost') }}" step="0.01" min="0" placeholder="0.00">
                                    @error('shipping_cost')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Only applicable if free shipping is disabled</div>
                                </div>
                            </div>

                            <!-- Warranty -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="warranty_period" class="form-label">Warranty Period</label>
                                    <input type="text" class="form-control @error('warranty_period') is-invalid @enderror" 
                                           id="warranty_period" name="warranty_period" value="{{ old('warranty_period') }}" placeholder="e.g., 1 Year, 6 Months, Lifetime">
                                    @error('warranty_period')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="warranty_terms" class="form-label">Warranty Terms</label>
                                    <textarea class="form-control @error('warranty_terms') is-invalid @enderror" 
                                              id="warranty_terms" name="warranty_terms" rows="3" placeholder="Warranty terms and conditions">{{ old('warranty_terms') }}</textarea>
                                    @error('warranty_terms')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Actions -->
                    <div class="form-section">
                        <div class="form-section-body">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg fw-medium">
                                    <i class="ti ti-device-floppy me-2"></i>Create Product
                                </button>
                                <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary fw-medium">
                                    <i class="ti ti-arrow-left me-2"></i>Back to Products
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Loading Overlay -->
    <div id="loading-overlay" class="loading-overlay" style="display: none;">
        <div class="loading-content">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <h5 class="mt-3">Saving Product...</h5>
            <p class="text-muted">Please wait while we process your product and images.</p>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    let uploadedImages = [];
    let primaryImageIndex = 0;

    // Auto-generate slug from product name (only if slug is empty)
    $('#name').on('input', function() {
        const name = $(this).val();
        const currentSlug = $('#slug').val();
        
        // Only auto-generate if slug field is empty or was previously auto-generated
        if (!currentSlug || currentSlug === '' || $('#slug').data('auto-generated')) {
            const slug = generateSlugFromName(name);
            $('#slug').val(slug).data('auto-generated', true);
            
            // Check availability if slug is not empty
            if (slug) {
                checkSlugAvailability(slug);
            } else {
                clearSlugFeedback();
            }
        }
        
        // Auto-generate SKU
        if (name && !$('#sku').val()) {
            const sku = name.replace(/[^a-zA-Z0-9]/g, '').toUpperCase().substring(0, 6) 
                + new Date().getFullYear().toString().substr(-2) +
                Math.floor(Math.random() * 100).toString().padStart(2, '0');
            $('#sku').val(sku);
        }
    });

    // Manual slug generation button
    $('#generate-slug-btn').on('click', function() {
        const name = $('#name').val();
        if (name) {
            const slug = generateSlugFromName(name);
            $('#slug').val(slug).data('auto-generated', true);
            checkSlugAvailability(slug);
        } else {
            showToast('Please enter a product name first', 'warning');
        }
    });

    // Mark slug as manually edited when user types in it
    let slugCheckTimeout;
    $('#slug').on('input', function() {
        $(this).data('auto-generated', false);
        
        const slug = $(this).val();
        if (slug) {
            // Clear previous timeout
            clearTimeout(slugCheckTimeout);
            
            // Set new timeout for slug checking
            slugCheckTimeout = setTimeout(() => {
                checkSlugAvailability(slug);
            }, 500);
        } else {
            clearSlugFeedback();
        }
    });

    // Function to check slug availability
    function checkSlugAvailability(slug) {
        if (!slug) return;
        
        const $slugField = $('#slug');
        const $feedback = $slugField.siblings('.slug-feedback');
        
        // Create feedback element if doesn't exist
        if ($feedback.length === 0) {
            $slugField.parent().append('<div class="slug-feedback"></div>');
        }
        
        // Show loading
        $slugField.parent().find('.slug-feedback').html('<small class="text-muted"><i class="fas fa-spinner fa-spin"></i> Checking availability...</small>');
        
        $.ajax({
            url: '{{ route('admin.products.check-slug') }}',
            method: 'GET',
            data: { slug: slug },
            success: function(response) {
                const $feedbackEl = $slugField.parent().find('.slug-feedback');
                if (response.available) {
                    $feedbackEl.html('<small class="text-success"><i class="fas fa-check-circle"></i> ' + response.message + '</small>');
                    $slugField.removeClass('is-invalid').addClass('is-valid');
                } else {
                    $feedbackEl.html('<small class="text-danger"><i class="fas fa-exclamation-circle"></i> ' + response.message + '</small>');
                    $slugField.removeClass('is-valid').addClass('is-invalid');
                }
            },
            error: function() {
                const $feedbackEl = $slugField.parent().find('.slug-feedback');
                $feedbackEl.html('<small class="text-danger"><i class="fas fa-exclamation-triangle"></i> Error checking slug availability</small>');
            }
        });
    }

    // Function to clear slug feedback
    function clearSlugFeedback() {
        $('#slug').removeClass('is-valid is-invalid').parent().find('.slug-feedback').empty();
    }

    // Function to show toast notifications
    function showToast(message, type = 'info') {
        // Create toast if it doesn't exist
        if ($('#toast-container').length === 0) {
            $('body').append('<div id="toast-container" class="position-fixed top-0 end-0 p-3" style="z-index: 9999;"></div>');
        }
        
        const toastId = 'toast-' + Date.now();
        const toast = `
            <div id="${toastId}" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header">
                    <i class="fas fa-${type === 'warning' ? 'exclamation-triangle text-warning' : 'info-circle text-info'}"></i>
                    <strong class="ms-2 me-auto">Notification</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">${message}</div>
            </div>
        `;
        
        $('#toast-container').append(toast);
        const toastElement = new bootstrap.Toast(document.getElementById(toastId));
        toastElement.show();
        
        // Remove toast element after it's hidden
        document.getElementById(toastId).addEventListener('hidden.bs.toast', function() {
            this.remove();
        });
    }

    // Function to generate slug from name
    function generateSlugFromName(name) {
        return name.toLowerCase()
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .trim('-');
    }

    // Handle free shipping toggle
    $('#free_shipping').on('change', function() {
        const isChecked = $(this).is(':checked');
        const shippingCostField = $('#shipping_cost');
        
        if (isChecked) {
            shippingCostField.val('0').prop('disabled', true);
        } else {
            shippingCostField.prop('disabled', false);
        }
    });

    // Initialize free shipping on page load
    if ($('#free_shipping').is(':checked')) {
        $('#shipping_cost').val('0').prop('disabled', true);
    }

    // Add click events for better user experience and reduce confusion
    
    // Show tooltip on slug field click
    $('#slug').on('click focus', function() {
        if (!$(this).val()) {
            showTooltip($(this), 'Slug will be auto-generated from product name. You can also customize it manually (e.g., "ami-vat-khai")');
        }
    });

    // Show tooltip on SKU field click
    $('#sku').on('click focus', function() {
        if (!$(this).val()) {
            showTooltip($(this), 'SKU will be auto-generated when you enter the product name. You can also enter it manually.');
        }
    });

    // Show price calculation helper on PV points click
    $('#pv_points').on('click focus', function() {
        const price = $('#price').val();
        if (price && !$(this).val()) {
            const suggestedPV = (price * 0.7).toFixed(2);
            showTooltip($(this), `Suggested PV Points: ${suggestedPV} (70% of price: ৳${price})`);
            $(this).attr('placeholder', suggestedPV);
        }
    });

    // Show BV points helper
    $('#bv_points').on('click focus', function() {
        const pvPoints = $('#pv_points').val();
        const price = $('#price').val();
        if ((pvPoints || price) && !$(this).val()) {
            const suggestedBV = pvPoints || (price * 0.7).toFixed(2);
            showTooltip($(this), `Suggested BV Points: ${suggestedBV} (usually same as PV Points)`);
            $(this).attr('placeholder', suggestedBV);
        }
    });

    // Show category selection help
    $('#category_id').on('click', function() {
        if ($(this).val() === '') {
            showTooltip($(this), 'Please select a category for your product. This helps customers find your product easily.');
        }
    });

    // Show vendor selection help
    $('#vendor_id').on('click', function() {
        if ($(this).val() === '') {
            showTooltip($(this), 'Select the vendor who will supply this product. This is required for order processing.');
        }
    });

    // Show unit selection help
    $('#unit_id').on('click', function() {
        showTooltip($(this), 'Select the unit of measurement for this product (e.g., Piece, Kilogram, Meter)');
    });

    // Show shipping cost help when clicked
    $('#shipping_cost').on('click focus', function() {
        if ($('#free_shipping').is(':checked')) {
            showTooltip($(this), 'Shipping cost is disabled because "Free Shipping" is enabled. Uncheck "Free Shipping" to set a cost.');
        } else {
            showTooltip($(this), 'Enter the shipping cost for this product in Taka (৳). Leave 0 for free shipping.');
        }
    });

    // Show weight help
    $('#weight, #shipping_weight').on('click focus', function() {
        const fieldName = $(this).attr('id') === 'weight' ? 'actual weight' : 'shipping weight';
        showTooltip($(this), `Enter the ${fieldName} of the product in kilograms. This helps calculate shipping costs.`);
    });

    // Show dimensions help
    $('#length, #width, #height').on('click focus', function() {
        showTooltip($(this), 'Enter product dimensions in centimeters. This helps customers understand the product size.');
    });

    // Show condition help
    $('#condition').on('click', function() {
        showTooltip($(this), 'Select the condition of the product:\n• New: Brand new product\n• Used: Previously used but functional\n• Refurbished: Restored to working condition\n• Damaged: Has some defects');
    });

    // Show starter kit tier help
    $('#starter_kit_tier').on('click', function() {
        if ($('#is_starter_kit').is(':checked')) {
            showTooltip($(this), 'Select the tier level for this starter kit:\n• Basic: ৳2,000\n• Standard: ৳5,000\n• Premium: ৳10,000\n• Platinum: ৳20,000');
        }
    });

    // Auto-fill price based on starter kit tier selection
    $('#starter_kit_tier').on('change', function() {
        const tier = $(this).val();
        const priceField = $('#price');
        
        if (tier && !priceField.val()) {
            let suggestedPrice = '';
            switch(tier) {
                case 'basic':
                    suggestedPrice = '2000';
                    break;
                case 'standard':
                    suggestedPrice = '5000';
                    break;
                case 'premium':
                    suggestedPrice = '10000';
                    break;
                case 'platinum':
                    suggestedPrice = '20000';
                    break;
            }
            
            if (suggestedPrice && confirm(`Would you like to set the price to ৳${suggestedPrice} based on the ${tier} tier?`)) {
                priceField.val(suggestedPrice).trigger('input');
            }
        }
    });

    // Show meta fields help
    $('#meta_title').on('click focus', function() {
        const productName = $('#name').val();
        if (productName && !$(this).val()) {
            showTooltip($(this), `Suggested: "${productName} - Buy Online | Your Store Name". Keep it under 60 characters for better SEO.`);
        }
    });

    // Auto-suggest meta description
    $('#meta_description').on('click focus', function() {
        const productName = $('#name').val();
        const shortDesc = $('#short_description').val();
        if ((productName || shortDesc) && !$(this).val()) {
            const suggestion = shortDesc || `Buy ${productName} online with fast delivery and best price.`;
            showTooltip($(this), `Suggested: "${suggestion}". Keep it between 150-160 characters.`);
        }
    });

    // Function to show helpful tooltips
    function showTooltip(element, message) {
        // Remove existing tooltip
        $('.custom-tooltip').remove();
        
        const tooltip = $(`
            <div class="custom-tooltip" style="
                position: absolute;
                background: #333;
                color: white;
                padding: 8px 12px;
                border-radius: 4px;
                font-size: 12px;
                max-width: 300px;
                z-index: 9999;
                white-space: pre-line;
                box-shadow: 0 2px 8px rgba(0,0,0,0.3);
            ">${message}</div>
        `);
        
        $('body').append(tooltip);
        
        const offset = element.offset();
        tooltip.css({
            top: offset.top - tooltip.outerHeight() - 8,
            left: offset.left
        });
        
        // Auto-hide after 5 seconds
        setTimeout(() => {
            tooltip.fadeOut(300, function() {
                $(this).remove();
            });
        }, 5000);
        
        // Hide on click elsewhere
        $(document).on('click.tooltip', function(e) {
            if (!$(e.target).is(element)) {
                tooltip.remove();
                $(document).off('click.tooltip');
            }
        });
    }

    // Add visual feedback for required fields
    $('input[required], select[required]').on('blur', function() {
        if (!$(this).val()) {
            $(this).addClass('border-warning');
            showTooltip($(this), 'This field is required!');
        } else {
            $(this).removeClass('border-warning');
        }
    });

    // Show image upload help
    $('#image-upload-area').on('click', function() {
        if ($('#image-preview-grid').children().length === 0) {
            showTooltip($(this), 'Click to upload product images. First image will be the main product image. You can upload multiple images and reorder them.');
        }
    });

    // Color Options Suggestions
    $('.color-suggestion').on('click', function() {
        const colors = $(this).data('colors');
        const currentColors = $('#color_options').val();
        
        if (currentColors.trim() === '') {
            $('#color_options').val(colors);
        } else {
            $('#color_options').val(currentColors + ', ' + colors);
        }
        
        // Trigger input event to update preview
        $('#color_options').trigger('input');
        showToast('Colors added successfully!', 'success');
    });

    // Clear colors
    $('#clear-colors').on('click', function() {
        $('#color_options').val('');
        // Trigger input event to update preview
        $('#color_options').trigger('input');
        showToast('Colors cleared', 'info');
    });

    // Custom color picker
    $('#custom-color-picker').on('click', function() {
        showColorPickerModal();
    });

    // Update color preview when text changes
    $('#color_options').on('input', function() {
        updateColorPreview();
    });

    // Function to update color preview
    function updateColorPreview() {
        const colorsText = $('#color_options').val();
        const colorPreview = $('#color-preview');
        const colorSwatches = $('#color-swatches');
        
        if (!colorsText || colorsText.trim() === '') {
            colorPreview.hide();
            return;
        }
        
        const colors = colorsText.split(',').map(c => c.trim()).filter(c => c !== '');
        
        // International Standard Color Mapping (ISO 3864 & Web Standards)
        const standardColors = {
            // Basic Colors (Primary)
            'black': '#000000', 'white': '#FFFFFF', 'gray': '#808080', 'grey': '#808080',
            'red': '#FF0000', 'green': '#008000', 'blue': '#0000FF',
            
            // Standard Web Colors
            'navy': '#000080', 'maroon': '#800000', 'olive': '#808000',
            'lime': '#00FF00', 'aqua': '#00FFFF', 'teal': '#008080',
            'silver': '#C0C0C0', 'fuchsia': '#FF00FF', 'purple': '#800080',
            'yellow': '#FFFF00', 'orange': '#FFA500',
            
            // Natural Colors
            'brown': '#A52A2A', 'beige': '#F5F5DC', 'tan': '#D2B48C',
            'khaki': '#F0E68C', 'coral': '#FF7F50', 'salmon': '#FA8072',
            
            // Professional Colors
            'pink': '#FFC0CB', 'gold': '#FFD700', 'turquoise': '#40E0D0',
            'violet': '#8A2BE2', 'indigo': '#4B0082', 'cyan': '#00FFFF'
        };
        
        colorSwatches.empty();
        
        if (colors.length === 0) {
            colorPreview.hide();
            return;
        }
        
        colors.forEach((color, index) => {
            const colorLower = color.toLowerCase().trim();
            let hexColor = standardColors[colorLower];
            
            // If color not found in standard colors, check if it's already a hex color
            if (!hexColor) {
                if (color.match(/^#[0-9A-Fa-f]{6}$/)) {
                    hexColor = color;
                } else {
                    hexColor = '#CCCCCC'; // Standard gray for unknown colors
                }
            }
            
            const swatch = $(`
                <div class="color-swatch-container d-inline-block me-1 mb-1" title="${color}" data-color="${color}">
                    <span class="color-swatch" style="
                        display: inline-block;
                        width: 20px;
                        height: 20px;
                        background-color: ${hexColor};
                        border: 1px solid #adb5bd;
                        border-radius: 3px;
                        cursor: pointer;
                        transition: border-color 0.15s ease-in-out;
                    "></span>
                    <small class="color-name d-block text-center">${color}</small>
                </div>
            `);
            
            // Add special styling for light colors
            if (hexColor === '#FFFFFF' || hexColor === '#F5F5DC' || hexColor === '#CCCCCC') {
                swatch.find('.color-swatch').css('border-color', '#6c757d');
            }
            
            colorSwatches.append(swatch);
        });
        
        // Show the preview container
        colorPreview.show();
        
        // Add a summary
        const summaryText = `${colors.length} color${colors.length > 1 ? 's' : ''} selected`;
        colorPreview.find('small').first().text(summaryText);
    }

    // Function to show color picker modal
    function showColorPickerModal() {
        const modal = $(`
            <div class="modal fade" id="colorPickerModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Select Colors</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Popular Colors</h6>
                                    <div class="popular-colors d-flex flex-wrap gap-2">
                                        ${generateColorButtons([
                                            {name: 'Red', hex: '#FF0000'},
                                            {name: 'Blue', hex: '#0000FF'},
                                            {name: 'Green', hex: '#008000'},
                                            {name: 'Black', hex: '#000000'},
                                            {name: 'White', hex: '#FFFFFF'},
                                            {name: 'Yellow', hex: '#FFFF00'},
                                            {name: 'Pink', hex: '#FFC0CB'},
                                            {name: 'Purple', hex: '#800080'},
                                            {name: 'Orange', hex: '#FFA500'},
                                            {name: 'Brown', hex: '#A52A2A'},
                                            {name: 'Grey', hex: '#808080'},
                                            {name: 'Navy', hex: '#000080'}
                                        ])}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h6>Fashion Colors</h6>
                                    <div class="fashion-colors d-flex flex-wrap gap-2">
                                        ${generateColorButtons([
                                            {name: 'Rose Gold', hex: '#E8B4A0'},
                                            {name: 'Champagne', hex: '#F7E7CE'},
                                            {name: 'Burgundy', hex: '#800020'},
                                            {name: 'Emerald', hex: '#50C878'},
                                            {name: 'Sapphire', hex: '#0F52BA'},
                                            {name: 'Coral', hex: '#FF7F50'},
                                            {name: 'Mint', hex: '#98FB98'},
                                            {name: 'Lavender', hex: '#E6E6FA'},
                                            {name: 'Gold', hex: '#FFD700'},
                                            {name: 'Silver', hex: '#C0C0C0'},
                                            {name: 'Copper', hex: '#B87333'},
                                            {name: 'Platinum', hex: '#E5E4E2'}
                                        ])}
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="selected-colors-display">
                                <h6>Selected Colors: <span id="selected-colors-count">0</span></h6>
                                <div id="selected-colors-list" class="text-muted">None selected</div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary" id="apply-selected-colors">Apply Colors</button>
                        </div>
                    </div>
                </div>
            </div>
        `);
        
        $('body').append(modal);
        
        let selectedColors = [];
        
        // Handle color selection
        modal.find('.color-btn').on('click', function() {
            const colorName = $(this).data('color');
            
            if (selectedColors.includes(colorName)) {
                selectedColors = selectedColors.filter(c => c !== colorName);
                $(this).removeClass('selected');
            } else {
                selectedColors.push(colorName);
                $(this).addClass('selected');
            }
            
            updateSelectedColorsDisplay();
        });
        
        function updateSelectedColorsDisplay() {
            $('#selected-colors-count').text(selectedColors.length);
            $('#selected-colors-list').text(selectedColors.length > 0 ? selectedColors.join(', ') : 'None selected');
        }
        
        // Apply colors
        modal.find('#apply-selected-colors').on('click', function() {
            if (selectedColors.length > 0) {
                const currentColors = $('#color_options').val();
                const newColors = selectedColors.join(', ');
                
                if (currentColors.trim() === '') {
                    $('#color_options').val(newColors);
                } else {
                    $('#color_options').val(currentColors + ', ' + newColors);
                }
                
                // Trigger input event to update preview
                $('#color_options').trigger('input');
                showToast(`${selectedColors.length} colors added!`, 'success');
            } else {
                showToast('Please select at least one color', 'warning');
                return;
            }
            
            modal.modal('hide');
        });
        
        // Show modal
        const modalInstance = new bootstrap.Modal(modal[0]);
        modalInstance.show();
        
        // Clean up when modal is hidden
        modal.on('hidden.bs.modal', function() {
            modal.remove();
        });
    }

    // Generate color buttons HTML
    function generateColorButtons(colors) {
        return colors.map(color => `
            <button type="button" class="btn btn-outline-secondary btn-sm color-btn" 
                    data-color="${color.name}" 
                    style="border-color: ${color.hex}; position: relative;">
                <span style="display: inline-block; width: 12px; height: 12px; background-color: ${color.hex}; border-radius: 2px; margin-right: 4px; border: 1px solid #ccc;"></span>
                ${color.name}
            </button>
        `).join('');
    }

    // Initialize color preview on page load
    updateColorPreview();

    // Helper function to copy colors to clipboard
    window.copyColorsToClipboard = function() {
        const colorsText = $('#color_options').val();
        if (colorsText) {
            navigator.clipboard.writeText(colorsText).then(function() {
                showToast('Colors copied to clipboard!', 'success');
            }).catch(function() {
                // Fallback for older browsers
                const textArea = document.createElement('textarea');
                textArea.value = colorsText;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
                showToast('Colors copied to clipboard!', 'success');
            });
        } else {
            showToast('No colors to copy', 'warning');
        }
    };

    // Helper function to show color details
    window.showColorDetails = function() {
        const colorsText = $('#color_options').val();
        if (!colorsText) {
            showToast('No colors selected', 'warning');
            return;
        }
        
        const colors = colorsText.split(',').map(c => c.trim()).filter(c => c !== '');
        let details = `Color Details (${colors.length} colors):\n\n`;
        
        colors.forEach((color, index) => {
            details += `${index + 1}. ${color}\n`;
        });
        
        alert(details);
    };

    // Size Chart Suggestions
    $('.size-suggestion').on('click', function() {
        const template = $(this).data('template');
        let sizeChartText = '';
        
        switch(template) {
            case 'clothing':
                sizeChartText = `Size Guide:
S: Chest 36", Waist 30", Length 27"
M: Chest 38", Waist 32", Length 28"
L: Chest 40", Waist 34", Length 29"
XL: Chest 42", Waist 36", Length 30"
XXL: Chest 44", Waist 38", Length 31"

Model is wearing size M (Height: 5'8", Chest: 38")
Fabric has slight stretch. Size up for loose fit.`;
                break;
                
            case 'shoes':
                sizeChartText = `Shoe Size Chart:
US 6 = UK 5.5 = EU 38 = CM 23
US 7 = UK 6.5 = EU 39 = CM 24
US 8 = UK 7.5 = EU 40 = CM 25
US 9 = UK 8.5 = EU 41 = CM 26
US 10 = UK 9.5 = EU 42 = CM 27

Half sizes available. True to size fit.
For wide feet, order half size up.`;
                break;
                
            case 'electronics':
                sizeChartText = `Technical Specifications:
Dimensions: L x W x H (in cm)
Screen Size: Diagonal measurement
Weight: Net weight without packaging
Battery: Capacity and life
Compatibility: Supported devices/OS
Warranty: Period and coverage terms`;
                break;
        }
        
        $('#size_chart').val(sizeChartText);
        showToast('Size chart template added!', 'success');
    });
    
    // Handle starter kit tier visibility and animations
    $('#is_starter_kit').on('change', function() {
        const isChecked = $(this).is(':checked');
        const tierSection = $('#starter_kit_tier_section');
        const levelSection = $('#starter_kit_level_section');
        
        if (isChecked) {
            tierSection.slideDown(300).addClass('highlight');
            levelSection.slideDown(300);
            tierSection.find('select').focus();
            showToast('Starter Kit enabled! Please select a tier level.', 'info');
        } else {
            tierSection.slideUp(300).removeClass('highlight');
            levelSection.slideUp(300);
            $('#starter_kit_tier').val('');
            $('#starter_kit_level').val('');
            // Reset price and points when unchecking starter kit
            $('#price, #pv_points, #bv_points').val('');
            showToast('Starter Kit disabled.', 'info');
        }
    });
    
    // Auto-populate price based on starter kit tier with enhanced confirmation
    $('#starter_kit_tier').on('change', function() {
        var tier = $(this).val();
        var suggestedPrice = '';
        var tierName = '';
        
        switch(tier) {
            case 'basic':
                suggestedPrice = '2000';
                tierName = 'Basic';
                break;
            case 'standard':
                suggestedPrice = '5000';
                tierName = 'Standard';
                break;
            case 'premium':
                suggestedPrice = '10000';
                tierName = 'Premium';
                break;
            case 'platinum':
                suggestedPrice = '20000';
                tierName = 'Platinum';
                break;
        }
        
        if (suggestedPrice) {
            // Visual feedback with styling
            const confirmMessage = `Set ${tierName} package price to ${suggestedPrice} TK?\n\nThis will also auto-calculate PV and BV points.`;
            
            if (confirm(confirmMessage)) {
                $('#price').val(suggestedPrice).addClass('is-valid');
                
                // Auto-calculate PV and BV points based on price with visual feedback
                var pv = Math.round(suggestedPrice * 0.7);
                var bv = Math.round(suggestedPrice * 0.5);
                $('#pv_points').val(pv).addClass('is-valid');
                $('#bv_points').val(bv).addClass('is-valid');
                
                // Remove the valid class after a short delay
                setTimeout(function() {
                    $('#price, #pv_points, #bv_points').removeClass('is-valid');
                }, 2000);
                
                // Show success toast
                showToast('Price Set', `${tierName} package configured with ${suggestedPrice} TK and points calculated.`, 'success');
            }
        }
    });

    // MLM Settings Toggle
    $('#generates_commission').on('change', function() {
        const isChecked = $(this).is(':checked');
        $('.mlm-settings').toggle(isChecked);
        
        if (isChecked) {
            $('.mlm-settings').slideDown();
        } else {
            $('.mlm-settings').slideUp();
        }
    });

    // Commission type change handler - removed since this field doesn't exist in the form
    // Future enhancement: can add commission type selector if needed

    // Volume calculations - Auto-suggest based on price
    $('#price').on('input', function() {
        const price = parseFloat($(this).val()) || 0;
        
        if (price > 0 && $('#generates_commission').is(':checked')) {
            // Auto-suggest PV (usually 70% of price)
            if (!$('#pv_points').val()) {
                $('#pv_points').val((price * 0.7).toFixed(2));
            }
            
            // Auto-suggest BV (usually 50% of price)
            if (!$('#bv_points').val()) {
                $('#bv_points').val((price * 0.5).toFixed(2));
            }
        }
    });

    // Point calculation - simplified for the actual form fields
    $('#pv_points, #bv_points').on('input', function() {
        // Basic validation to ensure points are not negative
        const value = parseFloat($(this).val()) || 0;
        if (value < 0) {
            $(this).val(0);
        }
    });

    // Category change handler - Load subcategories
    $('#category_id').on('change', function() {
        const categoryId = $(this).val();
        const subcategorySelect = $('#subcategory_id');
        
        // Reset subcategory dropdown
        subcategorySelect.html('<option value="">Select Subcategory (Optional)</option>').prop('disabled', true);
        
        if (categoryId) {
            // Show loading
            subcategorySelect.html('<option value="">Loading subcategories...</option>');
            
            // Fetch subcategories
            $.ajax({
                url: `/admin/products/subcategories/${categoryId}`,
                type: 'GET',
                success: function(subcategories) {
                    subcategorySelect.html('<option value="">Select Subcategory (Optional)</option>');
                    
                    if (subcategories.length > 0) {
                        subcategories.forEach(function(subcategory) {
                            subcategorySelect.append(`<option value="${subcategory.id}">${subcategory.name}</option>`);
                        });
                        subcategorySelect.prop('disabled', false);
                    } else {
                        subcategorySelect.html('<option value="">No subcategories available</option>');
                    }
                },
                error: function() {
                    subcategorySelect.html('<option value="">Error loading subcategories</option>');
                }
            });
        }
    });

    // Price validation
    $('#price, #sale_price, #cost_price').on('input', function() {
        const price = parseFloat($('#price').val()) || 0;
        const salePrice = parseFloat($('#sale_price').val()) || 0;
        const costPrice = parseFloat($('#cost_price').val()) || 0;
        
        // Validate sale price
        if (salePrice > 0 && salePrice >= price) {
            $('#sale_price').addClass('is-invalid');
            $('#sale_price').siblings('.invalid-feedback').remove();
            $('#sale_price').after('<div class="invalid-feedback">Sale price must be less than regular price</div>');
        } else {
            $('#sale_price').removeClass('is-invalid');
            $('#sale_price').siblings('.invalid-feedback').remove();
        }
        
        // Validate cost vs selling price
        if (costPrice > 0 && price > 0 && costPrice >= price) {
            $(this).addClass('is-invalid');
            $(this).siblings('.invalid-feedback').remove();
            $(this).after('<div class="invalid-feedback">Cost price should be less than selling price</div>');
        } else {
            $(this).removeClass('is-invalid');
            $(this).siblings('.invalid-feedback').remove();
        }
    });

    // Image Upload Handling
    $('#image-input').on('change', function(e) {
        const files = Array.from(e.target.files);
        files.forEach(function(file, index) {
            if (validateImageFile(file)) {
                previewImage(file, uploadedImages.length + index);
            }
        });
    });

    // Drag and drop for image upload
    const uploadArea = $('#image-upload-area');
    
    uploadArea.on('dragover', function(e) {
        e.preventDefault();
        $(this).addClass('drag-over');
    });
    
    uploadArea.on('dragleave', function(e) {
        e.preventDefault();
        $(this).removeClass('drag-over');
    });
    
    uploadArea.on('drop', function(e) {
        e.preventDefault();
        $(this).removeClass('drag-over');
        
        const files = Array.from(e.originalEvent.dataTransfer.files);
        files.forEach(function(file, index) {
            if (validateImageFile(file)) {
                previewImage(file, uploadedImages.length + index);
            }
        });
    });

    // Click to upload
    uploadArea.on('click', function() {
        $('#image-input').click();
    });

    function validateImageFile(file) {
        const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        const maxSize = 10 * 1024 * 1024; // 10MB
        
        if (!allowedTypes.includes(file.type)) {
            showToast('Invalid File Type', 'Please select valid image files (JPG, PNG, GIF, WEBP)', 'error');
            return false;
        }
        
        if (file.size > maxSize) {
            showToast('File Too Large', `${file.name} is larger than 10MB`, 'error');
            return false;
        }
        
        return true;
    }

    function previewImage(file, index) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const imageData = {
                file: file,
                url: e.target.result,
                name: file.name,
                size: file.size,
                index: index
            };
            
            uploadedImages.push(imageData);
            renderImagePreview(imageData, index);
            
            // Make first image primary
            if (uploadedImages.length === 1) {
                primaryImageIndex = 0;
            }
        };
        reader.readAsDataURL(file);
    }

    function renderImagePreview(imageData, index) {
        const isPrimary = index === primaryImageIndex;
        const previewHtml = `
            <div class="image-preview-item" data-index="${index}">
                <img src="${imageData.url}" alt="${imageData.name}">
                <div class="image-actions">
                    <button type="button" class="btn-primary-image" onclick="setPrimaryImage(${index})" 
                            title="Set as primary" ${isPrimary ? 'style="display:none;"' : ''}>
                        ★
                    </button>
                    <button type="button" class="btn-remove-image" onclick="removeImage(${index})" title="Remove">
                        ×
                    </button>
                </div>
                ${isPrimary ? '<div class="primary-badge">Primary</div>' : ''}
                <div class="image-info">
                    <small class="text-muted">${imageData.name}</small>
                    <small class="text-muted d-block">${formatFileSize(imageData.size)}</small>
                </div>
            </div>
        `;
        
        $('#image-preview-grid').append(previewHtml);
    }

    window.setPrimaryImage = function(index) {
        primaryImageIndex = index;
        updateImagePreviews();
    };

    window.removeImage = function(index) {
        uploadedImages = uploadedImages.filter(img => img.index !== index);
        if (primaryImageIndex === index && uploadedImages.length > 0) {
            primaryImageIndex = uploadedImages[0].index;
        }
        updateImagePreviews();
    };

    function updateImagePreviews() {
        $('#image-preview-grid').empty();
        uploadedImages.forEach(function(imageData) {
            renderImagePreview(imageData, imageData.index);
        });
    }

    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    // Attribute handling
    $('.attribute-toggle').on('change', function() {
        const attributeId = $(this).attr('id').replace('attr_', '');
        const valuesContainer = $(`#attr_values_${attributeId}`);
        
        if ($(this).is(':checked')) {
            valuesContainer.show();
            loadAttributeValues(attributeId);
        } else {
            valuesContainer.hide();
        }
    });

    function loadAttributeValues(attributeId) {
        $.ajax({
            url: `/admin/attributes/${attributeId}/values`,
            type: 'GET',
            success: function(response) {
                if (response.success && response.values) {
                    renderAttributeValues(attributeId, response.values);
                }
            },
            error: function() {
                console.error('Failed to load attribute values');
            }
        });
    }

    function renderAttributeValues(attributeId, values) {
        const container = $(`#attr_values_${attributeId}`);
        let html = '<div class="row">';
        
        values.forEach(function(value) {
            html += `
                <div class="col-md-6 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" 
                               name="attributes[${attributeId}][values][]" 
                               value="${value.id}" 
                               id="attr_${attributeId}_val_${value.id}">
                        <label class="form-check-label" for="attr_${attributeId}_val_${value.id}">
                            ${value.value}
                            ${value.color_code ? `<span class="color-preview" style="background-color: ${value.color_code}"></span>` : ''}
                        </label>
                    </div>
                </div>
            `;
        });
        
        html += '</div>';
        container.html(html);
    }

    // Initialize Select2 for tags
    if ($('#tags').length) {
        $('#tags').select2({
            theme: 'bootstrap-5',
            placeholder: 'Select or add tags...',
            allowClear: true,
            tags: true,
            tokenSeparators: [',', ' '],
            createTag: function (params) {
                var term = $.trim(params.term);
                if (term === '') {
                    return null;
                }
                // Check if the tag already exists
                var existingOption = $('#tags option').filter(function() {
                    return $(this).text().toLowerCase() === term.toLowerCase();
                });
                
                if (existingOption.length > 0) {
                    return null; // Tag already exists
                }
                
                return {
                    id: term,
                    text: term,
                    newTag: true
                };
            },
            templateResult: function (data) {
                if (data.loading) return data.text;
                if (data.newTag) {
                    return $('<span class="text-success"><i class="ti ti-plus me-2"></i>' + data.text + ' (create new)</span>');
                }
                return $('<span><i class="ti ti-tag me-2"></i>' + data.text + '</span>');
            },
            templateSelection: function (data) {
                if (data.newTag) {
                    return data.text;
                }
                return data.text;
            },
            escapeMarkup: function (markup) {
                return markup;
            }
        });
    }

    // Form submission
    $('#productForm').on('submit', function(e) {
        e.preventDefault();
        
        // Validate images (optional for now)
        // if (uploadedImages.length === 0) {
        //     showToast('Missing Images', 'Please upload at least one product image', 'error');
        //     return false;
        // }
        
        // Show loading
        $('#loading-overlay').show();
        
        // Create FormData with all form fields and images
        const formData = new FormData(this);
        
        // Remove the default file input and add our processed images (if any)
        formData.delete('images[]');
        if (uploadedImages.length > 0) {
            uploadedImages.forEach(function(imageData, index) {
                formData.append('images[]', imageData.file);
            });
            // Add primary image index only if there are images
            formData.append('primary_image_index', primaryImageIndex);
        }
        
        // Submit form
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                $('#loading-overlay').hide();
                showToast('Success', 'Product created successfully!', 'success');
                
                // Trigger cache refresh for frontend
                if (typeof localStorage !== 'undefined') {
                    localStorage.setItem('products_updated', Date.now());
                }
                
                // Clear service worker cache if available
                if ('serviceWorker' in navigator && navigator.serviceWorker.controller) {
                    navigator.serviceWorker.controller.postMessage({
                        type: 'PRODUCT_UPDATED'
                    });
                }
                
                setTimeout(function() {
                    window.location.href = '{{ route("admin.products.index") }}';
                }, 1500);
            },
            error: function(xhr) {
                $('#loading-overlay').hide();
                
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    let errorMessages = [];
                    Object.keys(errors).forEach(function(key) {
                        errorMessages.push(`${key}: ${errors[key][0]}`);
                    });
                    showToast('Validation Error', errorMessages.join('<br>'), 'error');
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    showToast('Error', xhr.responseJSON.message, 'error');
                } else {
                    showToast('Error', 'Failed to create product. Please try again.', 'error');
                }
                
                console.error('Product creation error:', xhr);
            }
        });
    });

    // Save as Draft
    $('#save-draft').on('click', function() {
        $('#status').val('draft');
        $('#productForm').submit();
    });

    function showToast(title, message, type) {
        const toastHtml = `
            <div class="toast align-items-center text-white bg-${type === 'success' ? 'success' : type === 'warning' ? 'warning' : 'danger'} border-0" role="alert">
                <div class="d-flex">
                    <div class="toast-body">
                        <strong>${title}:</strong> ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        `;
        
        // Create toast container if it doesn't exist
        if ($('.toast-container').length === 0) {
            $('body').append('<div class="toast-container position-fixed top-0 end-0 p-3"></div>');
        }
        
        $('.toast-container').append(toastHtml);
        $('.toast').last().toast('show');
    }

    // Character counter for descriptions
    $('#description, #short_description').on('input', function() {
        const maxLength = $(this).attr('id') === 'short_description' ? 500 : 10000;
        const currentLength = $(this).val().length;
        
        let counter = $(this).siblings('.char-counter');
        if (counter.length === 0) {
            counter = $('<small class="char-counter text-muted"></small>');
            $(this).after(counter);
        }
        
        counter.text(`${currentLength}/${maxLength} characters`);
        
        if (currentLength > maxLength * 0.9) {
            counter.removeClass('text-muted').addClass('text-warning');
        } else {
            counter.removeClass('text-warning').addClass('text-muted');
        }
    });
    
    // Image resize functions
    window.openResizeOptions = function() {
        const panel = document.getElementById('resize-options-panel');
        panel.style.display = panel.style.display === 'none' ? 'block' : 'none';
    };

    window.resizeUploadedImages = function() {
        const fileInput = document.getElementById('image-input');
        const files = fileInput.files;
        
        if (files.length === 0) {
            showToast('Please select images first', 'warning');
            return;
        }
        
        const width = document.getElementById('resize-width').value;
        const height = document.getElementById('resize-height').value;
        const quality = document.getElementById('resize-quality').value;
        const maintainRatio = document.getElementById('maintain-ratio').checked;
        
        const formData = new FormData();
        for (let i = 0; i < files.length; i++) {
            formData.append('images[]', files[i]);
        }
        formData.append('width', width);
        formData.append('height', height);
        formData.append('quality', quality);
        formData.append('maintain_ratio', maintainRatio ? '1' : '0');
        formData.append('folder', 'products/resized');
        formData.append('_token', '{{ csrf_token() }}');
        
        showToast('Resizing images...', 'info');
        
        fetch('/admin/image-upload/resize', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(`Successfully resized ${data.count} images!`, 'success');
                console.log('Resized images:', data.data);
            } else {
                showToast('Error: ' + data.message, 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Failed to resize images', 'danger');
        });
    };

    window.generateThumbnails = function() {
        const fileInput = document.getElementById('image-input');
        const files = fileInput.files;
        
        if (files.length === 0) {
            showToast('Please select images first', 'warning');
            return;
        }
        
        const formData = new FormData();
        for (let i = 0; i < files.length; i++) {
            formData.append('images[]', files[i]);
        }
        formData.append('sizes[]', '150x150');
        formData.append('sizes[]', '300x300');
        formData.append('sizes[]', '500x500');
        formData.append('folder', 'products/thumbnails');
        formData.append('_token', '{{ csrf_token() }}');
        
        showToast('Generating thumbnails...', 'info');
        
        fetch('/admin/image-upload/thumbnails', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(`Successfully generated ${data.count} thumbnails!`, 'success');
                console.log('Thumbnails:', data.data);
            } else {
                showToast('Error: ' + data.message, 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Failed to generate thumbnails', 'danger');
        });
    };

    window.optimizeImages = function() {
        const fileInput = document.getElementById('image-input');
        const files = fileInput.files;
        
        if (files.length === 0) {
            showToast('Please select images first', 'warning');
            return;
        }
        
        const quality = document.getElementById('resize-quality').value;
        
        const formData = new FormData();
        for (let i = 0; i < files.length; i++) {
            formData.append('images[]', files[i]);
        }
        formData.append('quality', quality);
        formData.append('folder', 'products/optimized');
        formData.append('_token', '{{ csrf_token() }}');
        
        showToast('Optimizing images...', 'info');
        
        fetch('/admin/image-upload/optimize', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(`Successfully optimized ${data.count} images!`, 'success');
                console.log('Optimized images:', data.data);
                
                // Show savings info
                const totalSavings = data.data.reduce((sum, img) => sum + (img.savings || 0), 0);
                const savingsKB = Math.round(totalSavings / 1024);
                showToast(`Space saved: ${savingsKB}KB`, 'info');
            } else {
                showToast('Error: ' + data.message, 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Failed to optimize images', 'danger');
        });
    };

    // Add progress indicator for form completion
    function updateFormProgress() {
        const requiredFields = $('input[required], select[required]');
        const filledFields = requiredFields.filter(function() {
            return $(this).val() && $(this).val().trim() !== '';
        });
        
        const progress = Math.round((filledFields.length / requiredFields.length) * 100);
        
        // Create or update progress bar
        if ($('.form-progress').length === 0) {
            $('.page-title').after(`
                <div class="form-progress mt-2">
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: ${progress}%"></div>
                    </div>
                    <small class="text-muted">Form completion: ${progress}% (${filledFields.length}/${requiredFields.length} required fields)</small>
                </div>
            `);
        } else {
            $('.progress-bar').css('width', progress + '%');
            $('.form-progress small').text(`Form completion: ${progress}% (${filledFields.length}/${requiredFields.length} required fields)`);
        }
    }

    // Update progress on field changes
    $(document).on('input change', 'input[required], select[required]', function() {
        updateFormProgress();
    });

    // Initial progress check
    updateFormProgress();

    // Add keyboard shortcuts
    $(document).on('keydown', function(e) {
        // Ctrl + S to save (prevent default and show toast)
        if (e.ctrlKey && e.key === 's') {
            e.preventDefault();
            showToast('Use the "Create Product" button to save', 'info');
            $('button[type="submit"]').focus().addClass('btn-pulse');
            setTimeout(() => {
                $('button[type="submit"]').removeClass('btn-pulse');
            }, 1000);
        }
        
        // Ctrl + G to generate slug
        if (e.ctrlKey && e.key === 'g') {
            e.preventDefault();
            $('#generate-slug-btn').click();
        }
    });

    // Add CSS for pulse effect
    $('head').append(`
        <style>
            .btn-pulse {
                animation: pulse-btn 0.5s infinite;
            }
            @keyframes pulse-btn {
                0% { transform: scale(1); }
                50% { transform: scale(1.05); }
                100% { transform: scale(1); }
            }
        </style>
    `);
});
</script>
@endpush
