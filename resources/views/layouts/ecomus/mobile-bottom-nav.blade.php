<!-- toolbar-bottom -->
<div class="tf-toolbar-bottom type-1150">
    <div class="toolbar-item">
        <a href="{{ route('home') }}">
            <div class="toolbar-icon">
                <i class="icon-home"></i>
            </div>
            <div class="toolbar-label">Home</div>
        </a>
    </div>

    <div class="toolbar-item">
        <a href="{{ route('shop.index') }}">
            <div class="toolbar-icon">
                <i class="icon-shop"></i>
            </div>
            <div class="toolbar-label">Shop</div>
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
                <div class="toolbar-label">Account</div>
            </a>
        @endauth
    </div>
    
    <div class="toolbar-item">
        <a href="{{ route('wishlist.index') }}">
            <div class="toolbar-icon">
                <i class="icon-heart"></i>
                <div class="toolbar-count" id="mobile-wishlist-count" style="display: none;">0</div>
            </div>
            <div class="toolbar-label">Wishlist</div>
        </a>
    </div>
    
    <div class="toolbar-item">
        <a href="#shoppingCart" data-bs-toggle="modal">
            <div class="toolbar-icon">
                <i class="icon-bag"></i>
                <div class="toolbar-count" id="mobile-cart-count" style="display: none;">0</div>
            </div>
            <div class="toolbar-label">Cart</div>
        </a>
    </div>
</div>
<!-- /toolbar-bottom -->