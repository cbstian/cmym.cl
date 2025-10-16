<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación de Pedido #{{ $order->order_number }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f8f9fa;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 700px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 2px solid #44AD49;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #44AD49;
            margin: 0;
            font-size: 28px;
        }
        .order-number {
            color: #6c757d;
            font-size: 18px;
            margin-top: 5px;
        }
        .status-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: bold;
            margin-top: 10px;
        }
        .status-pending {
            background-color: #ffc107;
            color: #212529;
        }
        .status-paid {
            background-color: #28a745;
            color: white;
        }
        .section {
            margin-bottom: 30px;
        }
        .section-title {
            color: #44AD49;
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 15px;
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 10px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }
        .info-box {
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
            border-left: 4px solid #44AD49;
            margin-bottom: 10px;
        }
        .info-label {
            font-weight: bold;
            color: #44AD49;
            margin-bottom: 5px;
        }
        .info-value {
            color: #333;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .items-table th {
            background-color: #44AD49;
            color: white;
            padding: 12px;
            text-align: left;
            font-weight: bold;
        }
        .items-table td {
            padding: 12px;
            border-bottom: 1px solid #dee2e6;
        }
        .items-table tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .product-attributes {
            font-size: 12px;
            color: #6c757d;
            margin-top: 5px;
        }
        .attribute-badge {
            display: inline-block;
            background-color: #e9ecef;
            color: #495057;
            padding: 2px 6px;
            border-radius: 3px;
            margin-right: 5px;
            margin-bottom: 2px;
            font-size: 11px;
        }
        .total-section {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            border: 2px solid #44AD49;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        .total-row.final {
            font-size: 20px;
            font-weight: bold;
            color: #44AD49;
            border-top: 2px solid #44AD49;
            padding-top: 10px;
            margin-top: 15px;
        }
        .footer {
            text-align: center;
            padding-top: 30px;
            border-top: 1px solid #dee2e6;
            color: #6c757d;
            font-size: 14px;
        }
        .brand {
            color: #44AD49;
            font-weight: bold;
        }
        @media (max-width: 600px) {
            .info-grid {
                grid-template-columns: 1fr;
            }
            .items-table {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>✅ ¡Pedido Confirmado!</h1>
            <div class="order-number">Pedido #{{ $order->order_number }}</div>
            <div class="status-badge {{ $order->payment_status === 'paid' ? 'status-paid' : 'status-pending' }}">
                {{ $order->payment_status === 'paid' ? '💳 Pagado' : '⏳ Pendiente de pago' }}
            </div>
        </div>

        <div class="section">
            <div class="section-title">📋 Información del Pedido</div>
            <div class="info-grid">
                <div class="info-box">
                    <div class="info-label">📅 Fecha del pedido</div>
                    <div class="info-value">{{ $order->created_at->format('d/m/Y H:i') }}</div>
                </div>
                <div class="info-box">
                    <div class="info-label">🔄 Estado</div>
                    <div class="info-value">
                        @switch($order->status)
                            @case('pending')
                                Pendiente
                                @break
                            @case('processing')
                                En proceso
                                @break
                            @case('shipped')
                                Enviado
                                @break
                            @case('delivered')
                                Entregado
                                @break
                            @case('cancelled')
                                Cancelado
                                @break
                            @default
                                {{ ucfirst($order->status) }}
                        @endswitch
                    </div>
                </div>
            </div>
        </div>

        <div class="section">
            <div class="section-title">👤 Información del Cliente</div>
            <div class="info-grid">
                <div class="info-box">
                    <div class="info-label">Nombre</div>
                    <div class="info-value">{{ $order->customer->user->name }}</div>
                </div>
                <div class="info-box">
                    <div class="info-label">Email</div>
                    <div class="info-value">{{ $order->customer->user->email }}</div>
                </div>
                @if($order->customer->user->phone)
                <div class="info-box">
                    <div class="info-label">Teléfono</div>
                    <div class="info-value">{{ $order->customer->user->phone }}</div>
                </div>
                @endif
                @if($order->customer->rut)
                <div class="info-box">
                    <div class="info-label">RUT</div>
                    <div class="info-value">{{ $order->customer->rut }}</div>
                </div>
                @endif
            </div>
        </div>

        <div class="section">
            <div class="section-title">📦 Productos</div>
            <table class="items-table">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio Unit.</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr>
                        <td>
                            <strong>{{ $item->product_name }}</strong>
                            @if($item->product_sku)
                                <br><small>SKU: {{ $item->product_sku }}</small>
                            @endif
                            @if($item->product_attributes)
                                <div class="product-attributes">
                                    @foreach($item->product_attributes as $attr => $value)
                                        <span class="attribute-badge">{{ $attr }}: {{ $value }}</span>
                                    @endforeach
                                </div>
                            @endif
                        </td>
                        <td>{{ $item->quantity }}</td>
                        <td>${{ number_format($item->unit_price, 0, ',', '.') }}</td>
                        <td><strong>${{ number_format($item->total_price, 0, ',', '.') }}</strong></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="section">
            <div class="section-title">💰 Resumen de Costos</div>
            <div class="total-section">
                <div class="total-row">
                    <span>Subtotal:&nbsp;</span>
                    <span>${{ number_format($order->subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="total-row">
                    <span>Envío:&nbsp;</span>
                    <span>
                        @if($order->shipping_cost > 0)
                            ${{ number_format($order->shipping_cost, 0, ',', '.') }}
                        @elseif($order->courier_company)
                            Por pagar
                        @else
                            Gratis
                        @endif
                    </span>
                </div>
                @if($order->courier_company)
                    <div class="total-row">
                        <span>Empresa envío:&nbsp;</span>
                        <span>{{ $order->courier_company }}</span>
                    </div>
                @endif
                @if($order->discount_amount > 0)
                <div class="total-row">
                    <span>Descuento:&nbsp;</span>
                    <span>-${{ number_format($order->discount_amount, 0, ',', '.') }}</span>
                </div>
                @endif
                <div class="total-row final">
                    <span>Total:&nbsp;</span>
                    <span>${{ number_format($order->total_amount, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Dirección de Envío -->
        @if($order->shippingAddress)
        <div class="section">
            <h2 class="section-title">🚚 Dirección de Envío</h2>
            <div class="address-box">
                <strong>{{ $order->shippingAddress->name }}</strong><br>
                {{ $order->shippingAddress->address_line_1 }}<br>
                @if($order->shippingAddress->address_line_2)
                    {{ $order->shippingAddress->address_line_2 }}<br>
                @endif
                {{ $order->shippingAddress->commune?->name }}, {{ $order->shippingAddress->region?->name }}<br>
                Teléfono: {{ $order->shippingAddress->phone }}
            </div>
        </div>
        @endif

        <!-- Notas -->
        @if($order->notes)
        <div class="section">
            <h2 class="section-title">💬 Notas del Pedido</h2>
            <p style="background-color: #f8f9fa; padding: 15px; border-radius: 5px; margin: 0;">
                {{ $order->notes }}
            </p>
        </div>
        @endif

        <div class="footer">
            <p>Gracias por tu compra en <span class="brand">CMYM.cl</span></p>
            <p>Si tienes alguna pregunta sobre tu pedido, no dudes en contactarnos.</p>
            <p><strong>Comercializadora M&M</strong></p>
        </div>
    </div>
</body>
</html>
