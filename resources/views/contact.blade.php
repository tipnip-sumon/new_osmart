@extends('layouts.ecomus')

@section('title', 'Contact Us - ' . config('app.name'))

@section('content')
    <!-- page-title -->
    <div class="tf-page-title style-2">
        <div class="container-full">
            <div class="heading text-center">Contact Us</div>
        </div>
    </div>
    <!-- /page-title -->

    <!-- Office Locations -->
    <section class="flat-spacing-21">
        <div class="container">
            <div class="row justify-content-center mb_60">
                <div class="col-lg-8 text-center">
                    <h2 class="mb_20">Our Office Locations</h2>
                    <p class="text-muted">Visit us at any of our convenient locations across Bangladesh</p>
                </div>
            </div>
            
            <!-- Location Tabs -->
            <div class="tf-tab-demo-element wow fadeInUp" data-wow-delay="0s">
                <ul class="widget-menu-tab overflow-x-auto">
                    <li class="item-title active">
                        <span class="inner">Dhaka Office</span>
                    </li>
                    <li class="item-title">
                        <span class="inner">Cumilla Branch</span>
                    </li>
                    <li class="item-title">
                        <span class="inner">Rajshahi Branch</span>
                    </li>
                </ul>
                
                <div class="widget-content-tab">
                    <!-- Dhaka Office -->
                    <div class="widget-content-inner active">
                        <div class="tf-grid-layout gap30 lg-col-2">
                            <div class="tf-content-left">
                                <h5 class="mb_20">Dhaka Head Office</h5>
                                <div class="mb_20">
                                    <p class="mb_15"><strong>Address</strong></p>
                                    <p>House #123, Road #15, Block C<br>Banani, Dhaka-1213, Bangladesh</p>
                                </div>
                                <div class="mb_20">
                                    <p class="mb_15"><strong>Phone</strong></p>
                                    <p>+88 01700-000000</p>
                                    <p>+88 02-55555555</p>
                                </div>
                                <div class="mb_20">
                                    <p class="mb_15"><strong>Email</strong></p>
                                    <p>dhaka@osmart.com.bd</p>
                                    <p>info@osmart.com.bd</p>
                                </div>
                                <div class="mb_36">
                                    <p class="mb_15"><strong>Business Hours</strong></p>
                                    <p class="mb_5">Saturday - Thursday: 9:00 AM - 7:00 PM</p>
                                    <p class="mb_5">Friday: 2:00 PM - 7:00 PM</p>
                                    <p class="text-danger">Government holidays: Closed</p>
                                </div>
                                <div class="mb_30">
                                    <ul class="tf-social-icon d-flex gap-20 style-default">
                                        <li><a href="#" class="box-icon link round social-facebook border-line-black"><i class="icon fs-14 icon-fb"></i></a></li>
                                        <li><a href="#" class="box-icon link round social-twiter border-line-black"><i class="icon fs-12 icon-Icon-x"></i></a></li>
                                        <li><a href="#" class="box-icon link round social-instagram border-line-black"><i class="icon fs-14 icon-instagram"></i></a></li>
                                        <li><a href="#" class="box-icon link round social-linkedin border-line-black"><i class="icon fs-14 icon-linkedin"></i></a></li>
                                        <li><a href="#" class="box-icon link round social-youtube border-line-black"><i class="icon fs-14 icon-youtube"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="tf-content-right">
                                <!-- Dhaka Map -->
                                <div class="map-container mb_30">
                                    <iframe
                                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3651.123456789!2d90.3840518!3d23.7945677!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3755c70c15ea1de1%3A0x97856381e88fb311!2sBanani%2C%20Dhaka!5e0!3m2!1sen!2sbd!4v1640995200000!5m2!1sen!2sbd"
                                        width="100%" height="300" style="border:0; border-radius: 10px;" allowfullscreen="" loading="lazy"
                                        referrerpolicy="no-referrer-when-downgrade"></iframe>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Cumilla Branch -->
                    <div class="widget-content-inner">
                        <div class="tf-grid-layout gap30 lg-col-2">
                            <div class="tf-content-left">
                                <h5 class="mb_20">Cumilla Branch Office</h5>
                                <div class="mb_20">
                                    <p class="mb_15"><strong>Address</strong></p>
                                    <p>Chowmuhani Bazar, Shop #45<br>Cumilla-3500, Bangladesh</p>
                                </div>
                                <div class="mb_20">
                                    <p class="mb_15"><strong>Phone</strong></p>
                                    <p>+88 01800-111111</p>
                                    <p>+88 081-66666</p>
                                </div>
                                <div class="mb_20">
                                    <p class="mb_15"><strong>Email</strong></p>
                                    <p>cumilla@osmart.com.bd</p>
                                </div>
                                <div class="mb_36">
                                    <p class="mb_15"><strong>Business Hours</strong></p>
                                    <p class="mb_5">Saturday - Thursday: 9:00 AM - 6:30 PM</p>
                                    <p class="mb_5">Friday: 2:30 PM - 6:30 PM</p>
                                    <p class="text-danger">Government holidays: Closed</p>
                                </div>
                                <div class="mb_30">
                                    <ul class="tf-social-icon d-flex gap-20 style-default">
                                        <li><a href="#" class="box-icon link round social-facebook border-line-black"><i class="icon fs-14 icon-fb"></i></a></li>
                                        <li><a href="#" class="box-icon link round social-whatsapp border-line-black"><i class="icon fs-14 icon-phone"></i></a></li>
                                        <li><a href="#" class="box-icon link round social-instagram border-line-black"><i class="icon fs-14 icon-instagram"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="tf-content-right">
                                <!-- Cumilla Map -->
                                <div class="map-container mb_30">
                                    <iframe
                                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3652.987654321!2d91.1833!3d23.4607!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x375368f5ca89d6a7%3A0x5c3f73c5c45a7b1a!2sCumilla!5e0!3m2!1sen!2sbd!4v1640995300000!5m2!1sen!2sbd"
                                        width="100%" height="300" style="border:0; border-radius: 10px;" allowfullscreen="" loading="lazy"
                                        referrerpolicy="no-referrer-when-downgrade"></iframe>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Rajshahi Branch -->
                    <div class="widget-content-inner">
                        <div class="tf-grid-layout gap30 lg-col-2">
                            <div class="tf-content-left">
                                <h5 class="mb_20">Rajshahi Branch Office</h5>
                                <div class="mb_20">
                                    <p class="mb_15"><strong>Address</strong></p>
                                    <p>Shaheb Bazar, Building #78<br>2nd Floor, Rajshahi-6000, Bangladesh</p>
                                </div>
                                <div class="mb_20">
                                    <p class="mb_15"><strong>Phone</strong></p>
                                    <p>+88 01900-222222</p>
                                    <p>+88 0721-777777</p>
                                </div>
                                <div class="mb_20">
                                    <p class="mb_15"><strong>Email</strong></p>
                                    <p>rajshahi@osmart.com.bd</p>
                                </div>
                                <div class="mb_36">
                                    <p class="mb_15"><strong>Business Hours</strong></p>
                                    <p class="mb_5">Saturday - Thursday: 9:30 AM - 6:00 PM</p>
                                    <p class="mb_5">Friday: 3:00 PM - 6:00 PM</p>
                                    <p class="text-danger">Government holidays: Closed</p>
                                </div>
                                <div class="mb_30">
                                    <ul class="tf-social-icon d-flex gap-20 style-default">
                                        <li><a href="#" class="box-icon link round social-facebook border-line-black"><i class="icon fs-14 icon-fb"></i></a></li>
                                        <li><a href="#" class="box-icon link round social-whatsapp border-line-black"><i class="icon fs-14 icon-phone"></i></a></li>
                                        <li><a href="#" class="box-icon link round social-instagram border-line-black"><i class="icon fs-14 icon-instagram"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="tf-content-right">
                                <!-- Rajshahi Map -->
                                <div class="map-container mb_30">
                                    <iframe
                                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3634.123456789!2d88.6041!3d24.3745!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39fbefa96a38d031%3A0x10f93a950ed6a410!2sRajshahi!5e0!3m2!1sen!2sbd!4v1640995400000!5m2!1sen!2sbd"
                                        width="100%" height="300" style="border:0; border-radius: 10px;" allowfullscreen="" loading="lazy"
                                        referrerpolicy="no-referrer-when-downgrade"></iframe>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Form Section -->
    <section class="flat-spacing-21 bg-grey-13">
        <div class="container">
            <div class="tf-grid-layout gap60 lg-col-2 align-items-start">
                <div class="tf-content-left">
                    <div class="wow fadeInLeft" data-wow-delay="0s">
                        <h3 class="mb_20">Get in Touch</h3>
                        <p class="mb_30 text-paragraph">Have questions about our products or services? We'd love to hear from you. Send us a message and we'll respond as soon as possible.</p>
                        
                        <!-- Quick Contact Info -->
                        <div class="contact-info-grid">
                            <div class="contact-info-item mb_25">
                                <div class="icon-box mb_10">
                                    <i class="icon icon-phone fs-20"></i>
                                </div>
                                <h6 class="mb_5">Call Us</h6>
                                <p class="text-paragraph">+88 01700-000000 (24/7 Support)</p>
                            </div>
                            
                            <div class="contact-info-item mb_25">
                                <div class="icon-box mb_10">
                                    <i class="icon icon-mail fs-20"></i>
                                </div>
                                <h6 class="mb_5">Email Us</h6>
                                <p class="text-paragraph">info@osmart.com.bd</p>
                            </div>
                            
                            <div class="contact-info-item mb_25">
                                <div class="icon-box mb_10">
                                    <i class="icon icon-clock fs-20"></i>
                                </div>
                                <h6 class="mb_5">Response Time</h6>
                                <p class="text-paragraph">Within 24 hours on business days</p>
                            </div>
                        </div>

                        <!-- FAQ Section -->
                        <div class="mt-4">
                            <h6 class="mb_15">Frequently Asked</h6>
                            <ul class="list-unstyled">
                                <li class="mb_10"><i class="icon icon-check me-2 text-success"></i>Product warranty information</li>
                                <li class="mb_10"><i class="icon icon-check me-2 text-success"></i>Shipping and delivery details</li>
                                <li class="mb_10"><i class="icon icon-check me-2 text-success"></i>Return and exchange policy</li>
                                <li class="mb_10"><i class="icon icon-check me-2 text-success"></i>Business partnership inquiries</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="tf-content-right">
                    <div class="wow fadeInRight" data-wow-delay="0.1s">
                        <div class="form-contact-wrap">
                            <h5 class="mb_20">Send us a Message</h5>
                            
                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show mb_20" role="alert">
                                    <i class="icon icon-check-circle me-2"></i>
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            @if($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show mb_20" role="alert">
                                    <i class="icon icon-alert-triangle me-2"></i>
                                    <ul class="mb-0 list-unstyled">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            <form class="form-contact" method="POST" action="{{ route('contact.submit') }}">
                                @csrf
                                <div class="d-flex gap-15 mb_15">
                                    <fieldset class="w-100">
                                        <input type="text" name="name" id="name" required placeholder="Your Name *" value="{{ old('name') }}" />
                                        @error('name')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </fieldset>
                                    <fieldset class="w-100">
                                        <input type="email" name="email" id="email" required placeholder="Your Email *" value="{{ old('email') }}" />
                                        @error('email')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </fieldset>
                                </div>
                                
                                <div class="d-flex gap-15 mb_15">
                                    <fieldset class="w-100">
                                        <input type="tel" name="phone" id="phone" placeholder="Phone Number" value="{{ old('phone') }}" />
                                        @error('phone')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </fieldset>
                                    <fieldset class="w-100">
                                        <select name="subject" id="subject" required class="tf-select">
                                            <option value="">Select Subject *</option>
                                            <option value="general" {{ old('subject') == 'general' ? 'selected' : '' }}>General Inquiry</option>
                                            <option value="business" {{ old('subject') == 'business' ? 'selected' : '' }}>Business Partnership</option>
                                            <option value="support" {{ old('subject') == 'support' ? 'selected' : '' }}>Technical Support</option>
                                            <option value="partnership" {{ old('subject') == 'partnership' ? 'selected' : '' }}>Affiliate Program</option>
                                            <option value="other" {{ old('subject') == 'other' ? 'selected' : '' }}>Other</option>
                                        </select>
                                        @error('subject')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </fieldset>
                                </div>
                                
                                <div class="mb_15">
                                    <textarea placeholder="Your Message *" name="message" id="message" required cols="30" rows="6">{{ old('message') }}</textarea>
                                    @error('message')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                
                                <div class="mb_20">
                                    <div class="form-check">
                                        <input type="checkbox" name="subscribe_newsletter" id="subscribe_newsletter" class="form-check-input" {{ old('subscribe_newsletter') ? 'checked' : '' }}>
                                        <label for="subscribe_newsletter" class="form-check-label">
                                            Subscribe to our newsletter for updates and offers
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="send-wrap">
                                    <button type="submit" class="tf-btn w-100 radius-3 btn-fill animate-hover-btn justify-content-center">
                                        <span>Send Message</span>
                                        <i class="icon icon-arrow1-top-left ms-2"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Additional Services Section -->
    <section class="flat-spacing-14">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center mb_40">
                    <h3>Why Choose OSmart?</h3>
                    <p class="text-paragraph">We're committed to providing the best service experience</p>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3 col-md-6 col-12 mb_30">
                    <div class="tf-icon-box text-center">
                        <div class="icon">
                            <i class="icon icon-shipped fs-40 text-primary"></i>
                        </div>
                        <div class="content">
                            <h6 class="mb_10">Fast Delivery</h6>
                            <p class="text-paragraph">Quick delivery across Bangladesh</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-12 mb_30">
                    <div class="tf-icon-box text-center">
                        <div class="icon">
                            <i class="icon icon-return fs-40 text-primary"></i>
                        </div>
                        <div class="content">
                            <h6 class="mb_10">Easy Returns</h6>
                            <p class="text-paragraph">Hassle-free return policy</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-12 mb_30">
                    <div class="tf-icon-box text-center">
                        <div class="icon">
                            <i class="icon icon-headphone fs-40 text-primary"></i>
                        </div>
                        <div class="content">
                            <h6 class="mb_10">24/7 Support</h6>
                            <p class="text-paragraph">Round-the-clock customer service</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-12 mb_30">
                    <div class="tf-icon-box text-center">
                        <div class="icon">
                            <i class="icon icon-payment fs-40 text-primary"></i>
                        </div>
                        <div class="content">
                            <h6 class="mb_10">Secure Payment</h6>
                            <p class="text-paragraph">Safe and secure payment options</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@push('styles')
<style>
    .contact-info-item {
        padding: 20px;
        background: #f8f9fa;
        border-radius: 10px;
        transition: all 0.3s ease;
    }
    
    .contact-info-item:hover {
        background: #fff;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        transform: translateY(-2px);
    }
    
    .icon-box {
        width: 50px;
        height: 50px;
        background: var(--primary-color);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        margin-bottom: 15px;
    }
    
    .tf-icon-box {
        padding: 30px 20px;
        background: #fff;
        border-radius: 15px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        height: 100%;
    }
    
    .tf-icon-box:hover {
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        transform: translateY(-5px);
    }
    
    .tf-icon-box .icon {
        margin-bottom: 20px;
    }
    
    .form-contact-wrap {
        background: white;
        padding: 40px;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
    }
    
    .map-container {
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }
    
    .widget-menu-tab {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        margin-bottom: 40px;
        list-style: none;
        padding: 0;
    }
    
    .widget-menu-tab .item-title {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        padding: 12px 25px;
        border-radius: 25px;
        margin: 0 10px 10px 0;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .widget-menu-tab .item-title.active,
    .widget-menu-tab .item-title:hover {
        background: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
    }
    
    .widget-content-inner {
        display: none;
    }
    
    .widget-content-inner.active {
        display: block;
    }
    
    .bg-grey-13 {
        background-color: #f8f9fa;
    }
    
    .text-paragraph {
        color: #666;
        line-height: 1.6;
    }
    
    @media (max-width: 768px) {
        .form-contact-wrap {
            padding: 25px;
        }
        
        .tf-icon-box {
            padding: 20px 15px;
        }
        
        .contact-info-item {
            padding: 15px;
            margin-bottom: 15px;
        }
        
        .widget-menu-tab {
            justify-content: flex-start;
            overflow-x: auto;
            white-space: nowrap;
        }
        
        .widget-menu-tab .item-title {
            margin-right: 15px;
            flex-shrink: 0;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Tab functionality
        const tabButtons = document.querySelectorAll('.widget-menu-tab .item-title');
        const tabContents = document.querySelectorAll('.widget-content-inner');
        
        tabButtons.forEach((button, index) => {
            button.addEventListener('click', function() {
                // Remove active class from all buttons and contents
                tabButtons.forEach(btn => btn.classList.remove('active'));
                tabContents.forEach(content => content.classList.remove('active'));
                
                // Add active class to clicked button and corresponding content
                this.classList.add('active');
                tabContents[index].classList.add('active');
            });
        });
        
        // AJAX Contact Form Submission
        const contactForm = document.querySelector('.form-contact');
        if (contactForm) {
            contactForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const submitBtn = this.querySelector('button[type="submit"]');
                const originalBtnText = submitBtn.innerHTML;
                const formData = new FormData(this);
                
                // Handle newsletter subscription checkbox properly
                const newsletterCheckbox = this.querySelector('input[name="subscribe_newsletter"]');
                if (newsletterCheckbox) {
                    // Remove the default FormData value and set proper boolean
                    formData.delete('subscribe_newsletter');
                    formData.append('subscribe_newsletter', newsletterCheckbox.checked ? '1' : '0');
                }
                
                // Clear previous error messages
                document.querySelectorAll('.text-danger').forEach(el => el.remove());
                document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
                
                // Show loading state
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Sending Message...';
                
                // Send AJAX request
                fetch('{{ route("contact.submit") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message
                        showNotification(data.message, 'success');
                        
                        // Reset form
                        contactForm.reset();
                        
                        // Show additional success info if available
                        if (data.data && data.data.reference_id) {
                            showNotification('Reference ID: ' + data.data.reference_id, 'info', 5000);
                        }
                        
                        // Show newsletter subscription confirmation if applicable
                        if (data.newsletter_subscribed) {
                            showNotification('✓ Successfully subscribed to newsletter!', 'success', 4000);
                        }
                        
                    } else {
                        // Show error message
                        showNotification(data.message || 'An error occurred. Please try again.', 'error');
                        
                        // Display field-specific errors
                        if (data.errors) {
                            Object.keys(data.errors).forEach(fieldName => {
                                const field = document.querySelector(`[name="${fieldName}"]`);
                                if (field) {
                                    field.classList.add('is-invalid');
                                    
                                    // Create error message element
                                    const errorElement = document.createElement('small');
                                    errorElement.className = 'text-danger';
                                    errorElement.textContent = data.errors[fieldName][0];
                                    
                                    // Insert error message after the field
                                    field.parentNode.insertBefore(errorElement, field.nextSibling);
                                }
                            });
                        }
                    }
                })
                .catch(error => {
                    console.error('Contact form error:', error);
                    showNotification('Network error. Please check your internet connection and try again.', 'error');
                })
                .finally(() => {
                    // Restore button state
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;
                });
            });
        }
        
        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    });
    
    // Notification system
    function showNotification(message, type = 'info', duration = 6000) {
        // Remove existing notifications
        const existingNotifications = document.querySelectorAll('.notification-toast');
        existingNotifications.forEach(notification => notification.remove());
        
        // Create notification container if it doesn't exist
        let notificationContainer = document.querySelector('.notification-container');
        if (!notificationContainer) {
            notificationContainer = document.createElement('div');
            notificationContainer.className = 'notification-container';
            notificationContainer.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 9999;
                max-width: 400px;
            `;
            document.body.appendChild(notificationContainer);
        }
        
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `notification-toast alert alert-${type === 'error' ? 'danger' : type === 'info' ? 'info' : 'success'} alert-dismissible fade show`;
        notification.style.cssText = `
            margin-bottom: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            border-radius: 8px;
            animation: slideInRight 0.3s ease-out;
        `;
        
        // Get appropriate icon
        let icon = '';
        switch(type) {
            case 'success':
                icon = '<i class="icon icon-check-circle me-2"></i>';
                break;
            case 'error':
                icon = '<i class="icon icon-alert-triangle me-2"></i>';
                break;
            case 'info':
                icon = '<i class="icon icon-info me-2"></i>';
                break;
            default:
                icon = '<i class="icon icon-bell me-2"></i>';
        }
        
        notification.innerHTML = `
            <div class="d-flex align-items-start">
                ${icon}
                <div class="flex-grow-1">${message}</div>
                <button type="button" class="btn-close" onclick="this.closest('.notification-toast').remove()"></button>
            </div>
        `;
        
        notificationContainer.appendChild(notification);
        
        // Auto-remove notification after duration
        setTimeout(() => {
            if (notification.parentNode) {
                notification.style.animation = 'slideOutRight 0.3s ease-in';
                setTimeout(() => notification.remove(), 300);
            }
        }, duration);
    }
    
    // Add CSS animations
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        @keyframes slideOutRight {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }
        
        .form-control.is-invalid,
        .tf-select.is-invalid,
        input.is-invalid,
        textarea.is-invalid {
            border-color: #dc3545;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
        }
        
        .spinner-border-sm {
            width: 1rem;
            height: 1rem;
            border-width: 0.1em;
        }
        
        .notification-container {
            pointer-events: none;
        }
        
        .notification-toast {
            pointer-events: all;
        }
    `;
    document.head.appendChild(style);
</script>
@endpush
