<?php

namespace App\Models;

use App\Models\Location\Commune;
use App\Models\Location\Region;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'type',
        'name',
        'phone',
        'region_id',
        'commune_id',
        'address_line_1',
        'address_line_2',
        'is_default',
    ];

    protected function casts(): array
    {
        return [
            'is_default' => 'boolean',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function commune(): BelongsTo
    {
        return $this->belongsTo(Commune::class);
    }

    protected function fullAddress(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->address_line_1.' '.$this->address_line_2.', '.$this->commune?->name.', '.$this->region?->name,
        );
    }
}
