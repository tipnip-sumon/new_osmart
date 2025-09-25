@extends('layouts.ecomus')

@section('title', 'Home - ' . config('app.name'))
@section('description', 'Welcome to ' . config('app.name') . ' - Your premier multivendor ecommerce destination with modern design and seamless shopping experience')

@push('styles')
<style>
/* Custom styles for Laravel integration */
.announcement-bar {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.tf-product-card {
    transition: all 0.3s ease;
}

.tf-product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}

/* Mobile optimizations */
@media (max-width: 768px) {
    .slider-effect .box-content h1 {
        font-size: 2rem;
    }
    
    .slider-effect .box-content .desc {
        font-size: 0.9rem;
    }
}
</style>
@endpush

@section('content')
<!-- Announcement Bar -->
<div class="announcement-bar bg_violet">
    <div class="wrap-announcement-bar">
        <div class="box-sw-announcement-bar speed-1">
            <div class="announcement-bar-item">
                <p>FREE SHIPPING AND RETURNS</p>
            </div>
            <div class="announcement-bar-item">
                <p>NEW SEASON, NEW STYLES: FASHION SALE YOU CAN'T MISS</p>
            </div>
            <div class="announcement-bar-item">
                <p>LIMITED TIME OFFER: FASHION SALE YOU CAN'T RESIST</p>
            </div>
            <div class="announcement-bar-item">
                <p>FREE SHIPPING AND RETURNS</p>
            </div>
            <div class="announcement-bar-item">
                <p>NEW SEASON, NEW STYLES: FASHION SALE YOU CAN'T MISS</p>
            </div>
            <div class="announcement-bar-item">
                <p>LIMITED TIME OFFER: FASHION SALE YOU CAN'T RESIST</p>
            </div>
        </div>
    </div>
    <span class="icon-close close-announcement-bar"></span>
</div>
<!-- /Announcement Bar -->

