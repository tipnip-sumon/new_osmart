<!-- Advanced Image Upload Component -->
<div class="image-upload-container">
    <div class="upload-area" id="upload-area">
        <div class="upload-content">
            <i class="bx bx-cloud-upload upload-icon"></i>
            <h5>Drop images here or click to upload</h5>
            <p class="text-muted">Supports: JPG, PNG, GIF, WEBP (Max: 10MB each)</p>
            <button type="button" class="btn btn-primary" onclick="document.getElementById('image-input').click()">
                <i class="bx bx-upload"></i> Choose Files
            </button>
        </div>
    </div>
    
    <input type="file" id="image-input" name="images[]" multiple accept="image/*" style="display: none;">
    
    <!-- Upload Progress -->
    <div id="upload-progress" class="upload-progress" style="display: none;">
        <div class="progress mb-2">
            <div class="progress-bar" role="progressbar" style="width: 0%"></div>
        </div>
        <small class="text-muted">Processing images...</small>
    </div>
    
    <!-- Upload Controls -->
    <div id="upload-controls" class="upload-controls mt-3" style="display: none;">
        <div class="d-flex justify-content-between align-items-center">
            <div class="upload-stats">
                <span class="badge bg-primary" id="total-files">0 files</span>
                <span class="badge bg-success" id="uploaded-files">0 uploaded</span>
                <span class="badge bg-warning" id="pending-files">0 pending</span>
            </div>
            <div class="upload-actions">
                <button type="button" class="btn btn-sm btn-primary" onclick="imageUpload.uploadAllPending()" id="upload-all-btn">
                    <i class="bx bx-cloud-upload"></i> Upload All
                </button>
                <button type="button" class="btn btn-sm btn-danger" onclick="imageUpload.clearAll()">
                    <i class="bx bx-trash"></i> Clear All
                </button>
            </div>
        </div>
    </div>
    
    <!-- Image Preview Grid -->
    <div id="image-preview-grid" class="image-preview-grid mt-3"></div>
</div>

<style>
.image-upload-container {
    width: 100%;
}

.upload-area {
    border: 2px dashed #dee2e6;
    border-radius: 8px;
    padding: 40px 20px;
    text-align: center;
    background: #f8f9fa;
    transition: all 0.3s ease;
    cursor: pointer;
}

.upload-area:hover {
    border-color: #007bff;
    background: #e7f3ff;
}

.upload-area.drag-over {
    border-color: #28a745;
    background: #d4edda;
}

.upload-icon {
    font-size: 48px;
    color: #6c757d;
    margin-bottom: 15px;
}

.upload-content h5 {
    margin: 10px 0;
    color: #495057;
}

.image-preview-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 15px;
    margin-top: 20px;
}

.image-preview-item {
    position: relative;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    overflow: hidden;
    background: white;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.image-preview-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.image-preview-item.primary {
    border-color: #007bff;
    box-shadow: 0 0 0 2px rgba(0,123,255,0.25);
}

.image-preview {
    width: 100%;
    height: 150px;
    object-fit: cover;
    display: block;
}

.image-actions {
    position: absolute;
    top: 5px;
    right: 5px;
    display: flex;
    gap: 5px;
}

.image-actions .btn {
    width: 30px;
    height: 30px;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    font-size: 12px;
}

.image-info {
    padding: 10px;
    background: white;
}

.image-filename {
    font-size: 12px;
    margin: 0;
    word-break: break-all;
    color: #6c757d;
}

.image-size {
    font-size: 11px;
    color: #adb5bd;
    margin: 2px 0 0 0;
}

.primary-badge {
    position: absolute;
    top: 5px;
    left: 5px;
    background: #007bff;
    color: white;
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 10px;
    font-weight: 600;
}

.upload-status {
    position: absolute;
    bottom: 45px;
    left: 5px;
    right: 5px;
    background: rgba(255,255,255,0.95);
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 11px;
    text-align: center;
    backdrop-filter: blur(4px);
}

.upload-progress {
    margin-top: 15px;
}

.upload-controls {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    border: 1px solid #dee2e6;
}

.upload-stats .badge {
    margin-right: 8px;
}

.upload-actions .btn {
    margin-left: 8px;
}

.drag-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,123,255,0.1);
    z-index: 9999;
    display: none;
    align-items: center;
    justify-content: center;
}

