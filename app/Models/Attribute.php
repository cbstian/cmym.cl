<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attribute extends Model
{
    protected $fillable = [
        'name',
        'is_required',
        'sort',
        'values',
        'product_id',
    ];

    protected function casts(): array
    {
        return [
            'is_required' => 'boolean',
            'values' => 'array',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
