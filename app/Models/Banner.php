<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = [
        'name',
        'desktop_image',
        'mobile_image',
        'link',
        'open_new_tab',
        'sort',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'open_new_tab' => 'boolean',
            'is_active' => 'boolean',
            'sort' => 'integer',
        ];
    }
}
