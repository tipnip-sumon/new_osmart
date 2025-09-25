@extends('member.layouts.app')

@section('title', 'Training Center')

@section('content')
<div class="main-content">
    <div class="container-fluid">
        
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">Training Center</h1>
            <div class="ms-md-1 ms-0">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('member.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Training</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- Page Header Close -->

        <!-- Training Categories -->
        <div class="row mb-4">
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                <div class="card custom-card training-category">
                    <div class="card-body text-center">
                        <div class="avatar avatar-lg bg-primary-transparent mb-3">
                            <i class="fe fe-play-circle fs-24"></i>
                        </div>
                        <h6 class="fw-semibold mb-2">Video Courses</h6>
                        <p class="text-muted mb-3">Interactive video training modules</p>
                        <span class="badge bg-primary-transparent">12 Videos</span>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                <div class="card custom-card training-category">
                    <div class="card-body text-center">
                        <div class="avatar avatar-lg bg-success-transparent mb-3">
                            <i class="fe fe-book-open fs-24"></i>
                        </div>
                        <h6 class="fw-semibold mb-2">Study Guides</h6>
                        <p class="text-muted mb-3">Comprehensive learning materials</p>
                        <span class="badge bg-success-transparent">8 Guides</span>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                <div class="card custom-card training-category">
                    <div class="card-body text-center">
                        <div class="avatar avatar-lg bg-warning-transparent mb-3">
                            <i class="fe fe-calendar fs-24"></i>
                        </div>
                        <h6 class="fw-semibold mb-2">Webinars</h6>
                        <p class="text-muted mb-3">Live and recorded training sessions</p>
                        <span class="badge bg-warning-transparent">5 Sessions</span>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                <div class="card custom-card training-category">
                    <div class="card-body text-center">
                        <div class="avatar avatar-lg bg-info-transparent mb-3">
                            <i class="fe fe-award fs-24"></i>
                        </div>
                        <h6 class="fw-semibold mb-2">Certifications</h6>
                        <p class="text-muted mb-3">Earn certificates and badges</p>
                        <span class="badge bg-info-transparent">3 Certs</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Training Materials -->
            <div class="col-xl-8">
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">
                            <i class="fe fe-book me-2"></i>Training Materials
                        </div>
                        <div class="d-flex gap-2">
                            <select class="form-select form-select-sm" id="categoryFilter" style="width: auto;">
                                <option value="">All Categories</option>
                                <option value="getting-started">Getting Started</option>
                                <option value="marketing">Marketing</option>
                                <option value="products">Products</option>
                                <option value="advanced">Advanced</option>
                            </select>
                            <select class="form-select form-select-sm" id="typeFilter" style="width: auto;">
                                <option value="">All Types</option>
                                <option value="video">Videos</option>
                                <option value="pdf">PDFs</option>
                                <option value="webinar">Webinars</option>
                                <option value="quiz">Quizzes</option>
                            </select>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($trainingMaterials->count() > 0)
                            <div class="training-materials">
                                @foreach($trainingMaterials as $index => $material)
                                <div class="training-item">
                                    <div class="training-icon">
                                        @if($material['type'] == 'Video')
                                            <i class="fe fe-play-circle text-primary fs-24"></i>
                                        @elseif($material['type'] == 'PDF')
                                            <i class="fe fe-file-text text-danger fs-24"></i>
                                        @else
                                            <i class="fe fe-book text-success fs-24"></i>
                                        @endif
                                    </div>
                                    <div class="training-content">
                                        <h6 class="fw-semibold mb-1">{{ $material['title'] }}</h6>
                                        <p class="text-muted mb-2">
                                            @if($material['type'] == 'Video')
                                                Interactive video course with step-by-step instructions
                                            @elseif($material['type'] == 'PDF')
                                                Comprehensive guide with detailed explanations
                                            @else
                                                Educational content to enhance your knowledge
                                            @endif
                                        </p>
                                        <div class="d-flex align-items-center gap-3">
                                            <span class="badge bg-{{ $material['type'] == 'Video' ? 'primary' : ($material['type'] == 'PDF' ? 'danger' : 'success') }}-transparent">
                                                {{ $material['type'] }}
                                            </span>
                                            <span class="text-muted fs-12">
                                                <i class="fe fe-clock me-1"></i>
                                                {{ $material['type'] == 'Video' ? '15-30 min' : ($material['type'] == 'PDF' ? '10-20 min read' : '5-10 min') }}
                                            </span>
                                            <span class="text-muted fs-12">
                                                <i class="fe fe-eye me-1"></i>{{ rand(50, 500) }} views
                                            </span>
                                        </div>
                                    </div>
                                    <div class="training-actions">
                                        <button class="btn btn-primary btn-sm" onclick="viewMaterial('{{ $material['title'] }}', '{{ $material['type'] }}')">
                                            <i class="fe fe-{{ $material['type'] == 'Video' ? 'play' : 'eye' }} me-1"></i>
                                            {{ $material['type'] == 'Video' ? 'Watch' : 'View' }}
                                        </button>
                                        <button class="btn btn-success btn-sm" onclick="downloadMaterial('{{ $material['title'] }}')">
                                            <i class="fe fe-download me-1"></i>Download
                                        </button>
                                    </div>
                                </div>
                                @endforeach
                                
                                <!-- Additional sample materials -->
                                <div class="training-item">
                                    <div class="training-icon">
                                        <i class="fe fe-play-circle text-primary fs-24"></i>
                                    </div>
                                    <div class="training-content">
                                        <h6 class="fw-semibold mb-1">Building Your Network</h6>
                                        <p class="text-muted mb-2">Learn effective strategies for recruiting and building your downline</p>
                                        <div class="d-flex align-items-center gap-3">
                                            <span class="badge bg-primary-transparent">Video</span>
                                            <span class="text-muted fs-12"><i class="fe fe-clock me-1"></i>25 min</span>
                                            <span class="text-muted fs-12"><i class="fe fe-eye me-1"></i>324 views</span>
                                        </div>
                                    </div>
                                    <div class="training-actions">
                                        <button class="btn btn-primary btn-sm" onclick="viewMaterial('Building Your Network', 'Video')">
                                            <i class="fe fe-play me-1"></i>Watch
                                        </button>
                                        <button class="btn btn-success btn-sm" onclick="downloadMaterial('Building Your Network')">
                                            <i class="fe fe-download me-1"></i>Download
                                        </button>
                                    </div>
                                </div>

                                <div class="training-item">
                                    <div class="training-icon">
                                        <i class="fe fe-file-text text-danger fs-24"></i>
                                    </div>
                                    <div class="training-content">
                                        <h6 class="fw-semibold mb-1">Commission Structure Guide</h6>
                                        <p class="text-muted mb-2">Detailed breakdown of our compensation plan and bonus structure</p>
                                        <div class="d-flex align-items-center gap-3">
                                            <span class="badge bg-danger-transparent">PDF</span>
                                            <span class="text-muted fs-12"><i class="fe fe-clock me-1"></i>15 min read</span>
                                            <span class="text-muted fs-12"><i class="fe fe-eye me-1"></i>156 views</span>
                                        </div>
                                    </div>
                                    <div class="training-actions">
                                        <button class="btn btn-primary btn-sm" onclick="viewMaterial('Commission Structure Guide', 'PDF')">
                                            <i class="fe fe-eye me-1"></i>View
                                        </button>
                                        <button class="btn btn-success btn-sm" onclick="downloadMaterial('Commission Structure Guide')">
                                            <i class="fe fe-download me-1"></i>Download
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <div class="avatar avatar-xl avatar-rounded bg-light mb-3">
                                    <i class="fe fe-book fs-24 text-muted"></i>
                                </div>
                                <h6 class="fw-semibold mb-1">No Training Materials</h6>
                                <p class="text-muted mb-3">Training materials will be available soon</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Training Progress & Stats -->
            <div class="col-xl-4">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="fe fe-trending-up me-2"></i>Training Progress
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="training-progress">
                            <div class="progress-item">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="fw-semibold">Getting Started</span>
                                    <span class="text-primary">100%</span>
                                </div>
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-primary" style="width: 100%"></div>
                                </div>
                            </div>
                            <div class="progress-item">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="fw-semibold">Marketing Basics</span>
                                    <span class="text-success">75%</span>
                                </div>
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-success" style="width: 75%"></div>
                                </div>
                            </div>
                            <div class="progress-item">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="fw-semibold">Product Knowledge</span>
                                    <span class="text-warning">45%</span>
                                </div>
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-warning" style="width: 45%"></div>
                                </div>
                            </div>
                            <div class="progress-item">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="fw-semibold">Advanced Strategies</span>
                                    <span class="text-info">20%</span>
                                </div>
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-info" style="width: 20%"></div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="training-stats">
                            <div class="stat-item">
                                <div class="stat-icon">
                                    <i class="fe fe-play-circle text-primary"></i>
                                </div>
                                <div class="stat-content">
                                    <h6 class="mb-0">8</h6>
                                    <p class="text-muted mb-0">Videos Watched</p>
                                </div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-icon">
                                    <i class="fe fe-file-text text-success"></i>
                                </div>
                                <div class="stat-content">
                                    <h6 class="mb-0">5</h6>
                                    <p class="text-muted mb-0">Guides Read</p>
                                </div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-icon">
                                    <i class="fe fe-award text-warning"></i>
                                </div>
                                <div class="stat-content">
                                    <h6 class="mb-0">2</h6>
                                    <p class="text-muted mb-0">Certificates Earned</p>
                                </div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-icon">
                                    <i class="fe fe-clock text-info"></i>
                                </div>
                                <div class="stat-content">
                                    <h6 class="mb-0">3.5h</h6>
                                    <p class="text-muted mb-0">Time Spent</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Upcoming Webinars -->
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="fe fe-calendar me-2"></i>Upcoming Webinars
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="webinar-list">
                            <div class="webinar-item">
                                <div class="webinar-date">
                                    <div class="date-box">
                                        <span class="day">25</span>
                                        <span class="month">Aug</span>
                                    </div>
                                </div>
                                <div class="webinar-content">
                                    <h6 class="mb-1">Marketing Mastery</h6>
                                    <p class="text-muted mb-1">Advanced marketing techniques</p>
                                    <small class="text-success">2:00 PM EST</small>
                                </div>
                            </div>
                            <div class="webinar-item">
                                <div class="webinar-date">
                                    <div class="date-box">
                                        <span class="day">28</span>
                                        <span class="month">Aug</span>
                                    </div>
                                </div>
                                <div class="webinar-content">
                                    <h6 class="mb-1">Team Building</h6>
                                    <p class="text-muted mb-1">Building effective teams</p>
                                    <small class="text-success">3:00 PM EST</small>
                                </div>
                            </div>
                            <div class="webinar-item">
                                <div class="webinar-date">
                                    <div class="date-box">
                                        <span class="day">01</span>
                                        <span class="month">Sep</span>
                                    </div>
                                </div>
                                <div class="webinar-content">
                                    <h6 class="mb-1">Product Deep Dive</h6>
                                    <p class="text-muted mb-1">Comprehensive product training</p>
                                    <small class="text-success">1:00 PM EST</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-center mt-3">
                            <button class="btn btn-primary btn-sm" onclick="viewAllWebinars()">
                                <i class="fe fe-calendar me-1"></i>View All Webinars
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('styles')
<style>
.training-category {
    transition: all 0.3s ease;
    border: 1px solid #e9ecef;
}

.training-category:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

.training-materials {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.training-item {
    display: flex;
    align-items: flex-start;
    gap: 20px;
    padding: 20px;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.training-item:hover {
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    transform: translateY(-2px);
}

.training-icon {
    flex-shrink: 0;
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f8f9fa;
    border-radius: 8px;
}

.training-content {
    flex: 1;
}

.training-actions {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.progress-item {
    margin-bottom: 20px;
}

.progress-sm {
    height: 6px;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 15px;
}

.stat-icon {
    width: 35px;
    height: 35px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f8f9fa;
    border-radius: 6px;
}

.webinar-item {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 15px 0;
    border-bottom: 1px solid #f1f3f4;
}

.webinar-item:last-child {
    border-bottom: none;
}

.date-box {
    background: linear-gradient(135deg, #6c5ce7 0%, #a29bfe 100%);
    color: white;
    padding: 8px;
    border-radius: 8px;
    text-align: center;
    min-width: 45px;
}

.date-box .day {
    display: block;
    font-size: 16px;
    font-weight: bold;
    line-height: 1;
}

.date-box .month {
    display: block;
    font-size: 10px;
    text-transform: uppercase;
    opacity: 0.8;
}

@media (max-width: 768px) {
    .training-item {
        flex-direction: column;
        text-align: center;
    }
    
    .training-actions {
        flex-direction: row;
        justify-content: center;
    }
}
</style>
@endpush

@push('scripts')
<script>
function viewMaterial(title, type) {
    Swal.fire({
        title: title,
        html: `
            <div class="text-center">
                <div class="avatar avatar-lg bg-primary-transparent mb-3">
                    <i class="fe fe-${type === 'Video' ? 'play-circle' : 'file-text'} fs-24"></i>
                </div>
                <p>This ${type.toLowerCase()} will open in a new window or player.</p>
                <p class="text-muted small">Note: This is a demo. Actual training content would be loaded here.</p>
            </div>
        `,
        icon: 'info',
        confirmButtonText: type === 'Video' ? 'Play Video' : 'Open Document',
        showCancelButton: true,
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Simulate opening training material
            console.log(`Opening ${type}: ${title}`);
        }
    });
}

function downloadMaterial(title) {
    Swal.fire({
        title: 'Download Starting...',
        text: `Downloading: ${title}`,
        icon: 'success',
        timer: 2000,
        showConfirmButton: false
    });
}

function viewAllWebinars() {
    Swal.fire({
        title: 'Upcoming Webinars',
        html: `
            <div class="text-start">
                <h6>This Month's Schedule:</h6>
                <ul class="list-unstyled">
                    <li class="mb-2">• Marketing Mastery - Aug 25, 2:00 PM EST</li>
                    <li class="mb-2">• Team Building - Aug 28, 3:00 PM EST</li>
                    <li class="mb-2">• Product Deep Dive - Sep 1, 1:00 PM EST</li>
                    <li class="mb-2">• Leadership Training - Sep 5, 2:00 PM EST</li>
                </ul>
                <p class="text-muted small mt-3">All webinars include Q&A sessions and downloadable materials.</p>
            </div>
        `,
        icon: 'info',
        confirmButtonText: 'Register for Webinars'
    });
}

// Filter functionality
document.getElementById('categoryFilter').addEventListener('change', function() {
    const category = this.value;
    console.log('Filtering by category:', category);
    // Implement filter logic here
});

document.getElementById('typeFilter').addEventListener('change', function() {
    const type = this.value;
    console.log('Filtering by type:', type);
    // Implement filter logic here
});
</script>
@endpush
