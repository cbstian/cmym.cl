<div class="product-purchase-form">
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session()->has('info'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            {{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Formulario de Compra --}}
    <form class="product-form" wire:submit.prevent="addToCart">
        {{-- Atributos del Producto --}}
        @if($product->attributes->count() > 0)
            <div class="product-attributes mb-4">
                @foreach($product->attributes as $attribute)
                    <div class="attribute-group mb-3">
                        <label class="form-label text-gray montserrat-600">
                            {{ $attribute->name }}
                            @if($attribute->is_required)
                                <span class="text-danger">*</span>
                            @endif
                        </label>

                        @if(count($attribute->values) > 0)
                            <select
                                class="form-select"
                                wire:model.live="selectedAttributes.{{ $attribute->id }}"
                                @if($attribute->is_required) required @endif>
                                <option value="">Seleccionar {{ strtolower($attribute->name) }}</option>
                                @foreach($attribute->values as $value)
                                    <option value="{{ $value }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif

        <div class="row align-items-start mb-4">
            {{-- Cantidad --}}
            <div class="col-auto">
                <label for="quantity" class="form-label text-gray montserrat-600">Cantidad:</label>
                <div class="input-group">
                    <button type="button"
                            class="btn btn-quantity"
                            wire:click="decreaseQuantity"
                            wire:loading.attr="disabled">
                        <i class="fas fa-minus"></i>
                    </button>
                    <input type="number"
                           id="quantity"
                           class="form-control quantity-input"
                           wire:model.live="quantity"
                           min="1"
                           max="{{ $product->stock_quantity }}">
                    <button type="button"
                            class="btn btn-quantity"
                            wire:click="increaseQuantity"
                            wire:loading.attr="disabled">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
            </div>
        </div>

        {{-- Botón Agregar al Carrito --}}
        <div class="mb-4">
            @if ($product->stock_quantity > 0)
                <button type="submit"
                        class="btn btn-add-to-cart w-100 py-3"
                        wire:loading.attr="disabled"
                        wire:target="addToCart">
                    <span wire:loading.remove wire:target="addToCart">
                        <i class="fas fa-shopping-cart me-2"></i>Agregar al carrito
                    </span>
                    <span wire:loading wire:target="addToCart">
                        <i class="fas fa-spinner fa-spin me-2"></i>Agregando...
                    </span>
                </button>
            @else
                <button type="button"
                        class="btn btn-secondary w-100 py-3 montserrat-600"
                        disabled>
                    <i class="fas fa-times me-2"></i>Sin Stock
                </button>
            @endif
        </div>

        {{-- Botón WhatsApp --}}
        <div class="mb-4">
            <a href="{{ $product->getWhatsappUrl() }}"
               class="btn btn-outline-green w-100 py-3"
               target="_blank"
               rel="noopener">
                <i class="fab fa-whatsapp me-2"></i>Consultar por WhatsApp
            </a>
        </div>
    </form>

    {{-- Información de Stock --}}
    @if ($product->stock_quantity > 0 && $product->stock_quantity <= 10)
        <div class="alert alert-warning">
            <small>
                <i class="fas fa-exclamation-triangle me-1"></i>
                ¡Solo quedan {{ $product->stock_quantity }} unidades disponibles!
            </small>
        </div>
    @elseif ($product->stock_quantity > 0)
        <div class="text-success small">
            <i class="fas fa-check me-1"></i>
            En stock ({{ $product->stock_quantity }} disponibles)
        </div>
    @endif
</div>