<!-- Header -->
<header id="header" class="header-default header-style-2">
    <div class="main-header line">
        <div class="container-full px_15 lg-px_40">
            <div class="row wrapper-header align-items-center">
                <div class="col-xl-5 tf-md-hidden">
                    <div class="tf-cur">
                        <div class="tf-currencies">
                            <select class="image-select center style-default type-currencies">
                                <option data-thumbnail="{{ asset('assets/ecomus/images/country/fr.svg') }}">EUR € | France</option>
                                <option data-thumbnail="{{ asset('assets/ecomus/images/country/de.svg') }}">EUR € | Germany</option>
                                <option selected data-thumbnail="{{ asset('assets/ecomus/images/country/us.svg') }}">USD $ | United States</option>
                                <option data-thumbnail="{{ asset('assets/ecomus/images/country/vn.svg') }}">VND ₫ | Vietnam</option>
                            </select>
                        </div>
                        <div class="tf-languages">
                            <select class="image-select center style-default type-languages">
                                <option>English</option>
                                <option>العربية</option>
                                <option>简体中文</option>
                                <option>اردو</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-3 tf-lg-hidden">
                    <a href="#mobileMenu" data-bs-toggle="offcanvas" aria-controls="offcanvasLeft">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="16" viewBox="0 0 24 16" fill="none">
                            <path d="M2.00056 2.28571H16.8577C17.1608 2.28571 17.4515 2.16531 17.6658 1.95098C17.8802 1.73665 18.0006 1.44596 18.0006 1.14286C18.0006 0.839753 17.8802 0.549063 17.6658 0.334735C17.4515 0.120408 17.1608 0 16.8577 0H2.00056C1.69745 0 1.40676 0.120408 1.19244 0.334735C0.978109 0.549063 0.857702 0.839753 0.857702 1.14286C0.857702 1.44596 0.978109 1.73665 1.19244 1.95098C1.40676 2.16531 1.69745 2.28571 2.00056 2.28571ZM0.857702 8C0.857702 7.6969 0.978109 7.40621 1.19244 7.19188C1.40676 6.97755 1.69745 6.85714 2.00056 6.85714H22.572C22.8751 6.85714 23.1658 6.97755 23.3801 7.19188C23.5944 7.40621 23.7148 7.6969 23.7148 8C23.7148 8.30311 23.5944 8.59379 23.3801 8.80812C23.1658 9.02245 22.8751 9.14286 22.572 9.14286H2.00056C1.69745 9.14286 1.40676 9.02245 1.19244 8.80812C0.978109 8.59379 0.857702 8.30311 0.857702 8ZM0.857702 14.8571C0.857702 14.554 0.978109 14.2633 1.19244 14.049C1.40676 13.8347 1.69745 13.7143 2.00056 13.7143H12.2863C12.5894 13.7143 12.8801 13.8347 13.0944 14.049C13.3087 14.2633 13.4291 14.554 13.4291 14.8571C13.4291 15.1602 13.3087 15.4509 13.0944 15.6653C12.8801 15.8796 12.5894 16 12.2863 16H2.00056C1.69745 16 1.40676 15.8796 1.19244 15.6653C0.978109 15.4509 0.857702 15.1602 0.857702 14.8571Z" fill="currentColor"></path>
                        </svg>
                    </a>
                </div>
                <div class="col-xl-2 col-md-4 col-6 text-center">
                    <a href="{{ route('home') }}" class="logo-header">
                        <img src="{{ siteLogo() }}" alt="{{ config('app.name') }}" class="logo">
                    </a>
                </div>

                <div class="col-xl-5 col-md-4 col-3">
                    <ul class="nav-icon d-flex justify-content-end align-items-center gap-20">
                        <li class="nav-search">
                            <a href="#canvasSearch" data-bs-toggle="offcanvas" aria-controls="offcanvasLeft" class="nav-icon-item">
                                <i class="icon icon-search"></i>
                            </a>
                        </li>
                        <li class="nav-account">
                            @auth
                                <a href="{{ route('member.dashboard') }}" class="nav-icon-item">
                                    <i class="icon icon-account"></i>
                                </a>
                            @else
                                <a href="#login" data-bs-toggle="modal" class="nav-icon-item">
                                    <i class="icon icon-account"></i>
                                </a>
                            @endauth
                        </li>
                        <li class="nav-wishlist">
                            <a href="{{ route('wishlist.index') }}" class="nav-icon-item">
                                <i class="icon icon-heart"></i>
                                <span class="count-box" id="wishlist-count">0</span>
                            </a>
                        </li>
                        <li class="nav-cart">
                            <a href="#shoppingCart" data-bs-toggle="modal" class="nav-icon-item">
                                <i class="icon icon-bag"></i>
                                <span class="count-box" id="cart-count">0</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="header-bottom line tf-md-hidden">
        <div class="container-full px_15 lg-px_40">
            <div class="wrapper-header d-flex justify-content-center align-items-center">
                <nav class="box-navigation text-center">
                    <ul class="box-nav-ul d-flex align-items-center justify-content-center gap-30">
                        <li class="menu-item">
                            <a href="{{ route('home') }}" class="item-link">Home</a>
                        </li>
                        <li class="menu-item">
                            <a href="{{ route('shop.index') }}" class="item-link">Shop<i class="icon icon-arrow-down"></i></a>
                            <div class="sub-menu">
                                <ul class="menu-list">
                                    <li><a href="{{ route('shop.index') }}" class="menu-link-text link">All Products</a></li>
                                    <li><a href="{{ route('categories.index') }}" class="menu-link-text link">Categories</a></li>
                                    <li><a href="{{ route('collections.index') }}" class="menu-link-text link">Collections</a></li>
                                    <li><a href="{{ route('brands.index') }}" class="menu-link-text link">Brands</a></li>
                                </ul>
                            </div>
                        </li>
                        <li class="menu-item">
                            <a href="{{ route('pages.about') }}" class="item-link">About</a>
                        </li>
                        <li class="menu-item">
                            <a href="{{ route('contact.show') }}" class="item-link">Contact</a>
                        </li>
                        @auth
                        <li class="menu-item">
                            <a href="{{ route('member.dashboard') }}" class="item-link">My Account</a>
                        </li>
                        @endauth
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</header>
<!-- /Header -->

