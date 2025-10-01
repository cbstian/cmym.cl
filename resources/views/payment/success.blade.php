@extends('layout.master')

@section('title', 'Pago Exitoso')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-success">
                <div class="card-body text-center">
                    <div class="text-success mb-4">
                        <i class="fas fa-check-circle" style="font-size: 4rem;"></i>
                    </div>

                    <h2 class="text-success mb-4">¡Pago Exitoso!</h2>

                    <p class="lead mb-4">Tu pago ha sido procesado correctamente.</p>

                    @if($payment && $payment->response_data && !empty($payment->response_data))
                        @php
                            $response = $payment->response_data;
                        @endphp

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title">Detalles de la Transacción</h6>
                                        <p class="mb-1"><strong>Orden:</strong> #{{ $payment->order->order_number }}</p>
                                        <p class="mb-1"><strong>Monto:</strong> ${{ number_format($response['amount'] ?? $payment->amount, 0, ',', '.') }} CLP</p>
                                        <p class="mb-1"><strong>Fecha:</strong> {{ now()->format('d/m/Y H:i:s') }}</p>
                                        @if(isset($response['authorization_code']))
                                            <p class="mb-1"><strong>Código Autorización:</strong> {{ $response['authorization_code'] }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title">Método de Pago</h6>
                                        @if(isset($response['card_detail']) && isset($response['card_detail']['card_number']))
                                            <p class="mb-1"><strong>Tarjeta:</strong> **** **** **** {{ $response['card_detail']['card_number'] }}</p>
                                        @endif
                                        @if(isset($response['payment_type_code']))
                                            <p class="mb-1"><strong>Tipo:</strong>
                                                @switch($response['payment_type_code'])
                                                    @case('VD') Venta Débito @break
                                                    @case('VN') Venta Normal @break
                                                    @case('VC') Venta en cuotas @break
                                                    @case('SI') Cuotas sin interés @break
                                                    @case('S2') Cuotas sin interés 2 @break
                                                    @case('NC') Cuotas normales @break
                                                    @default {{ $response['payment_type_code'] }}
                                                @endswitch
                                            </p>
                                        @endif
                                        @if(isset($response['installments_number']) && $response['installments_number'] > 1)
                                            <p class="mb-1"><strong>Cuotas:</strong> {{ $response['installments_number'] }}</p>
                                            @if(isset($response['installments_amount']))
                                                <p class="mb-1"><strong>Valor Cuota:</strong> ${{ number_format($response['installments_amount'], 0, ',', '.') }} CLP</p>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="alert alert-success">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>¿Qué sigue?</strong> Recibirás un email de confirmación con los detalles de tu pedido.
                        Tu orden será procesada y preparada para envío.
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                        <a href="{{ route('home') }}" class="btn btn-primary me-md-2">
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
}
</style>
@endsection
