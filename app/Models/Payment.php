<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'order_id',
        'method',
        'transaction_id',
        'session_id',
        'amount',
        'currency',
        'status',
        'token',
        'authorization_code',
        'response_code',
        'response_data',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'integer', // Pesos chilenos enteros
            'response_data' => 'array',
        ];
    }

    // Relación con Order
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    // Estados de pago
    public const STATUS_PENDING = 'pending';

    public const STATUS_AUTHORIZED = 'authorized';

    public const STATUS_PAID = 'paid';

    public const STATUS_FAILED = 'failed';

    public const STATUS_CANCELLED = 'cancelled';

    public const STATUS_REFUNDED = 'refunded';

    // Métodos de pago
    public const METHOD_WEBPAY = 'webpay';

    public const METHOD_TRANSFER = 'transfer';

    public const METHOD_CASH = 'cash';

    // Scopes
    public function scopeSuccessful($query)
    {
        return $query->whereIn('status', [self::STATUS_AUTHORIZED, self::STATUS_PAID]);
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeFailed($query)
    {
        return $query->where('status', self::STATUS_FAILED);
    }

    // Métodos helper
    public function isSuccessful(): bool
    {
        return in_array($this->status, [self::STATUS_AUTHORIZED, self::STATUS_PAID]);
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isFailed(): bool
    {
        return $this->status === self::STATUS_FAILED;
    }

    public function isWebpay(): bool
    {
        return $this->method === self::METHOD_WEBPAY;
    }

    public function isTransfer(): bool
    {
        return $this->method === self::METHOD_TRANSFER;
    }

    /**
     * Obtiene los detalles bancarios desde la configuración
     */
    public static function getBankDetails(): string
    {
        return app(\App\Settings\EcommerceSettings::class)->bank_details;
    }
}
