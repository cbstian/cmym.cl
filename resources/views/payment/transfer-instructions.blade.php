@extends('layout.master')

@section('title', 'Instrucciones de Transferencia')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card border-info">
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="text-info mb-3">
                            <i class="fas fa-university" style="font-size: 4rem;"></i>
                        </div>
                        <h2 class="text-info mb-2">¡Pedido Recibido!</h2>
                        <p class="lead mb-0">Orden #{{ $order->order_number }}</p>
                    </div>

                    <div class="alert alert-info mb-4">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Importante:</strong> Tu pedido ha sido registrado exitosamente. Para completar tu compra,
                        realiza la transferencia bancaria siguiendo las instrucciones a continuación.
                    </div>

                    <div class="row mb-4">
                        <div class="col-lg-6 mb-3">
                            <div class="card bg-light h-100">
                                <div class="card-header bg-info text-white">
                                    <h5 class="mb-0"><i class="fas fa-university me-2"></i>Datos Bancarios</h5>
                                </div>
                                <div class="card-body">
                                    @if($bankDetails)
                                        <div class="bank-details">
                                            {!! nl2br(e($bankDetails)) !!}
                                        </div>
                                    @else
                                        <p class="text-muted">No se han configurado los datos bancarios. Por favor, contacta con el administrador.</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 mb-3">
                            <div class="card bg-light h-100">
                                <div class="card-header bg-secondary text-white">
                                    <h5 class="mb-0"><i class="fas fa-receipt me-2"></i>Resumen del Pedido</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm mb-0">
                                        <tbody>
                                            <tr>
                                                <td><strong>Número de Orden:</strong></td>
                                                <td class="text-end">#{{ $order->order_number }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Fecha:</strong></td>
                                                <td class="text-end">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Subtotal:</strong></td>
                                                <td class="text-end">${{ number_format($order->subtotal, 0, ',', '.') }} CLP</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Envío:</strong></td>
                                                <td class="text-end">${{ number_format($order->shipping_cost, 0, ',', '.') }} CLP</td>
                                            </tr>
                                            <tr class="table-info">
                                                <td><strong>Total a Transferir:</strong></td>
                                                <td class="text-end"><strong>${{ number_format($order->total_amount, 0, ',', '.') }} CLP</strong></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card border-warning mb-4">
                        <div class="card-header bg-warning">
                            <h5 class="mb-0"><i class="fas fa-clipboard-list me-2"></i>Instrucciones de Pago</h5>
                        </div>
                        <div class="card-body">
                            <ol class="mb-0">
                                <li class="mb-2">
                                    <strong>Realiza la transferencia</strong> por el monto de <strong>${{ number_format($order->total_amount, 0, ',', '.') }} CLP</strong>
                                    a la cuenta bancaria indicada arriba.
                                </li>
                                <li class="mb-2">
                                    <strong>Incluye como referencia</strong> el número de orden: <strong>#{{ $order->order_number }}</strong>
                                </li>
                                <li class="mb-2">
                                    <strong>Envía el comprobante de transferencia</strong> por correo electrónico a:
                                    @if($emailConfirmationPayment)
                                        <a href="mailto:{{ $emailConfirmationPayment }}?subject=Comprobante Orden {{ $order->order_number }}">{{ $emailConfirmationPayment }}</a>
                                    @else
                                        <span class="text-muted">No disponible (contacta con soporte)</span>
                                    @endif
                                </li>
                                <li class="mb-2">
                                    <strong>Verificación:</strong> Una vez que recibamos y verifiquemos tu transferencia,
                                    te enviaremos un correo de confirmación y procederemos a preparar tu pedido.
                                </li>
                                <li class="mb-0">
                                    <strong>Tiempo de procesamiento:</strong> Tu pedido será procesado dentro de las 24-48 horas
                                    hábiles después de verificar el pago.
                                </li>
                            </ol>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0"><i class="fas fa-shopping-cart me-2"></i>Productos Ordenados</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>Producto</th>
                                            <th class="text-center">Cantidad</th>
                                            <th class="text-end">Precio Unit.</th>
                                            <th class="text-end">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($order->items as $item)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($item->product_image_path)
                                                        <img src="{{ Storage::url($item->product_image_path) }}"
                                                             alt="{{ $item->product_name }}"
                                                             class="img-thumbnail me-2"
                                                             style="width: 50px; height: 50px; object-fit: cover;">
                                                    @endif
                                                    <div>
                                                        <strong>{{ $item->product_name }}</strong>
                                                        @if($item->product_sku)
                                                            <br><small class="text-muted">SKU: {{ $item->product_sku }}</small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">{{ $item->quantity }}</td>
                                            <td class="text-end">${{ number_format($item->unit_price, 0, ',', '.') }}</td>
                                            <td class="text-end"><strong>${{ number_format($item->total_price, 0, ',', '.') }}</strong></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    @if($order->shippingAddress)
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0"><i class="fas fa-truck me-2"></i>Dirección de Envío</h5>
                        </div>
                        <div class="card-body">
                            <address class="mb-0">
                                <strong>{{ $order->shippingAddress->name }}</strong><br>
                                {{ $order->shippingAddress->address_line_1 }}<br>
                                @if($order->shippingAddress->address_line_2)
                                    {{ $order->shippingAddress->address_line_2 }}<br>
                                @endif
                                {{ $order->shippingAddress->commune?->name }}, {{ $order->shippingAddress->region?->name }}<br>
                                Teléfono: {{ $order->shippingAddress->phone }}
                            </address>
                        </div>
                    </div>
                    @endif

                    @if($order->notes)
                    <div class="alert alert-secondary">
                        <strong><i class="fas fa-comment me-2"></i>Notas del pedido:</strong>
                        <p class="mb-0 mt-2">{{ $order->notes }}</p>
                    </div>
                    @endif

                    <div class="alert alert-success">
                        <i class="fas fa-envelope me-2"></i>
                        <strong>Confirmación enviada:</strong> Se ha enviado un correo electrónico a
                        <strong>{{ $order->customer->user->email }}</strong> con los detalles de tu pedido y estas instrucciones de pago.
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-center mt-4">
                        <a href="{{ route('home') }}" class="btn btn-primary">
                            <i class="fas fa-home me-2"></i>
                            Volver al Inicio
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    .btn, .navbar, .footer {
        display: none !important;
    }

    .card {
        border: 1px solid #dee2e6 !important;
        page-break-inside: avoid;
    }

    .card-header {
        background-color: #f8f9fa !important;
        color: #000 !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
}

.bank-details {
    white-space: pre-line;
    font-family: 'Courier New', monospace;
    font-size: 0.95rem;
    line-height: 1.6;
}
</style>
@endsection