<!-- Slider -->
<section class="tf-slideshow slideshow-effect slider-effect-fade position-relative">
    <div dir="ltr" class="swiper tf-sw-effect">
        <div class="swiper-wrapper">
            <div class="swiper-slide" lazy="true">
                <div class="slider-effect wrap-slider">
                    <div class="content-left">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-6 col-12">
                                    <div class="box-content">
                                        <h1 class="heading fade-item fade-item-1">Summer<br> Escapades</h1>
                                        <p class="desc fade-item fade-item-2">Embrace the sun-kissed season with our collection of breezy</p>
                                        <a href="{{ route('collections.show', 'summer') }}" class="fade-item fade-item-3 tf-btn btn-light-icon animate-hover-btn btn-xl radius-3">
                                            <span>Shop collection</span><i class="icon icon-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="img-slider">
                        <img class="lazyload" data-src="{{ asset('assets/ecomus/images/slider/fashion-06-slide1.jpg') }}" alt="fashion-slideshow" src="{{ asset('assets/ecomus/images/slider/fashion-06-slide1.jpg') }}">
                    </div>
                </div>
            </div>
            <div class="swiper-slide" lazy="true">
                <div class="slider-effect wrap-slider">
                    <div class="content-left">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-6 col-12">
                                    <div class="box-content">
                                        <h1 class="heading fade-item fade-item-1">Multi-faceted<br> Beauty</h1>
                                        <p class="desc fade-item fade-item-2">Embrace the sun-kissed season with our collection of breezy</p>
                                        <a href="{{ route('collections.show', 'beauty') }}" class="fade-item fade-item-3 tf-btn btn-light-icon animate-hover-btn btn-xl radius-3">
                                            <span>Shop collection</span><i class="icon icon-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="img-slider">
                        <img class="lazyload" data-src="{{ asset('assets/ecomus/images/slider/fashion-06-slide2.jpg') }}" src="{{ asset('assets/ecomus/images/slider/fashion-06-slide2.jpg') }}" alt="fashion-slideshow">
                    </div>
                </div>
            </div>
            <div class="swiper-slide" lazy="true">
                <div class="slider-effect wrap-slider">
                    <div class="content-left">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-6 col-12">
                                    <div class="box-content">
                                        <h1 class="heading fade-item fade-item-1">Effortless<br> Elegance</h1>
                                        <p class="desc fade-item fade-item-2">Embrace the sun-kissed season with our collection of breezy</p>
                                        <a href="{{ route('collections.show', 'elegance') }}" class="fade-item fade-item-3 tf-btn btn-light-icon animate-hover-btn btn-xl radius-3">
                                            <span>Shop collection</span><i class="icon icon-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="img-slider">
                        <img class="lazyload" data-src="{{ asset('assets/ecomus/images/slider/fashion-06-slide3.jpg') }}" src="{{ asset('assets/ecomus/images/slider/fashion-06-slide3.jpg') }}" alt="fashion-slideshow">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="wrap-pagination">
        <div class="container">
            <div class="sw-dots line-pagination sw-pagination-slider"></div>
        </div>
    </div>
</section>
<!-- /Slider -->

