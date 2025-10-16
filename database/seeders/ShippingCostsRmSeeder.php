<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Settings\EcommerceSettings;
use App\Models\Location\Region;

class ShippingCostsRmSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ecommerceSettings = app(EcommerceSettings::class);
        $rm = Region::where('abbreviation', 'RM')->first();

        $shippingCostsRm = [];
        foreach ($rm->communes() as $commune) {
            $shippingCostsRm[] = [
                'commune_id' => $commune->id,
                'cost' => 10000, // Costo por defecto $10.000
            ];
        }

        // Inicializar costos de envío para RM con $10.000 por defecto si está vacío
        $ecommerceSettings->shipping_costs_rm = $shippingCostsRm;
        $ecommerceSettings->save();
    }
}
