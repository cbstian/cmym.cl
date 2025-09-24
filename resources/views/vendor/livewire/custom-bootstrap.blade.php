@php
if (! isset($scrollTo)) {
    $scrollTo = 'body';
}

$scrollIntoViewJsSnippet = ($scrollTo !== false)
    ? <<<JS
       (\$el.closest('{$scrollTo}') || document.querySelector('{$scrollTo}')).scrollIntoView()
    JS
    : '';
@endphp

<div class="mt-4">
    @if ($paginator->hasPages())
        <div class="d-flex flex-column align-items-center">
            {{-- Información de resultados --}}
            <div class="mb-3">
                <p class="mona-sans-500 font-size-16 text-gray mb-0">
                    Mostrando <span class="montserrat-600">{{ $paginator->firstItem() }}</span>
                    a <span class="montserrat-600">{{ $paginator->lastItem() }}</span>
                    de <span class="montserrat-600">{{ $paginator->total() }}</span> productos
                </p>
            </div>

            {{-- Navegación de páginas --}}
            <nav aria-label="Navegación de productos">
                <ul class="pagination pagination-custom mb-0">
                    {{-- Enlace Página Anterior --}}
                    @if ($paginator->onFirstPage())
                        <li class="page-item disabled">
                            <span class="page-link page-link-custom disabled-link" aria-label="Anterior">
                                <i class="fas fa-chevron-left"></i>
                                <span class="d-none d-sm-inline ms-1">Anterior</span>
                            </span>
                        </li>
                    @else
                        <li class="page-item">
                            <button type="button"
                                    dusk="previousPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}"
                                    class="page-link page-link-custom"
                                    wire:click="previousPage('{{ $paginator->getPageName() }}')"
                                    x-on:click="{{ $scrollIntoViewJsSnippet }}"
                                    wire:loading.attr="disabled"
                                    aria-label="Anterior">
                                <i class="fas fa-chevron-left"></i>
                                <span class="d-none d-sm-inline ms-1">Anterior</span>
                            </button>
                        </li>
                    @endif

                    {{-- Elementos de Paginación --}}
                    @foreach ($elements as $element)
                        {{-- Separador "Tres Puntos" --}}
                        @if (is_string($element))
                            <li class="page-item disabled d-none d-md-block">
                                <span class="page-link page-link-custom disabled-link">{{ $element }}</span>
                            </li>
                        @endif

                        {{-- Array de Enlaces --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <li class="page-item active d-none d-md-block" wire:key="paginator-{{ $paginator->getPageName() }}-page-{{ $page }}" aria-current="page">
                                        <span class="page-link page-link-custom active-link">{{ $page }}</span>
                                    </li>
                                @else
                                    <li class="page-item d-none d-md-block" wire:key="paginator-{{ $paginator->getPageName() }}-page-{{ $page }}">
                                        <button type="button"
                                                class="page-link page-link-custom"
                                                wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')"
                                                x-on:click="{{ $scrollIntoViewJsSnippet }}">{{ $page }}</button>
                                    </li>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Información de página actual en móviles --}}
                    <li class="page-item d-md-none">
                        <span class="page-link page-link-custom active-link">
                            {{ $paginator->currentPage() }} / {{ $paginator->lastPage() }}
                        </span>
                    </li>

                    {{-- Enlace Página Siguiente --}}
                    @if ($paginator->hasMorePages())
                        <li class="page-item">
                            <button type="button"
                                    dusk="nextPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}"
                                    class="page-link page-link-custom"
                                    wire:click="nextPage('{{ $paginator->getPageName() }}')"
                                    x-on:click="{{ $scrollIntoViewJsSnippet }}"
                                    wire:loading.attr="disabled"
                                    aria-label="Siguiente">
                                <span class="d-none d-sm-inline me-1">Siguiente</span>
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </li>
                    @else
                        <li class="page-item disabled">
                            <span class="page-link page-link-custom disabled-link" aria-label="Siguiente">
                                <span class="d-none d-sm-inline me-1">Siguiente</span>
                                <i class="fas fa-chevron-right"></i>
                            </span>
                        </li>
                    @endif
                </ul>
            </nav>

            {{-- Indicador de carga --}}
            <div wire:loading class="mt-3">
                <div class="d-flex align-items-center text-green">
                    <div class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></div>
                    <span class="mona-sans-500 font-size-16">Cargando productos...</span>
                </div>
            </div>
        </div>
    @endif
</div>

<style>
.pagination-custom {
    gap: 0.25rem;
}

.page-link-custom {
    border: 2px solid #44AD49;
    background-color: transparent;
    color: #44AD49;
    font-family: "Montserrat", sans-serif;
    font-weight: 600;
    font-size: 16px;
    padding: 12px 16px;
    border-radius: 50px;
    transition: all 0.3s ease;
    text-decoration: none;
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 48px;
    height: 48px;
}

.page-link-custom:hover {
    background-color: #44AD49;
    color: #fff;
    border-color: #44AD49;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(68, 173, 73, 0.3);
}

.page-link-custom:focus {
    box-shadow: 0 0 0 3px rgba(68, 173, 73, 0.25);
    border-color: #44AD49;
}

.active-link {
    background-color: #44AD49 !important;
    color: #fff !important;
    border-color: #44AD49 !important;
    font-weight: 700;
}

.disabled-link {
    border-color: #e9ecef;
    color: #6c757d;
    background-color: #f8f9fa;
    cursor: not-allowed;
}

.disabled-link:hover {
    background-color: #f8f9fa;
    color: #6c757d;
    border-color: #e9ecef;
    transform: none;
    box-shadow: none;
}

.spinner-border-sm {
    width: 1rem;
    height: 1rem;
    border-width: 0.15em;
}

/* Animaciones de carga */
[wire\:loading] .page-link-custom {
    opacity: 0.6;
    pointer-events: none;
}

/* Responsive adjustments */
@media (max-width: 767.98px) {
    .page-link-custom {
        font-size: 14px;
        padding: 10px 14px;
        min-width: 44px;
        height: 44px;
    }
}
</style>