<!-- Collection -->
<section class="flat-spacing-12 bg_grey-3">
    <div class="container">
        <div class="flat-title flex-row justify-content-between align-items-center px-0 wow fadeInUp" data-wow-delay="0s">
            <h3 class="title">Season Collection</h3>
            <a href="{{ route('collections.index') }}" class="tf-btn btn-line">View all categories<i class="icon icon-arrow1-top-left"></i></a>
        </div>
        <div class="hover-sw-nav hover-sw-2">
            <div dir="ltr" class="swiper tf-sw-collection" data-preview="6" data-tablet="3" data-mobile="2" data-space-lg="50" data-space-md="30" data-space="15" data-loop="false" data-auto-play="false">
                <div class="swiper-wrapper">
                    <div class="swiper-slide" lazy="true">
                        <div class="collection-item-circle hover-img">
                            <a href="{{ route('categories.show', 'women') }}" class="collection-image img-style">
                                <img class="lazyload" data-src="{{ asset('assets/ecomus/images/collections/collection-circle-1.jpg') }}" src="{{ asset('assets/ecomus/images/collections/collection-circle-1.jpg') }}" alt="collection-img">
                            </a>
                            <div class="collection-content text-center">
                                <a href="{{ route('categories.show', 'women') }}" class="link title fw-5">Women's</a>
                                <div class="count">23 items</div>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide" lazy="true">
                        <div class="collection-item-circle hover-img">
                            <a href="{{ route('categories.show', 'men') }}" class="collection-image img-style">
                                <img class="lazyload" data-src="{{ asset('assets/ecomus/images/collections/collection-circle-2.jpg') }}" src="{{ asset('assets/ecomus/images/collections/collection-circle-2.jpg') }}" alt="collection-img">
                            </a>
                            <div class="collection-content text-center">
                                <a href="{{ route('categories.show', 'men') }}" class="link title fw-5">Men's</a>
                                <div class="count">9 items</div>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide" lazy="true">
                        <div class="collection-item-circle hover-img">
                            <a href="{{ route('categories.show', 'accessories') }}" class="collection-image img-style">
                                <img class="lazyload" data-src="{{ asset('assets/ecomus/images/collections/collection-circle-3.jpg') }}" src="{{ asset('assets/assets/ecomus/images/collections/collection-circle-3.jpg') }}" alt="collection-img">
                            </a>
                            <div class="collection-content text-center">
                                <a href="{{ route('categories.show', 'accessories') }}" class="link title fw-5">Accessories</a>
                                <div class="count">12 items</div>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide" lazy="true">
                        <div class="collection-item-circle hover-img">
                            <a href="{{ route('categories.show', 'shoes') }}" class="collection-image img-style">
                                <img class="lazyload" data-src="{{ asset('assets/ecomus/images/collections/collection-circle-4.jpg') }}" src="{{ asset('assets/ecomus/images/collections/collection-circle-4.jpg') }}" alt="collection-img">
                            </a>
                            <div class="collection-content text-center">
                                <a href="{{ route('categories.show', 'shoes') }}" class="link title fw-5">Shoes</a>
                                <div class="count">16 items</div>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide" lazy="true">
                        <div class="collection-item-circle hover-img">
                            <a href="{{ route('categories.show', 'bags') }}" class="collection-image img-style">
                                <img class="lazyload" data-src="{{ asset('assets/ecomus/images/collections/collection-circle-5.jpg') }}" src="{{ asset('assets/ecomus/images/collections/collection-circle-5.jpg') }}" alt="collection-img">
                            </a>
                            <div class="collection-content text-center">
                                <a href="{{ route('categories.show', 'bags') }}" class="link title fw-5">Bags</a>
                                <div class="count">8 items</div>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide" lazy="true">
                        <div class="collection-item-circle hover-img">
                            <a href="{{ route('categories.show', 'jewelry') }}" class="collection-image img-style">
                                <img class="lazyload" data-src="{{ asset('assets/ecomus/images/collections/collection-circle-6.jpg') }}" src="{{ asset('assets/ecomus/images/collections/collection-circle-6.jpg') }}" alt="collection-img">
                            </a>
                            <div class="collection-content text-center">
                                <a href="{{ route('categories.show', 'jewelry') }}" class="link title fw-5">Jewelry</a>
                                <div class="count">14 items</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="nav-sw nav-next-slider nav-next-collection box-icon w_46 round"><span class="icon icon-arrow-left"></span></div>
            <div class="nav-sw nav-prev-slider nav-prev-collection box-icon w_46 round"><span class="icon icon-arrow-right"></span></div>
        </div>
    </div>
</section>
<!-- /Collection -->

