@extends('layout.master')

@section('title', 'Pago Fallido')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-danger">
                <div class="card-body text-center">
                    <div class="text-danger mb-4">
                        <i class="fas fa-times-circle" style="font-size: 4rem;"></i>
                    </div>

                    <h2 class="text-danger mb-4">Pago No Procesado</h2>

                    <p class="lead mb-4">Hubo un problema al procesar tu pago.</p>

                    @if($payment)
                        <div class="alert alert-info mb-4">
                            <strong>Orden:</strong> #{{ $payment->order->order_number }}<br>
                            <strong>Monto:</strong> ${{ number_format($payment->amount, 0, ',', '.') }} CLP<br>
                            <strong>Fecha del Intento:</strong> {{ $payment->updated_at->format('d/m/Y H:i:s') }}
                        </div>
                    @endif

                    <div class="alert alert-warning">
                        <h6><i class="fas fa-exclamation-triangle me-2"></i>Posibles causas del rechazo:</h6>
                        <ul class="list-unstyled mb-0 mt-3">
                            <li>• Error en el ingreso de los datos de tu tarjeta de crédito o débito</li>
                            <li>• Tu tarjeta no cuenta con saldo o cupo suficiente</li>
                            <li>• Tarjeta vencida o no habilitada en el sistema financiero</li>
                            <li>• Límites de transacción excedidos</li>
                            <li>• Problemas temporales con el banco emisor</li>
                        </ul>
                    </div>

                    <div class="alert alert-info">
                        <h6><i class="fas fa-lightbulb me-2"></i>¿Qué puedes hacer?</h6>
                        <ul class="list-unstyled mb-0 mt-3">
                            <li>• Verificar los datos de tu tarjeta</li>
                            <li>• Contactar a tu banco para verificar el estado de la tarjeta</li>
                            <li>• Intentar con otra tarjeta</li>
                            <li>• Intentar nuevamente en unos minutos</li>
                        </ul>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                        @if($payment)
                            <a href="{{ route('checkout') }}" class="btn btn-primary me-md-2">
                                <i class="fas fa-redo me-2"></i>
                                Intentar Nuevamente
                            </a>
                        @endif
                        <a href="{{ route('home') }}" class="btn btn-outline-primary">
                            <i class="fas fa-home me-2"></i>
                            Volver al Inicio
                        </a>
                    </div>

                    <div class="mt-4">
                        <small class="text-muted">
                            Si continúas teniendo problemas, puedes contactarnos a través de nuestro
                            <a href="{{ route('contact') }}">formulario de contacto</a>.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
