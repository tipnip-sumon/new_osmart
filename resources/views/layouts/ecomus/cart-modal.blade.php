<!-- shoppingCart -->
<div class="modal fullRight fade modal-shopping-cart" id="shoppingCart">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="header">
                <div class="title fw-5">Shopping cart</div>
                <span class="icon-close icon-close-popup" data-bs-dismiss="modal"></span>
            </div>
            <div class="wrap">
                <div class="tf-mini-cart-threshold">
                    <div class="tf-progress-bar">
                        @php
                            $freeShippingThreshold = config('shipping.free_shipping.minimum_order', 1000);
                            $currentCartTotal = 49.99; // This should be dynamic from your cart system
                            $progressPercentage = min(($currentCartTotal / $freeShippingThreshold) * 100, 100);
                            $remainingForFree = max($freeShippingThreshold - $currentCartTotal, 0);
                        @endphp
                        <span style="width: {{ $progressPercentage }}%;">
                            <div class="progress-car">
                                <svg xmlns="http://www.w3.org/2000/svg" width="21" height="14" viewBox="0 0 21 14"
                                    fill="currentColor">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M0 0.875C0 0.391751 0.391751 0 0.875 0H13.5625C14.0457 0 14.4375 0.391751 14.4375 0.875V3.0625H17.3125C17.5867 3.0625 17.845 3.19101 18.0104 3.40969L20.8229 7.12844C20.9378 7.2804 21 7.46572 21 7.65625V11.375C21 11.8582 20.6082 12.25 20.125 12.25H17.7881C17.4278 13.2695 16.4554 14 15.3125 14C14.1696 14 13.1972 13.2695 12.8369 12.25H7.72563C7.36527 13.2695 6.39293 14 5.25 14C4.10706 14 3.13473 13.2695 2.77437 12.25H0.875C0.391751 12.25 0 11.8582 0 11.375V0.875ZM2.77437 10.5C3.13473 9.48047 4.10706 8.75 5.25 8.75C6.39293 8.75 7.36527 9.48046 7.72563 10.5H12.6875V1.75H1.75V10.5H2.77437ZM14.4375 8.89937V4.8125H16.8772L19.25 7.94987V10.5H17.7881C17.4278 9.48046 16.4554 8.75 15.3125 8.75C15.0057 8.75 14.7112 8.80264 14.4375 8.89937ZM5.25 10.5C4.76676 10.5 4.375 10.8918 4.375 11.375C4.375 11.8582 4.76676 12.25 5.25 12.25C5.73323 12.25 6.125 11.8582 6.125 11.375C6.125 10.8918 5.73323 10.5 5.25 10.5ZM15.3125 10.5C14.8293 10.5 14.4375 10.8918 14.4375 11.375C14.4375 11.8582 14.8293 12.25 15.3125 12.25C15.7957 12.25 16.1875 11.8582 16.1875 11.375C16.1875 10.8918 15.7957 10.5 15.3125 10.5Z">
                                    </path>
                                </svg>
                            </div>
                        </span>
                    </div>
                    <div class="tf-progress-msg" id="free-shipping-progress">
                        @if($remainingForFree > 0)
                            Buy <span class="price fw-6">{{ formatCurrency($remainingForFree) }}</span> more to enjoy <span class="fw-6">Free Shipping</span>
                        @else
                            <span class="fw-6 text-success">🎉 You qualify for Free Shipping!</span>
                        @endif
                    </div>
                </div>
                <div class="tf-mini-cart-wrap">
                    <div class="tf-mini-cart-main">
                        <div class="tf-mini-cart-sroll">
                            <div class="tf-mini-cart-items" id="cart-items-container">
                                <!-- Cart items will be loaded here dynamically -->
                                <div class="tf-mini-cart-item">
                                    <div class="tf-mini-cart-image">
                                        <a href="product-detail.html">
                                            <img src="{{ asset('assets/img/product/white-2.jpg') }}" alt="">
                                        </a>
                                    </div>
                                    <div class="tf-mini-cart-info">
                                        <a class="title link" href="product-detail.html">T-shirt</a>
                                        <div class="meta-variant">Light gray</div>
                                        <div class="price fw-6">{{ formatCurrency(25.00) }}</div>
                                        <div class="tf-mini-cart-btns">
                                            <div class="wg-quantity small">
                                                <span class="btn-quantity minus-btn">-</span>
                                                <input type="text" name="number" value="1">
                                                <span class="btn-quantity plus-btn">+</span>
                                            </div>
                                            <div class="tf-mini-cart-remove">Remove</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tf-mini-cart-item">
                                    <div class="tf-mini-cart-image">
                                        <a href="product-detail.html">
                                            <img src="{{ asset('assets/img/product/white-3.jpg') }}" alt="">
                                        </a>
                                    </div>
                                    <div class="tf-mini-cart-info">
                                        <a class="title link" href="product-detail.html">Oversized Motif T-shirt</a>
                                        <div class="price fw-6">{{ formatCurrency(25.00) }}</div>
                                        <div class="tf-mini-cart-btns">
                                            <div class="wg-quantity small">
                                                <span class="btn-quantity minus-btn">-</span>
                                                <input type="text" name="number" value="1">
                                                <span class="btn-quantity plus-btn">+</span>
                                            </div>
                                            <div class="tf-mini-cart-remove">Remove</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tf-minicart-recommendations">
                                <div class="tf-minicart-recommendations-heading">
                                    <div class="tf-minicart-recommendations-title">You may also like</div>
                                    <div class="sw-dots small style-2 cart-slide-pagination"></div>
                                </div>
                                <div dir="ltr" class="swiper tf-cart-slide">
                                    <div class="swiper-wrapper">
                                        <div class="swiper-slide">
                                            <div class="tf-minicart-recommendations-item">
                                                <div class="tf-minicart-recommendations-item-image">
                                                    <a href="product-detail.html">
                                                        <img src="{{ asset('assets/img/product/white-3.jpg') }}" alt="">
                                                    </a>
                                                </div>
                                                <div class="tf-minicart-recommendations-item-infos flex-grow-1">
                                                    <a class="title" href="product-detail.html">Loose Fit
                                                        Sweatshirt</a>
                                                    <div class="price">{{ formatCurrency(25.00) }}</div>
                                                </div>
                                                <div class="tf-minicart-recommendations-item-quickview">
                                                    <div class="btn-show-quickview quickview hover-tooltip">
                                                        <span class="icon icon-view"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="swiper-slide">
                                            <div class="tf-minicart-recommendations-item">
                                                <div class="tf-minicart-recommendations-item-image">
                                                    <a href="product-detail.html">
                                                        <img src="{{ asset('assets/img/product/white-2.jpg') }}" alt="">
                                                    </a>
                                                </div>
                                                <div class="tf-minicart-recommendations-item-infos flex-grow-1">
                                                    <a class="title" href="product-detail.html">Loose Fit Hoodie</a>
                                                    <div class="price">{{ formatCurrency(25.00) }}</div>
                                                </div>
                                                <div class="tf-minicart-recommendations-item-quickview">
                                                    <div class="btn-show-quickview quickview hover-tooltip">
                                                        <span class="icon icon-view"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tf-mini-cart-bottom">
                        <div class="tf-mini-cart-tool">
                            <div class="tf-mini-cart-tool-btn btn-add-note">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="18" viewBox="0 0 16 18"
                                    fill="currentColor">
                                    <path
                                        d="M5.12187 16.4582H2.78952C2.02045 16.4582 1.39476 15.8325 1.39476 15.0634V2.78952C1.39476 2.02045 2.02045 1.39476 2.78952 1.39476H11.3634C12.1325 1.39476 12.7582 2.02045 12.7582 2.78952V7.07841C12.7582 7.46357 13.0704 7.77579 13.4556 7.77579C13.8407 7.77579 14.1529 7.46357 14.1529 7.07841V2.78952C14.1529 1.25138 12.9016 0 11.3634 0H2.78952C1.25138 0 0 1.25138 0 2.78952V15.0634C0 16.6015 1.25138 17.8529 2.78952 17.8529H5.12187C5.50703 17.8529 5.81925 17.5407 5.81925 17.1555C5.81925 16.7704 5.50703 16.4582 5.12187 16.4582Z">
                                    </path>
                                    <path
                                        d="M15.3882 10.0971C14.5724 9.28136 13.2452 9.28132 12.43 10.0965L8.60127 13.9168C8.51997 13.9979 8.45997 14.0979 8.42658 14.2078L7.59276 16.9528C7.55646 17.0723 7.55292 17.1993 7.58249 17.3207C7.61206 17.442 7.67367 17.5531 7.76087 17.6425C7.84807 17.7319 7.95768 17.7962 8.07823 17.8288C8.19879 17.8613 8.32587 17.8609 8.44621 17.8276L11.261 17.0479C11.3769 17.0158 11.4824 16.9543 11.5675 16.8694L15.3882 13.0559C16.2039 12.2401 16.2039 10.9129 15.3882 10.0971ZM10.712 15.7527L9.29586 16.145L9.71028 14.7806L12.2937 12.2029L13.2801 13.1893L10.712 15.7527ZM14.4025 12.0692L14.2673 12.204L13.2811 11.2178L13.4157 11.0834C13.6876 10.8115 14.1301 10.8115 14.402 11.0834C14.6739 11.3553 14.6739 11.7977 14.4025 12.0692Z">
                                    </path>
                                </svg>
                            </div>
                            <div class="tf-mini-cart-tool-btn btn-add-gift">
                                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="18" viewBox="0 0 17 18"
                                    fill="currentColor">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M2.99566 2.73409C2.99566 0.55401 5.42538 -0.746668 7.23916 0.463462L8.50073 1.30516L9.7623 0.463462C11.5761 -0.746668 14.0058 0.55401 14.0058 2.73409V3.24744H14.8225C15.9633 3.24744 16.8881 4.17233 16.8881 5.31312V6.82566C16.8881 7.21396 16.5734 7.52873 16.1851 7.52873H15.8905V15.1877C15.8905 15.1905 15.8905 15.1933 15.8905 15.196C15.886 16.7454 14.6286 18 13.0782 18H3.92323C2.37003 18 1.11091 16.7409 1.11091 15.1877V7.52877H0.81636C0.42806 7.52877 0.113281 7.21399 0.113281 6.82569V5.31316C0.113281 4.17228 1.03812 3.24744 2.179 3.24744H2.99566V2.73409ZM4.40181 3.24744H7.79765V2.52647L6.45874 1.63317C5.57987 1.0468 4.40181 1.67677 4.40181 2.73409V3.24744ZM9.20381 2.52647V3.24744H12.5996V2.73409C12.5996 1.67677 11.4216 1.0468 10.5427 1.63317L9.20381 2.52647ZM2.179 4.6536C1.81472 4.6536 1.51944 4.94888 1.51944 5.31316V6.12261H5.73398L5.734 4.6536H2.179ZM5.73401 7.52877V13.9306C5.73401 14.1806 5.86682 14.4119 6.08281 14.5379C6.29879 14.6639 6.56545 14.6657 6.78312 14.5426L8.50073 13.5715L10.2183 14.5426C10.436 14.6657 10.7027 14.6639 10.9187 14.5379C11.1346 14.4119 11.2674 14.1806 11.2674 13.9306V7.52873H14.4844V15.1603C14.4844 15.1627 14.4843 15.1651 14.4843 15.1675V15.1877C14.4843 15.9643 13.8548 16.5938 13.0782 16.5938H3.92323C3.14663 16.5938 2.51707 15.9643 2.51707 15.1877V7.52877H5.73401ZM15.482 6.12258V5.31312C15.482 4.94891 15.1867 4.6536 14.8225 4.6536H11.2674V6.12258H15.482ZM9.86129 4.6536H7.14017V12.7254L8.15469 12.1518C8.36941 12.0304 8.63204 12.0304 8.84676 12.1518L9.86129 12.7254V4.6536Z">
                                    </path>
                                </svg>
                            </div>
                            <div class="tf-mini-cart-tool-btn btn-estimate-shipping">
                                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="18" viewBox="0 0 26 18"
                                    fill="currentColor">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M0 0.811989C0 0.36354 0.36354 0 0.811989 0H15.4278C15.8763 0 16.2398 0.36354 16.2398 0.811989V3.10596H21.0144C23.6241 3.10596 25.8643 5.05894 25.8643 7.61523V14.6414C25.8643 15.0899 25.5007 15.4534 25.0523 15.4534H23.545C23.2139 16.9115 21.9098 18 20.3514 18C18.7931 18 17.4889 16.9115 17.1578 15.4534H8.69534C8.36423 16.9115 7.0601 18 5.50175 18C3.9434 18 2.63927 16.9115 2.30815 15.4534H0.811989C0.36354 15.4534 0 15.0899 0 14.6414V0.811989ZM2.35089 13.8294C2.74052 12.4562 4.00366 11.4503 5.50175 11.4503C6.99983 11.4503 8.26298 12.4562 8.6526 13.8294H14.6158V1.62398H1.62398V13.8294H2.35089ZM16.2398 4.72994V7.95749H24.2403V7.61523C24.2403 6.08759 22.8649 4.72994 21.0144 4.72994H16.2398ZM24.2403 9.58147H16.2398V13.8294H17.2006C17.5902 12.4562 18.8533 11.4503 20.3514 11.4503C21.8495 11.4503 23.1126 12.4562 23.5023 13.8294H24.2403V9.58147ZM5.50175 13.0743C4.58999 13.0743 3.85087 13.8134 3.85087 14.7251C3.85087 15.6369 4.58999 16.376 5.50175 16.376C6.41351 16.376 7.15263 15.6369 7.15263 14.7251C7.15263 13.8134 6.41351 13.0743 5.50175 13.0743ZM20.3514 13.0743C19.4397 13.0743 18.7005 13.8134 18.7005 14.7251C18.7005 15.6369 19.4397 16.376 20.3514 16.376C21.2632 16.376 22.0023 15.6369 22.0023 14.7251C22.0023 13.8134 21.2632 13.0743 20.3514 13.0743Z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                        <div class="tf-mini-cart-bottom-wrap">
                            <div class="tf-cart-totals-discounts">
                                <div class="tf-cart-total">Subtotal</div>
                                <div class="tf-totals-total-value fw-6" id="cart-subtotal">{{ formatCurrency(49.99) }}</div>
                            </div>
                            <div class="tf-cart-tax">Taxes and <a href="#">shipping</a> calculated at checkout</div>
                            <div class="tf-mini-cart-line"></div>
                            @if(cartTermsEnabled() || cartPrivacyEnabled())
                                <div class="tf-cart-checkboxes">
                                    @if(cartTermsEnabled())
                                        <div class="tf-cart-checkbox mb-2">
                                            <div class="tf-checkbox-wrapp">
                                                <input class="cart-terms-checkbox" type="checkbox" id="CartDrawer-Form_agree_terms"
                                                        name="agree_terms_checkbox" {{ cartTermsMandatory() ? 'required' : '' }}>
                                                <div>
                                                    <i class="icon-check"></i>
                                                </div>
                                            </div>
                                            <label for="CartDrawer-Form_agree_terms">
                                                {{ cartTermsText() }}
                                                <a href="{{ cartTermsLink() }}" target="_blank" title="{{ cartTermsLinkText() }}">{{ cartTermsLinkText() }}</a>
                                                @if(cartTermsMandatory())
                                                    <span class="text-danger">*</span>
                                                @endif
                                            </label>
                                        </div>
                                    @endif
                                    
                                    @if(cartPrivacyEnabled())
                                        <div class="tf-cart-checkbox">
                                            <div class="tf-checkbox-wrapp">
                                                <input class="cart-privacy-checkbox" type="checkbox" id="CartDrawer-Form_agree_privacy"
                                                        name="agree_privacy_checkbox" {{ cartPrivacyMandatory() ? 'required' : '' }}>
                                                <div>
                                                    <i class="icon-check"></i>
                                                </div>
                                            </div>
                                            <label for="CartDrawer-Form_agree_privacy">
                                                {{ cartPrivacyText() }}
                                                <a href="{{ cartPrivacyLink() }}" target="_blank" title="{{ cartPrivacyLinkText() }}">{{ cartPrivacyLinkText() }}</a>
                                                @if(cartPrivacyMandatory())
                                                    <span class="text-danger">*</span>
                                                @endif
                                            </label>
                                        </div>
                                    @endif
                                </div>
                            @else
                                <!-- Fallback to old static checkbox if all disabled -->
                                <div class="tf-cart-checkbox">
                                    <div class="tf-checkbox-wrapp">
                                        <input class="" type="checkbox" id="CartDrawer-Form_agree"
                                                name="agree_checkbox">
                                        <div>
                                            <i class="icon-check"></i>
                                        </div>
                                    </div>
                                    <label for="CartDrawer-Form_agree">
                                        I agree with the
                                        <a href="#" title="Terms of Service">terms and conditions</a>
                                    </label>
                                </div>
                            @endif
                            <div class="tf-mini-cart-view-checkout">
                                <a href="{{ route('cart.index') }}"
                                    class="tf-btn btn-outline radius-3 link w-100 justify-content-center">View
                                    cart</a>
                                <a href="{{ route('checkout.index') }}"
                                    class="tf-btn btn-fill animate-hover-btn radius-3 w-100 justify-content-center"><span>Check
                                        out</span></a>
                            </div>
                        </div>
                    </div>
                    <div class="tf-mini-cart-tool-openable add-note">
                        <div class="overplay tf-mini-cart-tool-close"></div>
                        <div class="tf-mini-cart-tool-content">
                            <label for="Cart-note" class="tf-mini-cart-tool-text">
                                <div class="icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="18"
                                        viewBox="0 0 16 18" fill="currentColor">
                                        <path
                                            d="M5.12187 16.4582H2.78952C2.02045 16.4582 1.39476 15.8325 1.39476 15.0634V2.78952C1.39476 2.02045 2.02045 1.39476 2.78952 1.39476H11.3634C12.1325 1.39476 12.7582 2.02045 12.7582 2.78952V7.07841C12.7582 7.46357 13.0704 7.77579 13.4556 7.77579C13.8407 7.77579 14.1529 7.46357 14.1529 7.07841V2.78952C14.1529 1.25138 12.9016 0 11.3634 0H2.78952C1.25138 0 0 1.25138 0 2.78952V15.0634C0 16.6015 1.25138 17.8529 2.78952 17.8529H5.12187C5.50703 17.8529 5.81925 17.5407 5.81925 17.1555C5.81925 16.7704 5.50703 16.4582 5.12187 16.4582Z">
                                        </path>
                                        <path
                                            d="M15.3882 10.0971C14.5724 9.28136 13.2452 9.28132 12.43 10.0965L8.60127 13.9168C8.51997 13.9979 8.45997 14.0979 8.42658 14.2078L7.59276 16.9528C7.55646 17.0723 7.55292 17.1993 7.58249 17.3207C7.61206 17.442 7.67367 17.5531 7.76087 17.6425C7.84807 17.7319 7.95768 17.7962 8.07823 17.8288C8.19879 17.8613 8.32587 17.8609 8.44621 17.8276L11.261 17.0479C11.3769 17.0158 11.4824 16.9543 11.5675 16.8694L15.3882 13.0559C16.2039 12.2401 16.2039 10.9129 15.3882 10.0971ZM10.712 15.7527L9.29586 16.145L9.71028 14.7806L12.2937 12.2029L13.2801 13.1893L10.712 15.7527ZM14.4025 12.0692L14.2673 12.204L13.2811 11.2178L13.4157 11.0834C13.6876 10.8115 14.1301 10.8115 14.402 11.0834C14.6739 11.3553 14.6739 11.7977 14.4025 12.0692Z">
                                        </path>
                                    </svg>
                                </div>
                                <span>Add Order Note</span>
                            </label>
                            <textarea name="note" id="Cart-note" placeholder="How can we help you?"></textarea>
                            <div class="tf-cart-tool-btns justify-content-center">
                                <div
                                    class="tf-mini-cart-tool-primary text-center w-100 fw-6 tf-mini-cart-tool-close">
                                    Close</div>
                            </div>
                        </div>
                    </div>
                    <div class="tf-mini-cart-tool-openable add-gift">
                        <div class="overplay tf-mini-cart-tool-close"></div>
                        <div class="tf-mini-cart-tool-content">
                            <div class="tf-mini-cart-tool-text">
                                <div class="icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="currentColor">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M4.65957 3.64545C4.65957 0.73868 7.89921 -0.995558 10.3176 0.617949L11.9997 1.74021L13.6818 0.617949C16.1001 -0.995558 19.3398 0.73868 19.3398 3.64545V4.32992H20.4286C21.9498 4.32992 23.1829 5.56311 23.1829 7.08416V9.10087C23.1829 9.61861 22.7632 10.0383 22.2454 10.0383H21.8528V20.2502C21.8528 20.254 21.8527 20.2577 21.8527 20.2614C21.8467 22.3272 20.1702 24 18.103 24H5.89634C3.82541 24 2.14658 22.3212 2.14658 20.2502V10.0384H1.75384C1.23611 10.0384 0.816406 9.61865 0.816406 9.10092V7.08421C0.816406 5.56304 2.04953 4.32992 3.57069 4.32992H4.65957V3.64545ZM6.53445 4.32992H11.0622V3.36863L9.27702 2.17757C8.10519 1.39573 6.53445 2.2357 6.53445 3.64545V4.32992ZM12.9371 3.36863V4.32992H17.4649V3.64545C17.4649 2.2357 15.8942 1.39573 14.7223 2.17756L12.9371 3.36863ZM3.57069 6.2048C3.08499 6.2048 2.69128 6.59851 2.69128 7.08421V8.16348H8.31067L8.3107 6.2048H3.57069ZM8.31071 10.0384V18.5741C8.31071 18.9075 8.48779 19.2158 8.77577 19.3838C9.06376 19.5518 9.4193 19.5542 9.70953 19.3901L11.9997 18.0953L14.2898 19.3901C14.58 19.5542 14.9356 19.5518 15.2236 19.3838C15.5115 19.2158 15.6886 18.9075 15.6886 18.5741V10.0383H19.9779V20.2137C19.9778 20.2169 19.9778 20.2201 19.9778 20.2233V20.2502C19.9778 21.2857 19.1384 22.1251 18.103 22.1251H5.89634C4.86088 22.1251 4.02146 21.2857 4.02146 20.2502V10.0384H8.31071ZM21.308 8.16344V7.08416C21.308 6.59854 20.9143 6.2048 20.4286 6.2048H15.6886V8.16344H21.308ZM13.8138 6.2048H10.1856V16.9672L11.5383 16.2024C11.8246 16.0405 12.1748 16.0405 12.461 16.2024L13.8138 16.9672V6.2048Z">
                                        </path>
                                    </svg>
                                </div>
                                <div class="tf-gift-wrap-infos">
                                    <p>Do you want a gift wrap?</p>
                                    <div class="gift-wrap-options">
                                        @php
                                            $giftWrapOptions = [
                                                ['name' => 'Basic Gift Wrap', 'price' => 5.00, 'description' => 'Simple wrapping paper with ribbon'],
                                                ['name' => 'Premium Gift Wrap', 'price' => 10.00, 'description' => 'Elegant box with bow and card'],
                                                ['name' => 'Luxury Gift Wrap', 'price' => 15.00, 'description' => 'Premium box with custom message']
                                            ];
                                        @endphp
                                        @foreach($giftWrapOptions as $index => $option)
                                            <div class="gift-wrap-option" data-price="{{ $option['price'] }}">
                                                <input type="radio" id="gift-wrap-{{ $index }}" name="gift_wrap_type" value="{{ $option['name'] }}" data-price="{{ $option['price'] }}">
                                                <label for="gift-wrap-{{ $index }}" class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <div class="fw-6">{{ $option['name'] }}</div>
                                                        <small class="text-muted">{{ $option['description'] }}</small>
                                                    </div>
                                                    <span class="price fw-6">{{ formatCurrency($option['price']) }}</span>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                    
                                    <div class="gift-message-section mt-3" style="display: none;">
                                        <label for="gift-message" class="form-label small fw-6">Gift Message (Optional)</label>
                                        <textarea class="form-control" id="gift-message" name="gift_message" rows="3" placeholder="Write your gift message here..." maxlength="200"></textarea>
                                        <small class="text-muted">Maximum 200 characters</small>
                                    </div>
                                    
                                    <div class="gift-wrap-summary mt-3" style="display: none;">
                                        <div class="d-flex justify-content-between">
                                            <span>Selected Option:</span>
                                            <span id="selected-gift-wrap-name" class="fw-6"></span>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span>Additional Cost:</span>
                                            <span id="selected-gift-wrap-price" class="fw-6 text-success"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="tf-cart-tool-btns">
                                <button type="button" id="add-gift-wrap-btn" disabled
                                    class="tf-btn fw-6 w-100 justify-content-center btn-fill animate-hover-btn radius-3">
                                    <span>Add Gift Wrap</span>
                                </button>
                                <div class="tf-mini-cart-tool-primary text-center w-100 fw-6 tf-mini-cart-tool-close">
                                    Cancel
                                </div>
                            </div>
                            
                            <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const giftWrapOptions = document.querySelectorAll('input[name="gift_wrap_type"]');
                                const giftMessageSection = document.querySelector('.gift-message-section');
                                const giftWrapSummary = document.querySelector('.gift-wrap-summary');
                                const addGiftWrapBtn = document.getElementById('add-gift-wrap-btn');
                                const selectedNameSpan = document.getElementById('selected-gift-wrap-name');
                                const selectedPriceSpan = document.getElementById('selected-gift-wrap-price');
                                let selectedGiftWrap = null;
                                
                                // Handle gift wrap option selection
                                giftWrapOptions.forEach(option => {
                                    option.addEventListener('change', function() {
                                        if (this.checked) {
                                            selectedGiftWrap = {
                                                name: this.value,
                                                price: parseFloat(this.dataset.price)
                                            };
                                            
                                            // Show gift message section
                                            giftMessageSection.style.display = 'block';
                                            giftWrapSummary.style.display = 'block';
                                            
                                            // Update summary
                                            selectedNameSpan.textContent = selectedGiftWrap.name;
                                            selectedPriceSpan.textContent = '{{ currencySymbol() }}' + selectedGiftWrap.price.toFixed(2);
                                            
                                            // Enable add button
                                            addGiftWrapBtn.disabled = false;
                                            addGiftWrapBtn.classList.remove('disabled');
                                        }
                                    });
                                });
                                
                                // Handle add gift wrap
                                addGiftWrapBtn.addEventListener('click', function() {
                                    if (!selectedGiftWrap) {
                                        alert('Please select a gift wrap option');
                                        return;
                                    }
                                    
                                    const giftMessage = document.getElementById('gift-message').value;
                                    
                                    // Add gift wrap to cart (you'll need to implement this based on your cart system)
                                    addGiftWrapToCart(selectedGiftWrap, giftMessage);
                                    
                                    // Close modal
                                    document.querySelector('.tf-mini-cart-tool-close').click();
                                    
                                    // Show success message
                                    showNotification('Gift wrap added to cart!', 'success');
                                });
                                
                                function addGiftWrapToCart(giftWrap, message) {
                                    // This function should integrate with your cart system
                                    console.log('Adding gift wrap to cart:', {
                                        name: giftWrap.name,
                                        price: giftWrap.price,
                                        message: message
                                    });
                                    
                                    // Add gift wrap item to cart display
                                    addGiftWrapToDisplay(giftWrap, message);
                                    
                                    // Update cart totals
                                    updateCartTotals();
                                    
                                    // You might want to make an AJAX call to save this to session/database
                                    /*
                                    fetch('/cart/add-gift-wrap', {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                                        },
                                        body: JSON.stringify({
                                            gift_wrap: giftWrap.name,
                                            gift_wrap_price: giftWrap.price,
                                            gift_message: message
                                        })
                                    })
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data.success) {
                                            updateCartDisplay();
                                        }
                                    });
                                    */
                                }
                                
                                function addGiftWrapToDisplay(giftWrap, message) {
                                    // Check if gift wrap already exists in cart
                                    const existingGiftWrap = document.querySelector('.tf-mini-cart-item.gift-wrap-item');
                                    if (existingGiftWrap) {
                                        existingGiftWrap.remove(); // Remove existing gift wrap to replace
                                    }
                                    
                                    // Create gift wrap item HTML
                                    const giftWrapItem = document.createElement('div');
                                    giftWrapItem.className = 'tf-mini-cart-item gift-wrap-item';
                                    giftWrapItem.innerHTML = `
                                        <div class="tf-mini-cart-image">
                                            <div class="gift-wrap-icon">
                                                🎁
                                            </div>
                                        </div>
                                        <div class="tf-mini-cart-info">
                                            <div class="title">${giftWrap.name}</div>
                                            ${message ? `<div class="meta-variant">Message: "${message}"</div>` : ''}
                                            <div class="price fw-6">{{ currencySymbol() }}${giftWrap.price.toFixed(2)}</div>
                                            <div class="tf-mini-cart-btns">
                                                <div class="tf-mini-cart-remove gift-wrap-remove">Remove</div>
                                            </div>
                                        </div>
                                    `;
                                    
                                    // Add to cart items container
                                    const cartContainer = document.getElementById('cart-items-container');
                                    if (cartContainer) {
                                        cartContainer.appendChild(giftWrapItem);
                                        
                                        // Add remove functionality
                                        const removeBtn = giftWrapItem.querySelector('.gift-wrap-remove');
                                        removeBtn.addEventListener('click', function() {
                                            removeGiftWrapFromCart(giftWrapItem, giftWrap.price);
                                        });
                                    }
                                }
                                
                                function removeGiftWrapFromCart(itemElement, price) {
                                    // Remove from display
                                    itemElement.remove();
                                    
                                    // Update cart totals
                                    const currentSubtotal = getCurrentCartTotal();
                                    const newSubtotal = Math.max(currentSubtotal - price, 0);
                                    
                                    // Update subtotal display
                                    const subtotalElement = document.getElementById('cart-subtotal');
                                    if (subtotalElement) {
                                        subtotalElement.textContent = '{{ currencySymbol() }}' + newSubtotal.toFixed(2);
                                    }
                                    
                                    // Update free shipping progress
                                    updateFreeShippingProgress(newSubtotal);
                                    
                                    // Show notification
                                    showNotification('Gift wrap removed from cart!', 'info');
                                }
                                
                                // Helper function to get current cart total (local version for gift wrap)
                                function getCurrentCartTotal() {
                                    // This should return the current cart subtotal
                                    // You may need to implement this based on your cart system
                                    const subtotalElement = document.getElementById('cart-subtotal');
                                    if (subtotalElement) {
                                        // Extract number from formatted currency string
                                        const subtotalText = subtotalElement.textContent || subtotalElement.innerText;
                                        const match = subtotalText.match(/[\d,]+\.?\d*/);
                                        return match ? parseFloat(match[0].replace(/,/g, '')) : 0;
                                    }
                                    return 49.99; // Default fallback based on current cart
                                }
                                
                                function updateCartTotals() {
                                    // Update cart subtotal with gift wrap cost
                                    const currentSubtotal = getCurrentCartTotal();
                                    const newSubtotal = currentSubtotal + selectedGiftWrap.price;
                                    
                                    // Update subtotal display
                                    const subtotalElement = document.getElementById('cart-subtotal');
                                    if (subtotalElement) {
                                        subtotalElement.textContent = '{{ currencySymbol() }}' + newSubtotal.toFixed(2);
                                    }
                                    
                                    // Update free shipping progress
                                    updateFreeShippingProgress(newSubtotal);
                                }
                                
                                function updateFreeShippingProgress(newTotal) {
                                    const freeShippingThreshold = {{ config('shipping.free_shipping.minimum_order', 1000) }};
                                    const progressElement = document.querySelector('.tf-progress-bar span');
                                    const messageElement = document.getElementById('free-shipping-progress');
                                    
                                    if (progressElement && messageElement) {
                                        const progressPercentage = Math.min((newTotal / freeShippingThreshold) * 100, 100);
                                        const remainingForFree = Math.max(freeShippingThreshold - newTotal, 0);
                                        
                                        progressElement.style.width = progressPercentage + '%';
                                        
                                        if (remainingForFree > 0) {
                                            messageElement.innerHTML = `Buy <span class="price fw-6">{{ currencySymbol() }}${remainingForFree.toFixed(2)}</span> more to enjoy <span class="fw-6">Free Shipping</span>`;
                                        } else {
                                            messageElement.innerHTML = '<span class="fw-6 text-success">🎉 You qualify for Free Shipping!</span>';
                                        }
                                    }
                                }
                                
                                function showNotification(message, type = 'success') {
                                    // Create notification element
                                    const notification = document.createElement('div');
                                    notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
                                    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
                                    notification.innerHTML = `
                                        ${message}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                    `;
                                    
                                    document.body.appendChild(notification);
                                    
                                    // Auto remove after 3 seconds
                                    setTimeout(() => {
                                        if (notification.parentNode) {
                                            notification.parentNode.removeChild(notification);
                                        }
                                    }, 3000);
                                }
                            });
                            </script>
                            
                            <style>
                            .gift-wrap-option {
                                margin-bottom: 10px;
                                border: 1px solid #e1e5e9;
                                border-radius: 6px;
                                transition: all 0.3s ease;
                            }
                            
                            .gift-wrap-option:hover {
                                border-color: var(--primary-color, #007bff);
                                background-color: #f8f9fa;
                            }
                            
                            .gift-wrap-option input[type="radio"] {
                                display: none;
                            }
                            
                            .gift-wrap-option label {
                                padding: 12px 15px;
                                margin: 0;
                                cursor: pointer;
                                width: 100%;
                                border-radius: 6px;
                                transition: all 0.3s ease;
                            }
                            
                            .gift-wrap-option input[type="radio"]:checked + label {
                                background-color: var(--primary-color, #007bff);
                                color: white;
                                border-color: var(--primary-color, #007bff);
                            }
                            
                            .gift-wrap-option input[type="radio"]:checked + label .text-muted {
                                color: rgba(255, 255, 255, 0.8) !important;
                            }
                            
                            .gift-message-section textarea {
                                border: 1px solid #e1e5e9;
                                border-radius: 6px;
                                padding: 10px 12px;
                                font-size: 14px;
                                resize: vertical;
                            }
                            
                            .gift-message-section textarea:focus {
                                border-color: var(--primary-color, #007bff);
                                box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
                                outline: none;
                            }
                            
                            .gift-wrap-summary {
                                background-color: #f8f9fa;
                                padding: 12px;
                                border-radius: 6px;
                                border: 1px solid #e1e5e9;
                            }
                            
                            #add-gift-wrap-btn:disabled {
                                opacity: 0.6;
                                cursor: not-allowed;
                            }
                            
                            /* Gift wrap item in cart styling */
                            .tf-mini-cart-item.gift-wrap-item {
                                border-left: 3px solid #28a745;
                                background-color: #f8f9fa;
                                margin-bottom: 10px;
                            }
                            
                            .gift-wrap-icon {
                                width: 60px;
                                height: 60px;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                font-size: 24px;
                                background-color: #28a745;
                                border-radius: 6px;
                                color: white;
                            }
                            
                            .tf-mini-cart-item.gift-wrap-item .title {
                                color: #28a745;
                                font-weight: 600;
                            }
                            
                            .tf-mini-cart-item.gift-wrap-item .meta-variant {
                                font-size: 12px;
                                color: #6c757d;
                                margin-top: 2px;
                                font-style: italic;
                            }
                            
                            .gift-wrap-remove {
                                background-color: #dc3545;
                                color: white;
                                padding: 4px 8px;
                                border-radius: 4px;
                                font-size: 12px;
                                cursor: pointer;
                                transition: background-color 0.3s ease;
                            }
                            
                            .gift-wrap-remove:hover {
                                background-color: #c82333;
                            }
                            </style>
                        </div>
                    </div>
                    <div class="tf-mini-cart-tool-openable estimate-shipping">
                        <div class="overplay tf-mini-cart-tool-close"></div>
                        <div class="tf-mini-cart-tool-content">
                            <div class="tf-mini-cart-tool-text">
                                <div class="icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="21" height="15"
                                        viewBox="0 0 21 15" fill="currentColor">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M0.441406 1.13155C0.441406 0.782753 0.724159 0.5 1.07295 0.5H12.4408C12.7896 0.5 13.0724 0.782753 13.0724 1.13155V2.91575H16.7859C18.8157 2.91575 20.5581 4.43473 20.5581 6.42296V11.8878C20.5581 12.2366 20.2753 12.5193 19.9265 12.5193H18.7542C18.4967 13.6534 17.4823 14.5 16.2703 14.5C15.0582 14.5 14.0439 13.6534 13.7864 12.5193H7.20445C6.94692 13.6534 5.93259 14.5 4.72054 14.5C3.50849 14.5 2.49417 13.6534 2.23664 12.5193H1.07295C0.724159 12.5193 0.441406 12.2366 0.441406 11.8878V1.13155ZM2.26988 11.2562C2.57292 10.1881 3.55537 9.40578 4.72054 9.40578C5.88572 9.40578 6.86817 10.1881 7.17121 11.2562H11.8093V1.76309H1.7045V11.2562H2.26988ZM13.0724 4.17884V6.68916H19.295V6.42296C19.295 5.2348 18.2252 4.17884 16.7859 4.17884H13.0724ZM19.295 7.95226H13.0724V11.2562H13.8196C14.1227 10.1881 15.1051 9.40578 16.2703 9.40578C17.4355 9.40578 18.4179 10.1881 18.7209 11.2562H19.295V7.95226ZM4.72054 10.6689C4.0114 10.6689 3.43652 11.2437 3.43652 11.9529C3.43652 12.662 4.0114 13.2369 4.72054 13.2369C5.42969 13.2369 6.00456 12.662 6.00456 11.9529C6.00456 11.2437 5.42969 10.6689 4.72054 10.6689ZM16.2703 10.6689C15.5611 10.6689 14.9863 11.2437 14.9863 11.9529C14.9863 12.662 15.5611 13.2369 16.2703 13.2369C16.9794 13.2369 17.5543 12.662 17.5543 11.9529C17.5543 11.2437 16.9794 10.6689 16.2703 10.6689Z">
                                        </path>
                                    </svg>
                                </div>
                                <span class="fw-6">Estimate Shipping</span>
                            </div>
                            <div class="field">
                                <p>District</p>
                                <select class="tf-select w-100" id="ShippingDistrict" name="district">
                                    <option value="">Select District</option>
                                    @php
                                        $deliveryCharges = \Illuminate\Support\Facades\DB::table('delivery_charges')
                                            ->where('is_active', 1)
                                            ->orderBy('district')
                                            ->orderBy('upazila')
                                            ->orderBy('ward')
                                            ->get()
                                            ->groupBy('district');
                                    @endphp
                                    @foreach($deliveryCharges as $district => $charges)
                                        <option value="{{ $district }}">{{ $district }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="field" id="upazila-field" style="display: none;">
                                <p>Upazila</p>
                                <select class="tf-select w-100" id="ShippingUpazila" name="upazila">
                                    <option value="">Select Upazila</option>
                                </select>
                            </div>
                            <div class="field" id="ward-field" style="display: none;">
                                <p>Ward</p>
                                <select class="tf-select w-100" id="ShippingWard" name="ward">
                                    <option value="">Select Ward</option>
                                </select>
                            </div>
                            <div id="shipping-result" class="shipping-result" style="display: none;">
                                <div class="shipping-info">
                                    <div class="shipping-charge" id="shipping-charge-display"></div>
                                    <div class="delivery-time" id="delivery-time-display"></div>
                                    <div class="free-shipping-msg" id="free-shipping-msg" style="display: none; color: #10B981; font-weight: 600; margin-top: 8px;"></div>
                                </div>
                            </div>
                            <div class="tf-cart-tool-btns">
                                <button type="button" class="tf-btn fw-6 justify-content-center btn-fill w-100 animate-hover-btn radius-3" id="calculate-shipping-btn">
                                    <span>Calculate Shipping</span>
                                </button>
                                <div class="tf-mini-cart-tool-primary text-center fw-6 w-100 tf-mini-cart-tool-close">
                                    Cancel</div>
                            </div>
                            
                            <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const districtSelect = document.getElementById('ShippingDistrict');
                                const upazilaSelect = document.getElementById('ShippingUpazila');
                                const wardSelect = document.getElementById('ShippingWard');
                                const upazilaField = document.getElementById('upazila-field');
                                const wardField = document.getElementById('ward-field');
                                const shippingResult = document.getElementById('shipping-result');
                                const calculateBtn = document.getElementById('calculate-shipping-btn');
                                
                                // Delivery charges data from database
                                const deliveryCharges = @json($deliveryCharges->toArray());
                                
                                // Shipping config from config/shipping.php
                                const shippingConfig = {
                                    freeShippingEnabled: {{ config('shipping.free_shipping.enabled') ? 'true' : 'false' }},
                                    freeShippingThreshold: {{ config('shipping.free_shipping.minimum_order', 1000) }},
                                    currency: '{{ currencySymbol() }}'
                                };
                                
                                // Handle district change
                                districtSelect.addEventListener('change', function() {
                                    const selectedDistrict = this.value;
                                    upazilaSelect.innerHTML = '<option value="">Select Upazila</option>';
                                    wardSelect.innerHTML = '<option value="">Select Ward</option>';
                                    
                                    if (selectedDistrict && deliveryCharges[selectedDistrict]) {
                                        console.log('District selected:', selectedDistrict);
                                        console.log('Available charges for district:', deliveryCharges[selectedDistrict]);
                                        
                                        const upazilas = [...new Set(deliveryCharges[selectedDistrict]
                                            .map(item => item.upazila)
                                            .filter(upazila => upazila && upazila.trim() !== '')
                                        )];
                                        
                                        console.log('Found upazilas:', upazilas);
                                        
                                        if (upazilas.length > 0) {
                                            upazilas.forEach(upazila => {
                                                upazilaSelect.innerHTML += `<option value="${upazila}">${upazila}</option>`;
                                            });
                                            upazilaField.style.display = 'block';
                                        } else {
                                            // If no specific upazilas, auto-select district level and show calculate button
                                            upazilaSelect.innerHTML += '<option value="District Level" selected>District Level Delivery</option>';
                                            upazilaField.style.display = 'block';
                                        }
                                    } else {
                                        upazilaField.style.display = 'none';
                                        wardField.style.display = 'none';
                                    }
                                    shippingResult.style.display = 'none';
                                });
                                
                                // Handle upazila change
                                upazilaSelect.addEventListener('change', function() {
                                    const selectedDistrict = districtSelect.value;
                                    const selectedUpazila = this.value;
                                    wardSelect.innerHTML = '<option value="">Select Ward</option>';
                                    
                                    if (selectedDistrict && selectedUpazila && deliveryCharges[selectedDistrict]) {
                                        const wards = deliveryCharges[selectedDistrict]
                                            .filter(item => item.upazila === selectedUpazila)
                                            .map(item => item.ward)
                                            .filter(ward => ward);
                                        
                                        const uniqueWards = [...new Set(wards)];
                                        uniqueWards.forEach(ward => {
                                            wardSelect.innerHTML += `<option value="${ward}">${ward}</option>`;
                                        });
                                        
                                        if (uniqueWards.length > 0) {
                                            wardField.style.display = 'block';
                                        }
                                    } else {
                                        wardField.style.display = 'none';
                                    }
                                    shippingResult.style.display = 'none';
                                });
                                
                                // Calculate shipping
                                calculateBtn.addEventListener('click', function() {
                                    const selectedDistrict = districtSelect.value;
                                    const selectedUpazila = upazilaSelect.value;
                                    const selectedWard = wardSelect.value || 'Default';
                                    
                                    console.log('Selected:', {
                                        district: selectedDistrict,
                                        upazila: selectedUpazila, 
                                        ward: selectedWard
                                    });
                                    
                                    console.log('Available delivery charges:', deliveryCharges);
                                    
                                    if (!selectedDistrict) {
                                        alert('Please select a district');
                                        return;
                                    }
                                    
                                    if (!selectedUpazila) {
                                        alert('Please select an upazila');
                                        return;
                                    }
                                    
                                    // Find matching delivery charge
                                    let deliveryCharge = null;
                                    
                                    // Handle district level delivery
                                    if (selectedUpazila === 'District Level') {
                                        if (deliveryCharges[selectedDistrict]) {
                                            deliveryCharge = deliveryCharges[selectedDistrict].find(item => 
                                                !item.upazila || item.upazila === null
                                            );
                                        }
                                    } else {
                                        // Find matching delivery charge for specific upazila
                                        if (deliveryCharges[selectedDistrict]) {
                                            console.log('District charges found:', deliveryCharges[selectedDistrict]);
                                            
                                            deliveryCharge = deliveryCharges[selectedDistrict].find(item => 
                                                item.upazila === selectedUpazila && 
                                                (item.ward === selectedWard || item.ward === null || item.ward === '')
                                            );
                                            
                                            // Fallback to district/upazila only if no exact match
                                            if (!deliveryCharge) {
                                                deliveryCharge = deliveryCharges[selectedDistrict].find(item => 
                                                    item.upazila === selectedUpazila
                                                );
                                            }
                                            
                                            // Final fallback to district-level charge
                                            if (!deliveryCharge) {
                                                deliveryCharge = deliveryCharges[selectedDistrict].find(item => 
                                                    !item.upazila
                                                );
                                            }
                                        }
                                    }
                                    
                                    console.log('Found delivery charge:', deliveryCharge);
                                    
                                    if (deliveryCharge) {
                                        const charge = parseFloat(deliveryCharge.charge) || 0;
                                        const deliveryTime = deliveryCharge.estimated_delivery_time || '3-5 business days';
                                        
                                        // Get current cart total (you may need to adjust this based on your cart implementation)
                                        const cartTotal = getCurrentCartTotal(); // This function needs to be implemented
                                        
                                        // Check for free shipping
                                        let finalCharge = charge;
                                        let freeShippingMsg = '';
                                        
                                        if (shippingConfig.freeShippingEnabled && cartTotal >= shippingConfig.freeShippingThreshold) {
                                            finalCharge = 0;
                                            freeShippingMsg = `🎉 Free shipping applied! (Order over ${shippingConfig.currency}${shippingConfig.freeShippingThreshold})`;
                                        }
                                        
                                        // Display results
                                        document.getElementById('shipping-charge-display').innerHTML = 
                                            `<strong>Shipping Charge: ${shippingConfig.currency}${finalCharge.toFixed(2)}</strong>`;
                                        document.getElementById('delivery-time-display').innerHTML = 
                                            `<small>Estimated Delivery: ${deliveryTime}</small>`;
                                        
                                        const freeShippingElement = document.getElementById('free-shipping-msg');
                                        if (freeShippingMsg) {
                                            freeShippingElement.innerHTML = freeShippingMsg;
                                            freeShippingElement.style.display = 'block';
                                        } else {
                                            freeShippingElement.style.display = 'none';
                                        }
                                        
                                        shippingResult.style.display = 'block';
                                    } else {
                                        alert('Shipping not available for selected location');
                                    }
                                });
                                
                                // Helper function to get current cart total
                                function getCurrentCartTotal() {
                                    // This should return the current cart subtotal
                                    // You may need to implement this based on your cart system
                                    const subtotalElement = document.getElementById('cart-subtotal');
                                    if (subtotalElement) {
                                        // Extract number from formatted currency string
                                        const subtotalText = subtotalElement.textContent || subtotalElement.innerText;
                                        const match = subtotalText.match(/[\d,]+\.?\d*/);
                                        return match ? parseFloat(match[0].replace(/,/g, '')) : 0;
                                    }
                                    return 1000; // Default fallback
                                }
                            });

                            // Dynamic Cart Terms and Conditions Validation
                            document.addEventListener('DOMContentLoaded', function() {
                                const checkoutBtn = document.querySelector('a[href="{{ route('checkout.index') }}"]');
                                const viewCartBtn = document.querySelector('a[href="{{ route('cart.index') }}"]');
                                
                                if (checkoutBtn) {
                                    checkoutBtn.addEventListener('click', function(e) {
                                        // Check for mandatory checkboxes
                                        let validationFailed = false;
                                        let errorMessage = '';
                                        
                                        @if(cartTermsEnabled() && cartTermsMandatory())
                                            const termsCheckbox = document.getElementById('CartDrawer-Form_agree_terms');
                                            if (termsCheckbox && !termsCheckbox.checked) {
                                                validationFailed = true;
                                                errorMessage += 'Please accept the {{ cartTermsLinkText() }}.\n';
                                            }
                                        @endif
                                        
                                        @if(cartPrivacyEnabled() && cartPrivacyMandatory())
                                            const privacyCheckbox = document.getElementById('CartDrawer-Form_agree_privacy');
                                            if (privacyCheckbox && !privacyCheckbox.checked) {
                                                validationFailed = true;
                                                errorMessage += 'Please accept the {{ cartPrivacyLinkText() }}.\n';
                                            }
                                        @endif
                                        
                                        if (validationFailed) {
                                            e.preventDefault();
                                            e.stopPropagation();
                                            
                                            // Show error message
                                            if (typeof Swal !== 'undefined') {
                                                Swal.fire({
                                                    title: 'Required Agreement',
                                                    text: errorMessage.trim(),
                                                    icon: 'warning',
                                                    confirmButtonText: 'OK',
                                                    confirmButtonColor: '#007bff'
                                                });
                                            } else {
                                                alert('Required Agreement:\n' + errorMessage);
                                            }
                                            
                                            // Highlight unchecked required checkboxes
                                            @if(cartTermsEnabled() && cartTermsMandatory())
                                                const termsCheckbox = document.getElementById('CartDrawer-Form_agree_terms');
                                                if (termsCheckbox && !termsCheckbox.checked) {
                                                    const termsWrapper = termsCheckbox.closest('.tf-cart-checkbox');
                                                    if (termsWrapper) {
                                                        termsWrapper.style.border = '2px solid #dc3545';
                                                        termsWrapper.style.borderRadius = '6px';
                                                        termsWrapper.style.padding = '8px';
                                                        termsWrapper.style.backgroundColor = '#f8f9fa';
                                                        
                                                        // Remove highlight after user interaction
                                                        termsCheckbox.addEventListener('change', function() {
                                                            if (this.checked) {
                                                                termsWrapper.style.border = '';
                                                                termsWrapper.style.borderRadius = '';
                                                                termsWrapper.style.padding = '';
                                                                termsWrapper.style.backgroundColor = '';
                                                            }
                                                        }, { once: true });
                                                    }
                                                }
                                            @endif
                                            
                                            @if(cartPrivacyEnabled() && cartPrivacyMandatory())
                                                const privacyCheckbox = document.getElementById('CartDrawer-Form_agree_privacy');
                                                if (privacyCheckbox && !privacyCheckbox.checked) {
                                                    const privacyWrapper = privacyCheckbox.closest('.tf-cart-checkbox');
                                                    if (privacyWrapper) {
                                                        privacyWrapper.style.border = '2px solid #dc3545';
                                                        privacyWrapper.style.borderRadius = '6px';
                                                        privacyWrapper.style.padding = '8px';
                                                        privacyWrapper.style.backgroundColor = '#f8f9fa';
                                                        
                                                        // Remove highlight after user interaction
                                                        privacyCheckbox.addEventListener('change', function() {
                                                            if (this.checked) {
                                                                privacyWrapper.style.border = '';
                                                                privacyWrapper.style.borderRadius = '';
                                                                privacyWrapper.style.padding = '';
                                                                privacyWrapper.style.backgroundColor = '';
                                                            }
                                                        }, { once: true });
                                                    }
                                                }
                                            @endif
                                            
                                            return false;
                                        }
                                    });
                                }
                                
                                // Optional: Also validate for view cart button if needed
                                // This is typically not required but can be enabled if needed
                                /*
                                if (viewCartBtn) {
                                    viewCartBtn.addEventListener('click', function(e) {
                                        // Similar validation logic if needed for cart view
                                    });
                                }
                                */
                            });
                            </script>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /Modal Shopping Cart -->