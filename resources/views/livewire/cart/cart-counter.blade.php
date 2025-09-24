<div class="cart-counter">
    @if ($itemCount > 0)
        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="z-index: 10;">
            {{ $itemCount }}
            <span class="visually-hidden">productos en carrito</span>
        </span>
    @endif
</div>
