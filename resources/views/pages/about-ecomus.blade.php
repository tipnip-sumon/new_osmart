@extends('layouts.ecomus')

@section('title', 'About Us - ' . config('app.name'))
@section('description', 'Learn about our story, mission, and commitment to providing high-quality products and exceptional service.')

@push('styles')
<style>
/* Custom About Page Styles */
.tf-slideshow.about-us-page {
    height: 60vh;
    min-height: 400px;
}

.tf-slideshow .banner-wrapper {
    position: relative;
    width: 100%;
    height: 100%;
    overflow: hidden;
}

.tf-slideshow .banner-wrapper img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.tf-slideshow .box-content {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 2;
}

.tf-slideshow .box-content::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.4);
    z-index: -1;
    border-radius: 12px;
    padding: 20px;
    margin: -20px;
}

.about-hero-text {
    font-size: 3rem;
    font-weight: 700;
    line-height: 1.2;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
}

.flat-image-text-section .tf-image-wrap img {
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.flat-image-text-section .heading {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 1.5rem;
    color: var(--primary-color);
}

.flat-image-text-section .text {
    font-size: 1.1rem;
    line-height: 1.8;
    color: #666;
}

.bg_grey-2 {
    background-color: #f8f9fa !important;
}

.flat-iconbox-v3 .tf-icon-box {
    padding: 2rem;
    border-radius: 12px;
    background: white;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    height: 100%;
}

.flat-iconbox-v3 .tf-icon-box:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.1);
}

.flat-iconbox-v3 .icon i {
    font-size: 3rem;
    color: var(--primary-color);
}

.flat-iconbox-v3 .title {
    font-size: 1.3rem;
    font-weight: 600;
    margin: 1rem 0;
}

