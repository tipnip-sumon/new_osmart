<!-- Modal Search -->
<div class="offcanvas offcanvas-end canvas-search" id="canvasSearch">
    <div class="canvas-wrapper">
        <header class="tf-search-head">
            <div class="title fw-5">
                Search our site
                <div class="close">
                    <span class="icon-close icon-close-popup" data-bs-dismiss="offcanvas" aria-label="Close"></span>
                </div>
            </div>
            <div class="tf-search-sticky">
                <form class="tf-mini-search-frm" action="{{ route('search') }}" method="GET">
                    <fieldset class="text">
                        <input type="text" placeholder="Search" class="" name="query" tabindex="0" value="{{ request('query') }}" aria-required="true" required="">
                    </fieldset>
                    <button class="" type="submit"><i class="icon-search"></i></button>
                </form>
            </div>
        </header>
        <div class="canvas-body p-0">
            <div class="tf-search-content">
                <div class="tf-cart-hide-has-results">
                    <div class="tf-col-quicklink">
                        <div class="tf-search-content-title fw-5">Quick link</div>
                        <ul class="tf-quicklink-list">
                            <li class="tf-quicklink-item">
                                <a href="{{ route('categories.show', 'fashion') }}" class="tf-quicklink-link">Fashion</a>
                            </li>
                            <li class="tf-quicklink-item">
                                <a href="{{ route('categories.show', 'men') }}" class="tf-quicklink-link">Men</a>
                            </li>
                            <li class="tf-quicklink-item">
                                <a href="{{ route('categories.show', 'women') }}" class="tf-quicklink-link">Women</a>
                            </li>
                            <li class="tf-quicklink-item">
                                <a href="{{ route('categories.show', 'accessories') }}" class="tf-quicklink-link">Accessories</a>
                            </li>
                        </ul>
                    </div>
                    <div class="tf-col-content">
                        <div class="tf-search-content-title fw-5">Need some inspiration?</div>
                        <div class="tf-search-hidden-inner">
                            <div class="tf-loop-item">
                                <div class="image">
                                    <a href="{{ route('categories.show', 'trending') }}">
                                        <img src="{{ asset('assets/ecomus/images/products/white-3.jpg') }}" alt="">
                                    </a>
                                </div>
                                <div class="content">
                                    <a href="{{ route('categories.show', 'trending') }}">Cotton jersey top</a>
                                    <div class="tf-product-info-price">
                                        <div class="compare-at-price">$10.00</div>
                                        <div class="price-on-sale fw-6">$8.00</div>
                                    </div>
                                </div>
                            </div>
                            <div class="tf-loop-item">
                                <div class="image">
                                    <a href="{{ route('categories.show', 'trending') }}">
                                        <img src="{{ asset('assets/ecomus/images/products/white-2.jpg') }}" alt="">
                                    </a>
                                </div>
                                <div class="content">
                                    <a href="{{ route('categories.show', 'trending') }}">Mini crossbody bag</a>
                                    <div class="tf-product-info-price">
                                        <div class="price fw-6">$18.00</div>
                                    </div>
                                </div>
                            </div>
                            <div class="tf-loop-item">
                                <div class="image">
                                    <a href="{{ route('categories.show', 'trending') }}">
                                        <img src="{{ asset('assets/ecomus/images/products/white-1.jpg') }}" alt="">
                                    </a>
                                </div>
                                <div class="content">
                                    <a href="{{ route('categories.show', 'trending') }}">Oversized Printed T-shirt</a>
                                    <div class="tf-product-info-price">
                                        <div class="price fw-6">$18.00</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /Modal Search -->