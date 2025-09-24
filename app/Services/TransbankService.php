<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Str;
use Transbank\Webpay\Options;
use Transbank\Webpay\WebpayPlus\Exceptions\TransactionCommitException;
use Transbank\Webpay\WebpayPlus\Exceptions\TransactionCreateException;
use Transbank\Webpay\WebpayPlus\Exceptions\TransactionRefundException;
use Transbank\Webpay\WebpayPlus\Exceptions\TransactionStatusException;
use Transbank\Webpay\WebpayPlus\Transaction;

class TransbankService
{
    protected Options $options;

    protected Transaction $transaction;

    public function __construct()
    {
        $environment = config('services.transbank.environment');
        $commerceCode = config('services.transbank.commerce_code');
        $apiKey = config('services.transbank.api_key');

        // Configurar el ambiente
        $integrationType = $environment === 'production'
            ? Options::ENVIRONMENT_PRODUCTION
            : Options::ENVIRONMENT_INTEGRATION;

        // Crear opciones de configuración
        $this->options = new Options(
            $apiKey,
            $commerceCode,
            $integrationType
        );

        // Crear la instancia de transacción
        $this->transaction = new Transaction($this->options);
    }

    /**
     * Crear una transacción de pago con Transbank
     */
    public function createTransaction(Order $order, string $returnUrl): array
    {
        try {
            $buyOrder = 'ORDER_'.$order->id.'_'.time();
            $sessionId = Str::uuid()->toString();
            $amount = (int) $order->total_amount; // Pesos chilenos enteros

            $response = $this->transaction->create(
                $buyOrder,
                $sessionId,
                $amount,
                $returnUrl
            );

            // Guardar información del pago en la base de datos
            $payment = Payment::create([
                'order_id' => $order->id,
                'transaction_id' => $buyOrder,
                'session_id' => $sessionId,
                'amount' => $order->total_amount,
                'currency' => 'CLP',
                'status' => Payment::STATUS_PENDING,
                'method' => Payment::METHOD_WEBPAY,
                'token' => $response->getToken(),
                'response_data' => json_encode([
                    'url' => $response->getUrl(),
                    'token' => $response->getToken(),
                    'buy_order' => $buyOrder,
                    'session_id' => $sessionId,
                ]),
            ]);

            return [
                'success' => true,
                'payment' => $payment,
                'url' => $response->getUrl(),
                'token' => $response->getToken(),
            ];

        } catch (TransactionCreateException $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'transbank_error' => $e->getTransbankErrorMessage() ?? null,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Confirmar una transacción con el token recibido de Transbank
     */
    public function confirmTransaction(string $token): array
    {
        try {
            $response = $this->transaction->commit($token);

            // Buscar el pago por token
            $payment = Payment::where('token', $token)->first();

            if (! $payment) {
                return [
                    'success' => false,
                    'error' => 'Payment not found',
                ];
            }

            // Actualizar el estado del pago según la respuesta
            $status = $response->isApproved()
                ? Payment::STATUS_PAID
                : Payment::STATUS_FAILED;

            $payment->update([
                'status' => $status,
                'authorization_code' => $response->getAuthorizationCode(),
                'response_code' => $response->getResponseCode(),
                'response_data' => json_encode([
                    'vci' => $response->getVci(),
                    'amount' => $response->getAmount(),
                    'status' => $response->getStatus(),
                    'buy_order' => $response->getBuyOrder(),
                    'session_id' => $response->getSessionId(),
                    'card_detail' => $response->getCardDetail(),
                    'accounting_date' => $response->getAccountingDate(),
                    'transaction_date' => $response->getTransactionDate(),
                    'authorization_code' => $response->getAuthorizationCode(),
                    'payment_type_code' => $response->getPaymentTypeCode(),
                    'response_code' => $response->getResponseCode(),
                    'installments_amount' => $response->getInstallmentsAmount(),
                    'installments_number' => $response->getInstallmentsNumber(),
                ]),
            ]);

            // Actualizar estado de la orden si el pago fue exitoso
            if ($response->isApproved()) {
                $payment->order->update([
                    'status' => Order::STATUS_PROCESSING,
                    'payment_status' => Order::PAYMENT_STATUS_PAID,
                ]);
            }

            return [
                'success' => true,
                'payment' => $payment,
                'approved' => $response->isApproved(),
                'response' => $response,
            ];

        } catch (TransactionCommitException $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'transbank_error' => $e->getTransbankErrorMessage() ?? null,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Obtener el estado de una transacción
     */
    public function getTransactionStatus(string $token): array
    {
        try {
            $response = $this->transaction->status($token);

            return [
                'success' => true,
                'status' => $response->getStatus(),
                'response' => $response,
            ];

        } catch (TransactionStatusException $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'transbank_error' => $e->getTransbankErrorMessage() ?? null,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Procesar reembolso de una transacción
     */
    public function refundTransaction(Payment $payment, float $amount): array
    {
        try {
            $response = $this->transaction->refund(
                $payment->token,
                $amount
            );

            // Actualizar el pago con información del reembolso
            $payment->update([
                'status' => Payment::STATUS_REFUNDED,
                'response_data' => json_encode(array_merge(
                    json_decode($payment->response_data ?? '[]', true),
                    [
                        'refund' => [
                            'type' => $response->getType(),
                            'balance' => $response->getBalance(),
                            'authorization_code' => $response->getAuthorizationCode(),
                            'response_code' => $response->getResponseCode(),
                            'authorization_date' => $response->getAuthorizationDate(),
                            'nullified_amount' => $response->getNullifiedAmount(),
                        ],
                    ]
                )),
            ]);

            return [
                'success' => true,
                'payment' => $payment,
                'response' => $response,
            ];

        } catch (TransactionRefundException $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'transbank_error' => $e->getTransbankErrorMessage() ?? null,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }
}
