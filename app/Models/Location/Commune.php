<?php

namespace App\Models\Location;

use App\Models\Address;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Commune extends Model
{
    use HasFactory;

    protected $fillable = [
        'province_id',
        'name',
        'active',
    ];

    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }

    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class, 'province_id');
    }
}
