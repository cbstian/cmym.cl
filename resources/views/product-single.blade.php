@extends('layout.master')

@section('title_1','Comercializadora M&M')
@section('title_2', $product->name)

@section('pre_css')
@endsection

@section('css')
<style>
    .product-image-main {
        max-height: 500px;
        object-fit: cover;
        border-radius: 8px;
    }

    .product-image-thumb {
        height: 80px;
        object-fit: cover;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .product-image-thumb:hover {
        opacity: 0.7;
        transform: scale(1.05);
    }

    .product-image-thumb.active {
        border: 3px solid #44AD49;
    }

    .product-price {
        font-size: 2rem;
        font-weight: 900;
    }

    .product-price-sale {
        color: #44AD49;
    }

    .product-price-regular {
        color: #999;
        text-decoration: line-through;
        font-size: 1.4rem;
        font-weight: 600;
    }

    .product-meta {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 20px;
    }

    .product-tabs {
        border-bottom: 2px solid #e9ecef;
    }

    .product-tabs .nav-link {
        color: #6c757d;
        border: none;
        padding: 15px 20px;
        font-weight: 600;
        border-bottom: 3px solid transparent;
    }

    .product-tabs .nav-link.active {
        color: #44AD49;
        background: none;
        border-bottom-color: #44AD49;
    }

    .quantity-input {
        width: 80px;
        text-align: center;
    }

    .btn-quantity {
        width: 35px;
        height: 40px;
        border: 1px solid #dee2e6;
        background: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .btn-quantity:hover {
        background: #f8f9fa;
    }
</style>
@endsection

@section('content')

{{-- Breadcrumb --}}
<div class="container-fluid bg-light py-2">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('home') }}" class="text-decoration-none text-green">
                        <i class="fas fa-home me-1"></i>Inicio
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('products') }}" class="text-decoration-none text-green">Productos</a>
                </li>
                @if($product->category)
                <li class="breadcrumb-item">
                    <span class="text-muted">{{ $product->category->name }}</span>
                </li>
                @endif
                <li class="breadcrumb-item active text-gray" aria-current="page">
                    {{ Str::limit($product->name, 50) }}
                </li>
            </ol>
        </nav>
    </div>
</div>

