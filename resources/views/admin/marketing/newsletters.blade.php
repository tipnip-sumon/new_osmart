@extends('admin.layouts.app')

@section('title', 'Newsletter Management')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">Newsletter Management</h1>
            <div class="ms-md-1 ms-0">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.marketing.index') }}">Marketing</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Newsletters</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Newsletter Stats -->
        <div class="row">
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div>
                                <span class="avatar avatar-md avatar-rounded bg-primary">
                                    <i class="bx bx-envelope fs-16"></i>
                                </span>
                            </div>
                            <div class="flex-fill ms-3">
                                <div class="d-flex align-items-center justify-content-between flex-wrap">
                                    <div>
                                        <p class="text-muted mb-0">Total Subscribers</p>
                                        <h4 class="fw-semibold mt-1">5,847</h4>
                                    </div>
                                    <div class="text-success fw-semibold">
                                        <i class="ri-arrow-up-s-line"></i>15.2%
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div>
                                <span class="avatar avatar-md avatar-rounded bg-success">
                                    <i class="bx bx-send fs-16"></i>
                                </span>
                            </div>
                            <div class="flex-fill ms-3">
                                <div class="d-flex align-items-center justify-content-between flex-wrap">
                                    <div>
                                        <p class="text-muted mb-0">Emails Sent</p>
                                        <h4 class="fw-semibold mt-1">23,456</h4>
                                    </div>
                                    <div class="text-success fw-semibold">
                                        <i class="ri-arrow-up-s-line"></i>8.2%
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div>
                                <span class="avatar avatar-md avatar-rounded bg-warning">
                                    <i class="bx bx-envelope-open fs-16"></i>
                                </span>
                            </div>
                            <div class="flex-fill ms-3">
                                <div class="d-flex align-items-center justify-content-between flex-wrap">
                                    <div>
                                        <p class="text-muted mb-0">Open Rate</p>
                                        <h4 class="fw-semibold mt-1">28.5%</h4>
                                    </div>
                                    <div class="text-warning fw-semibold">
                                        <i class="ri-arrow-up-s-line"></i>3.1%
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div>
                                <span class="avatar avatar-md avatar-rounded bg-info">
                                    <i class="bx bx-mouse fs-16"></i>
                                </span>
                            </div>
                            <div class="flex-fill ms-3">
                                <div class="d-flex align-items-center justify-content-between flex-wrap">
                                    <div>
                                        <p class="text-muted mb-0">Click Rate</p>
                                        <h4 class="fw-semibold mt-1">4.2%</h4>
                                    </div>
                                    <div class="text-info fw-semibold">
                                        <i class="ri-arrow-up-s-line"></i>1.8%
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Newsletter Composer -->
            <div class="col-xl-8">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Compose Newsletter</div>
                    </div>
                    <div class="card-body">
                        <form id="newsletterForm">
                            <div class="mb-3">
                                <label class="form-label">Subject *</label>
                                <input type="text" class="form-control" name="subject" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Newsletter Content *</label>
                                <textarea class="form-control" name="content" rows="12" required placeholder="Write your newsletter content here..."></textarea>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Send To</label>
                                        <select class="form-control" name="recipients">
                                            <option value="all">All Subscribers</option>
                                            <option value="active">Active Users Only</option>
                                            <option value="recent">Recent Subscribers</option>
                                            <option value="custom">Custom List</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Schedule</label>
                                        <select class="form-control" name="schedule">
                                            <option value="now">Send Now</option>
                                            <option value="later">Schedule for Later</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3" id="scheduleDateTime" style="display: none;">
                                <label class="form-label">Schedule Date & Time</label>
                                <input type="datetime-local" class="form-control" name="schedule_date">
                            </div>
                            
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-primary" onclick="sendNewsletter()">
                                    <i class="bx bx-send"></i> Send Newsletter
                                </button>
                                <button type="button" class="btn btn-secondary" onclick="previewNewsletter()">
                                    <i class="bx bx-show"></i> Preview
                                </button>
                                <button type="button" class="btn btn-success" onclick="saveAsDraft()">
                                    <i class="bx bx-save"></i> Save as Draft
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Newsletter Settings & Templates -->
            <div class="col-xl-4">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Quick Templates</div>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="loadTemplate('welcome')">
                                <i class="bx bx-user-plus"></i> Welcome Email
                            </button>
                            <button type="button" class="btn btn-outline-success btn-sm" onclick="loadTemplate('promotion')">
                                <i class="bx bx-gift"></i> Promotion Email
                            </button>
                            <button type="button" class="btn btn-outline-warning btn-sm" onclick="loadTemplate('newsletter')">
                                <i class="bx bx-news"></i> News Update
                            </button>
                            <button type="button" class="btn btn-outline-info btn-sm" onclick="loadTemplate('product')">
                                <i class="bx bx-shopping-bag"></i> Product Launch
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Subscriber Lists</div>
                    </div>
                    <div class="card-body">
                        <div class="list-group">
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                All Subscribers
                                <span class="badge bg-primary rounded-pill">5,847</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                Active Users
                                <span class="badge bg-success rounded-pill">4,234</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                Recent Subscribers
                                <span class="badge bg-warning rounded-pill">847</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                VIP Customers
                                <span class="badge bg-info rounded-pill">234</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Newsletters -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Recent Newsletters</div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table text-nowrap table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Subject</th>
                                        <th>Recipients</th>
                                        <th>Sent</th>
                                        <th>Opened</th>
                                        <th>Clicked</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Summer Sale - 50% Off Everything!</td>
                                        <td>5,234</td>
                                        <td>5,234</td>
                                        <td>1,456 (27.8%)</td>
                                        <td>234 (4.5%)</td>
                                        <td>July 15, 2024</td>
                                        <td><span class="badge bg-success">Sent</span></td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-sm btn-primary-light" onclick="viewNewsletter(1)">
                                                    <i class="bx bx-show"></i>
                                                </button>
                                                <button class="btn btn-sm btn-success-light" onclick="duplicateNewsletter(1)">
                                                    <i class="bx bx-copy"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>New Product Launch - Check It Out!</td>
                                        <td>4,567</td>
                                        <td>4,567</td>
                                        <td>1,234 (27.0%)</td>
                                        <td>189 (4.1%)</td>
                                        <td>July 10, 2024</td>
                                        <td><span class="badge bg-success">Sent</span></td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-sm btn-primary-light" onclick="viewNewsletter(2)">
                                                    <i class="bx bx-show"></i>
                                                </button>
                                                <button class="btn btn-sm btn-success-light" onclick="duplicateNewsletter(2)">
                                                    <i class="bx bx-copy"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Welcome to Our Newsletter!</td>
                                        <td>0</td>
                                        <td>0</td>
                                        <td>0 (0%)</td>
                                        <td>0 (0%)</td>
                                        <td>July 20, 2024</td>
                                        <td><span class="badge bg-secondary">Draft</span></td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-sm btn-primary-light" onclick="editNewsletter(3)">
                                                    <i class="bx bx-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger-light" onclick="deleteNewsletter(3)">
                                                    <i class="bx bx-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Schedule selector handler
    document.querySelector('select[name="schedule"]').addEventListener('change', function() {
        const scheduleDateTime = document.getElementById('scheduleDateTime');
        if (this.value === 'later') {
            scheduleDateTime.style.display = 'block';
        } else {
            scheduleDateTime.style.display = 'none';
        }
    });
});

