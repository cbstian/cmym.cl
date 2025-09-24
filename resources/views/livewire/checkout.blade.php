{{-- Mostrar errores y mensajes --}}
@if (session()->has('error'))
    <div class="alert alert-danger checkout-alert alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (session()->has('success'))
    <div class="alert alert-success checkout-alert alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<form wire:submit="processCheckout" class="checkout-form">
    <div class="row">
        {{-- Formulario del checkout --}}
        <div class="col-lg-8">
            {{-- Datos del cliente --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-user me-2"></i>
                        Datos del Cliente
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">
                                Nombre Completo <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   class="form-control @error('name') is-invalid @enderror"
                                   id="name"
                                   wire:model="name"
                                   placeholder="Ingresa tu nombre completo">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">
                                Correo Electrónico <span class="text-danger">*</span>
                            </label>
                            <input type="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   id="email"
                                   wire:model="email"
                                   placeholder="tu@email.com">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">
                                Teléfono <span class="text-danger">*</span>
                            </label>
                            <input type="tel"
                                   class="form-control @error('phone') is-invalid @enderror"
                                   id="phone"
                                   wire:model="phone"
                                   placeholder="+56 9 xxxx xxxx">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="rut" class="form-label">RUT (opcional)</label>
                            <input type="text"
                                   class="form-control @error('rut') is-invalid @enderror"
                                   id="rut"
                                   wire:model="rut"
                                   placeholder="12.345.678-9">
                            @error('rut')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 mb-3">
                            <label for="company_name" class="form-label">Empresa (opcional)</label>
                            <input type="text"
                                   class="form-control @error('company_name') is-invalid @enderror"
                                   id="company_name"
                                   wire:model="company_name"
                                   placeholder="Nombre de tu empresa">
                            @error('company_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Dirección de envío --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-shipping-fast me-2"></i>
                        Dirección de Envío
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="shipping_region_id" class="form-label">
                                Región <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('shipping_region_id') is-invalid @enderror"
                                    id="shipping_region_id"
                                    wire:model.live="shipping_region_id">
                                <option value="">Selecciona una región</option>
                                @foreach($regions as $region)
                                    <option value="{{ $region->id }}">{{ $region->name }}</option>
                                @endforeach
                            </select>
                            @error('shipping_region_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="shipping_commune_id" class="form-label">
                                Comuna <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('shipping_commune_id') is-invalid @enderror"
                                    id="shipping_commune_id"
                                    wire:model="shipping_commune_id"
                                    @if(empty($communes)) disabled @endif>
                                <option value="">Selecciona una comuna</option>
                                @foreach($communes as $commune)
                                    <option value="{{ $commune->id }}">{{ $commune->name }}</option>
                                @endforeach
                            </select>
                            @error('shipping_commune_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 mb-3">
                            <label for="shipping_address_line_1" class="form-label">
                                Dirección <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   class="form-control @error('shipping_address_line_1') is-invalid @enderror"
                                   id="shipping_address_line_1"
                                   wire:model="shipping_address_line_1"
                                   placeholder="Calle, número, departamento, etc.">
                            @error('shipping_address_line_1')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 mb-3">
                            <label for="shipping_address_line_2" class="form-label">
                                Información Adicional (opcional)
                            </label>
                            <input type="text"
                                   class="form-control @error('shipping_address_line_2') is-invalid @enderror"
                                   id="shipping_address_line_2"
                                   wire:model="shipping_address_line_2"
                                   placeholder="Piso, oficina, referencias, etc.">
                            @error('shipping_address_line_2')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Dirección de facturación --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-file-invoice me-2"></i>
                        Dirección de Facturación
                    </h5>
                </div>
                <div class="card-body">
                    <div class="form-check mb-3">
                        <input class="form-check-input"
                               type="checkbox"
                               id="same_as_shipping"
                               wire:model.live="same_as_shipping">
                        <label class="form-check-label" for="same_as_shipping">
                            Usar la misma dirección de envío
                        </label>
                    </div>

                    @if(!$same_as_shipping)
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="billing_region_id" class="form-label">
                                    Región <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('billing_region_id') is-invalid @enderror"
                                        id="billing_region_id"
                                        wire:model.live="billing_region_id">
                                    <option value="">Selecciona una región</option>
                                    @foreach($regions as $region)
                                        <option value="{{ $region->id }}">{{ $region->name }}</option>
                                    @endforeach
                                </select>
                                @error('billing_region_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="billing_commune_id" class="form-label">
                                    Comuna <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('billing_commune_id') is-invalid @enderror"
                                        id="billing_commune_id"
                                        wire:model="billing_commune_id"
                                        @if(empty($billing_communes)) disabled @endif>
                                    <option value="">Selecciona una comuna</option>
                                    @foreach($billing_communes as $commune)
                                        <option value="{{ $commune->id }}">{{ $commune->name }}</option>
                                    @endforeach
                                </select>
                                @error('billing_commune_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 mb-3">
                                <label for="billing_address_line_1" class="form-label">
                                    Dirección <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                       class="form-control @error('billing_address_line_1') is-invalid @enderror"
                                       id="billing_address_line_1"
                                       wire:model="billing_address_line_1"
                                       placeholder="Calle, número, departamento, etc.">
                                @error('billing_address_line_1')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 mb-3">
                                <label for="billing_address_line_2" class="form-label">
                                    Información Adicional (opcional)
                                </label>
                                <input type="text"
                                       class="form-control @error('billing_address_line_2') is-invalid @enderror"
                                       id="billing_address_line_2"
                                       wire:model="billing_address_line_2"
                                       placeholder="Piso, oficina, referencias, etc.">
                                @error('billing_address_line_2')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Notas adicionales --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-sticky-note me-2"></i>
                        Notas Adicionales
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="order_notes" class="form-label">
                            Comentarios sobre tu pedido (opcional)
                        </label>
                        <textarea class="form-control @error('order_notes') is-invalid @enderror"
                                  id="order_notes"
                                  rows="3"
                                  wire:model="order_notes"
                                  placeholder="Instrucciones especiales, horarios de entrega, etc."></textarea>
                        @error('order_notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Método de pago --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-credit-card me-2"></i>
                        Método de Pago
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        {{-- Webpay Plus --}}
                        <div class="col-12 mb-3">
                            <div class="form-check payment-method-option">
                                <input class="form-check-input"
                                       type="radio"
                                       name="payment_method"
                                       id="webpay"
                                       value="webpay"
                                       wire:model="payment_method">
                                <label class="form-check-label d-flex align-items-center" for="webpay">
                                    <div class="payment-method-info">
                                        <div class="d-flex align-items-center">
                                            <img src="{{ asset('images/webpay-logo.png') }}"
                                                 alt="Webpay Plus"
                                                 class="payment-logo me-3"
                                                 onerror="this.style.display='none'"
                                                 style="height: 30px;">
                                            <div>
                                                <strong>Webpay Plus</strong>
                                                <div class="text-muted small">Tarjetas de crédito y débito</div>
                                            </div>
                                        </div>
                                        <div class="payment-cards mt-2">
                                            <i class="fab fa-cc-visa text-primary me-1" title="Visa"></i>
                                            <i class="fab fa-cc-mastercard text-warning me-1" title="Mastercard"></i>
                                            <i class="fas fa-credit-card text-info me-1" title="Débito"></i>
                                            <span class="badge bg-success ms-2">
                                                <i class="fas fa-shield-alt me-1"></i>Seguro
                                            </span>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        {{-- Transferencia Bancaria --}}
                        <div class="col-12 mb-3">
                            <div class="form-check payment-method-option">
                                <input class="form-check-input"
                                       type="radio"
                                       name="payment_method"
                                       id="transfer"
                                       value="transfer"
                                       wire:model="payment_method">
                                <label class="form-check-label d-flex align-items-center" for="transfer">
                                    <div class="payment-method-info">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-university text-primary me-3" style="font-size: 1.5rem;"></i>
                                            <div>
                                                <strong>Transferencia Bancaria</strong>
                                                <div class="text-muted small">Pago manual via transferencia</div>
                                            </div>
                                        </div>
                                        <div class="mt-2">
                                            <span class="badge bg-warning">
                                                <i class="fas fa-clock me-1"></i>Validación manual
                                            </span>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    @error('payment_method')
                        <div class="text-danger small mt-2">{{ $message }}</div>
                    @enderror

                    {{-- Información adicional según método seleccionado --}}
                    @if($payment_method === 'webpay')
                        <div class="alert alert-info mt-3">
                            <i class="fas fa-info-circle me-2"></i>
                            Serás redirigido a la plataforma segura de Webpay Plus para completar tu pago.
                        </div>
                    @elseif($payment_method === 'transfer')
                        <div class="alert alert-warning mt-3">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Recibirás los datos bancarios por email para realizar la transferencia.
                            Tu pedido será procesado una vez confirmemos el pago.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Resumen del pedido --}}
        <div class="col-lg-4">
            <div class="order-summary">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-shopping-cart me-2"></i>
                            Resumen del Pedido
                        </h5>
                    </div>
                    <div class="card-body">
                        {{-- Items del carrito --}}
                        <div class="mb-3">
                            @forelse($cartItems as $item)
                                <div class="cart-item-summary">
                                    @if($item['product_image'])
                                        <img src="{{ asset('storage/' . $item['product_image']) }}"
                                             alt="{{ $item['product_name'] }}"
                                             class="item-image">
                                    @else
                                        <div class="no-image">
                                            <i class="fas fa-image"></i>
                                        </div>
                                    @endif

                                    <div class="item-details">
                                        <p class="item-name">{{ $item['product_name'] }}</p>
                                        @if($item['product_sku'])
                                            <p class="item-price">SKU: {{ $item['product_sku'] }}</p>
                                        @endif
                                        <p class="item-price">
                                            ${{ number_format($item['product_price'], 0, ',', '.') }} c/u
                                        </p>
                                    </div>

                                    <div class="item-quantity">
                                        x{{ $item['quantity'] }}
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-4">
                                    <i class="fas fa-shopping-cart fa-2x text-muted mb-2"></i>
                                    <p class="text-muted mb-0">Tu carrito está vacío</p>
                                </div>
                            @endforelse
                        </div>

                        {{-- Totales --}}
                        @if(!empty($cartItems))
                            <div class="order-total">
                                <div class="total-row">
                                    <span class="total-label">Subtotal:</span>
                                    <span class="total-value">${{ number_format($subtotal, 0, ',', '.') }}</span>
                                </div>

                                @if($shipping_cost > 0)
                                    <div class="total-row">
                                        <span class="total-label">Envío:</span>
                                        <span class="total-value">${{ number_format($shipping_cost, 0, ',', '.') }}</span>
                                    </div>
                                @endif

                                <div class="total-row">
                                    <span class="total-label">Total:</span>
                                    <span class="total-value">${{ number_format($total, 0, ',', '.') }}</span>
                                </div>
                            </div>

                            {{-- Botón de pagar --}}
                            <button type="submit"
                                    class="checkout-pay-button"
                                    @if($isLoading || empty($cartItems)) disabled @endif
                                    wire:loading.attr="disabled">
                                <span wire:loading.remove>
                                    <i class="fas fa-credit-card me-2"></i>
                                    Pagar
                                </span>
                                <span wire:loading>
                                    <i class="fas fa-spinner fa-spin me-2"></i>
                                    Procesando...
                                </span>
                            </button>

                            <div class="text-center mt-3">
                                <small class="text-muted">
                                    <i class="fas fa-lock me-1"></i>
                                    Compra 100% segura
                                </small>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