.drag-overlay.active {
    display: flex;
}

.drag-message {
    background: white;
    padding: 40px;
    border-radius: 8px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
    text-align: center;
}

.image-editor {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.9);
    z-index: 10000;
    display: none;
    align-items: center;
    justify-content: center;
}

.editor-content {
    background: white;
    border-radius: 8px;
    max-width: 90vw;
    max-height: 90vh;
    overflow: hidden;
}

.editor-header {
    padding: 15px 20px;
    border-bottom: 1px solid #dee2e6;
    display: flex;
    justify-content: between;
    align-items: center;
}

.editor-body {
    padding: 20px;
    text-align: center;
}

.editor-tools {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
    justify-content: center;
}

.editor-canvas {
    max-width: 100%;
    max-height: 400px;
    border: 1px solid #dee2e6;
}
</style>

<script>
class AdvancedImageUpload {
    constructor(containerId, options = {}) {
        this.container = document.getElementById(containerId) || document.querySelector('.image-upload-container');
        this.uploadArea = this.container.querySelector('#upload-area');
        this.fileInput = this.container.querySelector('#image-input');
        this.previewGrid = this.container.querySelector('#image-preview-grid');
        this.progressBar = this.container.querySelector('#upload-progress');
        
        this.files = [];
        this.maxFileSize = options.maxFileSize || (10 * 1024 * 1024); // 10MB
        this.maxFiles = options.maxFiles || 10;
        this.allowedTypes = options.allowedTypes || ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        this.uploadUrl = options.uploadUrl || '/admin/images/upload-multiple';
        this.folder = options.folder || 'products';
        this.autoUpload = options.autoUpload || false;
        this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                        document.querySelector('input[name="_token"]')?.value;
        
        if (!this.csrfToken) {
            console.warn('CSRF token not found. Make sure you have <meta name="csrf-token" content="{{ csrf_token() }}"> in your layout head.');
        }
        
        this.init();
    }
    
    init() {
        this.setupEventListeners();
        this.createDragOverlay();
    }
    
