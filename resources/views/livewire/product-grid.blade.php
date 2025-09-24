<div class="container py-5">
    @if($showTitle)
        <div class="row">
            <div class="col-md-12 px-0 text-center pb-5">
                <h1 class="montserrat-900 mb-0 text-green">PRODUCTOS</h1>
            </div>
        </div>
    @endif
    <div class="row">
        @forelse($products as $product)
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4" wire:key="product-{{ $product->id }}">
                <a href="{{ route('product.show', $product->slug) }}" class="text-decoration-none text-dark">
                    <div class="card h-100 border-0 shadow-sm product-card-hover">
                        @if($product->image_primary_path)
                            <img src="{{ asset('storage/' . $product->image_primary_path) }}"
                                 class="card-img-top"
                                 style="height: 250px; object-fit: cover;"
                                 alt="{{ $product->name }}">
                        @else
                            <div class="no-image-placeholder">
                                <i class="fas fa-image"></i>
                            </div>
                        @endif
                        <div class="card-body">
                            <h5 class="card-title mona-sans-700 text-gray">{{ strtoupper($product->name) }}</h5>
                            <p class="card-text mona-sans-400 font-size-16 mb-2">
                                @if($product->sale_price && $product->sale_price < $product->price)
                                    <span class="text-decoration-line-through text-muted">${{ number_format($product->price, 0, ',', '.') }}</span>
                                    <span class="text-danger fw-bold">${{ number_format($product->sale_price, 0, ',', '.') }}</span>
                                @else
                                    <span class="text-green fw-bold">${{ number_format($product->price, 0, ',', '.') }}</span>
                                @endif
                            </p>
                            @if($product->category)
                                <p class="card-text">
                                    <small class="text-muted">{{ $product->category->name }}</small>
                                </p>
                            @endif
                        </div>
                    </div>
                </a>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <p class="mona-sans-500 font-size-18 text-muted">No hay productos disponibles en este momento.</p>
            </div>
        @endforelse
    </div>

    @if($products->hasPages())
        <div class="row">
            <div class="col-12 d-flex justify-content-center">
                {{ $products->links() }}
            </div>
        </div>
    @endif
</div>