function sendNewsletter() {
    const form = document.getElementById('newsletterForm');
    const formData = new FormData(form);
    
    if (!formData.get('subject') || !formData.get('content')) {
        if (typeof Swal !== 'undefined') {
            Swal.fire('Error', 'Please fill in all required fields', 'error');
        } else {
            alert('Please fill in all required fields');
        }
        return;
    }
    
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Send Newsletter',
            text: 'Are you sure you want to send this newsletter?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, Send'
        }).then((result) => {
            if (result.isConfirmed) {
                // Simulate sending
                Swal.fire('Success!', 'Newsletter has been sent successfully.', 'success');
                form.reset();
            }
        });
    } else {
        if (confirm('Send newsletter?')) {
            alert('Newsletter sent successfully!');
            form.reset();
        }
    }
}

function previewNewsletter() {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Newsletter Preview',
            html: '<div class="text-start"><h5>Subject: Summer Sale - 50% Off Everything!</h5><hr><p>Dear Subscriber,</p><p>We are excited to announce our biggest sale of the year! Get 50% off on all items in our store.</p><p>This is a preview of how your newsletter will appear.</p></div>',
            showCloseButton: true,
            showConfirmButton: false,
            width: '600px'
        });
    } else {
        alert('Preview feature coming soon');
    }
}

