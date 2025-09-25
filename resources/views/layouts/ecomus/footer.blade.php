<!-- Footer -->
<footer id="footer" class="footer background-gray md-pb-70">
    <div class="footer-wrap">
        <div class="footer-body">
            <div class="container">
                <div class="row">
                    <div class="col-xl-3 col-md-6 col-12">
                        <div class="footer-infor">
                            <div class="footer-logo">
                                <a href="{{ route('home') }}">
                                    <img src="{{ siteLogo() }}" alt="{{ config('app.name') }}">
                                </a>
                            </div>
                            <ul>
                                <li>
                                    <p>Address: {{ generalSettings()->address ?? '1234 Fashion Street, Suite 567, New York, NY 10001' }}</p>
                                </li>
                                <li>
                                    <p>Email: <a href="mailto:{{ generalSettings()->support_email ?? 'support@osmart.com' }}">{{ generalSettings()->support_email ?? 'support@osmart.com' }}</a></p>
                                </li>
                                <li>
                                    <p>Phone: <a href="tel:{{ generalSettings()->phone ?? '+1234567890' }}">{{ generalSettings()->phone ?? '+1234567890' }}</a></p>
                                </li>
                            </ul>
                            <a href="{{ route('contact.show') }}" class="tf-btn btn-line">Get direction<i class="icon icon-arrow1-top-left"></i></a>
                            <ul class="tf-social-icon d-flex gap-10">
                                @if(generalSettings()->facebook_url)
                                <li><a href="{{ generalSettings()->facebook_url }}" class="box-icon w_34 round social-facebook social-line"><i class="icon fs-14 icon-fb"></i></a></li>
                                @endif
                                @if(generalSettings()->twitter_url)
                                <li><a href="{{ generalSettings()->twitter_url }}" class="box-icon w_34 round social-twiter social-line"><i class="icon fs-12 icon-Icon-x"></i></a></li>
                                @endif
                                @if(generalSettings()->instagram_url)
                                <li><a href="{{ generalSettings()->instagram_url }}" class="box-icon w_34 round social-instagram social-line"><i class="icon fs-14 icon-instagram"></i></a></li>
                                @endif
                                @if(generalSettings()->linkedin_url)
                                <li><a href="{{ generalSettings()->linkedin_url }}" class="box-icon w_34 round social-pinterest social-line"><i class="icon fs-14 icon-pinterest-1"></i></a></li>
                                @endif
                            </ul>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 col-12 footer-col-block">
                        <div class="footer-heading footer-heading-desktop">
                            <h6>Help</h6>
                        </div>
                        <div class="footer-heading footer-heading-moblie">
                            <h6>Help</h6>
                        </div>
                        <ul class="footer-menu-list tf-collapse-content">
                            <li>
                                <a href="{{ route('pages.privacy') }}" class="footer-menu_item">Privacy Policy</a>
                            </li>
                            <li>
                                <a href="{{ route('pages.returns') }}" class="footer-menu_item">Returns + Exchanges</a>
                            </li>
                            <li>
                                <a href="{{ route('pages.shipping') }}" class="footer-menu_item">Shipping</a>
                            </li>
                            <li>
                                <a href="{{ route('pages.terms') }}" class="footer-menu_item">Terms & Conditions</a>
                            </li>
                            <li>
                                <a href="{{ route('pages.faq') }}" class="footer-menu_item">FAQ's</a>
                            </li>
                            <li>
                                <a href="{{ route('compare.index') }}" class="footer-menu_item">Compare</a>
                            </li>
                            <li>
                                <a href="{{ route('wishlist.index') }}" class="footer-menu_item">My Wishlist</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-xl-3 col-md-6 col-12 footer-col-block">
                        <div class="footer-heading footer-heading-desktop">
                            <h6>About us</h6>
                        </div>
                        <div class="footer-heading footer-heading-moblie">
                            <h6>About us</h6>
                        </div>
                        <ul class="footer-menu-list tf-collapse-content">
                            <li>
                                <a href="{{ route('pages.about') }}" class="footer-menu_item">Our Story</a>
                            </li>
                            <li>
                                <a href="{{ route('pages.store') }}" class="footer-menu_item">Visit Our Store</a>
                            </li>
                            <li>
                                <a href="{{ route('contact.show') }}" class="footer-menu_item">Contact Us</a>
                            </li>
                            @auth
                            <li>
                                <a href="{{ route('member.dashboard') }}" class="footer-menu_item">My Account</a>
                            </li>
                            @else
                            <li>
                                <a href="{{ route('login') }}" class="footer-menu_item">Login</a>
                            </li>
                            @endauth
                        </ul>
                    </div>
                    <div class="col-xl-3 col-md-6 col-12">
                        <div class="footer-newsletter footer-col-block">
                            <div class="footer-heading footer-heading-desktop">
                                <h6>Sign Up for Email</h6>
                            </div>
                            <div class="footer-heading footer-heading-moblie">
                                <h6>Sign Up for Email</h6>
                            </div>
                            <div class="tf-collapse-content">
                                <div class="footer-menu_item">Sign up to get first dibs on new arrivals, sales, exclusive content, events and more!</div>
                                <form class="form-newsletter" id="subscribe-form" action="{{ route('newsletter.subscribe') }}" method="post" accept-charset="utf-8">
                                    @csrf
                                    <div id="subscribe-content">
                                        <fieldset class="email">
                                            <input type="email" name="email" id="subscribe-email" placeholder="Enter your email...." tabindex="0" aria-required="true" required>
                                        </fieldset>
                                        <div class="button-submit">
                                            <button id="subscribe-button" class="tf-btn btn-sm radius-3 btn-fill btn-icon animate-hover-btn" type="submit">Subscribe<i class="icon icon-arrow1-top-left"></i></button>
                                        </div>
                                    </div>
                                    <div id="subscribe-msg"></div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="footer-bottom-wrap d-flex gap-20 flex-wrap justify-content-between align-items-center">
                            <div class="footer-menu_item">© {{ date('Y') }} {{ config('app.name') }}. All Rights Reserved</div>
                            <div class="tf-payment">
                                <img src="{{ asset('assets/ecomus/images/payments/visa.png') }}" alt="Visa">
                                <img src="{{ asset('assets/ecomus/images/payments/img-1.png') }}" alt="MasterCard">
                                <img src="{{ asset('assets/ecomus/images/payments/img-2.png') }}" alt="PayPal">
                                <img src="{{ asset('assets/ecomus/images/payments/img-3.png') }}" alt="American Express">
                                <img src="{{ asset('assets/ecomus/images/payments/img-4.png') }}" alt="Discover">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- /Footer -->

