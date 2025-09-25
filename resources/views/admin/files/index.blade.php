@extends('admin.layouts.app')

@section('title', 'File Management')

@section('content')
<div class="main-content">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">File Management</h1>
            <div class="ms-md-1 ms-0">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Files</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- File Management Header -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex flex-wrap justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb mb-0">
                                        <li class="breadcrumb-item">
                                            <a href="#" class="text-primary">
                                                <i class="bx bx-home"></i> Root
                                            </a>
                                        </li>
                                        <li class="breadcrumb-item">
                                            <a href="#" class="text-primary">uploads</a>
                                        </li>
                                        <li class="breadcrumb-item active">images</li>
                                    </ol>
                                </nav>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadModal">
                                    <i class="bx bx-upload"></i> Upload Files
                                </button>
                                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createFolderModal">
                                    <i class="bx bx-folder-plus"></i> New Folder
                                </button>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown">
                                        <i class="bx bx-grid-alt"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#" onclick="setView('grid')"><i class="bx bx-grid-alt me-2"></i>Grid View</a></li>
                                        <li><a class="dropdown-item" href="#" onclick="setView('list')"><i class="bx bx-list-ul me-2"></i>List View</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Storage Information -->
        <div class="row">
            <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
                <div class="card overflow-hidden sales-card bg-primary-gradient">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h6 class="card-title mb-1 text-white">Total Files</h6>
                                <h2 class="text-white mb-0">{{ number_format(2847) }}</h2>
                            </div>
                            <div class="col-auto">
                                <div class="icon-box bg-white-1">
                                    <i class="bx bx-file text-white fs-18"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
                <div class="card overflow-hidden sales-card bg-success-gradient">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h6 class="card-title mb-1 text-white">Total Folders</h6>
                                <h2 class="text-white mb-0">{{ number_format(142) }}</h2>
                            </div>
                            <div class="col-auto">
                                <div class="icon-box bg-white-1">
                                    <i class="bx bx-folder text-white fs-18"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
                <div class="card overflow-hidden sales-card bg-warning-gradient">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h6 class="card-title mb-1 text-white">Storage Used</h6>
                                <h2 class="text-white mb-0">2.4 GB</h2>
                            </div>
                            <div class="col-auto">
                                <div class="icon-box bg-white-1">
                                    <i class="bx bx-hdd text-white fs-18"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
                <div class="card overflow-hidden sales-card bg-info-gradient">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h6 class="card-title mb-1 text-white">Storage Limit</h6>
                                <h2 class="text-white mb-0">10 GB</h2>
                            </div>
                            <div class="col-auto">
                                <div class="icon-box bg-white-1">
                                    <i class="bx bx-cloud text-white fs-18"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- File Browser -->
        <div class="row">
            <div class="col-xl-3">
                <!-- Sidebar -->
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Quick Access</div>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            <a href="#" class="list-group-item list-group-item-action d-flex align-items-center active">
                                <i class="bx bx-image me-2 text-primary"></i>
                                <span>Images</span>
                                <span class="badge bg-primary-transparent ms-auto">1,245</span>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action d-flex align-items-center">
                                <i class="bx bx-video me-2 text-success"></i>
                                <span>Videos</span>
                                <span class="badge bg-success-transparent ms-auto">87</span>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action d-flex align-items-center">
                                <i class="bx bx-file me-2 text-warning"></i>
                                <span>Documents</span>
                                <span class="badge bg-warning-transparent ms-auto">342</span>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action d-flex align-items-center">
                                <i class="bx bx-music me-2 text-info"></i>
                                <span>Audio</span>
                                <span class="badge bg-info-transparent ms-auto">23</span>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action d-flex align-items-center">
                                <i class="bx bx-archive me-2 text-secondary"></i>
                                <span>Archives</span>
                                <span class="badge bg-secondary-transparent ms-auto">15</span>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action d-flex align-items-center">
                                <i class="bx bx-trash me-2 text-danger"></i>
                                <span>Trash</span>
                                <span class="badge bg-danger-transparent ms-auto">8</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Storage Usage -->
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Storage Usage</div>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <div class="progress progress-lg">
                                <div class="progress-bar bg-primary" style="width: 24%"></div>
                            </div>
                            <div class="mt-2">
                                <span class="text-muted">2.4 GB of 10 GB used</span>
                            </div>
                        </div>
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="border-end">
                                    <div class="fw-semibold text-primary">24%</div>
                                    <div class="text-muted fs-12">Used</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="fw-semibold text-success">7.6 GB</div>
                                <div class="text-muted fs-12">Available</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-9">
                <!-- File Grid/List View -->
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">Files & Folders</div>
                        <div class="d-flex">
                            <div class="me-3">
                                <input class="form-control form-control-sm" type="text" placeholder="Search files..." aria-label="search">
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="bx bx-filter"></i> Filter
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#">All Files</a></li>
                                    <li><a class="dropdown-item" href="#">Images</a></li>
                                    <li><a class="dropdown-item" href="#">Documents</a></li>
                                    <li><a class="dropdown-item" href="#">Videos</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="#">Recently Modified</a></li>
                                    <li><a class="dropdown-item" href="#">Largest Files</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Grid View -->
                        <div id="grid-view">
                            <div class="row">
                                <!-- Folder Item -->
                                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-3">
                                    <div class="card text-center file-item" data-type="folder">
                                        <div class="card-body p-3">
                                            <div class="mb-2">
                                                <i class="bx bx-folder fs-40 text-warning"></i>
                                            </div>
                                            <h6 class="card-title fs-12 mb-1">Products</h6>
                                            <small class="text-muted">142 items</small>
                                        </div>
                                        <div class="card-footer p-2">
                                            <div class="btn-group w-100">
                                                <button class="btn btn-light btn-sm" data-bs-toggle="tooltip" title="Open">
                                                    <i class="bx bx-folder-open"></i>
                                                </button>
                                                <button class="btn btn-light btn-sm" data-bs-toggle="tooltip" title="Rename">
                                                    <i class="bx bx-edit"></i>
                                                </button>
                                                <button class="btn btn-light btn-sm" data-bs-toggle="tooltip" title="Delete">
                                                    <i class="bx bx-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Image File Item -->
                                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-3">
                                    <div class="card text-center file-item" data-type="image">
                                        <div class="card-body p-2">
                                            <div class="mb-2">
                                                <img src="https://via.placeholder.com/80x60" alt="Image" class="rounded" style="width: 60px; height: 45px; object-fit: cover;">
                                            </div>
                                            <h6 class="card-title fs-12 mb-1">product-1.jpg</h6>
                                            <small class="text-muted">245 KB</small>
                                        </div>
                                        <div class="card-footer p-2">
                                            <div class="btn-group w-100">
                                                <button class="btn btn-light btn-sm" data-bs-toggle="tooltip" title="View">
                                                    <i class="bx bx-show"></i>
                                                </button>
                                                <button class="btn btn-light btn-sm" data-bs-toggle="tooltip" title="Download">
                                                    <i class="bx bx-download"></i>
                                                </button>
                                                <button class="btn btn-light btn-sm" data-bs-toggle="tooltip" title="Delete">
                                                    <i class="bx bx-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Document File Item -->
                                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-3">
                                    <div class="card text-center file-item" data-type="document">
                                        <div class="card-body p-3">
                                            <div class="mb-2">
                                                <i class="bx bx-file fs-40 text-danger"></i>
                                            </div>
                                            <h6 class="card-title fs-12 mb-1">invoice.pdf</h6>
                                            <small class="text-muted">1.2 MB</small>
                                        </div>
                                        <div class="card-footer p-2">
                                            <div class="btn-group w-100">
                                                <button class="btn btn-light btn-sm" data-bs-toggle="tooltip" title="View">
                                                    <i class="bx bx-show"></i>
                                                </button>
                                                <button class="btn btn-light btn-sm" data-bs-toggle="tooltip" title="Download">
                                                    <i class="bx bx-download"></i>
                                                </button>
                                                <button class="btn btn-light btn-sm" data-bs-toggle="tooltip" title="Delete">
                                                    <i class="bx bx-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Video File Item -->
                                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-3">
                                    <div class="card text-center file-item" data-type="video">
                                        <div class="card-body p-3">
                                            <div class="mb-2">
                                                <i class="bx bx-video fs-40 text-info"></i>
                                            </div>
                                            <h6 class="card-title fs-12 mb-1">demo.mp4</h6>
                                            <small class="text-muted">15.7 MB</small>
                                        </div>
                                        <div class="card-footer p-2">
                                            <div class="btn-group w-100">
                                                <button class="btn btn-light btn-sm" data-bs-toggle="tooltip" title="Play">
                                                    <i class="bx bx-play"></i>
                                                </button>
                                                <button class="btn btn-light btn-sm" data-bs-toggle="tooltip" title="Download">
                                                    <i class="bx bx-download"></i>
                                                </button>
                                                <button class="btn btn-light btn-sm" data-bs-toggle="tooltip" title="Delete">
                                                    <i class="bx bx-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- More items would be loaded here dynamically -->
                            </div>
                        </div>

                        <!-- List View (Hidden by default) -->
                        <div id="list-view" style="display: none;">
                            <div class="table-responsive">
                                <table class="table text-nowrap table-hover">
                                    <thead>
                                        <tr>
                                            <th>
                                                <input class="form-check-input" type="checkbox" value="" aria-label="Select all">
                                            </th>
                                            <th>Name</th>
                                            <th>Type</th>
                                            <th>Size</th>
                                            <th>Modified</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <input class="form-check-input" type="checkbox" value="" aria-label="Select">
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="bx bx-folder me-2 text-warning"></i>
                                                    <span class="fw-semibold">Products</span>
                                                </div>
                                            </td>
                                            <td><span class="badge bg-warning-transparent">Folder</span></td>
                                            <td>-</td>
                                            <td>Jan 28, 2024</td>
                                            <td>
                                                <div class="hstack gap-2">
                                                    <a href="#" class="text-info fs-14 lh-1" data-bs-toggle="tooltip" title="Open">
                                                        <i class="bx bx-folder-open"></i>
                                                    </a>
                                                    <a href="#" class="text-primary fs-14 lh-1" data-bs-toggle="tooltip" title="Rename">
                                                        <i class="bx bx-edit"></i>
                                                    </a>
                                                    <a href="#" class="text-danger fs-14 lh-1" data-bs-toggle="tooltip" title="Delete">
                                                        <i class="bx bx-trash"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input class="form-check-input" type="checkbox" value="" aria-label="Select">
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="bx bx-image me-2 text-primary"></i>
                                                    <span class="fw-semibold">product-1.jpg</span>
                                                </div>
                                            </td>
                                            <td><span class="badge bg-primary-transparent">Image</span></td>
                                            <td>245 KB</td>
                                            <td>Jan 30, 2024</td>
                                            <td>
                                                <div class="hstack gap-2">
                                                    <a href="#" class="text-info fs-14 lh-1" data-bs-toggle="tooltip" title="View">
                                                        <i class="bx bx-show"></i>
                                                    </a>
                                                    <a href="#" class="text-success fs-14 lh-1" data-bs-toggle="tooltip" title="Download">
                                                        <i class="bx bx-download"></i>
                                                    </a>
                                                    <a href="#" class="text-danger fs-14 lh-1" data-bs-toggle="tooltip" title="Delete">
                                                        <i class="bx bx-trash"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                Showing <strong>1</strong> to <strong>8</strong> of <strong>2847</strong> items
                            </div>
                            <nav aria-label="Page navigation">
                                <ul class="pagination pagination-sm mb-0">
                                    <li class="page-item disabled">
                                        <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                                    </li>
                                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                                    <li class="page-item">
                                        <a class="page-link" href="#">Next</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Upload Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="uploadModalLabel">Upload Files</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="upload-zone border-dashed border-2 border-primary rounded p-4 text-center">
                    <i class="bx bx-cloud-upload fs-40 text-primary mb-3"></i>
                    <h5>Drag & Drop files here</h5>
                    <p class="text-muted">or click to browse files</p>
                    <input type="file" class="form-control" multiple hidden id="fileInput">
                    <button type="button" class="btn btn-primary" onclick="document.getElementById('fileInput').click()">
                        Choose Files
                    </button>
                </div>
                <div id="uploadProgress" style="display: none;">
                    <div class="mt-3">
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary">Upload Files</button>
            </div>
        </div>
    </div>
