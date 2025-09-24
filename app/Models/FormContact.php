<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormContact extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'correo',
        'telefono',
        'direccion',
        'mensaje',
        'reviewed',
    ];

    protected function casts(): array
    {
        return [
            'reviewed' => 'boolean',
        ];
    }
}