<!-- gotop -->
<button id="goTop">
    <span class="border-progress"></span>
    <span class="icon icon-arrow-up"></span>
</button>
<!-- /gotop -->

<!-- toolbar-bottom -->
<div class="tf-toolbar-bottom type-1150">
    <div class="toolbar-item">
        <a href="{{ route('shop.index') }}">
            <div class="toolbar-icon">
                <i class="icon-shop"></i>
            </div>
            <div class="toolbar-label">Shop</div>
        </a>
    </div>

    <div class="toolbar-item">
        <a href="#canvasSearch" data-bs-toggle="offcanvas" aria-controls="offcanvasLeft">
            <div class="toolbar-icon">
                <i class="icon-search"></i>
            </div>
            <div class="toolbar-label">Search</div>
        </a>
    </div>
    <div class="toolbar-item">
        @auth
        <a href="{{ route('member.dashboard') }}">
            <div class="toolbar-icon">
                <i class="icon-account"></i>
            </div>
            <div class="toolbar-label">Account</div>
        </a>
        @else
        <a href="#login" data-bs-toggle="modal">
            <div class="toolbar-icon">
                <i class="icon-account"></i>
            </div>
            <div class="toolbar-label">Login</div>
        </a>
        @endauth
    </div>
    <div class="toolbar-item">
        <a href="{{ route('wishlist.index') }}">
            <div class="toolbar-icon">
                <i class="icon-heart"></i>
            </div>
            <div class="toolbar-label">Wishlist</div>
        </a>
    </div>
    <div class="toolbar-item">
        <a href="#shoppingCart" data-bs-toggle="modal">
            <div class="toolbar-icon">
                <i class="icon-bag"></i>
            </div>
            <div class="toolbar-label">Cart</div>
        </a>
    </div>
</div>
<!-- /toolbar-bottom -->