@if(isset($featuredProducts) && $featuredProducts->count() > 0)
<!-- Product -->
<section class="flat-spacing-1">
    <div class="container">
        <div class="flat-title wow fadeInUp" data-wow-delay="0s">
            <span class="title">Best Seller</span>
            <p class="sub-title">Shop the Latest Styles: Stay ahead of the curve with our newest arrivals</p>
        </div>
        <div class="hover-sw-nav hover-sw-2">
            <div dir="ltr" class="swiper tf-sw-product-sell wrap-sw-over" data-preview="4" data-tablet="3" data-mobile="2" data-space-lg="30" data-space-md="15" data-pagination="2" data-pagination-md="3" data-pagination-lg="3">
                <div class="swiper-wrapper">
                    @foreach($featuredProducts as $product)
                    <div class="swiper-slide" lazy="true">
                        <div class="card-product">
                            <div class="card-product-wrapper">
                                <a href="{{ route('products.show', $product->slug) }}" class="product-img">
                                    <img class="lazyload img-product" data-src="{{ $product->image }}" src="{{ $product->image }}" alt="{{ $product->name }}">
                                    <img class="lazyload img-hover" data-src="{{ $product->gallery_images[0] ?? $product->image }}" src="{{ $product->gallery_images[0] ?? $product->image }}" alt="{{ $product->name }}">
                                </a>
                                <div class="list-product-btn">
                                    <a href="#quick_add" data-bs-toggle="modal" class="box-icon bg_white quick-add tf-btn-loading">
                                        <span class="icon icon-bag"></span>
                                        <span class="tooltip">Quick Add</span>
                                    </a>
                                    <a href="javascript:void(0);" class="box-icon bg_white wishlist btn-icon-action">
                                        <span class="icon icon-heart"></span>
                                        <span class="tooltip">Add to Wishlist</span>
                                        <span class="icon icon-delete"></span>
                                    </a>
                                    <a href="#compare" data-bs-toggle="offcanvas" aria-controls="offcanvasLeft" class="box-icon bg_white compare btn-icon-action">
                                        <span class="icon icon-compare"></span>
                                        <span class="tooltip">Add to Compare</span>
                                        <span class="icon icon-check"></span>
                                    </a>
                                    <a href="#quick_view" data-bs-toggle="modal" class="box-icon bg_white quickview tf-btn-loading">
                                        <span class="icon icon-view"></span>
                                        <span class="tooltip">Quick View</span>
                                    </a>
                                </div>
                                @if($product->discount > 0)
                                <div class="on-sale-wrap">
                                    <div class="on-sale-item">{{ $product->discount }}% OFF</div>
                                </div>
                                @endif
                            </div>
                            <div class="card-product-info">
                                <a href="{{ route('products.show', $product->slug) }}" class="title link">{{ $product->name }}</a>
                                <span class="price">
                                    @if($product->sale_price)
                                        <span class="compare-at-price">${{ number_format($product->price, 2) }}</span>
                                        <span class="price-on-sale fw-6">${{ number_format($product->sale_price, 2) }}</span>
                                    @else
                                        <span class="fw-6">${{ number_format($product->price, 2) }}</span>
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="nav-sw nav-next-slider nav-next-product box-icon w_46 round"><span class="icon icon-arrow-left"></span></div>
            <div class="nav-sw nav-prev-slider nav-prev-product box-icon w_46 round"><span class="icon icon-arrow-right"></span></div>
        </div>
    </div>
</section>
<!-- /Product -->
@endif

<!-- Banner Collection -->
<section class="flat-spacing-8">
    <div class="container">
        <div class="tf-grid-layout md-col-2 tf-img-with-text style-4">
            <div class="tf-content-left has-bg-color-2 wow fadeInLeft" data-wow-delay="0s">
                <div class="text-center">
                    <h2 class="heading">The Summer Sale</h2>
                    <p class="text-paragraph">Discover the hottest trends and must-have styles of the season.</p>
                    <div class="tf-countdown-v2 justify-content-center">
                        <div class="js-countdown" data-timer="1007500" data-labels=" :  :  : ">
                            <div class="countdown__item">
                                <span class="countdown__value countdown-days">0</span>
                                <span class="countdown__label">Days</span>
                            </div>
                            <div class="countdown__item">
                                <span class="countdown__value countdown-hours">0</span>
                                <span class="countdown__label">Hours</span>
                            </div>
                            <div class="countdown__item">
                                <span class="countdown__value countdown-minutes">0</span>
                                <span class="countdown__label">Mins</span>
                            </div>
                            <div class="countdown__item">
                                <span class="countdown__value countdown-seconds">0</span>
                                <span class="countdown__label">Secs</span>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('collections.show', 'summer-sale') }}" class="tf-btn style-3 fw-6 animate-hover-btn btn-xl radius-3">
                        <span>Shop Collection</span><i class="icon icon-arrow-right"></i>
                    </a>
                </div>
            </div>
            <div class="tf-image-wrap wow fadeInRight" data-wow-delay="0s">
                <img class="lazyload" data-src="{{ asset('assets/ecomus/images/collections/banner-collection-3.png') }}" src="{{ asset('assets/ecomus/images/collections/banner-collection-3.png') }}" alt="banner-collection">
            </div>
        </div>
    </div>
</section>
<!-- /Banner Collection -->