.testimonial-item {
    background: white;
    padding: 3rem;
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.stats-section {
    background: linear-gradient(135deg, var(--primary-color), #2c3e50);
    color: white;
    padding: 4rem 0;
    margin: 4rem 0;
}

.stat-item {
    text-align: center;
    padding: 2rem;
}

.stat-number {
    font-size: 3rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.stat-label {
    font-size: 1.1rem;
    opacity: 0.9;
}

.team-section .team-member {
    text-align: center;
    padding: 2rem;
    background: white;
    border-radius: 12px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
}

.team-section .team-member:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.15);
}

.team-member img {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    margin-bottom: 1.5rem;
    object-fit: cover;
    border: 4px solid var(--primary-color);
}

.team-member h4 {
    font-size: 1.3rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.team-member .position {
    color: var(--primary-color);
    font-weight: 500;
    margin-bottom: 1rem;
}

@media (max-width: 768px) {
    .about-hero-text {
        font-size: 2rem;
    }
    
    .flat-image-text-section .heading {
        font-size: 2rem;
    }
    
    .stat-number {
        font-size: 2.5rem;
    }
}
</style>
@endpush

@section('content')
<!-- Hero Section -->
<section class="tf-slideshow about-us-page position-relative">
    <div class="banner-wrapper">
        <img class="lazyload" src="{{ asset('assets/ecomus/images/slider/about-banner-01.jpg') }}"
            data-src="{{ asset('assets/ecomus/images/slider/about-banner-01.jpg') }}" alt="About {{ config('app.name') }}">
        <div class="box-content text-center">
            <div class="container">
                <h1 class="about-hero-text text-white">Empowering Success Through <br class="d-xl-block d-none"> Innovation & Quality</h1>
                <p class="text-white mt-3 fs-5">Building the future of e-commerce with integrity and excellence</p>
            </div>
        </div>
    </div>
</section>

<!-- Company Introduction -->
<section class="flat-spacing-9">
    <div class="container">
        <div class="flat-title my-0 text-center">
            <span class="title">We are {{ config('app.name') }}</span>
            <p class="sub-title text_black-2">
                Welcome to our innovative e-commerce platform, where we believe <br class="d-xl-block d-none">
                that success comes from combining cutting-edge technology with exceptional <br class="d-xl-block d-none">
                products and unwavering commitment to our customers and partners. <br class="d-xl-block d-none">
                Join thousands who have transformed their lives through our platform.
            </p>
        </div>
    </div>
</section>

<div class="container">
    <div class="line"></div>
</div>

<!-- Our Story -->
<section class="flat-spacing-23 flat-image-text-section">
    <div class="container">
        <div class="tf-grid-layout md-col-2 tf-img-with-text style-4">
            <div class="tf-image-wrap">
                <img class="lazyload w-100" data-src="{{ asset('assets/ecomus/images/collections/collection-69.jpg') }}"
                    src="{{ asset('assets/ecomus/images/collections/collection-69.jpg') }}" alt="Our Story">
            </div>
            <div class="tf-content-wrap px-0 d-flex justify-content-center w-100">
                <div>
                    <div class="heading">Established - {{ date('Y', strtotime('-5 years')) }}</div>
                    <div class="text">
                        {{ config('app.name') }} was founded with a vision to revolutionize the way people <br class="d-xl-block d-none">
                        do business online. Our founders, passionate entrepreneurs with decades <br class="d-xl-block d-none">
                        of combined experience, identified the need for a platform that truly <br class="d-xl-block d-none">
                        empowers individuals to build sustainable businesses while providing <br class="d-xl-block d-none">
                        exceptional value to customers. From our humble beginnings, we've <br class="d-xl-block d-none">
                        grown into a trusted platform serving thousands worldwide.
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Our Mission -->
<section class="flat-spacing-15">
    <div class="container">
        <div class="tf-grid-layout md-col-2 tf-img-with-text style-4">
            <div class="tf-content-wrap px-0 d-flex justify-content-center w-100">
                <div>
                    <div class="heading">Our Mission</div>
                    <div class="text">
                        Our mission is to empower entrepreneurs through innovative technology <br class="d-xl-block d-none">
                        and exceptional products. We strive to create opportunities for financial <br class="d-xl-block d-none">
                        freedom while maintaining the highest standards of integrity, transparency, <br class="d-xl-block d-none">
                        and customer satisfaction. We believe that success should be accessible <br class="d-xl-block d-none">
                        to everyone, regardless of their background or experience level.
                    </div>
                </div>
            </div>
            <div class="grid-img-group">
                <div class="tf-image-wrap box-img item-1">
                    <div class="img-style">
                        <img class="lazyload" src="{{ asset('assets/ecomus/images/collections/collection-71.jpg') }}"
                            data-src="{{ asset('assets/ecomus/images/collections/collection-71.jpg') }}" alt="Our Mission">
                    </div>
                </div>
                <div class="tf-image-wrap box-img item-2">
                    <div class="img-style">
                        <img class="lazyload" src="{{ asset('assets/ecomus/images/collections/collection-70.jpg') }}"
                            data-src="{{ asset('assets/ecomus/images/collections/collection-70.jpg') }}" alt="Our Values">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="stats-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-6">
                <div class="stat-item">
                    <div class="stat-number">50K+</div>
                    <div class="stat-label">Happy Customers</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-item">
                    <div class="stat-number">1000+</div>
                    <div class="stat-label">Products Available</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-item">
                    <div class="stat-number">25+</div>
                    <div class="stat-label">Countries Served</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-item">
                    <div class="stat-number">99.9%</div>
                    <div class="stat-label">Uptime Guarantee</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Why Choose Us -->
<section>
    <div class="container">
        <div class="bg_grey-2 radius-10 flat-wrap-iconbox">
            <div class="flat-title lg">
                <span class="title fw-5">Excellence is our priority</span>
                <div>
                    <p class="sub-title text_black-2">Our dedicated team has built a platform that exceeds expectations in every aspect.</p>
                    <p class="sub-title text_black-2">We continuously innovate to provide you with the best possible experience and results.</p>
                </div>
            </div>
            <div class="flat-iconbox-v3 lg">
                <div class="wrap-carousel wrap-mobile">
                    <div dir="ltr" class="swiper tf-sw-mobile" data-preview="1" data-space="15">
                        <div class="swiper-wrapper wrap-iconbox lg">
                            <div class="swiper-slide">
                                <div class="tf-icon-box text-center">
                                    <div class="icon">
                                        <i class="icon-materials"></i>
                                    </div>
                                    <div class="content">
                                        <div class="title">Premium Quality Products</div>
                                        <p class="text_black-2">We carefully curate and source only the highest quality products, ensuring that every item in our catalog meets our rigorous standards for excellence and customer satisfaction.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="tf-icon-box text-center">
                                    <div class="icon">
                                        <i class="icon-design"></i>
                                    </div>
                                    <div class="content">
                                        <div class="title">Innovative Technology</div>
                                        <p class="text_black-2">Our platform leverages cutting-edge technology to provide seamless user experiences, advanced analytics, and powerful tools that help our partners succeed.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="tf-icon-box text-center">
                                    <div class="icon">
                                        <i class="icon-sizes"></i>
                                    </div>
                                    <div class="content">
                                        <div class="title">Comprehensive Support</div>
                                        <p class="text_black-2">From training resources to 24/7 customer support, we provide everything you need to succeed. Our dedicated team is always here to help you achieve your goals.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="sw-dots style-2 sw-pagination-mb justify-content-center"></div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Team Section -->
<section class="flat-spacing-23 team-section">
    <div class="container">
        <div class="flat-title text-center">
            <span class="title">Meet Our Leadership Team</span>
            <p class="sub-title text_black-2">The visionaries and experts behind {{ config('app.name') }}</p>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="team-member">
                    <img src="{{ asset('assets/ecomus/images/item/tets3.jpg') }}" alt="CEO">
                    <h4>John Anderson</h4>
                    <div class="position">Chief Executive Officer</div>
                    <p class="text_black-2">Visionary leader with 15+ years in e-commerce and business development.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="team-member">
                    <img src="{{ asset('assets/ecomus/images/item/tets4.jpg') }}" alt="CTO">
                    <h4>Sarah Mitchell</h4>
                    <div class="position">Chief Technology Officer</div>
                    <p class="text_black-2">Technology innovator driving our platform's cutting-edge capabilities.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="team-member">
                    <img src="{{ asset('assets/ecomus/images/item/tets3.jpg') }}" alt="COO">
                    <h4>Michael Roberts</h4>
                    <div class="position">Chief Operations Officer</div>
                    <p class="text_black-2">Operations expert ensuring smooth processes and exceptional service.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials -->
<section class="flat-testimonial-v2 flat-spacing-24">
    <div class="container">
        <div class="wrapper-thumbs-testimonial-v2 flat-thumbs-testimonial">
            <div class="box-left">
                <div dir="ltr" class="swiper tf-sw-tes-2" data-preview="1" data-space-lg="40" data-space-md="30">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide">
                            <div class="testimonial-item lg lg-2">
                                <h4 class="mb_40">What our partners say</h4>
                                <div class="icon">
                                    <img class="lazyload" data-src="{{ asset('assets/ecomus/images/item/quote.svg') }}" alt=""
                                        src="{{ asset('assets/ecomus/images/item/quote.svg') }}">
                                </div>
                                <div class="rating">
                                    <i class="icon-star"></i>
                                    <i class="icon-star"></i>
                                    <i class="icon-star"></i>
                                    <i class="icon-star"></i>
                                    <i class="icon-star"></i>
                                </div>
                                <p class="text">
                                    "{{ config('app.name') }} has completely transformed my business. The platform is intuitive, the support is exceptional, and the earning potential is incredible. I've been able to build a sustainable income stream while helping others succeed."
                                </p>
                                <div class="author box-author">
                                    <div class="box-img d-md-none rounded-0">
                                        <img class="lazyload img-product" data-src="{{ asset('assets/ecomus/images/item/tets3.jpg') }}"
                                            src="{{ asset('assets/ecomus/images/item/tets3.jpg') }}" alt="testimonial">
                                    </div>
                                    <div class="content">
                                        <div class="name">Emily Johnson</div>
                                        <div class="text_black-2">Business Partner</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="testimonial-item lg lg-2">
                                <h4 class="mb_40">What our partners say</h4>
                                <div class="icon">
                                    <img class="lazyload" data-src="{{ asset('assets/ecomus/images/item/quote.svg') }}" alt=""
                                        src="{{ asset('assets/ecomus/images/item/quote.svg') }}">
                                </div>
                                <div class="rating">
                                    <i class="icon-star"></i>
                                    <i class="icon-star"></i>
                                    <i class="icon-star"></i>
                                    <i class="icon-star"></i>
                                    <i class="icon-star"></i>
                                </div>
                                <p class="text">
                                    "The training and support provided by {{ config('app.name') }} is outstanding. They truly care about your success and provide all the tools and resources needed to build a thriving business. Highly recommended!"
                                </p>
                                <div class="author box-author">
                                    <div class="box-img d-md-none rounded-0">
                                        <img class="lazyload img-product" data-src="{{ asset('assets/ecomus/images/item/tets4.jpg') }}"
                                            src="{{ asset('assets/ecomus/images/item/tets4.jpg') }}" alt="testimonial">
                                    </div>
                                    <div class="content">
                                        <div class="name">David Thompson</div>
                                        <div class="text_black-2">Senior Partner</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-md-flex d-none box-sw-navigation">
                    <div class="nav-sw nav-next-slider nav-next-tes-2"><span class="icon icon-arrow-left"></span></div>
                    <div class="nav-sw nav-prev-slider nav-prev-tes-2"><span class="icon icon-arrow-right"></span></div>
                </div>
                <div class="d-md-none sw-dots style-2 sw-pagination-tes-2"></div>
            </div>
            <div class="box-right">
                <div dir="ltr" class="swiper tf-thumb-tes" data-preview="1" data-space="30">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide">
                            <div class="img-sw-thumb">
                                <img class="lazyload img-product" data-src="{{ asset('assets/ecomus/images/item/tets3.jpg') }}"
                                    src="{{ asset('assets/ecomus/images/item/tets3.jpg') }}" alt="testimonial">
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="img-sw-thumb">
                                <img class="lazyload img-product" data-src="{{ asset('assets/ecomus/images/item/tets4.jpg') }}"
                                    src="{{ asset('assets/ecomus/images/item/tets4.jpg') }}" alt="testimonial">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="container">
    <div class="line"></div>
</div>

<!-- Call to Action -->
<section class="flat-spacing-23">
    <div class="container">
        <div class="bg_grey-2 radius-10 text-center p-5">
            <div class="flat-title">
                <span class="title">Ready to Get Started?</span>
                <p class="sub-title text_black-2">
                    Join thousands of successful partners who have transformed their lives with {{ config('app.name') }}
                </p>
            </div>
            <div class="d-flex justify-content-center gap-3 flex-wrap">
                <a href="{{ route('register') }}" class="tf-btn btn-fill animate-hover-btn radius-3">
                    <span>Join Our Community</span>
                </a>
                <a href="{{ route('contact.show') }}" class="tf-btn btn-outline animate-hover-btn radius-3">
                    <span>Contact Us</span>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Shop Gram -->
<section class="flat-spacing-1">
    <div class="container">
        <div class="flat-title">
            <span class="title">Follow Our Journey</span>
            <p class="sub-title">Connect with us and see the latest updates from our community.</p>
        </div>
        <div class="wrap-shop-gram">
            <div dir="ltr" class="swiper tf-sw-shop-gallery" data-preview="5" data-tablet="3" data-mobile="2"
                data-space-lg="7" data-space-md="7">
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <div class="gallery-item hover-img">
                            <div class="img-style">
                                <img class="lazyload img-hover" data-src="{{ asset('assets/ecomus/images/shop/gallery/gallery-7.jpg') }}"
                                    src="{{ asset('assets/ecomus/images/shop/gallery/gallery-7.jpg') }}" alt="gallery">
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="gallery-item hover-img">
                            <div class="img-style">
                                <img class="lazyload img-hover" data-src="{{ asset('assets/ecomus/images/shop/gallery/gallery-3.jpg') }}"
                                    src="{{ asset('assets/ecomus/images/shop/gallery/gallery-3.jpg') }}" alt="gallery">
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="gallery-item hover-img">
                            <div class="img-style">
                                <img class="lazyload img-hover" data-src="{{ asset('assets/ecomus/images/shop/gallery/gallery-5.jpg') }}"
                                    src="{{ asset('assets/ecomus/images/shop/gallery/gallery-5.jpg') }}" alt="gallery">
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="gallery-item hover-img">
                            <div class="img-style">
                                <img class="lazyload img-hover" data-src="{{ asset('assets/ecomus/images/shop/gallery/gallery-8.jpg') }}"
                                    src="{{ asset('assets/ecomus/images/shop/gallery/gallery-8.jpg') }}" alt="gallery">
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="gallery-item hover-img">
                            <div class="img-style">
                                <img class="lazyload img-hover" data-src="{{ asset('assets/ecomus/images/shop/gallery/gallery-6.jpg') }}"
                                    src="{{ asset('assets/ecomus/images/shop/gallery/gallery-6.jpg') }}" alt="gallery">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="sw-dots sw-pagination-gallery justify-content-center"></div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize Swiper for mobile carousel
    if (typeof Swiper !== 'undefined') {
        // Mobile iconbox carousel
        new Swiper('.tf-sw-mobile', {
            slidesPerView: 1,
            spaceBetween: 15,
            pagination: {
                el: '.sw-pagination-mb',
                clickable: true
            },
            breakpoints: {
                768: {
                    slidesPerView: 2,
                    spaceBetween: 20
                },
                1024: {
                    slidesPerView: 3,
                    spaceBetween: 30
                }
            }
        });

        // Testimonial carousel
        new Swiper('.tf-sw-tes-2', {
            slidesPerView: 1,
            spaceBetween: 30,
            navigation: {
                nextEl: '.nav-next-tes-2',
                prevEl: '.nav-prev-tes-2',
            },
            pagination: {
                el: '.sw-pagination-tes-2',
                clickable: true
            }
        });

        // Gallery carousel
        new Swiper('.tf-sw-shop-gallery', {
            slidesPerView: 2,
            spaceBetween: 7,
            pagination: {
                el: '.sw-pagination-gallery',
                clickable: true
            },
            breakpoints: {
                576: {
                    slidesPerView: 3
                },
                1024: {
                    slidesPerView: 5
                }
            }
        });
    }

    // Animate stats on scroll
    function animateStats() {
        $('.stat-number').each(function() {
            const $this = $(this);
            const target = $this.text().replace(/[^\d.]/g, '');
            const isPercent = $this.text().includes('%');
            const hasPlus = $this.text().includes('+');
            
            if (target) {
                $({ counter: 0 }).animate({ counter: parseFloat(target) }, {
                    duration: 2000,
                    easing: 'swing',
                    step: function() {
                        let value = Math.ceil(this.counter);
                        if (isPercent) {
                            $this.text(value + '%');
                        } else if (hasPlus) {
                            $this.text(value.toLocaleString() + '+');
                        } else {
                            $this.text(value.toLocaleString());
                        }
                    }
                });
            }
        });
    }

    // Trigger animation when stats section is in view
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateStats();
                observer.unobserve(entry.target);
            }
        });
    });

    if ($('.stats-section').length) {
        observer.observe($('.stats-section')[0]);
    }
});
</script>
@endpush