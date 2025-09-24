@extends('layout.master')

@section('title', 'Pago Cancelado')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-warning">
                <div class="card-body text-center">
                    <div class="text-warning mb-4">
                        <i class="fas fa-exclamation-triangle" style="font-size: 4rem;"></i>
                    </div>

                    <h2 class="text-warning mb-4">Pago Cancelado</h2>

                    <p class="lead mb-4">Has cancelado el proceso de pago o la sesión ha expirado.</p>

                    @if($payment)
                        <div class="alert alert-info mb-4">
                            <strong>Orden:</strong> #{{ $payment->order->order_number }}<br>
                            <strong>Monto:</strong> ${{ number_format($payment->amount, 0, ',', '.') }} CLP<br>
                            <strong>Estado:</strong> Cancelado
                        </div>
                    @endif

                    <div class="alert alert-light">
                        <h6><i class="fas fa-info-circle me-2"></i>¿Qué pasó?</h6>
                        <p class="mb-0">
                            El pago fue cancelado antes de completarse. Esto puede haber ocurrido porque:
                        </p>
                        <ul class="list-unstyled mt-3 mb-0">
                            <li>• Cancelaste voluntariamente la transacción</li>
                            <li>• La sesión de pago expiró (tiempo límite excedido)</li>
                            <li>• Cerraste la ventana del navegador durante el proceso</li>
                        </ul>
                    </div>

                    <div class="alert alert-success">
                        <h6><i class="fas fa-shield-alt me-2"></i>Tranquilo</h6>
                        <p class="mb-0">
                            No se realizó ningún cargo a tu tarjeta. Puedes intentar realizar el pago nuevamente
                            cuando estés listo.
                        </p>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                        <a href="{{ route('checkout') }}" class="btn btn-primary me-md-2">
                            <i class="fas fa-shopping-cart me-2"></i>
                            Volver a Checkout
                        </a>
                        <a href="{{ route('cart') }}" class="btn btn-outline-primary me-md-2">
                            <i class="fas fa-edit me-2"></i>
                            Modificar Carrito
                        </a>
                        <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-home me-2"></i>
                            Volver al Inicio
                        </a>
                    </div>

                    <div class="mt-4">
                        <small class="text-muted">
                            ¿Necesitas ayuda? <a href="{{ route('contact') }}">Contáctanos</a> y te ayudaremos
                            con tu compra.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
