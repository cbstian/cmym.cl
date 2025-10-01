<?php

namespace App\Models\Location;

use App\Models\Address;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Region extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'abbreviation',
        'capital',
        'active',
    ];

    public function provinces(): HasMany
    {
        return $this->hasMany(Province::class, 'region_id');
    }

    public function communes()
    {
        return $this::join('provinces', 'regions.id', '=', 'provinces.region_id')
            ->join('communes', 'provinces.id', '=', 'communes.province_id')
            ->where('regions.id', $this->id)
            ->select('communes.*')
            ->get();
    }

    public function communesActive()
    {
        return $this::join('provinces', 'regions.id', '=', 'provinces.region_id')
            ->join('communes', 'provinces.id', '=', 'communes.province_id')
            ->where('regions.id', $this->id)
            ->where('communes.active', 1)
            ->select('communes.*')
            ->get();
    }

    public function searchCommunesActive($string)
    {
        $communes = $this::join('provinces', 'regions.id', '=', 'provinces.region_id')
            ->join('communes', 'provinces.id', '=', 'communes.province_id')
            ->where('regions.id', $this->id)
            ->where('communes.active', 1)
            ->where('communes.commune', 'like', '%'.$string.'%')
            ->select('communes.*')
            ->take(10)
            ->get();

        if (count($communes) == 0) {
            return $this->searchCommunesActive('');
        }

        return $communes;
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }
}
