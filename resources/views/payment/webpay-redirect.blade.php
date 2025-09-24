@extends('layout.master')

@section('title', 'Procesando Pago')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body text-center">
                    <h4 class="mb-4">Procesando tu pago</h4>

                    <div class="mb-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                    </div>

                    <p class="mb-3">Estás siendo redirigido a Webpay Plus para completar tu pago de forma segura.</p>

                    <div class="alert alert-info">
                        <strong>Orden #{{ $order->order_number }}</strong><br>
                        Total a pagar: <strong>${{ number_format($order->total_amount, 0, ',', '.') }} CLP</strong>
                    </div>

                    <p class="text-muted small mb-4">
                        Si la redirección no ocurre automáticamente en 5 segundos, haz clic en el botón de abajo.
                    </p>

                    <form id="webpayForm" method="POST" action="{{ $url }}">
                        <input type="hidden" name="token_ws" value="{{ $token }}">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-credit-card me-2"></i>
                            Ir a Webpay Plus
                        </button>
                    </form>
                </div>
            </div>

            <div class="mt-4">
                <div class="row">
                    <div class="col-md-4 text-center">
                        <i class="fas fa-shield-alt text-success fs-2 mb-2"></i>
                        <h6>Pago Seguro</h6>
                        <small class="text-muted">Transbank protege tus datos</small>
                    </div>
                    <div class="col-md-4 text-center">
                        <i class="fas fa-lock text-success fs-2 mb-2"></i>
                        <h6>Encriptación SSL</h6>
                        <small class="text-muted">Conexión cifrada</small>
                    </div>
                    <div class="col-md-4 text-center">
                        <i class="fas fa-university text-success fs-2 mb-2"></i>
                        <h6>Respaldado por Bancos</h6>
                        <small class="text-muted">Tecnología confiable</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Auto-submit el formulario después de 5 segundos
setTimeout(function() {
    document.getElementById('webpayForm').submit();
}, 5000);
</script>
@endsection
