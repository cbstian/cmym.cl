@extends('layout.master')

@section('title_1','Comercializadora M&M')
@section('title_2', $product->name)

@section('pre_css')
@endsection

@section('css')
{{-- Todos los estilos ahora están centralizados en resources/less/product.less --}}
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
                             class="img-fluid w-100 product-image-main product-image-zoomable"
                             alt="{{ $product->name }}"
                             data-bs-toggle="modal"
                             data-bs-target="#productImageModal"
                             style="cursor: pointer;"
                             title="Click para ampliar imagen">
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

                    {{-- Formulario de Compra con Livewire --}}
                    <livewire:cart.add-to-cart-button :product="$product" />

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

{{-- Modal para Zoom de Imagen --}}
<div class="modal fade" id="productImageModal" tabindex="-1" aria-labelledby="productImageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productImageModalLabel">{{ $product->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body text-center p-2">
                <img id="modalProductImage"
                     src="{{ $product->image_primary_path ? asset('storage/' . $product->image_primary_path) : asset('images/no-image.png') }}"
                     class="img-fluid"
                     alt="{{ $product->name }}"
                     style="max-height: 80vh; width: auto;">
            </div>
            <div class="modal-footer justify-content-center">
                <div class="d-flex gap-2 flex-wrap">
                    {{-- Thumbnail de imagen principal --}}
                    <img src="{{ $product->image_primary_path ? asset('storage/' . $product->image_primary_path) : asset('images/no-image.png') }}"
                         class="modal-thumb active-thumb"
                         data-modal-image="{{ $product->image_primary_path ? asset('storage/' . $product->image_primary_path) : asset('images/no-image.png') }}"
                         alt="{{ $product->name }}"
                         style="width: 60px; height: 60px; object-fit: cover; cursor: pointer; border-radius: 4px; border: 2px solid #44AD49;">

                    {{-- Thumbnails de imágenes adicionales --}}
                    @if($product->image_paths && count($product->image_paths) > 0)
                        @foreach($product->image_paths as $imagePath)
                        <img src="{{ asset('storage/' . $imagePath) }}"
                             class="modal-thumb"
                             data-modal-image="{{ asset('storage/' . $imagePath) }}"
                             alt="{{ $product->name }}"
                             style="width: 60px; height: 60px; object-fit: cover; cursor: pointer; border-radius: 4px; border: 2px solid transparent;">
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Cambio de imagen principal en la galería
        const thumbnails = document.querySelectorAll('.product-image-thumb');
        const mainImage = document.getElementById('mainProductImage');
        const modalImage = document.getElementById('modalProductImage');

        thumbnails.forEach(thumb => {
            thumb.addEventListener('click', function() {
                // Remover clase active de todos los thumbnails
                thumbnails.forEach(t => t.classList.remove('active'));

                // Agregar clase active al thumbnail clickeado
                this.classList.add('active');

                // Cambiar imagen principal
                const newImageSrc = this.dataset.image;
                mainImage.src = newImageSrc;

                // También actualizar la imagen del modal
                modalImage.src = newImageSrc;
            });
        });

        // Funcionalidad del modal - thumbnails en el footer del modal
        const modalThumbs = document.querySelectorAll('.modal-thumb');

        modalThumbs.forEach(thumb => {
            thumb.addEventListener('click', function() {
                // Remover clase active-thumb de todos los thumbnails del modal
                modalThumbs.forEach(t => {
                    t.classList.remove('active-thumb');
                    t.style.borderColor = 'transparent';
                });

                // Agregar clase active-thumb al thumbnail clickeado
                this.classList.add('active-thumb');
                this.style.borderColor = '#44AD49';

                // Cambiar imagen en el modal
                const newModalImageSrc = this.dataset.modalImage;
                modalImage.src = newModalImageSrc;
            });
        });

        // Sincronizar imagen del modal con la imagen principal cuando se abre el modal
        const productImageModal = document.getElementById('productImageModal');
        productImageModal.addEventListener('show.bs.modal', function() {
            // Obtener la imagen actualmente mostrada
            const currentMainImageSrc = mainImage.src;
            modalImage.src = currentMainImageSrc;

            // Sincronizar thumbnails del modal
            modalThumbs.forEach(thumb => {
                thumb.classList.remove('active-thumb');
                thumb.style.borderColor = 'transparent';

                if (thumb.dataset.modalImage === currentMainImageSrc) {
                    thumb.classList.add('active-thumb');
                    thumb.style.borderColor = '#44AD49';
                }
            });
        });

        // Navegación con teclado en el modal (flechas izquierda/derecha)
        productImageModal.addEventListener('keydown', function(e) {
            if (e.key === 'ArrowLeft' || e.key === 'ArrowRight') {
                e.preventDefault();

                const activeModalThumb = document.querySelector('.modal-thumb.active-thumb');
                const allModalThumbs = Array.from(modalThumbs);
                const currentIndex = allModalThumbs.indexOf(activeModalThumb);

                let nextIndex;
                if (e.key === 'ArrowLeft') {
                    nextIndex = currentIndex > 0 ? currentIndex - 1 : allModalThumbs.length - 1;
                } else {
                    nextIndex = currentIndex < allModalThumbs.length - 1 ? currentIndex + 1 : 0;
                }

                allModalThumbs[nextIndex].click();
            }
        });
    });
</script>
@endsection
