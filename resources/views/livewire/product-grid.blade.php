<div class="container py-5">
    <div class="row">
        <div class="col-md-12 px-0 text-center pb-5">
            <h1 class="montserrat-900 mb-0 text-green">PRODUCTOS</h1>
        </div>
    </div>
    <div class="row">
        @forelse($products as $product)
            <div class="col-md-3 mb-4" wire:key="product-{{ $product->id }}">
                <div class="product">
                    <img src="{{ $product->image_primary_path ? asset('storage/' . $product->image_primary_path) : asset('images/product-1-sample.jpg') }}" class="img-fluid mb-3" alt="{{ $product->name }}">
                    <p class="mona-sans-700 font-size-20 mb-2">{{ strtoupper($product->name) }}</p>
                    <p class="mona-sans-400 font-size-16">
                        @if($product->sale_price && $product->sale_price < $product->price)
                            <span class="text-decoration-line-through text-muted">${{ number_format($product->price, 0, ',', '.') }}</span>
                            <span class="text-danger">${{ number_format($product->sale_price, 0, ',', '.') }}</span>
                        @else
                            ${{ number_format($product->price, 0, ',', '.') }}
                        @endif
                    </p>
                </div>
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
