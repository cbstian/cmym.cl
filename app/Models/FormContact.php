<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormContact extends Model
{
    protected $fillable = [
        'nombre',
        'correo',
        'telefono',
        'direccion',
        'mensaje',
    ];
}