{{-- Producto Principal --}}
<div class="container-fluid bg-white py-5">
    <div class="container">
        <div class="row">
            {{-- Galería de Imágenes --}}
            <div class="col-lg-6 mb-4">
                <div class="product-gallery">
                    {{-- Imagen Principal --}}
                    <div class="mb-3">
                        <img id="mainProductImage"
                             src="{{ $product->image_primary_path ? asset('storage/' . $product->image_primary_path) : asset('images/no-image.png') }}"
                             class="img-fluid w-100 product-image-main"
                             alt="{{ $product->name }}">
                    </div>

                    {{-- Miniaturas --}}
                    @if($product->image_paths && count($product->image_paths) > 0)
                    <div class="row g-2">
                        {{-- Imagen principal como thumbnail --}}
                        <div class="col-3">
                            <img src="{{ $product->image_primary_path ? asset('storage/' . $product->image_primary_path) : asset('images/no-image.png') }}"
                                 class="img-fluid w-100 product-image-thumb active"
                                 data-image="{{ $product->image_primary_path ? asset('storage/' . $product->image_primary_path) : asset('images/no-image.png') }}"
                                 alt="{{ $product->name }}">
                        </div>

                        {{-- Imágenes adicionales --}}
                        @foreach(array_slice($product->image_paths, 0, 3) as $imagePath)
                        <div class="col-3">
                            <img src="{{ asset('storage/' . $imagePath) }}"
                                 class="img-fluid w-100 product-image-thumb"
                                 data-image="{{ asset('storage/' . $imagePath) }}"
                                 alt="{{ $product->name }}">
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>

            {{-- Información del Producto --}}
            <div class="col-lg-6">
                <div class="product-info">
                    {{-- Título y Categoría --}}
                    <h1 class="montserrat-900 text-gray mb-2">{{ $product->name }}</h1>
                    @if($product->category)
                    <p class="text-muted mb-3">
                        <i class="fas fa-tag me-1"></i>{{ $product->category->name }}
                    </p>
                    @endif

                    {{-- Precio --}}
                    <div class="product-pricing mb-4">
                        @if($product->sale_price && $product->sale_price < $product->price)
                            <span class="product-price product-price-sale">${{ number_format($product->sale_price, 0, ',', '.') }}</span>
                            <span class="product-price-regular ms-2">${{ number_format($product->price, 0, ',', '.') }}</span>
                        @else
                            <span class="product-price text-green">${{ number_format($product->price, 0, ',', '.') }}</span>
                        @endif
                    </div>

                    {{-- SKU --}}
                    @if($product->sku)
                    <p class="text-muted mb-3">
                        <strong>SKU:</strong> {{ $product->sku }}
                    </p>
                    @endif

                    {{-- Descripción Corta --}}
                    @if($product->short_description)
                    <div class="product-short-description mb-4">
                        <p class="text-gray font-size-18 lh-base">{{ $product->short_description }}</p>
                    </div>
                    @endif

                    {{-- Formulario de Compra --}}
                    <form class="product-form">
                        <div class="row align-items-end mb-4">
                            {{-- Cantidad --}}
                            <div class="col-auto">
                                <label for="quantity" class="form-label text-gray montserrat-600">Cantidad:</label>
                                <div class="input-group">
                                    <button type="button" class="btn btn-quantity" onclick="decreaseQuantity()">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <input type="number" id="quantity" class="form-control quantity-input" value="1" min="1">
                                    <button type="button" class="btn btn-quantity" onclick="increaseQuantity()">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>

                            {{-- Botón Agregar al Carrito --}}
                            <div class="col">
                                <button type="button" class="btn btn-primary-green text-white montserrat-600 w-100 py-3">
                                    <i class="fas fa-shopping-cart me-2"></i>Agregar al carrito
                                </button>
                            </div>
                        </div>

                        {{-- Botón Contactar --}}
                        <div class="mb-4">
                            <a href="{{ route('contact') }}" class="btn btn-outline-green w-100 py-3 montserrat-600">
                                <i class="fas fa-comments me-2"></i>Consultar por WhatsApp
                            </a>
                        </div>
                    </form>

                    {{-- Información Adicional --}}
                    <div class="product-meta">
                        <div class="row">
                            @if($product->weight)
                            <div class="col-6 mb-2">
                                <strong class="text-gray">Peso:</strong><br>
                                <span class="text-muted">{{ $product->weight }} kg</span>
                            </div>
                            @endif
                            @if($product->dimensions)
                            <div class="col-6 mb-2">
                                <strong class="text-gray">Dimensiones:</strong><br>
                                <span class="text-muted">{{ $product->dimensions }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Tabs de Información Detallada --}}
