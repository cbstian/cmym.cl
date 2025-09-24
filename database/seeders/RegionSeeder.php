<?php

namespace Database\Seeders;

use App\Models\Commune;
use App\Models\Region;
use Illuminate\Database\Seeder;

class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $regionsData = [
            [
                'name' => 'Arica y Parinacota',
                'code' => 'AP',
                'communes' => [
                    ['name' => 'Arica', 'code' => '15101'],
                    ['name' => 'Camarones', 'code' => '15102'],
                    ['name' => 'Putre', 'code' => '15201'],
                    ['name' => 'General Lagos', 'code' => '15202'],
                ],
            ],
            [
                'name' => 'Tarapacá',
                'code' => 'TA',
                'communes' => [
                    ['name' => 'Iquique', 'code' => '01101'],
                    ['name' => 'Alto Hospicio', 'code' => '01107'],
                    ['name' => 'Pozo Almonte', 'code' => '01401'],
                    ['name' => 'Camiña', 'code' => '01402'],
                    ['name' => 'Colchane', 'code' => '01403'],
                    ['name' => 'Huara', 'code' => '01404'],
                    ['name' => 'Pica', 'code' => '01405'],
                ],
            ],
            [
                'name' => 'Antofagasta',
                'code' => 'AN',
                'communes' => [
                    ['name' => 'Antofagasta', 'code' => '02101'],
                    ['name' => 'Mejillones', 'code' => '02102'],
                    ['name' => 'Sierra Gorda', 'code' => '02103'],
                    ['name' => 'Taltal', 'code' => '02104'],
                    ['name' => 'Calama', 'code' => '02201'],
                    ['name' => 'Ollagüe', 'code' => '02202'],
                    ['name' => 'San Pedro de Atacama', 'code' => '02203'],
                    ['name' => 'Tocopilla', 'code' => '02301'],
                    ['name' => 'María Elena', 'code' => '02302'],
                ],
            ],
            [
                'name' => 'Atacama',
                'code' => 'AT',
                'communes' => [
                    ['name' => 'Copiapó', 'code' => '03101'],
                    ['name' => 'Caldera', 'code' => '03102'],
                    ['name' => 'Tierra Amarilla', 'code' => '03103'],
                    ['name' => 'Chañaral', 'code' => '03201'],
                    ['name' => 'Diego de Almagro', 'code' => '03202'],
                    ['name' => 'Vallenar', 'code' => '03301'],
                    ['name' => 'Alto del Carmen', 'code' => '03302'],
                    ['name' => 'Freirina', 'code' => '03303'],
                    ['name' => 'Huasco', 'code' => '03304'],
                ],
            ],
            [
                'name' => 'Coquimbo',
                'code' => 'CO',
                'communes' => [
                    ['name' => 'La Serena', 'code' => '04101'],
                    ['name' => 'Coquimbo', 'code' => '04102'],
                    ['name' => 'Andacollo', 'code' => '04103'],
                    ['name' => 'La Higuera', 'code' => '04104'],
                    ['name' => 'Paiguano', 'code' => '04105'],
                    ['name' => 'Vicuña', 'code' => '04106'],
                    ['name' => 'Illapel', 'code' => '04201'],
                    ['name' => 'Canela', 'code' => '04202'],
                    ['name' => 'Los Vilos', 'code' => '04203'],
                    ['name' => 'Salamanca', 'code' => '04204'],
                    ['name' => 'Ovalle', 'code' => '04301'],
                    ['name' => 'Combarbalá', 'code' => '04302'],
                    ['name' => 'Monte Patria', 'code' => '04303'],
                    ['name' => 'Punitaqui', 'code' => '04304'],
                    ['name' => 'Río Hurtado', 'code' => '04305'],
                ],
            ],
            [
                'name' => 'Valparaíso',
                'code' => 'VA',
                'communes' => [
                    ['name' => 'Valparaíso', 'code' => '05101'],
                    ['name' => 'Casablanca', 'code' => '05102'],
                    ['name' => 'Concón', 'code' => '05103'],
                    ['name' => 'Juan Fernández', 'code' => '05104'],
                    ['name' => 'Puchuncaví', 'code' => '05105'],
                    ['name' => 'Quintero', 'code' => '05107'],
                    ['name' => 'Viña del Mar', 'code' => '05109'],
                    ['name' => 'Isla de Pascua', 'code' => '05201'],
                    ['name' => 'Los Andes', 'code' => '05301'],
                    ['name' => 'Calle Larga', 'code' => '05302'],
                    ['name' => 'Rinconada', 'code' => '05303'],
                    ['name' => 'San Esteban', 'code' => '05304'],
                    ['name' => 'La Ligua', 'code' => '05401'],
                    ['name' => 'Cabildo', 'code' => '05402'],
                    ['name' => 'Papudo', 'code' => '05403'],
                    ['name' => 'Petorca', 'code' => '05404'],
                    ['name' => 'Zapallar', 'code' => '05405'],
                    ['name' => 'Quillota', 'code' => '05501'],
                    ['name' => 'Calera', 'code' => '05502'],
                    ['name' => 'Hijuelas', 'code' => '05503'],
                    ['name' => 'La Cruz', 'code' => '05504'],
                    ['name' => 'Nogales', 'code' => '05506'],
                    ['name' => 'San Antonio', 'code' => '05601'],
                    ['name' => 'Algarrobo', 'code' => '05602'],
                    ['name' => 'Cartagena', 'code' => '05603'],
                    ['name' => 'El Quisco', 'code' => '05604'],
                    ['name' => 'El Tabo', 'code' => '05605'],
                    ['name' => 'Santo Domingo', 'code' => '05606'],
                    ['name' => 'San Felipe', 'code' => '05701'],
                    ['name' => 'Catemu', 'code' => '05702'],
                    ['name' => 'Llaillay', 'code' => '05703'],
                    ['name' => 'Panquehue', 'code' => '05704'],
                    ['name' => 'Putaendo', 'code' => '05705'],
                    ['name' => 'Santa María', 'code' => '05706'],
                ],
            ],
            [
                'name' => 'Región Metropolitana de Santiago',
                'code' => 'RM',
                'communes' => [
                    ['name' => 'Santiago', 'code' => '13101'],
                    ['name' => 'Cerrillos', 'code' => '13102'],
                    ['name' => 'Cerro Navia', 'code' => '13103'],
                    ['name' => 'Conchalí', 'code' => '13104'],
                    ['name' => 'El Bosque', 'code' => '13105'],
                    ['name' => 'Estación Central', 'code' => '13106'],
                    ['name' => 'Huechuraba', 'code' => '13107'],
                    ['name' => 'Independencia', 'code' => '13108'],
                    ['name' => 'La Cisterna', 'code' => '13109'],
                    ['name' => 'La Florida', 'code' => '13110'],
                    ['name' => 'La Granja', 'code' => '13111'],
                    ['name' => 'La Pintana', 'code' => '13112'],
                    ['name' => 'La Reina', 'code' => '13113'],
                    ['name' => 'Las Condes', 'code' => '13114'],
                    ['name' => 'Lo Barnechea', 'code' => '13115'],
                    ['name' => 'Lo Espejo', 'code' => '13116'],
                    ['name' => 'Lo Prado', 'code' => '13117'],
                    ['name' => 'Macul', 'code' => '13118'],
                    ['name' => 'Maipú', 'code' => '13119'],
                    ['name' => 'Ñuñoa', 'code' => '13120'],
                    ['name' => 'Pedro Aguirre Cerda', 'code' => '13121'],
                    ['name' => 'Peñalolén', 'code' => '13122'],
                    ['name' => 'Providencia', 'code' => '13123'],
                    ['name' => 'Pudahuel', 'code' => '13124'],
                    ['name' => 'Quilicura', 'code' => '13125'],
                    ['name' => 'Quinta Normal', 'code' => '13126'],
                    ['name' => 'Recoleta', 'code' => '13127'],
                    ['name' => 'Renca', 'code' => '13128'],
                    ['name' => 'San Joaquín', 'code' => '13129'],
                    ['name' => 'San Miguel', 'code' => '13130'],
                    ['name' => 'San Ramón', 'code' => '13131'],
                    ['name' => 'Vitacura', 'code' => '13132'],
                    ['name' => 'Puente Alto', 'code' => '13201'],
                    ['name' => 'Pirque', 'code' => '13202'],
                    ['name' => 'San José de Maipo', 'code' => '13203'],
                    ['name' => 'Colina', 'code' => '13301'],
                    ['name' => 'Lampa', 'code' => '13302'],
                    ['name' => 'Tiltil', 'code' => '13303'],
                    ['name' => 'San Bernardo', 'code' => '13401'],
                    ['name' => 'Buin', 'code' => '13402'],
                    ['name' => 'Calera de Tango', 'code' => '13403'],
                    ['name' => 'Paine', 'code' => '13404'],
                    ['name' => 'Melipilla', 'code' => '13501'],
                    ['name' => 'Alhué', 'code' => '13502'],
                    ['name' => 'Curacaví', 'code' => '13503'],
                    ['name' => 'María Pinto', 'code' => '13504'],
                    ['name' => 'San Pedro', 'code' => '13505'],
                    ['name' => 'Talagante', 'code' => '13601'],
                    ['name' => 'El Monte', 'code' => '13602'],
                    ['name' => 'Isla de Maipo', 'code' => '13603'],
                    ['name' => 'Padre Hurtado', 'code' => '13604'],
                    ['name' => 'Peñaflor', 'code' => '13605'],
                ],
            ],
        ];

        foreach ($regionsData as $regionData) {
            $region = Region::create([
                'name' => $regionData['name'],
                'code' => $regionData['code'],
            ]);

            foreach ($regionData['communes'] as $communeData) {
                Commune::create([
                    'region_id' => $region->id,
                    'name' => $communeData['name'],
                    'code' => $communeData['code'],
                ]);
            }
        }
    }
}
