<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Services\CheckoutService;
use App\Services\TransbankService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected TransbankService $transbankService;

    public function __construct(TransbankService $transbankService)
    {
        $this->transbankService = $transbankService;
    }

    /**
     * Iniciar pago con Webpay
     */
    public function initWebpayPayment(Request $request, Order $order)
    {
        try {
            // Validar que la orden pueda ser pagada
            if ($order->payment_status !== Order::PAYMENT_STATUS_PENDING) {
                return redirect()->back()->withErrors([
                    'payment' => 'Esta orden ya ha sido procesada o no puede ser pagada.',
                ]);
            }

            // Generar URL de retorno
            $returnUrl = route('payment.webpay.return');

            // Crear transacción en Webpay
            $result = $this->transbankService->createTransaction($order, $returnUrl);

            if (! $result['success']) {
                Log::error('Error creating Webpay transaction', [
                    'order_id' => $order->id,
                    'error' => $result['error'],
                ]);

                return redirect()->back()->withErrors([
                    'payment' => 'Error al iniciar el pago: '.$result['error'],
                ]);
            }

            // Redirigir al formulario de pago de Webpay
            return view('payment.webpay-redirect', [
                'token' => $result['token'],
                'url' => $result['url'],
                'order' => $order,
            ]);

        } catch (\Exception $e) {
            Log::error('Exception in initWebpayPayment', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()->withErrors([
                'payment' => 'Error interno del sistema. Intenta nuevamente.',
            ]);
        }
    }

    /**
     * Procesar retorno desde Webpay
     */
    public function handleWebpayReturn(Request $request)
    {
        try {
            // Verificar si hay token de transacción exitosa
            if ($request->has('token_ws')) {
                return $this->processSuccessfulPayment($request->input('token_ws'));
            }

            // Verificar si hay datos de transacción abortada/fallida
            if ($request->has('TBK_TOKEN') || $request->has('TBK_ORDEN_COMPRA') || $request->has('TBK_ID_SESION')) {
                return $this->processFailedPayment($request);
            }

            // Si no hay parámetros reconocidos
            Log::warning('Webpay return without recognized parameters', [
                'request_data' => $request->all(),
            ]);

            return redirect()->route('home')->withErrors([
                'payment' => 'Error en el procesamiento del pago.',
            ]);

        } catch (\Exception $e) {
            Log::error('Exception in handleWebpayReturn', [
                'error' => $e->getMessage(),
                'request_data' => $request->all(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('home')->withErrors([
                'payment' => 'Error interno del sistema.',
            ]);
        }
    }

    /**
     * Procesar pago exitoso
     */
    private function processSuccessfulPayment(string $token): \Illuminate\Http\RedirectResponse
    {
        DB::beginTransaction();
        try {
            // Confirmar transacción con Webpay
            $result = $this->transbankService->confirmTransaction($token);

            if (! $result['success']) {
                DB::rollBack();
                Log::error('Error confirming Webpay transaction', [
                    'token' => $token,
                    'error' => $result['error'],
                ]);

                return redirect()->route('payment.failed')->withErrors([
                    'payment' => 'Error al confirmar el pago: '.$result['error'],
                ]);
            }

            $payment = $result['payment'];

            if ($result['approved']) {
                DB::commit();

                // Limpiar carrito y datos del checkout al completar el pago exitosamente
                CheckoutService::markCheckoutComplete();

                Log::info('Payment approved successfully', [
                    'payment_id' => $payment->id,
                    'order_id' => $payment->order_id,
                    'token' => $token,
                ]);

                return redirect()->route('payment.success', ['payment' => $payment->id])
                    ->with('success', 'Pago procesado exitosamente.');
            } else {
                DB::rollBack();

                // Marcar checkout como fallido para mantener datos en sesión
                CheckoutService::markCheckoutFailed();

                Log::warning('Payment rejected by bank', [
                    'payment_id' => $payment->id,
                    'order_id' => $payment->order_id,
                    'token' => $token,
                ]);

                return redirect()->route('payment.failed', ['payment' => $payment->id])
                    ->with('error', 'El pago fue rechazado por el banco.');
            }

        } catch (\Exception $e) {
            DB::rollBack();

            // Marcar checkout como fallido para mantener datos en sesión
            CheckoutService::markCheckoutFailed();

            Log::error('Exception processing successful payment', [
                'token' => $token,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('payment.failed')->withErrors([
                'payment' => 'Error procesando el pago.',
            ]);
        }
    }

    /**
     * Procesar pago fallido/abortado
     */
    private function processFailedPayment(Request $request): \Illuminate\Http\RedirectResponse
    {
        $token = $request->input('TBK_TOKEN');
        $buyOrder = $request->input('TBK_ORDEN_COMPRA');
        $sessionId = $request->input('TBK_ID_SESION');

        Log::info('Processing failed/aborted payment', [
            'token' => $token,
            'buy_order' => $buyOrder,
            'session_id' => $sessionId,
        ]);

        // Si tenemos un token, podemos consultar el estado
        if ($token) {
            try {
                $statusResult = $this->transbankService->getTransactionStatus($token);
                Log::info('Transaction status for failed payment', [
                    'token' => $token,
                    'status_result' => $statusResult,
                ]);
            } catch (\Exception $e) {
                Log::error('Error getting transaction status for failed payment', [
                    'token' => $token,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        // Buscar el pago por orden de compra si está disponible
        $payment = null;
        if ($buyOrder) {
            $payment = Payment::whereHas('order', function ($query) use ($buyOrder) {
                $query->where('id', $buyOrder);
            })->first();

            if ($payment) {
                $payment->update([
                    'status' => Payment::STATUS_CANCELLED,
                ]);
            }
        }

        // Marcar checkout como fallido para mantener datos en sesión
        CheckoutService::markCheckoutFailed();

        return redirect()->route('payment.cancelled', $payment ? ['payment' => $payment->id] : [])
            ->with('warning', 'El pago fue cancelado o no se pudo procesar.');
    }

    /**
     * Mostrar página de pago exitoso
     */
    public function paymentSuccess(Payment $payment)
    {
        if (! $payment->isSuccessful()) {
            return redirect()->route('payment.failed', ['payment' => $payment->id]);
        }

        return view('payment.success', compact('payment'));
    }

    /**
     * Mostrar página de pago fallido
     */
    public function paymentFailed(?Payment $payment = null)
    {
        return view('payment.failed', compact('payment'));
    }

    /**
     * Mostrar página de pago cancelado
     */
    public function paymentCancelled(?Payment $payment = null)
    {
        return view('payment.cancelled', compact('payment'));
    }
}