<div class="container-fluid bg-light py-5">
    <div class="container">
        <div class="row">
            <div class="col-12">
                {{-- Navegación de Tabs --}}
                <ul class="nav nav-tabs product-tabs" id="productTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="description-tab" data-bs-toggle="tab" data-bs-target="#description" type="button" role="tab">
                            Descripción
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="specifications-tab" data-bs-toggle="tab" data-bs-target="#specifications" type="button" role="tab">
                            Especificaciones
                        </button>
                    </li>
                </ul>

                {{-- Contenido de Tabs --}}
                <div class="tab-content bg-white p-4" id="productTabsContent">
                    {{-- Descripción --}}
                    <div class="tab-pane fade show active" id="description" role="tabpanel">
                        @if($product->description)
                            <div class="text-gray lh-lg">
                                {!! nl2br(e($product->description)) !!}
                            </div>
                        @else
                            <p class="text-muted">No hay descripción disponible para este producto.</p>
                        @endif
                    </div>

                    {{-- Especificaciones --}}
                    <div class="tab-pane fade" id="specifications" role="tabpanel">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    @if($product->sku)
                                    <tr>
                                        <td><strong>SKU</strong></td>
                                        <td>{{ $product->sku }}</td>
                                    </tr>
                                    @endif
                                    @if($product->weight)
                                    <tr>
                                        <td><strong>Peso</strong></td>
                                        <td>{{ $product->weight }} kg</td>
                                    </tr>
                                    @endif
                                    @if($product->dimensions)
                                    <tr>
                                        <td><strong>Dimensiones</strong></td>
                                        <td>{{ $product->dimensions }}</td>
                                    </tr>
                                    @endif
                                    @if($product->category)
                                    <tr>
                                        <td><strong>Categoría</strong></td>
                                        <td>{{ $product->category->name }}</td>
                                    </tr>
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Productos Relacionados --}}
<div class="container-fluid bg-white py-5">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h3 class="montserrat-900 text-green">Productos Relacionados</h3>
                <p class="text-gray">Descubre otros productos que podrían interesarte</p>
            </div>
        </div>

        @php
            $relatedProducts = \App\Models\Product::where('category_id', $product->category_id)
                ->where('id', '!=', $product->id)
                ->where('is_active', true)
                ->limit(4)
                ->get();
        @endphp

        @if($relatedProducts->count() > 0)
        <div class="row">
            @foreach($relatedProducts as $relatedProduct)
            <div class="col-md-3 mb-4">
                <a href="{{ route('product.show', $relatedProduct->slug) }}" class="text-decoration-none">
                    <div class="card h-100 border-0 shadow-sm product-card-hover">
                        @if($relatedProduct->image_primary_path)
                            <img src="{{ asset('storage/' . $relatedProduct->image_primary_path) }}"
                                 class="card-img-top"
                                 style="height: 200px; object-fit: cover;"
                                 alt="{{ $relatedProduct->name }}">
                        @else
                            <div class="no-image-placeholder" style="height: 200px; font-size: 2rem;">
                                <i class="fas fa-image"></i>
                            </div>
                        @endif
                        <div class="card-body">
                            <h5 class="card-title text-gray">{{ Str::limit($relatedProduct->name, 40) }}</h5>
                            <p class="card-text text-green montserrat-700 mb-0">
                                @if($relatedProduct->sale_price && $relatedProduct->sale_price < $relatedProduct->price)
                                    ${{ number_format($relatedProduct->sale_price, 0, ',', '.') }}
                                    <small class="text-muted text-decoration-line-through ms-1">
                                        ${{ number_format($relatedProduct->price, 0, ',', '.') }}
                                    </small>
                                @else
                                    ${{ number_format($relatedProduct->price, 0, ',', '.') }}
                                @endif
                            </p>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
        @else
        <div class="row">
            <div class="col-12 text-center">
                <p class="text-muted">No hay productos relacionados disponibles.</p>
            </div>
        </div>
        @endif
    </div>
</div>

@endsection

@section('js')
<script>
    // Cambio de imagen principal
    document.addEventListener('DOMContentLoaded', function() {
        const thumbnails = document.querySelectorAll('.product-image-thumb');
        const mainImage = document.getElementById('mainProductImage');

        thumbnails.forEach(thumb => {
            thumb.addEventListener('click', function() {
                // Remover clase active de todos los thumbnails
                thumbnails.forEach(t => t.classList.remove('active'));

                // Agregar clase active al thumbnail clickeado
                this.classList.add('active');

                // Cambiar imagen principal
                mainImage.src = this.dataset.image;
            });
        });
    });

    // Funciones para cantidad
    function increaseQuantity() {
        const quantityInput = document.getElementById('quantity');
        quantityInput.value = parseInt(quantityInput.value) + 1;
    }

    function decreaseQuantity() {
        const quantityInput = document.getElementById('quantity');
        if (parseInt(quantityInput.value) > 1) {
            quantityInput.value = parseInt(quantityInput.value) - 1;
        }
    }
</script>
@endsection