</div>

<!-- Create Folder Modal -->
<div class="modal fade" id="createFolderModal" tabindex="-1" aria-labelledby="createFolderModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="createFolderModalLabel">Create New Folder</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="mb-3">
                        <label for="folderName" class="form-label">Folder Name</label>
                        <input type="text" class="form-control" id="folderName" placeholder="Enter folder name">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success">Create Folder</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function setView(viewType) {
    if (viewType === 'grid') {
        $('#grid-view').show();
        $('#list-view').hide();
    } else {
        $('#grid-view').hide();
        $('#list-view').show();
    }
}

$(document).ready(function() {
    // Initialize tooltips
    $('[data-bs-toggle="tooltip"]').tooltip();
    
    // File upload handling
    $('#fileInput').change(function() {
        const files = this.files;
        if (files.length > 0) {
            $('#uploadProgress').show();
            // Simulate upload progress
            let progress = 0;
            const interval = setInterval(() => {
                progress += 10;
                $('.progress-bar').css('width', progress + '%');
                if (progress >= 100) {
                    clearInterval(interval);
                    setTimeout(() => {
                        $('#uploadModal').modal('hide');
                        location.reload();
                    }, 500);
                }
            }, 200);
        }
    });
    
    // Drag and drop functionality
    $('.upload-zone').on('dragover', function(e) {
        e.preventDefault();
        $(this).addClass('border-success');
    });
    
    $('.upload-zone').on('dragleave', function(e) {
        e.preventDefault();
        $(this).removeClass('border-success');
    });
    
    $('.upload-zone').on('drop', function(e) {
        e.preventDefault();
        $(this).removeClass('border-success');
        const files = e.originalEvent.dataTransfer.files;
        if (files.length > 0) {
            $('#fileInput')[0].files = files;
            $('#fileInput').trigger('change');
        }
    });
});
</script>
@endpush
@endsection
