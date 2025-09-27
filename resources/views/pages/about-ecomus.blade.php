@extends('layouts.ecomus')

@section('title', __('about.company_title', ['app_name' => config('app.name')]) . ' - ' . config('app.name'))
@section('description', 'Learn about our story, mission, and commitment to providing high-quality products and exceptional service.')

@push('styles')
<style>
/* Language Switcher */
.language-switcher {
    position: fixed;
    top: 100px;
    right: 20px;
    z-index: 1000;
    background: white;
    border-radius: 8px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    overflow: visible;
}

.language-switcher .dropdown {
    position: relative;
}

.language-switcher .dropdown-toggle {
    border: none;
    background: none;
    padding: 12px 16px;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 8px;
    min-width: 100px;
    cursor: pointer;
}

.language-switcher .dropdown-menu {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: white;
    border: none;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    border-radius: 8px;
    padding: 8px 0;
    margin: 0;
    opacity: 0;
    visibility: hidden;
    transform: translateY(-10px);
    transition: all 0.3s ease;
    min-width: 100px;
}

.language-switcher .dropdown-menu.show {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

.language-switcher .dropdown-item {
    padding: 8px 16px;
    font-weight: 500;
    transition: all 0.3s ease;
    color: #333;
    text-decoration: none;
    display: block;
}

.language-switcher .dropdown-item:hover {
    background-color: var(--primary-color);
    color: white;
}

/* Language switching transition */
.language-switching {
    opacity: 0.9;
    transition: opacity 0.3s ease;
}

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
<!-- Language Switcher -->
<div class="language-switcher">
    <div class="dropdown">
        <button class="dropdown-toggle" type="button" id="languageDropdown" aria-expanded="false">
            <i class="icon-globe"></i>
            {{ app()->getLocale() == 'bn' ? 'বাংলা' : 'English' }}
        </button>
        <ul class="dropdown-menu" id="languageMenu">
            <li>
                <a class="dropdown-item" href="#" data-lang="en">
                    English
                </a>
            </li>
            <li>
                <a class="dropdown-item" href="#" data-lang="bn">
                    বাংলা
                </a>
            </li>
        </ul>
    </div>
</div>

<!-- Hero Section -->
<section class="tf-slideshow about-us-page position-relative">
    <div class="banner-wrapper">
        <img class="lazyload" src="{{ asset('assets/ecomus/images/slider/about-banner-01.jpg') }}"
            data-src="{{ asset('assets/ecomus/images/slider/about-banner-01.jpg') }}" alt="About {{ config('app.name') }}">
        <div class="box-content text-center">
            <div class="container">
                <h1 class="about-hero-text text-white">{{ __('about.hero_title') }}</h1>
                <p class="text-white mt-3 fs-5 hero-subtitle">{{ __('about.hero_subtitle') }}</p>
            </div>
        </div>
    </div>
</section>

<!-- Company Introduction -->
<section class="flat-spacing-9">
    <div class="container">
        <div class="flat-title my-0 text-center">
            <span class="title company-title">{{ __('about.company_title', ['app_name' => config('app.name')]) }}</span>
            <p class="sub-title text_black-2 company-description">
                {{ __('about.company_description') }}
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
                    <div class="heading story-title">{{ __('about.story_title') }}</div>
                    <div class="text story-description">
                        {{ __('about.story_description', ['app_name' => config('app.name')]) }}
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
                    <div class="heading mission-title">{{ __('about.mission_title') }}</div>
                    <div class="text mission-description">
                        {{ __('about.mission_description') }}
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
                    <div class="stat-label">{{ __('about.stats_customers') }}</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-item">
                    <div class="stat-number">1000+</div>
                    <div class="stat-label">{{ __('about.stats_products') }}</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-item">
                    <div class="stat-number">25+</div>
                    <div class="stat-label">{{ __('about.stats_countries') }}</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-item">
                    <div class="stat-number">99.9%</div>
                    <div class="stat-label">{{ __('about.stats_uptime') }}</div>
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
                <span class="title fw-5">{{ __('about.excellence_title') }}</span>
                <div>
                    <p class="sub-title text_black-2">{{ __('about.excellence_subtitle_1') }}</p>
                    <p class="sub-title text_black-2">{{ __('about.excellence_subtitle_2') }}</p>
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
                                        <div class="title">{{ __('about.feature_quality_title') }}</div>
                                        <p class="text_black-2">{{ __('about.feature_quality_description') }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="tf-icon-box text-center">
                                    <div class="icon">
                                        <i class="icon-design"></i>
                                    </div>
                                    <div class="content">
                                        <div class="title">{{ __('about.feature_technology_title') }}</div>
                                        <p class="text_black-2">{{ __('about.feature_technology_description') }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="tf-icon-box text-center">
                                    <div class="icon">
                                        <i class="icon-sizes"></i>
                                    </div>
                                    <div class="content">
                                        <div class="title">{{ __('about.feature_support_title') }}</div>
                                        <p class="text_black-2">{{ __('about.feature_support_description') }}</p>
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
            <span class="title">{{ __('about.team_title') }}</span>
            <p class="sub-title text_black-2">{{ __('about.team_subtitle', ['app_name' => config('app.name')]) }}</p>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="team-member">
                    <img src="{{ asset('assets/ecomus/images/item/tets3.jpg') }}" alt="CEO">
                    <h4>John Anderson</h4>
                    <div class="position">{{ __('about.team_ceo') }}</div>
                    <p class="text_black-2">{{ __('about.team_ceo_description') }}</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="team-member">
                    <img src="{{ asset('assets/ecomus/images/item/tets4.jpg') }}" alt="CTO">
                    <h4>Sarah Mitchell</h4>
                    <div class="position">{{ __('about.team_cto') }}</div>
                    <p class="text_black-2">{{ __('about.team_cto_description') }}</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="team-member">
                    <img src="{{ asset('assets/ecomus/images/item/tets3.jpg') }}" alt="COO">
                    <h4>Michael Roberts</h4>
                    <div class="position">{{ __('about.team_coo') }}</div>
                    <p class="text_black-2">{{ __('about.team_coo_description') }}</p>
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
                                <h4 class="mb_40">{{ __('about.testimonials_title') }}</h4>
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
                                    "{{ __('about.testimonial_1', ['app_name' => config('app.name')]) }}"
                                </p>
                                <div class="author box-author">
                                    <div class="box-img d-md-none rounded-0">
                                        <img class="lazyload img-product" data-src="{{ asset('assets/ecomus/images/item/tets3.jpg') }}"
                                            src="{{ asset('assets/ecomus/images/item/tets3.jpg') }}" alt="testimonial">
                                    </div>
                                    <div class="content">
                                        <div class="name">{{ __('about.testimonial_1_author') }}</div>
                                        <div class="text_black-2">{{ __('about.testimonial_1_position') }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="testimonial-item lg lg-2">
                                <h4 class="mb_40">{{ __('about.testimonials_title') }}</h4>
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
                                    "{{ __('about.testimonial_2', ['app_name' => config('app.name')]) }}"
                                </p>
                                <div class="author box-author">
                                    <div class="box-img d-md-none rounded-0">
                                        <img class="lazyload img-product" data-src="{{ asset('assets/ecomus/images/item/tets4.jpg') }}"
                                            src="{{ asset('assets/ecomus/images/item/tets4.jpg') }}" alt="testimonial">
                                    </div>
                                    <div class="content">
                                        <div class="name">{{ __('about.testimonial_2_author') }}</div>
                                        <div class="text_black-2">{{ __('about.testimonial_2_position') }}</div>
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
                <span class="title">{{ __('about.cta_title') }}</span>
                <p class="sub-title text_black-2">
                    {{ __('about.cta_subtitle', ['app_name' => config('app.name')]) }}
                </p>
            </div>
            <div class="d-flex justify-content-center gap-3 flex-wrap">
                <a href="{{ route('register') }}" class="tf-btn btn-fill animate-hover-btn radius-3">
                    <span>{{ __('about.cta_join') }}</span>
                </a>
                <a href="{{ route('contact.show') }}" class="tf-btn btn-outline animate-hover-btn radius-3">
                    <span>{{ __('about.cta_contact') }}</span>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Shop Gram -->
<section class="flat-spacing-1">
    <div class="container">
        <div class="flat-title">
            <span class="title">{{ __('about.gallery_title') }}</span>
            <p class="sub-title">{{ __('about.gallery_subtitle') }}</p>
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
    // Language switcher dropdown functionality
    const languageDropdown = document.getElementById('languageDropdown');
    const languageMenu = document.getElementById('languageMenu');
    
    if (languageDropdown && languageMenu) {
        languageDropdown.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const isOpen = languageMenu.classList.contains('show');
            
            // Close all dropdowns first
            document.querySelectorAll('.dropdown-menu.show').forEach(menu => {
                menu.classList.remove('show');
            });
            
            // Toggle current dropdown
            if (!isOpen) {
                languageMenu.classList.add('show');
            }
        });
        
        // Handle language selection
        languageMenu.addEventListener('click', function(e) {
            if (e.target.classList.contains('dropdown-item')) {
                e.preventDefault();
                const selectedLang = e.target.getAttribute('data-lang');
                switchLanguage(selectedLang);
            }
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!languageDropdown.contains(e.target)) {
                languageMenu.classList.remove('show');
            }
        });
        
        // Close dropdown when pressing Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                languageMenu.classList.remove('show');
            }
        });
    }

    // Language switching function
    function switchLanguage(locale) {
        // Close dropdown
        languageMenu.classList.remove('show');
        
        // Show loading state
        const currentText = languageDropdown.innerHTML;
        languageDropdown.innerHTML = '<i class="icon-globe"></i> Loading...';
        languageDropdown.disabled = true;
        
        // Get translations from API
        Promise.all([
            // Set locale in session
            $.ajax({
                url: '{{ route("lang.switch", ":locale") }}'.replace(':locale', locale),
                type: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            }),
            // Get translations
            $.ajax({
                url: '/api/translations/' + locale,
                type: 'GET'
            })
        ]).then(function(responses) {
            const translations = responses[1];
            
            // Update content dynamically
            updatePageContent(translations);
            
            // Update dropdown button text
            const newText = locale === 'bn' ? 'বাংলা' : 'English';
            languageDropdown.innerHTML = '<i class="icon-globe"></i> ' + newText;
            languageDropdown.disabled = false;
        }).catch(function() {
            // Restore original text on error
            languageDropdown.innerHTML = currentText;
            languageDropdown.disabled = false;
            console.error('Language switching failed');
        });
    }

    // Update page content based on translations
    function updatePageContent(translations) {
        // Update specific elements by class
        $('.about-hero-text').text(translations.hero_title);
        $('.hero-subtitle').text(translations.hero_subtitle);
        $('.company-title').text(translations.company_title);
        $('.company-description').text(translations.company_description);
        $('.story-title').text(translations.story_title);
        $('.story-description').text(translations.story_description);
        $('.mission-title').text(translations.mission_title);
        $('.mission-description').text(translations.mission_description);
        
        // Update stats labels
        $('.stat-label').each(function(index) {
            const keys = ['stats_customers', 'stats_products', 'stats_countries', 'stats_uptime'];
            if (translations[keys[index]]) {
                $(this).text(translations[keys[index]]);
            }
        });
        
        // Update excellence section
        $('.flat-title .title:contains("Excellence")').text(translations.excellence_title);
        $('.flat-title .sub-title').eq(0).text(translations.excellence_subtitle_1);
        $('.flat-title .sub-title').eq(1).text(translations.excellence_subtitle_2);
        
        // Update feature boxes
        $('.tf-icon-box').each(function(index) {
            const featureKeys = [
                ['feature_quality_title', 'feature_quality_description'],
                ['feature_technology_title', 'feature_technology_description'], 
                ['feature_support_title', 'feature_support_description']
            ];
            
            if (featureKeys[index]) {
                $(this).find('.title').text(translations[featureKeys[index][0]]);
                $(this).find('.text_black-2').text(translations[featureKeys[index][1]]);
            }
        });
        
        // Update team section
        $('.team-section .title').text(translations.team_title);
        $('.team-section .sub-title').text(translations.team_subtitle);
        
        // Update testimonials
        $('.testimonial-item h4').text(translations.testimonials_title);
        
        // Update CTA section
        $('.bg_grey-2 .title').text(translations.cta_title);
        $('.bg_grey-2 .sub-title').text(translations.cta_subtitle);
        $('.tf-btn span').eq(0).text(translations.cta_join);
        $('.tf-btn span').eq(1).text(translations.cta_contact);
        
        // Update gallery section
        $('.wrap-shop-gram').prev('.flat-title').find('.title').text(translations.gallery_title);
        $('.wrap-shop-gram').prev('.flat-title').find('.sub-title').text(translations.gallery_subtitle);
        
        // Smooth transition effect
        $('body').addClass('language-switching');
        setTimeout(function() {
            $('body').removeClass('language-switching');
        }, 300);
    }

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