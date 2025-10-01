<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'customer_id',
        'status',
        'subtotal',
        'shipping_cost',
        'discount_amount',
        'total_amount',
        'currency',
        'payment_status',
        'payment_method',
        'billing_address_id',
        'shipping_address_id',
        'notes',
        'shipped_at',
        'delivered_at',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'integer',
            'shipping_cost' => 'integer',
            'discount_amount' => 'integer',
            'total_amount' => 'integer',
            'shipped_at' => 'datetime',
            'delivered_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Order $order) {
            if (empty($order->order_number)) {
                $order->order_number = 'ORD-'.date('Ymd').'-'.str_pad(
                    (static::whereDate('created_at', today())->count() + 1),
                    4, '0', STR_PAD_LEFT
                );
            }
        });
    }

    // Estados de la orden
    public const STATUS_PENDING = 'pending';

    public const STATUS_PROCESSING = 'processing';

    public const STATUS_SHIPPED = 'shipped';

    public const STATUS_DELIVERED = 'delivered';

    public const STATUS_CANCELLED = 'cancelled';

    // Estados de pago
    public const PAYMENT_STATUS_PENDING = 'pending';

    public const PAYMENT_STATUS_PAID = 'paid';

    public const PAYMENT_STATUS_FAILED = 'failed';

    public const PAYMENT_STATUS_REFUNDED = 'refunded';

    // Método para obtener el total en pesos chilenos para Transbank
    public function getTotalForTransbank(): int
    {
        return (int) $this->total_amount;
    }

    // Relaciones
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function billingAddress(): BelongsTo
    {
        return $this->belongsTo(Address::class, 'billing_address_id');
    }

    public function shippingAddress(): BelongsTo
    {
        return $this->belongsTo(Address::class, 'shipping_address_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Obtiene los emails de notificación desde la configuración
     */
    public static function getNotificationEmails(): array
    {
        return app(\App\Settings\EcommerceSettings::class)->emails_notifications_orders;
    }
}