<!-- Testimonial -->
<section class="flat-spacing-2 pt_0">
    <div class="container">
        <div class="flat-title wow fadeInUp" data-wow-delay="0s">
            <span class="title">Happy Clients</span>
            <p class="sub-title">Hear what they say about us</p>
        </div>
        <div class="wrap-carousel">
            <div dir="ltr" class="swiper tf-sw-testimonial" data-preview="3" data-tablet="2" data-mobile="1" data-space-lg="30" data-space-md="15">
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <div class="testimonial-item style-column wow fadeInUp" data-wow-delay="0s">
                            <div class="rating">
                                <i class="icon-start"></i>
                                <i class="icon-start"></i>
                                <i class="icon-start"></i>
                                <i class="icon-start"></i>
                                <i class="icon-start"></i>
                            </div>
                            <div class="heading">Best Online Fashion Site</div>
                            <div class="text">
                                " I always find something stylish and affordable on this website "
                            </div>
                            <div class="author">
                                <div class="name">Robert smith</div>
                                <div class="metas">Customer from New York</div>
                            </div>
                            <div class="product">
                                <div class="image">
                                    <a href="#">
                                        <img class="lazyload" data-src="{{ asset('assets/ecomus/images/products/white-3.jpg') }}" src="{{ asset('assets/ecomus/images/products/white-3.jpg') }}" alt="">
                                    </a>
                                </div>
                                <div class="content-wrap">
                                    <div class="product-title">
                                        <a href="#">Jersey thong body</a>
                                    </div>
                                    <div class="price">$105.95</div>
                                </div>
                                <a href="#" class=""><i class="icon-arrow1-top-left"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="testimonial-item style-column wow fadeInUp" data-wow-delay=".1s">
                            <div class="rating">
                                <i class="icon-start"></i>
                                <i class="icon-start"></i>
                                <i class="icon-start"></i>
                                <i class="icon-start"></i>
                                <i class="icon-start"></i>
                            </div>
                            <div class="heading">Great Selection and Quality</div>
                            <div class="text">
                                " I love the variety of styles and the high-quality clothing on this site "
                            </div>
                            <div class="author">
                                <div class="name">Allen Lyn</div>
                                <div class="metas">Customer from Chicago</div>
                            </div>
                            <div class="product">
                                <div class="image">
                                    <a href="#">
                                        <img class="lazyload" data-src="{{ asset('assets/ecomus/images/products/brown.jpg') }}" src="{{ asset('assets/ecomus/images/products/brown.jpg') }}" alt="">
                                    </a>
                                </div>
                                <div class="content-wrap">
                                    <div class="product-title">
                                        <a href="#">Ribbed modal T-shirt</a>
                                    </div>
                                    <div class="price">$18.95</div>
                                </div>
                                <a href="#" class=""><i class="icon-arrow1-top-left"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="testimonial-item style-column wow fadeInUp" data-wow-delay=".2s">
                            <div class="rating">
                                <i class="icon-start"></i>
                                <i class="icon-start"></i>
                                <i class="icon-start"></i>
                                <i class="icon-start"></i>
                                <i class="icon-start"></i>
                            </div>
                            <div class="heading">Best Customer Service</div>
                            <div class="text">
                                " I finally found a store I can rely on for trendy and quality clothing "
                            </div>
                            <div class="author">
                                <div class="name">Peter Rope</div>
                                <div class="metas">Customer from San Francisco</div>
                            </div>
                            <div class="product">
                                <div class="image">
                                    <a href="#">
                                        <img class="lazyload" data-src="{{ asset('assets/ecomus/images/products/white-2.jpg') }}" src="{{ asset('assets/ecomus/images/products/white-2.jpg') }}" alt="">
                                    </a>
                                </div>
                                <div class="content-wrap">
                                    <div class="product-title">
                                        <a href="#">Oversized Printed T-shirt</a>
                                    </div>
                                    <div class="price">$16.95</div>
                                </div>
                                <a href="#" class=""><i class="icon-arrow1-top-left"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="testimonial-item style-column wow fadeInUp" data-wow-delay=".2s">
                            <div class="rating">
                                <i class="icon-start"></i>
                                <i class="icon-start"></i>
                                <i class="icon-start"></i>
                                <i class="icon-start"></i>
                                <i class="icon-start"></i>
                            </div>
                            <div class="heading">Great Selection and Quality</div>
                            <div class="text">
                                " The customer service team is outstanding and very responsive! "
                            </div>
                            <div class="author">
                                <div class="name">Hellen Ase</div>
                                <div class="metas">Customer from Miami</div>
                            </div>
                            <div class="product">
                                <div class="image">
                                    <a href="#">
                                        <img class="lazyload" data-src="{{ asset('assets/ecomus/images/products/pink-1.jpg') }}" src="{{ asset('assets/ecomus/images/products/pink-1.jpg') }}" alt="">
                                    </a>
                                </div>
                                <div class="content-wrap">
                                    <div class="product-title">
                                        <a href="#">Ribbed Tank Top</a>
                                    </div>
                                    <div class="price">$16.95</div>
                                </div>
                                <a href="#" class=""><i class="icon-arrow1-top-left"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="sw-dots style-2 sw-pagination-testimonial justify-content-center"></div>
            </div>
        </div>
    </div>
