<div class="cart-manager">
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (count($cartItems) > 0)
        <div class="cart-items">
            @foreach ($cartItems as $index => $item)
                <div class="cart-item border-bottom py-3" wire:key="cart-item-{{ $index }}">
                    <div class="row align-items-center">
                        <div class="col-md-2">
                            @if ($item['product_image'] ?? null)
                                <img src="{{ Storage::url($item['product_image']) }}"
                                     alt="{{ $item['product_name'] ?? 'Producto' }}"
                                     class="img-fluid rounded w-100 product-image-cart">
                            @else
                                <div class="no-image-placeholder" style="height: 80px;">
                                    <i class="fas fa-image text-muted"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-4">
                            <h6 class="mb-1">{{ $item['product_name'] ?? 'Producto' }}</h6>
                            @if ($item['product_sku'] ?? null)
                                <small class="sku-text">SKU: {{ $item['product_sku'] }}</small>
                            @endif
                        </div>
                        <div class="col-md-2">
                            <strong class="price-highlight">${{ number_format($item['product_price'] ?? 0, 0, ',', '.') }}</strong>
                        </div>
                        <div class="col-md-2">
                            <div class="input-group input-group-sm">
                                <button class="btn btn-outline-secondary" type="button"
                                        wire:click="updateQuantity({{ $index }}, {{ ($item['quantity'] ?? 1) - 1 }})">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <input type="number" class="form-control text-center"
                                       value="{{ $item['quantity'] ?? 1 }}"
                                       wire:change="updateQuantity({{ $index }}, $event.target.value)"
                                       min="1">
                                <button class="btn btn-outline-secondary" type="button"
                                        wire:click="updateQuantity({{ $index }}, {{ ($item['quantity'] ?? 1) + 1 }})">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <strong class="price-highlight">${{ number_format(($item['product_price'] ?? 0) * ($item['quantity'] ?? 1), 0, ',', '.') }}</strong>
                        </div>
                        <div class="col-md-1">
                            <button class="btn btn-sm btn-outline-danger"
                                    wire:click="removeItem({{ $index }})"
                                    wire:confirm="¿Estás seguro de eliminar este producto?"
                                    title="Eliminar producto">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="cart-total">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <button class="btn btn-outline-secondary" wire:click="clearCart"
                                wire:confirm="¿Estás seguro de vaciar todo el carrito?">
                            <i class="fas fa-trash me-2"></i>
                            Vaciar Carrito
                        </button>
                    </div>
                    <div class="col-md-6 text-end">
                        <h4 class="mb-3">Total: <span class="price-highlight">${{ number_format($total, 0, ',', '.') }}</span></h4>
                        <a href="{{ route('checkout') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-credit-card me-2"></i>
                            Proceder al Pago
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="empty-cart text-center">
            <i class="fas fa-shopping-cart fa-3x mb-3"></i>
            <h5>Tu carrito está vacío</h5>
            <p class="text-muted">Agrega algunos productos para comenzar tu compra</p>
            <a href="{{ route('products') }}" class="btn btn-primary">
                <i class="fas fa-shopping-bag me-2"></i>
                Ver Productos
            </a>
        </div>
    @endif
</div>