function saveAsDraft() {
    if (typeof Swal !== 'undefined') {
        Swal.fire('Success!', 'Newsletter has been saved as draft.', 'success');
    } else {
        alert('Newsletter saved as draft!');
    }
}

function loadTemplate(type) {
    const contentTextarea = document.querySelector('textarea[name="content"]');
    const subjectInput = document.querySelector('input[name="subject"]');
    
    let subject = '';
    let content = '';
    
    switch(type) {
        case 'welcome':
            subject = 'Welcome to Our Newsletter!';
            content = `Dear Subscriber,

Welcome to our newsletter! We're excited to have you on board.

You'll receive updates about our latest products, exclusive offers, and industry news.

Thank you for subscribing!

Best regards,
The Team`;
            break;
        case 'promotion':
            subject = 'Special Offer Just for You!';
            content = `Dear Valued Customer,

We have an exclusive offer just for you! Get [X]% off on [products/categories].

Use code: [PROMOCODE] at checkout.

This offer is valid until [date].

Happy shopping!

Best regards,
The Team`;
            break;
        case 'newsletter':
            subject = 'Latest News and Updates';
            content = `Dear Subscriber,

Here's what's new this month:

• [News item 1]
• [News item 2]
• [News item 3]

Stay tuned for more updates!

Best regards,
The Team`;
            break;
        case 'product':
            subject = 'Introducing Our Latest Product!';
            content = `Dear Customer,

We're thrilled to announce the launch of our latest product: [Product Name]

[Product Description]

Key Features:
• [Feature 1]
• [Feature 2]
• [Feature 3]

Get yours today!

Best regards,
The Team`;
            break;
    }
    
    subjectInput.value = subject;
    contentTextarea.value = content;
    
    if (typeof Swal !== 'undefined') {
        Swal.fire('Template Loaded!', `${type} template has been loaded successfully.`, 'success');
    } else {
        alert(`${type} template loaded successfully!`);
    }
}

function viewNewsletter(id) {
    if (typeof Swal !== 'undefined') {
        Swal.fire('Info', `View newsletter ${id} - Feature coming soon`, 'info');
    } else {
        alert(`View newsletter ${id} - Feature coming soon`);
    }
}

function duplicateNewsletter(id) {
    if (typeof Swal !== 'undefined') {
        Swal.fire('Success!', `Newsletter ${id} has been duplicated.`, 'success');
    } else {
        alert(`Newsletter ${id} duplicated successfully!`);
    }
}

function editNewsletter(id) {
    if (typeof Swal !== 'undefined') {
        Swal.fire('Info', `Edit newsletter ${id} - Feature coming soon`, 'info');
    } else {
        alert(`Edit newsletter ${id} - Feature coming soon`);
    }
}

function deleteNewsletter(id) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Delete Newsletter',
            text: 'Are you sure you want to delete this newsletter?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, Delete',
            confirmButtonColor: '#dc3545'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire('Deleted!', 'Newsletter has been deleted.', 'success');
            }
        });
    } else {
        if (confirm('Delete this newsletter?')) {
            alert('Newsletter deleted successfully!');
        }
    }
}
</script>
@endsection