</section>
<!-- /Testimonial -->

<!-- Instagram -->
<section class="flat-spacing-1 pt_0">
    <div class="container">
        <div class="flat-title wow fadeInUp" data-wow-delay="0s">
            <span class="title">Shop the look</span>
            <p class="sub-title">Inspire and let yourself be inspired, from one unique fashion to another.</p>
        </div>
        <div class="wrap-carousel wrap-shop-the-look">
            <div dir="ltr" class="swiper tf-sw-shop-gallery" data-preview="5" data-tablet="3" data-mobile="2" data-space-lg="7" data-space-md="7">
                <div class="swiper-wrapper">
                    <div class="swiper-slide" lazy="true">
                        <div class="gallery-item hover-img">
                            <div class="img-style">
                                <img class="lazyload img-hover" data-src="{{ asset('assets/ecomus/images/shop/gallery/gallery-7.jpg') }}" src="{{ asset('assets/ecomus/images/shop/gallery/gallery-7.jpg') }}" alt="gallery-img">
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide" lazy="true">
                        <div class="gallery-item hover-img">
                            <div class="img-style">
                                <img class="lazyload img-hover" data-src="{{ asset('assets/ecomus/images/shop/gallery/gallery-3.jpg') }}" src="{{ asset('assets/ecomus/images/shop/gallery/gallery-3.jpg') }}" alt="gallery-img">
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide" lazy="true">
                        <div class="gallery-item hover-img">
                            <div class="img-style">
                                <img class="lazyload img-hover" data-src="{{ asset('assets/ecomus/images/shop/gallery/gallery-5.jpg') }}" src="{{ asset('assets/ecomus/images/shop/gallery/gallery-5.jpg') }}" alt="gallery-img">
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide" lazy="true">
                        <div class="gallery-item hover-img">
                            <div class="img-style">
                                <img class="lazyload img-hover" data-src="{{ asset('assets/ecomus/images/shop/gallery/gallery-8.jpg') }}" src="{{ asset('assets/ecomus/images/shop/gallery/gallery-8.jpg') }}" alt="gallery-img">
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide" lazy="true">
                        <div class="gallery-item hover-img">
                            <div class="img-style">
                                <img class="lazyload img-hover" data-src="{{ asset('assets/ecomus/images/shop/gallery/gallery-6.jpg') }}" src="{{ asset('assets/ecomus/images/shop/gallery/gallery-6.jpg') }}" alt="gallery-img">
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide" lazy="true">
                        <div class="gallery-item hover-img">
                            <div class="img-style">
                                <img class="lazyload img-hover" data-src="{{ asset('assets/ecomus/images/shop/gallery/gallery-2.jpg') }}" src="{{ asset('assets/ecomus/images/shop/gallery/gallery-2.jpg') }}" alt="gallery-img">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="sw-dots style-2 sw-pagination-gallery justify-content-center"></div>
        </div>
    </div>
</section>
<!-- /Instagram -->

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize cart count
    updateCartCount();
    updateWishlistCount();
    
    // Add to cart functionality
    $('.quick-add').on('click', function(e) {
        e.preventDefault();
        // Add your add to cart logic here
        updateCartCount();
    });
    
    // Add to wishlist functionality
    $('.wishlist').on('click', function(e) {
        e.preventDefault();
        // Add your wishlist logic here
        updateWishlistCount();
    });
    
    function updateCartCount() {
        // Update cart count from your cart service
        // $('#cart-count').text(cartCount);
    }
    
    function updateWishlistCount() {
        // Update wishlist count from your wishlist service
        // $('#wishlist-count').text(wishlistCount);
    }
});
</script>
@endpush

@endsection