    setupEventListeners() {
        // File input change
        this.fileInput.addEventListener('change', (e) => {
            this.handleFiles(e.target.files);
        });
        
        // Upload area click
        this.uploadArea.addEventListener('click', () => {
            this.fileInput.click();
        });
        
        // Drag and drop
        this.uploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            this.uploadArea.classList.add('drag-over');
        });
        
        this.uploadArea.addEventListener('dragleave', (e) => {
            e.preventDefault();
            this.uploadArea.classList.remove('drag-over');
        });
        
        this.uploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            this.uploadArea.classList.remove('drag-over');
            this.handleFiles(e.dataTransfer.files);
        });
        
        // Global drag and drop
        document.addEventListener('dragenter', (e) => {
            if (this.isDraggedFileImage(e)) {
                this.showDragOverlay();
            }
        });
        
        document.addEventListener('dragleave', (e) => {
            if (!e.relatedTarget) {
                this.hideDragOverlay();
            }
        });
        
        document.addEventListener('drop', (e) => {
            e.preventDefault();
            this.hideDragOverlay();
        });
    }
    
    createDragOverlay() {
        const overlay = document.createElement('div');
        overlay.className = 'drag-overlay';
        overlay.innerHTML = `
            <div class="drag-message">
                <i class="bx bx-cloud-upload" style="font-size: 48px; color: #007bff;"></i>
                <h4>Drop images anywhere to upload</h4>
                <p class="text-muted">Supports JPG, PNG, GIF, WEBP</p>
            </div>
        `;
        document.body.appendChild(overlay);
        this.dragOverlay = overlay;
        
        overlay.addEventListener('drop', (e) => {
            e.preventDefault();
            this.hideDragOverlay();
            this.handleFiles(e.dataTransfer.files);
        });
        
        overlay.addEventListener('dragover', (e) => {
            e.preventDefault();
        });
    }
    
    showDragOverlay() {
        this.dragOverlay.classList.add('active');
    }
    
    hideDragOverlay() {
        this.dragOverlay.classList.remove('active');
    }
    
    isDraggedFileImage(e) {
        return Array.from(e.dataTransfer.types).includes('Files');
    }
    
    handleFiles(fileList) {
        const files = Array.from(fileList);
        
        // Check file count limit
        if (this.files.length + files.length > this.maxFiles) {
            this.showError(`Maximum ${this.maxFiles} files allowed. Current: ${this.files.length}, Trying to add: ${files.length}`);
            return;
        }
        
        const validFiles = [];
        files.forEach((file, index) => {
            if (this.validateFile(file)) {
                validFiles.push(file);
            }
        });

        if (validFiles.length === 0) return;

        if (this.autoUpload) {
            this.uploadFiles(validFiles);
        } else {
            validFiles.forEach((file, index) => {
                this.addFile(file, this.files.length === 0 && index === 0);
            });
            this.updatePreview();
        }
    }
    
    async uploadFiles(files) {
        this.showProgress();
        
        const formData = new FormData();
        files.forEach(file => {
            formData.append('images[]', file);
        });
        formData.append('folder', this.folder);
        
        try {
            const response = await fetch(this.uploadUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json'
                },
                body: formData
            });
            
            // Check if response is JSON
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                const htmlResponse = await response.text();
                console.error('Server returned HTML instead of JSON:', htmlResponse);
                throw new Error('Server error: Expected JSON response but got HTML. Check browser console for details.');
            }
            
            const result = await response.json();
            
            if (result.success) {
                // Add uploaded images to files array
                result.data.forEach((imageData, index) => {
                    const fileData = {
                        file: files[index],
                        id: this.generateId(),
                        isPrimary: this.files.length === 0 && index === 0,
                        preview: imageData.urls.medium || imageData.urls.original,
                        uploaded: true,
                        serverData: imageData
                    };
                    this.files.push(fileData);
                });
                
                this.updatePreview();
                this.showSuccess(`${result.count} images uploaded successfully!`);
            } else {
                this.showError(result.message || 'Upload failed');
                if (result.errors) {
                    console.error('Validation errors:', result.errors);
                }
            }
        } catch (error) {
            console.error('Upload error:', error);
            this.showError('Upload failed: ' + error.message);
        } finally {
            this.hideProgress();
        }
    }
    
    showProgress() {
        this.progressBar.style.display = 'block';
        const progressBarInner = this.progressBar.querySelector('.progress-bar');
        progressBarInner.style.width = '100%';
    }
    
    hideProgress() {
        this.progressBar.style.display = 'none';
        const progressBarInner = this.progressBar.querySelector('.progress-bar');
        progressBarInner.style.width = '0%';
    }
    
    validateFile(file) {
        if (!this.allowedTypes.includes(file.type)) {
            this.showError(`Invalid file type: ${file.name}. Only JPG, PNG, GIF, WEBP are allowed.`);
            return false;
        }
        
        if (file.size > this.maxFileSize) {
            this.showError(`File too large: ${file.name}. Maximum size is 10MB.`);
            return false;
        }
        
        return true;
    }
    
    addFile(file, isPrimary = false) {
        const fileData = {
            file: file,
            id: this.generateId(),
            isPrimary: isPrimary && this.files.length === 0,
            preview: null
        };
        
        this.files.push(fileData);
        this.generatePreview(fileData);
    }
    
    generatePreview(fileData) {
        const reader = new FileReader();
        reader.onload = (e) => {
            fileData.preview = e.target.result;
            this.updatePreview();
        };
        reader.readAsDataURL(fileData.file);
    }
    
    updatePreview() {
        this.previewGrid.innerHTML = '';
        
        this.files.forEach((fileData, index) => {
            if (fileData.preview) {
                const previewItem = this.createPreviewItem(fileData, index);
                this.previewGrid.appendChild(previewItem);
            }
        });
        
        this.updateFileInput();
        this.updateStats();
    }
    
    updateStats() {
        const totalFiles = this.files.length;
        const uploadedFiles = this.getUploadedFiles().length;
        const pendingFiles = this.getPendingFiles().length;
        
        const controls = this.container.querySelector('#upload-controls');
        
        if (totalFiles > 0) {
            controls.style.display = 'block';
            
            this.container.querySelector('#total-files').textContent = `${totalFiles} files`;
            this.container.querySelector('#uploaded-files').textContent = `${uploadedFiles} uploaded`;
            this.container.querySelector('#pending-files').textContent = `${pendingFiles} pending`;
            
            const uploadAllBtn = this.container.querySelector('#upload-all-btn');
            uploadAllBtn.style.display = pendingFiles > 0 && !this.autoUpload ? 'inline-block' : 'none';
        } else {
            controls.style.display = 'none';
        }
    }
    
    createPreviewItem(fileData, index) {
        const div = document.createElement('div');
        div.className = `image-preview-item ${fileData.isPrimary ? 'primary' : ''}`;
        
        const uploadStatus = fileData.uploaded ? 
            '<div class="upload-status"><i class="bx bx-check-circle text-success"></i> Uploaded</div>' : 
            '<div class="upload-status"><i class="bx bx-clock text-warning"></i> Pending</div>';
            
        div.innerHTML = `
            <img src="${fileData.preview}" alt="Preview" class="image-preview">
            
            ${fileData.isPrimary ? '<div class="primary-badge">Primary</div>' : ''}
            ${uploadStatus}
            
            <div class="image-actions">
                <button type="button" class="btn btn-sm btn-warning" onclick="imageUpload.editImage(${index})" title="Edit">
                    <i class="bx bx-edit"></i>
                </button>
                <button type="button" class="btn btn-sm btn-success" onclick="imageUpload.setPrimary(${index})" title="Set as Primary">
                    <i class="bx bx-star"></i>
                </button>
                ${!this.autoUpload ? `
                    <button type="button" class="btn btn-sm btn-info" onclick="imageUpload.uploadSingle(${index})" title="Upload">
                        <i class="bx bx-cloud-upload"></i>
                    </button>
                ` : ''}
                <button type="button" class="btn btn-sm btn-danger" onclick="imageUpload.removeImage(${index})" title="Remove">
                    <i class="bx bx-trash"></i>
                </button>
            </div>
            
            <div class="image-info">
                <p class="image-filename">${fileData.file ? fileData.file.name : 'Unknown'}</p>
                <p class="image-size">${fileData.file ? this.formatFileSize(fileData.file.size) : ''}</p>
            </div>
        `;
        
        return div;
    }
    
    removeImage(index) {
        const wasRemovingPrimary = this.files[index].isPrimary;
        this.files.splice(index, 1);
        
        // If primary was removed, make first image primary
        if (wasRemovingPrimary && this.files.length > 0) {
            this.files[0].isPrimary = true;
        }
        
        this.updatePreview();
    }
    
    setPrimary(index) {
        this.files.forEach((file, i) => {
            file.isPrimary = i === index;
        });
        this.updatePreview();
    }
    
    editImage(index) {
        // Simple edit functionality - in real app you'd integrate a full image editor
        const fileData = this.files[index];
        this.showImageEditor(fileData, index);
    }
    
    showImageEditor(fileData, index) {
        const editor = document.createElement('div');
        editor.className = 'image-editor';
        editor.innerHTML = `
            <div class="editor-content">
                <div class="editor-header">
                    <h5>Edit Image: ${fileData.file.name}</h5>
                    <button type="button" class="btn btn-sm btn-secondary" onclick="this.closest('.image-editor').remove()">
                        <i class="bx bx-x"></i> Close
                    </button>
                </div>
                <div class="editor-body">
                    <div class="editor-tools">
                        <button type="button" class="btn btn-sm btn-primary" onclick="imageUpload.rotateImage(${index}, 90)">
                            <i class="bx bx-rotate-right"></i> Rotate Right
                        </button>
                        <button type="button" class="btn btn-sm btn-primary" onclick="imageUpload.rotateImage(${index}, -90)">
                            <i class="bx bx-rotate-left"></i> Rotate Left
                        </button>
                        <button type="button" class="btn btn-sm btn-warning" onclick="imageUpload.cropImage(${index})">
                            <i class="bx bx-crop"></i> Crop
                        </button>
                    </div>
                    <canvas class="editor-canvas" id="editor-canvas-${index}"></canvas>
                </div>
            </div>
        `;
        
        document.body.appendChild(editor);
        editor.style.display = 'flex';
        
        // Load image into canvas
        this.loadImageToCanvas(fileData, `editor-canvas-${index}`);
    }
    
    loadImageToCanvas(fileData, canvasId) {
        const canvas = document.getElementById(canvasId);
        const ctx = canvas.getContext('2d');
        const img = new Image();
        
        img.onload = () => {
            canvas.width = Math.min(img.width, 600);
            canvas.height = (img.height * canvas.width) / img.width;
            ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
        };
        
        img.src = fileData.preview;
    }
    
    rotateImage(index, degrees) {
        // Basic rotation - in real app you'd use a proper image manipulation library
        console.log(`Rotating image ${index} by ${degrees} degrees`);
        this.showSuccess('Image rotation will be implemented with full image editor');
    }
    
    cropImage(index) {
        console.log(`Cropping image ${index}`);
        this.showSuccess('Image cropping will be implemented with full image editor');
    }
    
    updateFileInput() {
        // Create a new DataTransfer object to update the file input
        const dt = new DataTransfer();
        this.files.forEach(fileData => {
            dt.items.add(fileData.file);
        });
        this.fileInput.files = dt.files;
    }
    
    formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
    
    generateId() {
        return Date.now().toString(36) + Math.random().toString(36).substr(2);
    }
    
    showError(message) {
        if (typeof Swal !== 'undefined') {
            Swal.fire('Error', message, 'error');
        } else {
            alert('Error: ' + message);
        }
    }
    
    showSuccess(message) {
        if (typeof Swal !== 'undefined') {
            Swal.fire('Success', message, 'success');
        } else {
            alert('Success: ' + message);
        }
    }
    
    // Public methods for external use
    getFiles() {
        return this.files;
    }
    
    getUploadedFiles() {
        return this.files.filter(f => f.uploaded);
    }
    
    getPendingFiles() {
        return this.files.filter(f => !f.uploaded);
    }
    
    getPrimaryImage() {
        return this.files.find(f => f.isPrimary) || this.files[0] || null;
    }
    
    clearAll() {
        this.files = [];
        this.updatePreview();
    }
    
    async uploadSingle(index) {
        const fileData = this.files[index];
        if (!fileData || !fileData.file || fileData.uploaded) return;
        
        this.showProgress();
        
        const formData = new FormData();
        formData.append('image', fileData.file);
        formData.append('folder', this.folder);
        
        try {
            const response = await fetch('/admin/images/upload-single', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': this.csrfToken
                },
                body: formData
            });
            
            const result = await response.json();
            
            if (result.success) {
                fileData.uploaded = true;
                fileData.serverData = result.data;
                this.updatePreview();
                this.showSuccess('Image uploaded successfully!');
            } else {
                this.showError(result.message || 'Upload failed');
            }
        } catch (error) {
            this.showError('Upload failed: ' + error.message);
        } finally {
            this.hideProgress();
        }
    }
    
    async uploadAllPending() {
        const pendingFiles = this.getPendingFiles();
        if (pendingFiles.length === 0) {
            this.showError('No pending files to upload');
            return;
        }
        
        const files = pendingFiles.map(f => f.file);
        await this.uploadFiles(files);
    }
    
    async deleteServerImage(index) {
        const fileData = this.files[index];
        if (!fileData || !fileData.uploaded || !fileData.serverData) return;
        
        try {
            const response = await fetch('/admin/images/delete', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken
                },
                body: JSON.stringify({
                    image_data: fileData.serverData
                })
            });
            
            const result = await response.json();
            
            if (result.success) {
                this.showSuccess('Image deleted from server successfully!');
            } else {
                this.showError(result.message || 'Failed to delete from server');
            }
        } catch (error) {
            this.showError('Delete failed: ' + error.message);
        }
    }
    
    addExistingImages(images) {
        // For editing existing products - load existing images
        images.forEach((imageData, index) => {
            const fileData = {
                file: null,
                id: imageData.id || this.generateId(),
                isPrimary: imageData.isPrimary || index === 0,
                preview: imageData.url || imageData.urls?.medium || imageData.urls?.original,
                uploaded: true,
                existing: true,
                serverData: imageData
            };
            this.files.push(fileData);
        });
        this.updatePreview();
    }
    
    // Get data for form submission
    getFormData() {
        return {
            uploaded_images: this.getUploadedFiles().map(f => f.serverData),
            primary_image_id: this.getPrimaryImage()?.id,
            total_images: this.files.length
        };
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    if (document.querySelector('.image-upload-container')) {
        // Initialize with default options, can be overridden
        window.imageUpload = new AdvancedImageUpload(null, {
            maxFiles: 10,
            maxFileSize: 10 * 1024 * 1024, // 10MB
            folder: 'products',
            autoUpload: false, // Manual upload for better control
            uploadUrl: '/admin/images/upload-multiple'
        });
    }
});
</script>
