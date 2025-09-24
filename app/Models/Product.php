<?php

namespace App\Models;

use Binafy\LaravelCart\Cartable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Product extends Model implements Cartable
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'short_description',
        'sku',
        'price',
        'sale_price',
        'weight',
        'dimensions',
        'is_active',
        'is_featured',
        'image_primary_path',
        'image_paths',
        'stock_quantity',
    ];

    protected function casts(): array
    {
        return [
            'image_paths' => 'array',
            'price' => 'decimal:2',
            'sale_price' => 'decimal:2',
            'weight' => 'decimal:2',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Product $product) {
            if (empty($product->slug) && ! empty($product->name)) {
                $product->slug = static::generateUniqueSlug($product->name);
            }
        });

        static::updating(function (Product $product) {
            // Solo regenerar el slug si el nombre cambió y el slug no fue modificado manualmente
            if ($product->isDirty('name') && ! $product->isDirty('slug')) {
                $product->slug = static::generateUniqueSlug($product->name, $product->id);
            }
        });
    }

    /**
     * Genera un slug único para el producto
     */
    protected static function generateUniqueSlug(string $name, ?int $excludeId = null): string
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;

        // Verificar si el slug ya existe
        while (static::slugExists($slug, $excludeId)) {
            $slug = $originalSlug.'-'.$counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Verifica si un slug ya existe en la base de datos
     */
    protected static function slugExists(string $slug, ?int $excludeId = null): bool
    {
        $query = static::where('slug', $slug);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function attributes(): HasMany
    {
        return $this->hasMany(Attribute::class);
    }

    /**
     * Genera el mensaje de WhatsApp personalizado para este producto
     */
    public function getWhatsappMessage(): string
    {
        $productName = $this->name;
        $productUrl = route('product.show', $this->slug);
        $price = $this->sale_price && $this->sale_price < $this->price
            ? number_format($this->sale_price, 0, ',', '.')
            : number_format($this->price, 0, ',', '.');

        $message = "🛍️ *Hola! Me interesa este producto:*\n\n";
        $message .= "📦 *Producto:* {$productName}\n";
        $message .= "💰 *Precio:* \${$price}\n";

        if ($this->sku) {
            $message .= "🔢 *SKU:* {$this->sku}\n";
        }

        if ($this->category) {
            $message .= "🏷️ *Categoría:* {$this->category->name}\n";
        }

        $message .= "\n🌐 *Ver producto:* {$productUrl}\n\n";
        $message .= '¿Podrías darme más información sobre disponibilidad y formas de pago? 😊';

        return $message;
    }

    /**
     * Genera la URL de WhatsApp con el mensaje personalizado
     */
    public function getWhatsappUrl(string $phoneNumber = '56951589643'): string
    {
        $message = $this->getWhatsappMessage();
        $encodedMessage = urlencode($message);

        return "https://wa.me/{$phoneNumber}?text={$encodedMessage}";
    }

    /**
     * Obtiene el número de WhatsApp desde configuración o usa el por defecto
     */
    public static function getWhatsappPhoneNumber(): string
    {
        return config('app.whatsapp_phone', '56951589643');
    }

    /**
     * Obtiene el precio del producto para el carrito
     * Implementa la interfaz Cartable
     */
    public function getPrice(): float
    {
        // Devuelve el precio de oferta si está disponible, sino el precio regular
        return $this->sale_price ?? $this->price;
    }
}
