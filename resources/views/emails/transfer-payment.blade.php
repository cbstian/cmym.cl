<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instrucciones de Pago - Pedido #{{ $order->order_number }}</title>
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
            border-bottom: 2px solid #17a2b8;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #17a2b8;
            margin: 0;
            font-size: 28px;
        }
        .header .icon {
            font-size: 48px;
            margin-bottom: 10px;
        }
        .order-number {
            color: #6c757d;
            font-size: 18px;
            margin-top: 5px;
        }
        .alert {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 25px;
            background-color: #d1ecf1;
            border-left: 4px solid #17a2b8;
            color: #0c5460;
        }
        .alert strong {
            color: #17a2b8;
        }
        .section {
            margin-bottom: 30px;
        }
        .section-title {
            color: #17a2b8;
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 15px;
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 10px;
        }
        .bank-details {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            border-left: 4px solid #17a2b8;
            margin-bottom: 20px;
            white-space: pre-line;
            font-family: 'Courier New', Courier, monospace;
            font-size: 14px;
            line-height: 1.8;
        }
        .summary-table {
            width: 100%;
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .summary-table table {
            width: 100%;
            border-collapse: collapse;
        }
        .summary-table td {
            padding: 8px 0;
            border-bottom: 1px solid #dee2e6;
        }
        .summary-table td:first-child {
            font-weight: bold;
            color: #6c757d;
        }
        .summary-table td:last-child {
            text-align: right;
        }
        .summary-table tr:last-child td {
            border-bottom: none;
        }
        .total-row {
            background-color: #d1ecf1;
            padding: 10px !important;
            font-size: 18px !important;
            font-weight: bold !important;
            color: #17a2b8 !important;
        }
        .instructions {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .instructions h3 {
            color: #856404;
            margin-top: 0;
            margin-bottom: 15px;
        }
        .instructions ol {
            margin: 0;
            padding-left: 20px;
        }
        .instructions li {
            margin-bottom: 12px;
            color: #856404;
        }
        .instructions strong {
            color: #664d03;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .items-table th {
            background-color: #17a2b8;
            color: white;
            padding: 12px;
            text-align: left;
            font-weight: bold;
        }
        .items-table td {
            padding: 12px;
            border-bottom: 1px solid #dee2e6;
        }
        .items-table tr:last-child td {
            border-bottom: none;
        }
        .items-table .text-right {
            text-align: right;
        }
        .items-table .text-center {
            text-align: center;
        }
        .address-box {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            border-left: 4px solid #17a2b8;
            margin-bottom: 20px;
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            color: #6c757d;
            font-size: 14px;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background-color: #17a2b8;
            color: white !important;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            margin-top: 15px;
        }
        .button:hover {
            background-color: #138496;
        }
        @media only screen and (max-width: 600px) {
            .container {
                padding: 15px;
            }
            .items-table {
                font-size: 12px;
            }
            .items-table th,
            .items-table td {
                padding: 8px 5px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="icon">🏦</div>
            <h1>Instrucciones de Pago</h1>
            <p class="order-number">Pedido #{{ $order->order_number }}</p>
            <p style="color: #6c757d; font-size: 14px;">{{ $order->created_at->format('d/m/Y H:i') }}</p>
        </div>

        <div class="alert">
            <strong>¡Tu pedido ha sido recibido!</strong><br>
            Para completar tu compra, realiza la transferencia bancaria siguiendo las instrucciones que encontrarás a continuación.
        </div>

        <!-- Resumen del Pedido -->
        <div class="section">
            <h2 class="section-title">📋 Resumen del Pedido</h2>
            <div class="summary-table">
                <table>
                    <tr>
                        <td>Subtotal:</td>
                        <td>${{ number_format($order->subtotal, 0, ',', '.') }} CLP</td>
                    </tr>
                    <tr>
                        <td>Envío:</td>
                        <td>${{ number_format($order->shipping_cost, 0, ',', '.') }} CLP</td>
                    </tr>
                    @if($order->discount_amount > 0)
                    <tr>
                        <td>Descuento:</td>
                        <td>-${{ number_format($order->discount_amount, 0, ',', '.') }} CLP</td>
                    </tr>
                    @endif
                    <tr>
                        <td class="total-row">Total a Transferir:</td>
                        <td class="total-row">${{ number_format($order->total_amount, 0, ',', '.') }} CLP</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Datos Bancarios -->
        <div class="section">
            <h2 class="section-title">🏦 Datos Bancarios</h2>
            <div class="bank-details">{{ $bankDetails }}</div>
        </div>

        <!-- Instrucciones -->
        <div class="instructions">
            <h3>📝 Instrucciones de Pago</h3>
            <ol>
                <li>
                    <strong>Realiza la transferencia</strong> por el monto exacto de <strong>${{ number_format($order->total_amount, 0, ',', '.') }} CLP</strong>
                    a la cuenta bancaria indicada arriba.
                </li>
                <li>
                    <strong>Incluye como referencia o glosa:</strong> <strong>#{{ $order->order_number }}</strong>
                </li>
                <li>
                    <strong>Envía el comprobante de transferencia</strong> respondiendo a este correo o enviándolo a:
                    <strong>{{ $emailConfirmationPayment }}</strong>
                </li>
                <li>
                    <strong>Verificación:</strong> Una vez que recibamos y verifiquemos tu transferencia,
                    te enviaremos un correo de confirmación y procederemos a preparar tu pedido para envío.
                </li>
                <li>
                    <strong>Tiempo de procesamiento:</strong> Tu pedido será procesado dentro de las 24-48 horas
                    hábiles después de verificar el pago.
                </li>
            </ol>
        </div>

        <!-- Productos -->
        <div class="section">
            <h2 class="section-title">🛍️ Productos Ordenados</h2>
            <table class="items-table">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th class="text-center">Cantidad</th>
                        <th class="text-right">Precio Unit.</th>
                        <th class="text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr>
                        <td>
                            <strong>{{ $item->product_name }}</strong>
                            @if($item->product_sku)
                                <br><span style="color: #6c757d; font-size: 12px;">SKU: {{ $item->product_sku }}</span>
                            @endif
                        </td>
                        <td class="text-center">{{ $item->quantity }}</td>
                        <td class="text-right">${{ number_format($item->unit_price, 0, ',', '.') }}</td>
                        <td class="text-right"><strong>${{ number_format($item->total_price, 0, ',', '.') }}</strong></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
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

        <!-- Botón -->
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ route('payment.transfer.instructions', ['order' => $order->id]) }}" class="button">
                Ver Instrucciones Completas
            </a>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>
                <strong>¿Necesitas ayuda?</strong><br>
                Si tienes alguna pregunta sobre tu pedido o el proceso de pago,
                no dudes en contactarnos.
            </p>
            <p style="margin-top: 15px;">
                Gracias por tu compra en <strong>CMYM.cl</strong>
            </p>
        </div>
    </div>
</body>
</html>
